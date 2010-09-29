<? 
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* lib/Comment.php
* This file defines the Comment object
*
* Documentation for this object can be found here:
* http://peoplepods.net/readme/comment-object
/**********************************************/

	require_once("Obj.php");
	class Comment extends Obj {
	
		function Comment($POD,$PARAMETERS=null) {
	
			parent::Obj($POD,'comment');
			if (!$this->success()) {
				return null;
			}
			
			if (isset($PARAMETERS['id']) && (sizeof($PARAMETERS)==1)) {
				$this->load('id',$PARAMETERS['id']);
				$this->loadMeta();
				return $this;
			} else if ($PARAMETERS) {
				// create based on parameters
				foreach ($PARAMETERS as $key=>$value) {
					if ($key != 'POD') {
						$this->set($key,$value);
					}
				}
			}
			$this->loadMeta();

			$this->success = true;
			return $this;
		}
		
		function delete() {
			$this->success = false;
			if (!$this->POD->isAuthenticated()) { 
				$this->error_code = 401;
				$this->throwError("Permission Denied");
				return null;
			}
			if (!$this->get('id')) {
				$this->error_code = 500;
				$this->throwError("Comment not saved yet.");			
				return null;
			}
			if (($this->get('userId') != $this->POD->currentUser()->get('id')) && ($this->parent('userId') != $this->POD->currentUser()->get('id')) && (!$this->POD->currentUser()->get('adminUser'))) { 
			// the only people who can delete a comment are the commenter, the owner of the content commented upon, or an admin user
			// if this person is none of those people, fail!
				$this->error_code = 401;
				$this->throwError("Permission Denied");
				return null;
			}
			
			$sql = "DELETE FROM flags WHERE type='comment' and itemId=" . $this->get('id');
			$this->POD->tolog($sql,2);
			mysql_query($sql,$this->POD->DATABASE);

			
			$sql = "DELETE FROM comments WHERE id=" . $this->get('id');
			$this->POD->tolog($sql,2);
			mysql_query($sql);
			
			$this->DATA = array();
			$this->success = true;
			return true;
		
		}
		
		
/*********************************************************************************************
* Accessors
*********************************************************************************************/
	
		
		function save() {
			$this->success = false;

			if (!$this->get('contentId')) {
				$this->throwError("Could not save comment. Required field contentId missing.");
				$this->error_code = 500;
				return;
			}
			if (!$this->get('comment')) {
				$this->throwError("Could not save comment. Required field comment missing.");
				$this->error_code = 500;
				return;
			}
			if (!$this->get('userId')) {
				$this->throwError("Could not save comment. Required field userId missing.");
				$this->error_code = 500;
				return;
			}

			// strip everything but basic tags out of the comment field.
			$this->set('comment',strip_tags(stripslashes($this->get('comment')),'<a><b><i><br>'));
			
			if (!$this->saved()) { 
				$this->set('date','now()');
			}

			parent::save();			

			return $this;
			
		}

/* Functions that output things */

		function render($template = 'comment',$backup_path=null) {
		
			return parent::renderObj($template,array('comment'=>$this),'content',$backup_path);
	
		}
	
		function output($template = 'comment',$backup_path=null) {
		
			parent::output($template,array('comment'=>$this),'content',$backup_path);
	
		}
	
		
	}
	
?>