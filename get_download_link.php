<!DOCTYPE html>
<html>
<style>
#urlBox2 {
    width:500px;
}
</style>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>	
    /*$(".match_values").on("submit", function() {console.log('fzef');
        var values = [];
        $('.match_values:checked').each(function() {
            var result = $(this).val();
            values.push(result);
        });
        var link = $(".link").attr('href', 'https://dumps.wikimedia.org/enwiki/latest/' + values);
		console.log(link);
    });

    // Init link on page load
    $(".match_values").trigger("submit");
	
	function initialUrl(){
			var link = $(url).val();
			$('#urlBox').val(link);
		}
	

	if(document.getElementById('checked').checked){
	document.getElementById('unchecked').disabled = true;
	}*/
	
	$(document).ready(function(){
		var x = document.getElementById("check");
		if (x.style.display === "none") {
		x.style.display = "block";
		}
    $('.check:button').toggle(function(){
        $('input:checkbox').attr('checked','checked');
        $(this).val('uncheck all')
    },function(){
        $('input:checkbox').removeAttr('checked');
        $(this).val('check all');        
    })
})
	
	
</script>
</head>
<body>
<!--<input type="button" id="check" class="check" value="check all">-->
<?php
$fromQuery = '';
$url = '';
if(isset($_POST['submit'])){
	$url= $_POST['urlBox'];
	//<input type="text" value="" id="urlBox" name="urlBox" readonly />
	//echo ('<input type="text" value="'.$url.'" id="urlBox2" name="urlBox2" readonly />');
	echo $url;
		
}

//$url = 'https://dumps.wikimedia.org/'.'enwiki/latest/';
//echo $url;

$curl_handle=curl_init();
curl_setopt($curl_handle, CURLOPT_URL,$url);
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0');
curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
$query = curl_exec($curl_handle);
	
$dom = new DOMDocument();
$dom->loadHTML($query);
//echo ($dom->saveHTML());

$tags = $dom->getElementsByTagName('a');

$counter = 0;

	//$re = '/[a-z-_0-9]*(?:\.txt|\.xml\.gz-rss\.xml|\.xml\.gz|\.xml\.bz2|\.xml\.7z|\.gz|\.txt\.bz2|\.json\.gz|\.sql\.gz|\.bz2-rss\.xml|\.sql\.gz-rss\.xml|\.gz-rss\.xml|\.xml-[a-z0-9]+\.bz2|\.xml[-a-z0-9]*\.bz2-rss\.xml|\.txt\.bz2-rss\.xml|\.json\.gz-rss\.xml)(?=")/';
	//$str = '<a href="enwiki-latest-abstract14.xml.gz-rss.xml">enwiki-latest-abstract14.xml.gz-rss.xml</a>';

	//preg_match($re, $str, $matches, PREG_OFFSET_CAPTURE, 0);

	// Print the entire match result
	//var_dump($matches);

//Checker for domElement
foreach ($tags as $tag) {
	
	$newdoc = new DOMDocument();
    $cloned = $tag->cloneNode(TRUE);
    $newdoc->appendChild($newdoc->importNode($cloned,TRUE));
	
	$re = '/[a-z-_0-9]*(?:\.txt|\.xml\.gz-rss\.xml|\.xml\.gz|\.xml\.bz2|\.xml\.7z|\.gz|\.txt\.bz2|\.json\.gz|\.sql\.gz|\.bz2-rss\.xml|\.sql\.gz-rss\.xml|\.gz-rss\.xml|\.xml-[a-z0-9]+\.bz2|\.xml[-a-z0-9]*\.bz2-rss\.xml|\.txt\.bz2-rss\.xml|\.json\.gz-rss\.xml)(?=")/';
	
	preg_match($re, $newdoc->saveHTML(), $matches, PREG_OFFSET_CAPTURE, 0);
	
	
	//var_dump($matches);
	if(empty($matches)){}
	else{
		
		foreach ($matches as $match) {
		
		echo('<form action="pass_link_to_zip.php" method="post"><input id= "checked" type="checkbox" name="match_values[]" value="'.$match[0].'">'.$match[0].'<br>');
		
		}
		
		$counter++;
	}
	
	
	
	/*$aDoor = $_POST['match_values'];
	if(empty($aDoor)){
		 echo("You didn't select any files.");

	}else{
		$n = count($aDoor);
		echo("You selected $n file(s): ");
		for($i=0; $i<$n; $i++){
			echo($aDoor[$i]."");
		}
	}*/
	
	
	//echo ('<a href="'.$url.$match[0].'">'.$match[0].'</a>');
	/*
	$newdoc = new DOMDocument();
    $cloned = $tag->cloneNode(TRUE);
    $newdoc->appendChild($newdoc->importNode($cloned,TRUE));
    echo $newdoc->saveHTML();
	*/
	
	//echo "<br/>";
}
if(isset($_POST['submit'])){
	$url= $_POST['urlBox'];
	//<input type="text" value="" id="urlBox" name="urlBox" readonly />
	echo ('<input type="text" value="'.$url.'" id="urlBox2" name="urlBox2" readonly />');
	//echo $url;
}

echo('<input type="submit" name="submit" value="Submit" /></form>');
echo $counter;

//echo ('<a class="link" href="https://dumps.wikimedia.org/enwiki/latest/">Open here</a>');

?>
</body>
</html>


