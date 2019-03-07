<?php

$con = @mysql_pconnect("10.2.2.3", "csinform", "inform4416#scf");
if (!$con) {
	echo 'Erro na conexao com o Servidor<br>';
	echo mysql_error();
	exit;
} else {
	$database = mysql_select_db("cs2",$con);
	if (!$database) {
		echo 'Erro na conexão com o Banco de dados<br>';
		echo mysql_error();
	}
}

session_start(); // Inicia a sessão


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

/* *************************************************************** */

$x1 = $_REQUEST["x1"];
$y1 = $_REQUEST["y1"];
$x2 = $_REQUEST["x2"];
$y2 = $_REQUEST["y2"];
$w  = $_REQUEST["w"];
$h  = $_REQUEST["h"];
	
// Escala a imagem para o thumb_width
$scale   = 64/$w;
$thumb_image_location = $_REQUEST['thumb_image_location'];
$large_image_location = $_REQUEST['large_image_location'];
$id_franquia = $_REQUEST['id_franquia'];

$cropped = resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);

// GRAVANDO NO BANCO DE DADOS O ID DA FRANQUIA, O NOME DO ARQUIVO, O USUARIO QUE GRAVOU E DATA_HORA.
$sql_img = "INSERT INTO franquia_foto(
				id_franquia, data_hora, id_usuario, nome_foto
				)
			VALUES(
				{$_SESSION['s_idfranquia']} , NOW(), {$_SESSION['id']}, '$cropped'  
			    )";
$res = mysql_query($sql_img,$con) or die($sql_img);

$_SESSION['user_file_ext'] = '';
$_SESSION['random_key'] = '';

echo "<script>alert(\"Imagem gravada com sucesso !\");history.go(-2)</script>";


?>