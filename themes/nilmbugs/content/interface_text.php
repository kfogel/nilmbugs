<?
	$doc->write('body');
	if ($doc->isEditable()) { ?>
		<a href="<?= $doc->editlink; ?>" class="edit_button">Edit</a>
	<? } 
?>
