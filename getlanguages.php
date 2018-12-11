 <?php

    $source = $_POST[source];

    $curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL,'https://dumps.wikimedia.org/backup-index.html');
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
    $query = curl_exec($curl_handle);
    curl_close($curl_handle);
	//$query =  htmlentities($query);
	// echo $query;

	$langarray[] = [];
	$count = 0;
	$dom = new DOMDocument;
	$dom->loadHTML($query);
	$links = $dom->getElementsByTagName('a');
	foreach ($links as $link){
		if(strstr($link->getAttribute('href'), $source)){
	   // echo $link->nodeValue.": : : :";
	   // echo $link->getAttribute('href'), '<br><br>';
		$matchstr = '/.+?(?='.$source.')/';
		preg_match($matchstr, $link->getAttribute('href'), $output);
		$lang = implode("", $output);
	   // echo $lang;
	  //  echo "<br/>";
		$langarray[$count++]= $lang;
		//echo"<option value=".$lang.">".$lang."</option>";
	}
	}
	//echo $langarray[10];
	//echo "done";

/*Get Language & Code from meta wikimedia*/
	//echo '<br/>';
	
	$langArray[] = [];
	$codeArray[] = [];
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
			
			if(strpos($code[0], '-'))
				$code[0] = str_replace("-","_",$code[0]);
			
			$codeArray[$count2D] = $code[0];
			
			//echo $language."<br/>";
			//echo($keywords[0]);
			//echo '<br>';
			//echo($keywords[1]);
			//echo '<br>';
			//echo '<br>';
			
			$langArray[$count2D] = array($code[0], $language);
			//echo $langArray[$count2D][0]." ".$langArray[$count2D][1]."<br/>";
			$count2D++;
		}
	}
	
/*Find intersect Language*/

	$results=array_intersect($codeArray,$langarray);
	//print_r($results);
	
	foreach($results as $result){
		for($i=0; $i<$count2D; $i++){
			if($result == $langArray[$i][0]){			
				echo"<option value=".$langArray[$i][0].">".$langArray[$i][1]."</option>";
			}
		}
	}

?>