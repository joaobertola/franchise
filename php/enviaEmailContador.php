<?php
/**
 * @file enviaEmailContador.php
 * @brief
 * @author ARLLON DIAS
 * @date 13/10/2016
 * @version 1.0
 **/
header('Content-Type: text/html; charset=utf-8');
//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

require_once('../../inform/lib/dompdf/dompdf_config.inc.php');
require_once('../../inform/lib/PHPMailer/PHPMailerAutoload.php');
require_once('../../inform/lib/PHPMailer/class.phpmailer.php');
require "connect/sessao.php";
require "connect/conexao_conecta.php";

$idCadastro = $_POST['idCadastro'];
$insc = $_POST['cnpj'];

$sqlCertificado = "SELECT
                            *
                        FROM certificadosclientes.certificado
                        WHERE cod_cliente = '$idCadastro'
                        AND cnpj = '$insc';";


$rsCert = mysql_query($sqlCertificado, $connf);
$certificado = 0;
if (mysql_num_rows($rsCert) > 0) {
    $certificado = 1;
}


$sqlHabReceita = "SELECT
                        liberacao_receita_nfc,
                        liberacao_receita_nfe
                    FROM cs2.cadastro
                    WHERE codLoja = '$idCadastro'
                    AND(liberacao_receita_nfc = 'S' AND liberacao_receita_nfe = 'S');
";

//echo $sqlHabReceita;
//die;
$rsHabReceita = mysql_query($sqlHabReceita, $con);
$habReceita = 0;
if (mysql_num_rows($rsHabReceita) > 0) {
    $habReceita = 1;
}

$sqlCSC = "SELECT
                        nfce_csc_token
                    FROM base_web_control.cadastro_imposto_padrao
                    WHERE id_cadastro = '$idCadastro'
                    WHERE nfce_csc_token IS NOT NULL AND nfce_csc_token ! = '';
";

//echo $sqlHabReceita;
//die;
$rsCSC = mysql_query($sqlCSC, $con);
$csc = 0;

if (!empty($rsCSC)) {
    if (mysql_num_rows($rsCSC) > 0) {
        $csc = 1;
    }
}

$sqlTributacao = "SELECT
                                    *
                                FROM base_web_control.cadastro_imposto_padrao
                                WHERE id_cadastro = '$idCadastro'
                                AND nfe_tipo_ambiente = 'P'
                                AND nfe_sequencia_nota != ''
                                AND nfe_sequencia_nota IS NOT NULL
                                AND nfce_tipo_ambiente = 'P'
                                AND nfce_csc_token IS NOT NULL
                                AND nfce_csc_token != ''
                                AND nfce_idtoken != ''
                                AND nfce_idtoken IS NOT NULL
                                AND nfce_data_ativacao IS NOT NULL
                                AND nfce_data_ativacao != ''
                                AND nfce_hora_ativacao IS NOT NULL
                                AND nfce_hora_ativacao != ''
                                AND nfce_sequencia_nota IS NOT NULL
                                AND nfce_sequencia_nota != ''
                                ";

$rsTrib = mysql_query($sqlTributacao, $con);
$tributacao = 0;


if (mysql_num_rows($rsTrib) > 0) {
    $tributacao = 1;
}
//echo $tributacao;
//die;
if ($_POST['tipoRegime'] == 'SN') {

    $assunto = 'Dados Fiscais - Regime Simples Nacional';

    $msg = '<meta charset="UTF-8">
Bom dia,<br>
<br>
Somos da WEB CONTROL EMPRESAS, empresa de Tecnologia e Sistemas a qual seu Cliente contratou.<br>
<br>
CNPJ: ' . $_POST['cnpj'] . '<br>
Raz&atilde;o Social: ' . $_POST['razaosoc'] . '<br>
Nome Fantasia: ' . $_POST['nomefantasia'] . '<br>
<br>
Pedimos gentilmente que nos forne&ccedil;a os dados abaixo solicitados para que possamos configurar a Nota Fiscal Eletr&ocirc;nica do cliente no sistema WEB CONTROL EMPRESAS.<br>
<br>
Favor encaminhar por gentileza:<br>
1&ordm; - Certificado A1 com senha:<br>
2&ordm; - C&oacute;digo CSC de produ&ccedil;&atilde;o com Data/Hora:<br>
3&ordm; - Inscri&ccedil;&atilde;o Municipal:<br>
4&ordm; - Se o Empres&aacute;rio j&aacute; emitia Nota Fiscal Eletr&ocirc;nica anteriormente, favor informar a sequencia que ele parou: NF-e = ____ NFC-e = ____<br>
<br>
Informar a o C&oacute;digo de Sa&iacute;da dos 4 impostos de Tributa&ccedil;&atilde;o SIMPLES NACIONAL - ICMS- IPI- PIS- COFINS<br>
<br>
ICMS :<br>
101 - Tributada com permiss&atilde;o de credito<br>
102 - Tributada sem permiss&atilde;o de credito<br>
103 - Isen&ccedil;&atilde;o de ICMS para faixa de receita bruta<br>
201 - Tributada com permiss&atilde;o de credito com cobran&ccedil;a de ICMS por ST<br>
202 - Tributada sem permiss&atilde;o de credito e com cobran&ccedil;a de ICMS por ST<br>
203 - Isen&ccedil;&atilde;o do ICMS para faixa de receita bruta e com cobran&ccedil;a de ICMS por ST<br>
300 - Imune<br>
400 - N&atilde;o tributada<br>
500 - ICMS cobrado anteriormente por ST ou por antecipa&ccedil;&atilde;o<br>
900 - Outros<br>
<br>
IPI:<br>
53 - Sa&iacute;da n&atilde;o tributada<br>
99 - Outras Sa&iacute;das<br>
<br>
PIS:<br>
01 - Opera&ccedil;&atilde;o tribut&aacute;vel “ base calculo = valor da opera&ccedil;&atilde;o al&iacute;quota normal "cumulativo/n&atilde;o cumulativo"<br>
07 - Opera&ccedil;&atilde;o Isenta da contribui&ccedil;&atilde;o<br>
08 - Opera&ccedil;&atilde;o sem incid&ecirc;ncia da contribui&ccedil;&atilde;o<br>
09 - Opera&ccedil;&atilde;o com suspens&atilde;o da contribui&ccedil;&atilde;o<br>
99 - Outras Opera&ccedil;&otilde;es<br>
<br>
COFINS:<br>
01 - Opera&ccedil;&atilde;o tribut&aacute;vel "base calculo = valor da opera&ccedil;&atilde;o al&iacute;quota normal "cumulativo/n&atilde;o cumulativo"<br>
02 - Opera&ccedil;&atilde;o tribut&aacute;vel "base calculo = valor da opera&ccedil;&atilde;o "al&iacute;quota diferenciada"<br>
03 - Opera&ccedil;&atilde;o tribut&aacute;vel "Base de calculo = quantidade vendida x al&iacute;quota por unidade de produto<br>
04 - Opera&ccedil;&atilde;o tribut&aacute;vel "Tributa&ccedil;&atilde;o monof&aacute;sica "al&iacute;quota zero"<br>
06 - Opera&ccedil;&atilde;o tribut&aacute;vel "Al&iacute;quota zero"<br>
07 - Opera&ccedil;&atilde;o Isenta da contribui&ccedil;&atilde;o<br>
99 - Outras Opera&ccedil;&otilde;es<br>
<br>
Preciso que seja feita a libera&ccedil;&atilde;o no site da Receita Estadual e depois a solicita&ccedil;&atilde;o do CSC (C&oacute;digo de Seguran&ccedil;a do Contribuinte).<br>
<br>
Segue CNPJ:  13.117.948/0001-73 (WORLD CLICK DESENVOLVEDORA DE SOFTWARES)<br>
<br>
Marcar c&oacute;digo: 72234<br>
<br>
Marcar op&ccedil;&otilde;es:<br>
- Nota Fiscal Eletr&ocirc;nica - NFE, Modelo 55- Emissor<br>
- Nota Fiscal de Consumidor Eletr&ocirc;nica - NFEC, Modelo 65- Emissor<br>
<br>
Atenciosamente,<br>
WebControl Empresas<br>
www.webcontrolempresas.com.br';
} elseif ($_POST['tipoRegime'] == 'N') {

    $assunto = 'Dados Fiscais - Regime Normal';
    $msg = '<meta charset="UTF-8">
Bom dia,<br>
<br>
Somos da WEB CONTROL EMPRESAS, empresa de Tecnologia e Sistemas a qual seu Cliente contratou.<br>
<br>
CNPJ: ' . $_POST['cnpj'] . '<br>
Raz&atilde;o Social: ' . $_POST['razaosoc'] . '<br>
Nome Fantasia: ' . $_POST['nomefantasia'] . '<br>
<br>
Pedimos gentilmente que nos forne&ccedil;a os dados abaixo solicitados para que possamos configurar a Nota Fiscal Eletr&ocirc;nica do cliente no sistema WEB CONTROL EMPRESAS.<br>
<br>
Favor encaminhar por gentileza:<br>
1&ordm; - Certificado A1 com senha:<br>
2&ordm; - C&oacute;digo CSC de produ&ccedil;&atilde;o com Data/Hora:<br>
3&ordm; - Inscri&ccedil;&atilde;o Municipal:<br>
4&ordm; - Se o Empres&aacute;rio j&aacute; emitia Nota Fiscal Eletr&ocirc;nica anteriormente, favor informar a sequencia que ele parou: NF-e = ____ NFC-e = ____<br>
<br>
Informar a o C&oacute;digo de Sa&iacute;da dos 4 impostos de Tributa&ccedil;&atilde;o REGIME NORMAL - ICMS- IPI- PIS- COFINS<br>
<br>
ICMS :<br>
0 - Tributada Integralmente<br>
10 - Tributada com cobran&ccedil;a do ICMS por ST<br>
10 - Tributada com cobran&ccedil;a do ICMS por ST (com partilha do ICMS entre a UF de origem e o UF de destino)<br>
20 - Com redu&ccedil;&atilde;o da Base de Calculo<br>
30 - Isenta ou n&atilde;o tributada e com cobran&ccedil;a do ICMS por ST<br>
40 - Isenta<br>
41 - N&atilde;o tributada<br>
41 - N&atilde;o tributada (ICMSST devido para a UF de destino, nas opera&ccedil;&otilde;es interestaduais dos produtos que tiverem reten&ccedil;&atilde;o..)<br>
50 - Suspens&atilde;o<br>
51 - Deferimento<br>
60 - Cobrado anteriormente por ST<br>
70 - Com redu&ccedil;&atilde;o da base de calculo e cobran&ccedil;a do ICMS por ST<br>
90 - Outras<br>
<br>
IPI:<br>
53 - Sa&iacute;da n&atilde;o tributada<br>
99 - Outras Sa&iacute;das<br>
<br>
PIS:<br>
01 - Opera&ccedil;&atilde;o tribut&aacute;vel “ base calculo = valor da opera&ccedil;&atilde;o al&iacute;quota normal "cumulativo/n&atilde;o cumulativo"<br>
07 - Opera&ccedil;&atilde;o Isenta da contribui&ccedil;&atilde;o<br>
08 - Opera&ccedil;&atilde;o sem incid&ecirc;ncia da contribui&ccedil;&atilde;o<br>
09 - Opera&ccedil;&atilde;o com suspens&atilde;o da contribui&ccedil;&atilde;o<br>
99 - Outras Opera&ccedil;&otilde;es<br>
<br>
COFINS:<br>
01 - Opera&ccedil;&atilde;o tribut&aacute;vel “ base calculo = valor da opera&ccedil;&atilde;o al&iacute;quota normal "cumulativo/n&atilde;o cumulativo"<br>
02 - Opera&ccedil;&atilde;o tribut&aacute;vel “ base calculo = valor da opera&ccedil;&atilde;o "al&iacute;quota diferenciada"<br>
03 - Opera&ccedil;&atilde;o tribut&aacute;vel “ Base de calculo = quantidade vendida x al&iacute;quota por unidade de produto<br>
04 - Opera&ccedil;&atilde;o tribut&aacute;vel “ Tributa&ccedil;&atilde;o monof&aacute;sica "al&iacute;quota zero"<br>
06 - Opera&ccedil;&atilde;o tribut&aacute;vel “Al&iacute;quota zero"<br>
07 - Opera&ccedil;&atilde;o Isenta da contribui&ccedil;&atilde;o<br>
99 - Outras Opera&ccedil;&otilde;es<br>
<br>
Preciso que seja feita a libera&ccedil;&atilde;o no site da Receita Estadual e depois a solicita&ccedil;&atilde;o do CSC (C&oacute;digo de Seguran&ccedil;a do Contribuinte).<br>
<br>
Segue CNPJ:  13.117.948/0001-73 (WORLD CLICK DESENVOLVEDORA DE SOFTWARES)<br>
<br>
Marcar c&oacute;digo: 72234<br>
<br>
Marcar op&ccedil;&otilde;es:<br>
- Nota Fiscal Eletr&ocirc;nica - NFE, Modelo 55- Emissor<br>
- Nota Fiscal de Consumidor Eletr&ocirc;nica - NFEC, Modelo 65- Emissor<br>
<br>
Atenciosamente,<br>
WebControl Empresas<br>
www.webcontrolempresas.com.br<br>
 ';
} else {
    $assunto = 'URGENTE!! Pendências para Habilitação de NFe / NFCe';
    $msg = '<meta charset="UTF-8">
Sr(a) Contador(a),<br>
<br>
Nós da WEB CONTROL EMPRESAS estamos realizando a configuração do sistema para emissão de Notas eletrônicas e Notas Fiscais eletrônicas do Consumidor do seu cliente ' . $_POST['razaosoc'] . ' /
' . $_POST['cnpj'] . ', mas infelizmente consta em nossos registros pendências que são da sua responsabilidade:<br>
<br>
<br>';

    if ($certificado == 0) {
        $msg .= 'Certificado Digital - Modelo A1.<br>';
    }
    if($tributacao == 0){
        $msg .= 'Informações sobre dados fiscais / impostos.<br>';
    }
    if($habReceita == 0){
        $msg .= 'Liberação na REceita Estadual do Modelo 55 e 65 do emissor.<br>';
    }
    if($csc == 0){
        $msg .= 'CSC - Código de Segurança do Contribuinte.<br>';
    }
    $msg .= '
<br>
Atenciosamente,<br>
WebControl Empresas<br>
www.webcontrolempresas.com.br<br>';

}


$txt_tmp = "$msg";
$txt_tmp .= "                                  ";
$txt_tmp .= "                                  ";
$txt_tmp .= "                                  ";
$txt_tmp .= "                                  ";

$configuracao = "$txt_tmp";

$to = $_POST['email'];

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->IsHTML(true);
$mail->CharSet = 'utf-8';        // Define o charset da mensagem
$mail->SMTPAuth = true;                // Permitir autentica??o SMTP
$mail->Host = '10.2.2.7';             // Define o servidor SMTP
$mail->Port = 25;
$mail->Subject = $assunto;
$mail->Body = utf8_decode($configuracao);
$mail->Username = "tecnologia@webcontrolempresas.com.br";  // SMTP conta de usu?rio
$mail->Password = "informbrasil";  // SMTP conta senha
$mail->From = 'atendimento2@webcontrolempresas.com.br';
if ($_POST['lista'] == 1) {
    $mail->From = 'atendimento1@webcontrolempresas.com.br';
}

$mail->FromName = 'Web Control Empresas'; // Adiciona um "From" endere?o
$mail->AddAddress($_POST['email'], '');  // Adiciona um "To" endere?o
/*
 salvar o arquivo antes de enviar
 colocar na variavel o diretorio onde est? o arquivo

 ex:  ./boleto/boletoxxxx.pdf
 # Anexando o arquivo

 */
$idCadastro = $_POST['idCadastro'];
$verifica = $mail->Send();//envia o email

$sqlEnviaEmail = "UPDATE cs2.atendimento_cadastros SET envio_email = 'S' WHERE id_cadastro = '$idCadastro'";
$qry = mysql_query($sqlEnviaEmail, $con);
//echo $sqlEnviaEmail;

echo json_encode(0);