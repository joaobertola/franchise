<script language="javascript">
function Gerar_Boletos(registro){
	frm = document.form;
    frm.action = 'painel.php?pagina1=area_restrita/d_cliente_atraso_antecipacao3.php&lista='+registro;
	frm.submit();
}

</script>
<?php

function geraTimestamp($data) {
        $partes = explode('/', $data);
        return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
}

require "connect/sessao.php";
require "connect/funcoes.php";


$escolha   = $_REQUEST['escolha'];
$data      = $_REQUEST['data_limite'];	
$qtd       = count($escolha);
?>
<form method='post' action='painel.php?pagina1=area_restrita/d_cliente_atraso_antecipacao3.php' name='form'>
	<table width='80%' border='1'>
		<tr>
			<td class='campoesquerda' style='text-align:center'>
				<div class='titulo'>Gera&ccedil;&atilde;o de Boleto - Antecipa&ccedil;&atilde;o em Atraso</div><hr>
<?

	$xml .= "	<lista>";
	for ( $i = 0 ; $i < $qtd ; $i++ ){
	
		$id      = $escolha[$i]; // Id do titulo Escolhido
		$xml    .= "<dados><id_titulo>$id</id_titulo>";
		$lista  .= $codloja.';';

		$sql = "SELECT  a.numero_parcela, date_format(data_vencimento, '%d/%m/%Y') as data_vencimento,
						a.valor_parcela, b.nomefantasia, b.razaosoc, a.protocolo, a.codloja
				FROM cs2.cadastro_emprestimo a
				INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
				WHERE 
					id = $id";
		$qry = mysql_query($sql);
		$linha = '';
		$total_cliente = 0;
		while ( $reg = mysql_fetch_array($qry) ){
			echo $codloja       = $reg['codloja'];
			$vencimento    = $reg['data_vencimento'];
			$valor_parcela = $reg['valor_parcela'];
			$nomefantasia  = $reg['nomefantasia'];
			$razaosocial   = $reg['razaosoc'];
			$protocolo     = $reg['protocolo'];
			$mes           = date('m');
			$ano           = date('Y');
			$ultimo_dia    = date("t", mktime(0,0,0,$mes,'01',$ano));
			$data_hoje     = "$ultimo_dia/$mes/$ano";
			$time_inicial  = geraTimestamp($vencimento);
			$time_final    = geraTimestamp($data_hoje);
			$diferenca     = $time_final - $time_inicial; // segundos
			$dif_dias      = (int)floor( $diferenca / (60 * 60 * 24)); // dias
			$multa         = $valor_parcela * 0.02;
			$juros_dias    = 5 / 30;
			$vrjuros       = $valor_parcela * ( ( $dif_dias * $juros_dias ) / 100 );
			$vr_parc_atual = $valor_parcela + $multa + $vrjuros;
			$total_cliente += $vr_parc_atual;
			$vr_parc_at2   = $vr_parc_atual;
			$vr_parc_atual = 'R$ '.number_format($vr_parc_atual,2, ',','.');
			$valor_parcela = 'R$ '.number_format($valor_parcela,2, ',','.');
			$xml .= "		<titulo>";
			$xml .= "			<codloja>$codloja</codloja>";
			$xml .= "			<vencimento>$vencimento</vencimento>";
			$xml .= "			<valor_parcela>$valor_parcela</valor_parcela>";
			$xml .= "			<data_calculo>$data_hoje</data_calculo>";
			$xml .= "			<dias_vencido>$dif_dias</dias_vencido>";
			$xml .= "			<protocolo>$protocolo</protocolo>";
			$xml .= "			<valor_atualizado>$vr_parc_at2</valor_atualizado>";
				
			$mensagem = "Vencimento: $vencimento - Valor Parcela: $valor_parcela - Dias Atraso: $dif_dias ($data_hoje) - Valor Parcela Atualizada: $vr_parc_atual<br>";
			$mensagemx = "Vencimento: $vencimento - Valor Parcela: $valor_parcela - Dias Atraso: $dif_dias ($data_hoje) - Valor Parcela Atualizada: $vr_parc_atual|";
			
			$linha     .= $mensagem;
			$xml .= "			<mensagem>$mensagemx</mensagem>";
			
			$lista  .= "$vr_parc_at2;";
			$xml .= "		</titulo>";
		}
		$xml .= "	</dados>";
		$lista  .= "|";
		$total_cliente = number_format($total_cliente,2, ',','.');
		echo "<p>Cliente: $razaosocial ($nomefantasia)<br><br>$linha<br>Valor Total Corrigido: R$ $total_cliente<hr></p>";
		$lista .= $linha;
		$lista .= '{}';
	}
	
	$lista = substr($lista,0,strlen($lista)-2);

	$xml .= "</lista>";
	@mysql_free_result($qry);
	
?>		
				<div align='center'>
	                <input type='hidden' name='xml' value='<?=$xml?>'/>
					<input type='submit' value='    Gerar Boleto (s)    '/>
				</div>
			</td>
		</tr>
	</table>
</form>