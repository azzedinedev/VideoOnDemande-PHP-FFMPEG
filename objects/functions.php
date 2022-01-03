<?php

class functions
{

    /**
     * @public fonctions()
     * @construct Construct the class
	 * used foctions *******************************************************
	 * format_bytes($bytes)
	 * format_kbytes($kbytes)
	 * empty_dir($path)
	 * remove_dir($dir)
	 * new_empty_dir($path)
	 * is_empty_dir($dir)
	 * remove_file_childes_from_dir($filename,$dir,$remove_this_dir = false)
	 * create_exploded_subdirictories($path)
	 * update_end_slash_dirictory($dir)
	 * stringURLSafe($string)
	 * stringToKewords($string)
	 * convertirChaineSansAccent($chaine)
	 * utf8_latin_to_ascii( $string, $case=0 )	 
	 * reduStr($str,$lenght)
	 * TimeAgo($datefrom,$dateto=-1)	 
	 * extractExtensionFile($file)
	 * impload_duration($seconds)
	 * replace_separator($from,$to,$path)
	 * **********************************************************************
     */
    function functions()
    {
		//constrctor
    }

	function format_bytes($bytes) {
	   if ($bytes < 1024) return $bytes.' B';
	   elseif ($bytes < 1048576) return round($bytes / 1024, 2).' KB';
	   elseif ($bytes < 1073741824) return round($bytes / 1048576, 2).' MB';
	   elseif ($bytes < 1099511627776) return round($bytes / 1073741824, 2).' GB';
	   else return round($bytes / 1099511627776, 2).' TB';
	}
	
	function format_kbytes($kbytes) {
	   if ($kbytes < 1024) return $kbytes.' KB';
	   elseif ($kbytes < 1048576) return round($kbytes / 1024, 2).' MB';
	   elseif ($kbytes < 1073741824) return round($kbytes / 1048576, 2).' GB';
	   else return round($kbytes / 1073741824, 2).' TB';
	}
	
	/*
	 * empty_dir($path)
	 * @param string : $path -> path to directory to being empty
	 */
		function empty_dir($path){
			//Initilaisation
			$return = "";
			if (filetype($path) == "dir") {
				if ($handle = opendir($path)) {
					while (false !== ($entry = readdir($handle))) {
						if ($entry != "." && $entry != "..") {
							//Remove all files and directories into the $path
							if (filetype($path."/".$entry) == "dir") {
								$this->remove_dir($path."/".$entry);                           
							} else { 
								unlink($path."/".$entry);                            
							}
						}
					}
					closedir($handle);   
				}
			}
		}     
		
		/*
	 * remove_dir($dir)
	 * @param string : $dir -> path to directory to removing
		*/  
		function remove_dir($dir) {
			$strlength_dir = strlen($dir)-1;
			if( $dir[$strlength_dir] != "/" ){
				$dir.= "/";
			}
			if (is_dir($dir)) {
			 $objects = scandir($dir);
			 foreach ($objects as $object) {
			   if ($object != "." && $object != "..") {
				 if (filetype($dir.$object) == "dir") $this->remove_dir($dir.$object); else unlink($dir.$object);
			   }
			 }
			 reset($objects);
			 rmdir($dir);
			}
		}
		
	/*
	 * new_empty_dir($path)
	 * Create an new empty directory or empty the existed directory
	 * @param string : $path -> path to directory to being empty
	 */
		function new_empty_dir($path){
			$strlength_dir = strlen($path)-1;
			if( $path[$strlength_dir] != "/" ){
				$path.= "/";
			}		
			//Initilaisation
			$return = "";
			if (filetype($path) == "dir") {
				if ($handle = opendir($path)) {
					while (false !== ($entry = readdir($handle))) {
						if ($entry != "." && $entry != "..") {
							//Remove all files and directories into the $path
							if (filetype($path.$entry) == "dir") {
								$this->remove_dir($path.$entry);                           
							} else { 
								unlink($path.$entry);                            
							}
						}
					}
					closedir($handle);   
				}
			}else{
				mkdir($path);
			}
		}    
	
	/*
	 * is_empty_dir($dir)
	 * Return the status of the folder if it is empty
	 * @param string : $dir -> path to directory to test
	 */  
		function is_empty_dir($dir)
		{
			if ($dh = @opendir($dir))
			{
				while ($file = readdir($dh))
				{
					if ($file != '.' && $file != '..') {
						closedir($dh);
						return false;
					}
				}
				closedir($dh);
				return true;
			}
			else return false; // whatever the reason is : no such dir, not a dir, not readable
		}
		
	/*
	 * remove_file_childes_from_dir($filename,$dir,$remove_this_dir = false)
	 * Remove file thumbs copies from subdirectories of the directory
	 *		pour tout les repertoires {
	 *			-> Fichier existe => supprimer le fichier s'il existe -> Repertoire vide => supprimer le repertoire 
	 *		}
	 * @param string : $dir -> path to directory to remove file thumbs from subdirectories
	 */  
		function remove_file_childes_from_dir($filename,$dir,$remove_this_dir = false) {
			$strlength_dir = strlen($dir)-1;
			if( $dir[$strlength_dir] != "/" ){
				$dir.= "/";
			}
	
			if (is_dir($dir)) {
			 $objects = scandir($dir);
			 foreach ($objects as $object) {
			   if ($object != "." && $object != "..") {
	
				 if (filetype($dir.$object) == "dir") { 
					$this->remove_file_childes_from_dir($filename,$dir.$object,true); 
					//Directory
					if( $remove_this_dir == true ){
						//Si on demande la suppression des sous-dossiers vides
						if( $this->is_empty_dir($dir.$object) ){
							$this->remove_dir($dir.$object);
						}
					}
				 } else { 
					//File
					if( $object == $filename ){
						unlink($dir.$object);
					}
				 }
				 
			   }
			 }
			}
		}	
	/*
	 * create_exploded_subdirictories($path)
	 * Create folders of all exploded subdirictories of the end path directory
	 * @param string : $path -> the end path directory
	 */  
	function create_exploded_subdirictories($path) {
		//Create dirctories when they not exists
		$exploaded_path = explode("/", $path);
		$i = 0;
		$exist_path = false;
		$complete_path_test .= "";  
		while ( ($i < count($exploaded_path)-1) ){
			$complete_path_test .= $exploaded_path[$i]."/";                                                
			if( !is_dir($complete_path_test) ){ 				
				mkdir($complete_path_test,0777);
			}
			$i++;
		}		
	}
	
	/*
	 * update_end_slash_dirictory($path)
	 * Update the end sllashe of the folder
	 * @param string : $dir -> the directory
	 */  
	function update_end_slash_dirictory($dir) {		
		$strlength_dir = strlen($dir)-1;
		if( $dir[$strlength_dir] != "/" ){
			$dir.= "/";
		}
		return $dir;
	}	
	
	/* 
	 * Fonction de conversion des texts en texts SEO
	 * @param string : La chaine à convertir
	 * <returns>string</returns>
	 */
	function stringURLSafe($string)
	{
		$string = strip_tags($string);
		
		//remove any '-' from the string since they will be used as concatenaters
		$str = str_replace('-', ' ', $string);
		$str = str_replace('_', ' ', $string);
		$str = $this->utf8_latin_to_ascii($str,0);
		// Convert certain symbols to letter representation
		$str = str_replace(array('&',"d'","l'", '"', '<', '>','|',',',';','(',')','°','\\','/','[',']','{','}','#','+','=','-','.','_','«','»',':','@'), array(' ',' ',' ', ' ', ' ', ' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' at '), $str);
		
		// Lowercase and trim
		$str = trim(mb_strtolower($str,'UTF-8'));		
		// Remove any duplicate whitespace, and ensure all characters are alphanumeric
		
		$str = preg_replace(array('/\s+/','/[^A-Za-z0-9\-]/'), array('-',''), $str);
	
		return $str;
	}
	
	/* 
	 * Fonction de conversion de chaîne vers des mots-clefs
	 * @param string : La chaine à convertir en mots-clefs
	 * @returns string : mots-clefs séparés par des virgules
	 */	
	function stringToKewords($string){
		
		$string = str_replace(array('&', '"', '<', '>','|',',',';','(',')','°','\\','/','[',']','{','}','#','+','=','-','.','_','«','»',':'), array(' ', ' ', ' ', ' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' '), $string);
		$string = trim(preg_replace('/\s+/', ' ', $string));
		
		$arr = explode(" ", $string);
		$arr_keywords = array();
		$arr_skiped_words = array('the','and','or','of','yes','no','to','à','de','des','du','le','la','les','oui','non','et','ou');
		foreach( $arr as $key => $str){
			if( !in_array( mb_strtolower($str,'UTF-8'),$arr_skiped_words) ){
					if( !in_array(mb_strtolower($str,'UTF-8'),$arr_keywords) ){
					$arr_keywords[] = mb_strtolower($str,'UTF-8');
				}
				
			}
		}
		
		$string = implode(', ',$arr_keywords);
		return $string;
	}	
	/* 
	 * Fonction de conversion de chaîne accentué en chaîne sans accent
	 * @param string : La chaine à convertir
	 * <returns>string</returns>
	 */
	function convertirChaineSansAccent($chaine) 
	{
		// Déclaration de variables
		$accent 	= "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÌÍÎÏìíîïÙÚÛÜùúûüÿÑñÇç";
		$sansAccent = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeIIIIiiiiUUUUuuuuyNnCc";
	
		// Pour chaque accent
		for($i=0; $i < strlen($sansAccent); $i++) 
		{
			// Remplacement de l'accent par son équivalent sans accent dans la chaîne de caractères
			$chaine = str_replace($accent[$i], $sansAccent[$i],$chaine);
		}
	
		// Retour du résultat
		return $chaine;
	}
	
	/**
	 * Returns strings transliterated from UTF-8 to Latin
	 *
	 * @param   string   $string  String to transliterate
	 * @param   boolean  $case    Optionally specify upper or lower case. Default to null.
	 *
	 * @return  string  Transliterated string
	 *
	 * @since   11.1
	 */
	function utf8_latin_to_ascii( $string, $case=0 ){

		static $UTF8_LOWER_ACCENTS = NULL;
		static $UTF8_UPPER_ACCENTS = NULL;

		if($case <= 0){

			if ( is_null($UTF8_LOWER_ACCENTS) ) {
				$UTF8_LOWER_ACCENTS = array(
				'à' => 'a', 'ô' => 'o', 'ď' => 'd', 'ḟ' => 'f', 'ë' => 'e', 'š' => 's', 'ơ' => 'o',
				'ß' => 'ss', 'ă' => 'a', 'ř' => 'r', 'ț' => 't', 'ň' => 'n', 'ā' => 'a', 'ķ' => 'k',
				'ŝ' => 's', 'ỳ' => 'y', 'ņ' => 'n', 'ĺ' => 'l', 'ħ' => 'h', 'ṗ' => 'p', 'ó' => 'o',
				'ú' => 'u', 'ě' => 'e', 'é' => 'e', 'ç' => 'c', 'ẁ' => 'w', 'ċ' => 'c', 'õ' => 'o',
				'ṡ' => 's', 'ø' => 'o', 'ģ' => 'g', 'ŧ' => 't', 'ș' => 's', 'ė' => 'e', 'ĉ' => 'c',
				'ś' => 's', 'î' => 'i', 'ű' => 'u', 'ć' => 'c', 'ę' => 'e', 'ŵ' => 'w', 'ṫ' => 't',
				'ū' => 'u', 'č' => 'c', 'ö' => 'oe', 'è' => 'e', 'ŷ' => 'y', 'ą' => 'a', 'ł' => 'l',
				'ų' => 'u', 'ů' => 'u', 'ş' => 's', 'ğ' => 'g', 'ļ' => 'l', 'ƒ' => 'f', 'ž' => 'z',
				'ẃ' => 'w', 'ḃ' => 'b', 'å' => 'a', 'ì' => 'i', 'ï' => 'i', 'ḋ' => 'd', 'ť' => 't',
				'ŗ' => 'r', 'ä' => 'ae', 'í' => 'i', 'ŕ' => 'r', 'ê' => 'e', 'ü' => 'ue', 'ò' => 'o',
				'ē' => 'e', 'ñ' => 'n', 'ń' => 'n', 'ĥ' => 'h', 'ĝ' => 'g', 'đ' => 'd', 'ĵ' => 'j',
				'ÿ' => 'y', 'ũ' => 'u', 'ŭ' => 'u', 'ư' => 'u', 'ţ' => 't', 'ý' => 'y', 'ő' => 'o',
				'â' => 'a', 'ľ' => 'l', 'ẅ' => 'w', 'ż' => 'z', 'ī' => 'i', 'ã' => 'a', 'ġ' => 'g',
				'ṁ' => 'm', 'ō' => 'o', 'ĩ' => 'i', 'ù' => 'u', 'į' => 'i', 'ź' => 'z', 'á' => 'a',
				'û' => 'u', 'þ' => 'th', 'ð' => 'dh', 'æ' => 'ae', 'µ' => 'u', 'ĕ' => 'e', 'œ' => 'oe',
				);
			}

		$string = str_replace(
			array_keys($UTF8_LOWER_ACCENTS),
			array_values($UTF8_LOWER_ACCENTS),
			$string
			);
		}

		if($case >= 0){
			if ( is_null($UTF8_UPPER_ACCENTS) ) {
				$UTF8_UPPER_ACCENTS = array(
				'À' => 'A', 'Ô' => 'O', 'Ď' => 'D', 'Ḟ' => 'F', 'Ë' => 'E', 'Š' => 'S', 'Ơ' => 'O',
				'Ă' => 'A', 'Ř' => 'R', 'Ț' => 'T', 'Ň' => 'N', 'Ā' => 'A', 'Ķ' => 'K',
				'Ŝ' => 'S', 'Ỳ' => 'Y', 'Ņ' => 'N', 'Ĺ' => 'L', 'Ħ' => 'H', 'Ṗ' => 'P', 'Ó' => 'O',
				'Ú' => 'U', 'Ě' => 'E', 'É' => 'E', 'Ç' => 'C', 'Ẁ' => 'W', 'Ċ' => 'C', 'Õ' => 'O',
				'Ṡ' => 'S', 'Ø' => 'O', 'Ģ' => 'G', 'Ŧ' => 'T', 'Ș' => 'S', 'Ė' => 'E', 'Ĉ' => 'C',
				'Ś' => 'S', 'Î' => 'I', 'Ű' => 'U', 'Ć' => 'C', 'Ę' => 'E', 'Ŵ' => 'W', 'Ṫ' => 'T',
				'Ū' => 'U', 'Č' => 'C', 'Ö' => 'Oe', 'È' => 'E', 'Ŷ' => 'Y', 'Ą' => 'A', 'Ł' => 'L',
				'Ų' => 'U', 'Ů' => 'U', 'Ş' => 'S', 'Ğ' => 'G', 'Ļ' => 'L', 'Ƒ' => 'F', 'Ž' => 'Z',
				'Ẃ' => 'W', 'Ḃ' => 'B', 'Å' => 'A', 'Ì' => 'I', 'Ï' => 'I', 'Ḋ' => 'D', 'Ť' => 'T',
				'Ŗ' => 'R', 'Ä' => 'Ae', 'Í' => 'I', 'Ŕ' => 'R', 'Ê' => 'E', 'Ü' => 'Ue', 'Ò' => 'O',
				'Ē' => 'E', 'Ñ' => 'N', 'Ń' => 'N', 'Ĥ' => 'H', 'Ĝ' => 'G', 'Đ' => 'D', 'Ĵ' => 'J',
				'Ÿ' => 'Y', 'Ũ' => 'U', 'Ŭ' => 'U', 'Ư' => 'U', 'Ţ' => 'T', 'Ý' => 'Y', 'Ő' => 'O',
				'Â' => 'A', 'Ľ' => 'L', 'Ẅ' => 'W', 'Ż' => 'Z', 'Ī' => 'I', 'Ã' => 'A', 'Ġ' => 'G',
				'Ṁ' => 'M', 'Ō' => 'O', 'Ĩ' => 'I', 'Ù' => 'U', 'Į' => 'I', 'Ź' => 'Z', 'Á' => 'A',
				'Û' => 'U', 'Þ' => 'Th', 'Ð' => 'Dh', 'Æ' => 'Ae', 'Ĕ' => 'E', 'Œ' => 'Oe',
				);
			}
		$string = str_replace(
		array_keys($UTF8_UPPER_ACCENTS),
		array_values($UTF8_UPPER_ACCENTS),
		$string
		);
		}

		return $string;
	}	
	
    /**
     * function reduStr($str):
     * Fonction permettant de reduire des texte à  une longeur spécifique
     * @$str : text à  reduire
    **/
    function reduStr($str,$lenght)
    {
        if( strlen($str) > $lenght )
        {
                $str = substr($str, 0, $lenght);
                $str = $str." ...";
        }
        return $str;
    }
	
    //Time ago
    function TimeAgo($datefrom,$dateto=-1)
    {
		global $webvars;
		
        // Defaults and assume if 0 is passed in that
        // its an error rather than the epoch

        if($datefrom<=0) { return $webvars["Timeago - A long time ago"]; }
        if($dateto==-1) { $dateto = time(); }

        // Calculate the difference in seconds betweeen
        // the two timestamps

        $difference = $dateto - $datefrom;

        // If difference is less than 60 seconds,
        // seconds is a good interval of choice

        if($difference < 60)
        {
            $interval = "s";
        }

        // If difference is between 60 seconds and
        // 60 minutes, minutes is a good interval
        elseif($difference >= 60 && $difference<60*60)
        {
        $interval = "n";
        }

        // If difference is between 1 hour and 24 hours
        // hours is a good interval
        elseif($difference >= 60*60 && $difference<60*60*24)
        {
            $interval = "h";
        }

        // If difference is between 1 day and 7 days
        // days is a good interval
        elseif($difference >= 60*60*24 && $difference<60*60*24*7)
        {
            $interval = "d";
        }

        // If difference is between 1 week and 30 days
        // weeks is a good interval
        elseif($difference >= 60*60*24*7 && $difference <
        60*60*24*30)
        {
            $interval = "ww";
        }

        // If difference is between 30 days and 365 days
        // months is a good interval, again, the same thing
        // applies, if the 29th February happens to exist
        // between your 2 dates, the function will return
        // the 'incorrect' value for a day
        elseif($difference >= 60*60*24*30 && $difference <
        60*60*24*365)
        {
            $interval = "m";
        }

        // If difference is greater than or equal to 365
        // days, return year. This will be incorrect if
        // for example, you call the function on the 28th April
        // 2008 passing in 29th April 2007. It will return
        // 1 year ago when in actual fact (yawn!) not quite
        // a year has gone by
        elseif($difference >= 60*60*24*365)
        {
            $interval = "y";
        }

        // Based on the interval, determine the
        // number of units between the two dates
        // From this point on, you would be hard
        // pushed telling the difference between
        // this function and DateDiff. If the $datediff
        // returned is 1, be sure to return the singular
        // of the unit, e.g. 'day' rather 'days'

        switch($interval)
        {
        case "m":
        $months_difference = floor($difference / 60 / 60 / 24 /
        29);
        while (mktime(date("H", $datefrom), date("i", $datefrom),
        date("s", $datefrom), date("n", $datefrom)+($months_difference),
        date("j", $dateto), date("Y", $datefrom)) < $dateto)
        {
            $months_difference++;
        }
        $datediff = $months_difference;

        // We need this in here because it is possible
        // to have an 'm' interval and a months
        // difference of 12 because we are using 29 days
        // in a month

        if($datediff==12)
        {
        $datediff--;
        }

        $res = ($datediff==1) ? "$datediff ".$webvars["Timeago - month ago"]."" : "$datediff ".$webvars["Timeago - month ago"];
        break;

        case "y":
        $datediff = floor($difference / 60 / 60 / 24 / 365);
        $res = ($datediff==1) ? "$datediff ".$webvars["Timeago - year ago"]."" : "$datediff ".$webvars["Timeago - years ago"];
        break;

        case "d":
        $datediff = floor($difference / 60 / 60 / 24);
        $res = ($datediff==1) ? "$datediff ".$webvars["Timeago - day ago"]."" : "$datediff ".$webvars["Timeago - days ago"];
        break;

        case "ww":
        $datediff = floor($difference / 60 / 60 / 24 / 7);
        $res = ($datediff==1) ? "$datediff ".$webvars["Timeago - week ago"]."" : "$datediff ".$webvars["Timeago - weeks ago"];
        break;

        case "h":
        $datediff = floor($difference / 60 / 60);
        $res = ($datediff==1) ? "$datediff ".$webvars["Timeago - hour ago"]."" : "$datediff ".$webvars["Timeago - hours ago"];
        break;

        case "n":
        $datediff = floor($difference / 60);
        $res = ($datediff==1) ? "$datediff ".$webvars["Timeago - minute ago"]."" : "$datediff ".$webvars["Timeago - minutes ago"]."";
        break;

        case "s":
        $datediff = $difference;
        $res = ($datediff==1) ? "$datediff ".$webvars["Timeago - second ago"] : "$datediff ".$webvars["Timeago - seconds ago"];
        break;
        }
        return $res;
      }

	/**
	 * extractExtensionFile($file)
	 * @Fonction permettant de séparer l'extension de la base du fichier
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
	 * @Fonction permettant de formater les seconds en durée hh:mm:ss
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
	  
}
?>