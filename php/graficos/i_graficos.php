<?php
require "connect/sessao.php";
?>
<style type="text/css">
       form {margin: 30px 50px 0;}
       form input, select {
       font-family: Arial;
       font-size: 8pt;
       }
</style>

<script language="javascript">
    function ir() {
        form = document.form1;
        form.action = 'painel.php?pagina1=graficos/i_graficos.php';
        form.submit();
    }

    function mostrar(id) {
        if (document.getElementById(id).style.display == 'none') {
            document.getElementById(id).style.display = '';
        }
    }

    function ocultar(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>
<?php
if ($_REQUEST['franqueado']) {
    $id_franquia = $_REQUEST['franqueado'];
}
?>
<body onLoad="ocultar('aguarde')">
    <form name="form1" method="post" action="#">

        <table width=90% border="0" align="center">
            <tr class="titulo">
                <td align="center">GR&Aacute;FICOS DE DESEMPENHO</td>
            </tr>
            <tr>
                <td align="center" class="titulo">
                    <?php if (($tipo == "a") || ($tipo == "c") || ($tipo == "d")) { ?>
                        <select name="franqueado" onChange="ir()">
                            <?php if ($tipo == "a") { ?>

                                <?php if ($_REQUEST['franqueado'] == 9999999) { ?>
                                    <option value="9999999" selected="selected">Todas as Franquias</option>
                                <?php } else { ?>
                                    <option value="9999999">Todas as Franquias</option>
                                <?php } ?>

                            <?php
                            }
                            $sql = "SELECT id, fantasia FROM franquia 
					WHERE classificacao <> 'J' AND sitfrq=0 ORDER BY id";
                            $resposta = mysql_query($sql, $con);
                            while ($array = mysql_fetch_array($resposta)) {
                                ?>	

                                <?php if ($_REQUEST['franqueado'] == $array["id"]) { ?>
                                    <option value="<?= $array["id"] ?>" selected="selected"><?= $array["fantasia"] ?></option>
                                <?php } else { ?>
                                    <option value="<?= $array["id"] ?>"><?= $array["fantasia"] ?></option>
                                <?php } ?>

                        <?php } ?>
                        </select>
                        <?php
                    } else {
                        echo $array["fantasia"];
                        ?>
                        <input name="franqueado" type="hidden" id="franqueado" value="<?= $array["id"] ?>"/>
                        <?php
                    }
                    ?></td>
            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_01.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Pesquisas de Cr&eacute;dito</a>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_02.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Localiza Max</a>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_13.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Localiza Max Novos Clientes - MALA DIRETA</a>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_03.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico Parcelamento de D&eacute;bitos</a>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_04.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Boletos Emitidos</a>";
                    ?>
                </td>

            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_23.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico Carn&ecirc; Pr&oacute;prio</a>";
                    ?>
                </td>

            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_05.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Recomende o Cliente</a>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_06.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Negativa&ccedil;&atilde;o de Devedores</a>";
                    ?>
                </td>
            </tr>
            <!--
            <tr>
                <td class="campoesquerda">
                    <?php
                    // $url = "/franquias/php/graficos/App/Grafico_Franquia_07.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    // echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Web Control Empresas</a>";
                    ?>
                </td>
            </tr>
            -->
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_14.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Empresas Afiliadas</a>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_15.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Consumidores Cadastrados</a>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_16.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Produtos/Servi&ccedil;os Cadastrados</a>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_17.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Vendas Realizadas</a>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_18.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Notas Fiscais ( NF-e )</a>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_19.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Notas Fiscais ( NFC-e )</a>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_20.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Notas Fiscais ( NFS-e )</a>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_21.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Equipamentos Vendidos</a>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_22.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de M&eacute;dia de Faturas</a>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_08.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Encaminhamento p/ Protesto</a>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_09.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Ve&iacute;culo Total</a>";
                    ?>
                </td>
            </tr>

            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_10.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico Documentos Diversos Impressos</a>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_11.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico de Virtual Flex</a>";
                    ?>
                </td>
            </tr>  
            <tr>
                <td class="campoesquerda">
                    <?php
                    $url = "graficos/App/Grafico_Franquia_25.php?franqueado=$id_franquia&id_franquia_session={$_SESSION['id']}";
                    echo "<a href=\"$url\" class=\"bodyText\" onfocus=\"mostrar('aguarde');return true;\">Gr&aacute;fico WhatsApp Marketing</a>";
                    ?>
                </td>
            </tr> 
            <tr>
                <td colspan="2" class="titulo">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " /></td>
            </tr>
        </table>
    </form>
</body>


<p>&nbsp;</p>
<table width="500px" height="50px" id="aguarde" style="display:none;" border="0" cellpadding="0" cellspacing="1" bgcolor="#FF6A6A" align="center">	
    <tr>
        <td width="10%" align="center"><img src="https://www.webcontrolempresas.com.br/franquias/img/ajax-loader.gif" height="50px"><td align="center"><font style="font-size:18px">Clique apenas uma vez e aguarde o gr&aacute;fico ser carregado.</font></td>
    </tr>		
</table>