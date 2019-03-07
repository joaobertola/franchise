<?php
    ob_start();
    session_start();

    // Alteração, inserir um botão de { Venda com Equipamento }

    include('../../../webcontrol/classes/DbConnection.class.php');

    $conexao = New DbConnection();

    // Parans
    $codloja = $_REQUEST['codloja'];
    $flag = $_REQUEST['flag'];
    $senha = $_REQUEST['senha'];

    if(empty($senha)){
        $result = array('status'=>0,'mensagem'=>'Preencha a senha');
    }else{


        // Valida a Senha
        $sql = "SELECT id FROM cs2.funcionario WHERE senha = '{$senha}' ";
        $pdo = $conexao->pdo->prepare($sql);
        $pdo->execute();
        $user = $pdo->fetch(PDO::FETCH_ASSOC);
        if($user){

            $userId = $user['id'];

            $sql  = "UPDATE cs2.cadastro SET venda_equipamento=$flag, id_user_equipamento=$userId WHERE codloja = '$codloja' ";
            $pdo = $conexao->pdo->prepare($sql);
            $pdo->execute();

            $result = array('status'=>1,'mensagem'=>'Alterado com Sucesso');

        }else{

            $result = array('status'=>0,'mensagem'=>'Senha Inválida');
        }
    }

    header('Content-Type: application/json');
    echo json_encode($result);

