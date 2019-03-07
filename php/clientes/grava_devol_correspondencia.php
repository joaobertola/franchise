<?

require "../connect/conexao_conecta.php";

echo "<pre>";
print_r( $_REQUEST );
exit;

$compo_alterar = $_REQUEST['compo_alterar'];
$numero_semana = $_REQUEST['numero_semana'];
$diasemana = strtolower($_REQUEST['dia_semana']);
$linha = $_REQUEST['linha'];
$valor = $_REQUEST['valor'];

$campo = $compo_alterar."_".$diasemana."_".$linha." = '$valor' ";
	
$sql = "SELECT count(*) as qtd FROM cs2.oficina_treinamento
		WHERE numero_semana = '$numero_semana'";
$qry = mysql_query($sql,$con) or die ("$sql");
$qtd = mysql_result($qry,0,'qtd');
if ( $qtd == '' ) $qtd = 0;

if ( $qtd == 0 ){
	$sql = "INSERT INTO cs2.oficina_treinamento(numero_semana)
			VALUES ('$numero_semana')";
	$qry = mysql_query($sql,$con) or die ("$sql");
}

$sql = "UPDATE cs2.oficina_treinamento
			SET $campo
		WHERE numero_semana = '$numero_semana'";
$qry = mysql_query($sql,$con) or die ("$sql");
?>