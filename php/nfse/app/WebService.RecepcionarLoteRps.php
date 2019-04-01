<?php
/**
 * @author Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
 * @version 2015083101
 * @copyright 
 * @package nfse
 * @name WebService.RecepcionarLoteRps.php
 */

if(!defined('_NFSE')):
	die("Hacking detectado, seu ip ".$_SERVER['REMOTE_ADDR']." foi logado !");
endif;

$objetoSoapServer->register('RecepcionarLoteRps',
		array(
				"modelo" => "xsd:string",
				"codigo" => "xsd:string",
				"cnpj" => "xsd:string",
				"arquivo" => "xsd:string"),
		array(
				"return"=>"xsd:string"),
		"urn:WebServer.NFsE",
		"urn:WebServer.NFsE#RecepcionarLoteRps",
		"rpc",
		"encoded",
		"Enviar a RPS para a Prefeitura de Curitiba:<br />
		 Modelo informar producao ou homologacao<br />
		 CNPJ da empresa prestadora<br />
		 Arquivo XML formato RPS sem a assinatura<br />");
	
function RecepcionarLoteRps(
		$modelo,
		$codigo,
		$cnpj,
		$arquivo)
{
	try
	{
		if( !in_array($modelo,array('producao','homologacao'))):
			throw new Exception('informe producao ou homologacao, parametro invalido');
		endif;
		
		if( !Validacoes::isValidCNPJ($cnpj)):
			throw new Exception(Validacoes::$errorMessage);
		endif;

 		if(!DB::connect()):
 			throw new Exception(DB::$error);
 		endif;
 		
 		DB::connect()->beginTransaction();

		$oRps = new RPS( );
		$oRps->aplicativo = $modelo;
		$oRps->CodigoEmpresa = $codigo;
		$oRps->CnpjEmpresa = $cnpj;
		$oRps->assinar($arquivo);
		$oRps->ValidarXml();
		$result = $oRps->enviar();
		
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