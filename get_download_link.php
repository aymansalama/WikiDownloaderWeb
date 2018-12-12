<!DOCTYPE html>
<html>
	<style>
		
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
		#downloadbutton {
			  display: inline-block;
			  padding: 10px 20px;
			  font-size: 24px;
			  cursor: pointer;
			  text-align: center;
			  text-decoration: none;
			  outline: none;
			  color: #fff;
			  background-color: #4da6ff;
			  border: none;
			  border-radius: 15px;
			  box-shadow: 0 9px #999;
		}

		#downloadbutton:hover {background-color: #0066cc}

		#downloadbutton:active {
			  background-color: #0066cc;
			  box-shadow: 0 5px #666;
			  transform: translateY(4px);
		}
	</style>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script>
		
			
			//unable for submission when no input selection
			$(document).ready(function() {
				var items = $(".itemChk");

				items.click(function() {
					if ($(this).is(":checked")) {
						$("#downloadbutton").removeAttr("disabled");
					} else {
						$("#downloadbutton").attr("disabled", "disabled");
					}
					
				});
			}); 
			
			//uncheck all previous selection for checkbox values when submission done
			function uncheckAll() {
			$("input[type='checkbox']:checked").prop("checked", false)
				}
			$(':submit').on('click', uncheckAll)
			
		</script>
	</head>

<body>
	<?php

			//get appended URL from user selection
			$url= $_POST['finalUrl'];
			//$url = 'https://dumps.wikimedia.org/nowiki/20181020/'; 
			
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
			$finder = new DomXPath($dom);


			$classname="file";
			//scan HTML file with <a> tag to get the link
			$link_tags = $dom->getElementsByTagName('a');

			//scan HTMl file with <li="class"> tag to get file size
			$filesize_tags = $finder->query("//*[contains(@class, '$classname')]");


			echo('<b style="font-size: 50px">List Of Files</b></br></br>');
			echo('<input type="checkbox" style="margin-left:5px" onClick="toggle(this)" /> Select All<br/>');

			//retrieve relevant link	
			foreach ($link_tags as $tag) {
				$newdoc = new DOMDocument();
				$cloned = $tag->cloneNode(TRUE);
				$newdoc->appendChild($newdoc->importNode($cloned,TRUE));
				
				//pattern for matching with file type
				$re = '/[a-z-_0-9]*(?:\.xml\.7z-rss\.xml|\.txt|\.xml\.gz-rss\.xml|\.xml\.gz|\.xml\.bz2|\.xml\.7z|\.gz|\.txt\.bz2|\.json\.gz|\.sql\.gz|\.bz2-rss\.xml|\.sql\.gz-rss\.xml|\.gz-rss\.xml|\.xml-[a-z0-9]+\.bz2|\.xml[-a-z0-9]*\.bz2-rss\.xml|\.txt\.bz2-rss\.xml|\.json\.gz-rss\.xml|\.xml-[a-z0-9]+\.7z|\.xml[-a-z0-9]*\.7z-rss\.xml)(?=")/';
				
				preg_match($re, $newdoc->saveHTML(), $matches, PREG_OFFSET_CAPTURE, 0);
					
				if(!empty($matches)){
				
					foreach ($matches as $match) {
					
					
					echo('<form action="pass_link_to_zip.php" method="post"><input type="checkbox" id="box" class="itemChk" name="match_values_1[]" value="'.$match[0].'">'.$match[0].'<br>');
					
					}
					
				}
			}

			//retrieve relevant filesize corresponding to each files
			foreach ($filesize_tags as $tag_2) {
				$newdoc = new DOMDocument();
				$cloned = $tag_2->cloneNode(TRUE);
				$newdoc->appendChild($newdoc->importNode($cloned,TRUE));
				
				//pattern for matching with file size
				$re_2 = '/(?:\d+|\d*\.\d+)\s+(?:GB|MB|KB|bytes)/';
				
				preg_match($re_2, $newdoc->saveHTML(), $matches_2, PREG_OFFSET_CAPTURE, 0);
				

				if(!empty($matches_2)){
					foreach ($matches_2 as $match_2){
						
						echo('<input type="hidden" name="match_values_2[]" value="'.$match_2[0].'">');
						
					}
				}
				
			}

			echo('<input type="hidden" name="url" value="'.$url.'">');
			echo('<input type="submit" id="downloadbutton" name="submit" value="Download" disabled="disabled"/></form><br>');

	?>
</body>
</html>


