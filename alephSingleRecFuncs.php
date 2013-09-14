<?php

include("alephXfunctions.php");

// Tests

/*
$oclc = "173136007";
$sysNum = singleRecGetSysNumFromOCLC($oclc);
echo $sysNum;
*/

/*
$oclc = "173136007";
$marcXML = singleRecGetMARCfromOCLC($oclc);
print_r ($marcXML);
*/
 
/*
include("getOCLCnums.php");
$oclcNums = getOCLCnums($books);
foreach ($oclcNums as $oclc) {
	$sysNum = singleRecGetSysNumFromOCLC($oclc);
	echo "SysNum: ".$sysNum." OCLC: ".$oclc."</br>";
 	
}
*/

/*
$sysNum = singleRecGetSysNumFromBar("31430057638498");
echo $sysNum;
echo "</br>";
$marcXML = singleRecGetMARCfromBar("31430057638498");
print_r($marcXML);
*/
 
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

// Tests to see if a set number was returne (i.e. was the search successful. Probably should move to alephXfunctions)
function alephTestForSetNum($query, $code) {
	$findURL = alephXbuildFindURL($query, $code);	
	$findXML = alephXfind($findURL);	
	$setNum = alephXgetSetNum($findXML);
	if ($findXML->set_number) {		
		return (True);
	} else {
		return(False);
	} // end if	
} // end alephTestForSetNum

/* *** Functions Returning a Specific MARCXML element *** */

// Returns an Aleph system number matching a query. Best queries are barcode, OCLC number. Can also work with call number, ISBN. 
function singleRecGetSysNum($query, $code) {
	$presentXML = alephSingleRec($query, $code);
	$sysNum = $presentXML->record->doc_number;
	return($sysNum);
}


/* *** Functions For Retrieving Info from a Barcode *** */

// Return an Aleph system number for a given barcode
function singleRecGetSysNumFromBar($barcode) {
	$sysNum = singleRecGetSysNum($barcode, "BAR");
	return($sysNum);
}

// Return MARCXML for a given barcode
function singleRecGetMARCfromBar($barcode) {
	$marcXML = alephSingleRec($barcode, "BAR");
	return ($marcXML);
}

/* *** Functions For Retrieving Info from an OCLC number *** */

// OCLC numbers have to be 8 digits for sending to Aleph. If less than 8, add zeroes.
function padOCLCnum($oclc) {
	if (strlen($oclc) < 8) {
		if (strlen($oclc) == 6) {
			$oclcAleph = "00".$oclc;			
		} elseif (strlen($oclc) == 7) {
			$oclcAleph = "0".$oclc;			
		}		
	} else {
		$oclcAleph = $oclc;
	}
	return($oclcAleph);
}

function singleRecGetSysNumFromOCLC($oclc) {		
	$code = "035";
	$oclcAleph = padOCLCnum($oclc);	
	$prefix = "ocm";	
	$oclcPre = $prefix.$oclcAleph;
	if ($test = alephTestForSetNum($oclcPre, $code)) {
		$sysNum = singleRecGetSysNum($oclcPre, $code);
		return($sysNum);
	} else {			
		$prefix = "ocn";		
		$oclcPre = $prefix.$oclcAleph;		
		if ($test = alephTestForSetNum($oclcPre, $code)) {
			$sysNum = singleRecGetSysNum($oclcPre, $code);
			return($sysNum);			
		} // end if
	} // end if
	
} // end function

function singleRecGetMARCfromOCLC($oclc) {		
	$code = "035";
	$oclcAleph = padOCLCnum($oclc);
	$prefix = "ocm";	
	$oclcPre = $prefix.$oclcAleph;
	if ($test = alephTestForSetNum($oclcPre, $code)) {
		$marcXML = alephSingleRec($oclcPre, $code);
		return($marcXML);
	} else {			
		$prefix = "ocn";		
		$oclcPre = $prefix.$oclcAleph;		
		if ($test = alephTestForSetNum($oclcPre, $code)) {
			$marcXML = alephSingleRec($oclcPre, $code);
			return($marcXML);
		} // end if
	} // end if
	
} // end function

?>