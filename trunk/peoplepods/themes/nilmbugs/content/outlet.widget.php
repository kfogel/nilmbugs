<?

	//$open_bugs = $POD->getContents(array('type'=>'bug','media_outlet'=>$doc->id,'bug_status:like'=>'open%'));
	//$closed_bugs = $POD->getContents(array('type'=>'bug','media_outlet'=>$doc->id,'bug_status:like'=>'closed%'));
	
	$img = $doc->files()->contains('file_name','img');
?>
<div id="media_outlet_widget">
	<? if ($img) { ?>
		<a href="/bugs/browse/outlet?q=<?= $doc->id; ?>" title="View more bugs"><img src="<?= $img->src(80); ?>" border="0" alt="Media Outlet Logo"></a>
	<? } else { ?>
		&nbsp;
	<? } ?>
</div>