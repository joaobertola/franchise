<?php

    # Autor:   Luciano Mancini
    # Modulo:  Remessa Titulos RecebaFacil - Banco Bradesco
    # Finalidade: 
    #		Gerar o arquivo de titulos a serem enviados ao Banco Bradesco 
    #		para REGISTRO

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


    include("../../../validar2.php");

    global $conexao,$arquivo;
    conecex();

    $timestamp = mktime(date("H") - $fusohorario, date("i"), date("s"), date("m"), date("d"), date("Y"));
    $DATA['DIA'] = gmdate("d",$timestamp);
    $DATA['MES'] = gmdate("m",$timestamp);
    $DATA['ANO'] = gmdate("y",$timestamp);

    
    $data_hora_final = date('Y-m-d H:i:s');
    
    // Buscando no numero do LOTE do Banco
    // Selecionando a Empresa que ir� Registrar os Titulos ( ISPCN )
    $sql_empresa = "SELECT id, banco, agencia, conta, empresa, sequencia, ult_data
                    FROM cs2.controle_banco
                    WHERE id = '1'";
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

    $header  = '0';                                    // tipo de registro id registro header 001 001 9(01) 
    $header .= 1;                                      // operacao tipo operacao remessa 002 002 9(01)
    $header .= 'REMESSA';                              // literal remessa escr. extenso 003 009 X(07)
    $header .= '01';                                   // codigo servico id tipo servico 010 011 9(02)
    $header .= 'COBRANCA       ';                      // literal cobranca escr. extenso 012 026 X(15)
    $header .= str_pad('4456755',20,0,STR_PAD_LEFT);   // codigo da empresa 027 046 9(20)
    $header .= substr(str_pad($empresa,30,' ',STR_PAD_RIGHT),0,30); // nome da empresa 047 076 X(30)
    $header .= '237';                                     // codigo banco N� BANCO C�MARA COMP. 077 079 9(03)
    $header .= 'BRADESCO       ';                         // nome do banco por ext. 080 094 X(15)
    $header .= $DATA['DIA'].$DATA['MES'].$DATA['ANO'];    // data geracao arquivo 095 100 9(06)
    $header .= str_pad('',8,' ',STR_PAD_LEFT);            // zeros complemento d registro 101 108 9(8)
    $header .= 'MX';                                      // Identificacao do sistema 109 110 X(2)
    $qtd_registro++;
    $header .= str_pad($sequencia_lote,7,0,STR_PAD_LEFT); // Numero Sequencial de Remessa 111 117 9(7)
    $header .= str_pad('',277,' ',STR_PAD_LEFT);          // brancos complemento registro 118 394 X(277)
    $header .= str_pad($qtd_registro,6,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
    $header .= chr(13).chr(10);                           // essa � a quebra de linha

    // DADOS DOS TITULOS

    // Select dos Lancamentos
    $sql = "SELECT 
                    a.codloja, b.insc, a.emissao, vencimento, valor, numboleto_bradesco, txjur, a.numdoc
                    datapg, valorpg
            FROM cs2.titulos a
            INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
            WHERE a.emissao >= '2016-08-14' AND a.data_movimentacao BETWEEN '$ult_data' AND '$data_hora_final'
            ";
    $qry = mysql_query($sql,$conexao);
    $conteudo = '';
    $i=0;
    $array_titulos = '';
    while ( $rs = mysql_fetch_array($qry) ) {
		
        $i++;
        $codloja                = $rs['codloja'];
        $cpfcnpj_devedor        = $rs['insc'];
        $vencimento             = $rs['vencimento'];
        $dia_venc               = substr($vencimento,8,2);
        $mes_venc               = substr($vencimento,5,2);
        $ano_venc               = substr($vencimento,2,2);

        $emissao                = $rs['emissao'];
        $dia_emis               = substr($emissao,8,2);
        $mes_emis               = substr($emissao,5,2);
        $ano_emis               = substr($emissao,2,2);

        $valor_titulo_Original  = $rs['valor'];
        $valor_titulo           = $rs['valor'];
        $valor_titulo           = str_replace('.','',$valor_titulo);


        $numboleto_bradesco     = $rs['numboleto_bradesco'];
        $txjur                  = $rs['txjur'];
        $numdoc                 = $rs['numdoc'];
        $datapg                 = $rs['datapg'];
        $valorpg                = $rs['valorpg'];
        
        $array_titulos.=$numboleto_bradesco.',';
        

        $conteudo .= 1;                                   // tipo registro id registro transacao.                           001 001  9(01)
        $conteudo .= '00000';                             // C�digo da Ag�ncia do Pagador Exclusivo para D�bito em Conta    002 006  X(05)
        $conteudo .= ' ';                                 // D�gito da Ag�ncia do Pagador                                   007 007  9(01)
        $conteudo .= '00000';                             // Raz�o da Conta do Pagador Vide                                 008 012  X(05)
        $conteudo .= '0000000';                           // N�mero da Conta do Pagadora                                    013 019  X(07)
        $conteudo .= ' ';                                 // D�gito da Conta do Pagador                                     020 020  9(01)
        $conteudo .= '0'.'009'.'03451'.'00402583';        // Zero, Carteira, Ag�ncia e Conta Corrente                       021 037  X(17)

        // Com codigo escritural mando com a Carteira  09 - Nova 
        // Sem preencher enviar com 06 - Antigos

        $conteudo .= str_pad($chavebol,25,' ',STR_PAD_RIGHT);  // Uso da Empresa - N� Controle do Participante                   038 062  X(25)
        $conteudo .= '000';                               // Codigo do Banco a ser debitado na Camara de Compensa��o        063 065  9(03)
        // Sempre existira
        $conteudo .= '2';                                 // Campo de Multa - Se = 2 considerar percentual de multa. Se = 0, sem multa   066 066  9(01)
        // Multa de 2%
        $conteudo .= '0002';                              // Percentual de multa                                                         067 070  9(04)  ??????????

        $numboleto_bradesco = str_pad($numboleto_bradesco,11,0,STR_PAD_LEFT);
        
        $conteudo .= $numboleto_bradesco; // N�mero Banc�rio para Cobran�a                                         071 081  9(11)

        $dv_numboleto =  digitoVerificador_nossonumero( '09'.$numboleto_bradesco );
        
        $conteudo .= $dv_numboleto;                             // Digito N/N                                                            082 082  9(01)

        $conteudo .= str_pad('',10,0,STR_PAD_LEFT);               // Desconto Bonifica��o por dia                                          083 092  9(10)
        $conteudo .= '2';                                         // Condi��o para Emiss�o - 1 = Banco   2 = Cliente                       093 093  9(01)
        $conteudo .= 'N';                                         // Ident. se emite Boleto para D�bito Autom�tico                         094 094  X(01)
        $conteudo .= str_pad('',10,' ',STR_PAD_RIGHT);            // Brancos                                                               095 104  X(10)
        $conteudo .= ' ';                                         // Identificador Rateio de credito                                       105 105  X(01)
        $conteudo .= '2';                                         // Endere�amento para Aviso do D�bito Autom�tico em Conta Corrente       106 106  9(01)
        $conteudo .= str_pad('',2,' ',STR_PAD_RIGHT);             // Brancos                                                               107 108  X(02)

        // 01 - Remessa
        // 02 - Pedido de Baixa
        // 06 - Alteracao de vencimento
        if ( $datapg == '' )
                $conteudo .= str_pad('',2,'01',STR_PAD_RIGHT);             // Identificacao da Ocorrencia                                           109 110 X(02)
        else
                $conteudo .= str_pad('',2,'02',STR_PAD_RIGHT);             // Identificacao da Ocorrencia                                           109 110 X(02)

        $conteudo .= str_pad($numboleto_bradesco*1,10,0,STR_PAD_LEFT);   // Numero do documento 111 120 X(10)
        $conteudo .= $dia_venc.$mes_venc.$ano_venc;                    // Vencimento do Titulo 121 126 N(6)
        $conteudo .= str_pad($valor_titulo,13,0,STR_PAD_LEFT);         // Valor do documento 127 139 N(13)
        $conteudo .= '000';                                            // Banco encarregado pela cobranca 140 142 N(03)		
        $conteudo .= '00000';                                          // Agencia depositaria 143 147 N(05)		
        $conteudo .= '99';                                             // especie de documento 148 149 9(02)
        $conteudo .= 'N';                                              // identificacao 150 150 9(01)
        $conteudo .= $dia_emis.$mes_emis.$ano_emis;                    // data emissao titulo NOTA 31 151 156 9(06)
        $conteudo .= '00'; // primeira instrucao 157 158 9(02)
        $conteudo .= '00'; // segunda instrucao 159 160 9(02)
		
        $tx_jur_dia = ($valor_titulo_Original * $txjur) / 100;
        $tx_jur_dia = number_format($tx_jur_dia,2,'.',',');
        $tx_jur_dia = str_replace(',','',$tx_jur_dia);
        $tx_jur_dia = str_replace('.','',$tx_jur_dia);
		
        $conteudo .= str_pad($tx_jur_dia,13,0,STR_PAD_LEFT);           // Valor de mora a ser cobrado por dia de atraso 161 173 9(11)V9(02)
        $conteudo .= str_pad('',6,0,STR_PAD_LEFT);                     // Data limite para concessao de desconto 174 179 9(06)
        $conteudo .= '0000000000000';// valor desconto a ser concedido NOTA 13 180 192 9(11)V9(02)
        $conteudo .= '0000000000000'; // valor I.O.F RECOLHIDO P NOTAS SEGURO NOTA 14 193 205 9(11)V9(02)
        $conteudo .= '0000000000000'; // abatimento a ser concedido NOTA 13 206 218 9(11)V9(02)
		
        if ( strlen($cpfcnpj_devedor) <= 11 ){
            // CPF
            $conteudo .= '01'; // tipo inscricao sacado 219 220 9(02)
        }else{
            // CNPJ
            $conteudo .= '02'; // tipo inscricao sacado 219 220 9(02)
        }
		
        $conteudo .= str_pad($cpfcnpj_devedor,14,0,STR_PAD_LEFT);  // numero de inscricao cpf ou cnpj 221 234 9(14)

        $sql_nome = "
            SELECT 
                razaosoc as nome, 
                telefone, 
                endereco, 
                numero, 
                complemento, 
                cep, 
                bairro, 
                cidade, 
                uf, 
                cnpj_cpf AS cpf
                
            FROM cs2.cadastro 
            WHERE codloja = $codloja
        ";
        $qry_nome = mysql_query($sql_nome,$conexao);

        $nome = mysql_result($qry_nome,0,'nome');
        $endereco = mysql_result($qry_nome,0,'endereco');
        $end_completo = $endereco;

        $numero = mysql_result($qry_nome,0,'numero');
        if ( trim($numero) <> '' )
            $end_completo = $endereco.', '.$numero;

        $complemento = mysql_result($qry_nome,0,'complemento');
        if ( trim($complemento) <> '' )
            $end_completo = $end_completo.' '.$complemento;

        $bairro = mysql_result($qry_nome,0,'bairro');
        if ( trim($bairro) <> '' )
            $end_completo = $end_completo.' '.$bairro;

        $cidade = mysql_result($qry_nome,0,'cidade');
        if ( trim($cidade) <> '' )
            $end_completo = $end_completo.' '.$cidade;

        $uf = mysql_result($qry_nome,0,'uf');
        if ( trim($uf) <> '' )
            $end_completo = $end_completo.' '.$uf;

        $cep = mysql_result($qry_nome,0,'cep');

        $conteudo .= substr(str_pad($nome,40,' ',STR_PAD_RIGHT),0,40); // nome nome do sacado NOTA 15 235 274 X(40)
        $conteudo .= substr(str_pad($end_completo,40,' ',STR_PAD_RIGHT),0,40); // logradouro rua numero e compl sacado 275 314 X(40)
        $conteudo .= substr(str_pad('',12,' ',STR_PAD_RIGHT),0,40); // 1a. Mensagem 315 326 X(12)
        $conteudo .= str_pad($cep,8,0,STR_PAD_LEFT); // cep do sacado 327 334 X(8)
        $conteudo .= str_pad('',60,' ',STR_PAD_LEFT);             // sacador/avalista ou 2a. mensagem 335 394 X(60)
        $qtd_registro++;
        $conteudo .= str_pad($qtd_registro,6,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)
        $conteudo .= chr(13).chr(10);                           // essa � a quebra de linha
    }

    # REGISTRO TRAILLER DO LOTE 0003
    $trailler = '9';                                        // 001 a 001 - Identificacao do Registro
    $trailler .= str_pad('',393,' ',STR_PAD_LEFT);          // 002 a 394 - Branco
    $qtd_registro++;
    $trailler .= str_pad($qtd_registro,6,0,STR_PAD_LEFT);   // numero sequencial registro no arquivo 395 400 9(06)

    $registros = $header.$conteudo.$trailler;

    try{
        $arquivo = "../../../download/TIT".$DATA['DIA'].$DATA['MES'].str_pad($sequencia_lote,2,0,STR_PAD_LEFT);
        $arq = "CB".$DATA['DIA'].$DATA['MES'].str_pad($sequencia_lote,2,0,STR_PAD_LEFT);
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