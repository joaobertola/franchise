<?php

include("../../../validar2.php");

global $conexao, $arquivo, $cont_nao, $NAO_ENC ;
global $erro ,$erro_valor, $QUITADO, $TIT_REG, $TIT_REGM, $TIT_NREG, $TIT_NREGM, $RECEBA_F, $RECEBA_A, $cont_liq;

conecex();

$erro = '';
$b = "&nbsp;";

$erro_valor = '';

$RECEBA_F = '';
$TIT_REG = '';
$TIT_REGM = '';
$TIT_NREG = '';
$TIT_NREGM = '';
$cont_nao = 0;
$cont_liq = 0;

function espaco($espa,$quant){
    $aux=$espa;
    $tamanho=strlen($espa);
    $zeros="";
    for($i=1;$i<=$quant-$tamanho;$i++){
            $zeros = "&nbsp;".$zeros;
    }
    $aux ="$aux$zeros";
    return $aux;
}

function quita_titulo($i_titulo, $i_valor_titulo, $i_total_recebido, $i_juros_titulo, $pagamento ){
    
    global $conexao, $cont_nao, $NAO_ENC, $RECEBA_F;
    $sql = "SELECT a.codloja,a.valor,a.numdoc, date_format(a.vencimento,'%d/%m/%Y') as vencimento,
                   b.nomefantasia,b.cidade,
                   a.cpfcnpj_devedor, c.Nom_Nome, b.banco_cliente, b.agencia_cliente,
                   b.conta_cliente, b.cpfcnpj_doc, b.nome_doc, a.tp_titulo, a.datapg,
                   MID(d.logon,1,LOCATE('S', d.logon) - 1) AS logon, a.emissao
            FROM cs2.titulos_recebafacil a
            INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
            LEFT OUTER JOIN base_inform.Nome_Brasil c ON a.cpfcnpj_devedor = c.Nom_CPF
            INNER JOIN cs2.logon d ON b.codloja = d.codloja
            WHERE a.numboleto_itau = '$i_titulo'
            GROUP BY a.numboleto_itau";
    $qr_sql = mysql_query($sql,$conexao) or die ("ERRO: $sql");
    $qtd  = mysql_num_rows($qr_sql);
    if ( $qtd > 0 ){
        $dados = mysql_fetch_array($qr_sql);
        $tp_tit = $dados['tp_titulo'];
        $Valor_Bol = $dados['valor'];
        $vencimento = $dados['vencimento'];
        $codloja = $dados['codloja'];
        $logon = $dados['logon'];
        $nomefantasia = $dados['nomefantasia'];
        $emissao = $dados['emissao'];

        # Atualizando tabela titulo_recebafacil
        $sql_update = " UPDATE cs2.titulos_recebafacil 
                        SET datapg = '$pagamento', valorpg='$i_total_recebido',juros='$i_juros_titulo' 
                        WHERE numboleto_itau = '$i_titulo'";
        $qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");
        # Verificando se o titulo j� est� na tabela Conta Corrente Receba F�cil
        $sql_cta = "SELECT count(*) qtd FROM cs2.contacorrente_recebafacil WHERE numboleto = '$i_titulo'";
        $qr_cta = mysql_query($sql_cta,$conexao) or die ("ERRO: $sql_cta");
        $qtd = mysql_result($qr_cta,0,'qtd');
        if ( empty($qtd) ) $qtd = '0';
        if ( $qtd == 0 ){
            $Text = ' Titulo Receb. Bco : '.$dados['cpfcnpj_devedor'].' '.$dados['Nom_Nome'];
            $Text = str_replace(chr(39),'',$Text);
            $Text = str_replace(chr(47),'',$Text);

            // verifico o saldo do cliente
            $sql_sdo = "SELECT saldo FROM cs2.contacorrente_recebafacil
                                    WHERE codloja='$codloja' order by id desc limit 1";
            $qr_sdo = mysql_query($sql_sdo,$conexao) or die ("ERRO: $sql_sdo");
            $saldo = mysql_result($qr_sdo,0,'saldo');
            if ( empty($saldo) ) $saldo = '0';
            $tx_adm = 0;
            $tx_adm = ( $i_valor_titulo * 0.025 );
            $saldo += $i_valor_titulo;

            $time_inicial = strtotime('2017-01-01');
            $time_final = strtotime($emissao);
            // Calcula a diferença de segundos entre as duas datas:
            $diferenca = $time_final - $time_inicial; // 19522800 segundos
            // Calcula a diferença de dias
            $diferenca_dias = (int) floor($diferenca / (60 * 60 * 24));
            // Após 01/01/2017
            if ( $diferenca >= 0 ) $tarifa = 2.25;
            else $tarifa = 4.95;

            $saldo = ($saldo - ( $tarifa + $tx_adm) );
                
            $sql_ins = "INSERT INTO cs2.contacorrente_recebafacil(
                                numboleto,codloja,data,discriminacao,venc_titulo,
                                valor_titulo,valor,saldo,datapgto,tx_adm, tarifa_bancaria)
                        SELECT numboleto_itau,a.codloja, now(),'$Text', a.vencimento, a.valor,
                                '$i_valor_titulo',' $saldo','$pagamento','$tx_adm', $tarifa
                        FROM cs2.titulos_recebafacil a
                        INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
                        WHERE a.numboleto_itau='$i_titulo'";
            $qr_ins = mysql_query($sql_ins,$conexao) or die ("ERRO: $sql_ins");
        }
        $RECEBA_F .= '<tr style="font-size: 10px"><td>'.substr($logon.' - '.$nomefantasia,0,35).'</td>';
        $RECEBA_F .= '<td>'.$dados['cpfcnpj_devedor'].'</td>';
        $RECEBA_F .= '<td>'.substr($dados['Nom_Nome'],0,35).'</td>';
        $RECEBA_F .= '<td width="120">'.$i_titulo.'</td>';
        $RECEBA_F .= '<td width="120">'.$vencimento.'</td>';
        $RECEBA_F .= '<td width="120" align="right">'.number_format($dados['valor'],2,',','.').'</td>';
        $RECEBA_F .= '<td width="120" align="right">'.number_format($i_valor_titulo,2,',','.').'</td></tr>';
        
    }else{

        $cont_nao++;
        $NAO_ENC .= espaco($i_numdoc,20).espaco('',3).espaco($i_titulo,20).' '.espaco($vencimento,10).espaco('',4).$i_valor_titulo."<br>";
    }
}

function processa_linha_banco($i_titulo, $dt_pagamento, $i_valor_titulo, $i_juros_titulo, $i_total_recebido, $i_bco_recebedor, $i_valor_pago)
{
    global $conexao,$NAO_ENC,$QUITADO,$erro_valor;
    
    # PESQUISA NA TABELA TITULOS_RECEBAFACIL
    
    $sql = "SELECT a.codloja,a.valor,a.numdoc,a.vencimento,b.nomefantasia,b.cidade,
                   a.cpfcnpj_devedor, c.Nom_Nome, b.banco_cliente, b.agencia_cliente, 
                   b.conta_cliente, b.cpfcnpj_doc, b.nome_doc, a.tp_titulo 
            FROM cs2.titulos_recebafacil a
            INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
            LEFT OUTER JOIN base_inform.Nome_Brasil c ON a.cpfcnpj_devedor = c.Nom_CPF
            WHERE a.numboleto_itau = '$i_titulo'
            LIMIT 1";
    $qr_sql = mysql_query($sql,$conexao) or die ("ERRO: $sql");
    $qtd  = mysql_num_rows($qr_sql);
    if ( $qtd > 0 ){

        if ( $i_total_recebido > 5000 ){

            $erro_valor .= "Titulo : $i_titulo<br>";
            $erro_valor .= "Valor Pago: $i_total_recebido<br>";
            $erro_valor .= "-----------------------------------------<br>";

        }else{
            // Achei o Titulo.
            quita_titulo($i_titulo, $i_valor_titulo, $i_total_recebido, $i_juros_titulo, $dt_pagamento);
        }

    }else{
        
        // Pesquisa na tabela de [titulos_recebafacil_excluidos]
        $sql_tit_exc = "SELECT count(*) as qtd  FROM cs2.titulos_recebafacil_excluidos 
                        WHERE numboleto_itau='$i_titulo'";
        $qry_tit_exc = mysql_query( $sql_tit_exc, $conexao ) or die("ERRO : $sql_tit_exc");
        $qtd_tit_exc = mysql_result($qry_tit_exc,0,'qtd');
        if ( $qtd_tit_exc > 0 ){
            $sql_ajuda = "INSERT INTO cs2.titulos_recebafacil
                         SELECT * FROM cs2.titulos_recebafacil_excluidos
                         WHERE numboleto_itau='$i_titulo'";
            $qry_ajuda = mysql_query($sql_ajuda,$conexao) or die("ERRO : $sql_ajuda");

            $sql_ajuda = "DELETE FROM cs2.titulos_recebafacil_excluidos
                          WHERE numboleto_itau='$i_titulo'";
            $qry_ajuda = mysql_query($sql_ajuda,$conexao) or die("ERRO : $sql_ajuda");


            if ( $i_total_recebido > 5000 ){

                $erro_valor .= "Titulo : $i_titulo<br>";
                $erro_valor .= "Valor Pago: $i_total_recebido<br>";
                $erro_valor .= "-----------------------------------------<br>";

            }else{
                quita_titulo($i_titulo, $i_valor_titulo, $i_total_recebido, $i_juros_titulo, $dt_pagamento);
            }

        }else{

            // Eh  nao achei mesmo, foi deletado na RAIZ

            // NAO TEM CREDIARIO / RECUPERE / BOLETO deste titulo
            // VERIFICANDO MENSALIDADE
            $i_titulo = str_pad($i_titulo,11,0,STR_PAD_LEFT);
            $sql = "SELECT a.codloja,a.valor,a.numdoc,a.vencimento,b.razaosoc,b.cidade,b.nomefantasia
                    FROM cs2.titulos a 
                    INNER JOIN cs2.cadastro b ON a.codloja = b.codloja 
                    WHERE a.numboleto = '$i_titulo'";
            $qr_sql = mysql_query($sql,$conexao) or die ("ERRO: $sql");
            $qtd  = mysql_num_rows($qr_sql);
            if ( $qtd > 0 ){
                $reg = mysql_fetch_array($qr_sql);
                $Valor_Bol = $reg['valor'];
                $codloja   = $reg['codloja'];
                $Num_Doc    = $reg['numdoc'];
                $Vencimento = $reg['vencimento'];
                $outros     = substr($reg['razaosoc'],0,25).'/'.substr($reg['cidade'],0,15);
                $nomefantasia   = substr($reg['nomefantasia'],0,25);

                # Buscando o logon do cliente
                $sql_logon = "  SELECT MID(logon,1,LOCATE('S', logon) - 1) as logon from cs2.logon
                                WHERE codloja = $codloja ";
                $qr_logon = mysql_query($sql_logon,$conexao) or die ("ERRO: $sql");
                $qtd_logon  = mysql_num_rows($qr_logon);
                if ( $qtd_logon > 0 ){
                    $reg_logon = mysql_fetch_array($qr_logon);
                    $logon = $reg_logon['logon'];
                }

                if ( $Valor_Bol > $i_total_recebido ){
                    $erro .="============================================================================================<br>";
                    $erro .="Corrupcao de registro !!! Verifique: <br>";
                    $erro .="                            Titulo : $i_titulo        Documento: $Num_Doc<br>";
                    $erro .="                            Cliente: ID = $codloja -  $logon<br>";
                    $erro .="                         Vencimento: $Vencimento<br>";
                    $erro .="                    Valor do Titulo: $Valor_Bol  Valor Pago: $i_total_recebido<br>";
                    $erro .="TITULO BAIXADO COM O VALOR DE      : $i_total_recebido<br>";
                    $erro .="============================================================================================<br>";
                    $cont_liq++;
                    Quita_fatura_Itau($i_titulo,$codloja,$i_juros_titulo,$dt_pagamento,$i_valor_titulo,$i_juros_titulo,$nomefantasia,$Valor_Bol,$Vencimento);
                    verifica_titulos($codloja);
                    $QUITADO .= espaco($Num_Doc,16).espaco('',2).$i_titulo.' '.$Vencimento.' '.espaco($Valor_Bol,10).' '.$outros.'<br>';
                    $valor_total_quitado += $Valor_Bol;
                }else{
                    $cont_liq++;
                    Quita_fatura_Itau($i_titulo,$codloja,$i_juros_titulo,$dt_pagamento,$i_valor_titulo,$i_juros_titulo,$nomefantasia,$Valor_Bol,$Vencimento);
                    verifica_titulos($codloja);
                    $QUITADO .= espaco($Num_Doc,15).espaco('',2).$i_titulo.' '.$Vencimento.' '.espaco($Valor_Bol,10).' '.$outros.'<br>';
                    $valor_total_quitado += $Valor_Bol;
                }
            }else{
                $cont_nao++;
                $NAO_ENC .= espaco($i_numdoc,20).espaco('',3).espaco($i_titulo,20).' '.espaco($vencimento,10).espaco('',4).$i_valor_titulo."<br>";
            }
        }
    }
}

function motivo($cod_ocorrencia,$cod_retorno){
    global $conexao;
    
}
        
$linha  = file($arquivo);
$total  = count($linha); //Conta as linhas

for($i=0;$i<$total;$i++){
    # Cabe�alho do Arquivo
    $lin = $linha[$i];
    if ( $i == 0 ){
        $pagamento = '20'.substr($lin,98,2).'-'.substr($lin,96,2).'-'.substr($lin,94,2);
        $data_movimento = '20'.substr($lin,98,2).'-'.substr($lin,96,2).'-'.substr($lin,94,2);
        $banco_registro = substr($lin,76,3);
        $agencia_registro = substr($lin,26,4);
        $conta_registro = substr($lin,30,8);
    }else{
        # Trailler do Arquivo
        if ( substr($lin,0,1) == '1' ){

            $dt_pagamento      = '20'.substr($lin,114,2).'-'.substr($lin,112,2).'-'.substr($lin,110,2);
            $i_lote            = trim(substr($lin,43,1));
            $i_titulo          = trim(substr($lin,62,8));
            
            $i_valor_titulo    = substr($lin,152,13)/100;
            $i_juros_titulo    = substr($lin,266,13)/100;
            $i_outros_creditos = substr($lin,279,13)/100;
            $i_descontos       = substr($lin,240,13)/100;

            $i_juros_titulo    = $i_juros_titulo + $i_outros_creditos;
            $i_total_recebido  = $i_valor_titulo + $i_juros_titulo;
            $i_total_recebido  = $i_total_recebido - $i_descontos;
            $i_valor_titulo    = $i_total_recebido;
            
            $i_bco_recebedor  = substr($lin,165,3);
            $i_valor_pago     = substr($lin,253,13)/100;
            
            $cod_ocorrencia    = trim(substr($lin,108,2));
            $data_credito      = trim(substr($lin,295,6));
            $cod_liquidacao    = trim(substr($lin,392,2));
            
            $cod_retorno       = trim(substr($lin,377,8));
            
            // Dados do Titular do Titulo
            $nome_consumidor   = espaco(trim(substr($lin,324,30)),30);
            $contrato          = trim(substr($lin,45,17));
            
            // 02 - Registro Confirmado
            // 03 - Registro Rejeitado
            // 06 - Titulo Liquidado

            switch ( $cod_ocorrencia ){
                
                case '06': // TITULO QUITADO
                    
                    if ( $data_credito != '' ){
                        if ( $i_valor_pago > 0 ){
                            processa_linha_banco($i_titulo, $dt_pagamento, $i_valor_titulo, $i_juros_titulo, $i_total_recebido, $i_bco_recebedor, $i_valor_pago);
                        }
                    }
                    break;

                case '02' : // ENTRADA CONFIRMADA
                    
                    // Verificando se o titulo é de mensalidade ou Boleto Emitido pelo Cliente
                    $i_titulox = str_pad($i_titulo,11,0,STR_PAD_LEFT);
                    $sql22 = "SELECT id FROM cs2.titulos WHERE numboleto = '$i_titulox'";
                    $qr22 = mysql_query($sql22,$conexao) or die ("ERRO: $sql22");
                   
                    $qtd = mysql_num_rows($qr22);
                     
                    if ( $qtd > 0 ){
                        // Eh titulo de mensalidade
                        $id_titulo = mysql_result($qr22,0,'id');
                        $sql_update = " UPDATE cs2.titulos 
                                        SET 
                                            num_lote = '$i_lote',
                                            banco_registro = '$banco_registro',
                                            agencia_registro = '$agencia_registro',
                                            conta_registro = '$conta_registro',
                                            cod_liquidacao = '$cod_liquidacao',
                                            data_registro = '$data_movimento'
                                        WHERE id = '$id_titulo'";
                        $qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");
                        $TIT_REGM .= $i_titulo.' '.$vencimento.' R$ '.espaco($i_valor_titulo,14).' '.$nome_consumidor.'<BR>';
                        $cont_reg_mens++;
                        $valor_total_registrado_mens += $i_valor_titulo;
                            
                    }else{
                        
                        // Verificando se é de Boleto/Crediario/Recupere
                        $sql22 = "SELECT numdoc FROM cs2.titulos_recebafacil WHERE numboleto_itau = '$i_titulo'";
                        $qr22 = mysql_query($sql22,$conexao) or die ("ERRO: $sql22");
                        $qtd = mysql_num_rows($qr22);
                        if ( $qtd > 0 ){
                        
                            $sql_update = " UPDATE cs2.titulos_recebafacil 
                                            SET 
                                                num_lote = '$i_lote',
                                                banco_registro = '$banco_registro',
                                                agencia_registro = '$agencia_registro',
                                                conta_registro = '$conta_registro',
                                                cod_liquidacao = '$cod_liquidacao',
                                                data_registro = '$data_movimento'
                                            WHERE numboleto_itau = '$i_titulo'";
                            $qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");
                            $TIT_REG .= $i_titulo.' '.$vencimento.' R$ '.espaco($i_valor_titulo,14).' '.$nome_consumidor.'<BR>';
                            $cont_reg++;
                            $valor_total_registrado += $i_valor_titulo;

                        }else{
                            // Nao achei o titulo
                            $cont_nao++;
                            $NAO_ENC .= espaco($contrato,20).espaco('',3).espaco($i_titulo,20).' '.espaco($vencimento,10).espaco('',4).$i_valor_titulo."<br>";
                        }
                    }
                    
                    break;

                case '03' :
                case '24' : // ENTRADA REJEITADA

                    $i_titulox = str_pad($i_titulo,11,0,STR_PAD_LEFT);
                    $sql22 = "SELECT id FROM cs2.titulos WHERE numboleto = '$i_titulox'";
                    $qr22 = mysql_query($sql22,$conexao) or die ("ERRO: $sql22");
                    $qtd = mysql_num_rows($qr22);
                    if ( $qtd > 0 ){
                        
                        // Eh titulo de mensalidade
                        $id_titulo = mysql_result($qr22,0,'id');
                        $sql_update = " UPDATE cs2.titulos 
                                        SET 
                                            data_movimentacao = NOW()
                                        WHERE id = '$id_titulo'";
                        $qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");
                        if ( $cod_retorno != ''  ){
                            $nome_erro = '';
                            for( $j=1; $j <= 5; $j++  ){
                                if     ($j == 1 ) $pos_i = 0;
                                elseif ($j == 2 ) $pos_i = 2;
                                elseif ($j == 3 ) $pos_i = 4;
                                elseif ($j == 4 ) $pos_i = 6;
                                elseif ($j == 5 ) $pos_i = 8;

                                $cod_erro = substr($cod_retorno,$pos_i,2);
                                if ( $cod_erro > 0 ){
                                    $sql_2 = "SELECT descricao FROM cs2.boleto_codigo_ocorrencia
                                              WHERE banco = '341' and codigo = '$cod_erro' and nota = '20'";
                                    $qry_2 = mysql_query($sql_2,$conexao) or die ("ERRO: $sql_2");
                                    $nome_erro .= mysql_result($qry_2,0,'descricao');
                                }
                            }
                            $sql2 = "SELECT 
                                        b.login, a.valor, date_format(a.vencimento,'%d/%m/%Y') as vencimento
                                     FROM cs2.titulos a
                                     INNER JOIN base_web_control.webc_usuario b ON a.codloja = b.id_cadastro
                                     WHERE a.chavebol = '$contrato'
                                     LIMIT 1";
                            $qry_2 = mysql_query($sql2,$conexao) or die ("ERRO: $sql2");

                            $login    = trim(mysql_result($qry_2,0,'login'));

                            $vencimento = mysql_result($qry_2,0,'vencimento');
                            $vencimento = espaco($vencimento,10);
                            $valor      = mysql_result($qry_2,0,'valor');
                            $valor      = number_format($valor,2,',','.');
                            $valor      = espaco($valor,14);
                            $i_titulo   = espaco($i_titulo,16);

                            $TIT_NREGM .= "MENSALIDADE: $login - Boleto: $i_titulo - Vencimento: $vencimento - Valor: R$ $valor - Motivo: $nome_erro<BR>";
                        }
                    
                    }else{
                        
                        // Atualizando para reenvio no proximo lote
                        $sql_update = " UPDATE cs2.titulos_recebafacil 
                                        SET 
                                            data_alteracao = NOW()
                                        WHERE numboleto_itau = '$i_titulo'";
                        $qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");

                        if ( $cod_retorno != ''  ){
                            $nome_erro = '';
                            for( $j=1; $j <= 5; $j++  ){
                                if     ($j == 1 ) $pos_i = 0;
                                elseif ($j == 2 ) $pos_i = 2;
                                elseif ($j == 3 ) $pos_i = 4;
                                elseif ($j == 4 ) $pos_i = 6;
                                elseif ($j == 5 ) $pos_i = 8;

                                $cod_erro = substr($cod_retorno,$pos_i,2);
                                if ( $cod_erro > 0 ){
                                    $sql_2 = "SELECT descricao FROM cs2.boleto_codigo_ocorrencia
                                              WHERE banco = '341' and codigo = '$cod_erro' and nota = '20'";
                                    $qry_2 = mysql_query($sql_2,$conexao) or die ("ERRO: $sql_2");
                                    $nome_erro .= mysql_result($qry_2,0,'descricao');
                                }
                            }
                            $sql2 = "SELECT 
                                        cpfcnpj_devedor, valor, date_format(vencimento,'%d/%m/%Y') as vencimento
                                     FROM cs2.titulos_recebafacil
                                     WHERE chavebol = ".$contrato;
                            $qry_2 = mysql_query($sql2,$conexao) or die ("ERRO: $sql2");

                            $cpfcnpj    = trim(mysql_result($qry_2,0,'cpfcnpj_devedor'));

                            if ( $cpfcnpj == '' ){
                                $cpfcnpj    = espaco('Ctr('.$contrato.')',20);
                            }else{
                                if ( strlen($cpfcnpj) <= 11 )
                                    $cpfcnpj    = espaco(mascaraCpf($cpfcnpj),20);
                                else
                                    $cpfcnpj    = espaco(mascaraCnpj($cpfcnpj),20);
                            }
                            $vencimento = mysql_result($qry_2,0,'vencimento');
                            $vencimento = espaco($vencimento,10);
                            $valor      = mysql_result($qry_2,0,'valor');
                            $valor      = number_format($valor,2,',','.');
                            $valor      = espaco($valor,14);
                            $i_titulo   = espaco($i_titulo,16);

                            $TIT_NREG .= $i_titulo.' '.$vencimento.' R$ '.$valor.' '.$cpfcnpj.' '.$nome_consumidor.' - Motivo: '.$nome_erro.'<BR>';
                        }
                    }
                    break;

                case '09' : // BAIXA SIMPLES - BOLETO EXPIRADO
                        /*
                        expirado = 0 - Nao
                        expirado = 1 - Sim
                        */  
                    
                        // Verificando se o titulo é de mensalidade ou Boleto Emitido pelo Cliente
                        $i_titulox = str_pad($i_titulo,11,0,STR_PAD_LEFT);
                        $sql22 = "SELECT id FROM cs2.titulos WHERE numboleto = '$i_titulox'";
                        $qr22 = mysql_query($sql22,$conexao) or die ("ERRO: $sql22");
                        $qtd = mysql_num_rows($qr22);

                        if ( $qtd > 0 ){
                            // Eh titulo de mensalidade
                            $id_titulo = mysql_result($qr22,0,'id');
                            $sql_update = " UPDATE cs2.titulos 
                                            SET 
                                                expirado = '1',
                                                data_baixa_contabilidade = '$data_movimento'
                                            WHERE id = '$id_titulo'";
                            $qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");

                        }else{
                            
                            // Verificando se o titulo está ATIVO ou EXCLUIDO pelo cliente
                        
                            $sql2 = "SELECT 
                                        numdoc, cpfcnpj_devedor, valor, date_format(vencimento,'%d/%m/%Y') as vencimento
                                     FROM cs2.titulos_recebafacil
                                     WHERE numboleto_itau = '$i_titulo'";
                            $qry_2 = mysql_query($sql2,$conexao) or die ("ERRO: $sql2");
                            $numdoc     = mysql_result($qry_2,0,'numdoc');
                            $vencimento = mysql_result($qry_2,0,'vencimento');
                            $vencimento = espaco($vencimento,10);
                            $valor      = mysql_result($qry_2,0,'valor');
                            $valor2     = mysql_result($qry_2,0,'valor');
                            $valor      = number_format($valor,2,',','.');
                            $valor      = espaco($valor,14);

                            if ( $numdoc > 0){
                                $sql_update = " UPDATE cs2.titulos_recebafacil 
                                                SET 
                                                    expirado = '1',
                                                    data_baixa_contabilidade = '$data_movimento'
                                                WHERE numboleto_itau = '$i_titulo'";
                                $qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");

                            }else{
                                $sql_update = " UPDATE cs2.titulos_recebafacil_excluidos 
                                                SET 
                                                    expirado = '1',
                                                    data_baixa_contabilidade = '$data_movimento'
                                                WHERE numboleto_itau = '$i_titulo'";
                                $qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");

                                $sql2 = "SELECT 
                                            valor, date_format(vencimento,'%d/%m/%Y') as vencimento
                                         FROM cs2.titulos_recebafacil_excluidos
                                         WHERE numboleto_itau = '$i_titulo'";
                                $qry_2 = mysql_query($sql2,$conexao) or die ("ERRO: $sql2");
                                $vencimento = mysql_result($qry_2,0,'vencimento');
                                $vencimento = espaco($vencimento,10);
                                $valor      = mysql_result($qry_2,0,'valor');
                                $valor2     = mysql_result($qry_2,0,'valor');
                                $valor      = number_format($valor,2,',','.');
                                $valor      = espaco($valor,14);
                            }
                        }
                        $TIT_EXP .= $i_titulo.' '.$vencimento.' R$ '.$valor.'<BR>';
                        $cont_exp++;
                        $valor_total_exp += $valor2;
                        break;
                        
                default :
                    break;
            }
        }
    }
} // For

$saida = "<hr><br></div><div style='font-size: 10px; font-family: Courier New, Courier, monospace;'>";
$saida .= "Web Control".espaco('','15')."Cobranca Bancaria".espaco('','10')."BANCO ITAU".espaco('','10')."Data Pgto: $pagamento<br><br>";

if ( ! empty($erro_valor) ){
    $saida  .= "============================================================================================<br>
<font color='#FF0000'><b>TITULOS PAGOS COM VALOR SUPERIOR E R$ 5.000,00$b$b$b$b$b$b$b-$b$b$b$b$b$b$b$b$b N A O$b$b$b$b$b$b$b B A I X A D O S</b></font><br>
============================================================================================<br><br>";
    $saida .= $erro_valor."<br>";
}

    
if ( ! empty($erro) ){
    $saida  .= "TITULOS -- CORROMPIDOS =======================================================================<br><br>";
    $saida .= $erro;
}
    
if ( $cont_nao > 0 ){
    $saida  .= "TITULOS NAO ENCONTRADO =======================================================================<br><br>";
    $saida  .= "Cliente    No. Documento     No. Boleto         Vencimento   Vlr Titulo<br><br>";
    $saida  .= "$NAO_ENC<br>";
    $saida  .= "==============================================================================================<br><br>";
    $saida  .= "Listado(s) ".colocazeros($cont_nao,4)." Titulo(s)<br><br>";
}
    
$saida .= "TITULOS QUITADOS - MENSALIDADE =============================================================================<br><br>";
$saida .= espaco('No. Docum.',17).espaco('No. Boleto',11).' Vencimento '.espaco('Vlr Titulo',13)." Nome/Cidade <br><br>";
$saida .= "$QUITADO";
$saida .= "==============================================================================================<br>";
$saida .= "Listado(s) ".colocazeros($cont_liq,4)." Titulo(s)     Totalizando : ".number_format($valor_total_quitado,2,',', '.')."<br><br>";

if ( strlen($RECEBA_F) > 0 ){
    $saida  .= "<br><br>";
    $saida  .= "TITULOS QUITADOS - BOLETO/CREDIARIO/RECUPERE =========================================================================<br><br>";
    $saida  .= "
            <table>
                    $RECEBA_F
            </table>";
}

// TITULOS REGISTRADOS

if ( strlen($TIT_REG) > 0 ){
    $saida  .= "<br><br>";
    $saida  .= "TITULOS REGISTRADOS COM SUCESSO - BOLETO/CREDIARIO/RECUPERE =============================================================<br><br>";
    $saida  .= "
            <table>
                    $TIT_REG
            </table>";
}

if ( strlen($TIT_REGM) > 0 ){
    $saida  .= "<br><br>";
    $saida  .= "TITULOS REGISTRADOS COM SUCESSO - MENSALIDADE =============================================================<br><br>";
    $saida  .= "
            <table>
                    $TIT_REGM
            </table>";
}

// TITULOS NAO REGISTRADOS

if ( strlen($TIT_NREG) > 0 ){
    $saida  .= "<br><br>";
    $saida  .= "TITULOS NAO REGISTRADOS - BOLETO/CREDIARIO/RECUPERE =====================================================================<br><br>";
    $saida  .= "
            <table>
                    $TIT_NREG
            </table>";
}

if ( strlen($TIT_NREGM) > 0 ){
    $saida  .= "<br><br>";
    $saida  .= "TITULOS NAO REGISTRADOS - MENSALIDADE =====================================================================<br><br>";
    $saida  .= "
            <table>
                    $TIT_NREGM
            </table>";
}

if ( strlen($TIT_EXP) > 0 ){
    $saida  .= "<br><br>";
    $saida  .= "TITULOS EXPIRADOS =====================================================================<br><br>";
    $saida  .= "
            <table>
                    $TIT_EXP
            </table>";
    $saida .= "==============================================================================================<br>";
    $saida .= "Listado(s) ".colocazeros($cont_exp,4)." Titulo(s)     Totalizando : ".number_format($valor_total_exp,2,',', '.')."<br><br>";
}

$saida .= "</div>";
    
echo $saida;

include("class.phpmailer.php");
try {
        $mail = new PHPMailer();
        $mail->IsSendmail(); // telling the class to use SendMail transport
        $mail->IsSMTP(); //ENVIAR VIA SMTP
        $mail->Host = "10.2.2.7"; //SERVIDOR DE SMTP 
        $mail->SMTPAuth = true; //ATIVA O SMTP AUTENTICADO
        $mail->Username = "cpd@webcontrolempresas.com.br"; // EMAIL PARA SMTP AUTENTICADO
        $mail->Password = "#9%kxP*-11"; // SENHA DO EMAIL PARA SMTP AUTENTICADO
        $mail->From = "cpd@webcontrolempresas.com.br"; // E-MAIL DO REMETENTE 
        $mail->FromName = "CPD - Web Control"; // NOME DO REMETENTE
        $mail->AddAddress("administrativo@webcontrolempresas.com.br","Administrativo - Web Control"); // E-MAIL DO DESTINATARIO, NOME DO DESTINATARIO 
        $mail->AddBCC('luciano@webcontrolempresas.com.br', 'Luciano Mancini - Diretor de Tecnologia');
        $mail->WordWrap = 50; // ATIVAR QUEBRA DE LINHA
        $mail->IsHTML(true); // ATIVA MENSAGEM NO FORMATO HTML
        $mail->Subject = "Retorno Cobranca ITAU"; // ASSUNTO DA MENSAGEM
        $mail->Body = $saida; // MENSAGEM NO FORMATO HTML
        $mail->Send();
        echo "ATENCAO >>  Este relatorio foi enviado para o EMAIL : administrativo@webcontrolempresas.com.br</p>\n";
} catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
}

?>