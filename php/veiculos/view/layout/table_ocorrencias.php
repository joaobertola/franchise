<?php

$idVeiculo = $_REQUEST['idVeiculo'];

require_once __DIR__ . '/../../../atendimento/classes/DbConnection.class.php';
require_once __DIR__ . '/../../controller/OcorrenciasController.php';

$ocorrencia = new OcorrenciasController();

$ocorrecias = $ocorrencia->selectAll($idVeiculo);

?>
<?php foreach ($ocorrecias as $o) : ?>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th class="bg-danger w-35">Protocolo de Registro</th>
                        <td class="table-active font-weight-bold">
                            <?= $o['idocorrencia']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-danger">Data e Hora Ocorrência</th>
                        <td class="table-active font-weight-bold">
                            <?= date('d/m/Y H:i:s', strtotime($o['data_hora'])); ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-danger">Condutor | Condutor Provisório</th>
                        <td class="table-active font-weight-bold">
                            <?= $o['condutor']; ?> | <?= $o['condutorprovisorio']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-danger">Atendente</th>
                        <td class="table-active font-weight-bold">
                            <?= $o['nome_atendente']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-danger">Ocorrência</th>
                        <td class="table-active font-weight-bold">
                            <div class="d-flex w-100 align-items-center">
                                <span class="ocorrencia h-100 w-50 mr-0">
                                    <?php
                                    switch ($o['ocorrencia']) {
                                        case 1:
                                            echo 'Mecânica';
                                            break;
                                        case 2:
                                            echo 'Lavagem';
                                            break;
                                        case 3:
                                            echo 'Plotagem';
                                            break;
                                        case 4:
                                            echo 'Troca de Condutor';
                                            break;
                                        case 5:
                                            echo 'Chave Reserva';
                                            break;
                                        case 6:
                                            echo 'Manutenção';
                                            break;
                                        default:
                                            echo 'Nenhuma Ocorrência Selecionada';
                                            break;
                                    }
                                    ?>
                                </span>
                                <?php if ($o['ocorrencia'] == 6) : ?>
                                    <button data-toggle="modal" data-target="#imagemModal" data-id="<?= $o['idocorrencia']; ?>" data-imagem="<?= __DIR__ . '/../../imagens/' . $o['imagem']; ?>" class="openImagem btn btn-light w-25 small-text">Imagem <i class="far fa-image"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <tr id="linhaImagem<?= $o['idocorrencia']; ?>" style="display: none;">
                        <td colspan="2" class="table-active font-weight-bold">
                            <button type="button" class="close" onclick="$('#linhaImagem<?= $o['idocorrencia']; ?>').css('display', 'none');" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="d-flex w-100 align-items-center">
                                <img width="100%" src="#" id="imagem<?= $o['idocorrencia']; ?>" class="imagemOcorrencia">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-danger">Local</th>
                        <td class="table-active font-weight-bold">
                            <?php
                            switch ($o['local']) {
                                case 1:
                                    echo 'WebControl';
                                    break;
                                case 2:
                                    echo 'Mecânica Chile';
                                    break;
                                case 3:
                                    echo 'Mecânica Patitucci';
                                    break;
                                case 4:
                                    echo 'Gláucio Funilaria';
                                    break;
                                case 5:
                                    echo 'Guga Funilaria';
                                    break;
                                case 6:
                                    echo 'ESA. Adesivos';
                                    break;
                                default:
                                    echo 'Nenhum Local Selecionado';
                                    break;
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-danger">Garantia</th>
                        <td class="table-active font-weight-bold">
                            <?php
                            switch ($o['garantia']) {
                                case 0:
                                    echo 'Sem Garantia';
                                    break;
                                case 1:
                                    echo '30 dias';
                                    break;
                                case 2:
                                    echo '60 dias';
                                    break;
                                case 3:
                                    echo '90 dias';
                                    break;
                                case 5:
                                    echo '120 dias';
                                    break;
                                case 6:
                                    echo '180 dias';
                                    break;
                                case 7:
                                    echo '1 ano';
                                    break;
                                default:
                                    echo 'Garantia não selecionada.';
                                    break;
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-danger">Descrição</th>
                        <td class="table-active font-weight-bold">
                            <?= $o['descricao']; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endforeach; ?>