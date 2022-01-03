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
	$data['duration'] 						//Duration of the video						
	$data['bitrate'] 						//Bitrate of the video						
	$data['start'] 							//Start of the video
	$data['video']['dimensions']['height'] 	//informations about height of the video						
	$data['video']['dimensions']['width'] 	//informations about width of the video							 
	$data['video']['frame_rate'] 			//informations about the frame rate of the video				
	$data['video']['pixel_aspect_ratio'] 	//informations about the pixel aspect ratio of the video		
	$data['video']['display_aspect_ratio'] 	//informations about the display aspect ratio of the video
	$data['video']['pixel_format'] 			//informations about the pixel format of the video
	$data['video']['codec'] 				//informations about the codec the video
	$data['audio'] 							//array('stereo' => -1, 'sample_rate' => -1, 'sample_rate' => -1) informations about sound
	$data['audio']['stereo'] 				//informations about stereo
	$data['audio']['sample_rate'] 			//informations about the sample rate of the sound
	$data['audio']['bitrate'] 				//informations about the bitrate of the sound
	$data['audio']['codec'] 				//informations about the codec of the sound
	$data['_raw_info']						//Informations about raw
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