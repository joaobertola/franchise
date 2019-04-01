<?php
/**
 * @author Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
 * @version 2014022001
 * @copyright TIGE, Tecnologia, Informacao e Gestao Empresarial.
 * @package auto.tige.com.br
 * @name headder.inc.php
 */

if (! defined ('_NFSE')):
	die ( "header Hacking detectado, seu ip " . $_SERVER ['REMOTE_ADDR'] . " foi logado !" );
endif;

header ( "Content-Type: text/html; charset=UTF-8", true );
if (! empty ( $_SERVER ['SERVER_SOFTWARE'] ) && strstr ( $_SERVER ['SERVER_SOFTWARE'], 'Apache/2' )):
	header ( 'Cache-Control: no-cache, pre-check=0, post-check=0' );
else:
	header ( 'Cache-Control: private, pre-check=0, post-check=0, max-age=0' );
endif;
header ( 'Expires: 0' );
header ( 'Pragma: no-cache' ); // HTTP/1.0
?>