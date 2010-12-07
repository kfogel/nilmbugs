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


  <div id="featured_slide">

    <div id="featured_content">
      <ul>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/AK.png" class="stateflag" alt="" /></a><br />AK</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/AL.png" class="stateflag" alt="" /></a><br />AL</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/AR.png" class="stateflag" alt="" /></a><br />AR</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/AZ.png" class="stateflag" alt="" /></a><br />AZ</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/CA.png" class="stateflag" alt="" /></a><br />CA</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/CO.png" class="stateflag" alt="" /></a><br />CO</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/CT.png" class="stateflag" alt="" /></a><br />CT</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/DE.png" class="stateflag" alt="" /></a><br />DE</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/FL.png" class="stateflag" alt="" /></a><br />FL</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/GA.png" class="stateflag" alt="" /></a><br />GA</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/hawaii.png" class="stateflag" alt="" /></a><br />Hawaii</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/IA.png" class="stateflag" alt="" /></a><br />IA</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/ID.png" class="stateflag" alt="" /></a><br />ID</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/IL.png" class="stateflag" alt="" /></a><br />IL</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/IN.png" class="stateflag" alt="" /></a><br />IN</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/KS.png" class="stateflag" alt="" /></a><br />KS</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/KY.png" class="stateflag" alt="" /></a><br />KY</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/LA.png" class="stateflag" alt="" /></a><br />LA</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/MA.png" class="stateflag" alt="" /></a><br />MA</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/MD.png" class="stateflag" alt="" /></a><br />MD</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/ME.png" class="stateflag" alt="" /></a><br />ME</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/MI.png" class="stateflag" alt="" /></a><br />MI</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/MN.png" class="stateflag" alt="" /></a><br />MN</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/MO.png" class="stateflag" alt="" /></a><br />MO</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/MS.png" class="stateflag" alt="" /></a><br />MS</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/MT.png" class="stateflag" alt="" /></a><br />MT</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/NC.png" class="stateflag" alt="" /></a><br />NC</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/ND.png" class="stateflag" alt="" /></a><br />ND</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/NE.png" class="stateflag" alt="" /></a><br />NE</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/NH.png" class="stateflag" alt="" /></a><br />NH</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/NJ.png" class="stateflag" alt="" /></a><br />NJ</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/NM.png" class="stateflag" alt="" /></a><br />NM</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/NV.png" class="stateflag" alt="" /></a><br />NV</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/NY.png" class="stateflag" alt="" /></a><br />NY</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/OH.png" class="stateflag" style="border: none;" alt="" /></a><br />OH</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/OK.png" class="stateflag" alt="" /></a><br />OK</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/OR.png" class="stateflag" alt="" /></a><br />OR</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/PA.png" class="stateflag" alt="" /></a><br />PA</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/RI.png" class="stateflag" alt="" /></a><br />RI</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/SC.png" class="stateflag" alt="" /></a><br />SC</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/SD.png" class="stateflag" alt="" /></a><br />SD</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/TN.png" class="stateflag" alt="" /></a><br />TN</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/TX.png" class="stateflag" alt="" /></a><br />TX</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/UT.png" class="stateflag" alt="" /></a><br />UT</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/VA.png" class="stateflag" alt="" /></a><br />VA</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/VT.png" class="stateflag" alt="" /></a><br />VT</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/WA.png" class="stateflag" alt="" /></a><br />WA</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/WI.png" class="stateflag" alt="" /></a><br />WI</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/WV.png" class="stateflag" alt="" /></a><br />WV</li>
        <li><a href="#"><img src="<? $POD->templateDir(); ?>/img/flags/WY.png" class="stateflag" alt="" /></a><br />WY</li>

      </ul>
    </div>
    <a href="javascript:void(0);" id="featured-item-prev"><img src="images/prev.png" alt="" /></a> <a href="javascript:void(0);" id="featured-item-next"><img src="images/next.png" alt="" /></a> 
</div>


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
					$interesting->output('short','header','footer','Bugs to watch','No bugs yet!'); 
			?>
				
	</div>
	<div class="column_4 last">
			
		<? $POD->output('sidebars/recent_bugs'); ?>


<div class="sidebar">
<h3><a href="http://twitter.com/#!/search/lawgov">Law.Gov on Twitter</a></h3>		
<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'search',
  search: '#lawgov OR Law.Gov',
  interval: 6000,
  title: '',
  subject: 'law.gov',
  width: 250,
  height: 300,
  theme: {
    shell: {
      background: '#ffffff',
      color: '#1986b5'
    },
    tweets: {
      background: '#ffffff',
      color: '#444444',
      links: '#1986b5'
    }
  },
  features: {
    scrollbar: false,
    loop: true,
    live: true,
    hashtags: true,
    timestamp: true,
    avatars: true,
    toptweets: true,
    behavior: 'default'
  }
}).render().start();
</script>
</div>
</div>

	<div class="clearer"></div>

	<div id="below_fold">	
		<div class="column_3">
			<? $POD->output('sidebars/member_leaderboard'); ?>
		</div>
		<div class="clearer"></div>
	</div>