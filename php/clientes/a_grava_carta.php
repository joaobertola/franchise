<?php
require "connect/sessao.php";
require "connect/funcoes.php";

function inverteData($p_data_padrao){
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

function envio_exclusao_negativos_spc_pf($protocolo,$con){
	# buscando os dados da divida, do devedor e do credor
	$sql_dados = "SELECT a.cpfcnpj, a.ncheque AS contrato, b.insc, date_format(a.vencimento,'%d%m%Y') as vencimento
				  FROM consulta.alertas a
				  INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
				  WHERE a.id = $protocolo";
	$qry_dados = mysql_query($sql_dados, $con);
	if ( mysql_num_rows($qry_dados) > 0 ){
		
		$doc_devedor	 = mysql_result($qry_dados,0,'cpfcnpj');
		$doc_devedor 	 = str_pad($doc_devedor, 11, 0, STR_PAD_LEFT);
		
		$num_contrato	= mysql_result($qry_dados,0,'contrato');
		$num_contrato	= str_pad($num_contrato, 20, ' ', STR_PAD_RIGHT);
		
		$cnpj_empresa 	= mysql_result($qry_dados,0,'insc');
		$cnpj_empresa 	= str_pad($cnpj_empresa, 15, 0, STR_PAD_LEFT);
		
		$vencimento 	= mysql_result($qry_dados,0,'vencimento');
		$vencimento		= str_pad($vencimento, 8, 0, STR_PAD_LEFT);

		# Buscando Nome do Devedor
		$sql_nome = "SELECT a.Nom_Nome, date_format(b.data_nascimento,'%d%m%Y') as data_nascimento FROM base_inform.Nome_Brasil a
					 LEFT OUTER JOIN base_inform.Nome_DataNascimento b on a.Nom_CPF = b.CPF
					 WHERE a.Nom_CPF = '$doc_devedor'
					 ORDER BY a.Origem_Nome_id, a.id DESC
					 LIMIT 1";
		$qry_nome 	= mysql_query($sql_nome, $con);
		$nome_devedor = mysql_result($qry_nome,0,'Nom_Nome');
		$nome_devedor = str_pad($nome_devedor, 60, ' ', STR_PAD_RIGHT);
		$data_nascimento = mysql_result($qry_nome,0,'data_nascimento');
		
		$string_envio = "codigo=50008&senha=62835&cnpj_empresa=$cnpj_empresa&contrato=$num_contrato&nome=$nome_devedor&data_nascimento=00000000&cpf=$doc_devedor&motivo=16&data_atraso=$vencimento";
		$ch = curl_init();   
		curl_setopt($ch, CURLOPT_URL,'http://www.3ccomunicacao.com.br/webservice/baixa/pf/string.php');
		curl_setopt($ch, CURLOPT_REFERER,'http://www.3ccomunicacao.com.br/webservice/baixa/pf/string.php');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,4);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$string_envio);
		$html = curl_exec ($ch); 
		curl_close ($ch);
		# Tratamento do Retorno
		$codigo_retorno = substr($html,109,3);
		if ( $codigo_retorno == '001' ){
			# mensagem de erro SPC
			$qtd_erro = substr($html,120,2);
			$cod_erro = substr($html,122,5);
			$msg_erro = substr($html,127,37);
			
			$sql_erro = "INSERT INTO cs2.mensagem_cs2(data_cad, hora_cad, mensagem) values( NOW(), NOW(), 'Envio: [$string_envio] Retorno: [$html]' )";
			$qry_erro = mysql_query($sql_erro,$con) or die("Erro: $sql_erro");			

		}elseif ( $codigo_retorno == '104' ){
			
			$msg     = "EXC : SPC OK - Envio [$string_envio] Retorno: [$html]";
			$sql_ok  = "INSERT INTO cs2.mensagem_cs2(data_cad, hora_cad, mensagem) values( NOW(), NOW(), '$msg' )";
			$qry_ok  = mysql_query($sql_ok,$con)  or die("Erro: $sql_ok");
			
			$sql_OK  = "UPDATE consulta.alertas SET dt_exclusao_spc = NOW() WHERE id = $protocolo";
			$qry_OK  = mysql_query($sql_OK,$con) or die("Erro: $sql_OK");
			
		}else{
			$msg = "EXC : SPC ERRO: Envio [$string_envio] Retorno: [$html]";
			$sql_erro = "INSERT INTO cs2.mensagem_cs2(data_cad, hora_cad, mensagem) values( NOW(), NOW(), '$msg' )";
			$qry_erro = mysql_query($sql_erro,$con) or die("Erro: $sql_erro");
			
		}
	}
}

function envio_exclusao_negativos_spc_pj($protocolo,$con){
	# buscando os dados da divida, do devedor e do credor
	$sql_dados = "SELECT a.cpfcnpj, a.ncheque AS contrato, b.insc, 
				  date_format(a.vencimento,'%d%m%Y') as vencimento, a.valor
				  FROM consulta.alertas a
				  INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
				  WHERE a.id = $protocolo";
	$qry_dados = mysql_query($sql_dados, $con);
	if ( mysql_num_rows($qry_dados) > 0 ){
		
		$doc_devedor	 = mysql_result($qry_dados,0,'cpfcnpj');
		$doc_devedor 	 = str_pad($doc_devedor, 14, 0, STR_PAD_LEFT);
		
		$valor	 = mysql_result($qry_dados,0,'valor');
		$valor 	 = str_pad($doc_devedor, 14, 0, STR_PAD_LEFT);
		
		$num_contrato	= mysql_result($qry_dados,0,'contrato');
		$num_contrato	= str_pad($num_contrato, 20, ' ', STR_PAD_RIGHT);
		
		$cnpj_empresa 	= mysql_result($qry_dados,0,'insc');
		$cnpj_empresa 	= str_pad($cnpj_empresa, 14, 0, STR_PAD_LEFT);
		
		$vencimento 	= mysql_result($qry_dados,0,'vencimento');
		$vencimento		= str_pad($vencimento, 8, 0, STR_PAD_LEFT);

		# Buscando Nome do Devedor
		$sql_nome = "SELECT Nom_Nome FROM base_inform.Nome_Brasil a
					 WHERE Nom_CPF = '$doc_devedor'
					 ORDER BY Origem_Nome_id, id DESC
					 LIMIT 1";
		$qry_nome 	= mysql_query($sql_nome, $con);
		$nome_devedor = mysql_result($qry_nome,0,'Nom_Nome');
		$nome_devedor = str_pad($nome_devedor, 60, ' ', STR_PAD_RIGHT);
		$data_nascimento = mysql_result($qry_nome,0,'data_nascimento');
		
		$string_envio = "codigo=50008&senha=62835&cnpj_credor=$cnpj_empresa&cnpj=$doc_devedor&titulo=$num_contrato&motivo=16&razaosocial=$nome_devedor&data_atraso=$vencimento&valor_parcela=$valor";
		$ch = curl_init();   
		curl_setopt($ch, CURLOPT_URL,'http://www.3ccomunicacao.com.br/webservice/baixa/pf/string.php');
		curl_setopt($ch, CURLOPT_REFERER,'http://www.3ccomunicacao.com.br/webservice/baixa/pf/string.php');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,4);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$string_envio);
		$html = curl_exec ($ch); 
		curl_close ($ch);
		# Tratamento do Retorno
		$codigo_retorno = substr($html,109,3);
		if ( $codigo_retorno == '001' ){
			# mensagem de erro SPC
			$qtd_erro = substr($html,120,2);
			$cod_erro = substr($html,122,5);
			$msg_erro = substr($html,127,37);
			
			$sql_erro = "INSERT INTO cs2.mensagem_cs2(data_cad, hora_cad, mensagem) values( NOW(), NOW(), 'Envio: [$string_envio] Retorno: [$html]' )";
			$qry_erro = mysql_query($sql_erro,$con) or die("Erro: $sql_erro");			

		}elseif ( $codigo_retorno == '104' ){
			
			$msg     = "EXC : SPC OK - Envio [$string_envio] Retorno: [$html]";
			$sql_ok  = "INSERT INTO cs2.mensagem_cs2(data_cad, hora_cad, mensagem) values( NOW(), NOW(), '$msg' )";
			$qry_ok  = mysql_query($sql_ok,$con)  or die("Erro: $sql_ok");
			
			$sql_OK  = "UPDATE consulta.alertas SET dt_exclusao_spc = NOW() WHERE id = $protocolo";
			$qry_OK  = mysql_query($sql_OK,$con) or die("Erro: $sql_OK");
			
		}else{
			$msg = "EXC : SPC ERRO: Envio [$string_envio] Retorno: [$html]";
			$sql_erro = "INSERT INTO cs2.mensagem_cs2(data_cad, hora_cad, mensagem) values( NOW(), NOW(), '$msg' )";
			$qry_erro = mysql_query($sql_erro,$con) or die("Erro: $sql_erro");
			
		}
	}
}

$data_doc 		     = inverteData($_REQUEST['data_doc']);
$tipo_documento      = $_REQUEST['tipo_documento'];
$id_mot_cancelamento = $_REQUEST['id_mot_cancelamento'];
$ultima_fatura       = inverteData($_REQUEST['ultima_fatura']);
$sitcli 		     = $_REQUEST['sitcli'];
$sit_cobranca 	     = $_REQUEST['sit_cobranca'];

$pendencia_contratual= $_REQUEST['pendencia_contratual'];
$dt_regularizacao    = inverteData($_REQUEST['dt_regularizacao']);
$alterar             = $_REQUEST['alterar'];
$pendencia_contrato  = $_REQUEST['pendencia_contrato'];
$senha_user          = $_REQUEST['senha_user'];
$data_suspensao      = $_REQUEST['data_suspensao'];

switch($_REQUEST['acao']){

	//GRAVA A CARTA
	case '1':
		if( ($_SESSION['id'] == 163) or ($_SESSION['id'] == 46) or ($_SESSION['id'] == 4) or ($_SESSION['id'] == 1204) ) {
			
			$sql_libera_acesso = "UPDATE cs2.logon SET sitlog = 0 where codloja='{$_REQUEST['codloja']}'";
			$qry_libera_acesso = mysql_query ($sql_libera_acesso, $con);

			$sql_p = "UPDATE cs2.cadastro 
						  SET pendencia_contrato = '$pendencia_contrato'
					 WHERE codloja = '{$_REQUEST['codloja']}'";
			$qry_p = mysql_query ($sql_p, $con);
			
			$sql = "SELECT id FROM cs2.funcionario WHERE senha = '$senha_user'";
			$qry = mysql_query ($sql, $con);
			$id_user = mysql_result($qry,0,'id');
			if ( $id_user == '' ) $id_user = 0;
			
			if($alterar == "S" ){
				if ( $dt_regularizacao == '--' and $pendencia_contratual == 0 ){
					echo "<script>alert('Favor informar a data da regulaizacao.');history.back()</script>";
				}else{
					$sql_p = "UPDATE cs2.cadastro 
							  SET 
									dt_regularizacao     = '$dt_regularizacao',
									pendencia_contratual = '$pendencia_contratual',
									user_pendencia       = '$id_user'
							  WHERE codloja = '{$_REQUEST['codloja']}'";
		
					$qry_p = mysql_query ($sql_p, $con);		
				}
			}
		}
		if ( $data_doc <> '--' ){	
		    
			//VERIFICA SE TEM A CARTA PARA CRIAR 
			$sql   = "SELECT COUNT(*)AS total FROM cs2.pedidos_cancelamento WHERE codloja = '{$_REQUEST['codloja']}'";
			$qry   = mysql_query ($sql, $con);		
			$total = mysql_result($qry,0,'total');
			if($total > 0){
				$sql_carta = "UPDATE cs2.pedidos_cancelamento SET
								  data_documento	  = '$data_doc',
								  tipo_documento      = '$tipo_documento',
								  id_mot_cancelamento = '$id_mot_cancelamento',
								  ultima_fatura       = '$ultima_fatura'
							  WHERE
							  	  codloja = '{$_REQUEST['codloja']}'";
				$qry_carta = mysql_query ($sql_carta, $con);		
			}else{
				$sql_carta = "INSERT INTO cs2.pedidos_cancelamento
				(data_documento, tipo_documento, id_mot_cancelamento, ultima_fatura, codloja, data_registro)
				VALUES('$data_doc', '$tipo_documento', '$id_mot_cancelamento', '$ultima_fatura', '{$_REQUEST['codloja']}', now())";
				$qry_carta = mysql_query ($sql_carta, $con);
			}
		}
		//ALTERA A SITUAÇÃO DO CLIENTE
		if ( $sitcli <> '' )
			$comp = " sitcli = '$sitcli', ";
			
		$data_suspenso = '';

		if ( $sitcli == 5){
			if ( $data_suspensao != '' ){
				$data_suspenso = ', data_suspenso = '."'".inverteData($data_suspensao)."'";

				$sql_I = $sql2 = "INSERT INTO cs2.cadastro_log(codloja,acao)
                                  VALUES({$_REQUEST['codloja']},'Acesso suspenso')";
         		$qry_I = mysql_query ($sql_I, $con);
			}
		}else{
			$data_suspenso = ", data_suspenso = null ";
		}

		$sql_cad = "UPDATE cs2.cadastro SET 
					$comp
					sit_cobranca = '$sit_cobranca'
					$data_suspenso
				    WHERE codloja = '{$_REQUEST['codloja']}'";
		
		$qry_cad = mysql_query ($sql_cad, $con);
		
		if ( $sitcli == 2 ){
			# Cliente CANCELADO, 
			# Selecionando todas as NEGATIVACOES realizadas pelo cliente
			$sql_alerta = "SELECT id, tipo FROM consulta.alertas
						   WHERE codloja = '{$_REQUEST['codloja']}' and situacao = 'N'";
			$qry_alerta = mysql_query ($sql_alerta, $con);
			if ( mysql_num_rows($qry_alerta) > 0){
				while ( $reg = mysql_fetch_array($qry_alerta) ){
					$protocolo = $reg['id'];
					if ( $tipo == '0' ) envio_exclusao_negativos_spc_pf($protocolo,$con);
					else envio_exclusao_negativos_spc_pj($protocolo,$con);
					# baixa todas as NEGATIVACOES REALIZADAS POR ELE.
					$sql_alerta2 = "UPDATE consulta.alertas 
									SET 
										data_exclusao = NOW(),
										situacao = 'E'
								   WHERE id = '$protocolo' ";
					$qry_alerta2 = mysql_query ($sql_alerta2, $con);
				}
			}
		}
		
	break;
	
	
    //CANCELA A CARTA
	case '2':
		//EXCLUI A CARTA DE CANCELAMENTO
		$sql = "DELETE FROM cs2.pedidos_cancelamento WHERE codloja = '{$_REQUEST['codloja']}'";
		$res = mysql_query ($sql, $con);		
		
		//ALTERA A SITUAÇÃO DO CLIENTE
		$sql_cad = "UPDATE cs2.cadastro SET 
						sitcli 		 = '$sitcli', 
						sit_cobranca = '$sit_cobranca'
				    WHERE codloja = '{$_REQUEST['codloja']}'";
		$qry_cad = mysql_query ($sql_cad, $con);		
	
	break;
	
	case '3':
		 if( ($_SESSION['id'] == 163) or ($_SESSION['id'] == 46) or ($_SESSION['id'] == 4) ) {
			$sql_p = "UPDATE cs2.cadastro 
					  SET 
							dt_regularizacao     = '$dt_regularizacao',
							pendencia_contratual = '$pendencia_contratual'
					  WHERE codloja = '{$_REQUEST['codloja']}'";
			$qry_p = mysql_query ($sql_p, $con);
		 }
	break;	
}

if($_REQUEST['acao'] == 3){ ?>
<script language="javascript">
	window.location.href="painel.php?pagina1=clientes/a_cons_cnpj.php&cnpj=<?=$_REQUEST['cnpj']?>";
</script>
<?php } ?>

<script language="javascript">
	window.location.href="painel.php?pagina1=clientes/a_cons_id.php&id=<?=$_REQUEST['codloja']?>";
</script>
