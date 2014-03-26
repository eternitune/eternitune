<?php

session_start();
if ($_SESSION['token_id'] !== $_POST['token'])
	echo "unauthorized";

$img = $_POST['img'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$filename = "images/" . md5(uniqid(substr($img, 0, 1000) . rand(), true)) . ".png";
file_put_contents( $filename, base64_decode($img));
echo "http://eternitune.com/" . $filename;


?>