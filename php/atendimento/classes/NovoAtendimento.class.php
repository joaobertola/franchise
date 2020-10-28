<?php

class NovoAtendimento {
    
    //private 
    
    function __construct(){}
    
    public function buscaCliente($idCliente){
        try {
	    /**
	     * Instancia Conexao
	     */
	    $objConexao = new DbConnection();	    
	    /**
	     * Comando SQL
	     */
	    $sql =  "  
		SELECT
                cad.codloja,
                cad.razaosoc,
                cad.uf,
                cad.cidade,
                cad.bairro,
                cad.end,
                cad.numero,
                cad.complemento,
                cad.cep,
                cons.nome AS nome_consultor,
                cad.fone,
                cad.fax,
                cad.email,
                cad.email2,
                cad.id_franquia,
                cad.dt_cad,
                cad.celular,
                cad.fone_res,
                cad.socio1,
                cad.socio2,
                cad.cpfsocio1,
                cad.cpfsocio2,
                cad.id_agendador,
                cad.ramo_atividade,
                cad.cpfcnpj_doc,
                cad.nfe,
                cad.nfce,
                cad.cte,
                cad.nfse,
                cad.cfiscal,
                cad.mdfe,
                fran.fantasia AS nome_franquia
            FROM
                cs2.cadastro AS cad
            LEFT JOIN
                cs2.consultores_assistente AS cons
            ON
                cad.id_agendador = cons.id
            LEFT JOIN
                cs2.franquia AS fran
            ON
                cad.id_franquia = fran.id
            WHERE
                cad.codloja = :codloja
	    ";
	    /**	
	     * Declaracao de variavel
	     */
	    $aResult = array();
            /*
            *campos variaveis
            **/
           
            $aCamposValores =  array (
               ':codloja' => $idCliente
               );
           
	    /**
	     * Preparando SQL
	     */
	    $pdo = $objConexao->pdo->prepare( $sql );
	    /*
	     * Executa consulta SQL
	     */
            
	    if($pdo->execute($aCamposValores)){
                /*
               *  busca logon id do cleinte 48785
                */
                $sql2 =  "  
                    SELECT
                        CAST(MID(logon.logon,1,6) AS UNSIGNED) as logon
                    FROM
                        cs2.logon as logon
                    WHERE
                        logon.codloja = :codloja
                    ";
             
                /*
                *campos variaveis
                **/
               
                $aCamposValores2 =  array (
                   ':codloja' => $idCliente
                   );
               
                /**
                 * Preparando SQL
                 */
                $pdo2 = $objConexao->pdo->prepare( $sql2 );
                /*
                 * Executa consulta SQL
                 */
                $pdo2->execute($aCamposValores2);
                
                $aResult[0] = $pdo2->fetchAll();
                
                /*
                 * faz um busca site do cliente
                */
                $sql3 =  "  
                    SELECT
                        domain as siteurl
                    FROM
                        cs2.virtualflex as vfx
                    WHERE
                        vfx.user_login = :codloja
                    ";
                
                /*
                *campos variaveis
                **/
               
                $aCamposValores3 =  array (
                   ':codloja' => $idCliente
                   );
               
                /**
                 * Preparando SQL
                 */
                $pdo3 = $objConexao->pdo->prepare( $sql3 );
                /*
                 * Executa consulta SQL
                 */
                $pdo3->execute($aCamposValores3);
                
                $aResult[1] = $pdo3->fetchAll();
                
            }
            
	    /**
	     * Cria array contendo o resultado da consulta
	     */		
	    $aResult[2] = $pdo->fetchAll();
	    /**
	     * Retorno
	     */
	    return $aResult;
        
	} catch ( Exception $e ) { 
            echo '<option>N&aacute;o foi poss&iacute;vel trazer os dados do cliente.</option>';
	} 
    }
    
    public function buscaHistoricoCliente($codCliente){
        try {
	    /**
	     * Instancia Conexao
	     */
	    $objConexao = new DbConnection();	    
	    /**
	     * Comando SQL
	     */
	    $sql =  "  
		SELECT
                orat.id,
                orat.protocolo,
                orat.datahora,
                orat.assunto,
                orat.status,
                orat.subdepto_destino,
                subdepto.nome_subdepartamento,
                subdepto.id_depto,
                depto.nome_departamento
            FROM
                cs2.ordem_atendimento AS orat
            LEFT JOIN
                cs2.ordem_atendimento_subdepto AS subdepto
            ON
                subdepto.id = orat.subdepto_destino
            LEFT JOIN
                cs2.ordem_atendimento_depto AS depto
            ON
                depto.id = subdepto.id_depto
            WHERE
                orat.cod_cliente = :cod_cliente
            LIMIT 0, 5
	    ";
	    /**	
	     * Declaracao de variavel
	     */
	    $aResult = array();
            /*
            *campos variaveis
            **/
           
            $aCamposValores =  array (
               ':cod_cliente' => $codCliente
               );
           
	    /**
	     * Preparando SQL
	     */
	    $pdo = $objConexao->pdo->prepare( $sql );
	    /*
	     * Executa consulta SQL
	     */		
	    $pdo->execute($aCamposValores);
	    /**
	     * Cria array contendo o resultado da consulta
	     */		
	    $aResult = $pdo->fetchAll();
	    /**
	     * Retorno
	     */
	    return $aResult;
        
	} catch ( Exception $e ) { 
            echo '<option>N&aacute;o foi poss&iacute;vel trazer os dados do cliente.</option>';
	} 
    }
     
    
    public function listaDepartamentos(){
        try {
	    /**
	     * Instancia Conexao
	     */
	    $objConexao = new DbConnection();	    
	    /**
	     * Comando SQL
	     */
	    $sql =  "  
		SELECT
                    depto.id,
                    depto.nome_departamento
                FROM
                    cs2.ordem_atendimento_depto AS depto;
	    ";
	    /**	
	     * Declaracao de variavel
	     */
	    $aResult = array();
	    /**
	     * Preparando SQL
	     */
	    $pdo = $objConexao->pdo->prepare( $sql );
	    /*
	     * Executa consulta SQL
	     */		
	    $pdo->execute();
	    /**
	     * Cria array contendo o resultado da consulta
	     */		
	    $aResult = $pdo->fetchAll();
	    /**
	     * Retorno
	     */
	    return $aResult;
	} catch ( Exception $e ) { 
            echo '<option>N&aacute;o foi poss&iacute;vel selecionar as ocorrencias.</option>';
	    Erros::mensagemErro ( $e );
	} 
    }
    
    public function listaSubDepartamentos(){
        try {
	    /**
	     * Instancia Conexao
	     */
	    $objConexao = new DbConnection();	    
	    /**
	     * Comando SQL
	     */
	    $sql =  "  
		SELECT 
                    depto.id AS deptoId,
                    depto.nome_departamento,
                    subdepto.id AS subdeptoId,
                    subdepto.id_depto AS deptoIdSubdepto,
                    subdepto.nome_subdepartamento
                FROM 
                    cs2.ordem_atendimento_depto AS depto
                RIGHT JOIN
                      cs2.ordem_atendimento_subdepto AS subdepto
                ON depto.id = subdepto.id_depto
	    ";
	    /**	
	     * Declaracao de variavel
	     */
	    $aResult = array();
	    /**
	     * Preparando SQL
	     */
	    $pdo = $objConexao->pdo->prepare( $sql );
	    /*
	     * Executa consulta SQL
	     */		
	    $pdo->execute();
	    /**
	     * Cria array contendo o resultado da consulta
	     */		
	    $aResult = $pdo->fetchAll();
	    /**
	     * Retorno
	     */
	    return $aResult;
	} catch ( Exception $e ) { 
            echo '<option>N&aacute;o foi poss&iacute;vel selecionar as ocorrencias.</option>';
	    Erros::mensagemErro ( $e );
	} 
    }
    
    public function buscaOrdemById($idOrdem){
        try {
            
	    /**
	     * Instancia Conexao
	     */
	    $objConexao = new DbConnection();	    
	    /**
	     * Comando SQL
	     */
            
            //orat.id,
            //        orat.protocolo,
            //        orat.datahora,
            //        orat.id_depto_solicitante,
            //        orat.id_solicitante,
            //        orat.nome_solicitante,
            //        orat.subdepto_destino,
            //        orat.assunto,
            //        orat.cod_cliente,
            //        orat.razao_cliente,
            //        orat.status,
            //        
            //        depto.nome_departamento,
            //        subdepto.nome_subdepartamento as depto_solicitante,
            //        subdeptoD.nome_subdepartamento as depto_destino
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
                    orat.id = :idOrdem
	    ";
	    /**	
	     * Declaracao de variavel
	     */
	    $aResult = array();
	    /**
	     * Preparando SQL
	     */
	    $pdo = $objConexao->pdo->prepare( $sql );
            
            /*
             *definindo valor dos campos
            */
            $aCamposValores =  array (
                ':idOrdem' => $idOrdem
            );
            
	    /*
	     * Executa consulta SQL
	     */		
	    if($pdo->execute($aCamposValores)){
                $aResult = $pdo->fetchAll(PDO::FETCH_ASSOC );
            } else {
                $aResult = 'erro';
            }
	    /**
	     * Cria array contendo o resultado da consulta
	     */		
	    //$aResult = $pdo->fetchAll();
	    /**
	     * Retorno
	     */
            
	    return $aResult;
        
        
	} catch ( Exception $e ) { 
            echo '<option>N&aacute;o foi poss&iacute;vel selecionar as ocorrencias.</option>';
	    Erros::mensagemErro ( $e );
	} 
    }
    
    public function pegaDadosEnviarPorEmail($protocolo){
        try {    
            /**
	     * Instancia Conexao
	     */
	    $objConexao = new DbConnection();	    
	    /**
	     * Comando SQL
	     */
	    $sql =  "  
		SELECT
                    orat.id,
                    orat.protocolo,
                    orat.subdepto_destino,
                    
                    subdepto.nome_subdepartamento,
                    subdepto.email_depto
                FROM
                    cs2.ordem_atendimento AS orat                    
                LEFT JOIN
                    cs2.ordem_atendimento_subdepto AS subdepto
                ON
                    orat.subdepto_destino = subdepto.id
                WHERE
                    orat.protocolo = :protocolo
	    ";
	    /**	
	     * Declaracao de variavel
	     */
	    $aResult = array();
	    /**
	     * Preparando SQL
	     */
	    $pdo = $objConexao->pdo->prepare( $sql );
            
            /*
             *definindo valor dos campos
            */
            $aCamposValores =  array (
                ':protocolo' => $protocolo
            );
            
	    /*
	     * Executa consulta SQL
	     */		
	    if($pdo->execute($aCamposValores)){
                $aResult = $pdo->fetchAll(PDO::FETCH_ASSOC );
            } else {
                $aResult = 'erro';
            }
	    /**
	     * Cria array contendo o resultado da consulta
	     */		
	    //$aResult = $pdo->fetchAll();
	    /**
	     * Retorno
	     */
            
	    return $aResult;
        
        
	} catch ( Exception $e ) { 
            echo '<option>N&aacute;o foi poss&iacute;vel selecionar as ocorrencias.</option>';
	    Erros::mensagemErro ( $e );
	} 
    }
    
    /*
     *method busca hirtorico da ordem
     **/
    
//    public function buscaHistoricoOrdem($idOrdem){
//        try {
//	    /**
//	     * Instancia Conexao
//	     */
//	    $objConexao = new DbConnection();	    
//	    /**
//	     * Comando SQL
//	     */
//	    $sql =  "  
//		SELECT
//                orat.id,
//                orat.protocolo,
//                orlog.datahora,
//                orlog.acao,
//                orlog.responsavel,
//                orlog.descricao,
//                orlog.depto_de,
//                orlog.depto_para
//            FROM
//                cs2.ordem_atendimento AS orat
//            RIGHT JOIN
//                cs2.ordem_atendimento_log AS orlog
//            ON
//                orlog.protocolo_atendimento = orat.protocolo
//            WHERE
//                orat.id = :id_ordem
//	    ";
//	    /**	
//	     * Declaracao de variavel
//	     */
//	    $aResult = array();
//            /*
//            *campos variaveis
//            **/
//           
//            $aCamposValores =  array (
//               ':id_ordem' => $idOrdem
//               );
//           
//	    /**
//	     * Preparando SQL
//	     */
//	    $pdo = $objConexao->pdo->prepare( $sql );
//	    /*
//	     * Executa consulta SQL
//	     */		
//	    $pdo->execute($aCamposValores);
//	    /**
//	     * Cria array contendo o resultado da consulta
//	     */		
//	    $aResult = $pdo->fetchAll();
//	    /**
//	     * Retorno
//	     */
//	    return $aResult;
//        
//	} catch ( Exception $e ) { 
//            echo '<option>N&aacute;o foi poss&iacute;vel trazer os dados do cliente.</option>';
//	} 
//    }
    
    
}


?>