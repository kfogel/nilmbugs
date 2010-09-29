<? 
	include_once("../../PeoplePods.php");	
	$POD = new PeoplePod(array('lockdown'=>'adminUser','authSecret'=>$_COOKIE['pp_auth']));
	$POD->changeTheme('admin');

	$msg = '';
	if ($_POST['command']=='Delete') { 
	
		$count = 0;	
		foreach ($_POST as $key=>$val) { 
			if (preg_match("/^content_(\d+)/",$key)) { 
				$doc = $POD->getContent(array('id'=>$val));
				$doc->delete();
				if (!$doc->success()) {
					$msg .= $doc->error() . "<br />";
				}
				$count++;
			}
		}
		$msg .= "Deleted $count " . $POD->pluralize($count,'piece','pieces') . " of content.";

	} else if ($_POST['command']=='Not Spam') { 
	
		$count = 0;	
		foreach ($_POST as $key=>$val) { 
			if (preg_match("/^content_(\d+)/",$key)) { 
				$doc = $POD->getContent(array('id'=>$val));
				$doc->notSpam();
				if (!$doc->success()) {
					$msg .= $doc->error() . "<br />";
				}
				$count++;
			}
		}
		$msg .= "Marked $count " . $POD->pluralize($count,'piece','pieces') . " of content as not spam";

	}

	header("Location: search.php?message=$msg");
	
	

?>