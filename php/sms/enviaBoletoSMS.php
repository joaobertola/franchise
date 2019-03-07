<?php

ob_start();
session_start();

//print_r($_REQUEST);
//print_r($_SESSION);

$action = $_REQUEST['action'];

switch ($action) {
    case 'enviarCodBarraSMS':

        $data = trim($_POST['dt_vencimento']);
        $valor = trim($_POST['valor']);
        $cod_barra = str_replace(["."," "],"",$_POST['barcode']);
        $smsNumber = $_POST['smsNumber'];

        $msg = "Seu boleto da Web Control c/ venc para {$data} no valor de {$valor}, pague com o cód. de barras: {$cod_barra}";


        $args = array(
            'action' => 'sendsms', //retorna do add no database
            'lgn' => 'WEBCONTROL',//nao e master aqui!
            'pwd' => '12462056',
            'msg' => $msg,
            'numbers' => $smsNumber
        );

        $field_string = http_build_query($args);

        //$url = 'http://server1.allcancesms.com.br/app/modulo/api/index.php?' . $field_string;
        $url = 'http://corporativo.allcancesms.com.br/app/modulo/api/index.php?' . $field_string;

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_URL,$url);
        $result = curl_exec($handle);

        //close connection
        curl_close($handle);

        $arry = json_decode($result);

        if(isset($arry) && $arry->status == '1'){

            require_once '../../../webcontrol/classes/DbConnection.class.php';

            $protocolo = $_REQUEST['smsId'];
            $codigo = $_REQUEST['codLoja_SMS'];
            $atendente = $_SESSION['usuario'];
            $franquia = $_SESSION['franquia'];
            $nomec = $_REQUEST['nomeFantaia_SMS'];
            $ocoorencia = $msg;
            $id_atendente = $_SESSION['id'];

            try {


                $conexao = new DBConnection();
                $sqlString = "INSERT INTO cs2.ocorrencias 
                    (protocolo,codigo,atendente,franquia,nomec,tipo_ocorr,ocorrencia,data,id_atendente) 
                    VALUES 
                    (:protocolo,:codigo,:atendente,:franquia,:nomec,:tipo_ocorr,:ocorrencia,:data,:id_atendente)";
                $sql = $conexao->pdo->prepare($sqlString);

                $parans = [
                    ':protocolo' => $protocolo,
                    ':codigo' => $codigo,
                    ':atendente' => $atendente,
                    ':franquia' => $franquia,
                    ':nomec' => $nomec,
                    ':tipo_ocorr' => 5,
                    ':data' => date('Y-m-d H:i:s'),
                    ':ocorrencia' => $ocoorencia,
                    ':id_atendente' => $id_atendente
                ];
                $sql->execute($parans);


            } catch (PDOException $e) {
                die($e->getMessage());
            }

            echo 'SMS Enviado com sucesso.';
        }else{
            echo 'Não foi possível enviar seu SMS.';
        }

        break;

    case 'digitarCodBarraSMS':

        $data = trim($_POST['dt_vencimento']);
        $valor = trim($_POST['valor']);
        $cod_barra = str_replace(["."," "],"",$_POST['barcode']);
        $smsNumber = $_POST['smsNumber'];

        $msg = $_POST['txtSmsManual'];

        $msg = "Seu boleto da Web Control c/ venc para {$data} no valor de {$valor}, pague com o cód. de barras: {$msg}";


        $args = array(
            'action' => 'sendsms', //retorna do add no database
            'lgn' => 'WEBCONTROL',//nao e master aqui!
            'pwd' => '12462056',
            'msg' => $msg,
            'numbers' => $smsNumber
        );

        $field_string = http_build_query($args);

        //$url = 'http://server1.allcancesms.com.br/app/modulo/api/index.php?' . $field_string;
        $url = 'http://corporativo.allcancesms.com.br/app/modulo/api/index.php?' . $field_string;

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_URL,$url);
        $result = curl_exec($handle);

        //close connection
        curl_close($handle);

        $arry = json_decode($result);

        if(isset($arry) && $arry->status == '1'){

            require_once '../../../webcontrol/classes/DbConnection.class.php';

            $protocolo = $_REQUEST['smsId'];
            $codigo = $_REQUEST['codLoja_SMS'];
            $atendente = $_SESSION['usuario'];
            $franquia = $_SESSION['franquia'];
            $nomec = $_REQUEST['nomeFantaia_SMS'];
            $ocoorencia = $msg;
            $id_atendente = $_SESSION['id'];

            try {


                $conexao = new DBConnection();
                $sqlString = "INSERT INTO cs2.ocorrencias
                    (protocolo,codigo,atendente,franquia,nomec,tipo_ocorr,ocorrencia,data,id_atendente)
                    VALUES
                    (:protocolo,:codigo,:atendente,:franquia,:nomec,:tipo_ocorr,:ocorrencia,:data,:id_atendente)";
                $sql = $conexao->pdo->prepare($sqlString);

                $parans = [
                    ':protocolo' => $protocolo,
                    ':codigo' => $codigo,
                    ':atendente' => $atendente,
                    ':franquia' => $franquia,
                    ':nomec' => $nomec,
                    ':tipo_ocorr' => 5,
                    ':data' => date('Y-m-d H:i:s'),
                    ':ocorrencia' => $ocoorencia,
                    ':id_atendente' => $id_atendente
                ];
                $sql->execute($parans);


            } catch (PDOException $e) {
                die($e->getMessage());
            }

            echo 'SMS Enviado com sucesso.';
        }else{
            echo 'Não foi possível enviar seu SMS.';
        }

        break;
}