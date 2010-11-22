<? 



// load bug types
$bug_types = $POD->bugTypes();

// load media outlets
// this should be sorted by most open bugs
$media_outlets = $POD->mediaOutlets(9);

?>
<div class="column_8">

	<h1>Browse Bugs</h1>

<? $POD->output('bugs/browse/date'); ?>

</div>

<div class="column_4 last">
	
	<? $POD->output('sidebars/recent_bugs'); ?>

	<? $POD->output('sidebars/browse'); ?>

</div>