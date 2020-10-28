<?php

require "connect/sessao.php";

$codloja = $_GET['codloja'];

$comando = "select a.renegociacao_tabela,  a.codloja, a.razaosoc, a.insc, a.nomefantasia, a.uf, a.cidade, a.bairro, a.end, a.cep, a.fone,
			a.fax, a.email, a.sitcli, a.tx_mens, b.fantasia, date_format(a.dt_cad, '%d/%m/%Y') as data, d.descsit, a.obs,
			a.ramo_atividade, a.celular, a.fone_res, a.socio1, a.socio2, a.cpfsocio1, a.cpfsocio2,
			a.emissao_financeiro, a.vendedor, c.descricao, e.nbanco, a.agencia_cliente, a.conta_cliente,
			a.cpfcnpj_doc, a.nome_doc, f.nome_concorrente, a.tpconta from cs2.cadastro a
			inner join cs2.franquia b on a.id_franquia=b.id
			inner join cs2.classif_cadastro c on a.classe=c.id
			inner join cs2.situacao d on a.sitcli=d.codsit
			left outer join consulta.banco e on a.banco_cliente=e.banco
			left outer join cs2.concorrentes f on a.origem=f.id
			where codloja='$codloja'";
$res = mysql_query ($comando, $con) or die("erro: $comando");
$matriz = mysql_fetch_array($res);

$sql = "select CAST(MID(logon,1,6) AS UNSIGNED), mid(logon,7,10), sitlog from logon where codloja='$codloja' limit 1";
$resposta = mysql_query ($sql, $con);
$log = mysql_fetch_array($resposta);

$command = "select a.codcons, b.nome, a.valorcons, b.vr_custo, c.qtd from valconscli a 
			inner join valcons b on a.codcons=b.codcons
			left join bonificadas c on a.codloja = c.codloja  and a.codcons = c.tpcons
			where a.codloja=$codloja;";
$result = mysql_query ($command, $con);
$linhas = mysql_num_rows ($result);
$linhas1 = $linhas + 3;
$sitcli = $matriz['sitcli'];

//tratamento para agencia e conta corrente
$agencia_cliente = $matriz['agencia_cliente'];
$agencia_cliente = strtoupper($agencia_cliente);
	
if (strlen($agencia_cliente) > 4) {
	$agencia_cliente = substr($agencia_cliente,0,4).'-'.substr($agencia_cliente,4,1);
} else {
	$agencia_cliente = substr($agencia_cliente,0,4);
}
$conta_cliente = $matriz['conta_cliente'];
//$conta_cliente = 100000000000 + $matriz['conta_cliente'];
//$conta_cliente = substr($conta_cliente,1,10).'-'.substr($conta_cliente,11,1);

$renegociacao_tabela =  $matriz['renegociacao_tabela'];
$dia = substr($renegociacao_tabela, 8,10);   
$mes = substr($renegociacao_tabela, 5,2);   
$ano = substr($renegociacao_tabela, 0,4);   
$data_view.=$dia;
$data_view.="/";
$data_view.=$mes;
$data_view.="/";
$data_view.=$ano;
?>
<table border="0" align="center" width="643">
  <tr>
    <td colspan="2" class="titulo" align="center">CLIENTES WEB CONTROL EMPRESAS</td>
  </tr>
  <tr>
    <td class="subtitulodireita">ID</td>
    <td class="subtitulopequeno"><?php echo $matriz['codloja']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo de Cliente </td>
    <td class="campojustificado"><?php echo $log['CAST(MID(logon,1,6) AS UNSIGNED)']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Senha</td>
    <td class="subtitulopequeno">
	<?php echo $log['mid(logon,7,10)']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Raz&atilde;o Social</td>
    <td class="subtitulopequeno"><?php echo $matriz['razaosoc']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Nome Fantasia</td>
    <td class="subtitulopequeno"><?php echo $matriz['nomefantasia']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">CNPJ</td>
    <td class="subtitulopequeno"><?php echo $matriz['insc']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Endere&ccedil;o</td>
    <td class="subtitulopequeno"><?php echo $matriz['end']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Bairro</td>
    <td class="subtitulopequeno"><?php echo $matriz['bairro']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">UF</td>
    <td class="subtitulopequeno"><?php echo $matriz['uf']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Cidade</td>
    <td class="subtitulopequeno"><?php echo $matriz['cidade']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">CEP</td>
    <td class="subtitulopequeno"><?php echo $matriz['cep']; ?></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Telefone</td>
    <td class="subtitulopequeno"><?php echo $matriz['fone']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Fax</td>
    <td class="subtitulopequeno"><?php echo $matriz['fax']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Celular</td>
    <td class="subtitulopequeno"><?php echo $matriz['celular']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Telefone
    Residencial</td>
    <td class="subtitulopequeno"><?php echo $matriz['fone_res']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">E-mail</td>
    <td class="subtitulopequeno"><?php echo $matriz['email']; ?></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Propriet&aacute;rio 1 </td>
    <td class="subtitulopequeno"><table border="0">
      <tr>
        <td class="subtitulodireita">Nome</td>
        <td class="campoesquerda"><?php echo $matriz['socio1']; ?></td>
      </tr>
      <tr>
        <td class="subtitulodireita">CPF 1</td>
        <td class="campoesquerda"><?php echo $matriz['cpfsocio1']; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Propriet&aacute;rio 2</td>
    <td class="subtitulopequeno"><table border="0">
      <tr>
        <td class="subtitulodireita">Nome</td>
		<td class="campoesquerda"><?php echo $matriz['socio2']; ?></td></tr>
      <tr>
        <td class="subtitulodireita">CPF 2</td>
        <td class="campoesquerda"><?php echo $matriz['cpfsocio2']; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Segmento Empresarial</td>
    <td class="subtitulopequeno"><?php echo $matriz['ramo_atividade']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Vendedor</td>
    <td class="subtitulopequeno"><?php echo $matriz['vendedor']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Dados da Conta Corrente Receba F&aacute;cil</td>
    <td class="subtitulopequeno"><table border="0" class="subtitulopequeno">
        <tr>
          <td class="campoesquerda">Banco (ex.: 1234-5) </td>
          <td><?php echo $matriz['nbanco']; ?>
          </select></td>
        </tr>
        <tr>
          <td class="campoesquerda">Ag&ecirc;ncia (ex.: 123456-7) </td>
          <td class="subtitulopequeno"><?php echo $agencia_cliente; ?></td>
        </tr>
        <tr>
          <td class="campoesquerda">Conta </td>
          <td class="subtitulopequeno"><?php echo $conta_cliente; ?></td>
        </tr>
        <tr>
          <td class="campoesquerda">Tipo de Conta</td>
          <td class="subtitulopequeno">
		  	<?php if ($matriz['tpconta'] == 2) echo "Poupan&ccedil;a"; 
				else echo "Conta Corrente"; ?>
          </td>
        </tr>
        <tr>
          <td class="campoesquerda">Nome do Respons&aacute;vel</td>
          <td class="subtitulopequeno"><?php echo $matriz['nome_doc']; ?></td>
        </tr>
        <tr>
          <td class="campoesquerda">CPF / CNPJ </td>
          <td class="subtitulopequeno"><?php echo $matriz['cpfcnpj_doc']; ?></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Franqueado</td>
    <td class="subtitulopequeno"><?php echo $matriz['fantasia']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Origem do Cliente</td>
    <td class="subtitulopequeno"><?php echo $matriz['nome_concorrente']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Data de afilia&ccedil;&atilde;o</td>
    <td class="subtitulopequeno"><?php echo $matriz['data']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Emiss&atilde;o de Nota Fiscal e Fatura</td>
    <td valign="top" class="subtitulopequeno"><table class="campoesquerda" border="0">
      <tr>
        <td><input type="radio" name="fatura" value="1" <?php if ($matriz['emissao_financeiro'] == "1"){ echo "checked"; }?> />
          Emite fatura e relaciona a NF &uacute;nica</td>
      </tr>
      <tr>
        <td><input type="radio" name="fatura" value="2" <?php if ($matriz['emissao_financeiro'] == "2"){ echo "checked"; }?> />
          Emite s&oacute; NF individual</td>
      </tr>
      <tr>
        <td><input type="radio" name="fatura" value="3" <?php if ($matriz['emissao_financeiro'] == "3"){ echo "checked"; }?> />
          Emite fatura e NF individual</td>
      </tr>
    </table></td>
  </tr>
  
   <tr>
    <td class="subtitulodireita">Renegocia��o de Tabela</td>
    <td class="subtitulopequeno"><?=$data_view?></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Tipo de Contrato</td>
    <td valign="top" class="subtitulopequeno"><?php echo $matriz['descricao']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Mensalidade</td>
    <td valign="top" class="subtitulopequeno">R$&nbsp;<?php echo $matriz['tx_mens']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Tabela de Pre&ccedil;os</td>
    <td>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
	 		<tr>
				<td colspan="7" height="1" bgcolor="#999999"></td>
			</tr>
	 		<tr>
				<td rowspan="<?php echo $linhas1; ?>" width="1" bgcolor="#999999"></td>
			</tr>
			<tr height="20">
				<td align="center" class="campoesquerda">C&oacute;digo</td>
				<td align="center" class="campoesquerda">Produto</td>
				<td align="center" class="campoesquerda">Venda</td>
				<td align="center" class="campoesquerda">Gratuidade</td>
				<td align="center" class="campoesquerda">Custo Unitario</td>
				<td rowspan="<?php echo $linhas1; ?>" width="1" bgcolor="#999999"></td>
			</tr>
			<tr>
				<td colspan="7" height="1" bgcolor="#666666">				</td>
			</tr>
		<?php
			for ($a=1; $a<=$linhas; $a++) {
				$matrix = mysql_fetch_array($result);
				$codigo = $matrix['codcons'];
				$produto = $matrix['nome'];
				$venda = $matrix['valorcons'];
				$custo = $matrix['vr_custo'];
				$gratuidade = $matrix['qtd'];
				echo "<tr height=\"22\">
						<td align=\"center\" class=\"subtitulopequeno\">$codigo</td>
						<td align=\"left\" class=\"subtitulopequeno\">$produto</td>
						<td align=\"right\" class=\"subtitulopequeno\">$venda</td>
						<td align=\"center\" class=\"subtitulopequeno\">$gratuidade</td>
						<td align=\"right\" class=\"subtitulopequeno\">$custo</td>
					</tr>";
				}
				echo "<tr>
						<td colspan=\"6\" align=\"right\" height=\"1\" bgcolor=\"#666666\"></td>
					</tr>";
			?>			
		</table>	</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Observa&ccedil;&otilde;es</td>
    <td class="formulario"><textarea name="obs" cols="50" rows="3"><?php echo $matriz['obs']; ?></textarea></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Status</td>
    <td class="formulario"<?php if ($sitcli == 0) {
								echo "bgcolor=\"#33CC66\"";
								} elseif ($sitcli == 1) {
								echo "bgcolor=\"#FFCC00\"";
								} else {
								echo "bgcolor=\"#FF0000\"";} ?> >
		<font color="#FFFFFF">
			<?php echo $matriz['descsit']; ?>		</font>	</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Acesso</td>
	<?php
		echo "<td class=\"formulario\" style=\"color:#FFFFFF\"";
    	if ($log['sitlog'] == 0) echo "bgcolor=\"#33CC66\">ATIVO";
		elseif ($log['sitlog'] == 1) echo "bgcolor=\"#FFCC00\">BLOQUEADO";
		else echo "bgcolor=\"#FF0000\">CANCELADO"; ?>
	</td>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <form method="post" action="../clientes/painel.php?pagina1=franqueado_jr/inclusao_cliente.php" /><tr align="right">
    <td colspan="2"><input name="incluir" type="submit" value="                Incluir novo Cliente" /></td>
  </form></tr>
  <form method="post" action="../clientes/painel.php?pagina1=franqueado_jr/altera_cliente.php&amp;codloja='$codloja'; ?&gt;" /><tr align="right">
    <td colspan="2"><input name="alterar" type="submit" value="Alterar os dados do Cliente" /></td>
  </form></tr>
</table>
  
<?php $res = mysql_close ($con); ?>