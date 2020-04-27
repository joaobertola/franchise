<?php
require "connect/sessao.php";
require "connect/sessao_r.php";

$id_franquia = $_SESSION['id'];

// echo "<pre>";
// print_r($_REQUEST);
// die();

if ( $id_franquia == 163 or $id_franquia == 46 or $id_franquia == 59 )
    $id_franquia = 1;

$data_agenda = $_REQUEST['data_agenda'];
$data_agenda = substr($data_agenda,6,4).'-'.substr($data_agenda,3,2).'-'.substr($data_agenda,0,2);
 
$id_funcionario         = $_REQUEST['id_funcionario_conferencia'];
$assitente              = $_REQUEST['assitente'];
$id_assistente_grava    = $_REQUEST['id_assistente_grava'];
$id_consultor           = $_REQUEST['id_consultor'];
$hora                   = $_REQUEST['hora'];
$hora                  .= ':00';
$empresa                = mysql_real_escape_string($_REQUEST['empresa']);
$endereco               = $_REQUEST['endereco'];
$bairro                 = $_REQUEST['bairro'];
$cidade                 = $_REQUEST['cidade'];
$ponto_referencia       = $_REQUEST['ponto_referencia'];
$fone1                  = $_REQUEST['fone1'];
$fone2                  = $_REQUEST['fone2'];
$responsavel            = $_REQUEST['responsavel'];
$protocolo              = $_REQUEST['protocolo'];
$status_venda           = $_REQUEST['status'];
$data_venda             = $_REQUEST['data_venda'];
$id_assistente          = $_REQUEST['id_assistente_grava'];
$ocorrencia             = $_REQUEST['ocorrencia'];
$tipo                   = $_REQUEST['tipo'];
$codigo_cliente         = $_REQUEST['codigo_cliente'];
$cep                    = $_REQUEST['cep_agendamento'];
$uf                     = $_REQUEST['uf'];
$numero                 = $_REQUEST['numero'];
$observacao             = $_REQUEST['observacao'];
$qtdCartao              = $_REQUEST['iptQtdCartoes'];
$enviarSMS              = $_REQUEST['sms'];
$id_concorrente         = $_REQUEST['id_concorrente'];
$flagVizinhos           = $_REQUEST['flagVizinhos'];

$resultado_visitou      = $_REQUEST['resultado_visitou'] == 'on' ? 1 : 0;
$resultado_demonstrou   = $_REQUEST['resultado_demonstrou']== 'on' ? 1 : 0;
$resultado_levousuper   = $_REQUEST['resultado_levousuper'] == 'on' ? 1 : 0;
$resultado_ligougerente = $_REQUEST['resultado_ligougerente'] == 'on' ? 1 : 0;
$resultado_cartaovisita = $_REQUEST['resultado_cartaovisita'] == 'on' ? 1 : 0;
$resultado_mousepad     = $_REQUEST['resultado_mousepad'] == 'on' ? 1 : 0;
$resultado_paralelo     = $_REQUEST['paralelo_sistemas'] == 'on' ? 1 : 0;

$filho_cadcli           = $_REQUEST['filho_cadcli'] == 'on' ? 1 : 0;
$filho_cadpro           = $_REQUEST['filho_cadpro'] == 'on' ? 1 : 0;
$filho_frentecx         = $_REQUEST['filho_frentecx'] == 'on' ? 1 : 0;
$filho_emis_bol         = $_REQUEST['filho_emis_bol'] == 'on' ? 1 : 0;
$filho_conscred         = $_REQUEST['filho_conscred'] == 'on' ? 1 : 0;
$filho_parc_deb         = $_REQUEST['filho_parc_deb'] == 'on' ? 1 : 0;
$filho_negativa         = $_REQUEST['filho_negativa'] == 'on' ? 1 : 0;
$filho_listamark        = $_REQUEST['filho_listamark'] == 'on' ? 1 : 0;

$triplicar_venda        = $_REQUEST['triplicar_venda'] == '1' ? 1 : 2;
$cad_cliente            = $_REQUEST['cad_cliente'] == '1' ? 1 : 2;
$prod_estoque           = $_REQUEST['prod_estoque'] == '1' ? 1 : 2;
$boletos                = $_REQUEST['boletos'] == '1' ? 1 : 2;
$nota_fiscal            = $_REQUEST['nota_fiscal'] == '1' ? 1 : 2;
$site                   = $_REQUEST['site'] == '1' ? 1 : 2;
$frente_caixa           = $_REQUEST['frente_caixa'] == '1' ? 1 : 2;
$email                  = $_REQUEST['email'];
$agendarFuturo          = $_REQUEST['agendarFuturo'] == 'on' ? 'S' : 'N';

if ( $resultado_demonstrou == 1 ){
    $array_filho =
        $filho_cadcli.';'.
        $filho_cadpro.';'.
        $filho_frentecx.';'.
        $filho_emis_bol.';'.
        $filho_conscred.';'.
        $filho_parc_deb.';'.
        $filho_negativa.';'.
        $filho_listamark;
}else{
    $array_filho = '0;0;0;0;0;0;0;0';
}

if ( $status_venda == 'S' && empty($data_venda) ){
    echo "<script>alert('Oi, tudo bem ? Poderia me informar qual foi a DATA DA FINALIZAÇÃO ?');history.back()</script>";
    exit;
}

if ( $status_venda == 'S' && empty($codigo_cliente) ){
    echo "<script>alert('Ho how , e ai, blz ? Pode me informar qual foi a CODIGO do Cliente ?');history.back()</script>";
    exit;
}

if ( $status_venda == 'N' && empty($data_venda) ){
    echo "<script>alert('Oi, tudo bem ? Poderia me informar qual foi a DATA DA FINALIZAÇÃO ?');history.back()</script>";
    exit;
}

if ( $status_venda == 'N' ){
    unset($codigo_cliente);
}

if ( $status_venda == 'P' ){
    unset($codigo_cliente);
    unset($data_venda);
}


$triplicar_venda = $_REQUEST['triplicar_venda'] == '1' ? 1 : 2;
$cad_cliente     = $_REQUEST['cad_cliente'] == '1' ? 1 : 2;
$prod_estoque    = $_REQUEST['prod_estoque'] == '1' ? 1 : 2;
$boletos         = $_REQUEST['boletos'] == '1' ? 1 : 2;
$nota_fiscal     = $_REQUEST['nota_fiscal'] == '1' ? 1 : 2;
$site            = $_REQUEST['site'] == '1' ? 1 : 2;

if(empty($_REQUEST['sms']) || $_REQUEST['sms'] == ''){
    $enviarSMS = 'S';
}

if ( empty($protocolo) ){
    # INCLUSAO DE UM NOVO REGISTRO
    $sql_insert = " INSERT INTO cs2.controle_comercial_visitas
                        (
                            data_cadastro , data_agendamento , hora_agendamento , tipo,
                            id_consultor , assitente_comercial , empresa , endereco , 
                            fone1 , fone2 , responsavel , id_franquia, hora_cadastro, id_assistente, ocorrencia,
                            triplicar_vendas, cad_cliente, prod_estoque, boletos, nota_fiscal, site, frente_caixa,
                            bairro, cidade, ponto_referencia, cep, numero, uf, observacao, email, agendar_futuro,
                            vizinhos
                        )
                        VALUES  
                        ( 
                            NOW(), '$data_agenda', '$hora', '$tipo',
                            '$id_consultor' , '$assitente' , '$empresa' , '$endereco',
                            '$fone1' , '$fone2' , '$responsavel' , '$id_franquia', NOW(), 
                            '$id_assistente_grava', '$ocorrencia',
                            '$triplicar_venda','$cad_cliente','$prod_estoque','$boletos','$nota_fiscal','$site','$frente_caixa',
                            '$bairro','$cidade','$ponto_referencia','$cep','$numero','$uf', '$observacao', '$email', '$agendarFuturo',
                            '$flagVizinhos'
                        )";
    
    

    $qry_insert = mysql_query( $sql_insert,$con) or die("Falha ao gravar o registro 001.");

    $protocolo = trim($_REQUEST["protocolo"]);

    if ( $protocolo == '' )
        $protocolo = mysql_insert_id($con);

    // Grava VISITA LOG

    if ( $id_funcionario == '' )
        $id_funcionario = 0;

    $insert_visita_log = " INSERT INTO cs2.visitas_log
                        (
                            id_funcionario , id_visita , data_hora
                        )
                        VALUES  
                        ( 
                            $id_funcionario,'$protocolo',NOW()
                        )";
    $qry_insert_visita_log = mysql_query( $insert_visita_log,$con) or die("Falha ao gravar o registro 002. : $insert_visita_log ");

    mysql_insert_id($con);

    // Fim Grava VISITA LOG
    
        echo "<script>alert(\"Registro gravado com sucesso, anote o NÚMERO DA VISITA : [  $protocolo  ]!\");</script>";
    echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=clientes/a_controle_visitas1aa.php\";>";

}else{
    # ALTERACAO DE REGISTRO

//    if ( $codigo_cliente <> '' ){
//
//        // Verifico se o codigo informado realmente está cadastrado na base.
//        $sql_logon = "SELECT count(*) qtd FROM cs2.logon WHERE MID(logon,1,5) = '$codigo_cliente'";
//        $qry_logon = mysql_query($sql_logon,$con);
//        $qtd = mysql_result($qry_logon,0,'qtd');
//        if ( $qtd == 0 ){
//            echo "<script>alert('Desculpe, mas NAO encontrei este codigo');history.back();</script>";
//            exit;
//        }else{
//            $sql2 = "  SELECT count(*) qtd FROM cs2.controle_comercial_visitas
//                                                    WHERE codigo_cliente = '$codigo_cliente'";
//            $qry2 = mysql_query($sql2,$con);
//            $qtd = mysql_result($qry2,0,'qtd');
//            if ( $qtd > 1 ){
//                echo "<script>alert('Desculpe, CLIENTE JA CADASTRADO ANTERIORMENTE');history.back();</script>";
//                exit;
//            }
//        }
//    }

    if ( !empty($data_venda) ){
        $data_venda = substr($data_venda,6,4).'-'.substr($data_venda,3,2).'-'.substr($data_venda,0,2);
        $continua .= " , data_venda = '$data_venda' ";
    }else
        $continua .= " , data_venda = NULL ";

    if ( !empty($codigo_cliente) ){
        $continua .= " , codigo_cliente = '$codigo_cliente' ";
    }else
        $continua .= " , codigo_cliente = NULL ";

    $endereco = mysql_real_escape_string($endereco);
    $empresa = mysql_real_escape_string($empresa);
    $ponto_referencia = mysql_real_escape_string($ponto_referencia);
    $observacao = mysql_real_escape_string($observacao);
    $responsavel = mysql_real_escape_string($responsavel);

    $sql_update = " UPDATE cs2.controle_comercial_visitas SET
                    data_agendamento       = '$data_agenda',
                    hora_agendamento       = '$hora',
                    id_consultor           = '$id_consultor', 
                    assitente_comercial    = '$assitente', 
                    empresa                = '$empresa',
                    cep                    = '$cep',
                    endereco               = '$endereco',
                    uf                     = '$uf',
                    bairro                 = '$bairro',
                    cidade                 = '$cidade',
                    ponto_referencia       = '$ponto_referencia',
                    fone1                  = '$fone1',
                    fone2                  = '$fone2', 
                    responsavel            = '$responsavel',
                    status_venda           = '$status_venda',
                    numero                 = '$numero', 
                    id_assistente          = '$id_assistente',
                    ocorrencia             = '$ocorrencia',
                    resultado_visitou      = '$resultado_visitou',
                    resultado_demonstrou   = '$resultado_demonstrou',
                    resultado_levousuper   = '$resultado_levousuper',
                    resultado_ligougerente = '$resultado_ligougerente',
                    resultado_cartaovisita = '$resultado_cartaovisita',
                    resultado_mousepad     = '$resultado_mousepad',
                    triplicar_vendas       = '$triplicar_venda',
                    cad_cliente            = '$cad_cliente',
                    prod_estoque           = '$prod_estoque',
                    boletos                = '$boletos',
                    nota_fiscal            = '$nota_fiscal',
                    site                   = '$site',
                    frente_caixa           = '$frente_caixa',
                    observacao             = '$observacao',
                    qtd_cartoes            = '$qtdCartao',
                    email                  = '$email',
                    enviar_sms             = '$enviarSMS',
                    agendar_futuro         = '$agendarFuturo',
                    filhos_visitou         = '$array_filho',
                    paralelo_sistemas      = '$resultado_paralelo',
                    id_concorrente         = '$id_concorrente',
                    vizinhos               = '$flagVizinhos'
                    $continua
                WHERE id = $protocolo";

    $qry_update = mysql_query( $sql_update,$con) or die("Falha ao ALTERAR o registro 003. $sql_update");

    $b_rel_assistente = $_REQUEST['b_rel_assistente'];
    $b_rel_consultor  = $_REQUEST['b_rel_consultor'];
    $b_rel_datai      = $_REQUEST['b_rel_datai'];
    $b_rel_dataf      = $_REQUEST['b_rel_dataf'];
    $b_rel_franquia   = $_REQUEST['b_rel_franquia'];

    // Grava VISITA LOG

    // $update_visita_log = " UPDATE cs2.visitas_log SET id_funcionario = {$id_funcionario} , id_visita = {$_REQUEST["protocolo"]}, data_hora = NOW()";
    // $qry_update_visita_log = mysql_query( $update_visita_log,$con) or die("Falha ao gravar o registro.");
    // mysql_insert_id($con);

    if ( trim($id_funcionario) == '' )
        $id_funcionario = 0;
    
    $insert_visita_log = " INSERT INTO cs2.visitas_log
                        (
                            id_funcionario , id_visita , data_hora
                        )
                        VALUES  
                        ( 
                            $id_funcionario,{$_REQUEST["protocolo"]},NOW()
                        )";
    $qry_insert_visita_log = mysql_query( $insert_visita_log,$con) or die("Falha ao gravar o registro 004. $insert_visita_log");

    mysql_insert_id($con);

    // Fim Grava VISITA LOG

    echo "<script>alert(\"Registro ALTERADO com sucesso !\");</script>";

    echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=clientes/a_controle_visitas2.php\";>";

}

?>