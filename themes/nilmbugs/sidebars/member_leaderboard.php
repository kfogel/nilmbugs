<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/sidebars/recent_visitors.php
* Displays last 5 visitors
*
* Use this in other templates:
* $POD->output('sidebars/recent_visitors');
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/person-object
/**********************************************/
?><? $recent = $POD->getPeople(array('leaderboard:!='=>'null','leaderboard:!='=>'0'),'u_m_leaderboard.value DESC',5); ?>
<div class="column_padding sidebar" id="recent_visitors_sidebar">
	<h3 style="padding-top: 0px; margin-top: 0px;">Active Contributors</h3>
	<? $recent->output('member_leaderboard','ul_header','ul_footer'); ?>
</div>