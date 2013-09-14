<?php

// Build a url for use in the alephXfind() function. Requires a $request and a $code (i.e. "BAR")  
function alephXbuildFindURL($request, $code, $base = "CP", $op = "find") {
	$findURL = "http://catalog.umd.edu/X?request=".$request."&op=".$op."&code=".$code."&base=".$base;
	return($findURL);
}

// Build a url for use in the alephXpresent() function. Requires the $setNum returned by alephXgetSetNum()
function alephXbuildPresentURL($setNum, $setEntry = 1) {
	$presentURL = "http://catalog.umd.edu/X?set_no=".$setNum."&set_entry=".$setEntry."&op=present";
	return($presentURL);
}

// Returns XML about a set of resuts. Requires the $findURL from alephXbuildFindURL
function alephXfind($findURL) {	
	$findResults = file_get_contents($findURL); // get results of find request
	$findXML = new SimpleXMLElement($findResults); // turn the results into an XML object
	return($findXML);	
} // end alephFind

// Returns set number for a result set. Requires $findXML returned from alephXfind()
function alephXgetSetNum($findXML) {			
	$setNum = $findXML->set_number;
	return($setNum);	
}		

// Returns MarcXML for a record. Default is the first result of a set. Requires the $findURL from alephXbuildFindURL
function alephXpresent($presentURL, $setEntry = 1){	
	$presentResults = file_get_contents($presentURL);
	$presentXML = new SimpleXMLElement($presentResults);
	return($presentXML); 
} // end alephPresent



 
?>    
 