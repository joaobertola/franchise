<?php
require_once('../connect/sessao.php');
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
?>
<script language="javascript">
function valida(){
	frm = document.form;	
	if(confirm("Deseja realmente excluir ?")) {
  		confirmaExclusao();
	} 
}

function confirmaExclusao(){
 	frm = document.form;
  frm.action = 'area_restrita/virtual_flex_excluir_dominio.php';
	frm.submit();
}
function retorna(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=area_restrita/virtual_flex_busca_excluir_dominio.php';
	frm.submit();
}
</script>
<?php
	if( ($_SESSION['id'] == '163') or ($_SESSION['id'] == '5') ){
		$id_franquia = '1';
	}else{
		$id_franquia = $_SESSION['id'];
	}	
	
	if($_SESSION['id'] == '163' or $_SESSION['id'] == '46' or $_SESSION['id'] == '4' or $_SESSION['id'] == '1204'){
		$sql = "SELECT
				 c.codloja, l.logon, c.razaosoc, c.nomefantasia
				FROM
				  cs2.cadastro c INNER JOIN
				  cs2.logon l ON c.codloja = l.codloja
				WHERE l.logon LIKE '{$_REQUEST['cod_cliente']}%'";
		$qry = mysql_query($sql, $con);
	}else{
		$sql = "SELECT
				 c.codloja, l.logon, c.razaosoc, c.nomefantasia
				FROM
				  cs2.cadastro c INNER JOIN
				  cs2.logon l ON c.codloja = l.codloja
				WHERE 
					c.id_franquia = '$id_franquia'
				AND 
					l.logon LIKE '{$_REQUEST['cod_cliente']}%'
				AND l.franqueado = 'S' ";
		$qry = mysql_query($sql, $con);
	}
	
	$total = mysql_num_rows($qry);
	$razaosoc = mysql_result($qry,0,'razaosoc');
	$nomefantasia = mysql_result($qry,0,'nomefantasia');
	$id_cadastro = mysql_result($qry,0,'codloja');

//seleciona o dominio
$usermy = "csinform";
$passwordmy = "inform4416#scf";
$nomedb = "dbsites";
$conexao = @mysql_pconnect("10.2.2.3",$usermy,$passwordmy)or die ("Falha ao Conectar no BD Sites".mail("lucianomancini@gmail.com","problemas na conexao com mysql"," From:lucianomancini@gmail.com"));
$bd=mysql_select_db($nomedb,$conexao) or die("Nao foi posivel selecionar o banco de dados contate o administrador erro 30");

$sql_d = "SELECT fra_nomesite, fra_dominio FROM tbl_framecliente WHERE fra_codloja = '$id_cadastro'";
$qry_d = mysql_query($sql_d, $conexao ) or die ("\nErro na query:\n". mysql_errno($con) .": ". mysql_error($con) ."\n\n");
$dominio = mysql_result($qry_d,0,'fra_nomesite');
$fra_dominio = mysql_result($qry_d,0,'fra_dominio');
$total = mysql_num_rows($qry_d);

if($total == "0") {
	
	$msg = "CLIENTE : [ ".$_REQUEST['cod_cliente']."]   /   NAO foi encontrado o Cliente   /   NAO Pertence a sua Franquia     ou     NAO Tem  SITE  criado.";
	
	echo "<script>alert('$msg');history.back();</script>";
	exit;
}

?>
<p>
<form action="#" method="post" name="form">
<input type="hidden" name="id_cadastro" value="<?=$id_cadastro?>">
<input type="hidden" name="dominio" value="<?=$dominio?>">
<table border='0' width='750' align='center' cellpadding='0' cellspacing='1' style='border:1px dashed #E8E8E8; background-color:#FFFFFF'>
<tr>
  <td colspan="2" align="center" class="titulo">Eliminar Informa&ccedil;&otilde;es Virtual - Flex</td>
</tr>
	<tr>
    	<td bgcolor="#E8E8E8" height="25"><b>Logon de Acesso</b></td>
        <td bgcolor="#F0F0F6"><?=$_REQUEST['cod_cliente']?></td>
    </tr>
    <tr>
    	<td bgcolor="#E8E8E8" height="25"><b>Raz&atilde;o Social</b></td>
        <td bgcolor="#F0F0F6"><?=$razaosoc?></td>
    </tr>
    
    <tr>
    	<td bgcolor="#E8E8E8" height="25"><b>Nome Fantasia</b></td>
        <td bgcolor="#F0F0F6"><?=$nomefantasia?></td>
    </tr>
    
    <tr>
    	<td bgcolor="#E8E8E8" height="25"><b>Dom&iacute;nio</b></td>
        <td bgcolor="#F0F0F6">http://www.<?=$dominio?>.<?=$fra_dominio?></td>
    </tr>
<tr>
  <td colspan="2" align="left">
  	<b><font color="#FF0000">As informa&ccedil;&otilde;es que forem exclu&iacute;das n&atilde;o ter&atilde;o como ser recuperadas</font></b>
  </td>
  </tr>
<tr>
	<td height="40"></td>
    <td>
    	<?php if ( $id_cadastro == 1 ) {
			echo "<script>alert('Desculpe, o site deste codigo NAO PODE SER EXCLUIDO')</script>";
		} else { ?> 
	    	<input type="button" value="Confirma a Exclus&atilde;o" name="exclusao" onclick="valida()" />
        <?php } ?>
        &nbsp;&nbsp;&nbsp;
        <input type="button" value="Retorna" name="exclusao" onclick="retorna()" />
    </td>
</tr>
</table>
</form>