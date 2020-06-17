<?php
//require_once('../connect/sessao.php');
//session_start();
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//if (($name=="") || ($tipo!="a")){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}

?>


<form action="#" method="post">
<input type="hidden" name="del" value="1">
<input type="hidden" name="codloja" value="<?=$_REQUEST['codloja']?>">
<div align="center"><input type="password" name="senha_del" maxlength="5">
<input type="submit"  value="Confirma Exclusao"></div>

</form>


<?php

$x = $_REQUEST['del'];
if( $x == 1){

	$senha_del = $_REQUEST['senha_del'];
	$sql = "SELECT count(*) qtd from cs2.franquia
	 		 WHERE usuario = 'wellington' and senha_restrita = '$senha_del' ";	
	$qr = mysql_query($sql,$con) or die ("erro ao selecionar o usuario $sql");
	
	$registro = mysql_fetch_array($qr);
	$qtd = $registro['qtd'];
	if ( $qtd > 0 ){
		
		$codloja = $_REQUEST['codloja'];
		//exclui o cadastro
		$query = "delete from cadastro where codloja='$codloja'";
		mysql_query ($query, $con);
		// registrando log
		$teste = str_replace(chr(39),'',$query);
		$sql = "insert into cs2.sql_cadastro(sql,datahora) values('$teste',now())";
		mysql_query($sql, $con);
		$query = "delete from bonificadas where codloja='$codloja'";
		mysql_query ($query, $con);
		$query = "delete from logon where codloja='$codloja'";
		mysql_query ($query, $con);
		$query = "delete from cons_liberada where codloja='$codloja'";
		mysql_query ($query, $con);
		$query = "delete from valconscli where codloja='$codloja'";
		mysql_query ($query, $con);
		$res = mysql_close ($con);
		echo "<script>alert(\"Cliente excluido com sucesso!\");</script>";
		echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=area_restrita/d_excluir.php\";>";
		
	}else{
		mail("lucianomancini@gmail.com","CONTINUE TENTANDO"," From:lucianomancini@gmail.com");
		
		echo "continue tentando.. otário";
		$email_daf = 'lucianomancini@gmail.com';
		$titulo = 'Alguem sem autorização para excluir cliente';
		$headers = "Content-Type: text/html; charset=iso-8859-1\n"; 
		$headers .= "From: $email_daf"; 
		$mensagem = "CONTINUE TENTANDO ";
		mail($email_daf, $titulo, $mensagem, $headers); 

	}
}
?>