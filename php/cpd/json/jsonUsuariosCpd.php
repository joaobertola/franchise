<?php

require '../../atendimento/classes/DbConnection.class.php';
require '../controller/UsuariosCpdController.php';
require '../controller/OcorrenciasController.php';

$op = $_REQUEST['op'];

$retorno = [];

defined("LISTAR_USUARIO_CPD") || define("LISTAR_USUARIO_CPD",  "listaUsuarioCpd");
defined("INSERT_USUARIO_CPD") || define("INSERT_USUARIO_CPD",  "insertUsuarioCpd");
defined("INSERT_OCORRENCIA")  || define("INSERT_OCORRENCIA",  "insertOcorrencia");
defined("DELETE_VEICULO")     || define("DELETE_VEICULO",  "deleteVeiculo");

switch ($op) {
    case LISTAR_USUARIO_CPD:

        $usuarioCpd = new UsuariosCpdController();

        if (isset($_POST['idUsuarioCpd']) && !empty($_POST['idUsuarioCpd'])) {
            $id = $_POST['idUsuarioCpd'];
            $listaUsuarioCpd = $usuarioCpd->selectOne($id);
        } else {
            $listaUsuarioCpd = $usuarioCpd->selectAll();
        }

        echo json_encode($listaUsuarioCpd);

        break;
    case INSERT_USUARIO_CPD:

        $status = false;

        unset($_REQUEST['op']);

        $dados = $_REQUEST;

        $usuarioCpd = new UsuariosCpdController();
        $usuarioCpd->setNome($dados['nome']);
        $usuarioCpd->setFuncao($dados['funcao']);
        $usuarioCpd->setStatus($dados['ativo']);
        $usuarioCpd->setLogin($dados['user']);
        $usuarioCpd->setSenha($dados['pass']);

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
                    $usuarioCpd->setFoto($novoNome);
                }
            }
        }

        if ($dados['id'] == 0) {
            $metodo = 'insert';
        } else {
            $metodo = 'update';
            $usuarioCpd->setIdUsuarioCpd($dados['id']);
        }




        if ($usuarioCpd->$metodo()) {
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
