<?
session_start("vlogin");

print_r( $_SESSION );

if(!(session_is_registered("login_ocorr") AND session_is_registered("senha_ocorr"))) {
                echo "<b>�rea restrita</b>.<br>Voc� n�o tem permiss�o para acess�-la.";
                exit;
}
$login=$HTTP_SESSION_VARS[login];
$senha=$HTTP_SESSION_VARS[senha];
?>
