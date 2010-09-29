<? 
	include_once("../../PeoplePods.php");	
	
	$POD = new PeoplePod(array('lockdown'=>'adminUser','authSecret'=>$_COOKIE['pp_auth']));
	$POD->changeTheme('admin');
	
	$conditions = array();

	if ($_GET['q'] && $_GET['q'] != 'Search') { 
		$conditions['groupname:like'] = '%' . $_GET['q'] . '%';	
	}	
	if ($_GET['type']) {
		$conditions['type'] = $_GET['type'];
	}
	if ($_GET['userId']) {
		$conditions['userId'] = $_GET['userId'];
	}

	$offset = $_GET['offset'];
	if (!$offset) {
		$offset = 0;
	}
	
	if (sizeof($conditions) > 0) {
		$groups = $POD->getGroups($conditions,'date DESC',20,$offset);	
	} else {	
		$groups = $POD->getGroups(array('1'=>1),'date DESC',20,$offset);
	}
	
	$message = $_GET['message'];
		$POD->header();

		include_once("tools.php");

		if ($message) { ?>
		
			<div class="info">
				<? echo $message; ?>
			</div>
		
		<? } ?>

		<div class="list_panel">
			<h1>Groups</h1>
			<? $groups->output('short','group_header','table_pager'); ?>
		
		</div>

	<?	$POD->footer(); ?>