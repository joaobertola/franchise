<?

//RECEBE PARÂMETRO 
$id = $_GET["id"]; 

//CONECTA AO MYSQL 
//require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

//EXIBE IMAGEM 
$sql = mysql_query("SELECT foto,tipofoto FROM franquia WHERE id = ".$id."",$con); 

$row = mysql_fetch_array($sql); 
$tipo = $row["tipofoto"]; 
$bytes = $row["foto"]; 
//EXIBE IMAGEM 
header("Content-type: ".$tipo.""); 
echo $bytes; 
?>