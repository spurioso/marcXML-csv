<?php
$worldcatList = "https://umaryland.worldcat.org/profiles/shenrywcl/lists/639480/rss"; //put your list URL here.

$rssxml = getOCLClist($worldcatList);
$books = getBooks($rssxml);
$oclcNums = getOCLCnums($books);
//print_r($oclcNums);    
    
    function getOCLClist($worldcatList) {
    	$rss = file_get_contents ($worldcatList); //load the list contents	
		$rssxml = new SimpleXMLElement($rss); // and convert it to the SimpleXML Element
		return($rssxml);
    } // end getOCLClist
    
	function getChannel($rssxml) {
		$channel = array (); //create an array to store the channel information
		$channel['title'] = (string) trim($xml->channel->title);
		$channel['generator'] = $xml->channel->generator;
		$channel['link'] = $xml->channel->link;
		$channel['description'] = (string) trim($xml->channel->description);
		$channel['ttl'] = $xml->channel->ttl;
		$channel['language'] = $xml->channel->language;
		$channel['category'] = $xml->channel->category;
		return($channel);	
	} // end getChannel
	
	function getBooks($rssxml) {
		$books = array(); //create an array to store the item information. I'm calling it "books" but it could be any Worldcat item	
		foreach ($rssxml->channel->item as $item) { //create an array for each item in the list
			$book = array();
			$book['title'] = (string) trim($item->title);
			$book['link'] = $item->link;
			$book['guid'][0] = $item->guid[0];
			$book['guid'][1] = $item->guid[1];
			$book['oclcNumber'] = str_replace("http://umaryland.worldcat.org/oclc/", "", $item->guid[1]); //create an oclc Element based on the GUID
			$book['description'] = (string) trim($item->description);
			array_push($books, $book); // add the book array to the books array		
		} // end foreach
		return($books);
	} // end getBooks
	
	function printBooks($books) {
		foreach ($books as $item) { // test to see if it can retrieve item information I want.
			echo "Title: <a href='$item[link]'> $item[title] </a>"."<br />";
			echo "OCLC number: $item[oclcNumber]"."<br />";
		} // end foreach
	echo "Third book in the list: ". $books[2]["title"];	
	} // end printBooks
	
	function getOCLCnums($books) {
		$oclcNums = array();			
		foreach ($books as $item) {						
			$oclcNum = $item['oclcNumber'];			
			array_push($oclcNums, $oclcNum);			
		} // end foreach		
		return($oclcNums);
	} // end getOCLCnums	
	
?>