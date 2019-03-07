<?php

    /**
     * @author Luciano Mancini
     * @version 2016-09-21 Versao 01
     * @copyright
     * @package nfse
     * @name enviar.php
     */

    function RecepcionarLoteRps(
        $modelo,
        $codigo,
        $cnpj,
        $arquivo,
        $issPadrao,
        $numdoc,
        $sigla,
        $urlArqXsd,
        $urlProducao,
        $urlHomologacao,
        $cidadePrestador,
        $ufPrestador,
        $assinarNFS
    ){
	try
	{
            
            if( !in_array($modelo,array('producao','homologacao'))):
                    throw new Exception('901;informe producao ou homologacao, parametro invalido');
            endif;

            if( !Validacoes::isValidCNPJ($cnpj)):
                    throw new Exception(Validacoes::$errorMessage);
            endif;

            if(!DB::connect()):
                    throw new Exception(DB::$error);
            endif;

            DB::connect()->beginTransaction();

            // Verificando se a nota já foi emitida anteriormente, se sim, retorna o link para visualizar a nota.

            $sql = <<<SQLQUERY
                    SELECT RETORNO, LINK_NFS
                    FROM cs2.titulos_notafiscal
                    WHERE 
                        numdoc = :numdoc
                    AND
                        status = :status
                        
SQLQUERY;
            try
            {
                $stmt = DB::connect()->Prepare ( $sql );
                $stmt->bindValue(':numdoc', $numdoc , PDO::PARAM_INT);
                $stmt->bindValue(':status', '5' , PDO::PARAM_INT);
                $stmt->Execute ();
                $rs = $stmt->fetchAll(PDO::FETCH_OBJ);
                $stmt->closeCursor();
                unset($stmt);
            } catch ( PDOException $PDOExceptione ) {
                    throw new Exception ( $PDOExceptione->getMessage() );
            } catch ( Exception $ae ) {
                    throw new Exception ( $ae->getMessage() );
            }

            if( ! empty($rs) ):	

                $xml = $rs[0]->RETORNO;
                if ( $xml ){
                    // NOTA EXISTE
                    $link_baixar_nota = $rs[0]->LINK_NFS;
                    echo '000;'.$link_baixar_nota;
                    die;
                }
                
            endif;

            $oRps = new RPS( );
            $oRps->aplicativo = $modelo;
            $oRps->CodigoEmpresa = $codigo;
            $oRps->CnpjEmpresa = $cnpj;
            $oRps->Sigla = $sigla;
            $oRps->NumeroPedido = $numdoc;
            $oRps->URLArqxsd = $urlArqXsd;
            
            $oRps->URLwebservice = 
                array('producao' => $urlProducao,
                      'homologacao' => $urlHomologacao
                     );
            
            // Assinando o XML do pedido

            $oRps->assinar($arquivo);
            // Assinando cada arquivo
            $oRps->ValidarXml();
            // Enviando cada arquivo
            $result = $oRps->enviar();
            
            DB::connect()->commit();

            return $result;

	} catch (Exception $ae)
	{
            if( DB::connect()->inTransaction() ):
                    DB::connect()->rollBack();
            endif;
            return '999;'.$ae->getMessage();

	}
	
    }

    define('_NFSE', true);

    $issPadrao       = $_REQUEST['issPadrao'];
    $numero_pedido   = $_REQUEST['numdoc'];
    $ambiente        = 'P';
    $cnpj            = '13117948000173';
    $sigla           = '06902';
    $urlArqXsd       = 'http://isscuritiba.curitiba.pr.gov.br/iss/nfse.xsd';
    $urlProducao     = 'https://isscuritiba.curitiba.pr.gov.br/Iss.NfseWebService/nfsews.asmx';
    $urlHomologacao  = 'http://pilotoisscuritiba.curitiba.pr.gov.br/nfse_ws/nfsews.asmx';
    $cidadePrestador = 'CURITIBA';
    $ufPrestador     = 'PR';
    $assinarNFS      = 'S';
    
    require('includes/application.php');

    // PADRAO: ABRASF COM ASSINATURA
    require( PATH_CLASSES . 'rps_curitiba_pr.class.php' );

    // Solicita o XML da venda. - NFS

    $servidor = "http://www.informsystem.com.br/web_control/xml_pedido_webcontrol2.php";
    $dadosenv = "numero_pedido=$numero_pedido&id_cadastro=$id_cadastro&tipo=nfse&ambiente=$ambiente";

    $ch = curl_init();
    //endere�o para envio do post
    curl_setopt ($ch, CURLOPT_URL, $servidor);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
    curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION,1);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    // envio do parametros
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dadosenv);
    $conteudo = curl_exec($ch);

    if (curl_errno($ch)) {
        print curl_error($ch);
    } else {
        curl_close($ch);
    }
    $xml_enc = $conteudo;

    print_r(
        RecepcionarLoteRps(
            $ambiente,
            $id_cadastro,
            $cnpj,
            $xml_enc,
            $issPadrao,
            $numero_pedido,
            $sigla,
            $urlArqXsd,
            $urlProducao,
            $urlHomologacao,
            $cidadePrestador,
            $ufPrestador,
            $assinarNFS
        )
    );
    exit();