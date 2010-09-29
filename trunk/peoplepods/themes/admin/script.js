	var current_group = null;
	var current_content = null;
	var current_person = null;


	function confirmBulkDelete() { 
		return confirm('Are you sure you want to delete the checked items?');
	}
	function confirmBulkNotSpam() { 
		return confirm('Are you sure you want mark these items as not spam?');
	}



	function changeAuthor() {
	
		$('#author_edit').show();
		$('#changeAuthorLink').hide();

		$('#userId_autofill').autocomplete(PODROOT+'/admin/userAutocomplete.php',{
		}).result(function(event,data,formatted) {
			$('#userId').val(data[1]);
		});
		return false;
	
	}



	function selectAll(obj) {
	
		$('.enabler').attr('checked',$(obj).attr('checked'));
	}
	function addChildSearch() { 
	
		q = $('#addChild_q').val();
		command = PODROOT+'/admin/content/search.php?mode=addChild&q=' + q; 
		$('#addChildResults').html('Loading...');
		$('#addChildResults').show();
		$('#addChildResults').load(command);	
		return false;

	}			
	


	function addChildDoc(child) { 
	
		if (current_group) {
			command = PODROOT+'/admin/groups/addToGroup.php?group=' + current_group + '&doc=' + child;
		} else if (current_content) { 
			command = PODROOT+'/admin/content/addChild.php?parent=' + current_content + '&child=' + child;		
		}
		$('#child_documents').load(command);
		$('#addChildResults').hide();
		return false;
	}

	function removeChildDoc(child) { 
		if (current_group) {
			command = PODROOT+'/admin/groups/addToGroup.php?action=remove&group=' + current_group + '&doc=' + child;
		} else if (current_content) {
			command = PODROOT+'/admin/content/addChild.php?action=removeChild&parent=' + current_content + '&child=' + child;		
		}
		$('#child_documents').load(command);
		return false;
	}		



	function addMemberSearch() { 
	
		q = $('#addMember_q').val();
		command = PODROOT+'/admin/people/search.php?result_mode=addMember&q=' + q; 
		$('#addMemberResults').html('Loading...');
		$('#addMemberResults').show();
		$('#addMemberResults').load(command);	
		return false;

	}			

	function addMember(child) { 
		type = $('#member_type_' + child).val();
		command = PODROOT+'/admin/groups/addMember.php?group=' + current_group + '&person=' + child + "&type=" + type;
		$('#members').load(command);
		$('#addMemberResults').hide();
		return false;
	}

	function removeMember(child) { 
		command = PODROOT+'/admin/groups/addMember.php?action=remove&group=' + current_group + '&person=' + child;
		$('#members').load(command);	
		return false;
	}		



			function removeFlag(flag,type,itemId,userId) { 
			
				var command = PODROOT + "/admin/flags/?removeFlag=" + flag + "&type=" + type + "&itemId=" + itemId + "&userId=" + userId;
				$.getJSON(command,flagSuccess);
			
			}
					
			function addFlag(flag,type,itemId,userId) { 
			
				var command = PODROOT + "/admin/flags/?addFlag=" + flag + "&type=" + type + "&itemId=" + itemId + "&userId=" + userId;
				$.getJSON(command,flagSuccess);
			
			}			
			
			function flagSuccess(res) { 
					if (res.error) {
						alert(res.error);
					} else {
						id = res.new_action + "_" + res.flag + "_" + res.itemId + "_" + res.userId;
						$('#'+id).show();
						id = res.old_action + "_" + res.flag + "_" + res.itemId + "_" + res.userId;
						$('#'+id).hide();
					}
			}
				
			
		
	function repairField(obj,message) {
		if ($(obj).val()==message) {
			$(obj).css('color','#000000');
			$(obj).val('');
		} else {
			$(obj).css('color','#CCCCCC');
			$(obj).val(message);
		}
		return false;
	
	}
	
	function showOptional(obj,op) {
		$(obj).hide();
		$(op).show();
		return false;
	}
	
	
	function addMetaField() { 
	
		name = $('#new_meta_name').val();
		
		valid = (name!='');

		if (valid) { 
			$('#new_meta_name').val('');
			$('#new_meta').hide();
			$('#add_field_link').show();
			
			p = document.createElement('p');
			p.setAttribute('class','input');
			p.innerHTML = '<label for="meta_' + name + '">'+name+':</label><input type="text" name="meta_'+name+'" id="meta_'+name+'" class="text" />';
					
			$('#new_meta_fields').append(p);
		}		
		return false;
	
	}

	function changeType() { 
		$('#content_type').hide();
		$('#type').show();
		return false;
	}

	$().ready( function() { 
	
		$('textarea.tinymce').tinymce({
			script_url : PODROOT+'/themes/admin/js/tinymce/jscripts/tiny_mce/tiny_mce.js',
			theme: "advanced",
			
			valid_elements: "p,blockquote,h1,h2,h3,h4,h5,h6,ol,ul,li,br,em,strong,i,u,b,strike,a[href|target|title],img[src|width|height|alt|border|title]",
			plugins:"paste",
			paste_auto_cleanup_on_paste: true,
			paste_strip_class_attributes: "all",
			paste_remove_spans: true,
			paste_remove_styles: true,
			relative_urls:false,
			remove_script_host: false,
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",	
			theme_advanced_buttons1: "pastetext,formatselect,removeformat,separator,bold,italic,separator,bullist,numlist,separator,link,unlink,image,separator,undo,removeformat,separator,charmap",
			theme_advanced_buttons2: "",
			theme_advanced_blockformats : "p,blockquote,h1,h2,h3,h4,h5,h6",
			content_css : PODROOT+"/themes/admin/tinymce.css"
		});

		$('.meta_lookup').autocomplete(PODROOT+'/admin/metaAutocomplete.php',{
				autoFill: true
		}
		);

		
		$('form.valid').validate();
	});

/****************************************************************************************/

