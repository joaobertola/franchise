<?php
session_start();
$nome2 = $_SESSION["ss_restrito"];
$tipo = $_SESSION["ss_tipo"];

if (($name=="") || ($tipo=="e")){
	echo "<script>alert(\"Area Exclusiva do administrador da Franquia!\"); javascript: history.back();</script>";
	die;
}
?>