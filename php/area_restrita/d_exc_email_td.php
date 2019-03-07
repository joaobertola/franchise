<?php
require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

$comando = "delete from correio_franquia where franquia='$id_franquia'";
$res = mysql_query ($comando, $con);
$res = mysql_close ($con);
echo "<meta http-equiv=\"refresh\" content=\"0; url=../painel.php?pagina1=area_restrita/d_email.php&mail=area_restrita/d_lista_email.php\";>";
?>