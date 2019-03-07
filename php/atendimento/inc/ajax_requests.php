<?php

    /**
     * Incluindo classes
     */
    require_once ( '../classes/DbConnection.class.php' );
    
    /**
    * Instancia Conexao
    */
   $objConexao = new DbConnection();
   
   $action = $_REQUEST['action'];
   
   /* caso nao exista action, lusta atendimntos*/
   if(!$action) $action = 'lista_atendimentos';
   
   /* se for listar atendimentos */
   if($action == 'lista_atendimentos'){

        try {
            /*
             *declaracoa de variaveis
             **/
            $aOcorrencias = array();
            $aOcorrenciasNoFilter = array();
            $data = array();
            $requestData = $_REQUEST;
            
            /**
             * Comando SQL
             */
            $sql =  "
                SELECT
                    orat.id,
                    orat.protocolo,
                    orat.datahora,
                    orat.id_depto_solicitante,
                    orat.id_solicitante,
                    orat.nome_solicitante,
                    orat.subdepto_destino,
                    orat.assunto,
                    orat.cod_cliente,
                    orat.razao_cliente,
                    orat.status,
                    
                    depto.nome_departamento as depto_solicitante,
                    subdeptoD.nome_subdepartamento as depto_destino
                    
                FROM
                    cs2.ordem_atendimento AS orat
                LEFT JOIN
                    cs2.ordem_atendimento_depto AS depto
                ON
                    orat.id_depto_solicitante = depto.id
                    
                LEFT JOIN
                    cs2.ordem_atendimento_subdepto AS subdepto
                ON
                    orat.id_depto_solicitante = subdepto.id
                LEFT JOIN
                     cs2.ordem_atendimento_subdepto AS subdeptoD
                ON
                     orat.subdepto_destino = subdeptoD.id
            ";
            
            /* declarando var */
            $aCamposValores = array ();
            
            
            /* FILTRO cod cliente */
            if($_REQUEST['codCliente']){
                
                $sql .=  "
                   WHERE cod_cliente = :cod_cliente
                ";
                
                $aCamposValores [ ':cod_cliente' ] = $_REQUEST['codCliente'];
                
            }
            
            /* FILTRO busca */
            if($_REQUEST['tipoBuscar']){
                
                if($_REQUEST['tipoBuscar'] == 'codigo'){
                    $sql .=  "WHERE cod_cliente = :cod_cliente";
                    $aCamposValores [ ':cod_cliente' ] = $_REQUEST['dadoBuscar'];
                }
                
                if($_REQUEST['tipoBuscar'] == 'depto'){
                    $sql .=  "WHERE subdepto_destino = :idSubdepto";
                    $aCamposValores [ ':idSubdepto' ] = $_REQUEST['buscarDepartamento'];
                }
                
                if($_REQUEST['tipoBuscar'] == 'empresa'){
                    $sql .=  "WHERE razao_cliente LIKE :empresa";
                    $aCamposValores [ ':empresa' ] =  '%' . $_REQUEST['dadoBuscar'] . '%';
                }               
            }
                        
            $orderBy = 'orat.datahora';
            
            /**
            * Adiciona Colulas de ordena��o e a quantidade de resgistros que deve retornar por p�gina 
            */
            $sql2 = "
                ORDER BY 
                   " . $orderBy . "
                   " . $requestData['order'][0]['dir'] . "  
                LIMIT 
                   " . $requestData['start'] . " ," . $requestData['length'] . "
            ";
        
            /* conslta para contar quantos registros há */
            $pdoCount = $objConexao->pdo->prepare( $sql );
            $pdoCount->execute($aCamposValores);
            $aOcorrenciasNoFilter = $pdoCount->fetchAll(PDO::FETCH_ASSOC );
            
            $sqlFinal = $sql . $sql2;
            /**
             * Preparando SQL
             */
            
            $pdo = $objConexao->pdo->prepare( $sqlFinal );
            
            /*
             * Executa consulta SQL
             */		
            if($pdo->execute($aCamposValores)){
                
                $aOcorrencias = $pdo->fetchAll(PDO::FETCH_ASSOC );
                
            }else {
                /**
                 * Para C�digo e exibe mensagem
                 */
                die( "Erro ao Listar as Ocorrências" );
            }
            
            /**
             * Criando Vari�veis contadoras
             */   
            $totalData = count( $aOcorrencias ) ;
            
            /**
            * Quando existe um par�metro de busca, ent�o n�s temos que modificar N�mero total de linhas filtradas de acordo com resultado da pesquisa.
            */
           $totalFiltered = count( $aOcorrenciasNoFilter ) ;
            
            /**
            * Percorrendo array de produtos
            */
           foreach ( $aOcorrencias as $index => $row ) {
               /**
                * Cria array retuntado
                */
               $nestedData = array();
               
               if($row["status"] == 'P'){
                    $status = "<span class='red'>Pendente</span>";
               } else if($row["status"] == 'A'){
                    $status = "<span class='blue'>Andamento</span>";
               } else if($row["status"] == 'R'){
                    $status = "<span class='grey'>Resolvido</span>";
               }
               
               
               /**
                * Colunas
                */
               $nestedData[] = $row["protocolo"];
               //$nestedData[] = $row["datahora"];
               $nestedData[] = date("j/m/Y H:j:s", strtotime($row["datahora"]));
               $nestedData[] = $row["depto_solicitante"];
               $nestedData[] = limitChars($row["nome_solicitante"], 15);
               $nestedData[] = $row["cod_cliente"];
               $nestedData[] = limitChars($row["razao_cliente"], 20);
               $nestedData[] = $row["depto_destino"];
               $nestedData[] = $status;
        
        
        
               $acao = '
                    <span class="txtcenter"><a class="aDetalhes"><span class="glyphicon glyphicon-eye-open" title="Ver detalhes"></span></a>
                    <a class="aImprimir"><span class="glyphicon glyphicon-print" title="Imprimir Ordem de Atendimento"></span></a></span>
               ';
        
               $nestedData[] = $acao;
        
               $data[] = $nestedData;
           }
        
           $json_data = array(
               "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
               "recordsTotal" => intval($totalData), // total number of records
               "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
               "data" => $data			   // total data array
           );
        
           echo json_encode($json_data);  // send data as json format        
            
        } catch ( Exception $e ) { 
            echo '<option>N&aacute;o foi poss&iacute;vel selecionar as ocorrencias.</option>';
            //Erros::mensagemErro ( $e );
        }
    
   } else if($action == 'buscaClienteById'){
        
        $idCliente = $_POST['idCliente'];
        $result = array();
        /**
        * Comando SQL
        */
        $sql =  "
            SELECT
                cad.codloja,
                cad.razaosoc,
                cad.cpfcnpj_doc
            FROM
                cs2.cadastro AS cad
            WHERE
                cad.codloja = :codloja
        ";
        
        /*
         *campos variaveis
         **/
        
        $aCamposValores =  array (
            ':codloja' => $idCliente
            );
        
        $pdo = $objConexao->pdo->prepare( $sql );
        
        if($pdo->execute($aCamposValores)){
            
            $result = $pdo->fetchAll(PDO::FETCH_ASSOC );
            
        } else {
            /**
             * Para C�digo e exibe mensagem
             */
            //die( "Cliente Não Encontrado" );
            $result['mensagem'] = 'Erro';
        }
        /*
         *caso nao tenha encontrado o cliente
         *
         **/
        if(count($result) == 0){
            $result['mensagem'] = 'Cliente Não Econtrado.';
            $result['idcliente'] = $idCliente;
        }
        
        echo json_encode($result);
    
   } else if($action == 'solicitanteAutocomplete'){
        
        try {
            /**
             * Pegando dados enviados
             */
            $keyword = $_POST['keyword'];
            $solicitante_id =  $_POST['solicitante_id'];
            $list_solicitante_id =  $_POST['list_solicitante_id'];
            
            /**
             * Verifica parâmentro de busca
             */
            if ( $keyword  ) {
                /**
                 * Declaração de variáveis
                 */
                //$aBancos = array ();
                $result = array();
                
                
                
                /**
                * Comando SQL
                */
                $sql =  "
                    SELECT 
                        fun.id,
                        fun.nome
                    FROM 
                        cs2.funcionario AS fun
                    WHERE
                        fun.nome LIKE :keyword
                    ORDER BY
                        fun.nome
                    ASC 
                    LIMIT 0, 10
                ";
                
                $pdo = $objConexao->pdo->prepare( $sql );
                
                /*
                 *campos variaveis
                 **/
                
                $aCamposValores =  array (
                    ':keyword' => '%'.$keyword.'%'
                    );
                
                $pdo->execute($aCamposValores);
                    
                $result = $pdo->fetchAll(PDO::FETCH_ASSOC );
                   // print_r($result);
                 echo json_encode($result);

            /**
             * Se não veio parâmetro
             */
            } else {
                /**
                 * Cria li de mensagem de erro
                 */
                echo '';
            }
            /**
             * Capturando exceção
             */
        } catch ( Exception $e ) {
            /**
             * Mensagem de erro
             */
            Erros::mensagemErro ( $e );
        }
    
    
    } else if($action == 'registraOrdem'){
    
        //pegando serialized params (string) in array
        parse_str($_POST['formData'], $params);
    
        //print_r($params);
        
        try {

            /**
             * Declaração de variáveis
             */
            $result = array();
            
            /**
            * Comando SQL
            */
            //INSERT INTO table_name
            //VALUES (value1,value2,value3,...);
            $sql =  "
                INSERT INTO
                    cs2.ordem_atendimento (
                          protocolo
                        , datahora		    
                        , id_depto_solicitante
                        , id_solicitante
                        , nome_solicitante
                        , subdepto_destino
                        , assunto
                        , descricao
                        , cod_cliente
                        , razao_cliente
                        , solicitado_nfe
                        , solicitado_nfce
                        , solicitado_cupomfiscal
                        , solicitado_nfse
                        , solicitado_cte
                        , solicitado_mdfe
                    ) VALUES (
                          :protocolo
                        , :datahora		    
                        , :id_depto_solicitante
                        , :id_solicitante
                        , :nome_solicitante
                        , :subdepto_destino
                        , :assunto
                        , :descricao
                        , :cod_cliente
                        , :razao_cliente
                        , :solicitado_nfe
                        , :solicitado_nfce
                        , :solicitado_cupomfiscal
                        , :solicitado_nfse
                        , :solicitado_cte
                        , :solicitado_mdfe
                    )	
            ";
            
            $pdo = $objConexao->pdo->prepare( $sql );
            
            /*
             *campos variaveis
             **/
            
            $idSolicitante = (isset($params['iptIdSolicitante'])) ? $params['iptIdSolicitante'] : 0;
            $nfe = ($params['iptNfe']) ? 1 : 0;
            $nfce = ($params['iptNfce']) ? 1 : 0;
            $cupom = ($params['iptCupom']) ? 1 : 0;
            $nfse = ($params['iptNfse']) ? 1 : 0;
            $cte = ($params['iptCte']) ? 1 : 0;
            $mdfe = ($params['iptMDFe']) ? 1 : 0;

            
            $aCamposValores =  array (
                ':protocolo'                => $params['iptProtocoloAt']
                , ':datahora'               => $params['iptDataHora']
                , ':id_depto_solicitante'   => $params['iptDeptoSolicitante']
                , ':id_solicitante'         => $idSolicitante
                , ':nome_solicitante'       => $params['iptSolicitante']
                , ':subdepto_destino'       => $params['iptDestino']
                , ':assunto'                => $params['iptAssunto']
                , ':descricao'              => $params['iptObservacoes']
                , ':cod_cliente'            => $params['iptIdCliente']
                , ':razao_cliente'          => $params['iptRazaoCliente']
                , ':solicitado_nfe'         => $nfe
                , ':solicitado_nfce'        => $nfce
                , ':solicitado_cupomfiscal' => $cupom
                , ':solicitado_nfse'        => $nfse
                , ':solicitado_cte'         => $cte
                , ':solicitado_mdfe'        => $mdfe
                );
            
            if ( $pdo->execute( $aCamposValores ) ) {
                $result['lastId'] = $objConexao->pdo->lastInsertId();
                $result['status'] = 'ok';
            } else {
		/**
		 * Mensagem de erro
		 */
		$result['status'] = 'erro';
	    }
            
            //
                
            //$result = $pdo->fetchAll(PDO::FETCH_ASSOC );
               // print_r($result);
             echo json_encode($result);
        
            /**
             * Capturando exceção
             */
        } catch ( Exception $e ) {
            /**
             * Mensagem de erro
             */
            Erros::mensagemErro ( $e );
        }
   
   } else if($action == 'buscaAtByProtocol'){
        
        $protocolo = $_POST['protocolo'];
        $result = array();
        /**
        * Comando SQL
        */
        $sql =  "
            SELECT
                    orat.id,
                    orat.protocolo,
                    orat.datahora,
                    orat.id_depto_solicitante,
                    orat.id_solicitante,
                    orat.nome_solicitante,
                    orat.subdepto_destino,
                    orat.assunto,
                    orat.cod_cliente,
                    orat.razao_cliente,
                    orat.status,
                    orat.descricao,
                    orat.solicitado_nfe,
                    orat.solicitado_nfce,
                    orat.solicitado_cupomfiscal,
                    orat.solicitado_nfse,
                    orat.solicitado_cte,
                    orat.solicitado_mdfe,
                    
                    depto.nome_departamento as depto_solicitante,
                    subdeptoD.nome_subdepartamento as depto_destino
                FROM
                    cs2.ordem_atendimento AS orat
                LEFT JOIN
                    cs2.ordem_atendimento_depto AS depto
                ON
                    orat.id_depto_solicitante = depto.id
                    
                LEFT JOIN
                    cs2.ordem_atendimento_subdepto AS subdepto
                ON
                    orat.id_depto_solicitante = subdepto.id
                LEFT JOIN
                     cs2.ordem_atendimento_subdepto AS subdeptoD
                ON
                     orat.subdepto_destino = subdeptoD.id
                WHERE
                    orat.protocolo = :protocolo
        ";
        
        /*
         *campos variaveis
         **/
        
        $pdo = $objConexao->pdo->prepare( $sql );
        
        $aCamposValores =  array (
            ':protocolo' => $protocolo
            );
        
        
        
        if($pdo->execute($aCamposValores)){
            
            $result = $pdo->fetchAll(PDO::FETCH_ASSOC );
            
        } else {
            /**
             * Para C�digo e exibe mensagem
             */
            //die( "Cliente Não Encontrado" );
            $result['mensagem'] = 'Erro';
        }
        /*
         *caso nao tenha encontrado o cliente
         *
         **/
        if(count($result) == 0){
            $result['mensagem'] = 'Atendimento Não Econtrado.';
        }
        
        echo json_encode($result);
     
   }  else if($action == 'alteraStatus'){
    
        //echo json_encode($_POST);
        $novoStatus = $_POST['novoStatus'];
        $protocolo = $_POST['protocolo'];
        
        $result = array();
        /**
        * Comando SQL
        */
        $sql =  "
            UPDATE
		cs2.ordem_atendimento
            SET
                status = :novoStatus
            WHERE
                protocolo = :protocolo
        ";
        
        /*
         *campos variaveis
         **/
        
        $pdo = $objConexao->pdo->prepare( $sql );
        
        $aCamposValores =  array (
            ':novoStatus' => $novoStatus,
            ':protocolo' => $protocolo
            );
        
        
        
        if($pdo->execute($aCamposValores)){
            
            $result['status'] = 'ok';
            
        } else {
            /**
             * Para C�digo e exibe mensagem
             */
            $result['status'] = 'Erro';
        }
        
        echo json_encode($result);
        
   } else if($action == 'baixarAtendimento'){
        
        //echo json_encode($_POST);
        
        $responsavel = $_POST['iptResponsavelBaixa'];
        $descricao = $_POST['iptDescricaoBaixa'];
        $protocolo = $_POST['iptProtocoloAt'];
        $depto = $_POST['iptDeptoResp'];
        
        $result = array();
        /**
        * Comando SQL
        */
        $sql =  "
                INSERT INTO
                    cs2.ordem_atendimento_log (
                          protocolo_atendimento
                        , datahora		    
                        , acao
                        , responsavel
                        , descricao
                        , depto_de
                        , depto_para
                    ) VALUES (
                          :protocolo
                        , :datahora		    
                        , :acao
                        , :responsavel
                        , :descricao
                        , :depto_de
                        , :depto_para
                    )	
            ";
        
        /*
         *campos variaveis
         **/
        
        $pdo = $objConexao->pdo->prepare( $sql );
        
        $aCamposValores =  array (
            ':protocolo' => $protocolo,
            ':datahora' => date('Y-m-d H:j:s'),
            ':acao' => 'B',
            ':responsavel' => $responsavel,
            ':descricao' => $descricao,
            ':depto_de' => $depto,
            ':depto_para' => '0',
            );
        
        
        /* grava baixa */
        if($pdo->execute($aCamposValores)){
            /* update no registro do atendiento */
            $sql2 =  "
                    UPDATE
                        cs2.ordem_atendimento
                    SET
                        status = :novoStatus
                    WHERE
                        protocolo = :protocolo
                ";
                
                /*
                 *campos variaveis
                 **/
                
                $pdo2 = $objConexao->pdo->prepare( $sql2 );
                
                $aCamposValores2 =  array (
                    ':novoStatus' => 'R',
                    ':protocolo' => $protocolo
                    );
                
                
                
                $pdo2->execute($aCamposValores2);
            
            
                $result['status'] = 'ok';
            
        } else {
            /**
             * Para C�digo e exibe mensagem
             */
            $result['status'] = 'Erro';
        }
        
        echo json_encode($result);
    
    
    } else if($action == 'redirAtendimento'){
        
        //echo json_encode($_POST);
        //Object {iptProtocoloAt: "2015.914.1047.1754", iptDeptoResp: "5", iptResponsavelRedir: "Simeia", iptDestino: "4", iptDescricaoRedir: "Motivo"}
        
        $responsavel = $_POST['iptResponsavelRedir']; /* pessoa resp */
        $descricao = $_POST['iptDescricaoRedir'];
        $protocolo = $_POST['iptProtocoloAt'];
        $deptoFrom = $_POST['iptDeptoResp'];
        $deptoTo = $_POST['iptDestino'];
        
        $result = array();
        /**
        * Comando SQL
        */
        $sql =  "
                INSERT INTO
                    cs2.ordem_atendimento_log (
                          protocolo_atendimento
                        , datahora		    
                        , acao
                        , responsavel
                        , descricao
                        , depto_de
                        , depto_para
                    ) VALUES (
                          :protocolo
                        , :datahora		    
                        , :acao
                        , :responsavel
                        , :descricao
                        , :depto_de
                        , :depto_para
                    )	
            ";
        
        /*
         *campos variaveis
         **/
        
        $pdo = $objConexao->pdo->prepare( $sql );
        
        $aCamposValores =  array (
            ':protocolo' => $protocolo,
            ':datahora' => date('Y-m-d H:j:s'),
            ':acao' => 'E',
            ':responsavel' => $responsavel,
            ':descricao' => $descricao,
            ':depto_de' => $deptoFrom,
            ':depto_para' => $deptoTo,
            );
        
        
        /* grava redir */
        if($pdo->execute($aCamposValores)){
            /* update no registro do atendiento do novo depto destino */
            $sql2 =  "
                    UPDATE
                        cs2.ordem_atendimento
                    SET
                        status = :novoStatus,
                        subdepto_destino = :deptoTo
                    WHERE
                        protocolo = :protocolo
                ";
                
                /*
                 *campos variaveis
                 **/
                
                $pdo2 = $objConexao->pdo->prepare( $sql2 );
                
                $aCamposValores2 =  array (
                    ':novoStatus' => 'P',
                    ':deptoTo' => $deptoTo,
                    ':protocolo' => $protocolo
                    );
                
                
                
                $pdo2->execute($aCamposValores2);
            
            
                $result['status'] = 'ok';
            
        } else {
            /**
             * Para C�digo e exibe mensagem
             */
            $result['status'] = 'Erro';
        }
        
        echo json_encode($result);
        
    }
    
function limitChars($oldText, $limit){

    $tamanho = strlen($oldText);
    
    $tamanho <= $limite ? $newText : $newText = trim(substr($oldText, 0, $limit))."...";
    
    return $newText;
}

?>