<? 	$media_outlet = $POD->getContent(array('id'=>$doc->media_outlet)); ?>

This was sent to you by <?= $doc->send_this_sender_name; ?> (<?= $doc->send_this_sender_email; ?>):

Message from <?= $doc->send_this_sender_name; ?>:

<?= $doc->write('send_this_message'); ?>

<?= $doc->bugHeadline(); ?>
<?= $doc->permalink; ?>

A bug in <?= $media_outlet->headline; ?> reported to the NILM Bug Tracker.

<?= $doc->bugSummary(); ?>