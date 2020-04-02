<?php

require "../connect/conexao_conecta.php";

$codloja      = $_REQUEST['codigo']; // codigo do cliente
$valor_divida = $_REQUEST['valor'];  // valor total da divida
$documentos   = $_REQUEST['numdoc']; // array com o numero dos titulos em acordada
$qtd_parcelas = $_REQUEST['qtd_parcelas']; // qtd de parcelas

$valor_parcela =  $valor_divida / $qtd_parcelas;
$valor_parcela =  number_format($valor_parcela , 2);

foreach ($documentos as $key => $value) {
    $numdoc .= $value.',';
}
$numdoc = substr($numdoc,0,-1);

// $i = ;
for ( $i=0 ; $i < $qtd_parcelas ; $i++ ){

    $parcela    = str_pad($i+1,2,0,STR_PAD_LEFT).'/'.str_pad($qtd_parcelas,2,0,STR_PAD_LEFT);
    $data       = date('d/m/Y');
    $data       = explode( "/",$data);
    $dia        = $data[0];
    $mes        = $data[1];
    $ano        = $data[2];
    $j          = $i+1;
    $vencimento = date("d/m/Y",strtotime("+".$j." month",mktime(0, 0, 0,$mes,$dia,$ano)));
    $venc       = $vencimento;
    $vencimento = substr($vencimento,6,4).'-'.substr($vencimento,3,2).'-'.substr($vencimento,0,2);

    $ano_new    = substr($venc,8,2);
    $mes_new    = substr($venc,3,2);

    $new_doc    = $ano_new.$mes_new.str_pad($codloja,6,0,STR_PAD_LEFT);

    $sql_insert = "INSERT INTO cs2.titulos_acordo(codloja,texto_numdoc_origem,parcela,vencimento,valor,numdoc_destino)
                   VALUES('$codloja','$numdoc','$parcela','$vencimento','$valor_parcela','$new_doc')";
    $qry_insert = mysql_query($sql_insert,$con);

}

echo "900;Registro Gravado com sucesso";
?>