<?php

include("alephXfunctions.php");
 
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

// Tests to see if a set number was returned (i.e. was the search successful. Probably should move to alephXfunctions)
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

/* *** Functions For Retrieving Info from a non-LC call number *** */

// Return an Aleph system number for a given non-LC call number
function singleRecGetSysNumFromCallNum($callNum) {
	$sysNum = singleRecGetSysNum($callNum, "CNL");
	return($sysNum);
}

// Return MARCXML for a given non-LC call number
function singleRecGetMARCfromCallNum($callNum) {
	$marcXML = alephSingleRec($callNum, "CNL");
	return ($marcXML);
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

/* *** Functions For preparing OCLC numbers for Aleph X-sevices *** */

// OCLC numbers have to be 8 digits for sending to Aleph. If less than 8, add zeroes.
function OCLCpadNum($oclc) {
	if (strlen($oclc) < 8) {
		if (strlen($oclc) == 6) {
			$oclcPad = "00".$oclc;			
		} elseif (strlen($oclc) == 7) {
			$oclcPad = "0".$oclc;			
		}		
	} else {
		$oclcPad = $oclc;
	}
	return($oclcPad);
}

// OCLC nums sent to aleph need to be preceded by "ocn" or "ocm". 
function OCLCtestPrefix($oclcPad) {
	$prefix = "ocm";		
	$oclcPre = $prefix.$oclcPad;	
	if ($test = alephTestForSetNum($oclcPre, "035")) {
		return($oclcPre);		
	} else {			
		$prefix = "ocn";		
		$oclcPre = $prefix.$oclcPad;		
		if ($test = alephTestForSetNum($oclcPre, "035")) {
			return($oclcPre);			
		} // end if
	} // end if
} // end function

// Prepares OCLC number for Aleph-X-services. Pads OCLC nums shorter than 8 digits, and adds proper prefix.
function OCLCforAlephX($oclc) {
	$oclcPad = OCLCpadNum($oclc);	
	$oclcForAlephX = OCLCtestPrefix($oclcPad);	
	return($oclcForAlephX);
}

/* *** Functions for retrieving information from OCLC numbers *** */

function singleRecGetSysNumFromOCLC($oclc) {		
	$code = "035";
	$oclcForAlephX = OCLCforAlephX($oclc);
	$sysNum = singleRecGetSysNum($oclcForAlephX, $code);
	return ($sysNum);
} // end function	

function singleRecGetMARCfromOCLC($oclc) {		
	$code = "035";
	$oclcForAlephX = OCLCforAlephX($oclc);
	$marcXML = alephSingleRec($oclcForAlephX, $code);
	return($marcXML);	
} // end function

// Tests

$oclcNums = array ("34919814", "428436794", "2648489");

/*
$oclc = $oclcNums[2];
$marcXML = singleRecGetMARCfromOCLC($oclc);
print_r ($marcXML);
*/

/*
foreach ($oclcNums as $oclcNum) {
	$sysNum = singleRecGetSysNumFromOCLC($oclcNum);
	echo "OCLC: ".$oclcNum;
	echo " Sys Num: ".$sysNum;	
	echo "</br>";	
}
*/

/*
foreach ($oclcNums as $oclcNum) {
	$oclcforaleph = OCLCforAlephX($oclcNum);
	echo "OCLC: ".$oclcNum;
	echo " OCLC for Aleph: ".$oclcforaleph;
	echo "</br>";	
}
*/


/*
print_r(singleRecGetMARCfromCallNum("MCD10000"));
*/

/*
$sysNum = singleRecGetSysNumFromCallNum("MCD10000");
echo $sysNum;
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

?>