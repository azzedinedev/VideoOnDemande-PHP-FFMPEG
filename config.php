<?php
//Chemin vers l'executable du FFMPEG
$config["path_ffmpeg"] 				= "C:\\ffmpg\\ffmpeg.exe";
$config["path_upload"] 				= realpath(__DIR__.'/uploads').'/';
$config["name_image_1"] 			= "image-1.jpg";
$config["name_image_2"] 			= "image-2.jpg";
$config["name_image_3"] 			= "image-3.jpg";
$config["basename_video"] 			= "video-source";
$config["allowed_video_extension"]	= array("avi","mpeg","mp4","wmv","mpg","flv");
$config["windows_envirenement"] 	= true;
		
?>