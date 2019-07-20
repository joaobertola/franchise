<?php

    if($_SESSION['id'] == '1204') { ?>
        <tr>
            <td align="center"><?php echo $i++; ?></td>
            <td><a href="painel.php?pagina1=Franquias/b_notafiscal_new.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Emissão de NOTA FISCAL - Clientes</a></td>
        </tr>
    <?php
    }
    if($_SESSION['id'] == '59') { ?>
        <tr>
            <td align="center"><?php echo $i++; ?></td>
            <td><a href="painel.php?pagina1=area_restrita/d_rel_pretendentes.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');"><font color="#FF6600" style="font-weight:bold">Pretendentes a franqueados</font></a></td>
        </tr>
    <?php
    }
    /*
    if($_SESSION['id'] == '4') { ?>
        <tr>
            <td align="center"><?php echo $i++; ?></td>
            <td><a href="painel.php?pagina1=clientes/a_isentajuros.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');"><font color="#FF6600" style="font-weight:bold">Isenta juros Faturas</font></a></td>
        </tr>
     <?php } 
   */
    if($_SESSION['id'] == '163') { ?>
        <tr>
            <td align="center"><?php echo $i++; ?></td>
            <td><a href="painel.php?pagina1=Franquias/b_extrato_venda.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Extrato de Contratos Venda Completa</a></td>
        </tr>
     <?php }

     if($_SESSION['id'] == '163') { ?>
        <tr>
            <td align="center"><?php echo $i++; ?></td>
            <td ><a href="painel.php?pagina1=area_restrita/a_relatorio_whatsApp.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');"><font color="#00CC00" style="font-weight:bold">Extrato Whatsapp</font></a></td>
        </tr>
     <?php }

    if ($tipo != 'c') {
    ?>
    <tr>
        <td align="center"><?php echo $i++; ?></td>
        <td><a href="painel.php?pagina1=Franquias/b_extratocontratos.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Extrato de contratos </a></td>
    </tr>


    <?php } ?>
    <tr>
        <td align="center"><?php echo $i++; ?></td>
        <td><a href="painel.php?pagina1=Franquias/b_tabelapreco.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Tabelas de preços de clientes </a></td>
    </tr>
    <tr>
        <td align="center"><?php echo $i++; ?></td>
        <td><a href="painel.php?pagina1=Franquias/b_relcli.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Relação de clientes por cidades</a></td>
    </tr>
    <tr>
        <td align="center"><?php echo $i++; ?></td>
        <td><a href="painel.php?pagina1=Franquias/b_tabelacusto.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Preços de custo Fornecedor</a></td>
    </tr>
    <tr>
        <td align="center"><?php echo $i++; ?></td>
        <td><a href="painel.php?pagina1=Franquias/b_baixafatura.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Relatórios de Faturas p/ Cobrança</a></td>
    </tr>
    <tr>
        <td align="center"><?php echo $i++; ?></td>
        <td><a href="painel.php?pagina1=clientes/a_negativado.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');"><font color="#FF0000" style="font-weight:bold">Excluir PODRE da Lista de Cobrança</font></a></td>
    </tr>
    <tr>
        <td align="center"><?php echo $i++; ?></td>
        <td><a href="painel.php?pagina1=Franquias/b_ctacte.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');"><font color="#0033FF"><b>Prestação Contas Franquia X Matriz</b></font></a></td>
    </tr>
    <tr>
        <td align="center"><?php echo $i++; ?></td>
        <td><a href="painel.php?pagina1=area_restrita/d_fechamento.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Relatório de Faturamento</a></td>
    </tr>
    <tr>
        <td align="center"><?php echo $i++; ?></td>
        <td><a href="painel.php?pagina1=Franquias/b_emailcli.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">E-mails dos clientes </a></td>
    </tr>
    <tr>
        <td align="center"><?php echo $i++; ?></td>
        <td><a href="painel.php?pagina1=Franquias/b_altfranqueado.php&id=<?php echo $id_franquia; ?>">Alterar Dados Cadastrais Franquia</a></td>
    </tr>
    <tr>
        <td align="center"><?php echo $i++; ?></td>
        <td><a href="painel.php?pagina1=Franquias/b_altsenha.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Alterar Senha Página de Franquias</a></td>
    </tr>
    <tr>
        <td align="center"><?php echo $i++; ?></td>
        <td><a href="painel.php?pagina1=clientes/a_altcliente_dados_bancarios.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Alteração de Conta Crediário / Recupere</a></td>
    </tr>
    <?php if ( $tipo == 'a' ){ ?>
    <tr>
        <td align="center"><?php echo $i++; ?></td>
        <td><a href="painel.php?pagina1=Franquias/web_control_busca_sugestao.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Sugestões WEB-CONTROL</a></td>
    </tr>
   <?php } ?>