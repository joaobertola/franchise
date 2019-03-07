<?php
error_reporting (E_ALL ^ E_NOTICE);
session_start(); // Inicia a sessão

include("funcao_php/conexao.php");

session_start();

$fra_nomesite  = $_SESSION['fra_nomesite'];

$cmd2 = shell_exec("sudo /usr/bin/libera $fra_nomesite");

/*
if($_REQUEST['primeiro_acesso'] == 1){
	if( ($_SESSION['s_pag_id'] == '') and ($_SESSION['s_id_cadastro'] == '') and ($_SESSION['s_dominio'] == '') and
		($_REQUEST['pag_id']   != '') and ($_REQUEST['id_cadastro']   != '') and ($_REQUEST['dominio']   != '') 
	 ){
		session_start();
		unset($_SESSION['s_pag_id']);
		unset($_SESSION['s_id_cadastro']);
		unset($_SESSION['s_dominio']);
		
		echo "->>>  ".$_SESSION['s_pag_id']      = $_REQUEST['pag_id'];
		$_SESSION['s_id_cadastro'] = $_REQUEST['id_cadastro'];
		$_SESSION['s_dominio']     = $_REQUEST['dominio'];	
	}
}
*/
if($_REQUEST['primeiro_acesso'] == 1){
	unset($_SESSION['s_pag_id']);
	unset($_SESSION['s_id_cadastro']);
	unset($_SESSION['s_dominio']);
	unset($_SESSION['s_tipo_pagina']);
	unset($_SESSION['user_file_ext']);	
	unset($_SESSION['s_largura']);	
	unset($_SESSION['s_altura']);	
	
	$_SESSION['s_pag_id']      = $_REQUEST['pag_id'];
	$_SESSION['s_id_cadastro'] = $_REQUEST['id_cadastro'];
	$_SESSION['s_dominio']     = $_REQUEST['dominio'];	
	$_SESSION['s_tipo_pagina'] = $_REQUEST['tipo_pagina'];		
	$_SESSION['s_largura']     = $_REQUEST['largura'];	
	$_SESSION['s_altura']      = $_REQUEST['altura'];		
}

//	echo "<pre>";
//print_r($_SESSION);


//só inicia um novo timestamp se a variavel de sessão tiver vazia
if (!isset($_SESSION['random_key']) || strlen($_SESSION['random_key'])==0){
    $_SESSION['random_key'] = strtotime(date('Y-m-d H:i:s')); // assina o timestamp para a variável de sessão
	$_SESSION['user_file_ext']= "";
}

//$upload_dir = "imagens";     		    	// O diretório em que as imagens serão salvas
$upload_dir = $_SESSION['s_dominio'].'/img/'; // O diretório em que as imagens serão salvas			   
$upload_path = $upload_dir."/";				// O path do diretório para onde as imagens serão armazenadas
$large_image_prefix = "resize_"; 			// O prefixo para o nome da imagem grande
$thumb_image_prefix = "thumbnail_";			// O prefixo para o nome da imagem thumb

$large_image_name = $large_image_prefix.$_SESSION['random_key']; // O Novo nome da imagem em formato grande
$thumb_image_name = $thumb_image_prefix.$_SESSION['random_key']; // O Novo nome da imagem thumbnail

$max_file     = "5"; 					// Tamanho máximo do arquivo (em MB)
$max_width    = "500";					// Width máximo permitido para a imagem grande

if($_SESSION['s_largura'] > 500){
	$max_width = $_SESSION['s_largura'];
}


$thumb_width  = $_SESSION['s_largura']; // Width da imagem thumbnail
$thumb_height = $_SESSION['s_altura'];	// Height da imagem thumbnail

/*
$max_file     = "3"; 					// Tamanho máximo do arquivo (em MB)
$max_width    = "500";					// Width máximo permitido para a imagem grande
$thumb_width  = "340";					// Width da imagem thumbnail
$thumb_height = "110";					// Height da imagem thumbnail
*/

//Tipos de imagem permitidos para UPLOAD
$allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
$allowed_image_ext = array_unique($allowed_image_types); // não altere esta parte
$image_ext = "";	// inicializa a variável (não altere esta parte)
foreach ($allowed_image_ext as $mime_type => $ext) {
    $image_ext.= strtoupper($ext)." ";
}

##########################################################################################################
# FUNÇOES E TRATAMENTO DA IMAGEM																		 #
# Só altere estas funções se você tiver certeza do que está fazendo.									 #
##########################################################################################################

//Verifica todas as informações da imagem (extensão, tamanho, etc)
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


// Baseado nas definições acima, esta função deverá gerar o tamanho, tipo de imagem usado no thumb, etc.
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

// Localização da imagem
$large_image_location = $upload_path.$large_image_name.$_SESSION['user_file_ext'];
$thumb_image_location = $upload_path.$thumb_image_name.$_SESSION['user_file_ext'];

// Cria o diretório onde serão armazenadas as imagens e dá as permissões (se ainda não existir)

if(!is_dir($upload_dir)){	
	mkdir($upload_dir, 0777);
	chmod($upload_dir, 0777);
}

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

if (isset($_POST["upload"])) { 
	// Pega as informações do arquivo
	$userfile_name = $_FILES['image']['name'];
	$userfile_tmp = $_FILES['image']['tmp_name'];
	$userfile_size = $_FILES['image']['size'];
	$userfile_type = $_FILES['image']['type'];
	$filename = basename($_FILES['image']['name']);
	$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
	
	//Só deverá ser processado se o arquivo for JPG, PNG ou GIF e se o tamanho permitido estiver dentro do limite
	if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) {
		
		foreach ($allowed_image_types as $mime_type => $ext) {
			if($file_ext==$ext && $userfile_type==$mime_type){
				$error = "";
				break;
			}else{
				$error = "Só <strong>".$image_ext."</strong> imagens são aceitas para upload<br />";
			}
		}
		if ($userfile_size > ($max_file*1048576)) {
			$error.= "As imagens precisam estar dentro do limite de ".$max_file."MB";
		}
		
	}else{
		$error= "Selecione uma imagem para tratamento";
	}
	
	// Está tudo ok? Beleza... o upload da imagem é permitido
	if (strlen($error)==0){
		
		if (isset($_FILES['image']['name'])){
			
			$large_image_location = $large_image_location.".".$file_ext;

			$thumb_image_location = $thumb_image_location.".".$file_ext;
			
			// pega a extensão do arquivo na sessão
			$_SESSION['user_file_ext']=".".$file_ext;
			
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
			// Exclui o arquivo thumbnail para o Usuário criar um novo
			if (file_exists($thumb_image_location)) {
				unlink($thumb_image_location);
			}
		}
		// Recarrega a página para o arquivo enviado ser apresentado em tela
		header("location:".$_SERVER["PHP_SELF"]);
		exit();
	}
}

if (isset($_POST["upload_thumbnail"]) && strlen($large_photo_exists)>0) {
	// Pega as coordenadas para o corte da imagem
	$x1 = $_POST["x1"];
	$y1 = $_POST["y1"];
	$x2 = $_POST["x2"];
	$y2 = $_POST["y2"];
	$w  = $_POST["w"];
	$h  = $_POST["h"];
	
	// Escala a imagem para o thumb_width
	$scale = $thumb_width/$w;
	$cropped = resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);
	
	//Efetua o Reload da página para que o Usuário possa visualizar o thumbnail
	header("location:".$_SERVER["PHP_SELF"]);
	exit();
}


if ($_GET['a']=="delete" && strlen($_GET['t'])>0){

	//Pega a localização do arquivo
	$large_image_location = $upload_path.$large_image_prefix.$_GET['t'];
	//$thumb_image_location = $upload_path.$thumb_image_prefix.$_GET['t'];
	if (file_exists($large_image_location)) {
		unlink($large_image_location);
	}
	/*if (file_exists($thumb_image_location)) {
		unlink($thumb_image_location);
	}*/
	header("location:".$_SERVER["PHP_SELF"]);
	exit(); 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="generator" content="WebMotionUK" />
	<title>Virtual Flex</title>
	<script type="text/javascript" src="funcao_php/js/jquery-pack.js"></script>
	<script type="text/javascript" src="funcao_php/js/jquery.imgareaselect.min.js"></script>
    <link rel="stylesheet" href="css/css.css" type="text/css">
</head>
<body bgcolor="#F1F1F1">

<?php
//Esta função faz com que o Javascrpt seja ativado apenas quando a imagem for enviada

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
			alert("Falta clicar com o mouse e arrastar para selecionar a área desejada ");
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

function voltar(){
	frm = document.thumbnail;
	frm.action = "<?=$_SERVER["PHP_SELF"]?>?primeiro_acesso=1";
	frm.submit();
}
</script>
<?php }?>
	<div class="topo" style="height:30px" align="center">Sistema de Tratamento de Imagens</div>
<?php

// Mostra erro em tela
if(strlen($error)>0){
	echo "<ul><li><strong>Erro !</strong>".$error."</li></ul>";
}

if(strlen($large_photo_exists)>0 && strlen($thumb_photo_exists)>0){
		
	//exclui a imagem original
	//$img_excluir = $_SESSION['s_dominio'].'/img/'.$large_image_name.".jpg";
	$img_excluir = $_SESSION['s_dominio'].'/img/'.$large_image_name.$_SESSION['user_file_ext'];
	
	//echo "=>:".$img_excluir."<br>";
	@unlink($img_excluir);
	
	//echo $large_photo_exists."&nbsp;".$thumb_photo_exists;
	//echo "<br>".$thumb_photo_exists;	
	//echo "<hr>";
	//echo "<div align='center'><a href='#' onclick='window.close()'>Fechar</a></div>";
	?>
    <script language=javascript>
       window.opener.location.reload();
       window.close();
	</script>
    <?php
	//echo "<p><a href=\"".$_SERVER["PHP_SELF"]."?a=delete&t=".$_SESSION['random_key'].$_SESSION['user_file_ext']."\">Fechar</a></p>";
	//echo "<p><a href=\"".$_SERVER["PHP_SELF"]."?a=delete&t=".$_SESSION['random_key'].$_SESSION['user_file_ext']."\">Fechar</a></p>";

	//REMOVE A IMAGEM de popup
	if($_SESSION['s_tipo_pagina'] == 'P'){
		$sql_s= "SELECT * FROM dbsitesv2.tbl_paginas WHERE pag_codloja = '{$_SESSION['s_id_cadastro']}' AND pag_id = '{$_SESSION['s_pag_id']}'";
		$qry_s = mysql_query($sql_s, $conexao);	
		$pag_popup_imagem_del = mysql_result($qry_s ,0,'pag_popup_imagem');
		$deleta_img = $_SESSION['s_dominio'].'/img/'.$pag_popup_imagem_del;
		@unlink($deleta_img);
		
		//ATUALIZA A IMAGEM
		$pag_popup_imagem = $thumb_image_name.$_SESSION['user_file_ext'];
		$sql = "UPDATE dbsitesv2.tbl_paginas SET
				pag_popup_imagem = '$pag_popup_imagem'
				WHERE pag_codloja = '{$_SESSION['s_id_cadastro']}'
				AND   pag_id      = '{$_SESSION['s_pag_id']}'";
		$qry = mysql_query($sql, $conexao);	
	}

	//REMOVE A IMAGEM de fundo, avulsa, link, blog
	if($_SESSION['s_tipo_pagina'] == 'F'){
		$sql_s= "SELECT fot_nome FROM dbsitesv2.tbl_fotos_site WHERE fot_codloja = '{$_SESSION['s_id_cadastro']}' AND fot_id = '{$_SESSION['s_pag_id']}'";
		$qry_s = mysql_query($sql_s, $conexao);	
		$pag_popup_imagem_del = mysql_result($qry_s ,0,'fot_nome');
		$deleta_img = $_SESSION['s_dominio'].'/img/'.$pag_popup_imagem_del;
		@unlink($deleta_img);
		
		//ATUALIZA A IMAGEM
		$pag_popup_imagem = $thumb_image_name.$_SESSION['user_file_ext'];
		
		$sql = "UPDATE dbsitesv2.tbl_fotos_site SET
				fot_nome = '$pag_popup_imagem'
				WHERE fot_codloja = '{$_SESSION['s_id_cadastro']}'
				AND   fot_id      = '{$_SESSION['s_pag_id']}'";
		$qry = mysql_query($sql, $conexao);	
	}
	
	echo "<p><a href=\"".$_SERVER["PHP_SELF"]."\">Upload outra imagem</a></p>";
	?>
       <!--  <input type="submit" name="fechar" value="fechar" onclick="window.close()" style="cursor:pointer" />-->
    <?php
	// Limpa o time stamp da sessão e a extensão do arquivo
	$_SESSION['random_key']= "";
	$_SESSION['user_file_ext']= "";
}else{
	
		if(strlen($large_photo_exists)>0){?>
		<h2>Clique com o mouse e arraste para selecionar a &aacute;rea desejada</h2>
		<div align="center">
			<img src="<?php echo $upload_path.$large_image_name.$_SESSION['user_file_ext'];?>" style="float: left; margin-right: 10px;" id="thumbnail" />
			<div style="border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden; width:<?php echo $thumb_width;?>px; height:<?php echo $thumb_height;?>px;">
			<img src="<?php echo $upload_path.$large_image_name.$_SESSION['user_file_ext'];?>" style="position: relative;" />
			</div>
			<br style="clear:both;"/>
			<form name="thumbnail" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                <input type="hidden" name="x1" value="" id="x1" />
				<input type="hidden" name="y1" value="" id="y1" />
				<input type="hidden" name="x2" value="" id="x2" />
				<input type="hidden" name="y2" value="" id="y2" />
				<input type="hidden" name="w" value="" id="w" />
				<input type="hidden" name="h" value="" id="h" />
                <table border="0" width="733px" align="center" cellpadding="4" cellspacing="1" bgcolor="#F1F1F1">
                    <tr>
                    	<td align="center" height="30px" width="25%">
                        <input type="submit" name="upload_thumbnail" value="salvar" id="save_thumb" style="cursor:pointer" class="botao" />
                        &nbsp;&nbsp;
                        <input type="submit" name="fechar" value="fechar" onclick="window.close()" style="cursor:pointer" class="botao" />
                        </td>    
                    </tr>	    
                </table>    

			</form>
		</div>

	<?php } else { ?>
	<form name="photo" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
        <table border="0" width="733px" align="center" cellpadding="4" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
        <tr><td height="30px" class="frm_input_1" width="25%"><b>Localize a imagem</b></td><td><input type="file" name="image" size="30" style="cursor:pointer"/></td></tr>
        <tr><td height="30px" class="frm_input_1"></td>
        	<td>
            <input type="submit" name="upload" value=" Enviar "  style="cursor:pointer" class="botao"/>
            &nbsp;&nbsp;
            <input type="submit" name="fechar" value="fechar" onclick="window.close()" style="cursor:pointer" checked="checked" class="botao" /></td>
        </tr>	    
        </table>    
	</form>
<?php } 
}?>
</body>
</html>
