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

$jurisdiction = $POD->getContent(array('id'=>$doc->bug_target));
$violations = $POD->getLawGovViolations();

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
					<? } else if ($POD->libOptions('enable_bugs_authentication_creation')) { ?>
						<li><a id="togglewatch" href="<? $POD->siteRoot(); ?>/join?redirect=<?= $doc->permalink; ?>">Track</a></li>
						<li><a id="togglesub" href="<? $POD->siteRoot(); ?>/join?redirect=<?= $doc->permalink; ?>">Email me updates</a></li>
					<? } ?>
					<li><a id="rsslink" href="<?= $doc->permalink ?>/feed">RSS</a></li>
					<li><a id="tweetlink" href="<?= $doc->createTweet(); ?>">Tweet</a></li>
					<? if ($POD->isAuthenticated()) { ?>
						<li><a id="sendlink" href="<? $POD->siteRoot(); ?>/send?id=<?= $doc->id; ?>">Send</a></li>
						<li><?= $POD->toggleBot($doc->hasFlag('report',$POD->currentUser()),'toggleflag','Flagged','Flag a problem','method=toggleFlag&flag=report&content='.$doc->id); ?></li>
					<? } else if ($POD->libOptions('enable_bugs_authentication_creation')) { ?>
						<li><a id="toggleflag" href="<? $POD->siteRoot(); ?>/join?redirect=<?= $doc->permalink; ?>">Flag a problem</a></li>
					<? } ?>
				</ul>
				<div class="clearer"></div>
			</div>
			<div class="clearer"></div>			
			<div id="media_info">
				<div class="media_info_text">
					This bug was reported about <strong><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?q=<?= $jurisdiction->id; ?>"><?= $jurisdiction->headline; ?></a></strong> on <strong><?= date('M j, Y',strtotime($doc->report_date)); ?></strong><? if ($doc->reporter) { ?> by <strong><?= $doc->reporter; ?></strong><? } ?>.
				</div>
				<div class="clearer"></div>			
			</div>

			<div id="bug_body">
				<h2 style="display:inline">Bug details:&nbsp;</h2>
				
                                <ul>
					<? foreach ($violations as $violation) { ?>
                                        <? if ($doc->{'bug_lgv_' . $violation->id}) { ?><li><? echo $violation->permalink(); ?></li><? } ?>
					<? } ?>
                                </ul>

				<? $doc->write('body'); ?>
		
				<? if ($doc->get('additional_information')) {  ?>
					<h3>Additional Information:</h3>
					<? $doc->write('additional_information');
				} ?>
					
				<? if ($doc->files()->count() >0) { ?>
					<h3>Attached Files:</h3>
					<? foreach ($doc->files() as $file) { ?>
						<? if ($file->isImage()) { ?>
							<a href="<?= $file->original_file; ?>"><img src="<?= $file->thumbnail; ?>" border=0></a>				
						<? } else { ?>
							<a href="<?= $file->original_file; ?>"><img src="<? $POD->templateDir(); ?>/img/document_stroke_32x32.png" border="0" width="32" style="padding:9px;" /></a>
						<? } ?>
					<? } ?>
				<? } ?>
				
				<? if ($doc->jurisdiction_contact_street_address or $doc->jurisdiction_contact_city or $doc->jurisdiction_contact_county or $doc->jurisdiction_contact_state) { ?>
					<h3>Address:</h3>
                                        <p style="margin-left: 5%;">
                                        <? if ($doc->jurisdiction_contact_street_address) { ?><?= $doc->jurisdiction_contact_street_address; }?><br/>
                                        <? if ($doc->jurisdiction_contact_city) { ?><?= $doc->jurisdiction_contact_city; } ?><? if ($doc->jurisdiction_contact_city and $doc->jurisdiction_contact_state) { ?>,<? } ?>
                                        <? if ($doc->jurisdiction_contact_state) { ?><?= $doc->jurisdiction_contact_state; }?>
                                        <? if ($doc->jurisdiction_contact_zip) { ?><?= $doc->jurisdiction_contact_zip; }?><br/>
                                        <? if ($doc->jurisdiction_contact_county) { ?>(County: <?= $doc->jurisdiction_contact_county; ?>)<? } ?>
                                        </p>
				<? } ?>

				<? if ($doc->has_official_vendor == 'yes') { ?>
					<h3>Official Vendor:</h3>
                                        <ul>
                                        <? if ($doc->official_vendor_name) {
                                          ?><li><?= $doc->official_vendor_name;
                                          }?></li>
                                        <? if ($doc->official_vendor_url) {
                                          ?><li><?= $doc->official_vendor_url;
                                          }?></li>
                                        </ul>
                                        <? if ($doc->official_vendor_addtl_info) {
                                          ?><?= $doc->official_vendor_addtl_info;
                                          }?>
				<? } ?>

				<? if ($doc->has_terms_of_service == 'yes') { ?>
					<h3>Terms of Service:</h3>
                                        <? if ($doc->terms_of_service_url) {
                                          ?><p><a href="<?= $doc->terms_of_service_url; ?>" ><?= $doc->terms_of_service_url; ?></a></p><? } ?>
                                        <? if ($doc->terms_of_service_addtl_info) {
                                          ?><?= $doc->terms_of_service_addtl_info;
                                          }?>
				<? } ?>

				<? if ($doc->enabling_legislation_etc_info) { ?>
					<h3>Enabling legislation or other statutory authority:</h3>
                                        <p><?= $doc->enabling_legislation_etc_info; ?></p>
				<? } ?>

				<? if ($doc->get('media_outlet_contacted')=='yes') { ?>
					<? if ($doc->media_outlet_response) {?> 
		                              <h3>Response:</h3>
					      <? $doc->author()->permalink(); ?> has contacted <?= $jurisdiction->bugTargetBrowseLink(); ?> and received the following response:
					<div id="media_outlet_response">
						<? $doc->write('media_outlet_response'); ?>
					<? } else { ?>
	                                     <h3>Contacted:</h3>
                                             <? $doc->author()->permalink(); ?> has contacted <?= $jurisdiction->bugTargetBrowseLink(); ?>, but reported no response.
                                        <? } ?>
					</div>
				<? } else { ?>
					<p><? $doc->author()->permalink(); ?> has not contacted <?= $jurisdiction->bugTargetBrowseLink(); ?></p>
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
		<? if ($POD->isAuthenticated()) {?><a href="#reply" class="littlebutton">Leave a comment</a><? } else if ($POD->libOptions('enable_bugs_authentication_creation')) { ?><a href="<? $POD->siteRoot(); ?>/join?redirect=<?= $doc->permalink; ?>" class="littlebutton">Leave a comment</a><? } ?>
		<div class="clearer"></div>		
		<!-- COMMENTS -->	
		<!-- <h2>Discussion</h2> -->
		<? 
			$comments->output('comment');
		?>
		<!-- END COMMENTS -->
	</div>	
	<? if ($this->POD->isAuthenticated() && !preg_match("/closed/",$doc->bug_status)) { ?>
		<div id="comment_form">
			<a name="reply"></a>
				<div class="feedback" id="spinner">
					<p style="margin:0px;" class="right_align byline">You are logged in as <? $POD->currentUser()->permalink(); ?>.&nbsp;|&nbsp;<a href="<? $POD->siteRoot(); ?>/logout" style="font-style: bold">Log&nbsp;out</a></p>
					Comment
				</div>
				<form method="post" id="add_comment" class="valid">
					<p class="input"><textarea name="comment" class="white text required" id="comment"></textarea></p>
					<p><input type="submit" value="Post" class="greenbutton" /></p>
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
		<? } else if ($POD->libOptions('enable_bugs_authentication_creation')) { ?>
			<p>
				<a href="<? $POD->siteRoot(); ?>/join?redirect=<?= $doc->permalink; ?>" id="metoo">I think this is a bug too!</a>
			</p>	
			<p id="metoo_counter"><?= $POD->pluralize($doc->flagCount('metoo'),'@number person thinks this is a bug','@number people think this is a bug'); ?></p>		
		<? } ?>

		<? if ($doc->isEditable()) { ?>
			<p><a href="<? $doc->write('editlink'); ?>" title="Edit this bug" class="edit_bug_button">Edit this bug</a></p>
			<? if ($POD->currentUser()->adminUser) { ?>
				<p><?= $POD->toggleBot($doc->hasFlag('featured'),'togglefeatured','Stop featuring this bug','Feature this bug','method=toggleFlag&type=global&flag=featured&content='.$doc->id); ?></p>
			<? } ?>
		<? } ?>

	</div>

	
	<? $POD->output('sidebars/recent_bugs'); ?>
	
	<? $POD->output('sidebars/browse'); ?>
	
</div>

