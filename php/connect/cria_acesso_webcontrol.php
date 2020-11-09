<?php

require "conexao_conecta.php";
require "funcoes.php";

$id_cadastro = $_REQUEST['id_cadastro']; 

$sql = "SELECT c.id, a.codloja, a.razaosoc, a.uf,a.cpfsocio1, a.email, MID(b.logon,1,LOCATE('S', b.logon) - 1) as logon, MID(logon,LOCATE('S', b.logon) + 1,10) as senha
FROM cs2.cadastro a
INNER JOIN cs2.logon b ON a.codloja = b.codloja
LEFT OUTER JOIN base_web_control.webc_usuario c ON a.codloja = c.id_cadastro
where a.codloja = $id_cadastro limit 1";
$qr = mysql_query($sql, $con);
while ( $reg = mysql_fetch_array($qr)){
    
    $codloja = $reg['codloja'];
    $razaosoc = $reg['razaosoc'];
    $uf = $reg['uf'];
    $cpfsocio1 = $reg['cpfsocio1'];
    $email = $reg['email'];
    $logon = $reg['logon'];
    $senha = $reg['senha'];
    
   // echo "[$codloja,$razaosoc,$cpfsocio1,$email,$logon,$senha,$uf]";
    Grava_Acesso_WebControl($codloja,$razaosoc,$cpfsocio1,$email,$logon,$senha,$uf);
    // die;
    
}

echo "Terminou";

?>