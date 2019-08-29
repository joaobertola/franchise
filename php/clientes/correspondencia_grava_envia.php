<?php

header('Content-Type: text/html; charset=utf-8');

$conteudoPag = $_REQUEST['conteudoPag'];
$email       = $_REQUEST['email'];
$codloja     = $_REQUEST['codloja'];
$tipo_carta  = $_REQUEST['escolha'];
$escolha     = $_REQUEST['escolha'];

switch ($escolha) {
    case 1:
        $assunto = "Documentos Pendentes para Cancelamento";
        break;
    case 2:
        $assunto = "Resposta de Cancelamento - Pagamento de Multa";
        break;
    case 3:
        $assunto = "Resposta de Cancelamento - Dentro do Prazo Contratual";
        break;
    case 4:
        $assunto = "Resposta PROCON - COM d&eacute;bito";
        break;
    case 5:
        $assunto = "Resposta PROCON - SEM d&eacute;bito";
        break;
    case 6:
        $assunto = "PETICAO DE ACORDO JUDICIAL";
        break;
    case 7:
        $assunto = "PETICAO DE ACORDO JUDICIAL";
        break;
    case 8:
        $assunto = "PETICAO DE ACORDO JUDICIAL";
        break;
    case 9:
        $assunto = "PETICAO DE ACORDO JUDICIAL";
        break;
}

$sql = "SELECT nomefantasia FROM cs2.cadastro
        WHERE codloja = $codloja";
$qry = mysql_query($sql, $con) or die("Erro ao selecionar cliente");
$nomefantasia = mysql_result($qry, 0, 'nomefantasia');
$nomefantasia = strtoupper($nomefantasia);

$sql = "INSERT INTO correspondencia_grava
            ( data, hora, codloja, texto, email_envio, tipo_carta )
        VALUES( NOW() , NOW() , '$codloja' ,'$conteudoPag','$email', '$escolha')";
$qry = mysql_query($sql, $con) or die("Erro ao gravar a Correspondencia");

if ($email) {

    // require 'class.phpmailer.php';

    // $mail = new PHPMailer();
    // $mail->IsSMTP();
    // $mail->IsHTML(true);
    // $mail->CharSet = 'utf-8';        // Define o charset da mensagem
    // $mail->SMTPAuth = true;                // Permitir autentica����o SMTP
    // $mail->Host = '10.2.2.7';             // Define o servidor SMTP
    // $mail->Username = "financeiro@webcontrolempresas.com.br";  // SMTP conta de usu��rio
    // $mail->Password = "infsys321";  // SMTP conta senha
    // $mail->SMTPOptions = array(
    //     'ssl' => array(
    //         'verify_peer' => false,
    //         'verify_peer_name' => false,
    //         'allow_self_signed' => true
    //     )
    // );
    // $mail->Subject = $assunto; // Define o assunto da mensagem
    // $mail->From = 'financeiro@webcontrolempresas.com.br';
    // $mail->FromName = 'Web Control Empresas'; // Adiciona um "From" endere��o
    // //$mail->FromName = 'Web Control Empresas - Sistemas - Automação - Consultas - Sites'; // Adiciona um "From" endere��o
    // $mail->AddAddress($email, '');  // Adiciona um "To" endere��o

    require_once("PHPMailerAutoload.php");
    require_once("class.phpmailer.php");

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->IsHTML(true);
    $mail->CharSet   = 'utf-8';               // Define o charset da mensagem
    $mail->SMTPAuth = true;                               // Permitir autentica??o SMTP
    $mail->Host         = '10.2.2.7';                         // Define o servidor SMTP
    $mail->Port = 587;
    $mail->Subject = $assunto;
    $mail->Body = utf8_decode($configuracao);
    $mail->Username = "financeiro@webcontrolempresas.com.br";   // SMTP conta de usu?rio
    $mail->Password = "infsys321";   // SMTP conta senha
    $mail->From         = 'financeiro@webcontrolempresas.com.br';
    $mail->FromName = 'Web Control Empresas'; // Adiciona um "From" endere?o
    $mail->AddAddress($email, '');   // Adiciona um "To" endere?o
    $mail->SMTPSecure = 'tls';
    $mail->SMTPOptions = array( //configurar isso porque por padrao ele verifica, se confogirar isso ele nao verifica ssl
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );


    if ($escolha >= 6) {
        if ($escolha == 6 or $escolha == 7) {
            $empresa = '_WC_Sistemas_';
            $conteudoPag = str_replace('{assinatura_administrativo}', '<img src="/var/www/html/franquias/img/Assinatura_WC_Wellington.jpg" width="300" />', $conteudoPag);
        }
        if ($escolha == 8 or $escolha == 9) {
            $empresa = '_Inform_System_';
            $conteudoPag = str_replace('{assinatura_administrativo}', '<img src="/var/www/html/franquias/img/Assinatura_Inform_Wellington.jpg" width="300" />', $conteudoPag);
        }

        date_default_timezone_set('America/Sao_Paulo');

        $conteudoPag2 = htmlspecialchars_decode($conteudoPag);
        $conteudoPag2 = str_replace('"', "'", $conteudoPag2);
        $conteudoPag2 = str_replace(chr(92), "", $conteudoPag2);

        $conteudoPag2 = "<meta charset='UTF-8'><div style='text-align: justify;font-size:9pt;'>" . $conteudoPag2 . "</div>";

        $data = date('dmY');
        $hora = time('hhmmss');
        $complemento = $codloja . '_' . $data . '_' . $hora;
        $arquivo = 'clientes/juridico/Termo_de_Acordo' . $empresa . $complemento . '.pdf';
        require_once("dompdf/dompdf_config.inc.php");

        $dompdf = new DOMPDF();
        // $dompdf->load_html($conteudoPag2);
        $dompdf->load_html(utf8_decode($conteudoPag2), 'UTF-8');

        $dompdf->set_paper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();

        # Nome do arquivo PETICAO a ser anexada ao Email
        // $arquivo = "clientes/juridico/Peticao_$data$hora.pdf";
        // $mail->AddAttachment($arquivo);

        # Gerando o arquivo na pasta 'clientes/juridico'
        file_put_contents($arquivo, $output);
        $mail->AddAttachment($arquivo);

        // $arq = 'clientes/documentos/Atos_Constitutivos_Inform_System.PDF';
        // $mail->AddAttachment($arq);

        # Anexando o arquivo Peticao

        $conteudoPag = "<p>Prezado Cliente</p>";
        $conteudoPag .= "<p>Cliente: $nomefantasia</p>";
        $conteudoPag .= "<p>Segue em anexo conforme combinado a PETI&Ccedil;&Atilde;O DE ACORDO, para que sejam assinados.</p>";
        $conteudoPag .= "<p>Aguardamos o envio para nossa empresa devidamente assinado para juntarmos no sistema do F&oacute;rum e darmos finaliza&ccedil;&atilde;o em nosso sistema.</p>";
        $conteudoPag .= "<p>Agradecemos e estamos ao inteiro dispor.</p>";
        $conteudoPag .= "<p>Atenciosamente</p>";
        //$conteudoPag .= "Jéssica Araujo<br>";
        $conteudoPag .= "Depto de Conciliação<br>";
        $conteudoPag .= "Web Control Empresas - Sistemas - Automação - Consultas - Sites<br>";

        $conteudoPag .= "(41) 3207-1744</p>";
    }
    $conteudoPag = str_replace('"', "'", $conteudoPag);
    $conteudoPag = str_replace(chr(92), "", $conteudoPag);

    $mail->Body = $conteudoPag; // Define o corpo da mensagem
    print_r($mail);
    die;
    if ($mail->Send()) {
        print_r($mail->Send());
        die;
        echo "<script>alert('Mensagem enviada com sucesso');window.close()</script>";
    } else {
        print_r(" entrou no else");
        die;
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}
