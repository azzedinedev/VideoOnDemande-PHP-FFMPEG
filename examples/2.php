<?php
session_start();
include("../objects/ffmpeg_class.php");
include("../objects/functions.php");
include("../config.php");
//Chemin vers l'executable du FFMPEG
$path_ffmpeg = $config["path_ffmpeg"];

$ffmpeg = new ffmpeg();
$ffmpeg->ffmpegPath = $path_ffmpeg;

$functions = new functions();

$token 		= "6b79a77180e9ec3a7ca351ebe54641a2";//$_POST['token'];
$videofile 	= "video-source.flv";//$_POST['videofile'];

//Chemins
$path_videos    = $config["path_upload"].$token.'/';  //Chemin des videos
$path_images    = $config["path_upload"].$token.'/';  //Chemin des images cr�es par FFMPEG

		
//Verifier l'extension du fichier video
$base_file = $functions->extractExtensionFile($videofile);
$extension = $base_file["1"];
$newvideofile 	= $config["basename_video"].'.'.$base_file[1];

if( file_exists($path_videos.$newvideofile) ){
	//unlink($path_videos.$newvideofile);
}
//Rename the video file
rename($path_videos.$videofile,$path_videos.$newvideofile);

$videofile = $newvideofile;

$array_ext = $config["allowed_video_extension"];
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
		echo "sconde1 : ".$seconde_1.' --> '.$functions->impload_duration($seconde_1).'<br>sconde2 : '.$seconde_2.' --> '.$functions->impload_duration($seconde_2).'<br>sconde3 : '.$seconde_3.' --> '.$functions->impload_duration($seconde_3).'<br>';
		
		//duration1
		$duration_1 = $functions->impload_duration($seconde_1);
		//duration2
		$duration_2 = $functions->impload_duration($seconde_2);
		//duration3
		$duration_3 = $functions->impload_duration($seconde_3);
		
		$name_file_1 = $config["name_image_1"];
		$name_file_2 = $config["name_image_2"];
		$name_file_3 = $config["name_image_3"];
		
		$source_path = $path_videos.$videofile;
		
		if( $config["windows_envirenement"] == true ){
			$distination_1 = $functions->replace_separator("/","\\",$path_images.$name_file_1);
			$distination_2 = $functions->replace_separator("/","\\",$path_images.$name_file_2);
			$distination_3 = $functions->replace_separator("/","\\",$path_images.$name_file_3);
		}else{
			$distination_1 = $functions->replace_separator("\\","/",$path_images.$name_file_1);
			$distination_2 = $functions->replace_separator("\\","/",$path_images.$name_file_2);
			$distination_3 = $functions->replace_separator("\\","/",$path_images.$name_file_3);
		}
		
		//Fichier video valide
		//Executer la creation des vignettes à partir de la vidéo
		//Image 1
		$exec = exec($path_ffmpeg." -i ".$source_path." -an -ss ".$duration_1." -r 1 -vframes 1 -f mjpeg -y ".$distination_1);
		//Image 2
		$exec = exec($path_ffmpeg." -i ".$source_path." -an -ss ".$duration_2." -r 1 -vframes 1 -f mjpeg -y ".$distination_2);
		//Image 3
		$exec = exec($path_ffmpeg." -i ".$source_path." -an -ss ".$duration_3." -r 1 -vframes 1 -f mjpeg -y ".$distination_3);
		
		echo '<hr>'.$path_ffmpeg." -i ".$source_path." -an -ss ".$duration_3." -r 1 -vframes 1 -f mjpeg -y ".$distination_3.'<hr>';
		
		echo $name_file_1.'|-|'.$name_file_2.'|-|'.$name_file_3;
	}
	
}
?>