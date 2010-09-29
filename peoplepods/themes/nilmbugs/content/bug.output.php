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

$media_outlet = $POD->getContent(array('id'=>$doc->media_outlet));

$subscribed = false;
if ($POD->isAuthenticated()) { 
	
	$subs = $POD->getContents(array('userId'=>$POD->currentUser()->id,'type'=>'subscription','parentId'=>$doc->id));
	$subscribed = ($subs->totalCount() > 0);
}

?>
<div class="column_8">
	<? if ($_GET['msg'] == "Bug saved!") { 
		$doc->output('bug.new_bug_instructions');
	} else if ($_GET['msg']) { ?>
	
		<div class="info">
			<?= strip_tags($_GET['msg']); ?>
		</div>

	<? } ?>
	<div id="bug_output">
	

			<div id="bug_info">
				<div class="bug_status">
					Bug #<?= $doc->id; ?>
					<img src="<? $POD->templateDir(); ?>/img/status_icons/<?= $POD->tokenize($doc->bug_status); ?>_50.png" alt="<?= htmlspecialchars($doc->bug_status); ?>" title="<?= htmlspecialchars($doc->bug_status); ?>" width="50" height="50" />
				</div>
				
				<h1><?= $doc->bugHeadline(); ?></h1>
				<span class="byline">Reported by <? $doc->author()->permalink(); ?> on <strong><?= date('M j, Y',strtotime($doc->date)); ?></strong></span>
				<ul id="bug_actions">
					<? if ($POD->isAuthenticated()) { ?>
						<li><?= $POD->toggleBot($POD->currentUser()->isWatched($doc),'togglewatch','Stop tracking','Track','method=toggleWatch&content='.$doc->id,null,null,'Stop tracking this bug on your My Bugs dashboard','Track this bug on your My Bugs dashboard'); ?></li>
						<li><?= $POD->toggleBot($subscribed,'togglesub','Stop receiving updates','E-mail me updates','method=toggleSub&contentId='.$doc->id); ?></li>
					<? } else { ?>
						<li><a id="togglewatch" href="<? $POD->siteRoot(); ?>/join?redirect=<?= $doc->permalink; ?>">Track</a></li>
						<li><a id="togglesub" href="<? $POD->siteRoot(); ?>/join?redirect=<?= $doc->permalink; ?>">Email me updates</a></li>
					<? } ?>
					<li><a id="rsslink" href="<?= $doc->permalink ?>/feed">RSS</a></li>
					<li><a id="tweetlink" href="<?= $doc->createTweet(); ?>">Tweet</a></li>
					<? if ($POD->isAuthenticated()) { ?>
						<li><a id="sendlink" href="<? $POD->siteRoot(); ?>/send?id=<?= $doc->id; ?>">Send</a></li>
						<li><?= $POD->toggleBot($doc->hasFlag('report',$POD->currentUser()),'toggleflag','Flagged','Flag a problem','method=toggleFlag&flag=report&content='.$doc->id); ?></li>
					<? } else { ?>
						<li><a id="toggleflag" href="<? $POD->siteRoot(); ?>/join?redirect=<?= $doc->permalink; ?>">Flag a problem</a></li>
					<? } ?>
				</ul>
				<div class="clearer"></div>
			</div>
			<div class="clearer"></div>			
			<div id="media_info">
				<? $media_outlet->output('outlet.widget'); ?>
				<div class="media_info_text">
					This bug appeared in a news report published by <strong><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?q=<?= $media_outlet->id; ?>"><?= $media_outlet->headline; ?></a></strong> on <strong><?= date('M j, Y',strtotime($doc->report_date)); ?></strong><? if ($doc->reporter) { ?> by <strong><?= $doc->reporter; ?></strong><? } ?>.
					<? if ($doc->link) { ?><strong><a href="<?= $doc->link; ?>">View the original news report</a></strong>.<? } ?>
				</div>
				<div class="clearer"></div>			
			</div>

			<div id="bug_body">
				<strong style="display:inline">Bug Type:&nbsp;</strong> <?= $doc->bug_type; ?>
				
				<? $doc->write('body'); ?>
		
				<? if ($doc->get('supporting_evidence')) {  ?>
					<h2>Supporting Information:</h2>
					<? $doc->write('supporting_evidence');
				} ?>
					
				<? if ($doc->files()->count() >0) { ?>
					<h2>Attached Files:</h2>
					<? foreach ($doc->files() as $file) { ?>
						<? if ($file->isImage()) { ?>
							<a href="<?= $file->original_file; ?>"><img src="<?= $file->thumbnail; ?>" border=0></a>				
						<? } else { ?>
							<a href="<?= $file->original_file; ?>"><img src="<? $POD->templateDir(); ?>/img/document_stroke_32x32.png" border="0" width="32" style="padding:9px;" /></a>
						<? } ?>
					<? } ?>
				<? } ?>
				
				<h2>Response</h2>
				<? if ($doc->get('media_outlet_contacted')=='yes') { ?>
					<p><? $doc->author()->permalink(); ?> has contacted <?= $media_outlet->mediaOutletBrowseLink(); ?>
						<? if ($doc->media_outlet_response) {?> and received the following response.<? } ?>
					</p>
					<div id="media_outlet_response">
					<? if ($doc->media_outlet_response) { 
						$doc->write('media_outlet_response');
					} ?>
					</div>
	
				<? } else { ?>
					<p><? $doc->author()->permalink(); ?> has not contacted <?= $media_outlet->mediaOutletBrowseLink(); ?></p>
				<? } ?>
			</div>

			
			<h2>Bug History</h2>			

			<div id="bug_history">
				<? 
					$status = new Stack($POD,'comment');
					$comments = new Stack($POD,'comment');
					foreach ($doc->comments() as $comment) {
				   		if ($comment->type == 'status') { 
							$status->add($comment);
						} else {
							$comments->add($comment);
						}
					} 
					
					$status->output('bug.history');
				?>
			</div>
	</div>	

	<div id="comments">
		<h2>Discussion <? if ($POD->isAuthenticated()) {?><a href="#reply" class="with_right_float littlebutton">Leave a comment</a><? } else { ?><a href="<? $POD->siteRoot(); ?>/join?redirect=<?= $doc->permalink; ?>" class="with_right_float littlebutton">Leave a comment</a><? } ?></h2>
		<!-- COMMENTS -->	
		<? 
			$comments->output('comment');
		?>
		<!-- END COMMENTS -->
	</div>	
	<? if ($this->POD->isAuthenticated() && !preg_match("/closed/",$doc->bug_status)) { ?>
		<div id="comment_form">
			<a name="reply"></a>
				<h3>Leave a comment</h3>
				<form method="post" id="add_comment" class="valid">
					<p style="margin:0px;" class="right_align">You are logged in as <? $POD->currentUser()->permalink(); ?>.  <a href="<? $POD->siteRoot(); ?>/logout">Logout</a></p>
					<p class="input"><textarea name="comment" class="text required" id="comment"></textarea></p>
					<div id="comment_extras">
						<p>Are you a direct participant in this story?</P>
						<p><input type="checkbox" name="journalist" id="journalist"> <label for="journalist">I am the journalist responsible.</label></p>
						<p><input type="checkbox" name="participant" id="participant"> <label for="participant">I am mentioned in this report or was interviewed for it.</label></p>
					</div>
					<p><input type="submit" value="Post Comment" class="button" /></p>
				</form>
			<div class="clearer"></div>		
		</div>
	<? } ?>	
</div>

<div class="column_4 last" id="post_info">


	<div class="sidebar">
	

		<? if ($POD->isAuthenticated() && $POD->currentUser()->id==$doc->author()->id) { ?>
			<p><strong>You reported this bug.</strong></p>
			<p id="metoo_counter"><?= $POD->pluralize($doc->flagCount('metoo'),'@number other person thinks this is a bug','@number other people think this is a bug'); ?></p>
		<? } else if ($POD->isAuthenticated()) { ?>
				<?= $POD->toggleBot($doc->hasFlag('metoo',$POD->currentUser()),'metoo','I think this is a bug too!','I think this is a bug too!','method=toggleFlag&flag=metoo&content=' . $doc->id,'metoocounter'); ?>		
			<p id="metoo_counter"><?= $POD->pluralize($doc->flagCount('metoo'),'@number person thinks this is a bug','@number people think this is a bug'); ?></p>
		<? }  else { ?>
			<p>
				<a href="<? $POD->siteRoot(); ?>/join?redirect=<?= $doc->permalink; ?>" id="metoo">I think this is a bug too!</a>
			</p>	
			<p id="metoo_counter"><?= $POD->pluralize($doc->flagCount('metoo'),'@number person thinks this is a bug','@number people think this is a bug'); ?></p>		
		<? } ?>

		<? if ($doc->isEditable()) { ?>
			<p><a href="<? $doc->write('editlink'); ?>" title="Edit this post" class="edit_button">Edit this bug</a></p>
			<? if ($POD->currentUser()->adminUser) { ?>
				<p><?= $POD->toggleBot($doc->hasFlag('featured'),'togglefeatured','Stop featuring this bug','Feature this bug','method=toggleFlag&type=global&flag=featured&content='.$doc->id); ?></p>
			<? } ?>
		<? } ?>

	</div>

	
	<? $POD->output('sidebars/recent_bugs'); ?>
	
	<? $POD->output('sidebars/browse'); ?>
	
</div>

