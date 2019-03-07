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

    function MM_formtCep(e, src, mask) {
        if (window.event) {
            _TXT = e.keyCode;
        } else if (e.which) {
            _TXT = e.which;
        }
        if (_TXT > 47 && _TXT < 58) {
            var i = src.value.length;
            var saida = mask.substring(0, 1);
            var texto = mask.substring(i)
            if (texto.substring(0, 1) != saida) {
                src.value += texto.substring(0, 1);
            }
            return true;
        } else {
            if (_TXT != 8) {
                return false;
            } else {
                return true;
            }
        }
    }

    function trim(str) {
        return str.replace(/^\s+|\s+$/g, "");
    }

    function pesquisa_dados() {
        d = document.form2;

        if (trim(d.rel_datai.value) == "") {
            alert("Ops.. Desculpe, voce poderia me informar a Data ? Obrigado !");
            d.rel_datai.focus();
            return false;
        }
        if (trim(d.rel_dataf.value) == "") {
            alert("Ops.. Desculpe, voce poderia me informar a Data ? Obrigado !");
            d.rel_dataf.focus();
            return false;
        }
        d.action = 'painel.php?pagina1=clientes/a_controle_visitas_relatorio.php';
        d.submit();
    }

</script>
<br>

<form name="form2" method="post" action="#" >
    <table border="0" align="center" width="700">
        <tr>
            <td colspan="3" class="titulo">Relat&oacute;rios Comerciais</td>
        </tr>
        <tr>
            <td class="subtitulodireita">&nbsp;</td>
            <td colspan="2" class="subtitulopequeno">
                <?php
                if (($tipo == "a") || ($tipo == "c") || ($tipo == "d")) {
                    echo "<select name=\"rel_franquia\" class=\"boxnormal\" onchange=\"this.form.submit();\" >";
                    if ($tipo <> "b")
                        echo "<option value=\"TODAS\" selected>Todas as Franquias</option>";

                    $sql = "SELECT id, fantasia FROM franquia 
                            WHERE sitfrq = 0 AND classificacao <> 'J'
                            ORDER BY id";
                    $resposta = mysql_query($sql, $con);
                    while ($array = mysql_fetch_array($resposta)) {
                        $franquia = $array["id"];
                        $nome_franquia = $franquia . ' - ' . $array["fantasia"];
                        if ($franquia == $id_franquia)
                            $select = 'selected';
                        else
                            $select = '';
                        echo "<option value=\"$franquia\" $select>$nome_franquia</option>\n";
                    }
                    echo "</select>";
                }
                else {
                    echo $nome_franquia;
                    echo "<input name=\"franqueado\" type=\"hidden\" id=\"franqueado\" value= $id_franquia />";
                }
                ?>
            </td>
        </tr>

        <tr>
            <td width="200" class="subtitulodireita">Assistente</td>
            <td colspan="2" class="subtitulopequeno">
                <?php
                $sql_sela = "SELECT * FROM cs2.consultores_assistente
                             WHERE id_franquia = '$id_franquia' AND tipo_cliente = '1' ORDER BY situacao, nome";
                $qrya = mysql_query($sql_sela, $con);
                echo "<select name='rel_assistente' id='rel_assistente' style='width:42%'>";
                ?>
        <option value="">Todos</option>
        <?php
        while ($rs = mysql_fetch_array($qrya)) {
            if ($rs['situacao'] == "0") {
                $sit = "Ativo";
            } elseif ($rs['situacao'] == "1") {
                $sit = "Bloqueado";
            } elseif ($rs['situacao'] == "2") {
                $sit = "Cancelado";
            }
            ?>
            <option value="<?= $rs['id'] ?>"><?= $rs['nome'] ?> - <?= $sit ?></option>                                                                            
        <?php 
        } 
        ?>
        </select>
        </td>
        </tr>

        <tr>
            <td width="200" class="subtitulodireita">Consultor</td>
            <td colspan="2" class="subtitulopequeno">
                <?php
                $sql_selb = "SELECT * FROM cs2.consultores_assistente
                             WHERE id_franquia = '$id_franquia' AND tipo_cliente = '0'  ORDER BY situacao, nome";
                $qryb = mysql_query($sql_selb, $con);
                echo "<select name='rel_consultor' id='rel_consultor' style='width:42%'>";
                ?>
        <option value="">Todos</option>
        <?php
        while ($rs = mysql_fetch_array($qryb)) {
            if ($rs['situacao'] == "0") {
                $sit = "Ativo";
            } elseif ($rs['situacao'] == "1") {
                $sit = "Bloqueado";
            } elseif ($rs['situacao'] == "2") {
                $sit = "Cancelado";
            }
            ?>
            <?php if ($_REQUEST['id_consultor'] == $rs['id']) { ?>
                <option value="<?= $rs['id'] ?>" selected><?= $rs['nome'] ?> - <?= $sit ?></option>
            <?php } else { ?>
                <option value="<?= $rs['id'] ?>"><?= $rs['nome'] ?> - <?= $sit ?></option>
    <?php }
}
?>
        </select>
        </td>
        </tr>

        <tr>
            <td width="200" class="subtitulodireita">
                <select name="tipo_periodo" onchange="rel_datai.focus();">
                    <option value="dtAge">Per&iacute;odo de Agendamento</option>
                    <option value="dtCad" selected="selected" >Per&iacute;odo de Cadastro</option>
                    <option value="visit">Visitas Realizadas (Todos que possuem data da Finaliza&ccedil;&atilde;o)</option>
                </select>
            </td>
            <td colspan="2" class="subtitulopequeno">
                <input name="rel_datai" type="text" id="rel_datai" size="15" maxlength="10" onFocus="this.className = 'boxover'" onKeyPress="return MM_formtCep(event, this, '##/##/####');" onBlur="this.className = 'boxnormal'" />
                &nbsp;&nbsp;&nbsp;&agrave;&nbsp;&nbsp;&nbsp;
                <input name="rel_dataf" type="text" id="rel_dataf" size="15" maxlength="10" onFocus="this.className = 'boxover'" onKeyPress="return MM_formtCep(event, this, '##/##/####');" onBlur="this.className = 'boxnormal'" />
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