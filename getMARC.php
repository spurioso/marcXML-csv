<?php

include "alephXfunctions.php";

/* Store the Aleph system number in a variable. Works also without the initial zeros.
 * I'm treating the number as a string in this example. 
 * Need to remove the initial zeroes otherwise.  */
$alephNumbers = array ("001450414", "003778295"); // sample aleph numbers for testing  
$alephNum = $alephNumbers[1];
$code = "SYS"; 
$setNum = alephFind($alephNum, $code); // calls the alephFind function in alephXfunctions.php
$alephMarcXML = alephPresent($setNum); // calls the alephPresent function in alephXfunctions.php

print_r($alephMarcXML); // for testing
   
?>