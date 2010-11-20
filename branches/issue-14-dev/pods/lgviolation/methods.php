<?
	// this file contains additional methods pertaining to the
	// lgviolation content type 

	function newFunction($violation) { 
		// do something with the $violation content
	}
	
	function getLawGovViolations($POD) { 
                $violations = $POD->getContents(array('type'=>'lgviolation'));
                // FIXME: This relies on the admin having entered
		// the violations in order into the database.
                $violations->sortBy('id', 1);
                return $violations;
	}

	// turn on $doc->newFunction();	
	Content::registerMethod('newFunction');
	
	// turn on $POD->getLawGovViolations();
	PeoplePod::registerMethod('getLawGovViolations');
