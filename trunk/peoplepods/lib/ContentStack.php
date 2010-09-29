<?
/* AS OF VERSION 0.668 THIS FILE IS DEPRECATED!!! */
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* lib/ContentStack.php
* This file defines a special subclass of Stack for content objects
*
* Documentation for this object can be found here:
* http://peoplepods.net/readme/stacks
/**********************************************/
require_once("Stack.php");
require_once("Content.php");

class ContentStack extends Stack {
	private $THISDOC;
	
	function ContentStack($POD,$PARAMETERS=null,$sort=null,$count=20,$offset=0) {
	
		$this->POD = $POD;
		
		$conditions = array();
		$from = array("FROM content d");

		if ($sort == "random") {
			$sort = "RAND();";
		}	
		
		foreach ($PARAMETERS as $field => $value) {
			/* handle some special fields */
			if ($field =="lockdown" && $value=="public") {
				$conditions['d.groupId']='null';
			} else if ($field=="type" || $field=="userId" || $field=="parentId" || $field=="status" || $field=="date") {
				// disambiguate common field names
				$conditions['d.' . $field]=$value;
			} else if ($field == 'startDate') {
				$conditions['d.date:gt']=$value;
			} else if ($field == 'endDate') {
				$conditions['d.date:lt']=$value;
			} else if ($field == 'exclude') {			
				$conditions['d.id:not'] = $value;					
			} else if ($field == 'favoriteOf') {
	/* handle some joins so we can get docs based on user actions */
//				array_push($from,"inner join stars s on d.id=s.contentId");
				$conditions['flag.userId'] = $value;
				$conditions['flag.name'] = "favorite";
			} else if ($field =='votedYes') {
				$conditions['flag.userId'] = $value;
				$conditions['flag.name'] = "vote";
				$conditions['flag.value']  = '1';
			} else if ($field == 'votedNo') {
				$conditions['flag.userId'] = $value;
				$conditions['flag.name'] = "vote";
				$conditions['flag.value']  = '0';
			} else if ($field == 'votedOn') {
				$conditions['flag.userId'] = $value;
				$conditions['flag.name'] = "vote";
			} else if ($field == 'tag') {
				array_push($from,"inner join tagRef t on d.id=t.contentId");
				if (is_array($value)) { 
				
					$tids = array();
					foreach ($value as $tag) { 
						$t = $this->getTagIdByValue($tag);
						array_push($tids,$t);
					} 
					$conditions['t.tagId'] = $tids;
				} else {
					$t = $this->getTagIdByValue($value);
					$conditions['t.tagId'] = $t;
				}
			} else {
				$conditions[$field] = $value;
			}
			
		}
	
		$tables = implode(" ",$from);

		parent::Stack($POD,'content',$conditions,$sort,$count,$offset,$tables);
		return $this;
	}


	function getNextDoc() {
		
		$this->THISDOC = $this->getNext();
		return $this->THISDOC;
	}

	
	function getTagIdByValue($value) {
		
		$pv = mysql_real_escape_string(stripslashes($value));
		$sql = "SELECT id FROM tags WHERE value='$pv';";
		$this->POD->tolog($sql,2);
		$results = mysql_query($sql,$this->POD->DATABASE);
		$num = mysql_num_rows($results);
		if ($num > 0) {
			$tag = mysql_fetch_assoc($results);
			return $tag['id'];
		} else {
			$sql = "INSERT INTO tags (value) VALUES ('$pv');";
			$this->POD->tolog($sql,2);
			$results = mysql_query($sql,$this->POD->DATABASE);	
			return mysql_insert_id($this->POD->DATABASE);
		}
	}
	
}

	



?>