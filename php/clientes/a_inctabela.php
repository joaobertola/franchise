<?php
require "connect/sessao.php";

$idi		 = $id;
$codig		 = $codigo;
$categoria   = $_POST['assinatura'];
$subcategoria = $_POST['pacote'];

$pre = "SELECT * FROM subcategoria WHERE categoria = '$categoria' and id = '$subcategoria'";
$prec = mysql_query ($pre, $con);
$mat = mysql_fetch_array($prec);
$preco = $mat['nome'];
	if ($categoria == "5") {
		$resulta = "SELECT * FROM tabela_restr_nacional WHERE mensalidade='$preco'";
		}
	elseif ($categoria == "2") {
		$resulta = "SELECT * FROM tabela_bacen WHERE mensalidade='$preco'";
		}
	elseif ($categoria == "3") {
		$resulta = "SELECT * FROM tabela_extra_bacen WHERE mensalidade='$preco'";
		}
	elseif ($categoria == "4") {
		$resulta = "SELECT * FROM tabela_financeira WHERE mensalidade='$preco'";
		}
	else {
		$resulta = "SELECT * FROM tabela_inform WHERE mensalidade='$preco'";
		}
$resgata = mysql_query ($resulta, $con);
$matriz = mysql_fetch_array($resgata);
$mensalidade 		= $matriz['mensalidade'];
$a1_bacen 			= $matriz['a1_bacen'];
$a1_gratuidade 		= $matriz['a1_gratuidade'];
$a1_custo 			= $matriz['a1_custo'];
$a2_extra_bacen 	= $matriz['a2_extra_bacen'];
$a2_gratuidade 		= $matriz['a2_gratuidade'];
$a2_custo 			= $matriz['a2_custo'];
$a3_financeira 		= $matriz['a3_financeira'];
$a3_gratuidade 		= $matriz['a3_gratuidade'];
$a3_custo 			= $matriz['a3_custo'];
$a4_crediario 		= $matriz['a4_crediario'];
$a4_gratuidade 		= $matriz['a4_gratuidade'];
$a4_custo 			= $matriz['a4_custo'];
$a5_cartorial 		= $matriz['a5_cartorial'];
$a5_gratuidade 		= $matriz['a5_gratuidade'];
$a5_custo 			= $matriz['a5_custo'];
$a6_empresarial 	= $matriz['a6_empresarial'];
$a6_gratuidade 		= $matriz['a6_gratuidade'];
$a6_custo 			= $matriz['a6_custo'];
$a7_cadastral 		= $matriz['a7_cadastral'];
$a7_gratuidade 		= $matriz['a7_gratuidade'];
$a7_custo 			= $matriz['a7_custo'];
$b1_restritiva_nacional = $matriz['b1_restritiva_nacional'];
$b1_gratuidade 		= $matriz['b1_gratuidade'];
$b1_custo 			= $matriz['b1_custo'];
$b2_bacen_plus 		= $matriz['b2_bacen_plus'];
$b2_gratuidade 		= $matriz['b2_gratuidade'];
$b2_custo 			= $matriz['b2_custo'];
$i1_bloqueio_dev 	= $matriz['i1_bloqueio_dev'];
$i1_gratuidade 		= $matriz['i1_gratuidade'];
$i1_custo 			= $matriz['i1_custo'];
$i2_desbloqueio_dev = $matriz['i2_desbloqueio_dev'];
$i2_gratuidade 		= $matriz['i2_gratuidade'];
$i2_custo 			= $matriz['i2_custo'];
$c1_negativa_serasa = $matriz['c1_negativa_serasa'];
$c1_gratuidade 		= $matriz['c1_gratuidade'];
$c1_custo 			= $matriz['c1_custo'];
$c2_cancela_serasa 	= $matriz['c2_cancela_serasa'];
$c2_gratuidade 		= $matriz['c2_gratuidade'];
$c2_custo 			= $matriz['c2_custo'];
$i3_embratel 		= $matriz['i3_embratel'];
$i3_gratuidade 		= $matriz['i3_gratuidade'];
$i3_custo 			= $matriz['i3_custo'];
$i4_locacao 		= $matriz['i4_locacao'];
$i4_custo 			= $matriz['i4_custo'];
$i5_ponto_adicional = $matriz['i5_ponto_adicional'];
$i5_custo 			= $matriz['i5_custo'];
$i6_taxa_dezembro 	= $matriz['i6_taxa_dezembro'];

$commando = "insert into tabela_clientes(id, codigo, mensalidade, a1_bacen, a1_gratuidade, a1_custo, a2_extra_bacen, a2_gratuidade, a2_custo, a3_financeira, a3_gratuidade, a3_custo, a4_crediario, a4_gratuidade, a4_custo, a5_cartorial, a5_gratuidade, a5_custo, a6_empresarial, a6_gratuidade, a6_custo, a7_cadastral, a7_gratuidade, a7_custo, b1_restritiva_nacional, b1_gratuidade, b1_custo, b2_bacen_plus, b2_gratuidade, b2_custo, i1_bloqueio_dev, i1_gratuidade, i1_custo, i2_desbloqueio_dev, i2_gratuidade, i2_custo, c1_negativa_serasa, c1_gratuidade, c1_custo, c2_cancela_serasa, c2_gratuidade, c2_custo, i3_embratel, i3_gratuidade, i3_custo, i4_locacao, i4_custo, i5_ponto_adicional, i5_custo, i6_taxa_dezembro) values ('$idi','$codig','$mensalidade','$a1_bacen','$a1_gratuidade','$a1_custo','$a2_extra_bacen','$a2_gratuidade','$a2_custo','$a3_financeira','$a3_gratuidade','$a3_custo','$a4_crediario','$a4_gratuidade','$a4_custo','$a5_cartorial','$a5_gratuidade','$a5_custo','$a6_empresarial','$a6_gratuidade','$a6_custo','$a7_cadastral','$a7_gratuidade','$a7_custo','$b1_restritiva_nacional','$b1_gratuidade','$b1_custo','$b2_bacen_plus','$b2_gratuidade','$b2_custo','$i1_bloqueio_dev','$i1_gratuidade','$i1_custo','$i2_desbloqueio_dev','$i2_gratuidade','$i2_custo','$c1_negativa_serasa','$c1_gratuidade','$c1_custo','$c2_cancela_serasa','$c2_gratuidade','$c2_custo','$i3_embratel','$i3_gratuidade','$i3_custo','$i4_locacao','$i4_custo','$i5_ponto_adicional','$i5_custo','$i6_taxa_dezembro')";
$result = mysql_query ($commando, $con);
?>