<?php

  # Autor:   Luciano Mancini
  # Modulo:  Remessa Fornecedor - Banco Bradesco
  # Finalidade: 
  #   Confirmacao dos registros enviado parra o banco para registro
  
  include("../../../validar2.php");

  global $conexao,$arquivo;
  conecex();

  $boletos = $_REQUEST['numero_boletos'];
  $data_limite = $_REQUEST['data_limite'];
  $sequencia_lote = $_REQUEST['sequencia_lote'];
  $id_controle_banco = $_REQUEST['id_controle_banco'];
  $tabela = $_REQUEST['tabela'];

  # Gravando o sequencial de registro de pagamento 
  $sql_registro = "UPDATE cs2.controle_banco 
                      SET 
                          sequencia = '$sequencia_lote',
                          ult_data = '$data_limite'
                   WHERE id = '$id_controle_banco'";
  $qry_registro = mysql_query($sql_registro,$conexao);

  if ( $tabela == 'mensalidade' ){
      
      $sql_grava_data_envio_lote = 
             "UPDATE cs2.titulos
                SET
                  data_movimentacao = '$data_limite',
                  data_gravacao_lote = '$data_limite',
                  num_lote = '$sequencia_lote'
              WHERE numboleto_bradesco IN ($boletos)";
      
  }else{

      $sql_grava_data_envio_lote = 
             "UPDATE cs2.titulos_recebafacil
                SET
                  data_gravacao_lote = '$data_limite'
              WHERE numboleto_itau IN ($boletos)";

  }

  $qry_registro = mysql_query($sql_grava_data_envio_lote,$conexao);
        
  echo 'OK';
?>