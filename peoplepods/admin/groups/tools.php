	<div id="tools">
		<ul>
			<li id="section_name">Groups</li><li>
				<a href="<? $POD->podRoot(); ?>/admin/groups" class="button"><img src="<? $POD->podRoot(); ?>/admin/img/page_add.png" border="0" align="absmiddle">&nbsp;Add Group</a>
			</li><li>
				<form method="get" action="search.php">
					<input name="q" value="Search Groups" onblur="repairField(this,'Search Groups');" onfocus="repairField(this,'Search Groups');" class="repairField"/>
				</form>
			</li>
		</ul>
	</div>