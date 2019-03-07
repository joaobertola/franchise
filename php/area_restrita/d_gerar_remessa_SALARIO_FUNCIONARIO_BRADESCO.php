<?php

    # Autor:   Luciano Mancini
    # M�dulo:  Remessa Funcionario - Banco Bradesco
    # Finalidade: 
    #		Gerar o arquivo de VT+VR

    include("../../../validar2.php");

    global $conexao,$arquivo;
    conecex();

    // Buscando no numero do LOTE
    $sql_registro = "SELECT controle_registro_bradesco FROM cs2.controle";
    $qry_registro = mysql_query($sql_registro,$conexao);
    $NumeroRegistro = mysql_result($qry_registro,0,'controle_registro_bradesco');

    $sql_registro = "select subdate(now(), interval 60 day) data";
    $qry_registro = mysql_query($sql_registro,$conexao);
    $data_limite = mysql_result($qry_registro,0,'data');
    $data_limite = substr($data_limite,0,10);

    $registros = '';

    $linha_ted = "  <p></p>
                    <table width='1000' align='center'>
                    <tr bgcolor='#CCCCCC' style='font-size:12px' >
                            <td>ID</td>
                            <td>Funcionario</td>
                            <td width='20'>Banco</td>
                            <td width='50'>Agencia</td>
                            <td width='100'>Conta</td>
                            <td width='100'>Valor</td>
                    </tr>
             ";
        
    $blq_repasse_inadimplencia = '';
    $NumeroArquivo = 1;

    $tot_pagar = 0;
    $totlinha = 0;
    $qtd_registro = 0;

    $cnpj = "08745918";
    $filial = "0001";
    $dv = "71";
    $empresa_pagadora = "WC SISTEMAS E EQUIPAMENTOS DE INFORMATIC";

    $sql = "SELECT id, endereco, nome, nr_banco, agencia, tp_conta, conta, cpf, (salario-adiantamento) as salario, vt
            FROM cs2.funcionario
            WHERE ativo = 'S' AND salario > 0 and id_empregador = '1'
            ORDER BY nome";
    $qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
    $qtd = mysql_num_rows($qr_sql);
    if ( $qtd > 0 ){
            
        // GERAR REGISTRO HEADER DO ARQUIVO
        $Registro  = '0';                                 // 001 a 001 - Obrigat�rio - fixo "zero"(0)
        $Registro .= colocazeros('161305',8);             // 002 a 009 - Identifica��o da Empresa no Banco Bradesco
        $Registro .= '2';                                 // 010 a 010 - Tipo de incri��o da Empresa Pagadora
        $Registro .= colocazeros($cnpj,9);                // 011 a 019 - Numero CNPJ
        $Registro .= colocazeros($filial,4);              // 020 a 023 - Filial
        $Registro .= colocazeros($dv,2);                  // 024 a 025 - DV
        $Registro .= colocaespacos($empresa_pagadora,40); // 026 a 065 - Nome da empresa Pagadora
        $Registro .= '20';                                // 066 a 067 - Tipo de Servi�o
        $Registro .= '1';                                 // 068 a 068 - Codigo de Origem do arquivo  1 Cliente  2 Banco
        $Registro .= colocazeros('1',5);                  // 069 a 073 - Numero da remessa - Sequencial Crescente
        $Registro .= '00000';                             // 074 a 078 - Numero do retorno - Desconsiderar
        $data = date('Ymd');
        $Registro .= $data;                               // 079 a 086 - Data da grava��o do arquivo
        $hora = date("His");
        $Registro .= $hora;                               // 087 a 092 - Hora da grava��o do arquivo
        $Registro .= colocaespacos('',5);                 // 093 a 097 - Densidade de grava��o do arquivo
        $Registro .= colocaespacos('',3);                 // 098 a 100 - Unidade de densidade de grava��o do arquivo
        $Registro .= colocaespacos('',5);                 // 101 a 105 - Identifica��o Modulo Micro
        $Registro .= colocaespacos('',1);                 // 106 a 106 - Tipo de Processamento - desconsiderado
        $Registro .= colocaespacos('',74);                // 107 a 180 - Uso reservado da empresa
        $Registro .= colocaespacos('',80);                // 181 a 260 - brancos
        $Registro .= colocaespacos('',217);               // 261 a 477 - brancos
        $Registro .= colocazeros('1',9);                  // 478 a 486 - N�mero da Lista de D�bito
        $Registro .= colocaespacos('',8);                 // 487 a 494 - brancos
        $totlinha++;
        $Registro .= colocazeros($totlinha,6);            // 495 a 500 - N�mero Sequencial do Registro

        $registros .= "$Registro\n";                      

        while($registro = mysql_fetch_array($qr_sql)){
            
            $id        = $registro['id'];
            $nome      = $registro['nome'];
            $endereco  = $registro['endereco'];
            $banco     = $registro['nr_banco'];
            $agencia   = $registro['agencia'];
            $agencia   = str_replace('-','',$agencia);
            
            $tpconta   = $registro['tp_conta'];
            $conta     = $registro['conta'];
            
            $conta     = str_replace('-','',$conta);
            $cpfcnpj   = $registro['cpf'];
            $valor_sal = $registro['salario'];
            $tot_pagar += $valor_sal;

            $linha_ted .= "
                    <tr>
                        <td>$id</td>
                        <td>$nome</td>
                        <td>$banco</td>
                        <td>$agencia</td>
                        <td>$conta</td>
                        <td>$valor_sal</td>
                    </tr>
                    ";

            $totlinha++;
            $NumeroRegistro++;
            $qtd_registro++;

            // REGISTRO DETALHES DO LOTE
            $Registro = '1';                               // 001 a 001 - Identifica��o
            $tam = strlen($cpfcnpj);
            if ($tam==11){
                $Registro .= '1';                          // 002 a 002 - 1 CPF 2 CGC
                $base = substr($cpfcnpj,0,9);
                $filial = '';
                $dv = substr($cpfcnpj,9,2);	
            }else{
                $Registro .= '2';
                $base = substr($cpfcnpj,0,8);
                $filial = substr($cpfcnpj,8,4);
                $dv = substr($cpfcnpj,12,2);
            }
            $Registro .= colocazeros($base,9);             // 003 a 011 - CPF/CNPJ - Base Fornecedor
            $Registro .= colocazeros($filial,4);           // 012 a 015 - CPF/CNPJ - FILIAL
            $Registro .= colocazeros($dv,2);               // 016 a 017 - CPF/CNPJ - CONTROLE
            $nome_doc = substr($nome,0,30);
            $Registro .= colocaespacosdir(substr($nome,0,30),30);   // 018 a 047 - Nome do Fornecedor
            $Registro .= colocaespacosdir(substr($endereco,0,40),40);  // 048 a 087 - Endereco do Fornecedor
            $Registro .= colocazeros($cep_doc,5);          // 088 a 092 - CEP do Fornecedor
            $Registro .= colocazeros($cep_doc_comp,3);     // 093 a 095 - CEP do Fornecedor - complemento
            $Registro .= colocazeros($banco,3);    // 096 a 098 - C�digo do Banco do Favorecido
            if ( strlen($agencia) <= 4 ) 
                $agencia = colocazeros($agencia,5);
            else 
                $agencia = colocazeros(substr($agencia,0,strlen($agencia)-1),5);
            $Registro .= $agencia;                         // 099 a 103 - C�d Agen do Favorecido
            if ( $banco == '237' ){
                $dv_agencia = mod11($agencia);
                $Registro .= $dv_agencia;                 // 104 a 104 - C�d Agen do Favorecido
            }else{
                $Registro .= '0';
            }
            $Registro .= colocazeros(substr($conta,0,strlen($conta)-1),13); // 105 a 117 - C�digo da Conta Corrente do Favorecido
            $Registro .= colocaespacosdir(substr($conta,strlen($conta)-1,1),2); // 118 a 119 - Digito da Conta Corrente do Favorecido
            $Registro .= colocazeros($NumeroRegistro,16);  // 120 a 135 - Numero do Pagamento
            $Registro .= colocaespacos('000',3);       // 136 a 138 - Carteira  ????????????????
            $Registro .= colocazeros('',12);                // 139 a 150 - Nosso N�mero
            $Registro .= colocazeros('',15);                // 151 a 165 - Seu N�mero
            $data = date("Ymd");
            $Registro .= date("Ymd");                             // 166 a 173 - Data de vencimento
            $Registro .= '00000000';                        // 174 a 181 - Data de Emissao
            $Registro .= '00000000';                        // 182 a 189 - Data Limite para Desconto
            $Registro .= '0';                               // 190 a 190 - Fixo
            $Registro .= '0000';                            // 191 a 194 - Fator de Vencimento
            $Registro .= colocazeros('0',10);               // 195 a 204 - Valor do Documento
            $valor = $valor_sal * 100;

            $valor = str_replace(',','',$valor);
            $valor = str_replace('.','',$valor);
            $Registro .= colocazeros($valor,15);            // 205 a 219 - Valor do Pagamento
            $Registro .= colocazeros('0',15);               // 220 a 234 - Valor do Desconto
            $Registro .= colocazeros('0',15);               // 235 a 249 - Valor do Acr�scimo
            $Registro .= '05';                              // 250 a 251 - Tipo de Documento
            $Registro .= colocazeros('0',10);               // 252 a 261 - N�mero da Nota Fiscal/Fatura
            $Registro .= '  ';                              // 262 a 263 - S�rie do Documento
            
            if ( $banco == '237' )
                $modalidade = '01'; // credito em conta
            else{
                if ( $saldo < 5000 ) $modalidade = '03'; // DOC
                else $modalidade = '08'; // TED	
            }

            $Registro .= colocazeros($modalidade,2);        // 264 a 265 - Modalidade de Pagamento
            // data atual mais 1 dia
            //$data = gmdate("Ymd", time()+(3600*27));
            $data = date("Ymd");
            $Registro .= $data;                             // 266 a 273 - Data para efetiva��o do Pagamento
            $Registro .= '   ';                             // 274 a 276 - Moeda (CODIGO CNAB)
            $Registro .= '01';                              // 277 a 278 - Situa��o do Agendamento
            $Registro .= '  ';                              // 279 a 280 - Informa��o de Retorno 1
            $Registro .= '  ';                              // 281 a 282 - Informa��o de Retorno 2
            $Registro .= '  ';                              // 283 a 284 - Informa��o de Retorno 3
            $Registro .= '  ';                              // 285 a 286 - Informa��o de Retorno 4
            $Registro .= '  ';                              // 287 a 288 - Informa��o de Retorno 5
            $Registro .= '0';                               // 289 a 289 - Tipo de Movimento  0 - Inclusao 5 - Alteracao - 9 Exclusao
            $Registro .= '00';                              // 290 a 291 - Codigo do Movimento
            $Registro .= '    ';                            // 292 a 295 - Horario para consulta saldo
            $Registro .= colocaespacos('',15);              // 296 a 310 - Saldo disponivel no momento da consulta
            $Registro .= colocaespacos('',15);              // 311 a 325 - Valor da taxa pre funding
            $Registro .= colocaespacos('',6);               // 326 a 331 - Brancos
            $Registro .= colocaespacos('',40);              // 332 a 371 - Sacador/Avalista
            $Registro .= colocaespacos('',1);               // 372 a 372 - Reserva
            $Registro .= colocaespacos('',1);               // 373 a 373 - Nivel da Informacao de Retorno
            if ( $banco== '237' ){
                $Registro .= colocaespacos('',40);          // 374 a 413 - Informa��es complementares
            }else{
                $Registro .= 'C';                           // Informa��es complementares  C - Titularidade Diferente  D - Mesma Titularidade
                $Registro .= colocazeros('0',6);            // NUMERO DO DC COMPE/TED - esse campo devera ser einformado zerado
                if ( $tpconta == 'C' ) $Registro .= '01';   // DOC-TED para conta corrente
                else $Registro .= '11';                     // DOC-TED para poupanca
                $Registro .= colocaespacos('',31);
            }
            $Registro .= colocazeros('0',2);                // 414 a 415  - Codigo de area na empresa
            $Registro .= colocaespacos($id,35);             // 416 a 450 - Uso da empresa
            $Registro .= colocaespacos('',22);              // 451 a 472 - Reserva
            $Registro .= colocazeros('0',5);                // 473 a 477 - Codigo de lancamento
            $Registro .= colocaespacos('',1);               // 478 a 478 - Reserva

            if ( $tpconta == 'C' ) $tipo_conta = '1'; // CONTA CORRENTE
            else $tipo_conta = '2'; // CONTA POUPANCA

            $Registro .= colocazeros($tipo_conta,1);        // 479 a 479 - Tipo de conta do fornecedor
            $Registro .= colocazeros('24037',7);            // 480 a 486 - Conta complementar
            $Registro .= colocaespacos('',8);               // 487 a 494 - Reserva
            $Registro .= colocazeros($totlinha,6);          // 495 a 500 - Numero Sequencial
            $registros .= "$Registro\n";

        }

        $tot_pagar = $tot_pagar*100;
        
        # REGISTRO TRAILLER DO LOTE 0003
        $Registro = '9';                                      // 001 a 001 - Identificacao do Registro
        $Registro .= colocazeros($qtd_registro+2,6);            // 002 a 007 - Qtd de registros

        $valor = str_replace('.','',$tot_pagar);
        $Registro .= colocazeros($valor,17);                   // 008 a 024 - Somat�ria dos valores DEB/CRED

        $Registro .= colocaespacos('',470);                    // 025 a 494 - Reserva
        $totlinha++;
        $Registro .= colocazeros($totlinha,6);                 // 495 a 500 - Numero Sequencial

        $registros .= "$Registro\n";

        try{
            $arquivo = "../../../download/SalBra_".date("dmY");
            $arq = "SalBra_".date("dmY");
            $abrir = fopen($arquivo, "w");
            $escreve = fputs($abrir, $registros);
            fclose($arquivo);
        } catch (Exception $e) {
            echo 'Erro ao gravar o arquivo: ',  $e->getMessage(), "\n";
        }

        $xtotal_geral = substr($tot_pagar,0,strlen($tot_pagar)-2).'.'.substr($tot_pagar,strlen($tot_pagar)-2,2);

        $total_geral = number_format($xtotal_geral,2);

        echo "
            <center>
            Arquivo Gerado com sucesso\r\n 
            Arquivo: <a href=https://www.webcontrolempresas.com.br/download/$arq>$arq</a><br> Total Geral : R$ $total_geral
            </center>";

        # Gravando o sequencial de registro de pagamento 
        $sql_registro = "UPDATE cs2.controle set controle_registro_bradesco = $NumeroRegistro";
        $qry_registro = mysql_query($sql_registro,$conexao);

        # Mostrando os TED
        $linha_ted .= "</table>";	
        echo $linha_ted;
        
    }else{
        
        echo "<center>Nenhum Salario a Pagar..</center><br><hr><br>";
    }

    include( "d_gerar_remessa_VTVR_FUNCIONARIO_BRADESCO.php" );
    
?>