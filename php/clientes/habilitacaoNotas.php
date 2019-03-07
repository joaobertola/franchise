<?php
/**
 * Created by PhpStorm.
 * User: dev02
 * Date: 25/11/2016
 * Time: 09:31
 */
require "../connect/sessao.php";
require "../connect/conexao_conecta.php";
require "../connect/funcoes.php";

if($_POST){

    $idCadastro = $_POST['id_cadastro'];
    $valor = $_POST['value'];

    switch($_POST['action']){

        case 'receitaNFE' :

            $sql = " UPDATE cs2.cadastro SET liberacao_receita_nfe = '$valor' WHERE codLoja = '$idCadastro'";
//            echo $sql;die;
            $query = mysql_query($sql,$con);

            $arrRetorno['mensagem'] = 0;
            if(mysql_affected_rows($con) > 0){
                $arrRetorno['mensagem'] = 1;
            }

            echo json_encode($arrRetorno);

            break;

        case 'receitaNFC' :

            $sql = " UPDATE cs2.cadastro SET liberacao_receita_nfc = '$valor' WHERE codLoja = '$idCadastro'";
//            echo $sql;die;
            $query = mysql_query($sql,$con);

            $arrRetorno['mensagem'] = 0;
            if(mysql_affected_rows($con) > 0){
                $arrRetorno['mensagem'] = 1;
            }

            echo json_encode($arrRetorno);

            break;

        case 'marcarUrgencia' :

            $idCadastro = $_POST['id_cadastro'];

            $sql = "UPDATE cs2.atendimento_cadastros SET urgencia = IF(urgencia = 1,0,1) WHERE id_cadastro = $idCadastro";

//            echo $sql;
//            die;

            $query = mysql_query($sql,$con);

            $arrRetorno['mensagem'] = 0;
            if(mysql_affected_rows($con) > 0){
                $arrRetorno['mensagem'] = 1;
            }

            echo json_encode($arrRetorno);


            break;


        case 'baixarListagem' :

            $idCadastro = $_POST['id_cadastro'];
            $motivo_baixa = $_POST['motivo'];

            $sql = "UPDATE cs2.atendimento_cadastros SET data_baixa = NOW(), motivo_baixa = '$motivo_baixa' WHERE id_cadastro = $idCadastro";

            $query = mysql_query($sql,$con);

            $arrRetorno['mensagem'] = 0;
            if(mysql_affected_rows($con) > 0){
                $arrRetorno['mensagem'] = 1;
            }

            echo json_encode($arrRetorno);


            break;

    }

}