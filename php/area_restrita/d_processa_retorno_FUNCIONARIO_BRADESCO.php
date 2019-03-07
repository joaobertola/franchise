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

$erro = '';
$linha  = file($arquivo);
$total  = count($linha); // Conta as linhas

for($i=0;$i<$total;$i++){
    # Cabecalho do Arquivo
    $lin = $linha[$i];

    if ( $i == 0 ){
        $sql = "select count(*) qtd from consulta.Controle_ccf where Tipo_Arq='RPGFU' and Arquivo = '$arquivo'";
        $qr_sql = mysql_query($sql,$conexao) or die ("ERRO: $sql");
        $qtd = mysql_result($qr_sql,0,'qtd');
        if ( empty($qtd) ) $qtd = '0';
        if ( $qtd > 0 ){
            echo "ATENCAO !!! Arquivo de RETORNO ( PAGAMENTO DE FUNCIONARIO ) ja foi processado";
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

            $bco     = substr($lin,95,3);
            $age     = substr($lin,98,4).'-'.substr($lin,102,1);
            $cta     = substr($lin,103,13).'-'.substr($lin,117,1);

            $descricao = "DEPOSITO C/C FUNCIONARIO ($bco/$age/$cta)";

            if ( $bco == '237' ) $comprova  = 'DEPOSITO COM SUCESSO (REALIZADO REMESSA PELO BANCO BRADESCO)';
            else $comprova  = 'DOC DEPOSITADO COM SUCESSO PELO BANCO BRADESCO';

            $comprova  = 'DOC DEPOSITADO COM SUCESSO PELO BANCO TRANSMISSOR';

            $nome    = substr($lin,17,30);
            $vlr     = substr($lin,204,13).'.'.substr($lin,217,2);

            $codloja = trim(substr($lin,440,10));
            $codloja = substr($codloja,5,50);

            // Verificando se e linha de retorno
            $retorno_antecipacao = trim(substr($lin,415,25));

            # ANALISE DE ERRO
            $conf_pgto = substr($lin,276,2);
            $erro = trim(substr($lin,278,10));

            if ( $conf_pgto == '01' and $erro == 'HA' ){
                # nao faz nada, pois nao foi pago mais est� agendado
            }else{

                if ( $conf_pgto == '01' and ($erro != 'HA' or $erro != 'HF') ){
                        $lista_erro .= "$conf_pgto : $erro -> ID : $codloja  - $nome - $bco - $age - $cta<br>";
                }else{
                    # COMANDO ==  02
                    $data_processamento = substr($lin,165,4).'-'.substr($lin,169,2).'-'.substr($lin,171,2);

                    if ( $conf_pgto == '02' and $erro == 'BW' ){
                        
                        // Deposito OKKKKKK
                        
                        $descricao = "Pagamento de SALARIO, VALE TRANSPORTE ou Outros ($bco/$age/$cta) - ($nome)";
                        
                        // localizando o funcionario
                        
                        $sql_fun = "SELECT id FROM cs2.funcionario WHERE cpf = '$doc' and nr_banco= '$bco'";
                        $qry_fun = mysql_query($sql_fun,$conexao) or die ("ERRO: $sql_fun");
                        $id_func = mysql_result($qry_fun,0,'id');

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
                        
                    }elseif ( $conf_pgto == '02' and $erro == 'JB' ){ 

                        // Deposito RUIMMMMM
                       
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
$sql_insert = " INSERT INTO consulta.Controle_ccf(Tipo_Arq,Arquivo,Data_Remessa,Hora_Inicio)
                VALUES('RPGFU','$arquivo',now(),now())";
$qr_insert = mysql_query($sql_insert,$conexao) or die ("ERRO: $sql_insert");
if ( !empty($lista_erro) ){
    $saida = "<hr><br></div><div style='font-size: 15px; font-family: Courier New, Courier, monospace;'>";
    $saida .= "Web Control Empresas".espaco('','5')."Retorno Funcion�rios - BANCO BRADESCO".espaco('','5')."Data Processamento: $data_processamento<br><br>";
    $saida  .= "ERROS LISTADOS ================================================================================<br><br>";
    $saida .= $lista_erro;
    $saida .= "</div>";
    echo $saida;
}else{
    $saida = "<hr><br></div><div style='font-size: 15px; font-family: Courier New, Courier, monospace;'>";
    $saida .= "Web Control Empresas".espaco('','15')."Retorno Fornecedor (CLIENTE)".espaco('','10')."BANCO BRADESCO".espaco('','10')."Data Processamento: $data_processamento<br><br>";
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
    $mail->FromName = "CPD - WebControl Empresas"; //NOME DO REMETENTE
    $mail->AddAddress("administrativo@webcontrolempresas.com.br","Administrativo - Web Control Empresas"); //E-MAIL DO DESINATARIO, NOME DO DESINATARIO 
    $mail->WordWrap = 50; // ATIVAR QUEBRA DE LINHA
    $mail->IsHTML(true); //ATIVA MENSAGEM NO FORMATO HTML
    $mail->Subject = "Retorno Funcionario"; //ASSUNTO DA MENSAGEM
    $mail->Body = $saida; //MENSAGEM NO FORMATO HTML
    $mail->Send();
    echo "ATENCAO >>  Este relatorio foi enviado para o EMAIL : administrativo@webcontrolempresas.com.br</p>\n";
} catch (phpmailerException $e) {
    echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
    echo $e->getMessage(); //Boring error messages from anything else!
}   

?>