<?php
require "connect/sessao_r.php";

$class = $_SESSION['ss_classificacao'];

if ($class == 'J') $msg_class = "Franqueado Junior";
else $msg_class = "Micro Franquia";
?>
<tr class="menu">
	<td align="center">&nbsp;</td>
	<td><?= $msg_class ?></td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td>
		<?php
		if ($class == 'X') $link = "painel.php?pagina1=clientes/a_incclient.php";
		else $link = "#";
		?>
		<a href="<?= $link ?>" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Inclusão de Clientes</a>
	</td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=clientes/a_altsenha.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Alteração de Senha</a></td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=clientes/a_faturas.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Fatura dos Clientes</a></td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=Franquias/b_liberaconsulta.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Liberação do limite de consultas</a></td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=ocorrencias/a_ocorrencia.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Registro de Atendimento</a></td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=franqueado_jr/tabela_preco.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Tabela de Preços</a></td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=Franquias/b_extratocontratos.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Extratos de Contratos</a></td>
</tr>
<?php if ($_SESSION['id'] == '163' || $_SESSION['id'] == '4') : ?>
	<tr>
		<td align="center"><?php echo $i++; ?> - </td>
		<td><a href="https://webcontrolempresas.com.br/apiranking/rabking-ti?u=franquias&id=<?= md5($_SESSION['id']); ?>" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Ranking Usuários CPD</a></td>
	</tr>
<?php endif; ?>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=ranking/c_ranksemanal.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Ranking Semanal de Vendas</a></td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=ranking/c_rankrent.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Ranking Mensal de Vendas</a></td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=franqueado_jr/extrato_contacorrente.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">
			<font color="#0033FF"><b>Extrato de Prestação de Contas</b></font>
		</a></td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?> - </td>
	<td><a href="painel.php?pagina1=franqueado_jr/documentos_diversos.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">
			<font color="#FF6600"><b>Documentos Diversos</b></font>
		</a></td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?></td>
	<td><a href="painel.php?pagina1=clientes/a_controle_visitas0.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">
			<font color="#00CC00"><b>
					<blink>Controle Comercial</blink>
				</b></font>
		</a></td>
</tr>
<tr>
	<td align="center"><?php echo $i++; ?></td>
	<td><a href="painel.php?pagina1=area_restrita/a_solicitacao_valores.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">
			<font color="#00CC00"><b>
					<blink>Requisições Financeiras</blink>
				</b></font>
		</a></td>
</tr>