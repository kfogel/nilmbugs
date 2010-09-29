<? 
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* lib/Activity.php
* This file defines the Activity object
* Handles news feed/activity feeds
*
* Documentation for this object can be found here:
* http://peoplepods.net/readme/activity-object
/**********************************************/	

	
	class Activity extends Obj { 
	
		protected $CONTENT;
		protected $RECIPIENT;
		protected $GROUP;
	
		function Activity($POD,$PARAMETERS=null) { 
			parent::Obj($POD,'activity');
			if (!$this->success()) {
				return $this;
			}
		
			// load item by id or accept params
			if (isset($PARAMETERS['gid']) && sizeof($PARAMETERS)==1) { 
				$this->load('gid',$PARAMETERS['gid']);
			} else if (isset($PARAMETERS['id']) && sizeof($PARAMETERS)==1) {
				$this->load('id',$PARAMETERS['id']);
			} else if ($PARAMETERS) { 
				foreach ($PARAMETERS as $key=>$val) {
					$this->set($key,$val);
				}
			}
			
			return $this;
		
		}
	
		function output() { 

//				$item['message'] = preg_replace("/\%person\.(.*?)\%/e",'$u->permalink("\\1",true)',$item['message']);
		
		
			$message = $this->message;
			$message = preg_replace("/\@type/",$this->type,$message);
			$message = preg_replace("/\@timesince/",$this->POD->timesince($this->minutes),$message);

			$message = preg_replace("/\@count/",$this->count,$message);	
			$message = preg_replace("/\@target\.(\w+)/e",'$this->unbundle("\\1");',$message);	
			$message = preg_replace("/\@user\.(\w+)/e",'$this->author()->permalink("\\1",true);',$message);	
			
			echo "<p>{$this->id}. " . $message . "</p>";
		
		
		}
	
		// publish a simple news item
		// $type is an arbitrary short text description of this message
		// $message is the singular message "Ben added a comment"
		// $bundle_message is the plural message "Ben added a comment to X, Y and Z" 
		// $data is an array that will hold a reference to the content, user or group acted upon
		// $gid is an optional parameter that defines this action as a unique and non-repeatable action
		// (for example, Ben added Katie as a friend.  we don't want that to shop up multiple times!)
		function publish($userId,$type,$message,$bundle_message=null,$target = null,$target_alert=null,$gid=null) {
		
			if ($gid) { 	
				$act = $this->POD->getActivity(array('gid'=>$gid));
				if ($act->success()) { 
					return $act;
				}
			}

			$this->POD->tolog("Publishing activity for $userId - $type - $message - $bundle_message");


			$published = false;
			
			if ($target && $bundle_message) { 
				// if there is a target user, content or group,
				// we need to see if it gets bundled
				
				// 1. same action on same target, increases the count
				// 2. same action on different targets should bundle


				// 1. same action on same target	
				$bundle = $this->POD->getActivityStream(array(
					'type'=>$type,
					'userId'=>$userId,
					'date:gt'=>(time()-(24*60*60)),
					'target'=>$target->id,
					'targetType'=>$target->TYPE,
				));
				if ($bundle->count() > 0) { 
					// increase count.
					
					$this->POD->tolog("Bundling identical actions.");
					$activity = $bundle->getNext();
					$activity->count = ($activity->count) ? $activity->count+1 : 2;
					$activity->message = $bundle_message;
					$activity->date = 'now()';
					$activity->save();
					$published = true;
				} else { 
				
					// 2. same action, different target	
					$bundle = $this->POD->getActivityStream(array(
						'type'=>$type,
						'userId'=>$userId,
						'date:gt'=>(time()-(24*60*60)),
						'target:!='=>$target->id,
						'targetType'=>$target->TYPE,
					));				
					if ($bundle->count() > 0) { 
						// bundle!!
						$this->POD->tolog("Bundling same action on different targets.");

						$activity = $bundle->getNext();
						$activity->userId = $userId;
						$activity->date = 'now()';
						
						$tids = explode(",",$activity->target);
						foreach ($tids as $tid) { 
							$targets[$tid] = $tid;
						}
						$targets[$target->id] = $target->id;
						// FIX THIS
						// should not include duplicate ids
						$activity->target = implode(",",$targets);
						$activity->message = $bundle_message;
						$activity->save();
						$published = true;
					} 
				
				}
			

			}
			
			
			if (!$published) {
				// slap this in the db!
				
				$this->POD->tolog("Publishing new activity item");
				$this->type = $type;
				$this->userId = $userId;
				$this->message = $message;
				if ($gid) {
					$this->gid = $gid;
				}
				if ($target) { 
					$this->targetType = $target->TYPE;
					$this->target = $target->id;
				}
				$this->save();				
			}
	
	
			// if I just did something to someone else
			// and $target_alert is set, send an alert to the user 
			// otherwise, they may not see the message because their inclusion may be obfuscated by bundling
			if ($target && $target_alert && $target->TYPE == 'user') { 
				$alert = $this->POD->getActivity();
				$alert->userId=$userId;
				$alert->type = 'alert';
				$alert->target = $target->id;
				$alert->targetType = 'user';
				$alert->gid = "$userId-$type-{$target->id}";
				$alert->message = $target_alert;
				$alert->save();
			
			}	
	
		}
		
		function unbundle($field) {
			

			if ($this->targetType=="user") { 
				$obj = "Person";
			} else if ($this->targetType=="content") {
				$obj = "Content";
			} else if ($this->targetType=="group") { 
				$obj = "Group";
			}
			
			$targets = array();
					
			if (preg_match("/\,/",$this->target)) { 
				$tids = explode(",",$this->target);
				foreach ($tids as $target) { 
					$targets[] = new $obj($this->POD,array('id'=>$target));					
				}
			} else {
				$targets[] = new $obj($this->POD,array('id'=>$this->target));
			}
			
			$string = '';
			$total = sizeof($targets);
			$count = 1;
			foreach ($targets as $target) { 
				if ($count==$total && $total > 1) {
					$string .= " and ";
				}
				if ($target->TYPE=="user" && $this->POD->isAuthenticated() && $target->id==$this->POD->currentUser()->get('id')) {
					$string .= "you";
				} else {
					$string .= $target->permalink($field,true);
				}
				if ($total > 2) { 
					$string .=", ";
				}
				$count++;
			}
			return $string;		
		
		}
		
		function delete() {
		
		
		}
		
		// handle database stuff
		function save() {

			$this->success = false;
			$this->POD->tolog("activity->save()");			

			if (!$this->POD->isAuthenticated()) { 
				$this->throwError("No current user! Can't save activity!");
				return null;
			}
			if (!$this->userId) { 
				$this->throwError("Missing required field 'userId'! Can't save activity!");
			}

		
			if (!$this->message) { 
				$this->throwError("Missing required field 'message'! Can't save activity!");
			}
		
			if (!$this->saved()) { 
				$this->set('date','now()');
			}
			
			parent::save();		
		
		}
	
	}


	class News {
		public $FEED = array();
		public $COUNT = 0;
		var $POD;
		public $success = null;
		public $error = null;
		public $error_code = null;
		var $userId = null;
		var $docId = null;
		var $userIds = null;
		var $docIds = null;
		
		function News($PARAMETERS) {
				$this->POD = $PARAMETERS['POD'];
				
				if ($PARAMETERS['userId']) {
					$this->userId = $PARAMETERS['userId'];
					$success = true;
				}
				if ($PARAMETERS['contentId']) { 
					$this->docId = $PARAMETERS['contentId'];
					$success = true;				
				}
				if ($PARAMETERS['userIds']) {
					$this->userIds = $PARAMETERS['userIds'];
					$success = true;
				}
				if ($PARAMETERS['contentIds']) { 
					$this->docIds = $PARAMETERS['contentIds'];
					$success = true;				
				}
				if ($PARAMETERS['docId']) { 
					$this->docId = $PARAMETERS['docId'];
					$success = true;
				}
				return $this->success;
		}
		
		function format($item) { 
		
			if ($item['userId']) {
				$u = $this->POD->getPerson(array('id'=>$item['userId']));
			}
			if ($item['contentId']) {
				$d = $this->POD->getContent(array('id'=>$item['contentId']));
			}
		
			
			if ($u) { 
				if ($this->POD->isAuthenticated() && $u->get('id') == $this->POD->currentUser()->get('id')) { 
					$item['message'] = preg_replace("/\%person\.nick\%/e",'You',$item['message']);
				
				}
				$item['message'] = preg_replace("/\%person\.(.*?)\%/e",'$u->permalink("\\1",true)',$item['message']);
			}
			$item['message'] = preg_replace("/%link%/",$item['link'],$item['message']);

			$item['message'] = preg_replace("/%counter%/",$item['counter'],$item['message']);
			
			if ($item['bundleType']=='user') { 
				$item['message'] = preg_replace("/\%document\.(.*?)\%/e",'$this->bundle("\\1",$item[\'value\'])',$item['message']);			
			}
			
			
			if ($d) { 
				$item['message'] = preg_replace("/\%document\.(.*?)\%/e",'$d->permalink("\\1",true)',$item['message']);
			}		
			return $item['message'];
		}
		
		function bundle($field,$idlist) { 
		

			$ids = explode(",",$idlist);
			$res = array();
			$hash = array();
			foreach ($ids as $id) { 
				if ($hash[$id] == 1) { continue; }	
				$hash[$id] = 1;	
				$doc = $this->POD->getContent(array('id'=>$id));
				if ($doc->success()) {
					array_push($res,$doc->permalink($field,true));
				} else {
					#echo "Failed to load";
				}
			}
			
			if (sizeof($res) > 1) {
				$last = array_pop($res); 
				$string = implode(", ",$res);
				$string .= " and $last";
			}
			return $string;
			
			
		}
		function create($PARAMS = null) { 
		
			$this->success = null;
			
			if ($PARAMS['message'] && $PARAMS['link'] && $PARAMS['type']  && ($this->userId || $this->docId)) { 
				$m = mysql_real_escape_string($PARAMS['message']);
				if ($PARAMS['multi_message']) { 
					$mm = mysql_real_escape_string($PARAMS['multi_message']);
				} else {
					$PARAMS['bundle'] = "none";
				}
				$l = mysql_real_escape_string($PARAMS['link']);
				$t = mysql_real_escape_string($PARAMS['type']);
				if ($PARAMS['uid']) {
					$x = mysql_real_escape_string($PARAMS['uid']);
				} else {
					$x = "null";					
				}

				if ($PARAMS['bundle'] ) {
					$bundle = $PARAMS['bundle'];
				} else {
					$bundle = "auto";
				}

				$u = mysql_real_escape_string($this->userId);
				$d = mysql_real_escape_string($this->docId);
												
				# is there a news item with the same user, type and document within the last 24 hours
				# if so, instead of adding a new one, we should swap it to the group message and bump the message date.
				
				$done = false;


				$this->delete($PARAMS['type'],$PARAMS['uid']);
				
				if ($u && $d) { 

						if ($bundle == "document" || $bundle=="auto") { 
						$sql = "SELECT * FROM news WHERE type='$t' and userId=$u and contentId=$d and (bundleType='document' OR bundleType is null) and date >= DATE_SUB(NOW(),INTERVAL 24 HOUR);";
						#echo $sql . "<br />";
						$res = mysql_query($sql,$this->POD->DATABASE);
						if ($res) {
							while ($news = mysql_fetch_assoc($res)) { 						
								# this user did the same action on the same document, so we increment the counter and set the message to multi, but don't change anything else.
								# ben left 15 comments on Foo
								# 
								$counter = $news['counter'];
								if ($counter == '') { $counter = 0; }
								$counter++;
								$sql = "UPDATE news SET message='$mm',counter=$counter,bundleType='document',date=now() WHERE id=" . $news['id'];
								#echo $sql . "<br />";
		
								mysql_query($sql,$this->POD->DATABASE);
								$done = true;
							}
						}
						}
						
				
						if ($bundle=="user" || $bundle=="auto") {
							if (!$done) { 		
								$sql = "SELECT * FROM news WHERE type='$t' and userId=$u and (bundleType='user' OR bundleType is null) and date >= DATE_SUB(NOW(),INTERVAL 24 HOUR);";
								#echo "$sql<br />";
								$res = mysql_query($sql,$this->POD->DATABASE);
								if ($res) {
									while ($news = mysql_fetch_assoc($res)) { 					
										# this user did the same action to different documents, so we increment the counter, set the message to multi, and add this document to the document list in values	
										# ben voted on a, b, and c
										# ben added a, b, and c as friends
										# ben left comments on a, b, and c
										
										$values = explode(",",$news['value']);
										if (sizeof($values)==0) {
											array_push($values,$news['contentId']);
										}
				
										array_push($values,$d);
										$counter = sizeof($values);
										$sql = "UPDATE news SET message='$mm',counter=$counter,value='" . implode(",",$values) . "',bundleType='user',date=now() WHERE id=" . $news['id'];
										#echo $sql . "<br />";		
										mysql_query($sql,$this->POD->DATABASE);
										$done = true;
									}
								}
							}
						}				
				}
							
				if (!$done) { 
					if (!$u) { $u = "null"; }
					if (!$d) { $d = "null"; }
	
					$sql = "INSERT INTO news (userId,contentId,message,link,type,counter,value,uid,date) VALUES ($u,$d,'$m','$l','$t',1,$d,$x,NOW());";
		     		#echo $sql . "<br />";

					$res = mysql_query($sql,$this->POD->DATABASE);
					$this->success = true;
				}
				
												
			} else {
				return false;
			}		
		}
	
		function load($TYPES = null,$andor = "OR",$exclude_self=true) {
			$offset = 0;
			$count = 5;
			$queries = array();
			
			$this->FEED = array();
			
			
			foreach ($TYPES as $type => $params) { 
			
				$query = array();
				if ($params['userId']) {
					array_push($query,"userId=" . $params['userId']);
				} 
				if ($params['docId']) {
					array_push($query,"contentId=".$params['docId']);
				} 
				if ($params['userIds']) {
					array_push($query,"userId in (".$params['userIds'].")");
				} 
				if ($params['docIds']) {
					array_push($query,"contentId in (".$params['docIds'].")");
				} 
				
				$a = $andor;
				
				if ($params['andor']) {
					$a = $params['andor'];
				}
				
				if ($exclude_self && $this->POD->isAuthenticated()) { 
					$ex = " AND (userId is null OR userId != " . $this->POD->currentUser()->get('id') . ") ";
				}
					
				$sql = "(SELECT * FROM news WHERE type='$type' AND (" . implode(" $a ",$query) . ") $ex ORDER BY date DESC)";
				array_push($queries,$sql);
				
			}
			

			if ($TYPES['offset']) {
				$offset = $TYPES['offset'];
			} 
			if ($TYPES['count']) {
				$count = $TYPES['count'];
			} 
			

			if (!$TYPES) { 
				$sql = "(SELECT * FROM news ORDER BY date DESC LIMIT $offset,$count)";
				array_push($queries,$sql);
							
			}

			$sql = implode(" UNION ",$queries) . "ORDER BY date DESC LIMIT $offset,$count";
		
			// echo $sql . "<br />";
			
				
			$res = mysql_query($sql,$this->POD->DATABASE);
			$this->COUNT = mysql_num_rows($res);
			if ($this->COUNT > 0) { 
				while ($row = mysql_fetch_assoc($res)) { 
					array_push($this->FEED,$row);
				}	
			
				$this->success = true;
			} else {
				$this->success = false;
			}	
		}
				
		function delete($type,$uid = null) {
		
			$query = array();
			$type = mysql_real_escape_string($type);
			array_push($query,"type='$type'");

			if ($this->userId) {
				array_push($query,"userId=". $this->userId);
			}
			if ($this->docId) {
				array_push($query,"contentId=". $this->docId);
			}
			if ($uid) {
				$uid = mysql_real_escape_string($uid);
				array_push($query,"uid='$uid'");
			}
			
			$sql = "DELETE FROM news WHERE bundleType is null AND " . implode(" AND ",$query);
			$res = mysql_query($sql,$this->POD->DATABASE);
			
		}

	}
1;
?>