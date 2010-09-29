		var comment_ajax;
		
		function repairField(field_id,value) { 
			
			field = $(field_id);			
			if (field.value=='') { 
				field.value=value;
				field.style.color='#CCC';
			} else if (field.value==value) {
				field.style.color='#000';
				field.value='';
			}
			return false;
		}
		
		function reply(commentId,nick) { 
			if ($('comment')) { 
				$('comment').value += '<a href="#' +commentId + '">@' + nick + '</a> '
			}
				return false;
		}
		function startSpinner() {
		
			$('spinner').innerHTML = '<img src="' + themeRoot + '/img/spinner.gif" />';
		}
		function stopSpinner() {
		
			$('spinner').innerHTML = 'FEED BACK';
		}


		function getComments(doc) { 
			var command = API + "?method=getComments&docId=" + doc;
			comment_ajax=new Ajax.PeriodicalUpdater('comments',command,{method:'get',frequency:3,decay:1});		
			return false;				
		}



		function getCommentsSince() { 
			var command = API + "?method=getCommentsSince&docId=" + current_doc + "&lastComment=" + lastSeen;
			comment_ajax=new Ajax.Request(command,{method:'get',onSuccess: getCommentsSinceSuccess});		
			return false;
		}

		function getCommentsSinceSuccess(res) {
			json = res.responseText.evalJSON(); 
			if (json.comments_as_html) { 
				$('comments').innerHTML = $('comments').innerHTML + json.comments_as_html;
				lastSeen = json.last;
				WAIT = 3000;
				TIMEOUT = setTimeout("getCommentsSince();",WAIT)
				stopSpinner();
			} else {		
				WAIT = WAIT + 1000;
				TIMEOUT = setTimeout("getCommentsSince();",WAIT)
			}
		}
		
		function removeComment(commentId) { 
			if (confirm('Are you sure you want to permanently comment this, permanently, forever?')) { 
				var command = API + "?method=removeComment&comment=" + escape(commentId);
				$('comment' + commentId).style.display='none';
				var ajax=new Ajax.Request(command,{method:'get',onSuccess: removeCommentSuccess});				
			}		
			return false;
	
		}
		function removeCommentSuccess(res) { 
				comment = res.responseText.evalJSON();
				if (comment.error) {
					$('comment' + comment.id).style.display='block';
					alert(comment.error);
				} else {
					$('comment' + comment.id).style.display='none';
				}
		}
		
		function addComment(docId,comment) { 
			$('comment').style.color='#CCC';
			var command = API + "?method=addComment&docId=" + docId + "&comment=" + escape(comment);
			var ajax=new Ajax.Request(command,{method:'get',onSuccess: addCommentSuccess});		
			startSpinner();
			return false;
		}

		function addCommentSuccess(res) { 
				comment = res.responseText.evalJSON();
				if (comment.error) {
					alert(comment.error);
				} else {
					stopSpinner();
					$('comment').style.color='#000';
					$('comment').value='';
				}
		}
		

		function markAsRead(docId) { 
		
			var command = API + "?method=markAsRead&docId=" + docId;
			var ajax=new Ajax.Request(command,{method:'get',onSuccess: markAsReadSuccess});		
			return false;
		}
	
		function markAsReadSuccess(res) {
			doc = res.responseText.evalJSON();
			if (doc.error) {
				alert(doc.error);
			} else {
				$('option_mark_as_read_' + doc.id).innerHTML = '&#x2714; Read';

			}		
		}

		function addWatch(docId) { 
		
			var command = API + "?method=addWatch&docId=" + docId;
			var ajax=new Ajax.Request(command,{method:'get',onSuccess: addWatchSuccess});		
			var addWatch = $('addWatch_' + docId);
			var removeWatch = $('removeWatch_' + docId);
			addWatch.style.display='none';
			removeWatch.style.display='block';
			return false;
		}
	
		function removeWatch(docId) { 
		
			var command = API + "?method=removeWatch&docId=" + docId;
			var ajax=new Ajax.Request(command,{method:'get',onSuccess: removeWatchSuccess});		
			var addWatch = $('addWatch_' + docId);
			var removeWatch = $('removeWatch_' + docId);
			addWatch.style.display='block';
			removeWatch.style.display='none';
			
			return false;
		}
	
	
		function addWatchSuccess(res) {
			var doc = res.responseText.evalJSON();
			var addWatch = $('addWatch_' + doc.id);
			var removeWatch = $('removeWatch_' + doc.id);

			if (doc.error) {	
				addWatch.style.display='block';
				removeWatch.style.display='none';
				alert(doc.error);
			} else Favorite
		}

		function removeWatchSuccess(res) {
			var doc = res.responseText.evalJSON();
			var addWatch = $('addWatch_' + doc.id);
			var removeWatch = $('removeWatch_' + doc.id);

			if (doc.error) {	
				addWatch.style.display='none';
				removeWatch.style.display='block';
				alert(doc.error);
			} else {
				addWatch.style.display='block';
				removeWatch.style.display='none';			
			}
		}		


		function addFavorite(docId) { 
		
			var command = API + "?method=addFavorite&docId=" + docId;
			var ajax=new Ajax.Request(command,{method:'get',onSuccess: addFavoriteSuccess});		
			var addFavorite = $('addFavorite_' + docId);
			var removeFavorite = $('removeFavorite_' + docId);
			addFavorite.style.display='none';
			removeFavorite.style.display='block';
			return false;
		}
	
		function removeFavorite(docId) { 
		
			var command = API + "?method=removeFavorite&docId=" + docId;
			var ajax=new Ajax.Request(command,{method:'get',onSuccess: removeFavoriteSuccess});		
			var addFavorite = $('addFavorite_' + docId);
			var removeFavorite = $('removeFavorite_' + docId);
			addFavorite.style.display='block';
			removeFavorite.style.display='none';
			
			return false;
		}
	
	
		function addFavoriteSuccess(res) {
			var doc = res.responseText.evalJSON();
			var addFavorite = $('addFavorite_' + doc.id);
			var removeFavorite = $('removeFavorite_' + doc.id);

			if (doc.error) {	
				addFavorite.style.display='block';
				removeFavorite.style.display='none';
				alert(doc.error);
			} else {
				addFavorite.style.display='none';
				removeFavorite.style.display='block';			
			}
		}

		function removeFavoriteSuccess(res) {
			var doc = res.responseText.evalJSON();
			var addFavorite = $('addFavorite_' + doc.id);
			var removeFavorite = $('removeFavorite_' + doc.id);
			if (doc.error) {	
				addFavorite.style.display='none';
				removeFavorite.style.display='block';
				alert(doc.error);
			} else {
				addFavorite.style.display='block';
				removeFavorite.style.display='none';			
			}
		}		


		function removeMember(gid,pid) { 
		
			var command = API + "?method=removeMember&group=" + gid + "&id=" + pid;
			var ajax=new Ajax.Request(command,{method:'get',onSuccess: removeMemberSuccess});		
			$('member_'+pid).style.display='none';			
			return false;
		}

		function removeMemberSuccess(res) {
			var person = res.responseText.evalJSON();
			if (person.error) {	
				$('member_' + person.id).style.display='block';
				alert(person.error);
			} else {
				$('member_' + person.id).style.display='none';
			}
		}
		function changeMemberType(gid,pid,type) { 
		
			var command = API + "?method=changeMemberType&group=" + gid + "&id=" + pid + "&type=" + type;
			var ajax=new Ajax.Request(command,{method:'get',onSuccess: changeMemberTypeSuccess});		
			return false;
		}

		function changeMemberTypeSuccess(res) {
			var person = res.responseText.evalJSON();
			if (person.error) {	
				alert(person.error);
			} else {
				$('member_invitee_' + person.id).style.display='none';
				$('member_member_' + person.id).style.display='none';
				$('member_manager_' + person.id).style.display='none';
				type = person.membership;
				$('member_type_' + person.id).innerHTML = type;
				if (type=='owner') { type='manager'; }
				$('member_' + type + '_' + person.id).style.display='block';			
	
			}
		}
		
		
		function vote(docId,vote) { 
			var command = API + "?method=vote&docId=" + docId + "&vote=" + vote;
			var ajax=new Ajax.Request(command,{method:'get',onSuccess: voteSuccess});		
			$('voting_spinner_' + docId).innerHTML = '<img src="' + themeRoot + '/img/spinner.gif" />';
			return false;			
		}
		
		function pluralize(count,singular,plural) {
		
			if (count == 1) {  return singular; } else { return plural; } 
		}
		
		function edit() {
			$('editform').style.display='block';
			$('post_output').style.display='none';

		}
		
		function voteSuccess(res) { 

			var doc = res.responseText.evalJSON();
			if (doc.error) {
			
				alert(doc.error);
			} else {
				img = $('voting_chart_' + doc.id);
				yes_count = $('yes_votes_' + doc.id);
				no_count = $('no_votes_' + doc.id);
				my_vote = $('my_vote_' + doc.id);
				// update image
				
				var newchart = "http://chart.apis.google.com/chart?chs=150x150&chd=t:" + doc.yes_percent + "," +doc.no_percent + "&cht=p&chco=" + doc.color1 + "," + doc.color2;
				img.src = newchart;
	
				// update yes counts
				// update no counts			
				yes_count.innerHTML = doc.yes_votes + " " + pluralize(doc.yes_votes,'vote','votes');
				no_count.innerHTML = doc.no_votes + " " + pluralize(doc.no_votes,'vote','votes');
				//update my vote
				if (doc.lastVote == "Y") {
					my_vote.innerHTML = doc.option1;
				} else {
					my_vote.innerHTML = doc.option2;
				}
									
				$('voting_booth_' + doc.id).style.display='none';
				$('voting_results_' + doc.id).style.display='block';
			} 
		}
		

		
		function addFriend(friendId) { 
			var command = API + "?method=addFriend&id=" + friendId;
			var ajax=new Ajax.Request(command,{method:'get',onSuccess: addFriendSuccess});		
			return false;
		}

		function addFriendSuccess(res) { 
			var newfriend = res.responseText.evalJSON();
			
			if (newfriend.error) {
				alert(newfriend.error);
			} else {
				var removeFriend = $('removeFriend_' + newfriend.id);
				var addFriend = $('addFriend_' + newfriend.id);
				
				removeFriend.style.display='block';
				addFriend.style.display='none';			}

		}

		function removeFriend(friendId) { 
			var command = API + "?method=removeFriend&id=" + friendId;
			var ajax=new Ajax.Request(command,{method:'get',onSuccess: removeFriendSuccess});		
			return false;
		}

		function removeFriendSuccess(res) { 
			var newfriend = res.responseText.evalJSON();
			if (newfriend.error) {
				alert(newfriend.error);
			} else {

				var removeFriend = $('removeFriend_' + newfriend.id);
				var addFriend = $('addFriend_' + newfriend.id);
				
				removeFriend.style.display='none';
				addFriend.style.display='block';

			}

		}

		function deleteDocument(docId) { 
			if (confirm('Are you sure you want to permanently delete this, permanently, forever?')) { 
				var command = API + "?method=deleteDocument&id=" + escape(docId);
				$('document_' + docId).style.display='none';
				var ajax=new Ajax.Request(command,{method:'get',onSuccess: deleteDocumentSuccess});	
			}
			return false;
		
		
		}
		function deleteDocumentSuccess(res) { 
				doc = res.responseText.evalJSON();
				if (doc.error) {
					$('document_' + doc.id).style.display='block';
					alert(doc.error);
				} else {
					$('document_' + doc.id).style.display='none';
				}
		}

		
		function unloader() {		
			if (TIMEOUT > 0) { 
				clearTimeout(TIMEOUT);
			}
		}
		
		function togglePostOption(option) {
		
			add_option = $('add_' + option);
			post_option = $('post_' + option);
			
			if (add_option.className=='') { // option is off, need to turn it on
				add_option.className='active';
				post_option.style.display='block';
			} else {
				add_option.className='';
				post_option.style.display='none';			
			
			}
			return false;
		}
