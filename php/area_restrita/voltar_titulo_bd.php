<?php
require "connect/sessao.php";

$sql = "INSERT INTO cs2.titulos_recebafacil (SELECT * FROM titulos_recebafacil_excluidos WHERE numdoc = '{$_REQUEST['numdoc']}' AND codloja  = '{$_REQUEST['codloja']}')";
$qry = mysql_query($sql,$con);

$sql_del = "DELETE FROM cs2.titulos_recebafacil_excluidos
            WHERE numdoc = '{$_REQUEST['numdoc']}'
			AND codloja  = '{$_REQUEST['codloja']}'";
$qry_del = mysql_query($sql_del,$con);

$sql_u = "UPDATE cs2.titulos_recebafacil SET 
			  datapg  = NULL,
			  valorpg = NULL
		  WHERE numdoc = '{$_REQUEST['numdoc']}'
		  AND codloja  = '{$_REQUEST['codloja']}'";
$qry_u = mysql_query($sql_u,$con);
?>

<script language="javascript">
	alert('Titulo voltado com sucesso ! ');
	window.location.href="painel.php?pagina1=area_restrita/voltar_titulo_form.php";
</script>
