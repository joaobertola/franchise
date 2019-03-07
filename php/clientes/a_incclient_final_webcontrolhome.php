<?php

error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set("display_errors", 1);
/* * *  
 * 
 *  DECLARAÇÃO DE FUNÇÕES E IMCLUDES 
 * 
 * * * */

#require "../connect/sessao.php";
require "../connect/conexao_conecta.php";
require "../connect/funcoes.php";
require "class.smtp_1.php";

function enviaErroCpd($p_parametro) {
    $data = date("d/m/Y");
    $hora = date("H:m:s");
    $smtp = new Smtp("10.2.2.7"); // host do servidor SMTP 
    $smtp->user = "financeiro@webcontrolempresas.com.br"; // usuario do servidor SMTP
    $smtp->pass = "infsys321"; // senha dousuario do servidor SMTP
    $smtp->debug = true; // ativar a autenticão SMTP
    $to = "erro_sistema@webcontrolempresas.com.br";
    $from = "erro_sistema@webcontrolempresas.com.br";
    $assunto = "Erro Cadastro de Cliente ";
    $msg .= "Data: $data \n ";
    $msg .= "Hora: $hora \n";
    $msg .= "Local do SQL: $p_parametro";
    $smtp->Send($to, $from, $assunto, $msg);
}

function substitui_acentos($value) {
    $trocaeste = array("(", ")", "'", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", ";", "'", "�");
    $poreste = array("", "", "", "O", "C", "U", "U", "O", "O", "O", "O", "A", "A", "A", "A", "E", "I", "", "", "");
    $value = str_replace($trocaeste, $poreste, $value);
    $value = strtoupper($value);
    return $value;
}

/* *  *      
 * 
 *                          DECLARAÇÃO DE VÁRIAVEIS    
 * 
 * 
 *  *  */
$data = date('Y-m-d H:i:s');
/* * *      
 * 
 * Informações da Empresa    
 * 
 * * */
$razaosoc = substitui_acentos(str_replace("'", "", $_POST['razaosoc']));
$nomefantasia = substitui_acentos(str_replace("'", "", $_POST['nomefantasia']));
$fone = str_replace(array(' ', '-'), '', $_POST['fone']);
$fone_res = $fone;
$celular = str_replace(array(' ', '-'), '', $_POST['celular']);
$email = $_POST['email'];
$insc = $_POST['insc'];
$Tipo = 0;
if (strlen($insc > 11)) {
    $Tipo = 1;
}

/* * * 
 * 
 *   Informações Socio 1        
 * 
 * * */
$socio1 = substitui_acentos($_POST['socio1']);
$cpfsocio1 = str_replace(array('.', '-'), '', $_POST['cpfsocio1']);

/* * *
 * 
 *   Informações de Localização        
 * 
 * * */
$cep = str_replace('-', '', $_POST['cep']);
$uf = $_POST['uf'];
$localidade = substitui_acentos($_POST['cidade']);
$bairro = substitui_acentos($_POST['bairro']);
$logradouro = substitui_acentos($_POST['end']);
$numero = substitui_acentos($_POST['numero']);
$complemento = substitui_acentos($_POST['complemento']);

/* * *    
 * 
 *   Informações Fixas para Configurar cadastro fixo        
 * 
 * * */
$atendente_resp = 1;                            //  Informações para Cadastro 
$pacote = 241;                                  //  Pacote 241 - cs2.tabela_valor = categoria 33
$franqueado = 1;                                //  Franqueado Padrão - 1 - Franquia Curitiba I (sede)
$agendador = 2083;                              //  Agendador WEB
$vendedor = 2083;                               //  Vendedor WEB
$fatura = 1;                                    //  Referencia FATURA
$origem = '';                                   //  Origem Cadastro
$assinatura = 33;                               //  Categoria do PACOTE

/* * *   
 * 
 *    DADOS DO PACOTE
 * 
 * * */
$tx_mens = $_POST['tx_mens'];                   //  Valor Pacote - 0.00 / 69.90 / 79.90 
$tx_adesao = 0;                                 //  Adesão 0

/* * *   
 * 
 *                          VERIFICA TELEFONE      
 * 
 * * */
$servidor = 'http://consultaoperadora.telein.com.br/sistema/consultas_resumidas.php';
$dadosEnv = 'chave=8d1b12d23b5362695071&numeros=' . $celular;
$ch = curl_init();
//endereço para envio do post
curl_setopt($ch, CURLOPT_URL, $servidor);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// envio do parametros
curl_setopt($ch, CURLOPT_POSTFIELDS, $dadosEnv);
$conteudo = curl_exec($ch);
if (curl_errno($ch)) {
    print curl_error($ch);
} else {
    curl_close($ch);
}

$arrRetorno = explode('#', $conteudo);
$idOperadora = $arrRetorno[0];
#FIM VALIDA TELEFONE


/*
 * 
 *                          VALIDA O CNPJ 
 *
 */
$sql8 = "SELECT COUNT(*) quant FROM cs2.cadastro WHERE insc='$insc'";
$ql8 = mysql_query($sql8, $con);
$resp = mysql_fetch_array($ql8);
$qtd = $resp["quant"];
//verifica se tem cnpj duplicado
if ($qtd > 0) {
    echo "<script>alert(\"CNPJ Cadastrado para outro cliente, favor verificar!\");</script>";
    exit;
}

/* * *
 * 
 *                          CADASTRO EMPRESA
 *                          cs2.cadastro
 * 
 * * */
$mensal = 'exp30dias';
if ($tx_mens > 0) {
    $mensal = 'Mensal';
    $tx_adesao = 200;
}

$comando = "
    INSERT INTO cs2.cadastro
        (
                atendente_resp, razaosoc, insc, nomefantasia, uf, cidade, bairro, end, numero, complemento, cep, 
                fone, email, tx_mens, tx_adesao, debito, diapagto, id_franquia, dt_cad, sitcli, classificacao, 
                celular, fone_res, socio1, cpfsocio1, emissao_financeiro, 
                pendencia_contratual, id_consultor, origem, qtd_acessos, hora_cadastro, id_agendador, id_operadora,nfce,nfe,liberar_nfe
        )
    VALUES
        (
                '$atendente_resp', '$razaosoc', '$insc', '$nomefantasia', '$uf', '$localidade', '$bairro', 
                '$logradouro', '$numero', '$complemento', '$cep', '$fone', '$email', '$tx_mens', '$tx_adesao', 
                'B', '30', '$franqueado', now(), '0', '$mensal', '$celular', 
                '$fone_res', '$socio1', '$cpfsocio1', '$fatura', '1', '$vendedor', '$origem', 
                '0', now(), '$agendador','$idOperadora','S','S','S'
        )
    ";


$res = mysql_query($comando, $con);
$codloja = mysql_insert_id($con);
if (!$res) {
    echo "<script>alert(\"Erro na inserção do cliente, entre em contato com o Departamento de Informatica !\");</script>";
    exit;
}

// registrando log
$teste = str_replace(chr(39), '', $comando);
$sql = "INSERT INTO cs2.sql_cadastro(comando_sql,datahora) VALUES ('$teste',now())";
mysql_query($sql, $con);

/* * *
 * 
 *                      GERA A SENHA
 * 
 * * */
require "./senha_aleatoria_webcontrolhome.php";

//isto serve para incrementar o �ltimo valor do c�digo
$conecta = "SELECT (logon + 1) as logon FROM cs2.controle";
$resposta = mysql_query($conecta, $con);
$codigo = mysql_result($resposta, 0, 'logon');
$login = mysql_result($resposta, 0, 'logon');

$sai = false;
do {
    $sql = "SELECT COUNT(*) qtd FROM cs2.logon WHERE mid(logon,1,5)='$codigo'";
    $ql8 = mysql_query($sql, $con);
    $consulta = mysql_fetch_array($ql8);
    $qtd = $consulta["qtd"];
    if ($qtd == 0) {
        $sai = true;
        $logon = $codigo . 'S' . $senha;
    } else {
        $codigo++;
    }
} while ($sai == false);

// atualizando na tabela controle o ultimo codigo gerado
$conecta = "UPDATE cs2.controle SET logon = $codigo";
$resposta = mysql_query($conecta, $con);
if (!$resposta) {
    echo "<script>alert(\"Erro ao atualizar o controle,  entre em contato com o Departamento de Informatica !\");</script>";
    enviaErroCpd("Erro ao atualizar o controle linha 200, codlloja=$codloja, SQL=$sql");
    exit;
}

/* * *
 * 
 *                  CRIAR LOGIN E SENHA
 * 
 * * */
$command = "INSERT INTO cs2.logon (codloja, logon, dt_atv) VALUES ('$codloja', '$logon', '$data')";
$result = mysql_query($command, $con);
if (!$result) {
    echo "<script>alert(\"Erro ao criar o Logon,  entre em contato com o Departamento de Informatica !\");</script>";
    enviaErroCpd("Erro ao criar o Logon linha 234, codlloja=$codloja, SQL=$sql");
    exit;
}

// caso for somente negativa��o
if ($assinatura == '7') {
    mysql_query("UPDATE cs2.cadastro SET classe='1' WHERE codloja='$codloja'", $con);
}

//insere tabela de pre�os e consultas liberadas
$sql = "SELECT codcons,valor FROM cs2.valcons";
$inserre = mysql_query($sql, $con);

while ($registro = mysql_fetch_array($inserre)) {
    $codcons = $registro["codcons"];
    $valcons = $registro["valor"];

    # Qtd Padrao
    $qtd = '25';

    if ($codcons == 'A0208' || $codcons == 'A0301')
        $qtd = '25';   // 05 consultas para Pesquisa Ligth e Pesquisa Restritiva
    else if ($codcons == 'A0203' || $codcons == 'A0115')
        $qtd = '25';  // 25 consultas para Pesquisa Cartorial e Pesquisa Empresarial
    else if ($codcons == 'A0207')
        $qtd = '5';   // 5 consultas para Pesquisa Personnalite
    else if ($codcons == 'A0100')
        $qtd = '100';  // 100 consultas para Pesquisa BACEN
    else if ($codcons == 'A0231')
        $qtd = '5000';  // 5000 consultas para Pesquisa LOCALIZA NOVOS CLIENTES

    if ($assinatura == '7')
        $qtd = '0';

    if (substr($codcons, 0, 1) == 'F')
        $qtd = '20'; // Features

    if ($tx_mens < 1) {
        $qtd = 0;
    }


    $tabela = "INSERT INTO cs2.valconscli( codloja, codcons, valorcons ) VALUES('$codloja','$codcons','$valcons')";
    $result1 = mysql_query($tabela, $con) or die("Erro: $tabela");
    $liberadas = "INSERT INTO cs2.cons_liberada VALUES ('$codloja','$codcons','$qtd','0')";
    $result2 = mysql_query($liberadas, $con) or die("Erro: $liberadas");
}

//insere consultas bonificadas

$sql_t = "SELECT tpcons, qtd, tpcons2, qtd2 FROM cs2.tabela_valor WHERE id = '$pacote'";
$qry_t = mysql_query($sql_t, $con);
while ($row_t = mysql_fetch_array($qry_t)) {
    $tpcons = $row_t['tpcons'];
    $qtd = $row_t['qtd'];
    $tpcons2 = $row_t['tpcons2'];
    $qtd2 = $row_t['qtd2'];

    //LOG
    $teste = "Resultado: Pacote 1: [$tpcons - $qtd] Pacote 2: [$tpcons2 - $qtd2]";
    $sql = "INSERT INTO cs2.sql_cadastro(comando_sql,datahora) VALUES ('$teste',now())";
    mysql_query($sql, $con);

    $sql = "INSERT INTO cs2.bonificadas (codloja, tpcons, qtd) VALUES( '$codloja', '$tpcons', '0' )";       // Qtd PARA NOVOS USUÁRIOS TEM QUE SER 0
    $qry_bonficadas = mysql_query($sql, $con);
    if (!$qry_bonficadas) {
        enviaErroCpd("Erro ao gravar as bonificadas linha 258, codlloja=$codloja, SQL=$sql");
        echo "<script>alert(\"Erro ao gravar as bonificadas,  entre em contato com o Departamento de Informatica !\");</script>";
        exit;
    }

    //LOG
    $teste = str_replace(chr(39), '', $sql);
    $sql = "INSERT INTO cs2.sql_cadastro(comando_sql,datahora) VALUES ('$teste',now())";
    mysql_query($sql, $con);


    if ($qtd2 > 0) {
        $sql = "INSERT INTO cs2.bonificadas (codloja, tpcons, qtd) VALUES( '$codloja', '$tpcons2', '0' )";    // Qtd PARA NOVOS USUÁRIOS TEM QUE SER 0
        $qry_bonficadas2 = mysql_query($sql, $con);
        if (!$qry_bonficadas2) {
            enviaErroCpd("Erro ao gravar as bonificadas linha 273, codlloja=$codloja, SQL=$sql");
            echo "<script>alert(\"Erro ao gravar as bonificadas,  entre em contato com o Departamento de Informatica !\");</script>";
            exit;
        }

        $teste = str_replace(chr(39), '', $sql);
        $sql = "INSERT INTO cs2.sql_cadastro(comando_sql,datahora) VALUES ('$teste',now())";
        mysql_query($sql, $con);
    }
}

$sql = "INSERT INTO cs2.historico_nfe(codloja,data,hora,mensagem)
				  VALUES('$codloja', NOW(), NOW(), 'Habilitado uso : NFe - NFCe (Usuário: franquiasnacional)')";
$qry = mysql_query($sql, $con);

/* * *
 * 
 *                  REGISTRA O BOLETO
 * 
 * * */

if ($tx_mens > 0) { // verifica se o plano não é gratuito.
    $sqlControleBoleto = "SELECT contador_recebafacil FROM cs2.controle_boletos ";
    $Registroboleto = mysql_query($sqlControleBoleto, $con);
    $NumeroBoleto = mysql_result($Registroboleto, 0, 'contador_recebafacil');
    $NumeroBoletoN = $NumeroBoleto + 1;

    $sqlUpdateControleBoleto = "UPDATE cs2.controle_boletos SET contador_recebafacil = $NumeroBoletoN WHERE contador_recebafacil = $NumeroBoleto";
    $Registroboleto = mysql_query($sqlUpdateControleBoleto, $con);

    $data = date('Y-m-d');
    $dataV = date('Y-m-d', strtotime('+5 days'));
    $dataC = '99' . date('YmdHis');

    $saveChaves = array('numdoc', 'insc', 'codloja', 'carteira', 'debito', 'emissao', 'vencimento', 'dti', 'dtf', 'valor', 'obs', 'numboleto', 'numboleto_bradesco', 'referencia', 'banco_faturado');
    $saveValores = array("'$dataC'", "'$insc'", $codloja, "'0'", "'B'", "'$data'", "'$dataV'", "'$data'", "'$data'", "200.00", "'Referente a TAXA DE ADESÃO WEB CONTROL EMPRESAS'", "$NumeroBoletoN", "$NumeroBoletoN", "'ADESAO'", 237);

    $sqlInsertTitulo = "INSERT INTO cs2.titulos (" . implode(',', $saveChaves) . ")VALUES(" . implode(',', $saveValores) . ")";
    $titulo = mysql_query($sqlInsertTitulo, $con);
    $tituloId = $dataC;
}

// Cria uma conta de Email para o cliente novo;
verifica_email($franqueado, $codloja, $nomefantasia);

grava_dados($insc, $Tipo, $razaosoc, $logradouro, $numero, $complemento, $bairro, $localidade, $uf, $cep, $email, $fone, $celular, $cpfsocio1, $socio1, '', '');

// Gravando cliente para utilizar o WEBCONTROL
// Criando Funcionario
// Criando Usuario
// Criando CLIENTE Balcao
// Criando VENDEDOR Padrao
// Aplicando todos os direitos
Grava_Acesso_WebControl($codloja, $nomefantasia, $cpfsocio1, $email, $login, $senha, $uf);

if ($tx_mens < 1) {
    echo ''
    . '<div class="col-md-12 text-left" style="font-size:21px;padding:5% 10%;">'
    . '     <br/>'
    . '     Parabéns!<br/><br/>Sua empresa poderá acessar o sistema utilizando o código e senha abaixo:'
    . '     <br/><br/>'
    . '     <b style="font-size:24px;color:#1e5e95;padding: 30px 40px;display: block;text-align: center;">Código: ' . $login . ' <span style="padding-left:40px;">  Senha: ' . $senha . '</span></b>'
    . '     <br/>'
    . '     '
    . '     <h3>Plano Selecionado: <b style="color:#1e5e95;"> 30 dias de Experiência</b></h3>'
    . ' '
    . '</div>'
    . '<div class="col-md-12 text-center form-group">'
    . '     <form method="post" action="">'
    . '         <input type="hidden" name="iptIdCadastro" value="' . $login . '"/>'
    . '         <input type="hidden" name="iptSenha" value="' . $senha . '"/><br/><br/>'
    . '         <button class="btn btn-success" style="padding:10px 30px;font-size:20px;">ACESSAR SISTEMA</button>'
    . '     </form>'
    . '</div>';

    $t = '30 dias de Experiência';
    $tV = 'R$ 0,00';

    /*
     *  
     *                  ENVIAR EMAIL 
     * 
     *  */
    $emailBody = '<html>
    <head>
        <title>Afiliação Web Control Empresas</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <style>
        body{font-family:Arial;}
        td { 
            background-clip: padding-box;
            padding:5px;
        }    
    </style>
    <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <!-- Save for Web Slices (Sem Título-2) -->
        <table align="center" id="Tabela_02" width="600"  border="0" cellpadding="0" cellspacing="0" style="font-family:Arial;color:#999999;">
            <tr>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td style="width:10%;"></td>
                <td style="width:40;"><font style="font-weight:bold;"><b>' . utf8_decode('ADESÃO') . '</b></font><br/><a style="color:#000;font-size:11px;" href="https://www.webcontrolempresas.com.br">www.webcontrolempresas.com.br</a></td>
                <td style="width:40;" align="right"><img src="https://www.webcontrolempresas.com.br/site/assets/img/logo.png" width="150"  alt=""></td>
                <td style="width:10;"></td>
            </tr>
            <tr>
                <td colspan="4"></td>
            </tr> 
            <tr>
                <td style="width:10%;"> </td>
                <td colspan="2" align="center"  style="padding:20px;width:80;"bgcolor="#05d728">
                    <font color="#FFFFFF"><b>' . utf8_decode('ADESÃO REALIZADA COM SUCESSO') . '!!!</b></font> 
                </td>
                <td style="width:10%;"> </td>
            </tr>
            <tr>
                <td style="width:10%;"> </td>
                <td colspan="2" style="width:80;"bgcolor="#EEEEEE">
                    <table width="100%">
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td colspan="2" width="80#"><font color="#999999"><b>' . utf8_decode('INFORMAÇÕES DE ACESSO AO SISTEMA') . '</b></font></td>
                            <td width="10#"></td>
                        </tr>  
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font color="#999999"><b>' . utf8_decode('CÓDIGO') . '</b></font></td>
                            <td width="30%"><font color="#05D728"><b>' . $login . '</b></font></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font color="#999999"><b>SENHA</b></font></td>
                            <td width="30%"><font color="#05D728"><b>' . $senha . '</b></font></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td bgcolor="#1f5f96" style="padding:10px;" align="center" colspan="2" width="80%"><a href="https://www.webcontrolempresas.com.br"><font color="#FFFFFF"><b>ACESSAR SISTEMA</b></font></a></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td colspan="2" width="80#"><font color="#999"><b>DETALHES DO CADASTRO</b></font></td>
                            <td width="10#"></td>
                        </tr>  
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>' . utf8_decode('RAZÃO SOCIAL') . '</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . utf8_decode($razaosoc) . '</font></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>NOME FANTASIA</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . utf8_decode($nomefantasia) . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>CPF/CNPJ</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $insc . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>E-MAIL</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $email . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>TELEFONE</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $fone . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>CELULAR</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $celular . '</font></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>' . utf8_decode('SÓCIO 1') . '</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . utf8_decode($socio1) . '</font></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>' . utf8_decode('CPF SÓCIO 1') . ' </b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $cpfsocio1 . '</font></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td colspan="2" width="80#"><font color="#999999"><b>' . utf8_decode('LOCALIZAÇÃO') . '</b></font></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>CEP</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $cep . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr> 
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>UF</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $uf . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>CIDADE</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . utf8_decode($localidade) . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>BAIRRO</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . utf8_decode($bairro) . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>' . utf8_decode('ENDEREÇO') . '</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . utf8_decode($logradouro) . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>NUMERO</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $numero . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>COMPLEMENTO</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . utf8_decode($complemento) . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td colspan="2" width="80#"><font color="#999999"><b>PLANO</b></font></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>' . utf8_decode($t) . '</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $tV . '</font></td>
                            <td width="10#"></td>
                        </tr>   
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                    </table>
                </td>
                <td style="width:10;"> </td>
            </tr>
            <tr>
                <td colspan="3" ></td>
            </tr>
            <tr>
                <td style="width:10%;"> </td>
                <td style="width:80%;"><font color="#999999" style="font-size:11px;">Mensagem enviada em: ' . date('d/m/Y H:i:s') . '</font></td>
                <td style="width:10%;"> </td>
            </tr>
            <tr>
                <td colspan="3" ></td>
            </tr>
        </table>

        <!-- End Save for Web Slices -->
    </body>
</html>';
    $assunto = utf8_decode('Afiliação - Web Control Empresas');

    /* enviar */
    $host = "10.2.2.7"; // host do servidor SMTP 
    $smtp = new Smtp($host);
    $smtp->user = "cpd@informsystem.com.br"; // usuario do servidor SMTP 
    $smtp->pass = "#9%kxP*-11"; // senha dousuario do servidor SMTP
    $smtp->debug = true; // ativar a autentica� SMTP 
    $smtp->Send('webdesigner@webcontrolempresas.com.br', 'webdesigner@webcontrolempresas.com.br', $assunto, $emailBody);

    /*
     * 
     * 
     */
} else {

    $t = 'Plano 12 meses';
    $tV = 'R$ 79,90';
    if ($tx_mens == '69.90') {
        $t = 'Plano 24 meses';
        $tV = ' R$ 69,90';
    }

    echo ''
    . '<div class="col-md-12 text-left" style="font-size:21px;padding:5% 10%;">'
    . '     <br/>'
    . '     Parabéns!<br/><br/>Sua empresa poderá acessar o sistema utilizando o código e senha abaixo:'
    . '     <br/><br/>'
    . '     <b style="font-size:24px;color:#1e5e95;padding: 30px 40px;display: block;text-align: center;">Código: ' . $login . ' <span style="padding-left:40px;">  Senha: ' . $senha . '</span></b>'
    . '     <br/>'
    . '     '
    . '     <h3>Plano Selecionado: <b style="color:#1e5e95;"> ' . $t . ' - ' . $tV . '</b></h3>'
    . '</div>'
    . '<div class="col-md-12 text-center form-group">'
    . '     <a href="https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=' . $tituloId . '" target="_blank" class="btn btn-primary" style="padding:10px 30px;font-size:20px;">'
    . '          <span class="fa fa-barcode"></span> IMPRIMIR BOLETO DE ADESÃO'
    . '     </a><br/>'
    . '     <button data-dismiss="modal" aria-label="close" type="button" class="btn btn-link" style="padding:10px 30px;font-size:20px;">'
    . '         Fechar '
    . '     </button>'
    . '</div>';

    /*
     *  
     *                  ENVIAR EMAIL 
     * 
     *  */
    $emailBody = '<html>
    <head>
        <title>' . utf8_decode('Afiliação Web Control Empresas') . '</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <style>
        body{font-family:Arial;}
        td { 
            background-clip: padding-box;
            padding:5px;
        }    
    </style>
    <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <!-- Save for Web Slices (Sem Título-2) -->
        <table align="center" id="Tabela_02" width="600"  border="0" cellpadding="0" cellspacing="0" style="font-family:Arial;color:#999999;">
            <tr>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td style="width:10%;"></td>
                <td style="width:40;"><font style="font-weight:bold;"><b>' . utf8_decode('ADESÃO') . '</b></font><br/><a style="color:#000;font-size:11px;" href="https://www.webcontrolempresas.com.br">www.webcontrolempresas.com.br</a></td>
                <td style="width:40;" align="right"><img src="https://www.webcontrolempresas.com.br/site/assets/img/logo.png" width="150"  alt=""></td>
                <td style="width:10;"></td>
            </tr>
            <tr>
                <td colspan="4"></td>
            </tr> 
            <tr>
                <td style="width:10%;"> </td>
                <td colspan="2" align="center"  style="padding:20px;width:80;"bgcolor="#05d728">
                    <font color="#FFFFFF"><b>' . utf8_decode('ADESÃO REALIZADA COM SUCESSO') . '!!!</b></font> 
                </td>
                <td style="width:10%;"> </td>
            </tr>
            <tr>
                <td style="width:10%;"> </td>
                <td colspan="2" style="width:80;"bgcolor="#EEEEEE">
                    <table width="100%">
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td colspan="2" width="80#"><font color="#999999"><b>' . utf8_decode('INFORMAÇÕES DE ACESSO AO SISTEMA') . '</b></font></td>
                            <td width="10#"></td>
                        </tr>  
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font color="#999999"><b>LOGIN</b></font></td>
                            <td width="30%"><font color="#05D728"><b>' . $login . '</b></font></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font color="#999999"><b>SENHA</b></font></td>
                            <td width="30%"><font color="#05D728"><b>' . $senha . '</b></font></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td bgcolor="#1f5f96" style="padding:10px;" align="center" colspan="2" width="80#"><a href="https://www.webcontrolempresas.com.br"><font color="#FFFFFF"><b>ACESSAR SISTEMA</b></font></a></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td colspan="2" width="80#"><font color="#999999"><b>DETALHES DO CADASTRO</b></font></td>
                            <td width="10#"></td>
                        </tr>  
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>' . utf8_decode('RAZÃO SOCIAL') . '</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . utf8_decode($razaosoc) . '</font></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>NOME FANTASIA</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . utf8_decode($nomefantasia) . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>CPF/CNPJ</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $insc . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>E-MAIL</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $email . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>TELEFONE</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $fone . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>CELULAR</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $celular . '</font></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>' . utf8_decode('SÓCIO 1') . '</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . utf8_decode($socio1) . '</font></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>' . utf8_decode('CPF SÓCIO 1') . ' </b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $cpfsocio1 . '</font></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td colspan="2" width="80#"><font color="#999999"><b>' . utf8_decode('LOCALIZAÇÃO') . '</b></font></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>CEP</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $cep . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr> 
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>UF</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $uf . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>CIDADE</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . utf8_decode($localidade) . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>BAIRRO</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . utf8_decode($bairro) . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>' . utf8_decode('ENDEREÇO') . '</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . utf8_decode($logradouro) . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>NUMERO</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $numero . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>COMPLEMENTO</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . utf8_decode($complemento) . '</font></td>
                            <td width="10#"></td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td colspan="2" width="80#"><font color="#999999"><b>PLANO</b></font></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td width="50%"><font style="font-size:12px;" color="#999999"><b>' . utf8_decode($t) . '</b></font></td>
                            <td width="30%"><font style="font-size:12px;" color="#999999">' . $tV . '</font></td>
                            <td width="10#"></td>
                        </tr>  
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td bgcolor="#1f5f96" style="padding:10px;" align="center" colspan="2" width="80#"><a href="https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=' . $tituloId . '"><font color="#FFFFFF"><b>IMPRIMIR BOLETO</b></font></a></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td width="10#"></td>
                            <td colspan="2"  width="80#"><a href="https://www.webcontrolempresas.com.br/franquias/afiliacao/contrato/' . $tituloId . '"><font style="font-size:12px;" color="#1f5f96"><b>Visualizar Contraro</b></font></a></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td width="10#"></td>
                            <td colspan="2" width="80#"><a href="https://www.webcontrolempresas.com.br/franquias/afiliacao/turno/' . $tituloId . '"><font style="font-size:12px;" color="#1f5f96"><b>Visualizar Tabela de Valores</b></font></a></td>
                            <td width="10#"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                        <tr>
                            <td colspan="5"></td>
                        </tr> 
                    </table>
                </td>
                <td style="width:10;"> </td>
            </tr>
            <tr>
                <td colspan="3" ></td>
            </tr>
            <tr>
                <td style="width:10%;"> </td>
                <td style="width:80%;"><font color="#999999" style="font-size:11px;">Mensagem enviada em: ' . date('d/m/Y H:i:s') . '</font></td>
                <td style="width:10%;"> </td>
            </tr>
            <tr>
                <td colspan="3" ></td>
            </tr>
        </table>

        <!-- End Save for Web Slices -->
    </body>
</html>';

    $assunto = utf8_decode('Afiliação - Web Control Empresas');

    /* enviar */
    $host = "10.2.2.7"; // host do servidor SMTP 
    $smtp = new Smtp($host);
    $smtp->user = "cpd@informsystem.com.br"; // usuario do servidor SMTP 
    $smtp->pass = "#9%kxP*-11"; // senha dousuario do servidor SMTP
    $smtp->debug = true; // ativar a autentica� SMTP 
    $smtp->Send('webdesigner@webcontrolempresas.com.br', 'webdesigner@webcontrolempresas.com.br', $assunto, $emailBody);
}
?>