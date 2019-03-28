<?php


function diferenca_entre_datas($data, $dt_base, $formato) {

    if ($formato == 'DD/MM/AAAA') {
	$d_data = substr($data, 0, 2);
	$m_data = substr($data, 3, 2);
	$a_data = substr($data, 6, 4);
	$d_base = substr($dt_base, 0, 2);
	$m_base = substr($dt_base, 3, 2);
	$a_base = substr($dt_base, 6, 4);
    } else {
	return "FORMATO INVALIDO";
	exit;
    }
    $dias_data = floor(gmmktime(0, 0, 0, $m_data, $d_data, $a_data) / 86400);
    $dias_base = floor(gmmktime(0, 0, 0, $m_base, $d_base, $a_base) / 86400);
    $val = $dias_data - $dias_base;
    return $val;
}

$nvalor              = $_REQUEST['valor_original'];
$nvalor              = str_replace('.', '', $nvalor);
$nvalor              = str_replace(',', '.', $nvalor);
$vencimento_original = $_REQUEST['vencimento_original'];
$data_pagamento      = $_REQUEST['data_pagamento'];

$dif = diferenca_entre_datas($data_pagamento, $vencimento_original, 'DD/MM/AAAA');

$encargosdia = ($nvalor * 0.0015 );
$valor = $nvalor;
$nvalor = str_replace(',', '.', $valor);
$multa = ($nvalor * 2) / 100;
$encargos = ($nvalor * 0.0015 ) * $dif;
$xencargos = number_format($encargos, 2, ',', '.');
$encargosdia = ($nvalor * 0.0015 );
$encargosdia = number_format($encargosdia, 2);
$encargos = number_format($encargos, 2);
$valor = $nvalor + $multa + $encargos;
$multa = number_format($multa, 2, ',', '.');
$valor = number_format($valor, 2, ',', '.');
echo $valor; 
            
            