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
	<script src="<? $POD->templateDir(); ?>/js/jquery-bt/jquery.bt.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/jquery-datepick/jquery.datepick.js"></script>
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/jquery-datepick/jquery.datepick-validation.js"></script>


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

	<!-- begin login status -->
	<div id="login_status">
		<div class="grid">
			<? if ($POD->isAuthenticated()) { ?>
				Welcome, <a href="<? $POD->currentUser()->write('permalink'); ?>" title="View My Profile"><? $POD->currentUser()->write('nick'); ?></a> |
				<a href="<? $POD->siteRoot(); ?>/logout" title="Logout">Log&nbsp;out</a>
			<? } else { ?>
				<a href="<? $POD->siteRoot(); ?>/login">Log&nbsp;in</a> or <a href="<? $POD->siteRoot(); ?>/join">Create an account</a>
			<? } ?>
		</div>
	</div>
	<!-- end login status -->
	<!-- begin header -->
	<div id="header">
		<div class="grid">
			<div class="column_5">
				<a href="<? $POD->siteRoot(); ?>/" title="NILM Bugs Homepage"><img src="<? $POD->templateDir(); ?>/img/nilmbugs_logo.png" id="logo" border="0" alt="NILM Bugs" width="361" height="100"/></a>	
			</div>
			<div class="column_7 last">
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
			<li id="nav_home"><a href="<? $POD->siteRoot(); ?>">Home</a></li>
			<li id="nav_report"><a href="<? $POD->siteRoot(); ?>/bugs/edit">Report&nbsp;a&nbsp;bug</a></li>
			<li id="nav_browse"><a href="<? $POD->siteRoot(); ?>/bugs">Browse&nbsp;bugs</a></li>
			<? if ($POD->isAuthenticated()) { ?>
				<li id="nav_my"><a href="<? $POD->siteRoot(); ?>/dashboard">My&nbsp;bugs</a></li>
			<? } ?>
			<li id="nav_about"><a href="<? $POD->siteRoot(); ?>/pages/about">About</a></li>
			<li id="nav_help"><a href="<? $POD->siteRoot(); ?>/pages/help">Help</a></li>
			<li id="nav_contact"><a href="<? $POD->siteRoot(); ?>/pages/contact">Contact</a></li>
		</ul>
		<div class="clearer"></div>
	</div>
	<!-- end main navigation -->	
	<div id="main" class="content grid">
