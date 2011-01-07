		var WATCH_ON;
		var WATCH_OFF;
		var WATCH_LINK;
		var FLAG_ON;
		var FLAG_OFF;
		var FLAG_LINK;
		var FLAG_COUNTER;
		var FLAG_SINGULAR;
		var FLAG_PLURAL;
		var SUB_ON;
		var SUB_OFF;
		var SUB_LINK;
		var FILE_COUNTER = 1;



	$().ready( function() { 
	
	
	
		url = window.location.pathname;
		var tab = 'home';
		if (url.indexOf('bugs/edit') > -1) {
			tab = 'report';
		} else if (url.indexOf('bugs') > -1) { 
			tab = 'browse';
		} else if (url.indexOf('dashboard') > -1) { 
			tab = 'my';
		} else if (url.indexOf('pages/help') > -1) { 
			tab='help';
		} else if (url.indexOf('pages/about') > -1) { 
			tab='about';
		} else if (url.indexOf('pages/contact') > -1) { 
			tab='contact';
		} else if (url.indexOf('pages') > -1) { 
			tab='about';
		}
		$('#nav_'+tab).addClass('active');
	
	
	
		$('form.valid').validate();
		
		if( !((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) ||  (navigator.userAgent.match(/iPad/i)))) { 
		
			$('textarea.tinymce').tinymce({
				script_url : podRoot+'/themes/admin/js/tinymce/jscripts/tiny_mce/tiny_mce.js',
				theme: "advanced",
				width: "100%",
				valid_elements: "p,blockquote,h1,h2,h3,h4,h5,h6,ol,ul,li,br,em,strong,i,u,b,strike,a[href|target|title],img[src|width|height|alt|border|title]",
				plugins:"paste",
				paste_auto_cleanup_on_paste: true,
				paste_strip_class_attributes: "all",
				paste_remove_spans: true,
				paste_remove_styles: true,
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",	
				theme_advanced_buttons1: "bold,italic,separator,bullist,numlist,separator,link,unlink,separator,undo,separator,charmap",
				theme_advanced_buttons2: "",
				content_css : podRoot+"/themes/nilmbugs/tinymce.css"
			});
		}
		$('a.glossary').bt( {
			positions: ['right'],
		  fill: 'rgba(51, 204, 0, .8)',
		   shadow: true,
		    shadowOffsetX: 3,
		    shadowOffsetY: 3,
		    shadowBlur: 8,
		    shadowColor: 'rgba(0,0,0,.6)',
		    shadowOverlap: false,	
		    padding:10,
			 cssStyles: {color: '#FFF', fontWeight: 'bold', fontFamily: 'Arial, Helvetica', fontSize: '10px'}			
			}
		);
		$('p.input input').bt( {
			 trigger: ['focus', 'blur'],
			positions: ['right'],
		  fill: 'rgba(0, 0, 0, .8)',
		   shadow: true,
		    shadowOffsetX: 3,
		    shadowOffsetY: 3,
		    shadowBlur: 8,
		    shadowColor: 'rgba(0,0,0,.6)',
		    shadowOverlap: false,	
		    padding:10,
			 cssStyles: {color: '#C0B770', fontWeight: 'bold', fontFamily: 'Arial, Helvetica', fontSize: '10px'}
			}
		);
		$('p.input textarea').bt( {
			 trigger: ['focus', 'blur'],
			positions: ['right'],
		  fill: 'rgba(0, 0, 0, .8)',
		   shadow: true,
		    shadowOffsetX: 3,
		    shadowOffsetY: 3,
		    shadowBlur: 8,
		    shadowColor: 'rgba(0,0,0,.6)',
		    shadowOverlap: false,	
		    padding:10,
			 cssStyles: {color: '#C0B770', fontWeight: 'bold', fontFamily: 'Arial, Helvetica', fontSize: '10px'}
			}
		);


	});


	function markAsRead(id) {		
		$.getJSON(API+"?method=markAsRead&docId="+id);
	}

	function showComments(id) {
		markAsRead(id); 
		$('.recent_summary').show();
		$('.recent_comments').hide();
		$('#comments_'+id).show();
		$('#link_'+id).hide();
		return false;
	}

	function submitBug() { 
	
		if (!validateSection('report')) { 
			showFileSection('report');
			return false;
		} else {
			$('#tab_report').addClass('completed');	
		}
		if (!validateSection('what')) { 
			showFileSection('what');
			return false;
		} else {
			$('#tab_what').addClass('completed');	
		}
		if (!validateSection('addtl_info')) { 
			showFileSection('addtl_info');
			return false;
		} else {
			$('#tab_addtl_info').addClass('completed');	
		}
		if (!validateSection('status')) { 
			showFileSection('status');
			return false;
		} else {
			$('#tab_status').addClass('completed');	
		}
		
		$('#save_button').hide();
		$('#saving_progress').show();
		return true;		
	}

	
		function validateSurvey() { 
			if (typeof(eval(window)['tinyMCE']) != 'undefined') { 
				tinyMCE.triggerSave();
			}
			return ($('#bug').validate().element('#oss') && $('#bug').validate().element('#rss') && $('#bug').validate().element('#tsm') && $('#bug').validate().element('#oss') && $('#bug').validate().element('#survey_comments'));
		}
	
		function validateSection(sectionName) { 

			if (typeof(eval(window)['tinyMCE']) != 'undefined') { 
				tinyMCE.triggerSave();
			}
			if (sectionName=='report') { 
				if ($('#bug').validate().element('#headline') && $('#bug').validate().element('#jurisdiction_q')) {
					if ($('#jurisdiction_new').css('display')=='block') {
						if ($('#new_jurisdiction_print').attr('checked')||$('#new_jurisdiction_tv').attr('checked')||$('#new_jurisdiction_radio').attr('checked')||$('#new_jurisdiction_online').attr('checked')) {
							return true;
						} else {
							// display custom error
							return false;
						}
					} else {
						return true;
					}
				}
				return false;
			
			}
			if (sectionName=='what') { 
			
					if ($('#bug').validate().element('#bug_body') && $('#bug').validate().element('#report_date')) {
						return true;
					} else {
						return false;
					}
			
			}		
			return true;
		}

		function tabClick(sectionName) {
		
			if ($('#tab_'+sectionName).hasClass('completed')) { 
				return showFileSection(sectionName);
			} else {
				return false;
			}
		}
	
		function nextSection(currentSection,sectionName) { 
		
			if (validateSection(currentSection)) { 
				$('#tab_'+currentSection).addClass('completed');
				showFileSection(sectionName);
			}
		
		}
	
		function showFileSection(sectionName) { 
			sections = ['report','what','addtl_info','status'];
		
			passed = false;
				
			for (var x in sections) {			
				section = sections[x];	
				if (section == sectionName) { 
					if ($('#tab_'+section).hasClass('completed')) { 
						$('#tab_'+section).removeClass('complete');
						$('#tab_'+section).addClass('revisited');
					} else {
						$('#tab_'+section).removeClass('complete');
						$('#tab_'+section).addClass('active');					
					}
					passed = true;
				} else {
					$('#tab_'+section).removeClass('active');
					$('#tab_'+section).removeClass('revisited');

					if ($('#tab_'+section).hasClass('completed')) { 
						$('#tab_'+section).addClass('complete');
					}
				}	
				
			}			
			if (!$('#bug_id').val()) {
				$('fieldset').hide();
				$('#'+sectionName).show();
			}
		}
	
		function removeFile(contentId,fileId) { 

			command = API + "?method=removeFile&contentId="+contentId+"&fileId="+fileId;
			$.getJSON(command,refreshFiles);
					
			return false;
		
		}
		
		
		function refreshFiles(json) {
		
			if (json.error) {	
				alert(json.error);
			} else {
				
				if (json.html) { 
					
					$('#files').html(json.html);
				}
			}
		
		
		
		}


		function addFile() { 
			file = document.createElement('input');
			file.setAttribute('type','file');
			file.setAttribute('name','file'+FILE_COUNTER);
			file.setAttribute('class','file');
			file.setAttribute('id','file'+FILE_COUNTER);
			document.getElementById('files').appendChild(file);
			FILE_COUNTER++;
			return false;
		}


		function repairField(obj,val) {
			obj = $(obj);
			
			if (obj.val()==val) { 
				obj.val('');
				obj.css("color","#ffffff");
			}  else if (obj.val()=='') {
				obj.val(val);
				obj.css("color","#C0B770");
			}
		
		}


		function getComments(doc) { 
			var command = API + "?method=getComments&docId=" + doc;
		//	comment_ajax=new Ajax.PeriodicalUpdater('comments',command,{method:'get',frequency:3,decay:1});		
			return false;				
		}
		
		
		function toggleCheckboxSub(id,obj) {
		
			var command = API+"?method=toggleSub&contentId="+id;
			$.getJSON(command,subCheckboxSuccess);
			SUB_LINK = obj;
			return false;
		
		}
		
		function subCheckboxSuccess(json) {
			if (json.error) {
				alert(json.error);
			} 
			
			if (json.isOn) {
				$('#'+json.html_id).attr('checked',true);
			} else {
				$('#'+json.html_id).attr('checked',false);
			}
			SUB_LINK = null;
		}		
		
		
		
		function toggleBot(html_id,on,off,api_parameters,handler) { 

			var command = API + '?' + api_parameters + '&html_id='+escape(html_id)+'&on='+escape(on)+'&off='+off;

			if(!handler) { handler = toggleBotSuccess; }

			$.getJSON(command,handler);

			return false;
		}
		
		function toggleBotSuccess(json) {
		
			if (json.error) {
				alert(json.error);
			}
			if (json.isOn) {
				$('#'+json.html_id).addClass('active');
				$('#'+json.html_id).html(json.on);
			} else {
				$('#'+json.html_id).removeClass('active');
				$('#'+json.html_id).html(json.off);				
			}
		
		}
		
	
		function metoocounter(json) { 
			toggleBotSuccess(json);
			if (json.count) { 
				$('#metoo_counter').html(json.count + pluralize(json.count,' person thinks this is a bug.',' people think this is a bug.'));
			}		
		}
	
		function multiToggleSuccess(json) { 
			if (json.error) {
				alert(json.error);
			}
			if (json.isOn) {
				$('.'+json.html_id).addClass('active');
				$('.'+json.html_id).html(json.on);
			} else {
				$('.'+json.html_id).removeClass('active');
				$('.'+json.html_id).html(json.off);				
			}
		
		}
	
		function pluralize(num,sing,plur) {
			if (num==1) { return sing; } else { return plur; }
		}
	
		function toggleBrowseSub(keyword,type,off_message,on_message,obj) {
			var command = API+"?method=toggleSub&keyword="+escape(keyword) + "&type="+escape(type);
			$.getJSON(command,subSuccess);
			SUB_ON = on_message;
			SUB_OFF = off_message;
			SUB_LINK = obj;
			
			return false;
		}
		
		function subSuccess(json) {
			if (json.error) {
				alert(json.error);
			} 
			
			if (json.subscribed) {
				$(SUB_LINK).html(SUB_ON);
			} else {
				$(SUB_LINK).html(SUB_OFF);
			}
			
			SUB_LINK = null;
			
		
		}
		function toggleUserFlag(id,flag,off_message,on_message,obj) {
			var command = API + "?method=toggleUserFlag&user="+id +"&flag=" + flag;
			$.getJSON(command,flagUserSuccess);
			
			FLAG_ON = on_message;
			FLAG_OFF = off_message;
			FLAG_LINK = obj;
			return false;
		}		

		function toggleCommentFlag(id,flag,off_message,on_message,obj) {
			var command = API + "?method=toggleCommentFlag&user="+id +"&flag=" + flag;
			$.getJSON(command,flagCommentSuccess);
			
			FLAG_ON = on_message;
			FLAG_OFF = off_message;
			FLAG_LINK = obj;
			return false;
		}		

		
		function flagUserSuccess(json) { 
		
			if (json.error) {
				alert(json.error);
			}
		
			if (json.flagged) { 
				$(FLAG_LINK).html(FLAG_ON);
				$('a.' + json.flag + '_flag_'+json.user.id).html(FLAG_ON);
			} else {
				$(FLAG_LINK).html(FLAG_OFF);
				$('a.' + json.flag + '_flag_'+json.user.id).html(FLAG_OFF);
			}		
			FLAG_LINK = null;
			if (json.count && FLAG_COUNTER) { 
				if (json.count == 1) { 
					$(FLAG_COUNTER).html(json.count + " " + FLAG_SINGULAR);
				} else {
					$(FLAG_COUNTER).html(json.count + " " + FLAG_PLURAL);
				}			
				FLAG_COUNTER = null;
			}
		}		
		

		function flagUserSuccess(json) { 
		
			if (json.error) {
				alert(json.error);
			}
		
			if (json.flagged) { 
				$(FLAG_LINK).html(FLAG_ON);
				$('a.' + json.flag + '_flag_'+json.user.id).html(FLAG_ON);
			} else {
				$(FLAG_LINK).html(FLAG_OFF);
				$('a.' + json.flag + '_flag_'+json.user.id).html(FLAG_OFF);
			}		
			FLAG_LINK = null;
			if (json.count && FLAG_COUNTER) { 
				if (json.count == 1) { 
					$(FLAG_COUNTER).html(json.count + " " + FLAG_SINGULAR);
				} else {
					$(FLAG_COUNTER).html(json.count + " " + FLAG_PLURAL);
				}			
				FLAG_COUNTER = null;
			}
		}
		
		function removeComment(commentId) { 
			if (confirm('Are you sure you want to permanently delete this comment?')) { 
				var command = API + "?method=removeComment&comment=" + escape(commentId);
				$('#comment' + commentId).hide();
				$.getJSON(command,removeCommentSuccess);
			}		
			return false;
	
		}
		function removeCommentSuccess(comment) { 
				if (comment.error) {
					$('#comment' + comment.id).show();
					alert(comment.error);
				} else {
					$('#comment' + comment.id).hide();
				}
		}

		function removeSub(subId) { 
			var command = API + "?method=removeSub&subscriptionId=" + escape(subId);
			$('#sub_' + subId).hide();
			$.getJSON(command,removeSubSuccess);
			return false;
	
		}
		function removeSubSuccess(res) { 
				if (res.error) {
					$('#sub_' + res.id).show();
					alert(res.error);
				} else {
					$('#sub_' + res.id).hide();
				}
		}

