<? 



// load bug types
$bug_types = $POD->bugTypes();

// load media outlets
// this should be sorted by most open bugs
$media_outlets = $POD->mediaOutlets(9);

?>
<div class="column_8">

	<h1>Browse Bugs</h1>

	<ul class="directory">
		<div class="mainheader"><a href="<? $POD->siteRoot(); ?>/bugs/browse/date">Browse by Date</a></div>
		<li class="mainheader">
			<ul>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/date">Newest Bugs</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/date?sort=date:asc">Oldest Bugs</a></li>
			</ul>
			<div class="clearer"></div>
		</li>
		<div class="mainheader"><a href="<? $POD->siteRoot(); ?>/bugs/browse/date?sort=modification">Browse by Recent Activity</a></div>
		<li class="mainheader">
			<ul>
			</ul>
			<div class="clearer"></div>
		</li>
		<div class="mainheader"><a href="<? $POD->siteRoot(); ?>/bugs/browse/type">Browse by Type</a></div>
		<li class="mainheader">
			<ul>
				<? foreach ($bug_types as $type) { ?>
					<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/type?q=<?= $type->headline; ?>"><?= $type->headline; ?></a></li>
				<? } ?>
			</ul>
			<div class="clearer"></div>
		</li>
		<div class="mainheader"><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet">Browse by Jurisdiction</a></div>
			<ul>
				<? foreach ($media_outlets as $outlet) { ?>
					<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?q=<?= $outlet->id; ?>"><?= $outlet->headline; ?></a></li>				
				<? } ?>
			</ul>	
			<div class="clearer"></div>
		</li>
		<div class="mainheader"><a href="<? $POD->siteRoot(); ?>/bugs/browse/status">Browse by Status</a></div>
		<li class="mainheader">
			<ul>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=open">Open</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=open:under discussion">Open: Under Discussion</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=open:responded to">Open: Responded To</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:corrected">Closed: Corrected</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:withdrawn">Closed: Withdrawn</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:unresolved">Closed: Unresolved</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:off topic">Off Topic</a></li>
			</ul>	
		</li>	
			<div class="clearer"></div>
	</ul>			

</div>

<div class="column_4 last">
	
	<? $POD->output('sidebars/recent_bugs'); ?>

<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'search',
  search: 'lawgov, law.gov',
  interval: 6000,
  title: '',
  subject: '#law.gov',
  width: 250,
  height: 300,
  theme: {
    shell: {
      background: '#ffffff',
      color: '#1986b5'
    },
    tweets: {
      background: '#ffffff',
      color: '#444444',
      links: '#1986b5'
    }
  },
  features: {
    scrollbar: false,
    loop: true,
    live: true,
    hashtags: true,
    timestamp: true,
    avatars: true,
    toptweets: true,
    behavior: 'default'
  }
}).render().start();
</script>
</div>