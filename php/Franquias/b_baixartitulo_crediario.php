<?php

function verifica_emprestimo($codloja){
	global $con;
	$now = date('Y-m-d');
	# Verifico se o cliente tem Antecipacao nao pagos
	$sql_antecipa = "SELECT sum(valor_parcela) AS valor_parcela
						FROM cs2.cadastro_emprestimo
					 WHERE  codloja = $codloja 
					 	AND 
							data_vencimento <= '$now' 
						AND 
							valor_pagamento IS NULL";
	$qr_antecipa = mysql_query($sql_antecipa,$con) or die ("Erro no SQL: $sql_antecipa");
	$valor_ant = mysql_result($qr_antecipa,0,'valor_parcela');
	if ( $valor_ant == '' )	$valor_ant = 0;
	return $valor_ant;
}

require "connect/sessao.php";

$codloja 		= $_REQUEST['codloja'];
$numdoc 		= $_REQUEST['numdoc'];
$valorcomjuros	= $_REQUEST['valorcomjuros'] ;
$logon			 = $_REQUEST['logon'];
$saldo_crediario = '0,00';

$sql_saldo = "SELECT saldo FROM cs2.contacorrente_recebafacil WHERE codloja='$codloja' order by id";
$qr2 = mysql_query($sql_saldo,$con) or die ("\nErro ao gerar o extrato\n".mysql_error()."\n\n");
while ($matriz = mysql_fetch_array($qr2)) {
	$saldo_crediario 	= number_format($matriz['saldo'],2,",",".");
}

if ( $saldo_crediario > 0 ){

	// Verifiicando EMPRESTIMO
	$valor_ant = verifica_emprestimo($codloja);
	# j� que est� inadimplente ZERO o saldo para n�o enviar
	if ( $valor_ant > 0 ){
		echo "<script>alert('Desculpe, este cliente tem PARCELA DE ANTECIPACAO em ATRASO');history.back()</script>";
		exit;
	}else{

		$comando = "select a.numdoc, b.razaosoc, b.nomefantasia, DATE_FORMAT(a.vencimento,'%d/%m/%Y') as venc, a.valor, a.numboleto from titulos a
				inner join cadastro b on a.codloja = b.codloja
				where numdoc='$numdoc'";
		$conex = mysql_query($comando, $con);
		$matriz = mysql_fetch_array($conex);
		$hoje = date('d/m/Y');
	}
}else{
	echo "<script>alert('Cliente sem saldo para COMPENSACAO');history.back()</script>";
	exit;
}

?>
<body>
<form name="baixa" method="post" action="painel.php?pagina1=Franquias/b_baixartitulo_crediario_fim.php" >

<input type="hidden" name="numdoc" value="<?=$_REQUEST['numdoc']?>">
<input type="hidden" name="numboleto" value=<?php echo $matriz['numboleto'];?>">
<input type="hidden" name="codloja" value="<?=$_REQUEST['codloja']?>">
<input type="hidden" name="valor_cobrado" value="<?php echo $valorcomjuros; ?>">
<input type="hidden" name="saldo_recupere" value="<?php echo $saldo_crediario; ?>">
<input type="hidden" name="vencimento" value="<?php echo $matriz['venc']; ?>">
<input type="hidden" name="logon" value="<?=$logon?>">

<table width="90%" border="0" align="center">
<tr>
  <td colspan="2" class="titulo">Baixa de T&iacute;tulos - DESCONTO NO SALDO DO CREDI�RIO / RECUPERE/ BOLETO</td>
</tr>
<tr>
  <td colspan="2" class="campoesquerda">&nbsp;</td>
</tr>
<tr>
  <td colspan="2" class="titulo">SALDO CREDI&Aacute;RIO / RECUPERE / BOLETO : R$  <?php echo $saldo_crediario; ?></td>
</tr>
<tr>
  <td colspan="2" class="campoesquerda">&nbsp;</td>
</tr>
<tr>
  <td class="subtitulodireita">N&ordm; do T&iacute;tulo</td>
  <td class="subtitulopequeno"><?php echo $matriz['numdoc']; ?><input name="numdoc" type="hidden" value="<?php echo $matriz['numdoc']; ?>" ></td>
</tr>
<tr>
  <td class="subtitulodireita">Sacado</td>
  <td class="subtitulopequeno"><?php echo $matriz['razaosoc']; ?></td>
</tr>
<tr>
  <td class="subtitulodireita">Nome de Fantas&iacute;a </td>
  <td class="subtitulopequeno"><?php echo $matriz['nomefantasia']; ?></td>
</tr>
<tr>
  <td class="subtitulodireita">Vencimento</td>
  <td class="subtitulopequeno"><?php echo $matriz['venc']; ?></td>
</tr>
<tr>
  <td class="subtitulodireita">Valor</td>
  <td class="subtitulopequeno"><?php echo $matriz['valor']; ?></td>
</tr>
<tr>
  <td class="subtitulodireita">Data de Pagamento </td>
  <td class="subtitulopequeno"><?php echo $hoje; ?></td>
</tr>
<tr>
  <td class="subtitulodireita">Valor pago </td>
  <td class="subtitulopequeno"><?php echo $valorcomjuros; ?></td>

  </td>
</tr>
  <tr>
    <td class="subtitulodireita">Origem do Pagamento</td>
    <td class="campoesquerda"><?php echo ' COMP' ?></td>
  </tr>

<tr>
  <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
<tr>
  <td colspan="2"><br>
    <p align="center">  
  <input type="submit" name="Submit" value="  CONFIRMAR RECEBIMENTO  "> <input type="reset" name="reset" value="  CANCELAR  " onClick="history.back()">
    </p></td>
</tr>
</table>
</form>
</body>