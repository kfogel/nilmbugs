<? 
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* lib/News.php
* This file defines the News object
* Handles news feed/activity feeds
*
* Documentation for this object can be found here:
* http://peoplepods.net/readme/news-object
/**********************************************/	
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