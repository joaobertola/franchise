<?php

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
$fones = '41996827867,41992806732,41995539206,41996766198,41997955682,41998144010,41996693114,41988063886,41997080448,41988522123,41999364298,41998329112,41999840314,41997503976,41997816906,41992539730,41995011597,41987877725,41988405036,41996149989,41998762362';
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

            enviaSms($fones, $mensagem);
        }

        $fones = '';


    }else if($i == $qtdFones){

        $resultCampanhas = mysql_query($sqlCampanhas);
        $qtdCampanhas = mysql_num_rows($resultCampanhas);

        for($j = 0; $j < $qtdCampanhas; $j++){

            $arrCampanha = mysql_fetch_array($resultCampanhas);

            $id_sms_automatico = $arrCampanha['id'];

            $mensagem = $arrCampanha['mensagem'];

            enviaSms($fones, $mensagem);
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

// PERCORE AS CAMPNHAS ENVIA SMS E SALVA O LOG PARA OS TELEFONES ENCONTRADOS

?>