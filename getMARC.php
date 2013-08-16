<?php

require "alephXfunctions.php";

/* Store the Aleph system number in a variable. Works also without the initial zeros.
 * I'm treating the number as a string in this example. 
 * Need to remove the initial zeroes otherwise.  */
$alephNumbers = array ("12345678", "001450414", "003778295"); // sample aleph numbers for testing  
$alephNum = $alephNumbers[1];
$code = "SYS"; // code for X-services. "SYS" is for system number. change to "bar" for barcode, etc. 

$alephFindResults = alephFind($alephNum, $code); // calls the alephFind function in alephXfunctions.php

// test results for erros
if ($alephFindResults->error) {
	echo "An error occurred: ".$alephFindResults->error."</br>";	 
} else {
	$setNum = $alephFindResults->set_number;	
	$alephMarcXML = alephPresent($setNum); // calls the alephPresent function in alephXfunctions.php
	print_r($alephMarcXML); // this line for testing. Remove for production. 
}// end if
   
?>