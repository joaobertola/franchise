<?php
require "connect/sessao.php";

$go        = $_REQUEST['go'];

$quantidade = 100;
//a pagina atual
$pagina     = (isset($_REQUEST['pagina'])) ? (int)$_REQUEST['pagina'] : 1;
//Calcula a pagina de qual valor será exibido
$inicio     = ($quantidade * $pagina) - $quantidade;

if (empty($go)) { ?>

    <script type="text/javascript" src="../../../inform/js/prototype.js"></script>
    <br>
    <form name="form" method="post" action="#">
        <table width='80%' border='0' cellpadding='0' cellspacing='0' class='bodyText'
            <tr class="titulo">
                <td colspan="2">EMISS&Atilde;O / IMPRESS&Atilde;O NOTA FISCAL - WEBCONTROL EMPRESAS</td>
            </tr>
            <tr class="titulo">
                <td colspan="2">NFSe- Curitiba / PR (Faturas de Clientes)</td>
            </tr>
            <tr class="titulo">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td width="25%" class="subtitulodireita">Faturamento</td>
                <td width="50%" class="campoesquerda">
                    <?php 
                    $sql_sel = "SELECT * FROM cs2.controle_faturamento ORDER BY data_emissao DESC LIMIT 1000";
                    $qry = mysql_query($sql_sel,$con) or die("Erro SQL: $sql_sel");
                    echo "<select name='id_faturamento' id='id_faturamento' style='width:42%'>";
                    while($rs = mysql_fetch_array($qry)) {?>
                        <option value="<?=$rs['mesano']?>"><?=substr($rs['mesano'],0,2)?> / <?=substr($rs['mesano'],2,4)?></option>
                    <?php
                    }
                    echo "  </select>";
                    ?>
                    <input type="hidden" name="go" value="ingressar" />
                </td>                
            </tr>
            <tr>
                <td width="25%" class="subtitulodireita">Cliente</td>
                <td width="50%" class="campoesquerda">
                    <input type="text" name="cliente" maxlength="10" >
                </td>
            </tr>
            <tr>
        <td colspan="2" class="titulo">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo">
                    <input type="submit" value=" Listar Faturas" name="envia" onClick="return check(this.form);"/>
                    <input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " />
                </td>
            </tr>
        </table>
    </form>
<?php
}

if ($go=='ingressar'){
    $cliente    = $_REQUEST['cliente'];
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
        
        function PrintTodasNotas(faturamento){
            var numdoc = $('input:checkbox:checked').map(function(){
                return this.value;
            }).get();
            frm = document.form_titulo; 
            frm.action = "?pagina1=Franquias/b_notafiscal_imprimir.php&go=ingressar&numdoc="+numdoc+"&faturamento="+faturamento;
            frm.submit();
        }
        
        function EmailTodasNotas(faturamento){
            var numdoc = $('input:checkbox:checked').map(function(){
                return this.value;
            }).get();
            frm = document.form_titulo; 
            frm.action = "https://www.webcontrolempresas.com.br/franquias/php/Franquias/b_notafiscal_sendmail_lote.php?numdoc="+numdoc+"&faturamento="+faturamento;
            frm.submit();
        }
        
        function SendMail_Notas(faturamento,numdoc){
            window.open("https://www.webcontrolempresas.com.br/franquias/php/Franquias/b_notafiscal_sendmail.php?numdoc="+numdoc+"&faturamento="+faturamento);
        }
        
        function Gerar_NFSe(faturamento,numdoc) {
            var r = confirm("Você tem certeza que deseja gerar a NOTA FISCAL DE SERVIÇO ?");
            if (r == true) {
                location.href = 'painel.php?pagina1=Franquias/b_notafiscal_gerar.php&numdoc=' + numdoc;
            }
        }
        

    </script>
        
    <?php
    try{
        
        $faturamento = $_REQUEST['id_faturamento'];
        $mes = substr($faturamento,3,3)*1;
        $ano = substr($faturamento,0,2);
        $tem_nota_gerar = 0;
        $arquivo = "/var/www/html/franquias/php/nfse/rps/producao/xml/NF_$mes$ano.xml";
        
        if ( file_exists($arquivo) ){

            $docxml = file_get_contents($arquivo);
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->preservWhiteSpace = false; // elimina espacos em branco
            $dom->formatOutput = false; // ignora formatacao
            $dom->loadXML( $docxml , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );
            
            $EnviarLoteRpsEnvio = $dom->getElementsByTagName ( "ArrayOfTcCompNfse" )->item ( 0 );

            foreach ( $EnviarLoteRpsEnvio->getElementsByTagName ( "tcCompNfse" ) as $Rps ){
                
                
                
                $Rps = $Rps->getElementsByTagName ( "Nfse" )->item ( 0 );
                $InfNfse = $Rps->getElementsByTagName ( "InfNfse" )->item ( 0 );

                $Numero = trim($InfNfse->getElementsByTagName ( "Numero" )->item ( 0 )->nodeValue);
                $CodigoVerificacao = trim($InfNfse->getElementsByTagName ( "CodigoVerificacao" )->item ( 0 )->nodeValue);

                $data_emissao = trim($InfNfse->getElementsByTagName ( "DataEmissao" )->item ( 0 )->nodeValue);
                
                $pmc_emitido = trim($InfNfse->getElementsByTagName ( "DataEmissao" )->item ( 0 )->nodeValue);
  
                $NaturezaOperacao = trim($InfNfse->getElementsByTagName ( "NaturezaOperacao" )->item ( 0 )->nodeValue);
                $RegimeEspecialTributacao = trim($InfNfse->getElementsByTagName ( "RegimeEspecialTributacao" )->item ( 0 )->nodeValue);
                $OptanteSimplesNacional = trim($InfNfse->getElementsByTagName ( "OptanteSimplesNacional" )->item ( 0 )->nodeValue);
                $IncentivadorCultural = trim($InfNfse->getElementsByTagName ( "IncentivadorCultural" )->item ( 0 )->nodeValue);
                // $Status = trim($InfRps->getElementsByTagName ( "Status" )->item ( 0 )->nodeValue);
                $Servicos = $InfNfse->getElementsByTagName ( "Servico" )->item ( 0 );
                
                $Prestador = $InfNfse->getElementsByTagName ( "PrestadorServico" )->item ( 0 );
                
                $Descricao = $Servicos->getElementsByTagName ( "Discriminacao" )->item ( 0 )->nodeValue;

                $Valores = $Servicos->getElementsByTagName ( "Valores" )->item ( 0 );
                $ValorServicos = trim($Valores->getElementsByTagName ( "ValorServicos" )->item ( 0 )->nodeValue);
                $IssRetido = trim($Valores->getElementsByTagName ( "IssRetido" )->item ( 0 )->nodeValue);
                $Perc_ISS = trim($Valores->getElementsByTagName ( "IssRetido" )->item ( 0 )->nodeValue);
                
                $ValorIss = trim($Valores->getElementsByTagName ( "ValorIss" )->item ( 0 )->nodeValue);
                $ValorIssRetido = trim($Valores->getElementsByTagName ( "ValorIssRetido" )->item ( 0 )->nodeValue);
                $BaseCalculo = trim($Valores->getElementsByTagName ( "BaseCalculo" )->item ( 0 )->nodeValue);
                $Aliquota = trim($Valores->getElementsByTagName ( "Aliquota" )->item ( 0 )->nodeValue);
                $ValorLiquidoNfse = trim($Valores->getElementsByTagName ( "ValorLiquidoNfse" )->item ( 0 )->nodeValue);
                $DescontoIncondicionado = trim($Valores->getElementsByTagName ( "DescontoIncondicionado" )->item ( 0 )->nodeValue);
                $DescontoCondicionado = trim($Valores->getElementsByTagName ( "DescontoCondicionado" )->item ( 0 )->nodeValue);
                
                $descriminacao = trim($Servicos->getElementsByTagName ( "Discriminacao" )->item ( 0 )->nodeValue);
                $pos_i = strpos($descriminacao,'- TITULO: ');
                $numdoc = trim(substr($descriminacao,$pos_i+10,10));
                
                $ItemListaServico = trim($Servicos->getElementsByTagName ( "ItemListaServico" )->item ( 0 )->nodeValue);
                $cod_mun1 = trim($Servicos->getElementsByTagName ( "CodigoMunicipio" )->item ( 0 )->nodeValue);

                $Pestador_cnpj = trim($Prestador->getElementsByTagName ( "Cnpj" )->item ( 0 )->nodeValue);
                $Pestador_im = trim($Prestador->getElementsByTagName ( "InscricaoMunicipal" )->item ( 0 )->nodeValue);
                
                $Tomador = $InfNfse->getElementsByTagName ( "TomadorServico" )->item ( 0 );
                $IdentificacaoTomador = $Tomador->getElementsByTagName ( "IdentificacaoTomador" )->item ( 0 );
                $CpfCnpj = $IdentificacaoTomador->getElementsByTagName ( "CpfCnpj" )->item ( 0 );
                
                $cli_cnpj = trim($CpfCnpj->getElementsByTagName ( "Cnpj" )->item ( 0 )->nodeValue);
                $cli_nome = trim($Tomador->getElementsByTagName ( "RazaoSocial" )->item ( 0 )->nodeValue);
                             
                $Endereco = $Tomador->getElementsByTagName ( "Endereco" )->item ( 0 );
                $Rua = trim($Endereco->getElementsByTagName ( "Endereco" )->item ( 0 )->nodeValue);
                $Num = trim($Endereco->getElementsByTagName ( "Numero" )->item ( 0 )->nodeValue);
                $Bai = trim($Endereco->getElementsByTagName ( "Bairro" )->item ( 0 )->nodeValue);
                $CodigoMunicipio = trim($Endereco->getElementsByTagName ( "CodigoMunicipio" )->item ( 0 )->nodeValue);
                
                $cli_uf = trim($Endereco->getElementsByTagName ( "Uf" )->item ( 0 )->nodeValue);
                $Cep = trim($Endereco->getElementsByTagName ( "Cep" )->item ( 0 )->nodeValue);
                
                $Contato = $Tomador->getElementsByTagName ( "Contato" )->item ( 0 );
                $Contato_Telefone = trim($Contato->getElementsByTagName ( "Telefone" )->item ( 0 )->nodeValue);
                $Contato_Email = trim($Contato->getElementsByTagName ( "Email" )->item ( 0 )->nodeValue);

                $xml = "<?xml version='1.0'?>
<EnviarLoteRpsEnvio xmlns='http://isscuritiba.curitiba.pr.gov.br/iss/nfse.xsd' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://isscuritiba.curitiba.pr.gov.br/iss/nfse.xsd'>
    <LoteRps id='201609261058'>
        <NumeroLote>$NumeroLoteRPS</NumeroLote>
        <Cnpj>$Cnpj</Cnpj>
        <InscricaoMunicipal>$InscricaoMunicipal</InscricaoMunicipal>
        <QuantidadeRps>1</QuantidadeRps>
        <ListaRps>
            <Rps>
                <InfRps>
                    <IdentificacaoRps>
                        <Numero>$Numero</Numero>
                        <Serie>$Serie</Serie>
                        <Tipo>$Tipo</Tipo>
                    </IdentificacaoRps>
                    <DataEmissao>$data_emissao</DataEmissao>
                    <NaturezaOperacao>$NaturezaOperacao</NaturezaOperacao>
                    <RegimeEspecialTributacao>$RegimeEspecialTributacao</RegimeEspecialTributacao>
                    <OptanteSimplesNacional>$OptanteSimplesNacional</OptanteSimplesNacional>
                    <IncentivadorCultural>$IncentivadorCultural</IncentivadorCultural>
                    <Status>$Status</Status>
                    <Servico>
                        <Valores>
                            <ValorServicos>$ValorServicos</ValorServicos>
                            <IssRetido>$IssRetido</IssRetido>
                            <ValorIss>$ValorIss</ValorIss>
                            <ValorIssRetido>$ValorIssRetido</ValorIssRetido>
                            <BaseCalculo>$BaseCalculo</BaseCalculo>
                            <Aliquota>$Aliquota</Aliquota>
                            <ValorLiquidoNfse>$ValorLiquidoNfse</ValorLiquidoNfse>
                            <DescontoIncondicionado>$DescontoIncondicionado</DescontoIncondicionado>
                            <DescontoCondicionado>$DescontoCondicionado</DescontoCondicionado>
                        </Valores>
                        <ItemListaServico>$ItemListaServico</ItemListaServico>
                        <Discriminacao>$descriminacao</Discriminacao>
                        <CodigoMunicipio>$cod_mun1</CodigoMunicipio>
                    </Servico>
                    <Prestador>
                        <Cnpj>$Pestador_cnpj</Cnpj>
                        <InscricaoMunicipal>$Pestador_im</InscricaoMunicipal>
                    </Prestador>
                    <Tomador>
                        <IdentificacaoTomador>
                            <CpfCnpj>
                                <Cnpj>$cli_cnpj</Cnpj>
                            </CpfCnpj>
                        </IdentificacaoTomador>
                        <RazaoSocial>$cli_nome</RazaoSocial>
                        <Endereco>
                            <Endereco>$Rua</Endereco>
                            <Numero>$Num</Numero>
                            <Bairro>$Bai</Bairro>
                            <CodigoMunicipio>$CodigoMunicipio</CodigoMunicipio>
                            <Uf>$cli_uf</Uf>
                            <Cep>$Cep</Cep>
                        </Endereco>
                        <Contato>
                            <Telefone>$Contato_Telefone</Telefone>
                            <Email>$Contato_Email</Email>
                        </Contato>
                    </Tomador>
                </InfRps>
            </Rps>
        </ListaRps>
    </LoteRps>
</EnviarLoteRpsEnvio>
";
                $sql_verifica = "SELECT numdoc FROM cs2.titulos_notafiscal WHERE numdoc = '$numdoc'";
                $qry_verifica = mysql_query($sql_verifica,$con) or die("Erro SQL: $sql_verifica");
                $num = mysql_result($qry_verifica,0,'numdoc');
                if ( trim($num) == '' ){
                    $xml = str_replace("'",'"',$xml);
                    $data_emissao = str_replace("T",' ',$data_emissao);
                    $sql_insert = "INSERT INTO cs2.titulos_notafiscal(numdoc, xml, status, data_emissao, numero_nota, codigo_verificacao)
                                   VALUES( '$numdoc' , '$xml' , '5' , '$data_emissao' , '$Numero', '$CodigoVerificacao')";
                    $qr_insert = mysql_query($sql_insert, $con) or die("Erro SQL: $sql_insert");
                }                
            } // Foreach
        }

        
        if ( $cliente != '' ){
            
            $sqlx = "SELECT id_cadastro FROM base_web_control.webc_usuario WHERE login = '$cliente'";
            $qrx = mysql_query($sqlx,$con) or die("Erro SQL: $sqlx");
            $codloja = mysql_result($qrx,0,'id_cadastro');
            $adicional = " AND a.codloja = ".$codloja." ";
        }
        $sql = "SELECT 
                    a.insc, a.razaosoc, a.cidade, a.uf, a.fone, a.email,
                    b.valor, b.numdoc,
                    date_format(b.vencimento,'%d/%m/%Y') as vencimento,
                    ( SELECT login FROM base_web_control.webc_usuario WHERE id_cadastro = a.codloja LIMIT 1 )as login,
                    c.protocolo, c.status, c.data_emissao, c.numero_nota, c.codigo_verificacao
                FROM cs2.cadastro a 
                LEFT OUTER JOIN cs2.titulos b ON a.codloja = b.codloja
                LEFT OUTER JOIN cs2.titulos_notafiscal c ON b.numdoc = c.numdoc
                WHERE 
                    MID(b.numdoc,1,4) = '$mes$ano'
                    $adicional
                ORDER BY a.razaosoc";

        $qry = mysql_query($sql,$con) or die("Erro SQL: $sql");
        $tot_reg = mysql_num_rows($qry);
        $totalPagina= ceil($tot_reg/$quantidade);
        $anterior  = (($pagina - 1) == 0) ? 1 : $pagina - 1;
        $posterior = (($pagina+1) >= $totalPagina) ? $totalPagina : $pagina+1;

        $sql = "SELECT 
                    a.insc, a.razaosoc, a.cidade, a.uf, a.fone, a.email,
                    b.valor, b.numdoc,
                    date_format(b.vencimento,'%d/%m/%Y') as vencimento,
                    ( SELECT login FROM base_web_control.webc_usuario WHERE id_cadastro = a.codloja LIMIT 1 )as login,
                    c.protocolo, c.status, c.data_emissao, c.numero_nota, c.codigo_verificacao
                FROM cs2.cadastro a 
                LEFT OUTER JOIN cs2.titulos b ON a.codloja = b.codloja
                LEFT OUTER JOIN cs2.titulos_notafiscal c ON b.numdoc = c.numdoc
                WHERE 
                    MID(b.numdoc,1,4) = '$mes$ano'
                    $adicional
                ORDER BY a.razaosoc
                LIMIT $inicio, $quantidade";

        $qry = mysql_query($sql,$con) or die("Erro SQL: $sql");
        $tot_reg = mysql_num_rows($qry);
        if ( $tot_reg > 0 ){
            
            echo "
                <form name='form_titulo' method='post'>
                    <table width='100%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
                        <tr class='titulo'>
                            <td colspan='9'><br>IMPRESS&Atilde;O DE NOTA FISCAL DE SERVI&Ccedil;O - WEBCONTROL EMPRESAS<br></td>
                        </tr>
                        <tr height='20' bgcolor='87b5ff' style='font-size:9px'>
                            <th>C&oacute;digo</th>
                            <th>Raz&atilde;o Social</th>
                            <th>Cidade / Uf</th>
                            <th>N&deg; Documento</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th>
                                <br>
                                    <input type='checkbox' name='checktodos' onclick='selecionar_tudo()'>
                                <br>
                            </th>
                            <th colspan='2'></th>
                        </tr>
                        <tr>
                            <td colspan='9' height='1' bgcolor='#666666'></td>
                        </tr>
                    ";
            $linha = 0;
            $registro = 0;
            while ( $reg = mysql_fetch_array($qry) ){
                $linha++;
                $registro++;
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
                $protocolo  = $reg['protocolo'];
                
                if ( $valor > 0 )
                    $link_ver_nota = "<input name='selecao[]' type='checkbox' value='$numdoc' />";
                else
                    $link_ver_nota = '';
                
                if ( $protocolo ){
                    // TEM NOTA GERADA
                    $link_print_nota = "<a href='painel.php?pagina1=Franquias/b_notafiscal_imprimir.php&numdoc=$numdoc&faturamento=$faturamento' title='Visualiza Nota Fiscal' ><IMG SRC='../img/imprimir.gif' width='16' height='16' border='0'></a>";
                    $link_sendmail_nota = "<a href='#' onClick='SendMail_Notas(\"$faturamento\",\"$numdoc\")'><img src='../img/mail.gif' height='16' border='0'></a>";
                }else{
                    // NAO FOI GERADA NOTA
                    $link_print_nota = "";
                
                    if ( $valor > 0 ){
                        $link_sendmail_nota = "<a href='#' onClick='Gerar_NFSe(\"$faturamento\",\"$numdoc\")'><img src='../img/nfiscal.gif' height='16' title='Emissão de Nota Fiscal' border='0'></a>";
                        $tem_nota_gerar++;
                    }
                }
                
                echo "<tr height='24' style='font-size:9px' ";
                if (($registro%2) <> 0) {
                    echo "bgcolor='#E5E5E5'>";
                } else {
                    echo ">";
                }
                echo "
                            <td width='15px'>$login</td>
                            <td width='300px'>$razaosoc</td>
                            <td>$cidade / $uf</td>
                            <td>$numdoc</td>
                            <td>$vencimento</td>
                            <td align='right'>".number_format($valor,2,',','.')."</td>
                            <td align='center'>$link_ver_nota</td>
                            <td align='center'>$link_print_nota</td>
                            <td align='center'>$link_sendmail_nota</td>
                      </tr>";
            }
        }
        else{
            echo "<script>alert('Nenhum registro encontrado ou Cliente Cancelado.')</script>";
            die;
        }
  
    } catch (Exception $e) {
            echo 'Erro ao listar os Titulos. ',  $e->getMessage(), "\n";
    }
    echo "<tr><td colspan='9'><hr></td>";
    echo "<tr><td colspan='2'>Listados $linha Faturas.</td>";
    
    if ( $tem_nota_gerar > 0 ){
        echo "    <td colspan='2'><input type='button' value=' Gerar Nota Fiscal ' onClick='GerarTodasNotas()' >";
    }
    echo "    <td colspan='2'><input type='submit' value=' Imprimir Notas Selecionadas ' onClick='PrintTodasNotas(\"$faturamento\")' >";
    echo "    <td colspan='2'><input type='submit' value=' Enviar por Email as Notas Selecionadas ' onClick='EmailTodasNotas(\"$faturamento\")' >";
    echo "</tr>";

    echo "<tr>";
    echo "<td colspan='9' align='center'>";
    echo "<a href=../php/painel.php?pagina1=Franquias/b_notafiscal_new.php&id_faturamento=$faturamento&go=ingressar&pagina=1>primeira</a> | ";
    echo "<a href=../php/painel.php?pagina1=Franquias/b_notafiscal_new.php&id_faturamento=$faturamento&go=ingressar&pagina=$anterior>anterior</a> | ";

    for($i = $pagina-$exibir; $i <= $pagina-1; $i++){
        if($i > 0)
            echo "<a href=https://www.webcontrolempresas.com.br/franquias/php/painel.php?pagina1=Franquias/b_notafiscal_new.php&id_faturamento=$faturamento&go=ingressar&pagina=".$i.'> '.$i.' </a>';
    }

    echo "<a href=https://www.webcontrolempresas.com.br/franquias/php/painel.php?pagina1=Franquias/b_notafiscal_new.php&id_faturamento=$faturamento&go=ingressar&pagina=".$pagina.'><strong>'.$pagina.'</strong></a>';

    for($i = $pagina+1; $i < $pagina+$exibir; $i++){
        if($i <= $totalPagina)
        echo "<a href=https://www.webcontrolempresas.com.br/franquias/php/painel.php?pagina1=Franquias/b_notafiscal_new.php&id_faturamento=$faturamento&go=ingressar&pagina=".$i.'> '.$i.' </a>';
    }
    echo " | <a href=../php/painel.php?pagina1=Franquias/b_notafiscal_new.php&id_faturamento=$faturamento&go=ingressar&pagina=$posterior>pr&oacute;xima</a> | ";
    echo "   <a href=../php/painel.php?pagina1=Franquias/b_notafiscal_new.php&id_faturamento=$faturamento&go=ingressar&pagina=$totalPagina>&uacute;ltima</a>";
 
    echo "</td>";
    echo "</tr>";
    echo "</table>";
    echo "</form>";
}

?>