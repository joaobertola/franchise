<?php
	require "connect/sessao.php";
	require "connect/conexao_conecta.php";
	require "connect/funcoes.php";
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

//	if($franqueado == 1){
//		echo '<pre>';
//		var_dump($comando);
//		die;
//	}


	$res = mysql_query ($comando, $con);
	$codloja = mysql_insert_id($con);
	if(!$res){
		echo "<script>alert(\"Erro na inserção do cliente, entre em contato com o Departamento de Informatica !\");history.back();</script>";
		exit;
	}

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
		$sql = "SELECT COUNT(*) qtd FROM cs2.logon WHERE mid(logon,1,5)='$codigo'";
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
	Grava_Acesso_WebControl($codloja,$nomefantasia,$cpfsocio1,$email,$login,$senha,$uf);
	
	echo "<script>alert(\"Cliente cadastrado com sucesso!\");</script>";
	echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=clientes/most_cliente.php&codloja=$codloja\";>";

?>