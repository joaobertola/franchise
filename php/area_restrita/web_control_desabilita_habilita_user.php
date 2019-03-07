<?
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

	$contano    = $_REQUEST['contano'];
	$contmes    = $_REQUEST['contmes'];
	$ativo      = $_REQUEST['ativo'];
	$ordenacao  = $_REQUEST['ordenacao'];
	$id         = $_REQUEST['id'];
	$franqueado = $_REQUEST['franqueado'];	
	$codigo     = $_REQUEST['codigo'];	
	
	$sql = "UPDATE 
						base_web_control.usuario 
				 SET 
				 		ativo			= '{$_REQUEST['ativo']}'";
	if($_REQUEST['ativo'] == "I"){
		$sql .= " , data_desabilita = now()";
	}	
	$sql .= " WHERE id = $id";

	$qry = mysql_query($sql);
	echo "<meta http-equiv='refresh' content='0; url= painel.php?pagina1=area_restrita/web_control_listagem_usuarios.php&contano=$contano&contmes=$contmes&ordenacao=$ordenacao&franqueado=$franqueado&codigo=$codigo'>";	
?>