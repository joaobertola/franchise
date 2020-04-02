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
    $xdoc = $value;
}
$numdoc = substr($numdoc,0,-1);

// selecionando o vencimento da ultima parcela selecionada e adicionando para o proximo mes

$sqlx = "select month(vencimento) as mes ,year(vencimento) as ano  from cs2.titulos where numdoc = '$xdoc'";
$qry_insert = mysql_query($sqlx,$con);
$mes = mysql_result($qry_insert,0,'mes');
$ano = mysql_result($qry_insert,0,'ano');

for ( $i=1 ; $i <= $qtd_parcelas ; $i++ ){

    $vencimento       = $ano.'-'.str_pad($mes+$i,2,0,STR_PAD_LEFT).'-30';
    $ano_new    = substr($vencimento,2,2);
    $mes_new    = substr($vencimento,5,2);

    $new_doc    = $ano_new.$mes_new.str_pad($codloja,6,0,STR_PAD_LEFT);

    $sql_insert = "INSERT INTO cs2.titulos_acordo(codloja,texto_numdoc_origem,parcela,vencimento,valor,numdoc_destino)
                   VALUES('$codloja','$numdoc','$parcela','$vencimento','$valor_parcela','$new_doc')";
    $qry_insert = mysql_query($sql_insert,$con);

}

echo "900;Registro Gravado com sucesso";
?>