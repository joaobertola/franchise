<?php

require "connect/conexao_conecta.php";

$codigo = $_REQUEST['codigo'];

$sql = "SELECT a.nomefantasia, a.id_franquia, c.fantasia, a.codloja, a.sitcli from cs2.cadastro a
        INNER JOIN cs2.logon b ON a.codloja = b.codloja
        INNER JOIN cs2.franquia c on a.id_franquia = c.id
        WHERE logon LIKE '$codigo%'";
$res = mysql_query($sql,$con);
if(mysql_num_rows($res) > 0 ){
    $nomefantasia = trim(mysql_result($res,0,'nomefantasia'));
    $id_franquia = trim(mysql_result($res,0,'id_franquia'));
    $fantasia = trim(mysql_result($res,0,'fantasia'));
    $codloja = trim(mysql_result($res,0,'codloja'));
    $sitcli = trim(mysql_result($res,0,'sitcli'));
    if ($sitcli == 2) $sitcli = 'CANCELADO';
}
if ( $nomefantasia == '')
    $nomefantasia = "CLIENTE NAO CADASTRADO";

echo "$nomefantasia][$id_franquia][$fantasia][$codloja][$sitcli";

?>