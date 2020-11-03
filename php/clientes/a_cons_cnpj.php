<?php
require "connect/sessao.php";

$cnpj = $_REQUEST['cnpj'];

$cnpj=str_replace("/","",$cnpj);
$cnpj=str_replace("-","",$cnpj);
$cnpj=str_replace(".","",$cnpj);

if (($tipo == "a") || ($tipo == "c")) {
$resulta = mysql_query("select codloja, insc from cadastro where insc='$cnpj'");
} else {
$resulta = mysql_query("select codloja, insc from cadastro where insc='$cnpj' and id_franquia='$id_franquia'");
}
$linha = mysql_num_rows($resulta);
if ($linha == 0)
{
	echo "<script>alert(\"Cliente inexistente ou de outra franquia!\"); javascript: history.back();</script>";
}
else 
{
$comando = "select 
				a.id_franquia_jr, a.codloja, a.razaosoc, a.insc, a.nomefantasia, a.uf, a.cidade, a.bairro, a.end, a.cep, a.fone, a.fax, 
				a.email, a.tx_mens, b.fantasia, date_format(a.dt_cad, '%d/%m/%Y') as data, a.sitcli, d.descsit, 
				a.obs, a.ramo_atividade, a.celular, a.fone_res, a.socio1, a.socio2, a.cpfsocio1, a.cpfsocio2, 
				a.emissao_financeiro, a.vendedor, a.sit_cobranca, e.descricao, f.nbanco, a.agencia_cliente, 
				a.conta_cliente, a.cpfcnpj_doc,	a.tpconta, a.nome_doc, a.pendencia_contratual, 
				date_format(a.dt_regularizacao, '%d/%m/%Y') AS dt_regularizacao
			FROM cadastro a
			inner join franquia b on a.id_franquia=b.id
			inner join situacao d on a.sitcli=d.codsit
			inner join classif_cadastro e on a.classe=e.id
			left outer join consulta.banco f on a.banco_cliente=f.banco
			where insc='$cnpj'";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res);
$codloja = $matriz['codloja'];

$sql = "select MID(logon,1,LOCATE('S', logon) - 1) as logon, MID(logon,LOCATE('S', logon) + 1,10) as senha from logon where codloja='$codloja' limit 1";
$resposta = mysql_query ($sql, $con);
$log = mysql_fetch_array($resposta);

$command = "select a.codcons, b.nome, a.valorcons, b.vr_custo, c.qtd from valconscli a 
			inner join valcons b on a.codcons=b.codcons
			left join bonificadas c on a.codloja = c.codloja  and a.codcons = c.tpcons
			where a.codloja=$codloja;";
$result = mysql_query ($command, $con);
$linhas = mysql_num_rows ($result);
$linhas1 = $linhas + 3;

$agencia = $matriz['agencia_cliente'];
if (strlen($agencia) > 4) {
	$agencia_cliente = 100000 + $matriz['agencia_cliente'];
	$agencia_cliente = substr($agencia_cliente,1,4).'-'.substr($agencia_cliente,5,1);
} else {
	$agencia_cliente = 10000 + $agencia;
	$agencia_cliente = substr($agencia_cliente,1,4);
}
$conta_cliente = 100000000000 + $matriz['conta_cliente'];
$conta_cliente = substr($conta_cliente,1,10).'-'.substr($conta_cliente,11,1);

if($matriz['pendencia_contratual'] == "0"){
	$cor = "#33CC66";	
	$des_cpendencia_contratual = "NORMALIZADA";
}

if($matriz['pendencia_contratual'] == "1"){
	$cor = "red";	
	$des_cpendencia_contratual = "PENDENTE";
}
?>
<script type="text/javascript" src="../../js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="../../js/jquery.maskedinput-1.1.1.js"></script>
<script type="text/javascript" src="../../js/jquery.meio.mask.js"></script>

<script language="javascript">
(function($){
  $(function(){
	$('input:text').setMask();
  }
);
})(jQuery);

jQuery(function($){ 
   $("#id_data").mask("99/99/9999");   
});

function afixar(form) {
	var data = new Date();
	var dia  = data.getDate(); 
	var mes  = data.getMonth(); 
	var ano4 = data.getFullYear(); 
	var datas = dia + '/' + (mes+1) + '/' + ano4;
	document.form.dt_regularizacao.value=datas
} 

function limpar(form) {
	document.form.dt_regularizacao.value=null;
} 

</script>

<body>
<form name="form" method="post" action="#">

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
    <td class="subtitulopequeno"><?php echo $log['logon']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Senha</td>
    <td class="subtitulopequeno">
	<?php echo $log['senha']; ?></td>
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
    <td class="campojustificado"><?php echo $matriz['insc']; ?></td>
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
          <td class="campoesquerda">Ag&ecirc;ncia</td>
          <td class="subtitulopequeno"><?php echo $agencia_cliente; ?></td>
        </tr>
        <tr>
          <td class="campoesquerda">Conta + DV</td>
          <td class="subtitulopequeno"><?php echo $conta_cliente; ?></td>
        </tr>
        <tr>
          <td class="campoesquerda">Tipo de Conta</td>
          <td class="subtitulopequeno">
		  	<?php 
				if ($matriz['tpconta'] == 2) echo "Poupan&ccedil;a"; 
				else echo "Conta Corrente";
			?>
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
  
    <?php 
  if($matriz['id_franquia_jr'] > 0){ 
  $sql_jr = "SELECT fantasia AS nome_jr FROM cs2.franquia WHERE id = '{$matriz['id_franquia_jr']}'";
  $qry_jr =mysql_query ($sql_jr, $con);
  ?>
  <tr>
    <td class="subtitulodireita">Franqueado J&uacute;nior</td>
    <td class="subtitulopequeno"><?=mysql_result($qry_jr,0,'nome_jr');?></td>
  </tr>
  <?php } ?>
  
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
  
  <?php if( ($_SESSION['id'] == 163) or ($_SESSION['id'] == 46) or ($_SESSION['id'] == 4) ){ ?>
  
  <?php if($matriz['pendencia_contratual'] == "0"){ ?>
  <tr>
    <td class="subtitulodireita">Documenta&ccedil;&atilde;o Cliente (contrato, tabela, ..)</td>
    <td colspan="2" bgcolor="<?=$cor?>"><font color="#FFFFFF" style="font-size:14px" face="Arial"><b><?=$des_cpendencia_contratual?></b></font></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Data da Regulariza&ccedil;&atilde;o da Documenta&ccedil;&atilde;o</td>
    <td colspan="2" bgcolor="<?=$cor?>"><font color="#FFFFFF" style="font-size:14px" face="Arial"><b><?=$matriz['dt_regularizacao']?></b></font></td>
  </tr>
  <?php } else { ?>
  <tr>
    <td class="subtitulodireita">Documenta&ccedil;&atilde;o Cliente (contrato, tabela, ..)</td>
    <td colspan="2" bgcolor="<?=$cor?>">
    	<input type="radio" name="pendencia_contratual" value="0" onClick="afixar(form)"><font color="#FFFFFF" style="font-size:14px" face="Arial"><b>NORMALIZADA</b></font>
        <input type="radio" name="pendencia_contratual" value="1" onClick="limpar(form)"checked="checked"><font color="#FFFFFF" style="font-size:14px" face="Arial"><b>PENDENTE</b></font>
    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Data da Regulariza&ccedil;&atilde;o da Documenta&ccedil;&atilde;o</td>
    <td colspan="2" bgcolor="<?=$cor?>"><input type='text' name='dt_regularizacao' onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="10" readonly="readonly"></td>
  </tr>
  <?php }   
  }else{
  ?>
  <tr>
    <td class="subtitulodireita">Documenta&ccedil;&atilde;o Cliente (contrato, tabela, ..)</td>
    <td colspan="2" bgcolor="<?=$cor?>"><font color="#FFFFFF" style="font-size:14px" face="Arial"><b><?=$des_cpendencia_contratual?></b></font></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Data da Regulariza&ccedil;&atilde;o da Documenta&ccedil;&atilde;o</td>
    <td colspan="2" bgcolor="<?=$cor?>"><font color="#FFFFFF" style="font-size:14px" face="Arial"><b><?=$matriz['dt_regularizacao']?></b></font></td>
  </tr>
  <?php } ?>


  <tr>
    <td class="subtitulodireita">Situa&ccedil;&atilde;o do Contrato</td>
    <td class="formulario" <?php if ($matriz['sitcli'] == 0) {
								echo "bgcolor=\"#33CC66\"";
								} else {
								echo "bgcolor=\"#FF0000\"";} ?> ><font color="#FFFFFF"><?php echo $matriz['descsit']; ?></font></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Situa&ccedil;&atilde;o de Cobran&ccedil;a</td>
    <td class="subtitulopequeno"><b><?php if ($matriz['sit_cobranca'] == 0) echo "ATIVO"; 
									   else echo "NEGATIVADO"; ?></b></td>
  </tr>
  <?php if ($matriz['sitcli'] == 2) {
  $ssql="select date_format(a.data_documento,'%d/%m/%Y') as documento, a.tipo_documento, b.motivo,
		date_format(a.ultima_fatura,'%d/%m/%Y') as ultima from pedidos_cancelamento a
		inner join motivo_cancel b on a.id_mot_cancelamento=b.id
		where codloja='$codloja'";
  $rs = mysql_query($ssql);
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
    <td class="subtitulodireita">CPF / CNPJ do Franqueado</td>
    <td class="subtitulopequeno">
		<?php        
			 $total_doc = strlen($matriz['cpfcnpj']);			
			if($total_doc == "11"){
				echo mascaraCpf($matriz['cpfcnpj']);
				echo "&nbsp;- <b>Fis�ca</b>";
			}
			if($total_doc == "14"){
				echo mascaraCnpj($matriz['cpfcnpj']);
				echo "&nbsp;- <b>Jur�dica</b>";	
			}
		?>
    </td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  
  <tr align="right">
    <td colspan="2">
     <?php if($matriz['pendencia_contratual'] == "1"){ ?>
     	<input name="Alterar" type="submit" value=" Alterar " />
     <?php } ?>
    </td>
    <input type="hidden" name="cnpj" value="<?=$cnpj?>">
    <input type="hidden" name="codloja" value="<?=$codloja?>">
  </form>
  
  <form method="post" action="painel.php?pagina1=clientes/a_incclient.php" /><tr align="right">
    <td colspan="2"><input name="incluir" type="submit" value="                Incluir novo Cliente" /></td>
  </form></tr>
  <form method="post" action="painel.php?pagina1=clientes/a_altcliente1.php&codloja='$codloja'; ?>" /><tr align="right">
    <td colspan="2"><input name="alterar" type="submit" value="Alterar os dados do Cliente" /></td>
  </form></tr>
</table>
  
<?php $res = mysql_close ($con);
} ?>
</body>
