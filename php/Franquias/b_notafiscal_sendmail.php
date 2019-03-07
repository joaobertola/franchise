<?php

require_once("/var/www/html/franquias/php/connect/conexao_conecta.php");
require_once("../dompdf/dompdf_config.inc.php");
require_once('class.phpmailer.php');

$numdoc      = $_REQUEST['numdoc'];
$faturamento = $_REQUEST['faturamento'];
@$lote       = $_REQUEST['lote'];

$msg_ok = '';
$msg_err = '';

/* header html */
$htmlO = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
$htmlO .= '<style>
            body{margin-top: 0; font-family: arial; font-size: 10px}
            .text-center{text-align: center}

            table{ border-color: #000; border-spacing: 0; border-collapse: collapse; font-size:12px;}
            table tr td{padding:3px !important;}
            #tbl-principal{width: 750px}
            #logo{padding: 0 10px}

            #tbl-prefeitura{width: 500px; border-spacing: 0; text-align: center; font-size: 14px}
            #tbl-prefeitura tr{height: 24px}
            #secretaria, #rps{font-size: 10px}

            #tbl-numero{height: 96px}
            #data-hora-titulo{border-top: 1px solid #000}
            #nota-emissao{text-align:center; border-bottom: 1px solid #000}

            #tbl-tomador {width: 100%}

            #tbl-totais{width: 100%; border-collapse: collapse}
            #titulos {text-align: center}
            #valores{text-align: right}

            #cancelada {font-size: 110px; -moz-transform: rotate(-36deg); -ms-transform: rotate(-36deg); transform: rotate(-36deg); -webkit-transform: rotate(-36deg);
                        left: 10px; top: 190px; font-family: arial; position: absolute;
                        opacity:0.35; -moz-opacity: 0.35; filter: alpha(opacity=35)}
            </style>';

$sql = "SELECT * FROM cs2.titulos_notafiscal WHERE FIND_IN_SET( numdoc, '$numdoc')";
$qry = mysql_query($sql, $con) or die('Erro SQL 01:'.$sql);
$qtd_email = mysql_num_rows($qry);
if ( $qtd_email > 0 ){
    
    while( $reg = mysql_fetch_array($qry) ){

        $xml = $reg['xml'];
        $codigo_verificacao = $reg['codigo_verificacao'];
        $numdocx = $reg['numdoc'];
        
        $sqlv = "SELECT date_format(vencimento,'%d-%m-%Y') as vencimento FROM cs2.titulos WHERE  numdoc = '$numdocx'";
        $qryv = mysql_query($sqlv, $con) or die('Erro SQL 01:'.$sqlv);
        $vencimento = mysql_result($qryv, 0, 'vencimento');
       
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preservWhiteSpace = false; // elimina espacos em branco
        $dom->formatOutput = false; // ignora formatacao
        $dom->loadXML( $xml , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );

        $EnviarLoteRpsEnvio = $dom->getElementsByTagName ( "EnviarLoteRpsEnvio" )->item ( 0 );
        $LoteRps = $EnviarLoteRpsEnvio->getElementsByTagName ( "LoteRps" )->item ( 0 );
        $Cnpj = trim($LoteRps->getElementsByTagName ( "Cnpj" )->item ( 0 )->nodeValue);
        $InscricaoMunicipal = trim($LoteRps->getElementsByTagName ( "InscricaoMunicipal" )->item ( 0 )->nodeValue);
        $NumeroLoteRPS = trim($LoteRps->getElementsByTagName ( "NumeroLote" )->item ( 0 )->nodeValue);
        $QuantidadeRps = trim($LoteRps->getElementsByTagName ( "QuantidadeRps" )->item ( 0 )->nodeValue);

        $ListaRps = $LoteRps->getElementsByTagName ( "ListaRps" )->item ( 0 );

        foreach ( $ListaRps->getElementsByTagName ( "Rps" ) as $Rps ){

            $InfRps = $Rps->getElementsByTagName ( "InfRps" )->item ( 0 );

            $IdentificacaoRps = $InfRps->getElementsByTagName ( "IdentificacaoRps" )->item ( 0 );

            $pmc_emitido = trim($InfRps->getElementsByTagName ( "DataEmissao" )->item ( 0 )->nodeValue);
            $pmc_emitido = substr($pmc_emitido,8,2).'/'.substr($pmc_emitido,5,2).'/'.substr($pmc_emitido,0,4);

            $hora_emitido = substr($pmc_emitido,11,8);

            $Numero = trim($IdentificacaoRps->getElementsByTagName ( "Numero" )->item ( 0 )->nodeValue);
            $Serie = trim($IdentificacaoRps->getElementsByTagName ( "Serie" )->item ( 0 )->nodeValue);

            $Servicos = $InfRps->getElementsByTagName ( "Servico" )->item ( 0 );

            $Valores = $Servicos->getElementsByTagName ( "Valores" )->item ( 0 );

            $Tomador = $InfRps->getElementsByTagName ( "Tomador" )->item ( 0 );

            $Descricao = $InfRps->getElementsByTagName ( "Discriminacao" )->item ( 0 )->nodeValue;

            $ValorServicos = trim($Valores->getElementsByTagName ( "ValorServicos" )->item ( 0 )->nodeValue);
            $total = number_format($ValorServicos, 2, ',', '.');

            $IssRetido = trim($Valores->getElementsByTagName ( "IssRetido" )->item ( 0 )->nodeValue);
            $Perc_ISS = trim($Valores->getElementsByTagName ( "IssRetido" )->item ( 0 )->nodeValue);

            $IssRetido = number_format($IssRetido, 2, ',', '.');

            $valor_deducoes = '0,00';
            $base_calculo = $total;
            $aliquota = $IssRetido;

            $valorIss = ( $ValorServicos * $Perc_ISS ) / 100;

            $valor_iss = number_format($valorIss, 2, ',', '.');;
            $credito_iptu = '0,00';

            $emp_nome = 'W.C. Desenvolvedora de Softwares (WEB CONTROL EMPRESAS)';
            $emp_cnpj = '13.117.948/0001-73';
            $emp_im = '01 01 0604910-9';
            $emp_end = 'AV. CANDIDO DE ABREU, 70 - BAIRRO: CENTRO';
            $emp_tel = '(41) 3207-1744';
            $emp_cid = 'CURITIBA';
            $emp_uf = 'PR';
            $emp_email = 'administrativo@webcontrolempresas.com.br';

            $Servicos = $InfRps->getElementsByTagName ( "Servico" )->item ( 0 );
            $descriminacao = trim($Servicos->getElementsByTagName ( "Discriminacao" )->item ( 0 )->nodeValue);
            $Valores = $Servicos->getElementsByTagName ( "Valores" )->item ( 0 );

            $Tomador = $InfRps->getElementsByTagName ( "Tomador" )->item ( 0 );
            $IdentificacaoTomador = $Tomador->getElementsByTagName ( "IdentificacaoTomador" )->item ( 0 );
            $CpfCnpj = $IdentificacaoTomador->getElementsByTagName ( "CpfCnpj" )->item ( 0 );

            $cli_cnpj = trim($CpfCnpj->getElementsByTagName ( "Cnpj" )->item ( 0 )->nodeValue);
            $cli_nome = trim($InfRps->getElementsByTagName ( "RazaoSocial" )->item ( 0 )->nodeValue);

            $Endereco = $InfRps->getElementsByTagName ( "Endereco" )->item ( 0 );
            $Rua = trim($Endereco->getElementsByTagName ( "Endereco" )->item ( 0 )->nodeValue);
            $Num = trim($Endereco->getElementsByTagName ( "Numero" )->item ( 0 )->nodeValue);
            $Bai = trim($Endereco->getElementsByTagName ( "Bairro" )->item ( 0 )->nodeValue);
            $CodigoMunicipio = trim($Endereco->getElementsByTagName ( "CodigoMunicipio" )->item ( 0 )->nodeValue);

            $cli_uf = trim($Endereco->getElementsByTagName ( "Uf" )->item ( 0 )->nodeValue);
            $Cep = trim($Endereco->getElementsByTagName ( "Cep" )->item ( 0 )->nodeValue);

            $sql_sel = "SELECT UPPER(descricao) AS descricao FROM base_web_control.nfe_municipio WHERE id_estado = ".substr($CodigoMunicipio,0,2)." and sigla = ".substr($CodigoMunicipio,2,8);
            $qry2 = mysql_query($sql_sel, $con) or die("Erro SQL: $sql_sel");
            $cli_cid = mysql_result($qry2,0,'descricao');

            $cli_end = $Rua.', '.$Num.' - Bairro: '.$Bai.' - CEP: '.$Cep;

            $Contato = $InfRps->getElementsByTagName ( "Contato" )->item ( 0 );
            $Contato_Telefone = trim($Contato->getElementsByTagName ( "Telefone" )->item ( 0 )->nodeValue);
            $cli_email = trim($Contato->getElementsByTagName ( "Email" )->item ( 0 )->nodeValue);

            $descriminacao = trim($Servicos->getElementsByTagName ( "Discriminacao" )->item ( 0 )->nodeValue);

            $cod_atividade = '1505 - Cadastro, elabora&ccedil;&atilde;o de ficha cadastral, renova&ccedil;&atilde;o cadastral e congeneres, inclus&atilde;o ou exclus&atilde;o no Cadastro de Emitentes de Cheques sem Fundos - CCF ou em quaisquer outros bancos cadastrais.';

            $info = 'Documento Emitido por ME ou EPP optante pelo Simples Nacional.<br>Nao gera direito a credito de IPI.';

            $html = "<table border='1' cellspacing='0' id='tbl-principal'>
                        <tr>
                            <td style='width: 158px'><img src='logomarca.jpg' width='140px' id='logo'></td>
                            <td style='width: 452px'>
                                <table border='0' cellspacing='0' cellpadding='0' id='tbl-prefeitura'>
                                    <tr>
                                        <td><strong>PREFEITURA MUNICIPAL DE CURITIBA</strong></td>
                                    </tr>
                                    <tr>
                                        <td id='secretaria'><strong>SECRETARIA MUNICIPAL DE FINAN&Ccedil;AS</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>NOTA FISCAL DE SERVI&Ccedil;OS ELETR&Ocirc;NICA - NFS-e</strong></td>
                                    </tr>
                                    <tr>
                                        <td id='rps'>RPS n&deg;. $Numero, S&eacute;rie: $Serie, emitido em $pmc_emitido, convers&atilde;o em $pmc_emitido</td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <table border='0' cellspacing='0' cellpadding='0' id='tbl-numero'>
                                    <tr>
                                        <td>N&uacute;mero da Nota</td>
                                    </tr>
                                    <tr>
                                        <td class='text-center'>$Numero</td>
                                    </tr>
                                    <tr>
                                        <td id='data-hora-titulo'>Data e Hora de Emiss&atilde;o</td>
                                    </tr>
                                    <tr>
                                        <td id='nota-emissao'>$pmc_emitido $hora_emitido</td>
                                    </tr>
                                    <tr>
                                        <td>C&oacute;digo de Verifica&ccedil;&atilde;o</td>
                                    </tr>
                                    <tr>
                                        <td class='text-center'><strong>$codigo_verificacao</strong></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr height='25'>
                            <td colspan='3' class='text-center'><strong>PRESTADOR DE SERVI&Ccedil;OS</strong></td>
                        </tr>
                        <tr>
                            <td colspan='3'>
                                <table border='0' cellspacing='0' cellpadding='0'>
                                    <tr>
                                        <td style='width: 110px'><strong>Raz&atilde;o Social:</strong></td>
                                        <td colspan='5'>$emp_nome</td>
                                    </tr>
                                    <tr>
                                        <td><strong>CPF / CNPJ:</strong></td>
                                        <td colspan='1'>$emp_cnpj</td>
                                        <td colspan='3'><strong>Inscri&ccedil;&atilde;o Municipal:</strong></td>
                                        <td colspan='1'>$emp_im</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Endere&ccedil;o:</strong></td>
                                        <td colspan='3'>$emp_end</td>
                                        <td colspan='1' align='right'><strong>Telefone:</strong></td>
                                        <td colspan='1'>$emp_tel</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Municipio:</strong></td>
                                        <td>$emp_cid</td>
                                        <td><strong>UF:</strong></td>
                                        <td>$emp_uf</td>
                                        <td><strong>Email:</strong></td>
                                        <td>financeiro@webcontrolempresas.com.br</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr height='25'>
                            <td colspan='3' align='center'><strong>TOMADOR DE SERVI&Ccedil;OS</strong></td>
                        </tr>
                        <tr>
                            <td colspan='3'>
                                <table border='0' cellspacing='0' cellpadding='0' id='tbl-tomador'>
                                    <tr>
                                        <td style='width: 110px'><strong>Nome/Raz&atilde;o Social:</strong></td>
                                        <td colspan='5'>$cli_nome</td>
                                    </tr>
                                    <tr>
                                        <td><strong>CPF / CNPJ:</strong></td>
                                        <td>$cli_cnpj</td>
                                        <td><strong>IMU:</strong></td>
                                        <td></td>
                                        <td><strong>Outro Doc.:</strong></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Endere&ccedil;o:</strong></td>
                                        <td colspan='5'>$cli_end</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Municipio:</strong></td>
                                        <td>$cli_cid</td>
                                        <td><strong>UF:</strong></td>
                                        <td>$cli_uf</td>
                                        <td><strong>Email:</strong></td>
                                        <td>$cli_email</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr height='25'>
                            <td colspan='3' class='text-center'><strong>DISCRIMINA&Ccedil;&Atilde;O DOS SERVI&Ccedil;OS</strong></td>
                        </tr>
                        <tr>
                            <td colspan='3'>
                                <br><br>$descriminacao<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='3' align='center'><strong>VALOR TOTAL DA NOTA - R$ $total</strong></td>
                        </tr>
                        <tr>
                            <td colspan='3'>
                                C&oacute;digo da Atividade:<br><br>
                                $cod_atividade
                            </td>
                        </tr>
                        <tr>
                            <td colspan='3'>
                                <table border='1' cellspacing='0' cellpadding='0' id='tbl-totais'>
                                    <tr id='titulos'>
                                        <td><strong>Valor Total das Dedu&ccedil&otilde;es (R$)</strong></td>
                                        <td><strong>Base de C&aacute;lculo (R$)</strong></td>
                                        <td><strong>Al&iacute;quota (%)</strong></td>
                                        <td><strong>Valor de ISS (R$)</strong></td>
                                        <td><strong>Cr&eacute;dito p/ Abatimento do IPTU (R$)</strong></td>
                                    </tr>
                                    <tr id='valores'>
                                        <td>$valor_deducoes</td>
                                        <td>$base_calculo</td>
                                         <td>$aliquota</td>
                                        <td>$valor_iss</td>
                                        <td>$credito_iptu</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr height='25'>
                            <td colspan='3' class='text-center'><strong>OUTRAS INFORMA&Ccedil;&Otilde;ES</strong></td>
                        </tr>
                        <tr>
                            <td colspan='3'>
                                Esta NFS-e foi emitida com respaldo da Lei 73/2009.<br>
                                $info
                            </td>
                        </tr>
                    </table>";

        } // For

        $htmlO .= '</head><body>';
        $htmlO .= $html;
        $htmlO .= '</body></html>';
        
        // Instanciamos a classe
        $dompdf = new DOMPDF();

        //Passamos o conteúdo que será convertido para PDF
        $dompdf->load_html($htmlO);

        //portrait, landscape
        $dompdf->set_paper('a4',"portrait");//formato Carta

        // O arquivo é convertido
        $dompdf->render();

        $output = $dompdf->output();
        $arquivo = "notafiscal/Nota_Fiscal_WebControl_Venc_".$vencimento."_".$numdoc.".pdf";

        file_put_contents($arquivo, $output);

        $conteudoPag = utf8_encode(html_entity_decode($html));
        $assunto = utf8_encode(html_entity_decode('NOTA FISCAL DE SERVI&Ccedil;O - WEB CONTROL EMPRESAS'));

        $mail = new PHPMailer(); 
        $mail -> IsSMTP(); 
        $mail -> IsHTML(true); 
        $mail -> CharSet  = 'utf-8';        // Define o charset da mensagem 
        $mail -> SMTPAuth = true;                // Permitir autenticação SMTP 
        $mail -> Host     = '10.2.2.7';             // Define o servidor SMTP 
        $mail -> Username = "financeiro@webcontrolempresas.com.br";  // SMTP conta de usuário 
        $mail -> Password = "infsys321";  // SMTP conta senha 
        $mail -> Subject  = $assunto; // Define o assunto da mensagem 
        $mail -> From     = 'financeiro@webcontrolempresas.com.br';
        $mail -> FromName = 'Web Control Empresas'; // Adiciona um "From" endereço 
        $mail -> AddAddress($cli_email, '');  // Adiciona um "To" endereço 

        # Anexando o arquivo
        $mail->AddAttachment($arquivo);

        $conteudoPag  = "<p>Prezado Cliente: $cli_nome</p>";
        $conteudoPag .= "<p>Segue em anexo Nota Fiscal de Presta&ccedil;&atilde;o de Servi&ccedil;o junto a WEB CONTROL EMPRESAS</p>";
        $conteudoPag .= "<p>Atenciosamente</p>";
        $conteudoPag .= "<p>Departamento Financeiro<br>";
        $conteudoPag .= "<p>WEB CONTROL EMPRESAS<br>";
        $conteudoPag .= "(41) 3207-1744</p>";

        $mail -> Body     = $conteudoPag; // Define o corpo da mensagem
        
        if ($mail->Send()){ 
            $msg_ok = 'OK;'.$cli_nome;
        }else{ 
            $msg_err = 'ERRO;'.$cli_nome.';'. $mail->ErrorInfo;
        }
        
    } // while
}

if ( $lote ){

    // solicitacao por lote
    if ( $msg_err ) echo $msg_err;
    if ( $msg_ok ) echo $msg_ok;
    
}else{
    
    if ( $qtd_email == 0 ){
        echo "<script>alert('NENHUM REGISTRO ENCONTRADO PARA O ENVIO. VERIFIQUE NOVAMENTE !!! ');window.close()</script>";
    }
    elseif ( $qtd_email == 1 ){
        if ( $msg_ok ){
            echo "<script>alert('Mensagem enviada com sucesso !!!');window.close()</script>";
        }
        if ( $msg_err ){
            echo "<script>alert('Mensagem NAO foi enviada');window.close()</script>";
        }
    }
}   

?>