<?php
session_start();

global $name;

$name = $_SESSION["ss_name"];
$tipo = $_SESSION["ss_tipo"];
$usuario = $_SESSION["usuario"];
$id_franquia = $_SESSION["id"];
$classificacao = $_SESSION["ss_classificacao"];
$frq_fantasia = $_SESSION["ss_fantasia"];


if (($name=="") || ($tipo=="e")){

	session_destroy();
	echo "<meta http-equiv=\"refresh\" content=\"0; url= https://webcontrolempresas.com.br/franquias/erro/index.php\";>";
	die;
}
?>