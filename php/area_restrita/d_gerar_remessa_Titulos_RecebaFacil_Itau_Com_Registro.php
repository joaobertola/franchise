<?php

    # Autor:   Luciano Mancini
    # Modulo:  Remessa Titulos RecebaFacil - Banco Bradesco
    # Finalidade: 
    #		Gerar o arquivo de titulos a serem enviados ao Banco Bradesco 
    #		para REGISTRO

    include("../../../validar2.php");

    global $conexao,$arquivo;
    conecex();

    $timestamp = mktime(date("H") - $fusohorario, date("i"), date("s"), date("m"), date("d"), date("Y"));
    $DATA['DIA'] = gmdate("d",$timestamp);
    $DATA['MES'] = gmdate("m",$timestamp);
    $DATA['ANO'] = gmdate("y",$timestamp);

    $data_hora_final = date('Y-m-d H:i:s');
    
    // Buscando no numero do LOTE do Banco
    // Selecionando a Empresa que ira Registrar os Titulos ( ISPCN )
    $sql_empresa = "SELECT id, banco, agencia, conta, empresa, sequencia, ult_data
                    FROM cs2.controle_banco
                    WHERE id = '6'";
    $qry_empresa       = mysql_query($sql_empresa,$conexao);
    $id_control_banco  = mysql_result($qry_empresa,0,'id');
    $banco             = mysql_result($qry_empresa,0,'banco');
    $banco             = str_pad($banco,3,0,STR_PAD_LEFT);	
    $agencia           = mysql_result($qry_empresa,0,'agencia');
    $agencia           = str_replace('-','',$agencia);
    $conta             = mysql_result($qry_empresa,0,'conta');
    $conta             = str_replace('-','',$conta);
    
    $dv_conta          = substr($conta,-1);
    $conta             = substr($conta,0,strlen($conta)-1);

    $empresa           = mysql_result($qry_empresa,0,'empresa');
    $sequencia_lote    = mysql_result($qry_empresa,0,'sequencia');
    $sequencia_lote++;

    $ult_data = mysql_result($qry_empresa,0,'ult_data');	

    $totlinha = 0;
    $qtd_registro = 0;

    # REGISTRO HEADER

    $header  = '0';                                    // tipo de registro id registro header 001 001 9(01) 
    $header .= 1;                                      // operacao tipo operacao remessa 002 002 9(01)
    $header .= 'REMESSA';                              // literal remessa escr. extenso 003 009 X(07)
    $header .= '01';                                   // codigo servico id tipo servico 010 011 9(02)
    $header .= 'COBRANCA       ';                      // literal cobranca escr. extenso 012 026 X(15)
    $header .= str_pad($agencia,4,0,STR_PAD_LEFT);     // codigo da empresa 027 046 9(20)
    $header .= '00';                                   // codigo da empresa 027 046 9(20)
    $header .= str_pad($conta,5,0,STR_PAD_LEFT);       // codigo da empresa 027 046 9(20)
    $header .= str_pad($dv_conta,1,0,STR_PAD_LEFT);    // codigo da empresa 027 046 9(20)
    $header .= str_pad('',8,' ',STR_PAD_RIGHT);   // nome da empresa 047 076 X(30)
    
    $header .= substr(str_pad($empresa,30,' ',STR_PAD_RIGHT),0,30);   // nome da empresa 047 076 X(30)
    $header .= '341';                                   // codigo banco N� BANCO C�MARA COMP. 077 079 9(03)
    $header .= 'BANCO ITAU SA  ';                       // nome do banco por ext. 080 094 X(15)
    $header .= $DATA['DIA'].$DATA['MES'].$DATA['ANO']; // data geracao arquivo 095 100 9(06)
    $header .= str_pad('',294,' ',STR_PAD_LEFT);           // zeros complemento d registro 101 108 9(8)
    $qtd_registro++;
    $header .= str_pad($qtd_registro,6,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
    $header .= chr(13).chr(10);                           // essa � a quebra de linha

    // DADOS DOS TITULOS

    // Select dos Lancamentos
    $sql = "SELECT 
                    codloja, cpfcnpj_devedor, emissao, vencimento, valor, numboleto_itau, txjur, 
                    cod_pedido_web_control, chavebol, 
                    date_format( (vencimento + interval 1 day), '%d%m%Y' ) as venc_pos,
                    datapg, valorpg, data_gravacao_lote, data_alteracao, descricao_repasse
            FROM cs2.titulos_recebafacil 
            WHERE 
                    emissao >= '2016-08-29'
                AND 
                    bco = '341' 
                AND 
                    ( codloja != 764 AND codloja != 23096 )
                AND 
                   ( data_gravacao_lote IS NULL OR data_alteracao > data_gravacao_lote )
            ORDER BY chavebol,vencimento";
    $qry = mysql_query($sql,$conexao);
    $conteudo = '';
    $registro_trailler = '';
    $i=0;
    $qtd_titulos=0;
    $valor_total_titulos = 0;
    $array_titulos = '';
    while ( $rs = mysql_fetch_array($qry) ) {
		
        $i++;
        $qtd_titulos++;
        
        $codloja                = $rs['codloja'];
        $cpfcnpj_devedor        = $rs['cpfcnpj_devedor'];
        $vencimento             = $rs['vencimento'];
        $data_alteracao         = $rs['data_alteracao'];
        $data_gravacao_lote     = $rs['data_gravacao_lote'];
        $descricao_repasse      = $rs['descricao_repasse'];
        
        $dia_venc               = substr($vencimento,8,2);
        $mes_venc               = substr($vencimento,5,2);
        $ano_venc               = substr($vencimento,2,2);

        $venc_pos               = $rs['venc_pos'];
        $emissao                = $rs['emissao'];
        $dia_emis               = substr($emissao,8,2);
        $mes_emis               = substr($emissao,5,2);
        $ano_emis               = substr($emissao,2,2);

        $valor_titulo_Original  = $rs['valor'];
        $valor_titulo           = $rs['valor'];

        $valor_titulo           = str_replace('.','',$valor_titulo);

        $numboleto_itau         = $rs['numboleto_itau'];
        $txjur                  = $rs['txjur'];
        $cod_pedido_web_control = $rs['cod_pedido_web_control'];
        $chavebol               = $rs['chavebol'];
        $datapg                 = $rs['datapg'];
        $valorpg                = $rs['valorpg'];
        
        $array_titulos .= $numboleto_itau.',';
        
        $registro_trailler  = 1;                                     // tipo registro id registro transacao.                           001 001  9(01)
        $registro_trailler .= '02';                                  // Codigo da Agencia do Pagador Exclusivo para Debito em Conta    002 006  X(05)
        $registro_trailler .= '06866893000139';                      // Digito da Agencia do Pagador                                   007 007  9(01)
        $registro_trailler .= str_pad($agencia,4,0,STR_PAD_LEFT);    // Razao da Conta do Pagador Vide                                 008 012  X(05)
        $registro_trailler .= '00';                                  // codigo da empresa 027 046 9(20)
        $registro_trailler .= str_pad($conta,5,0,STR_PAD_LEFT);      // Razao da Conta do Pagador Vide                                 008 012  X(05)
        $registro_trailler .= str_pad($dv_conta,1,0,STR_PAD_LEFT);   // Razao da Conta do Pagador Vide                                 008 012  X(05)
        $registro_trailler .= '    ';                                // Digito da Conta do Pagador                                     020 020  9(01)
        $registro_trailler .= '0000';                                // Zero                                                           021 024  9(04)
        
        $sql_nome = "
            (SELECT nome, telefone, id_tipo_log AS id_tipo_log
                , endereco, numero, complemento, cep, bairro, cidade
                , uf, cnpj_cpf AS cpf
                , data_cadastro, 'client' AS tipo, rg, fone_empresa, celular
            FROM base_web_control.cliente
            WHERE cnpj_cpf = '$cpfcnpj_devedor' AND id_cadastro = $codloja)
            UNION
            (SELECT nome, telefone, id_tipo_log, endereco, numero, complemento, cep, bairro,
                cidade, uf, cpf, data_cadastro,CONCAT('funcio') AS tipo, rg, '', celular
            FROM base_web_control.funcionario
            WHERE cpf = '$cpfcnpj_devedor' AND id_cadastro = $codloja)
            UNION
            (SELECT fantasia AS nome, telefone, id_tipo_log AS id_tipo_log, endereco
                , numero AS numero, complemento, cep, bairro, cidade, uf
                , cnpj_cpf AS cpf, data_cadastro,CONCAT('fornec') AS tipo, rgie as rg, '', celular
            FROM base_web_control.fornecedor
            WHERE cnpj_cpf = '$cpfcnpj_devedor' AND id_cadastro = $codloja)
            ORDER BY data_cadastro DESC limit 1		
        ";
        $qry_nome = mysql_query($sql_nome,$conexao);
        if ( mysql_num_rows($qry_nome) == 0 ){

            $sql_nome = "SELECT
                            a.Nom_Nome as nome, b.Tipo_Log_id as id_tipo_log, b.logradouro as endereco
                            , b.numero as numero, b.complemento as complemento, b.cep as cep,
                            b.bairro as bairro, b.cidade as cidade, b.uf as uf
                         FROM base_inform.Nome_Brasil a
                         LEFT OUTER JOIN base_inform.Endereco b on a.Nom_CPF = b.CPF
                         LEFT OUTER JOIN base_inform.Nome_RG c on a.Nom_CPF = c.CPF
                         WHERE a.Nom_CPF = $cpfcnpj_devedor
                         ORDER BY a.Origem_Nome_id, a.id, b.id DESC
                         LIMIT 1";
            $qry_nome = mysql_query($sql_nome,$conexao);			
        }
        $nome = mysql_result($qry_nome,0,'nome');
        $id_tipo_log = mysql_result($qry_nome,0,'id_tipo_log');
        $endereco = mysql_result($qry_nome,0,'endereco');
        $end_completo = $endereco;

        $numero = mysql_result($qry_nome,0,'numero');
        if ( trim($numero) <> '' )
            $end_completo = $endereco.', '.$numero;

        $complemento = mysql_result($qry_nome,0,'complemento');
        if ( trim($complemento) <> '' )
            $end_completo = $end_completo.' '.$complemento;

        $bairro = mysql_result($qry_nome,0,'bairro');
        $cidade = mysql_result($qry_nome,0,'cidade');
        $uf = mysql_result($qry_nome,0,'uf');
        $cep = mysql_result($qry_nome,0,'cep');
        
        $sequencia_lotex    = str_pad($sequencia_lote,2,' ',STR_PAD_RIGHT);
        $registro_trailler .= str_pad('REGTIT'.$sequencia_lotex.$chavebol,25,' ',STR_PAD_RIGHT); // Numero Bancario para Cobranca                                         071 081  9(11)
        $registro_trailler .= str_pad($numboleto_itau,8,0,STR_PAD_LEFT); // Numero Bancario para Cobranca                                         071 081  9(11)
        $registro_trailler .= str_pad('',13,0,STR_PAD_LEFT); // QTDE MOEDA VARIAVEL
        
        $registro_trailler .= str_pad('109',3,0,STR_PAD_LEFT); // NUMERO DA CARTEIRA
        
        $registro_trailler .= str_pad('',21,' ',STR_PAD_RIGHT); // 
        $registro_trailler .= str_pad('I',1,' ',STR_PAD_RIGHT); // 
        
        // Identificacao   109 110 X(02)
        $mata_linha = 'N';
        // 01 - Remessa
        // 02 - Pedido de Baixa
        // 06 - Alteracao de vencimento
        if ( $datapg == '' ){
            // TITULO NAO FOI PAGO, 
            // VERIFICANDO SE O MESMO FOI ENVIADO PARA O BANCO ANALIZANDO A DATA DE GRAVACAO DO LOTE
            // SE SIM, O CLIENTE EFETUOU ALTERACAO DE VENCIMENTO
            // SE N�O, � UM NOVO REGISTRO   
            if ( $data_gravacao_lote == '' ) // Novo Registro
                $registro_trailler .= '01';
            else // Cliente alterou o vencimento
                $registro_trailler .= '06';
            
        }else{
            
            // Titulo FOI PAGO!!!!
            
            // Verificando se o mesmo foi RECEBIDO PELA EMPRESA OU PAGO NO BANCO
            
            /*
               SIM FOI RECEBIDO NA EMPRESA
               - VERIFICA SE O MESMO FOI REGISTRADO
                 SIM - ENVIA A BAIXA PARA O BANCO
                 NAO - MATA A LINHA
            
               NAO FOI RECEBIDO NA EMPRESA, FOI PAGO NO BANCO
               - MATA LINHA
            */
            if ( $descricao_repasse == '' ){
                // Titulo foi Pago no Banco Nao fa�o nada
                $mata_linha = 'S';
            }else{
                if ( $data_gravacao_lote != '' ){
                    $registro_trailler .= '02';             // Identificacao da Ocorrencia   109 110 X(02)
                }else{
                    $mata_linha = 'S';
                }
            }
        }
        
        $registro_trailler .= str_pad($numboleto_itau,10,0,STR_PAD_LEFT);   // Numero do documento 111 120 X(10)
        $registro_trailler .= $dia_venc.$mes_venc.$ano_venc;                    // Vencimento do Titulo 121 126 N(6)
        $registro_trailler .= str_pad($valor_titulo,13,0,STR_PAD_LEFT);         // Valor do documento 127 139 N(13)
        $registro_trailler .= '000';                                            // Banco encarregado pela cobranca 140 142 N(03)		
        $registro_trailler .= '00000';                                          // Agencia depositaria 143 147 N(05)		
        $registro_trailler .= '99';                                             // especie de documento 148 149 9(02)
        $registro_trailler .= 'N';                                              // identificacao 150 150 9(01)
        $registro_trailler .= $dia_emis.$mes_emis.$ano_emis;                    // data emissao titulo NOTA 31 151 156 9(06)
        $registro_trailler .= '00'; // primeira instrucao 157 158 9(02)
        $registro_trailler .= '00'; // segunda instrucao 159 160 9(02)
		
        $tx_multa   = ( $valor_titulo_Original * $txjur) / 100;
        $tx_multa   = number_format($tx_multa,2,'.',',');
        $tx_multa   = str_replace(',','',$tx_multa);
        $tx_multa   = str_replace('.','',$tx_multa);

        $tx_jur_dia = ( ( ($valor_titulo_Original * $txjur) / 100 ) / 30 );
        $tx_jur_dia = number_format($tx_jur_dia,2,'.',',');
        $tx_jur_dia = str_replace(',','',$tx_jur_dia);
        $tx_jur_dia = str_replace('.','',$tx_jur_dia);
		
        $registro_trailler .= str_pad($tx_jur_dia,13,0,STR_PAD_LEFT); // Valor de mora a ser cobrado por dia de atraso 161 173 9(11)V9(02)
        $registro_trailler .= str_pad('',6,0,STR_PAD_LEFT); // Data limite para concessao de desconto 174 179 9(06)
        $registro_trailler .= '0000000000000'; // valor desconto a ser concedido NOTA 13 180 192 9(11)V9(02)
        $registro_trailler .= '0000000000000'; // valor I.O.F RECOLHIDO P NOTAS SEGURO NOTA 14 193 205 9(11)V9(02)
        $registro_trailler .= '0000000000000'; // abatimento a ser concedido NOTA 13 206 218 9(11)V9(02)
	
        if ( strlen($cpfcnpj_devedor) <= 11 ){
            // CPF
            $registro_trailler .= '01'; // tipo inscricao sacado 219 220 9(02)
        }else{
            // CNPJ
            $registro_trailler .= '02'; // tipo inscricao sacado 219 220 9(02)
        }
        
        $registro_trailler .= str_pad($cpfcnpj_devedor,14,0,STR_PAD_LEFT);  // numero de inscricao cpf ou cnpj 221 234 9(14)
        $registro_trailler .= substr(str_pad($nome,30,' ',STR_PAD_RIGHT),0,30); // nome nome do sacado NOTA 15 235 274 X(40)
        $registro_trailler .= str_pad('',10,' ',STR_PAD_RIGHT); // nome nome do sacado NOTA 15 235 274 X(40)
        
        $registro_trailler .= substr(str_pad($end_completo,40,' ',STR_PAD_RIGHT),0,40); // logradouro rua numero e compl sacado 275 314 X(40)
        $registro_trailler .= substr(str_pad($bairro,12,' ',STR_PAD_RIGHT),0,12); // 1a. Mensagem 315 326 X(12)
        $registro_trailler .= substr(str_pad($cep,8,0,STR_PAD_LEFT),0,40); // cep do sacado 327 334 X(8)
        $registro_trailler .= substr(str_pad($cidade,15,' ',STR_PAD_RIGHT),0,15); // cep do sacado 327 334 X(8)
        $registro_trailler .= str_pad($uf,2,' ',STR_PAD_RIGHT); // cep do sacado 327 334 X(8)
        $registro_trailler .= str_pad('',30,' ',STR_PAD_RIGHT);             // sacador/avalista ou 2a. mensagem 335 394 X(60)
        $registro_trailler .= str_pad('',4,' ',STR_PAD_RIGHT);             // sacador/avalista ou 2a. mensagem 335 394 X(60)
        $registro_trailler .= str_pad('',6,0,STR_PAD_LEFT);             // sacador/avalista ou 2a. mensagem 335 394 X(60)
        $registro_trailler .= str_pad('32',2,0,STR_PAD_LEFT);             // QUANTIDADE DE DIAS  -  NAO REDEBER APOS 90 DIAS DO VENCIMENTO
        $registro_trailler .= str_pad('',1,' ',STR_PAD_RIGHT);             // sacador/avalista ou 2a. mensagem 335 394 X(60)
        
        $qtd_registro++;
        
        $registro_trailler .= str_pad($qtd_registro,6,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
        $registro_trailler .= chr(13).chr(10);                           // essa � a quebra de linha
        
        $registro_trailler .= '2';
        $registro_trailler .= '1';
        // Vencimento + 1
        $registro_trailler .= str_pad($venc_pos,8,0,STR_PAD_LEFT);
        
        // Valor da Multa        
        $registro_trailler .= str_pad($tx_multa,13,0,STR_PAD_LEFT);
        $registro_trailler .= str_pad('',371,' ',STR_PAD_RIGHT);             // sacador/avalista ou 2a. mensagem 335 394 X(60)
        
        $qtd_registro++;
        
        $registro_trailler .= str_pad($qtd_registro,6,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
        $registro_trailler .= chr(13).chr(10);                           // essa � a quebra de linha
        
        if ( $mata_linha == 'N'){
            $conteudo .= $registro_trailler;
            $valor_total_titulos   += $rs['valor'];
        }else{
            $qtd_titulos = $qtd_titulos - 1;
            $qtd_registro = $qtd_registro - 2;
        }
    }

    $valor_total_titulos = $valor_total_titulos * 100;
    
    # REGISTRO TRAILLER DO LOTE 0003
    $trailler = '9';                                        // 001 a 001 - Identificacao do Registro
    $trailler .= '2';                                        // 001 a 001 - Identificacao do Registro
    $trailler .= '01';                                        // 001 a 001 - Identificacao do Registro
    $trailler .= '341';                                        // 001 a 001 - Identificacao do Registro
    $trailler .= str_pad('',10,' ',STR_PAD_LEFT);          // 002 a 394 - Branco
    $trailler .= str_pad($qtd_titulos,8,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
    $trailler .= str_pad($valor_total_titulos,14,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
    $trailler .= str_pad('',8,' ',STR_PAD_LEFT);          // 002 a 394 - Branco
    $trailler .= str_pad('',10,' ',STR_PAD_LEFT);          // 002 a 394 - Branco
    $trailler .= str_pad($qtd_titulos_vinculado,8,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
    $trailler .= str_pad($vr_total_titulos_vinculado,14,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
    $trailler .= str_pad('',8,' ',STR_PAD_LEFT);          // 002 a 394 - Branco
    $trailler .= str_pad('',90,' ',STR_PAD_LEFT);          // 002 a 394 - Branco
    $trailler .= str_pad($qtd_titulos_vinculado,8,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
    $trailler .= str_pad($vr_total_titulos_vinculado,14,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
    $trailler .= str_pad('',8,' ',STR_PAD_LEFT);          // 002 a 394 - Branco
    $trailler .= str_pad($sequencia_lote,5,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
    $trailler .= str_pad($qtd_titulos*2,8,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
    $trailler .= str_pad($valor_total_titulos,14,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
    $trailler .= str_pad('',160,' ',STR_PAD_LEFT);          // 002 a 394 - Branco
    $qtd_registro++;
    $trailler .= str_pad($qtd_registro,6,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
    $trailler .= chr(13).chr(10);                           // essa � a quebra de linha
    
    $registros = $header.$conteudo.$trailler;

    try{
        $arquivo = "../../../download/CI".$DATA['DIA'].$DATA['MES'].str_pad($sequencia_lote,2,0,STR_PAD_LEFT);
        $arq = "CI".$DATA['DIA'].$DATA['MES'].str_pad($sequencia_lote,2,0,STR_PAD_LEFT);
        $abrir = fopen($arquivo, "w");
        $escreve = fputs($abrir, $registros);
        fclose($arquivo);
    } catch (Exception $e) {
        echo 'Erro ao gravar o arquivo: ',  $e->getMessage(), "\n";
        exit;
    }

    $xtotal_geral = substr($tot_pagar,0,strlen($tot_pagar)-2).'.'.substr($tot_pagar,strlen($tot_pagar)-2,2);

    $total_geral = number_format($xtotal_geral,2);
    
    //Remove ultima virgula
    $array_titulos = substr($array_titulos,0,-1);
            
    echo " 
              Arquivo Gerado com sucesso  
              Arquivo:	<a href='#' onClick=\"window.open('https://www.webcontrolempresas.com.br/download/$arq','_blank')\">$arq</a>
              <br>
              <hr>
              Confirmar a grava&ccedil;&atilde;o deste Arquivo gerado at&eacute; as : $data_hora_final<br>
              <br>
              <input type='button' name='confirmar' value=' CONFIRMAR ' OnClick= \"Grava_Dados('$array_titulos' ,'$sequencia_lote','$data_hora_final','$id_control_banco')\" >
         ";
	
?>

<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
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
            },
            type: "POST",
            success: function (res) {
                
                console.log( res);

            }
        });
    }
</script>