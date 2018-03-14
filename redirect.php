<?php
require_once "classes/shortener.php";
if(isset($_GET['code'])){
	$code = $_GET['code'];
	$shorten = new shortener();
	if($url = $shorten->getURL($code)){
		header("location:" . $url);
		die();
	}
	header("location: index.php");
}
