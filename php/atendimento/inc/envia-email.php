<?php

    /* include conexao */
    require_once ( '../classes/DbConnection.class.php' );
    require_once('../classes/NovoAtendimento.class.php');
    include_once('smtp.class.php');

    echo json_encode($_POST['protocolo']);
    
    if($_POST){
        try{
            $objOrdem = new NovoAtendimento();
            $dadosOrdem = $objOrdem->pegaDadosEnviarPorEmail($_POST['protocolo']);
            $dadosOrdem = $dadosOrdem[0];
            //print_r($dadosOrdem);
            $aResult = array();
  
            $user_email = $dadosOrdem['email_depto'];

            $host = "10.2.2.7"; // host do servidor SMTP
            $assunto = "Ordem de Atendimento No. " . $dadosOrdem['protocolo'];
            $conteudo_da_mensagem =  $_POST['html'] . $_POST['style'];
            $smtp2 = new Smtp($host); // host do servidor SMTP
            $smtp2->user = "tecnologia@webcontrolempresas.com.br"; // usuario do servidor SMTP
            $smtp2->pass = "informbrasil"; // senha dousuario do servidor SMTP
            $smtp2->debug = true; // ativar a autentica� SMTP
            $smtp2->CharSet = 'UTF-8';
            $from = "tecnologia@webcontrolempresas.com.br";
            //if($smtp2->Send($user_email, $from, $assunto, $conteudo_da_mensagem )) e = "OK";
            $aResult['resultado'] = $smtp2->Send($user_email, $from, $assunto, $conteudo_da_mensagem ) ? "E-mail enviado com sucesso para ". $user_email : "Error. Email nao enviado.";
            
            echo json_encode($aResult);
            
        } catch (Exception $e){
            echo $e;
        }
        
    }

?>