<!DOCTYPE html>
<html>
<style>
#urlBox {
    width:500px;
}
</style>
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
		
		var SourceUrl = "https://dumps.wikimedia.org/";
		var queryUrl = "";
		var landingUrl = "";
		var source = "";
		var langsource = "";
		$(function() {
			
			 initialUrl();
			 
			 $('#languages').on('change', function(){
				if($(this).val() == 0){
					queryUrl = "";
				}else{
					queryUrl = $(this).val();
					source = $("#sources").val();
					langsource = queryUrl + source;
				}
				MakeUrl();
				return false;
			 });
			 
			 $('#dates').on('change', function(){
				if($(this).val() == 0){
					landingUrl = "";
				}else{
					landingUrl = $(this).val();
				}
				MakeUrl();
				return false;
			 });
		});
		
		function initialUrl(){
			var link = SourceUrl;
			$('#urlBox').val(link);
			$('#MyLink').attr('href', link);
		}
		
		function MakeUrl(){
			var finalUrl = SourceUrl + langsource + "/" + landingUrl + "/";
			$('#urlBox').val(finalUrl);
			$('#MyLink').attr('href', finalUrl);
		}
		
		
		 /*$('#form').on('submit', function (e) {
        e.preventDefault();
        var $form = $(this),
                $select = $form.find('select'),
                links = $select.val();
        if (links.length > 0) {
            for (i in links) {
                link = links[i];
				var source = $("#sources").val();
				var langsource = language + link;
                window.open('https://dumps.wikimedia.org/' + langsource);
            }
        }
		});*/
		
		
		/*$(function(){
			$( "#form" ).submit(function( event ){
				event.preventDefault();
				
				$("[name=languages]").change(function(){
					window.location.href = period(this) + type($("[name=dates]"));
					return true;
				});
				$("[name=dates]").change(function(){
					window.location.href = period($("[name=languages]")) + type(this);
					return true;
				});
				
				function period(element){
					var a = $(element).val();
					var temp;
					if(typeof a !== 'undefined'){
						temp = a;
					}else{
						temp = '';
					}
					var source = $("#sources").val();
					var langsource = temp + source;
					return 'https://dumps.wikimedia.org/' + langsource;
				}
				
				function type(element){
					var b = $(element).val();
					var temp;
					if(typeof b !== 'undefined'){
						temp = b;
					}else{
						temp = '';
					}
					return '/' + temp;
				}
			});
		});*/
	</script>

</head>
	<body>

		<form id="form" action="get_download_link.php" method="post">
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
			<input type="text" value="" id="urlBox" name="urlBox" readonly /> 
			<a id="MyLink" target="_blank" href="">Go</a>
			<br/><br/>
			
			<input type="submit" id="submit" name="submit" value="submit">
			
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