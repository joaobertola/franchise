<?php

require "../connect/conexao_conecta.php";

$campanha = $_REQUEST['id_campanha'];

$sql = "SELECT count(*) AS qtd, retorno FROM cs2.campanha_whatsApp_retorno
        WHERE id_campanha = $campanha
        GROUP BY retorno";
$qry = mysql_query( $sql, $con) or die('Erro comando SQL :'.$sql);
$row = mysql_fetch_array($qry);
echo json_encode($row);