<!DOCTYPE html>
<html>
<body>
<?php

$url = $_POST['url'];
$sizeArray = array(0,0);
$selectedArray = array();
$fileArray = array();


if(isset($_POST['submit'])){
	if(!empty($_POST['match_values_2'])){
		foreach($_POST['match_values_2'] as $filearray){
		
			$single = explode(",",$filearray);
			foreach($single as $item){
				$array[] = explode(" ",$item);
			}
		}
		//print_r($array);
		
		foreach ($array as $arr){
			if($arr[1] === 'GB'){
				$size = (int)$arr[0]*1024;
				//print_r($size);
			}
			if($arr[1] === 'KB'){
				$size = (int)$arr[0]/1024;
			}
			if($arr[1] === 'Bytes'){
				$size = (int)$arr[0]/1024/1024;
			}
			if($arr[1] === 'MB'){
				$size = (int)$arr[0];
			}
			array_push($sizeArray,$size);
		}
	}

	
	if(!empty($_POST['match_values'])){
		for($i = 0; $i < count($_POST['match_values']); $i++){
			$selected = $_POST['match_values'][$i];
			$URL = $url.$selected;
			$file = get_download_file($URL, $selected, $sizeArray[$i]);
			array_push($selectedArray, $selected);
			array_push($fileArray, $file);
		}
	}
}

 
//Zip files
$t = time();
$zipname = $url.".zip";
$tmp_file = tempnam(sys_get_temp_dir(),"zip");

$zip = new ZipArchive();
$zip->open($tmp_file, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
foreach ($fileArray as $file) {
		$zip->addFile($file);
}
$zip->close();


header('Content-Type: application/zip');
header('Content-Length: ' . filesize($tmp_file));
header('Content-Disposition: attachment; filename=\''.$zipname.'\'');
ob_clean();
flush();
readfile($tmp_file);

ignore_user_abort(true);
if (connection_aborted()) {
	unlink($tmp_file);
	foreach($selectedArray as $selected_file){
		unlink($selected_file);
	}
}



/*-------------------------FUNCTIOINS--------------------------*/

function get_download_file($url, $file_name, $file_size){
	//echo $url."\n";
	$output_filename = $file_name;
	//echo $output_filename."\n";
	
	set_time_limit(0);
	
	//Get the file from remote server
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
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
	
	
	//bytes to Mb
	$memory_use_inMB = memory_get_usage()/1024/1024;

	
	$new_size = 128 + $file_size;


	ini_set('memory_limit',strval($new_size).'M');
	
	$memory = ini_get('memory_limit');
	//echo $memory."\n";
	
	$result = curl_exec($ch);
	
	if(!$result){
		//echo 'error:' . curl_error($ch)."\n";
	 }
	curl_close($ch);

	 //print_r($result); // prints the contents of the collected file before writing..
	
	file_put_contents($output_filename, $result); 
	
	ini_set('memory_limit', '128M');
	
	return($output_filename);
}

?>
</body>
</html>
