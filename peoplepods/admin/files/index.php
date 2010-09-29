<? 

	include_once("../../PeoplePods.php");	
	
	$POD = new PeoplePod(array('debug'=>2,'lockdown'=>'adminUser','authSecret'=>$_COOKIE['pp_auth']));
	$POD->changeTheme('admin');


	if ($_GET['id']) { 
	
		$file = $POD->getFile(array('id'=>$_GET['id']));
		if (!$file->success()) { 
			$message = $file->error();
		} else {
	
			if ($file->parent()) { 
				$content = $file->parent();
			} else {
				$user = $file->author();
			}
	
		}
	
		$POD->header();
		include_once("tools.php");
		if ($message) { ?>
		
			<div class="info">
				<? echo $message; ?>
			</div>
		
		<? } 
		if ($file->success()) {
			$file->output();
		}			
		$POD->footer(); 
	
	
	} else {

		// load files based on a userId
		// or based on a docId
		// allow new files to be uploaded
		
		$content = null;
		$files = null;
		$user = null;
		$message = null;
		$newfile = null;
		
		if ($_GET['contentId']) { 
		
			$content = $POD->getContent(array('id'=>$_GET['contentId']));
			if (!$content->success()) { 
				$message = $content->error();			
			} else {
				$files = $content->files();
				$title = $content->headline;
			}
			$newfile = $POD->getFile(array('contentId'=>$content->get('id')));
		} else if ($_GET['userId']) { 
		
			$user = $POD->getPerson(array('id'=>$_GET['userId']));
			if (!$user->success()) { 
				$message = $user->error();
			} else {
				$files = $user->files();
				$title = $user->nick;
			}
			$newfile = $POD->getFile(array('userId'=>$user->get('id')));
		
		} else { 
		
			$files = $POD->getFiles();
			$title = "All Files";		
		}
		
		
	
		if ($_POST) { 
			if ($_POST['delete']) { 
				$f = $POD->getFile(array('id'=>$_POST['id']));
				if ($f->success()) { 
					$f->delete();
					if (!$f->success()) { 
						$message = $f->error();
					} else {
						$message = "File deleted!";
					}
				} else {
					$message = $f->error();
				}
			
			} else {

				if ($_GET['contentId']) { 
				
					$content->addFile($_POST['name'],$_FILES['file']);
					if (!$content->success()) {
						$message = $content->error();
					} else {
						$message = "File uploaded!";
					}

				}							
				if ($_GET['userId']) { 
				
					$user->addFile($_POST['name'],$_FILES['file']);
					if (!$user->success()) {
						$message = $user->error();
					} else {
						$message = "File uploaded!";
					}

				}

			}
			
						
			$files->fill();
		}
	
		if (isset($_GET['offset'])) {
			$files->getOtherPage($_GET['offset']);
		}
	
		$POD->header();
		include_once("tools.php");
		if ($message) { ?>
		
			<div class="info">
				<? echo $message; ?>
			</div>
		
		<? } ?>
		<div class="list_panel">
			<h1>Files: <?= $title; ?></h1>
			<?	
				
				$files->output('short','file_header','table_pager');
				
			?>
		</div>
		<?	if ($newfile) { ?>
			<div class="panel">
				<? $newfile->output('upload'); ?>
			</div>
		<? } ?>

	<?	
		$POD->footer(); 
	
	?>
<? } // if !$_GET['id'] ?>