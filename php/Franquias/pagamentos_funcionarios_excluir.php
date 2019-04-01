<?php
include("../connect/conexao_conecta.php");

$id_func = $_REQUEST['id_func'];
$id_lanc = $_REQUEST['id_lanc'];

echo "<meta http-equiv='refresh' content='1; url=painel.php?pagina1=Franquias/pagamentos_funcionarios_bd.php&id_lanc=$id_lanc&id_func=$id_func&acao=D'>";

?>