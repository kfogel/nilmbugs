<div id="send_this">

	<p><strong>Thank you!</strong></p>

	<p>An email has been sent to <strong><?= $doc->send_this_recipient; ?></strong> with a link to <? $doc->permalink(); ?></p>

	<p><a href="<?= $doc->permalink; ?>">Return to post</a></p>

</div>