<?php

ini_set('allow_url_include', true);

// Desenvolvimento
// define('ENDERECO', 'http://' . $_SERVER["HTTP_HOST"] . '/franquias');

// Produção 
define('ENDERECO', 'https://' . $_SERVER["HTTP_HOST"] . '/franquias');

require_once __DIR__ . '/../../atendimento/classes/DbConnection.class.php';
require_once __DIR__ . '/../controller/UsuariosCpdController.php';

// Instancia o objeto
$usuariocpd = new UsuariosCpdController();
// Lista todos os veiculos cadastrados 
$listaUsuariosCpd = $usuariocpd->selectAll();

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
            <button class="btn btn-primary mt-2 mb-3" data-toggle="modal" data-target="#cadastroUsuarioCpd" onclick="resetModal()"><i class="fas fa-plus"></i> Cadastrar Usuário</button>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <table id="veiculosDataTable" class="table table-bordered table-hover h1">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Nome</th>
                        <th>Função</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="bodyVeiculos">
                    <?php foreach ($listaUsuariosCpd as $uc) : ?>
                        <tr>
                            <td><?= $uc['id']; ?></td>
                            <td><?= $uc['nome']; ?></td>
                            <td>
                                <?php
                                switch ($uc['funcao']) {
                                    case 1:
                                        echo 'Programador PHP Sistema';
                                        break;
                                    case 2:
                                        echo 'Programador PHP Ecommerce';
                                        break;
                                    case 3:
                                        echo 'Programador Delphi';
                                        break;
                                    case 4:
                                        echo 'Suporte Automação';
                                        break;
                                    case 5:
                                        echo 'Supervisor TI';
                                        break;
                                    default:
                                        echo 'Geral';
                                        break;
                                }
                                ?>
                            </td>
                            <td>
                                <a href="#" data-id="<?= $uc['id']; ?>" data-toggle="modal" data-target="#cadastroUsuarioCpd" class="editUsuarioCpd mr-4 text-decoration-none">Editar <i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Usuário</th>
                        <th>Nome</th>
                        <th>Função</th>
                        <th>Ações</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Modal Cadastro de Usuário  -->
<div class="modal fade" id="cadastroUsuarioCpd" tabindex="-1" role="dialog" aria-labelledby="CadastroVeiculolLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleCadastroUsuarioCpd">Cadastrar Novo Usuário</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-success text-center" id="sucessoAlert" role="alert" style="display: none;">
                <b>Sucesso!</b> Gravado com sucesso.
            </div>
            <div class="alert alert-danger text-center" id="erroAlert" role="alert" style="display: none;">
                <b>Erro!</b> Erro ao gravar.
            </div>
            <div class="modal-body" id="bodyCadastro">
                <form name="cadastroUsuarioCpd" enctype="multipart/form-data">
                    <input type="hidden" name="op" value="insertUsuarioCpd">
                    <input type="hidden" id="idUsuarioCpd" name="id" value="0">
                    <!-- Preview Foto  -->
                    <div class="form-group" id="fotoUsuario">
                        <img src="/" alt="Prévia Usuário" style="display: none;" class="imagem-preview" id="imagem-preview">
                    </div>
                    <?php
                    $tamanho = explode('M', ini_get('upload_max_filesize'));
                    $tamanho = $tamanho[0];
                    ?>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" data-max-size="<?= $tamanho * 1000000; ?>" class="custom-file-input" name="imagem" id="imagem">
                            <label class="custom-file-label" for="customFile">Escolher Arquivo</label>
                        </div>
                        <p class="pre text-center">Tamanho mínimo recomendado: <span class='tamFull'>250x250 pixels. </span> - Extensão recomendada: jpg, png</p>
                    </div>
                    <div class="form-group">
                        <input maxlength="105" type="text" class="form-control form-required" id="nome" name="nome" placeholder="Nome Usuário">
                    </div>
                    <div class="form-group">
                        <select class="custom-select form-required" id="funcao" name="funcao">
                            <option>Função</option>
                            <option value="1">Programador PHP Sistema</option>
                            <option value="2">Programador PHP Ecommerce</option>
                            <option value="3">Programador Delphi</option>
                            <option value="4">Suporte Automação</option>
                            <option value="5">Supervisor TI</option>
                        </select>
                    </div>
                    <div class="form-group d-flex align-items-center">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="ativo" name="ativo" value="1" class="custom-control-input form-required">
                            <label class="custom-control-label" for="ativo">Ativo</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="inativo" name="ativo" value="0" class="custom-control-input form-required">
                            <label class="custom-control-label" for="inativo">Inativo</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input maxlength="105" type="text" class="form-control form-required" id="user" name="user" placeholder="Usuário">
                    </div>
                    <div class="form-group">
                        <input maxlength="pass" type="password" class="form-control form-required" id="pass" name="pass" placeholder="Senha">
                    </div>
                    <button type="submit" class="btn btn-primary" id="insertUsuarioCpd">Salvar <i class="fa fa-save fa-solid"></i></button>
                </form>
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
<script src="<?= ENDERECO; ?>/js/usuarios_cpd.js"></script>