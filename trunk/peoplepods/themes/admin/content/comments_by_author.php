<div class="comment">
	<div class="column_padding">
		<? 	
			$comment->writeFormatted('comment');
			echo "<br />";
			$comment->parent()->permalink();
			echo "&nbsp;&nbsp;(" . $this->POD->timesince($comment->get('minutes')) . ")";
			?>
	</div>
</div>