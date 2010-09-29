<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/content/editform.php
* Default content add/edit form used by the core_usercontent module
* Customizing the fields in this form will alter the information stored!
* Use this file as the basis for new content type forms
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/new-content-type
/**********************************************/

$bug_types = $POD->bugTypes();

if ($doc->saved()) {
	$media_outlet = $POD->getContent(array('id'=>$doc->media_outlet));
}

// after 15 minutes, users can only edit the last little piece.
$minutes = 1;
if (!$doc->saved() || $POD->currentUser()->adminUser || (time() - strtotime($doc->date) < ($minutes*60))) {
	$editable = true;
} else {
	$editable = false;
}


	$instructions_report = $POD->getContent(array('stub'=>'instructions-report-bug'));
	$instructions_what = $POD->getContent(array('stub'=>'instructions-what-bug'));
	$instructions_why = $POD->getContent(array('stub'=>'instructions-why-bug'));
	$instructions_status = $POD->getContent(array('stub'=>'instructions-status-bug'));
	$instructions_edit = $POD->getContent(array('stub'=>'instructions-edit-bug'));

	$instructions_survey = $POD->getContent(array('stub'=>'instructions-survey'));
	$instructions_survey_thanks = $POD->getContent(array('stub'=>'instructions-survey-thanks'));
	


?>

<ul id="bug_tabs">
	<? if (!$doc->saved()) { ?>
	<li id="tab_report" class="active">
		<a href="#" onclick="return tabClick('report');">Report a Bug</a>
	</li>
	<li id="tab_what">
		<a href="#" onclick="return tabClick('what');">Bug Details</a>
	</li>
	<li id="tab_why">
		<a href="#" onclick="return tabClick('why');">Supporting Info</a>
	</li>
	<li id="tab_status">
		<a href="#" onclick="return tabClick('status');">Status</a>
	</li>
	<? } else if ($doc->bugIsOpen()) { ?>
		<li id="tab_edit" class="active">
			Edit Bug
		</li>
	<? } else { ?>
		<li id="tab_closed" class="active">
			Closed Bug
		</li>
	<? } ?>
	<div class="clearer"></div>
</ul>
<div id="bug_form">
	<form action="<? $doc->write('editpath'); ?>" method="post" id="bug" enctype="multipart/form-data" onsubmit="return submitBug();">
		<? if ($doc->get('id')) { ?>
			<input type="hidden" name="id" id="bug_id" value="<? $doc->write('id'); ?>" />
			<input type="hidden" name="redirect" value="<? $doc->write('permalink'); ?>" />
		<? } ?>
		
		<? if ($doc->bugIsOpen()) { ?>
			<? if ($editable) { ?>		
	
				<input type="hidden" name="type" value="bug" />		
	
				<fieldset id="report">
					<legend>Report a Bug</legend>
					
					<? $instructions_report->output('interface_text'); ?>
	
		
					<? if ($doc->saved() && $POD->isAuthenticated() && $POD->currentUser()->adminUser) { ?>
	
					<hr />
	
					<h3>List View</h3>
					<p>
						Since you are an administrator, you can specify a title and summary that will override the 
						values specified by the original bug reporter.
					</p>
			
					<p class="input">
						<label for="override_headline">Display Headline:</label>
						<input name="meta_override_headline" id="override_headline" value="<? $doc->htmlspecialwrite('override_headline'); ?>" class="text" title="This will appear in list view instead of the headline below" />				
					</p>		
					<p class="input">
						<label for="summary">Summary:</label>
						<textarea name="meta_summary" id="summary" title="This will appear instead of the bug explanation" class="text tinymce"><? $doc->htmlspecialwrite('summary'); ?></textarea>
					</p>		
					
					<hr />
					
					<? } ?>
	
	
	
					<p class="input">
						<label for="headline">Name This Bug <span class="required">*</span></label>
						<input name="headline" id="headline" value="<? $doc->htmlspecialwrite('headline'); ?>" length="50" class="text required" title="Try to summarize the key problem in 10 words or less"/>					
					</p>
					
					<p class="input" id="media_outlet_search">
							<label for="media_outlet">Media outlet where this story appeared: <span class="required">*</label>
							<input name="media_outlet" id="media_outlet_q" class="text required" value="<? if ($media_outlet) { $media_outlet->htmlspecialwrite('headline'); }  if ($doc->suggested_outlet) { $doc->htmlspecialwrite('suggested_outlet'); } ?>" onblur="mo_newcheck();" title="Pick an outlet from the list, or suggest a new outlet by entering its full name." />
							<input name="meta_media_outlet" type="hidden" 3value="<? $doc->media_outlet; ?>" id="media_outlet_id" />
					</p>
		
					<div  id="media_outlet_new" style="display:none;">
						<p class="input"><strong>You are the first person to report a bug about this media outlet.</strong></p>
					</div>
					
					<p class="input">
						<label for="reporter">
							Name(s) of who wrote or created the story, if you know.
						</label>
						<input type="text" name="meta_reporter" value="<? $doc->htmlspecialwrite('reporter'); ?>" class="text" />
					</p>
		
					<p class="input nextbutton"><a href="#who" class="littlebutton" onclick="return nextSection('report','what');">Next</a></p>
				</fieldset>
	
				<a name="what"></a>
				<fieldset id="what" style="display: none;">
					<legend>What?</legend>
				
					<? $instructions_what->output('interface_text'); ?>
				
					<p class="input">
						<label for="bug_type">
							What type of bug is this? <span class="required">*</span>
						</label>
						<select name="meta_bug_type" id="bug_type" class="text required">
							<option value="">Please pick a category for your bug</option>
							<? foreach ($bug_types as $bug_type) { ?>
								<option value="<?= $bug_type->headline; ?>" <? if ($doc->bug_type==$bug_type->headline) {?>selected<? } ?>><?= $bug_type->headline; ?></option>
							<? } ?>	
						</select>
					</p>
				
					<p class="input">
						<label for="body">
							Explain the error in this story. <span class="required">*</span>
						</label>
						<textarea name="body" id="bug_body" class="text tinymce required"><? $doc->htmlspecialwrite('body'); ?></textarea>
					</p>
	
					<p class="input">
						<label for="link">
							 If the bug is present in an online version of this story, please provide a link to the story.
						</label>
						<input type="text" name="link" value="<?= $doc->link; ?>" class="text" />
					</p>
	
					<p class="input">
						<label for="date">When did this bug appear? <span class="required">*</span></label>
						<input type="text" name="meta_report_date" id="report_date" class="text required dpDate" value="<? if ($doc->report_date) { echo date('m/d/Y',strtotime($doc->report_date)); } else { echo date("m/d/Y"); }  ?>" />
						<script type="text/javascript">
							$('#report_date').datepick({navigationAsDateFormat: true, prevText: '< M', currentText: 'M y', nextText: 'M >',changeMonth: false, changeYear: false,mandatory:true});
						</script>
					</p>
	
					<p class="input nextbutton"><a href="#why" class="littlebutton" onclick="return nextSection('what','why');">Next</a></p>
				</fieldset>
	
				<a name="why"></a>
				<fieldset id="why" style="display: none;">
					<legend>Why?</legend>
					<? $instructions_why->output('interface_text'); ?>
	
	
					<p class="input">
						<label for="supporting_evidence">
							Supporting information:
						</label>
						<textarea name="meta_supporting_evidence" class="text tinymce"><? $doc->htmlspecialwrite('supporting_evidence'); ?></textarea>
					</p>
					
					<p class="input">
						<label for="file1">Attach File</label>
					</p>
					<div id="files">
						<? if ($doc->saved() && $doc->files()->count() > 0) {
							$doc->files()->output('input.file');
							?>
							<script type="text/javascript">
								FILE_COUNTER = <?= ($doc->files()->count()+1) ?>;	
							</script>
						<? } ?>
						<script type="text/javascript">
							addFile();
						</script>
					</div>
					<div class="clearer"></div>
					
					<p class="input">
						<label for="file2">&nbsp;</label>
						<a href="#" onclick="return addFile()";>Add Another File</a>
					</p>
	
					<p class="input nextbutton"><a href="#status" class="littlebutton" onclick="return nextSection('why','status');">Next</a></p>
	
	
				</fieldset>
			<? } ?>

			<a name="status"></a>
			<fieldset id="status" style="display: none;">
				<legend>Bug Status</legend>

				<? if (!$doc->saved()) { 
					$instructions_status->output('interface_text');
				} else { 				
					$instructions_edit->output('interface_text');
				} ?>
				
				<? if ($doc->saved()) { ?>
					<p class="input">
						<label for="bug_status">Status:</label>
						<? $current_status = $doc->bug_status ? $doc->bug_status : 'open'; 
							if (!$POD->currentUser()->adminUser && preg_match("/closed/",$current_status)) { 
								echo ucwords(preg_replace("/\:/",": ",$current_status));
								?>
								<input type="hidden" name="meta_bug_status" value="<?= $current_status; ?>" />
								
							<? } else { ?>				
								<select name="meta_bug_status">
									<option value="<?= $current_status ?>"><?= ucwords(preg_replace("/\:/",": ",$current_status)); ?></option>
									<option value="closed:corrected" <? if ($doc->bug_status=='closed:corrected') {?>selected<? } ?>>Closed: Corrected</option>
									<option value="closed:withdrawn" <? if ($doc->bug_status=='closed:withdrawn') {?>selected<? } ?>>Closed: Withdrawn</option>
									<? if ($POD->currentUser()->adminUser) { ?>
										<option value="closed:off topic" <? if ($doc->bug_status=='closed:off topic') {?>selected<? } ?>>Off Topic</option>
										<option value="closed:unresolved" <? if ($doc->bug_status=='closed:unresolved') {?>selected<? } ?>>Unresolved</option>						
										<option value="open" <? if ($doc->bug_status=='open') {?>selected<? } ?>>Open</option>						
										<option value="open:responded to" <? if ($doc->bug_status=='open:responded to') {?>selected<? } ?>>Open: Responded to</option>						
										<option value="open:under discussion" <? if ($doc->bug_status=='open:under discussion') {?>selected<? } ?>>Open: Under Discussion</option>						
									<? } ?>
								</select>&nbsp;&nbsp;<a href="/pages/status-explanation" target="_new">What do these mean?</a>
							<? } ?>				
					</p>
					
					<? if ($POD->currentUser()->adminUser) { ?>
						<p class="input"><input type="checkbox" name="sendSurveyEmail" checked /> Send survey reminder email to <? $doc->author()->write('nick'); ?> if I close this bug?</p>
					<? } ?>
					
				<? } ?>
			
				<p class="input">
					<label for="">Have you contacted this media outlet?</label>
					<input type="radio" name="meta_media_outlet_contacted" value="yes" id="contacted_yes" onchange="chcontact();" <? if ($doc->media_outlet_contacted=="yes") {?>checked<? } ?>> Yes
					<input type="radio" name="meta_media_outlet_contacted" value="no" id="contacted_no" onchange="chcontact();"<? if ($doc->media_outlet_contacted=="no" || !$doc->saved()) {?>checked<? } ?>> No
				</p>
				
				<p class="input" id="media_response" <? if (!$doc->saved() || $doc->media_outlet_response=='') { ?>style="display:none;"<? }?>>
					<label for="">What was the media outlet's response?</label>
					<textarea name="meta_media_outlet_response" class="text tinymce"><? $doc->htmlspecialwrite('media_outlet_response'); ?></textarea>
				</p>
			
				<p class="input" id="save_button"><input type="submit" class="button" value="Save Bug" /></p>
				<div id="saving_progress" style="display: none;">
					<strong>Saving...</strong>
					<br />
					<img src="<? $POD->templateDir(); ?>/img/ajax-loader.gif" align="absmiddle" />
				</div>

			</fieldset>
			
		<? } else { // if is open, else is closed ?>
		
			<fieldset id="survey">
				<h2>The bug <? $doc->permalink(); ?> is marked as closed.</h2>
	
				<? if (!$doc->surveyed) { ?>
					<? $instructions_survey->output('interface_text'); ?>
					
					<p class="radio">
						<label for="outcome_survey" class="label">How satisfied are you with the outcome of this bug?</label>
						<input type="radio"  class="radio required" name="outcome_survey" value="satisfied" id="oss" /> <label for="oss">I'm very satisfied</label><br />
						<input type="radio" class="radio"  name="outcome_survey" value="ok" id="oso"> <label for="oso">I'm ok with it</label><br />
						<input type="radio" class="radio" name="outcome_survey" value="not" id="osn"> <label for="osn">I'm not satisfied at all</label>
					</p>
	
					<p class="radio">
						<label class="label" for="response_survey">How satisfied are you with the response of the news outlet responsible for this bug?</label>
						<input type="radio" class="radio required"  name="response_survey" value="satisfied" id="rss"> <label for="rss">I'm very satisfied</label><br />
						<input type="radio" class="radio" name="response_survey" value="ok" id="rso"> <label for="rso">I'm ok with it</label><br />
						<input type="radio" class="radio" name="response_survey" value="not" id="rsn"> <label for="rsn">I'm not satisfied at all</label>
					</p>
	
	
					<p class="radio">
						<label class="label" for="trust_survey">Has your trust in the news outlet changed as a result of this process?</label>
						<input type="radio" class="radio required" name="trust_survey" value="more" id="tsm"> <label for="tsm">I trust it more</label><br />
						<input type="radio" class="radio" name="trust_survey" value="same" id="tss"> <label for="tss">It's the same as before</label><br />
						<input type="radio" class="radio" name="trust_survey" value="less" id="tsl"
						> <label for="tsl">I trust it less</label>
					</p>
					
					<p class="input">
						<label for="survey_comments">Do you have any further comments?</label>
						<textarea id="survey_comments" name="survey_comments" class="text tinymce."></textarea>
					</p>
					
					<p class="input">
						<input type="submit" name="survey" class="button" onclick="return validateSurvey();" value="Submit answers" />
					</p>
					<div class="clearer"></div>
				<? } else { ?>

				<? $instructions_survey_thanks->output('interface_text'); ?>
	
				<? } ?>
				<p style="text-align:center;">Does something new need to be added to this bug?  <a href="?id=<?= $doc->id; ?>&reopen=1">Re-open this bug</a>.</p>

			
			</fieldset>
		<? } // if is closed ?>

	</form>

</div>


<? if ($doc->saved()) { ?>
	<script type="text/javascript">
			$('fieldset').show();
			$('p.nextbutton').hide();
	</script>
<? } ?>


<script>

	function chcontact() { 
		if ($('#contacted_yes').attr('checked')) { 
			$('#media_response').show();
		} else {
			$('#media_response').hide();	
		}
		return false;
	}

	function mo_outletupdate(json) {
	
		if (json.id) {
			$('#media_outlet_id').val(json.id);
		//	$('#media_outlet_new').hide();
		} else {
			$('#media_outlet_id').val('');
		//	$('#media_outlet_new').show();		
		}
	
	}
	function mo_newcheck() { 
		var val = $('#media_outlet_q').val();
		$.getJSON('/api?method=mediaoutletcheck&outlet='+escape(val),mo_outletupdate);
	}

	$().ready(function() { 
	
			$('#media_outlet_q').autocomplete('/api',{
					autoFill: false,
					selectFirst: false,
					mustMatch: false,
					extraParams: { method: 'mediaoutletautocomplete' }
				}
			).result(function(event,data,formatted) { 
				mo_newcheck();
			})
	
		}
	);

</script>