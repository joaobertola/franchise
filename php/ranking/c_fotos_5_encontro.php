<html xmlns:fo="http://www.w3.org/1999/XSL/Format"><head>
</head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><title>. </title><body>
<?php
exit;
require "connect/sessao.php";
?>

<table width="783" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
     <td rowspan="2" align="left" valign="top">
    <td colspan="2" rowspan="2" align="center" valign="top" bgcolor="#FFFCC7">	
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>-</title>
    <script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
    <script type="text/javascript" src="../js/jquery.lightbox-0.5.js"></script>

    <link rel="stylesheet" type="text/css" href="../js/jquery.lightbox-0.5.css" media="screen" />
    <script type="text/javascript">
    $(function() {
        $('#gallery a').lightBox();
    });
    </script>
   	<style type="text/css">
	#gallery {		
     	background-color: #FFFFFF;
		padding: 10px;
		width: 820px;
		text-align:left;
	}
	#gallery ul { list-style: none; }
	#gallery ul li { display: inline; }
	#gallery ul img {
		border: 0px solid #FFFFFF;
		border-width: 0px 0px 0px;
	}
	#gallery ul a:hover img {
		border-width: 0px 0px 0px;
		
	}

	</style>
  
            
<div id="gallery">
<?php 
	$img = 5728;
	$foto = 1;
	for($i=1; $i<=10; $i++)
	{
?>
   <ul>
      <li>       
      
      <?php if($foto == 1) { ?>
      <table border="0px" align="center" width="800px" cellpadding="1" cellspacing="3" style="background:#CCCCCC; border:5px solid #CCCCCC; font-family:Arial, Helvetica, sans-serif;">        
        	<tr><td colspan="10" align="center" height="30" valign="top"><b>FOTOS 5� GRANDE ENCONTRO NACIONAL</b</td></tr>
        </table>
        <p>
		<?php } ?>
             
	  	<table border="1px" align="center" width="800px" cellpadding="1" cellspacing="3" style="background:#FFFFFF; border:5px solid #B6CBF6;">
		 <tr>
		  <td width="10%">&nbsp;<?php $img = $img + 1; ?><a href="../fotos/5_encontro/5_encontro_g/IMG_<?=$img?>.JPG">Foto <b><?=$foto++?></b></a></td>          
          		
		  <td width="10%">&nbsp;<?php $img = $img + 1; ?><a href="../fotos/5_encontro/5_encontro_g/IMG_<?=$img?>.JPG">Foto <b><?=$foto++?></b></a></td>         
          		
		  <td width="10%">&nbsp;<?php $img = $img + 1; ?><a href="../fotos/5_encontro/5_encontro_g/IMG_<?=$img?>.JPG">Foto <b><?=$foto++?></b></a></td>	         
          
		  <td width="10%">&nbsp;<?php $img = $img + 1; ?><a href="../fotos/5_encontro/5_encontro_g/IMG_<?=$img?>.JPG">Foto <b><?=$foto++?></b></a></td>	
          
          <td width="10%">&nbsp;<?php $img = $img + 1; ?><a href="../fotos/5_encontro/5_encontro_g/IMG_<?=$img?>.JPG">Foto <b><?=$foto++?></b></a></td>          
          		
		  <td width="10%">&nbsp;<?php $img = $img + 1; ?><a href="../fotos/5_encontro/5_encontro_g/IMG_<?=$img?>.JPG">Foto <b><?=$foto++?></b></a></td>         
          		
		  <td width="10%">&nbsp;<?php $img = $img + 1; ?><a href="../fotos/5_encontro/5_encontro_g/IMG_<?=$img?>.JPG">Foto <b><?=$foto++?></b></a></td>	         
          
		  <td width="10%">&nbsp;<?php $img = $img + 1; ?><a href="../fotos/5_encontro/5_encontro_g/IMG_<?=$img?>.JPG">Foto <b><?=$foto++?></b></a></td>	
          
          <td width="10%">&nbsp;<?php $img = $img + 1; ?><a href="../fotos/5_encontro/5_encontro_g/IMG_<?=$img?>.JPG">Foto <b><?=$foto++?></b></a></td>          
          		
		  <td width="23%">&nbsp;<?php $img = $img + 1; ?><a href="../fotos/5_encontro/5_encontro_g/IMG_<?=$img?>.JPG">Foto <b><?=$foto++?></b></a></td>                   			
                   
		 </tr>		
	    </table>		
      </li>  
   </ul> 

<?php } ?>   
   
    