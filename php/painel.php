<?php

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

require "connect/sessao.php";

$pagina1 = $_GET["pagina1"];
require "connect/conexao_conecta.php";
include("../funcoes/mascaras.php");

global $name;

$jaConectou = true;
$data = date('Y-m-d H:i:s'); 

$sql_gerente = "
SELECT  
  gerente.nome, gerente.fone
FROM
  franquia INNER JOIN
  gerente ON franquia.id_gerente = gerente.id
WHERE
  franquia.id = '{$_SESSION['id']}'";


  
$qry_gerente = mysql_query($sql_gerente,$con);
$nome = mysql_result($qry_gerente,0,'nome');
$fone = telefoneConverte(mysql_result($qry_gerente,0,'fone'));

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Web Control Empresas - Painel Administrativo</title>
	<link rel="icon" href="../favicon.ico" type="image/x-icon">
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link href="../css/tabela.css" rel="stylesheet" type="text/css" />
<style type="text/css" media="print">
.noprint {
	display:none;
}
</style>
	<script src="https://code.jquery.com/jquery-1.9.0.js"></script>
	<script src="https://code.jquery.com/jquery-migrate-1.4.1.js"></script>
<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="../js/jquery.maskedinput-1.1.1.js"></script>
<script type="text/javascript" src="../js/jquery.meio.mask.js"></script>

<script src="../js/funcoes.js"></script>
<script language="JavaScript" src="../js/mm_menu.js"></script>
<!-- bootstrap -->
<script type="text/javascript">
(function($){
$(
	function(){
		$('input:text').setMask();
	}
);

jQuery(function($){
   $("#id_agencia").mask("9999-9"); 
   $("#id_conta").mask("999999-9");
   
});
})(jQuery);

function ver(valor){ 
	alert(valor);
}


</script>

</head>
<body>
<div id="header" class="noprint col100" >
	<div class="h_logo">
  		<div class="right">
        	<a href="https://www.webcontrolempresas.com.br" tabindex="-1" target="_parent"><img src="../img/logowebcontrol.png" alt="Web Control Empresas" border="0" /></a>
      	</div>
    </div>
</div>

<table border="0" align="center" width="100%" class="noprint">
	<tr class="bodyText">
		<td align="left">Seja Bem-vindo
<?php

$sql = "select id, fantasia, classificacao, id_franquia_master from cs2.franquia where usuario='$name'";
$resposta = mysql_query($sql,$con);
while ($array = mysql_fetch_array($resposta)) {
	$id_franquia	= $array["id"];
	$nome_franquia	= $array["fantasia"];
	$classificacao = $array["classificacao"];
	$id_franquia_master	= $array["id_franquia_master"];
	echo $nome_franquia;
}

if ($tipo == "a") echo " voc&ecirc; &eacute; um Administrador";
elseif ($tipo == "c") echo " voc&ecirc; &eacute; um Usu&aacute;rio da matriz";
else{
	 echo " voc&ecirc; &eacute; um ";

	if ( $classificacao == 'J' ){
		echo " Franqueado - JUNIOR";
		$sql = "select fantasia from cs2.franquia where id = '$id_franquia_master'";
		$resposta = mysql_query($sql,$con);
		while ($array = mysql_fetch_array($resposta)) {
			$nome_franquia_master	= $array["fantasia"];
		}
	}elseif ( $classificacao == 'X' ){
		echo " Micro Franqueado";
		$nome_franquia_master = '1';
	}else{
		echo " Franqueado";
	}
}

//verifica se tem recados não lidos
$cigaro = mysql_query("select count(*) qtd from correio_franquia where lido='0' and franquia='$id_franquia'", $con);
while ($conta = mysql_fetch_array($cigaro)) {
    
    $qtd_email = $conta['qtd'];
    //if ($qtd_email <> 0) echo "  <a href=\"painel.php?pagina1=area_restrita/d_email.php&mail=area_restrita/d_lista_email.php\"><blink><img src=\"../../images/aaMsgNaoLida.gif\" border=\"0\" ></blink> Voc&ecirc; tem e-mails n&atilde;o lidos</a>";
}



?></h5>
		</td>
		<td align="right">Seu Gerente Comercial é: <font color="#0000CC"><?=$nome?>&nbsp;&nbsp;&nbsp;<?=$fone?>&nbsp;</td>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="218" valign="top" class="noprint"><?php include "menu.php"; ?></td>
		<td valign="top">
			<?php
			// include('meta_franquia.php');
                        
			if($pagina1!=""){
				include "$pagina1";
			} else{
				include('msg_inicial.php');
				//include('fotos_melhores_momentos.php');
			}
			?></td>
	</tr>
</table>
</body>
</html>