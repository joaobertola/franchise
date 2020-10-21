<?php

require '../../atendimento/classes/DbConnection.class.php';
require '../controller/VeiculosController.php';
require '../controller/OcorrenciasController.php';

$op = $_REQUEST['op'];

$retorno = [];

defined("LISTAR_VEICULOS")   || define("LISTAR_VEICULOS",  "listaVeiculo");
defined("INSERT_VEICULOS")   || define("INSERT_VEICULOS",  "insertVeiculo");
defined("INSERT_OCORRENCIA") || define("INSERT_OCORRENCIA",  "insertOcorrencia");
defined("DELETE_VEICULO")    || define("DELETE_VEICULO",  "deleteVeiculo");

switch ($op) {
    case LISTAR_VEICULOS:

        $veiculo = new VeiculosController();

        if (isset($_POST['idVeiculo']) && !empty($_POST['idVeiculo'])) {
            $idVeiculo = $_POST['idVeiculo'];
            $listaVeiculos = $veiculo->selectOne($idVeiculo);
        } else {
            $listaVeiculos = $veiculo->selectAll();
        }

        echo json_encode($listaVeiculos);

        break;
    case INSERT_VEICULOS:

        $status = false;

        unset($_REQUEST['op']);

        $dados = $_REQUEST;

        $veiculo = new VeiculosController();
        $veiculo->setModelo($dados['modelo']);
        $veiculo->setPlaca($dados['placa']);
        $veiculo->setAnoModelo($dados['ano_modelo']);
        $veiculo->setRenavam($dados['renavam']);
        $veiculo->setChassi($dados['chassi']);
        $veiculo->setChaveReserva($dados['chave_reserva']);
        $veiculo->setCredencial($dados['credencial']);
        $veiculo->setCondutor($dados['condutor']);
        $veiculo->setIdCondutorProvisorio($dados['condutor_provisorio']);

        if ($veiculo->insert()) {
            $status = true;
        }

        $retorno['status'] = $status;

        echo json_encode($retorno);

        break;
    case INSERT_OCORRENCIA:

        $status = false;

        unset($_REQUEST['op']);

        $dados = $_REQUEST;

        $files = $_FILES;

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $arquivo_tmp = $_FILES['imagem']['tmp_name'];
            $nome = $_FILES['imagem']['name'];
            $extensao = pathinfo($nome, PATHINFO_EXTENSION);
            $extensao = strtolower($extensao);
            if (strstr('.jpg;.jpeg;.gif;.png', $extensao)) {
                $novoNome = uniqid(time()) . '.' . $extensao;
                $destino = '../imagens';
                if (!is_dir($destino)) {
                    mkdir($destino);
                }
                $destino = $destino . '/' . $novoNome;
                if (@move_uploaded_file($arquivo_tmp, $destino)) {
                    $dados['imagem'] = $novoNome;
                }
            }
        }

        $ocorrencia = new OcorrenciasController();
        $ocorrencia->setIdVeiculo($dados['idVeiculo']);
        $ocorrencia->setCondutor($dados['condutor']);
        $ocorrencia->setCondutorProvisorio($dados['condutorProvisorio']);
        $ocorrencia->setAtendente($dados['atendente']);
        $ocorrencia->setOcorrencia($dados['ocorrencia']);
        $ocorrencia->setDataInicial($dados['dataInicial']);
        $ocorrencia->setDataFinal($dados['dataFinal']);
        $ocorrencia->setLocal($dados['local']);
        $ocorrencia->setGarantia($dados['garantia']);
        $ocorrencia->setDescricao($dados['descricao']);
        $ocorrencia->setChaveReserva($dados['chave_reserva']);
        $ocorrencia->setImagem($dados['imagem']);

        if ($ocorrencia->insert()) {
            $status = true;
        }

        $retorno['status'] = $status;

        echo json_encode($retorno);

        break;
    case DELETE_VEICULO:

        $status = false;

        $idVeiculo = $_POST['idVeiculo'];

        $veiculo = new VeiculosController();

        if ($veiculo->delete($idVeiculo)) {
            $status = true;
        }

        $retorno['status'] = $status;

        echo json_encode($retorno);

        break;

    default:
        return "Erro";
        break;
}
