<?php
	require "connect/sessao.php";
	require "connect/conexao_conecta.php";
	require "connect/funcoes.php";
	//require "controller/CreateBaseDadosController.php";
	//require "model/CreateBaseDadosModel.php";
	require "smtp.class.php";
	
	function enviaErroCpd($p_parametro){
		$data = date("d/m/Y");
		$hora = date("H:m:s");
		$smtp = new Smtp("10.2.2.7"); // host do servidor SMTP 
		$smtp->user = "financeiro@webcontrolempresas.com.br"; // usuario do servidor SMTP
		$smtp->pass = "infsys321"; // senha dousuario do servidor SMTP
		$smtp->debug = true; // ativar a autenticão SMTP
		$to = "erro_sistema@webcontrolempresas.com.br";
		$from = "erro_sistema@webcontrolempresas.com.br";
		$assunto = "Erro Cadastro de Cliente ";
		$msg .= "Data: $data \n ";
		$msg .= "Hora: $hora \n";
		$msg .= "Local do SQL: $p_parametro";
		$smtp->Send($to, $from, $assunto, $msg);
	}
	
	function substitui_acentos($value){ 
		$trocaeste=array( "(", ")","'","�","�","�","�","�","�","�","�","�","�","�","�","�","�",";","'","�"); 
		$poreste=array( "", "","","O","C","U","U","O","O","O","O","A","A","A","A","E","I","","",""); 
		$value=str_replace($trocaeste,$poreste,$value); 
		$value = strtoupper($value);
		return $value; 
	}

	$login = '';
	
	//coloca a data de hoje
	$data = date('Y-m-d H:i:s');

	$razaosoc     = str_replace("'","",$_POST['razaosoc']);
	$nomefantasia = str_replace("'","",$_POST['nomefantasia']);
	
	$razaosoc       = substitui_acentos($razaosoc);
	$nomefantasia   = substitui_acentos($nomefantasia);
	
	$atendente_resp = substitui_acentos($_POST['atendente_resp']);
	$insc 		      = $_POST['insc'];
	if ( strlen($insc > 11 ) ) $Tipo = 1;
	else $Tipo = 0;
	$uf 	     	    = $_POST['uf'];
	$localidade     = substitui_acentos($_POST['localidade']);
	$bairro       	= substitui_acentos($_POST['bairro']);
	$logradouro	    = substitui_acentos($_POST['logradouro']);
	$numero		      = substitui_acentos($_POST['numero']);
	$complemento    = substitui_acentos($_POST['complemento']);
	
	$cep 		= $_POST['cep'];
	$fone 		= $_POST['fone'];
	$fax 		= $_POST['fax'];
	$email 		= $_POST['email'];
	$assinatura = $_POST['assinatura'];
	$pacote		= $_POST['pacote'];

	$vr_loja_virtual = $_POST['loja_virtual'];
	$vr_pesq_cred	 = $_POST['pesq_cred'];
	$vr_rec_deved	 = $_POST['rec_deved'];
	$vr_aum_venda    = $_POST['aum_venda'];

	$obs 		= substitui_acentos($_POST['obs']);
	$contrato 	= $_POST['contrato'];
	$franqueado = substitui_acentos($_POST['franqueado']);
	$id_ramo 	= $_POST['id_ramo'];
	$celular 	= $_POST['celular'];
	$fone_res 	= $_POST['fone_res'];
	$socio1 	= substitui_acentos($_POST['socio1']);
	$socio2 	= substitui_acentos($_POST['socio2']);
	$cpfsocio1 	= $_POST['cpfsocio1'];
	$cpfsocio2 	= $_POST['cpfsocio2'];
	$agendador  = $_POST['id_agendador'];
	
	$fatura 	= $_POST['fatura'];
	$vendedor 	= $_POST['vendedor'];
	$origem 	  = substitui_acentos($_POST['origem']);
	
	//altera��o para nota fiscal
	$inscricao_estadual = str_replace("'","",$_REQUEST['inscricao_estadual']);
	$inscricao_estadual = str_replace(" ","",$inscricao_estadual);
	$cnae_fiscal = str_replace("'","",$_REQUEST['cnae_fiscal']);
	$cnae_fiscal = str_replace(" ","",$cnae_fiscal);
	$inscricao_municipal = str_replace("'","",$_REQUEST['inscricao_municipal']);
	$inscricao_municipal = str_replace(" ","",$inscricao_municipal);
	$inscricao_estadual_tributario = str_replace("'","",$_REQUEST['inscricao_estadual_tributario']);
	$inscricao_estadual_tributario = str_replace(" ","",$inscricao_estadual_tributario);
	
	//trata as variaveis para o formato padr�o
	$fone=str_replace("(","",$fone);
	$fone=str_replace(")","",$fone);
	$fone=str_replace("-","",$fone);
	$fone=str_replace(" ","",$fone);
	
	$fax=str_replace("(","",$fax);
	$fax=str_replace(")","",$fax);
	$fax=str_replace("-","",$fax);
	$fax=str_replace(" ","",$fax);
	
	$celular=str_replace("(","",$celular);
	$celular=str_replace(")","",$celular);
	$celular=str_replace("-","",$celular);
	$celular=str_replace(" ","",$celular);
	
	$fone_res=str_replace("(","",$fone_res);
	$fone_res=str_replace(")","",$fone_res);
	$fone_res=str_replace("-","",$fone_res);
	$fone_res=str_replace(" ","",$fone_res);
	
	$insc=str_replace("/","",$insc);
	$insc=str_replace("-","",$insc);
	$insc=str_replace(".","",$insc);
	
	$cpfsocio1=str_replace("/","",$cpfsocio1);
	$cpfsocio1=str_replace("-","",$cpfsocio1);
	$cpfsocio1=str_replace(".","",$cpfsocio1);
	
	$cpfsocio2=str_replace("/","",$cpfsocio2);
	$cpfsocio2=str_replace("-","",$cpfsocio2);
	$cpfsocio2=str_replace(".","",$cpfsocio2);
	
	$contador_nome = $_REQUEST['contador_nome'];
	$contador_telefone = $_REQUEST['contador_telefone'];
	$contador_telefone = str_replace("(","",$contador_telefone);
	$contador_telefone = str_replace(")","",$contador_telefone);
	$contador_telefone = str_replace("-","",$contador_telefone);
	$contador_telefone = str_replace(" ","",$contador_telefone);
	
	$contador_celular = $_REQUEST['contador_celular'];
	$contador_celular = str_replace("(","",$contador_celular);
	$contador_celular = str_replace(")","",$contador_celular);
	$contador_celular = str_replace("-","",$contador_celular);
	$contador_celular = str_replace(" ","",$contador_celular);
	
	$contador_email1 = $_REQUEST['contador_email1'];
	$contador_email2 = $_REQUEST['contador_email2'];
	
	$cep=str_replace("-","",$cep);

	$servidor = 'http://consultaoperadora.telein.com.br/sistema/consultas_resumidas.php';
	$dadosEnv = 'chave=8d1b12d23b5362695071&numeros='.$celular;

	$ch = curl_init();
	//endereço para envio do post
	curl_setopt ($ch, CURLOPT_URL, $servidor);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	// envio do parametros
	curl_setopt($ch, CURLOPT_POSTFIELDS, $dadosEnv);
	$conteudo = curl_exec($ch);
	if (curl_errno($ch)) {
		print curl_error($ch);
	} else {
		curl_close($ch);
	}

	$arrRetorno = explode('#',$conteudo);
	$idOperadora = $arrRetorno[0];

	if ( empty($vendedor) ) {
		echo "<script>alert(\"Preencha o campo [VENDEDOR] Obrigado !\");history.back();</script>";
		exit;
	}
	
	$sql8="SELECT COUNT(*) quant FROM cs2.cadastro WHERE insc='$insc'";
	$ql8=mysql_query($sql8,$con);
	$resp=mysql_fetch_array($ql8);
	$qtd=$resp["quant"];
	//verifica se tem cnpj duplicado
	if ($qtd > 0 ){
		echo "<script>alert(\"CNPJ Cadastrado para outro cliente, favor verificar!\");history.back();</script>";
		exit;
	}
	
	//captura o valor da assinatura mensal
	$cont_assinatura = 0;
	$variavel = "SELECT a.nome, b.tx_adesao 
				 FROM cs2.tabela_valor a 
				 INNER JOIN cs2.franquia b ON b.id=$franqueado
				 WHERE a.id='$pacote' 
				 AND a.categoria='$assinatura'";
	$response = mysql_query($variavel,$con);
	$cont_assinatura = mysql_num_rows($response);
	if($cont_assinatura == 0){
		echo "<script>alert(\"Erro na verficação de assinatura, entre em contato com o Departamento de Informatica !\");history.back();</script>";
		exit;
	}
	while ($array = mysql_fetch_array($response)) {
		$tx_mens = $array['nome'];
		$tx_adesao = $array['tx_adesao'];
	}
	if (empty($tx_mens)) $tx_mens = 0;
	if ($tx_mens == '0'){
		echo "<script>alert(\"Favor escolher o Pacote de Pesquisas novamente....!\");history.back();</script>";
		exit;
	}


	//cria um novo registro na tabela cadastro
	$comando = "INSERT INTO cs2.cadastro
					(
						atendente_resp, razaosoc, insc, nomefantasia, uf, cidade, bairro, end, numero, complemento, cep, 
						fone, fax, email, tx_mens, tx_adesao, debito, diapagto, id_franquia, dt_cad, sitcli, obs, classificacao, 
						contrato, ramo_atividade, celular, fone_res, socio1, socio2, cpfsocio1, cpfsocio2, emissao_financeiro, 
						pendencia_contratual, id_consultor, origem, qtd_acessos, hora_cadastro, inscricao_estadual, cnae_fiscal,
						inscricao_municipal, inscricao_estadual_tributario, contador_nome, contador_telefone, contador_celular,
						contador_email1, contador_email2, id_agendador, id_operadora,nfce,nfe,liberar_nfe
					)
				VALUES
					(
						'$atendente_resp', '$razaosoc', '$insc', '$nomefantasia', '$uf', '$localidade', '$bairro', 
						'$logradouro', '$numero', '$complemento', '$cep', '$fone', '$fax', '$email', '$tx_mens', '$tx_adesao', 
						'B', '30', '$franqueado', now(), '0', '$obs', 'Mensal', '$contrato', '$id_ramo', '$celular', 
						'$fone_res', '$socio1', '$socio2', '$cpfsocio1', '$cpfsocio2', '$fatura', '1', '$vendedor', '$origem', 
						'0', now(), '$inscricao_estadual', '$cnae_fiscal', '$inscricao_municipal', 
						'$inscricao_estadual_tributario', '$contador_nome', '$contador_telefone', '$contador_celular',
						'$contador_email1', '$contador_email2','$agendador','$idOperadora','S','S','S'
					)
				";

// echo '<pre>';
// var_dump($comando);
// exit;

#	if($franqueado == 1){
#		echo '<pre>';
#		var_dump($comando);
#		die;
#	}


	$res = mysql_query ($comando, $con);
	$codloja = mysql_insert_id($con);
	if(!$res){
		echo "<script>alert(\"Erro na inserção do cliente, entre em contato com o Departamento de Informatica !\");history.back();</script>";
		exit;
	}

	if ( $vr_loja_virtual == 'NULL' ){
		$xSql = "UPDATE cs2.cadastro SET modulo_loja_virtual = NULL WHERE codloja = $codloja";
	}else{
		$vr_loja_virtual = str_replace(',','.',$vr_loja_virtual);
		$xSql = "UPDATE cs2.cadastro SET modulo_loja_virtual = '$vr_loja_virtual' WHERE codloja = $codloja";
	}
	mysql_query($xSql, $con);
	

	if ( $vr_pesq_cred == 'NULL' ){
		$xSql = "UPDATE cs2.cadastro SET modulo_pesq_credito = NULL WHERE codloja = $codloja";
	}else{
		$vr_pesq_cred = str_replace(',','.',$vr_pesq_cred);
		$xSql = "UPDATE cs2.cadastro SET modulo_pesq_credito = '$vr_pesq_cred' WHERE codloja = $codloja";
	}
	mysql_query($xSql, $con);

	if ( $vr_rec_deved == 'NULL' ){
		$xSql = "UPDATE cs2.cadastro SET modulo_receber_deved = NULL WHERE codloja = $codloja";
	}else{
		$vr_rec_deved = str_replace(',','.',$vr_rec_deved);
		$xSql = "UPDATE cs2.cadastro SET modulo_receber_deved = '$vr_rec_deved' WHERE codloja = $codloja";
	}
    mysql_query($xSql, $con);

	if ( $vr_aum_venda == 'NULL' ){
		$xSql = "UPDATE cs2.cadastro SET modulo_aumentar_vendas = NULL WHERE codloja = $codloja";
	}else{
		$vr_aum_venda = str_replace(',','.',$vr_aum_venda);
		$xSql = "UPDATE cs2.cadastro SET modulo_aumentar_vendas = '$vr_aum_venda' WHERE codloja = $codloja";
	}
	mysql_query($xSql, $con);


	// registrando log
	$teste = str_replace(chr(39),'',$comando);
	$sql = "INSERT INTO cs2.sql_cadastro(comando_sql,datahora) VALUES ('$teste',now())";
	mysql_query($sql, $con);
	
	// Gera a senha aleat�ria de 5 d�gitos
	require "senha_aleatoria.php";
	
	//isto serve para incrementar o �ltimo valor do c�digo
	$conecta  = "SELECT (logon + 1) as logon FROM cs2.controle";
	$resposta = mysql_query($conecta, $con);
	$codigo    = mysql_result($resposta,0,'logon');
	$login     = mysql_result($resposta,0,'logon');
	
	$sai = false;
	do{
		$sql = "SELECT COUNT(*) qtd FROM cs2.logon WHERE MID(logon,1,LOCATE('S', logon) - 1)='$codigo'";
		$ql8=mysql_query($sql,$con);
		$consulta=mysql_fetch_array($ql8);
		$qtd=$consulta["qtd"];
		if ( $qtd == 0){
			$sai = true;
			$logon = $codigo.'S'.$senha;
		}else{
			$codigo++;
		}
	}while ($sai == false);
	
	// atualizando na tabela controle o ultimo codigo gerado
	$conecta="UPDATE cs2.controle SET logon = $codigo";
	$resposta = mysql_query($conecta, $con);
	if(!$resposta){
		echo "<script>alert(\"Erro ao atualizar o controle,  entre em contato com o Departamento de Informatica !\");history.back();</script>";
		enviaErroCpd("Erro ao atualizar o controle linha 200, codlloja=$codloja, SQL=$sql");
		exit;
	}
	
	//cria o login e senha
	$command = "INSERT INTO cs2.logon (codloja, logon, dt_atv) VALUES ('$codloja', '$logon', '$data')";
	$result = mysql_query($command, $con);
	if(!$result){
		echo "<script>alert(\"Erro ao criar o Logon,  entre em contato com o Departamento de Informatica !\");history.back();</script>";
		enviaErroCpd("Erro ao criar o Logon linha 210, codlloja=$codloja, SQL=$sql");
		exit;
	}
	
	// caso for somente negativa��o
	if ( $assinatura == '7' ) {
		mysql_query("UPDATE cs2.cadastro SET classe='1' WHERE codloja='$codloja'", $con);
	}
	
	//insere tabela de pre�os e consultas liberadas
	$sql = "SELECT codcons,valor FROM cs2.valcons";
	$inserre = mysql_query($sql,$con);
	
	while ($registro= mysql_fetch_array($inserre)) {
	   $codcons = $registro["codcons"];
	   $valcons = $registro["valor"];
	
	   # Qtd Padrao
	   $qtd = '25';
	
	   if ( $codcons == 'A0208' || $codcons == 'A0301' ) $qtd = '25'; 		// 05 consultas para Pesquisa Ligth e Pesquisa Restritiva
	   else if ( $codcons == 'A0203' || $codcons == 'A0115' ) $qtd = '25'; 	// 25 consultas para Pesquisa Cartorial e Pesquisa Empresarial
	   else if ( $codcons == 'A0207' ) $qtd = '5'; 		// 5 consultas para Pesquisa Personnalite
	   else if ( $codcons == 'A0100' ) $qtd = '100'; 	// 100 consultas para Pesquisa BACEN
	   else if ( $codcons == 'A0231' ) $qtd = '5000'; 	// 5000 consultas para Pesquisa LOCALIZA NOVOS CLIENTES
	   else if ( $codcons == 'TM001' ) $qtd = '5000'; 	// 5000 Torpedo Marketing
	   else if ( $codcons == 'WM001' ) $qtd = '5000'; 	// 5000 WhatsApp Marketing
       else if ( $codcons == 'EM001' ) $qtd = '5000'; 	// 5000 Email Marketing

	   if ( $assinatura == '7' ) $qtd= '0';
	   
	   if ( substr($codcons,0,1) == 'F' ) $qtd = '20'; // Features
	   
	   $tabela = "INSERT INTO cs2.valconscli( codloja, codcons, valorcons ) VALUES('$codloja','$codcons','$valcons')";
	   $result1 = mysql_query($tabela, $con) or die ("Erro: $tabela");
	   $liberadas = "INSERT INTO cs2.cons_liberada VALUES ('$codloja','$codcons','$qtd','0')";
	   $result2 = mysql_query($liberadas, $con)  or die ("Erro: $liberadas");
	} 
	
	//insere consultas bonificadas
	
	$sql_t = "SELECT tpcons, qtd, tpcons2, qtd2 FROM cs2.tabela_valor WHERE id = '$pacote'";
	$qry_t = mysql_query($sql_t, $con);
	while($row_t = mysql_fetch_array($qry_t)){	
		$tpcons  = $row_t['tpcons'];
		$qtd	 = $row_t['qtd'];
		$tpcons2 = $row_t['tpcons2'];
		$qtd2	 = $row_t['qtd2'];
	
		//LOG
		$teste = "Resultado: Pacote 1: [$tpcons - $qtd] Pacote 2: [$tpcons2 - $qtd2]";
		$sql = "INSERT INTO cs2.sql_cadastro(comando_sql,datahora) VALUES ('$teste',now())";
		mysql_query($sql, $con);
	
		$sql = "INSERT INTO cs2.bonificadas (codloja, tpcons, qtd) VALUES( '$codloja', '$tpcons', '$qtd' )";
		$qry_bonficadas = mysql_query($sql, $con);
		if(!$qry_bonficadas){
			enviaErroCpd("Erro ao gravar as bonificadas linha 258, codlloja=$codloja, SQL=$sql");
			echo "<script>alert(\"Erro ao gravar as bonificadas,  entre em contato com o Departamento de Informatica !\");history.back();</script>";
			exit;
		}
		
		//LOG
		$teste = str_replace(chr(39),'',$sql);
		$sql = "INSERT INTO cs2.sql_cadastro(comando_sql,datahora) VALUES ('$teste',now())";
		mysql_query($sql, $con);
	
	
		if($qtd2 > 0){
			$sql = "INSERT INTO cs2.bonificadas (codloja, tpcons, qtd) VALUES( '$codloja', '$tpcons2', '$qtd2' )";
			$qry_bonficadas2 = mysql_query($sql, $con);
				if(!$qry_bonficadas2){
			enviaErroCpd("Erro ao gravar as bonificadas linha 273, codlloja=$codloja, SQL=$sql");
			echo "<script>alert(\"Erro ao gravar as bonificadas,  entre em contato com o Departamento de Informatica !\");history.back();</script>";
			exit;
				}
			
			$teste = str_replace(chr(39),'',$sql);
			$sql = "INSERT INTO cs2.sql_cadastro(comando_sql,datahora) VALUES ('$teste',now())";
			mysql_query($sql, $con);	
		}
	}

	$sql = "INSERT INTO cs2.historico_nfe(codloja,data,hora,mensagem)
				  VALUES('$codloja', NOW(), NOW(), 'Habilitado uso : NFe - NFCe (Usuário: franquiasnacional)')";
	$qry = mysql_query($sql, $con);
	
	// Cria uma conta de Email para o cliente novo;
	// verifica_email($franqueado,$codloja,$nomefantasia);
	
	grava_dados($insc, $Tipo, $razaosoc, $logradouro, $numero, $complemento, $bairro, $localidade, $uf, $cep, $email, $fone, $celular, $cpfsocio1, $socio1, $cpfsocio2, $socio2);
	
	// Gravando cliente para utilizar o WEBCONTROL
	// Criando Funcionario
	// Criando Usuario
	// Criando CLIENTE Balcao
	// Criando VENDEDOR Padrao
	// Aplicando todos os direitos

	// INICIO CRIAÇÃO DA BASE DE DADOS E DE SUAS RESPECTIVAS TABELAS
	/*
	$data_base_create = new CreateBaseDadosController();
	$data_base_create->setDatabase('db_'.$login);
	$data_base_create->create_data_base();
	$data_base_create->create_table_acesso_filiacao();
	$data_base_create->create_table_agenda();
	$data_base_create->create_table_agenda_usuario_parceiro();
	$data_base_create->create_table_agendamento_tarefa();
	$data_base_create->create_table_agendamento_tarefa_log();
	$data_base_create->create_table_assistencia_tecnica();
	$data_base_create->create_table_assistencia_tecnica_conclusao();
	$data_base_create->create_table_assistencia_tecnica_garantia();
	$data_base_create->create_table_assistencia_tecnica_marcas();
	$data_base_create->create_table_assistencia_tecnica_observacoes();
	$data_base_create->create_table_assistencia_tecnica_produtos();
	$data_base_create->create_table_assistencia_tecnica_voltagem();
	$data_base_create->create_table_atendimento();
	$data_base_create->create_table_atendimento_fornecedor();
	$data_base_create->create_table_atendimento_funcionario();
	$data_base_create->create_table_atendimento_tipo();
	$data_base_create->create_table_atendimento_transportadora();
	$data_base_create->create_table_autorizacao_cielo();
	$data_base_create->create_table_aux_venda_faturado_impressao();
	$data_base_create->create_table_auxiliar_envio_sms();
	$data_base_create->create_table_auxiliar_importacao_produto();
	$data_base_create->create_table_banco();
	$data_base_create->create_table_boleto_doc();
	$data_base_create->create_table_cadastro();
	$data_base_create->create_table_cadastro_aut_notas();
	$data_base_create->create_table_cadastro_controles();
	$data_base_create->create_table_cadastro_convenio_bancario();
	$data_base_create->create_table_cadastro_imposto_padrao();
	$data_base_create->create_table_cadastro_imposto_padrao_hist();
	$data_base_create->create_table_cargo();
	$data_base_create->create_table_carne();
	$data_base_create->create_table_carrinho();
	$data_base_create->create_table_cartaofid_cartao();
	$data_base_create->create_table_cartaofid_config();
	$data_base_create->create_table_cartaofid_historico();
	$data_base_create->create_table_cartaofid_modelo();
	$data_base_create->create_table_cartaofid_pedido_grafica();
	$data_base_create->create_table_catalogo();
	$data_base_create->create_table_cest();
	$data_base_create->create_table_cestt();
	$data_base_create->create_table_cest2();
	$data_base_create->create_table_cfop();
	$data_base_create->create_table_cidade();
	$data_base_create->create_table_classificacao();
	$data_base_create->create_table_classificacao_alteracao_valores();
	$data_base_create->create_table_classificacao_bancodeimagens();
	$data_base_create->create_table_classificacao_contas();
	$data_base_create->create_table_classificacao_sub();
	$data_base_create->create_table_classificacoes_removidas();
	$data_base_create->create_table_cli_recebafacil();
	$data_base_create->create_table_cliente();
	$data_base_create->create_table_cliente_agendamentos();
	$data_base_create->create_table_cliente_documento();
	$data_base_create->create_table_cliente_documentos();
	$data_base_create->create_table_cliente_forma_pagamento();
	$data_base_create->create_table_cliente_optica();
	$data_base_create->create_table_cliente_veiculo();
	$data_base_create->create_table_cliente_veiculos();
	$data_base_create->create_table_clientes_removidos();
	$data_base_create->create_table_cm_comanda();
	$data_base_create->create_table_cm_historico();
	$data_base_create->create_table_cm_mesa();
	$data_base_create->create_table_cm_producao();
	$data_base_create->create_table_cm_reserva();
	$data_base_create->create_table_cm_reserva_mesa();
	$data_base_create->create_table_cnae();
	$data_base_create->create_table_cnae_issqn();
	$data_base_create->create_table_compartilhamento();
	$data_base_create->create_table_compromisso();
	$data_base_create->create_table_conferencia_estoque();
	$data_base_create->create_table_conferencia_estoque_itens();
	$data_base_create->create_table_conferencia_estoque_itens_temp();
	$data_base_create->create_table_conta_corrente();
	$data_base_create->create_table_conta_corrente_movimentacao();
	$data_base_create->create_table_contador_cliente();
	$data_base_create->create_table_contas_comprovante();
	$data_base_create->create_table_contas_empresa();
	$data_base_create->create_table_contas_pagar();
	$data_base_create->create_table_contas_pagar_bkp();
	$data_base_create->create_table_contas_pagar_tpdoc();
	$data_base_create->create_table_controle_notafiscal();
	$data_base_create->create_table_credenciadora_cartao();
	$data_base_create->create_table_credenciadoras_fixas();
	$data_base_create->create_table_credenciadoras_fixas_ignorar();
	$data_base_create->create_table_cst();
	$data_base_create->create_table_dados_avaliacao();
	$data_base_create->create_table_descricao_contas_pagar();
	$data_base_create->create_table_descricao_contas_pagar_padrao();
	$data_base_create->create_table_documentos_arquivado();
	$data_base_create->create_table_documentos_pasta();
	$data_base_create->create_table_encaminhamento();
	$data_base_create->create_table_encaminhamento_endereco();
	$data_base_create->create_table_encaminhamento_produtos();
	$data_base_create->create_table_encaminhamento_tipo();
	$data_base_create->create_table_envio_sms_boleto();
	$data_base_create->create_table_estado();
	$data_base_create->create_table_estado_civil();
	$data_base_create->create_table_estados();
	$data_base_create->create_table_estoque();
	$data_base_create->create_table_estoque_apoio();
	$data_base_create->create_table_estoque_apoio_();
	$data_base_create->create_table_estoque_produto();
	$data_base_create->create_table_exclusao_info();
	$data_base_create->create_table_exclusao_info_relacionados();
	$data_base_create->create_table_fila_tarefas();
	$data_base_create->create_table_financeiro_apoio();
	$data_base_create->create_table_financeiro_funcionario_banco();
	$data_base_create->create_table_financeiro_funcionario_valor();
	$data_base_create->create_table_fluxo_caixa();
	$data_base_create->create_table_forma_pagamento();
	$data_base_create->create_table_forma_pagamento_bandeira();
	$data_base_create->create_table_forma_pagamento_cliente();
	$data_base_create->create_table_forma_pagamento_ecommerce();
	$data_base_create->create_table_fornecedor();
	$data_base_create->create_table_fornecedor_banco();
	$data_base_create->create_table_fornecedor_pedido();
	$data_base_create->create_table_fornecedor_pedido_item();
	$data_base_create->create_table_fornecedor_produto();
	$data_base_create->create_table_fornecedor_servico();
	$data_base_create->create_table_fornecedor_transportadora();
	$data_base_create->create_table_funcionario();
	$data_base_create->create_table_funcionario2();
	$data_base_create->create_table_funcionario_agendamento();
	$data_base_create->create_table_funcionario_comissao();
	$data_base_create->create_table_funcionario_funcao();
	$data_base_create->create_table_funcionario_horario_trabalho();
	$data_base_create->create_table_grade();
	$data_base_create->create_table_grade_arrumar_estoque();
	$data_base_create->create_table_grade_atributo();
	$data_base_create->create_table_grade_atributo_valor();
	$data_base_create->create_table_grade_foto();
	$data_base_create->create_table_grade_historico();
	$data_base_create->create_table_grade_promocao();
	$data_base_create->create_table_grade_saida_estoque();
	$data_base_create->create_table_grau_instrucao();
	$data_base_create->create_table_historico_doc_fiscais();
	$data_base_create->create_table_horario_trabalho();
	$data_base_create->create_table_ibptax();
	$data_base_create->create_table_importacao();
	$data_base_create->create_table_indica_amigo();
	$data_base_create->create_table_indica_amigo_log();
	$data_base_create->create_table_lancamentos_empresas();
	$data_base_create->create_table_limite_funcionario();
	$data_base_create->create_table_link();
	$data_base_create->create_table_log_acesso_offline();
	$data_base_create->create_table_log_acoes_notasfiscais();
	$data_base_create->create_table_log_dados_cadastro();
	$data_base_create->create_table_log_envia_email();
	$data_base_create->create_table_log_erro_sessao();
	$data_base_create->create_table_log_estoque();
	$data_base_create->create_table_log_mensage_atencao();
	$data_base_create->create_table_log_monitoramento();
	$data_base_create->create_table_log_sync_loja();
	$data_base_create->create_table_log_sync_loja_itens();
	$data_base_create->create_table_log_web_control();
	$data_base_create->create_table_mailmkt_campanha();
	$data_base_create->create_table_mailmkt_campanha_agendamento();
	$data_base_create->create_table_mailmkt_campanha_fixa();
	$data_base_create->create_table_mailmkt_campanha_fixa_ignorar();
	$data_base_create->create_table_mailmkt_config();
	$data_base_create->create_table_mailmkt_config_master();
	$data_base_create->create_table_mailmkt_lista();
	$data_base_create->create_table_mailmkt_lista_emails();
	$data_base_create->create_table_mailmkt_log();
	$data_base_create->create_table_manifest();
	$data_base_create->create_table_manifest_condutor();
	$data_base_create->create_table_manifest_documentos();
	$data_base_create->create_table_manifest_reboque();
	$data_base_create->create_table_manifest_uf_percurso();
	$data_base_create->create_table_manifest_veictracao();
	$data_base_create->create_table_manifesto_informacoes();
	$data_base_create->create_table_manifesto_modal();
	$data_base_create->create_table_manifesto_modal_condutor();
	$data_base_create->create_table_manifesto_modal_reboque();
	$data_base_create->create_table_marcas();
	$data_base_create->create_table_matriz_filial();
	$data_base_create->create_table_matriz_filial_historico();
	$data_base_create->create_table_matriz_permissao_modulo();
	$data_base_create->create_table_mensagens();
	$data_base_create->create_table_mercado_livre_produto();
	$data_base_create->create_table_modalidade_calculo();
	$data_base_create->create_table_modalidade_calculo_st();
	$data_base_create->create_table_modelo_contrato();
	$data_base_create->create_table_modulo();
	$data_base_create->create_table_modulos();
	$data_base_create->create_table_movimento_titulo_recebafacil();
	$data_base_create->create_table_municipio_rf();
	$data_base_create->create_table_ncm();
	$data_base_create->create_table_nf_devolucao();
	$data_base_create->create_table_nf_devolucao_cobranca();
	$data_base_create->create_table_nf_devolucao_itens();
	$data_base_create->create_table_nf_devolucao_itens_COFINS();
	$data_base_create->create_table_nf_devolucao_itens_COFINSST();
	$data_base_create->create_table_nf_devolucao_itens_ICMS();
	$data_base_create->create_table_nf_devolucao_itens_II();
	$data_base_create->create_table_nf_devolucao_itens_IPI();
	$data_base_create->create_table_nf_devolucao_itens_PIS();
	$data_base_create->create_table_nf_devolucao_itens_PISST();
	$data_base_create->create_table_nf_entrada();
	$data_base_create->create_table_nf_entrada_estoque();
	$data_base_create->create_table_nf_entrada_estoque_itens();
	$data_base_create->create_table_nf_entrada_itens();
	$data_base_create->create_table_nf_entrada_xml();
	$data_base_create->create_table_nf_inutilizadas();
	$data_base_create->create_table_nf_municipio_RF();
	$data_base_create->create_table_nf_natureza();
	$data_base_create->create_table_nf_paises();
	$data_base_create->create_table_nf_servico_assinadas();
	$data_base_create->create_table_nf_tributos_itens_COFINS();
	$data_base_create->create_table_nf_tributos_itens_COFINSST();
	$data_base_create->create_table_nf_tributos_itens_ICMS();
	$data_base_create->create_table_nf_tributos_itens_II();
	$data_base_create->create_table_nf_tributos_itens_IPI();
	$data_base_create->create_table_nf_tributos_itens_PIS();
	$data_base_create->create_table_nf_tributos_itens_PISST();
	$data_base_create->create_table_nf_tributos_venda();
	$data_base_create->create_table_nfe_cupom_fiscal();
	$data_base_create->create_table_nfe_exigibilidade();
	$data_base_create->create_table_nfe_icms_interestaduais();
	$data_base_create->create_table_nfe_icms_interestaduais_cliente();
	$data_base_create->create_table_nfe_icms_situacao_tributaria();
	$data_base_create->create_table_nfe_lista_servico();
	$data_base_create->create_table_nfe_modalidade();
	$data_base_create->create_table_nfe_motivo_desoneracao_icms();
	$data_base_create->create_table_nfe_municipio();
	$data_base_create->create_table_nfe_mvat();
	$data_base_create->create_table_nfe_origem();
	$data_base_create->create_table_nfe_paises();
	$data_base_create->create_table_nfe_produto_COFINS();
	$data_base_create->create_table_nfe_produto_COFINSST();
	$data_base_create->create_table_nfe_produto_ICMS();
	$data_base_create->create_table_nfe_produto_II();
	$data_base_create->create_table_nfe_produto_IPI();
	$data_base_create->create_table_nfe_produto_ISSQN();
	$data_base_create->create_table_nfe_produto_Opcoes();
	$data_base_create->create_table_nfe_produto_PIS();
	$data_base_create->create_table_nfe_produto_PISST();
	$data_base_create->create_table_nfe_situacao_tributaria();
	$data_base_create->create_table_nfe_tipo_especifico();
	$data_base_create->create_table_nfe_transportadora();
	$data_base_create->create_table_nfe_uf();
	$data_base_create->create_table_nfs_server();
	$data_base_create->create_table_nfs_situacao_tributaria();
	$data_base_create->create_table_nfse_erros();
	$data_base_create->create_table_nota_credito();
	$data_base_create->create_table_nota_credito_historico();
	$data_base_create->create_table_nota_fiscal();
	$data_base_create->create_table_nota_fiscal_tmp();
	$data_base_create->create_table_notas_promissorias();
	$data_base_create->create_table_oauth_clients();
	$data_base_create->create_table_oauth_tokens();
	$data_base_create->create_table_orcamento();
	$data_base_create->create_table_orcamento_cliente();
	$data_base_create->create_table_orcamento_itens();
	$data_base_create->create_table_ordem_servico();
	$data_base_create->create_table_ordem_servico_itens();
	$data_base_create->create_table_ordem_servico_tipo();
	$data_base_create->create_table_origem();
	$data_base_create->create_table_pagamento_notas();
	$data_base_create->create_table_pais();
	$data_base_create->create_table_parcela();
	$data_base_create->create_table_permissao_usuario();
	$data_base_create->create_table_posto_registros();
	$data_base_create->create_table_pro_parametro();
	$data_base_create->create_table_pro_parametro_valor();
	$data_base_create->create_table_pro_valor();
	$data_base_create->create_table_produto();
	$data_base_create->create_table_produto2();
	$data_base_create->create_table_produto_apoio();
	$data_base_create->create_table_produto_arrumar_estoque();
	$data_base_create->create_table_produto_arrumar_estoque_tmp();
	$data_base_create->create_table_produto_beneficio_fiscal();
	$data_base_create->create_table_produto_busca_prevenda();
	$data_base_create->create_table_produto_busca_prevenda_ordem();
	$data_base_create->create_table_produto_combNF();
	$data_base_create->create_table_produto_configuracoes_comercial();
	$data_base_create->create_table_produto_contabil();
	$data_base_create->create_table_produto_fornecedor();
	$data_base_create->create_table_produto_foto();
	$data_base_create->create_table_produto_icms();
	$data_base_create->create_table_produto_info_nutricionais();
	$data_base_create->create_table_produto_num_parcelas_aux();
	$data_base_create->create_table_produto_pedido_equipamento();
	$data_base_create->create_table_produtos_apoio();
	$data_base_create->create_table_produtos_composicao();
	$data_base_create->create_table_produtos_deletados();
	$data_base_create->create_table_produtos_detalhes();
	$data_base_create->create_table_produtos_detalhes_agrupagem();
	$data_base_create->create_table_produtos_detalhes_dimensoes_caixa();
	$data_base_create->create_table_produtos_detalhes_dimensoes_palete();
	$data_base_create->create_table_produtos_detalhes_dimensoes_unidade();
	$data_base_create->create_table_produtos_excluidos();
	$data_base_create->create_table_produtos_reciclagem();
	$data_base_create->create_table_produtos_removidos();
	$data_base_create->create_table_promocao_kit();
	$data_base_create->create_table_promocao_kit_grade();
	$data_base_create->create_table_promocao_mix();
	$data_base_create->create_table_promocao_mix_desconto();
	$data_base_create->create_table_promocao_mix_tempo();
	$data_base_create->create_table_promocao_quantidade();
	$data_base_create->create_table_relatorio_estoque_mensal();
	$data_base_create->create_table_relatorio_estoque_mensal_produtos();
	$data_base_create->create_table_relatorios_campos();
	$data_base_create->create_table_relatorios_tabelas();
	$data_base_create->create_table_remetente();
	$data_base_create->create_table_sequencia_assistencia();
	$data_base_create->create_table_sequencia_orcamento();
	$data_base_create->create_table_sequencia_ordem_servico();
	$data_base_create->create_table_sequencia_pedido();
	$data_base_create->create_table_setor();
	$data_base_create->create_table_solicitacao_reativacao();
	$data_base_create->create_table_sugestao();
	$data_base_create->create_table_tabela_codigo_anp();
	$data_base_create->create_table_tabela_ncm();
	$data_base_create->create_table_tabela_ncm_vigente();
	$data_base_create->create_table_test_apoio();
	$data_base_create->create_table_tipo_entrada();
	$data_base_create->create_table_tipo_log();
	$data_base_create->create_table_tipo_residencia();
	$data_base_create->create_table_tipo_unidade_tmp();
	$data_base_create->create_table_tipos_lancamentos();
	$data_base_create->create_table_titulos();
	$data_base_create->create_table_titulos_movimentacao();
	$data_base_create->create_table_titulos_recebafacil();
	$data_base_create->create_table_titulos_recebafacil_historico();
	$data_base_create->create_table_titulos_retorno();
	$data_base_create->create_table_tmp_datas();
	$data_base_create->create_table_tmp_datas_afiliacoes();
	$data_base_create->create_table_tmp_datas_afiliacoes1();
	$data_base_create->create_table_tmp_datas_afiliacoes_comparar();
	$data_base_create->create_table_tmp_datas_atendimento();
	$data_base_create->create_table_tmp_datas_cancelamentos();
	$data_base_create->create_table_tmp_datas_equipamentos();
	$data_base_create->create_table_tmp_datas_teste();
	$data_base_create->create_table_tmp_fat_bonificada();
	$data_base_create->create_table_tmp_grafico_afiliacoes();
	$data_base_create->create_table_tmp_grafico_afiliacoes_consultor();
	$data_base_create->create_table_tmp_grafico_cancelados();
	$data_base_create->create_table_tmp_imp_classificacao();
	$data_base_create->create_table_tmp_imp_cliente();
	$data_base_create->create_table_tmp_imp_estoque();
	$data_base_create->create_table_tmp_imp_fornecedor();
	$data_base_create->create_table_tmp_imp_produto();
	$data_base_create->create_table_tmp_meses_label();
	$data_base_create->create_table_tmp_produto_aux();
	$data_base_create->create_table_tmp_ranking_agendamento_diario();
	$data_base_create->create_table_tmp_ranking_atendimento();
	$data_base_create->create_table_tmp_ranking_geral();
	$data_base_create->create_table_tmp_sped_150();
	$data_base_create->create_table_tmp_sped_190();
	$data_base_create->create_table_tmp_sped_unidade();
	$data_base_create->create_table_torpedo_campanha();
	$data_base_create->create_table_torpedo_campanha_agendamento();
	$data_base_create->create_table_torpedo_campanha_bkp_excluidos();
	$data_base_create->create_table_torpedo_campanha_fixa();
	$data_base_create->create_table_torpedo_campanha_fixa_ignorar();
	$data_base_create->create_table_torpedo_campanha_lista();
	$data_base_create->create_table_torpedo_lista();
	$data_base_create->create_table_torpedo_lista_telefones();
	$data_base_create->create_table_torpedo_lista_log();
	$data_base_create->create_table_torpedo_master();
	$data_base_create->create_table_torpedo_user();
	$data_base_create->create_table_transportadora();
	$data_base_create->create_table_transportadora_placa();
	$data_base_create->create_table_tributacao();
	$data_base_create->create_table_unidade();
	$data_base_create->create_table_unidades_fracionamento();
	$data_base_create->create_table_users();
	$data_base_create->create_table_usuario();
	$data_base_create->create_table_vale_presente();
	$data_base_create->create_table_vale_presente_historico();
	$data_base_create->create_table_vale_presente_new();
	$data_base_create->create_table_valor();
	$data_base_create->create_table_valor_extra_caixa();
	$data_base_create->create_table_valor_inicial_caixa();
	$data_base_create->create_table_valor_sangria();
	$data_base_create->create_table_venda();
	$data_base_create->create_table_venda_adiantamento();
	$data_base_create->create_table_venda_consignacao_devolucao();
	$data_base_create->create_table_venda_devolucao();
	$data_base_create->create_table_venda_informacoes();
	$data_base_create->create_table_venda_itens();
	$data_base_create->create_table_venda_itens_automoveis();
	$data_base_create->create_table_venda_itens_devolucao();
	$data_base_create->create_table_venda_juros_parcelamento();
	$data_base_create->create_table_venda_locacao();
	$data_base_create->create_table_venda_nfse_informacoes();
	$data_base_create->create_table_venda_notas_eletronicas();
	$data_base_create->create_table_venda_notas_eletronicas_lixo();
	$data_base_create->create_table_venda_optica();
	$data_base_create->create_table_venda_pagamento();
	$data_base_create->create_table_venda_pagamento_cheque();
	$data_base_create->create_table_venda_pagamento_ecommerce();
	$data_base_create->create_table_venda_parcelas();
	$data_base_create->create_table_venda_pgto_temp();
	$data_base_create->create_table_vendas_funcionario();
	$data_base_create->create_table_view_venda_parcelas();
	$data_base_create->create_table_vp_historico();
	$data_base_create->create_table_wc_menu();
	$data_base_create->create_table_wc_permissao_menu();
	$data_base_create->create_table_wc_submenu();
	$data_base_create->create_table_webc_configuracoes_sistema();
	$data_base_create->create_table_webc_grupo_usuarios();
	$data_base_create->create_table_webc_grupo_usuarios_cadastro();
	$data_base_create->create_table_webc_modulo();
	$data_base_create->create_table_webc_permissao();
	$data_base_create->create_table_webc_permissao_grupo_usuarios();
	$data_base_create->create_table_webc_permissao_modulo();
	$data_base_create->create_table_webc_permissao_usuario();
	$data_base_create->create_table_webc_posto_bomba();
	$data_base_create->create_table_webc_posto_bomba_bico();
	$data_base_create->create_table_webc_posto_tanque();
	$data_base_create->create_table_webc_situacao();
	$data_base_create->create_table_webc_sub_modulo();
	$data_base_create->create_table_webc_tipo_permissao_usuario();
	$data_base_create->create_table_webc_tipo_venda();
	$data_base_create->create_table_webc_usuario();
	$data_base_create->create_table_webc_vfx_syncloja();
	$data_base_create->create_table_webc_visualizacao_imediata();
	$data_base_create->create_table_webc_visualizacao_imediata_dados();
	$data_base_create->create_table_whatsapp_campanha();
	$data_base_create->create_table_whatsapp_campanha_agendamento();
	$data_base_create->create_table_whatsapp_campanha_fixa();
	$data_base_create->create_table_whatsapp_campanha_fixa_ignorar();
	$data_base_create->create_table_whatsapp_lista();
	$data_base_create->create_table_whatsapp_lista_telefones();
	$data_base_create->create_table_whatsapp_log();
	$data_base_create->create_table_whatsapp_master();
	$data_base_create->create_table_whatsapp_transacao();
	$data_base_create->create_table_whatsapp_user();
	*/
	// FIM CRIAÇÃO DA BASE DE DADOS E DE SUAS RESPECTIVAS TABELAS

	Grava_Acesso_WebControl($codloja,$nomefantasia,$cpfsocio1,$email,$login,$senha,$uf);
	
	echo "<script>alert(\"Cliente cadastrado com sucesso!\");</script>";
	echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=clientes/most_cliente.php&codloja=$codloja\";>";

?>