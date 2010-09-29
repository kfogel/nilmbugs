<?


/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* lib/Messages.php
* On-site messaging, including email-style and im-style messages
* Getting and putting messages
* Inbox management
*
* Documentation for this object can be found here:
* http://peoplepods.net/readme/messaging
/**********************************************/



require_once("Stack.php");

	Class Inbox extends Stack {

		protected $UNREAD_COUNT = 0;
			
		function Inbox($POD,$count = 20, $offset = 0) { 
		
			$this->POD = $POD;
			
			if (!$this->POD) { return false; }
			if (!$this->POD->isAuthenticated()) { return false; }


			// get unread count.
			$sql = "SELECT count(1) as count FROM messages WHERE userId=" . $this->POD->currentUser()->get('id') . " and status='new';";
			$this->POD->tolog($sql,2);
			$res = mysql_query($sql,$this->POD->DATABASE);
			if ($ur = mysql_fetch_assoc($res)) {
				$this->UNREAD_COUNT = $ur['count'];
			}
			mysql_free_result($res);

			
			$conditions = array();
			$conditions['userId'] = $this->POD->currentUser()->get('id');
			$sort = 'GROUP by friendId ORDER BY max(date) DESC';
			$tables = 'FROM messages m';
			$select = 'SELECT m.friendId as id, m.userId as ownerId,m.friendId,max(m.date) as latestMessage,(TIME_TO_SEC(TIMEDIFF(NOW(),max(date))) / 60) as minutes';
		
			parent::Stack($POD,'threads',$conditions,$sort,$count,$offset,$tables,$select);
			return $this;

			
		}

		function unreadCount() { 
			return $this->UNREAD_COUNT;
	
		}
	
		function newThread($friendId) { 
			return new Thread($this->POD,array('id'=>$friendId,'ownerId'=>$this->POD->currentUser()->get('id'),'friendId'=>$friendId));
		}
	

	
	
	}


	Class Thread extends Obj {
		
		public $MESSAGES;
		public $RECIPIENT;
		protected $UNREAD_COUNT = 0;
		
		function Thread($POD,$threadInfo=null) {

				
			parent::Obj($POD,'thread');
			if (!$this->success()) {
				return $this;
			}	 		

			if (isset($threadInfo)) { 
				foreach ($threadInfo as $key => $value) {
					$this->set($key,$value);
				}
			
				$this->RECIPIENT = $this->POD->getPerson(array('id'=>$this->get('friendId')));
	
					
				// get unread count.
				$sql = "SELECT count(1) as count FROM messages WHERE userId=" . $this->get('ownerId') ." AND friendId=" . $this->get('friendId') . " and status='new';";
				$this->POD->tolog($sql,2);
				$res = mysql_query($sql,$this->POD->DATABASE);
				if ($ur = mysql_fetch_assoc($res)) {
					$this->UNREAD_COUNT = $ur['count'];
				}
				mysql_free_result($res);
				
				$this->set('permalink',$this->POD->siteRoot(false) . $this->POD->libOptions('messagePath') . "/" . $this->RECIPIENT->get('safe_nick'));		
				$this->MESSAGES = new Stack($this->POD,'messages',array('userId'=>$this->get('ownerId'),'friendId'=>$this->get('friendId')),null,1000);			
			}
			$this->success = true;
			return $this;			
					
		}
		
		
		function messages() { 
		
			return $this->MESSAGES;
			
		}
		
		function recipient() {
		
			return $this->RECIPIENT;
			
		}
		function unreadCount() {
			return $this->UNREAD_COUNT;
		}
	
		function markAsRead() {
		
			$this->MESSAGES->reset();
			while ($message = $this->MESSAGES->getNext()) {
				$message->set('status','read');
				$message->save();
			}
			$this->UNREAD_COUNT = 0;
			
			
		}




		function render($template = 'thread',$backup_path=null) {
		
			return parent::renderObj($template,array('thread'=>$this),'messages',$backup_path);
	
		}
	
		function output($template = 'thread',$backup_path=null) {
		
			parent::output($template,array('thread'=>$this),'messages',$backup_path);
	
		}


		function reply($message) {
			$this->success = null;
			
			$msg = new Message($this->POD,array('toId'=>$this->RECIPIENT->get('id'),'message'=>$message));
			$msg->save();
			if ($msg->success()) { 
				$this->MESSAGES->exists();
				$this->MESSAGES->add($msg);
				$this->success = true;
				return $msg;		
			} else {
				$this->throwError($msg->error());
				$this->error_code = $msg->errorCode();
				return null;
			}	
		}
	
		function clear() {
			$this->success = null;
			while ($message = $this->MESSAGES->getNext()) { 
				$message->delete();
				if (!$message->success()) {
					$this->throwError($message->error());
					$this->error_code = $message->errorCode();
					return null;
				}
			}
			$this->MESSAGES->fill();
			$this->success = true;
		}
	
	
	}
	
	
	 Class Message extends Obj {
	 	public $FROM;
	 	public $TO;
	 
	 	function Message($POD,$PARAMETERS=null) {
			parent::Obj($POD,'message');
			if (!$this->success()) {
				return $this;
			}
			$this->POD->tolog("New Message");
		//	var_dump($this->POD);
			
			if (isset($PARAMETERS['id']) && (sizeof($PARAMETERS)==2)) { 
				// load by ID
				$this->load('id',$PARAMETERS['id']);							
			} else if ($PARAMETERS) {
				foreach ($PARAMETERS as $key=>$value) {
					if ($key != 'POD') {
						$this->set($key,$value);
					}
				}
			}
			
			if ($this->get('id')) {
				$this->FROM = $this->POD->getPerson(array('id'=>$this->get('fromId')));
				$this->TO = $this->POD->getPerson(array('id'=>$this->get('toId')));
			}
				
			$this->success = true;
			return $this;
	 	
	 	}
	 	
	 	function from() { 
	 		return $this->FROM;
	 	}
	 	
	 	function to() {
	 		return $this->TO;
	 	}
	 	
	 	
	 	function render($template = 'message',$backup_path=null) {
		
			return parent::render($template,array('message'=>$this),'messages',$backup_path);
	
		}
	
		function output($template = 'message',$backup_path=null) {
		
			parent::output($template,array('message'=>$this),'messages',$backup_path);
	
		}
	
	 	function save() {
		
			$this->success = false;
			if (!$this->POD->isAuthenticated()) {
				$this->throwError("Access Denied");
				$this->error_code = 401;
				return null;
			}
			
			if ($this->get('message') == "" || $this->get('toId') == "") {
				$this->throwError("Fields missing!");
				$this->error_code = 500;
				return null;
			}
		
			$this->set('message',strip_tags($this->get('message')));
			
			if (!$this->saved()) { 
				
				$this->set('fromId',$this->POD->currentUser()->get('id'));
			
						
				// for messages, we need to insert two near duplicate rows,
				// one for the sender and one for the recipient
				// we can do this by just swapping the values.
				
				// first create the recipient
	
				$this->set('userId',$this->get('toId'));
				$this->set('friendId',$this->get('fromId'));
				$this->set('status','new');
				$this->set('date','now()');

				parent::save();
				
				// now create the sender version
				
				$this->set('userId',$this->get('fromId'));
				$this->set('friendId',$this->get('toId'));

				$this->set('id',null);

				parent::save();
				
				$this->FROM = $this->POD->getPerson(array('id'=>$this->get('fromId')));
				$this->TO = $this->POD->getPerson(array('id'=>$this->get('toId')));

				if ($this->POD->libOptions('contactEmail')) { 
				 	$this->FROM->sendEmail("contact",array('to'=>$this->TO->get('email'),'message'=>$this->get('message')));
				}
			 	
			} else {
			
				parent::save();			
			}	 	

	 		return $this;
	 	
	 	}
	 
	 
	 	function delete() {
	 	
	 		$this->success = false;
			if ($this->get('id')) {
				if (!$this->POD->isAuthenticated()) { 
					$this->throwError("Access denied");
					$this->error_code = 501;
					return null;
				}
				if (!$this->get('userId') == $this->POD->currentUser()->get('id')) { 
					$this->throwError("Access denied");
					$this->error_code = 501;
					return null;
				}
				
				$sql = "DELETE FROM messages WHERE id=" . $this->get('id');
				$this->POD->tolog($sql,2);
				$res = mysql_query($sql);
				$this->success = true;
				$this->DATA = array();
				return $this;
	 		} else {
				// hasn't been saved yet
				$this->throwError("No such message");
				$this->error_code = 404;
				return null;
			}	
	 	
	 	
	 	}
	 
	 
	 
	 }



?>