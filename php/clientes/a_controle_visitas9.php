<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="<?= 'https://' . $_SERVER["SERVER_NAME"] ?>/franquias/css/assets/js/bootstrap.min.js"></script>
<link rel="stylesheet"
      href="<?= 'https://' . $_SERVER["SERVER_NAME"] ?>/franquias/css/assets/css/font-awesome.min.css">
<link rel="stylesheet"
      href="<?= 'https://' . $_SERVER["SERVER_NAME"] ?>/franquias/css/assets/css/bootstrap.min.css">

<?php
/**
 * Created by PhpStorm.
 * User: Arllon Dias
 * Date: 25/10/2016
 * Time: 10:21
 */
require "connect/sessao.php";
require "connect/sessao_r.php";
require "connect/funcoes.php";

function mask($val, $mask) {
    $maskared = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mask) - 1; $i++) {
        if ($mask[$i] == '#') {
            if (isset($val[$k]))
                $maskared .= $val[$k++];
        } else {
            if (isset($mask[$i]))
                $maskared .= $mask[$i];
        }
    }
    return $maskared;
}

$idFranquiaOrig = $id_franquia;

if ($id_franquia == 4 || $id_franquia == 163 || $id_franquia == 247) {
    $id_franquia = 1;
    $idFranquiaOrig = 247;
}

if ($_POST) {

    if ($_POST['id'] OR $_POST['id_campanha']) {

        if ($_POST['excluir'] == 1) {
            $id = $_POST['id'];

            $sqlExcluir = "DELETE FROM apoio.sms_automatico WHERE id = '$id'";
            $sqlExcluir = mysql_query($sqlExcluir, $con);
        } else if ($_POST['ativar'] == 1) {
            $id = $_POST['id'];

            $sqlInativar = "UPDATE apoio.sms_automatico SET ativo = 'A' WHERE id = '$id'";
            $qryInativar = mysql_query($sqlInativar, $con);
        } else if ($_POST['atualiza'] == 1){
            $id = $_POST['id_campanha'];
            $mensagem = $_POST['iptMensagem'];

            $sqlVerifica = "SELECT dias_do_mes FROM apoio.sms_automatico WHERE id = $id"; 

            $qry = mysql_query($sqlVerifica, $con);

            $rs  = mysql_fetch_array($qry);

            $dias = '';
            for ($i = 0; $i < count($_POST['iptDiasSemana']); $i++) {
                if ($i == 0) {
                    $dias = $_POST['iptDiasSemana'][$i];
                } else {
                    $dias .= ',' . $_POST['iptDiasSemana'][$i];
                }
            }

            foreach ($_POST['iptDiasSemana'] as $value) {
                if ($value == $rs['dias_do_mes']) {
                    $sqlUpdate = "UPDATE apoio.sms_automatico SET mensagem = '$mensagem', dias_do_mes = '$value' WHERE id = '$id'";

                    $qryAtualizar = mysql_query($sqlUpdate, $con);
                }else{
                    $sqlInsert = "INSERT INTO apoio.sms_automatico(mensagem, dias_do_mes, ativo) VALUES('$mensagem','$value','A')";

                    $qryInsert = mysql_query($sqlInsert, $con);                    
                }
            }
        } else {
            $id = $_POST['id'];

            $sqlInativar = "UPDATE apoio.sms_automatico SET ativo = 'E' WHERE id = '$id'";
            $qryInativar = mysql_query($sqlInativar, $con);
        }
    } else {
        $dias = '';
        for ($i = 0; $i < count($_POST['iptDiasSemana']); $i++) {
            if ($i == 0) {
                $dias = $_POST['iptDiasSemana'][$i];
            } else {
                $dias .= ',' . $_POST['iptDiasSemana'][$i];
            }
        }

        foreach ($_POST['iptDiasSemana'] as $value) {
            $mensagem = $_POST['iptMensagem'];

            $sqlInsert = "INSERT INTO apoio.sms_automatico(mensagem, dias_do_mes, ativo)
                      VALUES('$mensagem','$value','A')";

            $qryInsert = mysql_query($sqlInsert, $con);
        }
    }
}
?>
<style>
    .cropit-preview {
        background-color: #f8f8f8;
        background-size: cover;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-top: 7px;
        width: 294px;
        height: 212px;
    }

    .cropit-preview-image-container {
        cursor: move;
    }

    .image-size-label {
        margin-top: 10px;
    }

    input, .export {
        display: block;
    }

    button {
        margin-top: 10px;
    }

    .splash .controls-wrapper .slider-wrapper .cropit-image-zoom-input.custom, .demos .demo-wrapper .controls-wrapper .slider-wrapper .cropit-image-zoom-input.custom {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        height: 5px;
        background: #eee;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        outline: none;
    }



</style>
<?php
$sql_sel = "
        SELECT
    id,
    mensagem,
    ativo,
    dias_do_mes
FROM apoio.sms_automatico
ORDER BY dias_do_mes";

$qry = mysql_query($sql_sel, $con);
?>
<a class="btn btn-primary" data-toggle="modal" data-target="#myModal">CADASTRAR CAMPANHA </a>
<a class="btn btn-info" onclick="visualizarLog()" data-toggle="modal" data-target="#Log">VISUALIZAR LOG</a>

<!-- Modal  CADASTRAR CAMPANHA-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="frmModal" name="form1" method="post" action="" enctype="multipart/form-data" id="form1">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">CADASTRAR CAMPANHA</h4>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tr>
                            <th colspan="2" class="text-left">Mensagem <span class="pull-right"
                                                                             id="caracteres">160 Restantes</span>
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2" class=""><textarea name="iptMensagem" id="iptMensagem"
                                                               placeholder="Máximo 160 Caracteres"
                                                               class="form-control"
                                                               rows="6"
                                                               style="margin-bottom: 10px;"
                                                               maxlength="160"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td id="loadCalendar" colspan="2" class="">
                                <?php

                                $m = date('m');
                                $y = date('Y');
                                if (isset($_REQUEST['mes'])) {
                                    $m = $_REQUEST['mes'];
                                }
                                $ds = date('w', strtotime($y . '-' . $m . '-01'));
                                $t = date('t', strtotime($y . '-' . $m . '-01'));

                                function getMes($m)
                                {
                                    if ($m < 10) {
                                        $m = str_replace('0', '', $m);
                                    }
                                    $meses = array(1 => 'Janeiro', 'Fevereiro', 'MarÃ§o', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
                                    return strtoupper($meses[$m]);
                                }

                                echo '<table class="table table-bordered">
                                            <thead>
                                                <tr><th class="text-left"><a style="color:#000;font-size:12px;"></a></th><th colspan="4" class="text-center">' . getMes($m) . '</th><th class="text-left"></th></tr>
                                              </thead>
                                        <tr>
                                    ';

                                $ref = 0;
                                for ($i = 1; $i <= 31; $i++) {
                                    if ($ref == 6) {
                                        echo '</tr><tr>';
                                        $ref = 0;
                                    }
                                    $ref++;
                                    echo '<td class="col-md-1"><input type="checkbox" name="iptDiasSemana[]" id="iptDiasSemana" class="dia-' . $i . '" value="' . $i . '" />' . $i . '</td>';

                                }
                                echo '</tr>
                                                                </table>';
                                ?>                                

                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="id_campanha" name="id_campanha" />
                    <input type="hidden" id="atualiza" name="atualiza" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">FECHAR</button>
                    <button type="submit" id="btnSalvar" name="btnSalvar" class="btn btn-primary">ADICIONAR CAMPANHA
                    <button type="submit" id="btnAtualizar" name="btnAtualizar" class="btn btn-primary" style="display: none;">ATUALIZAR CAMPANHA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal LOG-->
<div class="modal fade" id="Log" tabindex="-1" role="dialog" aria-labelledby="LogLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="form1" method="post" action="" enctype="multipart/form-data" id="form1">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">LOG'S</h4>
                </div>
                <div class="modal-body content-log">
                    <div class="alert alert-waning">CARREGANDO...</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">FECHAR</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal LOG-->
<div class="modal fade" id="Telefones" tabindex="1" role="dialog" aria-labelledby="TelefonesLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"> 
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Telefones</h4>
            </div>
            <div class="modal-body content-telefones">
                <div class="alert alert-waning">CARREGANDO...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">FECHAR</button>
            </div> 
        </div>
    </div>
</div>

<div class="row" style="margin-top: 10px;">
    <div class="col-md-12" style="padding: 20px;">
        <form id="frmExcluir" name="frmExcluir" method="post">
            <input type="hidden" name="id" id="id"/>
            <input type="hidden" name="excluir" id="excluir"/>
            <input type="hidden" name="ativar" id="ativar"/>
            <table class="table table-hover table-borded">
                <thead>
                    <tr>
                        <th>DIA</th>
                        <th>MENSAGEM</th>
                        <th>STATUS</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while (@$rs = mysql_fetch_array($qry)) { ?>
                        <tr style="border: solid 1px;; ">
                            <td style="padding: 5px; text-align: center;" class=""><?php echo $rs['dias_do_mes'] ?></td>
                            <td style="padding: 5px;" class=""><?php echo $rs['mensagem'] ?></td>
                            <td style="padding: 5px; text-align: center;"
                                class=""><?php echo $rs['ativo'] == 'A' ? '<span style="color:green" class="fa fa-check"></span>' : '<span style="color:red" class="fa fa-close"></span>' ?></td>
                            <td style="padding: 5px; text-align: center;" class="">
                                <?php echo $rs['ativo'] == 'A' ? "<a style='cursor: pointer' class='btnEditar' onclick='editarCampanha(" . $rs['id'] . ")'>Editar</a> | <a style='cursor: pointer' class='btnRemover' onclick='inativarCampanha(" . $rs['id'] . ")'>Inativar</a>" :  " <a style='cursor: pointer' class='btnEditar' onclick='editarCampanha(" . $rs['id'] . ")'>Editar</a> | <a style='cursor: pointer' class='btnRemover' onclick='excluirCampanha(" . $rs['id'] . ")'>Excluir</a> | <a style='cursor: pointer' class='btnRemover' onclick='ativarCampanha(" . $rs['id'] . ")'>Ativar</a>" ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </form>
    </div>

</div>

<script>

    function visualizarLog() {
        $.get('clientes/a_controle_visitas9_js.php?a=1', function (retorno) {
            $('.content-log').html(retorno);
        });
    }

    function visualizarTelefones(id) {
        $('#Telefones').modal('show');
        $.post('clientes/a_controle_visitas9_js.php?a=2', {id: id}, function (retorno) {
            $('.content-telefones').html(retorno);
        });
    }

    function inativarCampanha(id) {

        var form = document.forms.frmExcluir;

        form.id.value = id;

        form.submit();

    }    

    function excluirCampanha(id) {
        var form = document.forms.frmExcluir;
        form.excluir.value = 1;
        form.id.value = id;

        form.submit();
    }

    function ativarCampanha(id) {
        var form = document.forms.frmExcluir;
        form.ativar.value = 1;
        form.id.value = id;

        form.submit();
    } 

    function editarCampanha(id) {

        $("#id_campanha").val(id);
        $("#btnSalvar").css("display", "none");
        $('#btnAtualizar').css("display", "block");

        $.ajax({
            url: "clientes/a_controle_visitas9_js.php",
            method: "POST",
            data: {
                a: "3",
                id: id
            },
            dataType: 'json',
            success: function (res) {
                var input = '.dia-'+res[0].dias_do_mes;

                $('#myModal').modal('show');
                $('#iptMensagem').text(res[0].mensagem);

                $(input).attr('checked', true);

                $('#btnSalvar').text('ATUALIZAR CAMPANHA');                

            }
        });           
    }

    $('.close').click(function () {
        location.reload();
    });

    $('#btnAtualizar').click(function () {
        $("#atualiza").val(1);
        $("#frmModal").submit();

        location.reload();
    });    

    $(document).ready(function () {

        $('.visualizarNumeros').on('click', function () {
            $(this).parent().find('.esconderTelefones').removeClass('hidden');
            $(this).parent().find('.visualizarNumeros').addClass('hidden');

            var id = $(this).parent().parent().data('id');

            $('.tr' + id).removeClass('hidden');

        });

        $('.esconderTelefones').on('click', function () {
            $(this).parent().find('.visualizarNumeros').removeClass('hidden');
            $(this).parent().find('.esconderTelefones').addClass('hidden');

            var id = $(this).parent().parent().data('id');

            $('.tr' + id).addClass('hidden');
        });

        $('#iptMensagem').on('keydown', function () {

            var caracteres = 160 - $(this).val().length;

            $('#caracteres').html(caracteres + ' Caracteres Restantes');

        })

    });

    // loadCalendar();
    // function loadCalendar($mes) {
    //     console.log($mes);
    //     $.post('loadcalendar.php', {mes: $mes}, function (retorno) {
    //         console.log(retorno);
    //         $('#loadCalendar').html(retorno);
    //         console.log(retorno);
    //     });
    // }

</script>
