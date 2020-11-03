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
if( ($franquia == 4)or($franquia == 163)or($franquia == 5)){
	$franquia = 1;
}else{
	$franquia = $_SESSION['id'];
}
 
$sql = "SELECT
			 b.id, b.id_cadastro, a.id_franquia, SUBSTRING(c.logon, 1, 5)AS logon, a.nomefantasia, 
			 b.login, b.senha, date_format(b.data_criacao,'%d/%m/%Y') AS data_criacao,  b.ativo AS ativo2,
		CASE  b.login_master
			 WHEN 'S' THEN '<font color=green>Sim</font>'
			 WHEN 'N' THEN '<font color=red>Não</font>'
		END AS login_master,
		CASE  b.ativo
			 WHEN 'A' THEN '<font color=green>Sim</font>'
			 WHEN 'I' THEN '<font color=red>Não</font>'
		END AS ativo
        FROM base_web_control.webc_usuario b
		INNER JOIN cs2.logon c on c.codloja = b.id_cadastro
		INNER JOIN cs2.cadastro a on c.codloja = a.codloja
		WHERE 1=1";			
		/* antigo
			FROM cs2.cadastro a
			INNER JOIN base_web_control.usuario b on a.codloja = b.id_cadastro
			INNER JOIN cs2.logon c on c.codloja = a.codloja		
			WHERE 1=1";
		*/	
		
if($_REQUEST['codigo']){
	$_sql = "SELECT c.codloja
				FROM 
				cadastro c INNER JOIN
				logon l ON c.codloja = l.codloja	
				WHERE MID(logon,1,LOCATE('S', logon) - 1) = '{$_REQUEST['codigo']}'";
	$_qry = mysql_query($_sql, $con);	
	$codloja   = mysql_result($_qry,0,'codloja');
	$sql .=" AND c.codloja = '$codloja' ";
	if ( ($tipo == "a") or ($tipo == "c") ){
		$sql .= "";
	}else{
		$sql .=" AND a.id_franquia = '$franquia' ";
	}
}else{
	if ( ($tipo == "a") or ($tipo == "c") ){
		if($_REQUEST['franqueado'] > 0){
			$sql .=" AND a.id_franquia = '{$_REQUEST['franqueado']}' ";
		}
	}else{
			$sql .=" AND a.id_franquia = '$franquia' ";
		}
	
	if($_REQUEST['contano']){ 
		if($_REQUEST['contano'] != "todos"){
			$sql .=" AND SUBSTRING(b.data_criacao, 1, 4) = '{$_REQUEST['contano']}' ";
		}	
	}	
	
	if($_REQUEST['contmes'] > 0){ 
		$sql .=" AND SUBSTRING(b.data_criacao, 6, 2) = '{$_REQUEST['contmes']}' ";
	}	
	
	if( ($_REQUEST['ativo'] == "I") or ($_REQUEST['ativo'] == "A") ){ 
		$sql .=" AND b.ativo = '{$_REQUEST['ativo']}' ";
	}	
	$sql .=	" GROUP BY a.codloja ";
}

$sql .=" ORDER BY {$_REQUEST['ordenacao']} ";
$qry = mysql_query($sql,$con);
$total = mysql_num_rows($qry);
if($total == "0"){
	echo "<div align='center'><b><p>Não foi encontrado nenhum Usuário WEB-CONTROL !</b></div>";
	echo "<div align='center'><b><p><a href='painel.php?pagina1=area_restrita/web_control_extrato_usuarios.php'><b>Retorna</b></a></b></div>";
	exit;
}			
?>
<form action="#" method="post" name="form">

<table border='0' width='850' align='center' cellpadding='0' cellspacing='1' style='border:1px dashed #E8E8E8; background-color:#FFFFFF'>
<tr><td colspan="7" align="center" class="titulo">Listagem de Usuário(s) WEB-CONTROL</td></tr>
<tr bgcolor="#FF9900">
	<td width="7%"><b>Código</b></td>
    <td><b>Nome Fantasia</b></td>
    <td width="9%"><b>Usuário</b></td>
    <td width="9%"><b>Senha</b></td>
    <td width="12%"><b>Dt Liberação</b></td>
    <td width="7%"><b>Master</b></td>
    <td width="6%"><b>Ativo</b></td>
</tr>
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
	<td><?=$rs['logon']?></td>
    <td><?=$rs['nomefantasia']?></td>
    <td><?=$rs['login']?></td>
    <td><?=$rs['senha']?></td>
    <td><?=$rs['data_criacao']?></td>
    <td><?=$rs['login_master']?></td>
    <td>
    <?php if($rs['ativo2'] == "A"){ $desc_ativo = "I";} ?>
    <?php if($rs['ativo2'] == "I"){ $desc_ativo = "A";} ?>     
    <a href="painel.php?pagina1=area_restrita/web_control_desabilita_habilita_user.php&id=<?=$rs['id']?>&contano=<?=$_REQUEST['contano']?>&contmes=<?=$_REQUEST['contmes']?>&ativo=<?=$_REQUEST['ativo']?>&ordenacao=<?=$_REQUEST['ordenacao']?>&ativo=<?=$desc_ativo?>&franqueado=<?=$_REQUEST['franqueado']?>&codigo=<?=$_REQUEST['codigo']?>"><?=$rs['ativo']?></a></td>    
</tr>
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
<tr>
	<td width="10%"><a href="painel.php?pagina1=area_restrita/web_control_extrato_usuarios.php"><b>Retorna</b></a></td>     
    <td width="45%" align="right">Quantidade de Consumidores Cadastrados: <b><?=$total_user?></b></td>
    <td width="45%" align="right">Total de <b><?=$total?></b> Usuário(s) WEB-CONTROL</td>
</tr>
</table>
<p>