<?php
require "connect/sessao.php";
require "connect/funcoes.php";


$codloja = $_REQUEST['codloja'];

$comando = "SELECT 
			a.atendente_resp, a.renegociacao_tabela,  a.codloja, a.razaosoc, a.insc, a.nomefantasia, 
			a.uf, a.cidade, a.bairro, a.end, a.cep, a.fone,
			a.fax, a.email, a.sitcli, a.tx_mens, b.fantasia, date_format(a.dt_cad, '%d/%m/%Y') as data, 
			d.descsit, a.obs, a.mensalidade_solucoes,
			a.ramo_atividade, a.celular, a.fone_res, a.socio1, a.socio2, a.cpfsocio1, a.cpfsocio2,
			a.emissao_financeiro, a.vendedor, c.descricao, e.nbanco, a.agencia_cliente, a.conta_cliente,
			a.cpfcnpj_doc, a.nome_doc, f.nome_concorrente, a.tpconta,
			a.inscricao_estadual, a.cnae_fiscal, a.inscricao_municipal, a.vr_max_limite_crediario,
			a.inscricao_estadual_tributario, a.numero, a.complemento, a.emitir_nfs,
			a.contador_nome, a.contador_telefone, a.contador_celular, a.contador_email1, a.contador_email2,
                        g.nome as nome_consultor, h.nome as nome_agendador
		 FROM cs2.cadastro a
			inner join cs2.franquia b on a.id_franquia=b.id
			inner join cs2.classif_cadastro c on a.classe=c.id
			inner join cs2.situacao d on a.sitcli=d.codsit
			left outer join consulta.banco e on a.banco_cliente=e.banco
			left outer join cs2.concorrentes f on a.origem=f.id
                        left outer join cs2.consultores_assistente g on g.id = a.id_consultor
                        left outer join cs2.consultores_assistente h on h.id = a.id_agendador
			where codloja='$codloja'";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res);

$sql = "select mid(logon,1,5), mid(logon,7,10), sitlog from logon where codloja='$codloja' limit 1";
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

///////////////////////////////////////////////////////////////	
$_comando = "SELECT 
			a.renegociacao_tabela, a.codloja, a.razaosoc, a.insc, a.nomefantasia, a.uf, a.cidade, a.bairro, 
			a.end, a.cep, a.fone, a.fax, a.email, a.tx_mens, a.id_franquia, 
			date_format(a.dt_cad, '%d/%m/%Y') as data, a.sitcli, d.descsit, a.ramo_atividade, a.obs,
			a.celular, a.fone_res, a.socio1, a.socio2, a.cpfsocio1, a.cpfsocio2, a.emissao_financeiro, a.vendedor,
			mid(b.logon,1,5) as logon, mid(b.logon,7,10) as senha, a.classe, a.banco_cliente, a.agencia_cliente,
			a.conta_cliente, a.cpfcnpj_doc, a.nome_doc, a.tpconta, 
			a.inscricao_estadual, a.cnae_fiscal, a.inscricao_municipal, 
			a.inscricao_estadual_tributario, a.numero, a.complemento, a.vr_max_limite_crediario
			FROM cs2.cadastro a
			inner join cs2.logon b on a.codloja=b.codloja
			inner join cs2.situacao d on a.sitcli=d.codsit
			where a.codloja='$codloja' limit 1";
$_res = mysql_query ($_comando, $con);
$franqueado = mysql_result($_res,0,'id_franquia');	

//seleciona a franquia junior
$sql_jr = "SELECT id_franquia_jr FROM cs2.cadastro WHERE codloja = '$codloja'";
$rs_jr = mysql_query ($sql_jr, $con);
$id_franquia_jr = mysql_result($rs_jr,0,'id_franquia_jr');	

if($id_franquia_jr > 0){
	$sql_jr = "SELECT id, id_franquia_master, razaosoc FROM cs2.franquia WHERE id = '$id_franquia_jr'";	
	$rs_jr = mysql_query ($sql_jr, $con);
	$id_franquia_junior = mysql_result($rs_jr,0,'id');	
	$franquia_junior    = mysql_result($rs_jr,0,'razaosoc');	
	$junior = "$id_franquia_junior - $franquia_junior";
}

///////////////////////////////////////////////////////////////		


//tratamento para agencia e conta corrente
$agencia_cliente = $matriz['agencia_cliente'];
$agencia_cliente = strtoupper($agencia_cliente);
	
if (strlen($agencia_cliente) > 4) {
	$agencia_cliente = substr($agencia_cliente,0,4).'-'.substr($agencia_cliente,4,1);
} else {
	$agencia_cliente = substr($agencia_cliente,0,4);
}

$conta_cliente = $matriz['conta_cliente'];
$conta_cliente = substr($conta_cliente,0,-1).'-'.substr($conta_cliente,-1,1);

$renegociacao_tabela =  $matriz['renegociacao_tabela'];
$vr_max_limite_crediario =  $matriz['vr_max_limite_crediario'];

$dia = substr($renegociacao_tabela, 8,10);   
$mes = substr($renegociacao_tabela, 5,2);   
$ano = substr($renegociacao_tabela, 0,4);   
$data_view.=$dia;
$data_view.="/";
$data_view.=$mes;
$data_view.="/";
$data_view.=$ano;

if($data_view == '00/00/0000'){
	$data_view = "";
}
?>
<script>
function novoCliente(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=clientes/a_incclient.php';
	frm.submit();
} 
function alterarCliente(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=clientes/a_altcliente1.php&codloja==<?php echo $codloja?>';
	frm.submit();
}
</script>

    <form name="form" action="#" method="post">
        <table border="0" align="center" width="643">
            <tr>
                <td colspan="2" class="titulo" align="center">CLIENTES WEB CONTROL EMPRESAS</td>
            </tr>
            <tr>
                <td class="subtitulodireita">ID</td>
                <td class="subtitulopequeno"><?php echo $matriz['codloja']; ?></td>
            </tr>

            <tr>
                <td class="subtitulodireita">Funcion&aacute;rio  Franquia</td>
                <td class="subtitulopequeno"><?php echo $matriz['atendente_resp']; ?></td>
            </tr>

            <tr>
                <td class="subtitulodireita">C&oacute;digo de Cliente </td>
                <td class="campojustificado"><?php echo $log['mid(logon,1,5)']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Senha</td>
                <td class="subtitulopequeno">
                    <?php echo $log['mid(logon,8,10)']; ?></td>
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
                <td class="subtitulodireita">Inscrição Estadual</td>
                <td class="subtitulopequeno"><?php echo $matriz['inscricao_estadual']?></td>
            </tr>

            <tr>
                <td class="subtitulodireita">CNAE Fiscal</td>
                <td class="subtitulopequeno"><?php echo $matriz['cnae_fiscal']?></td>
            </tr>

            <tr>
                <td class="subtitulodireita">Inscrição Municipal</td>
                <td class="subtitulopequeno"><?php echo $matriz['inscricao_municipal']?></td>
            </tr>

            <tr>
                <td class="subtitulodireita">Inscri&ccedil;&atilde;o Estadual (Subst. Tribut&aacute;ria)</td>
                <td class="subtitulopequeno"><?php echo $matriz['inscricao_estadual_tributario']?></td>
            </tr>

            <tr>
                <td class="subtitulodireita">Endere&ccedil;o</td>
                <td class="subtitulopequeno"><?php echo $matriz['end']; ?></td>
            </tr>

            <tr>
                <td class="subtitulodireita">Número</td>
                <td class="subtitulopequeno"><?php echo $matriz['numero']?></td>
            </tr>

            <tr>
                <td class="subtitulodireita">Complemento</td>
                <td class="subtitulopequeno"><?php echo $matriz['complemento']?></td>
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
                <td class="subtitulopequeno"><?php echo mascara_celular_wc($matriz['fone']); ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Fax</td>
                <td class="subtitulopequeno"><?php echo mascara_celular_wc($matriz['fax']); ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Celular</td>
                <td class="subtitulopequeno"><?php echo mascara_celular_wc($matriz['celular']); ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Telefone
                    Residencial</td>
                <td class="subtitulopequeno"><?php echo mascara_celular_wc($matriz['fone_res']); ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">E-mail</td>
                <td class="subtitulopequeno"><?php echo strtolower($matriz['email']); ?></td>
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
                <td class="subtitulodireita">Nome Contador</td>
                <td class="subtitulopequeno"><?php echo $matriz['contador_nome']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Telefone Contador</td>
                <td class="subtitulopequeno"><?php echo mascara_celular($matriz['contador_telefone']); ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Celular Contador</td>
                <td class="subtitulopequeno"><?php echo mascara_celular($matriz['contador_celular']); ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Email 1 Contador</td>
                <td class="subtitulopequeno"><?php echo $matriz['contador_email1']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Email 2 Contador</td>
                <td class="subtitulopequeno"><?php echo $matriz['contador_email2']; ?></td>
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
                                else echo "Conta Corrente"; ?>          </td>
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
                <td class="subtitulodireita">Franqueado J&uacute;nior</td>
                <td class="subtitulopequeno"><?php echo $junior?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Consultor</td>
                <td class="subtitulopequeno"><?php echo $matriz['nome_consultor']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Agendador</td>
                <td class="subtitulopequeno"><?php echo $matriz['nome_agendador']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Origem do Cliente</td>
                <td class="subtitulopequeno"><?php echo $matriz['nome_concorrente']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Data de afilia&ccedil;&atilde;o</td>
                <td class="subtitulopequeno"><?php echo $matriz['data']; ?></td>
            </tr>
            <!--
  <tr>
    <td class="subtitulodireita">Emiss&atilde;o de Nota Fiscal e Fatura</td>
    <td valign="top" class="subtitulopequeno"><table class="campoesquerda" border="0">
      <tr>
        <td><input type="radio" name="fatura" value="1" <?// if ($matriz['emissao_financeiro'] == "1"){ echo "checked"; }?> />
          Emite fatura e relaciona a NF &uacute;nica</td>
      </tr>
      <tr>
        <td><input type="radio" name="fatura" value="2" <? //if ($matriz['emissao_financeiro'] == "2"){ echo "checked"; }?> />
          Emite s&oacute; NF individual</td>
      </tr>
      <tr>
        <td><input type="radio" name="fatura" value="3" <? //if ($matriz['emissao_financeiro'] == "3"){ echo "checked"; }?> />
          Emite fatura e NF individual</td>
      </tr>
    </table></td>
  </tr>
  -->
            <?php if( ($_SESSION['ss_tipo'] == "a") or ($_SESSION["id"] == '1204') ){?>
                <tr>
                    <td class="subtitulodireita">Emiss&atilde;o de Nota Fiscal de Servi&ccedil;o</td>
                    <td colspan="3" valign="top" class="subtitulopequeno">
                        <select name="emitir_nfs" disabled="disabled">
                            <option value="" <?php if ($matriz['emitir_nfs'] == "") { echo "selected"; } ?> >Selecione</option>
                            <option value="S" <?php if ($matriz['emitir_nfs'] == "S") { echo "selected"; } ?> >SIM</option>
                            <option value="N" <?php if ($matriz['emitir_nfs'] == "N") { echo "selected"; } ?> >N&Atilde;O</option>
                        </select>
                    </td>
                </tr>
            <?php } ?>

            <tr>
                <td class="subtitulodireita">Valor M&aacute;ximo de Emissão do Cred/Rec/Bol</td>
                <td class="subtitulopequeno"><?php echo $vr_max_limite_crediario?></td>
            </tr>



            <tr>
                <td class="subtitulodireita">Renegociação de Tabela</td>
                <td class="subtitulopequeno"><?php echo $data_view?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Pacote Pesquisas</td>
                <td valign="top" class="subtitulopequeno">R$&nbsp;<?php echo $matriz['tx_mens']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Licen&ccedil;as - Softwares de Solu&ccedil;&otilde;es</td>
                <td valign="top" class="subtitulopequeno">R$&nbsp;<?php echo $matriz['mensalidade_solucoes']; ?></td>
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
            <tr align="right">
                <td colspan="4">
                    <input name="incluir" type="button" value="                Incluir novo Cliente" onclick="novoCliente()" />
                    &nbsp;&nbsp;&nbsp;
                    <input name="alterar" type="button" value="Alterar os dados do Cliente" onclick="alterarCliente()" />
                </td>
            </tr>
        </table>
    </form>
<?php
$res = mysql_close ($con);
?>