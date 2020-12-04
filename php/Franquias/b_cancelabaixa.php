<?php
require "connect/sessao.php";
require "connect/conexao_conecta.php"; 

$codloja = $_REQUEST['codloja'];
$numdoc  = $_REQUEST['numdoc'];

$query="UPDATE cs2.titulos SET 
			valorpg = NULL, 
			datapg = NULL, 
			juros = NULL, 
			origem_pgto='NULL' 
		WHERE numdoc = '$numdoc'";
mysql_query($query,$con) or die (" erro:  $sql");

$sql = "SELECT MID(a.logon,1,LOCATE('S', a.logon) - 1) as logon, b.razaosoc 
		FROM cs2.logon a
		INNER JOIN cs2.cadastro b ON a.codloja = b.codloja 
		WHERE a.codloja = $codloja";
$resulta = mysql_query($sql, $con ) or die (" erro:  $sql");
$linha = mysql_num_rows($resulta); 
if ($linha > 0){
	$matriz = mysql_fetch_array($resulta); 
	$logon = $matriz['logon'];
	$razaosoc = $matriz['razaosoc'];
}
mysql_close($con);

echo "<script language='javascript'>
		alert('CANCELAMENTO DE PAGAMENTO REALIZADO COM  SUCESSO !!!');
	</script>";

echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=clientes/a_ver_faturas.php&codloja=$codloja&logon=$logon&razaosoc=$razaosoc\";>";

?>