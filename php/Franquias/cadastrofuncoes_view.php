<?php
/**
 * @file cadastrofuncoes_view.php
 * @brief
 * @author ARLLON DIAS
 * @date 26/01/2017
 * @version 1.0
 **/

require("connect/sessao_r.php");

$sqlDepartamentos = "SELECT
                        id,
                        id_franquia,
                        descricao
                    FROM cs2.departamento
                    WHERE ativo = 1;
";
$resDepartamentos = mysql_query($sqlDepartamentos, $con);

$sqlFuncoes = "SELECT
                    f.id,
                    d.descricao AS departamento,
                    f.descricao AS funcao,
                    f.ativo AS ativo,
                    IF(f.ativo = 1, 'Ativo', 'Inativo') AS ativo_label
                FROM cs2.funcao f
                INNER JOIN cs2.departamento d
                ON d.id = f.id_departamento
                WHERE f.ativo = 1;";
$resFuncoes = mysql_query($sqlFuncoes, $con);
?>
<link rel="stylesheet" href="../css/assets/css/bootstrap.min.css">
<!---->
<!-- Optional theme -->
<link rel="stylesheet" href="../css/assets/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="../css/assets/css/sweetalert.css">

<!-- Latest compiled and minified JavaScript -->
<script src="../css/assets/js/bootstrap.min.js"></script>
<script src="../css/assets/js/sweetalert.min.js"></script>
<style>
    .cursorpointer {
        cursor: pointer;
    }
</style>

<div class="col-md-12">
    <div class="row text-center">
        <h4>Cadastro de Funções / Departamento</h4>
    </div>
    <div class="row col-md-12" style="margin-top: 15px;">
        <div class="col-md-6 col-md-offset-3">
            <form class="form-horizontal">
                <div class="form-group">

                    <div class="col-sm-12">
                        <label for="iptDepartamento" class="control-label">Departamento</label>
                        <div class="input-group">
                            <select class="form-control" id="iptDepartamento" name="iptDepartamento">
                                <option value="0">Selecione</option>
                                <?php if ($resDepartamentos) {
                                    while ($arrResult = mysql_fetch_array($resDepartamentos)) { ?>
                                        <option
                                            value="<?php echo $arrResult['id'] ?>"><?php echo $arrResult['descricao'] ?></option>

                                    <?php }
                                } ?>

                            </select>
                            <span class="input-group-addon"><span
                                    class="glyphicon glyphicon-plus-sign btnModalDepartamento"
                                    style="color: green; cursor: pointer"></span></span>

                        </div>
                        <span style="color: darkred" id="spanErroDepartamento"
                              class="hidden">Selecione um departamento!</span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label for="iptFuncao" class="control-label">Função</label>
                        <input type="text" class="form-control" id="iptFuncao" placeholder="Função">
                        <span style="color: darkred" id="spanErrorFuncao" class="hidden">Nome da Função, deve conter no mínimo 3 caracteres!</span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" class="btn btn-success" id="btnSalvarFuncao">Salvar Função</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="row" style="margin-top: 50px;">
            <div class="col-md-8 col-md-offset-2">
                <table class="table table-responsive table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Departamento</th>
                        <th>Função</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody id="tbodyFuncoes">
                    <?php if ($resFuncoes) {
                        while ($arrResultFuncoes = mysql_fetch_array($resFuncoes)) { ?>
                            <tr data-id="<?php echo $arrResultFuncoes['id'] ?>">
                                <td><?php echo $arrResultFuncoes['departamento'] ?></td>
                                <td><?php echo $arrResultFuncoes['funcao'] ?></td>
                                <td class="text-center"><?php echo $arrResultFuncoes['ativo'] == 1 ? '<span class="glyphicon glyphicon-remove btnRemoverFuncao cursorpointer" style="color: red"></span>' : '<span class="glyphicon glyphicon-repeat btnReativarFuncao cursorpointer"></span>' ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
     id="modalAdicionarDepartamento">
    <div class="modal-dialog modal-lg" role="document" id="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Cadastrar Departamento</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="iptDepartamentoDescricao" class="col-sm-2 control-label">Departamento</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="iptDepartamentoDescricao" name="iptDepartamento"
                                   placeholder="Departamento" required>
                            <span style="color: darkred" id="spanError" class="hidden">Nome do departamento, deve conter no mínimo 3 caracteres!</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10 text-center">
                            <button type="button" class="btn btn-primary" id="btnSalvarDepartamento"
                                    style="width: 250px !important">Salvar Departamento
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="iptFranquia" name="iptFranquia" value="<?php echo $id_franquia ?>">
<script type="">

    $(document).ready(function () {

        // Abre a modal para cadastrar departamento
        $('.btnModalDepartamento').on('click', function () {
            $('#modalAdicionarDepartamento').modal('show');
        });


        //Evento do click Salvar Departamento
        $('#btnSalvarDepartamento').on('click', function () {

            var strDepartamento = $('#iptDepartamentoDescricao').val();
            var idFranquia = $('#iptFranquia').val();

            if (strDepartamento == '' || strDepartamento.length < 2) {
                $('#iptDepartamentoDescricao').attr('style', 'border: solid 1px; border-color: red;');
                $('#spanError').removeClass('hidden');
                return false;
            } else {
                $('#iptDepartamentoDescricao').removeAttr('style');
                $('#spanError').addClass('hidden');
            }

            $.ajax({
                url: '../php/DepartamentoFuncoes.php',
                data: {
                    iptDepartamentoDescricao: strDepartamento,
                    iptFranquia: idFranquia,
                    action: 'adicionarDepartamento'
                },
                type: 'POST',
                dataType: 'json',
                success: function (data) {

                    if (data.id > 0) {

                        swal('Sucesso', 'Departamento salvo com sucesso', 'success');

                        var html = "<option value='" + data.id + "' selected>" + strDepartamento + "</option>";

                        $('#iptDepartamento').append(html);

                        $('#modalAdicionarDepartamento').modal('hide');

                    } else {
                        swal('Atenção', 'Ocorreu um erro ao salvar o Departamento', 'error');
                    }
                }
            })

        });


        //Evento do Click Salvar Função

        $('#btnSalvarFuncao').on('click', function () {

            var idDepartamento = $('#iptDepartamento').val();
            var strDepartamento = $('select[name="iptDepartamento"] option:selected').last().html();
            var idFranquia = $('#iptFranquia').val();
            var strFuncao = $('#iptFuncao').val();
            var erro = 0;


            if (strFuncao == '' || strFuncao.length < 2) {
                $('#iptFuncao').attr('style', 'border: solid 1px; border-color: red;');
                $('#spanErrorFuncao').removeClass('hidden');
                erro = 1;
            } else {
                $('#iptFuncao').removeAttr('style');
                $('#spanErrorFuncao').addClass('hidden');
            }

            if (idDepartamento == '' || idDepartamento == 0) {
                $('#iptDepartamento').attr('style', 'border: solid 1px; border-color: red;');
                $('#spanErroDepartamento').removeClass('hidden');
                erro = 1;
            } else {
                $('#iptDepartamento').removeAttr('style');
                $('#spanErroDepartamento').addClass('hidden');
            }

            if (erro == 1) {
                return false;
            }
            $.ajax({
                url: '../php/DepartamentoFuncoes.php',
                data: {
                    iptDepartamento: idDepartamento,
                    iptFranquia: idFranquia,
                    iptFuncaoDescricao: strFuncao,
                    action: 'adicionarFuncao'
                },
                type: 'POST',
                dataType: 'json',
                success: function (data) {

                    if (data.id > 0) {

                        swal('Sucesso!', 'Função cadastrada com sucesso!', 'success');

                        var html = "<tr data-id='" + data.id + "'>" +
                            "<td>" + strDepartamento + "</td>" +
                            "<td>" + strFuncao + "</td>" +
                            "<td class='text-center'><span class='glyphicon glyphicon-remove btnRemoverFuncao cursorpointer' style='color: red;'></span></td>" +
                            "</tr>";

                        $('#tbodyFuncoes').append(html);

                    } else {
                        swal('Atenção', 'Ocorreu um erro ao salvar a Função', 'error');
                    }
                }
            })

        });

        $('#iptDepartamento').on('change', function () {

            var idDepartamento = $(this).val();
            var idFranquia = $('#iptFranquia').val();

            $.ajax({
                url: '../php/DepartamentoFuncoes.php',
                data: {
                    action: 'listarFuncoesByDepartamento',
                    iptDepartamento: idDepartamento,
                    idFranquia: idFranquia
                },
                type: 'POST',
                dataType: 'json',
                success: function (data) {

                    $('#tbodyFuncoes').html(data);

                }
            })

        });

    })

    $(document).on('click', '.btnRemoverFuncao', function () {

        var id = $(this).parent().parent().data('id');
        var objLinha = $(this).parent().parent();

        $.ajax({

            url: '../php/DepartamentoFuncoes.php',
            data: {
                action: 'removerFuncao',
                id: id
            },
            type: 'POST',
            dataType: 'json',
            success: function (data) {

                if (data.retorno == 1) {

                    objLinha.remove();

                }else if(data.retorno == 2){
                    swal('Atenção', 'Existem Funcionários Cadastrados para esta função, primeiro altere o cadastro de funcionários!', 'error');
                }

            }

        });

    });
</script>