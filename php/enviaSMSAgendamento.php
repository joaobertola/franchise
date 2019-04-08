<?php

error_reporting(E_ALL | E_STRICT);
set_time_limit(30);
header('Content-Type: application/json; charset=utf-8');
header('Content-language: pt-br');

global $con;

// ABRE A CONEXÃO COM O BANCO DE DADOS
$con = @mysql_pconnect("10.2.2.3", "csinform", "inform4416#scf");

// FAZ A CONSULTA DOS TELEFONES CADASTRADOS QUE NÃO POSSUEM DATA DE AGENDAMENTO
$sql = "SELECT
            REPLACE(REPLACE(REPLACE(REPLACE(fone2,'(',''),')',''),' ',''),'-','') AS fone
        FROM cs2.controle_comercial_visitas AS cv
        WHERE id_franquia = 247
        AND fone2 IS NOT NULL
        AND fone2 != ''
        AND (SUBSTR(REPLACE(REPLACE(REPLACE(REPLACE(fone2,'(',''),')',''),' ',''),'-',''),3,1) = 9 ||
        SUBSTR(REPLACE(REPLACE(REPLACE(REPLACE(fone2,'(',''),')',''),' ',''),'-',''),3,1) = 8 ||
        SUBSTR(REPLACE(REPLACE(REPLACE(REPLACE(fone2,'(',''),')',''),' ',''),'-',''),3,1) = 7)
        AND LENGTH(REPLACE(REPLACE(REPLACE(REPLACE(fone2,'(',''),')',''),' ',''),'-','')) > 9
        AND enviar_sms = 'S'
--      AND data_cadastro >= subdate(now(), interval 30 day)
        GROUP BY fone
        ";
$res = mysql_query($sql);
$qtdFones = mysql_num_rows($res);

// BUSCA AS CAMPANHAS QUE ESTÃO AGENDADAS PARA O DIA ATUAL
$sqlCampanhas = "SELECT
                        *
                    FROM apoio.sms_automatico
                    WHERE ativo = 'A'
                    AND FIND_IN_SET(DAY(NOW()),dias_do_mes)
                    ";


// PERCORE OS TELEFONES E ARMAZENA EM UMA VARIÁVEL SEPARADOS POR ','
$fones = '41996827867,41992806732,41999199970,41988571735';
$fonesTotal = $fones;
for($i=1;$i<=$qtdFones;$i++){

    $result = mysql_fetch_array($res);

    if($fones == ''){
        $fones .= $result['fone'];
    }else{
        $fones .= ',' . $result['fone'];
    }

    $fonesTotal .= ',' . $result['fone'];


    if($i % 100 == 0 && $i != 0){

        $resultCampanhas = mysql_query($sqlCampanhas);
        $qtdCampanhas = mysql_num_rows($resultCampanhas);

        for($j = 0; $j < $qtdCampanhas; $j++){

            $arrCampanha = mysql_fetch_array($resultCampanhas);

            $id_sms_automatico = $arrCampanha['id'];

            $mensagem = $arrCampanha['mensagem'];
            $mensagem_whatsApp = $arrCampanha['mensagem_whatsApp'];

           // enviaSms($fones, $mensagem); // envia um bloco somente

	   // Para WhatsApp tem que enviar um a um

	   $array_fone = explode(',',$fones);
	   $qtd_fone = count( $array_fone );
	
	   for( $i = 0; $i < $qtd_fone ; $i++ ){
              enviaWhatsApp($array_fone[$i],$mensagem_whatsApp,$con);
	   }
	   
        }

        $fones = '';


    }else if($i == $qtdFones){

        $resultCampanhas = mysql_query($sqlCampanhas);
        $qtdCampanhas = mysql_num_rows($resultCampanhas);

        for($j = 0; $j < $qtdCampanhas; $j++){

            $arrCampanha = mysql_fetch_array($resultCampanhas);

            $id_sms_automatico = $arrCampanha['id'];

            $mensagem = $arrCampanha['mensagem'];

            $mensagem_whatsApp = $arrCampanha['mensagem_whatsApp'];

           // enviaSms($fones, $mensagem); // envia um bloco somente

           // Para WhatsApp tem que enviar um a um

           $array_fone = explode(',',$fones);
           $qtd_fone = count( $array_fone );

           for( $i = 0; $i < $qtd_fone ; $i++ ){
              enviaWhatsApp($array_fone[$i],$mensagem_whatsApp,$con);
           }


        }

        $fones = '';

        if($id_sms_automatico != '' || !empty($id_sms_automatico)){
            $sqlInsertLog = "INSERT INTO cs2.envio_sms(telefones, id_sms_automatico, data_envio)
                     VALUES('$fonesTotal', '$id_sms_automatico', NOW())";

            $resultInsert = mysql_query($sqlInsertLog);
        }

    }



}

function enviaSms($fones, $mensagem){

    // CRIA UM ARRAY COM OS PARAMETROS PARA ENVIAR PARA O CURL
    $args = array(
        'action' => 'sendsms', //retorna do add no database
        //'lgn' => $aUser[0]['celular_user'],//nao e master aqui!
        'lgn' => 'WEBCON',//nao e master aqui!
        //'pwd' => $aUser[0]['senha'],
        'pwd' => '12462056',
        'msg' => $mensagem,
        'numbers' => $fones
    );

    // TRANSFORMA O ARRAY EM PARAMETROS PARA A URL
    $field_string = http_build_query($args);
    $url = 'http://corporativo.allcancesms.com.br/app/modulo/api/index.php?' . $field_string;

    // CURL PARA ENVIO DOS SMS
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_URL,$url);
    $result = curl_exec($handle);
    //close connection
    curl_close($handle);

    $resultadoArr = json_decode($result, true);

    echo $result;

}

function enviaWhatsApp($fone, $mensagem, $con){

   // CRIA UM ARRAY COM OS PARAMETROS PARA ENVIAR PARA O CURL
   $fone = '41992806732';
   // $mensagem = 'Teste Msg';
   $args = array(
		'srv'	  => 'SET_WHA_API',
		'key'	  => 'd5f3088891e8fb174d07d3ec6eb04cc4570d8ea2',
		'phone'   => '55'.$fone,
		'sbj'	  => 'WEBCONTROL EMPRESAS',
		'message' => utf8_encode($mensagem),
		'b64' 	  => '0',
		'debug'	  => '0'
	        );

   $args_json = trim(json_encode($args, JSON_FORCE_OBJECT));

   // CURL PARA ENVIO DOS SMS
   $handle = curl_init('http://api.wscep.com/wha_json');
   curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'POST');
   curl_setopt($handle, CURLOPT_POSTFIELDS, $args_json);
   curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($handle, CURLOPT_TIMEOUT, 4);
   curl_setopt($handle, CURLOPT_POST, true);
   curl_setopt($handle, CURLOPT_USERAGENT, 'WC_SISTEMAS');
   curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($args_json)));
   $result = curl_exec($handle);
   curl_close($handle);
   $resultadoArr = json_decode($result, true);

   print_r($resultadoArr);

   $ret_resultado = $resultadoArr[0]['resultado'];
   $ret_msg = $resultadoArr[0]['msg'];
   $ret_phone = $resultadoArr[0]['phone'];
   $ret_id_msg = $resultadoArr[0]['id_msg'];
   $ret_data = $resultadoArr['data'];


   $sqlf = "INSERT INTO apoio.sms_automatico_enviado(telefone,resultado,resultado_msg,id_msg_envio,data_envio) VALUES('$ret_phone','$ret_resultado','$ret_msg','$ret_id_msg','$ret_data')"; 
   $res = mysql_query($sqlf,$con) or die("Erro SQL: $sqlf"); 
   sleep(1);
}


// PERCORE AS CAMPNHAS ENVIA SMS E SALVA O LOG PARA OS TELEFONES ENCONTRADOS

?>
