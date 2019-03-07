<?
require "connect/conexao_conecta.php";

$id_franquia = $_REQUEST['id_franquia'];

$sql = "SELECT DISTINCT bairro FROM cs2.cadastro WHERE id_franquia = id_franquia";
$res = mysql_query($sql);
$num_cidades = mysql_num_rows($res);

?>
<select name="bairro" id="bairro">
<?php 
	$lista = "<option value='TODOS'>TODOS OS BAIRROS</option>";
	for($j=0;$j<$num_cidades;$j++){
		$dados = mysql_fetch_array($res);
		$bairro = $dados['bairro'];
		$lista .= "<option value='$bairro'>$bairro</option>";
	}
	echo $lista;
	?>
</select>