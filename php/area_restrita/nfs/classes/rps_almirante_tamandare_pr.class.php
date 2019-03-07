<?php
/**
 * @author Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
 * @version 2015083101
 * @copyright 
 * @package nfse
 * @name rps.class.php
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
    public $DataEmissao;
    public $RazaoSocialEmpresa;
    public $NomeFantasiaEmpresa;
    public $AliquotaISSEmpresa;
    public $Sigla;

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
    public $soapDebug;

    #public $certName = 'certificado-tige-seg-nfse.pfx'; 
    # chave de acesso ao certificado
    private $keyPass;

    private $URLxmlns='http://www.abrasf.org.br/nfse.xsd';

    private $URLxmlnsCall ="http://www.e-governeapps2.com.br/";

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

    //public $URLxsdCuritiba = $this->url_xsd;
    public $URLwebservice;

    private $SubjectName = 'E=nfse@curitiba.pr.gov.br,  CN=Equipe  de  Desenvolvimento NFS-e,,  O=Secretaria  Municipal  da  Fazenda  (Sefaz),  L=Curitiba,  S=Parana, C=BR';

    // public $URLxsdCuritiba = 'http://isscuritiba.curitiba.pr.gov.br/iss/nfse.xsd';
    // public $URLwebservice = array('producao' => 'https://isscuritiba.curitiba.pr.gov.br/Iss.NfseWebService/nfsews.asmx',
    //                               'homologacao' => 'http://pilotoisscuritiba.curitiba.pr.gov.br/nfse_ws/nfsews.asmx');

    // private $SubjectName = 'E=nfse@curitiba.pr.gov.br,  CN=Equipe  de  Desenvolvimento NFS-e,,  O=Secretaria  Municipal  da  Fazenda  (Sefaz),  L=Curitiba,  S=Parana, C=BR';

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
                ci.issqn_percentual_aliquota as aliquota_iss,
                ci.senha_certificado as keypass,
                nm.url_endereco_hom as url_homologacao,
                nm.url_endereco_prod as url_producao
        FROM cs2.cadastro c
        LEFT JOIN base_web_control.cadastro_imposto_padrao ci ON c.codLoja = ci.id_cadastro
        LEFT JOIN base_web_control.nfe_municipio nm on nm.id = ci.issqn_id_municipio
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
            throw new Exception( '[' . __FUNCTION__ . '] - Inscrição Municipal da empresa ' . $this->NomeFantasiaEmpresa . ' não foi informada !');
        endif;

        #if( strtoupper(trim($rs[0]->inscr_municipal)) == strtoupper('ISENTO')):
        #	throw new Exception( '[' . __FUNCTION__ . '] - Inscrição Municipal da empresa não pode ser ISENTO !');
        #endif;

        if( empty($rs[0]->keypass) ):
            throw new Exception( '[' . __FUNCTION__ . '] - keypass da empresa não foi informada !');
        endif;

        $this->InscricaoMunicipalEmpresa =  $rs[0]->inscr_municipal;
        $this->InscricaoEstadualEmpresa =  $rs[0]->inscr_estadual;
        $this->RazaoSocialEmpresa = $rs[0]->razao_social;
        $this->NomeFantasiaEmpresa = $rs[0]->nome_fantasia; 
        $this->AliquotaISSEmpresa =  $rs[0]->aliquota_iss;
        $this->keyPass =  $rs[0]->keypass;
        $this->URLwebservice = array('producao' => $rs[0]->url_producao,
            'homologacao' => $rs[0]->url_homologacao);
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
            throw new Exception( __LINE__ . ' - O certificado nao pode ser lido!! Provavelmente senha inválida, corrompido ou com formato invalido !!!');
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
                            'POST /e-nota-contribuinte-ws/nfseWS?wsdl HTTP/1.1',
                            'Host: e-gov.betha.com.br',
                            'Content-Type: application/soap+xml;charset=utf-8',
                            "Content-length: $tamanho");

            $oCurl = curl_init();
            curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 10 );
            curl_setopt($oCurl, CURLOPT_URL,$this->URLwebservice[$this->aplicativo]);
            #curl_setopt($oCurl, CURLOPT_PORT, 443);
            curl_setopt($oCurl, CURLOPT_VERBOSE, 1);
            curl_setopt($oCurl, CURLOPT_HEADER, 1); //retorna o cabeçalho de resposta
            curl_setopt($oCurl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 2); // verifica o host evita MITM
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($oCurl, CURLOPT_SSLCERT, $this->certKEY);
            curl_setopt($oCurl, CURLOPT_SSLKEY, $this->priKEY);
            curl_setopt($oCurl, CURLOPT_POST, 1);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $dados);
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($oCurl, CURLOPT_HTTPHEADER, $parametros);

            $xml = curl_exec($oCurl);

            $info = curl_getinfo($oCurl); //informações da conexão

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
                //não houve retorno
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

       
        

        private function __assinarRPS( $returned_content  )
    {
        # declaracoes usadas no tratamento do xml
        $order = array("\r\n", "\n", "\r", "\t");
        $replace = '';
        $i = 0;
        $param = array();

        $returned_content = '<EnviarLoteRpsEnvio xmlns = "http://www.betha.com.br/e-nota-contribuinte-ws">
	<LoteRps  Id="lote1" versao="2.02">
		<NumeroLote>2012024</NumeroLote>
		<CpfCnpj>
      <Cnpj>10948798000151</Cnpj>
    </CpfCnpj>
		<InscricaoMunicipal>123498</InscricaoMunicipal>
		<QuantidadeRps>1</QuantidadeRps>
		<ListaRps>
			<Rps>
				<InfDeclaracaoPrestacaoServico  Id="rps1">
					<Rps>
						<IdentificacaoRps>
							<Numero>25</Numero>
							<Serie>A1</Serie>
							<Tipo>1</Tipo>
						</IdentificacaoRps>
						<DataEmissao>2014-12-06</DataEmissao>
						<Status>1</Status>
					</Rps>
					<Competencia>2014-12-01</Competencia>
					<Servico>
						<Valores>
							<ValorServicos>100</ValorServicos>
							<ValorDeducoes>0</ValorDeducoes>
							<ValorPis>0</ValorPis>
							<ValorCofins>0</ValorCofins>
							<ValorInss>0</ValorInss>
							<ValorIr>0</ValorIr>
							<ValorCsll>0</ValorCsll>
							<OutrasRetencoes>0</OutrasRetencoes>
							<DescontoIncondicionado>0</DescontoIncondicionado>
							<DescontoCondicionado>0</DescontoCondicionado>	
						</Valores>
						<IssRetido>2</IssRetido>
						<ItemListaServico>0702</ItemListaServico>
						<CodigoTributacaoMunicipio>2525</CodigoTributacaoMunicipio>
						<Discriminacao>Prog.</Discriminacao>
						<CodigoMunicipio>4204608</CodigoMunicipio>
						<ExigibilidadeISS>1</ExigibilidadeISS>
						<MunicipioIncidencia>4204608</MunicipioIncidencia>
					</Servico>
					<Prestador>
						<CpfCnpj>
				      <Cnpj>45111111111100</Cnpj>
			    </CpfCnpj>
						<InscricaoMunicipal>123498</InscricaoMunicipal>
					</Prestador>
					<Tomador>
						<IdentificacaoTomador>
							<CpfCnpj>
								<Cnpj>83787494000123</Cnpj>
							</CpfCnpj>						
						</IdentificacaoTomador>
						<RazaoSocial>INSTITUICAO FINANCEIRA</RazaoSocial>
						<Endereco>
							<Endereco>AV. 7 DE SETEMBRO</Endereco>
							<Numero>1505</Numero>
							<Complemento>AO LADO DO JOAO AUTOMOVEIS</Complemento>
							<Bairro>CENTRO</Bairro>
							<CodigoMunicipio>4201406</CodigoMunicipio>
							<Uf>SC</Uf>
							<Cep>88900000</Cep>
						</Endereco>
						<Contato>
							<Telefone>4835220026</Telefone>
							<Email>luiz.alves@cxpostal.com</Email>
						</Contato>
					</Tomador>
					<Intermediario>
						<IdentificacaoIntermediario>
							<CpfCnpj>
								<Cnpj>06410987065144</Cnpj>
							</CpfCnpj>
							<InscricaoMunicipal>22252</InscricaoMunicipal>				
						</IdentificacaoIntermediario>
						<RazaoSocial>CONSTRUTORA TERRA FIRME</RazaoSocial>
					</Intermediario>
					<ConstrucaoCivil>
						<CodigoObra>142</CodigoObra>
						<Art>1/2014</Art>
					</ConstrucaoCivil>
					<RegimeEspecialTributacao>3</RegimeEspecialTributacao>
					<OptanteSimplesNacional>2</OptanteSimplesNacional>
					<IncentivoFiscal>2</IncentivoFiscal>
				</InfDeclaracaoPrestacaoServico>
			</Rps>
		</ListaRps>
	</LoteRps>
</EnviarLoteRpsEnvio>';
        
        try
        {
            if( empty($this->InscricaoMunicipalEmpresa) ):
                throw new Exception( '[' . __FUNCTION__ . '] - Inscrição Municipal da empresa ' . $this->NomeFantasiaEmpresa . ' não foi informada !');
            endif;

            // segundo regra no site pbh quando IM terminar com X , acrescentar zero a esquerda
            if( !preg_match("/^([0-9]{10}([0-9]|[X]{1})?)$/", $this->InscricaoMunicipalEmpresa) ):
                #	throw new Exception( '[' . __FUNCTION__ . '] - Inscrição Municipal ' . $this->InscricaoMunicipalEmpresa . ' da empresa ' . $this->NomeFantasiaEmpresa . ' não é válida !');
            endif;

            if( empty($this->CnpjEmpresa) ):
                throw new Exception( '[' . __FUNCTION__ . '] - CNPJ da empresa ' . $this->NomeFantasiaEmpresa . ' nao foi informado !');	
            endif;

            if( !Validacoes::isValidCNPJ($this->CnpjEmpresa) ):
                throw new Exception( '[' . __FUNCTION__ . '] - ' . Validacoes::$errorMessage );
            endif;
	
            /*
            $matches = array ();
            preg_match_all ( '/<\?xml(.*?)\?>/is', $returned_content , $matches );
            if (! empty ( $matches [0] )):
                $returned_content = str_replace ( $matches [0], '', $returned_content );
            else:
                throw new Exception ( __LINE__ . ' - Erro resposta XML !' );
            endif;
            */
            
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->preservWhiteSpace = false; // elimina espacos em branco
            $dom->formatOutput = false; // ignora formatacao
            $dom->loadXML( $returned_content , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );

            // Enable user error handling
            libxml_use_internal_errors(true);
	
            // validando o xml de retorno
            if ( !$dom->schemaValidate ( PATH_SCHEMAS . $this->URLArqxsd ) ):
                echo '999;XML do rps inválido, verifique o arquivo  ! ['.PATH_SCHEMAS . $this->URLArqxsd .']';
                exit;
            endif;

            $EnviarLoteRpsEnvio = $dom->getElementsByTagName ( "EnviarLoteRpsEnvio" )->item ( 0 );
            
            $LoteRps = $EnviarLoteRpsEnvio->getElementsByTagName ( "LoteRps" )->item ( 0 );

            $Cnpj = trim($LoteRps->getElementsByTagName ( "Cnpj" )->item ( 0 )->nodeValue);
            $InscricaoMunicipal = trim($LoteRps->getElementsByTagName ( "InscricaoMunicipal" )->item ( 0 )->nodeValue);
            $this->NumeroLoteRPS = trim($LoteRps->getElementsByTagName ( "NumeroLote" )->item ( 0 )->nodeValue);
            $QuantidadeRps = trim($LoteRps->getElementsByTagName ( "QuantidadeRps" )->item ( 0 )->nodeValue);

            // compara o cnpj do prestador com o da rps
            if( $Cnpj != $this->CnpjEmpresa ):
                echo '999;CNPJ informado no RPS não confere com o CNPJ informado na consulta !';
                exit;
            endif;
			
            # obtem a chave private
            $fp = fopen($this->priKEY, "r");
            $priv_key = fread($fp, 8192);
            fclose($fp);
            $pkeyid = openssl_get_privatekey($priv_key);

            $this->CnpjEmpresa = str_pad( preg_replace('/[^[:digit:]]/','', $this->CnpjEmpresa ) , 14 , '0' , STR_PAD_LEFT ); // 00000000000000
            $this->InscricaoMunicipalEmpresa = str_pad( preg_replace('/[^[:alnum:]]/','', $this->InscricaoMunicipalEmpresa ) , 10 , '0' , STR_PAD_LEFT );  // 0000000000

            $ListaRps = $LoteRps->getElementsByTagName ( "ListaRps" )->item ( 0 );

            $result = array();
            
            foreach ( $ListaRps->getElementsByTagName ( "Rps" ) as $_Rps ):
                
                if( $_Rps->parentNode !== $ListaRps ):
                    continue;
                endif;
        
                $InfRps = $_Rps->getElementsByTagName ( "InfDeclaracaoPrestacaoServico" )->item ( 0 );
                $Rps = $InfRps->getElementsByTagName ( "Rps" )->item ( 0 );
                $IdentificacaoRps = $Rps->getElementsByTagName ( "IdentificacaoRps" )->item ( 0 );

                $Numero = trim($IdentificacaoRps->getElementsByTagName ( "Numero" )->item ( 0 )->nodeValue);
                $Serie = trim($IdentificacaoRps->getElementsByTagName ( "Serie" )->item ( 0 )->nodeValue);

                #criando o numero do rps formatado conforme layout
                $InfNfse = self::InfNfse( $this->CnpjEmpresa , preg_replace('/[^[:digit:]]/','',$this->InscricaoMunicipalEmpresa ) , $Serie , $Numero );	

                //extrai os dados da tag para uma string
                $dados = $InfRps->C14N(false,false,NULL,NULL);

                //calcular o hash dos dados
                $hashValue = hash('sha1',$dados,true);

                //converte o valor para base64 para serem colocados no xml
                $digValue = base64_encode($hashValue);

                //monta a tag da assinatura digital
                $Signature = $dom->createElementNS($this->URLdsig,'Signature');
                //$Signature->setAttribute('Id','Ass_'.$InfNfse);
                
                $_Rps->appendChild($Signature);

                $SignedInfo = $dom->createElement('SignedInfo');
                $Signature->appendChild($SignedInfo);

                //Cannocalization
                $newNode = $dom->createElement('CanonicalizationMethod');
                $SignedInfo->appendChild($newNode);
                $newNode->setAttribute('Algorithm', $this->URLCanonMeth);

                //SignatureMethod
                $newNode = $dom->createElement('SignatureMethod');
                $SignedInfo->appendChild($newNode);
                $newNode->setAttribute('Algorithm', $this->URLSigMeth);

                //Reference
                $Reference = $dom->createElement('Reference');
                $SignedInfo->appendChild($Reference);
                $Reference->setAttribute('URI', '#'.$InfNfse);

                //Transforms
                $Transforms = $dom->createElement('Transforms');
                $Reference->appendChild($Transforms);

                //Transform
                $newNode = $dom->createElement('Transform');
                $Transforms->appendChild($newNode);
                $newNode->setAttribute('Algorithm', $this->URLTransfMeth_1);

                //Transform
                $newNode = $dom->createElement('Transform');
                $Transforms->appendChild($newNode);
                $newNode->setAttribute('Algorithm', $this->URLTransfMeth_2);

                //DigestMethod
                $newNode = $dom->createElement('DigestMethod');
                $Reference->appendChild($newNode);
                $newNode->setAttribute('Algorithm', $this->URLDigestMeth);

                //DigestValue
                $newNode = $dom->createElement('DigestValue',$digValue);
                $Reference->appendChild($newNode);

                // extrai os dados a serem assinados para uma string
                $dados = $SignedInfo->C14N(false,false,NULL,NULL);

                //inicializa a variavel que ira receber a assinatura
                $signature = '';

                //executa a assinatura digital usando o resource da chave privada
                openssl_sign($dados,$signature,$pkeyid);

                //codifica assinatura para o padrao base64
                $signatureValue = base64_encode($signature);

                //SignatureValue
                $newNode = $dom->createElement('SignatureValue',$signatureValue);
                #$newNode->setAttribute('ID',$InfNfse);
                $Signature->appendChild($newNode);

                //KeyInfo
                $KeyInfo = $dom->createElement('KeyInfo');
                $Signature->appendChild($KeyInfo);

                //X509Data
                $X509Data = $dom->createElement('X509Data');
                $KeyInfo->appendChild($X509Data);

                //carrega o certificado sem as tags de inicio e fim
                $cert = $this->__cleanCerts($this->pubKEY);
            
                //X509Certificate
                $newNode = $dom->createElement('X509Certificate',$cert);
                $X509Data->appendChild($newNode);

            endforeach;

            /**
             * assinatura do lote
             */
            if ( $dom->getElementsByTagName ( "LoteRps" )->item ( 0 ) == false):
                echo '999;O nó LoteRps no XML não foi encontrado !';
                exit;
            endif;

            $node = $dom->getElementsByTagName('LoteRps')->item(0);

            if( $node->getAttribute("Id") == false ):
                echo '999;Id do LoteRps não foi encontrado no arquivo xml !';
                exit;
            endif;

            $idLoteRps = trim($node->getAttribute("Id"));

            //extrai os dados da tag para uma string
            $dados = $node->C14N(false,false,NULL,NULL);

            //calcular o hash dos dados
            $hashValue = hash('sha1',$dados,true);

            //converte o valor para base64 para serem colocados no xml
            $digValue = base64_encode($hashValue);

            //monta a tag da assinatura digital
            $Signature = $dom->createElementNS($this->URLdsig,'Signature');

            $EnviarLoteRpsEnvio->appendChild($Signature);

            //$Signature->setAttribute('Id',$idLoteRps);
            $SignedInfo = $dom->createElement('SignedInfo');
            $Signature->appendChild($SignedInfo);

            //Cannocalization
            $newNode = $dom->createElement('CanonicalizationMethod');
            $SignedInfo->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLCanonMeth);

            //SignatureMethod
            $newNode = $dom->createElement('SignatureMethod');
            $SignedInfo->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLSigMeth);

            //Reference
            $Reference = $dom->createElement('Reference');
            $SignedInfo->appendChild($Reference);
            $Reference->setAttribute('URI', '#'.$idLoteRps);

            //Transforms
            $Transforms = $dom->createElement('Transforms');
            $Reference->appendChild($Transforms);

            //Transform
            $newNode = $dom->createElement('Transform');
            $Transforms->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLTransfMeth_1);

            //Transform
            $newNode = $dom->createElement('Transform');
            $Transforms->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLTransfMeth_2);

            //DigestMethod
            $newNode = $dom->createElement('DigestMethod');
            $Reference->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLDigestMeth);

            //DigestValue
            $newNode = $dom->createElement('DigestValue',$digValue);
            $Reference->appendChild($newNode);

            // extrai os dados a serem assinados para uma string
            $dados = $SignedInfo->C14N(false,false,NULL,NULL);

            //inicializa a variavel que ira receber a assinatura
            $signature = '';

            //executa a assinatura digital usando o resource da chave privada
            openssl_sign($dados,$signature,$pkeyid);

            //codifica assinatura para o padrao base64
            $signatureValue = base64_encode($signature);

            //SignatureValue
            $newNode = $dom->createElement('SignatureValue',$signatureValue);
            $Signature->appendChild($newNode);

            //KeyInfo
            $KeyInfo = $dom->createElement('KeyInfo');
            $Signature->appendChild($KeyInfo);

            //X509Data
            $X509Data = $dom->createElement('X509Data');
            $KeyInfo->appendChild($X509Data);

            //carrega o certificado sem as tags de inicio e fim
            $cert = $this->__cleanCerts($this->pubKEY);

            //X509Certificate
            $newNode = $dom->createElement('X509Certificate',$cert);
            $X509Data->appendChild($newNode);

            openssl_free_key($pkeyid);

            # imprime o xml na tela
            $docxml = $dom->saveXML($dom->documentElement);
            $docxml = str_replace($order, $replace, $docxml);

            $codigoEmpresa = $this->CodigoEmpresa;
            
            $NomeArquivo  = $codigoEmpresa;
            $NomeArquivo .= '_RPS_';
            $NomeArquivo .= $this->NumeroPedido;
            $NomeArquivo .= '.xml';   

            # salvando o rps no banco de dados
            $sql = <<<SQLQUERY
            SELECT id
            FROM base_web_control.venda_notas_eletronicas
            WHERE id_venda = :numero_pedido
SQLQUERY;
            try
            {
                $stmt = DB::connect()->Prepare ( $sql );
                $stmt->bindValue(':numero_pedido', $this->NumeroPedido , PDO::PARAM_INT);
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

                $sql = "INSERT INTO base_web_control.venda_notas_eletronicas
                        (id_venda, tipo_nota, status, numero_nota, ambiente_nf, xml, LOTE, ARQUIVO)
                VALUES
                        (:id_venda, :tipo_nota, :status, :numero_nota, :ambiente_nf, :xml, :lote, :arquivo)";
                $amb = $this->aplicativo;
                if ( trim($amb) == 'producao' ) $amb = 2;
                else $amb = 1;
                try
                {
                    $stmt = DB::connect()->Prepare ( $sql );
                    $stmt->bindValue(':id_venda', $this->NumeroPedido, PDO::PARAM_INT);
                    $stmt->bindValue(':tipo_nota','NFS', PDO::PARAM_STR);
                    $stmt->bindValue(':status', '1', PDO::PARAM_STR);
                    $stmt->bindValue(':numero_nota', $this->NumeroLoteRPS, PDO::PARAM_INT);
                    $stmt->bindValue(':ambiente_nf', $amb , PDO::PARAM_INT);
                    $stmt->bindValue(':lote', $this->NumeroLoteRPS, PDO::PARAM_STR);
                    $stmt->bindValue(':xml', $docxml, PDO::PARAM_STR);
                    $stmt->bindValue(':arquivo', $NomeArquivo , PDO::PARAM_STR);
                    $stmt->Execute ();
                    $stmt->closeCursor();
                    unset($stmt);
                } catch ( PDOException $PDOExceptione ) {
                    throw new Exception ( $PDOExceptione->getMessage() );
                } catch ( Exception $ae ) {
                    throw new Exception ( $ae->getMessage() );
                }
                
                // ATUALIZANDO A NUMERO DE SEQUENCIA DO LOTE
                
                $amb = $this->aplicativo;
                if ( trim($amb) == 'producao' ) $amb = 'P';
                else $amb = 'H';
                
                // Verificando se existe numero de LOTE na tabela, deveremos INCLUIR ou ALTERAR
                $sql = "SELECT id FROM NFSE.controle_lote
                        WHERE 
                            id_cadastro = :id_cadastro
                        AND 
                            ambiente = :ambiente
                        ";
                try
                {
                    $stmt = DB::connect()->Prepare ( $sql );
                    $stmt->bindValue(':id_cadastro', $codigoEmpresa , PDO::PARAM_INT);
                    $stmt->bindValue(':ambiente', $amb , PDO::PARAM_STR);
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
                    // Nao existe
                    $sql = "INSERT INTO NFSE.controle_lote
                                (id_cadastro, ambiente, sequencia)
                            VALUES
                                (:id_cadastro, :ambiente, :sequencia)";
                    try
                    {
                        $stmt = DB::connect()->Prepare ( $sql );
                        $stmt->bindValue(':id_cadastro', $codigoEmpresa, PDO::PARAM_INT);
                        $stmt->bindValue(':ambiente', $amb , PDO::PARAM_INT);
                        $stmt->bindValue(':sequencia', '1', PDO::PARAM_STR);
                        $stmt->Execute ();
                        $stmt->closeCursor();
                        unset($stmt);
                    } catch ( PDOException $PDOExceptione ) {
                        throw new Exception ( $PDOExceptione->getMessage() );
                    } catch ( Exception $ae ) {
                        throw new Exception ( $ae->getMessage() );
                    }
                
                else:
                    
                    // SIM Existe
                    $sql = "UPDATE NFSE.controle_lote
                            SET
                                sequencia = sequencia + 1
                            WHERE 
                            id_cadastro = :id_cadastro
                        AND 
                            ambiente = :ambiente";
                    try
                    {
                        $stmt = DB::connect()->Prepare ( $sql );
                        $stmt->bindValue(':id_cadastro', $codigoEmpresa, PDO::PARAM_INT);
                        $stmt->bindValue(':ambiente', $amb , PDO::PARAM_STR);
                        $stmt->Execute ();
                        $stmt->closeCursor();
                        unset($stmt);
                    } catch ( PDOException $PDOExceptione ) {
                        throw new Exception ( $PDOExceptione->getMessage() );
                    } catch ( Exception $ae ) {
                        throw new Exception ( $ae->getMessage() );
                    }
                    
                endif;
                
            endif;

            if( $this->aplicativo == 'producao' ):
                $PathRemessa = PATH_PRODUCAO_REMESSA;
            else:
                $PathRemessa = PATH_HOMOLOGACAO_REMESSA;
            endif;

            $this->arquivoXMLRPS = $PathRemessa . $NomeArquivo;

            if( file_exists( $this->arquivoXMLRPS )):
                    @unlink( $this->arquivoXMLRPS );
            endif;

            # salvando o rps em arquivo
            //var_dump($this->arquivoXMLRPS);
            if (!$handle = fopen( $this->arquivoXMLRPS ,'xb')):
                echo '999;Não foi possível abrir o arquivo RPS ,Lote nro.';
                exit;
            endif;

            if(!fputs($handle,$docxml)):
                fclose($handle);
                echo '999;Não foi possível salvar o arquivo RPS ,Lote nro.';
                exit;
            endif;

            fclose($handle);			

            # verificando a integridade do xml
            $dom->load( $this->arquivoXMLRPS );

            if ( !$dom->schemaValidate ( PATH_SCHEMAS . $this->URLArqxsd ) ):
                echo '999;XML do rps assinado inválido, verifique o arquivo  !';
                exit;
            endif;	

            $this->mensageAssinaturaXMLRPS = 'ok';

            return true;
        }
        catch (Exception $ae)
        {
            if( DB::connect()->inTransaction() ):
                DB::connect()->rollBack();
            endif;
            if( isset($pkeyid) ):
                openssl_free_key($pkeyid);
            endif;
            throw new Exception($ae->getMessage());
        }		
    }
		
    public function enviar()
    {
        try{

            if( empty( $this->NumeroLoteRPS )):
                echo '999;NumeroLoteRPS não encontrado !';
                exit;
            endif;

            if( !file_exists( $this->arquivoXMLRPS )):
                echo '999;Não foi possível encontrar o arquivo RPS no diretório de destino !' ;
                exit;
            endif;

            $docxml = file_get_contents($this->arquivoXMLRPS);
            
            $envelope = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:e="http://www.betha.com.br/e-nota-contribuinte-ws">
			   <soapenv:Header/>
			   <soapenv:Body>
			      <e:RecepcionarLoteRps>
			        <nfseCabecMsg><![CDATA[<cabecalho xmlns="http://www.betha.com.br/e-nota-contribuinte-ws" versao="2.02"><versaoDados>2.02</versaoDados></cabecalho>]]></nfseCabecMsg>
			        <nfseDadosMsg><![CDATA['.$docxml.']]></nfseDadosMsg>
			      </e:RecepcionarLoteRps>
			   </soapenv:Body>
			</soapenv:Envelope>';
            
            $result = $this->startCurl( $envelope , 'EnviarLoteRpsEnvio' );

            //die(print_r($result,true));
            
            
            $xmlRecDoc = new DOMDocument("1.0", "UTF-8");
            $xmlRecDoc->preservWhiteSpace = false;//elimina espacos em branco
            $xmlRecDoc->formatOutput = false;
            $xmlRecDoc->loadXML( $result , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );

            if( $xmlRecDoc->getElementsByTagName ( "RecepcionarLoteRpsResponse" )->item ( 0 ) == false ):
                echo '999;Erro na validação na prefeitura : nó RecepcionarLoteRpsResponse não encontrado no xml ';
                exit;
            endif;

            $RecepcionarLoteRpsResponse = $xmlRecDoc->getElementsByTagName ( "RecepcionarLoteRpsResponse" )->item ( 0 );

            if( $RecepcionarLoteRpsResponse->getElementsByTagName ( "EnviarLoteRpsResposta" )->item ( 0 ) == false ):
                echo '999;Erro na validação na prefeitura : nó EnviarLoteRpsResposta não encontrado no xml ';
                exit;
            endif;

            $EnviarLoteRpsResposta = html_entity_decode($RecepcionarLoteRpsResponse->getElementsByTagName ( "EnviarLoteRpsResposta" )->item ( 0 )->nodeValue);

            print_r( $EnviarLoteRpsResposta );
            die;
            
            
            preg_match_all ( '/<\?xml(.*?)\?>/is', $RecepcionarXmlResult , $matches );
            if (! empty ( $matches [0] )):
                $RecepcionarXmlResult = str_replace ( $matches [0], '<?xml version="1.0" encoding="UTF-8"?>', $RecepcionarXmlResult );
            endif;
	
            return $RecepcionarXmlResult;

        } catch(SoapFault $fault)
        {
            throw new Exception($fault->getMessage());
        }
    }

    public function CancelarLoteRps()
    {
        try{
            
            if( empty($this->CnpjEmpresa) ):
                echo '999;CNPJ da empresa ' . $this->NomeFantasiaEmpresa . ' nao foi informado !';
                exit;
            endif;

            if( !Validacoes::isValidCNPJ($this->CnpjEmpresa) ):
                echo '999;' . Validacoes::$errorMessage;
                exit;
            endif;

            $this->__loadDadosEmpresa();
            $this->__loadCerts();

            if( empty($this->InscricaoMunicipalEmpresa) ):
                echo '999;Inscrição Municipal da empresa ' . $this->NomeFantasiaEmpresa . ' não foi informada !';
                exit;
            endif;

            // segundo regra no site pbh quando IM terminar com X , acrescentar zero a esquerda
            if( !preg_match("/^([0-9]{7}([0-9]|[X]{1})?)$/", $this->InscricaoMunicipalEmpresa) ):
                echo '999;Inscrição Municipal [' . $this->InscricaoMunicipalEmpresa . '] da empresa ' . $this->NomeFantasiaEmpresa . ' não é válida !';
                exit;
            endif;

            if( empty($this->rpsPROTOCOLO) ):
                echo '999;Numero do protocolo nao foi informado !';
                exit;
            endif;

            if( !is_numeric($this->rpsPROTOCOLO) ):
                echo '999;Numero do protocolo invalido !';
                exit;
            endif;

            $xmlDoc = $this->criarXML( "CancelarLoteRps" , true );

            $CancelarLoteRps = $xmlDoc->getElementsByTagName ( "CancelarLoteRps" )->item ( 0 );

            $CancelarLoteRpsEnvio = $xmlDoc->createElement("CancelarLoteRpsEnvio");
            $CancelarLoteRps->appendChild($CancelarLoteRpsEnvio);

            $LoteRps = $xmlDoc->createElement("LoteRps");
            $CancelarLoteRpsEnvio->appendChild($LoteRps);

            $Protocolo = $xmlDoc->createElement('Protocolo' , $this->rpsPROTOCOLO );
            $Cnpj = $xmlDoc->createElement('Cnpj' , $this->CnpjEmpresa );
            $InscricaoMunicipal = $xmlDoc->createElement('InscricaoMunicipal' , $this->InscricaoMunicipalEmpresa );

            $LoteRps->appendChild($Protocolo);
            $LoteRps->appendChild($Cnpj);
            $LoteRps->appendChild($InscricaoMunicipal);

            # obtem a chave private
            $fp = fopen($this->priKEY, "r");
            $priv_key = fread($fp, 8192);
            fclose($fp);
            $pkeyid = openssl_get_privatekey($priv_key);

            $InfNfse = self::InfNfse( $this->CnpjEmpresa , $this->InscricaoMunicipalEmpresa , $this->rpsPROTOCOLO , 0 );

            //extrai os dados da tag para uma string
            $dados = $LoteRps->C14N(false,false,NULL,NULL);

            //calcular o hash dos dados
            $hashValue = hash('sha1',$dados,true);

            //converte o valor para base64 para serem colocados no xml
            $digValue = base64_encode($hashValue);

            //monta a tag da assinatura digital
            $Signature = $xmlDoc->createElementNS($this->URLdsig,'Signature');
            #$Signature->setAttribute('Id',$InfNfse);

            $CancelarLoteRpsEnvio->appendChild($Signature);

            $SignedInfo = $xmlDoc->createElement('SignedInfo');
            # $SignedInfo->setAttribute('Id',$InfNfse);
            $Signature->appendChild($SignedInfo);

            //Cannocalization
            $newNode = $xmlDoc->createElement('CanonicalizationMethod');
            $SignedInfo->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLCanonMeth);

            //SignatureMethod
            $newNode = $xmlDoc->createElement('SignatureMethod');
            $SignedInfo->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLSigMeth);

            //Reference
            $Reference = $xmlDoc->createElement('Reference');
            $SignedInfo->appendChild($Reference);
            $Reference->setAttribute('URI', '#'.$InfNfse);

            //Transforms
            $Transforms = $xmlDoc->createElement('Transforms');
            $Reference->appendChild($Transforms);

            //Transform
            $newNode = $xmlDoc->createElement('Transform');
            $Transforms->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLTransfMeth_1);

            //Transform
            $newNode = $xmlDoc->createElement('Transform');
            $Transforms->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLTransfMeth_2);

            //DigestMethod
            $newNode = $xmlDoc->createElement('DigestMethod');
            $Reference->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLDigestMeth);

            //DigestValue
            $newNode = $xmlDoc->createElement('DigestValue',$digValue);
            $Reference->appendChild($newNode);

            // extrai os dados a serem assinados para uma string
            $dados = $SignedInfo->C14N(false,false,NULL,NULL);

            //inicializa a variavel que ira receber a assinatura
            $signature = '';

            //executa a assinatura digital usando o resource da chave privada
            openssl_sign($dados,$signature,$pkeyid);

            //codifica assinatura para o padrao base64
            $signatureValue = base64_encode($signature);

            //SignatureValue
            $newNode = $xmlDoc->createElement('SignatureValue',$signatureValue);
            #$newNode->setAttribute('Id',$InfNfse);
            $Signature->appendChild($newNode);

            //KeyInfo
            $KeyInfo = $xmlDoc->createElement('KeyInfo');
            $Signature->appendChild($KeyInfo);

            //X509Data
            $X509Data = $xmlDoc->createElement('X509Data');
            $KeyInfo->appendChild($X509Data);

            //carrega o certificado sem as tags de inicio e fim
            $cert = $this->__cleanCerts($this->pubKEY);

            //X509Certificate
            $newNode = $xmlDoc->createElement('X509Certificate',$cert);
            $X509Data->appendChild($newNode);

            $xml = str_replace( array("\r\n", "\n", "\r"), '', $xmlDoc->saveXML($xmlDoc->documentElement) );

            $result = $this->startCurl( $xml , 'CancelarLoteRps');

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
                    UPDATE base_web_control.venda_notas_eletronicas 
                        SET
                            xml_cancelamento = :retorno,
                            status = '3'
                    WHERE 
                        id_venda = :id_venda 
                    AND 
                        tipo_nota = 'NFS'
                    AND
                        numero_nota = :numero_nota
SQLQUERY;
            try
            {
                $stmt = DB::connect()->Prepare ( $sql );
                $stmt->bindValue(':retorno', $result , PDO::PARAM_STR);
                $stmt->bindValue(':id_venda', $this->NumeroPedido , PDO::PARAM_INT);
                $stmt->bindValue(':numero_nota', $this->rpsPROTOCOLO , PDO::PARAM_INT);
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

    public function ConsultarLoteRps()
    {
        try
        {
            if( empty($this->CnpjEmpresa) ):
                echo '999;CNPJ da empresa ' . $this->NomeFantasiaEmpresa . ' nao foi informado !';
                exit;
            endif;

            if( !Validacoes::isValidCNPJ($this->CnpjEmpresa) ):
                echo '999;'.Validacoes::$errorMessage;
                exit;
            endif;

            $this->__loadDadosEmpresa();
            $this->__loadCerts();

            if( empty($this->InscricaoMunicipalEmpresa) ):
                echo '999;Inscrição Municipal da empresa ' . $this->NomeFantasiaEmpresa . ' não foi informada !';
                exit;
            endif;

            // segundo regra no site pbh quando IM terminar com X , acrescentar zero a esquerda
            if( !preg_match("/^([0-9]{7}([0-9]|[X]{1})?)$/", $this->InscricaoMunicipalEmpresa) ):
                echo '999;Inscrição Municipal [' . $this->InscricaoMunicipalEmpresa . '] da empresa ' . $this->NomeFantasiaEmpresa . ' não é válida !';
                exit;
            endif;

            if( empty($this->rpsPROTOCOLO) ):
                echo '999;Numero do protocolo nao foi informado !';
                exit;
            endif;

            if( !is_numeric($this->rpsPROTOCOLO) ):
                echo 'Numero do protocolo invalido !';
                exit;
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
            
            /*
            // remove o node element e body
            $doc                     = new DOMDocument('1.0', 'UTF-8');
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput       = true;
            $doc->loadXML( $result );
            $code = $doc->documentElement->getElementsByTagName('*')->item(0)->getElementsByTagName('*')->item(0);
            $doc->replaceChild($code, $doc->documentElement);
            $result = $doc->saveXML();
            */
            
            // remove default:
            $result = preg_replace('/(<\s*)\w+:/','$1',$result);

            $sql = <<<SQLQUERY
                    UPDATE base_web_control.venda_notas_eletronicas SET
                        RETORNO = :retorno
                    WHERE 
                        tipo_nota = 'NFS'
                    AND 
                        protocolo = :numero_protocolo
SQLQUERY;
            try
            {
                $stmt = DB::connect()->Prepare ( $sql );
                $stmt->bindValue(':retorno', $result , PDO::PARAM_STR);
                $stmt->bindValue(':numero_protocolo', $this->rpsPROTOCOLO , PDO::PARAM_INT);
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

    public function ConsultarSituacaoLoteRps()
    {
        try
        {
            if( empty($this->CnpjEmpresa) ):
                echo '999;CNPJ da empresa ' . $this->NomeFantasiaEmpresa . ' nao foi informado !';
                exit;
            endif;

            if( !Validacoes::isValidCNPJ($this->CnpjEmpresa) ):
                echo '999;' . Validacoes::$errorMessage;
                exit;
            endif;

            $this->__loadDadosEmpresa();
            $this->__loadCerts();

            if( empty($this->InscricaoMunicipalEmpresa) ):
                echo '999;Inscrição Municipal da empresa ' . $this->NomeFantasiaEmpresa . ' não foi informada !';
                exit;
            endif;

            // segundo regra no site pbh quando IM terminar com X , acrescentar zero a esquerda
            if( !preg_match("/^([0-9]{7}([0-9]|[X]{1})?)$/", $this->InscricaoMunicipalEmpresa) ):
                echo '999;Inscrição Municipal [' . $this->InscricaoMunicipalEmpresa . '] da empresa ' . $this->NomeFantasiaEmpresa . ' não é válida !';
                exit;
            endif;

            if( empty($this->rpsPROTOCOLO) ):
                echo '999;Numero do protocolo nao foi informado !';
                exit;
            endif;

            if( !is_numeric($this->rpsPROTOCOLO) ):
                echo '999;Numero do protocolo invalido !';
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

            /*
            $sql = <<<SQLQUERY
                    UPDATE base_web_control.nf_servico_assinadas SET
                            RETORNO = :retorno,
                            SITUACAO = 'E'
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
            */

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
        $xmlDoc = new DOMDocument("1.0", "UTF-8");

        #gerar o codigo
        $xmlDoc->preservWhiteSpace = false;//elimina espacos em branco
        $xmlDoc->formatOutput = false;

        if( $header === true ):

            $Envelope = $xmlDoc->createElement("soap12:Envelope");
            $Envelope->setAttribute('xmlns:xsi', $this->URLxsi);
            $Envelope->setAttribute('xmlns:xsd', $this->URLxsd);
            $Envelope->setAttribute('xmlns:soap12', $this->URLsoap);
            $xmlDoc->appendChild($Envelope);

            $Body = $xmlDoc->createElement("soap12:Body");
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
                echo ' N&atilde;o foi poss&iacute;vel abrir o arquivo RPS !';
                exit;
            }
            if(!fputs($handle,$rps_file))
            {
                fclose($handle);
                echo ' N&atilde;o foi poss&iacute;vel salvar o arquivo RPS !';
                exit;
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
      * Divide a string do certificado publico em linhas com 76 caracteres (padrão original)
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
        //diferença em segundos entre os timestamp
        $diferenca = $dValid - $dHoje;
        // convertendo para dias
        $diferenca = round($diferenca /(60*60*24),0);
        //carregando a propriedade
        $daysToExpire = $diferenca;
        // convertendo para meses e carregando a propriedade
        $m = ($ano * 12 + $mes);
        $n = (date("y") * 12 + date("m"));
        //numero de meses até o certificado expirar
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
            $this->__loadCerts();

            # assinar rps
            $this->__assinarRPS($returned_content);
        }
        catch (Exception $ae)
        {
            throw new Exception($ae->getMessage());
        }
        return true;
    }
}
?>