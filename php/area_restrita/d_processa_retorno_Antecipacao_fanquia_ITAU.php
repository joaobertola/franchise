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

function retorno_erro($erro){
    global $conexao;
    $tam = strlen($erro);
    $loop = $tam / 2;
    for ( $i = 1 ; $i <= $loop ; $i++){
        if     ( $i == 1 ) $pos = 0;
        elseif ( $i == 2 ) $pos = 2;
        elseif ( $i == 3 ) $pos = 4;
        elseif ( $i == 4 ) $pos = 6;
        elseif ( $i == 5 ) $pos = 8;
        $codigo = substr($erro,$pos,2);
        $sql_erro = "SELECT descricao FROM cs2.ocorrencias_retorno_itau WHERE codigo = '$codigo'";
        $qry_erro = mysql_query($sql_erro,$conexao);
        $retorno_erro .= 'ERRO: '.$codigo.' - '.mysql_result($qry_erro,0,'descricao')."<br>";
    }
    return $retorno_erro;
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
        $bco        = substr($lin,20,3);
        $age        = substr($lin,24,4);
        $cta        = (substr($lin,29,12)*1).'-'.trim(substr($lin,41,2));
        $nome       = substr($lin,43,50);
        $vlr        = substr($lin,119,13).'.'.substr($lin,132,2);
        $vlr        = $vlr * 1;
        $valor      = number_format($vlr,2,',','.');

        $quem       = trim(substr($lin,177,20));
        $Q          = explode('-',$quem);
        $id_frq     = $Q[0];
        $protocolo  = trim($Q[1]);

        if ( substr($lin,13,1) == 'A' ){
            # ANALISE DE ERRO
            $conf_pgto = substr($lin,230,2);
            $erro = trim(substr($lin,232,10));
            $data_processamento = substr($lin,158,4).'-'.substr($lin,156,2).'-'.substr($lin,154,2);

            if ( $conf_pgto == 'BD' and $erro == '' ){
                    # nao faz nada, pois nao foi pago mais est� agendado
            }else{
    
                if ( $conf_pgto <> 'BD' and $erro != '' ){
                    $descricao_erro = retorno_erro($erro);
                    $lista_erro .= "ID : $codloja  - $nome - $bco - $age - $cta<br>$descricao_erro<br><hr>";
                }else{
                        
                    if ( $conf_pgto == '00' ){

                        # registrando pagamento
                        $sql = "SELECT fantasia FROM cs2.franquia WHERE id = $id_frq";
                        $qry = mysql_query($sql,$conexao);
                        $fantasia = mysql_result($qry,0,'fantasia');

                        $descricao = "Antecipa&ccedil;&atilde;o ( $id_frq - $fantasia )";
                        $comprova  = "DOC DEPOSITADO COM SUCESSO PELO BANCO TRANSMISSOR<br><br>Data do Dep&oacute;sito: $data_deposito<br>Banco: $bco - Ag&ecirc;ncia: $age - Conta Corrente: $cta<br>Nome: $nome<br>Valor do Dep&oacute;sito: R$ $valor<br><br>Dep&oacute;sito realizado com sucesso.";

                        $sql_acao = "INSERT INTO cs2.contacorrente_antecipacao
                                        (
                                            codloja, protocolo, data, descricao, data_lancamento, 
                                            valor, operacao, comprovante, cliente_franquia
                                        )
                                     VALUES
                                        (
                                            '$id_frq' , '$protocolo' , NOW() , '$descricao', '$data_processamento',
                                            '$vlr' , '1' , '$comprova' , 'F'
                                        )";
                        $qry_acao = mysql_query($sql_acao,$conexao);

                        $sql_up = " UPDATE cs2.cadastro_emprestimo_franquia SET
                                            depositado_cta_cliente = 'S',
                                            descricao_deposito = '$comprova',
                                            data_deposito = NOW(),
                                            bco_origem_deposito = '237'
                                    WHERE protocolo = '$protocolo'";
                        $qr_up = mysql_query($sql_up,$conexao) or die ("ERRO: $sql_up");

                        # DEBITANDO O DOC na Extrato na conta corrente recebafacil
                        $sql_insert = " INSERT INTO cs2.contacorrente( franqueado, data, operacao, discriminacao, valor )
                                        VALUES('$id_frq',now(),'1','DOC - ANTECIPA&Ccedil;&Atilde;O DE REPASSE - Protocolo: $protocolo', '$tarifa_doc')";
                        $qr_up = mysql_query($sql_insert,$conexao) or die ("ERRO: $sql_insert");
                        
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
$sql_insert = " INSERT INTO consulta.Controle_ccf(Tipo_Arq,Arquivo,Data_Remessa,Hora_Inicio)
                VALUES('REMPG','$arquivo',now(),now())";
$qr_insert = mysql_query($sql_insert,$conexao) or die ("ERRO: $sql_insert");

if ( !empty($lista_erro) ){
    $saida = "<hr><br></div><div style='font-size: 15px; font-family: Courier New, Courier, monospace;'>";
    $saida .= "Inform System".espaco('','5')."Retorno Fornecedor (FRANQUIA) - BANCO ITAU".espaco('','5')."Data Processamento: $data_processamento<br><br>";
    $saida  .= "ERROS LISTADOS ================================================================================<br><br>";
    $saida .= $lista_erro;
    $saida .= "</div>";
    echo $saida;
    $saida  = "<BR>===============================================================================================<br><br>";
    echo $saida;
}else{
    $saida = "<hr><br></div><div style='font-size: 15px; font-family: Courier New, Courier, monospace;'>";
    $saida .= "Inform System".espaco('','5')."Retorno Fornecedor (FRANQUIA) - BANCO ITAU".espaco('','5')."Data Processamento: $data_processamento<br><br>";
    $saida  .= "TODOS OS REGISTROS FORAM PROCESSADOS, POREM NENHUM ERRO DE RETORNO FOI ENCONTRADO<br>";     
    $saida .= "</div>";
    echo $saida;
}

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
    $mail->From = "cpd@informsystem.com.br"; //E-MAIL DO REMETENTE 
    $mail->FromName = "CPD - Web Control"; //NOME DO REMETENTE
    $mail->AddAddress("administrativo@webcontrolempresas.com.br","Administrativo - Web Control"); //E-MAIL DO DESINAT�RIO, NOME DO DESINAT�RIO 
    $mail->WordWrap = 50; // ATIVAR QUEBRA DE LINHA
    $mail->IsHTML(true); //ATIVA MENSAGEM NO FORMATO HTML
    $mail->Subject = "Retorno DE ANTECIPACAO DE REPASSE ( FRANQUIA ) - Banco Itau"; //ASSUNTO DA MENSAGEM
    $mail->Body = $saida; //MENSAGEM NO FORMATO HTML
    $mail->Send();
    echo "ATENCAO >>  Este relatorio foi enviado para o EMAIL : administrativo@webcontrolempresas.com.br</p>\n";
} catch (phpmailerException $e) {
    echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
    echo $e->getMessage(); //Boring error messages from anything else!
}   

?>