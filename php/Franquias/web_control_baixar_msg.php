<?php
require_once('../connect/sessao.php');
//session_start();
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//if (($name=="") && ($tipo!="a") && ($tipo!="d")){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}

require_once("../connect/conexao_conecta.php");

function telefoneConverte($p_telefone){
     if ($p_telefone == '') {
	   return ('');	   
	 } else { 	   
	   $a = substr($p_telefone, 0,2);   
	   $b = substr($p_telefone, 2,4);   
	   $c = substr($p_telefone, 6,4);   
	   
	   $telefone_mascarado  = "(";
   	   $telefone_mascarado .= $a;
	   $telefone_mascarado .= ")&nbsp;";
	   $telefone_mascarado .= $b;
	   $telefone_mascarado .= "-";
	   $telefone_mascarado .= $c;
	   return ($telefone_mascarado);
	 }  
}

$sql = "SELECT
		  s.id, date_format(s.data_envio,'%d/%m/%Y') AS data_envio,  fu.nome,  cadastro.razaosoc, s.descricao_envio,
		  date_format(s.data_lida,'%d/%m/%Y') AS data_lida, s.id_franquia_registra_baixa, s.descricao_lida, s.lida,
		  fu.email, fu.telefone, fu.celular, cadastro.id_franquia
		FROM
		  base_web_control.sugestao s INNER JOIN
		  base_web_control.usuario u ON s.id_usuario_envio = u.id INNER JOIN
		  base_web_control.funcionario fu ON u.id_funcionario = fu.id INNER JOIN
		  cs2.logon ON logon.codloja = s.id_cadastro INNER JOIN
		  cs2.cadastro ON cadastro.codloja = logon.codloja
		WHERE  s.id = '{$_REQUEST['id']}'";
$qry = mysql_query($sql,$con);
$data_envio = mysql_result($qry,0,'data_envio');
$nome = mysql_result($qry,0,'nome');
$razaosoc = mysql_result($qry,0,'razaosoc');
$descricao_envio = mysql_result($qry,0,'descricao_envio');
$lida  = mysql_result($qry,0,'lida');
$descricao_lida  = mysql_result($qry,0,'descricao_lida');
$data_lida  = mysql_result($qry,0,'data_lida');
$id_franquia_registra_baixa  = mysql_result($qry,0,'id_franquia_registra_baixa');
$email  = mysql_result($qry,0,'email');
$telefone  = telefoneConverte(mysql_result($qry,0,'telefone'));
$celular  = telefoneConverte(mysql_result($qry,0,'celular'));
$id_franquia = mysql_result($qry,0,'id_franquia');

//seleciona a franquia da pessoa que envio a sugest�o
$sql_fr = "SELECT fantasia FROM cs2.franquia WHERE id='$id_franquia'";
$qry_fr = mysql_query($sql_fr,$con);
$fantasia_env = mysql_result($qry_fr,0,'fantasia');

//pega o responsavel pela baixa
$sql_resp = "SELECT fantasia FROM cs2.franquia WHERE id = '$id_franquia_registra_baixa'";
$qry_resp = mysql_query($sql_resp,$con);
if (mysql_num_rows($qry_resp) == 0) {
	$responsavel = '';
} else {
	$responsavel = mysql_result($qry_resp,0,'fantasia');
}


unset($opcao);
if($lida == "D"){
	$topo = " [ Em Desenvolvimento ]";
	$opcao = 2;
	$nome_button = "Confirma a Baixa da Sugestão";
	$java = "valida()";
}
if($lida == "N"){
	$topo = " [ Em Análise ]";
	$opcao = 1;
	$nome_button = "Confirma Início do Desenvolvimento";
	$java = "baixa()";
}
if($lida == "S"){
	$topo = " [ Já foram Desenvolvidas ]";
}
?>
<script>
function baixa(){	
 	frm = document.form;	
    frm.action = 'web_control_baixar_msg_bd.php';
	frm.submit();
}

function valida(){
	if(document.form.descricao_lida.value == ""){
		alert('Falta informar a descrição da baixa ! ');
		document.form.descricao_lida.focus();
		return false;
	}
	baixa();
}
</script>
<body bgcolor="#F8F8FF">
<form name="form" action="web_control_baixar_msg_bd.php" method="post">
<input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
<input type="hidden" name="opcao" value="<?=$opcao?>">
<table border="0" align="center" width="730" cellpadding="1" cellspacing="2">
<tr><td colspan="4" align="center" bgcolor="#CCCCCC"><b>Sugestão WEB-CONTROL&nbsp;&nbsp;<?=$topo?></b></td></tr>
<tr bgcolor="87b5ff">
    <td bgcolor="87b5ff" width="18%"><b>Data Envio</b></td><td width="82%" bgcolor="#E8E8E8"><?=$data_envio?></td></tr>    
	<td bgcolor="87b5ff"><b>Franquia</b></td><td bgcolor="#E8E8E8"><?=$fantasia_env?></td></tr>    
	<td bgcolor="87b5ff"><b>Funcionário</b></td><td bgcolor="#E8E8E8"><?=$nome?></td></tr>
	<td bgcolor="87b5ff"><b>E-mail</b></td><td bgcolor="#E8E8E8"><?=$email?></td></tr>
	<td bgcolor="87b5ff"><b>Telefone</b></td><td bgcolor="#E8E8E8"><?=$telefone?></td></tr>
	<td bgcolor="87b5ff"><b>Celular</b></td><td bgcolor="#E8E8E8"><?=$celular?></td></tr>
    <td bgcolor="87b5ff"><b>Franquia</b></td><td bgcolor="#E8E8E8"><?=$razaosoc?></td></tr>
    <td bgcolor="87b5ff"><b>Descri&ccedil;&atilde;o</b></td><td align="justify" bgcolor="#E8E8E8"><?=$descricao_envio?></td>
</tr>

<?php if($lida == "D"){?>
<tr><td colspan="2" align="center" bgcolor="#CCCCCC"><b>Descrição para dar Baixa na Sugestão</b></td></tr>
<tr bgcolor="87b5ff">
    <td bgcolor="87b5ff" width="20%"><b>Data Baixa</b></td><td width="80%" bgcolor="#E8E8E8"><?=date("d/m/Y")?></td></tr>
    <td bgcolor="87b5ff"><b>Descri&ccedil;&atilde;o</b></td><td bgcolor="#E8E8E8">
    <textarea style="width:99%" rows="5" name="descricao_lida"></textarea>
    </td>
</tr>
<?php } ?>

<?php if($lida == "S"){?>
<tr><td colspan="2" align="center" bgcolor="#CCCCCC"><b>Descrição do Fechamento da Sugestão</b></td></tr>

<tr bgcolor="87b5ff">
    <td bgcolor="87b5ff" width="20%"><b>Data Baixa</b></td><td width="80%" bgcolor="#E8E8E8"><?=$data_lida?></td></tr>
    <td bgcolor="87b5ff" width="20%"><b>Responsável</b></td><td width="80%" bgcolor="#E8E8E8"><?=$responsavel?></td></tr>
    <td bgcolor="87b5ff"><b>Descri&ccedil;&atilde;o</b></td><td bgcolor="#E8E8E8"><?=$descricao_lida?></td>
</tr>
<?php } ?>

<tr>
	<td>&nbsp;</td>
	<td>
    	<?php if( ($lida == "D") || ($lida == "N") ){?>
        <input type="submit" value="<?=$nome_button?>" onClick="<?=$java?>" name="baixa">&nbsp;&nbsp;
        <?php } ?>
        <input type="button" value="Fecha Janela" onClick="window.close()">
    </td>
</tr>

</table>
</form>
</body>