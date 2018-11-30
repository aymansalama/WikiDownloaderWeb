<!DOCTYPE html>
<html>
	<body>

		<form action="/action_page.php">
			<select name="sources">
				<option value="wikipedia">Wikipedia</option>
				<option value="wikimedia">Wikimedia</option>
			</select>
				
			<br><br>

			<select name="cars">
				<option value="volvo">Volvo</option>
				<option value="saab">Saab</option>
				<option value="fiat">Fiat</option>
				<option value="audi">Audi</option>
			</select>
			
			<br><br>

			<select name="cars">
				<option value="volvo">Volvo</option>
				<option value="saab">Saab</option>
				<option value="fiat">Fiat</option>
				<option value="audi">Audi</option>
			</select>
			
			<br><br>
			
			<input type="submit">
			
		</form>

<?php
/*
    $curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL,'https://dumps.wikimedia.org/backup-index.html');
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
    $query = curl_exec($curl_handle);
    curl_close($curl_handle);
 //   $query =  htmlentities($query);
  //  echo $query;

$langarray[] = [];
$count = 0;
$dom = new DOMDocument;
$dom->loadHTML($query);
$links = $dom->getElementsByTagName('a');
foreach ($links as $link){
	if(strstr($link->getAttribute('href'),"wiki/")){
   // echo $link->nodeValue.": : : :";
   // echo $link->getAttribute('href'), '<br><br>';
    preg_match('/.+?(?=wiki)/', $link->getAttribute('href'), $output);
    $lang = implode("", $output);
    echo $lang;
    echo "<br/>";
    $langarray[$count++]= $lang;
}
}
//echo $langarray[10];
echo "done"; */

	$datearray = array();
	$count = 0;

	$curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL,'https://dumps.wikimedia.org/amwikiquote/');
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
			echo $str."<br/>";
			$datearray[$count++] = $str;
		}
	}

//echo $datearray[5];


/*Get Language & Code from meta wikimedia*/
	echo '<br/>';
	
	$langArray[] = [];
	$count2D = 0;
	
	$curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL,'https://meta.wikimedia.org/wiki/Template:List_of_language_names_ordered_by_code');
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
    $query = curl_exec($curl_handle);
    curl_close($curl_handle);
	
	$dom = new DOMDocument;
	$dom->loadHTML($query);
	$tags = $dom->getElementsByTagName('tr');
	
	function get_string_between($string, $start, $end){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}

	foreach ($tags as $tag) {
		
		if(preg_match("/^[a-z]/", $tag->nodeValue )){
			
			$code = preg_split("/[\s,]+/", $tag->nodeValue);
			
			if(strpos($tag->nodeValue, 'ltr'))
				$language = get_string_between($tag->nodeValue, $code[0], 'ltr');
			else
				$language = get_string_between($tag->nodeValue, $code[0], 'rtl');
			
			//echo $language."<br/>";
			//echo($keywords[0]);
			//echo '<br>';
			//echo($keywords[1]);
			//echo '<br>';
			//echo '<br>';
			
			$langArray[$count2D] = array($code[0], $language);
			echo $langArray[$count2D][0]." ".$langArray[$count2D][1]."<br/>";
	
			$count2D++;
		}
	}

?>

	</body>
</html>