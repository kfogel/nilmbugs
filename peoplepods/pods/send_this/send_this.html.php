<p>This was sent to you by <?= $doc->send_this_sender_name; ?> (<?= $doc->send_this_sender_email; ?>):</p>

<p>Message from <?= $doc->send_this_sender_name; ?>:</p>

<?= $doc->writeFormatted('send_this_message'); ?>

<h3><? $doc->permalink(); ?></h3>

<p>A post from <? $POD->siteName(); ?></p>

<p><?= $doc->shorten('body'); ?></p>

<hr />