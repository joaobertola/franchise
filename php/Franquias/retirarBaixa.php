<?php

require "connect/sessao.php";

include("connect/conexao_conecta.php");





        if(isset($_REQUEST['codloja'])) {
            $id = $_REQUEST['codloja'];


            $sql = "UPDATE  cadastro SET
                                dt_pgto_adesao = null,
                                vr_pgto_adesao = 0
                            WHERE
                                codloja = '$id'";
            //echo $sql;
            // echo $con;
            $qry = mysql_query($sql, $con) or die("Erro SQL : $sql");

            //echo "<script>location.href = 'painel.php?pagina1=Franquias/b_extratocontratos.php';</script>";


            echo "<script>location.href = 'painel.php?pagina1=Franquias/b_extratocontratos.php';</script>";



        } else {
            echo "<script>alert('Todos os campos são obrigatorios!');history.back()</script>";
        }
?>