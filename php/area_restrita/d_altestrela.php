<?php
require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

$id = $_GET['id'];
$tipo= $_GET['tipo'];

if (empty($tipo)) {
	echo "<meta http-equiv=\"refresh\" content=\"0; url=../painel.php?pagina1=area_restrita/d_estrelafrq.php\";>";
	exit;
}

$comando = "select id, estrela from cs2.franquia where id='$id'";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res); 
$estrela = $matriz['estrela'];
	
if ($tipo=='1') {
	$star = $estrela + 1;
	$sql = "update cs2.franquia set estrela='$star' WHERE id = '$id'";
	$ql = mysql_query($sql,$con);
	echo "<meta http-equiv=\"refresh\" content=\"0; url=../painel.php?pagina1=area_restrita/d_estrelafrq.php\";>";
}

if ($tipo=='2') {
	$star = $estrela - 1;
	if ($star <= 0) $star = 0;
	$sql = "update cs2.franquia set estrela='$star' WHERE id = '$id'";
	$ql = mysql_query($sql,$con);
	echo "<meta http-equiv=\"refresh\" content=\"0; url=../painel.php?pagina1=area_restrita/d_estrelafrq.php\";>";
}
mysql_close($con);
?>