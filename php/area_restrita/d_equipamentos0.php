<?php

require "connect/sessao.php";

?>
<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
<script language="JavaScript" src="../js/jquery.meio.mask.js" type="text/javascript"></script>
<script>

    function chamada_Menu(menu){

        if ( menu == 0 ){
            frm = document.tela0;
            frm.action = 'painel.php?pagina1=area_restrita/d_equipamentos.php';
        }
        else if ( menu == 1 ){
            frm = document.tela0;
            frm.action = 'painel.php?pagina1=area_restrita/d_equipamentos_venda.php';
        }
        else if ( menu == 2 ){
            frm = document.tela0;
            frm.action = 'painel.php?pagina1=area_restrita/d_equipamentos_relatorio.php';
        }
        frm.submit();
    }

</script>
<form method="post" action="#" name='tela0' id='tela0'>
    <table width=70% align="center">
        <tr class="titulo">
            <td colspan="2">Controle de Produtos e Equipamentos</td>
        </tr>

        <tr>	
            <td class="campoesquerda" style="font-size: 12px">
                <br><a href="painel.php?pagina1=area_restrita/d_equipamentos_solicitacao.php">Solicitação de Equipamentos para Franqueadora</a><br><br>
            </td>
            <td class="campoesquerda" style="font-size: 12px; text-align: right !important;">
                <br><a href="painel.php?pagina1=area_restrita/d_equipamentos_solicitacao_relatorio.php">Solicitações Realizadas</a><br><br>
            </td>
        </tr>
        <?php if($id_franquia == '163' || $id_franquia == 4) { ?>

        <tr>
            <td class="campoesquerda" style="font-size: 12px" colspan="2">
                <br><a href="#" onclick="chamada_Menu('0')" ><font color='red'>CONSIGNAÇÃO PARA CONSULTORES E FRANQUIAS</font> (Produtos e Equipamentos)</a><br><br>
            </td>
        </tr>
        <tr>
            <td class="campoesquerda" style="font-size: 12px" colspan="2">
                <br><a href="#" onclick="chamada_Menu('1')" ><font color='green'>VENDA PARA CLIENTES</font> (Produtos e Equipamentos)</a><br><br>
            </td>
        </tr>
        <tr>
            <td class="campoesquerda" style="font-size: 12px" colspan="2">
                <br><a href="#" onclick="chamada_Menu('2')">Relat&oacute;rios de Produtos/Equipamentos</a><br>&nbsp;
            </td>
        </tr>
        <?php } ?>
    </table>
</form>