<?php
//RECEBE PARÂMETRO 

$codloja = $_REQUEST["codloja"]; 

//CONECTA AO MYSQL 
$con = @mysql_connect("10.2.2.3", "csinform", "inform4416#scf");
if (!$con) {
	echo 'Erro na conexao com o Servidor<br>';
	echo mysql_error();
	exit;
} else {
	$database = mysql_select_db("cs2",$con);
	if (!$database) {
		echo 'Erro na conexão com o Banco de dados<br>';
		echo mysql_error();
	}
}

$sql = mysql_query("SELECT logomarca AS foto FROM cs2.cadastro WHERE codloja = $codloja"); 
$row = mysql_fetch_array($sql); 
//$tipo = $row["tipofoto"]; 
$bytes = $row["foto"]; 
//EXIBE IMAGEM 
//header("Content-type: ".$tipo.""); 
echo $bytes; 
?>