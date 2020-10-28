<?php
require "connect/sessao.php";

$codloja = $_POST['codloja'];
$inicial = $_POST['inicial'];
$final 	 = $_POST['final'];

if ( empty($inicial) ){
	$parametro = $_REQUEST['parametro'];
	$sql = "SELECT date_format(dti,'%d/%m/%Y') as dti , date_format(dtf,'%d/%m/%Y') as dtf 
			FROM cs2.titulos 
			WHERE numdoc = '$parametro'";
	$qry = mysql_query($sql,$con);
	$array_qry = mysql_fetch_array($qry);
	$inicial = $array_qry['dti'];
	$final   = $array_qry['dtf'];
}

$dia_i = substr($inicial, 0, 2);
$mes_i = substr($inicial, 3, 2);
$ano_i = substr($inicial, 6, 4);

$dia_f = substr($final, 0, 2);
$mes_f = substr($final, 3, 2);
$ano_f = substr($final, 6, 4);

/////////////////////////// razao social
$sql = "SELECT razaosoc, nomefantasia FROM cs2.cadastro WHERE codloja = '$codloja'";
$qry = mysql_query($sql, $con);
$razaosoc     = mysql_result($qry,0,'razaosoc');
$fantasia     = mysql_result($qry,0,'nomefantasia');


$total_inicial = strlen($inicial);

if ($inicial != ""){
	$inicio = implode(preg_match("~\/~", $inicial) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $inicial) == 0 ? "-" : "/", $inicial)));
	$fim = implode(preg_match("~\/~", $final) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $final) == 0 ? "-" : "/", $final)));
	$comando = "select a.insc, date_format(a.amd,'%d/%m/%Y') as data, a.hora, b.nome, a.resp, CAST(MID(logon,1,6) AS UNSIGNED) as logon from cons a
			inner join valcons b on a.debito = b.codcons
			where a.codloja='$codloja' and a.amd between '$inicio' and '$fim' order by a.amd,a.hora";
	
	$res = mysql_query ($comando, $con);
	$res_tmp = mysql_query ($comando, $con);
// 	echo $res_tmp;
	if(	mysql_num_rows ($res_tmp) > 0	)
		$logon = mysql_result($res_tmp,0,'logon');
	
	$linhas = mysql_num_rows ($res);
} else {
	echo $parametro;
	exit;
}


//INICIO SQL RESUMO DE CONSULTAS
/*
  1 - receba facil
  2 - crediario system
*/
$qtd_crediario = 0;
$qtd_recupere  = 0;
$vr_total_crediario = 0;
$vr_total_recupere  = 0;

$sql_receba_crediario = "
SELECT COUNT(*)AS qtd, tp_titulo 
FROM cs2.titulos_recebafacil 
WHERE valorpg != '' 
AND codloja = '$codloja' 
AND date_format(emissao,'%Y-%m-%d') >= '$ano_i-$mes_i-$dia_i'
AND date_format(emissao,'%Y-%m-%d') <= '$ano_f-$mes_f-$dia_f'
GROUP BY tp_titulo";
$qry_receba_crediario = mysql_query($sql_receba_crediario,$con) or die ("Erro ao selecionar o Crediário".mysql_error());
while($rs = mysql_fetch_array($qry_receba_crediario)){
	if($rs['tp_titulo'] == 2) $qtd_crediario = $rs['qtd'];
	else $qtd_recupere = $rs['qtd'];
}

$sql_vr_total_crediario = "
SELECT SUM(valor)AS vr_total, tp_titulo 
FROM cs2.titulos_recebafacil 
WHERE valorpg != '' 
AND codloja = '$codloja' 
AND date_format(emissao,'%Y-%m-%d') >= '$ano_i-$mes_i-$dia_i'
AND date_format(emissao,'%Y-%m-%d') <= '$ano_f-$mes_f-$dia_f'
GROUP BY tp_titulo";

$qry_vr_total_crediario = mysql_query($sql_vr_total_crediario,$con) or die ("Erro ao selecionar o Crediário".mysql_error());

while($rs_vr_total = mysql_fetch_array($qry_vr_total_crediario)){
	if($rs_vr_total['tp_titulo'] == 2) $vr_total_crediario = $rs_vr_total['vr_total'];
	else $vr_total_recupere = $rs_vr_total['vr_total'];
}
$vr_total_crediario = number_format($vr_total_crediario,2,",",".");
$vr_total_recupere  = number_format($vr_total_recupere,2,",",".");

//Recomenda��es de Clientes
$sql_recomenda_cli = "
SELECT COUNT(*)AS total_recomendacao_cli
FROM cs2.relacionamento_consumidor a
INNER JOIN cs2.cadastro b on a.codloja=b.codloja
WHERE a.codloja = '$codloja'
AND date_format(amd,'%Y-%m-%d') >= '$ano_i-$mes_i-$dia_i'
AND date_format(amd,'%Y-%m-%d') <= '$ano_f-$mes_f-$dia_f'";
$qry_recomenda_cli = mysql_query($sql_recomenda_cli,$con) or die ("erro ao preparar o recomende".mysql_error());
$total_recomendacao_cli = mysql_result($qry_recomenda_cli,0,'total_recomendacao_cli');

//encaminhamento para Cartorio  OK
$sql_encaminha_cartorio = "
SELECT COUNT(*)AS total_encaminha_cartorio
FROM consulta.alertas 
WHERE envio_cartorio = '2'
AND codloja = '$codloja'
AND date_format(dt_envio_cartorio,'%Y-%m-%d') >= '$ano_i-$mes_i-$dia_i'
AND date_format(dt_envio_cartorio,'%Y-%m-%d') <= '$ano_f-$mes_f-$dia_f'";
$qry_encaminha_cartorio = mysql_query($sql_encaminha_cartorio,$con) or die ("Erro ao fazer o relatório de encaminhar para o cartório".mysql_error());
$total_encaminha_cartorio = mysql_result($qry_encaminha_cartorio,0,'total_encaminha_cartorio');

//bloqueio de Devedores OK
$sql_bloqueio = "
SELECT COUNT(*)AS total_bloqueio
FROM consulta.alertas 
WHERE codloja = '$codloja'
AND date_format(data_cadastro,'%Y-%m-%d') >= '$ano_i-$mes_i-$dia_i'
AND date_format(data_cadastro,'%Y-%m-%d') <= '$ano_f-$mes_f-$dia_f'
and situacao = 'N'";
$qry_bloqueio = mysql_query($sql_bloqueio,$con) or die ("Erro ao fazer o relatório de devedores".mysql_error());
$total_bloqueio = mysql_result($qry_bloqueio,0,'total_bloqueio');

//Desbloqueio de Devedores OK
$sql_desbloqueio = "
SELECT COUNT(*)AS total_desbloqueio 
FROM consulta.alertas 
WHERE codloja = '$codloja'
AND data_exclusao >= '$ano_i-$mes_i-$dia_i' AND data_exclusao <= '$ano_f-$mes_f-$dia_f'
and situacao = 'E'";
$qry_desbloqueio = mysql_query($sql_desbloqueio,$con) or die ("Erro ao fazer o relatório de desbloqueio: $sql_desbloqueio");
$total_desbloqueio = mysql_result($qry_desbloqueio,0,'total_desbloqueio');


//localiza max
$sql_total_localiza = "
SELECT COUNT(*)AS total_localiza 
FROM cs2.cons
WHERE codloja = '$codloja'
AND date_format(amd,'%Y-%m-%d') >= '$ano_i-$mes_i-$dia_i'
AND date_format(amd,'%Y-%m-%d') <= '$ano_f-$mes_f-$dia_f'
AND ( debito = 'A0230' OR debito = 'A0232' )";
$qry_total_localiza = mysql_query($sql_total_localiza,$con) or die ("Erro ao fazer o relatório de Localiza".mysql_error());
$total_localiza = mysql_result($qry_total_localiza,0,'total_localiza');
//FIM SQL RESUMO DE CONSULTAS

//Pesquisa Nada Consta
//Pesquisa Alerta
//resultados de consulta
for ($i=0;$i<3; $i++){
	$sql_n_consta = "SELECT COUNT(*)AS qtd FROM cs2.cons 
				    WHERE date_format(amd,'%Y-%m-%d') >= '$ano_i-$mes_i-$dia_i'
					AND date_format(amd,'%Y-%m-%d') <= '$ano_f-$mes_f-$dia_f'
					AND codloja = '$codloja' AND resp='$i'";
	$qyr_n_consta = mysql_query($sql_n_consta,$con) or die ("erro ao preparar o resumo".mysql_error());
	$qtd = mysql_result($qyr_n_consta,0);
	if ($i==0) $tot_nc = $qtd;
	if ($i==1) $tot_re = $qtd;
	if ($i==2) $tot_al = $qtd;
}

//seleciona os valores
//Pesquisa Nada Consta
//Pesquisa Alerta
for ($j=0; $j<3; $j++){
	$sql_n_conta_vl = "SELECT SUM(valor)AS qtd FROM cs2.cons 
			  WHERE date_format(amd,'%Y-%m-%d') >= '$ano_i-$mes_i-$dia_i'
			  AND date_format(amd,'%Y-%m-%d') <= '$ano_f-$mes_f-$dia_f' 
			  AND codloja = '$codloja' AND resp='$j' ";
	$qry_n_conta_vl = mysql_query($sql_n_conta_vl,$con) or die ("erro ao preparar o resumo".mysql_error());
	$qtd = mysql_result($qry_n_conta_vl,0);
	$qtd = number_format($qtd,2,",",".");
	if ($j==0) $valor_nc = $qtd;
	if ($j==1) $valor_re = $qtd;
	if ($j==2) $valor_al = $qtd;
}

//total we-control
$sql_web_control = "SELECT COUNT(*)AS total_web_control 	
				    FROM base_web_control.cliente
					WHERE id_cadastro = '$codloja'";
$qry_web_control = mysql_query($sql_web_control,$con) or die ("Erro ao fazer o relatório de Localiza".mysql_error());
$total_web_control = mysql_result($qry_web_control,0,'total_web_control');
?>
<style>
	.total{
		FONT-SIZE: 10px;
		COLOR: midnightblue;
		FONT-FAMILY: Arial;
		BACKGROUND-COLOR: wheat;
		TEXT-ALIGN: center;
		border: 1px solid #D1D7DC;
	}

	.tabela {
		font-family: Tahoma, Verdana, Arial;
		font-size: 11px;
		border: 1px solid #D1D7DC;
		padding: 3px;
	}


	.html {
		font-family: Tahoma, Verdana, Arial;
		font-size: 11px;
		background-color: #ECF8FF;
		border: 1px solid #D1D7DC;
		padding: 3px;
	}
	.Grande {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 15px;
		font-weight: bold;
	}

	.letra{
		font-family: Arial, Helvetica, sans-serif;
		font-size: 10px;
	}
</style>
<table border="0" width="97%" align="left" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
	<tr>
		<td colspan="6"  height="55" align="center" background="../../../../images/bar5.gif" class="Grande" style="color:#ffffff; text-align:center; padding-bottom:10px">EXTRATO DE SOLUÇÕES E PESQUISAS </td>
	</tr>
	<tr>
		<td class="tabela" width='279'><b>Código:</b></td>
		<td colspan="5" class="html"><?php echo $_REQUEST['logon']?></td>
	</tr>
	<tr>
		<td class="tabela"><b>Raz&atilde;o Social:</b></td>
		<td  colspan="5" class="html"><?php echo $razaosoc?></td>
	</tr>
	<tr>
		<td class="tabela"><b>Fantasia:</b></td>
		<td colspan="5" class="html"><?php echo $fantasia?></td>
	</tr>
	<tr>
		<td class="tabela"><b>Periodo de uso:</b></td>
		<td colspan="5" class="html"><?php echo $inicial?> a: <?php echo $final?></td>
	</tr>
	<tr>
		<td colspan="6" class="Grande" background="../../images/separador.gif" align="center">Resumo de Soluções e Pesquisas</td>
	</tr>
	<tr>
		<td class="total" colspan="3">Tipos de Solu&ccedil;&otilde;es</td>
		<td width="192" class="total">Quantidade</td>
		<td width="163" class="total">&nbsp;</td>
		<td width="128" class="total">Total</td>
	</tr>
	<tr>
		<td class="tabela" colspan="3"><font color="#0066FF" style="font-weight:bold">Boletos Crediario</font></td>
		<td class="html"><?php echo $qtd_crediario?> Boleto(s)</td>
		<td class="tabela" align="right">&nbsp;</td>
		<td class="html" align="right"><font color="#0066FF" style="font-weight:bold">R$ <?php echo $vr_total_crediario?></font></td>
	</tr>

	<tr>
		<td class="tabela" colspan="3"><font color="#0066FF" style="font-weight:bold">Boletos Recupere</font></td>
		<td class="html"><?php echo $qtd_recupere?>&nbsp;Boleto(s)</td>
		<td class="tabela" align="right">&nbsp;</td>
		<td class="html" align="right"><font color="#0066FF" style="font-weight:bold">R$ <?php echo $vr_total_recupere?></font></td>
	</tr>
	<tr>
		<td class="tabela" colspan="3"><font color="#0066FF" style="font-weight:bold">Localiza Max</font></td>
		<td class="html"><?php echo $total_localiza?>&nbsp;Localização(ões)</td>
		<td class="tabela" align="right">&nbsp;</td>
		<td class="html" align="right">&nbsp;</td>
	</tr>

	<tr>
		<td class="tabela" colspan="3"><font color="#0066FF" style="font-weight:bold">WEB Control (Nº de Consumidores)</font></td>
		<td class="html"><?php echo $total_web_control?>&nbsp;Consumidore(s)</td>
		<td class="tabela" align="right">&nbsp;</td>
		<td class="html" align="right">&nbsp;</td>
	</tr>

	<tr>
		<td class="tabela" colspan="3">Encaminhamentos para Cart&oacute;rios</td>
		<td class="html"><?php echo $total_encaminha_cartorio?>&nbsp;Encaminhamento(s)</td>
		<td class="tabela" align="right">&nbsp;</td>
		<td class="html" align="right">&nbsp;</td>
	</tr>

	<tr>
		<td class="tabela" colspan="3">Recomenda&ccedil;&otilde;es de Clientes</td>
		<td class="html"><?php echo $total_recomendacao_cli?>&nbsp;Recomenda&ccedil;&atilde;o</td>
		<td class="tabela" align="right">&nbsp;</td>
		<td class="html" align="right">&nbsp;</td>
	</tr>

	<tr>
		<td class="tabela" colspan="3">Bloqueio de Devedores</td>
		<td class="html"><?php echo $total_bloqueio?>&nbsp;Bloqueio(s)</td>
		<td class="tabela" align="right">&nbsp;</td>
		<td class="html" align="right">&nbsp;</td>
	</tr>

	<tr>
		<td class="tabela" colspan="3">Desbloqueio de Devedores</td>
		<td class="html"><?php echo $total_desbloqueio?>&nbsp;Desbloqueio(s)</td>
		<td class="tabela" align="right">&nbsp;</td>
		<td class="html" align="right">&nbsp;</td>
	</tr>
	<tr>
		<td class="tabela" colspan="3"><font color="#0066FF" style="font-weight:bold">Pesquisa Nada Consta&nbsp;</font></td>
		<td class="html"><?php echo $tot_nc?> Pesquisa(s)</td>
		<td class="tabela" align="right"><font color="#0066FF" style="font-weight:bold">Vendido com Seguran&ccedil;a</font></td>
		<td class="html" align="right"><font color="#0066FF" style="font-weight:bold">R$ <?php echo $valor_nc?></font></td>
	</tr>
	<tr>
		<td class="tabela" colspan="3"><font color="#FF9900" style="font-weight:bold">Pesquisa Alerta</font></td>
		<td class="html"><?php echo $tot_al?> Pesquisa(s)</font></td>
		<td class="tabela" align="right"><font color="#FF9900" style="font-weight:bold">Alerta de poss&iacute;vel restri&ccedil;&atilde;o</font></td>
		<td class="html" align="right"><font color="#FF9900" style="font-weight:bold">R$ <?php echo $valor_al?></font></td>
	</tr>
	<tr>
		<td class="tabela" colspan="3"><font color="#FF0000" style="font-weight:bold">Pesquisa com Restri&ccedil;&atilde;o</font></td>
		<td class="html"><?php echo $tot_re?> Pesquisa(s)</font></td>
		<td class="tabela" align="right"><font color="#FF0000" style="font-weight:bold">Evitou prejuizo em</font></td>
		<td class="html" align="right"><font color="#FF0000" style="font-weight:bold">R$ <?php echo $valor_re?></font></td>
	</tr>

	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
	<!-- /table -->

	<?php
	$sql_localiza = "SELECT date_format(a.data,'%d/%m/%Y') data, hora, b.nome, a.pesquisado_por, 
						a.codigo_consulta, if (a.formato='L','Lista','Etiqueta') formato, qtd_registro 
				 FROM cs2.cons_localiza a
				 INNER JOIN cs2.valcons b ON a.tipo_consulta = b.codcons 
				 WHERE a.codloja = '$codloja' and  a.data >= '$inicio' AND a.data <= '$fim' ";
	$qry_localiza = mysql_query($sql_localiza,$con) or die($sql_localiza);
	if ( mysql_num_rows($qry_localiza) > 0 ){
		echo "
		<tr align='center' class='titulo' height='20'> 
			<td width='20%'>Consulta</td> 
			<td width='30%'>Dados Pesquisado</td>
			<td width='10%'>Data</td> 
			<td width='10%'>Hora</td> 
			<td width='10%'>Codigo Consulta</td> 
			<td width='10%'>Formato</td> 
		    <td width='10%'>Qtd Reg.</td> 
		</tr>";
		$b = 0;
		$tot_reg = 0;
		while ( $reg = mysql_fetch_array($qry_localiza) ){
			$b++;
			$data = $reg['data'];
			$hora = $reg['hora'];
			$nome = $reg['nome'];
			$pesquisado_por = $reg['pesquisado_por'];

			if ( substr($pesquisado_por,0,3) == 'END'){
				$dad = explode(';',$pesquisado_por);
				$uf = $dad[0];
				$uf = str_replace('END: ','',$uf);
				$id_cid = $dad[1];
				$id_bai = $dad[2];

				$select = "SELECT cidade FROM cep_brasil.tend_cidade WHERE id_cidade = $id_cid";
				$qry_cid = mysql_query($select,$con);
				$cidade_f = mysql_result( $qry_cid, 0, 'cidade' );

				$select = "SELECT bairro FROM cep_brasil.tend_bairro WHERE id_bairro = $id_bai";
				$qry_bai = mysql_query($select,$con);
				$bairro_f = mysql_result( $qry_bai, 0, 'bairro' );

				$pesquisado_por = 'END: '.$bairro_f.' / '.$cidade_f.' / '.$uf;
			}

			$codigo_consulta = $reg['codigo_consulta'];
			$formato = $reg['formato'];
			$qtd_registro = $reg['qtd_registro'];

			$tot_reg += $qtd_registro;

			if (($b%2) == 0) {
				$cor = "bgcolor='#E5E5E5'";
			}else $cor = '';

			echo "
			<tr $cor align='center' class='letra'> 
				<td>$nome</td> 
				<td>$pesquisado_por</td> 
				<td>$data</td> 
				<td>$hora</td> 
				<td>$codigo_consulta</td> 
				<td>$formato</td> 
				<td>$qtd_registro</td>
			</tr>";
		}
		echo "
		<tr>
			<td colspan='7' align='right' height='1' bgcolor='#666666'></td>
		</tr>
		<tr>
			<td colspan='6'><b> Total de Consultas</b></td>
			<td align='center'><b><font color='#ff6600'>$tot_reg</font></b></td>
		</tr>
		<tr>
			<td colspan='7'>&nbsp;</td>
		</tr>
		";
	}

	if ($linhas == "0"){
		echo "<table width=\"660\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
			<tr height=\"20\" class=\"titulo\">
			<td align=\"center\" width=\"100%\">Nenhuma consulta registrada neste periodo</td></tr></table>";
	}else{
		?>
		<tr>
			<td colspan="7" height="1" bgcolor="#999999"></td>
		</tr>
		<tr height="20" class="titulo">
			<td align="left" colspan="2">Tipo de Consulta</td>
			<td width='105' align="right" colspan="2" >CPF/CNPJ</td>
			<td width='192' align="center" >Data</td>
			<td width='163' align="center" >Hora</td>
			<td width='128' align="center" >Resposta</td>
		</tr>
		<tr>
			<td colspan="7" height="1" bgcolor="#666666">
			</td>
		</tr>
		<?php
		$resp_consulta = array( "0" => "Nada Consta",
			"1" => "Com Restri&ccedil;&atilde;o",
			"2" => "Alerta",
			"3" => "");

		for ($a=1; $a<=$linhas; $a++)
		{
			$matriz = mysql_fetch_array($res);
			$nome = $matriz['nome'];
			$insc = $matriz['insc'];
			$data = $matriz['data'];
			$hora = $matriz['hora'];
			$resp = $matriz['resp'];

			echo "<tr height='22'";
			if (($a%2) == 0) {
				echo "bgcolor='#E5E5E5'>";
			} else {
				echo ">";
			}
			$resp_desc = $resp_consulta[$resp];

			echo "	<td class='letra' colspan='2'>&nbsp;$nome</td>
		  	      	<td class='letra' align='right' colspan='2'>$insc</td>
		  	      	<td class='letra' align='center'>$data</td>
				  	<td class='letra' align='center'>$hora</td>
					<td class='letra' align='center'>$resp_desc</td>
		  	      </tr>";
		}
		echo "<tr>
			<td colspan='7' align='right' height='1' bgcolor='#666666'>
			</td>
		</tr>
		<tr>
			<td colspan=\"3\"><b> Total de Consultas no Periodo $inicial - $final</b></td>
			<td colspan=\"3\"><b><font color=\"#ff6600\">$linhas</font></b></td>
		</tr>";
	}
	$res = mysql_close ($con);
	?>
	<tr><td colspan="5" align="center" height="30"><input type="button" onClick="javascript: history.back();" value="       Voltar       " /></td></tr></table>


