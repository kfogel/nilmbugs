<? 



// load bug types
$bug_types = $POD->bugTypes();

// load media outlets
// this should be sorted by most open bugs
$media_outlets = $POD->mediaOutlets(9);

?>
<div class="column_8">

	<h1>Browse / By Date</h1>

<p class="bigwarning">If you see this text, it's probably a mistake: someone has undone or sidestepped the fix for <a href="http://code.google.com/p/nilmbugs/issues/detail?id=10" >nilmbugs issue #10</a>.  Please <a href="http://code.google.com/p/nilmbugs/issues/entry" >file a bug</a> in the code's issue tracker, mentioning this message and giving the exact URL where you saw it.</p>
<? $POD->output('bugs/browse/date'); ?>

</div>

<div class="column_4 last">
	
	<? $POD->output('sidebars/recent_bugs'); ?>

	<? $POD->output('sidebars/browse'); ?>

</div>