<script language="JavaScript">
    //ocultar link
    window.status = "Web Control Empresas";

    function mmLoadMenus() {
        if (window.mm_menu_0703115929_0) return;
        window.mm_menu_0703115929_0 = new Menu("root", 187, 16, "Geneva, Arial, Helvetica, sans-serif", 10, "#000000", "#FFFFFF", "#CCCCCC", "#FF6633", "left", "middle", 3, 0, 300, -5, 7, true, false, true, 0, true, true);
        mm_menu_0703115929_0.addMenuItem("Departamento&nbsp;Administrativo", "location='painel.php?pagina1=clientes/a_download_administrativo.php'");
        mm_menu_0703115929_0.addMenuItem("Departamento&nbsp;Comercial", "location='painel.php?pagina1=clientes/a_download_comercial.php'");
        mm_menu_0703115929_0.hideOnMouseOut = true;
        mm_menu_0703115929_0.bgColor = '#555555';
        mm_menu_0703115929_0.menuBorder = 1;
        mm_menu_0703115929_0.menuLiteBgColor = '#FFFFFF';
        mm_menu_0703115929_0.menuBorderBgColor = '#777777';

        mm_menu_0703115929_0.writeMenus();
    }
</script>
<script language="JavaScript1.2">
    mmLoadMenus();
</script>
<?php

$i = 1;
?>
<table width="230" cellspacing="1" cellpadding="0" class="bodyText">
    <?php

    if ($id_franquia == 2)
        include("menu_teixeira.php");

    else {

        if ($tipo == "d") {
            include("menu_operacional.php");
        }
        if ($tipo != "d" and $classificacao != "J" and $classificacao != "X") {
            include("menu_franquia.php");
        }
        if ($classificacao == "J")  include("menu_franqueado_jr.php");
        if ($classificacao == "X")  include("menu_franqueado_micro.php");

        if ($id_franquia != '01' and $classificacao != "J" and $classificacao != "X")
            if ($id_franquia != 1205) include("menu_ranking.php");


        if ($classificacao != "X" and $classificacao != "J" and $id_franquia != 1205 and $id_franquia != 247) {
    ?>
            <tr class="menu">
                <td align="center">&nbsp;</td>
                <td><a href="<?php $_SERVER["PHP_SELF"]; ?>?pagina1=area_restrita/d_login_restrito.php">Área Restrita</a></td>
            </tr>
        <?php
        }

        //require "connect/sessao_r.php";

        @$nome2 = $_SESSION['ss_restrito'];

        if (isset($nome2) || ($tipo == "a")) {
            include("menu_admin.php");
        }

        if ($tipo == "a") include("menu_total.php");

        if ($id_franquia == '247') { ?>
            <tr>
                <td align="center"><?php echo $i++; ?></td>
                <td><a href="painel.php?pagina1=area_restrita/d_pontos_ranking.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">
                        <font color="blue"><b>Relatório Pontuação</b></font>
                    </a></td>
            </tr>
            <tr>
                <td align="center"><?= $i++ ?></td>
                <td><a href="painel.php?pagina1=clientes/a_altsenha.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Alteração de Senha</a></td>
            </tr>
            <tr>
                <td align="center"><?php echo $i++; ?></td>
                <td><a href="painel.php?pagina1=Franquias/b_liberaconsulta.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Liberação do limite de consultas</a></td>
            </tr>
            <?php
            if ($id_franquia != '1205') {
            ?>
                <tr>
                    <td align="center"><?php echo $i++; ?></td>
                    <td><a href="painel.php?pagina1=area_restrita/virtual_flex_busca_excluir_dominio.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Excluir Informações Virtual - Flex</a></td>
                </tr>
            <?php
            }
        }
        if ($id_franquia == '1205') { ?>
            <tr>
                <td align="center"><?php echo $i++; ?></td>
                <td><a href="painel.php?pagina1=area_restrita/d_copia_documentos.php" onMouseOver="return showStatus('Menu Franquias');" onMouseOut="return showStatus('');">Cópia de Documentos</a></td>
            </tr>
        <?php }
        if (($id_franquia == '163') or ($id_franquia == '1204') or ($id_franquia == '46')) {
        ?>
            <tr>
                <td align="center"><?php echo $i++; ?></td>
                <td><a href="painel.php?pagina1=clientes/a_isentajuros.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Isenta juros Faturas</a></td>
            </tr>
        <?php
        }
        if ($id_franquia == '163') {
        ?>
            <tr>
                <td align="center"><?php echo $i++; ?></td>
                <td><a href="painel.php?pagina1=area_restrita/d_cliente_atraso_antecipacao.php" onmouseout="return showStatus('');">Antecipação</a></td>
            </tr>
            <tr>
                <td align="center"><?php echo $i++; ?></td>
                <td><a href="painel.php?pagina1=area_restrita/d_rel_boleto_pago.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Titulos Pagos Nexxera</a></td>
            </tr>
        <?php
        }
        if ($id_franquia == '1204') { ?>
            <tr>
                <td align="center"><?php echo $i++; ?></td>
                <td><a href="painel.php?pagina1=Franquias/b_relcli.php" onmouseover="return showStatus('Menu Franquias');" onmouseout="return showStatus('');">Relação de clientes por cidades</a></td>
            </tr>
    <?php }
    }
    ?>
    <tr class="menu">
        <td align="center">&nbsp;</td>
        <td><a href="connect/destroy.php">SAIR</a></td>
    </tr>
</table>