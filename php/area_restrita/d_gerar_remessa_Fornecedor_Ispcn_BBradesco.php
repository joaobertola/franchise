<?php

    # Autor:   Luciano Mancini
    # Modulo:  Remessa Fornecedor - Banco Bradesco
    # Finalidade: 
    #       Gerar o arquivo de crédito a serem enviados ao Banco Bradesco 
    #       para os cliente CREDIARIO/RECUPERE

    include("../../../validar2.php");

    global $conexao,$arquivo;
    conecex();

    function ver_autorizacao($codloja,$cpfcnpj_doc,$banco,$agencia,$conta){

        global $conexao;

        $sql_ver = "SELECT count(*) qtd FROM cs2.autorizacao_conta
                    WHERE cod_loja = '$codloja' 
                        AND cpf_cnpj = '$cpfcnpj_doc'
                        AND banco = '$banco' 
                        AND agencia = '$agencia' 
                        AND conta = '$conta'
                        AND status = 'A'";
        $qry_ver = mysql_query($sql_ver,$conexao);
        $qtd = mysql_result($qry_ver,0, 'qtd');
        if ( $qtd == 0 ) 
            return 'NEG';
        else
            return 'OK';

    }

    function trocax($saidax){
        $trocaeste = array("(", ")", "'", "Ö", "Ç", "Ü", "Ú", "Ó", "Ô", "Õ", "Ò", "Ã", "Â", "Á", "À", "É", "Í", ";","|");
        $poreste = array(" ", " ", " ", "O", "C", "U", "U", "O", "O", "O", "O", "A", "A", "A", "A", "E", "I", " ","-");
        $saidax = str_replace($trocaeste, $poreste, $saidax);
        $saidax = preg_replace('@[^A-Za-z0-9<>=?":\\n /,.\-_]+@i', "", $saidax);
        return $saidax;
    }
    
    $tarifa_doc = 10;

    // Buscando no numero do LOTE
    $sql_registro = "SELECT controle_registro_bradesco FROM cs2.controle";
    $qry_registro = mysql_query($sql_registro,$conexao);
    $NumeroRegistro = mysql_result($qry_registro,0,'controle_registro_bradesco');

    $sql_registro = "select subdate(now(), interval 60 day) data";
    $qry_registro = mysql_query($sql_registro,$conexao);
    $data_limite = mysql_result($qry_registro,0,'data');
    $data_limite = substr($data_limite,0,10);

    $registros = '';

    $linha_bloqueio_antecipa = "<p></p>
                                    <table width='1000' align='center'>
                                    <tr>
                                        <td colspan='9' style='font-size:12px' align='center'; style='color:#FFFFFF' bgcolor='#FF0000'>NAO AUTORIZADOS - POR TEREM PARCELAS DE ATENCIPACAO EM ATRASO.
                                    </tr>
                                    <tr bgcolor='#CCCCCC' style='font-size:12px' >
                                        <td>ID</td>
                                        <td>Cliente</td>
                                        <td>Titular</td>
                                        <td width='20'>Banco</td>
                                        <td width='50'>Agencia</td>
                                        <td width='100'>Conta</td>
                                        <td width='100'>Valor</td>
                                        <td width='100'>Data Afilia&ccedil;&atilde;o</td>
                                        <td width='100'>Motivo</td>
                                    </tr>
                             ";

    $linha_inad = "<p></p>
                                    <table width='1000' align='center'>
                                    <tr>
                                        <td colspan='9' style='font-size:12px' align='center'; style='color:#FFFFFF'; bgcolor='#FF0000'>CLIENTES COM MENSALIDADES EM ATRASO</td>
                                    </tr>
                                    <tr bgcolor='#CCCCCC' style='font-size:12px' >
                                        <td>ID</td>
                                        <td>Cliente</td>
                                        <td>Titular</td>
                                        <td width='20'>Banco</td>
                                        <td width='50'>Agencia</td>
                                        <td width='100'>Conta</td>
                                        <td width='100'>Valor</td>
                                        <td width='100'>Data Afilia&ccedil;&atilde;o</td>
                                        <td width='100'>Motivo</td>
                                    </tr>
                             ";

    $linha_sup_3000_rec = "<p></p>
                                    <table width='1000' align='center'>
                                    <tr>
                                        <td colspan='9' style='font-size:12px' align='center'; style='color:#FFFFFF' bgcolor='#FF0000'>VALORES SUPERIORES A R$ 2.000,00 - FALTA AUTORIZACAO PARA CONTA DE TERCEIROS.</td>
                                    </tr>
                                    <tr bgcolor='#CCCCCC' style='font-size:12px' >
                                        <td>ID</td>
                                        <td>Cliente</td>
                                        <td>Titular</td>
                                        <td width='20'>Banco</td>
                                        <td width='50'>Agencia</td>
                                        <td width='100'>Conta</td>
                                        <td width='100'>Valor</td>
                                        <td width='100'>Data Afilia&ccedil;&atilde;o</td>
                                        <td width='100'>Motivo</td>
                                    </tr>
                             ";

    $linha_ted = "<p></p>
                                    <table width='1000' align='center'>
                                    <tr>
                                        <td colspan='9' style='font-size:12px' align='center'; style='color:#FFFFFF' bgcolor='#0000FF'>TED - Valores superiores a R$ 5.000,00 ANEXADOS ao Lote.</td>
                                    </tr>
                                    <tr bgcolor='#CCCCCC' style='font-size:12px' >
                                        <td>ID</td>
                                        <td>Cliente</td>
                                        <td>Titular</td>
                                        <td width='20'>Banco</td>
                                        <td width='50'>Agencia</td>
                                        <td width='100'>Conta</td>
                                        <td width='100'>Valor</td>
                                        <td width='100'>Data Afilia&ccedil;&atilde;o</td>
                                        <td width='100'>Motivo</td>
                                    </tr>
                             ";

    $linha_bloqueio_lista = "<p></p>
                                    <table width='1000' align='center'>
                                    <tr>
                                        <td colspan='9' style='font-size:12px' align='center'; style='color:#FFFFFF'; bgcolor='#FF0000'>CLIENTES COM SALDO DEVEDOR - PGTO DUPLICADO </td>
                                    </tr>
                                    <tr bgcolor='#CCCCCC' style='font-size:12px' >
                                        <td>ID</td>
                                        <td>Cliente</td>
                                        <td>Titular</td>
                                        <td width='20'>Banco</td>
                                        <td width='50'>Agencia</td>
                                        <td width='100'>Conta</td>
                                        <td width='100'>Valor Repassar</td>
                                        <td width='100'>Valor a Reter</td>
                                        <td width='100'>Motivo</td>
                                    </tr>
                             ";

    $blq_repasse_inadimplencia = '';
    $NumeroArquivo = 1;

    $tot_pagar = 0;
    $totlinha = 0;
    $qtd_registro = 0;

    $cnpj = "12244595";
    $filial = "0001";
    $dv = "00";
    $empresa_pagadora = "ISPCN ADMINISTRACAO DE TITULOS DE COBRAN";

    // GERAR REGISTRO HEADER DO ARQUIVO
    $Registro  = '0';                                 // 001 a 001 - Obrigat�rio - fixo "zero"(0)
    $Registro .= colocazeros('139036',8);                  // 002 a 009 - Identifica��o da Empresa no Banco Bradesco
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

    // tpconta = 1  => CONTA CORRENTE
    // tpconta = 2  => CONTA POUPAN�A

    # Selecionando do Clientes

    $sql = "SELECT  DISTINCT(a.codloja) codloja, mid(b.razaosoc,1,30) razaosoc, b.banco_cliente, b.agencia_cliente, 
                    b.conta_cliente, cpfcnpj_doc, upper(mid(CONVERT(b.nome_doc USING utf8),1,30)) AS nome_doc, 
                    '$tarifa_doc' as tarifa_doc, b.tpconta, b.insc, b.cpfsocio1, b.cpfsocio2,
                    date_format(b.dt_cad,'%d/%m/%Y') as dt_cad
            FROM cs2.contacorrente_recebafacil a
            INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
            INNER JOIN cs2.logon c ON b.codloja = c.codloja  
            WHERE b.sitcli = 0 
                    AND c.sitlog = 0 
                    AND b.banco_cliente = 237 
                    AND ( ( b.pendencia_contratual = 0) or ( b.pendencia_contratual = 1 and b.dt_cad >= '$data_limite') )
            GROUP BY a.codloja
            ORDER BY banco_cliente,tpconta,nome_doc";
    $qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
    $qtd = mysql_num_rows($qr_sql);
    if ( $qtd > 0 ){
        while($registro = mysql_fetch_array($qr_sql)){
            $codloja = $registro['codloja'];
            $razaosoc = $registro['razaosoc'];
            $banco_cliente = $registro['banco_cliente'];
            $agencia_cliente = $registro['agencia_cliente'];
            $conta_cliente = $registro['conta_cliente'];
            $cpfcnpj_doc = $registro['cpfcnpj_doc'];
            $nome_doc = $registro['nome_doc'];
            $nome_doc = trim($nome_doc);
            $nome_doc = trocax($nome_doc);
            $tarifa = $registro['tarifa_doc'];
            $tpconta = $registro['tpconta'];
            $dt_cad = $registro['dt_cad'];
            $insc = $registro['insc'];
            $cpfsocio1 = $registro['cpfsocio1'];
            $cpfsocio2 = $registro['cpfsocio2'];

            $id   = str_pad($codloja,6,' ',STR_PAD_RIGHT);
            $emp  = str_pad($razaosoc,30,' ',STR_PAD_RIGHT);
            $tit  = str_pad($nome_doc,30,' ',STR_PAD_RIGHT);
            $bco  = str_pad($banco_cliente,4,' ',STR_PAD_RIGHT);
            $age  = str_pad($agencia_cliente,5,' ',STR_PAD_RIGHT);
            $cta  = str_pad($conta_cliente,20,' ',STR_PAD_RIGHT);                   

            # VERIFICANDO SE O CLIENTE CONSTA NA LISTA DE SALDO DEVEDOR ENVIO DUPLICADO
            $sql2 = 'SELECT valor FROM cs2.acerto_saldoerrado 
                     WHERE codloja='.$codloja.' AND pago = 0 ';
            $xqr_sql    = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
            $qtd = mysql_num_rows($xqr_sql);

            if ( $qtd > 0 ){
                $xvlr = mysql_result($xqr_sql,0,'valor');
                $xvlr = ( $xvlr * 1) / 100;
                $xvlr = number_format($xvlr,2,',', '.');
                $linha_bloqueio_lista .= "  <tr style='font-size:12px'>
                                                <td>$id</td>
                                                <td>$emp</td>
                                                <td>$tit</td>
                                                <td>$bco</td>
                                                <td>$age</td>
                                                <td>$cta</td>
                                                <td>$vlr</td>
                                                <td>$xvlr</td>
                                                <td>CC Itau - Pgto Dup</td>
                                            </tr>";
            }else{

                # verificando o logon do cliente
                $sql2 = 'SELECT MID(logon,1,LOCATE(\'S\',logon)-1) logon FROM cs2.logon WHERE codloja='.$codloja.' LIMIT 1';
                $xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
                $array_sql2 = mysql_fetch_array($xqr_sql);
                $logon = $array_sql2["logon"];
                # verificando o saldo na conta corrente do cliente
                $sql2 = 'SELECT saldo FROM cs2.contacorrente_recebafacil WHERE codloja='.$codloja.' ORDER BY id DESC LIMIT 1';
                $xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
                $array_sql2=mysql_fetch_array($xqr_sql);
                $saldo = $array_sql2["saldo"];
                $nsaldo = $array_sql2["saldo"];
                if ( empty($saldo) ) $saldo = 0;
                $saldo -= $tarifa;
                $vlr  = str_pad($saldo,20,' ',STR_PAD_RIGHT);
                $vlr  = number_format($vlr,2,',', '.');

                if ( $saldo > 0 ){
                    $autoriza = 'S';
                    $valor_ant = verifica_emprestimo($codloja);
                    # j� que est� inadimplente ZERO o saldo para n�o enviar
                    if ( $valor_ant > 0 ){
                        $linha_bloqueio_antecipa .= "<tr style='font-size:12px'>
                                                        <td>$id</td>
                                                        <td>$emp</td>
                                                        <td>$tit</td>
                                                        <td>$bco</td>
                                                        <td>$age</td>
                                                        <td>$cta</td>
                                                        <td>$vlr</td>
                                                        <td>$dt_cad</td>
                                                        <td>Atraso Antecipacao</td>
                                                    </tr>";
                        $autoriza = 'N';
                        $saldo = 0;             
                    }
                    if ( $saldo > 0 ){
                        $xdata = date('Y-m-d');
                        # verificando se o cliente possui titulo em aberto, se tiver n�o gera repasse para ele.
                        $sql2 = "SELECT count(*) qtd FROM cs2.titulos
                                 WHERE codloja='$codloja' and datapg is null and vencimento < '$xdata'";
                        $xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
                        $array_sql2=mysql_fetch_array($xqr_sql);
                        $xqtd = $array_sql2["qtd"];
                        if ( $xqtd > 0 ){
                            # Cliente INADIMPLENTE com as mensalidades
                            $saldo = number_format($saldo,2,',','.');
                            $linha_inad .= "<tr style='font-size:12px'>
                                                <td>$id</td>
                                                <td>$emp</td>
                                                <td>$tit</td>
                                                <td>$bco</td>
                                                <td>$age</td>
                                                <td>$cta</td>
                                                <td>$saldo</td>
                                                <td>$dt_cad</td>
                                                <td>Mens. Atrasada</td>
                                            </tr>";
                        }else{
                            # Cliente em dia com suas mensalidades
                            if ( $saldo > 2000 ){

                                if ( ($cpfcnpj_doc != $insc) && ($cpfcnpj_doc != $cpfsocio1) && ($cpfcnpj_doc != $cpfsocio2) ){
                                    $retorno = ver_autorizacao($codloja,$cpfcnpj_doc,$banco_cliente,$agencia_cliente,$conta_cliente);
                                    if ( $retorno == "NEG" ){
                                        $linha_sup_3000_rec .= "<tr style='font-size:12px'>
                                                                    <td>$id</td>
                                                                    <td>$emp</td>
                                                                    <td>$tit</td>
                                                                    <td>$bco</td>
                                                                    <td>$age</td>
                                                                    <td>$cta</td>
                                                                    <td>$vlr</td>
                                                                    <td>$dt_cad</td>
                                                                    <td>Conta Nao Autorizada</td>
                                                                </tr>";
                                        $autoriza = 'N';
                                    }else{
                                        // autorizado, a conta est� cadastrada para receber
                                        $autoriza = 'S';
                                    }
                                }
                            }

                            if ( $saldo >= 5000 ){
                                if ( $autoriza == 'S' ){
                                    $linha_ted .= "<tr style='font-size:12px'>
                                                        <td>$id</td>
                                                        <td>$emp</td>
                                                        <td>$tit</td>
                                                        <td>$bco</td>
                                                        <td>$age</td>
                                                        <td>$cta</td>
                                                        <td>$vlr</td>
                                                        <td>$dt_cad</td>
                                                        <td>Anexado ao Lote</td>
                                                    </tr>";
                                }
                            }

                            if ( $autoriza == 'S' ){
                                $totlinha++;
                                $NumeroRegistro++;
                                $qtd_registro++;
                                $CODLOJA = '99999'.$codloja;
                                // REGISTRO DETALHES DO LOTE
                                $Registro = '1';                               // 001 a 001 - Identifica��o
                                $tam = strlen($cpfcnpj_doc);
                                if ($tam==11){
                                    $Registro .= '1';                          // 002 a 002 - 1 CPF 2 CGC
                                    $base = substr($cpfcnpj_doc,0,9);
                                    $filial = '';
                                    $dv = substr($cpfcnpj_doc,9,2); 
                                }else{
                                    $Registro .= '2';
                                    $base = substr($cpfcnpj_doc,0,8);
                                    $filial = substr($cpfcnpj_doc,8,4);
                                    $dv = substr($cpfcnpj_doc,12,2);
                                }
                                $Registro .= colocazeros($base,9);             // 003 a 011 - CPF/CNPJ - Base Fornecedor
                                $Registro .= colocazeros($filial,4);           // 012 a 015 - CPF/CNPJ - FILIAL
                                $Registro .= colocazeros($dv,2);               // 016 a 017 - CPF/CNPJ - CONTROLE
                                $nome_doc = substr($nome_doc,0,30);
                                $Registro .= colocaespacosdir($nome_doc,30);   // 018 a 047 - Nome do Fornecedor
                                $Registro .= colocaespacosdir($end_doc,40);    // 048 a 087 - Endereco do Fornecedor
                                $Registro .= colocazeros($cep_doc,5);          // 088 a 092 - CEP do Fornecedor
                                $Registro .= colocazeros($cep_doc_comp,3);     // 093 a 095 - CEP do Fornecedor - complemento
                                $Registro .= colocazeros($banco_cliente,3);    // 096 a 098 - C�digo do Banco do Favorecido
                                if ( strlen($agencia_cliente) <= 4 ) 
                                    $agencia = colocazeros($agencia_cliente,5);
                                else 
                                    $agencia = colocazeros(substr($agencia_cliente,0,strlen($agencia_cliente)-1),5);
                                $Registro .= $agencia;                         // 099 a 103 - C�d Agen do Favorecido
                                if ( $banco_cliente == '237' ){
                                    $dv_agencia = mod11($agencia);
                                    $Registro .= $dv_agencia;                 // 104 a 104 - C�d Agen do Favorecido
                                }else{
                                    $Registro .= '0';
                                }
                                $Registro .= colocazeros(substr($conta_cliente,0,strlen($conta_cliente)-1),13); // 105 a 117 - C�digo da Conta Corrente do Favorecido
                                $Registro .= colocaespacosdir(substr($conta_cliente,strlen($conta_cliente)-1,1),2); // 118 a 119 - Digito da Conta Corrente do Favorecido
                                $Registro .= colocazeros($NumeroRegistro,16);  // 120 a 135 - Numero do Pagamento
                                $carteira = '000';
                                $Registro .= colocaespacos($carteira,3);       // 136 a 138 - Carteira  ????????????????
                                $Registro .= colocazeros('',12);                // 139 a 150 - Nosso N�mero
                                $Registro .= colocazeros('',15);                // 151 a 165 - Seu N�mero
                                $data = date("Ymd");
                                $Registro .= $data;                             // 166 a 173 - Data de vencimento
                                $Registro .= '00000000';                        // 174 a 181 - Data de Emissao
                                $Registro .= '00000000';                        // 182 a 189 - Data Limite para Desconto
                                $Registro .= '0';                               // 190 a 190 - Fixo
                                $Registro .= '0000';                            // 191 a 194 - Fator de Vencimento
                                $Registro .= colocazeros('0',10);               // 195 a 204 - Valor do Documento
                                $valor = $saldo * 100;
                                $tot_pagar += $valor;
                                $valor = str_replace(',','',$valor);
                                $valor = str_replace('.','',$valor);
                                $Registro .= colocazeros($valor,15);            // 205 a 219 - Valor do Pagamento
                                $Registro .= colocazeros('0',15);               // 220 a 234 - Valor do Desconto
                                $Registro .= colocazeros('0',15);               // 235 a 249 - Valor do Acr�scimo
                                $Registro .= '05';                              // 250 a 251 - Tipo de Documento
                                $Registro .= colocazeros('0',10);               // 252 a 261 - N�mero da Nota Fiscal/Fatura
                                $Registro .= '  ';                              // 262 a 263 - S�rie do Documento
                                if ( $banco_cliente == '237' )
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
                                if ( $banco_cliente == '237' )
                                    $Registro .= colocaespacos('',40);          // 374 a 413 - Informa��es complementares
                                else{
                                    $Registro .= 'C';              // Informa��es complementares  C - Titularidade Diferente  D - Mesma Titularidade
                                    $Registro .= colocazeros('0',6); // NUMERO DO DC COMPE/TED - esse campo devera ser einformado zerado
                                    if ( $tpconta == 1 ) $Registro .= '01'; // DOC-TED para conta corrente
                                    else $Registro .= '11'; // DOC-TED para poupanca
                                    $Registro .= colocaespacos('',31);
                                }
                                $Registro .= colocazeros('0',2);                // 414 a 415  - Codigo de area na empresa
                                $Registro .= colocaespacos($CODLOJA,35);        // 416 a 450 - Uso da empresa
                                $Registro .= colocaespacos('',22);              // 451 a 472 - Reserva
                                $Registro .= colocazeros('0',5);                // 473 a 477 - Codigo de lancamento
                                $Registro .= colocaespacos('',1);               // 478 a 478 - Reserva
                                if ( $tpconta == 1 ) $tipo_conta = '1'; // CONTA CORRENTE
                                else $tipo_conta = '2'; // CONTA POUPANCA
                                $Registro .= colocazeros($tipo_conta,1);        // 479 a 479 - Tipo de conta do fornecedor
                                $Registro .= colocazeros('32549',7);            // 480 a 486 - Conta complementar
                                $Registro .= colocaespacos('',8);               // 487 a 494 - Reserva
                                $Registro .= colocazeros($totlinha,6);          // 495 a 500 - Numero Sequencial
                                $registros .= "$Registro\n";
                                $total += $nsaldo;
                            }
                        }
                    }
                }
            }
        }
    }
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
        $arquivo = "../../../download/PagFor_".date("dmY");
        $arq = "PagFor_".date("dmY");
        $abrir = fopen($arquivo, "w");
        $escreve = fputs($abrir, $registros);
        fclose($abrir);
    } catch (Exception $e) {
        echo 'Erro ao gravar o arquivo: ',  $e->getMessage(), "\n";
    }

    $xtotal_geral = substr($tot_pagar,0,strlen($tot_pagar)-2).'.'.substr($tot_pagar,strlen($tot_pagar)-2,2);

    $total_geral = number_format($xtotal_geral,2);

    echo "
        Arquivo Gerado com sucesso\r\n 
        Arquivo: <a href=https://www.webcontrolempresas.com.br/download/".$arq.">".$arq."</a><br> Total Geral : R$ $total_geral";

    # Gravando o sequencial de registro de pagamento 
    $sql_registro = "UPDATE cs2.controle set controle_registro_bradesco = $NumeroRegistro";
    $qry_registro = mysql_query($sql_registro,$conexao);

    # Mostrando os TED

    $linha_bloqueio_lista .= "</table>";    
    echo $linha_bloqueio_lista;

    $linha_ted .= "</table>";   
    echo $linha_ted;

    $linha_sup_3000_rec .= "</table>";
    echo $linha_sup_3000_rec;

    $linha_bloqueio_antecipa .= "</table>";
    echo $linha_bloqueio_antecipa;

    $linha_inad .= "</table>";
    echo $linha_inad;
    
?>