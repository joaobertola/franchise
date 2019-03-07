<?
require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

$selected_cnt   = count($selected);
for ($i=0; $i<$selected_cnt; $i++) { 
	$b = $selected[$i];
	$comando = "delete from franquia where id='$b'";
	$res = mysql_query ($comando, $con);
}
$res = mysql_close ($con);
echo "<meta http-equiv=\"refresh\" content=\"0; url= ../painel.php?pagina1=area_restrita/d_relfranqueados.php\";>";
exit;
?>