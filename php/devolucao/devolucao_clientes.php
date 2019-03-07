<?php

session_start();

require_once '../../../webcontrol/classes/DbConnection.class.php';

extract($_POST);

switch($action) {
    case 'pesquisar_cliente': 

        switch ($_POST['idFranquia']) {
            case '163':
            case '247':
            case '1205':
            case '59':
            case '1204':
            case '4':
            case '5':

                $extra = ' AND a.id_franquia = 1';
                break;
            
            default:
                $extra = ' AND a.id_franquia = '.$_POST['idFranquia'];
                break;
        }

        $db = new DbConnection();
        $sql = "
        SELECT 
            a.codloja,
            a.razaosoc,
            a.nomefantasia,
            a.uf,
            a.cidade,
            a.end,
            a.bairro,
            a.fone,
            a.fax,
            a.celular,
            a.fone_res,
            a.cep,
            a.numero,
            a.email 
        FROM cs2.cadastro as a 
        INNER JOIN base_web_control.webc_usuario as b ON b.id_cadastro = a.codloja AND b.login = :login
        $extra
        GROUP BY a.codloja ";
        die;
        $pdo = $db->pdo->prepare($sql); 
        $aCamposValores = array(
            ':login' => $login
        );
        $pdo->execute($aCamposValores);
        $aReturn = $pdo->fetch(PDO::FETCH_ASSOC);
        echo json_encode($aReturn);
        return $aReturn;
    break;
    case 'alterar_cliente':
        $nome_contato = $_POST['falado_com'];
        $db = new DbConnection();
        // Tabela Cadastro
        $sql_cadastro = "UPDATE cs2.cadastro SET
            uf = :uf,
            cidade = :cidade,
            bairro = :bairro,
            end = :end,
            numero = :numero,
            cep = :cep,
            email = :email
            WHERE 
            codloja = :id_cadastro

        ";
        $pdo_cadastro = $db->pdo->prepare($sql_cadastro);
        $aCamposValoresCadastro = array(
            ':uf'           => $_POST['uf'],
            ':cidade'       => $_POST['cidade'],
            ':bairro'       => $_POST['bairro'],
            ':end'          => $_POST['endereco'],
            ':numero'       => $_POST['numero'],
            ':cep'          => $_POST['cep'],
            ':email'        => $_POST['email'],
            ':id_cadastro'  => $_POST['codloja']
        ); 

        if($pdo_cadastro->execute($aCamposValoresCadastro)){

            /**
             * Save Log (cs2.ocorrencia)
             */
            $sql_ocorrencia = "INSERT cs2.ocorrencias (
                protocolo,
                codigo,
                atendente,
                franquia,
                tipo_ocorr,
                ocorrencia,
                data
            ) VALUES (
                :protocolo,
                :codigo,
                :atendente,
                :franquia,
                :tipo_ocorr,
                :ocorrencia,
                NOW()
            )";
            $pdo_ocorrencia = $db->pdo->prepare($sql_ocorrencia);
            $aCamposValores = array(
                    ':protocolo'    => 0,
                    ':codigo'       => $_POST['codloja'],
                    ':atendente'    => $_POST['atendente'],
                    ':franquia'     => $_SESSION['id'],
                    ':tipo_ocorr'   => $_POST['tipo_documento'],
                    ':ocorrencia'   => "DEVOLUÇÃO DE CORRESPONDÊNCIA CORREIOS - Entrado em contato com {$nome_contato}."
            );
            if($pdo_ocorrencia->execute($aCamposValores)) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
 
    break;
}