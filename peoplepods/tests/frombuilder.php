<?

	require_once("../PeoplePods.php");
	$POD = new PeoplePod(array('debug'=>2));
	
	
	// get files from a user
	$files = $POD->getFiles(array('u.nick'=>'admin'));
	//$files->fill();

	// get files by document fields
	$files = $POD->getFiles(array('d.search:!='=>'null'));
 	// $files->fill();

	// get content that has a speecific file
	$content = $POD->getContents(array('f.file_name'=>'img'));
	//$content->fill();

	// get documents by a tag
	$content = $POD->getContents(array('t.value'=>'foo'));
	// $content->fill();
	
	// get tags by document fields
	$tags = $POD->getTags(array('d.type'=>'document'));
	//$tags->fill();
	
		
	// get comments by user
	$comments = $POD->getComments(array('u.adminUser'=>'1','u.foo'=>'bar'));
	//$comments->fill();	
	
	// get comments by document
	$comments = $POD->getComments(array('d.bar'=>'baz'));
	//$comments->fill();
	
	
	$content = $POD->getContents(array('u.bar'=>'baz','u.foo:!='=>'null'));
	//$content->fill();
	
	
	
	// get content from anyone named 'admin'
	$content = $POD->getContents(array('u.nick'=>'admin'));
//	$content->fill();
	error_log("---------------------------------------------");
	
	// get content from anyone who has a meta field named foo
	$content = $POD->getContents(array('u.foo'=>'bar'));
//	$content->fill();
	error_log("---------------------------------------------");
	
	// get content from anyone who has 2 meta fields
	$content = $POD->getContents(array('u.foo'=>'bar','u.bar'=>'baz'));
//	$content->fill();
	error_log("---------------------------------------------");


	// get all users who are a member of group x
	$people = $POD->getPeople(array('g.groupname'=>'test'));
//	$people->fill();
	error_log("---------------------------------------------");
	

	// get all the documents whose parent is "Test post!"
	$docs = $POD->getContents(array('p.headline'=>'test post!'));
//	$docs->fill();
	error_log("---------------------------------------------");


	// get all groups that a user owns
	$groups = $POD->getGroups(array('mem.userId'=>13,'mem.type'=>'owner'));
//	$groups->fill();
	error_log("---------------------------------------------");


	// get all groups that a user created
	$groups = $POD->getGroups(array('o.id'=>13));
//	$groups->fill();
	error_log("---------------------------------------------");


	// get all members of any groups that a user owns
	$people = $POD->getPeople(array('g.userId'=>13));
//	$people->fill();
	error_log("---------------------------------------------");


	// get all the groups I am a member of
	$groups = $POD->getGroups(array('mem.userId'=>13));
//	$groups->fill();

	error_log("---------------------------------------------");

	// get groups created by an admin user
	$groups = $POD->getGroups(array('u.adminUser'=>1));
//	$groups->fill();

	// get groups created by an admin user and containing content with a certain field
	$groups = $POD->getGroups(array('u.adminUser'=>1,'d.bar'=>'baccz'));
//	$groups->fill();


?>