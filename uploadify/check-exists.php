<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
session_start();
// Define a destination
$targetFolder = __DIR__."/../upload"; // Relative to the root and should match the upload folder in the uploader script

$verifyToken = $_SESSION["token_upload"];
$targetFolder = __DIR__."/../uploads".'/'.$verifyToken;
	
//if (file_exists($_SERVER['DOCUMENT_ROOT'] . $targetFolder . '/' . $_POST['filename'])) {
if (file_exists($targetFolder.'/'. $_POST['filename'])) {	
	echo 1;
} else {
	echo 0;
}
?>