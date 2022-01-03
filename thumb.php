<?php
//+---------------------------------------------------------------------+
//|	thumb.php															|
//+---------------------------------------------------------------------+
//|	Generer une image miniature                     					|
//|	Developpement par :						|
//|		SAR Azzeddine: azzedinedev@gmail.com							|
//+---------------------------------------------------------------------+
session_start();

//echo $configvars['vignette-news-source'].$file;

//Inclusion de tous les objets & classes
//les chemins
$path_thumb         = "objects/class.thumb.php";
if (!class_exists('thumb')) { include($path_thumb); }

//Thumb
$thumb 	= new thumb();


$width      = $_GET["w"];
$height     = $_GET["h"];
$file       = $_GET["f"];
$dir        = $_GET["s"];
$wat  		= $_GET["l"];
$quality    = $_GET["q"];
if( ($wat == "w") or ($wat == "l") ){
	$watermark  = $configvars['watermark-path'];
}else{
	$watermark  = NULL;
}

//if( ( $_SESSION['competition_thumb_acces'] != $fonctions->return_Current_Host() ) and ( $watermark == NULL ) ){
if( ( $_SESSION['competition_thumb_acces'] != "1" ) and ( $watermark == NULL ) ){

	$watermark = $configvars['watermark-path'];
}

$source_dir = "uploads/";
$source = $source_dir.$file;
$distination_file = "";

$path = $thumb->return_path($source,$width,$height,$distination_file,80,$watermark);
?>