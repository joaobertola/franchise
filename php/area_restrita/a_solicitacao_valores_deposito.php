<?php

$conn = new PDO("mysql:host=10.2.2.3", 'csinform', 'inform4416#scf');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$npedido     = $_REQUEST['id_pedido'];
$dt_deposito = trim($_REQUEST['data_deposito']);

$sql = "UPDATE cs2.solicitacao_valores";

if ( $dt_deposito == '' )
    $sql .= " SET data_deposito = 'NULL' ";
else{
    $dt_deposito = substr($dt_deposito,6,4).'-'.substr($dt_deposito,3,2).'-'.substr($dt_deposito,0,2);
    $sql .= " SET data_deposito = '$dt_deposito' ";
}
$sql .= " WHERE id = '$npedido'";
$conn->exec($sql);

?>