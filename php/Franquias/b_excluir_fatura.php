<?php
require "connect/sessao.php";

$usuario = $_SESSION["id"];

if( $_SESSION["id"] <> '163' and $_SESSION["id"] <> 46){
	echo "Usuário sem permissao";
	exit;
}

$sql = "INSERT INTO cs2.monitoria(usuario,tabela,cliente,cmd_sql)
        VALUES('$usuario','cs2.titulos','$numdoc','DELETE FROM cs2.titulos WHERE numdoc = $numdoc')";
$qry = mysql_query($sql,$con);

$numdoc = $_REQUEST['numdoc'];

$sql_delete = "DELETE FROM cs2.titulos WHERE numdoc = '$numdoc'";
$qry_delete = mysql_query($sql_delete,$con);


if($_REQUEST['retorna'] == '1'){?>
<script language="javascript">
	alert('TÍTULO   EXCLUÍDO   COM   SUCESSO !!!');
	window.location.href="painel.php?pagina1=clientes/a_cons_id.php&id=<?=$_REQUEST['codloja']?>";
</script>
<?php } else { ?>
<script language="javascript">
	alert('TÍTULO   EXCLUÍDO   COM   SUCESSO !!!');
	window.location.href="painel.php?pagina1=Franquias/b_baixafatura.php";
</script>
<?php } ?>