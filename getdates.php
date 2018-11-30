<?php

	$langsource = $_POST[langsource];

	$datearray = array();
	$count = 0;

	$url = 'https://dumps.wikimedia.org/'.$langsource.'/';

	$curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $url);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
    $query = curl_exec($curl_handle);
    curl_close($curl_handle);

    $dom = new DOMDocument;
	$dom->loadHTML($query);
	$links = $dom->getElementsByTagName('a');
	
	foreach ($links as $link){
		$str = str_replace("/", "", $link->nodeValue);
		
		if(is_numeric($str) || $str == "latest"){
			echo "<option value=".$str.">".$str."</option>";
			$datearray[$count++] = $str;
		}
	}

?>