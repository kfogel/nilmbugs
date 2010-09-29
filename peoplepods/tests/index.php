<?php
	error_reporting(E_ALL ^ E_NOTICE);

	include_once("../PeoplePods.php");
	
	echo "CREATE POD: ";
	$POD = new PeoplePod(array('debug'=>0,'authSecret'=>'dd240e6fec7bac60883486fce1e2fba5'));
	if (!$POD->success()) {
		echo "<b>FAILED</b><br />";
	} else {
		echo "SUCCESS!<br />";
	}

	echo "POD AUTHENTICATED? ";
	if ($POD->isAuthenticated()) { 
		echo "YES!<BR />";
	} else {
		echo "NO! :(<Br />";
	}
	
	echo "<hr />";
	echo "DOCUMENT FUNCTIONS";
	echo "<hr />";
	
	echo "CREATE DOC: ";
	$doc = $POD->getDocument();
	if (!$doc->success()){
		echo "<b>FAILED</b> " . $doc->error() . "<br />";
	} else {
		echo "SUCCESS!<br />";
	}
	
	echo "SAVE DOC (no values, should fail): ";
	$doc->save();
	if (!$doc->success()){
		echo "Failed as planned: " . $doc->error() . "<br />";
	} else {
		echo "<B>FAILED!</b> (Should not have succeeded)<br />";
	}

	
	echo "Voting (not saved yet): ";
	$doc->vote('1');
	if (!$doc->success()){
		echo "Failed as planned " . $doc->error() . "<br />";
	} else {
		echo "<B>FAILED!</B> should not have succeeded!<br />";
	}	
	
	echo "ADD TAG (not saved yet): ";
	$doc->addTag('bar');
	if (!$doc->success()){
		echo "FAILED as planned " . $doc->error() . "<br />";
	} else if (!preg_match("/foo/",$doc->tagsAsString())) {
		echo "<B>FAILED</B> tag not found!<br />";
	} else {
		echo "SUCCESS!<br />";
	}		


	$doc->set('headline','This is a test document');
	$doc->set('type','test');
	echo "SAVE DOC (valid): ";
	$doc->save();
	if (!$doc->success()){
		echo "<b>FAILED</b> " . $doc->error() . "<br />";
	} else {
		echo "SUCCESS!<br />";
	}		
	
	echo "DOC HAS ID: ";
	if ($doc->get('id')) { 
		echo "SUCCESS!<br />";
	} else {
		echo "<B>FAILED!</B><Br />";
	}
	

	echo "<hr />";
	echo "TAGS FUNCTIONS";
	echo "<hr />";

	echo "DOC TAGS: " . $doc->tagsAsString() . "<br />";
	
	echo "ADD TAG: ";
	$doc->addTag('bar');
	$doc->addTag('foo');
	if (!$doc->success()){
		echo "<b>FAILED</b> " . $doc->error() . "<br />";
	} else if (!preg_match("/foo/",$doc->tagsAsString())) {
		echo "<B>FAILED</B> tag not found!<br />";
	} else {
		echo "SUCCESS!<br />";
	}		

	echo "DOC TAGS: " . $doc->tagsAsString() . "<br />";

	echo "ADD SAME TAG: ";
	$doc->addTag('bar');
	$doc->addTag('foo');
	if (!$doc->success()){
		echo "<b>FAILED</b> " . $doc->error() . "<br />";
	} else if (!preg_match("/foo/",$doc->tagsAsString())) {
		echo "<B>FAILED</B> tag not found!<br />";
	} else {
		echo "SUCCESS!<br />";
	}		

	echo "DOC TAGS: " . $doc->tagsAsString() . "<br />";


	echo "Has Tag? ";
	if ($doc->hasTag('foo')) { 
		echo "Success!<br/>";
	} else {
		echo "<B>Failed!</b> hasTag returned false<br />";
	}
	echo "REMOVE TAG: ";
	$doc->removeTag('foo');
	if (!$doc->success()){
		echo "<b>FAILED</b> " . $doc->error() . "<br />";
	} else if (preg_match("/foo/",$doc->tagsAsString())) {
		echo "<B>FAILED</B> tag found!<br />";
	} else if (!preg_match("/bar/",$doc->tagsAsString())) {
		echo "<B>FAILED</B> other tags missing!<br />";
	} else {
		echo "SUCCESS!<br />";
	}		

	echo "DOC TAGS: " . $doc->tagsAsString() . "<br />";
	
	echo "Has Tag? ";
	if (!$doc->hasTag('foo')) { 
		echo "Success!<br/>";
	} else {
		echo "<B>Failed!</b> hasTag returned true!<br />";
	}


	$id = $doc->get('id');
	

	echo "Change Status: ";
	$doc->changeStatus('foobar');
	if (!$doc->success()){
		echo "<b>FAILED</b> " . $doc->error() . "<br />";
	} else if ($doc->get('status') == "foobar") {
		echo "SUCCESS!<br />";
	} else {
		echo "<b>FAILED!</b> status did not update.<Br />";
	}

	echo "<hr />";
	echo "VOTING FUNCTIONS";
	echo "<hr />";


	echo "Voting (invalid vote): ";
	$doc->vote('x');
	if (!$doc->success()){
		echo "FAILED as planned " . $doc->error() . "<br />";
	} else {
		echo "<B>FAILED!</B> should not have succeeded!<br />";
	}	
	
	echo "Voting: ";
	$doc->vote('y');
	if (!$doc->success()){
		echo "<b>FAILED</b> " . $doc->error() . "<br />";
	} else {
		echo "SUCCESS!!!!<br />";
	}
	
	
	
 	echo "Doc->VOTE: ";
 	$vote = $doc->POD->currentUser()->getVote($doc);
	if ($vote != "Y") {
		echo "<b>FAILED</b> " . $doc->error() . "<br />";
	} else {
		echo "SUCCESS!<br />";
	}
 	
	$doc->vote("0");

	echo "Get vote (N): ";
	if ($POD->currentUser()->getVote($doc) != "N") {
		echo "<b>FAILED</b> " . $POD->currentUser()->error() . "<br />";
	} else {
		echo "SUCCESS!<br />";
	}		

 	echo "Doc->VOTE: ";
 	$vote = $doc->POD->currentUser()->getVote($doc);

	if ($vote != "N") {
		echo "<b>FAILED</b> " . $doc->error() . "<br />";
	} else {
		echo "SUCCESS!<br />";
	}
	
	
	echo "POD->getPeopleByVote: ";
// query users to get all users who voted for this doc.
	$voted = $POD->getPeopleByVote($doc,"0");
	if ($voted->contains('id',$POD->currentUser()->get('id'))) {
		echo "Success!<br />";
	} else {
		echo "<B>Failed!</b><Br />";
	}


	echo "Unvote: ";
	$doc->unvote();
	if (!$doc->success()){
		echo "<b>FAILED</b> " . $doc->error() . "<br />";
	} else {
		echo "SUCCESS!<br />";
	}

 	echo "Doc->VOTE: ";
 	$vote = $doc->POD->currentUser()->getVote($doc);
	if ($vote) {
		echo "<b>FAILED</b> " . $doc->error() . "<br />";
	} else {
		echo "SUCCESS!<br />";
	}
	

	echo "POD->getPeopleByVote: ";
// query users to get all users who voted for this doc.
	$voted = $POD->getPeopleByVote($doc,"0");
	if (!$voted->contains('id',$POD->currentUser()->get('id'))) {
		echo "Success!<br />";
	} else {
		echo "<B>Failed!</b><Br />";
	}

	echo "Get vote (no vote): ";
	if ($POD->currentUser()->getVote($doc)) {
		echo "<b>FAILED</b> " . $POD->currentUser()->error() . "<br />";
	} else {
		echo "SUCCESS!<br />";
	}

			
	
	$doc2 = $POD->getDocument(array('id'=>$id));
	
	echo "REMOVE DOC: ";
	$doc->delete();
	if (!$doc->success()){
		echo "<b>FAILED</b> " . $doc->error() . "<br />";
	} else {
		echo "SUCCESS!<br />";
	}
	

	echo "Voting after deletion: ";
	$doc->vote('y');
	if (!$doc->success()){
		echo "FAILED as planned " . $doc->error() . "<br />";
	} else {
		echo "<B>FAILED</b> should not have succeeded<br />";
	}

	echo "Save after deletion: ";
	$doc2->save();	

	if (!$doc2->success()){
		echo "FAILED as planned " . $doc2->error() . "<br />";
	} else {
		echo "<B>FAILED</B> should not have succeeded!<br />";
	}
	
	
	echo "LOAD DELETED DOC $id: ";
	$doc = $POD->getDocument(array('id'=>$id));
	if (!$doc->success()){
		echo "FAILED as planned " . $doc->error() . "<br />";
	} else {
		echo "<b>FAILED!</B> should not have succeeded<br />";
	}

	echo "<hr />";
	echo "COMMENT FUNCTIONS";
	echo "<hr />";


	echo "HAS comments: ";
	if (!$doc->comments()) { 
		echo "Success!<br />";
	} else {
		echo "<B>FAiled!</b> Has comments but shouldn't<br />";
	}


	echo "ADD COMMENT: ";
	$doc = $POD->getDocument(array('headline'=>'Comment Test','type'=>'test'));
	$doc->save();
	echo "HELLO\n\n\n\n";
	if ($comment = $doc->addComment('Holy crap!')) {
		if ($comment->success()){
			echo "SUCCESS!<br />";	
		} else {
			echo "<B>FAILED!</b> - addComment return a comment, but it was a failed comment!<Br />";
		}
	} else {
		echo "<b>FAILED</b> " . $doc->error() . "<br />";
	}
	

	echo "HAS comments: ";
	if ($doc->comments()->exists()) { 
		echo "Success!<br />";
	} else {
		echo "<B>FAiled!</b> Has comments() but shouldn't<br />";
	}

	echo "Comment has correct parent: ";
	if ($comment->parent('id') == $doc->get('id')) { 
		echo "SUCCESS!<br />";
	} else {
		echo "<B>FAILED!</B> doc id: " . $doc->get('id') . " comment parent id: " . $comment->parent('id') . "<br />";
	}

	echo "Comment has correct author: ";
	if ($comment->author('id') == $POD->currentUser()->get('id')) { 
		echo "SUCCESS!<br />";
	} else {
		echo "<B>FAILED!</B> parent id: " . $POD->currentUser()->get('id') . " comment parent id: " . $comment->author('id') . "<br />";
	}
	
	echo "Is comment in doc->comments()? ";
	$doc->comments()->reset();
	$testcomment = $doc->comments()->getNext();
	if ($testcomment->get('id')==$comment->get('id')) {
		echo "SUCCESS<Br />";
	} else {
		echo "FAILED!<Br />";
	}
	
	$cid = $comment->get('id');
	
	
	echo "Load comment: ";
	$testcomment = $POD->getComment(array('id'=>$cid));
	if ($testcomment->success()){
		if ($testcomment->get('id') == $comment->get('id')) {
			echo "Success!<Br />";
		} else {
			echo "<B>FAILED</b> Comment loaded but NOT THE RIGHT ONE!<Br />";
		}
	} else {
		echo "<B>FAILED!</b> " . $testcomment->error();
	}
	
	$anothercomment = $doc->addComment('whatever!');
	
	echo "Load multiple comments(): ";
	$comments = $POD->getComments(array('userId'=>$POD->currentUser()->get('id')));
	if ($POD->success()){
		echo "SUCCESS! Count: " . $comments->count() . "<Br />";
	} else {
		echo "<B>Failed!</b> " . $POD->error();
	}
	
	echo "Comment IDS: " . $comments->implode(',','id') . "<br />";

	$count = $comments->count();
	echo "Correct comments loaded? ";
	$failed = false;
	while ($c = $comments->getNext()) {
		if ($c->get('userId') != $POD->currentUser()->get('id')) {
			$failed = true;
		}
	}
	if ($failed) {
		echo "<B>FAILED!</b></br>";
	} else {
		echo "SUCCESS!<Br />";
	}

	$comments = $POD->getComments();
	echo "Invalid stack->fill command: ";
	$comments->fill();
	if ($comments->success()){
		echo "<B>FAILED!</B> should not have succeeded.<Br />";
	} else {
		echo "Failed as planned " . $comments->error() . "<BR />";
	}

	echo "Comment delete: ";
	$comment->delete();
	if ($comment->success()){
		echo "Success!<Br />";
	} else {
		echo "<B>Failed!</b> - " . $comment->error() . "<br />";
	}

	echo "Invalid comment save: ";
	$comment->save();
	if ($comment->success()){
		echo "<b>FAILED!</b> should not have succeeded!<Br />";
	} else {
		echo "Success! - " . $comment->error() . "<br />";
	}


	echo "Load comment (now invalid): ";
	$testcomment = $POD->getComment('id',$cid);
	if ($testcomment->success()){
		if ($testcomment->get('id') == $comment->get('id')) {
			echo "<B>FAILED!</b> should not have loaded comment<Br />";
		} else {
			echo "<B>FAILED</b> Comment loaded but NOT THE RIGHT ONE!<Br />";
		}
	} else {
		echo "Success! " . $testcomment->error() . "<br />";
	}
	
	echo "Load multiple comments: ";
	$comments = $POD->getComments(array('userId'=>$POD->currentUser()->get('id')));
	if ($POD->success()){
		echo "SUCCESS! Count: " . $comments->count() . "<Br />";
	} else {
		echo "<B>Failed!</b> " . $POD->error() . "<br />";
	}
	$newcount = $comments->count();
	
	echo "Comment really gone? ";
	if ($newcount < $count) {
		echo "YUP!<Br />";
	} else {
		echo "<B>FAILED!</b><Br />";
	}
	
	$id = $doc->get('id');
	$doc->delete();
	echo "Doc delete removed all comments?";
	$comments = $POD->getComments(array('documentId'=>$id));
	if (!$comments->count() == 0) {
		echo "<b>Failed!</b> should not have returned any comments Count: " . $comments->count() . "<Br />";
	} else {
		echo "Success<Br />";
	}	
	
	echo "<hr />";
	echo "USER FUNCTIONS";
	echo "<hr />";

	// create new user	
	echo "Create user: ";
	$newuser = $POD->getPerson(array('nick'=>'chesterx','email'=>'chesterx@xoxco.com','password'=>'2434234'));
	$newuser->save();
	if ($newuser->success()){
		if ($newuser->get('id')) { 
			echo "Success!<br />";
		} else {
			echo "<B>FAILED!</b> - Should have an id, but doesn't<br />";
		}
	} else {
		echo "<B>Failed!</b> - " . $newuser->error() . "<br />";
	}
	
	// create the same user again
	echo "Create user again: ";
	$newuser2 = $POD->getPerson(array('nick'=>'chesterx','email'=>'chesterx@xoxco.com','password'=>'2434234'));
	$newuser2->save();
	if ($newuser2->success()){
		echo "<b>Failed!</b> should not have created<br />";
	} else {
		echo "Success! - failed as planned: " . $newuser2->error() . "<br />";
	}
	
	echo "<hr />";
	echo "FRIEND FUNCTIONS";
	echo "<hr />";


	// add friend
	
	echo "Add a friend: ";
	$POD->currentUser()->addFriend($newuser);
	if ($POD->currentUser()->success()){ 
		echo "SUCCESS!<br />";
	} else {
		echo "<B>FAILURE</B> " . $POD->currentUser()->error();
	}	


	// check isfriend one way
	echo "Adder isfriends()With? ";
	if ($POD->currentUser()->isFriendsWith($newuser)) { 
		echo "SUCCESS!<Br />";	
	} else {
		echo "<B>Failed!</b> - should have been true!<Br />";
	}
	
	// check isfriend the other way
	echo "Added !isfriends()With? ";
	if (!$newuser->isFriendsWith($POD->currentUser())) { 
		echo "SUCCESS!<Br />";	
	} else {
		echo "<B>Failed!</b> - should have been false!<Br />";
	}

	// check friends() one way
	echo "New friend is now in user->friends()? ";
	if (!$POD->currentUser()->friends()->contains('id',$newuser->get('id'))) {
		echo "<B>FAILED!</b> - friend not found in friends() array<Br />";
	} else {
		echo "Success!<br />";
	}

	// remove friend
	echo "Remove a friend: ";
	$POD->currentUser()->removeFriend($newuser);
	if ($POD->currentUser()->success()){ 
		echo "SUCCESS!<br />";
	} else {
		echo "<B>FAILURE</B> " . $POD->currentUser()->error();
	}	


	// check isfriend one way
	echo "Adder isfriends()With? ";
	if (!$POD->currentUser()->isFriendsWith($newuser)) { 
		echo "SUCCESS!<Br />";	
	} else {
		echo "<B>Failed!</b> - should have been false!<Br />";
	}
	echo "New friend is no longer in user->friends()? ";
	if ($POD->currentUser()->friends()->contains('id',$newuser->get('id'))) {
		echo "<B>FAILED!</b> - friend found in friends() array<Br />";
	} else {
		echo "Success!<br />";
	}
	

	$POD->currentUser()->addMeta('adminUser',null);



	// delete the user
	echo "Delete user (no permission): ";
	$newuser->delete();
	if ($newuser->success()){
		echo "<b>Failed</b> - should not have succeeded!<br />";
	} else {
		echo "Success - failed as planned: " . $newuser->error() . "<br />";
	}	
	
	// add admin user flag
	$POD->currentUser()->addMeta('adminUser',1);
	
	echo "Delete user: ";
	$newuser->delete();
	if ($newuser->success()){
		echo "Success!<br />";
	} else {
		echo "<B>Failed!</b> - " . $newuser->error() . "<br />";
	}
		


	echo "<hr />";
	echo "FAVORITE FUNCTIONS";
	echo "<hr />";

	$doc = $POD->getDocument();
	$doc->set('type','test');
	$doc->set('headline','One of my favorites');
	
	// addFavorite
	echo "Add Favorite (new doc, should fail): ";
	$POD->currentUser()->addFavorite($doc);
	if (!$POD->currentUser()->success()){ 
		echo "SUCCESS! Failed as planned - "  . $POD->currentUser()->error() . "<br />";
	} else {
		echo "<B>FAILURE</B> <br />";
	}	


	$doc->save();


	// addFavorite
	echo "Add Favorite: ";
	$POD->currentUser()->addFavorite($doc);
	if ($POD->currentUser()->success()){ 
		echo "SUCCESS!<br />";
	} else {
		echo "<B>FAILURE</B> " . $POD->currentUser()->error() . "<br />";
	}	


	// isFavorite
	echo "Is Favorite? ";
	if ($POD->currentUser()->isFavorite($doc)) { 
		echo "SUCCESS!<br />";
	} else {
		echo "<B>Failed!</b> - Should be favorite!<Br />";
	}

	
	echo "POD->getPeopleByFavorite: ";
// query users to get all users who voted for this doc.
	$voted = $POD->getPeopleByFavorite($doc);
	if ($voted->contains('id',$POD->currentUser()->get('id'))) {
		echo "Success!<br />";
	} else {
		echo "<B>Failed!</b><Br />";
	}





	// removeFavorite
	echo "Remove Favorite: ";
	$POD->currentUser()->removeFavorite($doc);
	if ($POD->currentUser()->success()){ 
		echo "SUCCESS!<br />";
	} else {
		echo "<B>FAILURE</B> " . $POD->currentUser()->error() . "<br />";
	}		
	
	// isFavorite
	echo "Is Favorite? ";
	if (!$POD->currentUser()->isFavorite($doc)) { 
		echo "SUCCESS!<br />";
	} else {
		echo "<B>Failed!</b> - Should NOT be favorite!<Br />";
	}	

	echo "POD->getPeopleByFavorite: ";
// query users to get all users who voted for this doc.
	$voted = $POD->getPeopleByFavorite($doc);
	if (!$voted->contains('id',$POD->currentUser()->get('id'))) {
		echo "Success!<br />";
	} else {
		echo "<B>Failed!</b><Br />";
	}


	echo "Toggle Favorite: ";
	$POD->currentUser()->toggleFavorite($doc);
	if ($POD->currentUser()->isFavorite($doc)) { 
		echo "SUCCESS!<br />";
	} else {
		echo "<B>Failed!</b> - Should be favorite!<Br />";
	}

	echo "Toggle Favorite: ";
	$POD->currentUser()->toggleFavorite($doc);
	if (!$POD->currentUser()->isFavorite($doc)) { 
		echo "SUCCESS!<br />";
	} else {
		echo "<B>Failed!</b> - Should NOT be favorite!<Br />";
	}	

	echo "<hr />";
	echo "GROUP FUNCTIONS";
	echo "<hr />";	
	

	// create a group
	$newgroup = $POD->getGroup();
	$newgroup->set('groupname','Test Group');
	$newgroup->set('description','This is a test group');
	$newgroup->save();
	echo "Creating a group: ";
	if ($newgroup->success()){
		echo "Success!<br />";
	} else {
		echo "<b>Failed!</b> - " . $newgroup->error() . "<br />";
	}
	
	// member of group?
	echo "Group->isMember? ";
	if ($m = $newgroup->isMember($POD->currentUser())) {
		echo "Success! $m<br />";	
	} else {
		echo "<B>Failed!</B> Should have been member!<br />";
	}
	
	// group in users->GROUPS?

	// join a group
	$newuser = $POD->getPerson(array('nick'=>'chesterx','email'=>'chesterx@xoxco.com','password'=>'2434234'));
	
	echo "Join a group (invalid): ";
	$newgroup->addMember($newuser);		
	if ($newgroup->success()){
		echo "<B>Failed</b> - should not have succeeded.<Br />";
	} else {
		echo "Failed as planned - " . $newgroup->error() . "<Br />";
	}
	
	$newuser->save();

	echo "Join a group: ";
	$newgroup->addMember($newuser);		
	if ($newgroup->success()){
		echo "Success!<Br />";
	} else {
		echo "<b>Failed</b> - " . $newgroup->error() . "<Br />";
	}

	// member of group?
	echo "Group->isMember? ";
	if ($m = $newgroup->isMember($newuser)) {
		echo "Success! $m<br />";	
	} else {
		echo "<B>Failed!</B> Should have been member!<br />";
	}
	
	// quit a group invalid
	echo "Quit a group (invalid): ";
	$newgroup->removeMember($POD->currentUser());
	if (!$newgroup->success()){
		echo "Success!<Br />";
	} else {
		echo "<b>Failed</b> - " . $newgroup->error() . "<Br />";
	}
	
		
	// change status
	echo "Change status: ";
	$newgroup->changeMemberType($POD->currentUser(),'member');
	$x = $newgroup->isMember($POD->currentUser());
	if ($x == "owner") { 
		echo "Success! <br />";
	} else {
		echo "<b>Failed!</b> Status not changed -" . $newgroup->error() . "<Br />";
	}


	echo "Change status: ";
	$newgroup->changeMemberType($POD->currentUser(),'owner');
	$x = $newgroup->isMember($POD->currentUser());
	if ($x == "owner") { 
		echo "Success! $x <br />";
	} else {
		echo "<b>Failed!</b> Status not changed $x.<Br />";
	}
	


	// quit a group valid


	echo "Quit a group: ";
	$newgroup->removeMember($newuser);		
	if ($newgroup->success()){
		echo "Success!<Br />";
	} else {
		echo "<b>Failed</b> - " . $newgroup->error() . "<Br />";
	}

	// member of group?
	echo "Group->isMember? ";
	if (!($m = $newgroup->isMember($newuser))) {
		echo "Success! $m<br />";	
	} else {
		echo "<B>Failed!</B> Should not have been member!<br />";
	}


	// store current user id
	
	$uid = $POD->currentUser()->get('id');

	// add a document invalid (not in group)

	$POD->changeActor(array('id'=>$newuser->get('id')));
	echo "Add Document (invalid not in group):";
	$doc2 = $POD->getDocument();
	$doc2->set('headline','foo');
	$doc2->set('type','test');
	$doc2->save();
	echo "Created " . $doc2->get('id');

	$newgroup->addDocument($doc2);
	if (!$newgroup->success()){
		echo "Success!- " . $newgroup->error() ."<Br />";
	} else {
		echo "<b>Failed</b><Br />";
	}
		
	echo "CURRENT USER ID: " . $POD->currentUser()->get('id');
	echo "CURRENT USER MEMBERSHIP: " . $newgroup->isMember($POD->currentUser());
	echo "Doc is owned by " . $doc2->get('userId');
	
	// add a document invalid (document belongs to someone else)
	$POD->changeActor(array('id'=>$uid));
//	$POD->currentUser()->set('adminUser',null);

	
/*
	echo "Add Document (invalid belongs to someone else):";
	$newgroup->addDocument($doc2);
	if (!$newgroup->success()){
		echo "Success!- " . $newgroup->error() ."<Br />";
	} else {
		echo "<b>Failed</b><Br />";
	}	
*/

	// add a document valid
	echo "Add Document:";
	$newgroup->addDocument($doc);
	if ($newgroup->success()){
		echo "Success!<Br />";
	} else {
		echo "<b>Failed</b> - " . $newgroup->error() . "<Br />";
	}
	
	// is in group?
	echo "Is in group?";
	if ($newgroup->documents()->contains('id',$doc->get('id'))) {
		echo "Success!<Br />";
	} else {
		echo "<B>Failed!</b> should be in group.";
	}

	// document->groupId?
	echo "Group ID?";
	if ($newgroup->get('id') == $doc->get('groupId')) {
		echo "Success!<Br />";
	} else {
		echo "<B>Failed!</b> should be in group.";
	}

	
	// delete group invalid (not owner)
	$POD->changeActor(array('id'=>$newuser->get('id')));
	echo "Delete group [invalid]: ";
	$newgroup->delete();
	if (!$newgroup->success()){
		echo "Success!<br />";
	} else {
		echo "<b>Failed</b> - " . $newgroup->error() . "<Br />";
		if ($newgroup->success()){ echo "Successssssss";}
	}

	// delete group
	
	echo "Delete group: ";
	$POD->changeActor(array('id'=>$uid));
	$newgroup->delete();
	if ($newgroup->success()){
		echo "Success!<br />";
	} else {
		echo "<b>Failed</b> - " . $newgroup->error() . "<Br />";
	}


	$POD->currentUser()->addMeta('adminUser',1);
	
	echo "Document Parent?";
	$doc->set('documentId',$doc2->get('id'));
	$doc->save();

	if ($doc->parent()->get('id') == $doc2->get('id')) { 
		echo "Success! " . $doc->parent()->get('id') . "<br />";		
	} else {
		echo "<b>Failed</b>!<br />";
	}


//	$doc2 = $POD->getDocument(array('id'=>$doc2->get('id')));
	
	echo "Is in CHILDREN?";
	if ($doc2->children()->contains('id',$doc->get('id'))) {
		echo "success!<br />";
	} else {
		echo "<B>Failed!</b><Br />";
	}





	$newuser->delete();
	$doc->delete();
	
	echo "<h1>Finished!</h1>"

?>