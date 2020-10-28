<?php

    # Autor     :  Luciano Mancini
    # M�dulo    :  Remessa Fornecedor - Banco Bradesco
    # Finalidade:  Gerar o arquivo de credito 
    #              a serem enviados ao Banco ITAU para os cliente CREDIARIO/RECUPERE

    include("../../../validar2.php");

    global $conexao,$arquivo;
    conecex();

    function ver_autorizacao($codloja,$cpfcnpj_doc,$banco,$agencia,$conta){
        
        global $conexao;

        $banco = str_pad($banco,3,0,STR_PAD_LEFT);
        $agencia = str_pad($agencia,4,0,STR_PAD_LEFT);

        $sql_ver = "SELECT count(*) qtd FROM cs2.autorizacao_conta
                    WHERE   cod_loja     = '$codloja' 
                            AND cpf_cnpj = '$cpfcnpj_doc'
                            AND banco    = '$banco' 
                            AND agencia  = '$agencia' 
                            AND conta    = '$conta'
                            AND status   = 'A'";
        $qry_ver = mysql_query($sql_ver,$conexao);
        $qtd = mysql_result($qry_ver,0, 'qtd');
        if ( $qtd == 0 ) 
            return 'NEG';
        else
            return 'OK';
    }

    function troca($saidax){
        $trocaeste = array("(", ")", "'", "Ö", "Ç", "Ü", "Ú", "Ó", "Ô", "Õ", "Ò", "Ã", "Â", "Á", "À", "É", "Í", ";","|");
        $poreste = array(" ", " ", " ", "O", "C", "U", "U", "O", "O", "O", "O", "A", "A", "A", "A", "E", "I", " ","-");
        $saidax = str_replace($trocaeste, $poreste, $saidax);
        $saidax = preg_replace('@[^A-Za-z0-9<>=?":\\n /,.\-_]+@i', "", $saidax);
        return $saidax;
    }
    
    $sql_registro = "select subdate(now(), interval 60 day) data;";
    $qry_registro = mysql_query($sql_registro,$conexao);
    $data_limite = mysql_result($qry_registro,0,'data');
    $data_limite = substr($data_limite,0,10);
    $soma_inad = 0;
    $tarifa_doc = 10;
        
    $registros = '';

    $linha_sup_3000_rec = "<p></p>
                            <table width='1000' align='center'>
                            <tr>
                                <td colspan='10' style='font-size:12px' align='center'; style='color:#FFFFFF' bgcolor='#FF0000'>VALORES SUPERIORES A R$ 2.000,00 - FALTA AUTORIZACAO PARA CONTA DE TERCEIROS.
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
                                <td width='10'>Frq</td>
                            </tr>
                            ";

    $linha_bloqueio_antecipa = "<p></p>
                                <table width='1000' align='center'>
                                <tr>
                                    <td colspan='10' style='font-size:12px' align='center'; style='color:#FFFFFF' bgcolor='#FF0000'>NAO AUTORIZADOS - POR TEREM PARCELAS DE ATENCIPACAO EM ATRASO.
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
                                    <td width='10'>Frq</td>
                                </tr>
                                ";

    $linha_ted = "<p></p>
                    <table width='1000' align='center'>
                        <tr>
                            <td colspan='10' style='font-size:12px' align='center'; style='color:#FFFFFF'; bgcolor='#0000FF'>TED - Valores superiores a R$ 5.000,00.
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
                            <td width='10'>Frq</td>
                        </tr>
                    ";
    
    $linha_inad = "<p></p>
                    <table width='1000' align='center'>
                    <tr>
                        <td colspan='10' style='font-size:12px' align='center'; style='color:#FFFFFF'; bgcolor='#FF0000'>CLIENTES COM MENSALIDADES EM ATRASO</td>
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
                        <td width='10'>Frq</td>
                    </tr>
                    ";

    $linha_tex = "<p></p>
                                    <table width='1000' align='center'>
                                    <tr>
                                        <td colspan='11' style='font-size:12px' align='center'; style='color:#FFFFFF'; bgcolor='#FF0000'>CLIENTES FRANQUIA C2</td>
                                    </tr>
                                    <tr bgcolor='#CCCCCC' style='font-size:12px' >
                                        <td>Codigo</td>
                                        <td>Cliente</td>
                                        <td>Titular</td>
                                        <td>Doc Titular</td>
                                        <td width='20'>Banco</td>
                                        <td width='50'>Agencia</td>
                                        <td width='100'>Conta</td>
                                        <td width='100'>Valor</td>
                                        <td width='100'>Data Afilia&ccedil;&atilde;o</td>
                                        <td width='100'>Motivo</td>
                                        <td width='10'>Frq</td>
                                    </tr>
                             ";
    
    $linha_bloqueio_lista = "<p></p>
                            <table width='1000' align='center'>
                                <tr>
                                    <td colspan='10' style='font-size:12px' align='center'; style='color:#FFFFFF'; bgcolor='#FF0000'>CLIENTES COM SALDO DEVEDOR - PGTO DUPLICADO </td>
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
                                    <td width='10'>Frq</td>
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
    $Registro .= '12244595000100';                    // 019 a 032 - N�mero de inscri��o do cedente
    $Registro .=  colocaespacos('',20);               // 033 a 052 - BRANCOS
    $Registro .= '08616';                             // 053 a 057 - C�digo da ag�ncia do cedente
    $Registro .= ' ';                                 // 058 a 058 - BRANCOS
    $Registro .= colocazeros('19914',12);             // 059 a 070 - N�mero da conta do cedente
    $Registro .= ' ';                                 // 071 a 071 - BRANCOS
    $Registro .= '0';                                 // 072 a 072 - D�gito verificador da ag�ncia / conta
    $Registro .= colocaespacosdir('ISPCN ADMINISTRACAO DE TITULOS',30);  // 073 a 102 - Nome do cedente
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
    
    # INICIO - CONTA CORRENTE BANCO ITAU
    
    // HEADER DO LOTE
    $Registro  = '341';                               // 001 a 003 - C�digo do Banco
    $Registro .= colocazeros($NumeroLote,4);          // 004 a 007 - Codigo do Lote
    $Registro .= '1';                                 // 008 a 008 - Tipo de Registro
    $Registro .= 'C';                                 // 009 a 009 - Tipo de Opera��o
    $tp_pagto = '20';
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
    $Registro .= '12244595000100';                    // 019 a 032 - Empresa Numero Inscri��o - Debitada

    $Registro .= $tp_pagto.$forma_pgto;               // 033 a 036 - Identificacao do lan�amento
    $Registro .= colocaespacos('',16);                // 037 a 052 - Em branco

    $Registro .= '08616';                             // 053 a 057 - Agencia debitada
    $Registro .= ' ';                                 // 058 a 058 - Em branco
    $Registro .= colocazeros('19914',12);             // 059 a 070 - Conta debitada
    $Registro .= ' ';                                 // 071 a 071 - Em branco
    $Registro .= '0';                                 // 072 a 072 - DAC - Digito verificado da conta   
    $Registro .= colocaespacos('ISPCN ADMINISTRACAO DE TITULOS',30);   // 073 a 102 - Nome da empresa debitada
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

    # Selecionando do Clientes  - CONTA CORRENTE BANCO ITAU
    $sql = "SELECT  DISTINCT(a.codloja) codloja, mid(b.razaosoc,1,30) razaosoc, b.banco_cliente, b.agencia_cliente, 
                    b.conta_cliente, cpfcnpj_doc, upper(mid(CONVERT(b.nome_doc USING utf8),1,30)) nome_doc, 
                    '$tarifa_doc' as vr_repasse, b.tpconta, b.insc, b.cpfsocio1, b.cpfsocio2,
                    date_format(b.dt_cad,'%d/%m/%Y') as dt_cad,
                    b.id_franquia
            FROM cs2.contacorrente_recebafacil a
            INNER JOIN cs2.cadastro b ON a.codloja=b.codloja 
            WHERE b.banco_cliente = 341 
                AND b.tpconta = 1 
                AND b.sitcli = 0 
                AND ( ( b.pendencia_contratual = 0) or ( b.pendencia_contratual = 1 and b.dt_cad >= '$data_limite') )
            ORDER BY b.nome_doc";
    $qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
    $qtd = mysql_num_rows($qr_sql);
    if ( $qtd > 0 ){
                
        while($registro = mysql_fetch_array($qr_sql)){
            $codloja         = $registro['codloja'];
            $razaosoc        = $registro['razaosoc'];
            $banco_cliente   = $registro['banco_cliente'];
            $agencia_cliente = $registro['agencia_cliente'];
            $conta_cliente   = $registro['conta_cliente'];
            $cpfcnpj_doc     = trim($registro['cpfcnpj_doc']);
            $nome_doc        = substr(troca($registro['nome_doc']),0,30);
            $vr_repasse      = $registro['vr_repasse'];
            $tpconta         = $registro['tpconta'];
            $dt_cad          = $registro['dt_cad'];
            $insc            = $registro['insc'];
            $cpfsocio1       = $registro['cpfsocio1'];
            $cpfsocio2       = $registro['cpfsocio2'];
            $frq             = $registro['id_franquia'];

            # verificando o saldo na conta corrente do cliente
            $sql2 = 'SELECT saldo FROM cs2.contacorrente_recebafacil 
                     WHERE codloja='.$codloja.' ORDER BY id DESC LIMIT 1';
            $xqr_sql    = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
            $array_sql2 = mysql_fetch_array($xqr_sql);
            $saldo      = $array_sql2["saldo"];
            if ( empty($saldo) ) $saldo = 0;
            $vlr    = str_pad($saldo,20,' ',STR_PAD_RIGHT);
            $vlr    = number_format($vlr,2,',', '.');
            $saldo -= $vr_repasse;

            $id   = str_pad($codloja,6,' ',STR_PAD_RIGHT);
            $emp  = str_pad($razaosoc,30,' ',STR_PAD_RIGHT);
            $tit  = str_pad($nome_doc,30,' ',STR_PAD_RIGHT);
            $bco  = str_pad($banco_cliente,4,' ',STR_PAD_RIGHT);
            $age  = str_pad($agencia_cliente,5,' ',STR_PAD_RIGHT);
            $cta  = str_pad($conta_cliente,20,' ',STR_PAD_RIGHT);

            if ( $saldo > 0 ){
                
                # VERIFICANDO SE O CLIENTE CONSTA NA LISTA DE SALDO DEVEDOR ENVIO DUPLICADO
                $sql2 = 'SELECT valor FROM cs2.acerto_saldoerrado 
                         WHERE codloja='.$codloja.' AND pago = 0 ';
                $xqr_sql    = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
                $qtd = mysql_num_rows($xqr_sql);

                if ( $qtd > 0 ){
                    $xvlr = mysql_result($xqr_sql,0,'valor');
                    $xvlr = ( $xvlr * 1) / 100;
                    $xvlr = number_format($xvlr,2,',', '.');
                    $linha_bloqueio_lista .= "<tr style='font-size:12px'>
                                                    <td>$id</td>
                                                    <td>$emp</td>
                                                    <td>$tit</td>
                                                    <td>$bco</td>
                                                    <td>$age</td>
                                                    <td>$cta</td>
                                                    <td>$vlr</td>
                                                    <td>$xvlr</td>
                                                    <td>CC Itau -Pgto Dup</td>
                                                    <td>$frq</td>
                                                </tr>";
                }else{
            
                    # verificando o logon do cliente
                    $sql2 = 'SELECT CAST(MID(logon,1,6) AS UNSIGNED) logon, sitlog FROM cs2.logon WHERE codloja='.$codloja.' and sitlog < 2 LIMIT 1';
                    $xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
                    $array_sql2 = mysql_fetch_array($xqr_sql);
                    $logon = $array_sql2["logon"];
                    $sitlog = $array_sql2["sitlog"];

                    if ( $sitlog == 0){

                        $autoriza = 'S';
                        $valor_ant = verifica_emprestimo($codloja);
                        # ja que esta inadimplente ZERO o saldo para nao enviar
                        if ( $valor_ant > 0 ){
                            $soma_inad += $vlr;
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
                                                            <td>$frq</td>
                                                        </tr>";
                            $autoriza = 'N';
                            $saldo = 0; 
                        }
                        if ( $saldo > 0 ){
                            # verificando se o cliente possui titulo em aberto, se tiver n�o gera repasse para ele.
                            $xdata = date('Y-m-d');
                            $sql2 = "SELECT count(*) qtd FROM cs2.titulos
                                     WHERE codloja='$codloja' and datapg is null and vencimento < '$xdata'";

                            $xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
                            $array_sql2=mysql_fetch_array($xqr_sql);
                            $xqtd = $array_sql2["qtd"];
                            if ( $xqtd > 0 ){
                                # Cliente INADIMPLENTE com as mensalidades
                                $linha_inad .= "<tr style='font-size:12px'>
                                                    <td>$id</td>
                                                    <td>$emp</td>
                                                    <td>$tit</td>
                                                    <td>$bco</td>
                                                    <td>$age</td>
                                                    <td>$cta</td>
                                                    <td>$vlr</td>
                                                    <td>$dt_cad</td>
                                                    <td>Mens. Atrasada</td>
                                                    <td>$frq</td>
                                                </tr>";
                                $soma_inad += $vlr;
                            }else{
                                # Cliente em dia com suas mensalidades, conferindo autorizacao
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
                                                                        <td>$frq</td>
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
                                                        <td>$frq</td>
                                                        </tr>";
                                    }
                                }

                                if ( $autoriza == 'S' ){

                                    $NumeroRegistro++;
                                    $CODLOJA = '99999'.$codloja;
                                    // REGISTRO DETALHES DO LOTE
                                    $Registro = '341';                                         // 001 a 003 - C�digo do banco
                                    $Registro .= str_pad($NumeroLote, 4, 0, STR_PAD_LEFT);     // 004 a 007 - N�mero do lote
                                    $Registro .= '3';                                          // 008 a 008 - Tipo do registro: Registro detalhe do lote
                                    $Registro .= str_pad($NumeroRegistro, 5, 0, STR_PAD_LEFT); // 009 a 013 - N�mero seq�encial do registro no lote
                                    $Registro .= 'A';                                          // 014 a 014 - C�digo do segmento do registro detalhe
                                    $Registro .= '000';                                        // 015 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
                                    $Registro .= '000';                                        // 018 a 020 - ZEROS
                                    $Registro .= str_pad($banco_cliente, 3, 0, STR_PAD_LEFT);  // 021 a 023 - C�digo do Banco do Favorecido

                                    $agencia   = colocazeros(substr($agencia_cliente,0,4),4);
                                    $conta     = substr($conta_cliente,0,strlen($conta_cliente)-1);
                                    $conta     = $conta * 1;
                                    $conta     = str_pad($conta, 6, 0, STR_PAD_LEFT);
                                    $dac_cta   = substr($conta_cliente,strlen($conta_cliente)-1,1);
                                    $dac_cta   = str_pad($dac_cta, 1, 0, STR_PAD_LEFT);

                                    $agencia_conta = '0'.$agencia.' 000000'.$conta.' '.$dac_cta;

                                    $Registro .= $agencia_conta;                               // 024 a 043 - Conta Corrente do Favorecido
                                    $Registro .= str_pad($nome_doc, 30, ' ', STR_PAD_RIGHT);   // 044 a 073 - Nome do Favorecido
                                    $Registro .= str_pad('', 20, ' ', STR_PAD_RIGHT);          // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA
                                    $data = date('dmY');
                                    $Registro .= $data;                                        // 094 a 101 - Data de Pagamento
                                    $Registro .= 'REA';                                        // 102 a 104 - Tipo de Moeda
                                    $Registro .= str_pad('', 15, 0, STR_PAD_LEFT);             // 105 a 119 - ZEROS

                                    $valor = $saldo * 100;
                                    $valor = str_replace(',','',$valor);
                                    $valor = str_replace('.','',$valor);
                                    $Registro .= str_pad($valor, 15, 0, STR_PAD_LEFT);         // 120 a 134 - Valor do PAGAMENTO
                                    $Registro .= str_pad('', 15, ' ', STR_PAD_RIGHT);          // 135 a 149 - Num DOC ATRIBUIDO P/ BANCO
                                    $Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);           // 150 a 154 - BRANCOS
                                    $Registro .= '00000000';                                   // 155 a 162 - Data real da efetiva��o do lan�amento
                                    $Registro .= '000000000000000';                            // 163 a 177 - Valor real da efetiva��o do lan�amento

                                    $Registro .= str_pad($CODLOJA, 18, ' ', STR_PAD_RIGHT);    // 178 a 195 - Outras informa��es
                                    $Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);           // 196 a 197 - BRANCOS
                                    $Registro .= str_pad('', 6, 0, STR_PAD_LEFT);              // 198 a 203 - Numero do Documento

                                    $Registro .= str_pad($cpfcnpj_doc, 14, 0, STR_PAD_LEFT);   // 204 a 217 - CPF/CNPJ do Favorecido
                                    $Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);             // 218 a 219 - Finalidade do DOC
                                    $Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 220 a 224 - Finalidade do TED
                                    $Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 225 a 229 - BRANCOS
                                    $Registro .= str_pad('', 1, ' ', STR_PAD_RIGHT);             // 230 a 230 - Aviso ao Favorecido
                                    $Registro .= str_pad('', 10, ' ', STR_PAD_RIGHT);            // 231 a 240 - Ocorrencia de retorno
                                    $total_conta_corrente  += $saldo;
                                    $totlinha++;
                                    $registros .= "$Registro\n";

                                    if ( $frq == '2'){
                                        $log = substr($logon,0,5);
                                        $linha_tex .= "<tr style='font-size:12px'>
                                                            <td>$log</td>
                                                            <td>$emp</td>
                                                            <td>$tit</td>
                                                            <td>$cpfcnpj_doc</td>
                                                            <td>$bco</td>
                                                            <td>$age</td>
                                                            <td>$cta</td>
                                                            <td>$vlr</td>
                                                            <td>$dt_cad</td>
                                                            <td>Anexado ao Lote</td>
                                                            <td>$frq</td>
                                                        </tr>";
                                        $soma_tex += $saldo;
                                    }
                                }
                            }
                        }

                    }else{
                            
                        # ACESSO BLOQUEADO

                        $soma_inad += $saldo;
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
                                            <td>$frq</td>
                                        </tr>";
                                                
                    }
                }
            }
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

    # FIM - CONTA CORRENTE BANCO ITAU
    #########################################################################################################

    # INICIO - CONTA POUPANCA BANCO ITAU
    $NumeroLote++;
    $NumeroRegistro = 0;    
    // HEADER
    $Registro  = '341';                               // 001 a 003 - C�digo do Banco
    $Registro .= colocazeros($NumeroLote,4);          // 004 a 007 - Codigo do Lote
    $Registro .= '1';                                 // 008 a 008 - Tipo de Registro
    $Registro .= 'C';                                 // 009 a 009 - Tipo de Opera��o
    $tp_pagto = '20';
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
    $Registro .= '12244595000100';                    // 019 a 032 - Empresa Numero Inscri��o - Debitada

    $Registro .= '20'.$forma_pgto;               // 033 a 036 - Identificacao do lan�amento
    $Registro .= colocaespacos('',16);                // 037 a 052 - Em branco

    $Registro .= '08616';                             // 053 a 057 - Agencia debitada
    $Registro .= ' ';                                 // 058 a 058 - Em branco
    $Registro .= colocazeros('19914',12);             // 059 a 070 - Conta debitada
    $Registro .= ' ';                                 // 071 a 071 - Em branco
    $Registro .= '0';                                 // 072 a 072 - DAC - Digito verificado da conta   
    $Registro .= colocaespacos('ISPCN ADMINISTRACAO DE TITULOS',30);   // 073 a 102 - Nome da empresa debitada
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

    # Selecionando do Clientes  - CONTA POUPANCA BANCO ITAU
    $sql = "SELECT  DISTINCT(a.codloja) codloja, mid(b.razaosoc,1,30) razaosoc, b.banco_cliente, b.agencia_cliente, 
                    b.conta_cliente, cpfcnpj_doc, upper(mid(CONVERT(b.nome_doc USING utf8),1,30)) nome_doc, 
                    '$tarifa_doc' as vr_repasse, b.tpconta, b.insc, b.cpfsocio1, b.cpfsocio2,
                    date_format(b.dt_cad,'%d/%m/%Y') as dt_cad,
                    b.id_franquia
            FROM cs2.contacorrente_recebafacil a
            INNER JOIN cs2.cadastro b ON a.codloja=b.codloja 
            WHERE b.banco_cliente = 341 
                AND b.tpconta = 2 
                AND b.sitcli = 0  
                AND ( ( b.pendencia_contratual = 0) or ( b.pendencia_contratual = 1 and b.dt_cad >= '$data_limite') )
            ORDER BY b.nome_doc";
    $qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
    $qtd = mysql_num_rows($qr_sql);
    if ( $qtd > 0 ){
        while($registro = mysql_fetch_array($qr_sql)){
            $codloja = $registro['codloja'];
            $razaosoc = $registro['razaosoc'];
            $banco_cliente = $registro['banco_cliente'];
            $agencia_cliente = $registro['agencia_cliente'];
            $conta_cliente = $registro['conta_cliente'];
            $cpfcnpj_doc = trim($registro['cpfcnpj_doc']);
            $nome_doc = substr(troca($registro['nome_doc']),0,30);
            $vr_repasse = $registro['vr_repasse'];
            $tpconta = $registro['tpconta'];
            $dt_cad = $registro['dt_cad'];
            $insc = $registro['insc'];
            $cpfsocio1 = $registro['cpfsocio1'];
            $cpfsocio2 = $registro['cpfsocio2'];
            $frq = $registro['id_franquia'];
            
            $id   = str_pad($codloja,6,' ',STR_PAD_RIGHT);
            $emp  = str_pad($razaosoc,30,' ',STR_PAD_RIGHT);
            $tit  = str_pad($nome_doc,30,' ',STR_PAD_RIGHT);
            $bco  = str_pad($banco_cliente,4,' ',STR_PAD_RIGHT);
            $age  = str_pad($agencia_cliente,5,' ',STR_PAD_RIGHT);
            $cta  = str_pad($conta_cliente,20,' ',STR_PAD_RIGHT);

            # verificando o saldo na conta corrente do cliente
            $sql2 = 'SELECT saldo FROM cs2.contacorrente_recebafacil 
                     WHERE codloja='.$codloja.' ORDER BY id DESC LIMIT 1';
            $xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
            $array_sql2=mysql_fetch_array($xqr_sql);
            $saldo = $array_sql2["saldo"];
            if ( empty($saldo) ) $saldo = 0;
            $vlr  = str_pad($saldo,20,' ',STR_PAD_RIGHT);
            $vlr  = number_format($vlr,2,',', '.');
            $saldo -= $vr_repasse;
            
            if ( $saldo > 0 ){
                
                # VERIFICANDO SE O CLIENTE CONSTA NA LISTA DE SALDO DEVEDOR ENVIO DUPLICADO
                $sql2 = 'SELECT valor FROM cs2.acerto_saldoerrado 
                         WHERE codloja='.$codloja.' AND pago = 0 ';
                $xqr_sql    = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
                $qtd = mysql_num_rows($xqr_sql);
                
                if ( $qtd > 0 ){
                    $xvlr = mysql_result($xqr_sql,0,'valor');
                                        $soma_inad += $xvlr;
                    $xvlr = ( $xvlr * 1) / 100;
                                        $total_bloqueio += $xvlr;
                    $xvlr = number_format($xvlr,2,',', '.');
                    $linha_bloqueio_lista .= "<tr style='font-size:12px'>
                                                                        <td>$id</td>
                                                                        <td>$emp</td>
                                                                        <td>$tit</td>
                                                                        <td>$bco</td>
                                                                        <td>$age</td>
                                                                        <td>$cta</td>
                                                                        <td>$vlr</td>
                                                                        <td>$xvlr</td>
                                                                        <td>CP Itau - Pgto Dup</td>
                                                                    </tr>";
                }else{
                    
                    # verificando o logon do cliente
                    $sql2 = 'SELECT CAST(MID(logon,1,6) AS UNSIGNED) logon, sitlog FROM cs2.logon WHERE codloja='.$codloja.' and sitlog < 2 LIMIT 1';
                    $xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
                    $array_sql2 = mysql_fetch_array($xqr_sql);
                    $logon  = $array_sql2["logon"];
                    $sitlog = $array_sql2['sitlog'];
                    if ( $sitlog == 0 ){
                        $autoriza = 'S';
                        $valor_ant = verifica_emprestimo($codloja);
                        # j� que est� inadimplente ZERO o saldo para n�o enviar
                        if ( $valor_ant > 0 ){
                                                        $soma_inad += $saldo;
                            $linha_bloqueio_antecipa .= "<tr style='font-size:12px'>
                                                                                        <td>$id</td>
                                                                                        <td>$emp</td>
                                                                                        <td>$tit</td>
                                                                                        <td>$bco</td>
                                                                                        <td>$age</td>
                                                                                        <td>$cta</td>
                                                                                        <td>$saldo</td>
                                                                                        <td>$dt_cad</td>
                                                                                        <td>Atraso Antecipacao</td>
                                                                                        <td>$frq</td>
                                                                                     </tr>";
                            $autoriza = 'N';
                            $saldo = 0;
                        }
                
                        if ( $saldo > 0 ){
                            # verificando se o cliente possui titulo em aberto, 
                            # se tiver n�o gera repasse para ele.
                            $xdata = date('Y-m-d');
                            $sql2 = "SELECT count(*) qtd FROM cs2.titulos
                                     WHERE codloja='$codloja' and datapg is null and vencimento < '$xdata'";
                            $xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
                            $array_sql2=mysql_fetch_array($xqr_sql);
                            $xqtd = $array_sql2["qtd"];
                            if ( $xqtd > 0 ){
                                # Cliente INADIMPLENTE com as mensalidades
                                                                $soma_inad += $vlr;
                                $linha_inad .= "<tr style='font-size:12px'>
                                                                                    <td>$id</td>
                                                                                    <td>$emp</td>
                                                                                    <td>$tit</td>
                                                                                    <td>$bco</td>
                                                                                    <td>$age</td>
                                                                                    <td>$cta</td>
                                                                                    <td>$vlr</td>
                                                                                    <td>$dt_cad</td>
                                                                                    <td>Mens. Atrasada</td>
                                                                                    <td>$frq</td>
                                                                                </tr>";
                            }else{
                                
                                # Cliente em dia com suas mensalidades
                                if ( $saldo >= 2000 ){
                                
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
                                                                                                                    <td>$frq</td>
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
                                            <td>$frq</td>
                                            </tr>";
                                    }
                                }
                                
                                if ( $autoriza == 'S' ){
    
                                    $NumeroRegistro++;
                                    $CODLOJA = '99999'.$codloja;
                                    // REGISTRO DETALHES DO LOTE
                                    $Registro = '341';                                         // 001 a 003 - C�digo do banco
                                    $Registro .= str_pad($NumeroLote, 4, 0, STR_PAD_LEFT);     // 004 a 007 - N�mero do lote
                                    $Registro .= '3';                                          // 008 a 008 - Tipo do registro: Registro detalhe do lote
                                    $Registro .= str_pad($NumeroRegistro, 5, 0, STR_PAD_LEFT); // 009 a 013 - N�mero seq�encial do registro no lote
                                    $Registro .= 'A';                                          // 014 a 014 - C�digo do segmento do registro detalhe
                                    $Registro .= '000';                                        // 015 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
                                    $Registro .= '000';                                        // 018 a 020 - ZEROS
                                    $Registro .= str_pad($banco_cliente, 3, 0, STR_PAD_LEFT);  // 021 a 023 - C�digo do Banco do Favorecido
                            
                                    $agencia   = colocazeros(substr($agencia_cliente,0,4),4);
                                    $conta     = substr($conta_cliente,0,strlen($conta_cliente)-1);
                                    $conta     = $conta * 1;
                                    $conta     = str_pad($conta, 6, 0, STR_PAD_LEFT);
                                    $dac_cta   = substr($conta_cliente,strlen($conta_cliente)-1,1);
                                    $dac_cta   = str_pad($dac_cta, 1, 0, STR_PAD_LEFT);
                                
                                    $agencia_conta = '0'.$agencia.' 000000'.$conta.' '.$dac_cta;
                            
                                    $Registro .= $agencia_conta;                               // 024 a 043 - Conta Corrente do Favorecido
                                    $Registro .= str_pad($nome_doc, 30, ' ', STR_PAD_RIGHT);   // 044 a 073 - Nome do Favorecido
                                    $Registro .= str_pad('', 20, ' ', STR_PAD_RIGHT);          // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA
                                    $data = date('dmY');
                                    $Registro .= $data;                                        // 094 a 101 - Data de Pagamento
                                    $Registro .= 'REA';                                        // 102 a 104 - Tipo de Moeda
                                    $Registro .= str_pad('', 15, 0, STR_PAD_LEFT);             // 105 a 119 - ZEROS
        
                                    $valor = $saldo * 100;
                                    $valor = str_replace(',','',$valor);
                                    $valor = str_replace('.','',$valor);
                                    $Registro .= str_pad($valor, 15, 0, STR_PAD_LEFT);         // 120 a 134 - Valor do PAGAMENTO
                                    $Registro .= str_pad('', 15, ' ', STR_PAD_RIGHT);          // 135 a 149 - Num DOC ATRIBUIDO P/ BANCO
                                    $Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);           // 150 a 154 - BRANCOS
                                    $Registro .= '00000000';                                   // 155 a 162 - Data real da efetiva��o do lan�amento
                                    $Registro .= '000000000000000';                            // 163 a 177 - Valor real da efetiva��o do lan�amento
    
                                    $Registro .= str_pad($CODLOJA, 18, ' ', STR_PAD_RIGHT);    // 178 a 195 - Outras informa��es
                                    $Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);           // 196 a 197 - BRANCOS
                                    $Registro .= str_pad('', 6, 0, STR_PAD_LEFT);              // 198 a 203 - Numero do Documento
                            
                                    $Registro .= str_pad($cpfcnpj_doc, 14, 0, STR_PAD_LEFT);   // 204 a 217 - CPF/CNPJ do Favorecido
                                    $Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);             // 218 a 219 - Finalidade do DOC
                                    $Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 220 a 224 - Finalidade do TED
                                    $Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 225 a 229 - BRANCOS
                                    $Registro .= str_pad('', 1, ' ', STR_PAD_RIGHT);             // 230 a 230 - Aviso ao Favorecido
                                    $Registro .= str_pad('', 10, ' ', STR_PAD_RIGHT);            // 231 a 240 - Ocorrencia de retorno
                                    $total_conta_poupanca  += $saldo;
                                    $totlinha++;
                                    $registros .= "$Registro\n";
                                                                        if ( $frq == '2'){
                                                                            $log = substr($logon,0,5);
                                                                            $linha_tex .= "<tr style='font-size:12px'>
                                                                                                <td>$log</td>
                                                                                                <td>$emp</td>
                                                                                                <td>$tit</td>
                                                                                                <td>$cpfcnpj_doc</td>
                                                                                                <td>$bco</td>
                                                                                                <td>$age</td>
                                                                                                <td>$cta</td>
                                                                                                <td>$vlr</td>
                                                                                                <td>$dt_cad</td>
                                                                                                <td>Anexado ao Lote</td>
                                                                                                <td>$frq</td>
                                                                                            </tr>";
                                                                            $soma_tex += $saldo;
                                                                        }
                                }
                            }
                        }
                    }else{
                        // Cliente inadimplente
                        # Cliente INADIMPLENTE com as mensalidades
                                                $soma_inad += $vlr;
                        $linha_inad .= "<tr style='font-size:12px'>
                                            <td>$id</td>
                                            <td>$emp</td>
                                            <td>$tit</td>
                                            <td>$bco</td>
                                            <td>$age</td>
                                            <td>$cta</td>
                                            <td>$vlr</td>
                                            <td>$dt_cad</td>
                                            <td>Mens. Atrasada</td>
                                            <td>$frq</td>
                                        </tr>";
                    }
                }
            }
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
    
    # FIM - CONTA POUPANCA BANCO ITAU


    #########################################################################################################
    
    # INICIO - TED - OUTROS BANCOS
    
    $NumeroLote++;
    $NumeroRegistro = 0;    
    // HEADER
    $Registro  = '341';                               // 001 a 003 - C�digo do Banco
    $Registro .= colocazeros($NumeroLote,4);          // 004 a 007 - Codigo do Lote
    $Registro .= '1';                                 // 008 a 008 - Tipo de Registro
    $Registro .= 'C';                                 // 009 a 009 - Tipo de Opera��o
    $tp_pagto = '20';
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
    $Registro .= '12244595000100';                    // 019 a 032 - Empresa Numero Inscri��o - Debitada
    
    $Registro .= $tp_pagto.$forma_pgto;               // 033 a 036 - Identificacao do lan�amento
    $Registro .= colocaespacos('',16);                // 037 a 052 - Em branco
    
    $Registro .= '08616';                             // 053 a 057 - Agencia debitada
    $Registro .= ' ';                                 // 058 a 058 - Em branco
    $Registro .= colocazeros('19914',12);             // 059 a 070 - Conta debitada
    $Registro .= ' ';                                 // 071 a 071 - Em branco
    $Registro .= '0';                                 // 072 a 072 - DAC - Digito verificado da conta   
    $Registro .= colocaespacos('ISPCN ADMINISTRACAO DE TITULOS',30);   // 073 a 102 - Nome da empresa debitada
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
    
    # Selecionando do Clientes - TED
    $sql = "SELECT  DISTINCT(a.codloja) codloja, mid(b.razaosoc,1,30) razaosoc, b.banco_cliente, b.agencia_cliente, 
                    b.conta_cliente, cpfcnpj_doc, upper(mid(CONVERT(b.nome_doc USING utf8),1,30)) nome_doc, 
                    '$tarifa_doc' as vr_repasse, b.tpconta, b.insc, b.cpfsocio1, b.cpfsocio2,
                    date_format(b.dt_cad,'%d/%m/%Y') as dt_cad,
                    b.id_franquia
            FROM cs2.contacorrente_recebafacil a
            INNER JOIN cs2.cadastro b ON a.codloja=b.codloja 
            WHERE ( b.banco_cliente <> 237 AND b.banco_cliente <> 341)
                    AND b.sitcli = 0 
                    AND b.banco_cliente > 0  
                    AND ( ( b.pendencia_contratual = 0) or ( b.pendencia_contratual = 1 and b.dt_cad >= '$data_limite') )
            ORDER BY b.nome_doc";
    $qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
    $qtd = mysql_num_rows($qr_sql);
    if ( $qtd > 0 ){
            while($registro = mysql_fetch_array($qr_sql)){
                $codloja = $registro['codloja'];
                $razaosoc = $registro['razaosoc'];
                $banco_cliente = $registro['banco_cliente'];
                $agencia_cliente = $registro['agencia_cliente'];
                $conta_cliente = $registro['conta_cliente'];
                $cpfcnpj_doc = trim($registro['cpfcnpj_doc']);
                $nome_doc = substr(troca($registro['nome_doc']),0,30);
                $vr_repasse = $registro['vr_repasse'];
                $tpconta = $registro['tpconta'];
                $dt_cad = $registro['dt_cad'];
                $insc = $registro['insc'];
                $cpfsocio1 = $registro['cpfsocio1'];
                $cpfsocio2 = $registro['cpfsocio2'];
                $frq = $registro['id_franquia'];

                # verificando o saldo na conta corrente do cliente
                $sql2 = 'SELECT saldo FROM cs2.contacorrente_recebafacil 
                                 WHERE codloja='.$codloja.' ORDER BY id DESC LIMIT 1';
                $xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
                $array_sql2=mysql_fetch_array($xqr_sql);
                $saldo = $array_sql2["saldo"];
                if ( empty($saldo) ) $saldo = 0;
                $vlr  = str_pad($saldo,20,' ',STR_PAD_RIGHT);
                $vlr  = number_format($vlr,2,',', '.');
                $saldo -= $vr_repasse;

                $id   = str_pad($codloja,6,' ',STR_PAD_RIGHT);
                $emp  = str_pad($razaosoc,30,' ',STR_PAD_RIGHT);
                $tit  = str_pad($nome_doc,30,' ',STR_PAD_RIGHT);
                $bco  = str_pad($banco_cliente,4,' ',STR_PAD_RIGHT);
                $age  = str_pad($agencia_cliente,5,' ',STR_PAD_RIGHT);
                $cta  = str_pad($conta_cliente,20,' ',STR_PAD_RIGHT);

                if ( $saldo > 0 ){

                    if ( $saldo > 4999 ){

                        # VERIFICANDO SE O CLIENTE CONSTA NA LISTA DE SALDO DEVEDOR ENVIO DUPLICADO
                        $sql2 = 'SELECT valor FROM cs2.acerto_saldoerrado 
                                 WHERE codloja='.$codloja.' AND pago = 0 ';
                        $xqr_sql    = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
                        $qtd = mysql_num_rows($xqr_sql);

                        if ( $qtd > 0 ){
                                    $xvlr = mysql_result($xqr_sql,0,'valor');
                                    $xvlr = ( $xvlr * 1) / 100;
                                    $xvlr = number_format($xvlr,2,',', '.');
                                    $linha_bloqueio_lista .= "<tr style='font-size:12px'>
                                                                        <td>$id</td>
                                                                        <td>$emp</td>
                                                                        <td>$tit</td>
                                                                        <td>$bco</td>
                                                                        <td>$age</td>
                                                                        <td>$cta</td>
                                                                        <td>$vlr</td>
                                                                        <td>$xvlr</td>
                                                                        <td>TED - Pgto Dup</td>
                                                                        <td>$frq</td>
                                                                </tr>";
                            }else{

                                # verificando o logon do cliente
                                $sql2 = 'SELECT CAST(MID(logon,1,6) AS UNSIGNED) logon, sitlog FROM cs2.logon 
                                         WHERE codloja='.$codloja.' and sitlog < 2 LIMIT 1';
                                $xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
                                $array_sql2 = mysql_fetch_array($xqr_sql);
                                $logon  = $array_sql2["logon"];
                                $sitlog = $array_sql2["sitlog"];
                                if ( $sitlog == 0 ){

                                    $valor_ant = verifica_emprestimo($codloja);
                                    # j� que est� inadimplente ZERO o saldo para n�o enviar
                                    if ( $valor_ant > 0 ){
                                        $soma_inad += $vlr;
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
                                                                        <td>$frq</td>
                                                                    </tr>";
                                    }else{

                                        # nao deve emprestimo

                                        # verificando se o cliente possui titulo em aberto, 
                                        # se tiver n�o gera repasse para ele.
                                        $xdata = date('Y-m-d');

                                        $sql2 = "SELECT count(*) qtd FROM cs2.titulos
                                                 WHERE codloja='$codloja' and datapg is null and vencimento < '$xdata'";

                                        $xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
                                        $array_sql2=mysql_fetch_array($xqr_sql);
                                        $xqtd = $array_sql2["qtd"];
                                        if ( $xqtd > 0 ){
                                            # Cliente INADIMPLENTE com as mensalidades
                                            $soma_inad += $saldo;
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
                                                            <td>Mens. atrasada</td>
                                                            <td>$frq</td>
                                                            </tr>";
                                        }else{

                                            # nao tem titulo em atraso
                                            $linha_ted .= "<tr style='font-size:12px'>
                                                            <td>$id</td>
                                                            <td>$emp</td>
                                                            <td>$tit</td>
                                                            <td>$bco</td>
                                                            <td>$age</td>
                                                            <td>$cta</td>
                                                            <td>$saldo</td>
                                                            <td>$dt_cad</td>
                                                            <td>Anexado ao Lote</td>
                                                            <td>$frq</td>
                                                            </tr>";

                                            # Cliente em dia com suas mensalidades
                                            $NumeroRegistro++;
                                            $CODLOJA = '99999'.$codloja;
                                            // REGISTRO DETALHES DO LOTE
                                            $Registro = '341';                                         // 001 a 003 - C�digo do banco
                                            $Registro .= str_pad($NumeroLote, 4, 0, STR_PAD_LEFT);     // 004 a 007 - N�mero do lote
                                            $Registro .= '3';                                          // 008 a 008 - Tipo do registro: Registro detalhe do lote
                                            $Registro .= str_pad($NumeroRegistro, 5, 0, STR_PAD_LEFT); // 009 a 013 - N�mero seq�encial do registro no lote
                                            $Registro .= 'A';                                          // 014 a 014 - C�digo do segmento do registro detalhe
                                            $Registro .= '000';                                        // 015 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
                                            $Registro .= '000';                                        // 018 a 020 - ZEROS
                                            $Registro .= str_pad($banco_cliente, 3, 0, STR_PAD_LEFT);  // 021 a 023 - C�digo do Banco do Favorecido

                                            $agencia   = colocazeros(substr($agencia_cliente,0,4),5);
                                            $conta     = substr($conta_cliente,0,strlen($conta_cliente)-1);
                                            $conta     = $conta * 1;
                                            $conta     = str_pad($conta, 12, 0, STR_PAD_LEFT);
                                            $dac_cta   = substr($conta_cliente,strlen($conta_cliente)-1,1);
                                            $dac_cta   = str_pad($dac_cta, 1, 0, STR_PAD_LEFT);

                                            $agencia_conta = $agencia.' '.$conta.' '.$dac_cta;

                                            $Registro .= $agencia_conta;                             // 024 a 043 - Conta Corrente do Favorecido
                                            $Registro .= str_pad($nome_doc, 30, ' ', STR_PAD_RIGHT); // 044 a 073 - Nome do Favorecido
                                            $Registro .= str_pad('', 20, ' ', STR_PAD_RIGHT);        // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA
                                            $data = date('dmY');
                                            $Registro .= $data;                                      // 094 a 101 - Data de Pagamento
                                            $Registro .= 'REA';                                      // 102 a 104 - Tipo de Moeda
                                            $Registro .= str_pad('', 15, 0, STR_PAD_LEFT);           // 105 a 119 - ZEROS

                                            $valor = $saldo * 100;
                                            $valor = str_replace(',','',$valor);
                                            $valor = str_replace('.','',$valor);
                                            $Registro .= str_pad($valor, 15, 0, STR_PAD_LEFT);       // 120 a 134 - Valor do PAGAMENTO
                                            $Registro .= str_pad('', 15, ' ', STR_PAD_RIGHT);        // 135 a 149 - Num DOC ATRIBUIDO P/ BANCO
                                            $Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);         // 150 a 154 - BRANCOS
                                            $Registro .= '00000000';                                 // 155 a 162 - Data real da efetiva��o do lan�amento
                                            $Registro .= '000000000000000';                          // 163 a 177 - Valor real da efetiva��o do lan�amento

                                            $Registro .= str_pad($CODLOJA, 18, ' ', STR_PAD_RIGHT);  // 178 a 195 - Outras informa��es
                                            $Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);         // 196 a 197 - BRANCOS
                                            $Registro .= str_pad('', 6, 0, STR_PAD_LEFT);            // 198 a 203 - Numero do Documento

                                            $Registro .= str_pad($cpfcnpj_doc, 14, 0, STR_PAD_LEFT); // 204 a 217 - CPF/CNPJ do Favorecido
                                            $Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);         // 218 a 219 - Finalidade do DOC
                                            $Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);         // 220 a 224 - Finalidade do TED
                                            $Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);         // 225 a 229 - BRANCOS
                                            $Registro .= str_pad('', 1, ' ', STR_PAD_RIGHT);         // 230 a 230 - Aviso ao Favorecido
                                            $Registro .= str_pad('', 10, ' ', STR_PAD_RIGHT);        // 231 a 240 - Ocorrencia de retorno
                                            $total_ted  += $saldo;
                                            $totlinha++;
                                            $registros .= "$Registro\n";
                                            if ( $frq == '2'){
                                                $log = substr($logon,0,5);
                                                $linha_tex .= "<tr style='font-size:12px'>
                                                                    <td>$log</td>
                                                                    <td>$emp</td>
                                                                    <td>$tit</td>
                                                                    <td>$cpfcnpj_doc</td>
                                                                    <td>$bco</td>
                                                                    <td>$age</td>
                                                                    <td>$cta</td>
                                                                    <td>$vlr</td>
                                                                    <td>$dt_cad</td>
                                                                    <td>Anexado ao Lote</td>
                                                                    <td>$frq</td>
                                                                </tr>";
                                                $soma_tex += $saldo;
                                            }
                                        }
                                    }
                                }else{
                                    # ACESSO BLOQUEADO
                                    $soma_inad += $saldo;
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
                                                                            <td>Mens. Atrasada.</td>
                                                                            <td>$frq</td>
                                                                    </tr>";
                                }
                            }
                    }
                }
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
    
    # FIM - TED - OUTROS BANCOS


    #########################################################################################################
    
    # INICIO - DOC - OUTROS BANCOS
    $NumeroLote++;
    $NumeroRegistro = 0;    
    // HEADER
    $Registro  = '341';                               // 001 a 003 - C�digo do Banco
    $Registro .= colocazeros($NumeroLote,4);          // 004 a 007 - Codigo do Lote
    $Registro .= '1';                                 // 008 a 008 - Tipo de Registro
    $Registro .= 'C';                                 // 009 a 009 - Tipo de Opera��o
    $tp_pagto = '20';
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
    $Registro .= '12244595000100';                    // 019 a 032 - Empresa Numero Inscri��o - Debitada
    
    $Registro .= $tp_pagto.$forma_pgto;               // 033 a 036 - Identificacao do lan�amento
    $Registro .= colocaespacos('',16);                // 037 a 052 - Em branco
    
    $Registro .= '08616';                             // 053 a 057 - Agencia debitada
    $Registro .= ' ';                                 // 058 a 058 - Em branco
    $Registro .= colocazeros('19914',12);             // 059 a 070 - Conta debitada
    $Registro .= ' ';                                 // 071 a 071 - Em branco
    $Registro .= '0';                                 // 072 a 072 - DAC - Digito verificado da conta   
    $Registro .= colocaespacos('ISPCN ADMINISTRACAO DE TITULOS',30);   // 073 a 102 - Nome da empresa debitada
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
    
    # Selecionando do Clientes  - CONTA CORRENTE OUTROS BANCOS
    $sql = "SELECT  DISTINCT(a.codloja) codloja, mid(b.razaosoc,1,30) razaosoc, b.banco_cliente, b.agencia_cliente, 
                    b.conta_cliente, cpfcnpj_doc, upper(mid(CONVERT(b.nome_doc USING utf8),1,30)) nome_doc, 
                    '$tarifa_doc' as vr_repasse, b.tpconta, b.insc, b.cpfsocio1, b.cpfsocio2,
                    date_format(b.dt_cad,'%d/%m/%Y') as dt_cad,
                    b.id_franquia
            FROM cs2.contacorrente_recebafacil a
            INNER JOIN cs2.cadastro b ON a.codloja=b.codloja 
            WHERE ( b.banco_cliente <> 341 AND b.banco_cliente <> 237)
                AND b.sitcli = 0 
                AND b.banco_cliente > 0 
                AND ( ( b.pendencia_contratual = 0) or ( b.pendencia_contratual = 1 and b.dt_cad >= '$data_limite') )
            ORDER BY b.nome_doc";
    $qr_sql = mysql_query($sql,$conexao) or die("Erro no SQL: $sql");
    $qtd = mysql_num_rows($qr_sql);
    if ( $qtd > 0 ){
        while($registro = mysql_fetch_array($qr_sql)){
            $codloja = $registro['codloja'];
            $razaosoc = $registro['razaosoc'];
            $banco_cliente = $registro['banco_cliente'];
            $agencia_cliente = $registro['agencia_cliente'];
            $conta_cliente = $registro['conta_cliente'];
            $cpfcnpj_doc = trim($registro['cpfcnpj_doc']);
            $nome_doc = substr(troca($registro['nome_doc']),0,30);
            $vr_repasse = $registro['vr_repasse'];
            $tpconta = $registro['tpconta'];
            $dt_cad = $registro['dt_cad'];
            $insc = $registro['insc'];
            $cpfsocio1 = $registro['cpfsocio1'];
            $cpfsocio2 = $registro['cpfsocio2'];
            $frq = $registro['id_franquia'];
            
            # verificando o saldo na conta corrente do cliente
            $sql2 = 'SELECT saldo FROM cs2.contacorrente_recebafacil 
                     WHERE codloja='.$codloja.' ORDER BY id DESC LIMIT 1';
            $xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
            $array_sql2=mysql_fetch_array($xqr_sql);
            $saldo = $array_sql2["saldo"];
            if ( empty($saldo) ) $saldo = 0;
            $vlr  = str_pad($saldo,20,' ',STR_PAD_RIGHT);
            $vlr  = number_format($vlr,2,',', '.');
            $saldo -= $vr_repasse;
            
            $id   = str_pad($codloja,6,' ',STR_PAD_RIGHT);
            $emp  = str_pad($razaosoc,30,' ',STR_PAD_RIGHT);
            $tit  = str_pad($nome_doc,30,' ',STR_PAD_RIGHT);
            $bco  = str_pad($banco_cliente,4,' ',STR_PAD_RIGHT);
            $age  = str_pad($agencia_cliente,5,' ',STR_PAD_RIGHT);
            $cta  = str_pad($conta_cliente,20,' ',STR_PAD_RIGHT);
        
            if ( $saldo > 0 ){
                
                # VERIFICANDO SE O CLIENTE CONSTA NA LISTA DE SALDO DEVEDOR ENVIO DUPLICADO
                $sql2 = 'SELECT valor FROM cs2.acerto_saldoerrado 
                         WHERE codloja='.$codloja.' AND pago = 0 ';
                $xqr_sql    = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
                $qtd = mysql_num_rows($xqr_sql);
                
                if ( $qtd > 0 ){
                    $xvlr = mysql_result($xqr_sql,0,'valor');
                    $xvlr = ( $xvlr * 1) / 100;
                    $xvlr = number_format($xvlr,2,',', '.');
                    $linha_bloqueio_lista .= "<tr style='font-size:12px'>
                                                                            <td>$id</td>
                                                                            <td>$emp</td>
                                                                            <td>$tit</td>
                                                                            <td>$bco</td>
                                                                            <td>$age</td>
                                                                            <td>$cta</td>
                                                                            <td>$vlr</td>
                                                                            <td>$xvlr</td>
                                                                            <td>DOC - Pgto Dup</td>
                                                                            <td>$frq</td>
                                                                    </tr>";
                }else{
                    
                    # verificando o logon do cliente
                    $sql2 = 'SELECT CAST(MID(logon,1,6) AS UNSIGNED) logon, sitlog FROM cs2.logon 
                             WHERE codloja = '.$codloja.' and sitlog < 2 LIMIT 1';
                    $xqr_sql = mysql_query($sql2,$conexao) or die ("Erro no SQL: $sql2");
                    $array_sql2 = mysql_fetch_array($xqr_sql);
                    $logon  = $array_sql2["logon"];
                    $sitlog = $array_sql2["sitlog"];
                    if ( $sitlog == 0 ){
                        
                        $autoriza = 'S';
                    
                        if ( $saldo < 5000 ){
                            
                            $valor_ant = verifica_emprestimo($codloja);
                            # j� que est� inadimplente ZERO o saldo para n�o enviar
                            if ( $valor_ant > 0 ){
                                                            $soma_inad += $saldo;
                                $linha_bloqueio_antecipa .= "<tr style='font-size:12px'>
                                                                                                    <td>$id</td>
                                                                                                    <td>$emp</td>
                                                                                                    <td>$tit</td>
                                                                                                    <td>$bco</td>
                                                                                                    <td>$age</td>
                                                                                                    <td>$cta</td>
                                                                                                    <td>$saldo</td>
                                                                                                    <td>$dt_cad</td>
                                                                                                    <td>Atraso Antecipacao</td>
                                                                                                    <td>$frq</td>
                                                                                            </tr>";
                            }else{
                                # nao deve emprestimo
                                # verificando se o cliente possui titulo em aberto, se tiver n�o gera repasse para ele.
                                $xdata = date('Y-m-d');
                        
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
                                                <td>Mens. atrasada</td>
                                                <td>$frq</td>
                                                </tr>";
                                }else{
                                    
                                # Cliente em dia com suas mensalidades
                                
                                    if ( $saldo >= 2000 ){
                                
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
                                                                                                                                <td>$frq</td>
                                                                                                                        </tr>";
                                                $autoriza = 'N';
                                            }else{
                                                // autorizado, a conta est� cadastrada para receber
                                                $autoriza = 'S';
                                            }
                                        }
                                    }
                                    elseif ( $saldo >= 5000 ){
        
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
                                                <td>$frq</td>
                                                </tr>";
                                        }
                                    }
                                    if ( $autoriza == 'S' ){
        
                                        $NumeroRegistro++;
                                        $CODLOJA = '99999'.$codloja;
                                        // REGISTRO DETALHES DO LOTE
                                        $Registro = '341';                                         // 001 a 003 - C�digo do banco
                                        $Registro .= str_pad($NumeroLote, 4, 0, STR_PAD_LEFT);     // 004 a 007 - N�mero do lote
                                        $Registro .= '3';                                          // 008 a 008 - Tipo do registro: Registro detalhe do lote
                                        $Registro .= str_pad($NumeroRegistro, 5, 0, STR_PAD_LEFT); // 009 a 013 - N�mero seq�encial do registro no lote
                                        $Registro .= 'A';                                          // 014 a 014 - C�digo do segmento do registro detalhe
                                        $Registro .= '000';                                        // 015 a 017 - C�digo da instru��o p/ movimento  00 - Inclus�o
                                        $Registro .= '000';                                        // 018 a 020 - ZEROS
                                        $Registro .= str_pad($banco_cliente, 3, 0, STR_PAD_LEFT);  // 021 a 023 - C�digo do Banco do Favorecido
                            
                                        $agencia   = colocazeros(substr($agencia_cliente,0,4),5);
                                        $conta     = substr($conta_cliente,0,strlen($conta_cliente)-1);
                                        $conta     = $conta * 1;
                                        $conta     = str_pad($conta, 12, 0, STR_PAD_LEFT);
                                        $dac_cta   = substr($conta_cliente,strlen($conta_cliente)-1,1);
                                        $dac_cta   = str_pad($dac_cta, 1, 0, STR_PAD_LEFT);
                            
                                        $agencia_conta = $agencia.' '.$conta.' '.$dac_cta;
                            
                                        $Registro .= $agencia_conta;                               // 024 a 043 - Conta Corrente do Favorecido
                                        $Registro .= str_pad($nome_doc, 30, ' ', STR_PAD_RIGHT);   // 044 a 073 - Nome do Favorecido
                                        $Registro .= str_pad('', 20, ' ', STR_PAD_RIGHT);          // 074 a 093 - Num DOC ATRIBUIDO P/ EMPRESA
                                        $data = date('dmY');
                                        $Registro .= $data;                                        // 094 a 101 - Data de Pagamento
                                        $Registro .= 'REA';                                        // 102 a 104 - Tipo de Moeda
                                        $Registro .= str_pad('', 15, 0, STR_PAD_LEFT);             // 105 a 119 - ZEROS
        
                                        $valor = $saldo * 100;
                                        $valor = str_replace(',','',$valor);
                                        $valor = str_replace('.','',$valor);
                                        $Registro .= str_pad($valor, 15, 0, STR_PAD_LEFT);         // 120 a 134 - Valor do PAGAMENTO
                                        $Registro .= str_pad('', 15, ' ', STR_PAD_RIGHT);          // 135 a 149 - Num DOC ATRIBUIDO P/ BANCO
                                        $Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);           // 150 a 154 - BRANCOS
                                        $Registro .= '00000000';                                   // 155 a 162 - Data real da efetiva��o do lan�amento
                                        $Registro .= '000000000000000';                            // 163 a 177 - Valor real da efetiva��o do lan�amento
    
                                        $Registro .= str_pad($CODLOJA, 18, ' ', STR_PAD_RIGHT);    // 178 a 195 - Outras informa��es
                                        $Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);           // 196 a 197 - BRANCOS
                                        $Registro .= str_pad('', 6, 0, STR_PAD_LEFT);              // 198 a 203 - Numero do Documento
                            
                                        $Registro .= str_pad($cpfcnpj_doc, 14, 0, STR_PAD_LEFT);   // 204 a 217 - CPF/CNPJ do Favorecido
                                        $Registro .= str_pad('', 2, ' ', STR_PAD_RIGHT);             // 218 a 219 - Finalidade do DOC
                                        $Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 220 a 224 - Finalidade do TED
                                        $Registro .= str_pad('', 5, ' ', STR_PAD_RIGHT);             // 225 a 229 - BRANCOS
                                        $Registro .= str_pad('', 1, ' ', STR_PAD_RIGHT);             // 230 a 230 - Aviso ao Favorecido
                                        $Registro .= str_pad('', 10, ' ', STR_PAD_RIGHT);            // 231 a 240 - Ocorrencia de retorno
    
                                        $total_doc  += $saldo;
                                        $totlinha++;
                                        $registros .= "$Registro\n";
                                                                                if ( $frq == '2'){
                                                                                    $log = substr($logon,0,5);
                                                                                    $linha_tex .= "<tr style='font-size:12px'>
                                                                                                        <td>$log</td>
                                                                                                        <td>$emp</td>
                                                                                                        <td>$tit</td>
                                                                                                        <td>$cpfcnpj_doc</td>
                                                                                                        <td>$bco</td>
                                                                                                        <td>$age</td>
                                                                                                        <td>$cta</td>
                                                                                                        <td>$vlr</td>
                                                                                                        <td>$dt_cad</td>
                                                                                                        <td>Anexado ao Lote</td>
                                                                                                        <td>$frq</td>
                                                                                                    </tr>";
                                                                                    $soma_tex += $saldo;
                                                                                }
                                    }
                                }
                            }
                        }
                    }else{
                        # LOGON BLOQUEADO
                                            $soma_inad += $saldo;
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
                                                                    <td>$frq</td>
                                                                </tr>";
                    }
                }
            }
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

    try{
        $arquivo = "../../../download/PG".date("dmy");
        $arq = "PG".date("dmy");
        $abrir = fopen($arquivo, "w");
        $escreve = fputs($abrir, $registros);
        fclose($arquivo);
    } catch (Exception $e) {
        echo 'Erro ao gravar o arquivo: ',  $e->getMessage(), "\n";
    }
    
    $tot_pagar = $total_conta_corrente + $total_conta_poupanca + $total_doc + $total_ted;
    $total_geral = number_format($tot_pagar,2,',','.');
    
    echo "<div align='center'>
        <font color='#FF0000' style='font-family:verdana' >
          <p>ATEN&Ccedil;&Atilde;O !!! </p>
          <p>Neste lote nao sera gerado repasse do BANCO BRADESCO (237)<br></p>
          </font>

          <p>Arquivo Gerado com sucesso</p> 
          <p><a href=https://www.webcontrolempresas.com.br/download/".$arq.">".$arq."</a><p>
          <p>Total Cta Corrente Itau: R$ $total_conta_corrente<br>
          Total Cta Poupanca Itau: R$ $total_conta_poupanca<br>
          Total Cta DOC: R$ $total_doc<br>
          Total Cta TED: R$ $total_ted</p>
          <p>Total Geral : R$ $total_geral</p>
          </div>";

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
        

        $total_bloqueio = number_format($total_bloqueio,2,',', '.');
        
        echo " 
        <table width='1000' align='center'>
            <tr bgcolor='#CCCCCC' style='font-size:12px' >
                <td></td>
                <td></td>
                <td></td>
                <td width='20'></td>
                <td width='50'></td>
                <td width='100'></td>
                <td width='100'></td>
                <td width='100'></td>
                <td width='100'></td>
            </tr>
            <tr style='font-size:12px'>
                <td colspan='6'></td>
                <td colspan='2'>$soma_inad</td>
            </tr>
        </table>";

    $soma_tex = number_format($soma_tex, 2, ',', '.');
    $linha_tex .= "<tr><td colspan='11'><hr></td></tr>
                    <tr>
                    <td colspan='6'></td>
                    <td>Total:</td>
                    <td colspan='4'>$soma_tex</td>";
    $linha_tex .= "</table>";
    echo $linha_tex;    
    
?>
