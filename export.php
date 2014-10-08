<?php 
$url = $_GET['path'];
if (strpos($url,'xml') !== false) {
    $filename = "export.xml";
} else if (strpos($url,'csv') !== false) {
	$filename = "export.csv";
} else {
	$filename = "export.json";
}
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=" . $filename);
readfile($_GET['path']);