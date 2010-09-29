<? 

	include_once("../../PeoplePods.php");	
	
	$POD = new PeoplePod(array('debug'=>2,'lockdown'=>'adminUser','authSecret'=>$_COOKIE['pp_auth']));
	$POD->changeTheme('admin');

	$offset = $_GET['offset'] ? $_GET['offset'] : 0;
		
		$content = null;
		$oflags = null;
		$iflags = null;

		$user = null;
		$message = null;
		
		if ($_GET['removeFlag']) { 
			
			$flag = $_GET['removeFlag'];
			$type = $_GET['type'];
			$itemId = $_GET['itemId'];
			$userId = $_GET['userId'];
			
			if ($type=="user") { 
				$obj = $POD->getPerson(array('id'=>$itemId));
			} else {
				$obj = $POD->getContent(array('id'=>$itemId));
			}
			
			$u = $POD->getPerson(array('id'=>$userId));
			
			$obj->removeFlag($flag,$u);
			if (!$obj->success()) { 
				$ret['error'] = $obj->error();
			} else {
				$ret = $_GET;
				$ret['flag'] = $flag;
				$ret['new_action'] = "add";
				$ret['old_action'] = "remove";

			}
			
			echo json_encode($ret);
			exit;
		
		} else if ($_GET['addFlag']) { 
			$flag = $_GET['addFlag'];
			$type = $_GET['type'];
			$itemId = $_GET['itemId'];
			$userId = $_GET['userId'];
			
			if ($type=="user") { 
				$obj = $POD->getPerson(array('id'=>$itemId));
			} else {
				$obj = $POD->getContent(array('id'=>$itemId));
			}
			
			$u = $POD->getPerson(array('id'=>$userId));
			
			$obj->addFlag($flag,$u);
			if (!$obj->success()) { 
				$ret['error'] = $obj->error();
			} else {
				$ret = $_GET;
				$ret['flag'] = $flag;
				$ret['new_action'] = "remove";
				$ret['old_action'] = "add";

			}
			
			echo json_encode($ret);
			exit;		
		  exit;
		}
		
		if ($_GET['contentId']) { 
		
			$content = $POD->getContent(array('id'=>$_GET['contentId']));
			if (!$content->success()) { 
				$message = $content->error();			
			} else {
				$iflags = $content->getInFlags();	
				if (!$content->success()) { 
					$message = $content->error();
				}
			}
		}
		
		if ($_GET['userId']) { 
		
			$user = $POD->getPerson(array('id'=>$_GET['userId']));
			if (!$user->success()) { 
				$message = $user->error();
			} else {
				$iflags = $user->getInFlags();
				$oflags = $user->getOutFlags();
				if (!$user->success()) { 
					$message = $user->error();
				}

			}			
		
		}
		
		
		if ($user || $content) { 
			
			if ($_GET['flag'] && $_GET['type'] && $_GET['direction']) { 
	
				$options = array();
				$options['flag.name'] = $_GET['flag'];
				$description = '';
				if ($_GET['direction'] == "in") { 
					if ($user) { 
						$options['flag.itemId'] = $user->get('id');
						$description = "People who flagged {$user->get('nick')} with the '" . $_GET['flag'] . "' flag";
					} else {
						$options['flag.itemId'] = $content->get('id');
						$description = "People who have flagged this content with the '" . $_GET['flag'] . "' flag";
					}
				} else {
					$options['flag.userId'] = $user->get('id');
					if ($_GET['type']=="user") {
						$description = "People {$user->get('nick')} flagged with the '" . $_GET['flag'] . "' flag";
					} else {
						$description = "Content {$user->get('nick')} flagged with the '" . $_GET['flag'] . "' flag";
					}
	
				}
	
				if ($_GET['type'] == "user") { 
				
					$results = $POD->getPeople($options,'flag.date desc',20,$offset);
				} else {
					$results = $POD->getContents($options,'flag.date desc',20,$offset);
				}
			
			}
		} else {
		
			if ($_GET['flag'] && $_GET['type']) { 
	
				$options = array();
				$options['flag.name'] = $_GET['flag'];
				$description = '';
				if ($_GET['type']=="user") {
					$description = "People flagged with the '" . $_GET['flag'] . "' flag";
				} else {
					$description = "Content flagged with the '" . $_GET['flag'] . "' flag";
				}
	
				if ($_GET['type'] == "user") { 
					$results = $POD->getPeople($options,'flag.date desc',20,$offset);
				} else if ($_GET['type'] == "comment") { 
					$results = $POD->getComments($options,'flag.date desc',20,$offset);
				} else if ($_GET['type'] == "content") { 
					$results = $POD->getContents($options,'flag.date desc',20,$offset);
				}
			
			}	
		
		
		
		}

	
		if ($_POST) { 
			if ($_POST['delete']) { 
			
			} else {

			}
			
		}
	
	
		$POD->header();
		include_once("tools.php");
		if ($message) { ?>
		
			<div class="info">
				<? echo $message; ?>
			</div>
		
		<? } ?>

		<div class="list_panel">
			<? if ($oflags || $iflags) { ?>
			<ul id="content_type">
			
				<li>Choose Flag:</li>
				<? if ($oflags) foreach ($oflags as $flag=>$type) { ?>
					<li <? if ($_GET['flag']==$flag && $_GET['direction']=="out") {?>class="active"<? } ?>><a href="?userId=<?= $_GET['userId']; ?>&flag=<?= $flag; ?>&type=<?= $type ?>&direction=out"><?= $flag; ?> &rarr;</a></li>
				<? } ?>
				<? if ($iflags) foreach ($iflags as $flag=>$type) { ?>
					<li <? if ($_GET['flag']==$flag && $_GET['direction']=="in") {?>class="active"<? } ?>><a href="?userId=<?= $_GET['userId']; ?>&contentId=<?= $_GET['contentId']; ?>&flag=<?= $flag; ?>&type=user&direction=in"><?= $flag; ?> &larr;</a></li>
				<? } ?>
			</ul>
			<? } ?>
	<?	
		if ($results) { ?>
			<h1 class="column_padding"><a href="<? $POD->podRoot(); ?>/admin/flags/">Flags</a>: <a href="index.php?<?= $content ? 'contentId='.$content->id : 'userId=' . $user->id; ?>"><?= $content ? $content->headline : $user->nick; ?></a> &#187; <?= $description; ?></h1>
			<? foreach ($results as $result) { 
					$result->set('flag',$_GET['flag'],false);
					if ($content || $user) {
						if ($content) { 
							$result->set('flag_itemId',$content->get('id'),false);
							$result->set('flag_type','content',false);
							$result->set('flag_userId',$result->get('id'),false);
		
						} else {
							$result->set('flag_type','user',false);
							if ($_GET['direction']=="out") {
								$result->set('flag_itemId',$result->get('id'),false);
								$result->set('flag_userId',$user->get('id'),false);
							} else {
								$result->set('flag_itemId',$user->get('id'),false);
								$result->set('flag_userId',$result->get('id'),false);
							}
						}
					}
				}
				$results->output('flagged','header','pager',null,'Nothing with this flag',"&userId={$_GET['userId']}&contentId={$_GET['contentId']}&flag={$_GET['flag'] }&type={$_GET['type']}&direction={$_GET['direction']}"); 
			?>
		<? } else { 
		
			if ($oflags || $iflags) { ?>
				<div class="empty_list">
					
					<h1><a href="<? $POD->podRoot(); ?>/admin/flags/">Flags</a>: <?= $content ? $content->headline : $user->nick; ?></h1>
					<? if ($oflags) { ?>
						<p>This person has added flags to things:</p>
						<ul class="flag_list">
						<? foreach($oflags as $flag=>$type) { ?>
							<li><a href="?userId=<?= $_GET['userId']; ?>&flag=<?= $flag; ?>&type=<?= $type ?>&direction=out"><strong><?= $flag; ?></strong></a> <? if ($type=="content") { echo $POD->pluralize($user->flaggedCount($flag),"@number piece of content","@number pieces of content"); } else { echo $POD->pluralize($user->flaggedCount($flag),"@number person","@number people"); } ?></li>	
						<? } ?>
						</ul>
					<? }
					
					if ($iflags) { ?>
						<p>Flags have been added to this <?= $content ? 'content' : 'person'; ?>:</p>
						<ul class="flag_list">
						<? foreach($iflags as $flag=>$type) { ?>
							<li><a href="?userId=<?= $_GET['userId']; ?>&contentId=<?= $_GET['contentId']; ?>&flag=<?= $flag; ?>&type=user&direction=in"><strong><?= $flag; ?></strong></a> Added by  <?= $content ? $POD->pluralize($content->flagCount($flag),'@number person','@number people') : $POD->pluralize($user->flagCount($flag),'@number person','@number people'); ?></li>					
						<? } ?>
						</ul>
					<? } ?>
				
				</div>
				
			<? } else { ?>
				<div class="empty_list">

					<? if ($content || $user) { ?>
					<h1>Flags: <?= $content ? $content->headline : $user->nick; ?></h1>

					<p>No flags</p>
					
					<? } else { ?>
					
						<h1>Flags:</h1>			
						<ul class="flag_list">		
						<?
						
							$flags = $POD->getFlagList();
							foreach ($flags as $flag) { ?>
								<li><a href="<? $POD->podRoot(); ?>/admin/flags/?flag=<?= $flag['name']; ?>&type=<?= $flag['type']; ?>"><?= $POD->pluralize($flag['count'],'@number ' . $flag['type'],'@number ' . $flag['type'] .'s'); ?> flagged <?= $flag['name']; ?></a></li>
							<? } ?>					
						<? } ?>
						</ul>

				</div>
			<? } ?>
		
		
		<? } ?>
		</div>
	<?
		$POD->footer(); 	
	?>