<?php
/**
 * @author Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
 * @version 2015083101
 * @copyright 
 * @package nfse
 * @name WebService.ConsultarLoteRps.php
 */

if(!defined('_NFSE')):
	die("Hacking detectado, seu ip ".$_SERVER['REMOTE_ADDR']." foi logado !");
endif;

$objetoSoapServer->register('ConsultarLoteRps',
		array(
				"modelo" => "xsd:string",
				"codigo" => "xsd:string",
				"cnpj" => "xsd:string",
				"protocolo" => "xsd:string"),
		array(
				"return"=>"xsd:string"),
		"urn:WebServer.NFsE",
		"urn:WebServer.NFsE#ConsultarLoteRps",
		"rpc",
		"encoded",
		"Consultar lote da RPS da Prefeitura de Curitiba:<br />
		 Modelo informar producao ou homologacao<br />
		 CNPJ da empresa prestadora<br />
		 Protocolo da RPS a ser consultado<br />");
	
function ConsultarLoteRps(
		$modelo,
		$codigo,
		$cnpj,
		$protocolo)
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
?>