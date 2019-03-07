<?php
require "connect/sessao.php";
require "connect/sessao_r.php";

if ( $_REQUEST['rel_franquia'] == '' )
$id_franquia = $_SESSION['id'];
else
$id_franquia = $_REQUEST['rel_franquia'];

if ( $id_franquia == 163 || $id_franquia == 4 || $id_franquia == 247) $id_franquia = 1;
?>

<script type="text/javascript" src="../../../inform/js/prototype.js"></script>

<script language="javascript">

    function pesquisa_dados() {
        d = document.form2;
        d.action = 'painel.php?pagina1=clientes/a_lista_cliente_segmento2.php';
        d.submit();
    }
    window.onload = function(){
        document.form2.segmento.focus(); 
    }
</script>
<br>

<form name="form2" method="post" action="#" >
    <table border="0" align="center" width="700">
        <tr>
            <td colspan="3" class="titulo">Lista por Segmentos</td>
        </tr>
        <tr>
            <td class="subtitulodireita">SEGMENTO: </td>
            <td colspan="2" class="subtitulopequeno">
                <input type="text" name="segmento" id="segmento">
            </td>
        </tr>

        <tr>
            <td width="200" class="subtitulodireita">CIDADE: </td>
            <td colspan="2" class="subtitulopequeno">
                <input type="text" name="cidade" id="segmento" value="">
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <input name="pesquisar" type="button" value=" Pesquisar " onClick="pesquisa_dados();" />
                <input type='button' value='  Voltar  ' style='cursor:pointer' onClick="document.location = 'painel.php?pagina1=clientes/a_controle_visitas0.php'"/>
            </td>
        </tr>
    </table>
</form>