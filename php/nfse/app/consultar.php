<?php
/**
 * @author Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
 * @version 2015083101
 * @copyright 
 * @package nfse
 * @name index.php
 */

define('_NFSE', true);

require('includes/application.php');

$issPadrao       = $_REQUEST['issPadrao'];
$numeroProtocolo = $_REQUEST['numeroProtocolo'];
$id_cadastro     = $_REQUEST['idCadastro'];
$ambiente        = $_REQUEST['ambiente'];
$cnpj            = $_REQUEST['cnpj'];
$issPadrao       = $_REQUEST['issPadrao'];
$sigla           = $_REQUEST['sigla'];
$urlArqXsd       = urldecode($_REQUEST['urlXsd']);
$urlProducao     = urldecode($_REQUEST['urlProducao']);
$urlHomologacao  = urldecode($_REQUEST['urlHomologacao']);
$cidadePrestador = strtoupper($_REQUEST['cidadePrestador']);
$ufPrestador     = strtoupper($_REQUEST['ufPrestador']);
$assinarNFS      = strtoupper($_REQUEST['assinarNFS']);

switch ( $ufPrestador ){

    case 'PR':  // ESTADO

        if ( $cidadePrestador == 'CURITIBA' ){ 

            // PADRAO: ABRASF COM ASSINATURA
            require( PATH_CLASSES . 'rps_curitiba_pr.class.php' );

        }else if ( $cidadePrestador == 'COLOMBO' ){

            // PADRAO: IPM  SEM  ASSINATURA 
            require( PATH_CLASSES . 'rps_colombo_pr.class.php' );

        }
        else if ( $cidadePrestador == 'ALMIRANTE TAMANDARE' ){

            // PADRAO: ABRASF - VERSAO 2.02 COM ASSINATURA
            require( PATH_CLASSES . 'rps_almirante_tamandare_pr.class.php' );

        }else if ( $cidadePrestador == 'LONDRINA' ){

            // PADRAO: IPM  SEM  ASSINATURA 
            require( PATH_CLASSES . 'rps_londrina_pr.class.php' );

        }else if ( $cidadePrestador == 'PINHAIS' ){

            // PADRAO: IPM  SEM  ASSINATURA 
            require( PATH_CLASSES . 'rps_pinhais_pr.class.php' );

        }else if ( $cidadePrestador == 'MARINGA' ){

            // PADRAO: ABRASF - VERSAO 2.01 COM ASSINATURA
            require( PATH_CLASSES . 'rps_maringa_pr.class.php' );

        }else{

               echo "999;CIDADE NAO HABILITADA PARA EMISSAO DE NFSe : ".$cidadePrestador;

        }
        break;

    case 'RS':  // ESTADO

        if ( $cidadePrestador == 'PORTO ALEGRE' ){ 

            // PADRAO: ABRASF COM ASSINATURA
            require( PATH_CLASSES . 'rps_porto_alegre_rs.class.php' );
        }
        break;
        
    default:

        echo "999;ESTADO NAO HABILITADO PARA EMISSAO DE NFSe : ".$ufPrestador;
        break;

}


function ConsultarLoteRps( 
        $modelo, 
        $codigo, 
        $cnpj, 
        $protocolo,
        $sigla,
        $urlArqXsd,
        $urlProducao,
        $urlHomologacao,
        $cidadePrestador,
        $ufPrestador,
        $assinarNFS
        )
{
    try
    {	
        if( !in_array($modelo,array('producao','homologacao'))):
                throw new Exception('informe producao ou homologacao, parametro invalido');
        endif;

        if( !Validacoes::isValidCNPJ($cnpj)):
                throw new Exception(Validacoes::$errorMessage);
        endif;

        if( empty($protocolo)):
                throw new Exception('informe o numero do protocolo');
        endif;

        if(!DB::connect()):
                throw new Exception(DB::$error);
        endif;

        DB::connect()->beginTransaction();

        $oRps = new RPS( );
        $oRps->aplicativo = $modelo;
        $oRps->CodigoEmpresa = $codigo;
        $oRps->CnpjEmpresa = $cnpj;
        $oRps->rpsPROTOCOLO = $protocolo;
        $oRps->URLArqxsd = $urlArqXsd;

        $oRps->URLwebservice = 
            array('producao' => $urlProducao,
                  'homologacao' => $urlHomologacao
                 );

        $result = $oRps->ConsultarLoteRps();

        DB::connect()->commit(); 

        return $result;

    } catch (Exception $ae)
    {
            if( DB::connect()->inTransaction() ):
                    DB::connect()->rollBack();
            endif;

            $resposta = '<?xml version="1.0" encoding="UTF-8" ?>';
            $resposta.= '<consulta>';
            $resposta.= '<errorMessage><![CDATA[' . $ae->getMessage() . ']]></errorMessage>';
            $resposta.= '</consulta>';

            $xml = new SimpleXMLElement( $resposta );
            return $xml->asXML();
    }		
}

print_r( 
        ConsultarLoteRps(
                $ambiente,
                $id_cadastro,
                $cnpj,
                $numeroProtocolo,
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

?>