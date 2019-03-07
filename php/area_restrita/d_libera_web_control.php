<?php
// require_once('../connect/sessao.php');
session_start();

$name = $_SESSION["ss_name"];
$tipo = $_SESSION["ss_tipo"];
$data_cadastro = date("Y-m-d");

//echo "- ".$name = $_SESSION["id"];

if ( $name == "" ){
	session_unregister($_SESSION['name']);
	session_destroy();
	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
	die;
}
 
$liberado_ok = "FALSE";
if( ($_REQUEST['libera'] == '2') and ($_REQUEST['codloja'] != "") ) {
	// Verifico se é o primeiro usuario do webcontrol para o codigo do cliente
	
	$sql_prim_user = "select count(*) qtd from base_web_control.usuario  where id_cadastro = '{$_REQUEST['codloja']}'";
	$qry_prim_user = mysql_query($sql_prim_user,$con);
	$total_primer_user  = mysql_result($qry_prim_user,0,'qtd');	
	if ( empty($total_primer_user) ) $total_primer_user = '0';
	
	if ( $total_primer_user == '0' ) $login_master = 'S';
		else $login_master = 'N';
	
	//habilita o usuario a ter o acesso ao WEB-CONTROL
	$up_sql = "UPDATE cs2.cadastro SET liberado_web_control = 'S', dt_libera_web = now() WHERE codloja = '{$_REQUEST['codloja']}'";
	$up_qry = mysql_query($up_sql,$con);
	
	//Faz o insert para criar um funcionario e usuario para o web-control
	$sql_libera = "SELECT  lo.logon, cad.nomefantasia AS nome
				   FROM cs2.cadastro cad INNER JOIN
				   cs2.logon lo ON cad.codloja = lo.codloja 
				   WHERE lo.codloja = '{$_REQUEST['codloja']}'";
	$qry_libera = mysql_query($sql_libera,$con);
	$nome  = mysql_result($qry_libera,0,'nome');	
	$_login = mysql_result($qry_libera,0,'logon');		
	$findme   = 'S';
	$pos = strpos($_login, $findme);
	$login = substr($_login, 0, $pos); 
	$senha = substr($_login, -$pos, 5);
	
	//cria o funcionario	
	$sql_func = "INSERT INTO base_web_control.funcionario 
						(nome, id_cadastro, data_cadastro, cpf) 
				 VALUES ('$nome', '{$_REQUEST['codloja']}', '$data_cadastro', '{$_REQUEST['cpfsocio1']}')";
	$qry_func = mysql_query($sql_func,$con);
	$id_funcionario= mysql_insert_id(); 
	
	//cria o usuario	
	$sql_user = "INSERT INTO base_web_control.usuario 
						(login, senha, data_criacao, id_cadastro, id_funcionario, ativo, login_master)
				 VALUES ('$login', '$senha', '$data_cadastro', '{$_REQUEST['codloja']}', '$id_funcionario', 'A', '$login_master')";
	$qry_user = mysql_query($sql_user,$con);
	$id_usuario= mysql_insert_id();  	
	
	//cria a permissao do menu administrativo se for o primeiro
	if ( $total_primer_user == '0' ) {
		$sql_per_user = "INSERT INTO base_web_control.permissao_usuario 
								 (id_modulo, id_cod_permissao, id_usuario)
						  VALUES ('9','401','$id_usuario')";
		$qry_per_user = mysql_query($sql_per_user,$con);
		$sql_per_user2 = "INSERT INTO base_web_control.permissao_usuario 
								 (id_modulo, id_cod_permissao, id_usuario)
						  VALUES ('9','402','$id_usuario')";
		$qry_per_user2 = mysql_query($sql_per_user2,$con);
		
//CLIENTE
$sql_per1 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('1','1','$id_usuario')";
$qry_per1 = mysql_query($sql_per1,$con);
$sql_per2 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('1','2','$id_usuario')";
$qry_per2 = mysql_query($sql_per2,$con);
$sql_per3 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('1','3','$id_usuario')";
$qry_per3 = mysql_query($sql_per3,$con);
$sql_per4 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('1','4','$id_usuario')";
$qry_per4 = mysql_query($sql_per4,$con);
$sql_per5 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('1','5','$id_usuario')";
$qry_per5 = mysql_query($sql_per5,$con);

//FUNCIONARIO 
$sql_per6 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('2','51','$id_usuario')";
$qry_per6 = mysql_query($sql_per6,$con);
$sql_per7 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('2','52','$id_usuario')";
$qry_per7 = mysql_query($sql_per7,$con);
$sql_per8 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('2','53','$id_usuario')";
$qry_per8 = mysql_query($sql_per8,$con);
$sql_per9 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('2','301','$id_usuario')";
$qry_per9 = mysql_query($sql_per9,$con);

//FORNECEDOR
$sql_per10 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('3','101','$id_usuario')";
$qry_per10 = mysql_query($sql_per10,$con);
$sql_per11 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('3','102','$id_usuario')";
$qry_per11 = mysql_query($sql_per11,$con);
$sql_per12 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('3','103','$id_usuario')";
$qry_per12 = mysql_query($sql_per12,$con);
$sql_per13 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('3','104','$id_usuario')";
$qry_per13 = mysql_query($sql_per13,$con);
$sql_per14 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('3','105','$id_usuario')";
$qry_per14 = mysql_query($sql_per14,$con);

//COMPROMISSO
$sql_per15 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('4','151','$id_usuario')";
$qry_per15 = mysql_query($sql_per15,$con);
$sql_per16 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('4','152','$id_usuario')";
$qry_per16 = mysql_query($sql_per16,$con);
$sql_per17 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('4','154','$id_usuario')";
$qry_per17 = mysql_query($sql_per17,$con);
$sql_per18 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('4','155','$id_usuario')";
$qry_per18 = mysql_query($sql_per18,$con);

//ESTOQUE
$sql_per19 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('5','203','$id_usuario')";
$qry_per19 = mysql_query($sql_per19,$con);
$sql_per20 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('5','202','$id_usuario')";
$qry_per20 = mysql_query($sql_per20,$con);
$sql_per21 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('5','201','$id_usuario')";
$qry_per21 = mysql_query($sql_per21,$con);
$sql_per22 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('5','204','$id_usuario')";
$qry_per22 = mysql_query($sql_per22,$con);
$sql_per23 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('5','205','$id_usuario')";
$qry_per23 = mysql_query($sql_per23,$con);
$sql_per24 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('5','206','$id_usuario')";
$qry_per24 = mysql_query($sql_per24,$con);
$sql_per25 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('5','207','$id_usuario')";
$qry_per25 = mysql_query($sql_per25,$con);
$sql_per26 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('5','209','$id_usuario')";
$qry_per26 = mysql_query($sql_per26,$con);
$sql_per27 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('5','210','$id_usuario')";
$qry_per27 = mysql_query($sql_per27,$con);

//FINANCEIRO
$sql_per28 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('7','302','$id_usuario')";
$qry_per28 = mysql_query($sql_per28,$con);
$sql_per29 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('7','303','$id_usuario')";
$qry_per29 = mysql_query($sql_per29,$con);
$sql_per30 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('7','304','$id_usuario')";
$qry_per30 = mysql_query($sql_per30,$con);
$sql_per31 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('7','305','$id_usuario')";
$qry_per31 = mysql_query($sql_per31,$con);
$sql_per32 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('7','306','$id_usuario')";
$qry_per32 = mysql_query($sql_per32,$con);
$sql_per33 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('7','307','$id_usuario')";
$qry_per33 = mysql_query($sql_per33,$con);
$sql_per34 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('7','308','$id_usuario')";
$qry_per34 = mysql_query($sql_per34,$con);
$sql_per344 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('7','311','$id_usuario')";
$qry_per344 = mysql_query($sql_per344,$con);

//VENDA
$sql_per35 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('10','451','$id_usuario')";
$qry_per35 = mysql_query($sql_per35,$con);
$sql_per36 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('10','453','$id_usuario')";
$qry_per36 = mysql_query($sql_per36,$con);
$sql_per37 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('10','452','$id_usuario')";
$qry_per37 = mysql_query($sql_per37,$con);
$sql_per38 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('10','454','$id_usuario')";
$qry_per38 = mysql_query($sql_per38,$con);
$sql_per39 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('10','456','$id_usuario')";
$qry_per39 = mysql_query($sql_per39,$con);
//AGENDA TELEFONICA
$sql_per42 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('11','501','$id_usuario')";
$qry_per42 = mysql_query($sql_per42,$con);
$sql_per43 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('11','502','$id_usuario')";
$qry_per43 = mysql_query($sql_per43,$con);
$sql_per44 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('11','503','$id_usuario')";
$qry_per44 = mysql_query($sql_per44,$con);
$sql_per45 = "INSERT INTO base_web_control.permissao_usuario (id_modulo, id_cod_permissao, id_usuario)VALUES ('11','504','$id_usuario')";
$qry_per45 = mysql_query($sql_per45,$con);
	}
	
	//libera as sugstoes
	$sql_per_user1 = "INSERT INTO base_web_control.permissao_usuario 
							 (id_modulo, id_cod_permissao, id_usuario)
				 	  VALUES ('8','351','$id_usuario')";
	$qry_per_user1 = mysql_query($sql_per_user1,$con);
	
	$sql_per_user2 = "INSERT INTO base_web_control.permissao_usuario 
							 (id_modulo, id_cod_permissao, id_usuario)
				 	  VALUES ('8','352','$id_usuario')";
	$qry_per_user2 = mysql_query($sql_per_user2,$con);
	//-----------------------------------------------------------------------
	
	//grava a jornada de trabalho
	for($i=0; $i<=7; $i++)
	{
		$entrada_1 = $_REQUEST['entrada_1'][$i];
		$saida_1   = $_REQUEST['saida_1'][$i];
		$entrada_2 = $_REQUEST['entrada_2'][$i];
		$saida_2   = $_REQUEST['saida_2'][$i];
		
		$sql_jornada = "INSERT INTO base_web_control.funcionario_horario_trabalho
					(id_funcionario, id_cadastro, id_semana, entrada_1, saida_1, entrada_2, saida_2)
					VALUES
					('$id_funcionario', {$_REQUEST['codloja']}, '$i', '$entrada_1', '$saida_1', '$entrada_2', '$saida_2')";			
		$qry_jornada = mysql_query($sql_jornada,$con);
	}		
	
	$libera = '1';
	$liberado_ok = "TRUE";
}
?>
<script language="javascript">
function buscaClienteLibera(){		
 	frm = document.form;	
    frm.action = 'painel.php?pagina1=area_restrita/d_libera_web_control.php';
	frm.submit();
}

function confirmaLiberacao(){		
   if (confirm("Tem certeza que deseja liberar o uso do WEB-CONTROL ?")){
		frm2 = document.form2;
		frm2.action = 'painel.php?pagina1=area_restrita/d_libera_web_control.php';
		frm2.submit();
	} 	
}
</script>
<p>
<form action="#" method="post" name="form">
	<input type="hidden" name="libera" value="1">
	<table border='0' width='650' align='center' cellpadding='0' cellspacing='1' style='border:1px dashed #E8E8E8; background-color:#FFFFFF'>
		<tr><td colspan="2" align="center" bgcolor="#E8E8E8" height="23px"><font size="+1">Liberação WEB-CONTROL</font></td></tr>
		<tr><td height="23px" width="30%" bgcolor="#F5F5F5"><b>&nbsp;Código do Cliente</b></td><td width="70%" bgcolor="#F0F0F6">&nbsp;<input type="text" name="cod_cliente" maxlength="10" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" >&nbsp;&nbsp;&nbsp;
				<input type="button" name="Confirma" value="Confirma" onclick="buscaClienteLibera()"></td></tr>
	</table>
</form>

<p>

<?php if( ($_REQUEST['libera'] == "1") and ($_REQUEST['cod_cliente'] != "") ) { ?>

<form action="#" method="post" name="form2">
<input type="hidden" name="libera" value="2">

<table border='0' width='650' align='center' cellpadding='0' cellspacing='1' style='border:1px dashed #E8E8E8; background-color:#FFFFFF'>
<?php
    if($_SESSION["id"] == "5"){
		$var = "1";
	}else{
		$var = "{$_SESSION['id']}";
	}
	 $sql = "SELECT
			  c.cpfsocio1, c.codloja, l.logon, c.razaosoc, c.nomefantasia, c.liberado_web_control AS  liberado_web_control2 
			  ,CASE  liberado_web_control
				 WHEN 'S' THEN '<font color=green><b>Sim</b></font>'
				 WHEN 'N' THEN '<font color=red><b>Não</b></font>'
			   END AS liberado
			FROM
			  cs2.cadastro c INNER JOIN 
			  cs2.logon l ON c.codloja = l.codloja
			WHERE 1=1 ";
			if($_SESSION['id'] != 163){
				$sql .= " AND c.id_franquia = '$var'  ";
			}
			$sql .= " AND l.logon LIKE '{$_REQUEST['cod_cliente']}%'";

	$qry = mysql_query($sql, $con);
	$total = mysql_num_rows($qry);
	if ($rs = mysql_fetch_array($qry)) {	
		$liberado_web_control = $rs['liberado_web_control2'];
		$codloja = $rs['codloja'];
		$cpfsocio1 = $rs['cpfsocio1'];
		echo "<input type='hidden' name='codloja' value='$codloja'>";
		echo "<input type='hidden' name='cpfsocio1' value='$cpfsocio1'>";
//		echo "<tr><td height='23px' width='30%' bgcolor='#F5F5F5'><b>Código do Cliente</b></td><td bgColor='#F0F0F6' width='70%'>&nbsp;".$rs['codloja']."</td></tr>";
		echo "<tr><td height='23px' bgcolor='#F5F5F5' width='30%'><b>&nbsp;Razão Social</b></td><td bgColor='#F0F0F6' width='70%'>&nbsp;".$rs['razaosoc']."</td></tr>";
		echo "<tr><td height='23px' bgcolor='#F5F5F5'><b>&nbsp;Nome Fantasia</b></td><td bgColor='#F0F0F6'>&nbsp;".$rs['nomefantasia']."</td></tr>";
		echo "<tr><td height='23px' bgcolor='#F5F5F5'><b>&nbsp;Liberado Web-Control</b></td><td bgColor='#F0F0F6'>&nbsp;".$rs['liberado']."</td></tr>";
	}
?>
</table>
	<?php } ?>

	<?php if( ($total > 0) and ($liberado_web_control == "N")){ ?>
	<p>

	<table border='0' width='650' align='center' cellpadding='0' cellspacing='1' style='border:1px dashed #E8E8E8; background-color:#FFFFFF'>
		<tr><td colspan="2" bgcolor="#E8E8E8" align="center" height="50px"><b>Será Cobrado na fatura do cliente o valor de R$</b> 0,00</td></tr>
		<tr><td width="30%" height="40px">&nbsp;</td><td align="left" width="70%"><input type="button" name="Confirma" value="Confirma Habilitar Web-Control" onclick="confirmaLiberacao()"></td></tr>
	</table>
	</form>
<?php } ?>

<?php if( ($_REQUEST['libera'] == "1") and ($_REQUEST['cod_cliente'] != "") and ($total == "0") ) { ?>
	<b><div align="center"><u>N&atilde;o foi encontrado o Cliente com C&oacute;digo:</u> <font color="#FF0000"><?=$_REQUEST['cod_cliente']?></font> ou N&atilde;o pertence a sua Franquia</div></b>
<?php } ?>

<?php if($liberado_ok == "TRUE") { ?>
	<b><div align="center">Cliente Liberado o uso do WEB-CONTROL</div></b>
<?php } ?>

