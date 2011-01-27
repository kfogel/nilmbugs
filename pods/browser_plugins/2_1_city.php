<?

Browser::addBrowseMethod('city','city_starters','Browse by City',null,'city_default','city_browseBy');

function city_starters($b) {

	// get a list of cities that have bugs attached
	$sql = "SELECT distinct(value) as city FROM meta WHERE name='jurisdiction_contact_city' limit 12;";
	$res = $b->POD->executeSQL($sql);
	
	$ret = array();
	while ($r = mysql_fetch_assoc($res)) {
		$ret[$r['city']] = ucwords($r['city']);
	}
		
	return $ret;
}


function city_default($b,$sort,$offset) {
	
	// just display the same starters from the homepage
	$b->crumbs('By City');
	echo "<ul class=\"directory\">";
	$b->browseStarters('city');
	echo "</ul>";
}

function city_browseBy($b,$keyword,$sort,$offset) { 

	$b->addCrumbs('<a href="' . $b->POD->siteRoot(false).'/bugs/browse/city">By City</a>');
	$b->addCrumbs(ucwords($keyword));	
	return array('jurisdiction_contact_city'=>$keyword,'bug_status:!='=>'closed:off topic');

}