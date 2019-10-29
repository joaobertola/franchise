<?php

include("../../../validar2.php");

global $conexao,$arquivo;
conecex();

$erro = '';
$QUITADO = '';
$RECEBA_F = '';
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
$REG_OK = '';
for($i=0;$i<$total;$i++){
    # Cabe�alho do Arquivo
    $lin = $linha[$i];

    if ( $i == 0 ){
        $sql = "select count(*) qtd from consulta.Controle_ccf where Tipo_Arq='RPGFU' and Arquivo = '$arquivo'";
        $qr_sql = mysql_query($sql,$conexao) or die ("ERRO: $sql");
        $qtd = mysql_result($qr_sql,0,'qtd');
        if ( empty($qtd) ) $qtd = '0';
        if ( $qtd > 0 ){
            echo "ATENCAO !!! Arquivo de RETORNO ( PAGAMENTO DE FUNCIONARIOS ) ja foi processado";
            exit;
        }
    }else{

        # Trailler do Arquivo
        $bco = substr($lin,20,3);
        $age = substr($lin,24,4);
        $cta = (substr($lin,29,12)*1).'-'.trim(substr($lin,41,2));

        $nome    = trim(substr($lin,43,50));
        $vlr     = substr($lin,119,13).'.'.substr($lin,132,2);
        $descricao = "Pagamento de SALARIO, VALE TRANSPORTE ou Outros ($bco/$age/$cta) - ($nome)";
        $id_func = trim(substr($lin,177,10));

        if ( substr($lin,13,1) == 'A' ){
            # ANALISE DE ERRO
            $conf_pgto = substr($lin,230,2);
            $erro = trim(substr($lin,232,10));
            $data_processamento = substr($lin,158,4).'-'.substr($lin,156,2).'-'.substr($lin,154,2);

            if ( $conf_pgto == 'BD' and $erro == '' ){
                # nao faz nada, pois nao foi pago, porem est� agendado
            }else{

                if ( $conf_pgto <> 'BD' and $erro != '' ){
                    $descricao_erro = retorno_erro($erro);
                    $lista_erro .= "ID : $id_func  - $nome - $bco - $age - $cta<br>$descricao_erro<br>";
                }else{

                    if ( $conf_pgto == '00' ){

                        $valor = number_format($vlr,2);
                        $REG_OK .= str_pad($id_func, 4, '0', STR_PAD_LEFT);
                        $REG_OK .= str_pad(' - R$ '.$valor, 20, ' ', STR_PAD_RIGHT);
                        $REG_OK .= ' - '.$nome.'<br>';

                        # registrando pagamento
                        $sql_insert = " INSERT INTO cs2.contacorrente_funcionario
                                            ( 
                                            id_func, data_pgto, operacao, descricao, valor_pgto
                                            )
                                        VALUES
                                            (
                                            '$id_func','$data_processamento','1','$descricao','$vlr'
                                            )";
                        $qr_insert = mysql_query($sql_insert,$conexao) or die ("ERRO: $sql_insert");

                    }elseif ( $conf_pgto == 'DV' ){

                        $lista_erro .= "$conf_pgto : $erro -> ID : $codloja  - $nome - $bco - $age - $cta<br>";
                        # registrando ESTORNO
                        $descricao = "ESTORNO DE DEPOSITO (Inconsistencia de Dados Bancarios)";
                        $sql_insert = " INSERT INTO cs2.contacorrente_funcionario
                                            ( 
                                            id_func, data_pgto, operacao, descricao, valor_pgto
                                            )
                                        VALUES
                                            (
                                            '$id_func',now(),'0','$descricao','$vlr'
                                            )";
                        $qr_insert = mysql_query($sql_insert,$conexao) or die ("ERRO: $sql_insert");

                    }else{
                        # ERRO NAO EH ESTORNO E NEM CONFIRMACAO
                        $lista_erro .= "$conf_pgto : $erro -> ID : $codloja  - $nome - $bco - $age - $cta ";
                    }
                }
            }
        }
    }
} # for

# Terminou processamento registrar movimento
$sql_insert = "INSERT INTO consulta.Controle_ccf(Tipo_Arq,Arquivo,Data_Remessa,Hora_Inicio)
        VALUES('RPGFU','$arquivo',now(),now())";
$qr_insert = mysql_query($sql_insert,$conexao) or die ("ERRO: $sql_insert");

if ( !empty($lista_erro) ){
    $saida = "<br></div><div style='font-size: 15px; font-family: Courier New, Courier, monospace;'>";
    $saida .= "Web Control Empresas".espaco('','5')."Retorno Funcion�rios - BANCO ITAU".espaco('','5')."Data Processamento: $data_processamento<br><br>";
    $saida  .= "ERROS LISTADOS ================================================================================<br><br>";
    $saida .= $lista_erro;
    $saida .= "</div>";
    echo $saida;
    $saida  = "<BR>===============================================================================================<br><br>";
    echo $saida;
}else{
    $saida = "<br></div><div style='font-size: 15px; font-family: Courier New, Courier, monospace;'>";
    $saida .= "Web Control Empresas".espaco('','5')."Retorno Funcion�rios - BANCO ITAU".espaco('','5')."Data Processamento: $data_processamento<br><br>";
    $saida .= "REGISTROS PROCESSADOS =========================================================================<br><br>";
    $saida .= $REG_OK;

    $saida .= "<BR>===============================================================================================<br><br>";
    $saida .= "TODOS OS REGISTROS FORAM PROCESSADOS, POREM NENHUM ERRO DE RETORNO FOI ENCONTRADO<br>";      
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
    $mail->From = "cpd@webcontrolempresas.com.br"; //E-MAIL DO REMETENTE 
    $mail->FromName = "CPD - Web Control Empresas"; //NOME DO REMETENTE
    $mail->AddAddress("administrativo@webcontrolempresas.com.br","Administrativo - Web Control Empresas"); //E-MAIL DO DESINAT�RIO, NOME DO DESINAT�RIO
    $mail->AddBCC("luciano@webcontrolempresas.com.br","Luciano Mancini"); //E-MAIL DO DESINATARIO, NOME DO DESINATARIO
    $mail->WordWrap = 50; // ATIVAR QUEBRA DE LINHA
    $mail->IsHTML(true); //ATIVA MENSAGEM NO FORMATO HTML
    $mail->Subject = "Retorno Funcionarios - Banco Itau"; //ASSUNTO DA MENSAGEM
    $mail->Body = $saida; //MENSAGEM NO FORMATO HTML
    $mail->Send();
    echo "<br><br>ATENCAO >>  Este relatorio foi enviado para o EMAIL : administrativo@webcontrolempresas.com.br</p>\n";
} catch (phpmailerException $e) {
    echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
    echo $e->getMessage(); //Boring error messages from anything else!
}

?>