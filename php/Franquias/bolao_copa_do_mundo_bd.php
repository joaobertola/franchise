<?php
require "connect/sessao_r.php";

$apostador   = strtoupper($_REQUEST['apostador']);
$brasil      = $_REQUEST['brasil'];
$adversario  = $_REQUEST['adversario'];
$id_jogo     = $_REQUEST['id_jogo'];

$sql = "INSERT INTO cs2.copa (brasil, adversario, apostador, id_franquia, id_jogo, data_hora_lance)
		VALUES
		('$brasil', '$adversario', '$apostador', '{$_SESSION['id']}', '$id_jogo', now())";

$res = mysql_query ($sql, $con);		
?>
<script>
window.location.href="painel.php?pagina1=Franquias/bolao_copa_do_mundo.php&msg=1";
</script>
