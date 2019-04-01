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

$sql = "SELECT
		 s.id, date_format(s.data_envio,'%d/%m/%Y') AS data_envio,  fu.nome, cadastro.id_franquia, s.descricao_envio
		FROM
		  base_web_control.sugestao s INNER JOIN
		  base_web_control.usuario u ON s.id_usuario_envio = u.id INNER JOIN
		  base_web_control.funcionario fu ON u.id_funcionario = fu.id INNER JOIN
		  cs2.logon ON logon.codloja = s.id_cadastro INNER JOIN
		  cs2.cadastro ON cadastro.codloja = logon.codloja
		WHERE 1=1
		AND s.lida = '{$_REQUEST['lida']}' ";

if($_REQUEST['cod_franquia']) { 
	$sql .=" AND cadastro.id_franquia = '{$_REQUEST['cod_franquia']}' ";
}

$sql .=" ORDER BY s.data_envio ASC";
		
$qry = mysql_query($sql,$con);
$total = mysql_num_rows($qry);

if($_REQUEST['lida'] == 'D'){
	$topo = " [ Em Desenvolvimento ]";
}
if($_REQUEST['lida'] == 'N'){
	$topo = " [ Em Análise ]";
}
if($_REQUEST['lida'] == 'S'){
	$topo = " [ Já foram Desenvolvidas ]";
}

if($total == "0"){
	echo "<p><div align='center'><font color='red'><b>Não foi encontrada nenhum sugestão com os critérios de consulta ! </b></font>";
	echo "<p><a href='painel.php?pagina1=Franquias/web_control_busca_sugestao.php'><b>Retorna a Tela de Consulta</b></a></div>";
	exit;
}
?>
<script>
   function lerSugestao(p_id) {
	    msg  = open('Franquias/web_control_baixar_msg.php?id='+p_id, 'janela', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width='+750+',height='+550+',left='+10+', top='+10+',screenX='+100+',screenY='+100+'');
  }	 
</script>
<table border="0" align="center" width="800" cellpadding="1" cellspacing="2">
<tr><td colspan="5" align="center" class="titulo">Listagem de Sugestão WEB-CONTROL&nbsp;&nbsp;<?=$topo?></td></tr>
<tr bgcolor="87b5ff">
	<td width="5%" align="center"><b>Cód</b></td>
    <td width="10%"><b>Data Envio</b></td>
	<td width="40%"><b>Funcionário</b></td>
    <td width="40%"><b>Franquia</b></td>
    <td width="5%">&nbsp;</td>
</tr>
<?php 
$cont=0;
while($rs = mysql_fetch_array($qry)) { 
$cont++;
if($cont%2 == "1")$cor = "#E8E8E8";
	else
		$cor = "";
?>
	<tr bgcolor="<?=$cor?>">
    	<td align="center"><?=$rs['id']?></td>
        <td><?=$rs['data_envio']?></td>
        <td><?=$rs['nome']?></td>
        <td>
		<?php
        	//seleciona a franquia da pessoa que envio a sugestao
			$sql_fr = "SELECT fantasia FROM cs2.franquia WHERE id='{$rs['id_franquia']}'";
			$qry_fr = mysql_query($sql_fr,$con);
			echo mysql_result($qry_fr,0,'fantasia');		
		?>
        </td>
        <td align="center"><a href="#" onclick="lerSugestao(<?=$rs['id']?>)"><b>Ler</b></a></td>
    </tr>
<?php } ?>
</table>
<hr width="90%">
<table border="0" align="center" width="800">
	<tr>
     <td width="50%"><a href="painel.php?pagina1=Franquias/web_control_busca_sugestao.php"><b>Retorna a Tela de Consulta</b></a></td>
     <td width="50%" align="right">Total de <b><?=$total?></b> Registro(s) Encontrado(s)</td>
    </tr>
</table>
