<?php

require("../connect/conexao_conecta.php");

$func = $_REQUEST['func'];
$salario = $_REQUEST['salario'];
$vt = $_REQUEST['vt'];
$adi = $_REQUEST['adianta'];

$qtd = count($func);

for ( $i = 0 ; $i < $qtd ; $i++ ){
	$id_func = $func[$i];
	$fun_sal = $salario[$i];
	$fun_sal = str_replace('.','',$fun_sal);
	$fun_sal = str_replace(',','.',$fun_sal);
	
	$fun_vt  = $vt[$i];
	$fun_vt = str_replace('.','',$fun_vt);
	$fun_vt = str_replace(',','.',$fun_vt);
	
	$fun_adi = $adi[$i];
	$fun_adi = str_replace('.','',$fun_adi);
	$fun_adi = str_replace(',','.',$fun_adi);
	
	$sql_update = " UPDATE funcionario 
						SET 
							salario      = '$fun_sal',
							vt           = '$fun_vt',
							adiantamento = '$fun_adi'
					WHERE id = $id_func";
	$qry_upd = mysql_query($sql_update,$con) or die("ERRO SQL: $sql_update");
}
?>
<script language="javascript">
		alert("Registro gravado com sucesso ! ");
		window.location.href="../painel.php?pagina1=Franquias/funcionario_listagem.php&lista_ativo=S";
</script>
