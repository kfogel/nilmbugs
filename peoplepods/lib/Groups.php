<? 
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* lib/Groups.php
* This file defines the Group object
*
* Documentation for this object can be found here:
* http://peoplepods.net/readme/group-object
/**********************************************/


class Group extends Obj {
	
	protected $MEMBERS;
	protected $DOCUMENTS;	
	
	function Group($POD,$PARAMETERS = null) {
		
		$this->success = null;
		parent::Obj($POD,'group');	
	
		$groupPath =  $this->POD->siteRoot(false) . "/" . $this->POD->libOptions('groupPath');

		if (isset($PARAMETERS['id']) && (sizeof($PARAMETERS) == 1)) {
			$this->loadById($PARAMETERS['id']);
			if (!$this->success()) {
				return;
			}	
		} else if (isset($PARAMETERS['stub']) && (sizeof($PARAMETERS)==1)) {
			$this->loadByStub($PARAMETERS['stub']);
			if (!$this->success()) {
				return;
			}	
		} else {
			$fill = true;
			if (isset($PARAMETERS['id'])) {
				$d = $this->POD->checkcache('Group','id',$PARAMETERS['id']);
				if ($d) {
					$fill = false;
					$this->DATA = $d;
				} 
			}
			
			if ($PARAMETERS) { 
				// fill in the deets with the parameters passed in_array
				foreach ($PARAMETERS as $key=>$value) {
					$this->set($key,$value);
				}
			}
			if ($fill && $this->get('id')) { 
				$this->loadMeta();
			}
		}		
		
		$this->set('permalink',"$groupPath/" . $this->get('stub'));
		
		$this->success = true;

	}

/*************************************************************************************	
* Accessors 																			
*************************************************************************************/

	function members() { 
		if (!$this->get('id')) {
			return null;
		}
		if (!$this->MEMBERS) { 
			$this->MEMBERS = $this->POD->getPeople(array('mem.type:!='=>'invitee','mem.groupId'=>$this->get('id')),'mem.date DESC',100,0);
			if (!$this->MEMBERS->success()) {
				return null;
			}
		}
		
		return $this->MEMBERS;
	}
	
	function content() { 
		if (!$this->get('id')) {
			return null;
		}
		if (!$this->DOCUMENTS) { 
			$this->DOCUMENTS = new Stack($this->POD,'content',array('d.groupId'=>$this->get('id')));
			if (!$this->DOCUMENTS->success()) { 
				return null;
			}
		} 
		
		return $this->DOCUMENTS;
	}
	

/*************************************************************************************	
* CRUD 																			
*************************************************************************************/

	function loadById($id) {
		$d = $this->POD->checkcache('Group','id',$id);
		$groupPath =  $this->POD->siteRoot(false) . "/" . $this->POD->libOptions('groupPath');

		if ($d) {
			$this->DATA = $d;
			$this->set('permalink',"$groupPath/" . $this->get('stub'));

		} else {
			$this->POD->tolog("I AM LOADING FROM THE DB A GROUP!");
			$this->load('id',$id);
			if ($this->success()) { 
				$this->loadMeta();
				$this->set('permalink',"$groupPath/" . $this->get('stub'));
			}
		}
	}
	
		
	
	function loadByStub($stub) {
		$groupPath =  $this->POD->siteRoot(false) . "/" . $this->POD->libOptions('groupPath');

		$this->load('stub',$stub);
		if ($this->success()) { 
			$this->loadMeta();
			$this->set('permalink',"$groupPath/" . $this->get('stub'));
		}

	}
	
	



	
	function save() {
	
		$this->success = null;
		
		if (!$this->POD->isAuthenticated()) { 
			$this->success = false;
			$this->throwError("No current user! Can't save group!");
			$this->error_code = 500;
			return null;
		}			
		
		if ($this->get('id')) {
		// if we are updating this group, make sure this user has permission to do so!
			$membership = $this->isMember($this->POD->currentUser());
			if ($membership != 'owner' && $membership!='manager' && !$this->POD->currentUser()->get('adminUser')) {
				$this->success = false;
				$this->throwError("Access denied!  Only group owner or manager can create group!");
				$this->error_code = 401;	
				return null;				
			}
		} else {	
			$this->set('userId',$this->POD->currentUser()->get('id'));		
		}
	
	
		
		if ($this->get('groupname') && $this->get('description') && $this->get('userId')) {
		
			$this->set('groupname',stripslashes(strip_tags($this->get('groupname'))));
			$this->set('description',stripslashes(strip_tags($this->get('description'))));

			if (!$this->get('stub')) {
				$stub = $this->get('groupname');			
				$stub = preg_replace("/\s+/","-",$stub);
				$stub = preg_replace("/[^a-zA-Z0-9\-]/","",$stub);
				$stub = strtolower($stub);
				$this->set('stub',$stub);
			}

			$stub = $this->get('stub');			
			$newstub = $stub;
			
			// check and see if any documents already use this stub.
			$stubcheck = $this->POD->getGroup(array('stub'=>$stub));
			$counter = 2;
			while ($stubcheck->success() && $stubcheck->get('id')!=$this->get('id')) {
			
				$newstub = $stub . "_" . $counter++;
				$stubcheck = $this->POD->getGroup(array('stub'=>$newstub));				
			}
			
			$stub = $newstub;							
			$this->set('stub',$stub);

			if (!$this->saved()) { 
				$this->set('date','now()');
				$this->set('changeDate','now()');
			} else {
				$this->set('changeDate','now()');
			}

			
			parent::save();

			$this->DOCUMENTS = new Stack($this->POD,'content',array('d.groupId'=>$this->get('id')));
			$this->MEMBERS = new Stack($this->POD,'user',array('mem.groupId'=>$this->get('id')),'mem.date DESC',20,0);			
			$this->addMember($this->POD->getPerson(array('id'=>$this->get('userId'))),'owner');

			$this->success = true;
			return $this;
		} else {
			$this->success = null;
			$this->throwError("Missing required field");
			$this->error_code = 500;
			return null;			
		}
	
	}

	function delete($delete_documents = false) {
		$this->success = false;
		
		// only allow delete by the owner of this group!
		if ($this->POD->isAuthenticated() && (($this->isMember($this->POD->currentUser())=="owner") || $this->POD->currentUser()->get('adminUser'))) {
			
			if ($delete_documents) { 
				$this->content()->reset();
				while ($doc = $this->content()->getNext()) {
					$doc->delete();
				}
			} else {
				$this->content()->reset();
				while ($doc = $this->content()->getNext()) {
					$doc->set('groupId',null);
				}
				if ($this->get('type')=="private") {
					$sql = "UPDATE content SET privacy='owner_only' WHERE privacy='group_only' and groupId=" . $this->get('id');
					$this->POD->tolog($sql,2);
					mysql_query($sql,$this->POD->DATABASE);
				}

				$sql = "UPDATE content SET groupId=null WHERE groupId=" . $this->get('id');
				$this->POD->tolog($sql,2);
				mysql_query($sql,$this->POD->DATABASE);
				
			}
						
			mysql_query("DELETE FROM groupMember WHERE groupId=" . $this->get('id'),$this->POD->DATABASE);
			mysql_query("DELETE FROM invites WHERE groupId=" . $this->get('id'),$this->POD->DATABASE);			
			mysql_query("DELETE FROM meta WHERE type='group' and itemId=". $this->get('id'),$this->POD->DATABASE);
			mysql_query("DELETE FROM groups WHERE id=". $this->get('id'),$this->POD->DATABASE);
			
			$this->DATA = null;
			$this->success = true;
			return $this->success;
		} else {

			$this->success = false;
		
			$this->throwError("Access denied");
			$this->error_code = 401;
			return $this->success;
		}
	
	
	}
	

	function permalink($field='groupname',$return=false) {
	
		$string = "<a href=\"" . $this->get('permalink') . "\" title=\"" . htmlentities($this->get('groupname')) . "\">" . $this->get($field) . "</a>";
		if ($return) { 
			return $string;
		} else {
			echo $string;
		}
	
	}


/*************************************************************************************	
* MEMBERS 																			
*************************************************************************************/

	
	function removeMember($person) {
		$this->success = null;
		
		$this->POD->tolog("group->removeMember()");
		if (!$this->POD->isAuthenticated()) { 
			$this->success = false;
			$this->throwError("No current user! Can't save group!");
			$this->error_code = 500;
			return null;
		}			

		if (!$person->get('id')) {
			$this->throwError("Person not saved yet!");
			$this->error_code = 500;
			return null;
		}
	
		if (!$this->get('id')) {
			$this->throwError("Group not saved yet!");
			$this->error_code = 500;
			return null;
		}

		$membership = $this->isMember($person);
		$my_membership = $this->isMember($this->POD->currentUser());
		
		if (($person->get('id') != $this->POD->currentUser()->get('id')) && ($my_membership != 'owner') && ($my_membership!='manager') && !$this->POD->currentUser()->get('adminUser')) {
			$this->success = false;
			$this->throwError("Access denied!  Only group owner or manager can remove someone from a group!");
			$this->error_code = 401;	
			return null;				
		}
	
		
		if ($membership == "owner") {
			$this->throwError("Group owner cannot quit!");
			$this->error_code = 401;
			$this->success = null;

			return null;
		} else {
			$sql = "DELETE FROM groupMember WHERE userId=" . $person->get('id') . " AND groupId=" . $this->get('id');
			$this->POD->tolog($sql,2);
			$res = mysql_query($sql);
			$this->success = true;
			$this->members()->fill();
			if (!$this->members()->success()) { 
				$this->throwError($this->members()->error());
				return null;
			}

			$fact = $person->get('id') . "-ismemberof-" . $this->get('id');
			$this->POD->cachefact($fact,false);

			return true;
		}
	}
	
	
	function changeMemberType($person,$type) {
		$this->success = false;
		$this->POD->tolog("group->changeMemberType() $type");


		if (!$this->POD->isAuthenticated()) { 
			$this->success = false;
			$this->throwError("No current user! Can't save group!");
			$this->error_code = 500;
			return null;
		}

		$membership = $this->isMember($person);
		$my_membership = $this->isMember($this->POD->currentUser());

		if (!$membership) {
			$this->success = false;
			$this->throwError("Person is not member of this group.");
			$this->error_code = 500;
			return null;
		}

		if (($person->get('id') != $this->POD->currentUser()->get('id')) && ($my_membership != 'owner') && ($my_membership!='manager') && !$this->POD->currentUser()->get('adminUser')) {
			$this->success = false;
			$this->throwError("Access denied!  Only group owner or manager can change someone's member type.");
			$this->error_code = 401;	
			return null;				
		}

		if ($membership == "owner") {
			$this->throwError("Group owner can't be demoted!");
			$this->error_code = 401;
			$this->success = false;
			return null;
		}

		if (($type == "manager" || $type=="owner") && !($my_membership=="manager" || $my_membership=="owner")) {
			$this->throwError("Only a group owner or manager can promote members to manager or owner");
			$this->error_code = 401;
			$this->success = false;
			return null;		
		}

		$fact = $person->get('id') . "-ismemberof-" . $this->get('id');
		$this->POD->cachefact($fact,$type);
		
		$type = mysql_real_escape_string($type);
		$sql = "UPDATE groupMember SET type='$type',date=NOW() WHERE userId=" . $person->get('id') . " AND groupId=" . $this->get('id');
		$this->POD->tolog($sql,2);
		$res = mysql_query($sql,$this->POD->DATABASE);
		$num = mysql_affected_rows($this->POD->DATABASE);
		if ($num < 1 || !$res) {
			$this->success = false;
			$this->throwError("SQL Error: GroupMember Update failed!");
			$this->error_code = 500;
			return null;
		}
		$this->success = true;
		return $type;
	
	}
	
	function addMember($person,$type='member',$invited = null) {
		$this->success = null;

		$this->POD->tolog("group->addMember()");
	
		if (!$this->POD->isAuthenticated()) { 
			$this->success = false;
			$this->throwError("No current user! Can't add a member to a group!");
			$this->error_code = 500;
			return null;
		}
		
		if (!$person->get('id')) {
			$this->throwError("Person not saved yet!");
			$this->error_code = 500;
			return null;
		}
	
		if (!$this->get('id')) {
			$this->throwError("Group not saved yet!");
			$this->error_code = 500;
			return null;
		}




// FIX THIS
/*
		// if this is a private group, make sure there is an invite already
		$sql = "SELECT type FROM groupMember WHERE userId=" . $person->get('id') . " and groupId=" . $this->get('id');
		$res = mysql_query($sql,$this->POD->DATABASE);
		$invites = mysql_num_rows($res);
		if ($invites > 0) {
			$membership = mysql_fetch_assoc($res);
		}	

		if (!$invited && $this->get('type') == "private" && $membership != 'invitee') {
				// this is a private group and you don't have an invite.
				return null;
		}
*/
		
		if (!$this->isMember($person)) {
			$this->POD->tolog("group->addMember() adding member");
			$sql = "INSERT INTO groupMember (groupId,userId,type,date) values (" . $this->get('id') . "," . $person->get('id') . ",'" . $type . "',NOW());";
			$this->POD->tolog($sql,2);
			$result = mysql_query($sql);
			$num = mysql_affected_rows($this->POD->DATABASE);
			if ($num < 1 || !$result) {
				$this->success = false;
				$this->throwError("SQL Error: GroupMember Insert failed!");
				$this->error_code = 500;
				return null;
			}
			$this->members()->add($person);
			if (!$this->members()->success()) { 
				$this->throwError($this->members()->error());
				return null;
			}
			$fact = $person->get('id') . "-ismemberof-" . $this->get('id');
			$this->POD->cachefact($fact,$type);

		} else {
			$this->POD->tolog("group->addMember() already a member!");
		}
		
		$this->success = true;

		return $type;
	}
	
	function isMember($person) {
		$this->success = false;
		$this->error = null;
		
		if (!$person || !$person->get('id')) {
			// this doesn't necessarily mean an error has happened
			// maybe the user isn't authenticated...
			//$this->throwError("Person not saved yet!");
			//$this->error_code = 500;
			return null;
		}
	
		if (!$this->get('id')) {
			$this->throwError("Group not saved yet!");
			$this->error_code = 500;
			return null;
		}
		
		$fact = $person->get('id') . "-ismemberof-" . $this->get('id');
		
		if ($val = $this->POD->factcache($fact)) { 
			$this->success = true;
			return $val;
		}
		
			$sql = "SELECT type FROM groupMember WHERE userId=" . $person->get('id') . " and groupId= " . $this->get('id');
			$this->POD->tolog($sql,2);
			$res = mysql_query($sql,$this->POD->DATABASE);	
			$num = mysql_num_rows($res);
			$this->success = true;	

			if ($num > 0) {
				$g = mysql_fetch_assoc($res);
				$this->POD->cachefact($fact,$g['type']);
				return $g['type'];
			} else {
				$this->POD->cachefact($fact,false);
				return null;
			}
	}
	

/*************************************************************************************	
* Content 																			
*************************************************************************************/
	
	function addContent($doc) {
		$this->success = null;
		
		if (!$doc->get('id')) {
			$this->throwError("Content not saved yet!");
			$this->error_code = 500;
			return null;
		}
		if (!$doc->isEditable()) {
			$this->throwError("Access Denied! Not authenticated");
			$this->error_code = 401;
			return null;		
		}
	
		if (!$this->get('id')) {
			$this->throwError("Group not saved yet!");
			$this->error_code = 500;
			return null;
		}
		
		if (!$this->POD->isAuthenticated()) { 
			$this->throwError("Access Denied! Not authenticated");
			$this->error_code = 401;
			return null;		
		}

		$membership = $this->isMember($this->POD->currentUser());
		$this->success = false;
		if (!$membership && !$this->POD->currentUser()->get('adminUser')) {
			$this->throwError("Access Denied! Not a member");
			$this->error_code = 401;
			return null;		
		}
			
		if ($doc->get('groupId') && $doc->get('groupId') != $this->get('id')) {
			$this->throwError("Content already belongs to a group!");
			$this->error_code=401;
			return null;
		}
		
		if ($doc->get('groupId') == $this->get('id')) {
			// already in the group
			$this->success = true;
			return true;
		}
		
		// set the group.  don't change privacy here.  (so if a public content is added to a private group, it remains public.)
		$doc->setGroup($this->get('id'));
		if (!$doc->success()) {
			$this->throwError($doc->error());
			$this->error_code = $doc->errorCode();
			return null;
		}
		
		$this->success = true;	
		$this->content()->add($doc);
		return $doc;
	}
	
	function removeContent($doc) {
		$this->success = false;
		if (!$doc->get('id')) {
			$this->throwError("Content not saved yet!");
			$this->error_code = 500;
			return null;
		}
	
		if (!$this->get('id')) {
			$this->throwError("Group not saved yet!");
			$this->error_code = 500;
			return null;
		}
		if ($doc->get('groupId') && $doc->get('groupId') != $this->get('id')) {
			$this->throwError("Content doesn't belong to this group!");
			$this->error_code=401;
			return null;
		}

		if (!$doc->get('groupId')) {
			$this->success = true;
			return true;		
		}
		
		if ($doc->get('groupId') == $this->get('id')) {
			
			if ($doc->get('privacy') == "group_only") {
				$doc->set('privacy','owner_only');
			}
			$doc->setGroup(null);
			if (!$doc->success()) {
				$this->throwError($doc->error());
				$this->error_code = $doc->errorCode();
				return null;
			}
		
			$this->success = true;	
			$this->content()->fill();
			return $doc;
		}
	}

/* Functions that output things */

		function render($template = 'output',$backup_path=null) {
		
			return parent::renderObj($template,array('group'=>$this),'groups',$backup_path);
	
		}
	
		function output($template = 'output',$backup_path=null) {
		
			parent::output($template,array('group'=>$this),'groups',$backup_path);
	
		}
		
}

?>