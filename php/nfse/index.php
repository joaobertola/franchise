<?php
   die("Ola " . ( isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER ['HTTP_X_FORWARDED_FOR'] . '/' : '' ) . $_SERVER['REMOTE_ADDR'] . " , voce esta em crosariol.com.br !");
?>
