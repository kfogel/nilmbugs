<?= $doc->headline; ?> (<?= $doc->permalink ?>)
Status: <?= $doc->bug_status; ?> | Jurisdiction: <? $jurisdiction = $POD->getContent(array('id'=>$doc->bug_target)); echo $jurisdiction->headline; ?> | Date Filed: <?= date('Y-m-d',strtotime($doc->date)); ?>

Recent Activity:

<? $new_comments = $POD->getComments(array('contentId'=>$doc->id,'date:gt'=>date('Y-m-d H:i',strtotime("-1 hour")))); 
$new_comments->output('subscriptions/comment',null,null); ?>

==========================================

