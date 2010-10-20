<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/people/dashboard.php
* Used by the dashboard module to create homepage of the site for members
* Displays a list of content the current user has created,
* and content from the user's friends and groups
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/person-object
/**********************************************/
?>

<?	


	$offset = 0;	
	if (isset($_GET['offset'])) {
		$offset = $_GET['offset'];
	}

	$interesting = $POD->interestingBugs(5,$offset);
	// load bug types
	$bug_types = $POD->bugTypes();


	// load welcome message
	if ($POD->isAuthenticated()) { 
		$welcome_message = $POD->getContent(array('stub'=>'welcome-message-loggedin'));	
	} else { 
		$welcome_message = $POD->getContent(array('stub'=>'welcome-message'));
	}

	if (!$interesting->success()) { 
		$msg =  $interesting->error();
	}
?>


	<div id="welcome_block">
		<? $welcome_message->output('interface_text'); ?>
		<div id="homepage_submit">
			<a href="<? $POD->siteRoot(); ?>/bugs/edit" class="button with_right_margin">Report a Bug Now</a>
			<div class="clearer"></div>
		</div>
		<div class="clearer"></div>	
	</div>
	<div class="column_8">
		<?
			if ($user->get('verificationKey')) { ?>
				<div id="welcome_message">
					
					<p><strong>Welcome to <? $POD->siteName(); ?>!</strong>.  We are so glad you joined us.</p>
					<p>
						However, before you're allowed to post anything or leave comments, we need to <a href="<? $POD->siteRoot(); ?>/verify">verify your email address</a>.
						This lets us make sure that you aren't a spambot.
						You should already have the verification email in your inbox!
					</p>
                                        <p><em>(If you don't see it, try searching in your spam folder.  The email contains the phrase "bugs.resource.org".)</em>
                                        </p>
					<p><a href="<? $POD->siteRoot(); ?>/verify">Verify My Account</a></p>
				
				</div>		
			<? } ?>		
		<? if (isset($msg)) { ?>	
			<div class="info">
				<? echo $msg; ?>
			</div>
		<? } ?>
		
		
	
		<!-- this is where new posts from friends and groups show up -->
			<? 
					$interesting->output('short','header','footer','Bugs to watch','There\'s nothing new yet!'); 
			?>
				
	</div>
	<div class="column_4 last">
			
		<? $POD->output('sidebars/recent_bugs'); ?>
		
		<? $POD->output('sidebars/browse'); ?>
	</div>

	<div class="clearer"></div>

	<div id="below_fold">	
		<div class="column_3">
			<? $POD->output('sidebars/member_leaderboard'); ?>
		</div>
		<div class="clearer"></div>
	</div>