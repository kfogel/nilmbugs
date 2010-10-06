<? 
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* lib/Content.php
* This file defines the Content object.  This object handles all of the different kinds of content
* that might exist in a peoplepods site.
*
* Documentation for this object can be found here:
* http://peoplepods.net/readme/content-object
/**********************************************/
	require_once("Obj.php"); 
	class Content extends Obj{

		protected $CHILDREN;
		protected $COMMENTS;
		protected $FILES;
		protected $TAGS;
		protected $GROUP;

		protected $IS_EDITABLE = false;
		protected $IS_FAVORITE = false;

		protected $VOTE = null;
				
		/* 
		* Content Constructor
		* @var $POD peoplepod object
		* @var array Associative array of parameters that MUST include POD, but may also include id or stub to load, or a list of default values.
		* @return Content New object
		*/ 
		function Content($POD,$PARAMETERS=null) {
				parent::Obj($POD,'content');
				if (!$this->success()) {
					return $this;
				}
				
				
				// Load a document from the database or from defaults, based on the parameters
				if (isset($PARAMETERS['id']) && (sizeof($PARAMETERS)==1)) { 
					// load by ID
					$this->getContentById($PARAMETERS['id']);		
					if (!$this->success()) {
						return;
					}				
				} else if (isset($PARAMETERS['stub']) && (sizeof($PARAMETERS)==1)) {		
					// load by unique stub
					$this->getContentByStub($PARAMETERS['stub']);
					if (!$this->success()) {
						return;
					}				

				} else if ($PARAMETERS) {
					// create based on parameters
					$this->POD->tolog("content->new Create doc from parameters");
					
					$fill = true;
					if (isset($PARAMETERS['id'])) {
						$d = $this->POD->checkcache('Content','id',$PARAMETERS['id']);
						if ($d) {
							$fill = false;
							$this->DATA = $d;
						} 
					}
			
					if ($fill) { 
						foreach ($PARAMETERS as $key=>$value) {
							$this->set($key,$value);
						}
			
								
						if (!$this->get('id')) {
							if (!$this->POD->isAuthenticated()) { 
								$this->success = false;
								$this->throwError("No current user! Can't create content!");
								return null;
							}
		
							$this->set('userId',$this->POD->currentUser()->get('id'));
						}
						
						$this->stuffDoc();	
						$this->POD->cachestore($this);
					}
				} else {	
					// this is a brand new content object	
					// we still need to call generatePermalink because some other stuff gets set up		
					$this->generatePermalink();

				}

				
				
				if ( $this->POD->isAuthenticated() && (($this->get('userId') == $this->POD->currentUser()->get('id')) || ($this->POD->currentUser()->get('adminUser')) || ($this->get('createdBy') == $this->POD->currentUser()->get('id')) ||!$this->get('id')) ) {
					// if there is a user logged in, and this user is the creator of this content, set the editable flag to true.
					$this->IS_EDITABLE = true;
				}
				
				if ($this->get('privacy') == "friends_only") { 
				
					if ($this->POD->isAuthenticated() && $this->author()->isFriendsWith($this->POD->currentUser())) {
						// OK! we are authenticated and this person is friends with the author.	
					} else if ($this->isEditable()) {
						// i own this, so of course I can see it
					} else {
						$this->throwError("Access Denied: Friends Only!");
						$this->error_code = 402;
						$this->success = false;
						$this->DATA = array();
						return $this;
					}		
				}


				if ($this->get('privacy') == "owner_only") { 
					if ($this->isEditable()) {
						// i own this, so of course I can see it
					} else {
						$this->throwError("Access Denied: Owner Only!");
						$this->error_code = 402;
						$this->success = false;
						$this->DATA = array();
						return $this;
					}		
				}

				if ($this->get('privacy') == "group_only") { 
					$group = $this->POD->getGroup(array('id'=>$this->get('groupId')));
					if ($group->success()) { 
						if ($this->POD->isAuthenticated() && $group->isMember($this->POD->currentUser())) {
							// OK! we are authenticated and this person is a member of the group that this doc is in.	
						} else if ($this->isEditable()) {
							// i own this, so of course I can see it
						} else {
							$this->throwError("Access Denied: Group Members Only!");
							$this->error_code = 402;
							$this->success = false;
							$this->DATA = array();
							return $this;
						}		
					}
				}
				
				if (isset($PARAMETERS['lockdown']) && $PARAMETERS['lockdown'] == "owner") {
					// check to make sure we can access this
					if ( !$this->POD->isAuthenticated() || ($this->get('userId') != $this->POD->currentUser()->get('id')) ) {
						$this->throwError("Access Denied");
						$this->error_code = 401;
						$this->success = false;			
						return $this;			
					}
				}			
					
					
					
				$this->success = true;
				return $this;	
		
		}
	
	
	
/*********************************************************************************************
* Accessors
*********************************************************************************************/
	
		
		function children() {		
			if (!$this->get('id')) {
				return null;
			}		
			if (!$this->CHILDREN) {
				$this->CHILDREN = $this->POD->getContents(array('parentId'=>$this->get('id')));
				if (!$this->CHILDREN->success()) { 
					return null;
				}
			}
			return $this->CHILDREN;	
		}
						
		function comments() {		
			if (!$this->get('id')) {
				return null;
			}		
			if (!$this->COMMENTS) {
				$this->COMMENTS = $this->POD->getComments(array('contentId'=>$this->get('id')));
				if (!$this->COMMENTS->success()) { 
					return null;
				}
			}
			return $this->COMMENTS;			
		
		}
		
		
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
					$file->set('contentId',$this->get('id'));
					$file->set('description',$description);
					$file->save();		
					if (!$file->success()) {
						$this->throwError($file->error());
					} else {
						$this->success = true;
						return $file;
					}
			} else if ($uploaded_file['error']!= 0 && $uploaded_file['error']!= UPLOAD_ERR_NO_FILE) {
			
				if ($uploaded_file['error'] == UPLOAD_ERR_INI_SIZE) {
					$this->throwError('The file ' . $file_name . ' exceeds the maximum allowed upload size on this server.');
				}
				if ($uploaded_file['error'] == UPLOAD_ERR_FORM_SIZE) {
					$this->throwError('The file ' . $file_name . ' exceeds the maximum allowed upload size for this form.');
				}
				if ($uploaded_file['error'] == UPLOAD_ERR_PARTIAL) {
					$this->throwError('The file ' . $file_name . ' did not successfully upload.');
				}
				if ($uploaded_file['error'] == UPLOAD_ERR_NO_TMP_DIR) {
					$this->throwError('PeoplePods cannot find a temporary folder to store the uploaded files.');
				}				
				if ($uploaded_file['error'] == UPLOAD_ERR_CANT_WRITE) {
					$this->throwError('PeoplePods cannot write to the temporary folder.');
				}				
				if ($uploaded_file['error'] == UPLOAD_ERR_EXTENSION) {
					$this->throwError('A PHP extension stopped the file upload.');
				}				

				return false;
			
			} else {
			
				// sometimes an invalid record gets into $_FILES where no tmp_name is specified
				// this normally happens when a javascript form validator has caused the file input to submit
				// even though there is no file!
				// we don't want to throw an error if this happens, we just want to silently ignore this record.
				$this->success = true;
			}
		
			return $this->success;
		
		}
		
		
		function files() {		
			if (!$this->get('id')) {
				return null;
			}
			if (!$this->FILES) { 
				$this->FILES = new Stack($this->POD,'file',array('contentId'=>$this->get('id')),null,100,0);
				if (!$this->FILES->success()) { 
					return null;
				}
			}
			return $this->FILES;
		}
		
		function tags() {		
			if (!$this->get('id')) {
				return null;
			}
			if (!$this->TAGS) { 
				$this->TAGS = new Stack($this->POD,'tag',array('tr.contentId'=>$this->get('id')),null,100,0);
				if (!$this->TAGS->success()) { 
					return null;
				}
			}
			return $this->TAGS;
		}

		function isEditable() {		
			return $this->IS_EDITABLE;
		}
		function isFavorite() {		
			return $this->IS_FAVORITE;
		}

	
		function asArray() { 
		
			$data = parent::asArray();
			// remove some fields
	
			return $data;
			

		}

	
/*	Functions that load things */

		function save($strip_html = true) {

	// set up some options
		
			$this->success = false;
			$this->POD->tolog("content->save()");			

			if (!$this->POD->isAuthenticated()) { 
				$this->throwError("No current user! Can't save content!");
				return null;
			}
			
			if (!$this->isEditable()) { 
				$this->throwError("Access Denied");
				$this->error_code = 401;
				return null;
			}			


			if ($strip_html) {
				$this->set('body',$this->POD->sanitizeInput($this->get('body')));			
			}	
			
			$this->set('body',stripslashes($this->get('body')));
			$this->set('headline',stripslashes(strip_tags($this->get('headline'))));
			$this->set('link',stripslashes(strip_tags($this->get('link'))));

			if (!$this->saved()) { 
				$this->set('date','now()');
				$this->set('editDate','now()');
				$this->set('minutes','0');
				$this->set('changeDate','now()');
				$this->set('yes_votes','0');
				$this->set('no_votes','0');

			} else {
				$this->set('editDate','now()');
				$this->set('changeDate','now()');
			}			
			
			if ($this->get('privacy')=='') {
				$this->set('privacy','public');
			}
			
			// do this down here instead of at the top to catch cases where the headline is blank after stripping html
			if ($this->get('headline')=='') {
				$this->success = false;
				$this->throwError("Missing required fields");
				$this->error_code = 500;
				return null;
			}
			
			
			if (!$this->get('type')) { 
				$this->set('type','document');
			}

			if (!$this->get('status')) { 
				$this->set('status','new');
			}

			if ($this->get('createdBy') == '') {
				$this->set('createdBy',$this->POD->currentUser()->get('id'));
			}

			if ($this->get('userId') == '') {
				$this->set('userId',$this->get('createdBy'));
			}

			if (!$this->get('stub')) {
				$stub = $this->get('headline');			
				$stub = preg_replace("/\s+/","_",$stub);
				$stub = preg_replace("/[^a-zA-Z0-9_\-]/","",$stub);
				$stub = strtolower($stub);
			} else {
				$stub = $this->get('stub');
			}
			
			$newstub = $stub;

			// check and see if any content already use this stub.
			$stubcheck = $this->POD->getContent(array('stub'=>$stub));
			$counter = 2;
			while ($stubcheck->success() && $stubcheck->get('id')!=$this->get('id')) {
			
				$newstub = $stub . "_" . $counter++;
				$stubcheck = $this->POD->getContent(array('stub'=>$newstub));				
			}
			
			$this->set('stub',$newstub);

			parent::save();
			
			if (!$this->success()) { 
				$this->POD->cacheclear($this);
				return null;
			}					
			
			$this->stuffDoc();	
			
			$this->POD->cachestore($this);

			$this->POD->tolog("content->save() ADD WATCH");			
			$this->POD->currentUser()->addWatch($this);

			$this->success= true;
			$this->POD->tolog("content->save(): Content saved!");
		}
	
		function changeStatus($status) {
			if ($this->get('id') && $this->isEditable()) { 
				$this->set('status',$status);
				$status = mysql_real_escape_string($status);
				$sql = "UPDATE content SET status='$status', changeDate=NOW(),flagDate=NOW() where id=" . $this->get('id');
				$this->POD->tolog($sql,2);
				$result = mysql_query($sql,$this->POD->DATABASE);	
				$num = mysql_affected_rows($this->POD->DATABASE);	
				if ($num < 1 || !$result) {
					$this->success = false;
					$this->throwError("SQL Error: Content Update failed!");
					$this->error_code = 500;
					return null;
				} else {
					$this->success = true;
					$this->POD->cachestore($this);
					return $this;
				}
			} else {
					$this->success = false;
					$this->throwError("Status change failed: permission denied");
					$this->error_code = 500;
					return null;			
			}
		}

	
		function delete($force= null) {
			$this->success = false;
			if ($this->get('id')) {
				if ($this->isEditable() || $force) {
					
					$this->POD->cacheclear($this);
					
					$sql = "DELETE FROM content WHERE id=" . $this->get('id');
					$this->POD->tolog($sql,2);
					mysql_query($sql,$this->POD->DATABASE);
					
	
					$this->files()->reset();
					while ($file = $this->files()->getNext()) {
						$file->delete();
					}
	
					$sql = "DELETE FROM tagRef WHERE contentId=" . $this->get('id');
					$this->POD->tolog($sql,2);
					mysql_query($sql,$this->POD->DATABASE);
					
					$sql = "DELETE FROM comments WHERE contentId=" . $this->get('id');
					$this->POD->tolog($sql,2);
					mysql_query($sql,$this->POD->DATABASE);
			
			
					$sql = "DELETE FROM flags WHERE type='content' and itemId=" . $this->get('id');
					$this->POD->tolog($sql,2);
					mysql_query($sql,$this->POD->DATABASE);
				
					$sql = "UPDATE content SET parentId=null WHERE parentId=" . $this->get('id');
					$this->POD->tolog($sql,2);
					mysql_query($sql,$this->POD->DATABASE);
					
					$this->COMMENTS = null;
					$this->TAGS = null;
					$this->CHILDREN = null;
					$this->DATA = array();
					
					
					$this->success = true;
				}  else {
					$this->throwError("You do not have permission to delete this content.");
					$this->error_code=403;
				}
			} else {
				// hasn't been saved yet
				$this->throwError("No such content");
				$this->error_code = 404;
			}
		}


		function getContentById($did) {
			$this->success = null;

			if ($did != '' && preg_match("/\d+/",$did)) {
				$d = $this->POD->checkcache('Content','id',$did);
				if ($d) {
					$this->POD->tolog("content->getContentById(): USING CACHE");
					$this->DATA = $d;
				} else {
					$this->load('id',$did);
					if ($this->success()) { 
						$this->stuffDoc();
						$this->POD->cachestore($this);
					} else {
						return $this;
					}
				}
				$this->success = true;
				return $this;
			} else {
				$this->throwError("No content id specified");
				$this->error_code=500;
			}
		}
	
		
		function getContentByStub($stub) {
			$this->POD->tolog("content->getContentByStub($stub)");
			$d = $this->POD->checkcache('Content','stub',$stub);
			if ($d) {
				$this->POD->tolog("content->getContentByStub(): USING CACHE");
				$this->DATA = $d;
			} else {

				$this->load('stub',$stub);		
				if ($this->success()) { 
					$this->stuffDoc();		
					$this->POD->cachestore($this);
					$this->success = true;
				} 
			}

			return $this;
		}	

		
	
		
/*************************************************************************************	
* TAGS 																			
*************************************************************************************/


		function hasTag($tagvalue) {
			
			return $this->TAGS->contains('value',$tagvalue);		
		
		}

		function removeTag($tag) {
		
			$this->success = false;
			if ($this->get('id')) { 
				$t = new Tag($this->POD);
				$t->load('value',$tag);
				if ($t->success()) {
					$sql = "DELETE FROM tagRef WHERE contentId=" . $this->get('id') . " AND tagId=" . $t->get('id') . ';';
					$this->POD->tolog($sql,2);	
					mysql_query($sql,$this->POD->DATABASE);		
					$this->success = true;
					$this->tags()->fill();
					return $t;
				}else {
					$this->throwError("Tag not found");
					$this->error_code = 404;
				}		
			
			} else {
				$this->throwError("Content not saved yet!");
				$this->error_code = 500;
			}
			
			return null;
		
		}

		function addTag($tag) {
				
			$this->success = false;
			if ($this->get('id')) {
				$t = new Tag($this->POD);
				$t->load('value',$tag);
				if (!$t->success()) {
					$this->POD->tolog("content->addTag: Adding tag $tag");
					$t->set('value',$tag);
					$t->save();
				}
				
				$sql = "DELETE FROM tagRef WHERE contentId=" . $this->get('id') . " AND tagId=" . $t->get('id') . ';';
				$this->POD->tolog($sql,2);
				mysql_query($sql,$this->POD->DATABASE);		
				
				$sql = "INSERT INTO tagRef(contentId,tagId,type) VALUES (" . $this->get('id') . "," . $t->get('id') . ",'pub');";
				$this->POD->tolog($sql,2);
				mysql_query($sql,$this->POD->DATABASE);		
				
				$this->tags()->add($t);
				$this->success = true;
				return $t;
			} else {
				$this->throwError("Content not saved yet!");
				$this->error_code = 500;
				return null;
			}
		}


		function tagsFromString($string,$delimiter=' ') {
		
			$tags = explode($delimiter,$string);

			$this->tags()->reset();
			while ($tag = $this->tags()->getNext()) {
				$this->removeTag($tag->get('value'));
			}
	
			foreach ($tags as $tag) {
				if ($tag != '') {
					$this->addTag($tag);
				}
			}
		
		}
			
		function tagsAsString() {
			if ($this->tags()) { 
				return $this->tags()->implode(" ",'value');
			} else {
				return null;
			}	
		}			

/*************************************************************************************	
* COMMENTS 																			
*************************************************************************************/

	
		function markCommentsAsRead() {
		
			if (!$this->get('id')) {
			
				$this->throwError("Content not saved yet!");
				$this->error_code = 500;
				return;
			}

			if (!$this->POD->isAuthenticated()) { 
				$this->throwError("Access denied");
				$this->error_code = 401;
				return;
			}
			
			$this->POD->currentUser()->addWatch($this);	
				
		}
		
		
		function goToFirstUnreadComment() {
			$last = null;
			if ($this->get('lastCommentId')) { 
				$last = $this->get('lastCommentId');
			} else {
				if ($this->POD->isAuthenticated()) { 
					$last = $this->POD->currentUser()->isWatched($this);
				}
			}
			$this->comments()->reset();
			while ($this->comments()->peekAhead() && $this->comments()->peekAhead()->get('id') <= $last) {
				$this->comments()->getNext(); 
			}

		}
		

		function addComment($comment,$type=null) {
		
			$this->success= false;
			if (!$this->get('id')) {
			
				$this->throwError("Content not saved yet!");
				$this->error_code = 500;
				return;
			}
			
			if (!$this->POD->isAuthenticated()) { 
				$this->throwError("Access denied");
				$this->error_code = 401;
				return;
			}
			
			$newcomment = $this->POD->getComment();
	
			$newcomment->set('contentId',$this->get('id'));
			$newcomment->set('comment',$comment);
			$newcomment->set('type',$type);
			$newcomment->set('userId',$this->POD->currentUser()->get('id'));
			$newcomment->save();
	
			if ($newcomment->success()) {

				$sql = "UPDATE content SET commentDate=NOW(),changeDate=NOW() where id=" . $this->get('id');
				$this->POD->tolog($sql,2);
				$result = mysql_query($sql,$this->POD->DATABASE);
				if (!$result) {
					$this->throwError("SQL Error: commentDate update failed!");
					$this->error_code = 500;
				}
					
				$this->comments()->add($newcomment);
				$this->POD->currentUser()->addWatch($this);
				$this->success = true;
				return $newcomment;
			} else {
				$this->throwError($newcomment->error());
				$this->error_code = $newcomment->errorCode();
				return;				
			}
		}
	
/*************************************************************************************	
* Groups 																			
*************************************************************************************/		

	function group($field=null) { 
		if ($this->get('groupId') && !$this->GROUP) { 
			$this->GROUP = $this->POD->getGroup(array('id'=>$this->get('groupId')));		
		}
		if ($field != null) {
			return $this->GROUP->get($field);
		} else {
			return $this->GROUP;	
		}
	}


	// this is a special function that bypasses normal update security to allow a group owner or manager to change the group and privacy settings of a content.
	function setGroup($groupId) { 
		$this->success = false;
		if (!$this->get('id')) {
		
			$this->throwError("Content not saved yet!");
			$this->error_code = 500;
			return;
		}		
		if (!$this->POD->isAuthenticated()) { 
			$this->throwError("Access denied");
			$this->error_code = 401;
			return;
		}	
			
		if ($groupId == "" || !$groupId) { 
			$group = $this->POD->getGroup(array('id'=>$this->get('groupId')));	
		} else {
			$group = $this->POD->getGroup(array('id'=>$groupId));
		}	
		
		if (!$group->success()) { 
			$this->throwError($group->error());
			$this->error_code = $group->errorCode();
			return;		
		}		
		$membership = $group->isMember($this->POD->currentUser());
		if ($group->success()) { 
			if (!($membership == "owner" || $membership == "manager")) { 
				$this->throwError("Access denied: Insufficient Group Privileges");
				$this->error_code = 401;
				return;		
			}
		} else {
			$this->throwError("Couldn't check membership: " . $group->error());
			return;
		}			
		$this->set('groupId',$groupId);
		
		if ($groupId == '') { 
			$groupId = "NULL";
		} else {
			$groupId= "'" . mysql_real_escape_string($groupId) . "'";
		}
		$privacy = mysql_real_escape_string($this->get('privacy'));
		
		$sql = "UPDATE content SET groupId=$groupId, privacy='$privacy', changeDate=NOW() where id=" . $this->get('id');
		$this->POD->tolog($sql,2);
		$result = mysql_query($sql,$this->POD->DATABASE);	
		$num = mysql_affected_rows($this->POD->DATABASE);	
		if ($num < 1 || !$result) {
			$this->success = false;
			$this->throwError("SQL Error: Set group failed!");
			$this->error_code = 500;
			return null;
		} else {
			$this->success = true;
			$this->POD->cachestore($this);
			return $this;
		}

	}

/*************************************************************************************	
* VOTING 																			
*************************************************************************************/

		
		function vote($vote) {
			$this->success = false;
			if (strtolower($vote) == "y") {
				$vote = 1;
			} 
			if (strtolower($vote)=="n") {
				$vote = 0;
			}
			if (($vote != '1') && ($vote != '0')) {
				$this->error_code = 500;
				$this->throwError("Invalid vote!");
				return null;
			}

			if (!$this->get('id')) {
				$this->error_code = 500;
				$this->throwError("Content has not been saved");
				return null;
			}

			$this->addFlag('vote',$this->POD->currentUser(),$vote);
			if (!$this->success()) { 
				return false;
			} else {
				if ($vote == "1") {
					$this->VOTE = "Y";
				} else {
					$this->VOTE="N";
				}

				$this->getVotes();
			}
			return true;
		}
		
		function unvote() {
			$val = $this->removeFlag('vote',$this->POD->currentUser());		
			if (!$this->success()) { 
				return $false;
			} else {
				$this->getVotes();
			}
			return $this;	
		}		
	
/* Helper functions */


	
	
		function permalink($field="headline",$return = false) {

			$string = "<a href=\"" . $this->get('permalink') . "\" title=\"" . htmlentities($this->get('headline')) . "\">" . $this->get($field) . "</a>";
			if ($return) {
				return $string;
			} else {
				echo $string;
			}
		}
		
		

		
		function authorisFriendsWith($person) {
			return $this->author()->isFriendsWith($person);
		}
			


		
	
		function stuffDoc() {
						
			$this->POD->tolog("content->stuffDoc " . $this->get('id'));
			if ($this->get('minutes')!='') { 
				$this->set('timesince', $this->POD->timesince($this->get('minutes')));
			}
			
			
			$tot = $this->get('yes_votes') + $this->get('no_votes');
			
			if ($tot > 0) { 
				$this->set('yes_percent',intval(($this->get('yes_votes') / $tot) * 100));
				$this->set('no_percent',intval(($this->get('no_votes') / $tot) * 100));
			}	
			
			$this->loadMeta();
			$this->generatePermalink();
		
		
	
				
		}
	
	
		function getVotes() {
			
			$this->success = false;
			if (!$this->get('id')) {
				$this->error_code = 500;
				$this->throwError("Content has not been saved");
				$this->POD->tolog("content->getVotes FAILED!");

				return null;
			}

			$this->POD->tolog("content->getVotes for doc " . $this->get('id'));
			$yes_votes = new Stack($this->POD,'content',array('flag.name'=>'vote','flag.value'=>'1','flag.itemId'=>$this->get('id')));
			$no_votes = new Stack($this->POD,'content',array('flag.name'=>'vote','flag.value'=>'0','flag.itemId'=>$this->get('id')));
			$this->set('yes_votes',$yes_votes->totalCount());
			$this->set('no_votes',$no_votes->totalCount());
	
			$tot = $this->get('yes_votes') + $this->get('no_votes');
			
			if ($tot > 0) { 
				$this->set('yes_percent',intval(($this->get('yes_votes') / $tot) * 100));
				$this->set('no_percent',intval(($this->get('no_votes') / $tot) * 100));
			}	
	
	// can't save, because security model won't let non-owners update!		
	//		$this->save();
	
			$sql = "UPDATE content SET yes_votes=" . $this->get('yes_votes') . ",no_votes=" . $this->get('no_votes') . " WHERE id=" . $this->get('id');
			$this->POD->tolog($sql,2);
			$res = mysql_query($sql,$this->POD->DATABASE);
			$this->success = true;
		
		}
	
	
		function generatePermalink() {			

			$path = $this->POD->libOptions('default_document_path');
			if ($this->POD->libOptions($this->get('type') . '_document_path')) { 
		 		$path = $this->POD->libOptions($this->get('type') . '_document_path');
			}
			
			$this->set('permalink',$this->POD->siteRoot(false) . "/$path/" . $this->get('stub'));

			$path = $this->POD->libOptions('default_document_editpath');
			
			if ($this->POD->libOptions($this->get('type') . '_document_editpath')) { 
		 		$path = $this->POD->libOptions($this->get('type') . '_document_editpath');
			}
				
			$this->set('editpath',$this->POD->siteRoot(false) . "/$path");
			$this->set('editlink',$this->POD->siteRoot(false) . "/$path?id=" . $this->get('id'));

		}
		
		
		function shareStory($email,$message,$from) {
			
			list($subject,$body) = $this->POD->mailCreate("share",array(DOC_NAME=>$this->get('headline'),DOC_LINK=>$this->get('permalink'),MEMBER_NAME=>$from->get('nick'),MEMBER_PERMALINK=>$from->get('permalink'),MESSAGE=>$message));
			
			
			$headers = "From: " . $this->POD->libOptions('fromAddress') . "\r\n" . "X-Mailer: php";
		
			mail($email,$subject, $body, $headers);
		
		}


	
		function getTagIdByValue($value) {
			
			$t = new Tag($this->POD);
			$t->load('value',$value);
			if ($t->success()) {
				return $t->get('id');
			} else {
				$t->set('value',$value);
				$t->save();
				return $t->get('id');
			}
		}
		

/* Functions that output things */


		function render($template = 'output',$backup_path=null,$cachable=true) {
		
			return parent::renderObj($template,array('content'=>$this,'doc'=>$this),'content',$backup_path,$cachable);
	
		}
	
		function output($template = 'output',$backup_path=null,$cachable=true) {
		
			parent::output($template,array('content'=>$this,'doc'=>$this),'content',$backup_path,$cachable);
	
		}

		
	
	}
?>