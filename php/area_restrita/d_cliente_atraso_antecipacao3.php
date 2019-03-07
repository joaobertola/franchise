<?php

require "connect/sessao.php";
require "connect/funcoes.php";

$xml_recebido = $_REQUEST['xml'];

$lista       = '<?xml version="1.0" encoding="UTF-8"?>'.$xml_recebido;
$xml = simplexml_load_string($lista);

$nlista = $xml -> dados	;

$qtd = count(  $xml -> dados );

for( $i=0 ; $i < $qtd ; $i++ ){
	$id_titulo = $xml -> dados[$i] -> id_titulo;
	$qtd_titulo = count(  $xml -> dados[$i] -> titulo);
	$total_divida = 0;
	for( $j=0 ; $j < $qtd_titulo ; $j++ ){
		$codloja        = $xml -> dados[$i] -> titulo[$j] -> codloja;
		$vencimento     = $xml -> dados[$i] -> titulo[$j] -> vencimento;
		$protocolo      = $xml -> dados[$i] -> titulo[$j] -> protocolo;
		$valor_parcela  = $xml -> dados[$i] -> titulo[$j] -> valor_parcela;
		$data_calculo   = $xml -> dados[$i] -> titulo[$j] -> data_calculo;
		$dias_vencido   = $xml -> dados[$i] -> titulo[$j] -> dias_vencido;
		$valor_atuali   = $xml -> dados[$i] -> titulo[$j] -> valor_atualizado;
		$data_calculo   = $xml -> dados[$i] -> titulo[$j] -> data_calculo;
		$data_calculo   = data_mysql($data_calculo);
		$mensagem       = $xml -> dados[$i] -> titulo[$j] -> mensagem.'<br>';
		$mensagem       = str_replace('|','<br>',$mensagem);
		$total_divida  += trim($valor_atuali);
	}
	
	$sql = "INSERT INTO cs2.titulos_antecipacao(contrato,codloja,emissao,vencimento,valor,id_antecipacao)
			VALUES( '$protocolo' , '$codloja' , NOW(), '$data_calculo' , '$total_divida', '$id_titulo'  )";
	$qry = mysql_query($sql) or die("Erro ao gravar o Boleto : $sql");
}
@mysql_free_result($qry);
?>
<script>
alert('Registros processados com sucesso.');
window.location.href="painel.php?pagina1=area_restrita/d_cliente_atraso_antecipacao.php";
</script>