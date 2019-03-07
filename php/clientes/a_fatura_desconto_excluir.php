<meta charset="iso-8859-1" />
<?php

	require ("connect/sessao.php");

	$numdoc = $_REQUEST['numdoc'];
	$id 	= $_REQUEST['id'];
	
	// localizando valores para calculo
	
	$sql_val = "SELECT a.valor, b.valor as valor_titulo, b.codloja FROM cs2.vr_extra_faturado a
				INNER JOIN cs2.titulos b ON a.numdoc = b.numdoc
				WHERE a.numdoc = '$numdoc' AND a.id = '$id'";
	$qry_val = mysql_query($sql_val,$con) or die ("Erro SQL : $sql_val");
	
	
	$valor = mysql_result($qry_val,0,'valor');
	$valor_titulo = mysql_result($qry_val,0,'valor_titulo');
	$codloja = mysql_result($qry_val,0,'codloja');

        // Gravando a Movimentacao de Titulo
        $sql_insert = "INSERT INTO cs2.titulos_movimentacao( numdoc, desconto )
                       VALUES( '$numdoc', '$valor' )";
        $qry_insert = mysql_query($sql_insert,$con) or die("Erro SQL : $sql_insert");
        
        
	if ( $valor > 0 )
		$vr_atualizado = $valor_titulo - $valor;
	else{
		$valor = str_replace('-','',$valor);
		$vr_atualizado = $valor_titulo + $valor;
	}
	
	$sql_update = " UPDATE cs2.titulos SET valor = '$vr_atualizado'
					WHERE numdoc = '$numdoc'";
	$qry_update = mysql_query($sql_update,$con) or die("Erro SQL : $sql_update");

	
	$sql_delete = " DELETE FROM cs2.vr_extra_faturado
					WHERE numdoc = '$numdoc' AND id = '$id'";
	$qry_insert = mysql_query($sql_delete,$con) or die("Erro SQL : $sql_delete");
	
?>
<script>
	alert('Registro EXCLU�DO com sucesso !');
	window.location.href="https://www.webcontrolempresas.com.br/franquias/php/painel.php?pagina1=clientes/a_ver_faturas.php&codloja=<?=$codloja?>";
</script>