<?
session_start();
	$nome = $_SESSION["ss_nome"];
	$tipo_ss = $_SESSION["ss_tipo"];
	if (($nome=="") || ($tipo_ss!="a")){
		echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.bluevision.com.br/publico/erro/index.php\";>";
		die;
		}
include "../connection/conec.php";
$comando = "delete from usuario where id='$id'";
$res = mysql_query ($comando, $con);
$res = mysql_close ($con);
$pagina1 = "usuario/most_usu.php";
echo "<meta http-equiv=\"refresh\" content=\"0; url= ../painel.php?pagina1=$pagina1\";>";
?>