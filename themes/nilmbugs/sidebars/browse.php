	<div class="sidebar" id="browse_starter">
			<h3><a href="<? $POD->siteRoot(); ?>/bugs/browse/date" title="Browse bugs in a variety of ways">Browse Bugs</a></h3>
			<ul class="sidebar_directory">
				<li>
					<a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet">Browse by Jurisdiction</a>
				</li>
				<li>
					<a href="<? $POD->siteRoot(); ?>/bugs/browse/state">Browse by State</a>
				</li>				
				<li>
					Browse by Status
					<div id="status" style="margin: 0px 0px 10px 10px;">
					<div><a class="resourcetitle" href="#resource1" onclick="return toggle('1'); return false;"><img src="<? $POD->templateDir(); ?>/img/status_icons/bug.png" alt="Open" align="absmiddle" border="0">&nbsp;Open</a></div>
					<ul id="resource1" style="display:none">
						<li class="subcategory"><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=open"><img src="<? $POD->templateDir(); ?>/img/status_icons/open_20.png" alt="Open" align="absmiddle" border="0">&nbsp;All Open Bugs</a></li>
						<li class="subcategory"><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=open:under%20discussion"><img src="<? $POD->templateDir(); ?>/img/status_icons/open_under_discussion_20.png" alt="Open: Under Discussion" align="absmiddle" border="0">&nbsp;Open: Under Discussion</a></li>
						<li class="subcategory"><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=open:responded%20to"><img src="<? $POD->templateDir(); ?>/img/status_icons/open_responded_to_20.png" alt="Open: Responded to" align="absmiddle" border="0">&nbsp;Open: Responded to</a></li>
					</ul>	
					<div><a class="resourcetitle" href="#resource1" onclick="return toggle('2'); return false;"><img src="<? $POD->templateDir(); ?>/img/status_icons/bug_delete.png" alt="Open" align="absmiddle" border="0">&nbsp;Closed</a></div>
					<ul id="resource2" style="display:none">
						<li class="subcategory"><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:corrected"><img src="<? $POD->templateDir(); ?>/img/status_icons/closed_corrected_20.png" alt="Closed: Corrected" align="absmiddle" border="0">&nbsp;Closed: Corrected</a></li>
						<li class="subcategory"><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:withdrawn"><img src="<? $POD->templateDir(); ?>/img/status_icons/closed_withdrawn_20.png" alt="Closed: Withdrawn" align="absmiddle" border="0">&nbsp;Closed: Withdrawn</a></li>
						<li class="subcategory"><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:unresolved"><img src="<? $POD->templateDir(); ?>/img/status_icons/closed_unresolved_20.png" alt="Closed: Unresolved" align="absmiddle" border="0">&nbsp;Closed: Unresolved</a></li>
					</ul>	
					<div><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:off%20topic"><img src="<? $POD->templateDir(); ?>/img/status_icons/bug_error.png" alt="Closed: Off Topic" align="absmiddle" border="0">&nbsp;Off Topic</a></div>
				</div>
					<a href="http://bugs.resource.org/pages/status-explanation">What do these mean?</a>
				</li>	
			</ul>			
	
		</div>
