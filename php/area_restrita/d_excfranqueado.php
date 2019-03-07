<?
require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

$comando = "delete from franquia where id='$id'";
$res = mysql_query ($comando, $con);
$res = mysql_close ($con);
echo "<meta http-equiv=\"refresh\" content=\"0; url=../painel.php?pagina1=area_restrita/d_relfranqueados.php\";>";
?>