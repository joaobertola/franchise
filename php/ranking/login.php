<?
session_start("vlogin");
if(!(session_is_registered("login") AND session_is_registered("senha"))) {
                echo "<b>�rea restrita</b>.<br>Voc� n�o tem permiss�o para acess�-la.";
                exit;
}
$login=$HTTP_SESSION_VARS[login];
$senha=$HTTP_SESSION_VARS[senha];
?>
