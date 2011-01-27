<?

Browser::addBrowseMethod('state','state_starters','Browse by State',null,'state_default','state_browseBy');

function state_starters($b) {

	// get a list of states that have bugs attached
	$sql = "SELECT distinct(value) as state FROM meta WHERE name='jurisdiction_contact_state' limit 12;";
	$res = $b->POD->executeSQL($sql);
	
	$ret = array();
	while ($r = mysql_fetch_assoc($res)) {
		$ret[$r['state']] = strtoupper($r['state']);
	}
		
	return $ret;
}


function state_default($b,$sort,$offset) {
	$POD = $b->POD;
	
	$b->crumbs('By State');
	echo '<ul style="list-style-type: none;">
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=AK"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/AK.png" alt="Alaska" />&nbsp;Alaska&nbsp;(AK)</a></li><!-- Alaska -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=AL"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/AL.png" alt="Alabama" />&nbsp;Alabama&nbsp;(AL)</a></li><!-- Alabama -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=AR"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/AR.png" alt="Arkansas" />&nbsp;Arkansas&nbsp;(AR)</a></li><!-- Arkansas -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=AZ"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/AZ.png" alt="Arizona" />&nbsp;Arizona&nbsp;(AZ)</a></li><!-- Arizona -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=CA"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/CA.png" alt="California" />&nbsp;California&nbsp;(CA)</a></li><!-- California -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=CO"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/CO.png" alt="Colorado" />&nbsp;Colorado&nbsp;(CO)</a></li><!-- Colorado -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=CT"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/CT.png" alt="Connecticut" />&nbsp;Connecticut&nbsp;(CT)</a></li><!-- Connecticut -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=DE"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/DE.png" alt="Delaware" />&nbsp;Delaware&nbsp;(DE)</a></li><!-- Delaware -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=FL"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/FL.png" alt="Florida" />&nbsp;Florida&nbsp;(FL)</a></li><!-- Florida -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=GA"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/GA.png" alt="Georgia" />&nbsp;Georgia&nbsp;(GA)</a></li><!-- Georgia -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=Hawaii"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/HI.png" alt="Hawaii" />&nbsp;Hawaii&nbsp;(Hawaii)</a></li><!-- Hawaii -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=IA"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/IA.png" alt="Iowa" />&nbsp;Iowa&nbsp;(IA)</a></li><!-- Iowa -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=ID"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/ID.png" alt="Idaho" />&nbsp;Idaho&nbsp;(ID)</a></li><!-- Idaho -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=IL"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/IL.png" alt="Illinois" />&nbsp;Illinois&nbsp;(IL)</a></li><!-- Illinois -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=IN"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/IN.png" alt="Indiana" />&nbsp;Indiana&nbsp;(IN)</a></li><!-- Indiana -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=KS"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/KS.png" alt="Kansas" />&nbsp;Kansas&nbsp;(KS)</a></li><!-- Kansas -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=KY"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/KY.png" alt="Kentucky" />&nbsp;Kentucky&nbsp;(KY)</a></li><!-- Kentucky -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=LA"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/LA.png" alt="Louisiana" />&nbsp;Louisiana&nbsp;(LA)</a></li><!-- Louisiana -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=MA"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/MA.png" alt="Massachusetts" />&nbsp;Massachusetts&nbsp;(MA)</a></li><!-- Massachusetts -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=MD"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/MD.png" alt="Maryland" />&nbsp;Maryland&nbsp;(MD)</a></li><!-- Maryland -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=ME"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/ME.png" alt="Maine" />&nbsp;Maine&nbsp;(ME)</a></li><!-- Maine -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=MI"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/MI.png" alt="Michigan" />&nbsp;Michigan&nbsp;(MI)</a></li><!-- Michigan -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=MN"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/MN.png" alt="Minnesota" />&nbsp;Minnesota&nbsp;(MN)</a></li><!-- Minnesota -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=MO"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/MO.png" alt="Missouri" />&nbsp;Missouri&nbsp;(MO)</a></li><!-- Missouri -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=MS"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/MS.png" alt="Mississippi" />&nbsp;Mississippi&nbsp;(MS)</a></li><!-- Mississippi -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=MT"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/MT.png" alt="Montana" />&nbsp;Montana&nbsp;(MT)</a></li><!-- Montana -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=NC"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/NC.png" alt="North Carolina" />&nbsp;North Carolina&nbsp;(NC)</a></li><!-- North Carolina -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=ND"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/ND.png" alt="North Dakota" />&nbsp;North Dakota&nbsp;(ND)</a></li><!-- North Dakota -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=NE"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/NE.png" alt="Nebraska" />&nbsp;Nebraska&nbsp;(NE)</a></li><!-- Nebraska -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=NH"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/NH.png" alt="New Hampshire" />&nbsp;New Hampshire&nbsp;(NH)</a></li><!-- New Hampshire -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=NJ"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/NJ.png" alt="New Jersey" />&nbsp;New Jersey&nbsp;(NJ)</a></li><!-- New Jersey -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=NM"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/NM.png" alt="New Mexico" />&nbsp;New Mexico&nbsp;(NM)</a></li><!-- New Mexico -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=NV"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/NV.png" alt="Nevada" />&nbsp;Nevada&nbsp;(NV)</a></li><!-- Nevada -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=NY"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/NY.png" alt="New York" />&nbsp;New York&nbsp;(NY)</a></li><!-- New York -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=OH"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/OH.png" style="border: none;" alt="Ohio" />&nbsp;Ohio&nbsp;(OH)</a></li><!-- Ohio -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=OK"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/OK.png" alt="Oklahoma" />&nbsp;Oklahoma&nbsp;(OK)</a></li><!-- Oklahoma -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=OR"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/OR.png" alt="Oregon" />&nbsp;Oregon&nbsp;(OR)</a></li><!-- Oregon -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=PA"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/PA.png" alt="Pennsylvania" />&nbsp;Pennsylvania&nbsp;(PA)</a></li><!-- Pennsylvania -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=RI"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/RI.png" alt="Rhode Island" />&nbsp;Rhode Island&nbsp;(RI)</a></li><!-- Rhode Island -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=SC"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/SC.png" alt="South Carolina" />&nbsp;South Carolina&nbsp;(SC)</a></li><!-- South Carolina -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=SD"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/SD.png" alt="South Dakota" />&nbsp;South Dakota&nbsp;(SD)</a></li><!-- South Dakota -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=TN"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/TN.png" alt="Tennessee" />&nbsp;Tennessee&nbsp;(TN)</a></li><!-- Tennessee -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=TX"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/TX.png" alt="Texas" />&nbsp;Texas&nbsp;(TX)</a></li><!-- Texas -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=UT"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/UT.png" alt="Utah" />&nbsp;Utah&nbsp;(UT)</a></li><!-- Utah -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=VA"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/VA.png" alt="Virginia" />&nbsp;Virginia&nbsp;(VA)</a></li><!-- Virginia -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=VT"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/VT.png" alt="Vermont" />&nbsp;Vermont&nbsp;(VT)</a></li><!-- Vermont -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=WA"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/WA.png" alt="Washington" />&nbsp;Washington&nbsp;(WA)</a></li><!-- Washington -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=WI"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/WI.png" alt="Wisconsin" />&nbsp;Wisconsin&nbsp;(WI)</a></li><!-- Wisconsin -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=WV"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/WV.png" alt="West Virginia" />&nbsp;West Virginia&nbsp;(WV)</a></li><!-- West Virginia -->
			<li><a href="' . $POD->siteRoot(false) . '/bugs/browse/state?q=WY"><img width="35" src="' . $POD->templateDir(false) . '/img/flags/WY.png" alt="Wyoming" />&nbsp;Wyoming&nbsp;(WY)</a></li><!-- Wyoming -->
		</ul>';

}

function state_browseBy($b,$keyword,$sort,$offset) { 

	$b->addCrumbs('<a href="' . $b->POD->siteRoot(false).'/bugs/browse/state">By State</a>');
	$b->addCrumbs(strtoupper($keyword));	
	return array('jurisdiction_contact_state'=>$keyword,'bug_status:!='=>'closed:off topic');

}