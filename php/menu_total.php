<tr class="menu">
  <td align="center">&nbsp;</td>
  <td>Area Administrativa</td>
</tr>

<?php if ($_SESSION['id'] == '163') { ?>
  <tr>
    <td align="center" class="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=cpd/view/usuarios_cpd.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');"><b> Usúarios CPD</b></a></td>
  </tr>
  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=Franquias/funcionario_listagem.php&lista_ativo=S" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Cadastro Funcionário </a></td>
  </tr>
  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=area_restrita/menu_premiacao.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Premiação Geral</a></td>
  </tr>

  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=Franquias/b_notafiscal_new.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Emissão de NOTA FISCAL - Clientes</a></td>
  </tr>
<?php } ?>
<tr>
  <td align="center"><?php echo $i++; ?></td>
  <td><a href="painel.php?pagina1=Franquias/b_relcli.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Relação completa de clientes </a></td>
</tr>

<tr>
  <td align="center"><?php echo $i++; ?></td>
  <td><a href="painel.php?pagina1=Franquias/b_recomenda.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Indicação de clientes</a></td>
</tr>
<tr>
  <td align="center"><?php echo $i++; ?></td>
  <td><a href="painel.php?pagina1=area_restrita/d_tabela.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Alteração da tabela de preços</a></td>
</tr>
<tr>
  <td align="center"><?php echo $i++; ?></td>
  <td><a href="painel.php?pagina1=area_restrita/d_cadfranqueado.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Cadastro de franqueados </a></td>
</tr>
<tr>
  <td align="center"><?php echo $i++; ?></td>
  <td><a href="painel.php?pagina1=area_restrita/d_relfranqueados.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Relação completa de franqueados </a></td>
</tr>
<tr>
  <td align="center"><?php echo $i++; ?></td>
  <td><a href="painel.php?pagina1=area_restrita/d_rel_pretendentes.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">
      <font color="#FF6600" style="font-weight:bold">Pretendentes a franqueados</font>
    </a></td>
</tr>
<tr>
  <td align="center"><?php echo $i++; ?></td>
  <td><a href="painel.php?pagina1=area_restrita/d_estrelafrq.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Estrelas Semanais</a></td>
</tr>
<tr>
  <td align="center"><?php echo $i++; ?></td>
  <td><a href="painel.php?pagina1=area_restrita/d_excluir.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Excluir clientes do cadastro </a></td>
</tr>
<tr>
  <td align="center"><?php echo $i++; ?></td>
  <td><a href="painel.php?pagina1=area_restrita/d_lancamento_conta_corrente.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Lançamentos em Conta Corrente</a></td>
</tr>

<?php if (($_SESSION["id"] == '163') or ($_SESSION["id"] == '46')) { ?>

  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=area_restrita/d_equipamentos0.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Controle Produtos/Equipamentos</a></td>
  </tr>


  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=area_restrita/d_geraremessa_banco.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Banco Gera Remessa</a></td>
  </tr>

  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=area_restrita/autorizacao_conta.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Autorização de Conta</a></td>
  </tr>

  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=area_restrita/d_processaretorno_banco.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Banco Processa Retorno</a></td>
  </tr>

  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=Franquias/b_extratocontratos_cancelados.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Ranking de Cancelamento por Autônomo</a></td>
  </tr>


  <!--
  <tr>
    <td align="center"><?php //echo $i++; 
                        ?></td>
    <td><a href="painel.php?pagina1=area_restrita/d_baixa_titulos_crediario_recupere.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Baixa T&iacute;tulos [Credi&aacute;rio/Recupere]</a></td>
  </tr>
 -->
  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=area_restrita/voltar_titulo_form.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Voltar Título Excluído</a></td>
  </tr>

<?php } ?>

<tr>
  <td align="center"><?php echo $i++; ?></td>
  <td><a href="painel.php?pagina1=area_restrita/d_libera_web_control.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Liberação Web-Control</a></td>
</tr>

<?php
if (($_SESSION["id"] == '163') or ($_SESSION["id"] == '46')) {
?>
  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=clientes/a_baixa_titulo_cred_rec_bol.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Baixa de Títulos - Cred/Rec/Bol</a>
    </td>
  </tr>
  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=area_restrita/d_envio_email_franqueados.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Envio Email Franqueados</a></td>
  </tr>

<?php } ?>