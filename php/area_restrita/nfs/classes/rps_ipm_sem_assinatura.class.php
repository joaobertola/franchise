<?php
/**
 * @author Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
 * @version 2015083101
 * @copyright 
 * @package nfse
 * @name rps_colombo.class.php
 * COLOMBO / PR
 */

 if(!defined('_NFSE')):
	die("Hacking detectado, seu ip ".$_SERVER['REMOTE_ADDR']." foi logado !");
endif;

if(!defined('WSDL_CACHE_NONE')):
	define('WSDL_CACHE_NONE', 0);
endif;

if(!defined('SOAP_1_2')):
	define('SOAP_1_2', 2);
endif;

if(!defined('SOAP_1_1')):
	define('SOAP_1_1', 1);
endif;


class RPS
{
    # config empresa
    public $aplicativo;
    public $CodigoEmpresa;
    public $InscricaoMunicipalEmpresa;
    public $InscricaoEstadualEmpresa;
    public $CnpjEmpresa;
	public $sigla;
    public $DataEmissao;
    public $RazaoSocialEmpresa;
    public $NomeFantasiaEmpresa;
    public $AliquotaISSEmpresa;
	
    private $fk_cliente;
    private $cliente_razao_social;
    private $cliente_cpf_cnpj;

    public $numero;
    public $serie;
    public $CodigoVerificacao;
    public $arquivo;
    public $arquivoXMLRPS;

    public $mensageValidacaoXMLRPS;
    public $mensageAssinaturaXMLRPS;
    public $mensageCodigoXMLRPS;
    public $mensageRetornoXMLRPS;
    public $mensageCorrecaoXMLRPS;
    public $mensageNumeroLoteXMLRPS;
    public $mensageDataRecebimentoXMLRPS;

    public $NumeroLoteRPS;
    public $QtdeRPSGeradas;
    public $QtdeRPS;

    public $rpsID_NUM_RPS;
    public $rpsPROTOCOLO;
    public $rpsQTDE;
    public $rpsXML;
    public $rpsARQUIVO;
    public $rpsSITUACAO;	

    public $detalhe;
    public $download;
    public $reenviar;
    public $regerar = null;

    public $loteCodigoFatura;
    public $loteIdFatura;
    public $loteNumNfse;
    public $loteIdCliente;
    public $loteCpfCnpj;
    public $loteNumeroSerie;
    public $loteCodigoVerificacao;
    public $loteNumeroNfse;
    public $loteMesAno;
    public $loteNomeArqNfse;
    public $loteValorNfse;
    public $loteValorLiquido;
    public $loteValorIss;
    public $loteRazaoSocial;
	
    # Meses que faltam para o certificado expirar
    public $certMonthsToExpire=0;
    # Dias que faltam para o certificado expirar
    public $certDaysToExpire=0;
    # nome do certificado
    public $certName;
    
    private $priKEY;
    private $pubKEY;
    private $certKEY;
    
    private $connectionSoap;
    private $soapDebug;
    
    #public $certName = 'certificado-tige-seg-nfse.pfx'; 
    # chave de acesso ao certificado
    private $keyPass;

    private $URLxmlns='http://www.abrasf.org.br/nfse.xsd';

    private $URLxmlnsCall ="http://iss.londrina.pr.gov.br/ws/v1_03";

    private $URLsoap = "http://www.w3.org/2003/05/soap-envelope";

    # Instancia do WebService
    private $URLxsi='http://www.w3.org/2001/XMLSchema-instance';
    # Instancia do WebService
    private $URLxsd='http://www.w3.org/2001/XMLSchema';
    # Instancia do WebService
    private $URLnfe='http://www.portalfiscal.inf.br/nfe';
    # Instancia do WebService
    private $URLdsig='http://www.w3.org/2000/09/xmldsig#';
    # Instancia do WebService
    private $URLCanonMeth='http://www.w3.org/TR/2001/REC-xml-c14n-20010315#WithComments';
    # Instancia do WebService
    private $URLSigMeth='http://www.w3.org/2000/09/xmldsig#rsa-sha1';
    # Instancia do WebService
    private $URLTransfMeth_1='http://www.w3.org/2000/09/xmldsig#enveloped-signature';
    # Instancia do WebService
    private $URLTransfMeth_2='http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    # Instancia do WebService
    private $URLDigestMeth='http://www.w3.org/2000/09/xmldsig#sha1';
   	# Instancia do WebService

    #private $URLPortal='http://www.portalfiscal.inf.br/nfe';
    
    // public $URLxsdCuritiba = 'http://iss.londrina.pr.gov.br/iss/nfse.xsd';
    
    private $Host = array('producao' => 'https://sync.nfs.e.net/datacenter/include/nfw/importa_nfw/importa_nfw_upload.php?eletron=1',
                          'homologacao' => 'https://sync.nfs.e.net/datacenter/include/nfw/importa_nfw/importa_nfw_upload.php?eletron=1');
    
    // public $URLwebservice = array('producao' => 'https://iss.londrina.pr.gov.br/ws/v1_03/sigiss_ws.php?wsdl',
    //                              'homologacao' => 'http://testeiss.londrina.pr.gov.br/ws/v1_03/sigiss_ws.php?wsdl');
    
    private $SubjectName = 'E=nfse@londrina.pr.gov.br,  CN=Equipe  de  Desenvolvimento NFS-e,,  O=Secretaria  Municipal  da  Fazenda  (Sefaz),  L=Curitiba,  S=Parana, C=BR';

    
    public function __construct()
    {

    }
		
    private function __loadDadosEmpresa()
    {
        if( empty($this->CodigoEmpresa) ):
            throw new Exception( __LINE__ . " - Codigo do Empresa nao informado !");
        endif;

        if( empty($this->CnpjEmpresa) ):
            throw new Exception( __LINE__ . " - CNPJ do Empresa nao informado !");
        endif;

        $sql = <<<SQLQUERY
        SELECT
                c.inscricao_municipal as inscr_municipal,
                c.inscricao_estadual as inscr_estadual,
                c.razaosoc as razao_social,
                c.nomefantasia as nome_fantasia,
				ci.issqn_senha_cmc_cpf as issqn_senha_cmc_cpf,
				ci.issqn_id_municipio,
                ci.issqn_percentual_aliquota as aliquota_iss,
                ci.senha_certificado as keypass,
				ci.issqn_cpf as issqn_cpf,
				ci.issqn_senha_cmc_cpf as issqn_senha_cmc_cpf,
				rf.codigo as issqn_id_municipio_rf
				
        FROM cs2.cadastro c
        LEFT JOIN base_web_control.cadastro_imposto_padrao ci   ON c.codLoja = ci.id_cadastro
		LEFT JOIN base_web_control.nf_municipio_RF rf   ON c.cidade = rf.cidade
        WHERE c.insc = :cnpj
        AND c.codloja = :id_cadastro
SQLQUERY;
        try
        {
            $stmt = DB::connect()->Prepare ( $sql );
            $stmt->bindValue(':cnpj', $this->CnpjEmpresa , PDO::PARAM_INT);
            $stmt->bindValue(':id_cadastro', $this->CodigoEmpresa , PDO::PARAM_INT);
            $stmt->Execute ();
            $rs = $stmt->fetchAll(PDO::FETCH_OBJ);
            $stmt->closeCursor();
            unset($stmt);
        } catch ( PDOException $PDOExceptione ) {
            throw new Exception ( $PDOExceptione->getMessage() );
        } catch ( Exception $ae ) {
            throw new Exception ( $ae->getMessage() );
        }

        if( empty($rs) ):
            throw new Exception ( __LINE__ . ' - Nenhuma Empresa encontrada com o codigo ' . strip_tags($this->CodigoEmpresa) . '!' );
        endif;

        if( empty($rs[0]->inscr_municipal) ):
            throw new Exception( '[' . __FUNCTION__ . '] - InscriÃƒÂ§ÃƒÂ£o Municipal da empresa ' . $this->NomeFantasiaEmpresa . ' nÃƒÂ£o foi informada !');
        endif;

        if( empty($rs[0]->keypass) ):
            throw new Exception( '[' . __FUNCTION__ . '] - keypass da empresa ' . $this->NomeFantasiaEmpresa . ' nÃƒÂ£o foi informada !');
        endif;

        $this->InscricaoMunicipalEmpresa =  $rs[0]->inscr_municipal;
        $this->InscricaoEstadualEmpresa  =  $rs[0]->inscr_estadual;
        $this->RazaoSocialEmpresa        =  $rs[0]->razao_social;
        $this->NomeFantasiaEmpresa       =  $rs[0]->nome_fantasia; 
        $this->AliquotaISSEmpresa        =  $rs[0]->aliquota_iss;
        $this->keyPass                   =  $rs[0]->keypass;
        $this->issqn_cpf                 =  $rs[0]->issqn_cpf;
        $this->issqn_senha_cmc_cpf       =  $rs[0]->issqn_senha_cmc_cpf;
        $this->issqn_id_municipio        =  $rs[0]->issqn_id_municipio;
        $this->issqn_id_municipio_rf     =  $rs[0]->issqn_id_municipio_rf;
		
        return true;
    }
	
    protected function __loadCerts()
    {
        if( empty($this->CnpjEmpresa) ):
            throw new Exception( __LINE__ . ' - CNPJ da empresa nao informado !' );
        endif;

        if( !Validacoes::isValidCNPJ($this->CnpjEmpresa) ):
            throw new Exception( __LINE__ . ' - CNPJ da empresa invalido !' );
        endif;

        $x509certdata = '';

        $this->certName = preg_replace('/[^[:digit:]]/','',$this->CnpjEmpresa) . '.pfx';

        //monta o path completo com o nome da chave privada
        $this->priKEY 	= PATH_CERTS . preg_replace('/[^[:digit:]]/','',$this->CnpjEmpresa) . '_priKEY.pem';
        //monta o path completo com o nome da chave prublica
        $this->pubKEY 	= PATH_CERTS . preg_replace('/[^[:digit:]]/','',$this->CnpjEmpresa) . '_pubKEY.pem';
        //monta o path completo com o nome do certificado
        //(chave publica e privada) em formato pem
        $this->certKEY 	= PATH_CERTS . preg_replace('/[^[:digit:]]/','',$this->CnpjEmpresa) . '_certKEY.pem';

        //verificar se o nome do certificado e o path foram carregados
        //nas variaveis da classe
        if ($this->certName == ''):
            throw new Exception( __LINE__ . ' - Um certificado deve ser passado para a classe !!!' );
        endif;

        //monta o caminho completo ate o certificado pfx
        $pCert = PATH_CERTS . $this->certName;
        //verifica se o arquivo existe
        if( !file_exists($pCert) ):
            throw new Exception( __LINE__ . ' - Certificado "' . $this->certName . '" nao encontrado !!!' );
        endif;

        //carrega o certificado em um string
        $key = file_get_contents($pCert);
        //carrega os certificados e chaves para um array denominado
        //$x509certdata
        if (!openssl_pkcs12_read($key,$x509certdata,$this->keyPass) ):
            throw new Exception( __LINE__ . ' - O certificado nao pode ser lido!! Provavelmente senha invÃƒÂ¡lida, corrompido ou com formato invalido !!!');
        endif;

        //verifica sua validade
        $aResp = $this->__validCerts($x509certdata['cert']);
        if ( $aResp['error'] != '' ):
            throw new Exception( __LINE__ . ' - Certificado invalido!! - ' . $aResp['error']);
        endif;

        //verifica se arquivo ja existe
        if( file_exists($this->priKEY) ):
            //se existir verificar se e o mesmo
            $conteudo = file_get_contents($this->priKEY);
            //comparar os primeiros 100 digitos
            if ( !substr($conteudo,0,100) == substr($x509certdata['pkey'],0,100) ):
                //se diferentes gravar o novo
                if (!file_put_contents($this->priKEY,$x509certdata['pkey']) ):
                    throw new Exception(__LINE__ . ' - Impossivel gravar no diretorio!!!Permissao negada !!!');
                endif;
            endif;
        else:
            //salva a chave privada no formato pem para uso so SOAP
            if ( !file_put_contents($this->priKEY,$x509certdata['pkey']) ):
                throw new Exception(__LINE__ . ' - Impossivel gravar no diretorio!!! Permissao negada !!!');
            endif;
        endif;

        //verifica se arquivo com a chave publica ja existe
        if(file_exists($this->pubKEY)):
            //se existir verificar se e o mesmo atualmente instalado
            $conteudo = file_get_contents($this->pubKEY);
            //comparar os primeiros 100 digitos
            if ( !substr($conteudo,0,100) == substr($x509certdata['cert'],0,100) ):
                //se diferentes gravar o novo
                file_put_contents($this->pubKEY,$x509certdata['cert']);
                //salva o certificado completo no formato pem
                file_put_contents($this->certKEY,$x509certdata['pkey']."\r\n".$x509certdata['cert']);
            endif;
        else:
            //se nao existir salva a chave publica no formato pem para uso do
            //SOAP
            file_put_contents($this->pubKEY,$x509certdata['cert']);
            //salva o certificado completo no formato pem
            file_put_contents($this->certKEY,$x509certdata['pkey']."\r\n".$x509certdata['cert']);
        endif;

        //chave privada
        if( !file_exists($this->priKEY) ):
            throw new Exception( __LINE__ . ' - Chave privada nao encontrada !!!' );
        endif;

        //chave prublica
        if( !file_exists($this->pubKEY) ):
            throw new Exception( __LINE__ . ' - Chave publica nao encontrada !!!' );
        endif;

        //certificado
        if( !file_exists($this->certKEY) ):
            throw new Exception( __LINE__ . ' - Certificado nao encontrado !!!' );
        endif;

        return true;
    }
	
    private function startCurl( $dados , $metodo )
    {
        try {

            //[Informational 1xx]
            $cCode['100']="Continue";
            $cCode['101']="Switching Protocols";
            //[Successful 2xx]
            $cCode['200']="OK";
            $cCode['201']="Created";
            $cCode['202']="Accepted";
            $cCode['203']="Non-Authoritative Information";
            $cCode['204']="No Content";
            $cCode['205']="Reset Content";
            $cCode['206']="Partial Content";
            //[Redirection 3xx]
            $cCode['300']="Multiple Choices";
            $cCode['301']="Moved Permanently";
            $cCode['302']="Found";
            $cCode['303']="See Other";
            $cCode['304']="Not Modified";
            $cCode['305']="Use Proxy";
            $cCode['306']="(Unused)";
            $cCode['307']="Temporary Redirect";
            //[Client Error 4xx]
            $cCode['400']="Bad Request";
            $cCode['401']="Unauthorized";
            $cCode['402']="Payment Required";
            $cCode['403']="Forbidden";
            $cCode['404']="Not Found";
            $cCode['405']="Method Not Allowed";
            $cCode['406']="Not Acceptable";
            $cCode['407']="Proxy Authentication Required";
            $cCode['408']="Request Timeout";
            $cCode['409']="Conflict";
            $cCode['410']="Gone";
            $cCode['411']="Length Required";
            $cCode['412']="Precondition Failed";
            $cCode['413']="Request Entity Too Large";
            $cCode['414']="Request-URI Too Long";
            $cCode['415']="Unsupported Media Type";
            $cCode['416']="Requested Range Not Satisfiable";
            $cCode['417']="Expectation Failed";
            //[Server Error 5xx]
            $cCode['500']="Internal Server Error";
            $cCode['501']="Not Implemented";
            $cCode['502']="Bad Gateway";
            $cCode['503']="Service Unavailable";
            $cCode['504']="Gateway Timeout";
            $cCode['505']="HTTP Version Not Supported";
				
            $tamanho = strlen($dados);
            $parametros = array(
                            'Host: ' . $this->Host[$this->aplicativo],
            				'soapaction: "http://'.$this->Host[$this->aplicativo].'/ws/v1_03#'.$metodo.'"',
                            'content-type: text/xml; charset=ISO-8859-1',
                            "Content-length: $tamanho");
                
            $oCurl = curl_init();
            curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 10 );
            curl_setopt($oCurl, CURLOPT_URL,$this->URLwebservice[$this->aplicativo]);
            curl_setopt($oCurl, CURLOPT_PORT, 443);
            curl_setopt($oCurl, CURLOPT_VERBOSE, 1);
            curl_setopt($oCurl, CURLOPT_HEADER, 1); //retorna o cabeÃƒÂ§alho de resposta
            curl_setopt($oCurl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            //curl_setopt($oCurl, CURLOPT_SSLVERSION, 3);
            //curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 2); // verifica o host evita MITM
            //curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
            //curl_setopt($oCurl, CURLOPT_SSLCERT, $this->certKEY);
            //curl_setopt($oCurl, CURLOPT_SSLKEY, $this->priKEY);
            curl_setopt($oCurl, CURLOPT_POST, 1);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $dados);
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($oCurl, CURLOPT_HTTPHEADER, $parametros);

            #$fp = fopen( dirname(__FILE__) . '/errorlog.txt', 'w');
            #curl_setopt($oCurl, CURLOPT_STDERR, $fp);
			
            $xml = curl_exec($oCurl);
            $info = curl_getinfo($oCurl); //informaÃƒÂ§ÃƒÂµes da conexÃƒÂ£o

            $txtInfo ="";
            $txtInfo .= "URL=$info[url]\n";
            $txtInfo .= "Content type=$info[content_type]\n";
            $txtInfo .= "Http Code=$info[http_code]\n";
            $txtInfo .= "Header Size=$info[header_size]\n";
            $txtInfo .= "Request Size=$info[request_size]\n";
            $txtInfo .= "Filetime=$info[filetime]\n";
            $txtInfo .= "SSL Verify Result=$info[ssl_verify_result]\n";
            $txtInfo .= "Redirect Count=$info[redirect_count]\n";
            $txtInfo .= "Total Time=$info[total_time]\n";
            $txtInfo .= "Namelookup=$info[namelookup_time]\n";
            $txtInfo .= "Connect Time=$info[connect_time]\n";
            $txtInfo .= "Pretransfer Time=$info[pretransfer_time]\n";
            $txtInfo .= "Size Upload=$info[size_upload]\n";
            $txtInfo .= "Size Download=$info[size_download]\n";
            $txtInfo .= "Speed Download=$info[speed_download]\n";
            $txtInfo .= "Speed Upload=$info[speed_upload]\n";
            $txtInfo .= "Download Content Length=$info[download_content_length]\n";
            $txtInfo .= "Upload Content Length=$info[upload_content_length]\n";
            $txtInfo .= "Start Transfer Time=$info[starttransfer_time]\n";
            $txtInfo .= "Redirect Time=$info[redirect_time]\n";
            $txtInfo .= "Certinfo=".print_r($info['certinfo'], true)."\n";
            $lenN = strlen($xml);
            $posX = stripos($xml, "<");
            if ($posX !== false):
                $xml = substr($xml, $posX, $lenN-$posX);
            else:
                $xml = '';
            endif;
            $this->soapDebug = $dados."\n\n".$txtInfo."\n".$xml;
            if ($xml === false || $posX === false):
                //nÃƒÂ£o houve retorno
                $msg = curl_error($oCurl);
                if ($info['http_code'] >= 100):
                    $msg .= $info['http_code'].$cCode[$info['http_code']];
                endif;
                throw new Exception($msg);
            else:
                //houve retorno mas ainda pode ser uma mensagem de erro do webservice
                if ($info['http_code'] > 300):
                    throw new Exception( $info['http_code'].$cCode[$info['http_code']] );
                endif;
            endif;
            curl_close($oCurl);
            if ($info['http_code'] != 200) {
                $xml = '';
            }
            return $xml;
            
        } catch ( Exception  $e) {
            #die($this->soapDebug);
            throw new Exception( $e->getMessage() );
        }
    } //fim sendSOAP
	
   
    public function enviar()
    {   
        try{

            if( empty( $this->NumeroLoteRPS )):
                echo '999;NumeroLoteRPS nao encontrado !';
            endif;

            if( !file_exists( $this->arquivoXMLRPS )):
                echo '999;Nao foi possivel encontrar o arquivo RPS no diretorio de destino !';
            endif;

            $login = $this->issqn_cpf;
            $senha = $this->issqn_senha_cmc_cpf;
            $cidade = $this->issqn_id_municipio_rf;

            // Enviando por POST para o PROVEDOR IPM

            $data['login'] = $login;
            $data['senha'] = $senha;
            $arquivo = curl_file_create('/'.$this->arquivoXMLRPS );
            $array_arquivo = file($this->arquivoXMLRPS) or die ("Nao foi possivel abrir o arquivo");
            $data['f1'] = $arquivo;
            $qtd_linhas = count($arquivo);
            $xml_envio = '';
            for($i=0;$i<$qtd_linhas;$i++)
            {
                $xml_envio .= $array_arquivo[0];
            }
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://sync.nfs-e.net/datacenter/include/nfw/importa_nfw/nfw_import_upload.php?eletron=1xxxxxxxxxxxxxxxxxx");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                print curl_error($ch);
            } else {
                curl_close($ch);
            }

            // limpando pois vem dados no inicio inseridos pela prefeitura

            $pos_inicial = strpos($result,'<?xml');
            $result = substr($result,$pos_inicial,strlen($result) );

            $xmlRecDoc = new DOMDocument("1.0", "iso-8859-1");
            $xmlRecDoc->preservWhiteSpace = false;//elimina espacos em branco
            $xmlRecDoc->formatOutput = false;
            $xmlRecDoc->loadXML( $result , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );

            if( $xmlRecDoc->getElementsByTagName ( "retorno" )->item ( 0 ) == false ):
                echo '999;Erro na validacao na prefeitura : retorno nao encontrado no xml';
            endif;

            $retorno = $xmlRecDoc->getElementsByTagName ( "retorno" )->item ( 0 );
            $mensagem = $retorno->getElementsByTagName ( "mensagem" )->item ( 0 );

            // verificando se a nota foi gerada com sucesso

            $msg_xml = $retorno->getElementsByTagName ( "mensagem" )->item ( 0 ) -> nodeValue;

            if ( trim($msg_xml) == "00001 - Sucesso" ){

                $this->NumeroLoteRPS = $retorno->getElementsByTagName ( "numero_nfse" )->item ( 0 ) -> nodeValue;
                $this->xml_envio = $xml_envio;			

                if( $this->NumeroLoteRPS == false ):
                        throw new Exception( '999;NUMERO DA NOTA FISCAL NAO ENCONTRADA NO XML DA PREFEITURA' );
                endif;

                $link_baixar_nota = $retorno->getElementsByTagName ( "link_nfse" )->item ( 0 ) -> nodeValue;

                $RecepcionarXmlResult = html_entity_decode($result);

                preg_match_all ( '/<\?xml(.*?)\?>/is', $RecepcionarXmlResult , $matches );
                if (! empty ( $matches [0] )):
                        $RecepcionarXmlResult = str_replace ( $matches [0], '<?xml version="1.0" encoding="UTF-8"?>', $RecepcionarXmlResult );
                endif;

                // GRAVANDO NO WEBCONTROL QUE A NOTA FOI GERADA

                $NomeArquivo  = $this->CodigoEmpresa;
                $NomeArquivo .= '_RPS_';
                $NomeArquivo .= $this->NumeroPedido;
                $NomeArquivo .= '.xml';

                // nao existe  INSERT
                $sql = "INSERT INTO  base_web_control.venda_notas_eletronicas
                            (id_venda, tipo_nota, status, numero_nota, ambiente_nf, xml, LINK_NFS, LOTE, ARQUIVO, RETORNO)
                        VALUES
                            (:id_venda, :tipo_nota, :status, :numero_nota, :ambiente_nf, :xml, :linkNfs, :lote, :arquivo, :retorno )";

                $stmt = DB::connect()->Prepare ( $sql );
                $stmt->bindValue(':id_venda', $this->NumeroPedido, PDO::PARAM_INT);
                $stmt->bindValue(':tipo_nota','NFS', PDO::PARAM_STR);
                $stmt->bindValue(':status', '5', PDO::PARAM_STR);
                $stmt->bindValue(':numero_nota', $this->NumeroLoteRPS, PDO::PARAM_INT);
                $stmt->bindValue(':ambiente_nf', '1' , PDO::PARAM_INT);
                $stmt->bindValue(':xml', $this->xml_envio , PDO::PARAM_STR);
                $stmt->bindValue(':linkNfs', $link_baixar_nota , PDO::PARAM_STR);
                $stmt->bindValue(':lote', $this->NumeroLoteRPS, PDO::PARAM_STR);
                $stmt->bindValue(':arquivo', $NomeArquivo , PDO::PARAM_STR);
                $stmt->bindValue(':retorno', $RecepcionarXmlResult , PDO::PARAM_STR);
                $stmt->Execute ();
                $stmt->closeCursor();
                unset($stmt);
                
                return 'OKNOTA;'.$link_baixar_nota;

            } else {

                    // TRATAMENTO DE ERRO NA PRIMEIRA REQUISICAO FEITA A IPM
                    return '900;'.$mensagem->nodeValue;

            }

        } catch(SoapFault $fault)
        {
                throw new Exception($fault->getMessage());
        }
    }

    private function __AssinarRPS( $returned_content  )
    {
        # declaracoes usadas no tratamento do xml
        $order = array("\r\n", "\n", "\r", "\t");
        $replace = '';
        $i = 0;
        $param = array();

        try
        {
            if( empty($this->InscricaoMunicipalEmpresa) ):
                throw new Exception( '999;InscriÃƒÂ§ÃƒÂ£o Municipal da empresa ' . $this->NomeFantasiaEmpresa . ' nÃƒÂ£o foi informada !');
            endif;

            // segundo regra no site pbh quando IM terminar com X , acrescentar zero a esquerda
            if( !preg_match("/^([0-9]{10}([0-9]|[X]{1})?)$/", $this->InscricaoMunicipalEmpresa) ):
            #	throw new Exception( '[' . __FUNCTION__ . '] - InscriÃƒÂ§ÃƒÂ£o Municipal ' . $this->InscricaoMunicipalEmpresa . ' da empresa ' . $this->NomeFantasiaEmpresa . ' nÃƒÂ£o ÃƒÂ© vÃƒÂ¡lida !');
            endif;

            if( empty($this->CnpjEmpresa) ):
                throw new Exception( '999;CNPJ da empresa ' . $this->NomeFantasiaEmpresa . ' nao foi informado !');	
            endif;
			
            if( !Validacoes::isValidCNPJ($this->CnpjEmpresa) ):
                throw new Exception( '999;CNPJ DO EMITENTE DA NOTA ESTA INVALIDO' );
                endif;

                $matches = array ();
                preg_match_all ( '/<\?xml(.*?)\?>/is', $returned_content , $matches );
                if (! empty ( $matches [0] )):
                    $returned_content = str_replace ( $matches [0], '', $returned_content );
                else:
                    throw new Exception ('999;Erro resposta XML !' );
                endif;

                $dom = new DOMDocument('1.0', 'UTF-8');
                $dom->preservWhiteSpace = false; // elimina espacos em branco
                $dom->formatOutput = false; // ignora formatacao
                $dom->loadXML( $returned_content , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );

                // Enable user error handling
                libxml_use_internal_errors(true);

                if( $dom->getElementsByTagName ( "nfse" )->item ( 0 ) == false ):
                        throw new Exception('999;Numero [nfse] nao encontrado , verifique o xml de envio !');
                endif;

                $prestador = $dom->getElementsByTagName ( "prestador" )->item ( 0 );
                $Cnpj = trim($prestador->getElementsByTagName ( "cpfcnpj" )->item ( 0 )->nodeValue);

                // compara o cnpj do prestador com o da rps
                if( $Cnpj != $this->CnpjEmpresa ):
                    throw new Exception('999;CNPJ informado no RPS nao confere com o CNPJ informado na consulta !');
                endif;

                $this->NumeroLoteRPS = $dom->getElementsByTagName('nfse')->item(0)->getAttribute("id");

                $QuantidadeRps = 1;

                # imprime o xml na tela
                $docxml = $dom->saveXML($dom->documentElement);
                $docxml = str_replace($order, $replace, $docxml);

                $matches = array ();
                preg_match_all ( '/<\?xml(.*?)\?>/is', $docxml , $matches );
                if (! empty ( $matches [0] )):
                        $docxml = str_replace ( $matches [0], '', $docxml );
                endif;

                $NomeArquivo  = $this->CodigoEmpresa;
                $NomeArquivo .= '_RPS_';
                $NomeArquivo .= $this->NumeroPedido;
                $NomeArquivo .= '.xml';

                $PathRemessa = PATH_PRODUCAO_REMESSA;

                $this->arquivoXMLRPS = $PathRemessa . $NomeArquivo;

                // apaga caso exista

                if( file_exists( $this->arquivoXMLRPS )):
                        unlink( $this->arquivoXMLRPS );
                endif;

                # salvando o rps em arquivo
                if (!$handle = fopen( $this->arquivoXMLRPS ,'xb')):
                    throw new Exception( '[' . __FUNCTION__ . '] - NÃƒÂ£o foi possÃƒÂ­vel abrir o arquivo RPS ,Lote nro.' . $this->NumeroLoteRPS . ' !' );
                endif;
		    
                if(!fputs($handle,$docxml)):
                    fclose($handle);
                    throw new Exception( '[' . __FUNCTION__ . '] - NÃƒÂ£o foi possÃƒÂ­vel salvar o arquivo RPS ,Lote nro.' . $this->NumeroLoteRPS.  ' !' );
                endif;

                fclose($handle);			

                # verificando a integridade do xml
                $dom->load( $this->arquivoXMLRPS );

                $this->mensageAssinaturaXMLRPS = 'ok';

                return true;
            }
            catch (Exception $ae)
            {
                if( DB::connect()->inTransaction() ):
                        DB::connect()->rollBack();
                endif;
                throw new Exception($ae->getMessage());
            }
		
	}
	
	public function CancelarLoteRps()
	{
            try{
	
                if( empty($this->rpsPROTOCOLO) ):
                        throw new Exception( 'Numero do Pedido invalido' . $this->rpsPROTOCOLO );
                endif;

                $xmlDoc = $this->xml_cancelamento;
                               
                $NomeArquivo = 'Cancelamento_RPS_Pedido_'.str_pad($this->rpsPROTOCOLO,10,'0',STR_PAD_LEFT).'.xml';
                
                $PathRemessa = PATH_PRODUCAO_REMESSA;

                $this->arquivoXMLRPS = $PathRemessa . $NomeArquivo;

                if( file_exists( $this->arquivoXMLRPS )):
                    unlink( $this->arquivoXMLRPS );
                endif;
                
                # salvando o rps em arquivo

                if (!$handle = fopen( $this->arquivoXMLRPS ,'xb')):
                    throw new Exception( '[' . __FUNCTION__ . '] - NÃ£o foi possÃ­vel abrir o arquivo RPS ,Lote nro.' . $this->NumeroLoteRPS . ' !' );
                endif;

                if(!fputs($handle,$xmlDoc)):
                        fclose($handle);
                        throw new Exception( '[' . __FUNCTION__ . '] - NÃ£o foi possÃ­vel salvar o arquivo RPS ,Lote nro.' . $this->NumeroLoteRPS.  ' !' );
                endif;

                fclose($handle);
                
                $this->__loadDadosEmpresa();
                
                $login = $this->issqn_cpf;
                $senha = $this->issqn_senha_cmc_cpf;
                $cidade = $this->issqn_id_municipio_rf;

                // Enviando por POST para o PROVEDOR IPM

                $data['login'] = $login;
                $data['senha'] = $senha;
                $arquivo = curl_file_create('/'.$this->arquivoXMLRPS );
                $array_arquivo = file($this->arquivoXMLRPS) or die ("Nao foi possivel abrir o arquivo");
                $data['f1'] = $arquivo;
                $qtd_linhas = count($arquivo);
                $xml_envio = '';
                for($i=0;$i<=$qtd_linhas;$i++)
                {
                    $xml_envio .= $array_arquivo[0];
                }
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://sync.nfs-e.net/datacenter/include/nfw/importa_nfw/nfw_import_upload.php?eletron=1xxxxxxxxxxxxxxxxxx");
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    print curl_error($ch);
                } else {
                    curl_close($ch);
                }
                
                // limpando pois vem dados no inicio inseridos pela prefeitura
                
                $pos_inicial = strpos($result,'<?xml');
                $result = substr($result,$pos_inicial,strlen($result) );
                
                $xmlRecDoc = new DOMDocument("1.0", "iso-8859-1");
                $xmlRecDoc->preservWhiteSpace = false;//elimina espacos em branco
                $xmlRecDoc->formatOutput = false;
                $xmlRecDoc->loadXML( $result , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );

                if( $xmlRecDoc->getElementsByTagName ( "retorno" )->item ( 0 ) == false ):
                    echo '999;Erro na validacao na prefeitura : retorno nao encontrado no xml';
                endif;

                $retorno = $xmlRecDoc->getElementsByTagName ( "retorno" )->item ( 0 );
                $mensagem = $retorno->getElementsByTagName ( "mensagem" )->item ( 0 );

                // verificando se a nota foi gerada com sucesso

                $msg_xml = $retorno->getElementsByTagName ( "mensagem" )->item ( 0 ) -> nodeValue;

                if ( trim($msg_xml) == "00001 - Sucesso" ){
                    
                    $sql = <<<SQLQUERY
                        UPDATE base_web_control.venda_notas_eletronicas 
                            SET
                                status = '3',
                                data_cancelamento = :data_cancelamento,
                                xml_cancelamento = :xml_cancelamento,
                                retorno_cancelamento_prefeitura = :retorno_cancelamento_prefeitura
                        WHERE id_venda = :id_venda
SQLQUERY;
                    try
                    {
                            $stmt = DB::connect()->Prepare ( $sql );
                            $data_cancelamento = date('Y-m-d h:i:s');
                            
                            $stmt->bindValue(':data_cancelamento', $data_cancelamento , PDO::PARAM_INT);
                            $stmt->bindValue(':xml_cancelamento', $xmlDoc , PDO::PARAM_STR);
                            $stmt->bindValue(':retorno_cancelamento_prefeitura', $result , PDO::PARAM_STR);
                            $stmt->bindValue(':id_venda', $this->rpsPROTOCOLO , PDO::PARAM_INT);
                            $stmt->Execute ();
                            $stmt->closeCursor();
                            unset($stmt);
                    } catch ( PDOException $PDOExceptione ) {
                            throw new Exception ( $PDOExceptione->getMessage() );
                            exit;
                    } catch ( Exception $ae ) {
                            throw new Exception ( $ae->getMessage() );
                            exit;
                    }
                    echo "OKCANCELAMENTO";
                    exit;
                }else{
                    echo "999;".$msg_xml;
                    exit;
                }
            } catch(SoapFault $fault)
            {
                throw new Exception($fault->getMessage());
            }
            return true;		
	}
	
	public function ConsultarLoteRps()
	{
            try
            {
                if( empty($this->CnpjEmpresa) ):
                    throw new Exception( 'CNPJ da empresa ' . $this->NomeFantasiaEmpresa . ' nao foi informado !');
                endif;

                if( !Validacoes::isValidCNPJ($this->CnpjEmpresa) ):
                    throw new Exception( '' . Validacoes::$errorMessage );
                endif;

                $this->__loadDadosEmpresa();
                $this->__loadCerts();

                if( empty($this->InscricaoMunicipalEmpresa) ):
                    throw new Exception( 'InscriÃƒÂ§ÃƒÂ£o Municipal da empresa ' . $this->NomeFantasiaEmpresa . ' nÃƒÂ£o foi informada !');
                endif;

                // segundo regra no site pbh quando IM terminar com X , acrescentar zero a esquerda
                if( !preg_match("/^([0-9]{7}([0-9]|[X]{1})?)$/", $this->InscricaoMunicipalEmpresa) ):
                    throw new Exception( 'InscriÃƒÂ§ÃƒÂ£o Municipal ' . $this->InscricaoMunicipalEmpresa . ' da empresa ' . $this->NomeFantasiaEmpresa . ' nÃƒÂ£o ÃƒÂ© vÃƒÂ¡lida !');
                endif;

                if( empty($this->rpsPROTOCOLO) ):
                    throw new Exception( 'Numero do protocolo nao foi informado !');
                endif;

                if( !is_numeric($this->rpsPROTOCOLO) ):
                    throw new Exception( 'Numero do protocolo invalido !');
                endif;

                $xmlDoc = $this->criarXML( "ConsultarLoteRps" , true );

                $ConsultarLoteRps = $xmlDoc->getElementsByTagName ( "ConsultarLoteRps" )->item ( 0 );

                $ConsultarLoteRpsEnvio = $xmlDoc->createElement("ConsultarLoteRpsEnvio");
                $ConsultarLoteRps->appendChild($ConsultarLoteRpsEnvio);

                $Prestador = $xmlDoc->createElement("Prestador");
                $Protocolo = $xmlDoc->createElement("Protocolo" , $this->rpsPROTOCOLO );
                $ConsultarLoteRpsEnvio->appendChild($Prestador);
                $ConsultarLoteRpsEnvio->appendChild($Protocolo);

                $Cnpj = $xmlDoc->createElement('Cnpj' , $this->CnpjEmpresa );
                $InscricaoMunicipal = $xmlDoc->createElement('InscricaoMunicipal' , $this->InscricaoMunicipalEmpresa );

                $Prestador->appendChild($Cnpj);
                $Prestador->appendChild($InscricaoMunicipal);

                $xml = str_replace( array("\r\n", "\n", "\r"), '', $xmlDoc->saveXML($xmlDoc->documentElement) );

                $result = $this->startCurl( $xml , 'ConsultarLoteRps');

                // remove o node element e body
                $doc                     = new DOMDocument('1.0', 'UTF-8');
                $doc->preserveWhiteSpace = false;
                $doc->formatOutput       = true;
                $doc->loadXML( $result );
                $code = $doc->documentElement->getElementsByTagName('*')->item(0)->getElementsByTagName('*')->item(0);
                $doc->replaceChild($code, $doc->documentElement);
                $result = $doc->saveXML();

                // remove default:
                $result = preg_replace('/(<\s*)\w+:/','$1',$result);


                return $result;

            } catch(SoapFault $fault)
            {
                throw new Exception($fault->getMessage());
            }
            return true;
	}
	
	public function ConsultarSituacaoLoteRps()
	{
            try
            {
                if( empty($this->CnpjEmpresa) ):
                    echo 'CNPJ da empresa nao foi informado !';
                    exit;
                endif;

                if( !Validacoes::isValidCNPJ($this->CnpjEmpresa) ):
                        throw new Exception( '' . Validacoes::$errorMessage );
                endif;

                $this->__loadDadosEmpresa();
                $this->__loadCerts();

                if( empty($this->InscricaoMunicipalEmpresa) ):
                    echo '999;Inscricao Municipal da empresa nao foi informada !';
                    exit;
                endif;

                // segundo regra no site pbh quando IM terminar com X , acrescentar zero a esquerda
                if( !preg_match("/^([0-9]{7}([0-9]|[X]{1})?)$/", $this->InscricaoMunicipalEmpresa) ):
                    echo '999;Inscricao Municipal invalida';
                    exit;
                endif;

                if( empty($this->rpsPROTOCOLO) ):
                    echo '999;Numero do protocolo nao foi informado';
                    exit;
                endif;

                if( !is_numeric($this->rpsPROTOCOLO) ):
                    echo '999;Numero do protocolo invalido';
                    exit;
                endif;

                $xmlDoc = $this->criarXML( "ConsultarSituacaoLoteRps" , true );

                $ConsultarSituacaoLoteRps = $xmlDoc->getElementsByTagName ( "ConsultarSituacaoLoteRps" )->item ( 0 );

                $ConsultarSituacaoLoteRpsEnvio = $xmlDoc->createElement("ConsultarSituacaoLoteRpsEnvio");
                $ConsultarSituacaoLoteRps->appendChild($ConsultarSituacaoLoteRpsEnvio);

                $Prestador = $xmlDoc->createElement("Prestador");
                $Protocolo = $xmlDoc->createElement("Protocolo" , $this->rpsPROTOCOLO );
                $ConsultarSituacaoLoteRpsEnvio->appendChild($Prestador);
                $ConsultarSituacaoLoteRpsEnvio->appendChild($Protocolo);

                $Cnpj = $xmlDoc->createElement('Cnpj' , $this->CnpjEmpresa );
                $InscricaoMunicipal = $xmlDoc->createElement('InscricaoMunicipal' , $this->InscricaoMunicipalEmpresa );

                $Prestador->appendChild($Cnpj);
                $Prestador->appendChild($InscricaoMunicipal);

                $xml = str_replace( array("\r\n", "\n", "\r"), '', $xmlDoc->saveXML($xmlDoc->documentElement) );

                $result = $this->startCurl( $xml , 'ConsultarLoteRps');

                // remove o node element e body
                $doc                     = new DOMDocument('1.0', 'UTF-8');
                $doc->preserveWhiteSpace = false;
                $doc->formatOutput       = true;
                $doc->loadXML( $result );
                $code = $doc->documentElement->getElementsByTagName('*')->item(0)->getElementsByTagName('*')->item(0);
                $doc->replaceChild($code, $doc->documentElement);
                $result = $doc->saveXML();

                // remove default:
                $result = preg_replace('/(<\s*)\w+:/','$1',$result);

                $sql = <<<SQLQUERY
                        UPDATE base_web_control.nf_servico_assinadas SET
                                RETORNO = :retorno,
                                status = '5'
                        WHERE LOTE = :lote
                        AND EMPRESA = :empresa
SQLQUERY;
                try
                {
                        $stmt = DB::connect()->Prepare ( $sql );
                        $stmt->bindValue(':retorno', $result , PDO::PARAM_STR);
                        $stmt->bindValue(':lote', $this->NumeroLoteRPS , PDO::PARAM_INT);
                        $stmt->bindValue(':empresa', $this->CnpjEmpresa , PDO::PARAM_STR);
                        $stmt->Execute ();
                        $stmt->closeCursor();
                        unset($stmt);
                } catch ( PDOException $PDOExceptione ) {
                        throw new Exception ( $PDOExceptione->getMessage() );
                } catch ( Exception $ae ) {
                        throw new Exception ( $ae->getMessage() );
                }

                return $result;

            } catch(SoapFault $fault)
            {
                    throw new Exception($fault->getMessage());
            }
            return true;
	}
	
	private function criarXML( $operacao , $header = true )
	{
		
            # cria o documento no DOM
            $xmlDoc = new DOMDocument("1.0", "ISO-8859-1");

            #gerar o codigo
            $xmlDoc->preservWhiteSpace = false;//elimina espacos em branco
            $xmlDoc->formatOutput = false;

            if( $header === true ):

                $Envelope = $xmlDoc->createElement("SOAP-ENV:Envelope");
                $Envelope->setAttribute('xmlns:SOAP-ENV', 'http://schemas.xmlsoap.org/soap/envelope/');
                $Envelope->setAttribute('xmlns:xsi', $this->URLxsi);
                $Envelope->setAttribute('xmlns:xsd', $this->URLxsd);
                $Envelope->setAttribute('xmlns:SOAP-ENC', 'http://schemas.xmlsoap.org/soap/encoding/');
                $Envelope->setAttribute('xmlns:tns', $this->URLxmlnsCall);
                $xmlDoc->appendChild($Envelope);

                $Body = $xmlDoc->createElement("SOAP-ENV:Body");
                $Envelope->appendChild($Body);

                $node = $xmlDoc->createElement( $operacao );
                $node->setAttribute('xmlns', $this->URLxmlnsCall );
                $Body->appendChild($node);

            else:
				
                $node = $xmlDoc->createElement( $operacao );
                $xmlDoc->appendChild($node);
		
            endif;

            return $xmlDoc; 
        }
		
	private function saveRPSFile($rps_file,$dir='remessa',$ext='xml')
	{
            try
            {
                $path = BASE_DIR."/rps/".$this->aplicativo."/".$dir."/";

                if (!$handle = fopen($path.date('Ymd-His').".".$ext,'xb'))
                {
                    throw new Exception(__LINE__.' - N&atilde;o foi poss&iacute;vel abrir o arquivo RPS !');
                }
                if(!fputs($handle,$rps_file))
                {
                    fclose($handle);
                    throw new Exception(__LINE__.' - N&atilde;o foi poss&iacute;vel salvar o arquivo RPS !');
                }
                fclose($handle);
            }
            catch (Exception $ae)
            {
                    throw new Exception($ae->getMessage());
            }
            return true;
	}

	private static function InfNfse( $cnpj , $insc_mun , $serie , $num )
	{
            //ajusta comprimento do numero
            $cnpj 		= str_pad($cnpj, 14, '0',STR_PAD_LEFT);
            $insc_mun  	= str_pad($insc_mun, 10, '0',STR_PAD_LEFT);
            $serie		= str_pad($serie, 11, '0',STR_PAD_LEFT);
            $num 		= str_pad($num, 11, '0',STR_PAD_LEFT);

            //monta a chave sem o digito verificador
            $InfNfse = "$cnpj$insc_mun$serie$num";

            return $InfNfse;
	}
	
   /**
     * __splitLines
     * Divide a string do certificado publico em linhas com 76 caracteres (padrÃƒÂ£o original)
     * @version 1.00
     * @package NFePHP
     * @author Bernardo Silva <bernardo at datamex dot com dot br>
     * @param string $cnt certificado
     * @return string certificado reformatado 
     */
    private function __splitLines($cnt){
        return rtrim(chunk_split(str_replace(array("\r", "\n"), '', $cnt), 76, "\n"));
    }//fim __splitLines	
	
   /**
     * verifySignatureXML
     * Verifica correcao da assinatura no xml
     * 
     * @version 1.01
     * @package NFePHP
     * @author Bernardo Silva <bernardo at datamex dot com dot br>
     * @param string $conteudoXML xml a ser verificado 
     * @param string $tag tag que e assinada
     * @return boolean false se nao confere e true se confere
     */
    public function verifySignatureXML($conteudoXML, $tag)
    {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadXML($conteudoXML);
        $tagBase = $dom->getElementsByTagName($tag)->item(0);
		$retXML = array(' xmlns:ds="http://www.w3.org/2000/09/xmldsig#"', 
					    ' xmlns:xsd="http://www.w3.org/2001/XMLSchema"', 
					    ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
					    ' xmlns="http://www.abrasf.org.br/nfse.xsd"');
        // validar digest value 
        $tagInf = $tagBase->C14N(false, false, null, null);
        $tagInf = str_replace($retXML, '', $tagInf);

        $digestCalculado = base64_encode(sha1($tagInf, true));
        $digestInformado = $dom->getElementsByTagName('DigestValue')->item(0)->nodeValue;		
        if ($digestCalculado != $digestInformado){
            $this->errStatus = true;
            $this->errMsg = "O conteudo do XML nao confere com o Digest Value.\nDigest calculado [{$digestCalculado}], informado no XML [{$digestInformado}].\nO arquivo pode estar corrompido ou ter sido adulterado.\n";
            throw new Exception(__LINE__ . ' - '.$this->errMsg);
            #return false;
        }
        // Remontando o certificado 
        $X509Certificate = $dom->getElementsByTagName('X509Certificate')->item(0)->nodeValue;
        $X509Certificate =  "-----BEGIN CERTIFICATE-----\n".
        $this->__splitLines($X509Certificate)."\n-----END CERTIFICATE-----\n";
        $pubKey = openssl_pkey_get_public($X509Certificate);
        if ($pubKey === false){
            $this->errStatus = true;
            $this->errMsg = "Ocorreram problemas ao remontar a chave publica. Certificado incorreto ou corrompido!!\n";
            throw new Exception(__LINE__ . ' - '.$this->errMsg);
            #return false;
        }                
        // remontando conteudo que foi assinado 
		$conteudoAssinado = $dom->getElementsByTagName('SignedInfo')->item(0)->C14N(false, false, null, null);
		// Retirar itens das tags da assinatura da nota 
		$conteudoAssinado = str_replace($retXML, '', $conteudoAssinado);
		// validando assinatura do conteudo 
		$conteudoAssinadoNoXML = $dom->getElementsByTagName('SignatureValue')->item(0)->nodeValue;
		$conteudoAssinadoNoXML = base64_decode(str_replace(array("\r", "\n"), '', $conteudoAssinadoNoXML));
		$ok = openssl_verify($conteudoAssinado, $conteudoAssinadoNoXML, $pubKey);
		if ($ok != 1){
            $this->errStatus = true;
            $this->errMsg = "Problema ({$ok}) ao verificar a assinatura do digital!!";
            throw new Exception(__LINE__ . ' - '.$this->errMsg);
            #return false;
		}
        $this->errStatus = false;
        $this->errMsg = "";
        return true;
    } // fim verifySignatureXML

    
    /**
     * __cleanCerts
     * Retira as chaves de inicio e fim do certificado digital
     * para inclusao do mesmo na tag assinatura do xml
     *
     * @name __cleanCerts
     * @version 1.00
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param    $certFile
     * @return   string contendo a chave digital limpa
     * @access   private
     **/
    protected function __cleanCerts($certFile)
    {
        //carregar a chave publica do arquivo pem
        $pubKey = file_get_contents($certFile);
        //inicializa variavel
        $data = '';
        //carrega o certificado em um array usando o LF como referencia
        $arCert = explode("\n", $pubKey);
        foreach ($arCert AS $curData) 
        {
            //remove a tag de inicio e fim do certificado
            if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) != 0 && 
            	strncmp($curData, '-----END CERTIFICATE', 20) != 0 )
            {
                //carrega o resultado numa string
                $data .= trim($curData);
            }
        }
        return $data;
    }//fim __cleanCerts
    	
   /**
    * __validCerts
    * Validacao do cerificado digital, alem de indicar
    * a validade, este metodo carrega a propriedade
    * mesesToexpire da classe que indica o numero de
    * meses que faltam para expirar a validade do mesmo
    * esta informacao pode ser utilizada para a gestao dos
    * certificados de forma a garantir que sempre estejam validos
    *
    * @name __validCerts
    * @param    string  $cert Certificado digital no formato pem
    * @return	array ['status'=>true,'meses'=>8,'dias'=>245]
    */    
    protected function __validCerts($cert)
    {
        $flagOK = true;
        $errorMsg = "";
        $data = openssl_x509_read($cert);
        $cert_data = openssl_x509_parse($data);
        // reformata a data de validade;
        $ano = substr($cert_data['validTo'],0,2);
        $mes = substr($cert_data['validTo'],2,2);
        $dia = substr($cert_data['validTo'],4,2);
        //obtem o timeestamp da data de validade do certificado
        $dValid = gmmktime(0,0,0,$mes,$dia,$ano);
        // obtem o timestamp da data de hoje
        $dHoje = gmmktime(0,0,0,date("m"),date("d"),date("Y"));
        // compara a data de validade com a data atual
        if ($dValid < $dHoje )
        {
            throw new Exception(__LINE__ . ' - A Validade do certificado expirou em [' . $dia . '/'. $mes . '/'. $ano . ']');
        } else
        {
            $flagOK = $flagOK && true;
        }
        //diferenÃƒÂ§a em segundos entre os timestamp
        $diferenca = $dValid - $dHoje;
        // convertendo para dias
        $diferenca = round($diferenca /(60*60*24),0);
        //carregando a propriedade
        $daysToExpire = $diferenca;
        // convertendo para meses e carregando a propriedade
        $m = ($ano * 12 + $mes);
        $n = (date("y") * 12 + date("m"));
        //numero de meses atÃƒÂ© o certificado expirar
        $monthsToExpire = ($m-$n);
        $this->certMonthsToExpire = $monthsToExpire;
        $this->certDaysToExpire = $daysToExpire;
        return array('status'=>$flagOK
        			,'error'=>$errorMsg
        			,'meses'=>$monthsToExpire
        			,'dias'=>$daysToExpire);
    }
    
	public function libxml_display_error($error)
	{
		$return = "<br/>\n";
		switch ($error->level)
		{
			case LIBXML_ERR_WARNING:
				$return .= "<b>Warning $error->code</b>: ";
				break;
			case LIBXML_ERR_ERROR:
				$return .= "<b>Error $error->code</b>: ";
				break;
			case LIBXML_ERR_FATAL:
				$return .= "<b>Fatal Error $error->code</b>: ";
				break;
		}
		$return .= trim($error->message);
		if ($error->file) 
		{
			$return .= " in <b>$error->file</b>";
		}
		$return .= " on line <b>$error->line</b>\n";
		
		return $return;
	}
	
	public function libxml_display_errors() 
	{
		$retorno = '';
		$errors = libxml_get_errors();
		foreach ($errors as $error)
		{
			$retorno.= $this->libxml_display_error($error);
		}
		libxml_clear_errors();
		
		return $retorno;
	}
	
	public function assinar( $returned_content )
	{		
		try
		{	
			# carrega os dados da empresa prestadora
			$this->__loadDadosEmpresa();

			# carrega os certificados
			#$this->__loadCerts();
		
			# assinar rps
			$this->__AssinarRPS($returned_content);
		}
		catch (Exception $ae)
		{
			 throw new Exception($ae->getMessage());
		}
		return true;
	}
}
?>