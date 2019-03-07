<?php

	# Autor:   Luciano Mancini
	# M�dulo:  Remessa Fornecedor - Banco Bradesco
	# Finalidade: 
	#		Gerar o arquivo de cr�dito a serem enviados ao Banco Bradesco 
	#		para os cliente ANTECIPA��O

	include("../../../validar2.php");

	global $conexao,$arquivo;
	conecex();

        $boletos = $_REQUEST['numero_boletos'];
        $data_limite = $_REQUEST['data_limite'];
        $sequencia_lote = $_REQUEST['sequencia_lote'];
        $id_controle_banco = $_REQUEST['id_controle_banco'];

	# Gravando o sequencial de registro de pagamento 
	$sql_registro = "UPDATE cs2.controle_banco 
                            SET 
                                sequencia = '$sequencia_lote',
                                ult_data = '$data_limite'
                         WHERE id = '$id_controle_banco'";
	$qry_registro = mysql_query($sql_registro,$conexao);

        $sql_grava_data_envio_lote = 
               "UPDATE cs2.titulos_recebafacil
                  SET
                    data_gravacao_lote = '$data_limite'
                WHERE numboleto_itau IN ($boletos)";
        $qry_registro = mysql_query($sql_grava_data_envio_lote,$conexao);
        
	echo 'OK';
?>