<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* core_profiles/editprofile.php
* Handles requests to /editprofile
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/messaging
/**********************************************/

	include_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('debug'=>0,'lockdown'=>'login','authSecret'=>$_COOKIE['pp_auth']));
	if (!$POD->libOptions('enable_mediabugs_profiles')) { 
		header("Location: " . $POD->libOptions('serverRoot'));
		exit;
	}



	if ($_POST['email']) {
			$POD->currentUser()->set('nick',$_POST['nick']);
			$POD->currentUser()->set('email',$_POST['email']);
			$POD->currentUser()->save();
			if (!$POD->currentUser()->success()) {
				$msg = $POD->currentUser()->error();
			} else {
				$msg = "Your settings have been updated.  ";
				foreach ($_FILES as $filename=>$file) { 
					$POD->currentUser()->addFile($filename,$file);
					if (!$POD->currentUser()->success()) { 
						$msg .= 'An error occured while attaching your file: ' . $content->error();
					}
				}
				$POD->currentUser()->files()->fill();

				$POD->currentUser()->set('aboutme',$_POST['meta_aboutme']);
				$POD->currentUser()->set('homepage',$_POST['meta_homepage']);
			
				if ($_POST['meta_updates']) { 
					$POD->currentUser()->addMeta('updates',1);
				} else {
					$POD->currentUser()->removeMeta('updates');
				}

				if ($_POST['meta_newsletter']) { 
					$POD->currentUser()->addMeta('newsletter',1);
				} else {
					$POD->currentUser()->removeMeta('newsletter');
				}
				

			}
									
			$days = 15;			
			setcookie('pp_auth',$POD->currentUser()->get('authSecret'),time()+(86400 * $days),"/");
	}
		
	if ($_POST['password']) {
		
			$POD->currentUser()->set('password',$_POST['password']);
			$POD->currentUser()->save();
			
			if (!$POD->currentUser()->success()) {
				$msg = $POD->currentUser()->error();
			} else {
				$msg = "Your password has been changed.";
			}

			$days = 15;			
			setcookie('pp_auth',$POD->currentUser()->get('authSecret'),time()+(86400 * $days),"/");
		
	}


	$POD->header('Edit Profile');	
	if (isset($msg)) { ?>
		<div class="info">
			<? echo $msg; ?>
		</div>
  	<? }
	$POD->currentUser()->output('editprofile');
	$POD->footer(); ?>
