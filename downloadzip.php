<html>
<head></head>
<body>
<?php

$arrayUrl = array('https://dumps.wikimedia.org/jawiki/20181201/jawiki-20181201-abstract2.xml.gz','https://dumps.wikimedia.org/jawiki/20181201/jawiki-20181201-abstract3.xml.gz','https://dumps.wikimedia.org/jawiki/20181201/jawiki-20181201-abstract6.xml.gz','https://dumps.wikimedia.org/jawiki/20181201/jawiki-20181201-templatelinks.sql.gz','https://dumps.wikimedia.org/jawiki/20181201/jawiki-20181201-stub-meta-history6.xml.gz');

$filesize = array(34.8,31.3,25.9,114.8,699.7);
$arrayFile = array();


//Call function
$arrayFile = get_all_download($arrayUrl,$filesize,$arrayFile);

//ZipAndDownloadMultipleFile($arrayFile);

print_r($arrayFile);

//Functions---------------------------------------------------------------------------------------------------
/**function create_file_name($handle, $url){
	preg_match('/$handle=(.*)\?/', $url,$match);
	foreach($match as $matchS){
	echo $matchS;
	}
}**/

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

function get_download_content($url, $number, $size1){
	echo $url."\n";
	$output_filename = "file-".$number.".bz2";
	echo $output_filename."\n";
	
	
	
	set_time_limit(0);
	//Get the file from server
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	//curl_setopt($ch, CURLOPT_RANGE, '0-4000');
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	//curl_setopt($ch, CURLOPT_REFERER, "https://dumps.wikimedia.org/scwiki/20181201/scwiki-20181201-pages-logging.xml.gz");
	//curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 300);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt($ch, CURLOPT_NOPROGRESS,false);
	
	$memoryuse_inMB = memory_get_usage()/1024/1024;
	echo $memoryuse_inMB;
	
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


	// the following lines write the contents to a file in the same directory (provided permissions etc)
	
	
	
	
	file_put_contents($output_filename, $result); 
	
	ini_set('memory_limit', '128M');
	
	return($output_filename);
}

/**function formArray($file, $arrayFile){
	
	array_push($arrayFile, $file);
	
}**/
	
	/**header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($output_filename));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($output_filename)); **/
	
	//download file
    //readfile($output_filename); 
    //exit;
	
	//return ($output_filename);
//}


	$zipname = "file.zip";
	$zip = new ZipArchive();
	$zip->open($zipname, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
	if($zip !== TRUE){
		echo "failed\n";
	}
	foreach ($arrayFile as $file) {
			$zip->addFile($file);
	}
	
	$zip->close();
	
	
	
	/**header('Content-Type: application/zip');
	header('Content-disposition: attachment; filename='.$zipname);
	header('Content-Length: ' . filesize($zipname));
	readfile($zipname);**/
	

/*?>
<?php */
//unlink($zipname);
?>

<a href="file.zip" download>Download link here</a>

</body>
</html>