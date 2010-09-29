	<div id="tools">
		<ul>
			<li id="section_name">Files</li><? if ($file) { ?><? if ($user) { ?><li><A href="<? $POD->podRoot(); ?>/admin/files/?userId=<? $user->write('id'); ?>">&larr; Back to <? $user->write('nick'); ?>'s files</a></li><? } ?><? if ($content) { ?><li><A href="<? $POD->podRoot(); ?>/admin/files/?contentId=<? $content->write('id'); ?>">&larr; Back to <? $content->write('headline'); ?>'s files</a></li><? } ?><? } ?><? if ($user) { ?><li><A href="<? $POD->podRoot(); ?>/admin/people/?id=<? $user->write('id'); ?>">&larr; Back to <? $user->write('nick'); ?></a></li><? } ?><? if ($content) { ?><li><A href="<? $POD->podRoot(); ?>/admin/content/?id=<? $content->write('id'); ?>">&larr; Back to <? $content->write('headline'); ?></a></li><? } ?>
		</ul>
	</div>