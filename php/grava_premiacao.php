<?php

require "connect/sessao.php";
require "connect/funcoes.php";
require "connect/conexao_conecta.php";

$funcionario = $_REQUEST['cobradora'];
$data        = data_mysql($_REQUEST['data']);
$logon       = $_REQUEST['logon'];
$qtd_reg     = count($logon);
$campanha	 = $_REQUEST['campanha'];

for( $i = 0 ; $i < $qtd_reg ; $i++ ){
	
	$codigo = trim($logon[$i]);
	
	if ( $codigo != '' && $funcionario != '0' ){
		$sql = "SELECT a.codloja FROM cs2.cadastro a
				INNER JOIN cs2.logon b on a.codloja = b.codloja
				WHERE MID(b.logon,1,LOCATE('S', b.logon) - 1) = '$codigo'";
		$qry = mysql_query($sql,$con);
		$codloja = mysql_result($qry,0,'codloja');
		
		$sql = "INSERT INTO cs2.premiacao( id_func , data , codloja , tipo_premio )
				VALUES( '$funcionario' , '$data' , '$codloja' , '$campanha' )";
		$res = mysql_query($sql,$con) or die("Erro ao gravar a Premiacao. Contate o Departamento de Tecnologia.: $sql");
	}
}
?>
<script>
  alert(" Cadastrado com Sucesso ");
  window.location.href="../php/painel.php?pagina1=area_restrita/menu_premiacao.php";
</script>