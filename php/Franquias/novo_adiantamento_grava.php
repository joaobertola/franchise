<?

require("../connect/conexao_conecta.php");

function converteDataGravaBanco($p_data_padrao){
       $dia = substr($p_data_padrao, 0,2);
	   $mes = substr($p_data_padrao, 3,2);
	   $ano = substr($p_data_padrao, 6,9);	
	   $data_bd.=$ano;
	   $data_bd.="-";
	   $data_bd.=$mes;
	   $data_bd.="-";
	   $data_bd.=$dia;
	   return $data_bd;
}

session_start();

$data       = converteDataGravaBanco($_REQUEST['data']);
$descricao  = $_REQUEST['descricao'];
$valor      = $_REQUEST['valor'];
$valor		= str_replace('.','',$valor);
$valor		= str_replace(',','.',$valor);


$sql = "INSERT INTO adiantamento_funcionario( data, valor, descricao, id_func )
		VALUES('$data','$valor','$descricao',{$_REQUEST['id_func']})";
$qry = mysql_query($sql,$con) or die("ERRO SQL: $sql");

?>

<script language="javascript">
		alert("Registro gravado com sucesso ! ");
		window.location.href="../painel.php?pagina1=Franquias/funcionario_alterar.php&id=<?=$_REQUEST['id_func']?>";
</script>