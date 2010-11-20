<?= $doc->headline; ?> (<?= $doc->permalink ?>)
Status: <?= $doc->bug_status; ?> | Jurisdiction: <? $jurisdiction = $POD->getContent(array('id'=>$doc->bug_target)); echo $jurisdiction->headline; ?> | Date Filed: <?= date('Y-m-d',strtotime($doc->date)); ?>


<?= $doc->body; ?>


==========================================

