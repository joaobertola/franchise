<?php
/**
 * Created by PhpStorm.
 * User: Arllon Dias
 * Date: 05/08/2016
 * Time: 09:14
 */

require "../connect/sessao.php";
require "../connect/conexao_conecta.php";
require "../connect/funcoes.php";

$id_franquia = $_POST['id_franquia'];
if ($id_franquia == 4 || $id_franquia == 163 || $id_franquia == 247) {
    $id_franquia = 1;
}
if ($_POST) {

    $id = (int)$_POST['id'];

    if(isset($_POST['id_consultor']) && !empty($_POST['id_consultor'])){

        $id_consultor = $_POST['id_consultor'];
        $codigo = $_POST['codigo'];

        $sql = "UPDATE cs2.cadastro_equipamento SET id_consultor = '$id_consultor' WHERE id = '$id'";
        $qry_insert = mysql_query( $sql,$con) or die("Falha ao gravar o registro.");

        ?>
        <script>
            window.history.go(-3);
        </script>
        <?php

        $arr = mysql_fetch_array($qry_insert);
    }

}
?>


<script type="text/javascript" src="../../../inform/js/prototype.js"></script>
<script language="javascript">
    function MM_formtCep(e, src, mask) {
        if (window.event) {
            _TXT = e.keyCode;
        }
        else if (e.which) {
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
            }
            else {
                return true;
            }
        }
    }

    function removerDesafio(id) {

        var form = document.forms.frmExcluir;

        form.id.value = id;

        form.submit();

    }

</script>
<form name="form1" method="post" action="" enctype="multipart/form-data">
    <table border="0" align="center" width="700">
        <tr>
            <td colspan="2" class="titulo"><br>ALTERAR VENDEDOR</td>
        </tr>
        <tr>
            <td class="subtitulodireita">Consultor</td>
            <td class="subtitulopequeno">
                <?php

                $sql_teste = "SELECT * FROM cs2.funcionario WHERE id_franqueado = '$id_franquia'
                  AND ativo  = 'S' ORDER nome";
                $qry_insert = mysql_query( $sql_teste, $con) or die("Falha ao gravar o registro.");
                echo "<select name='id_consultor' id='id_consultor' style='width:65%'>";
                ?>
                <option value="">Selecionar</option>
                <?php
                while($arrItemFunc = mysql_fetch_array($qry_insert)) {
                    if ($arrItemFunc['ativo'] == "0") {
                        $sit = "Ativo";
                    } elseif ($rs['ativo'] == "1") {
                        $arrItemFunc = "Bloqueado";
                    } elseif ($rs['ativo'] == "2") {
                        $arrItemFunc = "Cancelado";
                    }
                    ?>
                    <?php if ($_REQUEST['id_consultor'] == $arrItemFunc['id']) { ?>
                        <option value="<?= $arrItemFunc['id'] ?>" selected><?= $arrItemFunc['nome'] ?> - <?= $sit ?></option>
                    <?php } else { ?>
                        <option value="<?= $arrItemFunc['id'] ?>"><?= $arrItemFunc['nome'] ?> - <?= $sit ?></option>
                    <?php } ?>
                <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <input type="hidden" id="id" name="id" value="<?php echo $_POST['id'] ?>"/>
                <input type="hidden" id="codigo" name="codigo" value="<?php echo $_POST['codigo'] ?>"/>
                <button type="submit" id="btnSalvar" name="btnSalvar">Gravar</button>
            </td>
        </tr>
    </table>
</form>