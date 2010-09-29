<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
			<title><? echo $POD->libOptions('siteName'); ?> - PeoplePods Dashboard</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<script src="http://www.google.com/jsapi" type="text/javascript"></script>
			<script type="text/javascript">	
//				google.load("jquery","1.4");
			</script>	
			<script src="<? $POD->templateDir(); ?>/js/jquery-1.4.1.min.js"></script>
			<script src="<? $POD->templateDir(); ?>/js/jquery-autocomplete/jquery.autocomplete.js"></script>
			<link rel="stylesheet" type="text/css" href="<? $POD->templateDir(); ?>/js/jquery-autocomplete/jquery.autocomplete.css" media="screen" charset="utf-8" />
			<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
			<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.validate/1.6/jquery.validate.min.js"></script>
			<link rel="stylesheet" type="text/css" href="<? $POD->templateDir(); ?>/styles.css" />
			<script>
				var PODROOT = '<? $POD->podRoot(); ?>';
			</script>
			<script src="<? $POD->templateDir(); ?>/script.js"></script>
			
	</head>
	<body>
	
		<div id="peoplepods_admin">
			<div id="navigation">
				<h1><a href="<? $POD->podRoot(); ?>/admin/">PeoplePods</a></h1>
				<ul>
					<li class="first"><a href="<? $POD->podRoot(); ?>/admin/" id="nav_home">Home</a></li>
					<li><a href="<? $POD->podRoot(); ?>/admin/people/search.php" id="nav_people">People</a></li>
					<li><a href="<? $POD->podRoot(); ?>/admin/content/search.php" id="nav_posts">Content</a></li>
					<li><a href="<? $POD->podRoot(); ?>/admin/comments/" id="nav_posts">Comments</a></li>
					<li><a href="<? $POD->podRoot(); ?>/admin/files/" id="nav_posts">Files</a></li>
					<li><a href="<? $POD->podRoot(); ?>/admin/flags/" id="nav_flags">Flags</a></li>
					<li><a href="<? $POD->podRoot(); ?>/admin/groups/search.php" id="nav_groups">Groups</a></li>
					<li class="last"><a href="<? $POD->podRoot(); ?>/admin/options" id="nav_options">Options</a></li>
					<li><a href="<? $POD->siteRoot(); ?>">View Site</a></li>
				</ul>
			</div>
			<div id="panel">