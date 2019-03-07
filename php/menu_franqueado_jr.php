<?php
require "connect/sessao_r.php";

$class = $_SESSION['ss_classificacao'];

if ( $class == 'J' ) $msg_class = "Franqueado Junior";
else $msg_class = "Micro Franquia";
?>
<tr class="menu">
	<td align="center">&nbsp;</td>
	<td><?=$msg_class?></td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=franqueado_jr/inclusao_cliente.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Inclusão de Clientes</a></td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=franqueado_jr/faturas_cliente.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Fatura dos Clientes</a></td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=franqueado_jr/ocorrencia.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Registro de Atendimento</a></td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=franqueado_jr/tabela_preco.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Tabela de Preços</a></td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=franqueado_jr/extrato_contratos.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Extratos de Contratos</a></td>
</tr>
<?php if ( $class == 'J' ) { ?>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=franqueado_jr/repasse.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Relatório de Repasses</a></td>
</tr>
<?php }?>
<?php if ( $class == 'X' ) { ?>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=franqueado_jr/extrato_contacorrente.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');"><font color="#0033FF"><b>Extrato de Prestação de Contas</b></font></a></td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=franqueado_jr/documentos_diversos.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');"><font color="#FF6600"><b>Documentos Diversos</b></font></a></td>
</tr>

<?php }?>