<?php

require "connect/sessao.php";
require "connect/sessao_r.php";

$id_franquia = $_SESSION['id'];

if ($id_franquia == 163 or $id_franquia == 46 or $id_franquia == 59)
    $id_franquia = 1;

$data_agenda = $_REQUEST['data_agenda'];
$data_agenda = date('Y-m-d', strtotime(str_replace('/', '-', $data_agenda)));


$assitente = $_REQUEST['assitente'];
$id_assistente_grava = $_REQUEST['id_assistente'];
$id_consultor = $_REQUEST['id_consultor'];
$hora = $_REQUEST['hora'];
$hora .= ':00';
$empresa = $_REQUEST['empresa'];
$cep = $_REQUEST['cep_agendamento'];
$endereco = $_REQUEST['endereco'];
$numero = $_REQUEST['numero'];
$bairro = $_REQUEST['bairro'];
$cidade = $_REQUEST['cidade'];
$email = $_REQUEST['email'];
$fone1 = $_REQUEST['fone1'];
$fone2 = $_REQUEST['fone2'];
$responsavel = $_REQUEST['responsavel'];
$protocolo = $_REQUEST['protocolo'];
$status_venda = $_REQUEST['status'];
$data_venda = $_REQUEST['data_venda'];
$id_assistente = $_REQUEST['id_assistente'];
$ocorrencia = $_REQUEST['ocorrencia'];
$observacao = $_REQUEST['observacao'];
$qtdCartoes = $_REQUEST['iptQtdCartoes'];
$enviarSMS = $_REQUEST['sms'];
$continua = '';
if (empty($_REQUEST['sms']) || $_REQUEST['sms'] == '') {
    $enviarSMS = 'S';
}

$codigo_cliente = $_REQUEST['codigo_cliente'];

if ($status_venda == 'S' && empty($data_venda)) {
    echo "<script>alert('Oi, tudo bem ? Poderia me informar qual foi a DATA DA FINALIZAÇÃO ?');history.back()</script>";
    exit;
}

if ($status_venda == 'S' && empty($codigo_cliente)) {
    echo "<script>alert('Ho how , e ai, blz ? Pode me informar qual foi a CODIGO do Cliente ?');history.back()</script>";
    exit;
}

if ($status_venda == 'N' && empty($data_venda)) {
    echo "<script>alert('Oi, tudo bem ? Poderia me informar qual foi a DATA DA FINALIZAÇÃO ?');history.back()</script>";
    exit;
}

if ($status_venda == 'N') {
    unset($codigo_cliente);
}

if ($status_venda == 'P') {
    unset($codigo_cliente);
    unset($data_venda);
}

if (empty($protocolo)) {

    # INCLUSAO DE UM NOVO REGISTRO
    $sql_insert = " INSERT INTO cs2.controle_comercial_visitas
					(
						data_cadastro , data_agendamento , hora_agendamento ,
						id_consultor , assitente_comercial , empresa , endereco , numero, bairro, cidade, cep, email,
						fone1 , fone2 , responsavel , id_franquia, hora_cadastro, id_assistente, ocorrencia, observacao
					)
					VALUES  
					( 
						NOW(), '$data_agenda', '$hora',
						'$id_consultor' , '$assitente' , '$empresa' , '$endereco', '$numero', '$bairro', '$cidade','$cep','$email',
						'$fone1' , '$fone2' , '$responsavel' , '$id_franquia', NOW(), '$id_assistente_grava', '$ocorrencia', '$observacao'
					)";

    $qry_insert = mysql_query($sql_insert, $con) or die("Falha ao gravar o registro.");

    $protocolo = mysql_insert_id();
    if ($protocolo == 0) {
        $qc = mysql_query('SELECT id FROM cs2.controle_comercial_visitas WHERE id_franquia = ' . $id_franquia . ' ORDER BY id DESC LIMIT 1', $con);
        $protocolo = mysql_result($qc, 0, 'id');
    }

    
    $qQuery = 'INSERT INTO '
            . 'cs2.controle_comercial_visitas_historico '
            . '(id_visita,id_consultor,data_visita,hora,status)'
            . 'VALUES'
            . '(' . $protocolo . ',' . $id_consultor . ',"' . $data_agenda . '","' . $hora . '",1)';
    $insertVisita = mysql_query($qQuery, $con) or die("Falha ao gravar o registro.");


    echo "<script>alert(\"Registro gravado com sucesso, anote o NÚMERO DA VISITA : [  $protocolo  ]!\");</script>";
    echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=clientes/a_controle_visitas.php\";>";
} else {
    # ALTERACAO DE REGISTRO
    if (isset($codigo_cliente) && $codigo_cliente <> '') {

        // Verifico se o codigo informado realmente está cadastrado na base.
        $sql_logon = "SELECT count(*) qtd FROM cs2.logon WHERE MID(logon,1,5) = '$codigo_cliente'";
        $qry_logon = mysql_query($sql_logon, $con);
        $qtd = mysql_result($qry_logon, 0, 'qtd');
        if ($qtd == 0) {
            echo "<script>alert('Desculpe, mas NAO encontrei este codigo');history.back();</script>";
            exit;
        } else {
            $sql2 = "  SELECT count(*) qtd FROM cs2.controle_comercial_visitas
							WHERE codigo_cliente = '$codigo_cliente'";
            $qry2 = mysql_query($sql2, $con);
            $qtd = mysql_result($qry2, 0, 'qtd');
            if ($qtd > 1) {
                echo "<script>alert('Desculpe, CLIENTE JA CADASTRADO ANTERIORMENTE');history.back();</script>";
                exit;
            }
        }
    }

    if (!empty($data_venda)) {
        $data_venda = substr($data_venda, 6, 4) . '-' . substr($data_venda, 3, 2) . '-' . substr($data_venda, 0, 2);
        $continua .= " , data_venda = '$data_venda' ";
    } else
        $continua .= " , data_venda = NULL ";

    if (!empty($codigo_cliente)) {
        $continua .= " , codigo_cliente = '$codigo_cliente' ";
    } else
        $continua .= " , codigo_cliente = NULL ";

    $sql_update = " UPDATE cs2.controle_comercial_visitas SET
						data_agendamento    = '$data_agenda',
						hora_agendamento    = '$hora',
						id_consultor        = '$id_consultor', 
						assitente_comercial = '$assitente', 
						empresa             = '$empresa', 
						endereco            = '$endereco',  
						bairro              = '$bairro', 
						cidade              = '$cidade',  
						cep                 = '$cep',  
						email               = '$email',  
						fone1               = '$fone1',
						fone2               = '$fone2', 
						responsavel         = '$responsavel',
						status_venda        = '$status_venda',
                                                id_assistente       = '$id_assistente',
						ocorrencia          = '$ocorrencia' ,
						qtd_cartoes         = '$qtdCartoes' ,
						enviar_sms          = '$enviarSMS' ,
						observacao          = '$observacao'
						$continua						
					WHERE id = $protocolo";
    $qry_update = mysql_query($sql_update, $con) or die("Falha ao ALTERAR o registro.");

    $qQuery = 'SELECT id FROM '
            . 'cs2.controle_comercial_visitas_historico '
            . 'WHERE'
            . ' id_consultor = ' . $id_consultor . ' AND data_visita = "' . $data_agenda . '" AND hora = "' . $hora . '" ';
    $insertVisita = mysql_query($qQuery, $con) or die("Falha ao gravar o registro.");
    if (!$insertVisita) {
        $qQuery = 'INSERT INTO '
                . 'cs2.controle_comercial_visitas_historico '
                . '(id_visita,id_consultor,data_visita,hora,status)'
                . 'VALUES'
                . '(' . $protocolo . ',' . $id_consultor . ',"' . $data_agenda . '","' . $hora . '",1)';
        $insertVisita = mysql_query($qQuery, $con) or die("Falha ao gravar o registro.");
    }

    $b_rel_assistente = $_REQUEST['b_rel_assistente'];
    $b_rel_consultor = $_REQUEST['b_rel_consultor'];
    $b_rel_datai = $_REQUEST['b_rel_datai'];
    $b_rel_dataf = $_REQUEST['b_rel_dataf'];
    $b_rel_franquia = $_REQUEST['b_rel_franquia'];

    echo "<script>alert(\"Registro ALTERADO com sucesso !\");</script>";

    echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=clientes/a_controle_visitas3.php\";>";
}
?>