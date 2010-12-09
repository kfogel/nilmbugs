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
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=AK"><img src="<? $POD->templateDir(); ?>/img/flags/AK.png" class="stateflag" alt="" /></a><br />AK</li><!-- Alaska -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=AL"><img src="<? $POD->templateDir(); ?>/img/flags/AL.png" class="stateflag" alt="" /></a><br />AL</li><!-- Alabama -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=AR"><img src="<? $POD->templateDir(); ?>/img/flags/AR.png" class="stateflag" alt="" /></a><br />AR</li><!-- Arkansas -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=AZ"><img src="<? $POD->templateDir(); ?>/img/flags/AZ.png" class="stateflag" alt="" /></a><br />AZ</li><!-- Arizona -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=CA"><img src="<? $POD->templateDir(); ?>/img/flags/CA.png" class="stateflag" alt="" /></a><br />CA</li><!-- California -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=CO"><img src="<? $POD->templateDir(); ?>/img/flags/CO.png" class="stateflag" alt="" /></a><br />CO</li><!-- Colorado -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=CT"><img src="<? $POD->templateDir(); ?>/img/flags/CT.png" class="stateflag" alt="" /></a><br />CT</li><!-- Connecticut -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=DE"><img src="<? $POD->templateDir(); ?>/img/flags/DE.png" class="stateflag" alt="" /></a><br />DE</li><!-- Delaware -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=FL"><img src="<? $POD->templateDir(); ?>/img/flags/FL.png" class="stateflag" alt="" /></a><br />FL</li><!-- Florida -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=GA"><img src="<? $POD->templateDir(); ?>/img/flags/GA.png" class="stateflag" alt="" /></a><br />GA</li><!-- Georgia -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=Hawaii"><img src="<? $POD->templateDir(); ?>/img/flags/hawaii.png" class="stateflag" alt="" /></a><br />Hawaii</li><!-- Hawaii -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=IA"><img src="<? $POD->templateDir(); ?>/img/flags/IA.png" class="stateflag" alt="" /></a><br />IA</li><!-- Iowa -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=ID"><img src="<? $POD->templateDir(); ?>/img/flags/ID.png" class="stateflag" alt="" /></a><br />ID</li><!-- Idaho -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=IL"><img src="<? $POD->templateDir(); ?>/img/flags/IL.png" class="stateflag" alt="" /></a><br />IL</li><!-- Illinois -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=IN"><img src="<? $POD->templateDir(); ?>/img/flags/IN.png" class="stateflag" alt="" /></a><br />IN</li><!-- Indiana -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=KS"><img src="<? $POD->templateDir(); ?>/img/flags/KS.png" class="stateflag" alt="" /></a><br />KS</li><!-- Kansas -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=KY"><img src="<? $POD->templateDir(); ?>/img/flags/KY.png" class="stateflag" alt="" /></a><br />KY</li><!-- Kentucky -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=LA"><img src="<? $POD->templateDir(); ?>/img/flags/LA.png" class="stateflag" alt="" /></a><br />LA</li><!-- Louisiana -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=MA"><img src="<? $POD->templateDir(); ?>/img/flags/MA.png" class="stateflag" alt="" /></a><br />MA</li><!-- Massachusetts -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=MD"><img src="<? $POD->templateDir(); ?>/img/flags/MD.png" class="stateflag" alt="" /></a><br />MD</li><!-- Maryland -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=ME"><img src="<? $POD->templateDir(); ?>/img/flags/ME.png" class="stateflag" alt="" /></a><br />ME</li><!-- Maine -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=MI"><img src="<? $POD->templateDir(); ?>/img/flags/MI.png" class="stateflag" alt="" /></a><br />MI</li><!-- Michigan -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=MN"><img src="<? $POD->templateDir(); ?>/img/flags/MN.png" class="stateflag" alt="" /></a><br />MN</li><!-- Minnesota -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=MO"><img src="<? $POD->templateDir(); ?>/img/flags/MO.png" class="stateflag" alt="" /></a><br />MO</li><!-- Missouri -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=MS"><img src="<? $POD->templateDir(); ?>/img/flags/MS.png" class="stateflag" alt="" /></a><br />MS</li><!-- Mississippi -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=MT"><img src="<? $POD->templateDir(); ?>/img/flags/MT.png" class="stateflag" alt="" /></a><br />MT</li><!-- Montana -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=NC"><img src="<? $POD->templateDir(); ?>/img/flags/NC.png" class="stateflag" alt="" /></a><br />NC</li><!-- North Carolina -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=ND"><img src="<? $POD->templateDir(); ?>/img/flags/ND.png" class="stateflag" alt="" /></a><br />ND</li><!-- North Dakota -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=NE"><img src="<? $POD->templateDir(); ?>/img/flags/NE.png" class="stateflag" alt="" /></a><br />NE</li><!-- Nebraska -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=NH"><img src="<? $POD->templateDir(); ?>/img/flags/NH.png" class="stateflag" alt="" /></a><br />NH</li><!-- New Hampshire -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=NJ"><img src="<? $POD->templateDir(); ?>/img/flags/NJ.png" class="stateflag" alt="" /></a><br />NJ</li><!-- New Jersey -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=NM"><img src="<? $POD->templateDir(); ?>/img/flags/NM.png" class="stateflag" alt="" /></a><br />NM</li><!-- New Mexico -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=NV"><img src="<? $POD->templateDir(); ?>/img/flags/NV.png" class="stateflag" alt="" /></a><br />NV</li><!-- Nevada -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=NY"><img src="<? $POD->templateDir(); ?>/img/flags/NY.png" class="stateflag" alt="" /></a><br />NY</li><!-- New York -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=OH"><img src="<? $POD->templateDir(); ?>/img/flags/OH.png" class="stateflag" style="border: none;" alt="" /></a><br />OH</li><!-- Ohio -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=OK"><img src="<? $POD->templateDir(); ?>/img/flags/OK.png" class="stateflag" alt="" /></a><br />OK</li><!-- Oklahoma -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=OR"><img src="<? $POD->templateDir(); ?>/img/flags/OR.png" class="stateflag" alt="" /></a><br />OR</li><!-- Oregon -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=PA"><img src="<? $POD->templateDir(); ?>/img/flags/PA.png" class="stateflag" alt="" /></a><br />PA</li><!-- Pennsylvania -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=RI"><img src="<? $POD->templateDir(); ?>/img/flags/RI.png" class="stateflag" alt="" /></a><br />RI</li><!-- Rhode Island -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=SC"><img src="<? $POD->templateDir(); ?>/img/flags/SC.png" class="stateflag" alt="" /></a><br />SC</li><!-- South Carolina -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=SD"><img src="<? $POD->templateDir(); ?>/img/flags/SD.png" class="stateflag" alt="" /></a><br />SD</li><!-- South Dakota -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=TN"><img src="<? $POD->templateDir(); ?>/img/flags/TN.png" class="stateflag" alt="" /></a><br />TN</li><!-- Tennessee -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=TX"><img src="<? $POD->templateDir(); ?>/img/flags/TX.png" class="stateflag" alt="" /></a><br />TX</li><!-- Texas -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=UT"><img src="<? $POD->templateDir(); ?>/img/flags/UT.png" class="stateflag" alt="" /></a><br />UT</li><!-- Utah -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=VA"><img src="<? $POD->templateDir(); ?>/img/flags/VA.png" class="stateflag" alt="" /></a><br />VA</li><!-- Virginia -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=VT"><img src="<? $POD->templateDir(); ?>/img/flags/VT.png" class="stateflag" alt="" /></a><br />VT</li><!-- Vermont -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=WA"><img src="<? $POD->templateDir(); ?>/img/flags/WA.png" class="stateflag" alt="" /></a><br />WA</li><!-- Washington -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=WI"><img src="<? $POD->templateDir(); ?>/img/flags/WI.png" class="stateflag" alt="" /></a><br />WI</li><!-- Wisconsin -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=WV"><img src="<? $POD->templateDir(); ?>/img/flags/WV.png" class="stateflag" alt="" /></a><br />WV</li><!-- West Virginia -->
        <li><a href="<? $POD->siteRoot(); ?>/bugs/browse/state?q=WY"><img src="<? $POD->templateDir(); ?>/img/flags/WY.png" class="stateflag" alt="" /></a><br />WY</li><!-- Wyoming -->

      </ul>
    </div>
    <a href="javascript:void(0);" id="featured-item-prev"><img src="<? $POD->templateDir(); ?>/img/prev.png" alt="" /></a> <a href="javascript:void(0);" id="featured-item-next"><img src="<? $POD->templateDir(); ?>/img/next.png" alt="" /></a> 
</div>


	<div id="welcome_block">
		<? $welcome_message->output('interface_text'); ?>
		<div id="homepage_submit">
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