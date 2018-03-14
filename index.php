<?php
session_start();
require_once "classes/shortener.php";
if(isset($_POST['url'])){
	$url = $_POST['url'];
	$shorten = new Shortener();
	if($code = $shorten->makeURL($url)){
		$fullPath = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']  . $code;
		$fullPath = str_replace("/index.php", "", $fullPath);
		$_SESSION['feedback'] = "Shorten Done! Your new URL is : <a href='{$code}'>" . $fullPath . "</a>";
	} else{
		$_SESSION['feedback'] = "Enter A valid URL";
	}
	
}
// header("location: index.php");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset=utf-8>
	<meta name=description content="">
	<meta name=viewport content="width=device-width, initial-scale=1">
	<title>URL Shortener</title>
	<link rel="stylesheet" href="css/main.css">
</head>
<body>
	<div class="container">
		<h1>url shortener</h1>
		<p>
		<?php 
		if(isset($_SESSION['feedback'])){
			echo $_SESSION['feedback'];
			unset($_SESSION['feedback']);
		}
		?>
		</p>
		<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
			<input type="url" name="url" placeholder="Enter URL">
			<input type="submit" name="submit" value="Shorten">
		</form>
	</div>

</body>
</html>