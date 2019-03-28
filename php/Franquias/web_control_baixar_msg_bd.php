<?
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
$id = $_REQUEST['id'];

if($_REQUEST['opcao'] == "1")
{
	$sql = "UPDATE base_web_control.sugestao SET 
				lida           = 'D'
	    WHERE
				id = '$id'";
	$qry = mysql_query($sql);
}

if($_REQUEST['opcao'] == "2")
{
	$descricao_lida = strtoupper($_REQUEST['descricao_lida']);	
	$descricao_lida = str_replace("'","",$descricao_lida);
	$id_franquia_registra_baixa = $_SESSION["id"];
	
	$sql = "UPDATE base_web_control.sugestao SET 
					descricao_lida = '$descricao_lida',
					data_lida      = now(),
					lida           = 'S',
					id_franquia_registra_baixa = '$id_franquia_registra_baixa'
			WHERE
					id = '$id'";
	$qry = mysql_query($sql);
}
header("location: web_control_baixar_msg.php?id=$id");					
?>
