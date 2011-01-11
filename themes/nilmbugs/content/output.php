<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/content/output.php
* Default output template for a piece of content
* Use this file as a basis for your custom content templates
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/themes
/**********************************************/
?>
<div class="column_8">
	<div id="post_output">
			<h1><a href="<? $doc->write('permalink'); ?>" title="<? $doc->write('headline'); ?>"><? $doc->write('headline'); ?></a></h1>
			<? if ($POD->isAuthenticated()) {  ?>
				<ul class="post_actions">

					<? if ($doc->isEditable()) { ?>
						<li>
							<a href="<? $doc->write('editlink'); ?>" title="Edit this post" class="edit_button">Edit</a>
						</li>
					<? } ?>
					<? if ($doc->get('privacy')=="friends_only") { ?>
						<li class="friends_only_option">Friends Only</li>
					<? } else if ($doc->get('privacy')=="group_only") { ?>
						<li class="group_only_option">Group Members Only</li>
					<? } else if ($doc->get('privacy')=="owner_only") { ?>
						<li class="owner_only_option">Only you can see this.</li>
					<? } ?>
				</ul>
	<div class="clearer"></div>
			<? } ?>
			
			<? if ($doc->get('link')) { ?>
				<p>View Link: <a href="<? $doc->write('link'); ?>"><? $doc->write('link'); ?></a></p>
			<? } ?>		

			<? if ($doc->get('video')) {
				if ($embed = $POD->GetVideoEmbedCode($doc->get('video'),600,460,'true','always')) { 
					echo $embed; 
				} else { ?>
					<p>Watch Video: <a href="<? $doc->write('video'); ?>"><? $doc->write('video'); ?></a></p>
				<? }
			} ?>
			<? if ($img = $doc->files()->contains('file_name','img')) { ?>
				<p class="post_image"><img src="<? $img->write('resized'); ?>" /></p>
			<? } ?>	
			<? if ($doc->get('body')) { 
				$doc->writeFormatted('body');
			} ?>
						
			<? if ($doc->tags()->count() > 0){ ?>
				<p>
					<img src="<? $POD->templateDir(); ?>/img/tag_pink.png" alt="Tags" align="absmiddle" />
					<? $doc->tags()->output('tag',null,null); ?>
				</p>
			<? } ?>	
	</div>	
	<div id="comments">
		<!-- COMMENTS -->	
		<? 
		   	while ($comment = $doc->comments()->getNext()) { 
				$comment->output();	
			} 
		?>
		<!-- END COMMENTS -->
	</div>	
	<? if ($this->POD->isAuthenticated()) { ?>
		<div id="comment_form">
			<a name="reply"></a>
			<div class="column_7 last">
				<div class="feedback" id="spinner">
					Feedback
				</div>
			</div>
			<div class="column_6 last">
				<form method="post" id="add_comment" onsubmit="return addComment(<? $doc->write('id'); ?>,document.getElementById('comment').value);">
					<textarea name="comment" class="white" id="comment"></textarea>	
					<input type="submit" class="greenbutton" value="Post" />
				</form>
			</div>
			<div class="clearer"></div>		
			<script type="text/javascript">
				getComments(<? $doc->write('id'); ?>);
			</script>
		</div>
	<? } ?>	
</div>

<div class="column_4 last" id="post_info">
	<div id="member_info">
<!--	<div class="person_avatar">	<img src="<? $POD->templateDir(); ?>/img/lawgov.black.png"></div> -->
	
	<? $doc->author()->output('member_info'); ?>
	
		<div class="post-date">
			Posted on <? echo date_format(date_create($doc->get('date')),'l, M jS'); ?>
			(<? $doc->write('timesince'); ?>)
		</div>	
	<div class="clearer"></div>

	<? if ($doc->group()) {
		if ($POD->isAuthenticated()) {
			$member = $doc->group()->isMember($POD->currentUser());
		}

		?>
		<div class="column_padding"  id="post_group_navigation">
			<p>This is part of <? $doc->group()->permalink(); ?>.</p>

			<?
				$previous = $POD->getContents(array('groupId'=>$doc->group('id'),'id:lt'=>$doc->get('id')),'d.id DESC',1);
				if ($previous->success() && $previous->count() > 0) { 
					$previous = $previous->getNext();
					?>
					<a href="<? $previous->write('permalink');?>"  class="post_previous"><strong>&#171;&nbsp;Previous</strong>&nbsp;&nbsp;&nbsp;<? echo $POD->shorten($previous->get('headline'),100); ?></a>
			<? } ?>
			<?
				$next = $POD->getContents(array('groupId'=>$doc->group('id'),'id:gt'=>$doc->get('id')),'d.id ASC',1);	
				if ($next->success() && $next->count() > 0) {
					$next = $next->getNext(); 
				?>
					<a href="<? $next->write('permalink');?>" class="post_next"><strong>&#187;&nbsp;Next</strong>&nbsp;&nbsp;&nbsp;<?  echo $POD->shorten($next->get('headline'),80); ?></a>
			<? }  else { ?>
				<strong>&#187;&nbsp;Next</strong>&nbsp;&nbsp;&nbsp;This is the most recent post in <? $doc->group()->write('groupname'); ?>.
			<? } ?>
	

			<? if ($member == "manager" || $member=="owner") { ?>
				<p class="highlight">
					<strong>You are a manager of this group.</strong><br />
					<a href="<? $doc->group()->write('permalink'); ?>/remove?docId=<? $doc->write('id'); ?>">Remove this post from the group</a></p>
			<? } ?>
		</div>
	<? } ?>
	
			
</div>
</div>


