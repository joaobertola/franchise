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

$objetoSoapServer = new soap_server();
$objetoSoapServer->configureWSDL("WebServer.NFsE","urn:WebServer.NFsE");
$objetoSoapServer->wsdl->schemaTargeNamespace = "WebServer.NFsE";

require( PATH_CLASSES . 'rps.class.php' );

require( 'WebService.RecepcionarLoteRps.php' );
require( 'WebService.CancelarLoteRps.php' );
require( 'WebService.ConsultarLoteRps.php' );
require( 'WebService.ConsultarSituacaoLoteRps.php' );

$postdata = file_get_contents("php://input");
$objetoSoapServer->service($postdata);
exit();
?>