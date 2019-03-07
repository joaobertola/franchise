<?php

require_once("/var/www/html/franquias/php/connect/conexao_conecta.php");
//require_once("../dompdf/dompdf_config.inc.php");
//require_once('class.phpmailer.php');

$numdoc      = $_REQUEST['numdoc'];
$faturamento = $_REQUEST['faturamento'];

$sql = "SELECT numdoc FROM cs2.titulos_notafiscal WHERE FIND_IN_SET( numdoc, '$numdoc')";
$qry = mysql_query($sql, $con) or die('Erro SQL 01:'.$sql);
$qtd_email = mysql_num_rows($qry);
if ( $qtd_email > 0 ){
    $retorno = '';
    while( $reg = mysql_fetch_array($qry) ){
        
        $numdocx = $reg['numdoc'];
        
        // solicitando a nota UMA por UMA
        $URL = "http://www.informsystem.com.br/franquias/php/Franquias/b_notafiscal_sendmail.php";
        $dadosenv = "numdoc=$numdocx&faturamento=$faturamento&lote=SIM";
	$REF = "http://www.informsystem.com.br/franquias/php/Franquias/b_notafiscal_sendmail.php";
	$ch = curl_init();   
	curl_setopt($ch, CURLOPT_URL,$URL); 
	curl_setopt($ch, CURLOPT_REFERER,$REF);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT,4);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$dadosenv);
	$retorno = curl_exec ($ch); 
	curl_close ($ch);
      
        $array_retorno = explode(';',$retorno);
        $cmd      = trim($array_retorno[0]);
        $cliente  = trim($array_retorno[1]);
        $msg_erro = trim($array_retorno[2]);
        
        if ( $cmd == 'OK' ){
            $retorno_web_ok .= $cliente.'<br>';
        }
        if ( $cmd == 'ERR' ){
            $retorno_web_err .= $cliente.' - '.$msg_erro.'<br>';
        }
        
    } // while
    
}

echo "
<table>
   <tr></td>RETORNO DOS EMAILS ENVIADOS</td></tr>
";
if ( $retorno_web_err <> '' ){
    echo "<tr><td><font color='red'><b>Erros Encontrados !!!</b></font></td></tr>";
    echo "<tr><td>$retorno_web_err</td></tr>";
    echo "<tr><td><hr></td></tr>";
}
echo "<tr><td>Enviados com Sucesso !!! </td></tr>";
echo "<tr><td><hr></td></tr>";
echo "<tr><td>$retorno_web_ok</td></tr>";
echo "</table>";
?>