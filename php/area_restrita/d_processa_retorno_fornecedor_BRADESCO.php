<?php

include("../../../validar2.php");

global $conexao,$arquivo;
conecex();

$erro = '';
$QUITADO = '';
$RECEBA_F = '';
$cont_nao = 0;
$cont_liq = 0;
$tarifa_doc = 10.00;

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
$erro = '';
$linha  = file($arquivo);
$total  = count($linha); // Conta as linhas

for($i=0;$i<$total;$i++){
    # Cabecalho do Arquivo
    $lin = $linha[$i];

    if ( $i == 0 ){
        $sql = "select count(*) qtd from consulta.Controle_ccf where Tipo_Arq='REMPG' and Arquivo = '$arquivo'";
        $qr_sql = mysql_query($sql,$conexao) or die ("ERRO: $sql");
        $qtd = mysql_result($qr_sql,0,'qtd');
        if ( empty($qtd) ) $qtd = '0';
        if ( $qtd > 0 ){
            echo "ATENCAO !!! Arquivo de RETORNO ( PAGAMENTO DE FORNECEDOR ) ja foi processado";
            exit;
        }
    }else{

        # Trailler do Arquivo
        if ( substr($lin,0,1) == '1' ){
                
            $tip = substr($lin,1,1);
            if ( $tip == 1 )
                $doc = substr($lin,2,9).substr($lin,15,2);
            else
                $doc = substr($lin,3,14);

            $bco = substr($lin,95,3);
            $age = substr($lin,99,4).'-'.substr($lin,103,1);
            $cta = substr($lin,104,13).'-'.substr($lin,117,1);

            $descricao = "DEPOSITO C/C ASSOCIADO ($bco/$age/$cta)";

            if ( $bco == '237' ) $comprova  = 'DEPOSITO COM SUCESSO (REALIZADO REMESSA PELO BANCO BRADESCO)';
            else $comprova  = 'DOC DEPOSITADO COM SUCESSO PELO BANCO BRADESCO';

            $comprova  = 'DOC DEPOSITADO COM SUCESSO PELO BANCO TRANSMISSOR';

            $nome    = substr($lin,17,30);
            $vlr     = substr($lin,204,13).'.'.substr($lin,217,2);

            $codloja = trim(substr($lin,440,10));
            $codloja = substr($codloja,5,50);

            // Verificando se � linha de retorno de ANCETICACAO_CREDITO
            $retorno_antecipacao = trim(substr($lin,415,25));

            # ANALISE DE ERRO
            $conf_pgto = substr($lin,276,2);
            $erro = trim(substr($lin,278,10));

            if ( $conf_pgto == '01' and $erro == 'HA' ){
                # nao faz nada, pois nao foi pago mais esta agendado
            }else{

                if ( $conf_pgto == '01' and ($erro != 'HA' or $erro != 'HF') ){
                    $lista_erro .= "$conf_pgto : $erro -> ID : $codloja  - $nome - $bco - $age - $cta<br>";
                }
                else{
                    # COMANDO ==  02
                    $data_processamento = substr($lin,165,4).'-'.substr($lin,169,2).'-'.substr($lin,171,2);

                    $sql_sdo = "SELECT saldo FROM cs2.contacorrente_recebafacil 
                                WHERE codloja= '$codloja' 
                                ORDER BY id DESC 
                                LIMIT 1";
                    $qr_sdo = mysql_query($sql_sdo,$conexao) or die ("ERRO: $sql_sdo");
                    $saldo = mysql_result($qr_sdo,0,'saldo');
                    
                    if ( $conf_pgto == '02' and $erro == 'BW' ){ // Deposito OKKKKKK

                        if ( $retorno_antecipacao == '' ){ // Pagamento a Fornecedor

                            if ( empty($saldo) ) $saldo = '0';
                            $saldo -= $vlr;

                            # registrando pagamento
                            $sql_insert = "INSERT INTO cs2.contacorrente_recebafacil
                                                ( 
                                                    codloja, data, datapgto, tarifa_bancaria, 
                                                    operacao, discriminacao, valor, comprovante, origem, saldo
                                                )
                                           VALUES
                                                (
                                                    '$codloja',now(),'$data_processamento','Null',
                                                    1,'$descricao','$vlr','$comprova','WEL','$saldo'
                                                )";
                            $qr_insert = mysql_query($sql_insert,$conexao) or die ("ERRO: $sql_insert");
                            $saldo -= $tarifa_doc; // Tarifa de transferencia
                            $sql_insert = "INSERT INTO cs2.contacorrente_recebafacil( codloja, data, datapgto, tarifa_bancaria, operacao, discriminacao, valor, origem, saldo ) VALUES('$codloja',now(),'$data_processamento','Null',1,'DOC - TRANSFERENCIA ENTRE BANCOS', '$tarifa_doc' ,'WEL','$saldo')";
                            $qr_insert = mysql_query($sql_insert,$conexao) or die ("ERRO: $sql_insert");

                        }else{ // Deposito de Antecipacao

                            $reg       = trim(substr($lin,415,35));
                            $dados     = explode('-',$reg);
                            $codloja   = $dados[0];
                            $protocolo = $dados[1];

                            # Verificando o saldo do cliente                        
                            $sql_sdo = "SELECT saldo FROM cs2.contacorrente_recebafacil 
                                        WHERE codloja= '$codloja' 
                                        ORDER BY id DESC 
                                        LIMIT 1";
                            $qr_sdo = mysql_query($sql_sdo,$conexao) or die ("ERRO: $sql_sdo");
                            $saldo = mysql_result($qr_sdo,0,'saldo');           
                            if ( empty($saldo) ) $saldo = '0';

                            # Buscando Logon do cliente
                            $sql_l = "  SELECT mid(a.logon,1,5) AS logon, b.nomefantasia FROM cs2.logon a
                                        INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
                                        WHERE a.codloja = '$codloja'";
                            $qry_l = mysql_query($sql_l,$conexao);
                            $logon = mysql_result($qry_l,0,'logon');
                            $nomefantasia = mysql_result($qry_l,0,'nomefantasia');
                            $descricao = "Antecipa&ccedil;&atilde;o ( $logon - $nomefantasia )";

                            $data_processamento = substr($lin,165,4).'-'.substr($lin,169,2).'-'.substr($lin,171,2);
                            $data_deposito = substr($lin,171,2).'/'.substr($lin,169,2).'/'.substr($lin,165,4);

                            $valor     = number_format($vlr,2,',','.');

                            $comprova  = "DOC DEPOSITADO COM SUCESSO PELO BANCO TRANSMISSOR<br><br>Data do Dep&oacute;sito: $data_deposito<br>Banco: $bco - Ag&ecirc;ncia: $age - Conta Corrente: $cta<br>Nome: $nome<br>Valor do Dep&oacute;sito: R$ $valor<br><br>Dep&oacute;sito realizado com sucesso.";
                            # Verificando o saldo na conta corrente recebafacil

                            # registrando deposito
                            $sql_acao = "INSERT INTO cs2.contacorrente_antecipacao
                                                (
                                                    codloja, protocolo, data, descricao, data_lancamento, 
                                                    valor, operacao, comprovante
                                                )
                                         VALUES
                                                (
                                                    '$codloja' , '$protocolo' , NOW() , '$descricao', '$data_processamento',
                                                    '$vlr' , '1' , '$comprova' 
                                                )";
                            $qry_acao = mysql_query($sql_acao,$conexao);
                            $sql_up = " UPDATE cs2.cadastro_emprestimo SET
                                                depositado_cta_cliente = 'S',
                                                descricao_deposito = '$comprova',
                                                data_deposito = NOW(),
                                                bco_origem_deposito = '237'
                                        WHERE protocolo = '$protocolo'";
                            $qr_up = mysql_query($sql_up,$conexao) or die ("ERRO: $sql_up");
                            # DEBITANDO O DOC na Extrato na conta corrente recebafacil
                            $saldo -= $tarifa_doc;
                            $sql_insert = "INSERT INTO cs2.contacorrente_recebafacil( codloja, data, datapgto, tarifa_bancaria, operacao, discriminacao, valor, origem, saldo ) VALUES('$codloja',now(),'$data_processamento','Null',1,'DOC - ANTECIPA&Ccedil;&Atilde;O DE CR&Eacute;DITOS - Protocolo: $protocolo', '$tarifa_doc' ,'WEL','$saldo')";
                            $qr_insert = mysql_query($sql_insert,$conexao) or die ("ERRO: $sql_insert");
                        }
                    }elseif ( $conf_pgto == '02' and $erro == 'JB' ){ // Deposito RUIMMMMM

                        if ( empty($saldo) ) $saldo = '0';
                        $saldo += $vlr;

                        $lista_erro .= "$conf_pgto : $erro -> ID : $codloja  - $nome - $bco - $age - $cta<br>";

                        # registrando ESTORNO
                        $descricao = "ESTORNO DE DEPOSITO (Inconsistencia de Dados Bancarios)";
                        $sql_insert = " INSERT INTO cs2.contacorrente_recebafacil
                                            ( 
                                                codloja, data, datapgto, tarifa_bancaria, 
                                                operacao, discriminacao, valor, comprovante, origem, saldo 
                                            )
                                        VALUES
                                            (
                                                '$codloja',now(),'$data_processamento','Null',
                                                0,'$descricao','$vlr','$comprova','WEL','$saldo'
                                            )";
                        $qr_insert = mysql_query($sql_insert,$conexao) or die ("ERRO: $sql_insert");
                        $saldo += $tarifa_doc;
                        $sql_insert = "INSERT INTO cs2.contacorrente_recebafacil( codloja, data, datapgto, tarifa_bancaria, operacao, discriminacao, valor, origem, saldo ) VALUES('$codloja',now(),'$data_processamento','Null',0,'ESTORNO DE DOC - TRANSFERENCIA ENTRE BANCOS', '$tarifa_doc' ,'WEL','$saldo')";
                        $qr_insert = mysql_query($sql_insert,$conexao) or die ("ERRO: $sql_insert");
                    }else{
                        # ERRO NAO � ESTORNO E NEM CONFIRMACAO
                        $lista_erro .= "$conf_pgto : $erro -> ID : $codloja  - $nome - $bco - $age - $cta ";
                    }
                }
            }
        }
    }
} # for

# Terminou processamento registrar movimento
$sql_insert = "INSERT INTO consulta.Controle_ccf(Tipo_Arq,Arquivo,Data_Remessa,Hora_Inicio)
        VALUES('REMPG','$arquivo',now(),now())";
$qr_insert = mysql_query($sql_insert,$conexao) or die ("ERRO: $sql_insert");
if ( !empty($lista_erro) ){
    $saida = "<hr><br></div><div style='font-size: 15px; font-family: Courier New, Courier, monospace;'>";
    $saida .= "Web Control Empresas".espaco('','8')."Retorno Fornecedor (CLIENTE)".espaco('','10')."BANCO BRADESCO".espaco('','10')."Data Processamento: $data_processamento<br><br>";
    $saida  .= "ERROS LISTADOS ================================================================================<br><br>";
    $saida .= $lista_erro;
    $saida .= "</div>";
    echo $saida;
}else{
    $saida = "<hr><br></div><div style='font-size: 15px; font-family: Courier New, Courier, monospace;'>";
    $saida .= "Web Control Empresas".espaco('','8')."Retorno Fornecedor (CLIENTE)".espaco('','10')."BANCO BRADESCO".espaco('','10')."Data Processamento: $data_processamento<br><br>";
    $saida  .= "TODOS OS REGISTROS FORAM PROCESSADOS, POREM NENHUM ERRO DE RETORNO FOI ENCONTRADO<br>";     
    $saida .= "</div>";
    echo $saida;
}
echo "fim";
exit;

    
# enviando email dos erros
include("class.phpmailer.php");
try {
    $mail = new PHPMailer();
    $mail->IsSendmail(); // telling the class to use SendMail transport
    $mail->IsSMTP(); //ENVIAR VIA SMTP
    $mail->Host = "10.2.2.7"; //SERVIDOR DE SMTP 
    $mail->SMTPAuth = true; //ATIVA O SMTP AUTENTICADO
    $mail->Username = "cpd@webcontrolempresas.com.br"; //EMAIL PARA SMTP AUTENTICADO
    $mail->Password = "#9%kxP*-11"; //SENHA DO EMAIL PARA SMTP AUTENTICADO
    $mail->From = "cpd@webcontrolempresas.com.br"; //E-MAIL DO REMETENTE 
    $mail->FromName = "CPD - Web Control Empresas"; //NOME DO REMETENTE
    $mail->AddAddress("administrativo@webcontrolempresas.com.br","Administrativo - Web Control Empresas"); //E-MAIL DO DESINAT�RIO, NOME DO DESINAT�RIO 
    $mail->WordWrap = 50; // ATIVAR QUEBRA DE LINHA
    $mail->IsHTML(true); //ATIVA MENSAGEM NO FORMATO HTML
    $mail->Subject = "Retorno Fornecedor ( CLIENTE )"; //ASSUNTO DA MENSAGEM
    $mail->Body = $saida; //MENSAGEM NO FORMATO HTML
    $mail->Send();
    echo "ATENCAO >>  Este relatorio foi enviado para o EMAIL : administrativo@webcontrolempresas.com.br</p>\n";
} catch (phpmailerException $e) {
    echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
    echo $e->getMessage(); //Boring error messages from anything else!
}   

?>