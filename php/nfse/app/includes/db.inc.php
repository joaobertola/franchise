<?php
/**
 * @author Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
 * @version 2015083101
 * @copyright 
 * @package nfse
 * @name db.inc.php
 */

if(!defined('_NFSE')):
	die("Hacking detectado, seu ip ".$_SERVER['REMOTE_ADDR']." foi logado !");
endif;

class DB{
	public static $error = NULL;
	private static $instance = NULL;

	private function __construct() {
		/*** maybe set the db name here later ***/
	}

	public static function connect() {

		if (!self::$instance)
		{
			try
			{

				$options = array(
						PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8 COLLATE utf8_general_ci',
						PDO::MYSQL_ATTR_INIT_COMMAND => 'SET character_set_connection=utf8',
						PDO::MYSQL_ATTR_INIT_COMMAND => 'SET character_set_client=utf8',
						PDO::MYSQL_ATTR_INIT_COMMAND => 'SET character_set_results=utf8',
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				);
				self::$instance = new PDO( DB_DRIVER . ':host=' . DB_SERVER . ';dbname=' . DB_NAME , DB_USER , DB_PWD , $options);
			} catch ( PDOException $PDOExceptione )
			{
				self::$error = 'DB Error : ' . trim( $PDOExceptione->getMessage() );
			}
		}
		return self::$instance;
	}

	private function __clone(){
	}
}
?>