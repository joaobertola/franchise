<script type="text/javascript">
	function valida(){
		frm = document.form;
	    frm.action = 'painel.php?pagina1=area_restrita/d_cliente_atraso_antecipacao2.php';
		frm.submit();	
	}
	
	function CheckAll() {
		for (var i=0;i<document.form.elements.length;i++) {
			var x = document.form.elements[i];
			if (x.name == 'escolha[]')
				x.checked = document.form.selall.checked;
		}
	}
</script>

<?php
require "connect/sessao.php";
require "connect/funcoes.php";

$sql    = "select subdate(now(), interval 30 day) data";
$qry    = mysql_query($sql,$con);
$campos = mysql_fetch_array($qry);
$data   = substr($campos["data"],0,10);

$sql = "SELECT a.id, a.codloja, a.data_solicitacao, a.qtd_parcelas, a.numero_parcela,
			   a.data_vencimento, a.vr_emprestimo_solicitado, a.valor_parcela,
			   b.nomefantasia, a.protocolo
		FROM cs2.cadastro_emprestimo a
		INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
		WHERE 
				data_vencimento <= '$data' 
			AND 
				valor_pagamento IS NULL
		ORDER BY a.codloja";
$qry = mysql_query($sql,$con);
if ( mysql_num_rows($qry) == 0 ){
	echo "Nenhum cliente com empréstimo ATRASADO.";
	exit;
}

?>
<form method="post" action="#" name='form'>
<table width=70% border="0" align="center">
	<tr class="titulo">
		<td colspan="7">Relat&oacute;rio Clientes com Antecipação em Atraso</td>
	</tr>
	<tr>
		<td colspan="7">&nbsp;</td>
	</tr>
    <tr>
		<td class="campoesquerda" ><input type=checkbox name="selall" onClick="CheckAll()"></td>
		<td class="campoesquerda" >Cliente</td>
		<td class="campoesquerda" style="text-align:center">Data Emprestimo</td>
		<td class="campoesquerda" style="text-align:center">Numero Parcela</td>
		<td class="campoesquerda" style="text-align:center">Vencimento</td>
		<td class="campoesquerda" style="text-align:center">Valor Parcela</td>
        <td class="campoesquerda">&nbsp;</td>
	</tr>
    <?php
	$totg_atraso = 0;
	while ( $reg = mysql_fetch_array($qry) ){
		$id                        = $reg['id'];
		$codloja                   = $reg['codloja'];
		$nomefantasia              = $reg['nomefantasia'];
		$data_solicitacao          = data_mysql_i($reg['data_solicitacao']);
		$qtd_parcelas              = $reg['qtd_parcelas'];
		$protocolo                 = $reg['protocolo'];
		$numero_parcela            = $reg['numero_parcela'];
		$data_vencimento           = data_mysql_i($reg['data_vencimento']);
		$valor_parcela             = $reg['valor_parcela'];
		$totg_atraso              += $valor_parcela;
		$valor_parcelam            = number_format($valor_parcela,2,',','.');
		
		$sql_titulo = "SELECT count(*) as qtd, id_antecipacao FROM cs2.titulos_antecipacao
					   WHERE codloja = $codloja AND id_antecipacao = '$id'";
		$qry_titulo = mysql_query($sql_titulo,$con);
		if ( mysql_result($qry_titulo,0,'qtd') > 0 ){
			
			$checkbox  = '';
			$id_titulo = mysql_result($qry_titulo,0,'id_antecipacao');
			$acao = "<a href='painel.php?pagina1=area_restrita/d_cliente_atraso_antecipacao_boleto.php&id_titulo=$id_titulo'>Ver Boleto</a>";
		}else{
			$checkbox = "<input type='checkbox' name='escolha[]' value='$id'>";
			$acao     = "";
		}
		?>
	    <tr height="25">
        	<td class="subtitulodireita"><?=$checkbox?></td>
			<td class="subtitulodireita" style="text-align:left"><?php echo "$codloja - $nomefantasia";?></td>
			<td class="subtitulodireita" style="text-align:center">
				<?php echo "$data_solicitacao"; ?>
             </td>
			<td class='campoesquerda' style='text-align:center'><?php echo $numero_parcela.'/'.$qtd_parcelas;?></td>
			<td class='campoesquerda' style='text-align:center'><?=$data_vencimento?></td>
			<td class='campoesquerda' style='text-align:center'><?=$valor_parcelam?></td>
            <td class="subtitulodireita"><?=$acao?></td>
        </tr>
		<?php
	}
	@mysql_free_result($qry);
	@mysql_free_result($qry2);
	@mysql_free_result($qry_titulo);
?>

	<tr>
		<td colspan="7">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="7" align="right">Total Geral atrasado: <font color="#FF0000"><b>R$ <?=number_format($totg_atraso,2,',','.')?></b></font></td>
	</tr>

	<tr>
		<td colspan="7">
        	<input type="hidden" name="data_limite" value="<?=$data?>"/>
        	<input type="button" value="Gerar Boleto de Cobran&ccedil;a" onclick="valida()"/></td>
	</tr>
</table>
</form>
