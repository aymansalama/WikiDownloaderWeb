<!DOCTYPE html>

<body>
<?php
//$url = 'https://dumps.wikimedia.org/enwiki/latest/' ;
$url = $_POST['urlBox2'];
$stack = array();
if(isset($_POST['submit'])){
	if(!empty($_POST['match_values'])){
		foreach($_POST['match_values']as $selected){
			//echo ('<a href="'.$url.$selected.'">'.$selected.'</a><br>');
			array_push($stack, $url.$selected);	
		}
		//$link = "'".implode("','",array_unique($stack))."'";
		//$stack = array($link);
		//$arr = explode(",",$link);
		//print_r($stack);
		//print_r($link);
		//print_r($arr);
	}
}

$zip = new ZipArchive();
$tmp_file = tempnam('.','');
$zip->open($tmp_file, ZipArchive::CREATE);

foreach($stack as $file){
	$download_file = file_get_contents($file);
	$zip->addFromString(basename($file),$download_file);
	}
    $zip->close();
header('Content-disposition: attachment; filename = "my file.zip"');
header('Content-type: application/zip');
header("Content-Length: " . filesize($yourfile));
readfile($tmp_file);
unlink($tmp_file);
?>
</body>
</html>
