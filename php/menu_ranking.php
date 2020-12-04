<?php
if ($id_franquia != '1025') {
?>
  <tr class="menu">
    <td align="center">&nbsp;</td>
    <td>Webmail / Documentos</td>
  </tr>
  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=area_restrita/d_email.php&mail=area_restrita/d_lista_email.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Webmail das Franquias</a></td>
  </tr>
  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=area_restrita/d_webmail.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Webmail <font color="#FF6600" style="font-weight:bold">Novo!!!</font></a></td>
  </tr>
  <?php
  if ($tipo != 'b') {
  ?>
    <tr>
      <td align="center"><?php echo $i++; ?></td>
      <td><a href="painel.php?pagina1=Franquias/b_correspondencia.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Correspondência para Franquias</a></td>
    </tr>
<?php
  } //fim tipo <> b
} //fim franquia <> ricardo
?>
<tr>
  <td align="center"><?php echo $i++; ?></td>
  <td><a href="painel.php?pagina1=clientes/a_download.php" name="link1" id="link1" onMouseOver="MM_showMenu(window.mm_menu_0703115929_0,118,2,null,'link1')" onMouseOut="MM_startTimeout();">
      <font color="#0033FF"><b>Documentos diversos</b></font>
    </a></td>
</tr>
<?php

if (($tipo == 'a' or $tipo == 'b') && ($classificacao != 'J')) {
?>
  <tr class="menu">
    <td align="center">&nbsp;</td>
    <td>Franqueado Junior</td>
  </tr>

  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=franqueado_jr/cad_franqueado_jr.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Inclusão de Franqueado Junior</a></td>
  </tr>
  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=franqueado_jr/rel_franqueado_jr_tela.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Relatório de Franqueado Junior</a></td>
  </tr>
  <td align="center"><?php echo $i++; ?></td>
  <td><a href="painel.php?pagina1=franqueado_jr/repasse.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Relatório de Repasse Franqueado Junior</a></td>
  </tr>
<?php
}
?>
<tr class="menu">
  <td align="center">&nbsp;</td>
  <td>Ranking</td>
</tr>

<!-- tr>
    <td align="center">< ? echo $i++; ?></td>
    <td><a href="ranking/5_Encontro_Nacional_de_Franquias.pdf" target="_blank" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');"><font color="#FF0000"><blink><b>Pauta 5� Encontro de Franquias</b></blink></font></a></td>
  </tr -->
<?php if ($_SESSION['id'] == '163' || $_SESSION['id'] == '4') : ?>
  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><b><a href="https://webcontrolempresas.com.br/apiranking/restrito.php?u=master&pwd=<?= ('AccessMaster'); ?>" target="_blank">Ranking Usuários CPD</a></b></td>
  </tr>
<?php endif; ?>

<tr>
  <td align="center"><?php echo $i++; ?></td>
  <td><a href="painel.php?pagina1=ranking/c_ranksemanal.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">
      <font color="#0033FF">
        <blink><b>Ranking Semanal de Vendas</b></blink>
      </font>
    </a></td>
</tr>

<tr>
  <td align="center"><?php echo $i++; ?></td>
  <td><a href="painel.php?pagina1=ranking/c_rankrent.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Ranking Mensal de Vendas</a></td>
</tr>

<tr>
  <td align="center"><?php echo $i++; ?></td>
  <td><a href="painel.php?pagina1=area_restrita/web_control_listagem_franquias.php" onmouseout="return showStatus('');"><b style="color:blue">Ranking Franquias NOVO<b></a></td>
</tr>

<tr>
  <td align="center"><?php echo $i++; ?></td>
  <td><a href="painel.php?pagina1=ranking/ranking_gerente_franquias.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Ranking de Gerente de Franquias</a></td>
</tr>

<?php
if ($tipo != 'd') {
  if ($id_franquia == 163) {
?>
    <tr>
      <td align="center"><?php echo $i++; ?></td>
      <td><a href="painel.php?pagina1=ranking/c_rankfatmedia.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Ranking de fatura média </a></td>
    </tr>
  <?php      } ?>
  <tr>
    <td align="center"><?php echo $i++; ?></td>
    <td><a href="painel.php?pagina1=ranking/c_filiacoes.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Ranking de carteira de clientes </a></td>
  </tr>
<?php }  //fim tipo <> d
if (($tipo <> "b") and (($id_franquia <> "247") and ($id_franquia <> "5") and ($nome_franquia <> "MATRIZ"))) {
  echo "<tr>
    		<td align=\"center\">" . $i++ . "</td>
    		<td><a href=\"painel.php?pagina1=ranking/c_desempenho.php\" onmouseover=\"return showStatus('Menu Franquias');\" onmouseout=\"return showStatus('');\">Ranking de desempenho</a></td>
		 </tr>
		 <tr>
    		<td align=\"center\">" . $i++ . "</td>
    		<td><a href=\"painel.php?pagina1=Franquias/b_relfranqueados.php\" onmouseover=\"return showStatus('Menu Franquias');\" onmouseout=\"return showStatus('');\">Relação de franqueados </a></td>
  		</tr>";
}
?>