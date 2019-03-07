<?php
/**
 * @file ComissaoApoio.php
 * @brief
 * @author ARLLON DIAS
 * @date 07/03/2017
 * @version 1.0
 **/
require "connect/sessao.php";
require "connect/conexao_conecta.php";


if ($_POST) {


    $valorComissao = $_POST['valorComissao'];
    $idCadastro = $_POST['idCadastro'];

    switch ($_POST['action']) {


        case  'comissaoAfiliacao' :

            if ($_POST['marcarPago'] == 'true') {
                $sql = "
                        UPDATE cs2.cadastro
                          SET vr_pgto_comissao = '$valorComissao',
                              dt_pgto_comissao = NOW()
                        WHERE codLoja = '$idCadastro'
                              ";
            } else {
                $sql = "
                        UPDATE cs2.cadastro
                          SET vr_pgto_comissao = NULL,
                              dt_pgto_comissao = NULL
                        WHERE codLoja = '$idCadastro'
                              ";
            }

            $result = mysql_query($sql, $con);

            $arrRetorno['sucess'] = 0;
            if ($result) {
                $arrRetorno['sucess'] = 1;
            }

            echo json_encode($arrRetorno);

            break;

        case 'comissaoBonus':

            $idFuncionario = $_POST['idFuncionario'];
            $tipo = $_POST['tipo'];
            $dataReferencia = $_POST['dataReferencia'] . '-25';

            if($_POST['marcarPago'] == 'true'){

                $sql = " INSERT INTO cs2.funcionario_bonus_afiliacao(
                              id_funcionario,
                              referencia_bonus,
                              data_pagamento,
                              tipo_bonus)
                         VALUES(
                                '$idFuncionario',
                                '$dataReferencia',
                                NOW(),
                                '$tipo'
                                )";
                $result = mysql_query($sql, $con);
            }else{

                $sql = " DELETE FROM cs2.funcionario_bonus_afiliacao WHERE id_funcionario = '$idFuncionario' AND referencia_bonus = '$dataReferencia'";

                $result = mysql_query($sql, $con);
            }

            break;

        case 'comissaoEquipamento' :


            $idEquipamento = $_POST['idEquipamento'];

            if ($_POST['marcarPago'] == 'true') {

                $sql = "UPDATE cs2.cadastro_equipamento
                            SET vr_pgto_comissao = 	'$valorComissao',
                                dt_pgto_comissao = NOW()
                        WHERE id = '$idEquipamento';";

            } else {

                $sql = "UPDATE cs2.cadastro_equipamento
                            SET vr_pgto_comissao = 	NULL,
                                dt_pgto_comissao = NULL
                        WHERE id = '$idEquipamento';";

            }


            $result = mysql_query($sql, $con);

            $arrRetorno['sucess'] = 0;
            if ($result) {
                $arrRetorno['sucess'] = 1;
            }

            echo json_encode($arrRetorno);

            break;

    }

}