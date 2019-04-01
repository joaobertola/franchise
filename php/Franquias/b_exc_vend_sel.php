<?
session_start();
	$nome = $_SESSION["ss_nome"];
	$tipo = $_SESSION["ss_tipo"];
	if (($nome=="") || ($tipo!="a")){
		echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.bluevision.com.br/publico/erro/index.php\";>";
		die;
		}
include "../connection/conec.php";
$selected_cnt   = count($selected);
for ($i=0; $i<$selected_cnt; $i++)
{ 
$b = $selected[$i];
$comando = "delete from usuario where id='$b'";
$res = mysql_query ($comando, $con);
}
$res = mysql_close ($con);
echo "<meta http-equiv=\"refresh\" content=\"0; url= ../painel.php?pagina1=usuario/most_usu.php\";>";
exit;
?>