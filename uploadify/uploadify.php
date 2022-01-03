<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
session_start();
include("../config.php");

$verifyToken = md5('unique_salt' . $_POST['timestamp']);
//$verifyToken = $_SESSION["uploadify_token"];

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	
	$targetFolder = $config["path_upload"].$verifyToken;

	//Traitement du chemin vers le dossier du fichier distant/////////////////
	//Remplacer les slashs
	$realpath 		= str_replace('\\','/',$targetFolder);
	//Remplacer les slashs dans la racine du site
	$real_path_root = str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']);
	//Eliminer la racine du site du chemin complet pour le traitement des repertpoire inexistant
	$real_path_without_root = str_replace($real_path_root,'',$realpath);
	
	
	//Fichier de destination/////////////////////////////////////////////////
	$targetFile = $targetFolder.'/'.$_FILES['Filedata']['name'];	
		

	$_SESSION['videofile'] = $_FILES['Filedata']['name'];
	//Create dirctories when they not exists
	$exploaded_path = explode("/", $real_path_without_root);
	$i = 0;
	$exist_path = false;

	$complete_path_test .= "";  
	while ( ( $i < count($exploaded_path) ) ){
			$complete_path_test .= $exploaded_path[$i]."/";                                                
			//Creation du repertoir s'il nexiste pas
			if( !is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$complete_path_test) ){                                                
					mkdir($_SERVER['DOCUMENT_ROOT'].'/'.$complete_path_test,0777);
			}
			$i++;
	}
	
	// Validate the file type
	$fileTypes = $config["allowed_video_extension"]; // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		echo '1';
	} else {
		echo 'Le type du fichier est invalid.';
	}
}
?>