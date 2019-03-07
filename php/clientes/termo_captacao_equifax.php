<?php
require ("../connect/conexao_conecta.php");

$sql = "SELECT a.codloja, mid(c.logon,1,5) AS logon, b.razaosoc, b.nomefantasia, b.insc, b.fone, b.socio1,
		DATE_FORMAT(a.dt_chegada_procuracao,'%d/%m/%Y') AS chegada, a.autorizado_serasa, 
		date_format(a.dt_resposta_serasa,'%d/%m/%Y') AS resposta from movimento_serasa a
		INNER JOIN cadastro b ON a.codloja=b.codloja
		INNER JOIN logon c ON a.codloja=c.codloja
		WHERE a.codloja='{$_REQUEST['codloja']}' LIMIT 1";
$qry = mysql_query($sql, $con);

$razaosoc = mysql_result($qry,0,'razaosoc');
$nomefantasia = mysql_result($qry,0,'nomefantasia');
$insc = mysql_result($qry,0,'insc');
$fone = mysql_result($qry,0,'fone');
$socio1 = mysql_result($qry,0,'socio1');

$altura = 50;
?>
<style type="text/css">
.estilo_1{
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 20px;
    font-weight: bold;
}
.estilo_2{
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 20px;  
}

</style>	
<body onload="window.print()" topmargin="30">

<table border="1" align="center" cellpadding="0" cellspacing="0" width="95%">

<tr height="<?=$altura?>">
	<td class="estilo_1" align="center" colspan="2">Negativa�ao WEB CONTROL EMPRESAS - Sistema Capta��o</td>
</tr>

<tr height="<?=$altura?>">
	<td width="30%" class="estilo_1">Sub-C�digo</td>
    <td width="70%" class="estilo_2">&nbsp;</td>
</tr>

<tr height="<?=$altura?>">
	<td class="estilo_1">Raz�o Social</td>
    <td class="estilo_2"><?=$razaosoc?></td>
</tr>

<tr height="<?=$altura?>">
	<td class="estilo_1">CNPJ</td>
    <td class="estilo_2"><?=$insc?></td>
</tr>

<tr height="<?=$altura?>">
	<td class="estilo_1">Nome Fantasia</td>
    <td class="estilo_2"><?=$nomefantasia?></td>
</tr>

<tr height="<?=$altura?>">
	<td class="estilo_1">Telefone (DDD)</td>
    <td class="estilo_2"><?=$fone?></td>
</tr>

<tr height="<?=$altura?>">
	<td class="estilo_1">Nome do Contato</td>
    <td class="estilo_2"><?=$socio1?></td>
</tr>

<tr height="<?=$altura?>">
	<td class="estilo_1">E-mail do Contato</td>
    <td class="estilo_2">&nbsp;</td>
</tr>

</table>
</body>
