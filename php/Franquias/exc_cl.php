<?
require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

$comando = "delete from dados where id='$id'";
$res = mysql_query ($comando, $con);
$res = mysql_close ($con);
$pagina1 = "most_cl.php";
echo "<meta http-equiv=\"refresh\" content=\"0; url= ../franquias.php\";>";
?>