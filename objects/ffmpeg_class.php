<?php
//+---------------------------------------------------------------------+
//|	ffmpeg_class.php													|
//+---------------------------------------------------------------------+
//|	Video treatment with FFMPEG	                    					|
//| A class used to encode, decode, resize and reformat videos...       |
//|	Developpement par :						|
//|		SAR Azzeddine: azzedinedev@gmail.com							|
//+---------------------------------------------------------------------+
/*
 * @ name : video.php
 * @ description : a class used to encode, decode, resize and reformat videos...
 * @ author : Yaug - Manuel Esteban
 * @ contact : yaug@caramail.com
 * @ date : 03/01/2008
*/

class ffmpeg{
	var $ffmpegPath;
	var $video_info, $video_file, $duration, $bitrate, $video_format, $audio_format,
		$video_size, $video_fps, $audio_freq, $audio_bitrate,
		$encoding_vformat, $encoding_aformat, $encoding_vcodec, $encoding_acodec,
		$encoding_vsize, $encoding_duration, $encoding_afreq, $encoding_target,
		$encoding_packet_size, $encoding_aspect, $encoding_ac, $encoding_bitrate,
		$encoding_abitrate, $encoding_fps, $encoding_time_position, $encoding_nosound;
	var $video_id;
	
	/**
	 * Video formats supported
	 */
	var $vformat = array(
	'3g2', 			// 3gp2 format
	'3gp', 			// 3gp format
	'aac', 			// ADTS AAC
	'aiff', 		// Audio IFF
	'amr', 			// 3gpp amr file format
	'asf', 			// asf format
	'avi', 			// avi format
	'flv', 			// flv Animation
	'gif', 			// GIF Animation
	'mov', 			// mov format
	'mj2', 			// mov format
	'mp4', 			// mp4 format
	'mpeg4', 		// MPEG4 format
	'm4a', 			// m4a format
	'mpeg', 		// MPEG video
	'mpeg1video', 	// MPEG1 video
	'mpeg2video', 	// MPEG2 video
	'mpegvideo', 	// mpeg format
	'psp', 			// psp mp4 format
	'rm', 			// rm format
	'swf', 			// Flash format
	'vob', 			// MPEG2 PS format (VOB)
	'mjpeg', 		// jpeg
	'yuv4mpegpipe',	// yuv4mpegpipe format
	'h264'
	);
	
	/**
	 * Audio formats supported
	 */	 
	var $aformat = array(
	'aac', 			// ADTS AAC
	'aiff', 		// Audio IFF
	'amr', 			// 3gpp amr file format
	'asf', 			// asf format
	'mp2', 			// MPEG audio layer 2
	'mp3', 			// MPEG audio layer 3
	'mp4', 			// mp4 format
	'mpeg2video', 	// MPEG2 video	
	'rm', 			// rm format
	'wav', 			// wav format
	);	

	/**
	 * Size Presets
	 */
	var $size = array(	
	'SAS' 	=> 'SameAsSource',
	'SQCIF' => '128x96',
	'QCIF' 	=> '176x144',
	'CIF' 	=> '352x288',
	'4CIF' 	=> '704x576',
	'QQVGA' => '160x120',
	'QVGA' 	=> '320x240',
	'VGA' 	=> '640x480',
	'SVGA'	=> '800x600',
	'XGA' 	=> '1024x768',
	'UXGA' 	=> '1600x1200',
	'QXGA' 	=> '2048x1536',
	'SXGA' 	=> '1280x1024',
	'QSXGA' => '2560x2048',
	'HSXGA' => '5120x4096',
	'WVGA' 	=> '852x480',
	'WXGA' 	=> '1366x768',
	'WSXGA' => '1600x1024',
	'WUXGA'	=> '1920x1200',
	'WOXGA' => '2560x1600',
	'WQSXGA'=> '3200x2048',
	'WQUXGA'=> '3840x2400',
	'WHSXGA'=> '6400x4096',
	'WHUXGA'=> '7680x4800',
	'CGA' 	=> '320x200',
	'EGA' 	=> '640x350',
	'HD480' => '852x480',
	'HD720' => '1280x720',
	'HD1080'=> '1920x1080'
	);
	
	/**
	 * Ratio Presets
	 */
	var $aspect = array(
	'RATIO_STANDARD'	=> '4:3',
	'RATIO_WIDE'		=> '16:9',
	'RATIO_3333'		=> "1.3333",
	'RATIO_7777'		=> "1.7777",
	'RATIO_CINEMATIC' 	=> '1.85'
	);
	
	/**
	 * target array
	 */
	var $target = array(
	"vcd",
	"svcd",
	"dvd",
	"dv",
	"dv50",
	"pal-vcd",
	"ntsc-svcd"
	);
		
	/*
	 * function : __construct
	 * @description : Constructor
	 * @param : $video
	*/
	 function ffmpeg(){
		/*
		$this->video_file 	= $video;
		$this->video_id		= md5($video);
		*/
		//Initialisation
		$this->initialisation();
	}
	
	/*
	 * function : __construct
	 * @description : Initialisation
	*/
	 function initialisation(){
		//Initialisation
		$this->ffmpegPath 				= "ffmpeg";
		$this->encoding_vformat 		= "flv";
		$this->encoding_vcodec 			= "flv";
		$this->encoding_acodec 			= false;
		$this->encoding_vsize 			= "640x480";
		$this->encoding_duration 		= false;
		$this->encoding_fps 			= "25";
		$this->encoding_bitrate 		= "512";
		$this->encoding_nosound 		= false;
		$this->encoding_abitrate		= "32";
		$this->encoding_afreq 			= "22050";
		$this->encoding_ac 				= false;
		$this->encoding_target 			= false;
		$this->encoding_packet_size 	= false;
		$this->encoding_aspect 			= "4:3";
		$this->encoding_time_position	= false;
	}

	/*
	 * function : set_encoding_vformat()
	 * @description : Set the format for video encoding
	 * @param : $format (the format of the output video)
	*/
	 function set_encoding_vformat($format){
		$this->encoding_vformat = $format;
	}


	/*
	 * function : set_encoding_aformat()
	 * @description : Set the format for audio encoding
	 * @param : $format (the format of the output audio)
	*/
	 function set_encoding_aformat($format){
		$this->encoding_aformat = $format;
	}


	/*
	 * function : set_encoding_vcodec()
	 * @description : Set the codec for video encoding, be carefull with the codec name
	 * @param : $codec (the codec of the output video)
	*/
	 function set_encoding_vcodec($codec){
		$this->encoding_vcodec = $codec;
	}


	/*
	 * function : set_encoding_acodec()
	 * @description : Set the codec for audio encoding, be carefull with the codec name
	 * @param : $codec (the codec of the output audio)
	*/
	 function set_encoding_acodec($codec){
		$this->encoding_acodec = $codec;
	}


	/*
	 * function : set_encoding_vsize()
	 * @description : Set the size of the output video
	 * @param : $width (width of the output video)
	 * @param : $height (height of the output video)
	*/
	 function set_encoding_vsize($size){
		$this->encoding_vsize = $size;
	}


	/*
	 * function : set_encoding_duration()
	 * @description : Set the size of the output video
	 * @param : $duration (duration of the output like 00:00:10)
	*/
	 function set_encoding_duration($duration){
		$this->encoding_duration = $duration;
	}


	/*
	 * function : set_encoding_afreq()
	 * @description : Set the audio frequence of the output video
	 * @param : $freq (audio frequence of the output like 00:00:10)
	*/
	 function set_encoding_afreq($freq){
		$this->encoding_afreq = $freq;
	}


	/*
	 * function : set_encoding_target()
	 * @description : Specify target file type
	 * @param : $target (Specified target file type)
	*/
	 function set_encoding_target($target){
		$this->encoding_target = $target;
	}


	/*
	 * function : set_encoding_packet_size()
	 * @description : Set packet size in bits.
	 * @param : $weight (Size)
	*/
	 function set_encoding_packet_size($weight){
		$this->encoding_packet_size = $weight;
	}


	/*
	 * function : set_encoding_aspect()
	 * @description : Set aspect ratio (4:3, 16:9 or 1.3333, 1.7777).
	 * @param : $ratio (Ratio of the generated video)
	*/
	 function set_encoding_aspect($ratio){
		$this->encoding_aspect = $ratio;
	}


	/*
	 * function : set_encoding_ac()
	 * @description : Set the number of audio channels
	 * @param : $nb (Number of channels)
	*/
	 function set_encoding_ac($nb){
		$this->encoding_ac = $nb;
	}


	/*
	 * function : set_encoding_bitrate()
	 * @description : Set the video bitrate in bit/s
	 * @param : $bitrate
	*/
	 function set_encoding_bitrate($bitrate){
		$this->encoding_bitrate = $bitrate;
	}


	/*
	 * function : set_encoding_abitrate()
	 * @description : Set the audio bitrate in bit/s
	 * @param : $abitrate
	*/
	 function set_encoding_abitrate($abitrate){
		$this->encoding_abitrate = $abitrate;
	}


	/*
	 * function : set_encoding_fps()
	 * @description : Set frame rate
	 * @param : $fps
	*/
	 function set_encoding_fps($fps){
		$this->encoding_fps = $fps;
	}


	/*
	 * function : set_encoding_time_position()
	 * @description : time position in seconds. hh:mm:ss[.xxx] syntax is also supported.
	 * @param : $position
	*/
	 function set_encoding_time_position($position){
		$this->encoding_time_position = $position;
	}


	/*
	 * function : set_encoding_nosound()
	 * @description : time position in seconds. hh:mm:ss[.xxx] syntax is also supported.
	 * @param : $position
	*/
	 function set_encoding_nosound(){
		if($this->encoding_nosound){
			$this->encoding_nosound=false;
		}else{
			$this->encoding_nosound=true;
		}
	}

	/**
	 * @desc	tester la valideter de l'heure avec le format: hh:mm:ss[.xxx]
	 * @param	$var string
	 * @access public
	 * @return boolean
	 */
	function isValidTimeExt($var){
		if (ereg('^[0-5][0-9]:[0-5][0-9]:[0-5][0-9](.[0-9][0-9][0-9]){0,1}$',$var)) {
			return true;
		}else{
			return false;
		} 
	}
	 
	/**
	 * @desc	tester la valideter de l'heure avec le format: hh:mm:ss
	 * @param	$var string
	 * @access 	public
	 * @return 	boolean
	 */
	function isValidTime($var){
		if (ereg('^[0-5][0-9]:[0-5][0-9]:[0-5][0-9]$',$var)) {
			return true;
		}else{
			return false;
		} 
	}
	 
	/**
	 * @desc	tester si la variable est entière positif
	 * @param	$var string
	 * @access 	public
	 * @return 	boolean
	 */
	function isIntPos($var){
		if (ereg('^[0-9][0-9]*$',$var)) {
			return true;
		}else{
			return false;
		} 
	}
		
	/**
	 * @name return_duration($var)
	 * @desc @FR retourner la durée de la video si la durée est accepté au format hh:mm:ss
	 * @desc @EN return the video duration if the input is in the time format like hh:mm:ss
	 */
	function return_duration($var){
		if( $this->isValidTime($var) ){
			return " -t ".escapeshellarg($var);	
		}else{
			return "";
		}
	} 
	
	/**
	 * @name return_vformat($var)
	 * @desc @FR retourner le format de la video si le format est accépté
	 * @desc @EN return the video format if it is accepted
	 */
	function return_vformat($var){
		if( in_array($var,$this->vformat) or in_array($var,$this->aformat) ){
			return " -f ".escapeshellarg($var);	
		}else{
			return "";
		}
	} 
	
	/**
	 * @name return_vcodec($var)
	 * @desc @FR retourner le format du codec de la video si le format est accépté
	 * @desc @EN return the video codec format if it is accepted
	 */
	function return_vcodec($var){
//		if( in_array($var,$this->vformat) ){
			return " -vcodec ".escapeshellarg($var);	
//		}else{
			return "";
//		}
	} 
	
	/**
	 * @name return_acodec($var)
	 * @desc @FR retourner le ratio du son de la video si le format est accépté
	 * @desc @EN return the audio format of the video if it is accepted
	 */
	function return_acodec($var){
		if( in_array($var,$this->aformat) ){
			return " -acodec ".escapeshellarg($var);	
		}else{
			return "";
		}
	} 
	
	/**
	 * @name return_vsize($var)
	 * @desc @FR retourner les dimenssions de la video si cette dimenssion est accépté
	 * @desc @EN return the video size if it is accepted
	 */
	function return_vsize($var){
		if( in_array($var,$this->size) ){
			return " -s ".escapeshellarg($var);	
		}else{
			return "";
		}
	}
	
	/**
	 * @name return_vformat($var)
	 * @desc @FR retourner le format de la video si le format est accépté
	 * @desc @EN return the video format if it is accepted
	 */
	function return_fps($var){
		if( $var != "" ){
			return " -r ".escapeshellarg($var);	
		}else{
			return "";
		}
	} 
	
	/**
	 * @name return_bitrate($var)
	 * @desc @FR retourner le bitrate de la video si le format est accépté
	 * @desc @EN return the video bitrate if it is accepted
	 */
	function return_bitrate($var){
	
		if( $this->isIntPos($var) ){
			return " -b ".escapeshellarg($var.'kb');	
		}else{
			return "";
		}
	}
	
	/**
	 * @name return_abitrate($var)
	 * @desc @FR retourner le bitrate du son de la video si le format est accépté
	 * @desc @EN return the audio bitrate if it is accepted
	 */
	function return_abitrate($var){
		if(in_array(intval($var), array(16, 32, 64, 128))){
			return " -ab ".escapeshellarg($var.'kb');	
		}else{
			return "";
		}
	}
	
	/**
	 * @name return_afreq($var)
	 * @desc @FR retourner le bitrate du son de la video si le format est accépté
	 * @desc @EN return the audio bitrate if it is accepted
	 */
	function return_afreq($var){
		if(in_array(intval($var), array(11025, 22050, 44100))){
			return " -ar ".escapeshellarg($var);
		}else{
			return "";
		}
	}
	
	/**
	 * @name return_ac($var)
	 * @desc @FR retourner le nombre de chaine du son (par default = 1)
	 * @desc @EN return number of audio channels (default = 1)
	 */
	function return_ac($var){
		if( $this->isIntPos($var) ){
			return " -ac ".escapeshellarg($var);
		}else{
			return "";
		}
	}
	
	/**
	 * @name return_target($var)
	 * @desc @FR retourner le nombre de chaine du son (par default = 1)
	 * @desc @EN return number of audio channels (default = 1)
	 */
	function return_target($var){
		if( in_array($var,$this->target) ){
			return " -target ".escapeshellarg($var);
		}else{
			return "";
		}
	}
	
	/**
	 * @name return_packet_size($var)
	 * @desc @FR retourner le nombre de chaine du son (par default = 1)
	 * @desc @EN return number of audio channels (default = 1)
	 */
	function return_packet_size($var){
		if( $var != "" ){
			return " -ps ".escapeshellarg($var);
		}else{
			return "";
		}
	}
	
	/**
	 * @name return_aspect($var)
	 * @desc @FR retourner le ratio de la video si le format est accépté
	 * @desc @EN return the video ratio if it is accepted
	 */
	function return_aspect($var){
		if( in_array($var,$this->aspect) ){
			return " -aspect ".escapeshellarg($var);	
		}else{
			return "";
		}
	}
	
	/**
	 * @name return_aspect($var)
	 * @desc @FR retourner la position du debut de la video si le format est accépté => hh:mm:ss[.xxx]
	 * @desc @EN return and Seek to given time position in seconds. hh:mm:ss[.xxx] syntax is also supported. 
	 */
	function return_time_position($var){
		if( $this->isValidTimeExt($var) ){
			return " -ss ".escapeshellarg($var);
		}else{
			return "";
		}
	}

	/*
	 * function : encode()
	 * @description : Encode video with defined params
	 * @param : $file_name (name of the file created.)
	*/
	 function encode($file_name){
	 	if( ($this->video_file != "") and ($file_name != "") ){
			
			$file_name 			= str_replace('\\', '/', $file_name);
			$this->video_file 	= str_replace('\\', '/', $this->video_file);
			
			$command = $this->ffmpegPath." -y -i ".$this->video_file;
			
			if($this->encoding_vformat) 		$command.= $this->return_vformat($this->encoding_vformat);
			if($this->encoding_vcodec) 			$command.= $this->return_vcodec($this->encoding_vcodec);
			if($this->encoding_acodec) 			$command.= $this->return_acodec($this->encoding_acodec);
			if($this->encoding_vsize) 			$command.= $this->return_vsize($this->encoding_vsize);
			if($this->encoding_duration)		$command.= $this->return_duration($this->encoding_duration);
			if($this->encoding_fps)				$command.= $this->return_fps($this->encoding_fps);
			if($this->encoding_bitrate)			$command.= $this->return_bitrate($this->encoding_bitrate);
			if($this->encoding_nosound)			$command.= " -an ";
			if($this->encoding_abitrate)		$command.= $this->return_abitrate($this->encoding_abitrate);
			if($this->encoding_afreq)			$command.= $this->return_afreq($this->encoding_afreq);
			if($this->encoding_ac)				$command.= $this->return_ac($this->encoding_ac);
			if($this->encoding_target)			$command.= $this->return_target($this->encoding_target);
			if($this->encoding_packet_size)		$command.= $this->return_packet_size($this->encoding_packet_size);
			if($this->encoding_aspect)			$command.= $this->return_aspect($this->encoding_aspect);
			if($this->encoding_time_position)	$command.= $this->return_time_position($this->encoding_time_position);

			$command.=" ".$file_name;
	
			print('commande executée :<br> '.$command);
			exec($command);
		}
	}

	/*
	 * function : get_image()
	 * @description : Get an image for a specific frame of a video
	 * @param : $frame (00:00:10.0002)
	 * @param : $image_name (name of this image)
	 * @param : $size
	*/
	 function get_image($frame,$image_name,$size){
	 	//Initialisation
		$this->initialisation();
		$this->encoding_vformat 		= "mjpeg";
		$this->encoding_duration 		= "001";
		$this->encoding_time_position 	= $frame;
		$this->encoding_vsize 			= $size;
		
		//We build the image
		$this->encode($image_name);
	}

	/**
	 * getFileInfo($file)
	 * Recuperer les informations sur la video
	 * string $file => chemin vers le fichier video
	 * Informations générales
	 *		$data['duration'] => la duree du fichier video						
	 *		$data['bitrate'] => le bitrate du fichier video						
	 *		$data['start'] => le debut de la duree du fichier video
	 * Informations sur la video
	 *		$data['video']['dimensions']['height'] => informations sur la hauteur de la video						
	 *		$data['video']['dimensions']['width'] => informations sur la largeur de la video						
	 *		$data['video']['frame_rate'] => informations sur le ratio des frame de la video				
	 *		$data['video']['pixel_aspect_ratio'] => informations sur l'aspect des ratios de la video		
	 *		$data['video']['display_aspect_ratio'] => informations sur l'affichage des ratios de la video
	 *		$data['video']['pixel_format'] => informations sur le format des pîxels de la video
	 *		$data['video']['codec'] => informations sur le codec de la video
	 * Informations sur le son de la video
	 *		$data['audio'] => array('stereo' => -1, 'sample_rate' => -1, 'sample_rate' => -1)informations sur le son
	 *		$data['audio']['stereo'] => informations sur le stereo du son
	 *		$data['audio']['sample_rate'] => informations sur le sample_rate du son
	 *		$data['audio']['bitrate'] => informations sur le bitrate du son
	 *		$data['audio']['codec'] => informations sur le codec du son
	 * Informations sur le fichiers video
	 *		$data['_raw_info'] => informations récuperées non formatées			
	 * @return array $data ou false
	 */
	function getFileInfo($file){
		$data = array();
		
		//executer le ffmpeg pour récuperer les informations du fichier video
		exec($this->ffmpegPath.' -i '.$file.' 2>&1', $buffer);
		$buffer = implode("\r\n", $buffer);
		
		//Recuperer la durée et le taux de bit (bitrate)
			preg_match_all('/Duration: (.*)/', $buffer, $matches);
			if(count($matches) > 0)
			{
				$parts 				= explode(', ', trim($matches[1][0]));
				$duration_part		= explode('.', $parts[0]);
				$data['duration']	= $duration_part[0]; //la duree du fichier video
				
				$data['bitrate']  	= intval(ltrim($parts[2], 'bitrate: '));//le bitrate du fichier video
				$data['start']  	= ltrim($parts[1], 'start: ');//le debut de la duree du fichier video
			}
			
		//Recuperer les informations du stream
			preg_match('/Stream(.*): Video: (.*)/', $buffer, $matches);
			if(count($matches) > 0)
			{
				$data['video'] 		= array();
		//Recuperer les dimensions
				preg_match('/\s([0-9]{1,5})x([0-9]{1,5})\s/', $matches[2], $dimensions_matches);
				$dimensions_value = $dimensions_matches[0];
				$data['video']['dimensions'] 	= array(
					'width' 					=> floatval($dimensions_matches[1]),
					'height' 					=> floatval($dimensions_matches[2])
				);
				$data['test'] = $matches[2];
		//Recuperer le framerate
				preg_match('/([0-9\.]+) (fps|tb)\(r\)/', $matches[0], $fps_matches);
				$data['video']['frame_rate'] 	= floatval($fps_matches[1]);
				$fps_value = $fps_matches[0];
		//Recuperer les ratios
				preg_match('/\[PAR ([0-9\:\.]+) DAR ([0-9\:\.]+)\]/', $matches[0], $ratio_matches);
				if(count($ratio_matches))
				{
					$data['video']['pixel_aspect_ratio'] 	= $ratio_matches[1];
					$data['video']['display_aspect_ratio'] 	= $ratio_matches[2];
				}
		// 					formats should be anything left over, let me know if anything else exists
				$parts 							= explode(',', $matches[2]);
				$other_parts 					= array($dimensions_value, $fps_value);
				$formats = array();
				foreach($parts as $key=>$part)
				{
					$part = trim($part);
					if(!in_array($part, $other_parts))
					{
						array_push($formats, $part);
					}
				}
				$data['video']['pixel_format'] 	= $formats[1];
				$data['video']['codec'] 		= $formats[0];
			}
			
		//Recuperer les informartion sur l'audio de la video
			preg_match('/Stream(.*): Audio: (.*)/', $buffer, $matches);
			if(count($matches) > 0)
			{
	
		//Setup audio values
				$data['audio'] = array(
					'stereo'		=> -1, 
					'sample_rate'	=> -1, 
					'sample_rate'	=> -1
				);
				$other_parts = array();
		//Recuperer les valeurs du stereo
				preg_match('/(stereo|mono)/i', $matches[0], $stereo_matches);
				if(count($stereo_matches))
				{
					$data['audio']['stereo'] 		= $stereo_matches[0];
					array_push($other_parts, $stereo_matches[0]);
				}
		//Recuperer le sample_rate
				preg_match('/([0-9]{3,6}) Hz/', $matches[0], $sample_matches);
				if(count($sample_matches))
				{
					$data['audio']['sample_rate'] 	= count($sample_matches) ? floatval($sample_matches[1]) : -1;
					array_push($other_parts, $sample_matches[0]);
				}
		//Recuperer le bit rate
				preg_match('/([0-9]{1,3}) kb\/s/', $matches[0], $bitrate_matches);
				if(count($bitrate_matches))
				{
					$data['audio']['bitrate'] 		= count($bitrate_matches) ? floatval($bitrate_matches[1]) : -1;
					array_push($other_parts, $bitrate_matches[0]);
				}
		//formats should be anything left over, let me know if anything else exists
				$parts 							= explode(',', $matches[2]);
				$formats = array();
				foreach($parts as $key=>$part)
				{
					$part = trim($part);
					if(!in_array($part, $other_parts))
					{
						array_push($formats, $part);
					}
				}
	
				$data['audio']['codec'] 		= $formats[0];
			}
		
		//	 			check that some data has been obtained
			if(!count($data))
			{
				$data = false;
			}
			else
			{
				$data['_raw_info'] = $buffer;
			}
					
			//Retourner le tableau de valeurs de traitementent de la video sinon false
			$this->video_info = $data;
			return $data;
	}

}
?>