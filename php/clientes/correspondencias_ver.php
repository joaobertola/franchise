<?php
require "../connect/conexao_conecta.php";

$id = $_REQUEST['id'];
$sql_dados = "SELECT texto, tipo_carta FROM cs2.correspondencia_grava
              WHERE id = $id";
$qry_dados = mysql_query($sql_dados,$con) or die("Erro SQL");

$texto = mysql_result($qry_dados,0,'texto');
$tipo_carta = mysql_result($qry_dados,0,'tipo_carta');

if ( $tipo_carta == 6 or $tipo_carta == 7 ) // WC
   $texto = str_replace('{assinatura_administrativo}','<img src="https://www.webcontrolempresas.com.br/franquias/img/Assinatura_WC_Wellington.jpg" width="300" /><br><br><br><br><br>',$texto);
else // inform system
   $texto = str_replace('{assinatura_administrativo}','<img src="https://www.webcontrolempresas.com.br/franquias/img/Assinatura_Inform_Wellington.jpg" width="300" /><br><br><br><br><br>',$texto);

echo $texto;

?>