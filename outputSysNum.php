<?php

require "alephXfunctions.php";

/* retrieve a list of barcodes from a text file and save them as an array */
function getBarcodes(){
	$barcodes = array();		
	$fp = fopen("barcodes.txt", 'r');
	while (!feof($fp)){
		$barcode = trim(fgets($fp));	
		array_push($barcodes, $barcode);
	} // end while
	fclose($fp);
	return($barcodes);	
} // end getBarcodes

/* retrieve Aleph system numbers corresponding to a list of barcodes and store them in an array */
function getAlephNums($barcodes){
	$alephNums = array();
	foreach ($barcodes as $barcode) {		
		$alephFindResults = alephFind($barcode, "bar");		
		$setNum = $alephFindResults->set_number;		
		$alephMarcXML = alephPresent($setNum);
		$alephNum = $alephMarcXML->record->doc_number;		
		array_push($alephNums, $alephNum);				
	} //end foreach
	return($alephNums);	
} // end getAlephNums

/* take an array of Aleph numbers and add each one to a text file */
function addSysNums($alephNums){
	if (file_exists("alephnums.txt")) {
		unlink("alephnums.txt");
	} // end if	
	foreach ($alephNums as $alephNum) {					
		$fp = fopen("alephnums.txt", 'a');
		fputs($fp, $alephNum."\n");
		fclose($fp);	
	}	// end foreach	
	return("File written");	
} // end addSysNums

function addCallNums($callNums){
	if (file_exists("callnums.txt")) {
		unlink("callnums.txt");
	} // end if	
	foreach ($callNums as $callNum) {					
		$fp = fopen("callnums.txt", 'a');
		fputs($fp, $callNum."\n");
		fclose($fp);	
	}	// end foreach	
	return("File written");	
} // end addSysNums

function generateCallNums($start, $end) {
	if ($end >= $start) {
		$callNums = array ();
		for ($i=$start; $i <= $end ; $i++) { 
			$callNum = "MCD{$i}";
			array_push($callNums, $callNum);
	} //end for
	return($callNums);	
	} else {
		echo "Second number must be greater than the first.";
		return(False);
	} // end if
	
} // end generateCallNums

if ($callNums = generateCallNums(20, 29)) {
	echo addCallNums($callNums);
}


 
 /*
$barcodes = getBarcodes();
$alephNums = getAlephNums($barcodes);
addSysNums($alephNums);
*/
?>