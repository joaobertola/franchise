<?php

//require_once('../connect/sessao.php');
//session_start();
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//if (($name=="") || ($tipo!="a")){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}

require "../connect/conexao_conecta.php";

@$franqueado	= $_POST['franqueado'];
@$data		= $_POST['data'];
@$discriminacao = $_POST['discriminacao'];
@$valor		= $_POST['valor'];
@$valor		= str_replace(".","",$valor);
@$valor		= str_replace(",",".",$valor);
@$operacao	= $_POST['operacao'];
@$cliente       = $_POST['cliente'];
@$destino       = $_POST['destino'];

if ( $destino == 'CLIENTE'){
    
    // Buscando o ultimo valor do cliente
    $sql = "SELECT a.saldo, a.codloja FROM cs2.contacorrente_recebafacil a
            INNER JOIN cs2.logon b ON a.codloja = b.codloja
            WHERE b.logon like '$cliente%'
            ORDER BY a.id DESC
            LIMIT 1 ";
    $qry = mysql_query ($sql, $con);
    $saldo = mysql_result($qry, 0, 'saldo');
    $codloja = mysql_result($qry, 0, 'codloja');
    if ( $saldo == '' )
        $saldo = 0;
    
    if ( $operacao == 0 ){ // Credito
        $saldo += $valor; 
    }else{ // Debito
        $saldo -= $valor;
    }
    $comando = "INSERT INTO cs2.contacorrente_recebafacil
                (
                    codloja, data, discriminacao, valor, operacao, saldo, datapgto
                )
                VALUES
                (
                    '$codloja', NOW(), '$discriminacao', '$valor', '$operacao', '$saldo', NOW()
                )";
    $qry = mysql_query ($comando, $con);
    $qry = mysql_close ($con);
    echo "<script>alert(\"Movimento realizado com sucesso!\");</script>";
    echo "<meta http-equiv=\"refresh\" content=\"0; url= ../painel.php?pagina1=area_restrita/d_lancamento_conta_corrente.php\";>";
    
}else{
    $comando = "insert into contacorrente(franqueado, data, discriminacao, valor, operacao) values ('$franqueado', '$data', '$discriminacao', '$valor', '$operacao')";
    $res = mysql_query ($comando, $con);
    $res = mysql_close ($con);
    echo "<script>alert(\"Movimento realizado com sucesso!\");</script>";
    echo "<meta http-equiv=\"refresh\" content=\"0; url= ../painel.php?pagina1=area_restrita/d_ctacte.php\";>";
}
?>
