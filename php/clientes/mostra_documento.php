<script type="text/javascript" src="..../js/jquery-3.1.1.js"></script>
<script language="JavaScript" src="../../js/jquery.meio.mask.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery.maskedinput-1.1.1.js"></script>
<script type="text/javascript" src="../js/jquery.meio.mask.js"></script>

<script language="javascript">

    (function ($) {
        $(function () {
            $('input:text').setMask();
        }
        );
    })(jQuery);

    jQuery(function ($) {
        $("#data_consultoria").mask("99/99/9999");
    });

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

    function retorna() {
        frm = document.form;
        frm.action = 'scanner_carrega_foto.php';
        frm.submit();
    }

    function grava_registro() {
        
        //frm = document.form;
        //frm.action = 'mostra_documento_grava.php';
        //frm.submit();
        
        var id = $('#id').val();
        var consultora = $('#consultora').val();
        var cobrar_consultoria = $("input[name='cobrar_consultoria']:checked").val();
        
        var data_consultoria = $('#data_consultoria').val();
        var realizada = $("input[name='realizada']:checked").val();
        
        $.ajax({
            url: "mostra_documento_grava.php", //teste somente para retornar success
            data: {
                id: id,
                consultora: consultora,
                cobrar_consultoria: cobrar_consultoria,
                data_consultoria: data_consultoria,
                realizada: realizada
            },
            type: "POST",
            async: false,
            success:
                    function (dataResult) {

                        alert( dataResult );
                        
                    }
        });
    }

</script>

<?php
include("../connect/sessao.php");
include("../connect/conexao_conecta.php");

$id = $_REQUEST['id'];

if ($_SESSION['id'] <> 163)
    $mostra = 'disabled';

$sql = "SELECT valor FROM cs2.valcons WHERE codcons = 'CTREI'";
$qry = mysql_query($sql, $con) or die("Erro SQL:  $sql");
$taxa_consultoria = mysql_result($qry,0,'valor');

$sql = "
SELECT 
id, caminho_imagem, imagem, date_format(data_consultoria,'%d/%m/%Y') as data_consultoria, 
consultora, consultoria_realizada, cobrar_consultoria,
CASE tp_imagem
        WHEN 'C1' THEN 'CONSULTORIA E TREINAMENTO - 1'
        WHEN 'C2' THEN 'CONSULTORIA E TREINAMENTO - 2'
        WHEN 'C3' THEN 'CONSULTORIA E TREINAMENTO - 3'
        WHEN 'C4' THEN 'CONSULTORIA E TREINAMENTO - 4'
END AS tp_imagem 
FROM
       base_inform.cadastro_imagem WHERE id = $id";
$qry = mysql_query($sql, $con) or die("Erro SQL:  $sql");
?>

<form name="form" method="post" action="#">
    <table border="1" width="90%" align="center" cellpadding="0" cellspacing="1" style="border: 2px solid #3396ce; background-color:#FFF" bordercolor="#3396ce">
        <tr>
            <td colspan="2" bgcolor="#3396ce" align="center"><b><font color="#FFFFFF">
                    Web Control - Visualiza&ccedil;&atilde;o de Consultoria e Treinamento</font></b>
            </td>
        </tr>
        <input type="hidden" id="id" name="id" value="<?= $id ?>">
        <?php
        $i = 0;
        while ($rs = mysql_fetch_array($qry)) {
        ?>
            <tr>
                <td colspan="2" align="center" width="50%">
                    <b><?= $rs['tp_imagem'] ?></b>
                    <br>
                    <?php if ($rs['caminho_imagem'] == '') { ?>
                        <img src='../franquias/php/area_restrita/d_copia_documentos_ver.php?id=<?= $rs['id'] ?>' width="100%" height="100%">
                    <?php } else { 
                        
                        if ($tipo_arq == '.JPG') {
                            echo "<img src='http://contrato.webcontrolempresas.com.br/inform/{$rs['caminho_imagem']}' width='400' height='400'>";
                        }else{

                            $xarq = $rs['caminho_imagem'];
                            $tipo_arq = strtoupper(substr($xarq, strpos($xarq, '.'), 4));
                            $arquivo = 'http://contrato.webcontrolempresas.com.br/inform/' . $xarq;
                            $xarq = str_replace('contrato/','',$xarq);
                            shell_exec("/usr/bin/wget \"$arquivo\" -O \"/var/www/html/franquias/php/arq_tmp/$xarq\"");
                            $arquivo = 'https://www.webcontrolempresas.com.br/franquias/php/arq_tmp/'.$xarq;
                            echo '<iframe width="400px" height="400px" src="' . $arquivo . '"></iframe>';
                        }
                    } ?>
                </td>
            </tr>
            <tr>
                <td>Data da Consultoria</td>
                <td> <input type="text" name="data_consultoria" id="data_consultoria" value="<?= $rs['data_consultoria'] ?>"
                        onKeyPress="return MM_formtCep(event, this, '##/##/####')" onFocus="this.className = 'boxover'" onBlur="this.className = 'boxnormal'" <?= $mostra ?> ></td>
            </tr>
            <tr>
                <td>Consultoria Realizada</td>
                <td>
                    <?php
                    if ( $rs['consultoria_realizada'] == '1' ){
                        $checked_sim = 'checked="checked"';
                        $checked_nao = '';
                    }else{
                        $checked_nao = 'checked="checked"';
                        $checked_sim = '';
                    }
                    ?>
                    <input type="radio" name="realizada" id="realizada" value='1' <?php echo $checked_sim; ?> >Sim
                    <input type="radio" name="realizada" id="realizada" value='0' <?php echo $checked_nao; ?> >N&atilde;o
                </td>
            </tr>
            
            <tr>
                <td>Cobrar Consultoria Externa (R$ <?php echo number_format($taxa_consultoria, 2, ',', '.') ?>)</td>
                <td>
                    <?php
                    if ( $rs['cobrar_consultoria'] == '1' ){
                        $checked_sim = 'checked="checked"';
                        $checked_nao = '';
                    }else{
                        $checked_nao = 'checked="checked"';
                        $checked_sim = '';
                    }
                    ?>
                    <input type="radio" name="cobrar_consultoria" id="cobrar_consultoria" value='1' <?php echo $checked_sim; ?> >Sim
                    <input type="radio" name="cobrar_consultoria" id="cobrar_consultoria" value='0' <?php echo $checked_nao; ?> >N&atilde;o
                </td>
            </tr>
            
            <tr>
                <td >Consultora</td>
                <td>
                    <select id="consultora" name="consultora" <?= $mostra ?> >
                        <?php
                        $consultora = $rs['consultora'];
                        $sql_funcionario = "SELECT substring_index(nome, ' ', 1) as nome_funcionario, id FROM cs2.funcionario
                                            WHERE id_funcao = 19 AND ativo = 'S'";
                        $qry_funcionario = mysql_query($sql_funcionario, $con) or die("Erro SQL:  $sql_funcionario");
                        echo "<option value='' selected>Selecione</option>";
                        while ($rs2 = mysql_fetch_array($qry_funcionario)) {
                            $nome_funcionario = $rs2['nome_funcionario'];
                            $id_funcionario = $rs2['id'];
                            if (empty($rs['consultora'])) {
                                echo "<option value='$id_funcionario'>$nome_funcionario</option>";
                            } else {
                                if ($consultora == $nome_funcionario)
                                    $select = "selected";
                                else
                                    $select = '';
                                echo "<option value='$id_funcionario' $select >$nome_funcionario</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="button" name="Gravar" value="Gravar" onClick="grava_registro()" <?= $mostra ?> ></td>
            </tr>
        <?php }
        ?>
    </table>

</form>