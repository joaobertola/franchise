<?php

date_default_timezone_set('America/Los_Angeles');

define('_NFSE', true);

require "connect/sessao.php";
require "connect/conexao_conecta.php";
require('includes/application.php');
require('classes/rps_curitiba_pr.class.php');

function troca($value){
    $saidax = '';
    $value = trim($value);
    if ( strlen($value) > 0 ){
        for ($i = 0; $i <= strlen($value); $i++) {
            $x = $value[$i];
            $o = ord($value[$i]);
            switch ($o) {
                case 40 : $saidax .= ' ';
                    break; // (
                case 41 : $saidax .= ' ';
                    break; // )
                case 210 : $saidax .= 'O';
                    break; // Ò
                case 211 : $saidax .= 'O';
                    break; // Ó
                case 212 : $saidax .= 'O';
                    break; // Ô
                case 213 : $saidax .= 'O';
                    break; // Õ
                case 214 : $saidax .= 'O';
                    break; // Ö
                case 192 : $saidax .= 'A';
                    break; // À
                case 193 : $saidax .= 'A';
                    break; // Á
                case 194 : $saidax .= 'A';
                    break; // Â
                case 195 : $saidax .= 'A';
                    break; // Ã
                case 201 : $saidax .= 'E';
                    break; // É             
                case 202 : $saidax .= 'E';
                    break; // Ê
                case 203 : $saidax .= 'E';
                    break; // Ë
                case 204 : $saidax .= 'I';
                    break; // Ì
                case 205 : $saidax .= 'I';
                    break; // Í
                case 206 : $saidax .= 'I';
                    break; // Î
                case 207 : $saidax .= 'I';
                    break; // Ï
                case 217 : $saidax .= 'A';
                    break; // Ù
                case 218 : $saidax .= 'A';
                    break; // Ú
                case 219 : $saidax .= 'A';
                    break; // Û
                case 220 : $saidax .= 'A';
                    break; // Ü
               // case 91 : $saidax .= '';
               //     break; // [
               // case 93 : $saidax .= '';
               //     break; // ]
                case 43 : $saidax .= '';
                    break; // +
                case 64 : $saidax .= '';
                    break; // @
                case 38 : $saidax .= 'E';
                    break; // &
                case 199 : $saidax .= 'C';
                    break; // Ç
                default : $saidax .= $x;
                    break;
            }
        }
    }
    return $saidax;
}

$numdoc     = $_REQUEST['numdoc'];
$referencia = $_REQUEST['referencia'];

$sql = "SELECT 
             a.valor, a.vencimento, date_format(a.vencimento,'%d/%m/%Y') as venc,
             b.cnpj_empresa_faturar, b.insc, b.codloja, b.razaosoc, b.email,
             b.end, b.numero, b.bairro, b.cidade, b.uf, b.cep, b.fone,
             c.protocolo, c.status,
             mid(logon,1,5) as logon
        FROM cs2.titulos a
        INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
        LEFT OUTER JOIN cs2.titulos_notafiscal c ON a.numdoc = c.numdoc
        LEFT OUTER JOIN cs2.logon d ON a.codloja = d.codloja
        WHERE a.numdoc = '$numdoc' LIMIT 1 ";
$qry = mysql_query( $sql, $con ) or die("Erro ao executar o SQL. : $sql");
while ( $reg = mysql_fetch_array($qry) ){
    
    $valor                = $reg['valor'];
    $vencimento           = $reg['vencimento'];
    $cnpj_empresa_faturar = $reg['cnpj_empresa_faturar'];
    
    $protocolo            = trim($reg['protocolo']);
    $cpfcnpj              = $reg['insc'];
    $dados                = $reg['codloja'].' - '.$reg['razaosoc'];
    
    $RazaoSocial          = trim(troca($reg['razaosoc']));
    $Endereco             = trim(utf8_encode(troca($reg['end'])));
    $Numero               = trim($reg['numero']);
    $Complemento          = utf8_encode('');
    $Bairro               = trim(utf8_encode(troca($reg['bairro'])));
    $nCidade              = trim(utf8_encode(troca($reg['cidade'])));
    $Estado               = trim(utf8_encode(troca($reg['uf'])));
    $CEP                  = trim($reg['cep']);
    $Telefone             = trim($reg['fone']);
    $Email                = trim($reg['email']);
    $vencimento           = $reg['vencimento'];
    $venc                 = $reg['venc'];
    $valor                = $reg['valor'];
    $logon                = trim($reg['logon']);
    $status               = trim($reg['status']);
    
    #buscando codigo da cidade
    $sql_cidade = " SELECT CONCAT(a.id_estado,a.sigla) AS id_cidade
                    FROM base_web_control.nfe_municipio a
                    INNER JOIN base_web_control.nfe_uf b ON a.id_estado = b.id
                    WHERE a.descricao = '$nCidade' AND b.sigla = '$Estado'";
    $qry_cidade = mysql_query($sql_cidade,$con);
    $Cidade     = mysql_result($qry_cidade, 0, 'id_cidade') or die("Erro SQL: $sql_cidade");

    if ( empty($Cidade) ){
        echo "CIDADE INVALIDA --> ID: $codloja";
        exit;
    }

    if ( $cnpj_empresa_faturar == '08745918000171' ){
        // WC
        $Certificado_User  = 'wcsistemas';
        $Certificado_Cnpj  = '08745918000171';
        $Certificado_IM    = '05230906';
        $Certificado_Senha = 'WC20181974';
    }else{

        // WEBCONTROL
        $Certificado_User  = 'world click';
        $Certificado_Cnpj  = '13117948000173';
        $Certificado_IM    = '010106049109';
        $Certificado_Senha = 'WEBC20191974';
    }
        
    if ( $protocolo == '' ){
        
        // NAO FOI GERADO A NOTA FISCAL..   GERANDO O XML E ENVIANDO A PREFEITURA

        # Buscando NUMERO DO LOTE e NUMERO DO RPS
        $sql_lote_rps = "SELECT numero_lote, numero_rps, num_lote_WC, num_rps_WC FROM cs2.nota_controle";
        $qry_lote_rps = mysql_query($sql_lote_rps,$con);

        $WorldClick_NumeroLote = mysql_result($qry_lote_rps, 0, 'numero_lote');
        $WorldClick_NumeroRPS = mysql_result($qry_lote_rps, 0, 'numero_rps'); 
 
        $WC_NumeroLote = mysql_result($qry_lote_rps, 0, 'num_lote_WC');
        $WC_NumeroRPS = mysql_result($qry_lote_rps, 0, 'num_rps_WC');

        $DataEmissao     = date('Y-m-d').'T'.date('H:i:s');
        //CONFIGURAÇÃO DO ARQUIVO XML PARA A ASSINATURA
        $idNFe  = date('YmdHi');

        $RecepcionarLoteRps  = '    <EnviarLoteRpsEnvio xmlns="http://isscuritiba.curitiba.pr.gov.br/iss/nfse.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://isscuritiba.curitiba.pr.gov.br/iss/nfse.xsd">';
        
        if ( $cnpj_empresa_faturar == '08745918000171' ){
            // WC SISTEMAS
            $RecepcionarLoteRps .= "        <LoteRps id='WC_$idNFe'>\n";
            $WC_NumeroLote++;
            $RecepcionarLoteRps .= "            <NumeroLote>$WC_NumeroLote</NumeroLote>\n";
            
        }else{
            
            // WEBCONTROL
            $RecepcionarLoteRps .= "        <LoteRps id='WorldClick_$idNFe'>\n";
            $WorldClick_NumeroLote++;
            $RecepcionarLoteRps .= "            <NumeroLote>$WorldClick_NumeroLote</NumeroLote>\n";
        }
        
        $RecepcionarLoteRps .= "            <Cnpj>".$Certificado_Cnpj."</Cnpj>\n";
        $RecepcionarLoteRps .= "            <InscricaoMunicipal>".$Certificado_IM."</InscricaoMunicipal>\n";
        $RecepcionarLoteRps .= "            <QuantidadeRps>1</QuantidadeRps>\n";
        $RecepcionarLoteRps .= "            <ListaRps>\n";
        $Aliquota            = 5;
        $iss                 = $valor * $Aliquota / 100;
        $iss                 = number_format($iss, 2, ".", "");
        $aliq                = $Aliquota / 100;
        $ValorServicos       = number_format($valor, 2, ".", "");

        $ValorIss            = $iss;
        $ValorIssRetido      = $ValorIss;
        $BaseCalculo         = $ValorServicos;

        $ValorLiquidoNfse    = $ValorServicos;
        $Descricao_Serv      = utf8_encode("MENSALIDADE REFERENTE A VENCIMENTO : $venc - TITULO: $numdoc - Codigo: $logon");

        $RecepcionarLoteRps .= "                <Rps>\n";
        $RecepcionarLoteRps .= "                    <InfRps>\n";
        $RecepcionarLoteRps .= "                        <IdentificacaoRps>\n";
        if ( $cnpj_empresa_faturar == '08745918000171' ){
            $WC_NumeroRPS++;
            $RecepcionarLoteRps .= "                            <Numero>$WC_NumeroRPS</Numero>\n";
        }else{
            $WorldClick_NumeroRPS++;
            $RecepcionarLoteRps .= "                            <Numero>$WorldClick_NumeroRPS</Numero>\n";
        }

        $RecepcionarLoteRps .= "                            <Serie>RPS</Serie>\n";
        $RecepcionarLoteRps .= "                            <Tipo>1</Tipo>\n";
        $RecepcionarLoteRps .= "                        </IdentificacaoRps>\n";
        $RecepcionarLoteRps .= "                        <DataEmissao>".$DataEmissao."</DataEmissao>\n";
        $RecepcionarLoteRps .= "                        <NaturezaOperacao>1</NaturezaOperacao>\n";
        $RecepcionarLoteRps .= "                        <RegimeEspecialTributacao>1</RegimeEspecialTributacao>\n";
        $RecepcionarLoteRps .= "                        <OptanteSimplesNacional>1</OptanteSimplesNacional>\n";
        $RecepcionarLoteRps .= "                        <IncentivadorCultural>2</IncentivadorCultural>\n";
        $RecepcionarLoteRps .= "                        <Status>1</Status>\n";
        $RecepcionarLoteRps .= "                        <Servico>\n";
        $RecepcionarLoteRps .= "                            <Valores>\n";
        $RecepcionarLoteRps .= "                                <ValorServicos>".$ValorServicos."</ValorServicos>\n";
        $RecepcionarLoteRps .= "                                <IssRetido>2</IssRetido>\n";
        $RecepcionarLoteRps .= "                                <ValorIss>$iss</ValorIss>\n";
        $RecepcionarLoteRps .= "                                <ValorIssRetido>0.00</ValorIssRetido>\n";
        $RecepcionarLoteRps .= "                                <BaseCalculo>".$BaseCalculo."</BaseCalculo>\n";
        $RecepcionarLoteRps .= "                                <Aliquota>".($Aliquota/100)."</Aliquota>\n";
        $RecepcionarLoteRps .= "                                <ValorLiquidoNfse>".$ValorServicos."</ValorLiquidoNfse>\n";
        $RecepcionarLoteRps .= "                                <DescontoIncondicionado>0</DescontoIncondicionado>\n";
        $RecepcionarLoteRps .= "                                <DescontoCondicionado>0</DescontoCondicionado>\n";
        $RecepcionarLoteRps .= "                            </Valores>\n";

        if ( $cnpj_empresa_faturar == '08745918000171' )
            $RecepcionarLoteRps .= "                            <ItemListaServico>1722</ItemListaServico>\n";
        else
            $RecepcionarLoteRps .= "                            <ItemListaServico>1505</ItemListaServico>\n";

        $RecepcionarLoteRps .= "                            <Discriminacao>".$Descricao_Serv."</Discriminacao>\n";
        $RecepcionarLoteRps .= "                            <CodigoMunicipio>4106902</CodigoMunicipio>\n";
        $RecepcionarLoteRps .= "                        </Servico>\n";

        $RecepcionarLoteRps .= "                        <Prestador><Cnpj>$cnpj_empresa_faturar</Cnpj><InscricaoMunicipal>".$Certificado_IM."</InscricaoMunicipal></Prestador>\n";

        $RecepcionarLoteRps .= "                        <Tomador>\n";
        $RecepcionarLoteRps .= "                            <IdentificacaoTomador>\n";
        
        $TagCNPJCPF = $reg['insc'];
        if ( strlen($TagCNPJCPF) <= 11 ){
            $TagCNPJCPF = str_pad($TagCNPJCPF, 11, '0', STR_PAD_LEFT);
            $TIPO_DOC = "<Cpf>$TagCNPJCPF</Cpf>";
        }else{
            $TagCNPJCPF = str_pad($TagCNPJCPF, 14, '0', STR_PAD_LEFT);
            $TIPO_DOC = "<Cnpj>$TagCNPJCPF</Cnpj>";
        }
        
        $RecepcionarLoteRps .= "                                <CpfCnpj>$TIPO_DOC</CpfCnpj>\n";
        $RecepcionarLoteRps .= "                            </IdentificacaoTomador>\n";
        $RecepcionarLoteRps .= "                            <RazaoSocial><![CDATA[".$RazaoSocial."]]></RazaoSocial>\n";
        $RecepcionarLoteRps .= "                            <Endereco>\n";
        $RecepcionarLoteRps .= "                                <Endereco><![CDATA[".$Endereco."]]></Endereco>\n";
        $RecepcionarLoteRps .= "                                <Numero>".$Numero."</Numero>\n";
        $RecepcionarLoteRps .= "                                <Bairro><![CDATA[".$Bairro."]]></Bairro>\n";
        $RecepcionarLoteRps .= "                                <CodigoMunicipio>".$Cidade."</CodigoMunicipio>\n";
        $RecepcionarLoteRps .= "                                <Uf>".$Estado."</Uf>\n";
        $RecepcionarLoteRps .= "                                <Cep>".$CEP."</Cep>\n";
        $RecepcionarLoteRps .= "                            </Endereco>\n";
        $RecepcionarLoteRps .= "                            <Contato><Telefone>".$Telefone."</Telefone><Email><![CDATA[".$Email."]]></Email></Contato>\n";
        $RecepcionarLoteRps .= "                        </Tomador>\n";
        $RecepcionarLoteRps .= "                    </InfRps>\n";
        $RecepcionarLoteRps .= "                </Rps>\n";    
        $RecepcionarLoteRps .= "            </ListaRps>\n";
        $RecepcionarLoteRps .= "        </LoteRps>\n";
        $RecepcionarLoteRps .= "    </EnviarLoteRpsEnvio>\n";
        $RecepcionarLoteRps = str_replace( array("\r\n", "\n", "\r","  "), '', $RecepcionarLoteRps );

        $arquivo = $numdoc.'_'.date('His').'xml';
        
        $cria_arq = fopen($arquivo,"w");
        $inserindo = fputs($cria_arq,$RecepcionarLoteRps);
        
        $xml_enc = $RecepcionarLoteRps;
        
//        echo "<pre>";
//        print_r( $xml_enc );
//        die;
        
        $oRps = new RPS( );
        $oRps->CnpjEmpresa = $Certificado_Cnpj;
        $oRps->aplicativo = 'producao';
        $oRps->NumeroPedido = $numdoc;
        $oRps->Certificado_Cnpj = $cpfcnpj;
        $oRps->Certificado_InscMunicipal = $Certificado_IM;
        $oRps->Certificado_Usuario = $Certificado_User;
        $oRps->Certificado_Senha = $Certificado_Senha;

        $oRps->URLwebservice = 
                        array('producao' => 'https://isscuritiba.curitiba.pr.gov.br/Iss.NfseWebService/nfsews.asmx',
                              'homologacao' => 'http://pilotoisscuritiba.curitiba.pr.gov.br/nfse_ws/nfsews.asmx'
                             );

        // Assinando o XML
        $oRps->assinar($xml_enc);

        // Validando o XML assinado
        $oRps->ValidarXml();

        // Enviando para a prefeitura
        $conteudo = $oRps->enviar();
        
        if ( $cnpj_empresa_faturar == '08745918000171' ){ // WC
            $sql_UPDATE = "UPDATE cs2.nota_controle
                           SET
                              num_lote_WC = $WC_NumeroLote,
                              num_rps_WC = $WC_NumeroRPS";
        }else{ // WorldClick
            $sql_UPDATE = "UPDATE cs2.nota_controle
                           SET
                              numero_lote = $WorldClick_NumeroLote,
                              numero_rps = $WorldClick_NumeroRPS";
        }
        $qr_UPDATE = mysql_query($sql_UPDATE, $con) or die("Erro SQL: $sql_UPDATE");

        // Verificando se recebeu e retornou o protocolo, se recebeu grava na tabela
        $xml = simplexml_load_string( $conteudo );
                                
        $qtd_erro = count( $xml -> ListaMensagemRetorno -> MensagemRetorno );

        if ( $qtd_erro > 0 ){ // APRESENTOU ERRO

            for ( $i = 1 ; $i <= $qtd_erro ; $i++ ){
                $erro_codigo   = $xml -> ListaMensagemRetorno -> MensagemRetorno -> Codigo;
                $erro_mensagem = $xml -> ListaMensagemRetorno -> MensagemRetorno -> Mensagem;
                $erro_correcao = $xml -> ListaMensagemRetorno -> MensagemRetorno -> Correcao;                    
                $retorno_webcontrol .= "900;Erro: $erro_mensagem<br>Correcao: $erro_correcao<hr><br>";
            }
            echo $retorno_webcontrol;
            exit;

        }else{

            // Passou da primeira fase
            $Protocolo = $xml -> Protocolo;
            $DataRecebimento = $xml -> DataRecebimento;
            
            $sql_INSERT = "INSERT INTO cs2.titulos_notafiscal(numdoc,protocolo,xml,status,data_emissao)
                           VALUES('$numdoc','$Protocolo','$xml','1','$DataRecebimento')";
            $qr_INSERT = mysql_query($sql_INSERT, $con) or die("Erro SQL: $sql_INSERT");
            
            echo "<script>alert('Sua nota está em Processamento junto a Prefeitura. Clique no link abaixo para verificar sua liberação')</script>";
            echo "<table align='center' width='100%'>
                     <tr>
                        <td>
                          <br><br><br>
                          <a href='https://webcontrolempresas.com.br/franquias/php/painel.php?pagina1=Franquias/b_notafiscal_gerar.php&numdoc=$numdoc&id_faturamento=$referencia'>Consultar Nota Fiscal</a>
                    .   </td>
                     </tr>
                  </table>";
            die;

        }
        
    }
    else
    {
        
        // consultando a situacao do lote

        $oRps = new RPS( );
        $oRps->CnpjEmpresa = $Certificado_Cnpj;
        $oRps->aplicativo = 'producao';
        $oRps->NumeroPedido = $numdoc;
        $oRps->rpsPROTOCOLO = $protocolo;
        $oRps->Certificado_Cnpj = $cpfcnpj;
        $oRps->Certificado_InscMunicipal = $Certificado_IM;
        $oRps->Certificado_Usuario = $Certificado_User;
        $oRps->Certificado_Senha = $Certificado_Senha;
        
        $oRps->URLArqxsd = $urlArqXsd;

        $oRps->URLwebservice = 
                        array('producao' => 'https://isscuritiba.curitiba.pr.gov.br/Iss.NfseWebService/nfsews.asmx',
                              'homologacao' => 'http://pilotoisscuritiba.curitiba.pr.gov.br/nfse_ws/nfsews.asmx'
                             );

        $result = $oRps->ConsultarSituacaoLoteRps();
        
//        echo "<pre>";
//        print_r( $result );
//        die;
        
        $xml = simplexml_load_string( $result );
        
        $qtd_erro = count( $xml -> ListaMensagemRetorno -> MensagemRetorno );

        if ( $qtd_erro > 0 ){ // APRESENTOU ERRO

            for ( $i = 1 ; $i <= $qtd_erro ; $i++ ){
                $erro_codigo   = $xml -> ListaMensagemRetorno -> MensagemRetorno -> Codigo;
                $erro_mensagem = $xml -> ListaMensagemRetorno -> MensagemRetorno -> Mensagem;
                $erro_correcao = $xml -> ListaMensagemRetorno -> MensagemRetorno -> Correcao;                    
                $retorno_webcontrol .= "900;Erro: $erro_mensagem<br>Correcao: $erro_correcao<hr><br>";
            }
            echo $retorno_webcontrol;
            die;

        }
        else{

            $Situacao = $xml -> Situacao;
            
            Switch ( $Situacao ){

                case '1':  // 1 - Lote Recebido

                    switch ( $ufPrestador ){

                        case 'PR':

                            if ( $cidadePrestador == 'CURITIBA'){

                                $objNotaFiscalVenda = new NotaFiscalVendaController();
                                $objNotaFiscalVenda->setIdVenda($numeroPedido);
                                $objNotaFiscalVenda->setSituacao('1'); // 1 - Lote Recebido - Solicitado Nota
                                $objNotaFiscalVenda->setTipo_nota('NFS');
                                $objNotaFiscalVenda->setNumero_nota('0');
                                $arrResult = $objNotaFiscalVenda->atualizaNotasEletronicasVendas();

                                $objNotaFiscalServico -> setIdVenda($numeroPedido);
                                $objNotaFiscalServico -> setNumeroProtocolo($numeroProtocolo);
                                $objNotaFiscalServico -> setSituacao($Situacao);
                                $arrResult = $objNotaFiscalServico -> Incluir_Atualizar_Situacao_NFSE();
                                echo "900;Lote Recebido. Porem ainda nao processado pela Prefeitura. Feche a Janela e Clique novamente para gerar a Nota.";
                            }
                            break;
                    }
                    break;

                case '2':  // 2 - Lote em Processamento

                    echo "<script>alert('Nota em Processamento junto a Prefeitura. Clique no link abaixo para verificar sua liberação')</script>";
                    echo "<table align='center' width='100%'><tr><td><br><br><br><a href='https://webcontrolempresas.com.br/franquias/php/painel.php?pagina1=Franquias/b_notafiscal_gerar.php&numdoc=$numdoc&id_faturamento=$referencia'>Consultar Nota Fiscal</a></td></tr></table>";
                    die;

                case '3': // 3 - Lote Processado com ERROS - Solicitando a PREFEITURA quais foram os erros

                    // Atualiza o Status para 8
                    
                    $sql_UPDATE = "UPDATE cs2.titulos_notafiscal SET status = '8' WHERE numdoc = '$numdoc'";
                    $qr_UPDATE = mysql_query($sql_UPDATE, $con) or die("Erro SQL: $sql_UPDATE");
                    
                    $conteudo = $oRps->ConsultarLoteRps();
                    
                    $xml = simplexml_load_string( $conteudo, null, LIBXML_NOCDATA );

                    $qtd_erro = count( $xml -> ListaMensagemRetorno -> MensagemRetorno );
                    if ( $qtd_erro > 0 ){
                        // APRESENTOU ERRO
                        $retorno_webcontrol .= "900;";
                        for ( $i = 1 ; $i <= $qtd_erro ; $i++ ){
                            $erro_codigo = $xml -> ListaMensagemRetorno -> MensagemRetorno -> Codigo;
                            $erro_mensagem = $xml -> ListaMensagemRetorno -> MensagemRetorno -> Mensagem;
                            $erro_correcao = $xml -> ListaMensagemRetorno -> MensagemRetorno -> Correcao;                    
                            $retorno_webcontrol .= "Erro: $erro_mensagem<br>Correcao: $erro_correcao<hr>";
                        }
                        echo $retorno_webcontrol;
                        die;
                    }
                    
                    // Deu erro e nao veio no formato correto, mostrando o erro que veio
                    echo "<pre>";
                    print_r( $conteudo);
                    die;
                    
                    break;

                case '4': // 4 - NOTA GERADA COM SUCESSO OK
                    
                    $conteudo = $oRps->ConsultarLoteRps();
                    
                    $xml = simplexml_load_string( $conteudo, null, LIBXML_NOCDATA );
                    
                    $data_cancelamento = $xml -> ConsultarLoteRpsResult -> ListaNfse -> CompNfse -> tcCompNfse -> NfseCancelamento->Confirmacao->DataHoraCancelamento;
                    $numero_nota       = $xml -> ConsultarLoteRpsResult -> ListaNfse -> CompNfse -> tcCompNfse -> Nfse -> InfNfse -> Numero;
                    $data_emissao      = $xml -> ConsultarLoteRpsResult -> ListaNfse -> CompNfse -> tcCompNfse -> Nfse -> InfNfse -> DataEmissao;

                    // Salvando o XML na pasta  nfse\rps\producao\xml
                    $fp = fopen("/var/www/html/franquias/php/Franquias/notafiscal/".$idCadastro."_".$numeroPedido.".xml", "w");
                    $escreve = fwrite($fp, $conteudo);
                    fclose($fp);

                    // Lendo o arquivo e gravando no formato que precisa para ler a nota

                    $docxml = file_get_contents("/var/www/html/franquias/php/Franquias/notafiscal/".$idCadastro."_".$numeroPedido.".xml");
                    $dom = new DOMDocument('1.0', 'UTF-8');
                    $dom->preservWhiteSpace = false; // elimina espacos em branco
                    $dom->formatOutput = false; // ignora formatacao
                    $dom->loadXML( $docxml , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );

                    $ConsultarLoteRpsResposta = $dom->getElementsByTagName ( "ConsultarLoteRpsResposta" )->item ( 0 );
                    $CompNfse = $ConsultarLoteRpsResposta->getElementsByTagName ( "CompNfse" )->item ( 0 );

                    foreach ( $CompNfse->getElementsByTagName ( "tcCompNfse" ) as $Rps ){
                        
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
                <LoteRps id='UNICO'>
                    <NumeroLote>UNICO</NumeroLote>
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
            </EnviarLoteRpsEnvio>";
                        
                        $xml = str_replace("'",'"',$xml);
                        $data_emissao = str_replace("T",' ',$data_emissao);
                        $sql_UPDATE = "UPDATE cs2.titulos_notafiscal
                                       SET xml = '$xml', 
                                           status = '5', 
                                           data_emissao = '$data_emissao', 
                                           numero_nota = '$Numero', 
                                           codigo_verificacao = '$CodigoVerificacao'
                                        WHERE numdoc = '$numdoc'";
                        $qr_UPDATE = mysql_query($sql_UPDATE, $con) or die("Erro SQL: $sql_UPDATE");

                    } // Foreach
        
                    // CHAMANDO O LINK PARA VER A NOTA
                    
                    echo "<script>alert(\"Nota Fiscal processada com sucesso!\");</script>";
                    if ( $referencia != '' )
                        echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=Franquias/b_notafiscal_new.php&go=ingressar&id_faturamento=$referencia&cliente=$logon\";>";
                    
                    break;

                case 'ca':
                    /*
                    $objNotaFiscalVenda = new NotaFiscalVendaController();
                    $objNotaFiscalVenda->setIdVenda($numeroPedido);
                    $objNotaFiscalVenda->setSituacao('3'); // 2 - Lote Recebido - Nota em andamento
                    $objNotaFiscalVenda->setTipo_nota('NFS');
                    $objNotaFiscalVenda->setLinkNfs($Link_Nota);
                    $objNotaFiscalVenda->setNumero_nota($Numero_Nota);

                    $arrResult = $objNotaFiscalVenda->atualizaNotasEletronicasVendas();

                    echo "999;PEDIDO: $numeroPedido - NOTA DE SERVICO CANCELADA NA PREFEITURA.<br><br>
                          Clique no link abaixo para ver a Nota.<br><br>
                          <a href='$Link_Nota' target='_blank' >Nota Fiscal</a>
                          ";
                    */
                    break;

            }
        }

    }
}

// Tratando o retorno da prefeitura
echo "<pre>";
print_r( $result );


?>
