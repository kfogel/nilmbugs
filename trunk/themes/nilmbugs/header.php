<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/header.php
* Defines what is in the header of every page, used by $POD->header()
*
* Special variables in this file are:
* $pagetitle
* $feedurl
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/themes
/**********************************************/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><? if ($pagetitle) { echo $pagetitle . " - " . $POD->siteName(false); } else { echo $POD->siteName(false); } ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link REL="SHORTCUT ICON" HREF="<? $POD->siteRoot(); ?>/favicon.ico">
	<!--[if IE]>
		<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/excanvas/excanvas.compiled.js"></script>
	<![endif]-->

	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/jquery-1.4.2.min.js"></script>
	<script src="<? $POD->templateDir(); ?>/js/jquery-autocomplete/jquery.autocomplete.min.js"></script>
	<script src="<? $POD->templateDir(); ?>/js/jquery-bt/other_libs/jquery.hoverIntent.minified.js" type="text/javascript" charset="utf-8"></script>
	<script src="<? $POD->templateDir(); ?>/js/jquery-bt/other_libs/bgiframe_2.1.1/jquery.bgiframe.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<? $POD->templateDir(); ?>/js/jquery-bt/jquery.bt.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/jquery-datepick/jquery.datepick.js"></script>
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/jquery-datepick/jquery.datepick-validation.js"></script>

	<link rel="stylesheet" type="text/css" href="<? $POD->templateDir(); ?>/js/flowplayer.css" media="screen" charset="utf-8" />
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/flowplayer-3.2.4.min.js"></script>

	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/toggle.js"></script>

	<link rel="stylesheet" type="text/css" href="<? $POD->templateDir(); ?>/js/featured_slide.css" media="screen" charset="utf-8" />
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/jquery.jcarousel.pack.js"></script>
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/jquery.jcarousel.setup.js"></script>

	<link rel="stylesheet" type="text/css" href="<? $POD->templateDir(); ?>/js/jquery-bt/jquery.bt.css" media="screen" charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="<? $POD->templateDir(); ?>/js/jquery-autocomplete/jquery.autocomplete.css" media="screen" charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="<? $POD->templateDir(); ?>/js/jquery-datepick/flora.datepick.css" media="screen" charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="<? $POD->templateDir(); ?>/styles.css?v=20100609" media="screen" charset="utf-8" />
	
	<? if ($feedurl) { ?>
		<link rel="alternate" type="application/rss+xml" title="RSS: <? if ($pagetitle) { echo $pagetitle . " - " . $POD->siteName(false); } else { echo $POD->siteName(false); } ?>" href="<? echo $feedurl; ?>" />
	<? } ?>
		<link rel="alternate" type="application/rss+xml" title="RSS: Most recent bugs from	 <? $POD->siteName();  ?>" href="<? $POD->siteRoot(); ?>/bugs/feeds/date" />
	<script type="text/javascript">
		var siteRoot = "<? $POD->siteRoot(); ?>";
		var podRoot = "<? $POD->podRoot(); ?>";
		var themeRoot = "<? $POD->templateDir(); ?>";
		var API = siteRoot + "/api";		
	</script>
	
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/javascript.js?v=20090828"></script>

	
</head>

<body id="body">
	<div id="lawgov_logo"><a href="<? $POD->siteRoot(); ?>"><img src="<? $POD->templateDir(); ?>/img/law.gov.nilm.png" alt="Law.Gov" border="0"></a></div>
	<div id="stateflag"><a href="http://code.google.com/p/nilmbugs/"><img src="<? $POD->templateDir(); ?>/img/alpha.bug.png" alt="flags" id="flag-switcher" class="flag-switcher" border="0"></a></div>

	<!-- begin login status -->
	<div id="login_status">
			<? if ($POD->isAuthenticated()) { ?>
				Welcome, <a href="<? $POD->currentUser()->write('permalink'); ?>" title="View My Profile"><? $POD->currentUser()->write('nick'); ?></a> |
				<a href="<? $POD->siteRoot(); ?>/logout" title="Logout">Log&nbsp;out</a>
			<? } else { ?>
				<a href="<? $POD->siteRoot(); ?>/login">Log&nbsp;in</a> <? if ($POD->libOptions('enable_bugs_authentication_creation')) { ?>or <a href="<? $POD->siteRoot(); ?>/join">Create an account</a><? } ?>
			<? } ?>
	</div>
	<!-- end login status -->
	<!-- begin header -->
	<div id="header">
				<a href="<? $POD->siteRoot(); ?>/" title="NILM Bugs Homepage"><img src="<? $POD->templateDir(); ?>/img/NILM_logo.png" id="logo" alt="Law.Gov" border="0"></a>	
		<div class="grid">
			<div id="search_nilm">
				<form method="get" action="<? $POD->siteRoot(); ?>/bugs/browse/search">
					<input name="q" class="repairField" value="Find a specific bug..." onfocus="repairField(this,'Find a specific bug...');" onblur="repairField(this,'Find a specific bug...');" />
					<input name="search" value="Search" type="submit" class="littlebutton" />
				</form>
			</div>
			<div class="clearer"></div>
		</div>		
	</div>
	<!-- end header -->
	<!-- begin main navigation -->		
	<div id="nav">				
		<ul>
			<li id="nav_home" style="margin-left: 10px;"><a href="<? $POD->siteRoot(); ?>">Home</a></li>
			<li id="nav_report"><a href="<? $POD->siteRoot(); ?>/bugs/edit">Report&nbsp;a&nbsp;bug</a></li>
			<li id="nav_browse"><a href="<? $POD->siteRoot(); ?>/bugs/browse/date">Browse&nbsp;bugs</a></li>
			<? if ($POD->isAuthenticated()) { ?>
				<li id="nav_my"><a href="<? $POD->siteRoot(); ?>/dashboard">My&nbsp;bugs</a></li>
			<? } ?>
			<li id="nav_about"><a href="<? $POD->siteRoot(); ?>/pages/about">About</a></li>
			<li id="nav_help"><a href="<? $POD->siteRoot(); ?>/pages/help">Help</a></li>
		</ul>
			<div class="clearer"></div>
		</div>	
	<!-- end main navigation -->	
	<div id="main" class="content grid">

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
    <a href="javascript:void(0);" id="featured-item-prev"><img src="images/prev.png" alt="" /></a> <a href="javascript:void(0);" id="featured-item-next"><img src="images/next.png" alt="" /></a> </div>
</div>
