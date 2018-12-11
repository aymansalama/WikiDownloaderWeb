<?php
//change the url here
$url = "https://dumps.wikimedia.org/enwiki/latest/";
$html = file_get_contents($url);
$counter=-1;
$doc = new DOMDocument();
$doc->loadHTML($html); //helps if html is well formed and has proper use of html entities!

$xpath = new DOMXpath($doc);

$nodes = $xpath->query('//a');

foreach($nodes as $node) {
	$counter++;
    echo($node->getAttribute('href'));
	echo "<br/>";
}
echo $counter;

?>
