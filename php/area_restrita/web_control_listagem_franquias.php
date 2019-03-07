<?php
require_once('connect/sessao.php');
//session_start();
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//$data_cadastro = date("Y-m-d");
//
//if ( $name == "" ){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}
$franquia = $_SESSION['id'];
$nomeFranquia = $_SESSION['fantasia'];

echo "<div align='center'><b><p>$nomeFranquia</b></div>";


if( ($franquia == 4)or($franquia == 163)or($franquia == 5)or($franquia == 247)){
	$franquia = 1;
}else{
	$franquia = $_SESSION['id'];
}
 
$sql = "SELECT
			 ref_cs2 as id, fantasia
        FROM franquias.cad_franquia 
		WHERE status = 'A'
		-- AND ref_cs2 = '1'
		AND ref_cs2 != 23096 
		AND 1=1
		ORDER BY id "; 

$qry = mysql_query($sql,$con);
$total = mysql_num_rows($qry);
if($total == "0"){
	// echo "<div align='center'><b><p>Não foi encontrado nenhuma Franquia WEB-CONTROL !</b></div>";
// 	echo "<div align='center'><b><p><a href='painel.php?pagina1=area_restrita/web_control_extrato_usuarios.php'><b>Retorna</b></a></b></div>";
// 	exit;
}			
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<form action="#" method="post" name="form">

<table border='0' width='850' align='center' cellpadding='0' cellspacing='1' style='border:1px dashed #E8E8E8; background-color:#FFFFFF'>
<tr><td colspan="7" align="center" class="titulo">Listagem de Franquias(s) WEB-CONTROL</td></tr>
<tr bgcolor="#FF9900">
	<td width="7%"><b>Código</b></td>
    <td><b>Nome Fantasia</b></td>
    <td width="9%"><b>Ranking</b></td>
</tr>
<tr>
	<td style="padding-left: 5px"><?=$franquia?></td>
    <td style="padding-left: 5px"><?=$nomeFranquia?></td>
    <td><a href="https://webcontrolempresas.com.br/ranking/?acesso=&f=<?=$franquia?>" onmouseout="return showStatus('');" target="_blank""><i title="Ranking" class="fa fa-bar-chart" style="font-size:24px; padding-left: 35%;"></i></a></td>
</tr>

<?php if($franquia == 1): ?>
<?php 
$cont=0;
$total_geral = 0;
while($rs = mysql_fetch_array($qry)){
 $id_cadastro_tmp .= $rs['id_cadastro'].",";
	
$cont++;
if($cont%2 == "1")$cor = "#E8E8E8";
	else
		$cor = "";
?>
<tr bgcolor="<?=$cor?>">
	<td style="padding-left: 5px"><?=$rs['id']?></td>
    <td style="padding-left: 5px"><?=$rs['fantasia']?></td>
    <!-- <td><i class="glyphicon glyphicon-cloud"></i> id </i></td>-->
    <td><a href="https://webcontrolempresas.com.br/ranking/?acesso=&f=<?=$rs['id']?>" onmouseout="return showStatus('');" target="_blank""><i title="Ranking" class="fa fa-bar-chart" style="font-size:24px; padding-left: 35%;"></i></a></td>
   
<?php } 
$id_cadastro = substr($id_cadastro_tmp, 0, -1);
$sql_total_user = "SELECT COUNT(*)AS total_user 
				    FROM base_web_control.cliente
				    WHERE id_cadastro IN($id_cadastro)";
$qry_total_user = mysql_query($sql_total_user);
$total_user = @mysql_result($qry_total_user,0,'total_user');
?>
</table>
<p>
<table border='0' width='850' align='center' cellpadding='0' cellspacing='1'>
<!-- <tr> -->
<!-- 	<td width="10%"><a href="painel.php?pagina1=area_restrita/web_control_extrato_usuarios.php"><b>Retorna</b></a></td>      
    <td width="45%" align="right">Quantidade de Consumidores Cadastrados: <b><?=$total_user?></b></td>
    <td width="45%" align="right">Total de <b><?php //echo $total?></b> Usuário(s) WEB-CONTROL</td>
<!-- </tr> -->
</table>
<p>
<?php endif; ?>