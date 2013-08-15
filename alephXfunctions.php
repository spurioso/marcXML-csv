<?php
/* Returns a set number for a given request. $request and $code should be passed
 * into the function. $base and $op default to "CP" and "find." To chance to a different base,
 * say for Towson, pass in a third parameter when calling the function. (Need to document where
 * the base codes are.) alephFind returns $alephSetNumber, which can be used in alephPresent
 * to retrieve corresponding MarcXML records.  
 */
function alephFind($request, $code, $base = "CP", $op = "find") {
	/* Build the url for Aleph x-services find request. Returns set number if successful */	
	$findURL = "http://catalog.umd.edu/X?request=".$request."&op=".$op."&code=".$code."&base=".$base;
	$findResults = file_get_contents($findURL); // get results of find request...	
	$alephFindXML = new SimpleXMLElement($findResults); //...and turn the results into an XML object
    if ($alephFindXML->set_number) {        //check for set number
        $alephSetNumber = $alephFindXML->set_number; //if there is a set number, store it in a variable
        return($alephSetNumber);               
    } elseif ($alephFindXML->error) {        //error is returned if barcode not found
        return("Barcode not found <br />");        
    } else {
        return("Something went horribly wrong <br />"); //handles other unforseen errors       
        
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
 