<?php
function alephFind($query, $code = "SYS", $op = "find", $base = "CP") {
	/* Build the url for Aleph x-services find request. Returns set number if successful */	
	$findURL = "http://catalog.umd.edu/X?request=".$query."&op=".$op."&code=".$code."&base=".$base;
	$findResults = file_get_contents($findURL); // get results of find request...	
	$alephFindXML = new SimpleXMLElement($findResults); //...and turn the results into an XML object
    if ($alephFindXML->set_number) {        //check for set number
        $alephSetNumber = $alephFindXML->set_number; //if there is a set number, store it in a variable
        return($alephSetNumber);
        //echo "Set Number: ".$alephSetNumber."<br />";        
    } elseif ($alephFindXML->error) {        //error is returned if barcode not found
        return("Barcode not found <br />");        
    } else {
        return("Something went horribly wrong <br />"); //handles other unforseen errors       
        
    } // end if
} // end find
?>    
 