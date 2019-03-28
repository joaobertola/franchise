<?php
/**
 * @author Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
 * @version 2015083101
 * @copyright 
 * @package nfse
 * @name application.php
 */

if(!defined('_NFSE')):
	die("aplication Hacking detectado, seu ip ".$_SERVER['REMOTE_ADDR']." foi logado !");
endif;

error_reporting(E_ALL);

define('BASE_DIR', dirname(dirname(__FILE__))); 
define('BASE_ROOT', dirname(BASE_DIR));

define('PATH_NUSOAP', BASE_ROOT . '/libs/nusoap/');
define('PATH_PHPMAILER', BASE_ROOT . '/libs/phpmailer/');

define("PATH_INCLUDES", BASE_DIR . '/includes/');
define("PATH_CLASSES", BASE_DIR . '/classes/');
define("PATH_TEMPLATES", BASE_DIR . '/templates/');

define("PATH_CERTS", BASE_ROOT . "/Franquias/certs/");
define("PATH_SCHEMAS", BASE_ROOT . "/Franquias/schemas/");

define("PATH_PRODUCAO_REMESSA", BASE_ROOT . "/rps/producao/remessa/");
define("PATH_PRODUCAO_RETORNO", BASE_ROOT . "/rps/producao/retorno/");

define("PATH_HOMOLOGACAO_REMESSA", BASE_ROOT . "/rps/homologacao/remessa/");
define("PATH_HOMOLOGACAO_RETORNO", BASE_ROOT . "/rps/homologacao/retorno/");

define('MAILSERVER','mail.webcontrolempresas.com.br');
define('MAILAUTHSMTP',true);
define('MAILUSERNAME','luciano@webcontrolempresas.com.br');
define('MAILPASSWORD','inform123');
define('MAILFROM', MAILUSERNAME );
define('MAILFROMNAME','NFSe - WEB CONTROL');
define('MAILWORDWRAP',50);
define('MAILISHTML',true);

# Variaveis necessarias para conexao com o banco
define("DB_DRIVER","mysql");
define("DB_USER","csinform");
define("DB_PWD","inform4416#scf");
define("DB_SERVER","10.2.2.3");
define("DB_NAME","NFSE");

date_default_timezone_set("America/Sao_Paulo");
setlocale(LC_TIME, 'pt_BR');

//require_once( 'header.inc.php');
require_once( 'db.inc.php');
require_once( '/var/www/html/franquias/php/Franquias/classes/funcoes.class.php');
require_once( '/var/www/html/franquias/php/Franquias/classes/validacoes.class.php');
require_once( '/var/www/html/franquias/php/Franquias/libs/nusoap/nusoap.php');
?>