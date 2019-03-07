<?php

    /**
     * @author Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
     * @version 2015083101
     * @copyright
     * @package nfse
     * @name index .php
     */

function seo($value) { 
	$trocaeste=array( "(", ")","'","Ö","Ç","Ü","Ú","Ó","Ô","Õ","Ò","Ã","Â","Á","À","É","Í",";"); 
	$poreste=array( " ", " "," ","O","C","U","U","O","O","O","O","A","A","A","A","E","I"," "); 
	$value=str_replace($trocaeste,$poreste,$value); 
	$value = preg_replace("@[^A-Za-z0-9<> /,.\-_]+@i","",$value); 
	return $value; 
}

    function RecepcionarLoteRps(
        $modelo,
        $cnpj,
        $arquivo,
        $sigla,
        $urlArqXsd,
        $urlProducao,
        $urlHomologacao,
        $cidadePrestador,
        $ufPrestador,
        $assinarNFS
    ){
	try
	{
            
            if ( $modelo == 'P' ) $modelo = 'producao';
            else $modelo = 'homologacao';
           
            if( !in_array($modelo,array('producao','homologacao'))):
                    throw new Exception('901;informe producao ou homologacao, parametro invalido');
            endif;

            if( !Validacoes::isValidCNPJ($cnpj)):
                    throw new Exception(Validacoes::$errorMessage);
            endif;

            if(!DB::connect()):
                    throw new Exception(DB::$error);
            endif;

            DB::connect()->beginTransaction();

            $codigo = '1';
            
            $oRps = new RPS( );
            $oRps->aplicativo = $modelo;
            $oRps->CodigoEmpresa = $codigo;
            $oRps->CnpjEmpresa = $cnpj;
            $oRps->Sigla = $sigla;
            $oRps->NumeroPedido = $numero_pedido;
            $oRps->URLArqxsd = $urlArqXsd;
            
            $oRps->URLwebservice = 
                array('producao' => $urlProducao,
                      'homologacao' => $urlHomologacao
                     );
            
            // Assinando o XML do pedido

            $oRps->assinar($arquivo);
            
            echo "yyyyy";
            die;
            
            if ( $assinarNFS == 'S') 
                $oRps->ValidarXml();

            $result = $oRps->enviar();
            
            DB::connect()->commit();

            return $result;

	} catch (Exception $ae)
	{
            if( DB::connect()->inTransaction() ):
                    DB::connect()->rollBack();
            endif;
            return '999;'.$ae->getMessage();

	}
	
    }

    
    
    define('_NFSE', true);

    $numero_pedido   = $_REQUEST['numdoc'];
    $numero_pedido   = str_replace(',',"','",$numero_pedido);
    
    $ambiente        = 'P';
    $cnpj            = '13117948000173';
    $sigla           = '06902' ;
    $urlArqXsd       = 'http://isscuritiba.curitiba.pr.gov.br/iss/nfse.xsd';
    $urlProducao     = 'https://isscuritiba.curitiba.pr.gov.br/Iss.NfseWebService/nfsews.asmx';
    $urlHomologacao  = 'http://pilotoisscuritiba.curitiba.pr.gov.br/nfse_ws/nfsews.asmx';
    $cidadePrestador = 'CURITIBA';
    $ufPrestador     = 'PR';
    $assinarNFS      = 'S';

    require('includes/application.php');

    // PADRAO: ABRASF COM ASSINATURA
    require( PATH_CLASSES . 'rps_curitiba_pr.class.php' );
    
    // Gera o XML das notas solicitadas
    $Serie        = 'RPS';
    # Buscando NUMERO DO LOTE e NUMERO DO RPS
    $sql = "SELECT numero_lote, numero_rps FROM cs2.nota_controle";
    try
    {
        $stmt = DB::connect()->Prepare ( $sql );
        $stmt->Execute ();
        $rs = $stmt->fetchAll(PDO::FETCH_OBJ);
        $stmt->closeCursor();
        unset($stmt);
    } catch ( PDOException $PDOExceptione ) {
        throw new Exception ( $PDOExceptione->getMessage() );
    } catch ( Exception $ae ) {
        throw new Exception ( $ae->getMessage() );
    }

    if( !empty($rs) ):
        $NumeroLote = $rs[0]->numero_lote;
        $NumeroRPS = $rs[0]->numero_rps;
    endif;

    $NumeroRPS_Inicio = $NumeroRPS + 1;
    $NumeroLote++;

    $CNPJEmissor = '13117948000173';
    $IMEmissor   = '010106049109';
    $DataEmissao = date('Y-m-d').'T'.date('H:i:s');
    $idNFe       = date('YmdHi');

    # VERIFICANDO OS CLIENTES QUE PEDIRAM NOTA FISCAL
    $sql = "SELECT 
                a.codloja, a.razaosoc, a.end, a.numero, a.bairro, a.cidade, a.uf, a.cep, a.fone, a.email,
                b.vencimento, b.valor, b.numdoc, a.insc,
                date_format(b.vencimento,'%d/%m/%Y') as venc
            FROM cs2.cadastro a
            LEFT OUTER JOIN cs2.titulos b ON a.codloja = b.codloja
            WHERE
                a.emitir_nfs = 'S' AND a.sitcli < 2 AND  numdoc IN ('$numero_pedido' )
            ORDER BY a.razaosoc";
    try
    {

        $stmt = DB::connect()->Prepare ( $sql );
        $stmt->Execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if( !empty($rs) ){
            
            $RecepcionarLoteRps  = '    <EnviarLoteRpsEnvio xmlns="http://isscuritiba.curitiba.pr.gov.br/iss/nfse.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://isscuritiba.curitiba.pr.gov.br/iss/nfse.xsd">';
            $RecepcionarLoteRps .= "        <LoteRps id=\"$idNFe\">\n";
            $RecepcionarLoteRps .= "            <NumeroLote>".$NumeroLote."</NumeroLote>\n";
            $RecepcionarLoteRps .= "            <Cnpj>".$CNPJEmissor."</Cnpj>\n";
            $RecepcionarLoteRps .= "            <InscricaoMunicipal>".$IMEmissor."</InscricaoMunicipal>\n";
            $RecepcionarLoteRps .= "            <QuantidadeRps>$qtd_nota</QuantidadeRps>\n";
            $RecepcionarLoteRps .= "            <ListaRps>\n";

            foreach ($rs as $index => $reg) {
                
                $NumeroRPS++;
                $TagCNPJCPF = $reg['insc'];
                if ( strlen($TagCNPJCPF) <= 11 ){
                    $TagCNPJCPF = str_pad($TagCNPJCPF, 11, '0', STR_PAD_LEFT);
                    $TIPO_DOC = "<Cpf>$TagCNPJCPF</Cpf>";
                }else{
                    $TagCNPJCPF = str_pad($TagCNPJCPF, 14, '0', STR_PAD_LEFT);
                    $TIPO_DOC = "<Cnpj>$TagCNPJCPF</Cnpj>";
                }
                $codloja = $reg['codloja'];

                $sql_logon = "SELECT login from base_web_control.webc_usuario WHERE id_cadastro = $codloja LIMIT 1";
                try
                {
                    $stmt = DB::connect()->Prepare ( $sql_logon );
                    $stmt->Execute ();
                    $rs2 = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $stmt->closeCursor();
                    unset($stmt);
                } catch ( PDOException $PDOExceptione ) {
                    throw new Exception ( $PDOExceptione->getMessage() );
                } catch ( Exception $ae ) {
                    throw new Exception ( $ae->getMessage() );
                }

                if( !empty($rs2) ):
                    $logon = $rs2[0]->login;
                endif;
    
                $RazaoSocial = trim(utf8_encode(seo($reg['razaosoc'])));
                $Endereco    = trim(utf8_encode(seo($reg['end'])));
                $Numero      = $reg['numero'];
                $Complemento = utf8_encode('');
                $Bairro      = trim(utf8_encode(seo($reg['bairro'])));
                $nCidade     = trim(utf8_encode(seo($reg['cidade'])));
                $Estado      = trim(utf8_encode(seo($reg['uf'])));
                $CEP         = trim(utf8_encode($reg['cep']));
                $Telefone    = trim(utf8_encode($reg['fone']));
                $Email       = $reg['email'];
                $vencimento  = $reg['vencimento'];
                $venc        = $reg['venc'];
                $valor       = $reg['valor'];
                $numdoc      = $reg['numdoc'];

                #buscando codigo da cidade
                $sql_cidade = " SELECT CONCAT(a.id_estado,a.sigla) AS id_cidade
                                FROM base_web_control.nfe_municipio a
                                INNER JOIN base_web_control.nfe_uf b ON a.id_estado = b.id
                                WHERE a.descricao = '$nCidade' AND b.sigla = '$Estado'";
                try
                {
                    $stmt = DB::connect()->Prepare ( $sql_cidade );
                    $stmt->Execute ();
                    $rs2 = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $stmt->closeCursor();
                    unset($stmt);
                } catch ( PDOException $PDOExceptione ) {
                    throw new Exception ( $PDOExceptione->getMessage() );
                } catch ( Exception $ae ) {
                    throw new Exception ( $ae->getMessage() );
                }

                if( !empty($rs2) ):
                    $Cidade = $rs2[0]->id_cidade;
                endif;
                
                if ( empty($Cidade) ){
                        echo "CIDADE INVALIDA --> ID: $codloja";
                        exit;
                }
                $Aliquota        = 2;
                $iss = $valor * $Aliquota / 100;
                $iss = number_format($iss, 2, ".", "");
                $aliq = $Aliquota / 100;
                $ValorServicos = number_format($valor, 2, ".", "");

                $ValorIss         = $iss;
                $ValorIssRetido   = $ValorIss;
                $BaseCalculo      = $ValorServicos;
                $ValorLiquidoNfse = $ValorServicos;
                $Descricao_Serv   = utf8_encode("MENSALIDADE REFERENTE A VENCIMENTO : $venc - TITULO: $numdoc - Codigo: $logon");
                $RecepcionarLoteRps .= "                <Rps>\n";
                $RecepcionarLoteRps .= "                    <InfRps>\n";
                $RecepcionarLoteRps .= "                        <IdentificacaoRps>\n";
                $RecepcionarLoteRps .= "                            <Numero>".$NumeroRPS."</Numero>\n";
                $RecepcionarLoteRps .= "                            <Serie>".$Serie."</Serie>\n";
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
                $RecepcionarLoteRps .= "                                <ValorIss>0.00</ValorIss>\n";
                $RecepcionarLoteRps .= "                                <ValorIssRetido>0.00</ValorIssRetido>\n";
                $RecepcionarLoteRps .= "                                <BaseCalculo>".$BaseCalculo."</BaseCalculo>\n";
                $RecepcionarLoteRps .= "                                <Aliquota>0.00</Aliquota>\n";
                $RecepcionarLoteRps .= "                                <ValorLiquidoNfse>".$ValorLiquidoNfse."</ValorLiquidoNfse>\n";
                $RecepcionarLoteRps .= "                                <DescontoIncondicionado>0</DescontoIncondicionado>\n";
                $RecepcionarLoteRps .= "                                <DescontoCondicionado>0</DescontoCondicionado>\n";
                $RecepcionarLoteRps .= "                            </Valores>\n";
                $RecepcionarLoteRps .= "                            <ItemListaServico>1505</ItemListaServico>\n";
                $RecepcionarLoteRps .= "                            <Discriminacao>".$Descricao_Serv."</Discriminacao>\n";
                $RecepcionarLoteRps .= "                            <CodigoMunicipio>4106902</CodigoMunicipio>\n";
                $RecepcionarLoteRps .= "                        </Servico>\n";
                $RecepcionarLoteRps .= "                        </Servico>\n";
                $RecepcionarLoteRps .= "                        <Prestador><Cnpj>$CNPJEmissor</Cnpj><InscricaoMunicipal>".$IMEmissor."</InscricaoMunicipal></Prestador>\n";
                $RecepcionarLoteRps .= "                        <Tomador>\n";
                $RecepcionarLoteRps .= "                            <IdentificacaoTomador>\n";
                $RecepcionarLoteRps .= "                                <CpfCnpj>$TIPO_DOC</CpfCnpj>\n";
                $RecepcionarLoteRps .= "                            </IdentificacaoTomador>\n";
                $RecepcionarLoteRps .= "                            <RazaoSocial>".$RazaoSocial."</RazaoSocial>\n";
                $RecepcionarLoteRps .= "                            <Endereco>\n";
                $RecepcionarLoteRps .= "                                <Endereco>".$Endereco."</Endereco>\n";
                $RecepcionarLoteRps .= "                                <Numero>".$Numero."</Numero>\n";
                $RecepcionarLoteRps .= "                                <Bairro>".$Bairro."</Bairro>\n";
                $RecepcionarLoteRps .= "                                <CodigoMunicipio>".$Cidade."</CodigoMunicipio>\n";
                $RecepcionarLoteRps .= "                                <Uf>".$Estado."</Uf>\n";
                $RecepcionarLoteRps .= "                                <Cep>".$CEP."</Cep>\n";
                $RecepcionarLoteRps .= "                            </Endereco>\n";
                $RecepcionarLoteRps .= "                            <Contato><Telefone>".$Telefone."</Telefone><Email>".$Email."</Email></Contato>\n";
                $RecepcionarLoteRps .= "                        </Tomador>\n";
                $RecepcionarLoteRps .= "                    </InfRps>\n";
                $RecepcionarLoteRps .= "                </Rps>\n";
            }
            $RecepcionarLoteRps .= "            </ListaRps>\n";
            $RecepcionarLoteRps .= "        </LoteRps>\n";
            $RecepcionarLoteRps .= "    </EnviarLoteRpsEnvio>\n";
            
        }

       // $stmt->closeCursor();
      //  unset($stmt);
    } catch ( PDOException $PDOExceptione ) {
            throw new Exception ( $PDOExceptione->getMessage() );
    } catch ( Exception $ae ) {
            throw new Exception ( $ae->getMessage() );
    }

    $xml_enc = $RecepcionarLoteRps;

    print_r(
        RecepcionarLoteRps(
            $ambiente,
            $cnpj,
            $xml_enc,
            $sigla,
            $urlArqXsd,
            $urlProducao,
            $urlHomologacao,
            $cidadePrestador,
            $ufPrestador,
            $assinarNFS
        )
    );
    exit();