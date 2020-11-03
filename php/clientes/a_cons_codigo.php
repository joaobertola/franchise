<?php
require "connect/sessao.php";

$codigo = $_GET['codigo'];

$comando = "select a.codloja, a.razaosoc, a.insc, a.nomefantasia, a.uf, a.cidade, a.bairro, a.end, a.cep, a.fone,
			a.fax, a.email, a.tx_mens, a.id_franquia, c.fantasia, date_format(a.dt_cad, '%d/%m/%Y') as data, a.sitcli,
			d.descsit, a.ramo_atividade, a.obs, a.celular, a.fone_res, a.socio1, a.socio2, a.cpfsocio1, a.cpfsocio2,
			a.emissao_financeiro, a.vendedor, MID(b.logon,1,LOCATE('S', b.logon) - 1) as logon, mid(b.logon,8,10) as senha, e.descricao, f.nbanco,
			a.agencia_cliente, a.conta_cliente, a.cpfcnpj_doc, a.tpconta, a.nome_doc, a.tx_mens_anterior from cadastro a
			inner join logon b on a.codloja=b.codloja
			inner join franquia c on a.id_franquia=c.id
			inner join situacao d on a.sitcli=d.codsit
			inner join classif_cadastro e on a.classe=e.id
			left outer join consulta.banco f on a.banco_cliente=f.banco
			where a.codloja='$codigo'";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res);
$codloja = $matriz['codloja'];

$sql = "select MID(logon,1,LOCATE('S', logon) - 1) as logon, MID(logon,LOCATE('S', logon) + 1,10) as senha, sitlog from logon where codloja='$codloja' limit 1";
$resposta = mysql_query ($sql, $con);
$log = mysql_fetch_array($resposta);

$command = "select a.codcons, b.nome, a.valorcons, b.vr_custo, c.qtd from valconscli a 
			inner join valcons b on a.codcons=b.codcons
			left join bonificadas c on a.codloja = c.codloja  and a.codcons = c.tpcons
			where a.codloja=$codloja and a.codcons<>'A0409'";
$result = mysql_query ($command, $con);
$linhas = mysql_num_rows ($result);
$linhas1 = $linhas + 3;
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
?>

<table border="0" align="center" width="643" class="noprint">
  <tr>
    <td colspan="2" class="titulo" align="center">CLIENTES WEB CONTROL EMPRESAS</td>
  </tr>
  <tr>
    <td class="subtitulodireita">ID</td>
    <td class="subtitulopequeno"><?php echo $codloja; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo de Cliente </td>
    <td class="campojustificado"><?php echo $log['logon']; ?></td>
  </tr>
  <!--<tr>
    <td class="subtitulodireita">Senha</td>
    <td class="subtitulopequeno">
	<?php echo $log['senha']; ?></td>
  </tr>-->
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
          <td class="campoesquerda">Banco</td>
          <td><?php echo $matriz['nbanco']; ?>
              </select></td>
        </tr>
        <tr>
          <td class="campoesquerda">Ag&ecirc;ncia + DV (ex.: 1234-5) </td>
          <td class="subtitulopequeno"><?php echo $agencia_cliente; ?></td>
        </tr>
        <tr>
          <td class="campoesquerda">Conta  + DV (ex.: 123456-7) </td>
          <td class="subtitulopequeno"><?php echo $conta_cliente; ?></td>
        </tr>
        <tr>
          <td class="campoesquerda">Tipo de Conta</td>
          <td class="subtitulopequeno">
		  	<?php 
				if ($matriz['tpconta'] == 2) echo "Poupan&ccedil;a"; 
				else if ($matriz['tpconta'] == 1) echo "Conta Corrente";
				else echo "";
			?>          </td>
        </tr>
        <tr>
          <td class="campoesquerda">Nome do Correntista </td>
          <td class="subtitulopequeno"><?php echo $matriz['nome_doc']; ?></td>
        </tr>
        <tr>
          <td class="campoesquerda">CPF / CNPJ do Titular </td>
          <td class="subtitulopequeno"><?php echo $matriz['cpfcnpj_doc']; ?></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Franqueado</td>
    <td class="subtitulopequeno"><?php echo $matriz['fantasia']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Tipo de Contrato</td>
    <td class="subtitulopequeno"><?php echo $matriz['descricao']; ?></td>
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
    <td class="subtitulodireita">Mensalidade</td>
    <td class="subtitulopequeno">R$&nbsp;<?php echo $matriz['tx_mens']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Mensalidade Anterior</td>
    <td class="subtitulopequeno">R$ <?php echo $matriz['tx_mens_anterior']; ?></td>
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
				<td rowspan="<?php echo $linhas1; ?>" width="1" bgcolor="#999999"></td>
			</tr>
			<tr>
				<td colspan="6" height="1" bgcolor="#666666">				</td>
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
					</tr>";
				}
				echo "<tr>
						<td colspan=\"5\" align=\"right\" height=\"1\" bgcolor=\"#666666\"></td>
					</tr>";
			?>			
		</table>	</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Observa&ccedil;&otilde;es</td>
    <td class="formulario"><textarea name="obs" cols="50" rows="3"><?php echo $matriz['obs']; ?></textarea></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Situa&ccedil;&atilde;o do Contrato</td>
    <td class="formulario" <?php if ($matriz['sitcli'] == 0) {
								echo "bgcolor=\"#33CC66\"";
								} else {
								echo "bgcolor=\"#FF0000\"";} ?> ><font color="#FFFFFF"><?php echo $matriz['descsit']; ?></font></td>
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
  <tr>
    <td class="subtitulodireita">Situa&ccedil;&atilde;o de Cobran&ccedil;a</td>
    <td class="subtitulopequeno"><b><?php if ($matriz['sit_cobranca'] == 0) echo "ATIVO"; 
									   else echo "NEGATIVADO"; ?></b></td>
  </tr>
  <?php 
  $ssql="select date_format(a.data_documento,'%d/%m/%Y') as documento, a.tipo_documento, b.motivo,
		date_format(a.ultima_fatura,'%d/%m/%Y') as ultima from pedidos_cancelamento a
		inner join motivo_cancel b on a.id_mot_cancelamento=b.id
		where codloja='$codloja'";
  $rs = mysql_query($ssql);
  $line = mysql_num_rows ($rs);
  if ($line != 0) {
  while ($fila = mysql_fetch_object($rs)) {
  echo "
  <tr>
    <td class=\"subtitulodireita\">Dados do Cancelamento </td>
    <td class=\"subtitulopequeno\"><table>
      <tr>
        <td class=\"subtitulodireita\">Data do Documento</td>
        <td class=\"campoesquerda\">$fila->documento</td>
      </tr>
      <tr>
        <td class=\"subtitulodireita\">Doc. de Cancelamento</td>
        <td class=\"campoesquerda\">$fila->tipo_documento</td>
      </tr>
      <tr>
        <td class=\"subtitulodireita\">Motivo do Cancelamento</td>
        <td class=\"campoesquerda\">$fila->motivo</td>
      </tr>
      <tr>
        <td class=\"subtitulodireita\">&Uacute;ltima Fatura</td>
        <td class=\"campoesquerda\">$fila->ultima</td>
      </tr>
    </table></td>
  </tr>";
  }
  mysql_free_result($rs);
  }
  ?>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <form method="post" action="painel.php?pagina1=clientes/a_incclient.php" /><tr align="right">
    <td colspan="2"><input name="incluir" type="submit" value="                Incluir novo Cliente" /></td>
  </form></tr>
  <form method="post" action="painel.php?pagina1=clientes/a_altcliente1.php&codloja=<?php echo $codloja; ?>" /><tr align="right">
    <td colspan="2">
		<input name="alterar" type="submit" value="Alterar os dados do Cliente" />	</td>
  </form></tr>
</table>
<?php 
$res = mysql_close ($con); 
?>