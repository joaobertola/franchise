<?php

	# Author:  Luciano Mancini
	# M�dulo:  Remessa Fornecedor
	# Finalidade: Gerar o arquivo de cr�dito a serem enviados ao Banco do Brasil para os cliente CREDI�RIO/RECUPERE

	include("../../../validar2.php");

	global $conexao,$arquivo;
	conecex();

	function EnvioArqBco($Tipo,$NumeroArquivo,$NumeroLote){
		
		if ( $Tipo == 'HEADER' ){
			// GERAR REGISTRO HEADER DO ARQUIVO
			$Registro = '001';      // 001 a 003 - C�digo do banco
			$Registro .= '0000';                           // 004 a 007 - Lote de servi�o
			$Registro .= '0';                              // 008 a 008 - Tipo de registro - Registro header de arquivo
			$Registro .= colocaespacos('',9);              // 009 a 017 - Uso exclusivo FEBRABAN/CNAB
			$Registro .= '2';                              // 018 a 018 - Tipo de inscri��o do cedente
			$Registro .= '06866893000139';                 // 019 a 032 - N�mero de inscri��o do cedente
			$Registro .= '0009003400126       ';           // 033 a 052 - C�digo do conv�nio no banco
			$Registro .= '03051';                          // 053 a 057 - C�digo da ag�ncia do cedente
			$Registro .= '1';                              // 058 a 058 - D�gito da ag�ncia do cedente
			$Registro .= colocazeros('14236',12);          // 059 a 070 - N�mero da conta do cedente
			$Registro .= '0';                              // 071 a 071 - D�gito da conta do cedente
			$Registro .= ' ';                              // 072 a 072 - D�gito verificador da ag�ncia / conta
			$Registro .= colocaespacosdir('INFORM SYSTEM TECNOLOGIA EM IN',30);// 073 a 102 - Nome do cedente
			$Registro .= colocaespacosdir('BANCO DO BRASIL',30);               // 103 a 132 - Nome do banco
			$Registro .= colocaespacos('',10);                                 // 133 a 142 - Uso exclusivo FEBRABAN/CNAB
			$Registro .= '1';                                                // 143 a 143 - C�digo de Remessa (1) / Retorno (2)
			$data = date('dmY');
			$hora = date("His");
			$Registro .= $data;                               // 144 a 151 - Data do de gera��o do arquivo
			$Registro .= $hora;                               // 152 a 157 - Hora de gera��o do arquivo
			$Registro .= colocazeros($NumeroArquivo,6);       // 158 a 163 - N�mero seq�encial do arquivo
			$Registro .= '030';                               // 164 a 166 - N�mero da vers�o do layout do arquivo
			$Registro .= colocazeros('',5);                   // 167 a 171 - Densidade de grava��o do arquivo (BPI)
			$Registro .= colocaespacos('',20);                // 172 a 191 - Uso reservado do banco
			$Registro .= colocaespacos('',20);                // 192 a 211 - Uso reservado da empresa
			$Registro .= colocaespacos('',11);                // 212 a 222 - 11 brancos
			$Registro .= 'CSP';                               // 223 a 225 - 'CSP'
			$Registro .= '000';                               // 226 a 228 - Uso exclusivo de Vans
			$Registro .= colocaespacos('',2);                 // 229 a 230 - Tipo de servico
			$Registro .= colocaespacos('',10);                // 231 a 240 - titulo em carteira de cobranca
		}else if ( $Tipo == 'HEADERLOTECCBB' ){
			$Registro = '001';                               // 001 a 003 - C�digo do banco
			$Registro .= colocazeros($NumeroLote,4);          // 004 a 007 - N�mero do lote de servi�o
			$Registro .= '1';                                 // 008 a 008 - Tipo do registro - Registro header de lote
			$Registro .= 'C';                                 // 009 a 009 - Tipo de opera��o: C - CREDITO
			$Registro .= '20';                                // 010 a 011 - Tipo de servi�o: 20 (Pagamento Fornecedor)
			$Registro .= '01';                                // 012 a 013 - Forma de lan�amento: 01 (Credito em Conta Corrente)
			$Registro .= '020';                               // 014 a 016 - N�mero da vers�o do layout do lote
			$Registro .= ' ';                                 // 017 a 017 - Uso exclusivo FEBRABAN/CNAB
			$Registro .= '2';                                 // 018 a 018 - Tipo de inscri��o do cedente
			$Registro .= '06866893000139';                    // 019 a 032 - N�mero de inscri��o do cedente
			$Registro .= '0009003400126       ';              // 033 a 052 - C�digo do conv�nio no banco
			$Registro .= '03051';                             // 053 a 057 - C�digo da ag�ncia do cedente
			$Registro .= '1';                                 // 058 a 058 - D�gito da ag�ncia do cedente
			$Registro .= colocazeros('14236',12);             // 059 a 700 - N�mero da conta do cedente
			$Registro .= '0';                                 // 071 a 071 - D�gito da conta do cedente
			$Registro .= ' ';                                 // 072 a 073 - D�gito verificador da ag�ncia / conta
			$Registro .= colocaespacosdir('INFORM SYSTEM TECNOLOGIA EM IN',30); // 073 a 102 - Nome do cedente
			$Registro .= colocaespacos('',40);                // 103 a 142 - Mensagem 1 para todos os boletos do lote
			$Registro .= colocaespacosdir('AV. MARECHAL FLORIANO PEIXOTO',30);  // 143 a 172 - Nome da rua, av. p�a, etc
			$Registro .= '00306';                            // 173 a 177 - N�mero do local
			$Registro .= colocaespacosdir('SL 11',15);       // 178 a 192 - Complemento
			$Registro .= colocaespacosdir('CURITIBA',20);    // 193 a 212 - Cidade
			$Registro .= '80010';                            // 213 a 217 - CEP
			$Registro .= '130';                              // 218 a 220 - Complemento do CEP
			$Registro .= 'PR';                               // 221 a 222 - Sigla do Estado
			$Registro .= colocaespacos('',8);                // 223 a 230 - Uso exclusivo FEBRABAN/CNAB
			$Registro .= colocaespacos('',10);               // 231 a 240 - C�digo de Ocorr�ncias p/ retorno
		}else if ( $Tipo == 'HEADERLOTECPBB' ){
			$Registro = '001';                               // 001 a 003 - C�digo do banco
			$Registro .= colocazeros($NumeroLote,4);          // 004 a 007 - N�mero do lote de servi�o
			$Registro .= '1';                                 // 008 a 008 - Tipo do registro - Registro header de lote
			$Registro .= 'C';                                 // 009 a 009 - Tipo de opera��o: C - CREDITO
			$Registro .= '98';                                // 010 a 011 - Tipo de servi�o: 98 (Pagamento Diversos)
			$Registro .= '05';                                // 012 a 013 - Forma de lan�amento: 05 (Credito em Conta Poupan�a)
			$Registro .= '020';                               // 014 a 016 - N�mero da vers�o do layout do lote
			$Registro .= ' ';                                 // 017 a 017 - Uso exclusivo FEBRABAN/CNAB
			$Registro .= '2';                                 // 018 a 018 - Tipo de inscri��o do cedente
			$Registro .= '06866893000139';                    // 019 a 032 - N�mero de inscri��o do cedente
			$Registro .= '0009003400126       ';              // 033 a 052 - C�digo do conv�nio no banco
			$Registro .= '03051';                             // 053 a 057 - C�digo da ag�ncia do cedente
			$Registro .= '1';                                 // 058 a 058 - D�gito da ag�ncia do cedente
			$Registro .= colocazeros('14236',12);             // 059 a 700 - N�mero da conta do cedente
			$Registro .= '0';                                 // 071 a 071 - D�gito da conta do cedente
			$Registro .= ' ';                                 // 072 a 073 - D�gito verificador da ag�ncia / conta
			$Registro .= colocaespacosdir('INFORM SYSTEM TECNOLOGIA EM IN',30); // 073 a 102 - Nome do cedente
			$Registro .= colocaespacos('',40);                // 103 a 142 - Mensagem 1 para todos os boletos do lote
			$Registro .= colocaespacosdir('AV. MARECHAL FLORIANO PEIXOTO',30);  // 143 a 172 - Nome da rua, av. p�a, etc
			$Registro .= '00306';                            // 173 a 177 - N�mero do local
			$Registro .= colocaespacosdir('SL 11',15);       // 178 a 192 - Complemento
			$Registro .= colocaespacosdir('CURITIBA',20);    // 193 a 212 - Cidade
			$Registro .= '80010';                            // 213 a 217 - CEP
			$Registro .= '130';                              // 218 a 220 - Complemento do CEP
			$Registro .= 'PR';                               // 221 a 222 - Sigla do Estado
			$Registro .= colocaespacos('',8);                // 223 a 230 - Uso exclusivo FEBRABAN/CNAB
			$Registro .= colocaespacos('',10);               // 231 a 240 - C�digo de Ocorr�ncias p/ retorno
		}else if ( $Tipo == 'HEADERLOTECCOB' ){
			$Registro = '001';                               // 001 a 003 - C�digo do banco
			$Registro .= colocazeros($NumeroLote,4);          // 004 a 007 - N�mero do lote de servi�o
			$Registro .= '1';                                 // 008 a 008 - Tipo do registro - Registro header de lote
			$Registro .= 'C';                                 // 009 a 009 - Tipo de opera��o: C - CREDITO
			$Registro .= '20';                                // 010 a 011 - Tipo de servi�o: 20 (Pagamento Fornecedor)
			$Registro .= '03';                                // 012 a 013 - Forma de lan�amento: 03 (DOC/TED)
			$Registro .= '020';                               // 014 a 016 - N�mero da vers�o do layout do lote
			$Registro .= ' ';                                 // 017 a 017 - Uso exclusivo FEBRABAN/CNAB
			$Registro .= '2';                                 // 018 a 018 - Tipo de inscri��o do cedente
			$Registro .= '06866893000139';                    // 019 a 032 - N�mero de inscri��o do cedente
			$Registro .= '0009003400126       ';              // 033 a 052 - C�digo do conv�nio no banco
			$Registro .= '03051';                             // 053 a 057 - C�digo da ag�ncia do cedente
			$Registro .= '1';                                 // 058 a 058 - D�gito da ag�ncia do cedente
			$Registro .= colocazeros('14236',12);             // 059 a 700 - N�mero da conta do cedente
			$Registro .= '0';                                 // 071 a 071 - D�gito da conta do cedente
			$Registro .= ' ';                                 // 072 a 073 - D�gito verificador da ag�ncia / conta
			$Registro .= colocaespacosdir('INFORM SYSTEM TECNOLOGIA EM IN',30); // 073 a 102 - Nome do cedente
			$Registro .= colocaespacos('',40);                // 103 a 142 - Mensagem 1 para todos os boletos do lote
			$Registro .= colocaespacosdir('AV. MARECHAL FLORIANO PEIXOTO',30);  // 143 a 172 - Nome da rua, av. p�a, etc
			$Registro .= '00306';                            // 173 a 177 - N�mero do local
			$Registro .= colocaespacosdir('SL 11',15);       // 178 a 192 - Complemento
			$Registro .= colocaespacosdir('CURITIBA',20);    // 193 a 212 - Cidade
			$Registro .= '80010';                            // 213 a 217 - CEP
			$Registro .= '130';                              // 218 a 220 - Complemento do CEP
			$Registro .= 'PR';                               // 221 a 222 - Sigla do Estado
			$Registro .= colocaespacos('',8);                // 223 a 230 - Uso exclusivo FEBRABAN/CNAB
			$Registro .= colocaespacos('',10);               // 231 a 240 - C�digo de Ocorr�ncias p/ retorno
		}else if ( $Tipo == 'HEADERLOTECPOB' ){
			$Registro = '001';                               // 001 a 003 - C�digo do banco
			$Registro .= colocazeros($NumeroLote,4);          // 004 a 007 - N�mero do lote de servi�o
			$Registro .= '1';                                 // 008 a 008 - Tipo do registro - Registro header de lote
			$Registro .= 'C';                                 // 009 a 009 - Tipo de opera��o: C - CREDITO
			$Registro .= '20';                                // 010 a 011 - Tipo de servi�o: 20 (Pagamento Fornecedor)
			$Registro .= '03';                                // 012 a 013 - Forma de lan�amento: 03 (DOC/TED)
			$Registro .= '020';                               // 014 a 016 - N�mero da vers�o do layout do lote
			$Registro .= ' ';                                 // 017 a 017 - Uso exclusivo FEBRABAN/CNAB
			$Registro .= '2';                                 // 018 a 018 - Tipo de inscri��o do cedente
			$Registro .= '06866893000139';                    // 019 a 032 - N�mero de inscri��o do cedente
			$Registro .= '0009003400126       ';              // 033 a 052 - C�digo do conv�nio no banco
			$Registro .= '03051';                             // 053 a 057 - C�digo da ag�ncia do cedente
			$Registro .= '1';                                 // 058 a 058 - D�gito da ag�ncia do cedente
			$Registro .= colocazeros('14236',12);             // 059 a 700 - N�mero da conta do cedente
			$Registro .= '0';                                 // 071 a 071 - D�gito da conta do cedente
			$Registro .= ' ';                                 // 072 a 073 - D�gito verificador da ag�ncia / conta
			$Registro .= colocaespacosdir('INFORM SYSTEM TECNOLOGIA EM IN',30); // 073 a 102 - Nome do cedente
			$Registro .= colocaespacos('',40);                // 103 a 142 - Mensagem 1 para todos os boletos do lote
			$Registro .= colocaespacosdir('AV. MARECHAL FLORIANO PEIXOTO',30);  // 143 a 172 - Nome da rua, av. p�a, etc
			$Registro .= '00306';                            // 173 a 177 - N�mero do local
			$Registro .= colocaespacosdir('SL 11',15);       // 178 a 192 - Complemento
			$Registro .= colocaespacosdir('CURITIBA',20);    // 193 a 212 - Cidade
			$Registro .= '80010';                            // 213 a 217 - CEP
			$Registro .= '130';                              // 218 a 220 - Complemento do CEP
			$Registro .= 'PR';                               // 221 a 222 - Sigla do Estado
			$Registro .= colocaespacos('',8);                // 223 a 230 - Uso exclusivo FEBRABAN/CNAB
			$Registro .= colocaespacos('',10);               // 231 a 240 - C�digo de Ocorr�ncias p/ retorno
		}
		return $Registro."\n";
	}

	function EnvioArqBco_DETALHES($Tipo,$NumeroLote,$NumeroRegistro,$Banco,$Agencia,$Conta,$NomeFavorecido,$IdFranquia,$saldo,$TipoLcto,$DocFavorecido){
		$Banco   = str_replace('-','',$Banco);
		$Agencia = str_replace('-','',$Agencia);
		$Conta   = str_replace('-','',$Conta);
		$CODLOJA = '99999'.$IdFranquia;
		if ( $Tipo == 'DETALHES_LOTE_BB' ){
			// REGISTRO DETALHES DO LOTE
			$Registro  = '001';                             // 001 a 003 - C�digo do banco
			$Registro .= colocazeros($NumeroLote,4);        // 004 a 007 - N�mero do lote
			$Registro .= '3';                               // 008 a 008 - Tipo do registro: Registro detalhe do lote
			$Registro .= colocazeros($NumeroRegistro,5);    // 009 a 013 - N�mero seq�encial do registro no lote - Cada t�tulo tem 2 registros (P e Q)
			$Registro .= 'A';         // 014 a 014 - C�digo do segmento do registro detalhe
			$Registro .= '0';         // 015 a 015 - Tipo de movimento  0-Inclusao
			$Registro .= '00';        // 016 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
			$Registro .= '000';       // 018 a 020 - C�digo da camara centralizadora  
									  //       700 - DOC
									  //       018 - TED   
									  //       Quando BB Informar "000"
			$Registro .= colocazeros($Banco,3);   // 021 a 023 - C�digo do Banco do Favorecido
			$Registro .= colocazeros(substr($Agencia,0,strlen($Agencia)-1),5);  // 024 a 028 - C�digo da Agencia do Favorecido
			$Registro .= colocazeros(substr($Agencia,strlen($Agencia)-1,1),1);  // 029 a 029 - Digito Verificado da Agencia do Favorecido
			$Registro .= colocazeros(substr($Conta,0,strlen($Conta)-1),12);   // 030 a 041 - C�digo da Conta Corrente do Favorecido
			$Registro .= colocazeros(substr($Conta,strlen($Conta)-1,1),1);    // 042 a 042 - Digito da Conta Corrente do Favorecido
			$Registro .= ' ';                              // 043 a 043 - Digito verificador da AG/CONTA
			$Registro .= colocaespacosdir($NomeFavorecido,30);   // 044 a 073 - Nome do Favorecido
			$Registro .= colocaespacos('',20);             // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA -  DOC,NF,NP,ETC
			# data atual mais 1 dia
			$data = gmdate("dmY", time()+(3600*27));
			$Registro .= $data;                           // 094 a 101 - Data para lan�amento do Debito ou Credito
			$Registro .= 'BRL';                           // 102 a 104 - Tipo de Moeda
			$Registro .= colocazeros('',15);              // 105 a 119 - QTD de moeda
			$valor = $saldo * 100;
			$valor = str_replace(',','',$valor);
			$valor = str_replace('.','',$valor);
			$Registro .= colocazeros($valor,15);          // 120 a 134 - Valor para DEBITAR ou CREDITAR
			$Registro .= colocaespacos('',20);            // 135 a 154 - Num DOC ATRIBUIDO P/ BANCO
			$Registro .= '00000000';                      // 155 a 162 - Data real da efetiva��o do lan�amento
			$Registro .= '000000000000000';               // 163 a 177 - Valor real da efetiva��o do lan�amento
			$Registro .= colocaespacosdir($CODLOJA,40);    // codloja do associado  // 178 a 217 - Outras informa��es
			$Registro .= colocaespacos('',12);             // 218 a 229 - USO EXCLUSIVO FEBRABAN/CNAB
			$Registro .= '0';                              // 230 a 230 - Aviso ao favorecido  0-nao  1-sim
			$Registro .= colocaespacos('',10);             // 231 a 240 - CODIGOS DAS OCORRENCIAS P/ RETORNO
			$total += $nsaldo;
		}
		if ( $Tipo == 'DETALHES_LOTE_DOC_SEGMENTO_A' ){
			// REGISTRO DETALHES DO LOTE  - SEGMENTO A
			$Registro = '001';                           // 001 a 003 - C�digo do banco
			$Registro .= colocazeros($NumeroLote,4);     // 004 a 007 - N�mero do lote
			$Registro .= '3';                            // 008 q 008 - Tipo do registro: Registro detalhe do lote
			$Registro .= colocazeros($NumeroRegistro,5); // 009 a 013 - N�mero seq�encial do registro no lote - Cada t�tulo tem 2 registros (P e Q)
			$Registro .= 'A';          // 014 a 014 - C�digo do segmento do registro detalhe
			$Registro .= '0';          // 015 a 015 - Tipo de movimento  0-Inclusao
			$Registro .= '00';         // 016 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
			$Registro .= '700';        // 018 a 020 - C�digo da camara centralizadora  
									   //       700 - DOC
									   //       018 - TED   
									   //       Quando BB Informar "000"
			$Registro .= colocazeros($Banco,3);          // 021 a 023 - C�digo do Banco do Favorecido
			if ( strlen($Agencia) <= 4 ) $Registro .= colocazeros($Agencia,5);
			else $Registro .= colocazeros(substr($Agencia,0,strlen($Agencia)-1),5);    // 024 a 028 - C�d Agen do Favorecido
			$Registro .= ' ';
			$Registro .= colocazeros(substr($Conta,0,strlen($Conta)-1),12);   // 030 a 041 - C�digo da Conta Corrente do Favorecido
			$Registro .= colocazeros(substr($Conta,strlen($Conta)-1,1),1);    // 042 a 042 - Digito da Conta Corrente do Favorecido
			$Registro .= ' ';                              // 043 a 043 - Digito verificador da AG/CONTA
			$Registro .= colocaespacosdir($NomeFavorecido,30);   // 044 a 073 - Nome do Favorecido
			$Registro .= colocaespacos('',20);             // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA -  DOC,NF,NP,ETC
			# data atual mais 1 dia
			$data = gmdate("dmY", time()+(3600*27));
			$Registro .= $data;                           // 094 a 101 - Data para lan�amento do Debito ou Credito
			$Registro .= 'BRL';                           // 102 a 104 - Tipo de Moeda
			$Registro .= colocazeros('',15);              // 105 a 119 - QTD de moeda
			$valor = $saldo * 100;
			$valor = str_replace(',','',$valor);
			$valor = str_replace('.','',$valor);
			$Registro .= colocazeros($valor,15);          // 120 a 134 - Valor para DEBITAR ou CREDITAR
			$Registro .= colocaespacos('',20);            // 135 a 154 - Num DOC ATRIBUIDO P/ BANCO
			$Registro .= '00000000';                      // 155 a 162 - Data real da efetiva��o do lan�amento
			$Registro .= '000000000000000';               // 163 a 177 - Valor real da efetiva��o do lan�amento
			$Registro .= colocaespacosdir($CODLOJA,40);   // codloja do associado  // 178 a 217 - Outras informa��es
			$Registro .= colocaespacos('',12);            // 218 a 229 - USO EXCLUSIVO FEBRABAN/CNAB
			$Registro .= '0';                             // 230 a 230 - Aviso ao favorecido  0-nao  1-sim
			$Registro .= colocaespacos('',10);            // 231 a 240 - CODIGOS DAS OCORRENCIAS P/ RETORNO
		}
		if ( $Tipo == 'DETALHES_LOTE_DOC_SEGMENTO_B' ){
			$Registro = '001';                             // 001 a 003 - C�digo do banco
			$Registro .= colocazeros($NumeroLote,4);       // 004 a 007 - N�mero do lote
			$Registro .= '3';                              // 008 a 008 - Registro detalhe do lote
			$Registro .= colocazeros($NumeroRegistro,5);   // 009 a 013 - N�mero seq. do registro no lote - Cada t�tulo tem 2 registros (P e Q)
			$Registro .= 'B';                              // 014 a 014 - C�digo do segmento do registro detalhe
			$Registro .= '   ';                            // 015 a 017 - BRANCOS
			$tam = strlen(trim($DocFavorecido));
			if ($tam==11) $Registro .= '1'; // 018 a 018 - 1 CPF 2 CGC
			else $Registro .= '2';                         // 018 a 018 - 1 CPF 2 CGC
			$Registro .= colocazeros($DocFavorecido,14);     // 019 a 032 - Numero de Inscricao do Favorecido
			$Registro .= colocaespacos('',30);             // 033 a 020 - Endere�o do Favorecido
			$Registro .= colocazeros('',5);                // 063 a 067 - Numero do Endere�o
			$Registro .= colocaespacos('',15);             // 068 a 082 - Complemento Endere�o
			$Registro .= colocaespacos('',15);             // 083 a 097 - Bairro do Endere�o
			$Registro .= colocaespacos('',20);             // 098 a 117 - Nome da Cidade
			$Registro .= colocazeros('',5);                // 118 a 122 - CEP
			$Registro .= colocaespacos('',3);              // 123 a 125 - Complemento CEP
			$Registro .= colocaespacos('',2);              // 126 a 127 - UF
			$Registro .= '00000000';                       // 128 a 135 - Data do Vencimento
			$Registro .= colocazeros('',15);               // 136 a 150 - Valor do Documento
			$Registro .= colocazeros('',15);               // 151 a 165 - Valor do Abatimento
			$Registro .= colocazeros('',15);               // 166 a 180 - Valor do Desconto
			$Registro .= colocazeros('',15);               // 181 a 195 - Valor da Mora
			$Registro .= colocazeros('',15);               // 196 a 210 - Valor da Multa
			$Registro .= colocaespacos('',15);             // 211 a 225 - Codigo do Favorecido
			$Registro .= colocaespacos('',15);             // 226 a 240 - USO EXCLUSIVO FEBRABAN
		}
		return $Registro."\n";
	}
	
	
	$totccbb = 0;
	$totcpbb = 0;
	$totccou = 0;
	$totcpou = 0;
	$total = 0;
	$totlinha = 0;
	$NumeroLote = 0;
        $tarifa_doc = 10.00;
        
	$sql = "SELECT id, MID(razaosoc,1,30) razaosoc, banco, agencia, tpconta, conta, titular, cpftitular 
			FROM cs2.franquia
			ORDER BY id";
	$qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
	$qtd = mysql_num_rows($qr_sql);
	if ( $qtd > 0 ){
		while($registro = mysql_fetch_array($qr_sql)){
			$id = $registro['id'];
			$raz = $registro['razaosoc'];
			$bco = $registro['banco'];
			$age = $registro['agencia'];
			$tpcta = $registro['tpconta'];
			$cta = $registro['conta'];
			$doct = $registro['cpftitular'];
			$tit = $registro['titular'];
			$saldo = 0;
			$xsql = "select valor,operacao from cs2.contacorrente where franqueado = '$id' order by id asc";
			$xqr_sql = mysql_query($xsql,$conexao) or die("Erro no SQL: $sql");
			$xqtd = mysql_num_rows($xqr_sql);
			if ( $xqtd > 0 ){
				while($xregistro = mysql_fetch_array($xqr_sql)){
					$valor = $xregistro['valor'];
					$operacao = $xregistro['operacao'];
					if ( $operacao == 0 ) $saldo += $valor;
					else $saldo -= $valor;
				}
			}
			// Somar os valores dos titulos pagos com data superior a data atual
			$xsql = "SELECT 
						SUM(valor) as valorpg 
			 		 FROM cs2.contacorrente 
					 WHERE franqueado='$id' AND venc_titulo >= NOW()";
			$xqr_sql = mysql_query($xsql,$conexao) or die("Erro no SQL: $sql");
			$array_sql=mysql_fetch_array($xqr_sql);
			$valorpg = $array_sql["valorpg"];
			if ( empty($valorpg) ) $valorpg = 0;
			$saldo -= $valorpg;
			
			$saldo = number_format($saldo,2,'.','');
				
			if (  $tpcta == '0' || $bco == '0' || $saldo <= 0  ){
				$erro .= $id.' '.colocaespacosdir($raz,30).'  '.colocazeros($bco,3).' '.colocazeros($age,6).' '.colocaespacosdir($tpcta,15).'  '.colocaespacosdir($cta,15).'  '.colocaespacosdir($doct,15).'  '.colocaespacosdir($tit,45).' R$ '.($saldo - $vr_titulo_a_vencer);
			}else{
				$reg++;
				$MeuRegistro[$reg]["Franquia"] = $id;
				$MeuRegistro[$reg]["Banco"]    = $bco;
				$MeuRegistro[$reg]["Agencia"]  = $age;
				$MeuRegistro[$reg]["TipoConta"] = $tpcta;
				$MeuRegistro[$reg]["Conta"]    = $cta;
				$MeuRegistro[$reg]["DocTitular"]  = $doct;
				$MeuRegistro[$reg]["NomeTitular"] = $tit;
				$MeuRegistro[$reg]["Valor"]       = $saldo;
			}
		}
	
		// CORRENTISTA BANCO DO BRASIL - CONTA CORRENTE
		// REGISTRO HEADER DO LOTE  0001
		$NumeroArquivo = '1';
		$Tipo = 'HEADER';
		$registro = EnvioArqBco($Tipo,$NumeroArquivo,$NumeroLote);
		$totlinha++;
		$NumeroLote++;
		$Tipo = 'HEADERLOTECCBB';
		$registro .= EnvioArqBco($Tipo,$NumeroArquivo,$NumeroLote);
		$totlinha++;
		$NumeroRegistro = 0;
		for ( $I=1 ; $I <= $reg; $I++ ){
			$Banco = $MeuRegistro[$I]["Banco"];
			$TpConta = $MeuRegistro[$I]["TipoConta"];
			$Agencia = $MeuRegistro[$I]["Agencia"];
			$Conta = $MeuRegistro[$I]["Conta"];
			$NomeFavorecido = $MeuRegistro[$I]["NomeTitular"];
			$NomeFavorecido = substr($NomeFavorecido,0,30);
			$DocFavorecido = trim($MeuRegistro[$I]["DocTitular"]);
			$IdFranquia = $MeuRegistro[$I]["Franquia"];
			$saldo = $MeuRegistro[$I]["Valor"];
			$saldo -= 8;
			$saldo_negativo = strpos($saldo,'-');
			if (  $TpConta == '1' ){
				if ( $saldo_negativo == 0 ){
					$totlinha++;
					$NumeroRegistro++;
					$TipoLcto = '';
					$registro .= EnvioArqBco_DETALHES('DETALHES_LOTE_BB',$NumeroLote,$NumeroRegistro,$Banco,$Agencia,$Conta,$NomeFavorecido,$IdFranquia,$saldo,'CC_BB',$DocFavorecido);
					$totccbb  += $saldo;
					$total += $saldo;
				}
			}
		}
		// TRAILER DO LOTE 001
		$Registro = '';
		$Registro .= '001';                                    // 001 a 003 - C�digo do banco
		$Registro .= colocazeros($NumeroLote,4);               // 004 a 007 - N�mero do lote
		$Registro .= '5';                                      // 008 a 008 - Tipo do registro: Registro trailer do lote
		$Registro .= colocaespacos('',9);                      // 009 a 017 - USO EXCLUSIVO FEBRABAN/CNAB
		$Registro .= colocazeros($NumeroRegistro+2,6);         // 018 a 023 - Qtd de registro de lote
		$valor = $totccbb * 100;
		$valor = str_replace(',','',$valor);
		$valor = str_replace('.','',$valor);
		$Registro .= colocazeros($valor,18);                   // 024 a 041 - Somat�ria dos valores DEB/CRED
		$Registro .= '000000000000000000';                     // 042 a 059 - Somat�ria de quant de moedas
		$Registro .= colocaespacos('',171);                    // 060 a 230 - Uso exclusivo FEBRABAN/CNAB
		$Registro .= colocaespacos('',10);                     // 231 a 240 - C�digo das ocorr�ncias para retorno
		$totlinha++;
		$registro .= "$Registro\n";
		
		// CORRENTISTA BANCO DO BRASIL - CONTA POUPAN�A
		// REGISTRO HEADER DO LOTE  0002
		$totlinha++;
		$NumeroLote++;
		$Tipo = 'HEADERLOTECPBB';
		$registro .= EnvioArqBco($Tipo,$NumeroArquivo,$NumeroLote);
		$NumeroRegistro = 0;
		for ( $I=1 ; $I <= $reg; $I++ ){
			$Banco = $MeuRegistro[$I]["Banco"];
			$TpConta = $MeuRegistro[$I]["TipoConta"];
			$Agencia = $MeuRegistro[$I]["Agencia"];
			$Conta = $MeuRegistro[$I]["Conta"];
			$NomeFavorecido = $MeuRegistro[$I]["NomeTitular"];
			$NomeFavorecido = substr($NomeFavorecido,0,30);
			$DocFavorecido = trim($MeuRegistro[$I]["DocTitular"]);
			$IdFranquia = $MeuRegistro[$I]["Franquia"];
			$saldo = $MeuRegistro[$I]["Valor"];
			$saldo -= 8;
			$saldo_negativo = strpos($saldo,'-');
			if ( $TpConta == '2' ){
				if ( $saldo_negativo == 0 ){
					$totlinha++;
					$NumeroRegistro++;
					$TipoLcto = '';
					$registro .= EnvioArqBco_DETALHES('DETALHES_LOTE_BB',$NumeroLote,$NumeroRegistro,$Banco,$Agencia,$Conta,$NomeFavorecido,$IdFranquia,$saldo,'CP_BB',$DocFavorecido);
					$totcpbb  += $saldo;
					$total += $saldo;
				}
			}
		}
		// TRAILER DO LOTE 002
		$Registro = '';
		$Registro .= '001';                                    // 001 a 003 - C�digo do banco
		$Registro .= colocazeros($NumeroLote,4);               // 004 a 007 - N�mero do lote
		$Registro .= '5';                                      // 008 a 008 - Tipo do registro: Registro trailer do lote
		$Registro .= colocaespacos('',9);                      // 009 a 017 - USO EXCLUSIVO FEBRABAN/CNAB
		$Registro .= colocazeros($NumeroRegistro+2,6);         // 018 a 023 - Qtd de registro de lote
		$valor = $totcpbb * 100;
		$valor = str_replace(',','',$valor);
		$valor = str_replace('.','',$valor);
		$Registro .= colocazeros($valor,18);                   // 024 a 041 - Somat�ria dos valores DEB/CRED
		$Registro .= '000000000000000000';                     // 042 a 059 - Somat�ria de quant de moedas
		$Registro .= colocaespacos('',171);                    // 060 a 230 - Uso exclusivo FEBRABAN/CNAB
		$Registro .= colocaespacos('',10);                     // 231 a 240 - C�digo das ocorr�ncias para retorno
		$totlinha++;
		$registro .= "$Registro\n";

		// CORRENTISTA OUTROS BANCOS - CONTA CORRENTE (DOC/TED)
		// REGISTRO HEADER DO LOTE  0003
		$totlinha++;
		$NumeroLote++;
		$Tipo = 'HEADERLOTECCOB';
		$registro .= EnvioArqBco($Tipo,$NumeroArquivo,$NumeroLote);
		$NumeroRegistro = 0;
		$totcpou = 0;
		for ( $I=1 ; $I <= $reg; $I++ ){
			$Banco = $MeuRegistro[$I]["Banco"];
			$TpConta = $MeuRegistro[$I]["TipoConta"];
			$Agencia = $MeuRegistro[$I]["Agencia"];
			$Conta = $MeuRegistro[$I]["Conta"];
			$NomeFavorecido = $MeuRegistro[$I]["NomeTitular"];
			$NomeFavorecido = substr($NomeFavorecido,0,30);
			$DocFavorecido = trim($MeuRegistro[$I]["DocTitular"]);
			$IdFranquia = $MeuRegistro[$I]["Franquia"];
			$saldo = $MeuRegistro[$I]["Valor"];
			if (  $Banco <> '001' && $TpConta == '1' ){
				if ( ( $saldo - $tarifa_doc ) > 0 ){
					$totlinha++;
					$NumeroRegistro++;
					$TipoLcto = '';
					$registro .= EnvioArqBco_DETALHES('DETALHES_LOTE_DOC_SEGMENTO_A',$NumeroLote,$NumeroRegistro,$Banco,$Agencia,$Conta,$NomeFavorecido,$IdFranquia,($saldo-$tarifa_doc),'CC_OB',$DocFavorecido);
					$totccou  += ($saldo-$tarifa_doc);
					$totlinha++;
					$NumeroRegistro++;
					$registro .= EnvioArqBco_DETALHES('DETALHES_LOTE_DOC_SEGMENTO_B',$NumeroLote,$NumeroRegistro,$Banco,$Agencia,$Conta,$NomeFavorecido,$IdFranquia,$saldo,'CC_OB',$DocFavorecido);		
					$total += $saldo;
				}
			}
		}
		// TRAILER DO LOTE 002
		$Registro = '';
		$Registro .= '001';                                    // 001 a 003 - C�digo do banco
		$Registro .= colocazeros($NumeroLote,4);               // 004 a 007 - N�mero do lote
		$Registro .= '5';                                      // 008 a 008 - Tipo do registro: Registro trailer do lote
		$Registro .= colocaespacos('',9);                      // 009 a 017 - USO EXCLUSIVO FEBRABAN/CNAB
		$Registro .= colocazeros($NumeroRegistro+2,6);         // 018 a 023 - Qtd de registro de lote
		$valor = $totccou * 100;
		$valor = str_replace(',','',$valor);
		$valor = str_replace('.','',$valor);
		$Registro .= colocazeros($valor,18);                   // 024 a 041 - Somat�ria dos valores DEB/CRED
		$Registro .= '000000000000000000';                     // 042 a 059 - Somat�ria de quant de moedas
		$Registro .= colocaespacos('',171);                    // 060 a 230 - Uso exclusivo FEBRABAN/CNAB
		$Registro .= colocaespacos('',10);                     // 231 a 240 - C�digo das ocorr�ncias para retorno
		$totlinha++;
		$registro .= "$Registro\n";

		// CORRENTISTA OUTROS BANCOS - CONTA POUPANCA (DOC/TED)
		// REGISTRO HEADER DO LOTE  0003
		$totlinha++;
		$NumeroLote++;
		$Tipo = 'HEADERLOTECPOB';
		$registro .= EnvioArqBco($Tipo,$NumeroArquivo,$NumeroLote);
		$NumeroRegistro = 0;
		$totcpou = 0;
		for ( $I=1 ; $I <= $reg; $I++ ){
			$Banco = $MeuRegistro[$I]["Banco"];
			$TpConta = $MeuRegistro[$I]["TipoConta"];
			$Agencia = $MeuRegistro[$I]["Agencia"];
			$Conta = $MeuRegistro[$I]["Conta"];
			$NomeFavorecido = $MeuRegistro[$I]["NomeTitular"];
			$NomeFavorecido = substr($NomeFavorecido,0,30);
			$DocFavorecido = trim($MeuRegistro[$I]["DocTitular"]);
			$IdFranquia = $MeuRegistro[$I]["Franquia"];
			$saldo = $MeuRegistro[$I]["Valor"];
			if ( $TpConta == '2' ){
				if ( ( $saldo - 8 ) > 0 ){
					$totlinha++;
					$NumeroRegistro++;
					$TipoLcto = '';
					$registro .= EnvioArqBco_DETALHES('DETALHES_LOTE_DOC_SEGMENTO_A',$NumeroLote,$NumeroRegistro,$Banco,$Agencia,$Conta,$NomeFavorecido,$IdFranquia,($saldo-$tarifa_doc),'CP_OB',$DocFavorecido);
					$totcpou  += ($saldo-$tarifa_doc);
					$totlinha++;
					$NumeroRegistro++;
					$registro .= EnvioArqBco_DETALHES('DETALHES_LOTE_DOC_SEGMENTO_B',$NumeroLote,$NumeroRegistro,$Banco,$Agencia,$Conta,$NomeFavorecido,$IdFranquia,$saldo,'CP_OB',$DocFavorecido);		
					$total += $saldo;
				}
			}
		}
		// TRAILER DO LOTE 002
		$Registro = '';
		$Registro .= '001';                                    // 001 a 003 - C�digo do banco
		$Registro .= colocazeros($NumeroLote,4);               // 004 a 007 - N�mero do lote
		$Registro .= '5';                                      // 008 a 008 - Tipo do registro: Registro trailer do lote
		$Registro .= colocaespacos('',9);                      // 009 a 017 - USO EXCLUSIVO FEBRABAN/CNAB
		$Registro .= colocazeros($NumeroRegistro+2,6);         // 018 a 023 - Qtd de registro de lote
		$valor = $totcpou * 100;
		$valor = str_replace(',','',$valor);
		$valor = str_replace('.','',$valor);
		$Registro .= colocazeros($valor,18);                   // 024 a 041 - Somat�ria dos valores DEB/CRED
		$Registro .= '000000000000000000';                     // 042 a 059 - Somat�ria de quant de moedas
		$Registro .= colocaespacos('',171);                    // 060 a 230 - Uso exclusivo FEBRABAN/CNAB
		$Registro .= colocaespacos('',10);                     // 231 a 240 - C�digo das ocorr�ncias para retorno
		$totlinha++;
		$registro .= "$Registro\n";
	}
	$totlinha++;

	// TRAILER DO ARQUIVO
	$Registro = '';
	$Registro .= '001';                        // 001 a 003 - C�digo do banco
	$Registro .= '9999';                       // 004 a 007 - N�mero do lote
	$Registro .= '9';                          // 008 a 008 - Tipo do registro: Registro trailer do lote
	$Registro .= colocaespacos('',9);          // 009 a 017 - USO EXCLUSIVO FEBRABAN/CNAB
	$Registro .= colocazeros($NumeroLote,6);   // 018 a 023 - Qtd de lotes no arquivo
	$Registro .= colocazeros($totlinha,6);     // 018 a 023 - quantidade de registros do arquivo
	$Registro .= '000000';                     // 024 a 029 - qtd de contas p/ conc. LOTES
	$Registro .= colocaespacos('',205);        // 009 a 017 - USO EXCLUSIVO FEBRABAN/CNAB
	$registro .= "$Registro";
	
	//Aqui voc� coloca o nome do arquivo que ser� gravado
	try{
		$arquivo = "../../../download/Repasse_Franquia_".date("dmY");
		$arq = "Repasse_Franquia_".date("dmY");
		$abrir = fopen($arquivo, "w");
		$escreve = fputs($abrir, $registro);
		fclose($arquivo);
	} catch (Exception $e) {
    	echo 'Erro ao gravar o arquivo: ',  $e->getMessage(), "\n";
	}

	$tamanho = filesize($arquivo);
	header("Content-type: Application/unknown");
	header("Content-length: $tamanho");
	header("Content-Disposition: attachment; filename=$arquivo");
	header("Content-Description: PHP Generated Data");

	$total_geral = number_format($total,2);
	
	echo "
		  Arquivo Gerado com sucesso\r\n 
		  Arquivo:	<a href=https://www.webcontrolempresas.com.br/download/".$arquivo.">".$arq."</a><br> Total Geral : R$ $total_geral";
	exit;

	$tamanho = filesize($arquivo);
	header("Content-type: Application/unknown");
	header("Content-length: $tamanho");
	header("Content-Disposition: attachment; filename=$arquivo");
	header("Content-Description: PHP Generated Data");
	readfile($arquivo);


?>