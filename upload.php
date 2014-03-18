<?php
require_once('FirePHPCore/fb.php');
ob_start();
$filePath = 'xls/';


if($_SERVER['REQUEST_METHOD'] == "POST"){
	if(move_uploaded_file($_FILES['file']['tmp_name'], "xls/".$_FILES['file']['name'])){
		echo($_POST['index']);
	}
	$fileName = $filePath . $_FILES['file']['name'];
	$fileType = $_FILES["file"]["type"];
	session_start();
    $_SESSION['path'] = $fileName;
	exit;
}
?>