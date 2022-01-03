<?php
session_start();
include("objects/ffmpeg_class.php");
include("objects/functions.php");
include("config.php");
//Chemin vers l'executable du FFMPEG
$path_ffmpeg = $config["path_ffmpeg"];

$ffmpeg = new ffmpeg();
$ffmpeg->ffmpegPath = $path_ffmpeg;

$functions = new functions();

$token 		= $_POST['token'];
$videofile 	= $_POST['videofile'];

//Chemins
$path_videos    = $config["path_upload"].$token.'/';  //Chemin des videos
		
//Verifier l'extension du fichier video
$base_file = $functions->extractExtensionFile($videofile);
$extension = $base_file["1"];

//$newvideofile 	= $functions->stringURLSafe($base_file[0]).'.'.$base_file[1];
$videofile 	= $config["basename_video"].'.'.$base_file[1];


$array_ext = $config["allowed_video_extension"];
if( in_array($extension, $array_ext) and ( file_exists($path_videos.$videofile) ) ){

	$data 		= $ffmpeg->getFileInfo($path_videos.$videofile);
	/*
	$data['duration'] 						//la duree du fichier video						
	$data['bitrate'] 						//le bitrate du fichier video						
	$data['start'] 							//le debut de la duree du fichier video
	$data['video']['dimensions']['height'] 	//informations sur la hauteur de la video						
	$data['video']['dimensions']['width'] 	//informations sur la largeur de la video							 
	$data['video']['frame_rate'] 			//informations sur le ratio des frame de la video				
	$data['video']['pixel_aspect_ratio'] 	//informations sur l'aspect des ratios de la video		
	$data['video']['display_aspect_ratio'] 	//informations sur l'affichage des ratios de la video
	$data['video']['pixel_format'] 			//informations sur le format des p�xels de la video
	$data['video']['codec'] 				//informations sur le codec de la video
	$data['audio'] 							//array('stereo' => -1, 'sample_rate' => -1, 'sample_rate' => -1) informations sur le son
	$data['audio']['stereo'] 				//informations sur le stereo du son
	$data['audio']['sample_rate'] 			//informations sur le sample_rate du son
	$data['audio']['bitrate'] 				//informations sur le bitrate du son
	$data['audio']['codec'] 				//informations sur le codec du son	
	$data['_raw_info']
	*/	
	
	if( $data['video']['dimensions']['height'] >= '720' ){
		//convert to 720_profile		
		//convert to 360_profile
		//convert to 240_profile
		//shell_exec("ffmpeg -y -i input.avi output.avi </dev/null >/dev/null 2>/var/log/ffmpeg.log &");
		$profles = '720p,360p,240p';
	}elseif( $data['video']['dimensions']['height'] > '360' ){
		//convert to 360_profile
		//convert to 240_profile
		$profles = '360p,240p';
	}elseif( $data['video']['dimensions']['height'] > '240' ){
		//convert to 240_profile
		$profles = '240p';
	}else{
		//noting
		$profles = 'auccun';
	}
	
	
}

echo $profles;
?>