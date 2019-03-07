<?php

$url_dolar = "http://www.valor.com.br/valor-data/cma_amline_moedas/1d/200_DOL COM";
$xml = simplexml_load_file($url_dolar);
	
$x = $xml -> series;
foreach ($x as $v1) {
    foreach ($v1 as $v2) {
		$data_hora_ult_dolar = $v2;
    }
}

$v = $xml -> graphs -> graph ;
foreach ($v as $v1) {
    foreach ($v1 as $v2) {
		if ( $v2 <> '' )
			$valor_ult_dolar = $v2;
	}
}

// echo "D&oacute;lar: $data_hora_ult_dolar - $valor_ult_dolar";


$url_euro = "http://www.valor.com.br/valor-data/cma_amline_moedas/1d/200_EUROCOM";
$xml = simplexml_load_file($url_euro);
	
$x = $xml -> series;

foreach ($x as $v1) {
    foreach ($v1 as $v2) {
		$data_hora_ult_euro = $v2;
    }
}

$v = $xml -> graphs -> graph ;

foreach ($v as $v1) {
    foreach ($v1 as $v2) {
		if ( $v2 <> '' )
			$valor_ult_euro = $v2;
	}
}

//echo "<br>EURO: $data_hora_ult_dolar - $valor_ult_dolar";
$valor_ult_dolar = number_format($valor_ult_dolar,4,',','.');
$valor_ult_euro = number_format($valor_ult_euro,4,',','.');

?>
<table width="170">
	<tr height="30">
		<td width="60%">Dolar</td>
		<td><?php echo "$valor_ult_dolar"; ?></td>
	</tr>
	<tr height="30">
		<td>Euro</td>
		<td><?php echo "$valor_ult_euro"; ?></td>
	</tr>
	<tr height="30">
		<td colspan="2" align="center"><?php echo $data_hora_ult_dolar; ?></td>
	</tr>
	<tr height="30">
		<td colspan="2" align="center">Fonte: Valor Econ&ocirc;mico</td>
	</tr>    
</table>
