<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/people/member_info.php
* Creates a little member info box
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/person-object
/**********************************************/
?>
<div id="member_info">
	<? $user->output('avatar'); ?>
		<div style="padding-top: 5px;"><? $user->permalink(); ?></div>
		<? if ($user->get('location')) {
			$user->write('location');
			echo "<br />";
		} ?>
		<? if ($user->POD->isAuthenticated() && $user->POD->currentUser()->get('id') == $user->get('id')) { ?>
			<? if ($user->get('verificationKey')) { ?>
				<a href="<? $user->POD->siteRoot(); ?>/verify" class="highlight">Verify Your Account!</a>
			<? } ?>
			<a href="<? $user->POD->siteRoot(); ?>/editprofile" class="person_permalink">Edit Profile</a>
		<? } ?>
	<div class="clearer"></div>
</div>