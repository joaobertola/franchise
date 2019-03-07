<?php

include('conexao_conecta.php');

$usuario = $_REQUEST['usuario'];

$sql  = "SELECT id,nome FROM cs2.funcionario WHERE senha = '$usuario'";
$qry  = mysql_query($sql,$con);
$id   = mysql_result($qry,0,'id');
$nome = mysql_result($qry,0,'nome');
echo $id.';'.$nome;

?>