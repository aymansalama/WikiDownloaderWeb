<!DOCTYPE html>
<html>
<style>
#urlBox2 {
    width:500px;
}
</style>
<head>
</head>
<body>
<?php

session_start();
$url = 'https://dumps.wikimedia.org/nowiki/20181201/';
echo $url;


$curl_handle=curl_init();
curl_setopt($curl_handle, CURLOPT_URL,$url);
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0');
curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
$query = curl_exec($curl_handle);
	
$dom = new DOMDocument();
$dom->loadHTML($query);
$finder = new DomXPath($dom);

$classname="file";
$tags = $dom->getElementsByTagName('a');
$tags_2 = $finder->query("//*[contains(@class, '$classname')]");

$counter = 0;
$counter_2 = 0;

	
foreach ($tags as $tag) {
	$newdoc = new DOMDocument();
    $cloned = $tag->cloneNode(TRUE);
    $newdoc->appendChild($newdoc->importNode($cloned,TRUE));
	
	$re = '/[a-z-_0-9]*(?:\.txt|\.xml\.gz-rss\.xml|\.xml\.gz|\.xml\.bz2|\.xml\.7z|\.gz|\.txt\.bz2|\.json\.gz|\.sql\.gz|\.bz2-rss\.xml|\.sql\.gz-rss\.xml|\.gz-rss\.xml|\.xml-[a-z0-9]+\.bz2|\.xml[-a-z0-9]*\.bz2-rss\.xml|\.txt\.bz2-rss\.xml|\.json\.gz-rss\.xml)(?=")/';
	
	preg_match($re, $newdoc->saveHTML(), $matches, PREG_OFFSET_CAPTURE, 0);
		
	//var_dump($matches);
	if(!empty($matches)){
	
		
		foreach ($matches as $match) {
		
		echo('<form action="pass_link_to_zip_test.php" method="post"><input id= "checked" type="checkbox" name="match_values[]" value="'.$match[0].'">'.$match[0].'<br>');
		//echo ('<a href="'.$url.$match[0].'">'.$match[0].'</a><br>');
		}
		
		$counter++;
	}
	
}

foreach ($tags_2 as $tag_2) {
	$newdoc = new DOMDocument();
    $cloned = $tag_2->cloneNode(TRUE);
    $newdoc->appendChild($newdoc->importNode($cloned,TRUE));
	
	$re_2 = '/(?:\d+|\d*\.\d+)\s+(?:GB|MB|KB|bytes)/';
	preg_match($re_2, $newdoc->saveHTML(), $matches_2, PREG_OFFSET_CAPTURE, 0);
	
	//$a = array();
	if(!empty($matches_2)){
	foreach ($matches_2 as $match_2){
			//echo $match_2[0];
			//var_dump($match_2);
			
			echo('<input type="hidden" name="match_values_2[]" value="'.$match_2[0].'">');
		}
	}
	$counter_2++;
}


echo('<input type="hidden" name="url" value="'.$url.'">');
echo('<input type="submit" name="submit" value="Submit" /></form>');
echo $counter;
echo $counter_2;


?>
</body>
</html>


