<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/stacks/pager.php
* Footer template which includes next/previous navigation
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/stack-output
/**********************************************/
?>	
<? if ($this->count() == 0) { ?>
		<tr colspan="5">
			<td class="empty_list">
			<? if ($empty_message) {
				echo $empty_message; 
			} else { ?>
			Nothing to show!
			<? } ?>
			</td>
		</tr>
	<? } ?>
	<tr>
		<td colspan="6" class="bulk">
			Bulk Operations 
			<input type="submit" value="Delete" name="command" onclick="return confirmBulkDelete();" />
			<input type="submit" value="Not Spam" name="command" onclick="return confirmBulkNotSpam();" />
		</td>
	</tr>
	<tr >
		<td colspan="6" class="stack_footer">
		<? if ($this->hasPreviousPage()) { echo '<a href="?offset=' . $this->previousPage() . $additional_parameters . '" class="stack_previous_link">Previous</a>'; } ?>
		<? if ($this->hasNextPage()) { echo '<a href="?offset=' . $this->nextPage() . $additional_parameters . '" class="stack_next_link">Next</a>'; }	?>
		</td>
	</tr>
</table>
</form>