<?php

$selected = $_POST['selected'];

$selected_cnt = count($selected);


for ($i = 0; $i < $selected_cnt; $i++) {
    $b = $selected[$i];
    $comando = "delete from pretendentes where id='$b'";
    $res = mysql_query($comando, $con);
}
$res = mysql_close($con);
echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=area_restrita/pretendentes_form_listar.php&id_status=1&go=1&af=2\";>";
exit;
?>