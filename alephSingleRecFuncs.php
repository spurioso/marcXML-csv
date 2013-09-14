<?php

include("alephXfunctions.php");

$sysNum = alephXgetSysNumFromBar("31430057638498");
echo $sysNum;
echo "</br>";
$marcXML = alephXgetMARCfromBar("31430057638498");
print_r($marcXML);

/* Returns MarcXML for a single record. Requires a query and the code for the query. If more than one record matches the query
the first result is returned. Best uses of this function are retrieving a record based on barcode, OCLC number, or Aleph
 * system number. Can also be used for ISBN or call number*/ 
function alephSingleRec($query, $code) {
	$findURL = alephXbuildFindURL($query, $code);	
	$findXML = alephXfind($findURL);	
	$setNum = alephXgetSetNum($findXML);	
	$presentURL = alephXbuildPresentURL($setNum);
	$presentXML = alephXpresent($presentURL);
	return($presentXML);	
}

/* *** Functions Returning a Specific MARCXML element *** */

// Returns an Aleph system number matching a query. Best queries are barcode, OCLC number. Can also work with call number, ISBN. 
function alephXgetSysNum($query, $code) {
	$presentXML = alephSingleRec($query, $code);
	$sysNum = $presentXML->record->doc_number;
	return($sysNum);
}


/* *** Functions For Retrieving Info from a Barcode *** */

// Return an Aleph system number for a given barcode
function alephXgetSysNumFromBar($barcode) {
	$sysNum = alephXgetSysNum($barcode, "BAR");
	return($sysNum);
}

// Return MARCXML for a given barcode
function alephXgetMARCfromBar($barcode) {
	$marcXML = alephSingleRec($barcode, "BAR");
	return ($marcXML);
}

?>