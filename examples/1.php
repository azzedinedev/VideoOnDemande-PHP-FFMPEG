<?php
session_start();
include("../objects/ffmpeg_class.php");
//Chemin vers l'executable du FFMPEG
$path_ffmpeg = "C:\\ffmpg\\ffmpeg.exe";

$ffmpeg = new ffmpeg();
$ffmpeg->ffmpegPath = $path_ffmpeg;

$token 		= $_POST['token'];
$videofile 	= $_POST['videofile'];
	
//Chemins
$path_videos    = realpath(__DIR__.'/uploads').'/'.$token.'/';  //Chemin des videos
$path_images    = realpath(__DIR__.'/uploads').'/'.$token.'/';  //Chemin des images cr�es par FFMPEG

/**
 * extractExtensionFile($file)
 * @Fonction permettant de s�parer l'extension de la base du fichier
**/
function extractExtensionFile($file)
{
	$list = explode(".",$file);
	$base = array();
	$file_base 	= "";
	$file_ext 	= "";
	$last = count($list)-1;
	for($i=0;$i<$last;$i++){
		$base[$i] = $list[$i];
	}
	$file_base 	= implode(".",$base);
	$file_ext	=strtolower($list[$last]);
	return array($file_base,$file_ext);
}

/**
 * impload_duration($seconds)
 * @Fonction permettant de formater les seconds en dur�e hh:mm:ss
**/
function impload_duration($seconds){
	
	$floor_h 	= floor($seconds/3600);
	$modulo_h = $seconds%3600;
	$rest_h	= $modulo_h;
	
	$floor_m 	= floor($rest_h/60);
	$modulo_min = $rest_h%60;
	$rest_min	= $modulo_min;
	
	$floor_s	= $rest_min;
	
	if( intval($floor_h) < 10 ){ $floor_h = "0".$floor_h;}
	if( intval($floor_m) < 10 ){ $floor_m = "0".$floor_m;}
	if( intval($floor_s) < 10 ){ $floor_s = "0".$floor_s;}
	
	return $floor_h.':'.$floor_m.':'.$floor_s;
}

function replace_separator($from,$to,$path){
	$return = str_replace($from,$to,$path);
	return $return;
}
		
//Verifier l'extension du fichier video
$base_file = extractExtensionFile($videofile);		
$extension = $base_file["1"];

$array_ext = array("avi","mpeg","mp4","wmv","mpg","flv");
if(in_array($extension, $array_ext)){

	$data 		= $ffmpeg->getFileInfo($path_videos.$videofile);
	$duration 	= $data['duration'];
	$exploded_duration = explode(":",$duration);
	$hour 	= $exploded_duration[0];
	$min 	= $exploded_duration[1];
	$sec 	= $exploded_duration[2];
	$duration_as_seconds = ($hour*3600)+($min*60)+$sec;

	if( $duration_as_seconds > 0 ){
		$dicotomie = $duration_as_seconds / 3;	
		if( $duration_as_seconds > 10 ){
			$seconde_1 = "5";	
			$seconde_2 = floor($duration_as_seconds/2);
			$seconde_3 = $duration_as_seconds-"5";
		}else{
			$seconde_1 = "0";
			$seconde_2 = floor($duration_as_seconds/2);
			$seconde_3 = $duration_as_seconds;
		}
		
		$duration_1 = impload_duration("4567");
		
		//duration1
		$duration_1 = impload_duration($seconde_1);
		//duration2
		$duration_2 = impload_duration($seconde_2);
		//duration3
		$duration_3 = impload_duration($seconde_3);
		
		$name_file_1 = "image-1-".$base_file[0].".jpg";
		$name_file_2 = "image-2-".$base_file[0].".jpg";
		$name_file_3 = "image-3-".$base_file[0].".jpg";
		
		$source_path = $path_videos.$videofile;
		
		$distination_1 = replace_separator("/","\\",$path_images.$name_file_1);
		$distination_2 = replace_separator("/","\\",$path_images.$name_file_2);
		$distination_3 = replace_separator("/","\\",$path_images.$name_file_3);
		
		
		//Fichier video valide
		//Executer la creation des vignettes � partir de la vid�o                    
		//Image 1
		$exec = exec($path_ffmpeg." -i ".$source_path." -an -ss ".$duration_1." -r 1 -vframes 1 -f mjpeg -y ".$distination_1);
		//Image 2
		$exec = exec($path_ffmpeg." -i ".$source_path." -an -ss ".$duration_2." -r 1 -vframes 1 -f mjpeg -y ".$distination_2);
		//Image 3
		$exec = exec($path_ffmpeg." -i ".$source_path." -an -ss ".$duration_3." -r 1 -vframes 1 -f mjpeg -y ".$distination_3);
		
		echo $name_file_1.'|-|'.$name_file_2.'|-|'.$name_file_3;
	}
	
}
?>