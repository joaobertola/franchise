<script>
    function cancela_recebimento(id,id_franquia,vr_extornado){
        
        if (confirm('Deseja realmente EXTORNAR este recebimento ?')) {
            
            $.ajax({
                url: "area_restrita/b_cancela_emprestimo.php?update",
                type: "POST",
                data: {
                    'id_emprestimo': id,
                    'id_franquia': id_franquia,
                    'vr_extornado': vr_extornado
                },
                success: function (data) {
                    if (data == 1) {
                       location.reload();
                    } else {
                        console.log(data);
                    }
                }
            });
            
        }
    }
</script>
<?php

require "connect/sessao_r.php";
$nome2 = $_SESSION['ss_restrito'];

$id_franquia_sessao = $_SESSION['id'];

if (!isset($nome2) && ($tipo != "a"))

$id = $_REQUEST['id'];

if ( empty( $_REQUEST['id_frq'] ) )
    $id = $_SESSION['id'];

//fun��o para tratar os resultados com 2 decimais
function FloatFormat($Value, $Precision) { 
    $decimals = log10(abs($Value));         
    $decimals = - (intval(min($decimals, 0)) - $Precision); 	
	if ($decimals = 2 ) $format = "%." . $decimals . "f";
    return sprintf($format, $Value); 
} 

$dias_listados = 60;

$sql="select subdate(now(), interval $dias_listados day) data";
$qr=mysql_query($sql,$con)or die ("ERRO:  Segundo SQL  ==>  $sql");
$campos=mysql_fetch_array($qr);
$data_periodo=substr($campos["data"],0,10);

$comando = "select id, discriminacao, valor, operacao, 
            valor_titulo, date_format(venc_titulo,'%d/%m/%Y') as venc,
            date_format(data, '%d/%m/%Y') AS data_registro, 
			date_format(data, '%Y-%m-%d') AS data_tmp 
			from contacorrente where franqueado=".$_REQUEST['id_frq']." order by id";
			
$sql = "SELECT 
			SUM(valor) as soma 
		FROM contacorrente 
		WHERE franqueado='".$_REQUEST['id_frq']."' AND venc_titulo >= NOW()";

$ql = mysql_query($sql, $con);
$res = mysql_query ($comando, $con);

$sql_tipo_franquia = 'select classificacao, fantasia from franquia where id = '.$_REQUEST['id_frq'];
$qr_tipo_franquia = mysql_query($sql_tipo_franquia, $con);
$tipo_franquia = mysql_result($qr_tipo_franquia,0,'classificacao');
$nome_franquia = mysql_result($qr_tipo_franquia,0,'fantasia');

$fpagas = mysql_result($ql,0,"soma");


$linhas = mysql_num_rows ($res);

$saldo=0.00; //zera o saldo antes de come�ar analizar o total

if ($linhas == "0"){
	echo "<table width='680'>
			<tr height='22' class='titulo'>
				<td align='center' width='100%'>Nenhum movimento neste periodo</td>
			</tr>
		  </table>";
} else {
	echo "<br><table width='750' border='0' cellpadding='0' cellspacing='1' class='bodyText'>
	 		<tr>
				<td colspan='9' class='titulo'>VISUALIZAR CONTA CORRENTE - $nome_franquia<br>
				&Uacute;ltimos $dias_listados dias
				</td>
			</tr>
			<tr height='20' bgcolor='87b5ff'>
				<td align='center'>&nbsp;Data</td>
				<td align='center'>Discrimina&ccedil;&atilde;o</td>
				<td align='center'>Venc. T&iacute;tulo</td>
				<td align='center'>Valor T&iacute;tulo</td>
				<td align='center'>Valor Repasse</td>
				<td align='center'>Oper</td>
				<td align='center'>Saldo</td>
				<td align='center'>Protocolo &nbsp;</td>
			</tr>";
	for ($a=1; $a<=$linhas; $a++) {

	
	  	$matriz = mysql_fetch_array($res);
	  	$data = $matriz['data_registro'];
		$data_tmp = $matriz['data_tmp'];
		
		$discriminacao = $matriz['discriminacao'];
		$valor_titulo = $matriz['valor_titulo'];
		$venc = $matriz['venc'];
		$valor = $matriz['valor'];
		if ($matriz['operacao'] == '0') {
			$operacao = 'C';
			$saldo = $saldo + $valor;
			$saldo = FloatFormat($saldo,2);  
			$font_oper = "<font color='#336600'>";
		} else {
			$operacao = 'D';
			$saldo = $saldo - $valor;
			$saldo = FloatFormat($saldo,2);  
			$font_oper = "<font color='#FF0000'>";
		}
		if($data_tmp >= $data_periodo){
			$protocolo = $matriz['id'];
			if (($saldo > 0) || ($saldo == 0)) {
				$font_saldo = "<font color='#336600'>";
			} else {
				$font_saldo = "<font color='#FF0000'>";
			}
			$string = $discriminacao;
			$limite = 130;
			$sizeName = strlen($string);
			//
			
			echo "<tr height='22' bgcolor='#E5E5E5'>
					<td align='center'>&nbsp;$data</td>
					<td>&nbsp;";
				  for($num=0;$num<$limite;$num++) {
						print($string[$num]);
						}
						if($sizeName>$limite){echo"...";}
			echo "</td>
					<td align='center'>$venc</td>
					<td align='right'>$valor_titulo&nbsp;</td>
					<td align='right'>$valor&nbsp;</td>
					<td align='center'>$font_oper $operacao</font></td>
					<td align='right'>$font_saldo $saldo &nbsp;</font></td>
					<td align='right'>$protocolo &nbsp;</td>
				  </tr>
				  
				";
		}
	}
		
		$total = $saldo - $fpagas;
		if ($total >= 0) $font = "<font color='#336600'>";
		else $font = "<font color='#FF0000'>";
		$total = number_format($total, 2, ',', '.');
		echo "<tr class='subtitulodireita' height='20'>
				<td></td>
				<td align='left'>Saldo da Conta Corrente</font></td>
				<td colspan='3'>R$ $font_saldo $saldo</font></td>
				<td colspan='3'></td>
			</tr>
			<tr class='subtitulodireita' height='20'>
				<td></td>
				<td align='left'>Faturas Pagas n&atilde;o vencidas (Credito bruto apartir dia 1&ordm;)</font></td>
				<td colspan='3'>R$ $fpagas</td>
				<td colspan='3'></td>
			</tr>
			<tr class='subtitulodireita' height='20'>
				<td></td>
				<td align='left'>Cr&eacute;dito para Dep&oacute;sito na Ter&ccedil;a-feira</font></td>
				<td colspan='3'><b>R$ $font $total</font></b></td>
				<td colspan='3'></td>
			</tr>
		</table>";
}

// Verificando se o FRANQUEADO 
$sql_ant = "SELECT  date_format(data_solicitacao,'%d/%m/%Y') as data_solicitacao,
                    vr_emprestimo_solicitado, qtd_parcelas, protocolo, descricao_deposito, 
                    date_format(data_deposito,'%d/%m/%Y') AS data_deposito,
                    bco_origem_deposito, numero_parcela, observacao
            FROM cs2.cadastro_emprestimo_franquia
            WHERE id_franquia = ".$_REQUEST['id_frq']."
            GROUP BY protocolo
            ORDER BY id";
$qry_ant = mysql_query($sql_ant,$con) or die ("Erro SQL: $sql_ant" );
if ( mysql_num_rows($qry_ant ) > 0 ){
	?>
	<table width='750' border='0' cellspacing='0' cellpadding='3'>
		<tr>
                    <td class='Titulo_consulta' height='84'><font color='#FF6600'>Extrato de Cr&eacute;ditos e D&eacute;bitos Programados</font></td>
		</tr>
	</table>
	<?php
	while ( $res = mysql_fetch_array($qry_ant) ){
		$protocolo                = $res['protocolo'];
		$data_solicitacao         = $res['data_solicitacao'];
		$vr_emprestimo_solicitado = $res['vr_emprestimo_solicitado'];
		$vr_emprestimo_solicitado = number_format($vr_emprestimo_solicitado,2,',','.');
		$qtd_parcelas             = $res['qtd_parcelas'];
		$descricao_deposito       = $res['descricao_deposito'];
		$tp_movimento             = $res['tp_movimento'];
		$observacao               = $res['observacao'];
		
		$texto1 = 'D&Eacute;BITO Programado';
		
		if ( trim($descricao_deposito) == '' ) $descricao_deposito = "Aguardando libera&ccedil;&atilde;o do lote";
			
		$data_deposito            = $res['data_deposito'];
		$bco_origem_deposito      = $res['bco_origem_deposito'];
		echo "
		<table  width='750' border='0' cellspacing='0' cellpadding='3'>
			<tr>
				<th class='total' width='15%' style='text-align:right;font-size:10px'>Protocolo:</th>
				<th class='total' width='22%' style='text-align:left;color:#F00;font-size:10px'>$protocolo</th>
				<th class='total' width='19%' style='text-align:right;font-size:10px'>Data Solicita&ccedil;&atilde;o:</th>
				<th class='total' colspan='3' style='text-align:left;font-size:10px'>$data_solicitacao</th>
			</tr>
			<tr>
				<th class='total' style='text-align:right;font-size:10px'>$texto1:</th>
				<th class='total' style='text-align:left;font-size:10px'>R$ $vr_emprestimo_solicitado</th>
				<th class='total' colspan='4' style='text-align:center;color:#0000FF;font-size:10px'>$descricao_deposito</font></th>
			</tr>
			<tr>
				<td colspan='4'>
					<tr bgcolor='#CCCCCC' >
						<th class='total'>Parcela</th>
						<th class='total'>Vencimento</th>
						<th class='total'>Vr Parcela</th>
						<th width='18%' class='total'>Data Pagamento</th>
						<th width='15%' class='total'>Valor Pagamento</th>
						<th width='15%' class='total'></th>
					</tr>";
				$sql_ant2 = "SELECT numero_parcela, date_format(data_vencimento,'%d/%m/%Y') as data_vencimento, valor_parcela,
									date_format(data_pagamento,'%d/%m/%Y') as data_pagamento, valor_pagamento,
									id
								FROM cs2.cadastro_emprestimo_franquia 
								WHERE protocolo = '$protocolo'
								ORDER BY numero_parcela";
				$qry_ant2 = mysql_query($sql_ant2,$con) or die ("Erro SQL: $sql_ant2" );
				while ( $res2 = mysql_fetch_array($qry_ant2) ){
					$id_emprestimo   = $res2['id'];
					$numero_parcela  = $res2['numero_parcela'];
					$data_vencimento = $res2['data_vencimento'];
					$valor_parcela   = number_format($res2['valor_parcela'],2,",",".");
					$data_pagamento  = $res2['data_pagamento'];
					$valor_pagamento = $res2['valor_pagamento'];
					
                                        $franqueado = $_REQUEST['id_frq'];
                                                
					if ( $id_franquia_sessao == 163 ){
						if ( $data_pagamento == '' )
                                                    $link_acao = "<a href=\"painel.php?pagina1=area_restrita/b_baixa_emprestimo.php&id_emprestimo=$id_emprestimo&situacao=$situacao&codigo1=$codigo1&codigo2=$codigo2&vencimento1=$vencimento1&vencimento2=$vencimento2&franqueado=$franqueado&cobranca=$cobranca&ordem=$ordem&lpp=$lpp&pagina=$pagina&periodo=$periodo&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='Quitar Titulo'; return true\" title='Clique para quitar a parcela'><IMG SRC='../img/cancela.gif' width='16' height='16' border='0'></a>";
						else{
                                                    // $link_acao = "<a href=\"painel.php?pagina1=area_restrita/b_cancela_emprestimo.php&id_emprestimo=$id_emprestimo&situacao=$situacao&codigo1=$codigo1&codigo2=$codigo2&vencimento1=$vencimento1&vencimento2=$vencimento2&franqueado=$franqueado&cobranca=$cobranca&ordem=$ordem&lpp=$lpp&pagina=$pagina&periodo=$periodo&codloja=$codloja&retorna=1\" onMouseOver=\"window.status='Quitar Titulo'; return true\" title='Clique para EXTORNAR a parcela'><IMG SRC='../img/recicla.jpg' width='16' height='16' border='0'></a>";
                                                    $link_acao = "<a href=# onClick='cancela_recebimento($id_emprestimo,$franqueado,$valor_pagamento)' title='Clique para EXTORNAR a parcela'><IMG SRC='../img/recicla.jpg' width='16' height='16' border='0'></a>";
                                                }
					}else{
						$link_acao = '';
					}
					
					echo "
					<tr>
						<td class='html'>$numero_parcela</td>
						<td class='html'>$data_vencimento</td>
						<td class='html'>$valor_parcela</td>
						<td class='html'>$data_pagamento</td>
						<td class='html'>$valor_pagamento</td>
						<td class='html'>
							$link_acao
						</td>
					</tr>";
					
				}
				echo "
					</td>
				</tr>
				<tr>
					<th class='total' colspan='6' style='text-align:center;color:#F00;font-size:10px'>&nbsp;$observacao</th>
				</tr>
				<tr>
					<th colspan='6'>&nbsp;</th>
				</tr>
				<tr>
					<th colspan='6'>&nbsp;</th>
				</tr>
		</table>";
	}
}
?>