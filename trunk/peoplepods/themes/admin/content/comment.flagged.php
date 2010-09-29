<div class="doc_short">
	<div class="column_8">
		<div class="column_padding">
			<a href="<? $comment->POD->podRoot(); ?>/admin/comments/?id=<? $comment->write('id'); ?>"  title="View this content"><? $comment->write('comment'); ?></a>
		</div>
	</div>
	<div class="column_2 last">
		<div class="column_padding">
			<? if ($comment->flag_userId) { ?>
			<a href="#" id="remove_<? $comment->write('flag') ?>_<? $comment->write('flag_itemId') ?>_<? $comment->write('flag_userId'); ?>" onclick="return removeFlag('<? $comment->write('flag'); ?>','<? $comment->write('flag_type'); ?>',<? $comment->write('flag_itemId'); ?>,<? $comment->write('flag_userId'); ?>);">Remove <? $comment->write('flag'); ?></a>
			<a href="#" style="display:none;" id="add_<? $comment->write('flag') ?>_<? $comment->write('flag_itemId') ?>_<? $comment->write('flag_userId'); ?>" onclick="return addFlag('<? $comment->write('flag'); ?>','<? $comment->write('flag_type'); ?>',<? $comment->write('flag_itemId'); ?>,<? $comment->write('flag_userId'); ?>);">Add <? $comment->write('flag'); ?></a>
			<? } else { 
				echo $POD->pluralize($comment->flagCount($comment->flag),'@number time','@number times');
			} ?>
		</div>
	</div>
	<div class="clearer"></div>
</div>