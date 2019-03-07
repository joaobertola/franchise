<?php
require "connect/sessao.php";

$go 	   = $_POST['go'];
$codigo    = $_POST['codigo'];
$tp_libera = $_POST['tp_libera'];

if (empty($go)) { ?>
    <script language="javascript">
            //fun��o para aceitar somente numeros em determinados campos
            function mascara(o,f){
                v_obj=o
                v_fun=f
                setTimeout("execmascara()",1)
            }
            function execmascara(){
                v_obj.value=v_fun(v_obj.value)
            }
            function soNumeros(v){
                return v.replace(/\D/g,"")
            }

            function baixa_lote(){
                    frm = document.form;
                    frm.action = 'Franquias/ver_lote_confirma.php';
                    frm.submit();

            }
    </script>

    <script type="text/javascript" src="../../../inform/js/prototype.js"></script>
    <br>
    <form name="form" method="post" action="#">
        <table width="80%" border="0" align="center">
            <tr class="titulo">
                <td colspan="3">EMISS&Atilde;O / IMPRESS&Atilde;O NOTA FISCAL - WEBCONTROL EMPRESAS</td>
            </tr>
            <tr class="titulo">
                <td colspan="3">NFSe- Curitiba / PR (Faturas de Clientes)</td>
            </tr>
            <tr class="titulo">
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td width="25%" class="subtitulodireita">Faturamento</td>
                <td width="50%" class="campoesquerda">
                    <?php 
                    $sql_sel = "SELECT * FROM cs2.controle_faturamento ORDER BY data_emissao DESC LIMIT 3";
                    $qry = mysql_query($sql_sel) or die("Erro SQL: $sql_sel");
                    echo "<select name='id_faturamento' id='id_faturamento' style='width:42%'>";
                    while($rs = mysql_fetch_array($qry)) {?>
                        <option value="<?=$rs['mesano']?>"><?=substr($rs['mesano'],0,2)?> / <?=substr($rs['mesano'],2,4)?></option>
                    <?php
                    }
                    echo "	</select>";
                    ?>
                    <input type="hidden" name="go" value="ingressar" />
                </td>
                <td width="25%" class="campoesquerda"><input type="submit" value=" Listar Faturas" name="envia" onClick="return check(this.form);"/></td>
            </tr>
            <tr>
		<td colspan="3" class="titulo">&nbsp;</td>
            </tr>
        </table>
    </form>
    <div align="center">
        <input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " />
    </div>
<?php
}

if ($go=='ingressar'){
    
    ?>
    <script type="text/javascript" src="../../js/jquery.js"></script>
    <script type="text/javascript" src="../../js/jquery.maskedinput-1.1.1.js"></script>
    <script type="text/javascript" src="../../js/jquery.meio.mask.js"></script>

    <script language='javascript'>
        
        function selecionar_tudo(){
            for (i=0;i<document.form_titulo.elements.length;i++){
                if(document.form_titulo.elements[i].type == 'checkbox'){
                    if(document.form_titulo.checktodos.checked == 1)
                        document.form_titulo.elements[i].checked=1
                    else
                        document.form_titulo.elements[i].checked=0
                }
            }
        }

        function GerarTodasNotas(){

            var numdoc = $('input:checkbox:checked').map(function(){
             return this.value;
                }).get();
        
            $.ajax({
            type: "POST",
            url: "nfse/app/enviar.php",
            dataType: 'html',
            data: 'numdoc='+numdoc,
            success: function( data ){
                console.log(data);
            }
        });
        }

    </script>
        
    <?php
    try{
        
        
        $faturamento = $_REQUEST['id_faturamento'];
        $mes = substr($faturamento,3,3)*1;
        $ano = substr($faturamento,0,2);

        
        $arquivo = "/var/www/html/franquias/php/nfse/rps/producao/xml/NF_$mes$ano.xml";
        
        if ( file_exists($arquivo) ){
            
            $docxml = file_get_contents($arquivo);
            
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->preservWhiteSpace = false; // elimina espacos em branco
            $dom->formatOutput = false; // ignora formatacao
            $dom->loadXML( $docxml , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );
            
            $EnviarLoteRpsEnvio = $dom->getElementsByTagName ( "EnviarLoteRpsEnvio" )->item ( 0 );
            $LoteRps = $EnviarLoteRpsEnvio->getElementsByTagName ( "LoteRps" )->item ( 0 );
            $Cnpj = trim($LoteRps->getElementsByTagName ( "Cnpj" )->item ( 0 )->nodeValue);
            $InscricaoMunicipal = trim($LoteRps->getElementsByTagName ( "InscricaoMunicipal" )->item ( 0 )->nodeValue);
            $NumeroLoteRPS = trim($LoteRps->getElementsByTagName ( "NumeroLote" )->item ( 0 )->nodeValue);
            $QuantidadeRps = trim($LoteRps->getElementsByTagName ( "QuantidadeRps" )->item ( 0 )->nodeValue);

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
                            #data-hora-titulo{border-top: 1px solid #000}
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
                    <body>";
            
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
                
                $codigo_verificacao = '0JFL110A';
                
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
                $qry = mysql_query($sql_sel) or die("Erro SQL: $sql_sel");
                $cli_cid = mysql_result($qry,0,'descricao');
                
                $cli_end = $Rua.', '.$Num.' - Bairro: '.$Bai.' - CEP: '.$Cep;

                $cli_email = '';

                $descriminacao = trim($Servicos->getElementsByTagName ( "Discriminacao" )->item ( 0 )->nodeValue);

                $cod_atividade = '15 - 05 - Cadastro, elabora&ccedil;&atilde;o de ficha cadastral, renova&ccedil;&atilde;o cadastral e congeneres, inclus&atilde;o ou exclus&atilde;o no Cadastro de Emitentes de Cheques sem Fundos - CCF ou em quaisquer outros bancos cadastrais.';

                $info = 'Documento Emitido por ME ou EPP optante pelo Simples Nacional.<br>Nao gera direito a credito de IPI.';


                $html .= "
                        <table border='1' cellspacing='0' id='tbl-principal'>
                            <tr>
                                <td style='width: 108px'><img src='nfse/logo_municipio/logomarca.jpg' width='88' id='logo'></td>
                                <td>
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
                            <tr>
                                <td></td>
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
                                            <td><$emp_uf</td>
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
                                <td colspan='3' class='text-center'><strong>VALOR TOTAL DA NOTA - R$ $total</strong></td>
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
            
            $html .= "</body></html>";
        
            
        }
        
        print_r($html);
        die;
        
        echo 'sdfsfsdf';
        die;
        
        $sql = "SELECT 
                    a.insc, a.razaosoc, a.cidade, a.uf, a.fone, a.email,
                    b.valor, b.numdoc,
                    date_format(b.vencimento,'%d/%m/%Y') as vencimento,
                    ( SELECT login FROM base_web_control.webc_usuario WHERE id_cadastro = a.codloja LIMIT 1 )as login,
                    b.protocolo_nf
                FROM cs2.cadastro a 
                LEFT OUTER JOIN cs2.titulos b ON a.codloja = b.codloja
                WHERE 
                    a.emitir_nfs = 'S' AND a.sitcli < 2 AND MID(numdoc,1,4) = '$mes$ano'
                ORDER BY a.razaosoc";
        
        $qry = mysql_query($sql) or die("Erro SQL: $sql");
        if (mysql_num_rows($qry) > 0 ){
            
            echo "
                <form name='form_titulo' method='post'>
                    <table width='80%' border='0'>
                        <tr class='titulo'>
                            <th>C&oacute;digo</th>
                            <th>Raz&atilde;o Social</th>
                            <th>Cidade/UF</th>
                            <th>Num Documento</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th><input type='checkbox' name='checktodos' onclick='selecionar_tudo()'></th>
                        </tr>
                    ";
            $linha = 0;
            while ( $reg = mysql_fetch_array($qry) ){
                $linha++;
                $login      = $reg['login'];
                $insc       = $reg['insc'];
		$razaosoc   = substr($reg['razaosoc'],0,40);
		$cidade     = $reg['cidade'];
		$uf         = $reg['uf'];
		$fone       = $reg['fone'];
		$email      = $reg['email'];
		$numdoc     = $reg['numdoc'];
		$vencimento = $reg['vencimento'];
		$valor      = $reg['valor'];
		$protocolo  = $reg['protocolo_nf'];
                
                if ( $protocolo_nf ){
                    
                    $link_ver_nota = "<a href='painel.php?pagina1=Franquias/b_ver_notafiscal.php&numdoc=$numdoc' onMouseOver=\"window.status='Cancela Recebimento de T�tulo'; return true\" title='Visualiza Nota Fiscal' onclick='return alerta()'><IMG SRC='../img/imprimir.gif' width='16' height='16' border='0'></a>";
                }else
                    $link_ver_nota = "<input name='selecao[]' type='checkbox' value='$numdoc' />";
                
                echo "<tr>
                            <td>$login</td>
                            <td>$razaosoc</td>
                            <td>$cidade / $uf</td>
                            <td>$numdoc</td>
                            <td>$vencimento</td>
                            <td align='right'>".number_format($valor,2,',','.')."</td>
                            <td>$link_ver_nota</td>
                      </tr>";
            }
        }
  
    } catch (Exception $e) {
            echo 'Erro ao listar os Titulos. ',  $e->getMessage(), "\n";
    }
    echo "<tr><td colspan='7'><hr></td>";
    echo "<tr><td colspan='3'>Listados $linha Faturas.</td>";
    echo "    <td colspan='2'><input type='button' value=' Gerar Nota Fiscal ' onClick='GerarTodasNotas()' >";
    echo "    <td colspan='2'><input type='button' value=' Verificar Notas ' onClick='ConsultarTodasNotas()' >";
    echo "</table></form>";
}

?>