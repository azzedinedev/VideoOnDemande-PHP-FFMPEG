<?php
//+---------------------------------------------------------------------+
//|	class.thumb.php														|
//+---------------------------------------------------------------------+
//|	classe de création des images miniatures							|
//|	Developpement by 		:											|
//|		SAR Azzeddine: azzedinedev@gmail.com							|
//+---------------------------------------------------------------------+

class thumb
{
	var $img;
	var $quality = 72;
	var $pos_x = "RIGHT"; 	// Logo position {'LEFT','CENTER','RIGHT'}
	var $pos_y = "BOTTOM"; 	// Logo position {'TOP','MIDDLE','BOTTOM'}
	var $is_create_directories_path = true;

	//Initialisation of watermark
	var $watermark_v_position			= "center";						//vertical position 	: {'top',  'center', 'bottom' }
	var $watermark_h_position			= "center";						//horizontal position 	: {'left', 'center', 'right' }
	var $watermark_size					= "1";							//{larger, 1,.5 , .....}
	var $watermark_disp_width_max		= 150;							// used when displaying watermark choices
	var $watermark_disp_height_max		= 80;							// used when displaying watermark choices
	var $watermark_edgePadding			= 35;							// used when placing the watermark near an edge
	var $watermark_quality				= 100;							// used when generating the final image
	var $watermark_default_watermark	= "files/images/watermark.png";	// the default image to use if no watermark was chosen
	var $watermark_file 				= NULL;
  function thumb()
  {
      $this->is_create_directories_path = true;
  }
	
  function imageCreateFromX($file)
  {
     switch ($this->type) :
      case "JPEG"   :   return ImageCreateFromJPEG($file);          break;
      case "PNG"    :   return ImageCreateFromPNG($file);           break;
      case "GIF"    :   return ImageCreateFromGIF($file);           break;
      case "BMP"    :   return $this->imagecreatefrombmp($file);    break;
      default       :   $this->CreateErrorImage($this->type."Cannot find image!");
     endswitch;
  }	
	
  function imageCreateX($file,$saveFile="")
  {
     switch ($this->type):
      case "JPEG":  ImageJPEG($file,"",$this->quality);         break;
      case "PNG" :  imagealphablending($file, true); imagesavealpha($file, true); ImagePNG($file); break;
      case "GIF" :  ImageGIF($file);                            break;
      case "BMP" :  $this->imagebmp($file,"");
     endswitch;
     return true;
  }


  function imageSaveX($file,$saveFile="")
  {
     switch ($this->type):
      case "JPEG" : ImageJPEG($file,"$saveFile",$this->quality);                            break;
      case "PNG" :  imagealphablending($file, true); imagesavealpha($file, true); ImagePNG($file,"$saveFile"); break;
      case "GIF" :  ImageGIF($file,"$saveFile");                                            break;
      case "BMP" :  $this->imagebmp($file,"$saveFile");   break;     
     endswitch;
    return true;
  }	

   function imagebmp($image_resource,$file_path = ''){
		$this->image_resource = $image_resource;
		
		if(!$this->image_resource) die("cant not convert. image resource is null");
		$picture_width  = imagesx($this->image_resource);
		$picture_height = imagesy($this->image_resource);
		
		
		if(!imageistruecolor($this->image_resource)){
			$tmp_img_reource = imagecreatetruecolor($picture_width,$picture_height);
			imagecopy($tmp_img_reource,$this->image_resource, 0, 0, 0, 0, $picture_width, $picture_height);
			imagedestroy($this->image_resource);
			$this->image_resource = $tmp_img_reource;
			
		}
		
		
		if((int) $this->new_width >0 && (int) $this->new_height > 0){
			
			$image_resized = imagecreatetruecolor($this->new_width, $this->new_height); 
			imagecopyresampled($image_resized,$this->image_resource,0,0,0,0,$this->new_width,$this->new_height,$picture_width,$picture_height);
			imagedestroy($this->image_resource);
			$this->image_resource =  $image_resized;
		
		}
		
		$result = '';
		
		
		
		$biBPLine =  ((int) $this->new_width >0 &&(int)$this->new_height > 0) ? $this->new_width * 3 : $picture_width * 3;
		$biStride = ($biBPLine + 3) & ~3;
		$biSizeImage =  ((int) $this->new_width >0 &&(int)$this->new_height > 0) ? $biStride * $this->new_height : $biStride * $picture_height;
		$bfOffBits = 54;
		$bfSize = $bfOffBits + $biSizeImage;
		
		$result .= substr('BM', 0, 2);
		$result .= pack ('VvvV', $bfSize, 0, 0, $bfOffBits);
		$result .= ((int) $this->new_width >0 &&(int)$this->new_height > 0) ? pack ('VVVvvVVVVVV', 40, $this->new_width, $this->new_height, 1, 24, 0, $biSizeImage, 0, 0, 0, 0) : pack ('VVVvvVVVVVV', 40, $picture_width, $picture_height, 1, 24, 0, $biSizeImage, 0, 0, 0, 0);
		
		$numpad = $biStride - $biBPLine;
		
		$h = ((int) $this->new_width >0 &&(int)$this->new_height > 0) ? $this->new_height : $picture_height;
		$w = ((int) $this->new_width >0 &&(int)$this->new_height > 0) ? $this->new_width  : $picture_width;
		

		for ($y = $h - 1; $y >= 0; --$y) {
			for ($x = 0; $x < $w; ++$x) {
				$col = imagecolorat ($this->image_resource, $x, $y);
				$result .= substr(pack ('V', $col), 0, 3);
			}
			for ($i = 0; $i < $numpad; ++$i) {
				$result .= pack ('C', 0);
			}
        }
		
		
      if($file_path == ''){
      	echo $result;
      } else {
      	
      	$fp = fopen($file_path,"wb");
      	fwrite($fp,$result);
      	fclose($fp);
      	//=============
      }
      return ;	
	}
        

	function size_height($size=100)
	{
		//Define the height of the image
		  if($size > $this->height){$size = $this->height; }
    	$this->new_height=$size;
    	$this->new_width =  round(($this->new_height/$this->height)*$this->width);
			$this->x = 0;
		  $this->y = 0;
  }

	function size_width($size=100)
	{
		//Define the width of the image
		if($size > $this->width){$size = $this->width; }
		$this->new_width=$size;
		$this->new_height = round(($this->new_width/$this->width)*$this->height);
		$this->x = 0;
		$this->y = 0;
	}

	function size_auto($size=100)
	{
		//Define the height and width of the image as auto dimension
		if ($this->width >= $this->height) {
    		if($size > $this->width){$size = $this->width; }
		    $this->new_width=$size;
                    $this->new_height = round(($this->new_width/$this->width)*$this->height);
		} else {
                    if($size > $this->height){$size = $this->height; }
                    $this->new_height=$size;
                    $this->new_width =  round(($this->new_height/$this->height)*$this->width);
 		}
		$this->x = 0;
		$this->y = 0;
	}
	
	function size_crop($size=100)
	{
		//Crop the image to the size
		if ($this->width >= $this->height) { 		
		    $biggestSide = $this->height; 
		} else {	    	
		    $biggestSide = $this->width;
		}
		$cropPercent = 1; 
                $this->new_width   = round($biggestSide*$cropPercent); 
                $this->new_height  = round($biggestSide*$cropPercent);
		$this->x = round(($this->width-$this->new_width)/2);
                $this->y = round(($this->height-$this->new_height)/2);
		
		if($size > $this->width OR $size > $this->height)
		{
		   if($this->width  > $this->height){ 	 
			   $size = $this->height;			
			 }else{  
			   $size = $this->width;			
			 } 
		}
		$this->width = $this->new_width ;
		$this->height = $this->new_height; 
		$this->new_width = round($size);
		$this->new_height = round($size); 
	}
	
	function size_width_height($size_w=100,$size_h=100)
	{
		//Define the height and the width of the image
		if($size_w >= $this->width){$size_w = $this->width;}
		if($size_h >= $this->height){$size_h = $this->height;}
		
		$Correlation_main = $this->height/$this->width;
		$Correlation_new = $size_h/$size_w;
			
		if($Correlation_main < $Correlation_new)
		{
		   $tmp = ($Correlation_main/$Correlation_new);
		   $this->new_width   = round($this->width*$tmp); 
                    $this->new_height  = round($this->height);
		} else {	    	
                    $tmp = ($Correlation_new/$Correlation_main);
                    $this->new_width   = round($this->width); 
                    $this->new_height  = round($this->height*$tmp);
		}
		
		$this->x = round(($this->width-$this->new_width)/2);
                $this->y = round(($this->height-$this->new_height)/2);
	
		$this->width = $this->new_width ;
		$this->height = $this->new_height; 
		$this->new_width = round($size_w);
		$this->new_height = round($size_h); 
	}
	
	function show()
	{
		
		//Show the image in the webpage with header
		$this->newImage = ImageCreateTrueColor($this->new_width ,$this->new_height);
		$bg = imagecolortransparent($this->newImage);   
		//$bg=imagecolorallocate($newImage,255,255,255);
		imagefill($this->newImage, 0, 0, $bg ); 
		imagecopyresampled($this->newImage, $this->image, 0, 0, $this->x, $this->y, $this->new_width, $this->new_height, $this->width, $this->height);			
		//Add watermark
		if( $this->watermark_file != NULL ){
			$newImage = $this->add_watermark($this->newImage,$this->new_width,$this->new_height,$this->watermark_file);
		}else{
			$newImage = $this->newImage;
		}
		
		header("Content-type: image/".$this->type);
		$this->imageCreateX($newImage);
		imagedestroy($newImage);
		
	}

	function save_thumb($saveFile="")
	{
		
		//Show the image in the webpage with header
		$this->newImage = ImageCreateTrueColor($this->new_width ,$this->new_height);
		$bg = imagecolortransparent($this->newImage);   
		//$bg=imagecolorallocate($newImage,255,255,255);
		imagefill($this->newImage, 0, 0, $bg ); 
		imagecopyresampled($this->newImage, $this->image, 0, 0, $this->x, $this->y, $this->new_width, $this->new_height, $this->width, $this->height);			
		//Add watermark
		if( $this->watermark_file != NULL ){
			$newImage = $this->add_watermark($this->newImage,$this->new_width,$this->new_height,$this->watermark_file);
		}else{
			$newImage = $this->newImage;
		}
				
		$this->imageSaveX($newImage,$saveFile);
		
		//header("Content-type: image/".$this->type);
		//$this->imageCreateX($newImage);
		//imagedestroy($newImage);		
		
	}
/*	
	function save_thumb($saveFile="")
	{
		//save the image in file
		if (empty($saveFile)) $saveFile=strtolower("./thumb.".$this->type);
		$this->newImage = ImageCreateTrueColor($this->new_width ,$this->new_height);
		imagecopyresampled($newImage, $this->image, 0, 0, $this->x, $this->y, $this->new_width, $this->new_height, $this->width, $this->height);		
		//Add watermark
		if( $this->watermark_file != NULL ){
			$newImage = $this->add_watermark($this->newImage,$this->new_width,$this->new_height,$this->watermark_file);
		}else{
			$newImage = $this->newImage;
		}
		
		header("Content-type: image/".$this->type);
		$this->imageSaveX($newImage,$saveFile);
	}
*/
	function add_watermark($srcImg,$srcWidth,$srcHeight,$watermark){
		$v_position 		= $this->watermark_v_position;
		$h_position 		= $this->watermark_h_position;
		$wm_size			= $this->watermark_size;		
		//others
		$disp_width_max		= $this->watermark_disp_width_max;
		$disp_height_max	= $this->watermark_disp_height_max;
		$edgePadding		= $this->watermark_edgePadding;
		$quality			= $this->watermark_quality;
		$default_watermark 	= $this->watermark_default_watermark;
	
				// be sure that the other options we need have some kind of value
				if(!isset($v_position)) 	$v_position	= 'center';
				if(!isset($h_position)) 	$h_position	= 'center';
				if(!isset($wm_size)) 		$wm_size	= '1';
				if(!isset($watermark))		$watermark	= $default_watermark;
			
				// Original file
					// it was a JPEG or PNG image, so we're OK so far
					$origWidth 	= $srcWidth; 
					$origHeight = $srcHeight; 
	
					$waterMarkInfo = getimagesize($watermark);
					$waterMarkWidth = $waterMarkInfo[0];
					$waterMarkHeight = $waterMarkInfo[1];
			
					// watermark sizing info
					if($wm_size=='larger'){
						$placementX=0;
						$placementY=0;
						$h_position='center';
						$v_position='center';
						$waterMarkDestWidth=$waterMarkWidth;
						$waterMarkDestHeight=$waterMarkHeight;
						
						// both of the watermark dimensions need to be 5% more than the original image...
						// adjust width first.
						if($waterMarkWidth > $origWidth*1.05 && $waterMarkHeight > $origHeight*1.05){
							// both are already larger than the original by at least 5%...
							// we need to make the watermark *smaller* for this one.
							
							// where is the largest difference?
							$wdiff=$waterMarkDestWidth - $origWidth;
							$hdiff=$waterMarkDestHeight - $origHeight;
							if($wdiff > $hdiff){
								// the width has the largest difference - get percentage
								$sizer=($wdiff/$waterMarkDestWidth)-0.05;
							}else{
								$sizer=($hdiff/$waterMarkDestHeight)-0.05;
							}
							$waterMarkDestWidth-=$waterMarkDestWidth * $sizer;
							$waterMarkDestHeight-=$waterMarkDestHeight * $sizer;
						}else{
							// the watermark will need to be enlarged for this one
							
							// where is the largest difference?
							$wdiff=$origWidth - $waterMarkDestWidth;
							$hdiff=$origHeight - $waterMarkDestHeight;
							if($wdiff > $hdiff){
								// the width has the largest difference - get percentage
								$sizer=($wdiff/$waterMarkDestWidth)+0.05;
							}else{
								$sizer=($hdiff/$waterMarkDestHeight)+0.05;
							}
							$waterMarkDestWidth+=$waterMarkDestWidth * $sizer;
							$waterMarkDestHeight+=$waterMarkDestHeight * $sizer;
						}
					}else{
						$waterMarkDestWidth=round($origWidth * floatval($wm_size));
						$waterMarkDestHeight=round($origHeight * floatval($wm_size));
						if($wm_size==1){
							$waterMarkDestWidth-=2*$edgePadding;
							$waterMarkDestHeight-=2*$edgePadding;
						}
					}
	
					// OK, we have what size we want the watermark to be, time to scale the watermark image										
					$resize_png_img 		= $watermark;
					$resize_png_newWidth 	= $waterMarkDestWidth;
					$resize_png_newHeight 	= $waterMarkDestHeight;				
					
					$resize_png_srcImage=imagecreatefrompng($resize_png_img);
	
					$resize_png_srcWidth	= imagesx($resize_png_srcImage);
					$resize_png_srcHeight	= imagesy($resize_png_srcImage);
					$resize_png_percentage	= (double)$resize_png_newWidth/$resize_png_srcWidth;
					$resize_png_destHeight	= round($resize_png_srcHeight*$resize_png_percentage)+1;
					$resize_png_destWidth	= round($resize_png_srcWidth*$resize_png_percentage)+1;
					if($resize_png_destHeight > $resize_png_newHeight){
						// if the width produces a height bigger than we want, calculate based on height
						$resize_png_percentage	= (double)$resize_png_newHeight/$resize_png_srcHeight;
						$resize_png_destHeight	= round($resize_png_srcHeight*$resize_png_percentage)+1;
						$resize_png_destWidth	= round($resize_png_srcWidth*$resize_png_percentage)+1;
					}
					$resize_png_destImage	= imagecreatetruecolor($resize_png_destWidth-1,$resize_png_destHeight-1);
					
					imagealphablending($resize_png_destImage,FALSE);
					imagesavealpha($resize_png_destImage,TRUE);
					imagecopyresampled($resize_png_destImage,$resize_png_srcImage,0,0,0,0,$resize_png_destWidth,$resize_png_destHeight,$resize_png_srcWidth,$resize_png_srcHeight);
										
					// get the size info for this watermark.					
					$waterMarkDestWidth	= $resize_png_destWidth;
					$waterMarkDestHeight= $resize_png_destHeight;
	
					$differenceX = $origWidth - $waterMarkDestWidth;
					$differenceY = $origHeight - $waterMarkDestHeight;
	
					// where to place the watermark?
					switch($h_position){
						// find the X coord for placement
						case 'left':
							$placementX = $edgePadding;
							break;
						case 'center':
							$placementX =  round($differenceX / 2);
							break;
						case 'right':
							$placementX = $origWidth - $waterMarkDestWidth - $edgePadding;
							break;
					}
	
					switch($v_position){
						// find the Y coord for placement
						case 'top':
							$placementY = $edgePadding;
							break;
						case 'center':
							$placementY =  round($differenceY / 2);
							break;
						case 'bottom':
							$placementY = $origHeight - $waterMarkDestHeight - $edgePadding;
							break;
					}
		   
					$resultImage = $srcImg;
						
					imagealphablending($resultImage, TRUE);
					
					$finalWaterMarkImage = $resize_png_destImage;
					$finalWaterMarkWidth = $resize_png_destWidth-1;
					$finalWaterMarkHeight = $resize_png_destHeight-1;					
	
					
					imagecopy($resultImage,
							  $finalWaterMarkImage,
							  $placementX,
							  $placementY,
							  0,
							  0,
							  $finalWaterMarkWidth,
							  $finalWaterMarkHeight
					);
	
					if( $this->type == "PNG" ){
						imagealphablending($resultImage,FALSE);
						imagesavealpha($resultImage,TRUE);
					}
					
					imagedestroy($finalWaterMarkImage);
					
					return $resultImage;
					//imagedestroy($resultImage);				
	}
	
	//For exrternal file to add watermark
	function watermark_exec($original,$target,$watermark,$v_position,$h_position,$wm_size){
		$disp_width_max		=150;                   // used when displaying watermark choices
		$disp_height_max	=80;                    // used when displaying watermark choices
		$edgePadding		=15;                    // used when placing the watermark near an edge
		$quality			=100;            		// used when generating the final image
		$default_watermark	='Sample-trans.png';  	// the default image to use if no watermark was chosen
	
	
				// be sure that the other options we need have some kind of value
				if(!isset($v_position)) 	$v_position	= 'center';
				if(!isset($h_position)) 	$h_position	= 'center';
				if(!isset($wm_size)) 		$wm_size	= '1';
				if(!isset($watermark))		$watermark	= $default_watermark;
			
				// Original file
				$size=getimagesize($original);
				if($size[2]==2 || $size[2]==3){
					// it was a JPEG or PNG image, so we're OK so far
					
					$wmTarget=$watermark.'.tmp';
	
					$origInfo = getimagesize($original); 
					$origWidth = $origInfo[0]; 
					$origHeight = $origInfo[1]; 
	
					$waterMarkInfo = getimagesize($watermark);
					$waterMarkWidth = $waterMarkInfo[0];
					$waterMarkHeight = $waterMarkInfo[1];
			
					// watermark sizing info
					if($wm_size=='larger'){
						$placementX=0;
						$placementY=0;
						$h_position='center';
						$v_position='center';
						$waterMarkDestWidth=$waterMarkWidth;
						$waterMarkDestHeight=$waterMarkHeight;
						
						// both of the watermark dimensions need to be 5% more than the original image...
						// adjust width first.
						if($waterMarkWidth > $origWidth*1.05 && $waterMarkHeight > $origHeight*1.05){
							// both are already larger than the original by at least 5%...
							// we need to make the watermark *smaller* for this one.
							
							// where is the largest difference?
							$wdiff=$waterMarkDestWidth - $origWidth;
							$hdiff=$waterMarkDestHeight - $origHeight;
							if($wdiff > $hdiff){
								// the width has the largest difference - get percentage
								$sizer=($wdiff/$waterMarkDestWidth)-0.05;
							}else{
								$sizer=($hdiff/$waterMarkDestHeight)-0.05;
							}
							$waterMarkDestWidth-=$waterMarkDestWidth * $sizer;
							$waterMarkDestHeight-=$waterMarkDestHeight * $sizer;
						}else{
							// the watermark will need to be enlarged for this one
							
							// where is the largest difference?
							$wdiff=$origWidth - $waterMarkDestWidth;
							$hdiff=$origHeight - $waterMarkDestHeight;
							if($wdiff > $hdiff){
								// the width has the largest difference - get percentage
								$sizer=($wdiff/$waterMarkDestWidth)+0.05;
							}else{
								$sizer=($hdiff/$waterMarkDestHeight)+0.05;
							}
							$waterMarkDestWidth+=$waterMarkDestWidth * $sizer;
							$waterMarkDestHeight+=$waterMarkDestHeight * $sizer;
						}
					}else{
						$waterMarkDestWidth=round($origWidth * floatval($wm_size));
						$waterMarkDestHeight=round($origHeight * floatval($wm_size));
						if($wm_size==1){
							$waterMarkDestWidth-=2*$edgePadding;
							$waterMarkDestHeight-=2*$edgePadding;
						}
					}
	
					// OK, we have what size we want the watermark to be, time to scale the watermark image										
					$resize_png_img 		= $watermark;
					$resize_png_newWidth 	= $waterMarkDestWidth;
					$resize_png_newHeight 	= $waterMarkDestHeight;					
					
					$resize_png_srcImage=imagecreatefrompng($resize_png_img);
	
					$resize_png_srcWidth	= imagesx($resize_png_srcImage);
					$resize_png_srcHeight	= imagesy($resize_png_srcImage);
					$resize_png_percentage	= (double)$resize_png_newWidth/$resize_png_srcWidth;
					$resize_png_destHeight	= round($resize_png_srcHeight*$resize_png_percentage)+1;
					$resize_png_destWidth	= round($resize_png_srcWidth*$resize_png_percentage)+1;
					if($resize_png_destHeight > $resize_png_newHeight){
						// if the width produces a height bigger than we want, calculate based on height
						$resize_png_percentage	= (double)$resize_png_newHeight/$resize_png_srcHeight;
						$resize_png_destHeight	= round($resize_png_srcHeight*$resize_png_percentage)+1;
						$resize_png_destWidth	= round($resize_png_srcWidth*$resize_png_percentage)+1;
					}
					$resize_png_destImage	= imagecreatetruecolor($resize_png_destWidth-1,$resize_png_destHeight-1);
					
					imagealphablending($resize_png_destImage,FALSE);
					imagesavealpha($resize_png_destImage,TRUE);
					imagecopyresampled($resize_png_destImage,$resize_png_srcImage,0,0,0,0,$resize_png_destWidth,$resize_png_destHeight,$resize_png_srcWidth,$resize_png_srcHeight);
										
					// get the size info for this watermark.					
					$waterMarkDestWidth	= $resize_png_destWidth;
					$waterMarkDestHeight= $resize_png_destHeight;
	
					$differenceX = $origWidth - $waterMarkDestWidth;
					$differenceY = $origHeight - $waterMarkDestHeight;
	
					// where to place the watermark?
					switch($h_position){
						// find the X coord for placement
						case 'left':
							$placementX = $edgePadding;
							break;
						case 'center':
							$placementX =  round($differenceX / 2);
							break;
						case 'right':
							$placementX = $origWidth - $waterMarkDestWidth - $edgePadding;
							break;
					}
	
					switch($v_position){
						// find the Y coord for placement
						case 'top':
							$placementY = $edgePadding;
							break;
						case 'center':
							$placementY =  round($differenceY / 2);
							break;
						case 'bottom':
							$placementY = $origHeight - $waterMarkDestHeight - $edgePadding;
							break;
					}
		   
					if($size[2]==3)
						$resultImage = imagecreatefrompng($original);
					else
						$resultImage = imagecreatefromjpeg($original);
					imagealphablending($resultImage, TRUE);
					
					$finalWaterMarkImage = $resize_png_destImage;
					$finalWaterMarkWidth = $resize_png_destWidth-1;
					$finalWaterMarkHeight = $resize_png_destHeight-1;					
	
					
					imagecopy($resultImage,
							  $finalWaterMarkImage,
							  $placementX,
							  $placementY,
							  0,
							  0,
							  $finalWaterMarkWidth,
							  $finalWaterMarkHeight
					);
	
					if($size[2]==3){
						imagealphablending($resultImage,FALSE);
						imagesavealpha($resultImage,TRUE);						
						header("Content-type: image/png");
						imagepng($resultImage,$target,$quality);
					}else{
						header("Content-type: image/jpeg");
						imagejpeg($resultImage,$target,$quality); 
					}
	
					imagedestroy($resultImage);
					imagedestroy($finalWaterMarkImage);
	
					// display resulting image for download
				}
	}
	
function add_logo($logo = NULL)
	{

		//Add logo to the image
		if(!file_exists($logo)){
		   $this->CreateErrorImage('logo not found!');
		 }
		$this->newImage = ImageCreateTrueColor($this->new_width ,$this->new_height);
		$bg = imagecolortransparent($this->newImage);
		imagefill($this->newImage, 0, 0, $bg );
		imagecopyresampled($this->newImage, $this->image, 0, 0, $this->x, $this->y, $this->new_width, $this->new_height, $this->width, $this->height);
		list($this->logo_width, $this->logo_height, $this->logo_type) = getimagesize($logo);
		 switch ($this->logo_type) :
			  case "2" :    $this->logo = ImageCreateFromJPEG($logo);	break;
			  case "3" :    $this->logo = ImageCreateFromPNG($logo);   	break;
			  case "1" :    $this->logo = ImageCreateFromGIF($logo); 	break;
			  default : 
		 endswitch;
		 
		$this->wt_x = $this->calc_pos_x($this->pos_x);
		$this->wt_y = $this->calc_pos_y($this->pos_y); 		
		imagecopy($this->newImage, $this->logo, $this->wt_x, $this->wt_y, 0, 0, $this->logo_width, $this->logo_height);
		header("Content-type: image/".$this->type);
		$this->imageCreateX($this->newImage);
		imagedestroy($this->newImage);
	}	
	

	function CreateErrorImage($text)
	{
		//$text = "Competition.dz";
		$im = imagecreate(110, 30);
		$bg = imagecolorallocate($im, 255, 255, 255);
		$textcolor = imagecolorallocate($im, 255, 0, 0);
		imagestring($im, 2, 0, 0, $text, $textcolor);
		header("Content-type: image/png");
		imagepng($im);
		die();
	}

        function imagecreatefrombmp($p_sFile)
        {
            $file    =    fopen($p_sFile,"rb");
            $read    =    fread($file,10);
            while(!feof($file)&&($read<>""))
                $read    .=    fread($file,1024);
            $temp    =    unpack("H*",$read);
            $hex    =    $temp[1];
            $header    =    substr($hex,0,108);
            if (substr($header,0,4)=="424d")
            {
                $header_parts    =    str_split($header,2);
                $width            =    hexdec($header_parts[19].$header_parts[18]);
                $height            =    hexdec($header_parts[23].$header_parts[22]);
                unset($header_parts);
            }
            $x                =    0;
            $y                =    1;
            $image            =    imagecreatetruecolor($width,$height);
            $body            =    substr($hex,108);
            $body_size        =    (strlen($body)/2);
            $header_size    =    ($width*$height);
            $usePadding        =    ($body_size>($header_size*3)+4);
            for ($i=0;$i<$body_size;$i+=3)
            {
                if ($x>=$width)
                {
                    if ($usePadding)
                        $i    +=    $width%4;
                    $x    =    0;
                    $y++;
                    if ($y>$height)
                        break;
                }
                $i_pos    =    $i*2;
                $r        =    hexdec($body[$i_pos+4].$body[$i_pos+5]);
                $g        =    hexdec($body[$i_pos+2].$body[$i_pos+3]);
                $b        =    hexdec($body[$i_pos].$body[$i_pos+1]);
                $color    =    imagecolorallocate($image,$r,$g,$b);
                imagesetpixel($image,$x,$height-$y,$color);
                $x++;
            }
            unset($body);
            return $image;
        }
	
	
function calc_pos_x($position_x)
    {
	//Calculate the X position of the logo
	 $x = 0;
     switch($position_x) :
		  case 'LEFT':    $x = 0;     break;
		  case 'CENTER':  $x = @$this->new_width/2 - @$this->logo_width/2;  break;
		  case 'RIGHT':   $x = @$this->new_width - @$this->logo_width;      break;
		  default:        $x = 0;
	endswitch;
    return $x;
  }

  function calc_pos_y($position_y)
  {
	//Calculate the Y position of the logo
	 $y = 0;
	 switch($position_y) :
		  case 'TOP':    $y = 0;      break;
		  case 'MIDDLE': $y = @$this->new_height/2 - @$this->logo_height/2; break;
		  case 'BOTTOM': $y = @$this->new_height - @$this->logo_height;     break;
		  default:       $y = 0;
	endswitch;	
    return $y;
  }
  
	/**
	 * process($img,$width,$height,$path= NULL,$quality=80,$WatermarkFile = NULL)
	 * @param string $img		: Image source
	 * @param string $width		: Largeur de l'image
	 * @param string $height	: Longueur de l'image
	 * @param string $path		: Sauvegarder  l'image dans cette chaine
	 * @param string $quality	: Qualit� d'image jpeg
	 * @param string $WatermarkFile : Image du watermark
	 * Fonction de creation d'une image miniature avec possiblit� de waermark
	 */
	function process($img,$width,$height,$path= NULL,$quality=80,$WatermarkFile = NULL){            
                
		if(!file_exists($img)){
	//	   $this->CreateErrorImage('file not found!');
		}else{						
                         list($width_o, $height_o, $type) = getimagesize($img);
						 
 						if( $width < ( ($this->watermark_edgePadding * 2) + 80) ){						
							$WatermarkFile = "";	
						}
						 
						 
                         $types = array(1=>'GIF',2=>'JPEG',3=>'PNG',4=>'SWF',5=>'PSD',6=>'BMP',7=>'TIFF(intel byte order)',8=>'TIFF(motorola byte order)',9 =>'JPC',10 =>'JP2',11 =>'JPX',12 =>'JB2',13 => 'SWC',14 => 'IFF',15 => 'WBMP',16 => 'XBM');
                         if( !in_array($type,array("1","2","3","6")) ){
//                           $this->CreateErrorImage($type.'Error format file!');
                         }else{
                            $this->width = $width_o;
                            $this->height = $height_o;
                            
                            $this->width_new = $width;
                            $this->height_new = $height;
                            $this->type = $types[$type];
                            $this->image = $this->imageCreateFromX($img);

                            $this->image_extension = $this->extractExtensionFile($file);
                            
                            if( $quality != "" ){
                                $this->quality = $quality;
                            }
                            
                            //--------------------------------------------
                            //Creation of thumb
                            if( (intval($this->width_new) != 0) and (intval($this->height_new) != 0) ){
                                $this->size_width_height($this->width_new,$this->height_new);
                            }else{
                                if( (intval($this->width_new) != 0) and (intval($this->height_new) == 0) ){
                                    $size = $this->height_new = $this->width_new;                                     
                                    $this->size_auto($size);
                                    $this->size_crop($this->width_new);
                                }else{        
                                    if( (intval($this->width_new) == 0) and (intval($this->height_new) != 0) ){
                                        $size = $this->width_new = $this->height_new;
                                        $this->size_auto($size);
                                        $this->size_crop($this->width_new);
                                    }else{
                                       // $this->CreateErrorImage('Size not found!');
                                    }
                                }   
                            }                           
                                        
                            if( ( $WatermarkFile != "" ) and ( file_exists($WatermarkFile) ) ){
								//$this->add_logo($WatermarkFile); 
								$this->watermark_file = $WatermarkFile;
                            }
                            
                            
                            if( !($path) ){
                                $this->show();
                            }else{
                                    if( $this->is_create_directories_path == true){
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
                                //Save the image in : $path
                                $this->save_thumb($path);
                            }                                                                
                         }
		}

	}
        
	/**
	 *return_path($img,$width,$height,$path,$quality=80,$WatermarkFile = NULL)
	 * @param string $img		: Image source
	 * @param string $width		: Largeur de l'image
	 * @param string $height	: Longueur de l'image
	 * @param string $path		: Sauvegarder  l'image dans cette chaine
	 * @param string $quality	: Qualit� d'image jpeg
	 * @param string $WatermarkFile : Image du watermark
	 * Fonction de retour du chemin de l'image miniature avec r�ation en cas de non existance
	 */
	function return_path($img,$width,$height,$path,$quality=80,$WatermarkFile = NULL){	
                $to_process = false;
                
            if( ( !file_exists($path) and ($path != "") ) or ($path == "") ){
			    if( empty($width) or empty($height) ){
                    //Si la largeuer ou la hauteur ne sont spesifie => redefinir les tailles pour croper l'image
                    list($width_orig, $height_orig) = getimagesize($img);
                    if( empty($width) and empty($height) ){
                        $height = $height_orig;
                        $width  = $width_orig;
                    }else{
                        if( empty($width) ){                        
                            $ratio_orig = $width_orig/$height_orig;
                            $width = round($ratio_orig * $height);
                        }else{
                            $ratio_orig = $height_orig/$width_orig;
                            $height = round($ratio_orig * $width);
                            
                        }       
                    }
                    $to_process = true;
                }else{
                    $to_process = true;
                }	                
                
                if( $to_process == true ){
        /*            if( file_exists($path) and ($path != "") ){
                            //On recr�era la vignette si les tailles sont difirentes
                            $sizeWH 	= GetImageSize($path);
                            $sizeW		= $sizeWH[0];
                            $sizeH		= $sizeWH[1];
                            if( ( $sizeW != $width ) or ( $sizeH != $height ) ){
                                $this->process($img,$width,$height,$path,$quality,$WatermarkFile);
                            }

                    }else{
						*/
                            //On recréera la vignette si le fichier n'existe pas
                            $this->process($img,$width,$height,$path,$quality,$WatermarkFile);
                    //}
                }
		
			}
		return $path;
	}

        
	/**
	 * return_tag($img,$width,$height,$path,$quality=80,$WatermarkFile = NULL)
	 * @param string $img		: Image source
	 * @param string $width		: Largeur de l'image
	 * @param string $height	: Longueur de l'image
	 * @param string $path		: Sauvegarder  l'image dans cette chaine
	 * @param string $quality	: Qualit� d'image jpeg
	 * @param string $WatermarkFile : Image du watermark
	 * Fonction de retour du chemin de l'image miniature avec r�ation en cas de non existance
	 */
	function return_tag($img,$width,$height,$path,$quality=80,$WatermarkFile = NULL){
                $to_process = false;
                
                if( empty($width) or empty($height) ){
                    //Si la largeuer ou la hauteur ne sont spesifie => redefinir les tailles pour croper l'image
                    list($width_orig, $height_orig) = getimagesize($img);
                    if( empty($width) and empty($height) ){
                        $height = $height_orig;
                        $width  = $width_orig;
                    }else{
                        if( empty($width) ){                        
                            $ratio_orig = $width_orig/$height_orig;
                            $width = round($ratio_orig * $height);
                        }else{
                            $ratio_orig = $height_orig/$width_orig;
                            $height = round($ratio_orig * $width);
                            
                        }       
                    }
                    $to_process = true;
                }else{
                    $to_process = true;
                }	                
                
                if( $to_process == true ){
                    if( file_exists($path) ){
                            //On recr�era la vignette si les tailles sont difirentes
                            $sizeWH 	= GetImageSize($path);
                            $sizeW		= $sizeWH[0];
                            $sizeH		= $sizeWH[1];
                            if( ( $sizeW != $width ) or ( $sizeH != $height ) ){
                                $this->process($img,$width,$height,$path,$quality,$WatermarkFile);
                            }

                    }else{
                            //On recr�era la vignette si le fichier n'existe pas
                            $this->process($img,$width,$height,$path,$quality,$WatermarkFile);
                    }
                }
		
                $tag = '<img src="'.$path.'" width="'.$width.'" height="'.$height.'"/>';
                
		return $tag;
	}
        
	/**
	 * image_from_text($text,$path,$width,$height)
	 * @param string $text		: Text � transformer dans l'image
	 * @param string $path		: Sauvegarder  l'image dans cette chaine
	 * Fonction de transformation d'un text en image en retournat le chemin $path ou envoyer l'aper�u de l'image si $path est vide
	 */
	function image_from_text($text,$path,$width=150,$height=20){
        if( !($path) ){
            header("Content-type: image/png");
        	$im 	= 	@imagecreate($width, $height) or die("Cannot Initialize new GD image stream");
        	$background_color 	= 	imagecolorallocate($im,  190, 190, 190);//couleur du background ici gris
        	$text_color 		= 	imagecolorallocate($im, 0, 0, 0);//couleur du text ici gris
        	imagestring($im, 1, 5, 5,  $text, $text_color);//construire l'image
        	imagepng($im);//afficher l'image
        	imagedestroy($im);
        }else{
        	//Sauvgarder la vignette dans : $path
            $im 	= 	@imagecreate($width, $height) or die("Cannot Initialize new GD image stream");
        	$background_color 	= 	imagecolorallocate($im,  190, 190, 190);//couleur du background ici gris
        	$text_color 		= 	imagecolorallocate($im, 0, 0, 0);//couleur du text ici gris
        	imagestring($im, 1, 5, 5,  $text, $text_color);//construire l'image
            imagejpeg($im,$path,"80");

            return $path;
        }

	}
	
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
		
}

?>