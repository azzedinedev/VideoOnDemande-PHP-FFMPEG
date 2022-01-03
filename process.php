<?php

//Chemin vers l'executable du FFMPEG
$path_ffmpeg = "C:\\ffmpg\\ffmpeg.exe";

//Chemins
$path_videos    = "C:\\Vod-To-Box\\";  //Chemin des videos
$path_images    = "C:\\wamp\\www\\ffmpg\\vignettes/n/";                 //Chemin des images crées par FFMPEG
$path_vig_small = "C:\\wamp\\www\\ffmpg\\vignettes/s/";                 //Chemin des images vignettes en petit format cr�es par GD suivant les tail de la BOX
$path_vig_big   = "C:\\wamp\\www\\ffmpg\\vignettes/b/";                 //Chemin des images vignettes en grand format cr�es par GD suivant les tail de la BOX

//Objet de creation de vignettes
$path_thumb	= "thumb/class.thumb.php";
include($path_thumb);
$thumb 	= new thumb();

function treat_file($path){
    //Initilaisation
    global $path_ffmpeg;
    global $thumb;
    global $path_videos;
    global $path_images;
    global $path_vig_small;
    global $path_vig_big;
    $return = "";
    
    if ($handle = opendir($path)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {            
                //dossier parent
                $parent = str_replace($path_videos, "", $path);
                $parent = str_replace("\\", "/", $parent);
                
                if( is_dir($path.$entry) ){
                    //Dossier
                    //$path_videos.= $entry."\\";
                    //Creation du dossier dans les chemin de vignettes                    
                    if( !is_dir($path_images.$parent.$entry."/") ){
                        echo "Creation du repertoire : ".$path_images."<b>".$parent.$entry."/</b><br>";
                        mkdir($path_images.$parent.$entry."/",0777);                    
                    }
                    if( !is_dir($path_vig_small.$parent.$entry."/") ){
                        echo "Creation du repertoire : ".$path_vig_small."<b>".$parent.$entry."/</b><br>";                    
                        mkdir($path_vig_small.$parent.$entry."/",0777);
                    }
                    if( !is_dir($path_vig_big.$parent.$entry."/") ){
                        echo "Creation du repertoire : ".$path_vig_big."<b>".$parent.$entry."/</b><br>";
                        mkdir($path_vig_big.$parent.$entry."/",0777);
                    }
                    //Retourner le dossier pour le traitement
                    $return = $path.$entry."/";
                    
                    treat_file($return);
                }else{
                    //Fichier
                    echo "Traitement du fichier : ".$path."<b>".$entry."</b><br>";
                    //extension
                    $base_file = $thumb->extractExtensionFile($entry);		
                    $extension = $base_file["1"];
                    $array_ext = array("avi","mpeg","mp4","wmv","mpg","flv");
                    if(in_array($extension, $array_ext)){
                        
                        //Fichier video valide
                        //Executer la creation des vignettes à partir de la vidéo                    
                        $exec = exec($path_ffmpeg." -i ".$path.$entry." -an -ss 00:00:03 -r 1 -vframes 1 -f mjpeg -y ".$path_images.$parent.$base_file[0].".jpg");
                        echo "Creation de l'image a partir du FFMPEG<br>";
                        
                        echo $path_ffmpeg." -i ".$path.$entry." -an -ss 00:00:03 -r 1 -vframes 1 -f mjpeg -y ".$path_images.$parent.$base_file[0].".jpg<hr>";
                        //echo end(explode(" ", $exec ));
                        //echo "<hr>";        

                        //Creation des vignettes
                        echo "Creation de la grande vignette l'image<br>";
                        $thumb->return_path($path_images.$parent.$base_file[0].".jpg",140,200,$path_vig_big.$parent.$base_file[0].".jpg",80,NULL);
                        echo "Creation de la petite vignette l'image<br>";
                        $thumb->return_path($path_images.$parent.$base_file[0].".jpg",74,100,$path_vig_small.$parent.$base_file[0].".jpg",80,NULL);                        
                    }
                }            
            }
        }
        closedir($handle);   
    }
    return $return;
}

echo "Debut de Creation des images<hr>";

if( ($path_videos != "") and (is_dir($path_videos)) ){
    
    echo 'Traitement du repertoire : <b>'.$path_videos.'</b><br>';
    $path_videos = treat_file($path_videos);    
    
}

echo "Fin de Creation des images<hr>";
?>