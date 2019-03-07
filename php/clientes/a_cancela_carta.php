<?php
require "connect/sessao.php";
require "connect/funcoes.php";

echo "<pre>";
print_r($_SESSION);
print_r($_REQUEST);

echo "<script>alert(\"Registro Alterado com sucesso!\");</script>";
//echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=clientes/most_cliente.php&codloja=$codloja\";>";
?>