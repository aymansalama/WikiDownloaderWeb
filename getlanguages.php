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
 //   $query =  htmlentities($query);
  //  echo $query;

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
    echo"<option value=".$lang.">".$lang."</option>";
}
}
//echo $langarray[10];
//echo "done";

?>