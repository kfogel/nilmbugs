<?
	$POD->registerPOD(
		'mediabugs_subscriptions',
		'cron job that sends subscription messages hourly and daily',
		array('^dailysubs'=>'mediabugs_subscriptions/daily.php','^hourlysubs'=>'mediabugs_subscriptions/hourly.php'),
		array()
	);


?>