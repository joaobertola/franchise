<?php
/**
 * Created by PhpStorm.
 * User: Arllon Dias
 * Date: 05/08/2016
 * Time: 09:14
 */

require "connect/sessao.php";
require "connect/sessao_r.php";

?>

<script type="text/javascript" src="../../../inform/js/prototype.js"></script>
<script language="javascript">
    function MM_formtCep(e,src,mask) {
        if(window.event) { _TXT = e.keyCode; }
        else if(e.which) { _TXT = e.which; }
        if(_TXT > 47 && _TXT < 58) {
            var i = src.value.length; var saida = mask.substring(0,1); var texto = mask.substring(i)
            if (texto.substring(0,1) != saida) { src.value += texto.substring(0,1); }
            return true; } else { if (_TXT != 8) { return false; }
        else { return true; }
        }
    }
</script>

<form name="form1" method="post" action="clientes/a_controle_visitas_imprimir_agendamento.php">
    <table border="0" align="center" width="700">
        <tr>
            <td colspan="2" class="titulo"><br>IMPRIMIR AGENDAMENTO CONSULTOR</td>
        </tr>
        <tr>
            <td width="200" class="subtitulodireita">&nbsp;</td>
            <td class="subtitulopequeno">&nbsp;</td>
        </tr>
        <tr>
            <td class="subtitulodireita">Consultor</td>
            <td class="subtitulopequeno">
                <?php
                if ( $id_franquia == 4 || $id_franquia == 5 || $id_franquia == 163 || $id_franquia == 247 )
                $id_franquia = 1;
                
                $sql_sel = "SELECT * FROM cs2.consultores_assistente WHERE id_franquia = '$id_franquia' 
         AND tipo_cliente = '0' AND situacao IN('0', '1') ORDER BY situacao, nome";
                $qry = mysql_query($sql_sel, $con);
                echo "<select name='iptIdConsultor' id='iptIdConsultor' style='width:65%'>";
                ?>
                <option value="">Selecionar</option>
                <?php
                while($rs = mysql_fetch_array($qry)) {
                    ?>
                    <option value="<?=$rs['id']?>"><?=$rs['nome']?></option>
                <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Data do Agendamento</td>
            <td class="subtitulopequeno"><input name="iptDataAgendamento" type="text" id="iptDataAgendamento" value="<?php echo date('d/m/Y')?>" size="15" maxlength="10"  onFocus="this.className='boxover'" onKeyPress="return MM_formtCep(event,this,'##/##/####');" onBlur="this.className='boxnormal'" /></td>
            <input type="hidden" id="iptMostrarTudo" name="iptMostrarTudo" value="S">
        </tr>
        <tr>
            <td colspan="3" align="center">
                <button type="submit" id="btnImprimir" name="btnImprimir">Imprimir</button>
            </td>
        </tr>
    </table>
</form>
<div style="margin-top: 50px;">
    <form name="form2" method="post" action="clientes/a_controle_visitas_imprimir_agendamento.php">
        <table border="0" align="center" width="700">
            <tr>
                <td colspan="2" class="titulo"><br>IMPRIMIR POR NÚMERO DE AGENDAMENTO</td>
            </tr>
            <tr>
                <td width="200" class="subtitulodireita">&nbsp;</td>
                <td class="subtitulopequeno">&nbsp;</td>
            </tr>
            <tr>
                <td class="subtitulodireita">Nº</td>
                <td class="subtitulopequeno">
                    <input type="text" id="iptNumeroAtendimento" name="iptNumeroAtendimento">
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Imprimir Vias</td>
                <td class="subtitulopequeno">
                    <input type="checkbox" name="iptViaUm" id="iptViaUm" checked>1&ordf; VIA
                    <input type="checkbox" name="iptViaDois" id="iptViaDois" checked>2&ordf; VIA
                    <input type="checkbox" name="iptViaTres" id="iptViaTres" checked>3&ordf; VIA CHECKLIST
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <button type="submit" id="btnImprimir" name="btnImprimir">Imprimir</button>
                </td>
            </tr>
        </table>
    </form>
</div>