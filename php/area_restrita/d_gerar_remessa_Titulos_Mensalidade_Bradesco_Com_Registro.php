<?php


    # Autor:   Luciano Mancini
    # Modulo:  Remessa Titulos Mensalidades - Banco Bradesco
    # Finalidade: 
    #       Gerar o arquivo de titulos a serem enviados ao Banco Bradesco 
    #       para REGISTRO
    
    # Monitoramento porque não está sendo registrado os titulos gerado pelo COMERCIAL.
    # Resumo: Está gerando sim, conferido no arquivo /home/skyunix/sentbox/cob237_4972103_231018_01.rem

    function troca($dados){

        for ($i = 0; $i <= strlen($dados); $i++) {

            $x = $dados[$i];
            $o = ord($dados[$i]);
            //echo "[$o][$x]<br>";
            switch ($o) {
                case 40 : $saidax .= ' ';
                    break; // (
                case 41 : $saidax .= ' ';
                    break; // )
                case 210 : $saidax .= 'O';
                    break; // Ò
                case 211 : $saidax .= 'O';
                    break; // Ó
                case 212 : $saidax .= 'O';
                    break; // Ô
                case 213 : $saidax .= 'O';
                    break; // Õ
                case 214 : $saidax .= 'O';
                    break; // Ö
                case 192 : $saidax .= 'A';
                    break; // À
                case 193 : $saidax .= 'A';
                    break; // Á
                case 194 : $saidax .= 'A';
                    break; // Â
                case 195 : $saidax .= 'A';
                    break; // Ã
                case 201 : $saidax .= 'E';
                    break; // É             
                case 202 : $saidax .= 'E';
                    break; // Ê
                case 203 : $saidax .= 'E';
                    break; // Ë
                case 204 : $saidax .= 'I';
                    break; // Ì
                case 205 : $saidax .= 'I';
                    break; // Í
                case 206 : $saidax .= 'I';
                    break; // Î
                case 207 : $saidax .= 'I';
                    break; // Ï
                case 217 : $saidax .= 'A';
                    break; // Ù
                case 218 : $saidax .= 'A';
                    break; // Ú
                case 219 : $saidax .= 'A';
                    break; // Û
                case 220 : $saidax .= 'A';
                    break; // Ü
                case 43 : $saidax .= '';
                    break; // +
                case 64 : $saidax .= '';
                    break; // @
                case 38 : $saidax .= 'E';
                    break; // &
                case 199 : $saidax .= 'C';
                    break; // Ç
                default : $saidax .= $x;
                    break;
            }
        }
        return $saidax;
    };

    function modulo_11($num, $base=9, $r=0)  {

        $soma = 0;
        $fator = 2;
        /* Separacao dos numeros */
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num,$i-1,1);
            // Efetua multiplicacao do numero pelo falor
            $parcial[$i] = $numeros[$i] * $fator;
            // Soma dos digitos
            $soma += $parcial[$i];
            if ($fator == $base) {
                // restaura fator de multiplicacao para 2 
                $fator = 1;
            }
            $fator++;
        }
        /* Calculo do modulo 11 */
        if ($r == 0) {
            $soma *= 10;
            $digito = $soma % 11;
            if ($digito == 10) {
                $digito = 0;
            }
            return $digito;
        } elseif ($r == 1){
            $resto = $soma % 11;
            return $resto;
        }
    }

    function digitoVerificador_nossonumero($numero) {
        $resto2 = modulo_11($numero, 7, 1);
        $digito = 11 - $resto2;
        if ($digito == 10) {
            $dv = "P";
        } elseif ($digito == 11) {
            $dv = 0;
        } else {
            $dv = $digito;
        }
        return $dv;
    }

    global $conexao,$arquivo;

    $usermy = "csinform";
    $passwordmy = "inform4416#scf";
    $nomedb = "consulta";
    $conexao = @mysql_pconnect("10.2.2.3",$usermy,$passwordmy)or die ("Prezado Cliente, <br><br> Estamos em manuten&ccedil;&atilde;o em Nosso Banco de Dados, dentro de instantes a conex&atilde;o ser&aacute; estabelecida novamente. <br><br>Atenciosamente, <br><br>Departamento de TI.");
    $bd = mysql_select_db($nomedb,$conexao) or die("Nao foi posivel selecionar o banco de dados contate o administrador erro 30");

    @$processado_nexxera = $argv[1]; // somente vira pela nexxera

    $timestamp = mktime(date("H") - $fusohorario, date("i"), date("s"), date("m"), date("d"), date("Y"));
    $DATA['DIA'] = gmdate("d",$timestamp);
    $DATA['MES'] = gmdate("m",$timestamp);
    $DATA['ANO'] = gmdate("y",$timestamp);

    $data_hora_final = date('Y-m-d H:i:s');
    
    // Buscando no numero do LOTE do Banco
    // Selecionando a Empresa que ira Registrar os Titulos ( WC SISTEMAS )
    $sql_empresa = "SELECT id, banco, agencia, conta, empresa, sequencia, ult_data
                    FROM cs2.controle_banco
                    WHERE id = '9'";
    $qry_empresa       = mysql_query($sql_empresa,$conexao);
    $id_control_banco  = mysql_result($qry_empresa,0,'id');
    $banco             = mysql_result($qry_empresa,0,'banco');
    $banco             = str_pad($banco,3,0,STR_PAD_LEFT);  
    $agencia           = mysql_result($qry_empresa,0,'agencia');
    $agencia           = str_replace('-','',$agencia);
    $agencia           = str_pad($agencia,5,0,STR_PAD_LEFT);
    $conta             = mysql_result($qry_empresa,0,'conta');
    $conta             = str_replace('-','',$conta);
    $conta             = str_pad($conta,12,0,STR_PAD_LEFT);

    $empresa           = mysql_result($qry_empresa,0,'empresa');
    $sequencia_lote    = mysql_result($qry_empresa,0,'sequencia');
    $sequencia_lote++;

    $ult_data          = mysql_result($qry_empresa,0,'ult_data');   

    $totlinha = 0;
    $qtd_registro = 0;

    # REGISTRO HEADER

    $header  = '0';                                                 // tipo de registro id registro header 001 001 9(01) 
    $header .= 1;                                                   // operacao tipo operacao remessa 002 002 9(01)
    $header .= 'REMESSA';                                           // literal remessa escr. extenso 003 009 X(07)
    $header .= '01';                                                // codigo servico id tipo servico 010 011 9(02)
    $header .= 'COBRANCA       ';                                   // literal cobranca escr. extenso 012 026 X(15)
    $header .= str_pad('4972103',20,0,STR_PAD_LEFT);                // codigo da empresa 027 046 9(20)
    $header .= substr(str_pad($empresa,30,' ',STR_PAD_RIGHT),0,30); // nome da empresa 047 076 X(30)
    $header .= '237';                                               // codigo banco No BANCO C�MARA COMP. 077 079 9(03)
    $header .= 'BRADESCO       ';                                   // nome do banco por ext. 080 094 X(15)
    $header .= $DATA['DIA'].$DATA['MES'].$DATA['ANO'];              // data geracao arquivo 095 100 9(06)
    $header .= str_pad('',8,' ',STR_PAD_LEFT);                      // zeros complemento d registro 101 108 9(8)
    $header .= 'MX';                                                // Identificacao do sistema 109 110 X(2)
    $qtd_registro++;
    $header .= str_pad($sequencia_lote,7,0,STR_PAD_LEFT); // Numero Sequencial de Remessa 111 117 9(7)
    $header .= str_pad('',277,' ',STR_PAD_LEFT);          // brancos complemento registro 118 394 X(277)
    $header .= str_pad($qtd_registro,6,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
    $header .= chr(13).chr(10);                           // essa e a quebra de linha

    // DADOS DOS TITULOS

    // Select dos Lancamentos

    $sql = "SELECT 
                a.codloja, a.emissao, a.vencimento, a.valor, a.numdoc, a.numboleto_bradesco, a.data_movimentacao,
                a.datapg, a.valorpg, a.origem_pgto, a.data_gravacao_lote, a.desconto, a.isento_juros,
                b.insc, UPPER(b.razaosoc) AS razaosoc, UPPER(b.end) AS end, b.numero, 
                UPPER(b.bairro) AS bairro, UPPER(b.cidade) AS cidade, UPPER(b.uf) AS uf, b.cep,
                a.origem_pgto, a.num_lote,
                date_format( (a.vencimento + interval 1 day), '%d%m%Y' ) as venc_pos,
                a.banco_registro
            FROM cs2.titulos a
            INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
            WHERE 
                    a.banco_faturado = '237' 
                AND 
                    a.valor > 0
                AND
                    b.sitcli < 2
                AND 
                    ( a.origem_pgto is NULL OR a.origem_pgto = 'FRANQUIA' or a.origem_pgto  = 'COMP')
                AND
                    a.data_movimentacao > '$ult_data'
                ORDER by a.numdoc";
    
    $qry = mysql_query($sql,$conexao);
    $conteudo = '';
    $i=0;
    $array_titulos = '';
    while ( $rs = mysql_fetch_array($qry) ) {
        
        $i++;
        $codloja                = $rs['codloja'];
        $emissao                = $rs['emissao'];
        $dia_emis               = substr($emissao,8,2);
        $mes_emis               = substr($emissao,5,2);
        $ano_emis               = substr($emissao,2,2);
        
        $cpfcnpj_devedor        = $rs['insc'];
        if ( strlen($cpfcnpj_devedor) <= 11 ){
            $cpfcnpj_devedor = str_pad($cpfcnpj_devedor,11,0,STR_PAD_LEFT);
        }else{
            $cpfcnpj_devedor = str_pad($cpfcnpj_devedor,14,0,STR_PAD_LEFT);
        }
        
        $razaosoc               = $rs['razaosoc'];
        $endereco               = $rs['end'];
        $numero                 = $rs['numero'];
        $bairro                 = troca($rs['bairro']);
        $cidade                 = troca($rs['cidade']);
        $cep                    = trim($rs['cep']);
        $uf                     = trim($rs['uf']);
        $banco_registro         = trim($rs['banco_registro']);
        
        $end_completo  = $endereco;
        if ( trim($numero) <> '' ) $end_completo = $end_completo.', '.$numero;
        if ( trim($complemento) <> '' ) $end_completo = $end_completo.' '.$complemento;
        
        $end_completo  = troca($end_completo);
                
        $vencimento             = $rs['vencimento'];
        $num_lote               = $rs['num_lote'];
        $data_gravacao_lote     = $rs['data_gravacao_lote'];
        
        $dia_venc               = substr($vencimento,8,2);
        $mes_venc               = substr($vencimento,5,2);
        $ano_venc               = substr($vencimento,2,2);

        $valor_titulo_Original  = $rs['valor'];
        $valor_titulo           = $rs['valor'];
        $valor_titulo           = str_replace('.','',$valor_titulo);

        $numboleto_bradesco     = $rs['numboleto_bradesco'];
        $txjur                  = '4.5';
        $numdoc                 = $rs['numdoc'];
        $datapg                 = $rs['datapg'];
        $valorpg                = $rs['valorpg'];
        $desconto               = $rs['desconto'];
        $origem_pgto            = $rs['origem_pgto'];
        $isento_juros           = $rs['isento_juros'];
        $array_titulos         .=$numboleto_bradesco.',';
        
        // Verificando cada um dos titulos listados no SQL acima o motivo de terem sidos incluidos.

        if ( $num_lote == '' && $datapg == '' ){ // BOLETO NOVO
        
            $registro_trailler  = 1;                                   // Tipo registro id registro transacao.                           001 001  9(01)
            $registro_trailler .= '00000';                             // Codigo da Agencia do Pagador Exclusivo para Debito em Conta    002 006  X(05)
            $registro_trailler .= ' ';                                 // Digito da Agencia do Pagador                                   007 007  9(01)
            $registro_trailler .= '00000';                             // Razao da Conta do Pagador Vide                                 008 012  X(05)
            $registro_trailler .= '0000000';                           // Numero da Conta do Pagadora                                    013 019  X(07)
            $registro_trailler .= ' ';                                 // Dugito da Conta do Pagador                                     020 020  9(01)
            $registro_trailler .= '0'.'009'.'03451'.'00240370';        // Zero, Carteira, Agencia e Conta Corrente                       021 037  X(17)

            // Com codigo escritural mando com a Carteira  09 - Nova 
            $sequencia_lotex = str_pad($sequencia_lote,2,' ',STR_PAD_RIGHT);
            $registro_trailler .= str_pad('REGTIT'.$sequencia_lotex.$numdoc,25,' ',STR_PAD_RIGHT);  // Uso da Empresa - No Controle do Participante                   038 062  X(25)
            $registro_trailler .= '000';                                    // Codigo do Banco a ser debitado na Camara de Compensa��o        063 065  9(03)

            if ( $isento_juros == 'N'){
                $registro_trailler .= '2';                                      // Campo de Multa - Se = 2 considerar percentual de multa. Se = 0, sem multa   066 066  9(01)
                $registro_trailler .= '0200';                                   // Percentual de multa 2%                                                      067 070  9(04)  ??????????
            }else{
                $registro_trailler .= '0';                                      // Campo de Multa - Se = 2 considerar percentual de multa. Se = 0, sem multa   066 066  9(01)
                $registro_trailler .= '0000';                                   // Percentual de multa 0%                                                      067 070  9(04)  ??????????
            }
            $numboleto_bradesco = str_pad($numboleto_bradesco,11,0,STR_PAD_LEFT);

            $registro_trailler .= $numboleto_bradesco; // Numero Bancario para Cobranca                                         071 081  9(11)

            $dv_numboleto =  digitoVerificador_nossonumero( '09'.$numboleto_bradesco );

            $registro_trailler .= $dv_numboleto;                               // Digito N/N                                                            082 082  9(01)
            $registro_trailler .= str_pad('',10,0,STR_PAD_LEFT);               // Desconto Bonificacao por dia                                          083 092  9(10)
            $registro_trailler .= '2';                                         // Condicao para Emissao - 1 = Banco   2 = Cliente                       093 093  9(01)
            $registro_trailler .= 'N';                                         // Ident. se emite Boleto para Debito Automatico                         094 094  X(01)
            $registro_trailler .= str_pad('',10,' ',STR_PAD_RIGHT);            // Brancos                                                               095 104  X(10)
            $registro_trailler .= ' ';                                         // Identificador Rateio de credito                                       105 105  X(01)
            $registro_trailler .= '2';                                         // Enderecamento para Aviso do Debito Automatico em Conta Corrente       106 106  9(01)
            $registro_trailler .= str_pad('',2,' ',STR_PAD_RIGHT);             // Brancos                                                               107 108  X(02)

            // Identificacao                     109 110 X(02)
            // 01 - Remessa
            $registro_trailler .= '01';
            
            $registro_trailler .= str_pad(substr($numdoc,0,10)*1,10,0,STR_PAD_LEFT); // Numero do documento 111 120 X(10)
            $registro_trailler .= $dia_venc.$mes_venc.$ano_venc;                    // Vencimento do Titulo 121 126 N(6)
            $registro_trailler .= str_pad($valor_titulo,13,0,STR_PAD_LEFT);         // Valor do documento 127 139 N(13)
            $registro_trailler .= '000';                                            // Banco encarregado pela cobranca 140 142 N(03)        
            $registro_trailler .= '00000';                                          // Agencia depositaria 143 147 N(05)        
            $registro_trailler .= '99';                                             // especie de documento 148 149 9(02)
            $registro_trailler .= 'N';                                              // identificacao 150 150 9(01)
            $registro_trailler .= $dia_emis.$mes_emis.$ano_emis;                    // data emissao titulo NOTA 31 151 156 9(06)
            $registro_trailler .= '00';                                             // primeira instrucao 157 158 9(02)
            $registro_trailler .= '00';                                             // segunda instrucao 159 160 9(02)

            $tx_jur_dia = ( ($valor_titulo_Original * $txjur) / 100 ) / 30;
            $tx_jur_dia = number_format($tx_jur_dia,2,'.',',');
            $tx_jur_dia = str_replace(',','',$tx_jur_dia);
            $tx_jur_dia = str_replace('.','',$tx_jur_dia);

            // Valor de mora a ser cobrado por dia de atraso 161 173 9(11)V9(02)
            if( $isento_juros == 'N')
                $registro_trailler .= str_pad($tx_jur_dia,13,0,STR_PAD_LEFT);  // NAO ISENTA JUROS          
            else
                $registro_trailler .= str_pad('',13,0,STR_PAD_LEFT);  // SIM - ISENTA JUROS

            $registro_trailler .= str_pad('',6,0,STR_PAD_LEFT);                 // Data limite para concessao de desconto 174 179 9(06)
            $registro_trailler .= str_pad('',13,0,STR_PAD_LEFT);         // valor desconto a ser concedido NOTA 13 180 192 9(11)V9(02)

            $registro_trailler .= '0000000000000';                                  // valor I.O.F RECOLHIDO P NOTAS SEGURO NOTA 14 193 205 9(11)V9(02)

            // ABATIMENTO
            if ( $desconto_altera > 0 ){
                $desconto_altera    = str_replace('.','',$desconto_altera);
                $registro_trailler .= str_pad($desconto_altera,13,0,STR_PAD_LEFT); // abatimento a ser concedido NOTA 13 206 218 9(11)V9(02)
            }else{
                // Nao tem abatimento
                $registro_trailler .= '0000000000000';                            // abatimento a ser concedido NOTA 13 206 218 9(11)V9(02)
            }
            if ( strlen($cpfcnpj_devedor) <= 11 ) // CPF
                $registro_trailler .= '01'; // tipo inscricao sacado 219 220 9(02)
            else // CNPJ
                $registro_trailler .= '02'; // tipo inscricao sacado 219 220 9(02)

            $registro_trailler .= str_pad($cpfcnpj_devedor,14,0,STR_PAD_LEFT);  // numero de inscricao cpf ou cnpj 221 234 9(14)
            $registro_trailler .= substr(str_pad($razaosoc,40,' ',STR_PAD_RIGHT),0,40); // nome nome do sacado NOTA 15 235 274 X(40)
            $registro_trailler .= substr(str_pad($end_completo,40,' ',STR_PAD_RIGHT),0,40); // logradouro rua numero e compl sacado 275 314 X(40)
            $registro_trailler .= substr(str_pad('',12,' ',STR_PAD_RIGHT),0,40); // 1a. Mensagem 315 326 X(12)
            $registro_trailler .= str_pad($cep,8,0,STR_PAD_LEFT); // cep do sacado 327 334 X(8)
            $registro_trailler .= str_pad('',60,' ',STR_PAD_LEFT);             // sacador/avalista ou 2a. mensagem 335 394 X(60)

            $qtd_registro++;

            $registro_trailler .= str_pad($qtd_registro,6,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
            $registro_trailler .= chr(13).chr(10);                           // essa � a quebra de linha

            if ( $banco_registro == '' )
                $conteudo .= $registro_trailler;
            
        }
        else{
            
            // NAO EH BOLETO NOVO
            // AGORA VEREI O QUE ACONTECEU COM OS TITULOS PARA ESTAREM NA LISTA
            
            // ANALISANDO SE O BOLETO FOI PAGO POR RECEBIMENTO FRANQUIA OU COMPENSACAO
            
            if ( $origem_pgto == 'FRANQUIA' or $origem_pgto == 'COMP'){
                
                $registro_trailler  = 1;                                   // Tipo registro id registro transacao.                           001 001  9(01)
                $registro_trailler .= '00000';                             // Codigo da Agencia do Pagador Exclusivo para Debito em Conta    002 006  X(05)
                $registro_trailler .= ' ';                                 // Digito da Agencia do Pagador                                   007 007  9(01)
                $registro_trailler .= '00000';                             // Razao da Conta do Pagador Vide                                 008 012  X(05)
                $registro_trailler .= '0000000';                           // Numero da Conta do Pagadora                                    013 019  X(07)
                $registro_trailler .= ' ';                                 // Dugito da Conta do Pagador                                     020 020  9(01)
                $registro_trailler .= '0'.'009'.'03451'.'00240370';        // Zero, Carteira, Agencia e Conta Corrente                       021 037  X(17)

                // Com codigo escritural mando com a Carteira  09 - Nova 
                $sequencia_lotex = str_pad($sequencia_lote,2,' ',STR_PAD_RIGHT);
                $registro_trailler .= str_pad('REGTIT'.$sequencia_lotex.$numdoc,25,' ',STR_PAD_RIGHT);  // Uso da Empresa - No Controle do Participante                   038 062  X(25)
                $registro_trailler .= '000';                                    // Codigo do Banco a ser debitado na Camara de Compensa��o        063 065  9(03)

                if ( $isento_juros == 'N'){
                    $registro_trailler .= '2';                                      // Campo de Multa - Se = 2 considerar percentual de multa. Se = 0, sem multa   066 066  9(01)
                    $registro_trailler .= '0200';                                   // Percentual de multa 2%                                                      067 070  9(04)  ??????????
                }else{
                    $registro_trailler .= '0';                                      // Campo de Multa - Se = 2 considerar percentual de multa. Se = 0, sem multa   066 066  9(01)
                    $registro_trailler .= '0000';                                   // Percentual de multa 0%                                                      067 070  9(04)  ??????????
                }
                $numboleto_bradesco = str_pad($numboleto_bradesco,11,0,STR_PAD_LEFT);

                $registro_trailler .= $numboleto_bradesco; // Numero Bancario para Cobranca                                         071 081  9(11)

                $dv_numboleto =  digitoVerificador_nossonumero( '09'.$numboleto_bradesco );

                $registro_trailler .= $dv_numboleto;                               // Digito N/N                                                            082 082  9(01)
                $registro_trailler .= str_pad('',10,0,STR_PAD_LEFT);               // Desconto Bonificacao por dia                                          083 092  9(10)
                $registro_trailler .= '2';                                         // Condicao para Emissao - 1 = Banco   2 = Cliente                       093 093  9(01)
                $registro_trailler .= 'N';                                         // Ident. se emite Boleto para Debito Automatico                         094 094  X(01)
                $registro_trailler .= str_pad('',10,' ',STR_PAD_RIGHT);            // Brancos                                                               095 104  X(10)
                $registro_trailler .= ' ';                                         // Identificador Rateio de credito                                       105 105  X(01)
                $registro_trailler .= '2';                                         // Enderecamento para Aviso do Debito Automatico em Conta Corrente       106 106  9(01)
                $registro_trailler .= str_pad('',2,' ',STR_PAD_RIGHT);             // Brancos                                                               107 108  X(02)

                // Identificacao                     109 110 X(02)
                // 01 - Remessa
                // 02 - Pedido de Baixa
                // 04 - Descontos / Abatimentos
                // 06 - Alteracao de vencimento
                $registro_trailler .= '02';
            
                $registro_trailler .= str_pad(substr($numdoc,0,10)*1,10,0,STR_PAD_LEFT); // Numero do documento 111 120 X(10)
                $registro_trailler .= $dia_venc.$mes_venc.$ano_venc;                    // Vencimento do Titulo 121 126 N(6)
                $registro_trailler .= str_pad($valor_titulo,13,0,STR_PAD_LEFT);         // Valor do documento 127 139 N(13)
                $registro_trailler .= '000';                                            // Banco encarregado pela cobranca 140 142 N(03)        
                $registro_trailler .= '00000';                                          // Agencia depositaria 143 147 N(05)        
                $registro_trailler .= '99';                                             // especie de documento 148 149 9(02)
                $registro_trailler .= 'N';                                              // identificacao 150 150 9(01)
                $registro_trailler .= $dia_emis.$mes_emis.$ano_emis;                    // data emissao titulo NOTA 31 151 156 9(06)
                $registro_trailler .= '00';                                             // primeira instrucao 157 158 9(02)
                $registro_trailler .= '00';                                             // segunda instrucao 159 160 9(02)

                $tx_jur_dia = ( ($valor_titulo_Original * $txjur) / 100 ) / 30;
                $tx_jur_dia = number_format($tx_jur_dia,2,'.',',');
                $tx_jur_dia = str_replace(',','',$tx_jur_dia);
                $tx_jur_dia = str_replace('.','',$tx_jur_dia);

                // Valor de mora a ser cobrado por dia de atraso 161 173 9(11)V9(02)
                if( $isento_juros == 'N')
                    $registro_trailler .= str_pad($tx_jur_dia,13,0,STR_PAD_LEFT);  // NAO ISENTA JUROS          
                else
                    $registro_trailler .= str_pad('',13,0,STR_PAD_LEFT);  // SIM - ISENTA JUROS

                $registro_trailler .= str_pad('',6,0,STR_PAD_LEFT);                 // Data limite para concessao de desconto 174 179 9(06)
                $registro_trailler .= str_pad('',13,0,STR_PAD_LEFT);         // valor desconto a ser concedido NOTA 13 180 192 9(11)V9(02)

                $registro_trailler .= '0000000000000';                                  // valor I.O.F RECOLHIDO P NOTAS SEGURO NOTA 14 193 205 9(11)V9(02)

                // ABATIMENTO
                if ( $desconto_altera > 0 ){
                    $desconto_altera    = str_replace('.','',$desconto_altera);
                    $registro_trailler .= str_pad($desconto_altera,13,0,STR_PAD_LEFT); // abatimento a ser concedido NOTA 13 206 218 9(11)V9(02)
                }else{
                    // Nao tem abatimento
                    $registro_trailler .= '0000000000000';                            // abatimento a ser concedido NOTA 13 206 218 9(11)V9(02)
                }
                if ( strlen($cpfcnpj_devedor) <= 11 ) // CPF
                    $registro_trailler .= '01'; // tipo inscricao sacado 219 220 9(02)
                else // CNPJ
                    $registro_trailler .= '02'; // tipo inscricao sacado 219 220 9(02)

                $registro_trailler .= str_pad($cpfcnpj_devedor,14,0,STR_PAD_LEFT);  // numero de inscricao cpf ou cnpj 221 234 9(14)
                $registro_trailler .= substr(str_pad($razaosoc,40,' ',STR_PAD_RIGHT),0,40); // nome nome do sacado NOTA 15 235 274 X(40)
                $registro_trailler .= substr(str_pad($end_completo,40,' ',STR_PAD_RIGHT),0,40); // logradouro rua numero e compl sacado 275 314 X(40)
                $registro_trailler .= substr(str_pad('',12,' ',STR_PAD_RIGHT),0,40); // 1a. Mensagem 315 326 X(12)
                $registro_trailler .= str_pad($cep,8,0,STR_PAD_LEFT); // cep do sacado 327 334 X(8)
                $registro_trailler .= str_pad('',60,' ',STR_PAD_LEFT);             // sacador/avalista ou 2a. mensagem 335 394 X(60)

                $qtd_registro++;

                $registro_trailler .= str_pad($qtd_registro,6,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
                $registro_trailler .= chr(13).chr(10);                           // essa � a quebra de linha

                $conteudo .= $registro_trailler;


            }else{
                
                // VERIFICO SE ALTEROU O VENCIMENTO
                $sql_movimento = "SELECT vencimento FROM cs2.titulos_movimentacao
                                  WHERE 
                                     numdoc = '$numdoc' 
                                  AND 
                                     vencimento IS NOT NULL 
                                  AND 
                                     data_cadastro > '$ult_data'
                                  ORDER BY id DESC LIMIT 1";
                $qry_movimento = mysql_query($sql_movimento,$conexao);
                if (mysql_num_rows($qry_movimento) > 0 ){
                    while ( $reg = mysql_fetch_array($qry_movimento) ){
                        
                        // VENCIMENTO ALTERADO

                        $vencimento_alterado = $reg['vencimento'];
                        $dia_venc = substr($vencimento_alterado,8,2);
                        $mes_venc = substr($vencimento_alterado,5,2);
                        $ano_venc = substr($vencimento_alterado,2,2);
        
                        $registro_trailler  = 1;                                   // Tipo registro id registro transacao.                           001 001  9(01)
                        $registro_trailler .= '00000';                             // Codigo da Agencia do Pagador Exclusivo para Debito em Conta    002 006  X(05)
                        $registro_trailler .= ' ';                                 // Digito da Agencia do Pagador                                   007 007  9(01)
                        $registro_trailler .= '00000';                             // Razao da Conta do Pagador Vide                                 008 012  X(05)
                        $registro_trailler .= '0000000';                           // Numero da Conta do Pagadora                                    013 019  X(07)
                        $registro_trailler .= ' ';                                 // Dugito da Conta do Pagador                                     020 020  9(01)
                        $registro_trailler .= '0'.'009'.'03451'.'00240370';        // Zero, Carteira, Agencia e Conta Corrente                       021 037  X(17)

                        // Com codigo escritural mando com a Carteira  09 - Nova 
                        $sequencia_lotex = str_pad($sequencia_lote,2,' ',STR_PAD_RIGHT);
                        $registro_trailler .= str_pad('REGTIT'.$sequencia_lotex.$numdoc,25,' ',STR_PAD_RIGHT);  // Uso da Empresa - No Controle do Participante                   038 062  X(25)
                        $registro_trailler .= '000';                                    // Codigo do Banco a ser debitado na Camara de Compensa��o        063 065  9(03)

                        if ( $isento_juros == 'N'){
                            $registro_trailler .= '2';                                      // Campo de Multa - Se = 2 considerar percentual de multa. Se = 0, sem multa   066 066  9(01)
                            $registro_trailler .= '0200';                                   // Percentual de multa 2%                                                      067 070  9(04)  ??????????
                        }else{
                            $registro_trailler .= '0';                                      // Campo de Multa - Se = 2 considerar percentual de multa. Se = 0, sem multa   066 066  9(01)
                            $registro_trailler .= '0000';                                   // Percentual de multa 0%                                                      067 070  9(04)  ??????????
                        }
                        $numboleto_bradesco = str_pad($numboleto_bradesco,11,0,STR_PAD_LEFT);

                        $registro_trailler .= $numboleto_bradesco; // Numero Bancario para Cobranca                                         071 081  9(11)

                        $dv_numboleto =  digitoVerificador_nossonumero( '09'.$numboleto_bradesco );

                        $registro_trailler .= $dv_numboleto;                               // Digito N/N                                                            082 082  9(01)
                        $registro_trailler .= str_pad('',10,0,STR_PAD_LEFT);               // Desconto Bonificacao por dia                                          083 092  9(10)
                        $registro_trailler .= '2';                                         // Condicao para Emissao - 1 = Banco   2 = Cliente                       093 093  9(01)
                        $registro_trailler .= 'N';                                         // Ident. se emite Boleto para Debito Automatico                         094 094  X(01)
                        $registro_trailler .= str_pad('',10,' ',STR_PAD_RIGHT);            // Brancos                                                               095 104  X(10)
                        $registro_trailler .= ' ';                                         // Identificador Rateio de credito                                       105 105  X(01)
                        $registro_trailler .= '2';                                         // Enderecamento para Aviso do Debito Automatico em Conta Corrente       106 106  9(01)
                        $registro_trailler .= str_pad('',2,' ',STR_PAD_RIGHT);             // Brancos                                                               107 108  X(02)

                        // Identificacao                     109 110 X(02)
                        // 01 - Remessa
                        // 02 - Pedido de Baixa
                        // 04 - Descontos / Abatimentos
                        // 06 - Alteracao de vencimento
                        $registro_trailler .= '06';

                        $registro_trailler .= str_pad(substr($numdoc,0,10)*1,10,0,STR_PAD_LEFT); // Numero do documento 111 120 X(10)
                        $registro_trailler .= $dia_venc.$mes_venc.$ano_venc;                    // Vencimento do Titulo 121 126 N(6)
                        $registro_trailler .= str_pad($valor_titulo,13,0,STR_PAD_LEFT);         // Valor do documento 127 139 N(13)
                        $registro_trailler .= '000';                                            // Banco encarregado pela cobranca 140 142 N(03)        
                        $registro_trailler .= '00000';                                          // Agencia depositaria 143 147 N(05)        
                        $registro_trailler .= '99';                                             // especie de documento 148 149 9(02)
                        $registro_trailler .= 'N';                                              // identificacao 150 150 9(01)
                        $registro_trailler .= $dia_emis.$mes_emis.$ano_emis;                    // data emissao titulo NOTA 31 151 156 9(06)
                        $registro_trailler .= '00';                                             // primeira instrucao 157 158 9(02)
                        $registro_trailler .= '00';                                             // segunda instrucao 159 160 9(02)

                        $tx_jur_dia = ( ($valor_titulo_Original * $txjur) / 100 ) / 30;
                        $tx_jur_dia = number_format($tx_jur_dia,2,'.',',');
                        $tx_jur_dia = str_replace(',','',$tx_jur_dia);
                        $tx_jur_dia = str_replace('.','',$tx_jur_dia);

                        // Valor de mora a ser cobrado por dia de atraso 161 173 9(11)V9(02)
                        if( $isento_juros == 'N')
                            $registro_trailler .= str_pad($tx_jur_dia,13,0,STR_PAD_LEFT); // NAO ISENTA JUROS          
                        else
                            $registro_trailler .= str_pad('',13,0,STR_PAD_LEFT);          // SIM - ISENTA JUROS

                        $registro_trailler .= str_pad('',6,0,STR_PAD_LEFT);               // Data limite para concessao de desconto 174 179 9(06)
                        $registro_trailler .= str_pad('',13,0,STR_PAD_LEFT);              // valor desconto a ser concedido NOTA 13 180 192 9(11)V9(02)

                        $registro_trailler .= '0000000000000';                            // valor I.O.F RECOLHIDO P NOTAS SEGURO NOTA 14 193 205 9(11)V9(02)
                        $registro_trailler .= '0000000000000';                            // abatimento a ser concedido NOTA 13 206 218 9(11)V9(02)

                        if ( strlen($cpfcnpj_devedor) <= 11 ) // CPF
                            $registro_trailler .= '01'; // tipo inscricao sacado 219 220 9(02)
                        else // CNPJ
                            $registro_trailler .= '02'; // tipo inscricao sacado 219 220 9(02)

                        $registro_trailler .= str_pad($cpfcnpj_devedor,14,0,STR_PAD_LEFT);  // numero de inscricao cpf ou cnpj 221 234 9(14)
                        $registro_trailler .= substr(str_pad($razaosoc,40,' ',STR_PAD_RIGHT),0,40); // nome nome do sacado NOTA 15 235 274 X(40)
                        $registro_trailler .= substr(str_pad($end_completo,40,' ',STR_PAD_RIGHT),0,40); // logradouro rua numero e compl sacado 275 314 X(40)
                        $registro_trailler .= substr(str_pad('',12,' ',STR_PAD_RIGHT),0,40); // 1a. Mensagem 315 326 X(12)
                        $registro_trailler .= str_pad($cep,8,0,STR_PAD_LEFT); // cep do sacado 327 334 X(8)
                        $registro_trailler .= str_pad('',60,' ',STR_PAD_LEFT);             // sacador/avalista ou 2a. mensagem 335 394 X(60)

                        $qtd_registro++;

                        $registro_trailler .= str_pad($qtd_registro,6,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
                        $registro_trailler .= chr(13).chr(10);                           // essa � a quebra de linha

                        $conteudo .= $registro_trailler;

                    }
                }
                
                // VERIFICANDO SE O TITULO TEVE DESCONTO
                $sql_movimentox = "SELECT SUM(desconto) as desconto FROM cs2.titulos_movimentacao
                                  WHERE numdoc = '$numdoc' AND data_cadastro > '$ult_data'";
                $qry_movimentox = mysql_query($sql_movimentox,$conexao);
                while ( $regx = mysql_fetch_array($qry_movimentox) ) {    
                        
                    // DESCONTO CONCEDIDO
                    $desconto_concedido = $regx['desconto'];
                    if ( $desconto_concedido > 0 ){

                        $registro_trailler  = 1;                                   // Tipo registro id registro transacao.                           001 001  9(01)
                        $registro_trailler .= '00000';                             // Codigo da Agencia do Pagador Exclusivo para Debito em Conta    002 006  X(05)
                        $registro_trailler .= ' ';                                 // Digito da Agencia do Pagador                                   007 007  9(01)
                        $registro_trailler .= '00000';                             // Razao da Conta do Pagador Vide                                 008 012  X(05)
                        $registro_trailler .= '0000000';                           // Numero da Conta do Pagadora                                    013 019  X(07)
                        $registro_trailler .= ' ';                                 // Dugito da Conta do Pagador                                     020 020  9(01)
                        $registro_trailler .= '0'.'009'.'03451'.'00240370';        // Zero, Carteira, Agencia e Conta Corrente                       021 037  X(17)

                        // Com codigo escritural mando com a Carteira  09 - Nova 
                        $sequencia_lotex = str_pad($sequencia_lote,2,' ',STR_PAD_RIGHT);
                        $registro_trailler .= str_pad('REGTIT'.$sequencia_lotex.$numdoc,25,' ',STR_PAD_RIGHT);  // Uso da Empresa - No Controle do Participante                   038 062  X(25)
                        $registro_trailler .= '000';                                    // Codigo do Banco a ser debitado na Camara de Compensa��o        063 065  9(03)

                        if ( $isento_juros == 'N'){
                            $registro_trailler .= '2';                                      // Campo de Multa - Se = 2 considerar percentual de multa. Se = 0, sem multa   066 066  9(01)
                            $registro_trailler .= '0200';                                   // Percentual de multa 2%                                                      067 070  9(04)  ??????????
                        }else{
                            $registro_trailler .= '0';                                      // Campo de Multa - Se = 2 considerar percentual de multa. Se = 0, sem multa   066 066  9(01)
                            $registro_trailler .= '0000';                                   // Percentual de multa 0%                                                      067 070  9(04)  ??????????
                        }
                        $numboleto_bradesco = str_pad($numboleto_bradesco,11,0,STR_PAD_LEFT);

                        $registro_trailler .= $numboleto_bradesco; // Numero Bancario para Cobranca                                         071 081  9(11)

                        $dv_numboleto =  digitoVerificador_nossonumero( '09'.$numboleto_bradesco );

                        $registro_trailler .= $dv_numboleto;                               // Digito N/N                                                            082 082  9(01)
                        $registro_trailler .= str_pad('',10,0,STR_PAD_LEFT);               // Desconto Bonificacao por dia                                          083 092  9(10)
                        $registro_trailler .= '2';                                         // Condicao para Emissao - 1 = Banco   2 = Cliente                       093 093  9(01)
                        $registro_trailler .= 'N';                                         // Ident. se emite Boleto para Debito Automatico                         094 094  X(01)
                        $registro_trailler .= str_pad('',10,' ',STR_PAD_RIGHT);            // Brancos                                                               095 104  X(10)
                        $registro_trailler .= ' ';                                         // Identificador Rateio de credito                                       105 105  X(01)
                        $registro_trailler .= '2';                                         // Enderecamento para Aviso do Debito Automatico em Conta Corrente       106 106  9(01)
                        $registro_trailler .= str_pad('',2,' ',STR_PAD_RIGHT);             // Brancos                                                               107 108  X(02)

                        // Identificacao                     109 110 X(02)
                        // 01 - Remessa
                        // 02 - Pedido de Baixa
                        // 04 - Descontos / Abatimentos
                        // 06 - Alteracao de vencimento
                        $registro_trailler .= '04';

                        $registro_trailler .= str_pad(substr($numdoc,0,10)*1,10,0,STR_PAD_LEFT); // Numero do documento 111 120 X(10)
                        $registro_trailler .= $dia_venc.$mes_venc.$ano_venc;                    // Vencimento do Titulo 121 126 N(6)
                        $registro_trailler .= str_pad($valor_titulo,13,0,STR_PAD_LEFT);         // Valor do documento 127 139 N(13)
                        $registro_trailler .= '000';                                            // Banco encarregado pela cobranca 140 142 N(03)        
                        $registro_trailler .= '00000';                                          // Agencia depositaria 143 147 N(05)        
                        $registro_trailler .= '99';                                             // especie de documento 148 149 9(02)
                        $registro_trailler .= 'N';                                              // identificacao 150 150 9(01)
                        $registro_trailler .= $dia_emis.$mes_emis.$ano_emis;                    // data emissao titulo NOTA 31 151 156 9(06)
                        $registro_trailler .= '00';                                             // primeira instrucao 157 158 9(02)
                        $registro_trailler .= '00';                                             // segunda instrucao 159 160 9(02)

                        $tx_jur_dia = ( ($valor_titulo_Original * $txjur) / 100 ) / 30;
                        $tx_jur_dia = number_format($tx_jur_dia,2,'.',',');
                        $tx_jur_dia = str_replace(',','',$tx_jur_dia);
                        $tx_jur_dia = str_replace('.','',$tx_jur_dia);

                        // Valor de mora a ser cobrado por dia de atraso 161 173 9(11)V9(02)
                        if( $isento_juros == 'N')
                            $registro_trailler .= str_pad($tx_jur_dia,13,0,STR_PAD_LEFT); // NAO ISENTA JUROS          
                        else
                            $registro_trailler .= str_pad('',13,0,STR_PAD_LEFT);          // SIM - ISENTA JUROS

                        $registro_trailler .= str_pad('',6,0,STR_PAD_LEFT);               // Data limite para concessao de desconto 174 179 9(06)
                        $registro_trailler .= str_pad('',13,0,STR_PAD_LEFT);              // valor desconto a ser concedido NOTA 13 180 192 9(11)V9(02)

                        $registro_trailler .= '0000000000000';                            // valor I.O.F RECOLHIDO P NOTAS SEGURO NOTA 14 193 205 9(11)V9(02)
                        
                        $desconto_concedido    = str_replace('.','',$desconto_concedido);
                        $registro_trailler .= str_pad($desconto_concedido,13,0,STR_PAD_LEFT); // abatimento a ser concedido NOTA 13 206 218 9(11)V9(02)

                        if ( strlen($cpfcnpj_devedor) <= 11 ) // CPF
                            $registro_trailler .= '01'; // tipo inscricao sacado 219 220 9(02)
                        else // CNPJ
                            $registro_trailler .= '02'; // tipo inscricao sacado 219 220 9(02)

                        $registro_trailler .= str_pad($cpfcnpj_devedor,14,0,STR_PAD_LEFT);  // numero de inscricao cpf ou cnpj 221 234 9(14)
                        $registro_trailler .= substr(str_pad($razaosoc,40,' ',STR_PAD_RIGHT),0,40); // nome nome do sacado NOTA 15 235 274 X(40)
                        $registro_trailler .= substr(str_pad($end_completo,40,' ',STR_PAD_RIGHT),0,40); // logradouro rua numero e compl sacado 275 314 X(40)
                        $registro_trailler .= substr(str_pad('',12,' ',STR_PAD_RIGHT),0,40); // 1a. Mensagem 315 326 X(12)
                        $registro_trailler .= str_pad($cep,8,0,STR_PAD_LEFT); // cep do sacado 327 334 X(8)
                        $registro_trailler .= str_pad('',60,' ',STR_PAD_LEFT);             // sacador/avalista ou 2a. mensagem 335 394 X(60)

                        $qtd_registro++;

                        $registro_trailler .= str_pad($qtd_registro,6,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
                        $registro_trailler .= chr(13).chr(10);                           // essa � a quebra de linha

                        $conteudo .= $registro_trailler;
                        $valor_total_titulos   += $rs['valor'];
                    }
                }
            }
        }
    }
    # REGISTRO TRAILLER DO LOTE 0003
    $trailler = '9';                                        // 001 a 001 - Identificacao do Registro
    $trailler .= str_pad('',393,' ',STR_PAD_LEFT);          // 002 a 394 - Branco
    $qtd_registro++;
    $trailler .= str_pad($qtd_registro,6,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)

    $registros = $header.$conteudo.$trailler;

    try{
        
        if( $processado_nexxera ) {
            
            $origem_arquivo = 'NEXXERA';
            $data = date('dmy');
            
            if ( $array_titulos == '' ){
                
                // NAO Foi gerado nenhum registro... Abortando o processo
                echo "NENHUM TITULO PARA SER REGISTRADO... ".date('d/m/Y H:i:s');
                die;
                
            }

            $dat = date('Y-m-d');
            $sql_seq_nexxera = "SELECT sequencia FROM cs2.titulos_controle_nexxera 
                                WHERE data = '$dat' AND empresa = 'WC'";
            $qry_seq_nexxera = mysql_query($sql_seq_nexxera,$conexao);
            if (mysql_num_rows( $qry_seq_nexxera ) > 0 ){
                
                $sequencia_nexxera_dia = mysql_result($qry_seq_nexxera, 0, 'sequencia' ) + 1;
                $sql_seq_nexxera = "UPDATE cs2.titulos_controle_nexxera 
                                      SET
                                        sequencia = $sequencia_nexxera_dia
                                    WHERE data = '$dat' AND empresa = 'WC'";
                $qry_seq_nexxera = mysql_query($sql_seq_nexxera,$conexao);
                
            }else{
                
                $sequencia_nexxera_dia = 1;
                $sql_seq_nexxera = "INSERT INTO cs2.titulos_controle_nexxera(data,sequencia,empresa)
                                    VALUES('$dat','$sequencia_nexxera_dia','WC')";
                $qry_seq_nexxera = mysql_query($sql_seq_nexxera,$conexao);
                
            }
            $arquivo = "/home/skyunix/outbox/cob237_4972103_".$data."_".str_pad($sequencia_nexxera_dia,2,0,STR_PAD_LEFT).".rem";
        }
        else{
            $origem_arquivo = 'WEB';
            $arquivo = "/var/www/html/download/MB".$DATA['DIA'].$DATA['MES'].str_pad($sequencia_lote,2,0,STR_PAD_LEFT);
            $arq = "MB".$DATA['DIA'].$DATA['MES'].str_pad($sequencia_lote,2,0,STR_PAD_LEFT);
        }
        
        $abrir = fopen($arquivo, "w");
        $escreve = fputs($abrir, $registros);
        fclose($abrir);
        
    } catch (Exception $e) {
        echo 'Erro ao gravar o arquivo: ',  $e->getMessage(), "\n";
        exit;
    }

    if ( $tot_pagar == '' ) $tot_pagar = '0.00';
    
    $xtotal_geral = substr($tot_pagar,0,strlen($tot_pagar)-2).'.'.substr($tot_pagar,strlen($tot_pagar)-2,2);

    $total_geral = number_format($xtotal_geral,2,',','.');
    
    //Remove ultima virgula
    $array_titulos = substr($array_titulos,0,-1);

    if( $processado_nexxera ) {

        # Gravando o sequencial de registro de pagamento 
        $sql_registro = "UPDATE cs2.controle_banco 
                                SET 
                                    sequencia = '$sequencia_lote',
                                    ult_data = '$data_hora_final'
                             WHERE id = '9'";
        $qry_registro = mysql_query($sql_registro,$conexao);
        
        // Atualizando os registros, indica que o arquivo foi processado e enviado a nexxera
        
        $sql_grava_data_envio_lote = 
                   "UPDATE cs2.titulos
                      SET
                        data_gravacao_lote = '$data_hora_final'
                    WHERE numboleto_bradesco IN ($array_titulos)";
        $qry_grava = mysql_query($sql_grava_data_envio_lote,$conexao);
        
        // Atualizando os registros, indica que o arquivo foi processado e enviado a 
        $dados = "numero_boletos=$array_titulos&sequencia_lote=$sequencia_lote&data_limite=$data_hora_final&id_controle_banco=$id_control_banco&tabela=mensalidade";
        // Gera a url para fins de verificacao
        $url = 'https://www.webcontrolempresas.com.br/franquias/php/area_restrita/d_grava_lote_titulos_registrados.php?' . $dados;

        // Gravando as informacoes para visualizacao para pagina de franquia

        $registros = str_replace("'",'',$registros);
        $registros = str_replace("`",'',$registros);
        
        $sql_grava = "INSERT INTO cs2.titulos_processamento_nexxera
                        (data_processamento,hora_processamento,nome_arquivo_processado,registros_arquivo,texto_processamento,banco,envio_retorno,empresa,link_confirmacao_registro_tabela)
                      VALUES
                        ( NOW(), NOW(), '$arquivo', '$registros', '$result', '237', 'E', 'WC','$url')
                      ";
        $qry_grava = mysql_query($sql_grava,$conexao);

        shell_exec("cp $arquivo /var/www/html/franquias/php/area_restrita/nexxera/envio/");

        
        // monitorando comando SQL
        
        $sql_grava = "INSERT INTO cs2.titulos_processamento_nexxera_sql
                        (data_processamento,hora_processamento,comando_sql)
                      VALUES
                        ( NOW(), NOW(), '$sql_grava_data_envio_lote')
                      ";
        $qry_grava = mysql_query($sql_grava,$conexao);


        die;
        
    }
    
    echo "    Arquivo Gerado com sucesso  
              Arquivo:  <a href='#' onClick=\"window.open('https://www.webcontrolempresas.com.br/download/$arq','_blank')\">$arq</a>
              <br>
              <hr>
              Confirmar a grava&ccedil;&atilde;o deste Arquivo gerado at&eacute; as : $data_hora_final<br>
              <br>
              <input type='button' name='confirmar' value=' CONFIRMAR ' OnClick= \"Grava_Dados('$array_titulos' ,'$sequencia_lote','$data_hora_final','$id_control_banco')\" >
         ";
    

?>

<script type="text/javascript" src="../js/jquery.js"></script>
<script language="JavaScript" src="../js/jquery.meio.mask.js" type="text/javascript"></script>
<script>
    function Grava_Dados(par1,par2,par3,par4){
        
        $.ajax({

            url: "d_grava_lote_titulos_registrados.php",
            data: {
                numero_boletos: par1
                , sequencia_lote: par2
                , data_limite: par3
                , id_controle_banco: par4
                , tabela: 'mensalidade'
            },
            type: "POST",
            success: function (res) {
                
                if ( res = 'OK'){
                    alert( 'Registro Gravado com Sucesso.');
                }

            }
        });
    }
</script>
