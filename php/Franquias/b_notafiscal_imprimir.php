<?php

$numdoc = $_REQUEST['numdoc'];
$faturamento = $_REQUEST['faturamento'];
$link_externo = $_REQUEST['link_externo'];

$conexao = $con;

if ( $link_externo == 'SIM'){
    require_once('../../../validar2.php');
    conecex();
    global $conexao;
}
?>

<script type="text/javascript">
    function Voltar_Listagem(){
        frm = document.rel; 
        frm.action = "?pagina1=Franquias/b_notafiscal_new.php&go=ingressar&id_faturamento=<?php echo $faturamento; ?>";
    frm.submit(); 
    }
</script>

<?php

$sql = "SELECT * FROM cs2.titulos_notafiscal WHERE FIND_IN_SET( numdoc, '$numdoc')";
$qry = mysql_query($sql,$conexao) or die('Erro SQL 01:'.$sql);
if (mysql_num_rows($qry) > 0 ){
   
    $html = "<html>
                 <head>
                     <meta charset='UTF-8'>
                     <title>NFS-e</title>
                     <style>
                         body{margin-top: 0; font-family: arial; font-size: 10px}
                         .text-center{text-align: center}
                         table{ border-color: #000; border-spacing: 0; border-collapse: collapse; font-size:12px;}
                         table tr td{padding:3px !important;}
                         #tbl-principal{width: 800px}
                         #logo{padding: 0 10px}

                         #tbl-prefeitura{width: 500px; border-spacing: 0; text-align: center; font-size: 14px}
                         #tbl-prefeitura tr{height: 24px}
                         #secretaria, #rps{font-size: 10px}

                         #tbl-numero{height: 96px}
                         #data-hora-titulo{border-top: 1px solid #000; font-size: 10px}
                         #codigo-verificacao{border-top: 1px solid #000; font-size: 10px}
                         #nota-emissao{text-align:center; border-bottom: 1px solid #000}

                         #tbl-tomador {width: 100%}

                         #tbl-totais{width: 100%; border-collapse: collapse}
                         #titulos {text-align: center}
                         #valores{text-align: right}

                         #cancelada {font-size: 110px; -moz-transform: rotate(-36deg); -ms-transform: rotate(-36deg); transform: rotate(-36deg); -webkit-transform: rotate(-36deg);
                                     left: 10px; top: 190px; font-family: arial; position: absolute;
                                     opacity:0.35; -moz-opacity: 0.35; filter: alpha(opacity=35)}
                     </style>
                 </head>
                 <body>
                 <form name='rel' action = '#' method='post'>";
    
   if ( trim($link_externo) == '' ){
       
       echo "     <table width='800px'>
                     <tr align='center' class='noprint'>
                         <td>
                             <input type='submit' value=' Voltar a listagem ' onClick='Voltar_Listagem()' />
                         </td>
                     </tr>
                     <tr><td></td></tr>
                 </table>";
   }
   
    while( $reg = mysql_fetch_array($qry) ){
        
        $xml = $reg['xml'];
        $codigo_verificacao = $reg['codigo_verificacao'];
    
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

            $ValorIssRetido = trim($Valores->getElementsByTagName ( "ValorIssRetido" )->item ( 0 )->nodeValue);
            
            $ValorliquidoNfse = trim($Valores->getElementsByTagName ( "ValorLiquidoNfse" )->item ( 0 )->nodeValue);
            $ValorliquidoNfse = number_format($ValorliquidoNfse, 2, ',', '.');
            
            $ValorServicos = trim($Valores->getElementsByTagName ( "ValorServicos" )->item ( 0 )->nodeValue);
            $total = number_format($ValorServicos, 2, ',', '.');

            $IssRetido = trim($Valores->getElementsByTagName ( "IssRetido" )->item ( 0 )->nodeValue);
            $Perc_ISS = trim($Valores->getElementsByTagName ( "IssRetido" )->item ( 0 )->nodeValue);

            $IssRetido = number_format($IssRetido, 2, ',', '.');

            $valor_deducoes = '0,00';
            $base_calculo = $total;
            $aliquota = $IssRetido;

            $valorIss = ( $ValorServicos * $Perc_ISS ) / 100;

            $valor_iss = number_format($valorIss, 2, ',', '.');
            $credito_iptu = '0,00';

            $Prestador = $InfRps->getElementsByTagName ( "Prestador" )->item ( 0 );
            $Cnpj_Tomador = $Prestador->getElementsByTagName ( "Cnpj" )->item ( 0 )->nodeValue;
            
            if ( $Cnpj_Tomador == '08745918000171'){
                $emp_nome = 'WC SISTEMAS E EQUIPAMENTOS DE INFORMATICA LTDA - ME';
                $emp_cnpj = '08.745.918/0001-71';
                $emp_im = '17 22 0523090-6';
            }else{
                $emp_nome = 'WEB CONTROL SISTEMAS DE AUTOMACAO LTDA ME';
                $emp_cnpj = '13.117.948/0001-73';
                $emp_im = '01 01 0604910-9';
            }
            $emp_end = 'AV. CANDIDO DE ABREU, 70 - BAIRRO: CENTRO CIVICO';
            $emp_tel = '(41) 3207-1744';
            $emp_cid = 'CURITIBA';
            $emp_uf = 'PR';
            $emp_email = 'administrativo@webcontrolempresas.com.br';

            $Servicos = $InfRps->getElementsByTagName ( "Servico" )->item ( 0 );
            
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
            $qry2 = mysql_query($sql_sel,$conexao) or die("Erro SQL: $sql_sel");
            $cli_cid = mysql_result($qry2,0,'descricao');

            $cli_end = $Rua.', '.$Num.' - Bairro: '.$Bai.' - CEP: '.$Cep;

            $Contato = $InfRps->getElementsByTagName ( "Contato" )->item ( 0 );
            $Contato_Telefone = trim($Contato->getElementsByTagName ( "Telefone" )->item ( 0 )->nodeValue);
            $cli_email = trim($Contato->getElementsByTagName ( "Email" )->item ( 0 )->nodeValue);

            $descriminacao = trim($Servicos->getElementsByTagName ( "Discriminacao" )->item ( 0 )->nodeValue);
            if ( $ValorIssRetido > 0 ){
                $descriminacao .= '<br><br>ISS RETIDO : R$ '.number_format($ValorIssRetido, 2, ',', '.');                
            }

            if ( $emp_cnpj == '08.745.918/0001-71' ){
                $cod_atividade = '17 22 - Cobrança em geral.';
            }else{
                $cod_atividade = '15 05 - Cadastro, elabora&ccedil;&atilde;o de ficha cadastral, renova&ccedil;&atilde;o cadastral e congeneres, inclus&atilde;o ou exclus&atilde;o no Cadastro de Emitentes de Cheques sem Fundos - CCF ou em quaisquer outros bancos cadastrais.';
            }
            
            $info = '';
            
            if ( $ValorIssRetido > 0 ){
                $info = 'O ISS desta NFS-e será RETIDO pelo Tomador do Serviço<br>';
            }
            $info .= 'Documento Emitido por ME ou EPP optante pelo Simples Nacional.';
            $info .= '<br>Nao gera direito a credito de IPI.';

            $html .= "
                    <table border='1' cellspacing='0' id='tbl-principal'>
                        <tr>
                            <td style='width: 108px'><img src='https://webcontrolempresas.com.br/nfse/logo_municipio/41_06902.jpg' width='88' id='logo'></td>
                            <td style='width: 550px'>
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
                                        <td id='codigo-verificacao'>C&oacute;digo de Verifica&ccedil;&atilde;o</td>
                                    </tr>
                                    <tr>
                                        <td class='text-center'><strong>$codigo_verificacao</strong></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style='width: 108px'><img src='https://webcontrolempresas.com.br/nfse/logo_municipio/logomarca.png' width='88' id='logo'></td>
                            <td colspan='2'>
                                <table border='0' cellspacing='0' cellpadding='0'>
                                    <tr height='25'>
                                        <td colspan='6' class='text-center'><strong>PRESTADOR DE SERVI&Ccedil;OS</strong></td>
                                    </tr>
                                    <tr>
                                        <td style='width: 80px'><strong>Raz&atilde;o Social:</strong></td>
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
                                        <td colspan='1'>$emp_end</td>
                                        <td colspan='3' align='right'><strong>Telefone:</strong></td>
                                        <td colspan='1'>$emp_tel</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Munic&iacute;pio:</strong></td>
                                        <td>$emp_cid</td>
                                        <td><strong>UF:</strong></td>
                                        <td>$emp_uf</td>
                                        <td><strong>Email:</strong></td>
                                        <td>$emp_email</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr height='25'>
                            <td colspan='3' class='text-center'><strong>TOMADOR DE SERVI&Ccedil;OS</strong></td>
                        </tr>
                        <tr>
                            <td colspan='3'>
                                <table border='0' cellspacing='0' cellpadding='0' id='tbl-tomador'>
                                    <tr>
                                        <td style='width: 80px'><strong>Nome/Raz&atilde;o Social:</strong></td>
                                        <td colspan='5'>$cli_nome</td>
                                    </tr>
                                    <tr>
                                        <td><strong>CPF / CNPJ:</strong></td>
                                        <td>$cli_cnpj</td>
                                        <td><strong>IMU:</strong></td>
                                        <td>$cli_im</td>
                                        <td><strong>Outro Doc.:</strong></td>
                                        <td>$cli_outro_doc</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Endere&ccedil;o:</strong></td>
                                        <td colspan='5'>$cli_end</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Munic&iacute;pio:</strong></td>
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
                            <td colspan='3' class='text-center'><strong>VALOR TOTAL DA NOTA - R$ $ValorliquidoNfse</strong></td>
                        </tr>
                        <tr>
                            <td colspan='3'>
                                C&oacute;digo da Atividade<br><br>
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
                    </table>
                    <div style='page-break-after: always'></div>";

        }
    }
}

$html .= "
                <table width='800px'>
                    <tr><td></td></tr>
                    <tr align='center' class='noprint'>
                         <td>
                             <input type='submit' value=' Voltar a listagem ' onClick='Voltar_Listagem()' />
                         </td>
                     </tr>
                 </table>
            </body>
            </form>
        </html>
";
        
print_r($html);
echo "<script>this.print()</script>";

?>