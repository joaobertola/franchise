<?php

function ver_operadora($telefone){
	
	$con = @mysql_connect("10.2.2.3", "csinform", "inform4416#scf");

	$sql    = "SELECT subdate(now(), interval 45 day) data";
	$qr     = mysql_query($sql,$con)or die ("ERRO:  Segundo SQL  ==>  $sql");
	$campos = mysql_fetch_array($qr);
	$data   = substr($campos["data"],0,10);
	
	$sql = "SELECT id_operadora FROM cs2.telefone_operadora
			WHERE telefone = '$telefone' and data_atualiza > '$data'";
	$qr	 = mysql_query($sql,$con);
	$id_op = mysql_result($qr,0,'id_operadora');

	
	if ( $id_op > 0 ){

		$sql = "SELECT descricao FROM cs2.operadora WHERE id = '$id_op'";
		$res = mysql_query($sql,$con);
		$saida = mysql_result($res,0,'descricao');
		return $saida;
		
	}else{
		
		// NAO CADASTRADO OU VENCEU O PRAZO
		$string = "numero=$telefone&chave=313a62f437cc2a20c481";
		$url = "http://consultanumero1.telein.com.br/sistema/consulta_numero.php";

	    $ch = curl_init();
	    flush();
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS,  $string);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_FAILONERROR, true);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 40);
	    $resp = curl_exec($ch);
	    curl_close($ch);

		$array = explode('#',$resp);
		$operadora = $array[0];
	
		if ( $operadora <> '99' ){
		
			$sql = "SELECT id FROM cs2.operadora WHERE id = '$operadora'";
			$res = mysql_query($sql,$con) or die('Erro');
			$id = mysql_result($res,0,'id');
	
			
			$sql = "DELETE FROM FROM cs2.telefone_operadora
					WHERE telefone = '$telefone'";
			$res = mysql_query($sql,$con);
			
			$sql = "INSERT INTO cs2.telefone_operadora(telefone,id_operadora,data_atualiza)
					VALUES('$telefone','$id', NOW() )";
			$res = mysql_query($sql,$con);

			$sql = "SELECT descricao FROM cs2.operadora WHERE id = '$id'";
			$res = mysql_query($sql,$con);
			$saida = mysql_result($res,0,'descricao');
			
			return '-'.$saida;
		}
	}
}


function seguranca3($ip,$usuario,$validador){
	$con = @mysql_connect("10.2.2.3", "csinform", "inform4416#scf");
	$sql="select quant from consulta.seguranca_franquias where codigo='$usuario' and data=now()";
	$qr=mysql_query($sql,$con)or die ("Erros nas tentativas. Caso nao se lembre a senha, contate o administrador. Erro 50  $sql");
	$campos=mysql_fetch_array($qr);
	$counts=mysql_num_rows($qr);
	$bcquant=$campos["quant"];
	if ($bcquant >= 3){
		return $bcquant;
	}else{
		if ( $validador == 'N' ){
			if ( empty($bcquant) ) $sqls="insert into consulta.seguranca_franquias (data,codigo,quant,ip,hora) values (now(),'$usuario','1','$ip',now())";
			else $sqls="update consulta.seguranca_franquias set data=now(), hora=now(), ip='$ip', quant=quant+1 where codigo='$usuario' ";
			$qrs=mysql_query($sqls,$con)or die ("Erro na insercao da primeira tentativa./n Contate o administrador erro 60");
		}
		return $bcquant;
	}
	exit;
}

function limpaCaracterEspecial($s) {
	$tr  = array('�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','<','>','/','%','_','-','@','#','$','^','&',',','+','.','*','~','�','`', '=',' ');
	$eng = array('a','a','a','a','a','A','A','A','A','i','i','i','I','I','I','e','e','e','E','E','E','o','o','o','o','o','o','O','O','O','u','u','u','u','U','U','N','n','c','C','' ,'' ,'' ,'' ,' ',' ','' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' , '' ,'');
	$s = str_replace($tr,$eng,$s);
	$s = preg_replace('/[^0-9A-Za-z]/',"",$s);
	return strtolower($s);
}

function verifica_email($franqueado,$codloja,$nomefantasia){
	
	
	$nomefantasia = limpaCaracterEspecial($nomefantasia);
	
	$cnx_email = @mysql_pconnect("10.2.2.7", "root", "cntos43");
	if (!$cnx_email) {
            echo 'Erro na conexao com o Servidor de Email<br>';
            echo mysql_error();
            exit;
	} else {
            $database_mail = mysql_select_db("vpopmail",$cnx_email);
            if (!$database_mail) {
                echo "Erro na conexão com o Banco de dados de Email<br>";
                echo mysql_error();
            }
	}
	$nomefantasia = substr($nomefantasia,0,29);
	$nomefantasia = strtolower($nomefantasia);
	$nome_mail = str_replace(' ','',$nomefantasia);
	$existe_email = 'N';
	for ( $i = 1 ; $i < 50 ; $i++ ){
            $sql_email = "SELECT pw_name FROM vpopmail.webcontrolempresas_com_br WHERE pw_name='$nome_mail'";
            $resp_mail = mysql_query($sql_email, $cnx_email) or die ("ERRO ao verificar se o Email existe");
            while ($array = mysql_fetch_array($resp_mail)){
                $existe_email = "S";
                $email_cliente = $array['pw_name'];
            }
            if ($existe_email == 'S' )
                $nome_mail .= '_'.$i;
		else{
			# N�o existe email, cadastrando na tabela com a referencia [codloja] do cliente
			$email_cliente = $nome_mail; 
			$descricao = 'Conta de Email Cliente Web Control Empresas';
			$timestamp = time();
			$sql_insert = "	INSERT INTO vpopmail.webcontrolempresas_com_br(pw_name,pw_passwd,pw_gecos,
																	pw_dir,pw_clear_passwd,pw_referencia,pw_codloja)
							VALUES('$nome_mail','$1$9PLghCWq$4B9EEcEN1vxug/8Q2UAYF0','$descricao',
								   '/home/vpopmail/domains/webcontrolempresas.com.br/$nome_mail','123456','C',$codloja)";
			$ql_mail = mysql_query($sql_insert, $cnx_email) or die ("ERRO: 01 => Falha ao cadastrar o Email do novo cliente");
			$sql_insert = "	INSERT INTO vpopmail.lastauth(user,domain,timestamp)
							VALUES('$nome_mail','webcontrolempresas.com.br','$timestamp')";
			$ql_mail = mysql_query($sql_insert, $cnx_email) or die ("ERRO: 02 => Falha ao cadastrar o Email do novo cliente");
			break;
		}
	}
	mysql_close($cnx_email);

	return $email_cliente;
}

function grava_dados($cpfcnpj, $tipo, $razao, $endereco, $numero, $complemento, $bairro, $cidade, $uf, $cep, $email, $telefone, $celular, $cpf1, $nome_prop1, $cpf2 , $nome_prop2){
	
	$cnx_email = @mysql_pconnect("10.2.2.3", "csinform", "inform4416#scf");
	
	if ( strlen($cpfcnpj) <= 11 ) $tipo = 0;
	else $tipo = 1;
	
	$tipo_log = substr($endereco,0,strpos($endereco,' '));
	$log = substr($endereco,strpos($endereco,' ')+1,strlen($endereco));
	$log = trim($log);
	
	# NOMES BRASIL
	$sql_nome = "SELECT Nom_Nome FROM base_inform.Nome_Brasil 
				 WHERE Nom_CPF = '$cpfcnpj' AND Nom_Tp = $tipo AND Origem_Nome_id = 1";
	$ql_nome = mysql_query($sql_nome, $cnx_email);
	$quant_dados = mysql_num_rows($ql_nome);
	if ( $quant_dados == '0' ){
		#Verificando se existe o NOME CADASTRADO para o CPF
		$sql_nome = "SELECT Nom_Nome FROM base_inform.Nome_Brasil 
					 WHERE Nom_CPF = '$cpfcnpj' AND Nom_Tp = $tipo AND
					 Origem_Nome_id <> 1 AND Nom_Nome = '$razao' ";
		$ql_nome = mysql_query($sql_nome, $cnx_email);
		$quant_dados = mysql_num_rows($ql_nome);
		if ( $quant_dados == '0' ){
			mysql_query("INSERT INTO base_inform.Nome_Brasil(Origem_Nome_id, Nom_CPF, Nom_Tp, Nom_Nome, Dt_Cad)
						 VALUES('2','$cpfcnpj','$tipo','$razao', now() )" ,$cnx_email);
		}
	}

	# VERIFICANDO SE EXISTE O REGISTRO OU O ENDERE�O
	if(! empty( $log ) ){
		
		$sql_tipo = "SELECT id from apoio.Tipo_Log WHERE reduzido = '$log' or descricao = '$log'";
		$qry_sql_tipo = mysql_query($sql_tipo,$cnx_email);
		$id = mysql_fetch_array($qry_sql_tipo);
		$id_tipo_log = $id['id'];

		$sql_endereco = "SELECT id, count(*) qtd FROM base_inform.Endereco WHERE CPF='$cpfcnpj' AND logradouro = '$log'";
		$qr_end = mysql_query($sql_endereco,$cnx_email) or die ("Erro !!! 2532 => $sql_endereco");
		$id = mysql_result($qr_end,0,'id');
		$qtd = mysql_result($qr_end,0,'qtd');
		if( $qtd == 0 ){
			#N�O EXISTE REGISTRO OU O LOGRADOURO � NOVO, CADASTRAR UM NOVO
			$sql = "INSERT INTO base_inform.Endereco(CPF, Tipo, Origem_Nome_id, Tipo_Log_id, logradouro, numero, complemento, bairro, cidade, uf, cep, data_cadastro)
					VALUES('$cpfcnpj', '$tipo', '2', '$id_tipo_log', '$log', '$numero', '$complemento', '$bairro', '$cidade', '$uf', '$cep', NOW() )";
			$qr = mysql_query($sql, $cnx_email) or die (mysql_error()." $sql --- Erro ao inserir");
		
		}else{ 
			$sql = "UPDATE base_inform.Endereco SET 
							Tipo			= '$tipo', 
							Tipo_Log_id     = '$id_tipo_log', 
							logradouro      = '$log', 
							numero          = '$numero', 
							complemento     = '$complemento', 
							bairro          = '$bairro', 
							cidade          = '$cidade', 
							uf              = '$uf', 
							cep             = '$cep'
					WHERE
							id             = '$id'";
			$qr = mysql_query($sql, $cnx_email) or die (mysql_error()." $sql --- Erro ao alterar");
		}
	}
	
	# EMAIL
	if( ! empty( $email ) ){
		$sql_email = "SELECT * FROM base_inform.Email_Brasil WHERE CPF = '$cpfcnpj' AND Email = '$email'";
		$qry = mysql_query($sql_email,$cnx_email);
		if ( mysql_fetch_array( $qry ) == 0 ){
			$sql_insert = " INSERT INTO base_inform.Email_Brasil(CPF, Tipo, Email)
							VALUES('$cpfcnpj' , '$tipo' , '$email' )";
			$qr = mysql_query( $sql_insert , $cnx_email );
		}
	}
	
	// SOCIO
	if( ! empty( $cpf1 ) ){
		$sql_email = "SELECT * FROM base_inform.Nome_Socio WHERE cpfcnpj = '$cpfcnpj' AND cpf_socio = '$cpf1'";
		$qry = mysql_query($sql_email,$cnx_email);
		if ( mysql_fetch_array( $qry ) == 0 ){
			$sql_insert = " INSERT INTO base_inform.Email_Brasil(cpfcnpj, nome_socio, cpf_socio)
							VALUES('$cpfcnpj' , '$nome_prop1' , '$cpf1' )";
			$qr = mysql_query( $sql_insert , $cnx_email );
		}
	}
	if( ! empty( $cpf2 ) ){
		$sql_email = "SELECT * FROM base_inform.Nome_Socio WHERE cpfcnpj = '$cpfcnpj' AND cpf_socio = '$cpf2'";
		$qry = mysql_query($sql_email,$cnx_email);
		if ( mysql_fetch_array( $qry ) == 0 ){
			$sql_insert = " INSERT INTO base_inform.Email_Brasil(cpfcnpj, nome_socio, cpf_socio)
							VALUES('$cpfcnpj' , '$nome_prop2' , '$cpf2' )";
			$qr = mysql_query( $sql_insert , $cnx_email );
		}
	}
	
}

function extenso($valor=0, $maiusculas=false) { 
	$singular = array("centavo", "real", "mil", "milh�o", "bilh�o", "trilh�o", "quatrilh�o"); 
	$plural = array("centavos", "reais", "mil", "milh�es", "bilh�es", "trilh�es", "quatrilh�es"); 
	
	$c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"); 
	$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa"); 
	$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove"); 
	$u = array("", "um", "dois", "tr�s", "quatro", "cinco", "seis", "sete", "oito", "nove"); 
	
	$z=0; 
	
	$valor = number_format($valor, 2, ".", "."); 
	$inteiro = explode(".", $valor); 
	for($i=0;$i<count($inteiro);$i++) 
	for($ii=strlen($inteiro[$i]);$ii<3;$ii++) 
	$inteiro[$i] = "0".$inteiro[$i]; 
	
	$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2); 
	for ($i=0;$i<count($inteiro);$i++) { 
		$valor = $inteiro[$i]; 
		$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]]; 
		$rd = ($valor[1] < 2) ? "" : $d[$valor[1]]; 
		$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : ""; 
		
		$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru; 
		$t = count($inteiro)-1-$i; 
		$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : ""; 
		if ($valor == "000")$z++; elseif ($z > 0) $z--; 
		if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t]; 
		if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r; 
	} 
	
	if(!$maiusculas){ 
		return($rt ? $rt : "zero"); 
	} else { 
		//return (ucwords($rt) ? ucwords($rt) : "Zero"); 
		return (strtolower($rt)); 
	} 
}

function data_mysql($data){
	// converte data no formato DD/MM/AAAA  para AAAA-MM-DD
	$data = substr($data,6,4)."-".substr($data,3,2) . "-" . substr($data,0,2);
	return $data;
}

function data_mysql_i($data){
	// converte data no formato AAAA-MM-DD para DD/MM/AAAA 
	$data = substr($data,8,2)."/".substr($data,5,2) . "/" . substr($data,0,4);
	return $data;
}

function ver_logon($codloja){
	
	$con = @mysql_connect("10.2.2.3", "csinform", "inform4416#scf");
	$sql = "SELECT mid(logon,1,5) AS logon FROM cs2.logon WHERE codloja = $codloja";
	$qry = mysql_query($sql,$con) or die($sql);
	$logon = mysql_result($qry,0,'logon');
	return $logon;
}

function mascara_celular($celular){
	if ( strlen($celular) == 10 )
		$saida = '('.substr($celular,0,2).') '.substr($celular,2,4).'-'.substr($celular,6,4);
	else
		$saida = '('.substr($celular,0,2).') '.substr($celular,2,5).'-'.substr($celular,7,4);
	return $saida;	
}

function soNumero($str) {
    return preg_replace("/[^0-9]/", "", $str);
}

function diferenca_entre_datas($data,$dt_base,$formato) {

	if ( $formato == 'DD/MM/AAAA' ){
		$d_data = substr($data,0,2);
		$m_data = substr($data,3,2);
		$a_data = substr($data,6,4);
		$d_base = substr($dt_base,0,2);
		$m_base = substr($dt_base,3,2);
		$a_base = substr($dt_base,6,4);
	}else{
		return "FORMATO INVALIDO";
		exit;
	}
	$dias_data = floor(gmmktime (0,0,0,$m_data,$d_data,$a_data)/ 86400);
	$dias_base = floor(gmmktime (0,0,0,$m_base,$d_base,$a_base)/ 86400);
	$val = $dias_data - $dias_base;
	return $val;
}

function seo2($value) { 
	$trocaeste=array( "(", ")","'","�","�","�","�","�","�","�","�","�","�","�","�","�","�"); 
	$poreste=array( " ", " "," ","O","C","U","U","O","O","O","O","A","A","A","A","E","I"); 
	$value=str_replace($trocaeste,$poreste,$value); 
	$value = preg_replace("@[^A-Za-z0-9<> /,.\-_]+@i","",$value); 
	return $value; 
}

function Grava_Acesso_WebControl($codloja,$nomefantasia,$cpfsocio1,$email,$login,$senha, $uf){

	$con = @mysql_connect("10.2.2.3", "csinform", "inform4416#scf");
	
	// Verificando se o Funcionario Master ja existe, se existir, pego o seu ID
	
	$sql2 = "SELECT id FROM base_web_control.funcionario
			 WHERE id_cadastro = '$codloja' AND tp_funcionario = 'P'";
	$qry2 = mysql_query($sql2, $con);

    if (mysql_num_rows($qry2) == 0) {
        $id_funcionario = 0;
    } else {
        $id_funcionario = mysql_result($qry2,0,'id');
    }

	if ( $id_funcionario == 0 ){
		// Cadastrando o Vendedor Padrao.
		$sql2 = "INSERT INTO base_web_control.funcionario( nome, cpf, id_cadastro, tp_funcionario )
				 VALUES( 'Funcionario MASTER', '$cpfsocio1', '$codloja', 'P' )";
		$qry2 = mysql_query($sql2, $con);
		$id_funcionario = mysql_insert_id($con);
	}
	
	$sql2 = "SELECT id FROM base_web_control.webc_usuario
			 WHERE id_cadastro = $codloja";
	$qry2 = mysql_query($sql2, $con);
    if (mysql_num_rows($qry2) == 0) {
        $id_usuario = 0;
    } else {
        $id_usuario = mysql_result($qry2,0,'id');
    }

	if ( $id_usuario == 0 ){
		// Cadastrando o usuario
		$sql2 = "INSERT INTO base_web_control.webc_usuario
			 (nome_usuario, login, senha, id_cadastro, login_master, id_funcionario, cnpj_cpf, email )
			 VALUES
				('FUNCIONARIO MASTER','$login','$senha','$codloja','S', '$id_funcionario', '$cpfsocio1', '$email')";
		$qry2 = mysql_query($sql2, $con);
		$id_usuario = mysql_insert_id();
	}
	
	// Cadastrando o Permissoes
	
	// Selecionando todos os modulos 
	$sql_x = "SELECT id_permissao FROM base_web_control.webc_permissao";
	$qry_x = mysql_query($sql_x, $con);
	$id_permissao = '';
	while ( $modulo = mysql_fetch_array($qry_x) ){
		$id_permissao .= $modulo['id_permissao'].',';
	}

	$id_permissao = substr($id_permissao,0,strlen($id_permissao)-1);
	// Gravando as permissoes
	$sql_i = "UPDATE base_web_control.webc_usuario SET array_permissao = '$id_permissao'
			  WHERE id = '$id_usuario'";
	mysql_query($sql_i, $con);
	
	// Criando CLIENTE BALCAO

	$sql2 = "INSERT INTO base_web_control.cliente
				( 
					id_cadastro, id_usuario, tipo_pessoa, cnpj_cpf, nome, razao_social, id_tipo_log, 
					endereco, bairro, cidade, uf
				)
			 VALUES
			 	( 
			 	'$codloja' , '$id_usuario', 'B', '00000000000', 'CLIENTE BALCAO', 'CLIENTE BALCAO', '1' ,
				'CLIENTE BALCAO', 'CLIENTE BALCAO', 'CLIENTE BALCAO', '$uf'
				)";
	mysql_query($sql2, $con);
	
}


?>