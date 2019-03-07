<?php
require_once("../../../../connect/conexao_conecta.php");

if ( isset( $_POST ) )
   $postArray = &$_POST ;			// 4.1.0 or later, use $_POST
else
   $postArray = &$HTTP_POST_VARS ;	// prior to 4.1.0, use HTTP_POST_VARS

foreach ( $postArray as $sForm => $value )
{
	if ( get_magic_quotes_gpc() )
		$postedValue = stripslashes($value);
	else
		$postedValue = $value;
}

$sql = "UPDATE pretendentes_status SET texto_email = '$postedValue' WHERE id = '{$_REQUEST['id_status']}'";
$qry = mysql_query($sql);
?>
<script language="javascript">
	alert('Alterado com sucesso o texto ! ');
	//window.location.href="pretendente_altera_texto.php";
</script>