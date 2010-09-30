<div class="sidebar" id="twitter_sidebar">
	<h3><a href="http://twitter.com/media_bugs">Recent Tweets</a></h3>
	<script src="http://widgets.twimg.com/j/2/widget.js"></script>
	<script>
	new TWTR.Widget({
	  version: 2,
	  type: 'profile',
	  rpp: 4,
	  interval: 6000,
	  width: 280,
	  height: 300,
	  theme: {
	    shell: {
	      background: '#ffffff',
	      color: '#ffffff'
	    },
	    tweets: {
	      background: '#ffffff',
	      color: '#111834',
	      links: '#00418C'
	    }
	  },
	  features: {
	    scrollbar: false,
	    loop: false,
	    live: false,
	    hashtags: true,
	    timestamp: true,
	    avatars: false,
	    behavior: 'all'
	  }
	}).render().setUser('media_bugs').start();
	</script>
</div>