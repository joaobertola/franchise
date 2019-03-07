<?php
  include("../../../connect/conexao_conecta.php") ;

	//recebe a mensgem para enviar email
	if ( isset( $_POST ) )
		$postArray = &$_POST ;			// 4.1.0 or later, use $_POST
	else
	$postArray = &$HTTP_POST_VARS ;	// prior to 4.1.0, use HTTP_POST_VARS
	
	foreach ( $postArray as $sForm => $value )
	{
	if ( get_magic_quotes_gpc() )
		$postedValue = htmlspecialchars( stripslashes( $value ) ) ;
	else
		$postedValue = htmlspecialchars( $value ) ;
	}

	//seleciona os pretendentes para enviar o e-mail
	$sql_email = "SELECT nome, email  from cs2.pretendentes id IN'{$_REQUEST['id_pretendentes']}'";
	$qry_email = mysql_query($sql_email, $con);
	$email = mysql_result($qry_email,0,'email');		
	$nome  = mysql_result($qry_email,0,'nome');		
	
	echo "<pre>";
	print_r($_REQUEST);
	exit;
?>
<script type="text/javascript">
	alert('E-mail enviado com sucesso ! ');
	parent.mural.document.close();
	top.document.location.href="../../../../painel.php?pagina1=area_restrita/pretendentes_form.php"; 
</script>
