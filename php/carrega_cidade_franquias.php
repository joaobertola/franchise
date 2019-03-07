<?php
require "connect/conexao_conecta.php";

$id_franquia = $_REQUEST['id_franquia'];

$sql = "SELECT DISTINCT cidade FROM cs2.cadastro WHERE id_franquia = $id_franquia ORDER BY cidade";

$res = mysql_query($sql,$con);
$num_cidades = mysql_num_rows($res);

?>
<select name="cidade" id="cidade">
<?php 
	$lista = "<option value=''>== Selecione a cidade ==</option>";
	$lista .= "<option value='TODAS'>TODAS AS CIDADES</option>";
	for($j=0;$j<$num_cidades;$j++){
		$dados = mysql_fetch_array($res);
		$cidade = $dados['cidade'];
		$lista .= "<option value='$cidade'>$cidade</option>";
	}
	echo $lista;
	?>
</select>