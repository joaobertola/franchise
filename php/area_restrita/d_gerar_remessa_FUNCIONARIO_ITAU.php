<?php

	# Author:  Luciano Mancini
	# M�dulo:  Remessa Funcion�rio - SALARIO - Banco Itau
	# Finalidade: Gerar o arquivo de cr�dito a serem enviados ao Banco ITAU para os funcion�rios

	include("../../../validar2.php");

	global $conexao,$arquivo;
	conecex();

	$registros = '';
	
	$linha_ted = "<p></p>
					<table width='1000' align='center'>
					<tr bgcolor='#CCCCCC' style='font-size:12px' >
						<td colspan='8'>ID</td>
						<td>Cliente</td>
						<td>Titular</td>
						<td width='20'>Banco</td>
						<td width='50'>Agencia</td>
						<td width='100'>Conta</td>
						<td width='100'>Valor</td>
						<td width='100'>Data Afilia&ccedil;&atilde;o</td>
					</tr>
				 ";
	
	$blq_repasse_inadimplencia = '';
	$NumeroArquivo = 1;
	$NumeroLote = 1;
	$totlinha = 0;
	
	$tot_pagar = 0;
	$NumeroRegistro = 0;
	
    $total_conta_corrente = 0;	
	$total_conta_poupanca = 0;
	$total_doc            = 0;
	$total_ted            = 0;
	
	// HEADER DO ARQUIVO
	$Registro  = colocazeros('341',3);                // 001 a 003 - C�digo do banco
	$Registro .= '0000';                              // 004 a 007 - Lote de servi�o
	$Registro .= '0';                                 // 008 a 008 - Tipo de registro - Registro header de arquivo
	$Registro .= colocaespacos('',6);                 // 009 a 014 - BRANCOS
	$Registro .= '080';                               // 015 a 017 - Layout do Arquivo
	$Registro .= '2';                                 // 018 a 018 - Tipo de inscricao
	$Registro .= '06866893000139';                    // 019 a 032 - N�mero de inscri��o do cedente
	              
	$Registro .=  colocaespacos('',20);               // 033 a 052 - BRANCOS
	$Registro .= '08616';                             // 053 a 057 - C�digo da ag�ncia do cedente
	$Registro .= ' ';                                 // 058 a 058 - BRANCOS
	$Registro .= colocazeros('21199',12);             // 059 a 070 - N�mero da conta do cedente
	$Registro .= ' ';                                 // 071 a 071 - BRANCOS
	$Registro .= '4';                                 // 072 a 072 - D�gito verificador da ag�ncia / conta
	$Registro .= colocaespacosdir('INFORM SYSTEM TECN INF LTDA',30);  // 073 a 102 - Nome do cedente
	$Registro .= colocaespacosdir('BANCO ITAU',30);       // 103 a 132 - Nome do banco
	$Registro .= colocaespacos('',10);                    // 133 a 142 - BRANCOS
	$Registro .= '1';                                     // 143 a 143 - C�digo de Remessa (1) / Retorno (2)
	$data = date('dmY');
	$Registro .= $data;                                   // 144 a 151 - Data do de gera��o do arquivo
	$hora = date("His");
	$Registro .= substr($hora,0,6);                       // 152 a 157 - Hora de gera��o do arquivo
	$Registro .= colocazeros('',9);                       // 158 a 163 - ZEROS
	$Registro .= '00000';                                 // 167 a 171 - Unidade de Densidade
	$Registro .= colocaespacos('',69);                    // 172 a 240 - BRANCOS
	$totlinha++;
	$registros .= "$Registro\n";
	
	/*
	*******************************************
	#   INICIO  -  CONTA CORRENTE BANCO ITAU  #
	*******************************************
	*/
	
    $sql = "SELECT id, nome, nr_banco, agencia, tp_conta, conta, cpf, salario, vt
			FROM cs2.funcionario
			WHERE nr_banco = '341' AND tp_conta = 'C'
			ORDER BY nome";
	$qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
	$qtd = mysql_num_rows($qr_sql);
	if ( $qtd > 0 ){
	
		// HEADER DO LOTE
		$Registro  = '341';                               // 001 a 003 - C�digo do Banco
		$Registro .= colocazeros($NumeroLote,4);          // 004 a 007 - Codigo do Lote
		$Registro .= '1';                                 // 008 a 008 - Tipo de Registro
		$Registro .= 'C';                                 // 009 a 009 - Tipo de Opera��o
		$tp_pagto = '30';
		$Registro .= $tp_pagto;                           // 010 a 011 - Tipo de Pagamento | 20-Fornecedores | 30-Salarios

		# Forma de Pagamento
		# 01 - Credito em Conta Corrente no ITAU
		# 03 - DOC C
		# 05 - Credito em Conta Poupan�a no ITAU
		# 06 - Credito em Conta Corrente no ITAU de mesma titularidade
		# 07 - DOC D
		# 41 - TED Outro titular
		# 43 - TED Mesmo titular
		# 60 - Cartao Salario
	
		$forma_pgto = '01';
		
		$Registro .= colocazeros($forma_pgto,2);          // 012 a 013 - Forma de Pagamento
		$Registro .= '040';                               // 014 a 016 - Layout do Lote
		$Registro .= ' ';                                 // 017 a 017 - Em branco
		$Registro .= '2';                                 // 018 a 018 - Empresa Tipo Inscri��o - Debitada
		$Registro .= '06866893000139';                    // 019 a 032 - Empresa Numero Inscri��o - Debitada
		
		$Registro .= $tp_pagto.$forma_pgto;               // 033 a 036 - Identificacao do lan�amento
		$Registro .= colocaespacos('',16);                // 037 a 052 - Em branco
		
		$Registro .= '08616';                             // 053 a 057 - Agencia debitada
		$Registro .= ' ';                                 // 058 a 058 - Em branco
		$Registro .= colocazeros('21199',12);             // 059 a 070 - Conta debitada
		$Registro .= ' ';                                 // 071 a 071 - Em branco
		$Registro .= '4';                                 // 072 a 072 - DAC - Digito verificado da conta	
		$Registro .= colocaespacos('INFORM SYSTEM TECN INF LTDA',30);   // 073 a 102 - Nome da empresa debitada
		                            
		$Registro .= colocaespacos('',30);                // 103 a 132 - Finalidade do lote
		$Registro .= colocaespacos('',10);                // 133 a 142 - Hist�rico de conta corrente
		$Registro .= colocaespacosdir('AV. MAL. FLORIANO PEIXOTO',30);            // 143 a 172 - Endereco da empresa
		$Registro .= colocaespacosdir('306',5);              // 173 a 177 - Numero
		$Registro .= colocaespacosdir('SALA 11',15);         // 178 a 192 - Complemento
		$Registro .= colocaespacosdir('CURITIBA',20);        // 193 a 212 - Cidade
		$Registro .= colocaespacosdir('80010130',8);         // 213 a 220 - CEP
		$Registro .= colocaespacos('PR',2);               // 221 a 222 - UF
		$Registro .= colocaespacos('',8);                 // 223 a 230 - Em branco
		$Registro .= colocazeros('',10);                  // 231 a 240 - Ocorrencias
		$totlinha++;
		$registros .= "$Registro\n";	
		# Fim do header
	
		while($registro = mysql_fetch_array($qr_sql)){

			$id_func = $registro['id'];
			$banco_func    =  $registro['nr_banco'];
			$agencia_func  =  $registro['agencia'];
			$conta_func    =  $registro['conta'];
			$conta_func    =  str_replace('-','',$conta_func);
			$conta_func    =  str_replace(' ','',$conta_func);
			$conta_func    =  str_replace('/','',$conta_func);
			$cpfcnpj_func  =  trim($registro['cpf']);
			$nome_func     =  substr($registro['nome'],0,30);
			$salario_func  =  $registro["salario"];
			$vt_vr         =  $registro["vt"];
			
			if ( empty($salario_func) ) $salario_func = 0;
			if ( empty($vt_vr) ) $vt_vr = 0;

			$salario = number_format($salario_func,2,'.','');
			$vt_vr   = number_format($vt_vr,2,'.','');
			
			if ( $salario_func > 0 ){
				
				$tit  = str_pad(substr($nome_func,0,30),30,' ',STR_PAD_RIGHT);
				$bco  = str_pad($banco_func,4,' ',STR_PAD_RIGHT);
				$age  = str_pad($agencia_func,5,' ',STR_PAD_RIGHT);
				$cta  = str_pad($conta_func,20,' ',STR_PAD_RIGHT);
				$vlr  = str_pad($salario,20,' ',STR_PAD_RIGHT);
				$vlr  = number_format($vlr,2,',', '.');

				# Cliente em dia com suas mensalidades
				$NumeroRegistro++;
				$CODLOJA = '99999'.$id;
				// REGISTRO DETALHES DO LOTE
				$Registro = '341';                                         // 001 a 003 - C�digo do banco
				$Registro .= str_pad($NumeroLote, 4, 0, STR_PAD_LEFT);     // 004 a 007 - N�mero do lote
				$Registro .= '3';                                          // 008 a 008 - Tipo do registro: Registro detalhe do lote
				$Registro .= str_pad($NumeroRegistro, 5, 0, STR_PAD_LEFT); // 009 a 013 - N�mero seq�encial do registro no lote
				$Registro .= 'A';                                          // 014 a 014 - C�digo do segmento do registro detalhe
				$Registro .= '000';                                        // 015 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
				$Registro .= '000';                                        // 018 a 020 - ZEROS
				$Registro .= str_pad($banco_func, 3, 0, STR_PAD_LEFT);     // 021 a 023 - C�digo do Banco do Favorecido
						
				$agencia   = colocazeros(substr($agencia_func,0,4),4);
				$conta     = substr($conta_func,0,strlen($conta_func)-1);
				$conta     = $conta * 1;
				
				$conta     = str_pad($conta, 6, 0, STR_PAD_LEFT);
				$dac_cta   = substr($conta_func,strlen($conta_func)-1,1);
				$dac_cta   = str_pad($dac_cta, 1, 0, STR_PAD_LEFT);
				$agencia_conta = '0'.$agencia.' 000000'.$conta.' '.$dac_cta;
						
				$Registro .= $agencia_conta;                               // 024 a 043 - Conta Corrente do Favorecido
				$Registro .= str_pad($nome_func, 30, ' ', STR_PAD_RIGHT);  // 044 a 073 - Nome do Favorecido
				$Registro .= str_pad('', 20, ' ', STR_PAD_RIGHT);          // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA
				$data = date('dmY');
				$Registro .= $data;                                        // 094 a 101 - Data de Pagamento
				$Registro .= 'REA';                                        // 102 a 104 - Tipo de Moeda
				$Registro .= str_pad('', 15, 0, STR_PAD_LEFT);             // 105 a 119 - ZEROS
				$valor = $salario * 100;
				$valor = str_replace(',','',$valor);
				$valor = str_replace('.','',$valor);
				$Registro .= str_pad($valor, 15, 0, STR_PAD_LEFT);         // 120 a 134 - Valor do PAGAMENTO
				$Registro .= str_pad('', 15, ' ', STR_PAD_RIGHT);          // 135 a 149 - Num DOC ATRIBUIDO P/ BANCO
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);           // 150 a 154 - BRANCOS
				$Registro .= '00000000';                                   // 155 a 162 - Data real da efetiva��o do lan�amento
				$Registro .= '000000000000000';                            // 163 a 177 - Valor real da efetiva��o do lan�amento
				$Registro .= str_pad($id_func, 18, ' ', STR_PAD_RIGHT);    // 178 a 195 - Outras informa��es
				$Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);           // 196 a 197 - BRANCOS
				$Registro .= str_pad('', 6, 0, STR_PAD_LEFT);              // 198 a 203 - Numero do Documento
						
				$Registro .= str_pad($cpfcnpj_func, 14, 0, STR_PAD_LEFT);  // 204 a 217 - CPF/CNPJ do Favorecido
				$Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);           // 218 a 219 - Finalidade do DOC
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);           // 220 a 224 - Finalidade do TED
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);           // 225 a 229 - BRANCOS
				$Registro .= str_pad('', 1, ' ', STR_PAD_RIGHT);           // 230 a 230 - Aviso ao Favorecido
				$Registro .= str_pad('', 10, ' ', STR_PAD_RIGHT);          // 231 a 240 - Ocorrencia de retorno
				$total_conta_corrente  += $salario_func;
				$totlinha++;
				$registros .= "$Registro\n";
						
			}
		}
		# TRAILLER - CONTA CORRENTE ITAU
		$Registro  = '341';                                    // 001 a 003 - C�digo do banco
		$Registro .= colocazeros($NumeroLote,4);               // 004 a 007 - N�mero do lote
		$Registro .= '5';                                      // 008 a 008 - Tipo do registro: Registro trailer do lote
		$Registro .= colocaespacos('',9);                      // 009 a 017 - BRANCO
		$Registro .= colocazeros($NumeroRegistro+2,6);         // 018 a 023 - Qtd de registro de lote
		$valor = $total_conta_corrente * 100;
		$valor = str_replace(',','',$valor);
		$valor = str_replace('.','',$valor);
		$Registro .= colocazeros($valor,18);                   // 024 a 041 - Somat�ria dos valores
		$Registro .= '000000000000000000';                     // 042 a 059 - ZEROS
		$Registro .= colocaespacos('',171);                    // 060 a 230 - BRANCOS
		$Registro .= colocaespacos('',10);                     // 231 a 240 - C�digo das ocorr�ncias para retorno
		$totlinha++;
		$registros .= "$Registro\n";	
	}
	# FIM - CONTA CORRENTE BANCO ITAU
	
	#########################################################################################################
	
	# INICIO - CONTA POUPANCA BANCO ITAU
	
    $sql = "SELECT id, nome, nr_banco, agencia, tp_conta, conta, cpf, salario, vt
			FROM cs2.funcionario
			WHERE nr_banco = '341' AND tp_conta = 'P' 
			ORDER BY nome";
	$qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
	$qtd = mysql_num_rows($qr_sql);
	if ( $qtd > 0 ){

		$NumeroLote++;
		$NumeroRegistro = 0;	
		// HEADER
		$Registro  = '341';                               // 001 a 003 - C�digo do Banco
		$Registro .= colocazeros($NumeroLote,4);          // 004 a 007 - Codigo do Lote
		$Registro .= '1';                                 // 008 a 008 - Tipo de Registro
		$Registro .= 'C';                                 // 009 a 009 - Tipo de Opera��o
		$tp_pagto = '30';
		$Registro .= $tp_pagto;                           // 010 a 011 - Tipo de Pagamento | 20-Fornecedores | 30-Salarios
	
		# Forma de Pagamento
		# 01 - Credito em Conta Corrente no ITAU
		# 03 - DOC C
		# 05 - Credito em Conta Poupan�a no ITAU
		# 06 - Credito em Conta Corrente no ITAU de mesma titularidade
		# 07 - DOC D
		# 41 - TED Outro titular
		# 43 - TED Mesmo titular
		# 60 - Cartao Salario

		$forma_pgto = '05';
		$Registro .= colocazeros($forma_pgto,2);          // 012 a 013 - Forma de Pagamento
		$Registro .= '040';                               // 014 a 016 - Layout do Lote
		$Registro .= ' ';                                 // 017 a 017 - Em branco
		$Registro .= '2';                                 // 018 a 018 - Empresa Tipo Inscri��o - Debitada
		$Registro .= '06866893000139';                    // 019 a 032 - Empresa Numero Inscri��o - Debitada
		
		$Registro .= '20'.$forma_pgto;               // 033 a 036 - Identificacao do lan�amento
		$Registro .= colocaespacos('',16);                // 037 a 052 - Em branco
		
		$Registro .= '08616';                             // 053 a 057 - Agencia debitada
		$Registro .= ' ';                                 // 058 a 058 - Em branco
		$Registro .= colocazeros('21199',12);             // 059 a 070 - Conta debitada
		$Registro .= ' ';                                 // 071 a 071 - Em branco
		$Registro .= '4';                                 // 072 a 072 - DAC - Digito verificado da conta	
		$Registro .= colocaespacos('INFORM SYSTEM TECN INF LTDA',30);   // 073 a 102 - Nome da empresa debitada
		$Registro .= colocaespacos('',30);                // 103 a 132 - Finalidade do lote
		$Registro .= colocaespacos('',10);                // 133 a 142 - Hist�rico de conta corrente
		$Registro .= colocaespacosdir('AV. MAL. FLORIANO PEIXOTO',30);            // 143 a 172 - Endereco da empresa
		$Registro .= colocaespacosdir('306',5);              // 173 a 177 - Numero
		$Registro .= colocaespacosdir('SALA 11',15);         // 178 a 192 - Complemento
		$Registro .= colocaespacosdir('CURITIBA',20);        // 193 a 212 - Cidade
		$Registro .= colocaespacosdir('80010130',8);         // 213 a 220 - CEP
		$Registro .= colocaespacos('PR',2);               // 221 a 222 - UF
		$Registro .= colocaespacos('',8);                 // 223 a 230 - Em branco
		$Registro .= colocazeros('',10);                  // 231 a 240 - Ocorrencias
		$totlinha++;
		$registros .= "$Registro\n";	
		# Fim do header
	
		while($registro = mysql_fetch_array($qr_sql)){

			$id_func = $registro['id'];
			$banco_func    =  $registro['nr_banco'];
			$agencia_func  =  $registro['agencia'];
			$conta_func    =  $registro['conta'];
			$conta_func    =  str_replace('-','',$conta_func);
			$conta_func    =  str_replace(' ','',$conta_func);
			$conta_func    =  str_replace('/','',$conta_func);
			$cpfcnpj_func  =  trim($registro['cpf']);
			$nome_func     =  substr($registro['nome'],0,30);
			$salario_func  =  $registro["salario"];
			$vt_vr         =  $registro["vt"];
			
			if ( empty($salario_func) ) $salario_func = 0;
			if ( empty($vt_vr) ) $vt_vr = 0;

			$salario = number_format($salario_func,2,'.','');
			$vt_vr   = number_format($vt_vr,2,'.','');
			
			if ( $salario_func > 0 ){

				# Cliente em dia com suas mensalidades
				$NumeroRegistro++;
				$CODLOJA = '99999'.$id;
				// REGISTRO DETALHES DO LOTE
				$Registro = '341';                                         // 001 a 003 - C�digo do banco
				$Registro .= str_pad($NumeroLote, 4, 0, STR_PAD_LEFT);     // 004 a 007 - N�mero do lote
				$Registro .= '3';                                          // 008 a 008 - Tipo do registro: Registro detalhe do lote
				$Registro .= str_pad($NumeroRegistro, 5, 0, STR_PAD_LEFT); // 009 a 013 - N�mero seq�encial do registro no lote
				$Registro .= 'A';                                          // 014 a 014 - C�digo do segmento do registro detalhe
				$Registro .= '000';                                        // 015 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
				$Registro .= '000';                                        // 018 a 020 - ZEROS
				$Registro .= str_pad($banco_func, 3, 0, STR_PAD_LEFT);  // 021 a 023 - C�digo do Banco do Favorecido
				
				$agencia   = colocazeros(substr($agencia_func,0,4),4);
				$conta     = substr($conta_func,0,strlen($conta_func)-1);
				$conta     = $conta * 1;
				$conta     = str_pad($conta, 6, 0, STR_PAD_LEFT);
				$dac_cta   = substr($conta_func,strlen($conta_func)-1,1);
				$dac_cta   = str_pad($dac_cta, 1, 0, STR_PAD_LEFT);

				$conta    =  str_pad($conta, 12, 0, STR_PAD_LEFT);
									  
				$agencia_conta = '0'.$agencia.' '.$conta.' '.$dac_cta;
						
				$Registro .= $agencia_conta;                               // 024 a 043 - Conta Corrente do Favorecido
				$Registro .= str_pad($nome_func, 30, ' ', STR_PAD_RIGHT);   // 044 a 073 - Nome do Favorecido
				$Registro .= str_pad('', 20, ' ', STR_PAD_RIGHT);          // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA
				$data = date('dmY');
				$Registro .= $data;                                        // 094 a 101 - Data de Pagamento
				$Registro .= 'REA';                                        // 102 a 104 - Tipo de Moeda
				$Registro .= str_pad('', 15, 0, STR_PAD_LEFT);             // 105 a 119 - ZEROS

				$valor = $salario_func * 100;
				$valor = str_replace(',','',$valor);
				$valor = str_replace('.','',$valor);
				$Registro .= str_pad($valor, 15, 0, STR_PAD_LEFT);         // 120 a 134 - Valor do PAGAMENTO
				$Registro .= str_pad('', 15, ' ', STR_PAD_RIGHT);          // 135 a 149 - Num DOC ATRIBUIDO P/ BANCO
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);           // 150 a 154 - BRANCOS
				$Registro .= '00000000';                                   // 155 a 162 - Data real da efetiva��o do lan�amento
				$Registro .= '000000000000000';                            // 163 a 177 - Valor real da efetiva��o do lan�amento

				$Registro .= str_pad($id_func, 18, ' ', STR_PAD_RIGHT);    // 178 a 195 - Outras informa��es
				$Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);           // 196 a 197 - BRANCOS
				$Registro .= str_pad('', 6, 0, STR_PAD_LEFT);              // 198 a 203 - Numero do Documento
						
				$Registro .= str_pad($cpfcnpj_func, 14, 0, STR_PAD_LEFT);   // 204 a 217 - CPF/CNPJ do Favorecido
				$Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);             // 218 a 219 - Finalidade do DOC
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 220 a 224 - Finalidade do TED
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 225 a 229 - BRANCOS
				$Registro .= str_pad('', 1, ' ', STR_PAD_RIGHT);             // 230 a 230 - Aviso ao Favorecido
				$Registro .= str_pad('', 10, ' ', STR_PAD_RIGHT);            // 231 a 240 - Ocorrencia de retorno

				$total_conta_poupanca  += $salario_func;
				$totlinha++;
				$registros .= "$Registro\n";
						
			}
		}
		# TRAILLER DO PRIMEIRO LOTE   -->>   CONTA POUPANCA ITAU
		$Registro  = '341';                                    // 001 a 003 - C�digo do banco
		$Registro .= colocazeros($NumeroLote,4);               // 004 a 007 - N�mero do lote
		$Registro .= '5';                                      // 008 a 008 - Tipo do registro: Registro trailer do lote
		$Registro .= colocaespacos('',9);                      // 009 a 017 - BRANCO
		$Registro .= colocazeros($NumeroRegistro+2,6);         // 018 a 023 - Qtd de registro de lote
		$valor = $total_conta_poupanca * 100;
		$valor = str_replace(',','',$valor);
		$valor = str_replace('.','',$valor);
		$Registro .= colocazeros($valor,18);                   // 024 a 041 - Somat�ria dos valores
		$Registro .= '000000000000000000';                     // 042 a 059 - ZEROS
		$Registro .= colocaespacos('',171);                    // 060 a 230 - BRANCOS
		$Registro .= colocaespacos('',10);                     // 231 a 240 - C�digo das ocorr�ncias para retorno
		$totlinha++;
		$registros .= "$Registro\n";
	}
	# FIM - CONTA POUPANCA BANCO ITAU

	# Selecionando TED

    $sql = "SELECT id, nome, nr_banco, agencia, tp_conta, conta, cpf, salario, vt
			FROM cs2.funcionario
			WHERE nr_banco <> '341' AND salario >= 5000 AND nr_banco > 0
			ORDER BY nome";
				
	$qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
	$qtd = mysql_num_rows($qr_sql);
	if ( $qtd > 0 ){
		
		$NumeroLote++;
		$NumeroRegistro = 0;	
		// HEADER
		$Registro  = '341';                               // 001 a 003 - C�digo do Banco
		$Registro .= colocazeros($NumeroLote,4);          // 004 a 007 - Codigo do Lote
		$Registro .= '1';                                 // 008 a 008 - Tipo de Registro
		$Registro .= 'C';                                 // 009 a 009 - Tipo de Opera��o
		$tp_pagto = '30';
		$Registro .= $tp_pagto;                           // 010 a 011 - Tipo de Pagamento | 20-Fornecedores | 30-Salarios
	
		# Forma de Pagamento
		# 01 - Credito em Conta Corrente no ITAU
		# 03 - DOC C
		# 05 - Credito em Conta Poupan�a no ITAU
		# 06 - Credito em Conta Corrente no ITAU de mesma titularidade
		# 07 - DOC D
		# 41 - TED Outro titular
		# 43 - TED Mesmo titular
		# 60 - Cartao Salario

		$forma_pgto = '41';
		$Registro .= colocazeros($forma_pgto,2);          // 012 a 013 - Forma de Pagamento
		$Registro .= '040';                               // 014 a 016 - Layout do Lote
		$Registro .= ' ';                                 // 017 a 017 - Em branco
		$Registro .= '2';                                 // 018 a 018 - Empresa Tipo Inscri��o - Debitada
		$Registro .= '06866893000139';                    // 019 a 032 - Empresa Numero Inscri��o - Debitada
	
		$Registro .= $tp_pagto.$forma_pgto;               // 033 a 036 - Identificacao do lan�amento
		$Registro .= colocaespacos('',16);                // 037 a 052 - Em branco
		
		$Registro .= '08616';                             // 053 a 057 - Agencia debitada
		$Registro .= ' ';                                 // 058 a 058 - Em branco
		$Registro .= colocazeros('21199',12);             // 059 a 070 - Conta debitada
		$Registro .= ' ';                                 // 071 a 071 - Em branco
		$Registro .= '4';                                 // 072 a 072 - DAC - Digito verificado da conta	
		$Registro .= colocaespacos('INFORM SYSTEM TECN INF LTDA',30);   // 073 a 102 - Nome da empresa debitada
		$Registro .= colocaespacos('',30);                // 103 a 132 - Finalidade do lote
		$Registro .= colocaespacos('',10);                // 133 a 142 - Hist�rico de conta corrente
		$Registro .= colocaespacosdir('AV. MAL. FLORIANO PEIXOTO',30);            // 143 a 172 - Endereco da empresa
		$Registro .= colocaespacosdir('306',5);              // 173 a 177 - Numero
		$Registro .= colocaespacosdir('SALA 11',15);         // 178 a 192 - Complemento
		$Registro .= colocaespacosdir('CURITIBA',20);        // 193 a 212 - Cidade
		$Registro .= colocaespacosdir('80010130',8);         // 213 a 220 - CEP
		$Registro .= colocaespacos('PR',2);               // 221 a 222 - UF
		$Registro .= colocaespacos('',8);                 // 223 a 230 - Em branco
		$Registro .= colocazeros('',10);                  // 231 a 240 - Ocorrencias
		$totlinha++;
		$registros .= "$Registro\n";	
		# Fim do header
	
		while($registro = mysql_fetch_array($qr_sql)){

			$id_func = $registro['id'];
			$banco_func    =  $registro['nr_banco'];
			$agencia_func  =  $registro['agencia'];
			$conta_func    =  $registro['conta'];
			$conta_func    =  str_replace('-','',$conta_func);
			$conta_func    =  str_replace(' ','',$conta_func);
			$conta_func    =  str_replace('/','',$conta_func);
			$cpfcnpj_func  =  trim($registro['cpf']);
			$nome_func     =  substr($registro['nome'],0,30);
			$salario_func  =  $registro["salario"];
			$vt_vr         =  $registro["vt"];
			
			if ( empty($salario_func) ) $salario_func = 0;
			if ( empty($vt_vr) ) $vt_vr = 0;

			$salario = number_format($salario_func,2,'.','');
			$vt_vr   = number_format($vt_vr,2,'.','');
			
			if ( $salario_func > 0 ){
				
				# Cliente em dia com suas mensalidades
				$NumeroRegistro++;
				$CODLOJA = '99999'.$id;
				// REGISTRO DETALHES DO LOTE
				$Registro = '341';                                         // 001 a 003 - C�digo do banco
				$Registro .= str_pad($NumeroLote, 4, 0, STR_PAD_LEFT);     // 004 a 007 - N�mero do lote
				$Registro .= '3';                                          // 008 a 008 - Tipo do registro: Registro detalhe do lote
				$Registro .= str_pad($NumeroRegistro, 5, 0, STR_PAD_LEFT); // 009 a 013 - N�mero seq�encial do registro no lote
				$Registro .= 'A';                                          // 014 a 014 - C�digo do segmento do registro detalhe
				$Registro .= '000';                                        // 015 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
				$Registro .= '000';                                        // 018 a 020 - ZEROS
				$Registro .= str_pad($banco_func, 3, 0, STR_PAD_LEFT);  // 021 a 023 - C�digo do Banco do Favorecido
					
				$agencia   = colocazeros(substr($agencia_func,0,4),5);
				$conta     = substr($conta_func,0,strlen($conta_func)-1);
				$conta     = $conta * 1;
				$conta     = str_pad($conta, 12, 0, STR_PAD_LEFT);
				$dac_cta   = substr($conta_func,strlen($conta_func)-1,1);
				$dac_cta   = str_pad($dac_cta, 1, 0, STR_PAD_LEFT);
						
				$agencia_conta = $agencia.' '.$conta.' '.$dac_cta;
						
				$Registro .= $agencia_conta;                               // 024 a 043 - Conta Corrente do Favorecido
				$Registro .= str_pad($nome_func, 30, ' ', STR_PAD_RIGHT);   // 044 a 073 - Nome do Favorecido
				$Registro .= str_pad('', 20, ' ', STR_PAD_RIGHT);          // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA
				$data = date('dmY');
				$Registro .= $data;                                        // 094 a 101 - Data de Pagamento
				$Registro .= 'REA';                                        // 102 a 104 - Tipo de Moeda
				$Registro .= str_pad('', 15, 0, STR_PAD_LEFT);             // 105 a 119 - ZEROS

				$valor = $salario_func * 100;
				$valor = str_replace(',','',$valor);
				$valor = str_replace('.','',$valor);
				$Registro .= str_pad($valor, 15, 0, STR_PAD_LEFT);         // 120 a 134 - Valor do PAGAMENTO
				$Registro .= str_pad('', 15, ' ', STR_PAD_RIGHT);          // 135 a 149 - Num DOC ATRIBUIDO P/ BANCO
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);           // 150 a 154 - BRANCOS
				$Registro .= '00000000';                                   // 155 a 162 - Data real da efetiva��o do lan�amento
				$Registro .= '000000000000000';                            // 163 a 177 - Valor real da efetiva��o do lan�amento

				$Registro .= str_pad($id_func, 18, ' ', STR_PAD_RIGHT);    // 178 a 195 - Outras informa��es
				$Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);           // 196 a 197 - BRANCOS
				$Registro .= str_pad('', 6, 0, STR_PAD_LEFT);              // 198 a 203 - Numero do Documento
					
				$Registro .= str_pad($cpfcnpj_func, 14, 0, STR_PAD_LEFT);   // 204 a 217 - CPF/CNPJ do Favorecido
				$Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);             // 218 a 219 - Finalidade do DOC
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 220 a 224 - Finalidade do TED
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 225 a 229 - BRANCOS
				$Registro .= str_pad('', 1, ' ', STR_PAD_RIGHT);             // 230 a 230 - Aviso ao Favorecido
				$Registro .= str_pad('', 10, ' ', STR_PAD_RIGHT);            // 231 a 240 - Ocorrencia de retorno

				$total_ted  += $salario_func;
				$totlinha++;
				$registros .= "$Registro\n";
			}
		}
		# TRAILLER - TED - OUTROS BANCOS
		$Registro  = '341';                                    // 001 a 003 - C�digo do banco
		$Registro .= colocazeros($NumeroLote,4);               // 004 a 007 - N�mero do lote
		$Registro .= '5';                                      // 008 a 008 - Tipo do registro: Registro trailer do lote
		$Registro .= colocaespacos('',9);                      // 009 a 017 - BRANCO
		$Registro .= colocazeros($NumeroRegistro+2,6);         // 018 a 023 - Qtd de registro de lote
		$valor = $total_ted * 100;
		$valor = str_replace(',','',$valor);
		$valor = str_replace('.','',$valor);
		$Registro .= colocazeros($valor,18);                   // 024 a 041 - Somat�ria dos valores
		$Registro .= '000000000000000000';                     // 042 a 059 - ZEROS
		$Registro .= colocaespacos('',171);                    // 060 a 230 - BRANCOS
		$Registro .= colocaespacos('',10);                     // 231 a 240 - C�digo das ocorr�ncias para retorno
		$totlinha++;
		$registros .= "$Registro\n";
	}
	# FIM - TED - OUTROS BANCOS

	#########################################################################################################

    $sql = "SELECT id, nome, nr_banco, agencia, tp_conta, conta, cpf, salario, vt
			FROM cs2.funcionario
			WHERE nr_banco <> '341' AND salario < 5000 AND nr_banco > 0
			ORDER BY nome";
	$qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
	$qtd = mysql_num_rows($qr_sql);
	if ( $qtd > 0 ){
			
		# INICIO - DOC - OUTROS BANCOS
		$NumeroLote++;
		$NumeroRegistro = 0;	
		// HEADER
		$Registro  = '341';                               // 001 a 003 - C�digo do Banco
		$Registro .= colocazeros($NumeroLote,4);          // 004 a 007 - Codigo do Lote
		$Registro .= '1';                                 // 008 a 008 - Tipo de Registro
		$Registro .= 'C';                                 // 009 a 009 - Tipo de Opera��o
		$tp_pagto = '30';
		$Registro .= $tp_pagto;                           // 010 a 011 - Tipo de Pagamento | 20-Fornecedores | 30-Salarios
	
		# Forma de Pagamento
		# 01 - Credito em Conta Corrente no ITAU
		# 03 - DOC C
		# 05 - Credito em Conta Poupan�a no ITAU
		# 06 - Credito em Conta Corrente no ITAU de mesma titularidade
		# 07 - DOC D
		# 41 - TED Outro titular
		# 43 - TED Mesmo titular
		# 60 - Cartao Salario

		$forma_pgto = '03';
		
		$Registro .= colocazeros($forma_pgto,2);          // 012 a 013 - Forma de Pagamento
		$Registro .= '040';                               // 014 a 016 - Layout do Lote
		$Registro .= ' ';                                 // 017 a 017 - Em branco
		$Registro .= '2';                                 // 018 a 018 - Empresa Tipo Inscri��o - Debitada
		$Registro .= '06866893000139';                    // 019 a 032 - Empresa Numero Inscri��o - Debitada
		
		$Registro .= $tp_pagto.$forma_pgto;               // 033 a 036 - Identificacao do lan�amento
		$Registro .= colocaespacos('',16);                // 037 a 052 - Em branco
		
		$Registro .= '08616';                             // 053 a 057 - Agencia debitada
		$Registro .= ' ';                                 // 058 a 058 - Em branco
		$Registro .= colocazeros('21199',12);             // 059 a 070 - Conta debitada
		$Registro .= ' ';                                 // 071 a 071 - Em branco
		$Registro .= '4';                                 // 072 a 072 - DAC - Digito verificado da conta	
		$Registro .= colocaespacos('INFORM SYSTEM TECN INF LTDA',30);   // 073 a 102 - Nome da empresa debitada
		$Registro .= colocaespacos('',30);                // 103 a 132 - Finalidade do lote
		$Registro .= colocaespacos('',10);                // 133 a 142 - Hist�rico de conta corrente
		$Registro .= colocaespacosdir('AV. MAL. FLORIANO PEIXOTO',30);            // 143 a 172 - Endereco da empresa
		$Registro .= colocaespacosdir('306',5);              // 173 a 177 - Numero
		$Registro .= colocaespacosdir('SALA 11',15);         // 178 a 192 - Complemento
		$Registro .= colocaespacosdir('CURITIBA',20);        // 193 a 212 - Cidade
		$Registro .= colocaespacosdir('80010130',8);         // 213 a 220 - CEP
		$Registro .= colocaespacos('PR',2);               // 221 a 222 - UF
		$Registro .= colocaespacos('',8);                 // 223 a 230 - Em branco
		$Registro .= colocazeros('',10);                  // 231 a 240 - Ocorrencias
		$totlinha++;
		$registros .= "$Registro\n";
		# Fim do header
	
		while($registro = mysql_fetch_array($qr_sql)){
			
			$id_func = $registro['id'];
			$banco_func    =  $registro['nr_banco'];
			$agencia_func  =  $registro['agencia'];
			$conta_func    =  $registro['conta'];
			$conta_func    =  str_replace('-','',$conta_func);
			$conta_func    =  str_replace(' ','',$conta_func);
			$conta_func    =  str_replace('/','',$conta_func);
			$cpfcnpj_func  =  trim($registro['cpf']);
			$nome_func     =  substr($registro['nome'],0,30);
			$salario_func  =  $registro["salario"];
			$vt_vr         =  $registro["vt"];
			
			if ( empty($salario_func) ) $salario_func = 0;
			if ( empty($vt_vr) ) $vt_vr = 0;

			$salario = number_format($salario_func,2,'.','');
			$vt_vr   = number_format($vt_vr,2,'.','');
			
			if ( $salario_func > 0 ){

				$NumeroRegistro++;
				$CODLOJA = '99999'.$id;
				// REGISTRO DETALHES DO LOTE
				$Registro = '341';                                         // 001 a 003 - C�digo do banco
				$Registro .= str_pad($NumeroLote, 4, 0, STR_PAD_LEFT);     // 004 a 007 - N�mero do lote
				$Registro .= '3';                                          // 008 a 008 - Tipo do registro: Registro detalhe do lote
				$Registro .= str_pad($NumeroRegistro, 5, 0, STR_PAD_LEFT); // 009 a 013 - N�mero seq�encial do registro no lote
				$Registro .= 'A';                                          // 014 a 014 - C�digo do segmento do registro detalhe
				$Registro .= '000';                                        // 015 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
				$Registro .= '000';                                        // 018 a 020 - ZEROS
				$Registro .= str_pad($banco_func, 3, 0, STR_PAD_LEFT);     // 021 a 023 - C�digo do Banco do Favorecido
				
				$agencia   = colocazeros(substr($agencia_func,0,4),5);
				$conta     = substr($conta_func,0,strlen($conta_func)-1);
				$conta     = $conta * 1;
				$conta     = str_pad($conta, 12, 0, STR_PAD_LEFT);
				$dac_cta   = substr($conta_func,strlen($conta_func)-1,1);
				$dac_cta   = str_pad($dac_cta, 1, 0, STR_PAD_LEFT);
						
				$agencia_conta = $agencia.' '.$conta.' '.$dac_cta;
					
				$Registro .= $agencia_conta;                               // 024 a 043 - Conta Corrente do Favorecido
				$Registro .= str_pad($nome_func, 30, ' ', STR_PAD_RIGHT);   // 044 a 073 - Nome do Favorecido
				$Registro .= str_pad('', 20, ' ', STR_PAD_RIGHT);          // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA
				$data = date('dmY');
				$Registro .= $data;                                        // 094 a 101 - Data de Pagamento
				$Registro .= 'REA';                                        // 102 a 104 - Tipo de Moeda
				$Registro .= str_pad('', 15, 0, STR_PAD_LEFT);             // 105 a 119 - ZEROS

				$valor = $salario_func * 100;
				$valor = str_replace(',','',$valor);
				$valor = str_replace('.','',$valor);
				$Registro .= str_pad($valor, 15, 0, STR_PAD_LEFT);         // 120 a 134 - Valor do PAGAMENTO
				$Registro .= str_pad('', 15, ' ', STR_PAD_RIGHT);          // 135 a 149 - Num DOC ATRIBUIDO P/ BANCO
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);           // 150 a 154 - BRANCOS
				$Registro .= '00000000';                                   // 155 a 162 - Data real da efetiva��o do lan�amento
				$Registro .= '000000000000000';                            // 163 a 177 - Valor real da efetiva��o do lan�amento
				$Registro .= str_pad($id_func, 18, ' ', STR_PAD_RIGHT);    // 178 a 195 - Outras informa��es
				$Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);           // 196 a 197 - BRANCOS
				$Registro .= str_pad('', 6, 0, STR_PAD_LEFT);              // 198 a 203 - Numero do Documento
						
				$Registro .= str_pad($cpfcnpj_func, 14, 0, STR_PAD_LEFT);  // 204 a 217 - CPF/CNPJ do Favorecido
				$Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);           // 218 a 219 - Finalidade do DOC
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);           // 220 a 224 - Finalidade do TED
				$Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);           // 225 a 229 - BRANCOS
				$Registro .= str_pad('', 1, ' ', STR_PAD_RIGHT);           // 230 a 230 - Aviso ao Favorecido
				$Registro .= str_pad('', 10, ' ', STR_PAD_RIGHT);          // 231 a 240 - Ocorrencia de retorno

				$total_doc  += $salario_func;
				$totlinha++;
				$registros .= "$Registro\n";
			}
		}
		# TRAILLER - DOC - OUTROS BANCOS
		$Registro  = '341';                                    // 001 a 003 - C�digo do banco
		$Registro .= colocazeros($NumeroLote,4);               // 004 a 007 - N�mero do lote
		$Registro .= '5';                                      // 008 a 008 - Tipo do registro: Registro trailer do lote
		$Registro .= colocaespacos('',9);                      // 009 a 017 - BRANCO
		$Registro .= colocazeros($NumeroRegistro+2,6);         // 018 a 023 - Qtd de registro de lote
		$valor = $total_doc * 100;
		$valor = str_replace(',','',$valor);
		$valor = str_replace('.','',$valor);
		$Registro .= colocazeros($valor,18);                   // 024 a 041 - Somat�ria dos valores
		$Registro .= '000000000000000000';                     // 042 a 059 - ZEROS
		$Registro .= colocaespacos('',171);                    // 060 a 230 - BRANCOS
		$Registro .= colocaespacos('',10);                     // 231 a 240 - C�digo das ocorr�ncias para retorno
		$totlinha++;
		$registros .= "$Registro\n";
		
		# FIM - DOC - OUTROS BANCOS
	}
	
	// TRAILER DO ARQUIVO
	$totlinha++;
	$Registro  = '341';                        // 001 a 003 - C�digo do banco
	$Registro .= '9999';                       // 004 a 007 - N�mero do lote
	$Registro .= '9';                          // 008 a 008 - Tipo do registro: Registro trailer do lote
	$Registro .= colocaespacos('',9);          // 009 a 017 - BRANCOS
	$Registro .= colocazeros($NumeroLote,6);   // 018 a 023 - Qtd de lotes no arquivo
	$Registro .= colocazeros($totlinha,6);     // 024 a 029 - quantidade de registros do arquivo
	$Registro .= colocaespacos('',211);        // 030 a 240 - USO EXCLUSIVO FEBRABAN/CNAB
	$registros .= "$Registro\n";

	$tot_pagar = $total_conta_corrente + $total_conta_poupanca + $total_doc + $total_ted;
	
	$total_geral = number_format($tot_pagar,2);

	try{
		$arquivo = "../../../download/SISPAG_SALARIO_FUNCIONARIO_".date("dmY");
		$arq = "SISPAG_SALARIO_FUNCIONARIO_".date("dmY");
		$abrir = fopen($arquivo, "w");
		$escreve = fputs($abrir, $registros);
		fclose($arquivo);
	} catch (Exception $e) {
    	echo 'Erro ao gravar o arquivo: ',  $e->getMessage(), "\n";
	}

	echo "<div align='center'>
		  <p>Arquivo Gerado com sucesso</p> 
		  <p><a href=https://www.webcontrolempresas.com.br/download/".$arq.">".$arq."</a><p>
		  <p>Total Geral : R$ $total_geral</p>
		  </div>";

?>
