<?

	$POD->registerPOD('mediabugs_profiles','Give each member a personal profile',array('^people/(.*)'=>'mediabugs_profiles/profile.php?username=$1','^editprofile'=>'mediabugs_profiles/editprofile.php'),array('profilePath'=>'/people'));

?>