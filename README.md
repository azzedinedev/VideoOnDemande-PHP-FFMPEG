<h3 align="center">Video On Demande thumbnails generator using PHP and FFMPEG</h3>

---

<p align="center"> A manager of the video on demande actions using PHP and FFMPEG to generate thumbnails for videos
    <br> 
</p>

## About

This project consist to generate thumbnails from videos and encode the videos using a predefined scripts located on the (scripts_encodage) folder.

## Getting Started

On the project we have :

- index.php : It containe the form to charge the video and process the encoding.
- process.php : To process the encoding
- create_thumb.php
- convert_video.php

### Objects

We have 3 classes :

- class.thumb.php : to create the thumbnail image
- ffmpeg_class.php : A class used to encode, decode, resize and reformat videos
- functions.php


```
Give examples
```

### FFMPEG Class

The supported videos formats are :

- '3g2' : 3gp2 format
- '3gp': 3gp format
- 'aac' :  ADTS AAC
-	'aiff' : Audio IFF
-	'amr' :  3gpp amr file format
-	'asf' :  asf format
-	'avi' :  avi format
-	'flv' :  flv Animation
-	'gif' :  GIF Animation
-	'mov' :  mov format
-	'mj2' :  mov format
-	'mp4' :  mp4 format
-	'mpeg4' : MPEG4 format
-	'm4a' :  m4a format
-	'mpeg' : MPEG video
-	'mpeg1video' : MPEG1 video
-	'mpeg2video' : MPEG2 video
-	'mpegvideo' : mpeg format
-	'psp' :  psp mp4 format
-	'rm' :  rm format
-	'swf' :  Flash format
-	'vob' :  MPEG2 PS format (VOB)
-	'mjpeg' : jpeg
-	'yuv4mpegpipe' : yuv4mpegpipe format
-	'h264' : H264 format

The supported audios formats are :

- 'aac' :  ADTS AAC
-	'aiff' : Audio IFF
-	'amr' :  3gpp amr file format
-	'asf' :  asf format
-	'mp2' :  MPEG audio layer 2
-	'mp3' :  MPEG audio layer 3
-	'mp4' :  mp4 format
-	'mpeg2video' : MPEG2 video	
-	'rm' :  rm format
-	'wav' :  wav format

### Other fonctions

- format_bytes($bytes)
- format_kbytes($kbytes)
- empty_dir($path)
- remove_dir($dir)
- new_empty_dir($path)
- is_empty_dir($dir)
- remove_file_childes_from_dir($filename,$dir,$remove_this_dir = false)
- create_exploded_subdirictories($path)
- update_end_slash_dirictory($dir)
- stringURLSafe($string)
- stringToKewords($string)
- convertirChaineSansAccent($chaine)
- utf8_latin_to_ascii( $string, $case=0 )	 
- reduStr($str,$lenght)
- TimeAgo($datefrom,$dateto=-1)	 
- extractExtensionFile($file)
- impload_duration($seconds)
- replace_separator($from,$to,$path)

## How it work

To generate the informations about the video file :

```
$ffmpeg = new ffmpeg();
$data 	= $ffmpeg->getFileInfo($path_videos.$videofile);
```

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

To create the thumbnail using FFMPEG

```
 $exec = exec($path_ffmpeg." -i ".$path." -an -ss 00:00:03 -r 1 -vframes 1 -f mjpeg -y ".$path_images.".jpg");
```

## Author

- [@azzedinedev](https://github.com/azzedinedev)