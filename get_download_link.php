<!DOCTYPE html>
<html>
<body>

<?php

$fromQuery = '';
$url = 'https://dumps.wikimedia.org/'.'enwiki/latest/';
echo $url;
echo "<br/>";

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
//echo ($dom->saveHTML());

$tags = $dom->getElementsByTagName('a');

$counter = 0;

	//$re = '/[a-z-_0-9]*(?:\.txt|\.xml\.gz-rss\.xml|\.xml\.gz|\.xml\.bz2|\.xml\.7z|\.gz|\.txt\.bz2|\.json\.gz|\.sql\.gz|\.bz2-rss\.xml|\.sql\.gz-rss\.xml|\.gz-rss\.xml|\.xml-[a-z0-9]+\.bz2|\.xml[-a-z0-9]*\.bz2-rss\.xml|\.txt\.bz2-rss\.xml|\.json\.gz-rss\.xml)(?=")/';
	//$str = '<a href="enwiki-latest-abstract14.xml.gz-rss.xml">enwiki-latest-abstract14.xml.gz-rss.xml</a>';

	//preg_match($re, $str, $matches, PREG_OFFSET_CAPTURE, 0);

	// Print the entire match result
	//var_dump($matches);

//Checker for domElement
foreach ($tags as $tag) {
	
	$newdoc = new DOMDocument();
    $cloned = $tag->cloneNode(TRUE);
    $newdoc->appendChild($newdoc->importNode($cloned,TRUE));
	
	$re = '/[a-z-_0-9]*(?:\.txt|\.xml\.gz-rss\.xml|\.xml\.gz|\.xml\.bz2|\.xml\.7z|\.gz|\.txt\.bz2|\.json\.gz|\.sql\.gz|\.bz2-rss\.xml|\.sql\.gz-rss\.xml|\.gz-rss\.xml|\.xml-[a-z0-9]+\.bz2|\.xml[-a-z0-9]*\.bz2-rss\.xml|\.txt\.bz2-rss\.xml|\.json\.gz-rss\.xml)(?=")/';
	
	preg_match($re, $newdoc->saveHTML(), $matches, PREG_OFFSET_CAPTURE, 0);
	
	//var_dump($matches);
	if(empty($matches)){}
	else{
		
		foreach ($matches as $match) {
		echo ('<a href="'.$url.$match[0].'">'.$match[0].'</a>');
		}
		$counter++;
	}
	
	/*
	$newdoc = new DOMDocument();
    $cloned = $tag->cloneNode(TRUE);
    $newdoc->appendChild($newdoc->importNode($cloned,TRUE));
    echo $newdoc->saveHTML();
	*/
	
	echo "<br/>";
}

echo $counter;
?>

</body>
</html>