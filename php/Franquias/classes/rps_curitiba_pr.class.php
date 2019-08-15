<?php
/**
 * @author Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
 * @version 2015083101
 * @copyright 
 * @package nfse
 * @name rps.class.php
 */

if(!defined('_NFSE')):
//    die("Hacking detectado, seu ip ".$_SERVER['REMOTE_ADDR']." foi logado !");
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

if(!defined('SOAP_DOCUMENT')):
	define('SOAP_DOCUMENT', 2);
endif;
if(!defined('SOAP_LITERAL')):
	define('SOAP_LITERAL', 2);
endif;


class NFSeSOAPClient extends SoapClient {
	public $soapRequest;
	public function __doRequest($request, $location, $action, $version , $one_way = NULL ) {
		$request = str_replace(':ns1', '', $request);
		$request = str_replace('ns1:', '', $request);
		$request = str_replace("\n", '', $request);
		$request = str_replace("\r", '', $request);
		if (strpos($request,"EnviarLoteRpsEnvio") !== FALSE) {
			$request=str_replace("<EnviarLoteRPS/><param1>",'<EnviarLoteRPS xmlns="http://tempuri.org/"><loteXML>',$request);
			$request=str_replace("</param1>","</loteXML></EnviarLoteRPS>",$request);
		}
		if (strpos($request,"ConsultarLoteRps") !== FALSE) {
			$request=str_replace("<ConsultarLoteRPS/><param1>",'<ConsultarLoteRPS xmlns="http://tempuri.org/"><loteXML>',$request);
			$request=str_replace("</param1>","</loteXML></ConsultarLoteRPS>",$request);
		}
		if (strpos($request,"ConsultarSituacaoLoteRps") !== FALSE) {
			$request=str_replace("<ConsultarSituacaoLoteRPS/><param1>",'<ConsultarSituacaoLoteRPS xmlns="http://tempuri.org/"><loteXML>',$request);
			$request=str_replace("</param1>","</loteXML></ConsultarSituacaoLoteRPS>",$request);
		}
		#if (strpos($request,"CancelarLoteRps") !== FALSE) {
		#	$request=str_replace("<CancelarLoteRps/><param1>",'<CancelarLoteRps xmlns="http://tempuri.org/"><loteXML>',$request);
		#	$request=str_replace("</param1>","</loteXML></CancelarLoteRps>",$request);
		#}
		$this->soapRequest=$request;
		return (parent::__doRequest($request, $location, $action, $version ,$one_way ));
	}
} //fim da classe NFSeSOAPClient

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
    private $certKeyFile;
    
    public $errStatus;
    public $errMsg;
    public $soapRequest;
    
    private $connectionSoap;
    private $soapDebug;

    #public $certName = 'certificado-tige-seg-nfse.pfx'; 
    # chave de acesso ao certificado
    private $keyPass;

    # private $URLxmlns='http://www.abrasf.org.br/nfse.xsd';

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

    public $URLArqxsd; // a valor adicionado nesta variavel vem do PHP chamado anteriormente
    
    public $URLwebservice;
    /* 
     * o valor adicionado nesta variavel vem do PHP chamado anteriormente
     * Obrigatorio um array nesta variavel "$URLwebservice"
    */
    
    private $SubjectName = 'E=nfse@curitiba.pr.gov.br,  CN=Equipe  de  Desenvolvimento NFS-e,,  O=Secretaria  Municipal  da  Fazenda  (Sefaz),  L=Curitiba,  S=Parana, C=BR';

    public function __construct()
    {	
    }
		
    private function __loadDadosEmpresa()
    {

        $this->InscricaoMunicipalEmpresa = $this->Certificado_InscMunicipal;
        return true;
    }

    protected function __loadCerts()
    {
        
        if( empty($this->CnpjEmpresa) ):
            echo '999 - CNPJ da empresa nao informado !';
            die;
        endif;

        $this->keyPass = $this->Certificado_Senha;
       
        $x509certdata = '';

        $this->certName = 'Franquias/notafiscal/'.preg_replace('/[^[:digit:]]/','',$this->CnpjEmpresa) . '.pfx';

        if ( ! file_exists( $this->certName )){
            echo "Certificado DIGITAL nao existe na pasta";
        }
        
        //monta o path completo com o nome da chave privada
        $this->priKEY 	= 'Franquias/notafiscal/'. preg_replace('/[^[:digit:]]/','',$this->CnpjEmpresa) . '_priKEY.pem';
        //monta o path completo com o nome da chave prublica
        $this->pubKEY 	= 'Franquias/notafiscal/'. preg_replace('/[^[:digit:]]/','',$this->CnpjEmpresa) . '_pubKEY.pem';
        //monta o path completo com o nome do certificado
        //(chave publica e privada) em formato pem
        $this->certKEY 	= 'Franquias/notafiscal/'. preg_replace('/[^[:digit:]]/','',$this->CnpjEmpresa) . '_certKEY.pem';
        
        //verificar se o nome do certificado e o path foram carregados
        //nas variaveis da classe
        if ($this->certName == ''):
            throw new Exception( __LINE__ . ' - Um certificado deve ser passado para a classe !!!' );
        endif;

        //monta o caminho completo ate o certificado pfx
        $pCert = $this->certName;
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
                echo '999 - Impossivel gravar no diretorio!!! Permissao negada !!!';
                die;
            endif;
        endif;

        // die(print_r($x509certdata,true));
        
        //verifica se arquivo com a chave publica ja existe
        if(file_exists($this->pubKEY)):
            //se existir verificar se e o mesmo atualmente instalado
            $conteudo = file_get_contents($this->pubKEY);
            //comparar os primeiros 100 digitos
            if ( !substr($conteudo,0,100) == substr($x509certdata['cert'],0,100) ):
                //se diferentes gravar o novo
                file_put_contents($this->pubKEY,$x509certdata['cert']);
                //salva o certificado completo no formato pem
                file_put_contents($this->certKEY,$x509certdata['pkey']."\n".$x509certdata['cert']);
               
                if( !empty($x509certdata['extracerts']) ):
	                $certificate = file_get_contents($this->certKEY);
	                $aCerts = $x509certdata['extracerts'];
	                foreach ($aCerts as $cert) {
	                	if (is_file($cert)) {
	                		$dados = file_get_contents($cert);
	                		$certificate .= "\n" . $dados;
	                	} else {
	                		$certificate .= "\n" . $cert;
	                	}
	                }
	                if (is_file($this->certKEY)) {
	                	file_put_contents($this->certKEY, $certificate);
	                }
                endif;                
            endif;
        else:
            //se nao existir salva a chave publica no formato pem para uso do
            //SOAP
            file_put_contents($this->pubKEY,$x509certdata['cert']);
            //salva o certificado completo no formato pem
            file_put_contents($this->certKEY,$x509certdata['pkey']."\n".$x509certdata['cert']);
            
            if( !empty($x509certdata['extracerts']) ):
	            $certificate = file_get_contents($this->certKEY);
	            $aCerts = $x509certdata['extracerts'];
	            foreach ($aCerts as $cert) {
	            	if (is_file($cert)) {
	            		$dados = file_get_contents($cert);
	            		$certificate .= "\n" . $dados;
	            	} else {
	            		$certificate .= "\n" . $cert;
	            	}
	            }
	            if (is_file($this->certKEY)) {
	            	file_put_contents($this->certKEY, $certificate);
	            }
            endif;
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
	
    private function __sendSOAPNFSe( $dados , $metodo )
    {
        use_soap_error_handler(TRUE);
        $soapver = SOAP_1_1;
        $options = array(
            'encoding'      => 'UTF-8',
            'verifypeer'    => FALSE,
            'verifyhost'    => FALSE,
            'soap_version'  => $soapver,
            'style'         => SOAP_DOCUMENT,
            'use'           => SOAP_LITERAL,
            'local_cert'    => $this->certKEY,
            'trace'         => TRUE,
            'compression'   => 0,
            'exceptions'    => TRUE,
            'cache_wsdl'    => WSDL_CACHE_NONE
        );

        //instancia a classe soap
        try
        {

            $oSoapClient = new NFSeSOAPClient( $this->URLwebservice[$this->aplicativo].'?wsdl' , $options );
            
            if( in_array($metodo, array('RecepcionarLoteRps','ConsultarSituacaoLoteRps','ConsultarNfsePorRps','ConsultarNfse','ConsultarLoteRps','CancelarNfse'))):
            	$params = array('metodo'=>$metodo,'xml'=>$dados);
            	$resp = $oSoapClient->__soapCall("RecepcionarXml", array($params));
            elseif( $metodo == 'ValidarXml'):
            	$params = array('metodo'=>$metodo,'xml'=>$dados);
            	$resp = $oSoapClient->__soapCall("ValidarXml", array($params));
            elseif( $metodo == 'CancelarLoteRps'):
              	$soapBody = new SoapVar($dados, XSD_ANYXML);
                $resp = $oSoapClient->__SoapCall('CancelarLoteRps', array($soapBody));
            else:
            	throw new Exception('Método '.strip_tags($metodo).' ainda não foi implementado !');
            endif;
           	
            $resposta = $oSoapClient->__getLastResponse();
            
        } catch (Exception $e) {
        	
            $this->soapDebug = $oSoapClient->__getLastRequestHeaders();
            $this->soapDebug .= "\n" . $oSoapClient->__getLastRequest();
            $this->soapDebug .= "\n" . $oSoapClient->__getLastResponseHeaders();
            $this->soapDebug .= "\n" . $oSoapClient->__getLastResponse();
            throw new Exception( __LINE__ . ' - ' . $e->__toString() ."\n".$this->soapDebug );
        }
        
        return $resposta;
    } //fim __sendSOAPNFSe
    
    private function __assinarRPS( $returned_content )
    {
        # declaracoes usadas no tratamento do xml
        $order = array("\r\n", "\n", "\r", "\t");
        $replace = '';
        $i = 0;
        $param = array();

        try
        {
            if( empty($this->InscricaoMunicipalEmpresa) ):
                echo '999 - Inscrição Municipal da empresa ' . $this->NomeFantasiaEmpresa . ' não foi informada !';
                die;
            endif;

            if( empty($this->CnpjEmpresa) ):
                echo '999 - CNPJ da empresa ' . $this->NomeFantasiaEmpresa . ' nao foi informado !';
                die;
            endif;
           

            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->preservWhiteSpace = false; // elimina espacos em branco
            $dom->formatOutput = false; // ignora formatacao
            $dom->loadXML( $returned_content , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );
            
            // Enable user error handling
            libxml_use_internal_errors(true);
            
            // $this->URLArqxsd = '/var/www/html/nfse/schemas/nfse_curitiba_pr.xsd';
            $this->URLArqxsd = '../nfse/schemas/nfse_curitiba_pr.xsd';
	
            // validando o xml de retorno
            if ( !$dom->schemaValidate ( $this->URLArqxsd ) ):
                echo '999; XML do rps inválido, verifique o arquivo  ! ['. $this->URLArqxsd .']<hr>'.$returned_content;
                exit;
            endif;

            $EnviarLoteRpsEnvio = $dom->getElementsByTagName ( "EnviarLoteRpsEnvio" )->item ( 0 );
            $LoteRps = $EnviarLoteRpsEnvio->getElementsByTagName ( "LoteRps" )->item ( 0 );
   
            $Cnpj = trim($LoteRps->getElementsByTagName ( "Cnpj" )->item ( 0 )->nodeValue);
            $InscricaoMunicipal = trim($LoteRps->getElementsByTagName ( "InscricaoMunicipal" )->item ( 0 )->nodeValue);
            $this->NumeroLoteRPS = trim($LoteRps->getElementsByTagName ( "NumeroLote" )->item ( 0 )->nodeValue);
            $QuantidadeRps = trim($LoteRps->getElementsByTagName ( "QuantidadeRps" )->item ( 0 )->nodeValue);

            $ListaRps = $LoteRps->getElementsByTagName ( "ListaRps" )->item ( 0 );            
            
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

            foreach ( $ListaRps->getElementsByTagName ( "Rps" ) as $Rps ):

                $InfRps = $Rps->getElementsByTagName ( "InfRps" )->item ( 0 );

                $IdentificacaoRps = $InfRps->getElementsByTagName ( "IdentificacaoRps" )->item ( 0 );

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
                $Signature->setAttribute('Id','Ass_'.$InfNfse);

                $Rps->appendChild($Signature);

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

            if( $node->getAttribute("id") == false ):
                echo '999;Id do LoteRps não foi encontrado no arquivo xml !';
                exit;
            endif;

            $idLoteRps = trim($node->getAttribute("id"));

            //extrai os dados da tag para uma string
            $dados = $node->C14N(false,false,NULL,NULL);

            //calcular o hash dos dados
            $hashValue = hash('sha1',$dados,true);

            //converte o valor para base64 para serem colocados no xml
            $digValue = base64_encode($hashValue);

            //monta a tag da assinatura digital
            $Signature = $dom->createElementNS($this->URLdsig,'Signature');

            $EnviarLoteRpsEnvio->appendChild($Signature);

            $Signature->setAttribute('Id','Ass_'.$idLoteRps);
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
            $NomeArquivo .= 'RPS_';
            $NomeArquivo .= $this->NumeroPedido;
            $NomeArquivo .= date('His');
            $NomeArquivo .= '.xml';

            /*
            # salvando o rps no banco de dados
            $sql = <<<SQLQUERY
            SELECT id
            FROM base_web_control.venda_notas_eletronicas
            WHERE id_venda = :numero_pedido AND tipo_nota = 'NFS'
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
                if ( trim($amb) == 'producao' ) $amb = 1;
                else $amb = 2;
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
            */
            
            $this->arquivoXMLRPS = $NomeArquivo;

            if( file_exists( $this->arquivoXMLRPS )):
                    @unlink( $this->arquivoXMLRPS );
            endif;
            
            if (!$handle = fopen( $this->arquivoXMLRPS ,'xb')):
                echo '999;Não foi possível abrir o arquivo RPS ,Lote nro x1.';
                exit;
            endif;

            if(!fputs($handle,$docxml)):
                fclose($handle);
                echo '999;Não foi possível salvar o arquivo RPS ,Lote nro x2.';
                exit;
            endif;

            fclose($handle);			

            # verificando a integridade do xml
            $dom->load( $this->arquivoXMLRPS );

            if ( !$dom->schemaValidate ( $this->URLArqxsd ) ):
                echo '999;XML do rps assinado inválido, verifique o arquivo  !';
                exit;
            endif;	

            $this->mensageAssinaturaXMLRPS = 'ok';

            return true;
        }
        catch (Exception $ae)
        {
            /* if( DB::connect()->inTransaction() ):
                DB::connect()->rollBack();
            endif; */
            if( isset($pkeyid) ):
                openssl_free_key($pkeyid);
            endif;
            throw new Exception($ae->getMessage());
        }		
    }
	
    public function ValidarXml()
    {
        try{

            if( !file_exists( $this->arquivoXMLRPS )):
                throw new Exception('999;Não foi possível encontrar o arquivo RPS no diretório de destino !');
            endif;

            $xml = file_get_contents($this->arquivoXMLRPS);
            
            $xml = str_replace( array("\r\n", "\n", "\r"), '', $xml );

            $result = $this->__sendSOAPNFSe( $xml , 'ValidarXml');

            $xmlRecDoc = new DOMDocument("1.0", "UTF-8");
            $xmlRecDoc->preservWhiteSpace = false;//elimina espacos em branco
            $xmlRecDoc->formatOutput = false;
            $xmlRecDoc->loadXML( $result , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );

            if( $xmlRecDoc->getElementsByTagName ( "ValidarXmlResponse" )->item ( 0 ) == false ):
                throw new Exception('999;Erro na validação na prefeitura : nó ValidarXmlResponse não encontrado no xml ');
            endif;

            $ValidarXmlResponse = $xmlRecDoc->getElementsByTagName ( "ValidarXmlResponse" )->item ( 0 );

            if( $ValidarXmlResponse->getElementsByTagName ( "ValidarXmlResult" )->item ( 0 ) == false ):
                throw new Exception('999;Erro na validação na prefeitura : nó ValidarXmlResult não encontrado no xml ');
            endif;

            $ValidarXmlResult = $ValidarXmlResponse->getElementsByTagName ( "ValidarXmlResult" )->item ( 0 );

            if( $ValidarXmlResult->getElementsByTagName ( "MensagemRetorno" )->item ( 0 ) == false ):
                throw new Exception('999;Erro na validação na prefeitura : nó MensagemRetorno não encontrado no xml ');
            endif;

            $MensagemRetorno = $ValidarXmlResult->getElementsByTagName ( "MensagemRetorno" )->item ( 0 );

            if( $MensagemRetorno->getElementsByTagName ( "tcMensagemRetorno" )->item ( 0 ) == false ):
                throw new Exception('999;Erro na validação na prefeitura : nó tcMensagemRetorno não encontrado no xml ');
            endif;

            $tcMensagemRetorno = $MensagemRetorno->getElementsByTagName ( "tcMensagemRetorno" )->item ( 0 );

            if( $tcMensagemRetorno->getElementsByTagName ( "Codigo" )->item ( 0 ) == false ):
                throw new Exception('999;Erro na validação na prefeitura : nó Codigo não encontrado no xml ');
            endif;

            if ( $tcMensagemRetorno->getElementsByTagName ( "Codigo" )->item ( 0 )->nodeValue == 'I1' ):
            	$this->mensageValidacaoXMLRPS = utf8_encode(trim($xmlRecDoc->getElementsByTagName ( "Codigo" )->item ( 0 )->nodeValue));
            else:
                throw new Exception('999;Erro na validaçãodo XML na prefeitura :'. utf8_encode(trim($xmlRecDoc->getElementsByTagName ( "Mensagem" )->item ( 0 )->nodeValue)));
            endif;

        } catch(SoapFault $fault)
        {
            die('999;'.$fault->getMessage());
        }
        return true;
    }
	
    public function enviar()
    {
        try
        {
            
//            echo "<pre>";
//            print_r( $this );
//            die;
            
            if( empty( $this->NumeroLoteRPS )):
                echo '999 - NumeroLoteRPS não encontrado !';
                die;
            endif;

            if( !file_exists( $this->arquivoXMLRPS )):
                echo '999 - Não foi possível encontrar o arquivo RPS no diretório de destino !';
                die;
            endif;
        
            $xml = file_get_contents($this->arquivoXMLRPS);
            
            $xml = str_replace( array("\r\n", "\n", "\r"), '', $xml );
            
            $result = $this->__sendSOAPNFSe( $xml , 'RecepcionarLoteRps' );

            $xmlRecDoc = new DOMDocument("1.0", "UTF-8");
            $xmlRecDoc->preservWhiteSpace = false;//elimina espacos em branco
            $xmlRecDoc->formatOutput = false;
            $xmlRecDoc->loadXML( $result , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );

            if( $xmlRecDoc->getElementsByTagName ( "RecepcionarXmlResponse" )->item ( 0 ) == false ):
                echo '999;Erro na validação na prefeitura : nó RecepcionarXmlResponse não encontrado no xml ';
                die;
            endif;

            $RecepcionarXmlResponse = $xmlRecDoc->getElementsByTagName ( "RecepcionarXmlResponse" )->item ( 0 );
            
            $RecepcionarXmlResult = html_entity_decode($RecepcionarXmlResponse->getElementsByTagName ( "RecepcionarXmlResult" )->item ( 0 )->nodeValue);

            preg_match_all ( '/<\?xml(.*?)\?>/is', $RecepcionarXmlResult , $matches );
            if (! empty ( $matches [0] )):
                $RecepcionarXmlResult = str_replace ( $matches [0], '<?xml version="1.0" encoding="UTF-8"?>', $RecepcionarXmlResult );
            endif;
	
            return $RecepcionarXmlResult;

        } catch(SoapFault $fault)
        {
            die($fault->getMessage());
        }
    }

    public function CancelarLoteRps()
    {
        try{
            
            if( empty($this->CnpjEmpresa) ):
                throw new Exception('999;CNPJ da empresa ' . $this->NomeFantasiaEmpresa . ' nao foi informado !');
            endif;

            if( !Validacoes::isValidCNPJ($this->CnpjEmpresa) ):
                throw new Exception('999;' . Validacoes::$errorMessage);
            endif;

            $this->__loadDadosEmpresa();
            $this->__loadCerts();

            if( empty($this->InscricaoMunicipalEmpresa) ):
                throw new Exception('999;Inscrição Municipal da empresa ' . $this->NomeFantasiaEmpresa . ' não foi informada !');
            endif;

            // segundo regra no site pbh quando IM terminar com X , acrescentar zero a esquerda
            if( !preg_match("/^([0-9]{7}([0-9]|[X]{1})?)$/", $this->InscricaoMunicipalEmpresa) ):
                throw new Exception('999;Inscrição Municipal [' . $this->InscricaoMunicipalEmpresa . '] da empresa ' . $this->NomeFantasiaEmpresa . ' não é válida !');
            endif;

            if( empty($this->rpsPROTOCOLO) ):
                throw new Exception('999;Numero do protocolo nao foi informado !');
            endif;

            if( !is_numeric($this->rpsPROTOCOLO) ):
                throw new Exception('999;Numero do protocolo invalido !');
            endif;
            
            $xmlDoc = $this->criarXML( "CancelarLoteRps" , false );

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

            $result = $this->__sendSOAPNFSe( $xml , 'CancelarLoteRps' );
        	
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
                throw new Exception ( '999;'.$PDOExceptione->getMessage() );
            } catch ( Exception $ae ) {
                throw new Exception ( '999;'.$ae->getMessage() );
            }
            return $result;

        } catch(SoapFault $fault)
        {
            die($fault->getMessage());
        }
        return true;		
    }

    public function ConsultarLoteRps()
    {
        try
        {

            if( empty($this->CnpjEmpresa) ):
                throw new Exception('999;CNPJ da empresa ' . $this->NomeFantasiaEmpresa . ' nao foi informado !');
            endif;

            if( !Validacoes::isValidCNPJ($this->CnpjEmpresa) ):
                throw new Exception('999;'.Validacoes::$errorMessage);
            endif;

            $this->__loadDadosEmpresa();
            $this->__loadCerts();

            if( empty($this->InscricaoMunicipalEmpresa) ):
                throw new Exception('999;Inscrição Municipal da empresa ' . $this->NomeFantasiaEmpresa . ' não foi informada !');
            endif;

            /*
            // segundo regra no site pbh quando IM terminar com X , acrescentar zero a esquerda
            if( !preg_match("/^([0-9]{7}([0-9]|[X]{1})?)$/", $this->InscricaoMunicipalEmpresa) ):
                throw new Exception('999;Inscrição Municipal [' . $this->InscricaoMunicipalEmpresa . '] da empresa ' . $this->NomeFantasiaEmpresa . ' não é válida !');
            endif;
            */
            
            if( empty($this->rpsPROTOCOLO) ):
                throw new Exception('999;Numero do protocolo nao foi informado !');
            endif;

            if( !is_numeric($this->rpsPROTOCOLO) ):
                throw new Exception('Numero do protocolo invalido !');
            endif;

            $xmlDoc = $this->criarXML( "ConsultarLoteRpsEnvio" , false );

            $ConsultarLoteRpsEnvio = $xmlDoc->getElementsByTagName ( "ConsultarLoteRpsEnvio" )->item ( 0 );

            $Prestador = $xmlDoc->createElement("Prestador");
            $Protocolo = $xmlDoc->createElement("Protocolo" , $this->rpsPROTOCOLO );
            $ConsultarLoteRpsEnvio->appendChild($Prestador);
            $ConsultarLoteRpsEnvio->appendChild($Protocolo);

            $Cnpj = $xmlDoc->createElement('Cnpj' , $this->CnpjEmpresa );
            $InscricaoMunicipal = $xmlDoc->createElement('InscricaoMunicipal' , $this->InscricaoMunicipalEmpresa );

            $Prestador->appendChild($Cnpj);
            $Prestador->appendChild($InscricaoMunicipal);

            $xml = str_replace( array("\r\n", "\n", "\r"), '', $xmlDoc->saveXML($xmlDoc->documentElement) );

            $result = $this->__sendSOAPNFSe( $xml , 'ConsultarLoteRps' );
            
            // remove o node element e body
            $doc                     = new DOMDocument('1.0', 'UTF-8');
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput       = true;
            $doc->loadXML( $result );
            $code = $doc->documentElement->getElementsByTagName('*')->item(0)->getElementsByTagName('*')->item(0);
            $doc->replaceChild($code, $doc->documentElement);
            $result = $doc->saveXML();
            
            $xmlRecDoc = new DOMDocument("1.0", "UTF-8");
            $xmlRecDoc->preservWhiteSpace = false;//elimina espacos em branco
            $xmlRecDoc->formatOutput = false;
            $xmlRecDoc->loadXML( $result , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );
                        
            $result =  $xmlRecDoc -> textContent;
            
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
                throw new Exception ( '999;'.$PDOExceptione->getMessage() );
            } catch ( Exception $ae ) {
                throw new Exception ( '999;'.$ae->getMessage() );
            }

            return $result;

        } catch(SoapFault $fault)
        {
            die($fault->getMessage());
        }
        return true;
    }

    public function ConsultarSituacaoLoteRps()
    {
        try
        {
            
            
            if( empty($this->CnpjEmpresa) ):
                echo '999 - CNPJ da empresa ' . $this->NomeFantasiaEmpresa . ' nao foi informado !';
                die;
            endif;

            $this->__loadDadosEmpresa();
            
            $this->__loadCerts();

            
            if( empty($this->InscricaoMunicipalEmpresa) ):
                echo '999 - Inscrição Municipal da empresa ' . $this->NomeFantasiaEmpresa . ' não foi informada !';
            endif;

            if( empty($this->rpsPROTOCOLO) ):
                echo '999;Numero do protocolo nao foi informado !';
                die;
            endif;

            $xmlDoc = $this->criarXML( "ConsultarSituacaoLoteRpsEnvio" , false );

            $ConsultarSituacaoLoteRpsEnvio = $xmlDoc->getElementsByTagName ( "ConsultarSituacaoLoteRpsEnvio" )->item ( 0 );
            
            $Prestador = $xmlDoc->createElement("Prestador");
            $Protocolo = $xmlDoc->createElement("Protocolo" , $this->rpsPROTOCOLO );
            $ConsultarSituacaoLoteRpsEnvio->appendChild($Prestador);
            $ConsultarSituacaoLoteRpsEnvio->appendChild($Protocolo);

            $Cnpj = $xmlDoc->createElement('Cnpj' , $this->CnpjEmpresa );
            $InscricaoMunicipal = $xmlDoc->createElement('InscricaoMunicipal' , $this->InscricaoMunicipalEmpresa );

            $Prestador->appendChild($Cnpj);
            $Prestador->appendChild($InscricaoMunicipal);
            
            $xml = str_replace( array("\r\n", "\n", "\r"), '', $xmlDoc->saveXML($xmlDoc->documentElement) );

            $result = $this->__sendSOAPNFSe( $xml , 'ConsultarSituacaoLoteRps' );
            
            // remove o node element e body
            $doc                     = new DOMDocument('1.0', 'UTF-8');
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput       = true;
            $doc->loadXML( $result );
            $code = $doc->documentElement->getElementsByTagName('*')->item(0)->getElementsByTagName('*')->item(0);
            $doc->replaceChild($code, $doc->documentElement);
            $result = $doc->saveXML();

            // $result = html_entity_decode($result);
            
            $xmlRecDoc = new DOMDocument("1.0", "UTF-8");
            $xmlRecDoc->preservWhiteSpace = false;//elimina espacos em branco
            $xmlRecDoc->formatOutput = false;
            $xmlRecDoc->loadXML( $result , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );
                        
            $result =  $xmlRecDoc -> textContent;
            
            // remove default:
            $result = preg_replace('/(<\s*)\w+:/','$1',$result);

            return $result;

        } catch(SoapFault $fault)
        {
            die($fault->getMessage());
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