<?php

ini_set('allow_url_include', true);

// Desenvolvimento
// define('ENDERECO', 'http://' . $_SERVER["HTTP_HOST"] . '/franquias');

// Produção 
define('ENDERECO', 'https://' . $_SERVER["HTTP_HOST"]);

require_once __DIR__ . '/../../atendimento/classes/DbConnection.class.php';
require_once __DIR__ . '/../controller/VeiculosController.php';

// Instancia o objeto
$veiculos = new VeiculosController();
// Lista todos os veiculos cadastrados 
$listaVeiculos = $veiculos->selectAll();
// Lista todos os fucionarios ativos 
$listaFuncionarios = $veiculos->selectFuncionarios();

?>

<!-- CSS Específico -->
<link rel="stylesheet" href="<?= ENDERECO; ?>/css/controle_veiculos.css">
<!-- Font Awesome  -->
<link href="<?= ENDERECO; ?>/vendor/fontawesome/css/all.css" rel="stylesheet">
<!-- Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<!-- DataTables -->
<link rel="stylesheet" href="<?= ENDERECO; ?>/js/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= ENDERECO; ?>/js/datatables-responsive/css/responsive.bootstrap4.min.css">
<!-- Date Picker  -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet" />
<!-- Select2 -->
<link rel="stylesheet" href="<?= ENDERECO; ?>/vendor/select2/css/select2.min.css">
<link rel="stylesheet" href="<?= ENDERECO; ?>/vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <button class="btn btn-primary mt-2 mb-3" data-toggle="modal" data-target="#cadastroVeiculo"><i class="fas fa-plus"></i> Incluir Novo Veículo</button>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <table id="veiculosDataTable" class="table table-bordered table-hover h1">
                <thead>
                    <tr>
                        <th>Id Veículo</th>
                        <th>Modelo</th>
                        <th>Placa</th>
                        <th>Ano/Modelo</th>
                        <th>Renavam</th>
                        <th>Chassi</th>
                        <th>Chave Reserva</th>
                        <th>Credencial</th>
                        <th>Condutor</th>
                        <th>Condutor Provisório</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="bodyVeiculos">
                    <?php foreach ($listaVeiculos as $lv) : ?>
                        <tr>
                            <td><?= $lv['idveiculo']; ?></td>
                            <td><?= $lv['modelo']; ?></td>
                            <td><?= $lv['placa']; ?></td>
                            <td><?= $lv['ano_modelo']; ?></td>
                            <td><?= $lv['renavam']; ?></td>
                            <td><?= $lv['chassi']; ?></td>
                            <td><?= $lv['chave_reserva'] == 1 ? 'Sim' : 'Não'; ?></td>
                            <td><?= $lv['credencial']; ?></td>
                            <td><?= $lv['condutor']; ?></td>
                            <td><?= $lv['condutorprovisorio']; ?></td>
                            <td>
                                <a href="#" data-id="<?= $lv['idveiculo']; ?>" data-toggle="modal" data-target="#editVeiculo" class="editVeiculo mr-4 text-decoration-none">Editar <i class="fas fa-edit"></i></a>
                                <a href="#" data-id="<?= $lv['idveiculo']; ?>" class="excluiVeiculo mr-4 text-decoration-none">Excluir <i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Id Veículo</th>
                        <th>Modelo</th>
                        <th>Placa</th>
                        <th>Ano/Modelo</th>
                        <th>Renavam</th>
                        <th>Chassi</th>
                        <th>Chave Reserva</th>
                        <th>Credencial</th>
                        <th>Condutor</th>
                        <th>Condutor Provisório</th>
                        <th>Ações</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Modal Cadastro de Veículo  -->
<div class="modal fade" id="cadastroVeiculo" tabindex="-1" role="dialog" aria-labelledby="CadastroVeiculolLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleCadastroVeiculo">Cadastrar Novo Veículo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-success text-center" id="sucessoAlert" role="alert" style="display: none;">
                <b>Sucesso!</b> Veículo cadastrado com sucesso.
            </div>
            <div class="alert alert-danger text-center" id="erroAlert" role="alert" style="display: none;">
                <b>Erro!</b> Não foi possível cadastrar o veículo.
            </div>
            <div class="modal-body" id="bodyCadastro">
                <form name="cadastroVeiculo">
                    <input type="hidden" name="op" value="insertVeiculo">
                    <div class="form-group">
                        <input maxlength="50" type="text" class="form-control form-required" id="modelo" name="modelo" placeholder="Modelo Veículo">
                    </div>
                    <div class="form-group">
                        <input maxlength="10" type="text" class="form-control form-required" id="placa" name="placa" placeholder="Placa Veículo">
                    </div>
                    <div class="form-group">
                        <input maxlength="4" type="text" class="form-control form-required" id="ano_modelo" name="ano_modelo" placeholder="Ano/Modelo">
                    </div>
                    <div class="form-group">
                        <input type="number" class="form-control form-required" id="renavam" name="renavam" placeholder="Renavam">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-required" id="chassi" name="chassi" placeholder="Chassi">
                    </div>
                    <div class="form-group">
                        <select class="custom-select" name="chave_reserva">
                            <option>Chave Reserva</option>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="number" class="form-control" id="credencial" name="credencial" placeholder="Credencial">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="condutor" name="condutor" placeholder="Condutor">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="condutor_provisorio" name="condutor_provisorio" placeholder="Condutor Provisório">
                    </div>
                    <button type="submit" class="btn btn-primary" id="insertVeiculo">Salvar <i class="fa fa-save fa-solid"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edição de Veículo  -->
<div class="modal fade bd-example-modal-lg overflow-auto" id="editVeiculo" tabindex="-1" role="dialog" aria-labelledby="EditaVeiculo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="titleEditVeiculo"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="bodyOcorrencias">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="w-25">Modelo</th>
                                    <td class="table-active font-weight-bold" id="editModelo"></td>
                                </tr>
                                <tr>
                                    <th class="">Placa</th>
                                    <td class="table-active font-weight-bold" id="editPlaca"></td>
                                </tr>
                                <tr>
                                    <th class="">Ano/Modelo</th>
                                    <td class="table-active font-weight-bold" id="editAno_modelo"></td>
                                </tr>
                                <tr>
                                    <th class="">Condutor</th>
                                    <td class="table-active font-weight-bold" id="editCondutor"></td>
                                </tr>
                                <tr>
                                    <th class="">Condutor Provisório</th>
                                    <td class="table-active font-weight-bold" id="editCondutorProvisorio"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row justify-content-center mb-3">
                    <div class="col-md-4">
                        <button type="button" data-toggle="modal" data-target="#cadastraOcorrencia" class="btn btn-secondary m-3">Nova Ocorrência <i class="fa fa-plus ml-2"></i></button>
                    </div>
                </div>
                <div class="registroOcorrencias"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cadastro de OCorrencia  -->
<div class="modal fade bd-example-modal-lg" id="cadastraOcorrencia" role="dialog" aria-labelledby="CadastroOcorrencia">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="titleCadastroOcorrencia">Registrar Ocorrência</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-success text-center" id="sucessoOcorrenciaAlert" role="alert" style="display: none;">
                <b>Sucesso!</b> Ocorrência cadastrado com sucesso.
            </div>
            <div class="alert alert-danger text-center" id="erroOcorrenciaAlert" role="alert" style="display: none;">
                <b>Erro!</b> Não foi possível cadastrar o ocorrência.
            </div>
            <div class="modal-body" id="bodyOcorrencias">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <form name="cadastroOcorrencia" id="cadastroOcorrencia" enctype="multipart/form-data">
                            <input type="hidden" name="op" value="insertOcorrencia">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th class="w-25">Modelo</th>
                                        <td class="table-active font-weight-bold" id="ocorrenciaModelo"></td>
                                        <input type="hidden" name="idVeiculo" id="ocorrenciaIdVeiculo">
                                    </tr>
                                    <tr>
                                        <th class="">Placa</th>
                                        <td class="table-active font-weight-bold" id="ocorrenciaPlaca"></td>
                                    </tr>
                                    <tr>
                                        <th class="">Ano/Modelo</th>
                                        <td class="table-active font-weight-bold" id="ocorrenciaAno_modelo"></td>
                                    </tr>
                                    <tr>
                                        <th class="">Condutor</th>
                                        <td class="table-active font-weight-bold">
                                            <input readonly class="form-control" type="text" name="condutor" id="ocorrenciaCondutor">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="">Atendente</th>
                                        <td class="table-active font-weight-bold">
                                            <div class="form-group">
                                                <select name="atendente" class="form-control select2" style="width: 100%;">
                                                    <option disabled selected>Selecione: </option>
                                                    <?php foreach ($listaFuncionarios as $lf) : ?>
                                                        <option value="<?= $lf['id']; ?>"><?= $lf['nome']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="">Ocorrência</th>
                                        <td class="table-active font-weight-bold">
                                            <select name="ocorrencia" id="ocorrencia" class="form-control select2" style="width: 100%;">
                                                <option selected>Selecione: </option>
                                                <option value="1">Mecânica</option>
                                                <option value="2">Lavagem</option>
                                                <option value="3">Plotagem</option>
                                                <option value="4">Troca de Condutor</option>
                                                <option value="5">Chave Reserva</option>
                                                <option value="6">Manutenção</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr id="inputCondutorProvisorio" class="d-none">
                                        <th class="">Condutor Provisório</th>
                                        <td class="table-active font-weight-bold">
                                            <input class="form-control" type="text" name="condutorProvisorio" id="ocorrenciaCondutorProvisorio" value="">
                                            <input type="text" class="form-control datePicker mt-2" id="dataInical" name="dataInical" placeholder="Data Inicial">
                                            <input type="text" class="form-control datePicker mt-2" id="dataFinal" name="dataFinal" placeholder="Data Final">
                                        </td>
                                    </tr>
                                    <tr id="inputChaveReserva" class="d-none">
                                        <th class="">Chave Reserva</th>
                                        <td class="table-active font-weight-bold">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" checked name="chave_reserva" id="chave_reserva2" value="0">
                                                <label class="form-check-label" for="chave_reserva2">Não</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="chave_reserva" id="chave_reserva1" value="1">
                                                <label class="form-check-label" for="chave_reserva1">Sim</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="inputImagemOS" class="d-none">
                                        <th class="">Imagem O.S.</th>
                                        <td class="table-active font-weight-bold">
                                            <input type="file" class="form-control-file mb-3" id="imagem" name="imagem"> </td>
                                    </tr>
                                    <tr>
                                        <th class="">Local</th>
                                        <td class="table-active font-weight-bold">
                                            <select name="local" id="local" class="form-control select2" style="width: 100%;">
                                                <option disabled selected>Selecione: </option>
                                                <option value="1">WebControl</option>
                                                <option value="2">Mecânica Chile</option>
                                                <option value="3">Mecânica Patitucci</option>
                                                <option value="4">Gláucio Funilaria</option>
                                                <option value="5">Guga Funilaria</option>
                                                <option value="6">ESA. Adesivos</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="">Garantia</th>
                                        <td class="table-active font-weight-bold">
                                            <select name="garantia" id="garantia" class="form-control select2" style="width: 100%;">
                                                <option disabled selected>Selecione: </option>
                                                <option value="0">Sem Garantia</option>
                                                <option value="1">30 dias</option>
                                                <option value="2">60 dias</option>
                                                <option value="3">90 dias</option>
                                                <option value="4">120 dias</option>
                                                <option value="5">180 dias</option>
                                                <option value="6">1 ano</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="">Descrição</th>
                                        <td class="table-active font-weight-bold">
                                            <textarea class="form-control" name="descricao"></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="d-flex w-100 justify-content-center">
                                <button type="button" class="btn btn-success w-25 mr-3" id="sendCadastroOcorrencia">Salvar</button>
                                <button type="button" class="btn btn-warning w-25" data-dismiss="modal">Voltar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap e Jquery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<!-- Ajax  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Select2 -->
<script src="<?= ENDERECO; ?>/vendor/select2/js/select2.full.min.js"></script>
<!-- DataTables -->
<script src="<?= ENDERECO; ?>/js/datatables/jquery.dataTables.min.js"></script>
<script src="<?= ENDERECO; ?>/js/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= ENDERECO; ?>/js/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= ENDERECO; ?>/js/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- DatePicker  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- Script Específico  -->
<script src="<?= ENDERECO; ?>/js/controle_veiculos.js"></script>