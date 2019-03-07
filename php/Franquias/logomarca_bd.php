<?php
require ("connect/sessao.php");

$codigo = $_REQUEST['codigo'];

// VERIFICA TAMANHO DA IMAGEM
if(is_uploaded_file($_FILES['image']['tmp_name'])){
	if($_FILES['f_foto_usuarios']['size']>(40*1024)) { // se for maior de 64k
	echo 'Arquivo de imagem deve ser menor que 40Kb!';
	echo "<p><a href='painel.php?pagina1=Franquias/logomarca_buscar_cliente_listar.php&codigo=$codigo'><b>Retornar</b></a></div>";
	}
}

// Verifica se o mime-type do arquivo � de imagem
$arquivo = isset($_FILES["image"]) ? $_FILES["image"] : FALSE;
if (!preg_match('/^image\/(pjpeg|jpeg)$/', $arquivo['type'])) {
  echo "<p><table border='0' width='65%' align='center' cellpadding='0' cellspacing='5' style='border: 2px solid #D1D7DC; background-color:#FFFFFF'>";
  echo "<tr><td><font color='red'>Arquivo em formato inv�lido! A imagem deve ser jpg ou jpeg. Envie outro arquivo.</td></tr>";
  echo "<tr><td><b>Dicas: </b></font><font color='blue'>Copiar a Logomarca do cliente e colar no \"Paint\" e Salve no fomato JPG</font></td></tr>";
  echo "<tr><td>";
  echo "    <b>Formato:</b> <font color='blue'>JPG</font>
		<br><b>Largura:</b> <font color='blue'>234Px</font>
		<br><b>Altura:</b> <font color='blue'>170Px</font>
		<br><b>Resolu&ccedil;&atilde;o:</b> <font color='blue'>96 Pixels</font>";
    echo "</td></tr>";		
  echo "<tr><td align='center'><a href='painel.php?pagina1=Franquias/logomarca_buscar_cliente_listar.php&codigo=$codigo'><b><< Retornar >> </b></a></td></tr>";
  echo "</table>";
  exit;
}

/*************************************************************************************************************************/
// DEFINE A IMAGEM DA QUAL SER� GERADA A MINIATURA
   // Lembrar que essa imagem tem que estar no diret�rio do script...
   // .. nenhum teste ser� feito para saber se ela existe
   $imagem = $_FILES['image']['tmp_name']; // Tipo: JPG

// DEFINIR O NOME DO ARQUIVO PARA O THUMBNAIL
   $thumbnail = explode('.', $imagem);
   $thumbnail = $thumbnail[0]."_thumbnail.jpg";

// DEFINIR AS DIMENS�ES PARA O THUMBNAIL
   $x = 234; // Largura
   $y = 170; // Altura

// L� A IMAGEM DE ORIGEM
    $img_origem = ImageCreateFromJPEG($imagem);

// PEGA AS DIMENS�ES DA IMAGEM DE ORIGEM
    $origem_x = imagesx($img_origem); // Largura
    $origem_y = imagesy($img_origem); // Altura

// ESCOLHE A LARGURA MAIOR E, BASEADO NELA, GERA A LARGURA MENOR
    if($origem_x > $origem_y) { // Se a largura for maior que a altura
       $final_x = $x; // A largura ser� a do thumbnail
       $final_y = floor($x * $origem_y / $origem_x); // A altura � calculada
       $f_x = 0; // Colar no x = 0
       $f_y = round(($y / 2) - ($final_y / 2)); // Centralizar a imagem no meio y do thumbnail
    } else { // Se a altura for maior ou igual � largura
       $final_x = floor($y * $origem_x / $origem_y); // Calcula a largura
       $final_y = $y; // A altura ser� a do thumbnail
       $f_x = round(($x / 2) - ($final_x / 2)); // Centraliza a imagem no meio x do thumbnail
       $f_y = 0; // Colar no y = 0
    }

// CRIA A IMAGEM FINAL PARA O THUMBNAIL
   $img_final = imagecreatetruecolor($x,$y);	

// COPIA A IMAGEM ORIGINAL PARA DENTRO DO THUMBNAIL
    ImageCopyResized($img_final, $img_origem, $f_x, $f_y, 0, 0, $final_x, $final_y, $origem_x, $origem_y);

// SALVA O THUMBNAIL
    ImageJPEG($img_final, $thumbnail);

// LIBERA A MEM�RIA
    ImageDestroy($img_origem);
    ImageDestroy($img_final);

/******************************************************************************************************************************/
// selected and uploaded a file
if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) { 

      //$tmpName = $_FILES['image']['tmp_name'];  
	  $tmpName = $thumbnail;
       
      // Read the file 
      $fp = fopen($tmpName, 'r');
	  
      $data = fread($fp, filesize($tmpName));
      $data = addslashes($data);
      fclose($fp);
	  
	  $codloja = $_REQUEST['codloja'];
      $sql = "UPDATE cadastro SET logomarca = '$data' WHERE codloja = '$codloja'";
 	  $qry = mysql_query($sql);
      
      // Print results
	  echo "<p>&nbsp;</p><table border='0' width='30%' align='center' cellpadding='0' cellspacing='5' style='border: 1px solid #D1D7DC; background-color:#FFFFFF'>";
	  echo "<tr><td align='center'>";
	  echo "<img src='ranking/d_gera3.php?codloja=$codloja'>";
	  echo "</td></tr></table>";	  
}
	
echo "<p align='center'><a href='painel.php?pagina1=Franquias/logomarca_buscar_cliente.php'><b>Imagem Enviada com Sucesso Retornar</b></a>";
echo "<meta http-equiv='refresh' content='9999990; url=painel.php?pagina1=Franquias/logomarca_buscar_cliente.php&no=2'>";	
?>