<?php

session_start(); // Inicia a sess�o

$id_usuario = $_SESSION['id'];
$id_franquia = $_REQUEST['id_franquia'];

// if ( $_REQUEST['large_photo_exists'] == '' )
// 	$_SESSION['user_file_ext'] = '';
	

if ( $id_franquia != '' )
	$_SESSION['s_idfranquia'] = $id_franquia;
	
//s� inicia um novo timestamp se a variavel de sess�o tiver vazia
if (!isset($_SESSION['random_key']) || strlen($_SESSION['random_key'])==0){
    $_SESSION['random_key'] = strtotime(date('Y-m-d H:i:s')); // assina o timestamp para a vari�vel de sess�o
	$_SESSION['user_file_ext']= "";
}

$upload_dir = "upload";     		    	// O diret�rio em que as imagens ser�o salvas
$upload_path = $upload_dir."/";				// O path do diret�rio para onde as imagens ser�o armazenadas
$large_image_prefix = "resize_"; 			// O prefixo para o nome da imagem grande
$thumb_image_prefix = "thumbnail_";			// O prefixo para o nome da imagem thumb

$large_image_name = $large_image_prefix.$_SESSION['random_key']; // O Novo nome da imagem em formato grande
$thumb_image_name = $thumb_image_prefix.$_SESSION['random_key']; // O Novo nome da imagem thumbnail

$max_file     = "5"; 					// Tamanho m�ximo do arquivo (em MB)
$max_width    = "500";					// Width m�ximo permitido para a imagem grande

if($_SESSION['s_largura'] > 500){
	$max_width = $_SESSION['s_largura'];
}

$thumb_width  = 64; // Width da imagem thumbnail
$thumb_height = 95;	// Height da imagem thumbnail

//Tipos de imagem permitidos para UPLOAD
$allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
$allowed_image_ext = array_unique($allowed_image_types); // n�o altere esta parte
$image_ext = "";	// inicializa a vari�vel (n�o altere esta parte)
foreach ($allowed_image_ext as $mime_type => $ext) {
    $image_ext.= strtoupper($ext)." ";
}

##########################################################################################################
# FUN�OES E TRATAMENTO DA IMAGEM																		 #
# S� altere estas fun��es se voc� tiver certeza do que est� fazendo.									 #
##########################################################################################################

//Verifica todas as informa��es da imagem (extens�o, tamanho, etc)
function resizeImage($image,$width,$height,$scale) {
	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	$newImageWidth  = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image); 
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			break;
  	}
	imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
	
	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$image); 
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$image,90); 
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$image);  
			break;
    }
	
	chmod($image, 0777);
	return $image;
}


// Baseado nas defini��es acima, esta fun��o dever� gerar o tamanho, tipo de imagem usado no thumb, etc.
function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image); 
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			break;
  	}
	imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$thumb_image_name); 
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$thumb_image_name,90); 
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$thumb_image_name);  
			break;
    }
	chmod($thumb_image_name, 0777);
	return $thumb_image_name;
}

//Pega o tamanho Height da imagem
function getHeight($image) {
	$size = getimagesize($image);
	$height = $size[1];
	return $height;
}
// Pega o tamanho Width da imagem
function getWidth($image) {
	$size = getimagesize($image);
	$width = $size[0];
	return $width;
}

// Localiza��o da imagem
$large_image_location = $upload_path.$large_image_name.$_SESSION['user_file_ext'];
$thumb_image_location = $upload_path.$thumb_image_name.$_SESSION['user_file_ext'];

// Verifica se existe alguma imagem com o mesmo nome
if (file_exists($large_image_location)){

	if(file_exists($thumb_image_location)){
		$thumb_photo_exists = "<img src=\"".$upload_path.$thumb_image_name.$_SESSION['user_file_ext']."\" alt=\"Imagem Thumbnail\"/>";
	}else{
		$thumb_photo_exists = "";
	}
   	$large_photo_exists = "<img src=\"".$upload_path.$large_image_name.$_SESSION['user_file_ext']."\" alt=\"Imagem Grande\"/>";
} else {
   	$large_photo_exists = "";
	$thumb_photo_exists = "";
}

if (isset($_REQUEST["upload"])) { 
	// Pega as informa��es do arquivo
	$userfile_name = $_FILES['image']['name'];
	$userfile_tmp  = $_FILES['image']['tmp_name'];
	$userfile_size = $_FILES['image']['size'];
	$userfile_type = $_FILES['image']['type'];
	$filename      = basename($_FILES['image']['name']);
	$file_ext      = strtolower(substr($filename, strrpos($filename, '.') + 1));
	
	//S� dever� ser processado se o arquivo for JPG, PNG ou GIF e se o tamanho permitido estiver dentro do limite
	if( (!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0) ) {
		
		foreach ($allowed_image_types as $mime_type => $ext) {
			if($file_ext==$ext && $userfile_type==$mime_type){
				$error = "";
				break;
			}else{
				$error = "S� <strong>".$image_ext."</strong> imagens s�o aceitas para upload<br />";
			}
		}
		if ($userfile_size > ($max_file*1048576)) {
			$error.= "As imagens precisam estar dentro do limite de ".$max_file."MB";
		}
		
	}else{
		$error= "Selecione uma imagem para tratamento";
	}
	
	// Est� tudo ok? Beleza... o upload da imagem � permitido
	
	if (strlen($error)==0){
		
		if (isset($_FILES['image']['name'])){
			// echo "[$large_image_location]<br>";
			
			if ( strpos($large_image_location,$file_ext) == 0 ){
				$large_image_location = $large_image_location.".".$file_ext;
			}

			if ( strpos($thumb_image_location,$file_ext) == 0 ){
				$thumb_image_location = $thumb_image_location.".".$file_ext;
			}

			// pega a extens�o do arquivo na sess�o
			$_SESSION['user_file_ext']=".".$file_ext;
			
			// echo "[$userfile_tmp, $large_image_location]";
			
			move_uploaded_file($userfile_tmp, $large_image_location);
			chmod($large_image_location, 0777);
			
			$width = getWidth($large_image_location);
			$height = getHeight($large_image_location);
			
			// Escala a imagem
			if ($width > $max_width){
				$scale = $max_width/$width;
				$uploaded = resizeImage($large_image_location,$width,$height,$scale);
			}else{
				$scale = 1;
				$uploaded = resizeImage($large_image_location,$width,$height,$scale);
			}
			// Exclui o arquivo thumbnail para o Usu�rio criar um novo
			if (file_exists($thumb_image_location)) {
				unlink($thumb_image_location);
			}
		}
		// Recarrega a p�gina para o arquivo enviado ser apresentado em tela
		header("location:".$_SERVER["PHP_SELF"]);
		exit();
	}
}

if (isset($_REQUEST["upload_thumbnail"]) && strlen($large_photo_exists)>0) {
	
	// Pega as coordenadas para o corte da imagem
	
	$x1 = $_REQUEST["x1"];
	$y1 = $_REQUEST["y1"];
	$x2 = $_REQUEST["x2"];
	$y2 = $_REQUEST["y2"];
	$w  = $_REQUEST["w"];
	$h  = $_REQUEST["h"];
	
	// Escala a imagem para o thumb_width
	$scale = $thumb_width/$w;
	$cropped = resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);
	
	//Efetua o Reload da p�gina para que o Usu�rio possa visualizar o thumbnail
	header("location:".$_SERVER["PHP_SELF"]);
	exit();
}


if ($_GET['a']=="delete" && strlen($_GET['t'])>0){

	//Pega a localiza��o do arquivo
	$large_image_location = $upload_path.$large_image_prefix.$_GET['t'];
	if (file_exists($large_image_location)) {
		unlink($large_image_location);
	}

	header("location:".$_SERVER["PHP_SELF"]);
	exit(); 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="generator" content="WebMotionUK" />
	<title>Web Control Empresas</title>
	<script type="text/javascript" src="js/jquery-pack.js"></script>
	<script type="text/javascript" src="js/jquery.imgareaselect.min.js"></script>
    <link rel="stylesheet" href="css/css.css" type="text/css">
    <link rel="stylesheet" href="css/font-glyphicons.css" type="text/css">
</head>
<body bgcolor="#F1F1F1">

<?php
//Esta fun��o faz com que o Javascrpt seja ativado apenas quando a imagem for enviada

if(strlen($large_photo_exists)>0){
	$current_large_image_width = getWidth($large_image_location);
	$current_large_image_height = getHeight($large_image_location);?>
<script type="text/javascript">
function preview(img, selection) { 
	var scaleX = <?php echo $thumb_width;?> / selection.width; 
	var scaleY = <?php echo $thumb_height;?> / selection.height; 
	
	$('#thumbnail + div > img').css({ 
		width: Math.round(scaleX * <?php echo $current_large_image_width;?>) + 'px', 
		height: Math.round(scaleY * <?php echo $current_large_image_height;?>) + 'px',
		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
	});
	$('#x1').val(selection.x1);
	$('#y1').val(selection.y1);
	$('#x2').val(selection.x2);
	$('#y2').val(selection.y2);
	$('#w').val(selection.width);
	$('#h').val(selection.height);
} 

$(document).ready(function () { 
	$('#save_thumb').click(function() {
		var x1 = $('#x1').val();
		var y1 = $('#y1').val();
		var x2 = $('#x2').val();
		var y2 = $('#y2').val();
		var w = $('#w').val();
		var h = $('#h').val();
		if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
			alert("Falta clicar com o mouse e arrastar para selecionar a �rea desejada ");
			return false;
		}else{
			return true;
		}
	});
}); 

$(window).load(function () { 
	$('#thumbnail').imgAreaSelect({ aspectRatio: '1:<?php echo $thumb_height/$thumb_width;?>', onSelectChange: preview }); 
});

function fechar(){
	window.close();
}

</script>
<?php }?>
	<div class="topo" style="height:30px" align="center">Tratamento de Foto</div>
<?php

// Mostra erro em tela
if(strlen($error)>0){
	echo "<ul><li><strong>Erro !</strong>".$error."</li></ul>";
}

if(strlen($large_photo_exists)>0){
	?>
        
    <h2>Clique com o mouse e arraste para selecionar a &aacute;rea desejada</h2>
    <div align="center">
        <img src="<?php echo $upload_path.$large_image_name.$_SESSION['user_file_ext'];?>" style="float: left; margin-right: 10px;" id="thumbnail" />
        <div style="border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden; width:<?php echo $thumb_width;?>px; height:<?php echo $thumb_height;?>px;">
        <img src="<?php echo $upload_path.$large_image_name.$_SESSION['user_file_ext'];?>" style="position: relative;" />
        </div>
        <br style="clear:both;"/>
        <form name="thumbnail" action="alterar_recortar_imagens2.php" method="post">
            <input type="hidden" name="x1" value="" id="x1" />
            <input type="hidden" name="y1" value="" id="y1" />
            <input type="hidden" name="x2" value="" id="x2" />
            <input type="hidden" name="y2" value="" id="y2" />
            <input type="hidden" name="w" value="" id="w" />
            <input type="hidden" name="h" value="" id="h" />
            <input type="hidden" name="thumb_image_location" value="<?php echo $thumb_image_location; ?>" >
            <input type="hidden" name="large_image_location" value="<?php echo $large_image_location; ?>" >
            <input type="hidden" name="id_franquia" value="<?php echo $_REQUEST['id_franquia']; ?>" >
            
            <table border="0" width="733px" align="center" cellpadding="4" cellspacing="1" bgcolor="#F1F1F1">
                <tr>
                    <td align="center" height="30px" width="25%">
                    <input type="submit" name="upload_thumbnail" value=" Salvar Foto no Sistema " id="save_thumb" style="cursor:pointer" class="botao" />
                    </td>    
                </tr>
                <tr>
                    <td align="center" height="30px" width="25%">
                    	 <input type="submit" name="fechar" value=" Fechar " onclick="window.close()" style="cursor:pointer" class="botao" />
                    </td>
                </tr>	    
            </table>    

        </form>
    </div>
<?php 
} else { 
?>
	<form name="photo" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
        <table border="0" width="733px" align="center" cellpadding="4" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
	        
        	<tr>
            	<td height="30px" class="frm_input_1" width="25%">
                	<b>Localize a imagem</b>
                </td>
                <td>
                	<input type="file" name="image" size="30" style="cursor:pointer"/>
                </td>
            </tr>
        	<tr>
            	<td height="30px" class="frm_input_1" width="25%">&nbsp;</td>
        		<td>
                	<input type="hidden" name="id_franquia" value="<?php echo $_REQUEST['id_franquia']; ?>" />
            		<input type="submit" name="upload" value=" Tratamento de Foto "  style="cursor:pointer" class="botao"/>
				</td>
	        </tr>
            <tr>
            	<td height="30px" class="frm_input_1" width="25%">&nbsp;</td>
				<td height="30px" width="25%">
                	<input type="submit" name="fechar" value=" Fechar " onclick="window.close()" style="cursor:pointer" class="botao" />
                </td>
            </tr>	    
        </table>    
	</form>
<?php 
} 
?>
</body>
</html>