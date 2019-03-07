<?php

	session_start();

	echo "Aguarde... Excluindo a foto";
	
	$id_franquia = $_REQUEST['id_franquia'];
	$id_foto = $_REQUEST['id_foto'];
	$id_user = $_SESSION['id'];

	if ( $id_user == 163 ){
	
		$sql = "DELETE FROM cs2.franquia_foto WHERE id = $id_foto";
		$qry = mysql_query($sql,$con) or die('erro');

	}
	echo "<meta http-equiv='refresh' content='0; url= painel.php?pagina1=area_restrita/d_altfranqueado.php&id=$id_franquia'&acao=3;>";
?>