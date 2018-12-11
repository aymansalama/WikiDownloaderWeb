 <?php

    $source = $_POST[source];	//source selected by user

    $curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL,'https://dumps.wikimedia.org/backup-index.html');		//get html source from url
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
    $query = curl_exec($curl_handle);
    curl_close($curl_handle);

	$langarray[] = [];
	$count = 0;

	//look for elements with tag <a> containing links in the html
	$dom = new DOMDocument;
	$dom->loadHTML($query);
	$links = $dom->getElementsByTagName('a');

	//for eack link found in the html
	foreach ($links as $link){
		//if the link contains an occurrence of the source selected by the user
		if(strstr($link->getAttribute('href'), $source)){
			//extract the language code from the link
			$matchstr = '/.+?(?='.$source.')/';
			preg_match($matchstr, $link->getAttribute('href'), $output);
			$lang = implode("", $output);
			//insert the language code found into an array
			$langarray[$count++]= $lang;
		}
	}
	

/*Get Language & Code from meta wikimedia*/
	
	$langArray[] = [];
	$codeArray[] = [];
	$count2D = 0;
	
	//get all html source from the link and store in $query
	$curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL,'https://meta.wikimedia.org/wiki/Template:List_of_language_names_ordered_by_code');		//get html source from url
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
    $query = curl_exec($curl_handle);
    curl_close($curl_handle);
	
	//look for elements with tag <tr> in the html
	$dom = new DOMDocument;
	$dom->loadHTML($query);
	$tags = $dom->getElementsByTagName('tr');
	
	//get the langauge between code and 'ltr'/'rtl'
	function get_string_between($string, $start, $end){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}

	//for each element in <tr> tag, get the language and code only
	foreach ($tags as $tag) {
		
		//getting the content in the table 
		if(preg_match("/^[a-z]/", $tag->nodeValue )){
			
			// split the phrase by any number of commas or space characters,
			// which include " ", \r, \t, \n and \f
			$code = preg_split("/[\s,]+/", $tag->nodeValue);
			
			//Find the position of the first occurrence of 'ltr' & 'rtl' and 
			//get the string between code and 'ltr'/'rtl' and save as language
			if(strpos($tag->nodeValue, 'ltr'))
				$language = get_string_between($tag->nodeValue, $code[0], 'ltr');
			else
				$language = get_string_between($tag->nodeValue, $code[0], 'rtl');
			
			//replace the '-' with '_' to match the code get from https://dumps.wikimedia.org/backup-index.html
			if(strpos($code[0], '-'))
				$code[0] = str_replace("-","_",$code[0]);
			
			//store language and code into array 
			$codeArray[$count2D] = $code[0];
			$langArray[$count2D] = array($code[0], $language);
			
			$count2D++;
		}
	}
	
/*Find intersect Language*/

	//find the code that matches from dump file with wiki available code
	$results=array_intersect($codeArray,$langarray);
	
	echo "<option value='null' selected disabled>-select a language-</option>";
	
	//show the language in the dropdown based on available code
	foreach($results as $result){
		for($i=0; $i<$count2D; $i++){
			if($result == $langArray[$i][0]){
				echo"<option value=".$langArray[$i][0].">".$langArray[$i][1]."</option>";
			}
		}
	}

?>