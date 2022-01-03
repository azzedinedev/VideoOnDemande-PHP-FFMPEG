<?php
$timestamp = $_POST['album_timestamp'];
$token = md5('unique_salt' . $timestamp); 
$_SESSION["token"]	= $token;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="css/style.css" rel="stylesheet" type="text/css">
<link href="css/form.css" rel="stylesheet" type="text/css">
<script src="uploadify/jquery.1.7.1.min.js" type="text/javascript"></script>
<script src="uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
<script src="includes/tagsinput/jquery.tagsinput.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="includes/tagsinput/jquery.tagsinput.css">
<link rel="stylesheet" type="text/css" href="uploadify/uploadify.css">

<script type="text/javascript">
$(function(){
	$('#form_keywords').tagsInput({
	   'height':'100px',
	   'width':'388px',
	   'interactive':true,
	   'defaultText':'Tags',
	   'removeWithBackspace' : true,
	   'minChars' : 0,
	   'maxChars' : 0,
	   'placeholderColor' : '#888'
	});
});


</script>

<body>
  <div id="header">
        <div id="inner-header">
            <a id="logo-top" href="#"></a>
            <h1>Gestionnaire de VOD</h1>    
        </div>    
    </div>
        
    <div id="container">
        <div id="inner-container">
            <div id="description_head">
                <h2 class="title">Chargez vos vidéos</h2>
                <div class="note">Veuillez spécifier la vidéo dont voulez-vous la charger dans notre système. Cette vidéo doit être décrit par un <abbr>titre</abbr>, <abbr>une description</abbr> et <abbr>des mots clefs</abbr> séparés par des virgules dont le nombre maximal des caractères autorisés est 255 caractères.</div>
            </div>
            <form>
            	<div class="leftblok">
                    
                    <p>
                    <input id="form_title" placeholder="Nom de la vidéo" class="newInput" type="text" value="">
                    </p>
                    
                    <p>
                    <input id="form_duration" placeholder="Durée" class="newInput" value="" type="number" min="0" max="500" style="padding-right:80px;">
                    <span class="tag_input">en minutes</span>
                    </p>
                    <p>
                    <input id="form_director" placeholder="Réalisateur" class="newInput" type="text" value="">
                    </p>
                    <p>
                    <input id="form_year" placeholder="Année de production" class="newInput" type="text" value="">
                    </p>
                    
                    <p>
                    <select name="service" id="form_service" class="newInput">
                        <option value="0">==choix du service==</option>
                        <option value="1">Films</option>
                        <option value="2">Islamiette</option>
                        <option value="5">Replay</option>
                    </select>
					</p>
                    
                    <p>
                    <select name="rubrique" id="form_rubrique" class="newInput" >
                        <option value="0">==Choix de la rubrique==
                        </option>
                        <option value="39">Comedie</option>
                        <option value="38">Aventure</option>
                        <option value="36">Films-Arabes</option>
                        <option value="37">Animation</option>
                        <option value="35">Action</option>
                        <option value="40">Drama</option>
                        <option value="43">Espionnage</option>
                        <option value="44">Fantastique</option>
                        <option value="45">Historique</option>
                        <option value="46">Horreur</option>
                        <option value="47">Policier</option>
                        <option value="48">Romance</option>
                        <option value="49">Science-fiction</option>
                        <option value="50">Thriller</option>
                        <option value="51">Western</option>
                    </select>
					</p>
                    
                    <p>
                    <select name="genre" id="form_genre" class="newInput" >
                        <option value="0">==Choix du genre==</option>
                        <option value="1">Action</option>
                        <option value="2">Animation</option>
                        <option value="3">Arts Martiaux</option>
                        <option value="4">Aventure</option>
                        <option value="5">e-learning</option>
                        <option value="6">News</option>
                        <option value="7">Comédie</option>
                        <option value="8">Comédie dramatique</option>
                        <option value="9">Comédie musicale</option>
                        <option value="10">Divers</option>
                        <option value="11">Documentaire</option>
                        <option value="12">Drame</option>
                        <option value="13">Epouvante-horreur</option>
                        <option value="14">Espionnage</option>
                        <option value="15">Kids &amp; Family</option>
                        <option value="16">Fantastique</option>
                        <option value="17">Guerre</option>
                        <option value="18">Historique</option>
                        <option value="19">Musical</option>
                        <option value="20">Policier</option>
                        <option value="21">Romance</option>
                        <option value="22">Science fiction</option>
                        <option value="23">Sport event</option>
                        <option value="24">Thriller</option>
                        <option value="25">Western</option>
                        <option value="26">Islamiette</option>
                        <option value="27">Guerre</option>
                        <option value="28">Fantastique</option>
                        <option value="29">Arabe</option>
                    </select>
                    </p>
                    
                    
                    <p>
                    <input id="form_keywords" placeholder="Mots-clef" class="newInput" type="text" value="">
                    </p>
                                            
                    <textarea id="form_description" placeholder="Description" class="newTextarea"></textarea>
                    <input id="file" type="hidden" name="file" value="">
                    <input id="small_image" type="hidden" name="small_image" value="">
                    <input id="medium_image" type="hidden" name="medium_image" value="">
                    <input id="large_image" type="hidden" name="large_image" value="">
                    <input id="token" type="hidden" name="token" value="">
                    <input id="btn-add-optional-vat-details" title="Click to show more fields" type="button" class="btn btn-more" value="+ Envoyer la vidéo">
                </div>
                
                <div class="rightblok">
                    <div class="line">
                        <div class="upload_container">
                            <input id="file_upload" name="file_upload" type="file" multiple="false">
                            <div id="queue"></div>
                        </div>
                        <script type="text/javascript">
                            $(function() {
                                $('#file_upload').uploadify({
                                    'formData'     : {
                                        'timestamp' : '<?php echo $timestamp;?>',
                                        'token'     : '<?php echo $token; ?>'
                                    },
                                    'auto'     		: false,
                                    'buttonText' 	: '',
                                    'swf'      		: 'uploadify/uploadify.swf',
                                    'uploader' 		: 'uploadify/uploadify.php',
                                    'fileTypeExts' 	: '*.mp4; *.flv; *.mpg; *.mpeg; *.avi; *.wmv',
                                    'onCancel' : function(file) {
                                        alert('Le choix du fichier vidéo : ' + file.name + ' est annulé.');
                                    },
                                    'onUploadSuccess' : function(file) {
                                        $('#file').val(file.name);
                                        $('#token').val('<?php echo $token; ?>');
    
                                        $('#thumbs h3').addClass('loading_title');
                                        $('#thumb1').removeClass("thumb").addClass('loading_thumb');
                                        $('#thumb2').removeClass("thumb").addClass('loading_thumb');
                                        $('#thumb3').removeClass("thumb").addClass('loading_thumb');
                                    },
                                    'onQueueComplete' : function(queueData) {
                                        var message = '';
                                        $.post('create_thumbs.php', { videofile: $('#file').val(),token:$('#token').val()},
                                           function success(data){
                                             var thumbs  = data.split('|-|');
                                             var v1 = thumbs[0].replace(/(^[\s]+|[\s]+$)/g, '');
                                             var v2 = thumbs[1].replace(/(^[\s]+|[\s]+$)/g, '');
                                             var v3 = thumbs[2].replace(/(^[\s]+|[\s]+$)/g, '');								 
                                             $('#thumb1').html('<img src="<?php echo "vignette/160x90/".$token.'/'; ?>'+v1+'" width="160" height="90" />');
                                             $('#thumb1').removeClass("loading_thumb").addClass('thumb');										     
                                             $('#thumb2').html('<img src="<?php echo "vignette/160x90/".$token.'/'; ?>'+v2+'" width="160" height="90" />');
                                             $('#thumb2').removeClass("loading_thumb").addClass('thumb');
                                             $('#thumb3').html('<img src="<?php echo "vignette/160x90/".$token.'/'; ?>'+v3+'" width="160" height="90" />');
                                             $('#thumb3').removeClass("loading_thumb").addClass('thumb');
                                             $('#thumbs h3').removeClass('loading_title');
                                             $.post('convert_video.php', { videofile: $('#file').val(),token:$('#token').val()},
                                                //convert to video profiles				
                                               function success(data){
                                                    alert('result convertion : '+data);	
                                                    $('#status_convertion').show();
                                               });
                                           });
                                        if( queueData.uploadsSuccessful == 1 ){
                                            $('.upload_result').html(queueData.uploadsSuccessful + ' video est chargée avec succès.');
                                        }else{
                                            if( queueData.uploadsSuccessful > 1 ){	
                                            $('.upload_result').html(queueData.uploadsSuccessful + ' videos sont chargées avec succès.');
                                            }
                                        }
                                    }
                                });
                            });
                        </script>
                        <a href="javascript:$('#file_upload').uploadify('upload', '*')" class="confirm_upload">Chargez la vidéo</a>
                    </div>    
                    
                    <div class="thumbs line">
                        <div class="thumbs_header">
                            <h3>Aperçu des vignettes</h3>                        
                            <div class="upload_result"></div>
                        </div>    
                    	<div class="thumb" id="thumb1"></div>
                    	<div class="thumb" id="thumb2"></div>
                    	<div class="thumb last" id="thumb3"></div>
                    </div>
                    
                    <div class="line">
                        <div class="upload_container">
                            <input id="small_image_upload" name="small_image_upload" type="file" multiple="false">
                            <div id="queue"></div>
                        </div>
                        <script type="text/javascript">
                            $(function() {
                                $('#file_upload').uploadify({
                                    'formData'     : {
                                        'timestamp' : '<?php echo $timestamp;?>',
                                        'token'     : '<?php echo $token; ?>'
                                    },
                                    'auto'     		: false,
                                    'buttonText' 	: '',
                                    'swf'      		: 'uploadify/uploadify.swf',
                                    'uploader' 		: 'uploadify/uploadify.php',
                                    'fileTypeExts' 	: '*.jpg; *.jpeg; *.png; *.gif',
                                    'onCancel' : function(file) {
                                        alert('Le choix de la petite image : ' + file.name + ' est annulé.');
                                    },
                                    'onUploadSuccess' : function(file) {
                                        $('#small_image').val(file.name);
                                        $('#token').val('<?php echo $token; ?>');
                                    },
                                    'onQueueComplete' : function(queueData) {
                                        var message = '';
                                        $.post('create_thumbs.php', { videofile: $('#small_image').val(),token:$('#token').val()},
                                           function success(data){
                                             var thumbs  = data.split('|-|');
                                             var v1 = thumbs[0].replace(/(^[\s]+|[\s]+$)/g, '');
                                             var v2 = thumbs[1].replace(/(^[\s]+|[\s]+$)/g, '');
                                             var v3 = thumbs[2].replace(/(^[\s]+|[\s]+$)/g, '');								 
                                             $('#thumb1').html('<img src="<?php echo "vignette/160x90/".$token.'/'; ?>'+v1+'" width="160" height="90" />');
                                             $('#thumb1').removeClass("loading_thumb").addClass('thumb');										     
                                             $('#thumb2').html('<img src="<?php echo "vignette/160x90/".$token.'/'; ?>'+v2+'" width="160" height="90" />');
                                             $('#thumb2').removeClass("loading_thumb").addClass('thumb');
                                             $('#thumb3').html('<img src="<?php echo "vignette/160x90/".$token.'/'; ?>'+v3+'" width="160" height="90" />');
                                             $('#thumb3').removeClass("loading_thumb").addClass('thumb');
                                             $('#thumbs h3').removeClass('loading_title');
                                             $.post('convert_video.php', { videofile: $('#file').val(),token:$('#token').val()},
                                                //convert to video profiles				
                                               function success(data){
                                                    alert('result convertion : '+data);	
                                                    $('#status_convertion').show();
                                               });
                                           });
                                        if( queueData.uploadsSuccessful == 1 ){
                                            $('.upload_result').html(queueData.uploadsSuccessful + ' video est chargée avec succès.');
                                        }else{
                                            if( queueData.uploadsSuccessful > 1 ){	
                                            $('.upload_result').html(queueData.uploadsSuccessful + ' videos sont chargées avec succès.');
                                            }
                                        }
                                    }
                                });
                            });
                        </script>
                        <a href="javascript:$('#file_upload').uploadify('upload', '*')" class="confirm_upload">Chargez l'image</a>
                        <span class="thumb_man"><span class="larg_thumb"></span></span>
                        <span class="thumb_man"><span class="medium_thumb"></span></span>
                        <span class="thumb_man"><span class="small_thumb"></span></span>
                    </div>

                    <div class="loading" id="status_convertion" style="display:none;">La vidéo est en train d'être convertie vers les formats spécifique en arrière plan</div>


                </div>
                
            </form>
        </div>   
    </div>    
        
    <div id="footer">             
            <div id="inner-footer">                  
                <div id="footer-left">TOUT LES DROITS SONT RESERVES © 2012 - Azz</div>
            </div> 
        </div>    
    
</body>
</html>