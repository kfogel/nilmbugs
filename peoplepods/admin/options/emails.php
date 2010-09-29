<? 
	include_once("../../PeoplePods.php");	
	$POD = new PeoplePod(array('lockdown'=>'adminUser','authSecret'=>$_COOKIE['pp_auth']));

	if ($_POST) {
	
		$fromAddress = $_POST['fromAddress'];
		$friendEmail = $_POST['friendEmail'];


		$POD->setLibOptions('fromAddress',$fromAddress);

		$POD->setLibOPtions('friendEmail',$friendEmail);
		$POD->setLibOPtions('contactEmail',$_POST['contactEmail']);


		$POD->saveLibOptions();
		if ($POD->success()) { 
				$message = "Config updated.";
		} else {
			$message = $POD->error();
		}

	}


	$POD->changeTheme('admin');
	$POD->header();
	$current_tab="emails";

?>
<? include_once("option_nav.php"); ?>

	<? if ($message) { ?>
		<div class="info">
		
			<? echo $message ?>
			
		</div>
	
	<? } ?>		
<div class="panel">

	<h1>Email Options</h1>
	
	<p>
		PeoplePods sends a variety of emails during the course of operation.
		These settings effect all emails.
	</p>

	<form method="post" class="valid">
		
		<p class="input"><label for="fromAddress">From Address:</label><input name="fromAddress" id="fromAddress" class="text required" value="<? echo htmlspecialchars($POD->libOptions('fromAddress')); ?>" type="text" /></p>
		

		<p class="input"><label>Optional Emails:</label></p>
		
		<p><input type="checkbox" name="friendEmail" value="friendEmail" <? if ($POD->libOptions('friendEmail')) { ?>checked<? } ?> /> Send an email 
		notification when a member adds another member as a friend.</p>

		<p><input type="checkbox" name="contactEmail" value="contactEmail" <? if ($POD->libOptions('contactEmail')) { ?>checked<? } ?> /> Send an email 
		member sends a private message.</p>

		
		<p><input type="submit" value="Update Email Options" class="button" /></p>
	</form>
</div>
<? $POD->footer(); ?>