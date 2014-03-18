<?php
require_once('FirePHPCore/fb.php');
session_start();
    if($_GET["arrayType"] == 'json'){
    	$_SESSION['type'] = 'json';
    }else{
    	$_SESSION['type'] = 'array';
    }
    header('Location: http://charlesericktremblay.com/xls/');
?>