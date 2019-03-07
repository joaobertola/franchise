<?php
require "connect/sessao.php";
require "connect/funcoes.php";

echo "<pre>";
print_r( $_REQUEST );

$data1       = data_mysql($_REQUEST['data1']);
$situacao    = $_REQUEST['situacao'];
$data2       = data_mysql($_REQUEST['data2']);
$chave       = $_REQUEST['chave'];
$id_franquia = $_REQUEST['id_franquia'];

if ( $chave == 'data_cancelamento' ){
	$situacao = '2';
	$periodo  = " AND b.data_registro BETWEEN '$data1' AND $data2";
}else{
	$periodo  = " AND a.dt_cad BETWEEN '$data1' AND $data2";
}

$sql = "SELECT
			a.codloja, a.razaosoc,a.cidade, a.uf, a.dt_cad, b.data_registro, b.ultima_fatura, c.motivo,
			(select sum(valor) from cs2.titulos where codloja = a.codloja AND valorpg > 0 ) as mensalidade_pagas,
			(select sum(valor) from cs2.titulos where codloja = a.codloja AND valorpg is null ) as mensalidade_nao_pagas,
			a.valor_comissao_vendedor, a.vendedor
		FROM cs2.cadastro a
		INNER JOIN cs2.pedidos_cancelamento b ON a.codloja = b.codloja
		INNER JOIN cs2.motivo_cancel c ON b.id_mot_cancelamento = c.id
		WHERE a.sitcli = '$situacao' $periodo AND a.id_franquia = '$id_franquia'
";
$qry = mysql_query($sql, $con);
while ( $reg = mysql_fetch_array($qry) ){
	
	$razao    = $reg['razaosoc'];
	$dt_cad   = $reg['dt_cad'];
	$dt_canc  = $reg['data_registro'];
	$motivo   = $reg['motivo'];
	$men_pg   = $reg['mensalidade_pagas'];
	$men_npg  = $reg['mensalidade_nao_pagas'];
	$vr_com   = $reg['valor_comissao_vendedor'];
	$vendedor = $reg['vendedor'];
	
	$saldo    = $men_pg - ( $men_npg + $vr_com );
	
	$sdo_total += $saldo;
}

echo "Saldo Total: $sdo_total";




?>