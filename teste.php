<?php

/**
 *  Comentario aqui
 */
error_reporting (E_ALL ^ E_NOTICE);
session_start();
if (!isset($_SESSION['random_key']) || strlen($_SESSION['random_key'])==0){
    $_SESSION['random_key'] = strtotime(date('Y-m-d H:i:s'))."_".$_SESSION['usuario'];
    $_SESSION['user_file_ext']= "";
}
$upload_dir = "fotos";
$upload_path = $upload_dir."/";
//prefijo de foto redimensionada
$large_image_prefix = "resize_";
//prefijo de thumbnail
$thumb_image_prefix = "thumbnail_";
//nombre de la foto
$large_image_name = $large_image_prefix.$_SESSION['random_key'];
//nombre del thumbnail
$thumb_image_name = $thumb_image_prefix.$_SESSION['random_key'];
//maximo tamaño de la foto a subir (en mb)
$max_file = "4";
//maximo ancho para redimensionar
$max_width = "900";
//maximo alto para redimensionar
$max_height = "710";
//ancho del thumbnail
$thumb_width = "80";
//alto del thumbnail
$thumb_height = "80";
//extension de imagenes aceptadas
$allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
$allowed_image_ext = array_unique($allowed_image_types);
$image_ext = "";
foreach ($allowed_image_ext as $mime_type => $ext) {
    $image_ext.= strtoupper($ext)." ";
}

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
function getHeight($image) {
    $size = getimagesize($image);
    $height = $size[1];
    return $height;
}
function getWidth($image) {
    $size = getimagesize($image);
    $width = $size[0];
    return $width;
}
$large_image_location = $upload_path.$large_image_name.$_SESSION['user_file_ext'];
$thumb_image_location = $upload_path.$thumb_image_name.$_SESSION['user_file_ext'];
if(!is_dir($upload_dir)){
    mkdir($upload_dir, 0777);
    chmod($upload_dir, 0777);
}
if (file_exists($large_image_location)){
    if(file_exists($thumb_image_location)){
        $thumb_photo_exists = "<img src=\"".$upload_path.$thumb_image_name.$_SESSION['user_file_ext']."\" alt=\"Thumbnail Image\"/>";
    }else{
        $thumb_photo_exists = "";
    }
       $large_photo_exists = "<img src=\"".$upload_path.$large_image_name.$_SESSION['user_file_ext']."\" alt=\"Large Image\"/>";
} else {
       $large_photo_exists = "";
    $thumb_photo_exists = "";
}
if (isset($_REQUEST["upload"])) {
	 
    $userfile_name = $_FILES['image']['name'];
    $userfile_tmp = $_FILES['image']['tmp_name'];
    $userfile_size = $_FILES['image']['size'];
    $userfile_type = $_FILES['image']['type'];
    $filename = basename($_FILES['image']['name']);
    $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
	
	$max_file = 5;
	
    if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) {
        foreach ($allowed_image_types as $mime_type => $ext) {
            if($file_ext==$ext && $userfile_type==$mime_type){
                $error = "";
                break;
            }else{
                $error = "Solo <strong>".$image_ext."</strong> imagenes son aceptadas<br />";
            }
        }
        if ($userfile_size > ($max_file * 1048576)) {
            $error.= "Las imagenes deben pesar menos de ".$max_file."MB";
        }
    }else{
        $error= "Elige una imagen para subir";
    }
	
    if (strlen($error)==0){

        if (isset($_FILES['image']['name'])){
			
            $large_image_location = $large_image_location.".".$file_ext;
            $thumb_image_location = $thumb_image_location.".".$file_ext;
            $_SESSION['user_file_ext']=".".$file_ext;
			
		//	echo " $userfile_tmp >> $large_image_location ";
			
            move_uploaded_file($userfile_tmp, $large_image_location);
            chmod($large_image_location, 0777);
            $width = getWidth($large_image_location);
            $height = getHeight($large_image_location);
            if ($width > $height){
                if ($width > $max_width){
                    $scale = $max_width/$width;
                    $uploaded = resizeImage($large_image_location,$width,$height,$scale);
                }else{
                    $scale = 1;
                    $uploaded = resizeImage($large_image_location,$width,$height,$scale);
            }
            }else{
                if ($height > $max_height){
                    $scale = $max_height/$height;
                    $uploaded = resizeImage($large_image_location,$width,$height,$scale);
                }else{
                    $scale = 1;
                    $uploaded = resizeImage($large_image_location,$width,$height,$scale);
            }
            }
            if (file_exists($thumb_image_location)) {
                unlink($thumb_image_location);
            }
        }
        header("location:".$_SERVER["PHP_SELF"]);
        exit();
    }
}
if (isset($_REQUEST["upload_thumbnail"]) && strlen($large_photo_exists)>0) {
    $x1 = $_REQUEST["x1"];
    $y1 = $_REQUEST["y1"];
    $x2 = $_REQUEST["x2"];
    $y2 = $_REQUEST["y2"];
    $w = $_REQUEST["w"];
    $h = $_REQUEST["h"];
    $scale = $thumb_width/$w;
    $cropped = resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);
    header("location:".$_SERVER["PHP_SELF"]);
    exit();
}
if ($_GET['a']=="delete" && strlen($_GET['t'])>0){
    $large_image_location = $upload_path.$large_image_prefix.$_GET['t'];
    $thumb_image_location = $upload_path.$thumb_image_prefix.$_GET['t'];
    if (file_exists($large_image_location)) {
        unlink($large_image_location);
    }
    if (file_exists($thumb_image_location)) {
        unlink($thumb_image_location);
    }
    header("location:".$_SERVER["PHP_SELF"]);
    exit(); 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/jquery-1.4.3.min.js"></script>
<script type="text/javascript" src="js/jquery.imgareaselect.min.js"></script>

<?php
// Configura la información de tu cuenta 
$dbhost='10.2.2.3';
$dbusername='csinform'; 
$dbuserpass='inform4416#scf'; 
$dbname='cs2'; 
// Conexión a la base de datos 
mysql_connect ($dbhost, $dbusername, $dbuserpass); 
mysql_select_db($dbname) or die('No se puede selecionar la base de datos'); 
?>
<title>Subir Imagenes</title>
</head>
<body>
<?php
//solo mostrar si la imagen ha sido subida
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
            alert("Debes de seleccionar primero");
            return false;
        }else{
            return true;
        }
    });
}); 
$(window).load(function () { 
    $('#thumbnail').imgAreaSelect({ aspectRatio: '1:<?php echo $thumb_height/$thumb_width;?>', onSelectChange: preview }); 
});
</script>
<?php }?>
<?php
//mostrar error si hay
if(strlen($error)>0){
    echo "<ul><li><strong>Error!</strong></li><li>".$error."</li></ul>";
}
if(strlen($large_photo_exists)>0 && strlen($thumb_photo_exists)>0){
    echo $large_photo_exists."&nbsp;".$thumb_photo_exists;
    echo    "<form action='registrar_foto.php' method='post'>"
            ."<table border='0'>"
            ."<tr>"
            ."<td>Titulo: </td>"
            ."<td><input name='titulo' type='text' maxlength='25' /></td>"
            ."</tr>"
            ."<tr>"
            ."<td>Descripcion: </td>"
            ."<td><input name='descripcion' type='text' maxlength='400' /></td>"
            ."</tr>"
            ."<tr>"
            ."<td></td>"
            ."<td><input name='Registrar' type='submit' value='Registrar'/></td>"
            ."</tr>"
            ."</table>"
            ."</form>";
}else{
        if(strlen($large_photo_exists)>0){?>
        <h2>Crear Thumbnail</h2>
        <div align="center">
            <img src="<?php echo $upload_path.$large_image_name.$_SESSION['user_file_ext'];?>" style="float: left; margin-right: 10px;" id="thumbnail" alt="Create Thumbnail" />
            <div style="border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden; width:<?php echo $thumb_width;?>px; height:<?php echo $thumb_height;?>px;">
                <img src="<?php echo $upload_path.$large_image_name.$_SESSION['user_file_ext'];?>" style="position: relative;" alt="Thumbnail Preview" />
            </div>
            <br style="clear:both;"/>
            <form name="thumbnail" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                <input type="hidden" name="x1" value="" id="x1" />
                <input type="hidden" name="y1" value="" id="y1" />
                <input type="hidden" name="x2" value="" id="x2" />
                <input type="hidden" name="y2" value="" id="y2" />
                <input type="hidden" name="w" value="" id="w" />
                <input type="hidden" name="h" value="" id="h" />
                <input type="submit" name="upload_thumbnail" value="Save Thumbnail" id="save_thumb" />
            </form>
        </div>
    <hr />
    <?php     }else{ ?>
    <h2>Subir Foto</h2>
    <form name="photo" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
    Foto <input type="file" name="image" size="30" /> <input type="submit" name="upload" value="Upload" />
    </form>
<?php }} ?>
</body>
</html>