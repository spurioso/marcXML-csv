<?php

/*Function alephFind sends an Aleph "find" request to Aleph X-Services (the Aleph API).
 * If successful, the Find request returns the number of results for the query, a unique number for the set
 * of results ("set_number"), and some other things. If unsuccessful, Find returns an error message.
 * An X-Services "Present" request returns MARCXML for a given set_entry in a set_number. The alephPresent function
 * handles this step.
 */
 
function buildFindURL($request, $code, $base = "CP", $op = "find") {
	$findURL = "http://catalog.umd.edu/X?request=".$request."&op=".$op."&code=".$code."&base=".$base;
	return($findURL);
}

function buildPresentURL($alephSetNumber, $setEntry = 1) {
	$presentURL = "http://catalog.umd.edu/X?set_no=".$alephSetNumber."&set_entry=".$setEntry."&op=present";
	return($presentURL);
}

function alephFind($request, $code, $base = "CP", $op = "find") {
	/* Build the url for Aleph x-services find request. Returns set number if successful */	
	$findURL = "http://catalog.umd.edu/X?request=".$request."&op=".$op."&code=".$code."&base=".$base;	
	if (file_get_contents($findURL) === False) { // something went wrong with the HTTP request or other unforseen errors occurred
		$error = "<item><error>unknown error</error></item>";     	
     	$alephFindXML = new SimpleXMLElement($error);
        return($alephFindXML); 
	} else {
		$findResults = file_get_contents($findURL); // get results of find request
		$alephFindXML = new SimpleXMLElement($findResults); // turn the results into an XML object
		return($alephFindXML);	
	} // end if
} // end alephFind		

function alephPresent($alephSetNumber, $setEntry = 1){
	/*present url gets the actual MARC XML file for the results set
	 * and loads it into a PHP object
	 */	
	$presentURL = "http://catalog.umd.edu/X?set_no=".$alephSetNumber."&set_entry=".$setEntry."&op=present";
	$presentResults = file_get_contents($presentURL);
	$alephPresentXML = new SimpleXMLElement($presentResults);
	return($alephPresentXML); 
} // end alephPresent

?>    
 