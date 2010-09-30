<?

	$POD->registerPOD(
		'lgviolation',
		'Creates permalink pages for the LawGovViolation type',
		array('^lgviolation/(.*)'=>'lgviolation/handler.php?stub=$1'),
		array('lgviolation_document_path'=>'lgviolation'),
		dirname(__FILE__)."/methods.php"
	);
