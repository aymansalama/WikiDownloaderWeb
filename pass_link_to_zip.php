<!DOCTYPE html>
<html>
<head>

</head>
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
		//convert size unit into Megabytes
		foreach ($array as $arr){
			if($arr[1] === 'GB'){
				$size = (int)$arr[0]*1024;
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
	
	//Download multiple files
	
	if(!empty($_POST['match_values_1'])){
		//print_r($_POST['match_values_1']);
		for($i = 0; $i < count($_POST['match_values_1']); $i++){
			$selected = $_POST['match_values_1'][$i];
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

//Delete temporary file
ignore_user_abort(true);
if (connection_aborted()) {
	unlink($tmp_file);
}



/*-------------------------FUNCTIOINS--------------------------*/

function get_download_file($url, $file_name, $file_size){
	$output_filename = $file_name;
	
	set_time_limit(0);
	
	//Get the file from remote server
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 300);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt($ch, CURLOPT_NOPROGRESS,false);
	
	//Set memory limit allowed
	$memory = ini_get('memory_limit');
	
	
	//bytes to Mb
	$memory_use_inMB = memory_get_usage()/1024/1024;
	$new_size = 128 + $file_size + $memory_use_inMB;
	
	ini_set('memory_limit', -1);
	
	$result = curl_exec($ch);
	curl_close($ch);
	file_put_contents($output_filename, $result); 
	
	ini_set('memory_limit', $memory);
	
	return($output_filename);
}

?>
</body>
</html>
