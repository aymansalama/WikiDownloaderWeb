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

$dom = new DOMDocument;
$dom->loadHTML($query);
$links = $dom->getElementsByTagName('a');
foreach ($links as $link){
    echo $link->nodeValue.": : : :";
    echo $link->getAttribute('href'), '<br>';
}
echo "done";
?> 

</body>
</html>