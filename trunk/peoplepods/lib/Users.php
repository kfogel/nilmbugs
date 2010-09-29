<?
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* lib/Users.php
* Creates the Person object
*
* Documentation for this object can be found here:
* http://peoplepods.net/readme/person-object
/**********************************************/

require_once("Obj.php");
require_once("Stack.php");

class Person extends Obj {
	var $wrong_password;
	protected $FRIENDS;
	protected $FOLLOWERS;	
	protected $FAVORITES;
	protected $WATCHED;
	protected $FILES;
			
	function Person($POD,$PARAMETERS=null) {
		parent::Obj($POD,'user');	
		if (!$this->success()) {
			return $this;
		}
		
		$this->success = false;
		if (isset($PARAMETERS['authSecret']) && (sizeof($PARAMETERS)==1)) {
			$this->POD->tolog("user->new(): Attempting to verify user...");
			$this->getUserByAuthSecret($PARAMETERS['authSecret']);
		} else if (isset($PARAMETERS['passwordResetCode']) && (sizeof($PARAMETERS)==1)) {
			$this->POD->tolog("user->new(): Load by reset code...");
			$this->getUserByPasswordResetCode($PARAMETERS['passwordResetCode']);
		} else if (isset($PARAMETERS['id']) && (sizeof($PARAMETERS)==1)) {
			$this->POD->tolog("user->new(): Load user by id " . $PARAMETERS['id']);
			$this->getUserById($PARAMETERS['id']);
	 	} else if (isset($PARAMETERS['email']) && (sizeof($PARAMETERS)==1)) {
			$this->POD->tolog("user->new(): Load user by email");
	 		$this->getUserByEmail($PARAMETERS['email']);
	 	} else if (isset($PARAMETERS['nick']) && (sizeof($PARAMETERS)==1)) {
			$this->POD->tolog("user->new(): Load user by nick");
	 		$this->getUserByNick($PARAMETERS['nick']); 
		} else if (isset($PARAMETERS['nick']) && isset($PARAMETERS['email']) && ($PARAMETERS['password'] || $PARAMETERS['id'])) {
			$this->POD->tolog("user->new(): Creating user from parameters");
			$fill = true;
			
			if (isset($PARAMETERS['id'])) {
				$d = $this->POD->checkcache('Person','id',$PARAMETERS['id']);
				if ($d) {
					$fill = false;
					$this->DATA = $d;
					$this->success = true;
				} 
			}
			
			if ($fill) {
				foreach ($PARAMETERS as $key => $value) {
					if ($key != 'POD') {
						$this->set($key,$value);
					}
				}
				$this->success = true;
				$this->stuffUser();
				$this->loadMeta();
				$this->POD->cachestore($this);
			}
		} else {
			$this->success = true;
			 $this->POD->tolog("user->new(): Empty User");
		}


		// if we failed to create the user by this point, we're screwed.
		if (!$this->success()) { 
			return;
		}

		return $this;
		
	}



	
/*********************************************************************************************
* Accessors
*********************************************************************************************/
		function addFile($file_name,$uploaded_file,$description=null) { 
		// pass in an array of parameters from the $_FILES array and this will automatically create the file record.
		
			$this->success = false;
			
			// if the file already exists, update it.
			if (!$file = $this->files()->contains('file_name',$file_name)) { 
				// create a new file
				$file = $this->POD->getFile();
			}
		
			if ($uploaded_file['tmp_name']) {
					
					$file->set('file_name',$file_name);
					$file->set('original_name',$uploaded_file['name']);
					$file->set('tmp_name',$uploaded_file['tmp_name']);
					$file->set('description',$description);
					$file->set('userId',$this->id);
					$file->save();		
					if (!$file->success()) {
						$this->throwError($file->error());
					} else {
						$this->success = true;
						return $file;
					}
			} else {
				// sometimes an invalid record gets into $_FILES where no tmp_name is specified
				// this normally happens when a javascript form validator has caused the file input to submit
				// even though there is no file!
				// we don't want to throw an error if this happens, we just want to silently ignore this record.
				$this->success = true;
				return null;
			}

			return $this->success;		
		
		}
		
		function files($count=100,$offset=0) {		
			if (!$this->get('id')) {
				return null;
			}
			if (!$this->FILES) { 
				$this->FILES = new Stack($this->POD,'file',array('contentId'=>'null','userId'=>$this->get('id')),null,$count,$offset);
				if (!$this->FILES->success()) { 
					return null;
				}
			}
			return $this->FILES;
		}
		
		
		function friends($count = 20,$offset=0) { 
			if (!$this->get('id')) {
				return null;
			}
			if (!$this->FRIENDS || $count != 20 || $offset != 0) {
				$this->FRIENDS =   new Stack($this->POD,'user',array('flag.name'=>'friends','flag.userId'=>$this->get('id')),"flag.date DESC",$count,$offset);
				if (!$this->FRIENDS->success()) { 
					return null;
				}
			}
			return $this->FRIENDS;	

		}

		function followers($count=20,$offset=0) { 
			if (!$this->get('id')) {
				return null;
			}
			if (!$this->FOLLOWERS || $count != 20 || $offset != 0) {
				$this->FOLLOWERS = new Stack($this->POD,'user',array('flag.name'=>'friends','flag.itemId'=>$this->get('id')),"flag.date DESC",$count,$offset);
				if (!$this->FOLLOWERS->success()) { 
					return null;
				}
			}
			return $this->FOLLOWERS;	
		}

		function favorites($count=20,$offset=0) {
			if (!$this->get('id')) {
				return null;
			}		
			if (!$this->FAVORITES) {
				$this->FAVORITES =   new Stack($this->POD,'content',array('flag.name'=>'favorite','flag.userId'=>$this->get('id')),'flag.date DESC',$count,$offset);	
				if (!$this->FAVORITES->success()) { 
					return null;
				}
			}
			return $this->FAVORITES;
		
		}

		function watched($count=20,$offset=0) {
			if (!$this->get('id')) {
				return null;
			}		
			if (!$this->WATCHED) {
				$this->WATCHED =   new Stack($this->POD,'content',array('flag.name'=>'watching','flag.userId'=>$this->get('id')),'d.commentDate DESC',$count,$offset);	
				if (!$this->WATCHED->success()) { 
					return null;
				}
			}
			return $this->WATCHED;
		
		}
		
		function asArray() { 
		
			$data = parent::asArray();
			// remove some fields

			
			unset($data['email']);
			unset($data['verificationKey']);
			unset($data['authSecret']);
			unset($data['password']);
			unset($data['passwordResetCode']);
			
			
			return $data;

		}


/* Loader Functions */


	function save($nomail = false) {
		
		$profilePath = $this->POD->libOptions('profilePath');
		
		$this->success = false;
		
		$this->POD->tolog("user->save() " . $this->get('nick'));



		// clean up input
		$this->set('nick',stripslashes(strip_tags($this->get('nick'))));
		$this->set('email',stripslashes(strip_tags($this->get('email'))));


		if ($this->get('nick') == "") {
			$this->throwError("Missing required field nick.");
			$this->error_code=201;
			return null;
		}
		if ($this->get('email') == "") {
			$this->throwError("Missing required field email.");
			$this->error_code=202;
			return null;
		}

		// Do I need to create a user or update a user?
		if (!$this->saved()) { 

			// CREATE NEW USER!
			
			$this->set('memberSince','now()');


			// new users must specify a password, though we will not store it in the db			
			if ($this->get('password') == "") {
				$this->throwError("Missing required field password.");
				$this->error_code=203;
				return null;
			}		

			$error = $this->checkUsernames($this->get('nick'),$this->get('email'),'');
			if ($error == "nick_taken") {
				$this->throwError("Oops!  The name you specified is already being used by someone else on the site.  Please pick a new one.");
				$this->error_code = 204;
				return;
			} else if ($error == "email_taken") {
				$this->throwError("Ooops! The email address you specified is already registered on the site.");
				$this->error_code = 205;
				return;
			}
				
				
			// FIX THIS
			// Should use an oop method for handling invites.	
			if ($this->get('invite_code') != '') {
				$this->POD->tolog('user->save() Looking for invite.');
				$sql = "SELECT * FROM invites WHERE code='" . $this->get('invite_code') . "';";
				$this->POD->tolog($sql,2);
				$res = mysql_query($sql,$this->POD->DATABASE);
				$num = mysql_num_rows($res);
				if ($num > 0) {
					$this->POD->tolog("user->save() INVITE FOUND");
					$invite = mysql_fetch_assoc($res);
					$sql = "DELETE FROM invites WHERE id=" . $invite['id'];
					$this->POD->tolog($sql,2);
					mysql_query($sql,$this->POD->DATABASE);		
				}
			}
				
			$authSecret = md5($this->get('email') . $this->get('password'));
			$this->set('safe_nick',urlencode(preg_replace("/\s/","_",$this->get('nick'))));
			$this->set('permalink',"$profilePath/" . $this->get('safe_nick'));
			$this->set('authSecret',$authSecret);
			
			// now that we've generated the authSecret, we can clear the password
			$this->set('password',null);
					
			
			if ($invite) {
			
				$this->POD->tolog('user->save() Invite found, processing...');
				$invitedBy = $invite['userId'];
				$this->set('invitedBy',$invitedBy);
				
				// members who are invited by other members do not need to confirm their emails
				$this->set('verificationKey',null);

				parent::save();
				if (!$this->success()) { 
					$this->POD->cacheclear($this);
					return null;				
				}						
				
				$this->POD->changeActor(array('id'=>$this->get('id')));
				
				if (isset($invite['groupId'])) {
					$this->POD->tolog('user->save() Adding user to group');
					$group = $this->POD->getGroup(array('id'=>$invite['groupId']));
					$group->addMember($this,'member',true);					
				}

				$inviter = $this->POD->getPerson(array('id'=>$invitedBy));
				
				// add the person who invited me as a friend, and send an email
				$this->addFriend($inviter);
				
				// cause the friend who invited me to add me as a friend, but do not send email
				$inviter->addFriend($this,false);				
				
			} else {
				
				// new members have to confirm their email address
				$this->set('verificationKey',md5($this->get('password').$this->get('email')));

				parent::save();
				if (!$this->success()) { 
					$this->POD->cacheclear($this);
					return null;				
				}					

			}
			
			$this->success = true;
			if (!$nomail) { 
				$this->POD->tolog("user->save() user created, sending welcome email");
				$this->welcomeEmail();
			}	
		
		} else {
			// UPDATE USER
		
			$this->POD->tolog("user->save() Updating user " . $this->get('nick'));
			
			$error = $this->checkUsernames($this->get('nick'),$this->get('email'),$this->get('id'));
			if ($error == "nick_taken") {
				$this->throwError("Oops!  The name you specified is already being used by someone else on the site.  Please pick a new one.");
		 		$this->error_code = 208;
				$this->POD->cacheclear($this);
				return;
			} else if ($error == "email_taken") {
				$this->throwError("Oops! The email address you specified is already registered on the site.  You might need to <a href=\"/$this->POD->DATABASEPath/login.php\">log in</a>.");
		 		$this->error_code = 209;
				$this->POD->cacheclear($this);
				return;
			}

			if ($this->get('password')) { 
				$this->set('authSecret',md5($this->get('email') . $this->get('password')));		
				$this->set('password',null);	
			}

	
			parent::save();
			if (!$this->success()) { 
				$this->POD->cacheclear($this);
				return null;				
			}			
		
		}	
		

		$this->stuffUser();
		$this->success = true;
		$this->POD->cachestore($this);
		return $this;
	
	} // end function save()




	function delete() {
	
		$this->success = false;
		
		if ($this->get('id')=='') {
			$this->throwError("User not saved yet!");
			$this->error_code = 222;
			return false;
		}
		
		// can only be deleted by self or adminUser
		if ($this->POD->isAuthenticated() && (($this->POD->currentUser()->get('id') == $this->get('id')) || ($this->POD->currentUser()->get('adminUser')))) {
			if ($this->get('id')) { 
			
				$this->POD->cacheclear($this);

				$id = $this->get('id');
				// get all the documents, delete them
				// this should delete any watch, favorite, votes, etc.
				$docs = $this->POD->getContents(array('userId'=>$id),null,1000000);
				while ($doc = $docs->getNext()) { 
					$doc->delete();
					if (!$doc->success()) {
						$this->throwError($doc->error());
						$this->error_code = $doc->errorCode();
						return false;
					}
				}
				
				$this->files()->reset();
				while ($file = $this->files()->getNext()) {
					$file->delete();
				}
				// get rid of any remaining comments by this user in other threads
				mysql_query("DELETE FROM comments WHERE userId=$id");		
								
				// group memberships		
				mysql_query("DELETE FROM groupMember WHERE userId=$id");
				
				// meta		
				mysql_query("DELETE FROM meta WHERE type='user' and itemId=$id");	
				
				// outgoing flags
				mysql_query("DELETE FROM flags WHERE userId=$id");
				
				// incoming flags
				mysql_query("DELETE FROM flags WHERE type='user' and itemId=$id");
	
				// delete the messages
				mysql_query("DELETE FROM messages WHERE fromId=$id OR toId=$id");		

				// delete the user totally
				mysql_query("DELETE FROM users WHERE id=$id");		

				$this->DATA = array();
				$this->success = true;
			}
		} else {
			$this->throwError("Access denied");
			$this->error_code = 401;
		}
		
		return $this->success;
	}


	function permalink($field = 'nick',$return = false) {
		$string = "<a href=\"" . $this->get('permalink') . "\" class=\"person_permalink\" title=\"" . htmlentities($this->get('nick')) . "\">" . $this->get($field) . "</a>";
		if ($return) { 
			return $string;
		} else {
			echo $string;
		}
	}


	function stuffUser() {
		$profilePath = $this->POD->siteRoot(false) .$this->POD->libOptions('profilePath');
		
		if ($this->get('id')) {
		
			$this->set('safe_nick',urlencode(preg_replace("/\s/","_",$this->get('nick'))));
			$this->set('permalink',"$profilePath/" . $this->get('safe_nick'));
		
		}
	}
	
	
	function getContents($PARAMETERS = null,$sort="date DESC",$count=20,$offset=0) {

		$PARAMETERS['userId'] = $this->get('id');
		return $this->POD->getContents($PARAMETERS,$sort,$count,$offset);
	}

	function getUserByNick($nick) {

		$no_u_nick = preg_replace("/\_/"," ",$nick);

		$d = $this->POD->checkcache('Person','nick',$nick);
		if ($d) {
			$this->success = true;
			$this->DATA = $d;
		} else {
			$this->load('nick',$nick);
			if (!$this->success()) {
				$this->load('nick',$no_u_nick);
			}
			$this->stuffUser();
			$this->loadMeta();
			$this->POD->cachestore($this);
		}	
		return $this;

	}	
	
	function getUserByEmail($email) {
		$this->load('email',$email);
		$this->stuffUser();
		$this->loadMeta();
		$this->POD->cachestore($this);
		return $this;		
	}	

	function getUserById($uid) {
		$this->success = false;
		$d = $this->POD->checkcache('Person','id',$uid);
		if ($d) {
			$this->success = true;
			$this->POD->tolog("user->getUserById(): USING CACHE");
			$this->DATA = $d;
		} else {
			$this->POD->tolog("user->getUserById(); NOT USING CACHE");
			$this->load('id',$uid);
			if ($this->success()) { 
				$this->stuffUser();
				$this->loadMeta();
				$this->POD->cachestore($this);
				$this->success = true;
			}
		}
		return $this;
	}	


	function getUserByAuthSecret($authSecret) {
		$this->success = false;
		$d = $this->POD->checkcache('Person','auth',$authSecret);
		if ($d) { 
			$this->success = true;
			$this->POD->tolog("user->getUserByAuthSecret(): USING CACHE");
			$this->DATA = $d;
			$sql = "UPDATE users SET lastVisit=NOW() WHERE id=" . $this->get('id');
			$this->set('lastVisit',time());
			$this->POD->tolog($sql,2);
			mysql_query($sql,$this->POD->DATABASE);

		} else {
			$this->load('authSecret',$authSecret);
			$this->POD->tolog("user->getUserByAuthSecret(): NOT USING CACHE");
			if ($this->success()) {
				$sql = "UPDATE users SET lastVisit=NOW() WHERE id=" . $this->get('id');
				$this->POD->tolog($sql,2);
				mysql_query($sql,$this->POD->DATABASE);
				$this->set('lastVisit',time());

				$this->stuffUser();
				$this->loadMeta();		
				$this->POD->cachestore($this);
			}
		} 
		
		return $this;
	}	
	
	
	function getUserByPasswordResetCode($resetCode) {
		
		$this->load('passwordResetCode',$resetCode);
		$this->stuffUser();
		$this->loadMeta();

		$this->POD->cachestore($this);
		return $this;
	}	



/* Output Functions */

	function render($template = 'output',$backup_path=null) {
	
		return parent::renderObj($template,array('user'=>$this),'people',$backup_path);

	}

	function output($template = 'output',$backup_path=null) {
		parent::output($template,array('user'=>$this),'people',$backup_path);

	}




/* Awesome functions */


	/*
	* Recommend Friends based on Friend-of-Friend network
	*
	*/
	function recommendFriends($minimumoverlap = 2,$max=20) {
	
		$this->friends(500)->reset();	
		$fof = array();
		while ($u = $this->friends()->getNext()) {

			while ($x = $u->friends()->getNext()) {
				if (isset($fof[$x->get('id')])){
					$fof[$x->get('id')]['count']++;
				} else{
					$fof[$x->get('id')]['user'] = $x;
					$fof[$x->get('id')]['count'] = 1;
				}
			}
		}
		
		$results = new Stack($this->POD,'user');
		foreach ($fof as $rec) {
			if ($results->count() <= $max) {
				$p = $rec['user'];
				if ($rec['count'] >= $minimumoverlap && !$this->isFriendsWith($p) && $p->success() && $this->get('id') != $p->get('id')){ 
					$results->add($p);
				}
			}
		}
		$this->friends()->reset();			
		return $results;
	}

		
	function friendList() {
		
		return $this->friends()->extract('id');		
	
	}

	
	function getVote($doc) {
		$val = $doc->hasFlag('vote',$this);		
		$this->success = $doc->success();
		if (!$this->success()) { 
			$this->throwError($doc->error());
			$this->error_code = $doc->errorCode();
		}
		return $val;
	}
	
	function publishActivity($type,$message,$bundle_message=null,$target = null,$target_alert=null,$gid=null) {
	
		$act = $this->POD->getActivity();
		$act->publish($this->id,$type,$message,$bundle_message,$target,$target_alert,$gid);
	}

	
/*************************************************************************************	
* Comment Watching 																			
*************************************************************************************/

	function isWatched($doc) {

		$this->success = false;
		$val = $doc->hasFlag('watching',$this);		
		$this->success = $doc->success();
		if (!$this->success()) { 
			$this->throwError('isWatched ' . $doc->error());
			$this->error_code = $doc->errorCode();
		}
		return $val;				
	}

	function removeWatch($doc) {

		$val = $doc->removeFlag('watching',$this);		
		$this->success = $doc->success();
		if (!$this->success()) { 
			$this->throwError('removeWatch ' . $doc->error());
			$this->error_code = $doc->errorCode();
		} else {
			if ($this->watched()->full()) { 
				$this->watched()->fill();
			}
		}
		return $doc;	
	}
	

	function addWatch($doc,$start_from_beginning = false) {
		
		if ($doc->comments()->count() > 0) { 
			$doc->comments()->reset();
			while ($c = $doc->comments()->getNext()) {
				$lastcomment = $c->get('id');
			}
			$doc->comments()->reset();
		} else {
			$lastcomment = 1;
		}	
		
		if ($start_from_beginning) {
			$lastcomment = 1;
		}

		// we need to purge any pre-existing flag.
		$doc->removeFlag('watching',$this);
		$val = $doc->addFlag('watching',$this,$lastcomment);		
		$this->success = $doc->success();
		if (!$this->success()) { 
			$this->throwError('addWatch ' . $doc->error());
			$this->error_code = $doc->errorCode();
		} else {
			if ($this->watched()->full()) { 
				$this->watched()->fill();
			}
		}
		return $doc;

	}

	function toggleWatch($doc) {
		
		if ($this->isWatched($doc)) {
			$this->removeWatch($doc);
			return 0;
		} else {
			$this->addWatch($doc);
			return 1;
		}	
	}

	
/*************************************************************************************	
* FAVORITES 																			
*************************************************************************************/

	function isFavorite($doc) {
		$val = $doc->hasFlag('favorite',$this);		
		$this->success = $doc->success();
		if (!$this->success()) { 
			$this->throwError('isFavorite ' . $doc->error());
			$this->error_code = $doc->errorCode();
		}
		return $val;
	}

	function removeFavorite($doc) {

		$val = $doc->removeFlag('favorite',$this);		
		$this->success = $doc->success();
		if (!$this->success()) { 
			$this->throwError('removeFavorite ' . $doc->error());
			$this->error_code = $doc->errorCode();
		} else {
			if ($this->favorites()->full()) { 
				$this->favorites()->fill();
			}
		}
		return $doc;
		
	}

	function addFavorite($doc) {

		$val = $doc->addFlag('favorite',$this);		
		$this->success = $doc->success();
		if (!$this->success()) { 
			$this->throwError('addFavorite ' . $doc->error());
			$this->error_code = $doc->errorCode();
		} else {
			if ($this->favorites()->full()) { 
				$this->favorites()->fill();
			}
		}
		return $doc;

	}

	function toggleFavorite($doc) {

		$val = $doc->toggleFlag('favorite',$this);		
		$this->success = $doc->success();
		if (!$this->success()) { 
			$this->throwError('toggleFavorite ' . $doc->error());
			$this->error_code = $doc->errorCode();
		} else {
			if ($this->favorites()->full()) { 
				$this->favorites()->fill();
			}
		}
		return $val;
		
		
	}



/*************************************************************************************	
* FRIENDS 																			
*************************************************************************************/



	function isFriendsWith($person) {
		
		$val = $person->hasFlag('friends',$this);		
		$this->success = $person->success();
		if (!$this->success()) { 
			$this->throwError('isFriendsWith ' . $person->error());
			$this->error_code = $person->errorCode();
		}
		return $val;
	}

	function removeFriend($person) {
	
		$val = $person->removeFlag('friends',$this);		
		$this->success = $person->success();
		if (!$this->success()) { 
			$this->throwError('removeFriend ' .$person->error());
			$this->error_code = $person->errorCode();
		} else {
			if ($this->favorites()->full()) { 
				$this->favorites()->fill();
			}
		}
		return $person;
		
	
	}
	
	
	function addFriend($person,$sendEmail=true) {
		
		$this->POD->tolog("user->addFriend(): Adding friend relationship between " . $this->get('nick') . " and " . $person->get('nick'));

		$wasAlreadyFriends = $this->isFriendsWith($person);
		$val = $person->addFlag('friends',$this);		
		$this->success = $person->success();
		if (!$this->success()) { 
			$this->throwError('addFriend ' . $person->error());
			$this->error_code = $person->errorCode();
		} else {

			if ($sendEmail && !$wasAlreadyFriends) {
				if ($this->POD->libOptions('friendEmail')) {
					$this->sendEmail("addFriend",array('to'=>$person->get('email')));
				}
			}

			if ($this->friends()->full()) { 
				$this->friends()->fill();
			}
		}
		return $person;	
	}




/*************************************************************************************	
* HELPERS 																			
*************************************************************************************/



	function sendEmail($email_name,$vars = null) {
		$this->success = null;
	
		// set up some variables
		// we know that we'll have a user, because the email is going to them.
		$sender = $this;
		$to = $this->get('email');
		$document = null;
		$group = null;
		$message = null;
		$code = null;
		
		$subject = "Email from " . $this->POD->libOptions('siteName');
	
		
		// we might also have a document, like when someone shares a post with someone else
		if (isset($vars['document'])) { 
			$document = $vars['document'];
		}

		if (isset($vars['subject'])) { 
			$subject = stripslashes($vars['subject']);
		}		

		// we might also have a group, like when someone invites someone to a group
		if (isset($vars['group'])) {
			$group = $vars['group'];
		}
				
		// and we might have a custom message, like when someone sends a personal note.
		if (isset($vars['message'])) {
			$message = stripslashes($vars['message']);
		}
		
		// by default, we assume this is an email going to this user.  but maybe we're sending a note or inviting someone.
		if (isset($vars['to'])) {
			$to = $vars['to'];
		}
		
		// finally, we may have an invite code or some other kind of secret code. 
		if (isset($vars['code'])) {
			$code = $vars['code'];
		}
	
		// using output buffering, we can just include the output of the appropriate email template and capture it in a string
		// the email templates should also reset $subject
		ob_start();
	
		include($this->POD->libOptions('templateDir') . "/emails/" . $email_name . ".php");
		
		$body = ob_get_contents();
		
		ob_end_clean();

		$headers = "From: " . $this->POD->libOptions('fromAddress') . "\r\n" . "X-Mailer: PeoplePods - XOXCO.com";
		$this->POD->tolog("Sending email: $subject to $to");
		if (mail($to, $subject, $body, $headers)) {
			$this->success = true;
		} else {
			$this->POD->tolog("Failed to send email $email_name to " . $to);
		}
		
		return $this->success;
	}



	
	function sendInvite($email,$message,$groupId = null) { // send an invite to someone.  optionally include a group to be invited to.
		
		$touser = $this->POD->getPerson(array('email'=>$email));
		if ($touser->success()) {
		// this person is already a member. 
		// add friend and/or invite to group 
		
			if (isset($groupId)) {
				
				$group = $this->POD->getGroup(array('id'=>$groupId));			
			// add group invitee membership
				$this->POD->tolog('user->sendInvite(): inviting existing user to group');
				$group->addMember($touser,'invitee',true);
				
				$this->sendEmail('invite',array('group'=>$group,'message'=>$message,'to'=>$touser->get('email')));

			} else {
				$this->POD->tolog('user->sendInvite(): adding friend on site');
				$this->addFriend($touser);
			}	
		} else {
			if (isset($groupId)) {
				$this->POD->tolog('user->sendInvite(): inviting new user to group');

				$group = $this->POD->getGroup(array('id'=>$groupId));			

			// generate invite key
				$vkey = md5($email . time() . $this->get('nick'));
				$sql = "INSERT INTO invites (userId,groupId,date,code) VALUES (" . $this->get('id') . "," . $group->get('id') . ",NOW(),'" . $vkey . "');";
				$this->POD->tolog($sql,2);
				mysql_query($sql,$this->POD->DATABASE);

				$this->sendEmail('invite',array('group'=>$group,'message'=>$message,'to'=>$email,'code'=>$vkey));
				
			} else {
				$this->POD->tolog('user->sendInvite(): inviting new user to join');		
		
				// generate invite key
				$vkey = md5($email . time() . $this->get('nick'));
					
				$sql = "INSERT INTO invites (userId,date,code) VALUES (" . $this->get('id') . ",NOW(),'" . $vkey . "');";
				$this->POD->tolog($sql,2);
				mysql_query($sql,$this->POD->DATABASE);

				$this->sendEmail('invite',array('message'=>$message,'to'=>$email,'code'=>$vkey));

			}
		}
				
	}


	function isVerified() {
		return ($this->get('verificationKey')=='');
	}

	function verify($code) {
		$this->success = null;	
	
		$this->POD->tolog("user->verify(): Does $code = " . $this->get('verificationKey'));
		if ($code == $this->get('verificationKey')) {
			$this->POD->tolog("user->verify(): VERIFIED");
			$this->set('verificationKey',null);
			$this->save();
		} else {
			$this->error_code = 221;
			$this->throwError("Could not verify: verification code incorrect");
		}
	

	}
	
	
	function sendMessage($message) {
	
			$this->success = null;
			
			$msg = new Message(array('POD'=>$this->POD,'toId'=>$this->get('id'),'message'=>$message));
			$msg->save();
			if ($msg->success()) { 
				$this->success = true;
				return $msg;		
			} else {
				$this->throwError($msg->error());
				$this->error_code = $msg->errorCode();
				return null;
			}	
	
	}
	

	function sendPasswordReset() { // send a password reset message to this user via email
		return $this->sendEmail('passwordReset');			
	}



	function welcomeEmail() { // send a welcome/verify your email message to this user via email
		return $this->sendEmail('welcome');			
	}

	function checkUsernames($nick,$email,$id) {
		
		
		$nick = mysql_real_escape_string(stripslashes($nick));
		$email = mysql_real_escape_string(stripslashes($email));
		$idsql = '';
		if ($id != '') {
			$idsql = "AND users.id!=$id ";
		}
		
		$sql = "SELECT nick='$nick' as nicktaken,email='$email' as emailtaken FROM users WHERE (nick='$nick' OR email='$email') $idsql;";
		$this->POD->tolog($sql,2);
		$res = mysql_query($sql,$this->POD->DATABASE);
		$num = mysql_num_fields($res);
		if ($num > 0) {
			$error = mysql_fetch_assoc($res);
			mysql_free_result($res);
			if ($error['nicktaken']==1) {	
				return 'nick_taken';
			} 
			if ($error['emailtaken']==1) {
				return 'email_taken';
			}
		} else {
			return;
		}
	
	}





} // end Person class

// -------------------------------------------------------------------------------------------------------- //
// -------------------------------------------------------------------------------------------------------- //
// -------------------------------------------------------------------------------------------------------- //
// -------------------------------------------------------------------------------------------------------- //
// -------------------------------------------------------------------------------------------------------- //
// -------------------------------------------------------------------------------------------------------- //


?>
