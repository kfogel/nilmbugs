<?

	include_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('authSecret'=>$_COOKIE['pp_auth']));
	
	$doc = $POD->getContent(array('stub'=>$_GET['stub']));
	$POD->header($doc->headline);
	$doc->output('lgviolation.output',dirname(__FILE__));
	$POD->footer();
