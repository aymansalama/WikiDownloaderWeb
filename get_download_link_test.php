<?php
$url="https://dumps.wikimedia.org/viwiki/latest/";
$counter = -1;
$data=file_get_contents($url);
$data = strip_tags($data,"<a>");
$d = preg_split("/<\/a>/",$data);
foreach ( $d as $k=>$u ){
    if( strpos($u, "<a href=") !== FALSE ){
		$counter++;
        $u = preg_replace("/.*<a\s+href=\"/sm","",$u);
        $u = preg_replace("/\".*/","",$u);
        echo ($u."\n");
		
    }
	echo "<br/>";
}

echo $counter;
?>