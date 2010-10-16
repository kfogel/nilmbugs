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

$violations = $POD->getLawGovViolations();

if ($doc->saved()) {
	$jurisdiction = $POD->getContent(array('id'=>$doc->bug_target));
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
	$instructions_addtl_info = $POD->getContent(array('stub'=>'instructions-addtl-info'));
	$instructions_status = $POD->getContent(array('stub'=>'instructions-status-bug'));
	$instructions_edit = $POD->getContent(array('stub'=>'instructions-edit-bug'));

	$instructions_survey = $POD->getContent(array('stub'=>'instructions-survey'));
	$instructions_survey_thanks = $POD->getContent(array('stub'=>'instructions-survey-thanks'));
	


?>

<ul id="bug_tabs">
	<? if (!$doc->saved()) { ?>
	<li id="tab_report" class="active">
		<a href="#" onclick="return tabClick('report');">Describe Jurisdiction</a>
	</li>
	<li id="tab_what">
		<a href="#" onclick="return tabClick('what');">Describe Violation</a>
	</li>
	<li id="tab_addtl_info">
		<a href="#" onclick="return tabClick('addtl_info');">Additional Info</a>
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
					<legend>Describe Jurisdiction</legend>
					
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
						<label for="headline">Name of Bug<span class="required">*</span></label>
						<input name="headline" id="headline" value="<? if ($doc->htmlspecialwrite('headline')) { echo $doc->htmlspecialwrite('headline'); } else { echo 'law bug'; } ?>" length="50" class="text required" title='You can leave this as "law bug", or if you wish you can replace it with a brief description of the problem (try for 10 words or fewer).'/>
					</p>
					
					<p class="input" id="jurisdiction_search">
							<label for="bug_target">Name of Jurisdiction<span class="required">*</label>
							<input name="bug_target" id="jurisdiction_q" class="text required" value="<? if ($jurisdiction) { $jurisdiction->htmlspecialwrite('headline'); }  if ($doc->suggested_outlet) { $doc->htmlspecialwrite('suggested_outlet'); } ?>" onblur="mo_newcheck();" title="Please enter the jurisdiction's full name.  If there are already bug reports about this jurisdiction, try to write the jurisdiction's name the same way those bugs do." />
							<input name="meta_bug_target" type="hidden" value="<? $doc->bug_target; ?>" id="jurisdiction_id" />
					</p>
		
					<div  id="jurisdiction_new" style="display:none;">
						<p class="input"><strong>You are the first person to report a bug about this jurisdiction.</strong></p>
					</div>
					
					<p class="input">
						<label for="jurisdiction_contact">
							Point of Contact
						</label>
						<input type="text" name="meta_jurisdiction_contact" value="<? $doc->htmlspecialwrite('jurisdiction_contact'); ?>" class="text" title='For example, "Attorney General Opinions", or the name/address of the official point of contact (e.g., chief judge, clerk, solicitor general, secretary of state).'/>

						<label for="jurisdiction_contact_mailing_address">
							Mailing Address
						</label>
						<textarea rows="5" cols="40" name="meta_jurisdiction_contact_mailing_address" id="jurisdiction_contact_mailing_address" title="The mailing address for the Point of Contact." ><? $doc->htmlspecialwrite('jurisdiction_contact_mailing_address'); ?></textarea>
					</p>
		
					<p class="input">
						<label for="jurisdiction_url">
							Jurisdiction URL
						</label>
						<input type="text" name="meta_jurisdiction_url" value="<? $doc->htmlspecialwrite('jurisdiction_url'); ?>" class="text" title='The main URL (web page) for the jurisdiction, if any.  Leave blank if none or unknown.'/>
					</p>
		
					<p class="input">
						<label for="jurisdiction_level">
							What level is this jurisdiction? <span class="required">*</span>
						</label>
						<select name="meta_jurisdiction_level" id="jurisdiction_level" class="text required">
							<option value="Federal" >Federal</option>
							<option value="State" >State</option>
							<option value="Local" >Local</option>
							<option value="Other" >Other</option>
						</select>
					</p>

					<p class="input">
						<label for="jurisdiction_branch">
							What branch is this jurisdiction? <span class="required">*</span>
						</label>
						<select name="meta_jurisdiction_branch" id="jurisdiction_branch" class="text required">
							<option value="Executive" >Executive</option>
							<option value="Judicial" >Judicial</option>
							<option value="Legislative" >Legislative</option>
						</select>
					</p>

					<p class="input nextbutton"><a href="#who" class="littlebutton" onclick="return nextSection('report','what');">Next</a></p>
				</fieldset>
	
				<a name="what"></a>
				<fieldset id="what" style="display: none;">
					<legend>What?</legend>
				
					<? $instructions_what->output('interface_text'); ?>
				
					<p class="input">
 						<label for="violations">
							Mark any violations of <a href="http://public.resource.org/law.gov/" >Law.Gov</a> Principles:
 						</label>
						<? foreach ($violations as $violation) { ?>
                                                <!-- Use each violation object's ID to distinguish it when attaching it to a bug via a meta field. -->
                                                        <input type="checkbox" name="meta_bug_lgv_<?= $violation->id; ?>" id="bug_lgv_<?= $violation->id; ?>" value="<?= $violation->stub; ?>">&nbsp;<? echo $violation->permalink(); ?></input><br/>
						<? } ?>
					</p>
				
					<p class="input">
						<label for="body">
							Please describe the nature of the violation(s).<span class="required">*</span>
						</label>
						<textarea name="body" id="bug_body" class="text tinymce required"><? $doc->htmlspecialwrite('body'); ?></textarea>
					</p>
	
					<p class="input">
						<label for="date">When was this bug discovered? <span class="required">*</span></label>
						<input type="text" name="meta_report_date" id="report_date" class="text required dpDate" value="<? if ($doc->report_date) { echo date('m/d/Y',strtotime($doc->report_date)); } else { echo date("m/d/Y"); }  ?>" />
						<script type="text/javascript">
							$('#report_date').datepick({navigationAsDateFormat: true, prevText: '< M', currentText: 'M y', nextText: 'M >',changeMonth: false, changeYear: false,mandatory:true});
						</script>
					</p>
	
					<p class="input nextbutton"><a href="#addtl_info" class="littlebutton" onclick="return nextSection('what','addtl_info');">Next</a></p>
				</fieldset>
	
				<a name="addtl_info"></a>
				<fieldset id="addtl_info" style="display: none;">
					<legend>Additional Info</legend>
					<? $instructions_addtl_info->output('interface_text'); ?>
	
<!-- BEGIN Official Vendor -->
				<p class="input">
					<label for="">Is there an official vendor?</label>
					<input type="radio" name="meta_has_official_vendor" value="yes" id="has_official_vendor_yes" onchange="chofficialvendor();" <? if ($doc->has_official_vendor=="yes") {?>checked<? } ?>> Yes
					<input type="radio" name="meta_has_official_vendor" value="no" id="has_official_vendor_no" onchange="chofficialvendor();"<? if ($doc->has_official_vendor=="no" || !$doc->saved()) {?>checked<? } ?>> No
				</p>

				<p class="input" id="official_vendor_name" <? if (!$doc->saved() || $doc->official_vendor_name=='') { ?>style="display:none;"<? }?>>
					<label for="">Name:</label>
					<input type="text" name="meta_official_vendor_name" class="text tinymce"><? $doc->htmlspecialwrite('official_vendor_name'); ?></input>
				</p>

				<p class="input" id="official_vendor_url" <? if (!$doc->saved() || $doc->official_vendor_url=='') { ?>style="display:none;"<? }?>>
					<label for="">URL:</label>
					<input type="text" name="meta_official_vendor_url" class="text tinymce"><? $doc->htmlspecialwrite('official_vendor_url'); ?></input>
				</p>

				<p class="input" id="official_vendor_addtl_info" <? if (!$doc->saved() || $doc->official_vendor_addtl_info=='') { ?>style="display:none;"<? }?>>
						<label for="">Price or
						additional information:</label>
						<textarea name="meta_official_vendor_addtl_info" class="text tinymce"><? $doc->htmlspecialwrite('official_vendor_addtl_info'); ?></textarea>
				</p>
                                <br/>
<!-- END Official Vendor -->
<!-- BEGIN Terms of Service -->
				<p class="input">
					<label for="">Are there terms of service?</label>
					<input type="radio" name="meta_has_terms_of_service" value="yes" id="has_terms_of_service_yes" onchange="chtos();" <? if ($doc->has_terms_of_service=="yes") {?>checked<? } ?>> Yes
					<input type="radio" name="meta_has_terms_of_service" value="no" id="has_terms_of_service_no" onchange="chtos();"<? if ($doc->has_terms_of_service=="no" || !$doc->saved()) {?>checked<? } ?>> No
				</p>

				<p class="input" id="terms_of_service_url" <? if (!$doc->saved() || $doc->terms_of_service_url=='') { ?>style="display:none;"<? }?>>
					<label for="">URL:</label>
					<input type="text" name="meta_terms_of_service_url" class="text tinymce"><? $doc->htmlspecialwrite('terms_of_service_url'); ?></input>
				</p>
                                <br/>
<!-- END Terms of Service -->
<!-- BEGIN Enabling Legislation Etc -->
				<p class="input">
					<label for="">Is there enabling legislation for this jurisdiction or other statutory authority?</label>
					<input type="radio" name="meta_has_enabling_legislation_etc" value="yes" id="has_enabling_legislation_etc_yes" onchange="chenablinglegislation();" <? if ($doc->has_enabling_legislation_etc=="yes") {?>checked<? } ?>> Yes
					<input type="radio" name="meta_has_enabling_legislation_etc" value="no" id="has_enabling_legislation_etc_no" onchange="chenablinglegislation();"<? if ($doc->has_enabling_legislation_etc=="no" || !$doc->saved()) {?>checked<? } ?>> No
				</p>

				<p class="input" id="enabling_legislation_etc_info" <? if (!$doc->saved() || $doc->enabling_legislation_etc_info=='') { ?>style="display:none;"<? }?>>
					<label for="">Please describe, including URLs where possible:</label>
					<input type="text" name="meta_enabling_legislation_etc_info" class="text tinymce"><? $doc->htmlspecialwrite('enabling_legislation_etc_info'); ?></input>
				</p>
                                <br/>
<!-- END Enabling Legislation Etc -->
<!-- BEGIN Other Information -->
                                <p class="input">
						<label for="">Any other information we should know?</label>
						<textarea name="meta_additional_information" class="text tinymce"><? $doc->htmlspecialwrite('additional_information'); ?></textarea>
                                </p>
                                <br/>
<!-- END Other Information -->
<!-- BEGIN Attach File(s) -->
					<p class="input">
						<label for="file1">Attach file, if any</label>
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
<!-- END Attach File(s) -->
	
					<p class="input nextbutton"><a href="#status" class="littlebutton" onclick="return nextSection('addtl_info','status');">Next</a></p>
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
								</select>&nbsp;&nbsp;<a href="<? $POD->siteRoot(); ?>/pages/status-explanation" target="_new">What do these mean?</a>
							<? } ?>				
					</p>
					
					<? if ($POD->currentUser()->adminUser) { ?>
						<p class="input"><input type="checkbox" name="sendSurveyEmail" checked /> Send survey reminder email to <? $doc->author()->write('nick'); ?> if I close this bug?</p>
					<? } ?>
					
				<? } ?>
			
				<p class="input">
					<label for="">Have you contacted this jurisdiction?</label>
					<input type="radio" name="meta_media_outlet_contacted" value="yes" id="contacted_yes" onchange="chcontact();" <? if ($doc->media_outlet_contacted=="yes") {?>checked<? } ?>> Yes
					<input type="radio" name="meta_media_outlet_contacted" value="no" id="contacted_no" onchange="chcontact();"<? if ($doc->media_outlet_contacted=="no" || !$doc->saved()) {?>checked<? } ?>> No
				</p>
				
				<p class="input" id="jurisdiction_response" <? if (!$doc->saved() || $doc->media_outlet_response=='') { ?>style="display:none;"<? }?>>
					<label for="">What was the jurisdiction's response?</label>
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

	function chofficialvendor() { 
		if ($('#has_official_vendor_yes').attr('checked')) { 
			$('#official_vendor_name').show();
			$('#official_vendor_url').show();
			$('#official_vendor_addtl_info').show();
		} else {
			$('#official_vendor_name').hide();
			$('#official_vendor_url').hide();
			$('#official_vendor_addtl_info').hide();
		}
		return false;
	}

	function chtos() { 
		if ($('#has_terms_of_service_yes').attr('checked')) { 
			$('#terms_of_service_url').show();
		} else {
			$('#terms_of_service_url').hide();
		}
		return false;
	}

	function chenablinglegislation() { 
		if ($('#has_enabling_legislation_etc_yes').attr('checked')) { 
			$('#enabling_legislation_etc_info').show();
		} else {
			$('#enabling_legislation_etc_info').hide();
		}
		return false;
	}

	function chcontact() { 
		if ($('#contacted_yes').attr('checked')) { 
			$('#jurisdiction_response').show();
		} else {
			$('#jurisdiction_response').hide();	
		}
		return false;
	}

	function mo_outletupdate(json) {
	
		if (json.id) {
			$('#jurisdiction_id').val(json.id);
		//	$('#jurisdiction_new').hide();
		} else {
			$('#jurisdiction_id').val('');
		//	$('#jurisdiction_new').show();		
		}
	
	}
	function mo_newcheck() { 
		var val = $('#jurisdiction_q').val();
		$.getJSON('/api?method=mediaoutletcheck&outlet='+escape(val),mo_outletupdate);
	}

	$().ready(function() { 
	
			$('#jurisdiction_q').autocomplete('/api',{
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