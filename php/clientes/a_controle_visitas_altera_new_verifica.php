<?php

ob_start();
session_start();


if ($_SESSION['SENHA-CONTROLE-CLIENTES'] == 'webcontrolempresas') {
    require "../connect/conexao_conecta.php";
}