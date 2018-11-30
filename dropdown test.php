<!DOCTYPE html>
<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			$("#sources").change(function(){
				var source = $("#sources").val();
				console.log(source);
				$.ajax({
					type:"post",
					url:"getlanguages.php",
					data: "source=" + source,
					success: function(data){
						$("#languages").html(data);
					} 
				});
			});
		});

		$(document).ready(function(){
			$("#languages").change(function(){
				var language = $("#languages").val();
				var source = $("#sources").val();
				var langsource = language + source;
				console.log(langsource);
				$.ajax({
					type:"post",
					url:"getdates.php",
					data: "langsource=" + langsource,
					success: function(data){
						$("#dates").html(data);
					} 
				});
			});
		});
	</script>

</head>
	<body>

		<form action="/action_page.php">
			<select name="sources" id="sources">
				<option>-select source-</option>
				<option value="wiki">Wikipedia</option>
				<option value="wikimedia">Wikimedia</option>
			</select>
				
			<br><br>

			<select name="languages" id="languages">
				<option>-select language-</option>
			</select>
			
			<br><br>

			<select name="dates" id="dates">
				<option>-select date-</option>
			</select>
			
			<br><br>
			
			<input type="submit">
			
		</form>

<?php

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