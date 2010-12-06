<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/footer.php
* Defines what is in the footer of every page, used by $POD->footer()
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/themes
/**********************************************/
?>

		<div class="clearer"></div>
	</div> <!-- main -->
	<div id="footer">
			<ul>
				<li style="margin-left: 10px;">
					<a href="http://public.resource.org" class="navlinks">Public.Resource.org</a>
				</li>
				<li>
					<a href="<? $POD->siteRoot(); ?>/pages/privacy" class="navlinks">Privacy</a>
				</li>
				<li>
					<a href="<? $POD->siteRoot(); ?>/pages/credits" class="navlinks">Credits</a>
				</li>
				
				<? if ($POD->isAuthenticated() && $POD->currentUser()->adminUser) { ?>
					<li>
						<a href="<? $POD->siteRoot(); ?>/peoplepods/admin" class="navlinks">Admin</a>
					</li>			
				<? } ?>
			</ul>
	</div>	
<!-- FIXME: update the Google Analytics, or get rid of them. -->
	<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	try {
	var pageTracker = _gat._getTracker("UA-15426486-1");
	pageTracker._setDomainName(".mediabugs.org");
	pageTracker._trackPageview();
	} catch(err) {}</script>


<script language="JavaScript">
	// a very simple setup
	flowplayer("div.player", "http://releases.flowplayer.org/swf/flowplayer-3.2.5.swf");
</script>

</body>
</html>