<?php
	require "connect/sessao.php";
	
	# Atualizando a tabela titulo
	
	//echo "<pre>";
	
	$data_pgto = date('Y-m-d');	
	
	$numdoc			= $_REQUEST['numdoc'];
	$codloja		= $_REQUEST['codloja'];
	$valor_cobrado	= $_REQUEST['valor_cobrado'];
	$vencimento		= $_REQUEST['vencimento'];
	$logon          = $_REQUEST['logon'];
	$numboleto		= $_REQUEST['numboleto'];
	
	$valor_cobrado	= str_replace(".","",$valor_cobrado);
	$valor_cobrado	= str_replace(",",".",$valor_cobrado);
	
	$saldo_recupere = $_REQUEST['saldo_recupere'];
	
	$saldo_recupere = str_replace(".","",$saldo_recupere);
	$saldo_recupere = str_replace(",",".",$saldo_recupere);
	
	$saldo_atualizado = $saldo_recupere - $valor_cobrado;
	
	# Verificando se o titulo já foi baixado como COMPENSADO
	$sql_ver = "SELECT COUNT(*) AS qtd FROM cs2.contacorrente_recebafacil WHERE numboleto = '$numboleto'";
	$qry_ver = mysql_query($sql_ver,$con) or die ("ERRO SQL  ==>  $sql_ver");
	$campos = mysql_fetch_array($qry_ver);
	$qtd 	= substr($campos["qtd"],0,10);
	
	if ( $qtd > 0 ){
		
		echo "<script language='javascript'>
				alert(' Título já baixado como [ COMPENSAÇÃO ] ');
			  </script>";
		exit;
		
	}else{
	
		# BAIXANDO TITULO COMO RECEBIDO COMPENSACAO
		$query = "	UPDATE cs2.titulos 
					SET valorpg = $valor_cobrado, datapg = '$data_pgto', origem_pgto = 'COMP' 
					WHERE numdoc = '$numdoc'";
		mysql_query($query,$con) or die ("ERRO SQL  ==>  $sql_cc_crediario");

		# VERIFICANDO SE O CLIENTE TEM ALGUMA FATURA EM ABERTO, CASO NAO TENHA NENHUMA FATURA, DESBLOQUEIA O ACESSO
	
		$sql 	= "SELECT subdate(now(), interval 30 day) data ";
		$qr 	= mysql_query($sql,$con)or die ("ERRO SQL  ==>  $sql");
		$campos = mysql_fetch_array($qr);
		$data 	= substr($campos["data"],0,10);
		$sql 	= "SELECT count(*) qtd FROM cs2.titulos WHERE codloja=$codloja and vencimento <= '$data' and datapg is null";
		$qr 	= mysql_query($sql,$con)or die ("ERRO SQL  ==>  $sql");
		$campos = mysql_fetch_array($qr);
		$xqtd 	= substr($campos["qtd"],0,10);

		if ( $xqtd == 0 ){
			$sqlx="Update cs2.logon set sitlog=0 where codloja=$codloja";
			mysql_query($sqlx,$con)or die ("ERRO SQL  ==>  $sqlx");
		}

		# Localizando o ID  da franquia deste cliente
		$sql_franquia = "SELECT a.id_franquia, b.classificacao
						 FROM cs2.cadastro a
						 INNER JOIN cs2.franquia b ON a.id_franquia = b.id 
						 WHERE a.codloja = '$codloja'";
		$qr_franquia 	= mysql_query($sql_franquia,$con)or die ("ERRO SQL  ==>  $sql_franquia");
		$campo	 		= mysql_fetch_array($qr_franquia);
		$id_franquia	= $campo["id_franquia"];
		$classificacao  = $campo["classificacao"];
	
		# Verificando novamente se o titulo já foi baixado como COMPENSADO
		$sql_ver = "SELECT COUNT(*) AS qtd FROM cs2.contacorrente_recebafacil WHERE numboleto = '$numboleto'";
		$qry_ver = mysql_query($sql_ver,$con) or die ("ERRO SQL  ==>  $sql_ver");
		$campos = mysql_fetch_array($qry_ver);
		$qtd 	= substr($campos["qtd"],0,10);
		if ( $qtd > 0 ){
		
			echo "<script language='javascript'>
					alert(' Título já baixado como [ COMPENSAÇÃO ] ');
					window.location.href='painel.php?pagina1=clientes/a_ver_faturas.php&codloja=<?=$codloja?>';
				  </script>";
			exit;
			
		}else{
	
			# LANCAMENTO DO DEBITO NA CONTA CORRENTE DO CLIENTE
			$sql_cc_crediario = "INSERT INTO cs2.contacorrente_recebafacil(data, codloja, discriminacao, datapgto, 
																		valor, operacao,comprovante, saldo, tarifa_bancaria, numboleto)
							 VALUES('$data_pgto','$codloja','compensacao de mensalidades em atraso [$logon]' , '$data_pgto',
							 		'$valor_cobrado', '1', 'MENSALIDADE VENCIMENTO EM $vencimento', $saldo_atualizado, '0.00', '$numboleto' )";
			$qr_sql_cc_crediario = mysql_query($sql_cc_crediario,$con) or die ("ERRO SQL  ==>  $sql_cc_crediario");
			 
			# LANCAMENTO DO CREDITO NA CONTA CORRENTE DO FRANQUEADO
			
			if ( $classificacao == 'X' ){
				# Micro franqueado, calculando o percentual.
				$mes = substr($vencimento,5,2);
				$ano = substr($vencimento,0,4);
				if ( $mes == 1 ){
					$mes = '12';
					$ano -= 1;
				}else $mes -= 1;
				
				$mes = str_pad($mes,2,0,STR_PAD_LEFT);
				// CALCULANDO OS CONTRATOS FEITOS NO MES ANTERIOR
				$sql_calc = "SELECT count(*) as qtd from cs2.cadastro a
							 WHERE month(a.dt_cad) = '$mes' 
							 	AND year(a.dt_cad) = '$ano' 
								AND a.sitcli = 0
								AND id_franquia = $id_franquia";
				$qry_calc = mysql_query($sql_calc,$con);
				$qtd_ctr = mysql_result($qry_calc,0,'qtd');
				
				if ( $qtd_ctr < 20 ) $perc = '20';
				else $perc = '25';
	
				$valor_cobrado = $valor_cobrado * ($perc/100);
				$perc =  " [$perc %]";
			}else
				$perc = '';	


			$sql_cc_franqueado = "INSERT INTO contacorrente(franqueado,data,discriminacao,valor,operacao)
								  VALUES( $id_franquia, '$data_pgto' , '$logon ( comp cred/recup/bol venc : $vencimento ) $perc', $valor_cobrado,'0' )";
			$qr_sql_cc_franqueado = mysql_query($sql_cc_franqueado,$con)or die ("ERRO SQL  ==>  $sql_cc_franqueado");
		}
	}			 
	?>
	<script language="javascript">
		alert("Titulo baixado com sucesso !!! ");
		window.location.href="painel.php?pagina1=clientes/a_ver_faturas.php&codloja=<?=$codloja?>";
	</script>