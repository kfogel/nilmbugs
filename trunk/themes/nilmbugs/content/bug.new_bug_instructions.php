<? 
	$subscribed = false;
	if ($POD->isAuthenticated()) { 
		$subs = $POD->getContents(array('userId'=>$POD->currentUser()->id,'type'=>'subscription','parentId'=>$doc->id));
		$subscribed = ($subs->totalCount() > 0);
	}

?>


<div id="new_bug_instructions">
	<a href="#" onclick="$('#new_bug_instructions').fadeOut();return false;" id="closer">&nbsp;</a>
	<h1>Thank you for reporting this bug!</h1>
	
	<? if ($POD->isAuthenticated()) { ?>
	
		<p><input type="checkbox" id="subcheck" <? if ($subscribed) { ?>checked<? } ?> onclick="return toggleBot('subcheck','','','method=toggleSub&contentId='+<?= $doc->id; ?>,subCheckboxSuccess);" /> Send me a message when someone leaves a comment on this bug</p>
	
		<p>You can <a href="<?= $doc->editlink; ?>">edit this bug</a> even after submitting it.</p>
	
	<? } else { ?>
	
		<p>
			By filing this bug, you've potentially opened up a communication channel with the jurisdiction involved.
		  	We believe it is important that you remain engaged in this discussion.  The best way to do that is to <strong><a href="<? $POD->siteRoot(); ?>/join?claim=<?= $doc->id; ?>">create a NILM Bugs account</a></strong>
		  	so that you can track this bug and make sure it gets closed. 
		</p>
	
                <? if ($POD->libOptions('enable_bugs_authentication_creation')) { ?>
                        <p>
                                <a href="<? $POD->siteRoot(); ?>/join?claim=<?= $doc->id; ?>" class="littlebutton">Claim this bug</a>		
		        </p>
                <? } ?>
		<div class="clearer"></div>

	
	<? } ?>
	
	<p>You can link to your bug using the full url, below:</p>
	<p class="input"><input value="<? $doc->write('permalink'); ?>" class="text" /></p>
	<div class="clearer"></div>
</div>