<?php
include("functions.php");
$usuario = $_POST["usuario"];
$senha  = $_POST["senha"];
$pagina = $_POST["pagina"];
valida_login($usuario,$senha,$pagina);
?>