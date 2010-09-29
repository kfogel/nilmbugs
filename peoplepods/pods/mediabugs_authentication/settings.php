<? 

	$POD->registerPOD('mediabugs_authentication_login','Allows Login, Logout, Password Reset',array('^r/(.*)'=>'mediabugs_authentication/login.php?checklogin=1&redirect=/$1','^login'=>'mediabugs_authentication/login.php','^logout'=>'mediabugs_authentication/logout.php','^password_reset/(.*)'=>'mediabugs_authentication/password.php?resetCode=$1','^password_reset$'=>'mediabugs_authentication/password.php'),array());
	$POD->registerPOD('mediabugs_authentication_creation','Allows new members to join your site',array('^join'=>'mediabugs_authentication/join.php','^verify'=>'mediabugs_authentication/verify.php'),array());

?>