<?php
require "connect/sessao_r.php";

$mes = $_REQUEST['mes'];
$ano = $_REQUEST['ano'];
	// Buscando porcetagem de participacao do Franqueado JUNIOR
	$id_franquia_jr = $_REQUEST['id_franquia_jr'];
	
	$sql17 = "	Select comissao_frqjr from cs2.franquia where id = '$id_franquia_jr'";
	$qr17 = mysql_query($sql17,$con);
	$participacao = mysql_result($qr17,0,"comissao_frqjr");
	if ( empty( $participacao ) ) $participacao = '0';
	
	if ($mes == 1) {
		$mes_bloqueio=12;
		$ano_bloqueio=$ano-1;
	} else {
		$mes_bloqueio = $mes-1;
		if ($mes_bloqueio < 10) $mes_bloqueio = "0".$mes_bloqueio;
		$ano_bloqueio = $ano;
	}
	$sql17 = "	select count(*) totvendas from cs2.cadastro 
				where dt_cad like '$ano_bloqueio-$mes_bloqueio%' 
				and id_franquia_jr='$id_franquia_jr'";
	$qr17 = mysql_query($sql17,$con);
	$totvendas = mysql_result($qr17,0,"totvendas");
	
	//linha A
	$sql1 = "SELECT count(*), sum(valor) FROM titulos a
			inner join cadastro b on a.codloja=b.codloja 
			where b.id_franquia_jr='$id_franquia_jr' 
			and MONTH(vencimento)='$mes' and Year(vencimento)='$ano'";

	$ql1 = mysql_query($sql1,$con);
	while ($array = mysql_fetch_array($ql1)) {
		$qtd_faturado = $array['count(*)'];
		$tot_faturado_bruto = $array['sum(valor)'];
	}

	//linha B
	$qtd_faturas_pagas_bco = '0';
	$tot_faturas_pagas_bco = '0,00';
	$sql1 = "SELECT count(*), sum(valor) FROM titulos a
			inner join cadastro b on a.codloja=b.codloja 
			where b.id_franquia_jr='$id_franquia_jr' 
			and MONTH(vencimento)='$mes' and Year(vencimento)='$ano' and datapg is not null and origem_pgto='BANCO'";
	$ql1 = mysql_query($sql1,$con);
	while ($array = mysql_fetch_array($ql1)) {
		$qtd_faturas_pagas_bco = $array['count(*)'];
		$tot_faturas_pagas_bco = $array['sum(valor)'];
		$ntot_faturas_pagas_bco = $tot_faturas_pagas_bco;
		$tot_faturas_pagas_bco = number_format($tot_faturas_pagas_bco, 2, ',', '.');
	}

	//linha C
	$qtd_ctr_pendente = '0';
	$tot_faturas_pendente = '0,00';
	$sql1 = "SELECT count(*), sum(valor) FROM titulos a
			inner join cadastro b on a.codloja=b.codloja 
			where b.id_franquia_jr='$id_franquia_jr' 
			and MONTH(vencimento)='$mes' and Year(vencimento)='$ano' and datapg is null";
	$ql1 = mysql_query($sql1,$con);
	while ($array = mysql_fetch_array($ql1)) {
		$qtd_faturas_pendentes = $array['count(*)'];
		$tot_faturas_pendentes = $array['sum(valor)'];
		$tot_faturas_pendentes = number_format($tot_faturas_pendentes, 2, ',', '.');
	}
	
	//linha D
	if ($mes >= 3) {
		$xmes = $mes -2;
		$xano = $ano;
	} else {
		$xmes = ($mes + 12) - 2;
		$xano = $ano - 1;
	}
	$qtd_ctr_pendente = '0';
	$tot_ctr_pendente = '0,00';
	$sql9 = "SELECT count(*), sum(valor) total FROM titulos a
			inner join cadastro b on a.codloja=b.codloja
			left outer join pedidos_cancelamento c on a.codloja=c.codloja
			where b.id_franquia_jr ='$id_franquia_jr' 
			and dt_cad <= '$xano-$xmes-31' and pendencia_contratual = 1 and sitcli < 2 and
			month(vencimento)='$mes' and Year(vencimento)='$ano' AND c.data_documento is NULL";
	$ql9 = mysql_query($sql9,$con);
	while ($array = mysql_fetch_array($ql9)) {
		$qtd_ctr_pendente = $array['count(*)'];
		$somfat = $array['total'];
		$ntot_ctr_pendente = $somfat;
		$tot_ctr_pendente = number_format($somfat, 2, ',', '.');
		
	}
	
	//linha E
	$tot_cod_demonstrativo = '0,00';
	$sql1="SELECT c.valor AS soma 
				FROM franquia a
				INNER JOIN cadastro b ON a.id = b.id_franquia_jr
				INNER JOIN titulos c ON b.codloja = c.codloja
				INNER JOIN logon d ON b.codloja = d.codloja
				WHERE a.id='$id_franquia_jr' and MONTH(c.vencimento)='$mes' AND YEAR(c.vencimento)='$ano'
						AND mid(numdoc,1,1) <> '9' AND d.franqueado = 'S' ";
	$ql1 = mysql_query($sql1,$con);
	while ($array = mysql_fetch_array($ql1)) {
		$tot_cod_demonstrativo = $array['soma'];
		$ntot_cod_demonstrativo = $tot_cod_demonstrativo;
		$tot_cod_demonstrativo = number_format($tot_cod_demonstrativo, 2, ',', '.');
	}	
	
?>
<form name="form" action="painel.php?pagina1=franqueado_jr/repasse.php" method="post">
<table width="100%" align="center">
  <tr>
    <td width="50%"><img src="../img/logo.gif" border="0" /></td>
    <td class="pageName">Relat&oacute;rio de Repasses</td>
  </tr>
  <tr class="bodyText">
    <td>Franqueado JUNIOR : 
    <?php
	$resposta = mysql_query("select razaosoc from franquia where id='$id_franquia_jr'", $con);
	$consulta = mysql_fetch_array($resposta);
	echo $consulta["razaosoc"];
	?>    </td>
    <td>M&ecirc;s de Refer&ecirc;ncia: <?php echo $mes." - ".$ano; ?></td>
  </tr>
  <tr class="bodyText">
    <td>Total de Contratos Fechados: <?php echo $totvendas; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
    <table width="671" class="bodyText">
      <tr class="titulo">
        <td width="548"><div align="left">A) Faturamento Total (<?php echo $qtd_faturado; ?>)</div></td>
        <td width="111"><div  align="right">R$ <?php echo number_format($tot_faturado_bruto, 2, ',', '.'); ?></div></td>
      </tr>
      </table>
    <table width="671">
      <tr>
        <td width="548" class="titulo"><div align="left">B) Faturas Pagas em Banco  (<?php echo $qtd_faturas_pagas_bco; ?>)</div></td>
        <td width="111" align="right" class="titulo"><div align="right"> R$ <?php echo $tot_faturas_pagas_bco; ?></div></td>
      </tr>
      <tr>
        <td class="titulo"><div align="left">C) Faturas Pentendes de Pagamentos (<?php echo $qtd_faturas_pendentes; ?>)</div></td>
        <td align="right" class="titulo"><div align="right">R$ <?php echo $tot_faturas_pendentes;?></div></td>
      </tr>
      <tr>
        <td class="titulo"><div align="left">D) <font color="#FF0000">Contratos Pendentes a mais de 60 dias (<?php echo $qtd_ctr_pendente; ?>) - Soma das Faturas</font></div></td>
        <td align="right" class="titulo"><div align="right"><font color="#FF0000"> R$ <?php echo $tot_ctr_pendente; ?></font></div></td>
      </tr>
      <tr>
        <td class="titulo"><div align="left">E) <font color="#FF0000">C&oacute;digo Demonstrativo</font></div></td>
        <td align="right" class="titulo"><div align="right"><font color="#FF0000"> R$ <?php echo $tot_cod_demonstrativo; ?></font></div></td>
      </tr>
      
      <tr>
        <td class="titulo_e_nois"><div align="left">F) Repasse  ( B - D - E ) &nbsp;&nbsp; x &nbsp;&nbsp; <?=$participacao?> %</div></td>
        <td align="right" class="titulo_e_nois"><div align="right"><b>
          <?php
			$retma = $ntot_faturas_pagas_bco - ( $ntot_ctr_pendente + $ntot_cod_demonstrativo );
			$saldo = ( ( $retma * $participacao ) / 100 );
			$retmat = number_format($saldo, 2, ',', '.');			
			if ( $saldo < 0 )
				$txt = "<font color='red'>R$ $retmat</font>";
			else
				$txt = "<font color='green'>R$ $retmat</font>";
			echo $txt ;
        ?>
        </b></div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
  	<td><input type="submit" name="retorna" value="Retorna"  style="cursor:pointer"/></td>
  </tr>
</table>
</form>