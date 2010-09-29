<? 	$media_outlet = $POD->getContent(array('id'=>$doc->media_outlet)); ?>

This was sent to you by <?= $doc->send_this_sender_name; ?> (<?= $doc->send_this_sender_email; ?>):

Message from <?= $doc->send_this_sender_name; ?>:

<?= $doc->write('send_this_message'); ?>

<?= $doc->headline; ?>
<?= $doc->permalink; ?>

A post from <? $POD->siteName(); ?>

<?= $doc->shorte('body'); ?>