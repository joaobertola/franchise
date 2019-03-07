<?php	

function grava_fone($p_fone, $p_ref, $nCpfcgc){
	global $conexao;		
	if(!empty($p_fone)){		
		$DDD = substr($p_fone,0,2);
		$TELEFONE = substr($p_fone,2,8);
		$fsql = "SELECT id FROM consulta2.fones_consultados WHERE cpfcnpj='$nCpfcgc' AND telefone='$TELEFONE'";
		$fqry = mysql_query($fsql,$conexao) or die ("erro ao selecionar o ultimos telefones - ".$fsql);
		$numero = mysql_num_rows($fqry);
		if( $numero == 0 ){
			$fsql = "INSERT INTO consulta2.fones_consultados(cpfcnpj,telefone,ddd,tp_fone) 
					 VALUES('$nCpfcgc' ,'$TELEFONE' ,'$DDD' ,'$p_ref' )";
			$fqry = mysql_query($fsql,$conexao)or die ("Erro: $fsql");
		}
	}	
}
	
function grava_nomes($nCpfcgc, $Tipo, $nome, $data_nascimento, $numero_titulo, $endereco, $id_tipo_log, $numero, $complemento, $bairro, $cidade, $uf, $cep, $email, $nome_mae, $telefone, $celular, $referencia_comercial, $referencia_pessoal, $empresa_trabalha, $cargo, $endereco_empresa, $rg, $nome_referencia, $fax, $fone_empresa, $cnpj_empresa){
	
//$email, $rg, $nome_mae, $data_nascimento, $numero_titulo, $telefone, $celular, $referencia_comercial, $referencia_pessoal, $empresa_trabalha

    #$Tipo         -> [ 0-CPF   1-CNPJ ]
    #$nreceita     -> [ Nome da Pessoa ou Empresa ]
    #$nacireceita2 -> [ Data Nascimento ou Data Fundação ]
	global $conexao;
	#Cadastra o nome na base caso nao exista
	
	if ( $nCpfcgc > 0 ){
		$sql_nome = "SELECT Nom_Nome FROM base_inform.Nome_Brasil 
					 WHERE Nom_CPF = '$nCpfcgc' AND Nom_Tp= $Tipo AND
					 Origem_Nome_id = 1";
		$ql_nome = mysql_query($sql_nome, $conexao);
		$quant_dados = mysql_num_rows($ql_nome);
		if ( $quant_dados == '0' ){
			#Verificando se existe o NOME CADASTRADO para o CPF
			$sql_nome = "SELECT Nom_Nome FROM base_inform.Nome_Brasil 
						 WHERE Nom_CPF = '$nCpfcgc' AND Nom_Tp= $Tipo AND
						 Origem_Nome_id <> 1 AND Nom_Nome = '$nome' ";
			$ql_nome = mysql_query($sql_nome, $conexao);
			$quant_dados = mysql_num_rows($ql_nome);
			if ( $quant_dados == '0' ){
				mysql_query("INSERT INTO base_inform.Nome_Brasil(Origem_Nome_id, Nom_CPF, Nom_Tp, Nom_Nome, Dt_Cad)
							 VALUES('2','$nCpfcgc','$Tipo','$nome', now() )" ,$conexao);
			}
		}
	
		#VERIFICANDO SE EXISTE O REGISTRO OU O ENDEREÇO
		if(! empty($endereco ) ){
			$sql = "SELECT count(*) qtd FROM base_inform.Endereco WHERE CPF='$nCpfcgc' AND logradouro = '$endereco'";
			$qr = mysql_query($sql,$conexao) or die ("Erro !!! 2532 $sql");
			$qtd = mysql_result($qr,0,'qtd');
			if($qtd == 0){
				#NÃO EXISTE REGISTRO OU O LOGRADOURO É NOVO, CADASTRAR UM NOVO
				$sql = "INSERT INTO base_inform.Endereco(CPF, Tipo, Origem_Nome_id, Tipo_Log_id, logradouro, numero, complemento, bairro, cidade, uf, cep, data_cadastro)
						VALUES('$nCpfcgc', '$Tipo', '2', '$id_tipo_log', '$endereco', '$numero', '$complemento', '$bairro', '$cidade', '$uf', '$cep', NOW() )";
				$qr = mysql_query($sql, $conexao) or die (mysql_error()." $sql --- Erro ao inserir");
			
			}else{ 
				$sql = "UPDATE base_inform.Endereco SET 
								Tipo			= '$Tipo', 
								Tipo_Log_id     = '$id_tipo_log', 
								logradouro      = '$endereco', 
								numero          = '$numero', 
								complemento     = '$complemento', 
								bairro          = '$bairro', 
								cidade          = '$cidade', 
								uf              = '$uf', 
								cep             = '$cep'
						WHERE
								CPF             = '$nCpfcgc'";
				$qr = mysql_query($sql, $conexao) or die (mysql_error()." $sql --- Erro ao alterar");
			}
		}
	
		//Email
		if(! empty($email ) ){
			$sql_email = "SELECT * FROM base_inform.Email_Brasil WHERE CPF = '$nCpfcgc' AND Email = '$email'";
			$qry = mysql_query( $sql_email , $conexao );
			if ( mysql_fetch_array( $qry ) == 0 ){
				$sql_insert = " INSERT INTO base_inform.Email_Brasil(CPF, Tipo, Email)
								VALUES('$nCpfcgc' , '$Tipo' , '$email' )";
				$qry = mysql_query( $sql_insert , $conexao );
			}
		}
	
	
		//RG
		if(! empty($rg ) ){
			$sql_rg = "SELECT * FROM base_inform.Nome_RG WHERE CPF = '$nCpfcgc'";
			$qry = mysql_query( $sql_rg , $conexao );
			if ( mysql_fetch_array( $qry ) == 0 ){
				$sql_insert = " INSERT INTO base_inform.Nome_RG(CPF, Tipo, Numero_RG)
								VALUES('$nCpfcgc' , '$Tipo' , '$rg' )";
				$qry = mysql_query( $sql_insert , $conexao );
			}
		}
	
		
		//Nome_Mae
		if(! empty($nome_mae ) ){
			$sql_rg = "SELECT * FROM base_inform.Nome_Mae WHERE CPF = '$nCpfcgc'";
			$qry = mysql_query( $sql_rg , $conexao );
			if ( mysql_fetch_array( $qry ) == 0 ){
				$sql_insert = " INSERT INTO base_inform.Nome_Mae(CPF, Tipo, Nome_Mae)
								VALUES('$nCpfcgc' , '$Tipo' , '$nome_mae' )";
				$qry = mysql_query( $sql_insert , $conexao );
			}
		}
	
		//Data Nascimento
		if(! empty($data_nascimento ) ){
			$sql_rg = "SELECT * FROM base_inform.Nome_DataNascimento WHERE CPF = '$nCpfcgc'";
			$qry = mysql_query( $sql_rg , $conexao );
			if ( mysql_fetch_array( $qry ) == 0 ){
				$sql_insert = " INSERT INTO 
									base_inform.Nome_DataNascimento(CPF, Tipo, data_nascimento)
								VALUES('$nCpfcgc' , '$Tipo' , '$data_nascimento' )";
				$qry = mysql_query( $sql_insert , $conexao );
			}
		}
		
		//Titulo de Eleitor
		if(! empty($numero_titulo ) ){
			$sql_rg = "SELECT * FROM base_inform.Nome_Titulo WHERE CPF = '$nCpfcgc'";
			$qry = mysql_query( $sql_rg , $conexao );
			if ( mysql_fetch_array( $qry ) == 0 ){
				$sql_insert = " INSERT INTO 
									base_inform.Nome_Titulo(CPF, Tipo, Numero_Titulo)
								VALUES('$nCpfcgc' , '$Tipo' , '$numero_titulo' )";
				$qry = mysql_query( $sql_insert , $conexao );
			}
		}
	
		grava_fone($telefone, '1', $nCpfcgc);
		grava_fone($celular, '3', $nCpfcgc);
		grava_fone($referencia_comercial, '4', $nCpfcgc);
		grava_fone($referencia_pessoal, '4', $nCpfcgc);
		grava_fone($fax, '2', $nCpfcgc);
		grava_fone($fone_empresa, '2', $nCpfcgc);
	
		//Profissao	
		if(!empty($empresa_trabalha)){
			$sql = "SELECT CPF, id FROM base_inform.Nome_Empresa_Trabalha 
					WHERE CPF = '$nCpfcgc' AND Tipo = '$Tipo' AND empresa = '$empresa_trabalha' AND cargo = '$cargo'";
			$qry = mysql_query($sql,$conexao) or die ("erro ao selecionar o profissao - ".$sql);
			$numero = mysql_num_rows($qry);
			if( $numero == 0 ){			
				$sql = "INSERT INTO base_inform.Nome_Empresa_Trabalha(cnpj_empresa, CPF, Tipo, empresa, cargo, endereco_empresa)
						VALUES('$cnpj_empresa', '$nCpfcgc', '$Tipo', '$empresa_trabalha', '$cargo', '$endereco_empresa')";
				$qry = mysql_query($sql,$conexao)or die ("Erro: $sql");
			}else{
				$id = mysql_result($qry,0,'id');
				$sql = "UPDATE base_inform.Nome_Empresa_Trabalha
							SET cnpj_empresa = '$cnpj_empresa', 
								CPF = '$nCpfcgc',
								Tipo = '$Tipo',
								empresa = '$empresa_trabalha',
								cargo = '$cargo',
								endereco_empresa = '$endereco_empresa'
						WHERE id = $id";
				//$qry = mysql_query($sql,$conexao)or die ("Erro: $sql");
			}
		}
	
		//Referencia Pessoal
		if(!empty($nome_referencia)){
			$sql = "SELECT CPF FROM base_inform.Nome_PessoaContato 
					WHERE cpf = '$nCpfcgc' AND nome_contato = '$nome_referencia'";
			$qry = mysql_query($sql,$conexao) or die ("erro ao selecionar o REFERENCIA PESSOAL - ".$sql);
			$numero = mysql_num_rows($qry);
			if( $numero == 0 ){
				$sql = "INSERT INTO base_inform.Nome_PessoaContato(cpf , nome_contato)
						VALUES('$nCpfcgc', '$nome_referencia')";
				$qry = mysql_query($sql,$conexao)or die ("Erro: $sql");
			}
		}
	}
}
?>