<?
require "../connect/sessao.php";
include "../ocorrencias/config.php";
$id = $_POST['id'];
$de = $_POST['de'];
$email = $_POST['email'];
$msn = $_POST['msn'];
$para = $_POST['para'];
$msg = $_POST['msg'];

$conexao = mysql_connect($db[host], $db[user], $db[senha]);
//PARA APARECER OS SMILES<BR>
include "smilie.php";

if($conexao) {
$sql = "INSERT INTO mural"
." (id, de, email, msn, para, msg)"
." VALUES('$id', '$de', '$email', '$msn', '$para', '$msg')";

if($de == "" || $msg == "" || $para == ""){
echo "
<script>
alert(\"Não foi possível enviar sua mensagem. \\nVolte e complete o formulário corretamente!\");
window.location = 'javascript:history.back(-1)';
</script>
";
}
else { $query = mysql_db_query($db[nome], $sql, $conexao);}
if ($query)
{ echo("
<script>
alert(\"$thanks_msg\");
window.location = '../painel.php?pagina1=ranking/c_rankrent.php';
</script>
");}
else
{ echo("
<script>
alert(\"$error_msg \n <b>ERRO:</b>".mysql_error()."\");
window.location = '../painel.php?pagina1=ranking/c_rankrent.php';
</script>
"); }
} else { echo("<b>Erro na tentativa de conexão.</b> ".mysql_error()."\n");}
mysql_close($conexao);
?>