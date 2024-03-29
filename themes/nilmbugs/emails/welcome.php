<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/emails/welcome.php
* sent to members when they create their accounts.
* this template needs to include logic to check if a verification is required.
* This can be done by checking the verificationKey field on $sender
*
* Define $subject as a variable
* The output of this template is otherwise used as the body of the email
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/themes
/**********************************************/
?>

<?


$subject='Welcome to ' . $sender->POD->siteName(false);

?>

Hello <? $sender->write('nick'); ?>,

Thank you for joining NILM Bugs!

<? if ($sender->get('verificationKey')) { ?>

Before you can post things and leave comments, you must verify your email address.  To do so, click the link below.

<? $sender->POD->siteRoot(); ?>/verify?key=<? $sender->write('verificationKey'); ?>

<? } ?>

You can update your account here:
<? $sender->POD->siteRoot(); ?>/editprofile