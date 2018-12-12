<!DOCTYPE html>
<html>
	<style>
		#urlBox2 {
			width:500px;
		}
		input[type=checkbox]
		{
		  /* Double-sized Checkboxes */
		  -ms-transform: scale(2); /* IE */
		  -moz-transform: scale(2); /* FF */
		  -webkit-transform: scale(2); /* Safari and Chrome */
		  -o-transform: scale(2); /* Opera */
		  padding: 50px;
		  margin: 15px;
		}
		input[type=submit]
		{
		  margin: 50px;
		}
	</style>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script>	
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

		<?php

			$url= $_POST['finalUrl'];

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
			$tags = $dom->getElementsByTagName('a');

			$counter = 0;
			
			echo('<b style="font-size: 50px">List Of Files</b></br></br>');
			echo('<input type="checkbox" style="margin-left:5px" onClick="toggle(this)" /> Select All<br/>');

			//Checker for domElement
			foreach ($tags as $tag) {
				
				$newdoc = new DOMDocument();
				$cloned = $tag->cloneNode(TRUE);
				$newdoc->appendChild($newdoc->importNode($cloned,TRUE));
				
				$re = '/[a-z-_0-9]*(?:\.xml\.7z-rss\.xml|\.txt|\.xml\.gz-rss\.xml|\.xml\.gz|\.xml\.bz2|\.xml\.7z|\.gz|\.txt\.bz2|\.json\.gz|\.sql\.gz|\.bz2-rss\.xml|\.sql\.gz-rss\.xml|\.gz-rss\.xml|\.xml-[a-z0-9]+\.bz2|\.xml[-a-z0-9]*\.bz2-rss\.xml|\.txt\.bz2-rss\.xml|\.json\.gz-rss\.xml|\.xml-[a-z0-9]+\.7z|\.xml[-a-z0-9]*\.7z-rss\.xml)(?=")/';
				
				preg_match($re, $newdoc->saveHTML(), $matches, PREG_OFFSET_CAPTURE, 0);
			
				if(empty($matches)){}
				else{
					
					foreach ($matches as $match) {
					
						echo('<form action="pass_link_to_zip.php" method="post"><input id= "checked" type="checkbox" name="link" value="'.$match[0].'"> '.$match[0].'<br>');
					
					}
				
					$counter++;
				}
			}
			
			if(isset($_POST['submit'])){
				$url= $_POST['urlBox'];
				echo ('<input type="text" value="'.$url.'" id="urlBox2" name="urlBox2" readonly />');
			}

			echo('<input type="submit" name="Download" value="Download" /></form>');

		?>

	</body>
</html>


