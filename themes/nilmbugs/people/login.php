<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/people/login.php
* Used by the core_authentication pod to create the /login page
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/person-object
/**********************************************/
?>
	<div id="connect_form">

		<h1>Sign in to <? $POD->siteName(); ?></h1>

		<p>Log&nbsp;in with <a href="<? $POD->siteRoot(); ?>/openid">OpenID</a> or <a href="<? $POD->siteRoot(); ?>/facebook">Facebook</a></p>
		
		<form method="post" id="login" action="<? $POD->siteRoot(); ?>/login" class="valid">
			<input type="hidden" name="redirect" value="<? echo htmlspecialchars($user->get('redirect')); ?>" />
			<p class="input">
				<label for="email">Email:</label>
				<input class="required validate-email text" name="email" id="email" />
			</p>
			
			<p class="input">
				<label for="password">Password:</label>
				<input class="required text" name="password" type="password" id="password" />
			</p>
			
			<p class="input">
				<label for="remember_me">Remember Me:</label>
				<input type="checkbox" name="remember_me" value="true" checked />
			</p>
			
			<p class="input">
				<input type="submit" class="button" value="Login" name="login" />
			</p>
                        <? if ($POD->libOptions('enable_bugs_authentication_creation')) { ?>
			        <p class="right_align">Need an account? <a href="<? $POD->siteRoot(); ?>/join">Join this site!</a></p>
                        <? } ?>
			<p class="right_align"><a href="<? $POD->siteRoot(); ?>/password_reset">Forgot your password?</a></p>
			

		</form>

	</div>
