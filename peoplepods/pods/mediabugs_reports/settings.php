<?

	$POD->registerPOD(
	
		'mediabugs_reports',
		'reports!',
		array(
			'^reports$'=>'mediabugs_reports/handler.php',
			'^reports/$'=>'mediabugs_reports/handler.php',
			'^reports/(.*)'=>'mediabugs_reports/handler.php?mode=$1'
		),
		array()
	);



?>