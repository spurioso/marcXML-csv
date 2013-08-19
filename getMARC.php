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
	//print_r($alephMarcXML); // this line for testing. Remove for production. 
}// end if

echo "Find: <a href=\"".buildFindURL($alephNum, $code)."\">".buildFindURL($alephNum, $code)."<a/>";
echo "</br>";
echo "Present: <a href=\"".buildPresentURL($setNum)."\">".buildPresentURL($setNum)."</a>";
echo "</br>";

$marcData = array("Main Title" => "","Alternative Title" => "","Translated Title" => "","Uniform Title" => "","Creator" => "","Contributor" => "","Statement of Responsibility" => "","Genre" => "","Publisher" => "","Place of Origin" => "","Date Created" => "","Date Issued" => "","Copyright Date" => "","Abstract" => "","Note" => "","Topical Subject" => "","Geographic Subject" => "","Temporal Subject" => "","Occupation Subject" => "","Person Subject" => "","Corporate Subject" => "","Family Subject" => "","Title Subject" => "","Collection" => "","Publish" => "","Hidden"
);
echo "</br>";
print_r($marcData);   
echo "</br>";

$author = $alephMarcXML->xpath("/present/record/metadata/oai_marc/varfield[@id='100']/subfield[@label='a']");
print_r($author);
echo "</br>";
echo $author[0];
$author = $author[0];
$author = "\"".$author."\"";
echo "</br>";
echo "Author with quotes: ".$author;
echo "</br>";
echo $alephNum;
echo "</br>";
$csvData = array($author, $alephNum);
print_r($csvData);

/*
$fp = fopen("sysNums.txt", w);
	if ($fp) {
		echo "So far so good </br>";
	}
	else {
		echo "Cannot open file.</br>";
	} //end if

fwrite($fp, $alephNum);
fclose($fp);

*/

$fp = fopen("csvtest.csv", w);
	if ($fp) {
		echo "So far SO good </br>";
	}
	else {
		echo "Cannot open file.</br>";
	} //end if

$putTest = fputcsv($fp, $csvData);
	if ($putTest) {
		echo "File put.";
	}
	else {
		echo "No dice on the putting";
	}


fclose($fp);

//$alephPresentXML->record->metadata->oai_marc->varfield
?>