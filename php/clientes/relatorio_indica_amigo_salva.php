<?php

require "../connect/sessao.php";
require "../connect/conexao_conecta.php";
require "../connect/funcoes.php";

// print_r($_REQUEST);exit;
	
if($_REQUEST['action']){

	switch($_REQUEST['action']){

		case 'buscaDataCadastroIndicado':

			$login = $_POST['codigoIndicado'];

			$sql = " SELECT DATE_FORMAT(c.dt_cad, '%d/%m/%Y') AS data_venda FROM cs2.cadastro c INNER JOIN base_web_control.webc_usuario u ON u.id_cadastro = c.codLoja  WHERE u.login = $login LIMIT 1";
			$result = mysql_query($sql,$con) or die($sql);

			$arrResult = mysql_fetch_assoc($result);

			echo json_encode($arrResult);

			break;

		case 'buscaCodigoExiste':


			$codigoAssociado = $_POST['codigoIndicado'];
			$idCadastro = $_POST['idCadastro'];

                        
                        $sql = "SELECT id_cadastro FROM base_web_control.webc_usuario WHERE login = '$codigoAssociado'";
                        $result = mysql_query($sql,$con) or die($sql);
                        $id_Cadastro = mysql_result($result,0,'id_cadastro');

                        
                        if ( $idCadastro == $id_Cadastro){
                            $lista = array();
                            $lista['existe'] = '1';
                            $lista['id'] = 'INDICACAO PROPRIA';
                            $arrResult = $lista;
                            
                        }else{
                            $sql = "
                            SELECT
                            IF(COUNT(*)>0,1,0) AS existe,
                            ia.id
                            FROM base_web_control.indica_amigo_log ial
                            INNER JOIN base_web_control.indica_amigo ia
                            ON ia.id = ial.id_indicacao
                            WHERE status_indicacao = 'VR'
                            AND cod_cliente_vr = $codigoAssociado";

                            $result = mysql_query($sql,$con) or die($sql);

                            $arrResult = mysql_fetch_assoc($result);
                        }
			echo json_encode($arrResult);


			break;
		
			
		case 'atualizaAgendadora':
			
				$agendador = $_POST['agendador'];
				$id = $_POST['iptIdIndicacao'];
				
				$sql = "UPDATE base_web_control.indica_amigo SET id_agendador = '$agendador' WHERE id = '$id'";
				
				$qry = mysql_query($sql,$con);

 				if($qry){
					header('Location: ../painel.php?pagina1=clientes/relatorio_indica_amigo_altera.php&idIndicacao='. $id);
				}else{
					header('Location: ../painel.php?pagina1=clientes/relatorio_indica_amigo_altera.php&idIndicacao='. $id.'&msg=2');
				}
				echo json_encode($qry);
			
				break;
				
        case 'deletaRegistro':
            
            $iptIdIndicacao = $_REQUEST['$iptIdIndicacao'];
            $iptIdIndicacaoLog = $_REQUEST['iptIdIndicacaoLog'];
            $sql = "DELETE FROM base_web_control.indica_amigo_log WHERE id = '$iptIdIndicacaoLog'";
            $qry = mysql_query($sql,$con);
            break;
	}

}


/*if ($_POST['iptIdIndicacao'] || $_POST['agendador']) {

	// 	$fatura_bonificada = $_POST['fatura_bonificar'];
	$agendador = $_POST['agendador'];
	$id = $_POST['iptIdIndicacao'];

	$sql .= "UPDATE base_web_control.indica_amigo SET id_agendador = '$agendador' WHERE id = '$id'";

	$qry = mysql_query($sql);

}*/

if ($_REQUEST['fatura_bonificar'] || $_REQUEST['agendador'] && $_REQUEST['action'] != 'atualizaAgendadora') {

	$fatura_bonificada = $_REQUEST['fatura_bonificar'];
	$agendador = $_REQUEST['agendador'];
	$id = $_REQUEST['iptIdIndicacao'];

	$sql .= "UPDATE base_web_control.indica_amigo SET id_agendador = '$agendador' WHERE id = '$id'";

	$qry = mysql_query($sql);

	if($qry){
		header('Location: ../painel.php?pagina1=clientes/relatorio_indica_amigo_altera.php&idIndicacao='. $id);
	}else{
		header('Location: ../painel.php?pagina1=clientes/relatorio_indica_amigo_altera.php&idIndicacao='. $id.'&msg=2');
	}

}