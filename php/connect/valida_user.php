<?php

// include('conexao_conecta.php');

$servername = '10.2.2.3';
$username = "csinform";
$password = "inform4416#scf";
$dbname = 'cs2';

if (!$con = mysql_connect($servername, $username, $password)) {
    echo 'Não foi possível conectar ao mysql';
    exit;
}

if (!mysql_select_db($dbname, $con)) {
    echo 'Não foi possível selecionar o banco de dados';
    exit;
}

$usuario = $_REQUEST['usuario'];
$id = '';
$nome = '';

$sql  = "SELECT id,nome FROM cs2.funcionario WHERE senha = '$usuario' AND ativo = 'S'";
$qry  = mysql_query($sql,$con);
while ($row = mysql_fetch_assoc($qry)) {
    $id = $row['id'];
    $nome = $row['nome'];
}
echo $id.';'.$nome;

?>