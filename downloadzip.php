<html>
<head></head>
<body>
<?php

$arrayUrl = array('https://dumps.wikimedia.org/jawiki/20181201/jawiki-20181201-abstract2.xml.gz',
'https://dumps.wikimedia.org/jawiki/20181201/jawiki-20181201-abstract3.xml.gz',
'https://dumps.wikimedia.org/jawiki/20181201/jawiki-20181201-abstract6.xml.gz',
'https://dumps.wikimedia.org/jawiki/20181201/jawiki-20181201-templatelinks.sql.gz');  
//'https://dumps.wikimedia.org/jawiki/20181201/jawiki-20181201-stub-meta-history6.xml.gz');

//filesize array is correspnding to the arrayUrl array
$filesize = array(34.8,31.3,25.9,114.8); //,699.7
$arrayFile = array();

//Call function
$arrayFile = get_all_download($arrayUrl,$filesize,$arrayFile);

print_r($arrayFile);

//Zip file
$zipname = "file.zip";
	$zip = new ZipArchive();
	$zip->open($zipname, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
	
	foreach ($arrayFile as $file) {
			$zip->addFile($file);
	}
	
	$zip->close();

/*------------------------------------Functions-----------------------------------------*/

//Download all files
function get_all_download($array,$file_SIZE, $arrayFile1){
	for($i=0; $i<count($array) ; $i++){
		$url2 = $array[$i];
		$numBer = $i;
		$size = $file_SIZE[$i];
		echo " get_all_download ";
		$file = get_download_content($url2, $numBer, $size);
		echo " get file = ";
		print_r($file);
		echo "\n";
		array_push($arrayFile1, $file);
		
	}
	echo " array file= ";
	return($arrayFile1);
}

//Download one file
function get_download_content($url, $number, $size1){
	echo $url."\n";
	$output_filename = "file-".$number.".bz2";
	echo $output_filename."\n";
	
	
	set_time_limit(0);
	
	//Get the file from server
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	//curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 300);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt($ch, CURLOPT_NOPROGRESS,false);
	
	$new_size = 128 + $size1; 
	ini_set('memory_limit',strval($new_size).'M');
	$memory = ini_get('memory_limit');
	echo $memory."\n";
	
	$result = curl_exec($ch);
	
	if(!$result){
		echo 'error:' . curl_error($ch)."\n";
	 }
	curl_close($ch);

	 
	//print_r($result); // prints the contents of the collected file before writing..


	// Write the contents to a file in the same directory
	file_put_contents($output_filename, $result); 
	
	ini_set('memory_limit', '128M');
	
	return($output_filename);
}
	

?>

<a href="file.zip" download>Download link here</a>

</body>
</html>