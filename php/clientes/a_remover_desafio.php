<?php
/**
 * @file a_remover_desafio.php
 * @brief
 * @author ARLLON DIAS
 * @date 01/09/2016
 * @version 1.0
 **/

require "../connect/sessao.php";
require "../connect/conexao_conecta.php";
require "../connect/funcoes.php";

if($_POST){

    $id = $_POST['id'];

    $sql = "DELETE FROM cs2.desafio_consultores_assistentes WHERE id = '$id'";
    $qry_insert = mysql_query($sql, $con) or die("Falha ao gravar o registro.");

    header("Location: ../painel.php?pagina1=clientes/a_controle_visitas6.php");

}
