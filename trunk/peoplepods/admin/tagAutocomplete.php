<? 
	include_once("../lib/Core.php");	
	$POD = new PeoplePod(array('lockdown'=>'adminUser','authSecret'=>$_COOKIE['pp_auth']));

	$v = $_POST['tag'];
	$v = mysql_real_escape_string($v);
	$sql = "SELECT distinct value FROM tags WHERE value like '$v%' limit 10;";
	$res = mysql_query($sql,$POD->DATABASE);
	echo "<ul>";
	while ($r = mysql_fetch_assoc($res)) { 
		echo "<li>" . $r['value'] . "</li>\n";
	}
	echo "</ul>";
	