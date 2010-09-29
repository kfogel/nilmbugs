<div class="comment">
	<div class="column_4">
		<div class="column_padding">
		<? 	
			$comment->writeFormatted('comment');
			echo "<br />";
			$comment->author()->permalink('nick'); 
			echo "&nbsp;&nbsp;(" . $this->POD->timesince($comment->get('minutes')) . ")";
			?>
		</div>
	</div>
	<div class="column_1 last subItemControls">
		<div class="column_padding">
			<a href="#" onclick="return deleteComment(<? $comment->write('id') ?>);">Delete</a>		
		</div>
	</div>
	<div class="clearer"></div>
</div>