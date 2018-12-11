<?php

	$langsource = $_POST[langsource];	//source and language selected by user

	$url = 'https://dumps.wikimedia.org/'.$langsource.'/';	//append the source and language code e.g. 'enwiki' into the url

	$curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $url);	//get html source from the url
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
    $query = curl_exec($curl_handle);
    curl_close($curl_handle);

    //look for elements with tag <a> containing links in the html
    $dom = new DOMDocument;
	$dom->loadHTML($query);
	$links = $dom->getElementsByTagName('a');
	
	echo "<option value='null' selected disabled>-select a date-</option>";
	
	//for eack link found in the html
	foreach ($links as $link){
		//extract dates from the link
		$str = str_replace("/", "", $link->nodeValue);
		
		//check whether date contains only digits or is 'latest'
		if(is_numeric($str) || $str == "latest"){
			//display option in the dropdown
			echo "<option value=".$str.">".$str."</option>";
		}
	}

?>