<?php
/**
 * @author Luciano Mancini 
 * @version 20160505_01
 * @copyright 
 * @package nfse
 * @name rps_londrina_pr.class.php
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

    # chave de acesso ao certificado
    private $keyPass;

    //private $URLxmlnsCall ="http://www.e-governeapps2.com.br/";
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
                ci.issqn_cmc, 
                ci.issqn_cpf, 
                ci.issqn_senha_cmc_cpf
        FROM cs2.cadastro c
        LEFT JOIN base_web_control.cadastro_imposto_padrao ci ON c.codLoja = ci.id_cadastro
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
            echo "999;PRESTADOR COM CADASTRO DE INSCRICAO MUNICIPAL NAO INFORMADO";
            exit;
        endif;

        if( strtoupper(trim($rs[0]->inscr_municipal)) == strtoupper('ISENTO')):
            echo "999;PRESTADOR COM CADASTRO DE INSCRICAO MUNICIPAL INVALIDO";
            exit;
        endif;

        if( empty($rs[0]->keypass) ):
            echo "999;SENHA DO CMC NAO INFORMADO";
            exit;
        endif;

        $this->InscricaoMunicipalEmpresa =  $rs[0]->inscr_municipal;
        $this->InscricaoEstadualEmpresa =  $rs[0]->inscr_estadual;
        $this->RazaoSocialEmpresa = $rs[0]->razao_social;
        $this->NomeFantasiaEmpresa = $rs[0]->nome_fantasia; 
        $this->AliquotaISSEmpresa =  $rs[0]->aliquota_iss;
        $this->keyPass =  $rs[0]->keypass;
        $this->cmc =  $rs[0]->issqn_cmc;
        $this->cpf =  $rs[0]->issqn_cpf;
        $this->senha_cmc_cpf =  $rs[0]->issqn_senha_cmc_cpf;
        return true;
    }
	
    private function __loadDadosNota()
    {
        
        if( empty( $this->rpsPROTOCOLO ) ):
            echo "999;NOTA NAO ENCONTRADA";
            exit;
        endif;
        
        $sql = <<<SQLQUERY
        SELECT
                xml
        FROM base_web_control.venda_notas_eletronicas
        WHERE protocolo = :protocolo
SQLQUERY;

        try
        {
            $stmt = DB::connect()->Prepare ( $sql );
            $stmt->bindValue(':protocolo', $this->rpsPROTOCOLO , PDO::PARAM_INT);
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
            throw new Exception ( __LINE__ . ' - Nenhuma nota encontrada com o codigo ' . strip_tags($this->rpsPROTOCOLO) . '!' );
        endif;
        $this->xmlNota =  $rs[0]->xml;
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
            throw new Exception( __LINE__ . ' - O certificado nao pode ser lido!! Provavelmente senha invÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¡lida, corrompido ou com formato invalido !!!');
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
	
    public function startCurl( $dados , $metodo )
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
            
            if( $this->aplicativo == 'homologacao' ):
                $parametros = array(
                        'soapaction: "http://testeiss.londrina.pr.gov.br/ws/v1_03#'.$metodo.'"',
                        'Host: testeiss.londrina.pr.gov.br',
                        'Content-Type: text/xml; charset=ISO-8859-1',
                        "Content-length: $tamanho");
            else:
                $parametros = array(
                        'soapaction: "http://iss.londrina.pr.gov.br/ws/v1_03#'.$metodo.'"',
                        'Host: iss.londrina.pr.gov.br',
                        'Content-Type: text/xml; charset=ISO-8859-1',
                        "Content-length: $tamanho");
            endif;
            
            $oCurl = curl_init();
            curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 10 );
            curl_setopt($oCurl, CURLOPT_URL,$this->URLwebservice[$this->aplicativo]);
            #curl_setopt($oCurl, CURLOPT_PORT, 443);
            curl_setopt($oCurl, CURLOPT_VERBOSE, 1);
            curl_setopt($oCurl, CURLOPT_HEADER, 1); //retorna o cabecalho de resposta
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

            $info = curl_getinfo($oCurl); //informacoes da conexao

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
            
            // die($this->soapDebug);
            
            if ($xml === false || $posX === false):
                //nao houve retorno
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

        try
        {
            if( empty($this->InscricaoMunicipalEmpresa) ):
                throw new Exception( '[' . __FUNCTION__ . '] - Inscricao Municipal da empresa ' . $this->NomeFantasiaEmpresa . ' nao foi informada !');
            endif;

            // segundo regra no site pbh quando IM terminar com X , acrescentar zero a esquerda
            if( !preg_match("/^([0-9]{10}([0-9]|[X]{1})?)$/", $this->InscricaoMunicipalEmpresa) ):
                #	throw new Exception( '[' . __FUNCTION__ . '] - Inscricao Municipal ' . $this->InscricaoMunicipalEmpresa . ' da empresa ' . $this->NomeFantasiaEmpresa . ' invalida !');
            endif;

            if( empty($this->CnpjEmpresa) ):
                throw new Exception( '[' . __FUNCTION__ . '] - CNPJ da empresa ' . $this->NomeFantasiaEmpresa . ' nao foi informado !');	
            endif;

            if( !Validacoes::isValidCNPJ($this->CnpjEmpresa) ):
                throw new Exception( '[' . __FUNCTION__ . '] - ' . Validacoes::$errorMessage );
            endif;
			
            $matches = array ();
            preg_match_all ( '/<\?xml(.*?)\?>/is', $returned_content , $matches );
            if (! empty ( $matches [0] )):
                $returned_content = str_replace ( $matches [0], '', $returned_content );
            else:
                throw new Exception ( __LINE__ . ' - Erro resposta XML !' );
            endif;

            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->preservWhiteSpace = false; // elimina espacos em branco
            $dom->formatOutput = false; // ignora formatacao
            $dom->loadXML( $returned_content , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );

            // Enable user error handling
            libxml_use_internal_errors(true);
	
            $DescricaoRps = $dom->getElementsByTagName ( "DescricaoRps" )->item ( 0 );

            $Cnpj = trim($DescricaoRps->getElementsByTagName ( "cnpj" )->item ( 0 )->nodeValue);
            $InscricaoMunicipal = trim($DescricaoRps->getElementsByTagName ( "tomador_ie" )->item ( 0 )->nodeValue);
            $this->NumeroLoteRPS = trim($DescricaoRps->getElementsByTagName ( "rps_num" )->item ( 0 )->nodeValue);
            $QuantidadeRps = 1;


            // compara o cnpj do prestador com o da rps
            if( $Cnpj != $this->CnpjEmpresa ):
                echo '999;CNPJ informado no RPS nao confere com o CNPJ informado na consulta !';
                exit;
            endif;
            
            $LoteRps = $DescricaoRps->getElementsByTagName ( "rps_num" )->item ( 0 );

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
                
                // 1 - Homologacao
                // 2 - Producao
                
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

            if (!$handle = fopen( $this->arquivoXMLRPS ,'xb')):
                echo '999;Nao foi possivel abrir o arquivo RPS ,Lote nro.';
                exit;
            endif;

            if(!fputs($handle,$docxml)):
                fclose($handle);
                echo '999;Nao foi possivel salvar o arquivo RPS ,Lote nro.';
                exit;
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
                echo '999;Nao foi possivel encontrar o arquivo RPS no diretorio de destino !';
                exit;
            endif;

            $docxml = file_get_contents($this->arquivoXMLRPS);

            $xmlDoc = $this->criarXML( "ValidarXml" , true );

            $ValidarXml = $xmlDoc->getElementsByTagName ( "ValidarXml" )->item ( 0 );

            $xml = $xmlDoc->createElement('xml');
            $xml->appendChild( $xmlDoc->createCDATASection($docxml));

            $ValidarXml->appendChild($xml);

            $xml = $xmlDoc->saveXML($xmlDoc->documentElement);

            $xml = str_replace( array("\r\n", "\n", "\r"), '', $xml );

            $result = $this->startCurl( $xml , 'ValidarXml' );

            $xmlRecDoc = new DOMDocument("1.0", "UTF-8");
            $xmlRecDoc->preservWhiteSpace = false;//elimina espacos em branco
            $xmlRecDoc->formatOutput = false;
            $xmlRecDoc->loadXML( $result , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );

            if( $xmlRecDoc->getElementsByTagName ( "ValidarXmlResponse" )->item ( 0 ) == false ):
                echo '999;Erro na validacao na prefeitura : no ValidarXmlResponse nao encontrado no xml ';
                exit;
            endif;

            $ValidarXmlResponse = $xmlRecDoc->getElementsByTagName ( "ValidarXmlResponse" )->item ( 0 );

            if( $ValidarXmlResponse->getElementsByTagName ( "ValidarXmlResult" )->item ( 0 ) == false ):
                echo '999;Erro na validacao na prefeitura : no ValidarXmlResult nao encontrado no xml ';
                exit;
            endif;

            $ValidarXmlResult = $ValidarXmlResponse->getElementsByTagName ( "ValidarXmlResult" )->item ( 0 );

            if( $ValidarXmlResult->getElementsByTagName ( "MensagemRetorno" )->item ( 0 ) == false ):
                echo '999;Erro na validacao na prefeitura : no MensagemRetorno nao encontrado no xml ';
                exit;
            endif;

            $MensagemRetorno = $ValidarXmlResult->getElementsByTagName ( "MensagemRetorno" )->item ( 0 );

            if( $MensagemRetorno->getElementsByTagName ( "tcMensagemRetorno" )->item ( 0 ) == false ):
                echo '999;Erro na validacao na prefeitura : no tcMensagemRetorno nao encontrado no xml ';
                exit;
            endif;

            $tcMensagemRetorno = $MensagemRetorno->getElementsByTagName ( "tcMensagemRetorno" )->item ( 0 );

            if( $tcMensagemRetorno->getElementsByTagName ( "Codigo" )->item ( 0 ) == false ):
                echo '999;Erro na validacao na prefeitura : no Codigo nao encontrado no xml ';
                exit;
            endif;

            if ( $tcMensagemRetorno->getElementsByTagName ( "Codigo" )->item ( 0 )->nodeValue == 'I1' ):
                    $this->mensageValidacaoXMLRPS = utf8_encode(trim($xmlRecDoc->getElementsByTagName ( "Codigo" )->item ( 0 )->nodeValue));
            else:
                echo '999;Erro na validacao na prefeitura :'. utf8_encode(trim($xmlRecDoc->getElementsByTagName ( "Mensagem" )->item ( 0 )->nodeValue));		
                exit;
            endif;

        } catch(SoapFault $fault)
        {
            throw new Exception($fault->getMessage());
        }
        return true;
    }
	
    public function enviar()
    {
        try{
           
            if( empty( $this->NumeroLoteRPS )):
                echo '999;NumeroLoteRPS nao encontrado !';
                exit;
            endif;

            if( !file_exists( $this->arquivoXMLRPS )):
                echo '999;Nao foi possivel encontrar o arquivo RPS no diretorio de destino !' ;
                exit;
            endif;

            $docxml = file_get_contents($this->arquivoXMLRPS);

            // FUNCAO CRIARXML

            # cria o documento no DOM
            $xmlDoc = new DOMDocument("1.0", "UTF-8");
            #gerar o codigo
            $xmlDoc->preservWhiteSpace = false;//elimina espacos em branco
            $xmlDoc->formatOutput = false;
            $Envelope = $xmlDoc->createElement("soap12:Envelope");
            $Envelope->setAttribute('xmlns:xsi', $this->URLxsi);
            $Envelope->setAttribute('xmlns:xsd', $this->URLxsd);
            $Envelope->setAttribute('xmlns:soap12', $this->URLsoap);
            $xmlDoc->appendChild($Envelope);
            
            $Body = $xmlDoc->createElement("soap12:Body");
            $Envelope->appendChild($Body);
   
            $node = $xmlDoc->createElement( 'GerarNota' );
            $node->setAttribute('xmlns', $this->URLxmlnsCall );
  
            $node->appendChild( $xmlDoc->createCDATASection($docxml));
            $Body->appendChild($node);
            
            $xml = $xmlDoc->saveXML($xmlDoc->documentElement);
            
            $xml = str_replace( array("\r\n", "\n", "\r"), '', $xml );
            $xml = str_replace( array("<![CDATA[", "]]>"), '', $xml );

            $xml = '<?xml version="1.0" encoding="ISO-8859-1"?>'.$xml;

            $xml = utf8_decode($xml);
            $result = $this->startCurl( $xml , 'GerarNota' );
            
            $xmlRecDoc = new DOMDocument("1.0", "UTF-8");
            $xmlRecDoc->preservWhiteSpace = false;//elimina espacos em branco
            $xmlRecDoc->formatOutput = false;
            $xmlRecDoc->loadXML( $result , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );

            if( $xmlRecDoc->getElementsByTagName ( "GerarNotaResponse" )->item ( 0 ) == false ):
                echo '999;Erro na validacao na prefeitura : nó GerarNotaResponse nao encontrado no xml ';
                exit;
            endif;

            $GerarNotaResponse = $xmlRecDoc->getElementsByTagName ( "GerarNotaResponse" )->item ( 0 );
            $RetornoNota = $xmlRecDoc->getElementsByTagName( "RetornoNota" )->item ( 0 );

            $Nota = $RetornoNota->getElementsByTagName ( "Nota" )->item ( 0 )->nodeValue;
            $erro = '';
            
            if ( $Nota == 0 ){
                
                // Apresentou erro no processo da Nota
                $Mensagens = $GerarNotaResponse->getElementsByTagName ( "Mensagens" )->item ( 0 );
                foreach ( $Mensagens->getElementsByTagName ( "item" ) as $item ):
                    $erro .= $item->getElementsByTagName ( "id" )->item ( 0 )->nodeValue.' - '.$item->getElementsByTagName ( "DescricaoErro" )->item ( 0 )->nodeValue.'<br>';
                endforeach;
                
                echo '999;'.$erro;
                exit;
                                
            }else{
                
                // NOTA GERADA COM SUCESSO
                $autenticidade = $RetornoNota->getElementsByTagName ( "autenticidade" )->item ( 0 )->nodeValue;
                $link_baixar_nota = $RetornoNota->getElementsByTagName ( "LinkImpressao" )->item ( 0 )->nodeValue;
                
                $pos_ini = strpos($link_baixar_nota,'hash=')+5;
                $protocolo = substr($link_baixar_nota,$pos_ini,8);
                
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
                
                $sql = "UPDATE base_web_control.venda_notas_eletronicas
                            SET
                                  tipo_nota    = :tipo_nota
                                , status       = :status
                                , numero_nota  = :numero_nota
                                , ambiente_nf  = :ambiente_nf
                                , LINK_NFS     = :linkNfs
                                , LOTE         = :lote
                                , ARQUIVO      = :arquivo
                                , RETORNO      = :retorno
                                , protocolo    = :protocolo
                        WHERE
                            id_venda = :id_venda";

                $stmt = DB::connect()->Prepare ( $sql );

                $stmt->bindValue(':tipo_nota','NFS', PDO::PARAM_STR);
                $stmt->bindValue(':status', '5', PDO::PARAM_STR);
                $stmt->bindValue(':numero_nota', $this->NumeroLoteRPS, PDO::PARAM_INT);
                $stmt->bindValue(':ambiente_nf', '1' , PDO::PARAM_INT);
                $stmt->bindValue(':linkNfs', $link_baixar_nota , PDO::PARAM_STR);
                $stmt->bindValue(':lote', $this->NumeroLoteRPS, PDO::PARAM_STR);
                $stmt->bindValue(':arquivo', $NomeArquivo , PDO::PARAM_STR);
                $stmt->bindValue(':retorno', $RecepcionarXmlResult , PDO::PARAM_STR);
                $stmt->bindValue(':protocolo', $protocolo , PDO::PARAM_STR);
                $stmt->bindValue(':id_venda', $this->NumeroPedido, PDO::PARAM_INT);
                $stmt->Execute();
                $stmt->closeCursor();
                unset($stmt);
                
                return 'OKNOTA';
                
            }

            return $RetornoNota;

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
            $this->__loadDadosNota();

            if( empty($this->InscricaoMunicipalEmpresa) ):
                echo '999;Inscricao Municipal da empresa ' . $this->NomeFantasiaEmpresa . ' nao foi informada !';
                exit;
            endif;

            // segundo regra no site pbh quando IM terminar com X , acrescentar zero a esquerda
            if( !preg_match("/^([0-9]{7}([0-9]|[X]{1})?)$/", $this->InscricaoMunicipalEmpresa) ):
                echo '999;Inscricao Municipal [' . $this->InscricaoMunicipalEmpresa . '] da empresa ' . $this->NomeFantasiaEmpresa . ' invalida !';
                exit;
            endif;

            if( empty($this->rpsPROTOCOLO) ):
                echo '999;Numero do protocolo nao foi informado !';
                exit;
            endif;

            $ccm = $this->cmc;
            $cnpj = $this->CnpjEmpresa;
            $cpf = $this->cpf;
            $senha = $this->senha_cmc_cpf;
            $xmlNota = $this->xmlNota;
            
            // Lendo dados do XML da nota gerada e gravada no BD.
            
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->preservWhiteSpace = false; // elimina espacos em branco
            $dom->formatOutput = false; // ignora formatacao
            $dom->loadXML( $xmlNota , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );

            $DescricaoRps = $dom->getElementsByTagName ( "DescricaoRps" )->item ( 0 );

            $rps_dia = $DescricaoRps->getElementsByTagName ( "rps_dia" )->item ( 0 )->nodeValue;
            $rps_mes = $DescricaoRps->getElementsByTagName ( "rps_mes" )->item ( 0 )->nodeValue;
            $rps_ano = $DescricaoRps->getElementsByTagName ( "rps_ano" )->item ( 0 )->nodeValue;
            $numeroNota = $DescricaoRps->getElementsByTagName ( "rps_num" )->item ( 0 )->nodeValue;
            $numeroNota *= 1;
            
            $docxml = '<?xml version="1.0" encoding="ISO-8859-1"?>';
            $docxml.= '<soap12:Envelope xmlns:soap12="'.$this->URLsoap.'" xmlns:xsi="'.$this->URLxsi.'" xmlns:xsd="'.$this->URLxsd.'">';
            $docxml.= '<soap12:Header/>';
            $docxml.= '<soap12:Body>';
            $docxml.= '<CancelarNota xmlns="'.$this->URLsoap.'">';
            $docxml.= "<DescricaoCancelaNota><ccm>$ccm</ccm><cnpj>$cnpj</cnpj><cpf>$cpf</cpf><senha>$senha</senha><nota>$numeroNota</nota><cod_cancelamento>2</cod_cancelamento></DescricaoCancelaNota>";      
            $docxml.= '</CancelarNota>';
            $docxml.= '</soap12:Body>';
            $docxml.= '</soap12:Envelope>';
               
            $result = $this->startCurl( $docxml , 'CancelarNota' );
            
            // lendo o retorno da prefeitura
            $doc                     = new DOMDocument('1.0', 'ISO-8859-1');
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput       = true;
            $doc->loadXML( $result );
          
            if( $doc->getElementsByTagName('CancelarNotaResponse') == false ):
            	die( '999;Nó CancelarNotaResponse nao encontrado !');
            endif;
            
            $CancelarNotaResponse = $doc->getElementsByTagName('CancelarNotaResponse')->item(0);
            
            if( $CancelarNotaResponse->getElementsByTagName('RetornoNota') == false ):
            	die( '999;Nó RetornoNota nao encontrado !' );
            endif;
            
            $RetornoNota = $CancelarNotaResponse->getElementsByTagName('RetornoNota')->item(0);

            $Nota = $RetornoNota->getElementsByTagName ( "Nota" )->item ( 0 )->nodeValue;
            $erro = '';
            
            if ( $Nota == 0 ){
                
                // Apresentou erro no processo DE CANCELAMENTO da Nota
                $Mensagens = $CancelarNotaResponse->getElementsByTagName ( "Mensagens" )->item ( 0 );
                foreach ( $Mensagens->getElementsByTagName ( "item" ) as $item ):
                    $erro .= $item->getElementsByTagName ( "id" )->item ( 0 )->nodeValue.' - '.$item->getElementsByTagName ( "DescricaoErro" )->item ( 0 )->nodeValue.'<br>';
                endforeach;
                
                echo '999;'.$erro;
                exit;
                                
            }else{
                
            }
            
            print_r( $result );
            die;
            
            
            if( $doc->getElementsByTagName('EspelhoNfse') == false ):
            	die( '999;nó EspelhoNfse nao encontrado !' );
            endif;
            
            $EspelhoNfse = $doc->getElementsByTagName('EspelhoNfse')->item(0);

            if( $EspelhoNfse->getElementsByTagName('Nfse') == false ):
            	die( '999;nó Nfse nao encontrado !' );
            endif;
            
            $Nfse = $EspelhoNfse->getElementsByTagName('Nfse')->item(0);
            
            if( $Nfse->getElementsByTagName('IdentificacaoNfse') == false ):
            	die( '999;nó IdentificacaoNfse nao encontrado !' );
            endif;
            
            $IdentificacaoNfse = $Nfse->getElementsByTagName('IdentificacaoNfse')->item(0);
            
            $result = array();

            $result['CodigoVerificacao'] = $IdentificacaoNfse->getElementsByTagName('CodigoVerificacao')==true?$IdentificacaoNfse->getElementsByTagName('CodigoVerificacao')->item(0)->nodeValue:'';
            $result['LinkImpressao'] = $IdentificacaoNfse->getElementsByTagName('LinkImpressao')==true?$IdentificacaoNfse->getElementsByTagName('LinkImpressao')->item(0)->nodeValue:'';
            $result['DataEmissao'] = $IdentificacaoNfse->getElementsByTagName('DataEmissao')==true?$IdentificacaoNfse->getElementsByTagName('DataEmissao')->item(0)->nodeValue:'';
            $result['Competencia'] = $IdentificacaoNfse->getElementsByTagName('Competencia')==true?$IdentificacaoNfse->getElementsByTagName('Competencia')->item(0)->nodeValue:'';
            $result['StatusNfse'] = $IdentificacaoNfse->getElementsByTagName('StatusNfse')==true?$IdentificacaoNfse->getElementsByTagName('StatusNfse')->item(0)->nodeValue:'';
            $DadosNfse = $Nfse->getElementsByTagName('DadosNfse')->item(0);
            $result['RpsNumero'] = $DadosNfse->getElementsByTagName('RpsNumero')==true?$DadosNfse->getElementsByTagName('RpsNumero')->item(0)->nodeValue:'';

            $StatusNfse = $IdentificacaoNfse->getElementsByTagName('StatusNfse')==true?$IdentificacaoNfse->getElementsByTagName('StatusNfse')->item(0)->nodeValue:'';
            $LinkImpressao = $IdentificacaoNfse->getElementsByTagName('LinkImpressao')==true?$IdentificacaoNfse->getElementsByTagName('LinkImpressao')->item(0)->nodeValue:'';
            $RpsNumero = $DadosNfse->getElementsByTagName('RpsNumero')==true?$DadosNfse->getElementsByTagName('RpsNumero')->item(0)->nodeValue:'';
            
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
                echo '999;Inscricao Municipal da empresa ' . $this->NomeFantasiaEmpresa . ' nao foi informada !';
                exit;
            endif;

            // segundo regra no site pbh quando IM terminar com X , acrescentar zero a esquerda
            if( !preg_match("/^([0-9]{7}([0-9]|[X]{1})?)$/", $this->InscricaoMunicipalEmpresa) ):
                echo '999;Inscricao Municipal [' . $this->InscricaoMunicipalEmpresa . '] da empresa ' . $this->NomeFantasiaEmpresa . ' invalida !';
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
            
            $this->__loadDadosNota();

            if( empty($this->InscricaoMunicipalEmpresa) ):
                echo '999;Inscricao Municipal da empresa ' . $this->NomeFantasiaEmpresa . ' nao foi informada !';
                exit;
            endif;

            if( !preg_match("/^([0-9]{7}([0-9]|[X]{1})?)$/", $this->InscricaoMunicipalEmpresa) ):
                echo '999;Inscricao Municipal [' . $this->InscricaoMunicipalEmpresa . '] da empresa ' . $this->NomeFantasiaEmpresa . ' invalida !';
                exit;
            endif;

            if( empty($this->rpsPROTOCOLO) ):
                echo '999;Numero do protocolo nao foi informado !';
                exit;
            endif;

            $ccm = $this->cmc;
            $cnpj = $this->CnpjEmpresa;
            $cpf = $this->cpf;
            $senha = $this->senha_cmc_cpf;
            $xmlNota = $this->xmlNota;
            
            // Lendo dados do XML da nota gerada e gravada no BD.
            
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->preservWhiteSpace = false; // elimina espacos em branco
            $dom->formatOutput = false; // ignora formatacao
            $dom->loadXML( $xmlNota , LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );

            $DescricaoRps = $dom->getElementsByTagName ( "DescricaoRps" )->item ( 0 );

            $rps_dia = $DescricaoRps->getElementsByTagName ( "rps_dia" )->item ( 0 )->nodeValue;
            $rps_mes = $DescricaoRps->getElementsByTagName ( "rps_mes" )->item ( 0 )->nodeValue;
            $rps_ano = $DescricaoRps->getElementsByTagName ( "rps_ano" )->item ( 0 )->nodeValue;
            $numeroNota = $DescricaoRps->getElementsByTagName ( "rps_num" )->item ( 0 )->nodeValue;
            $numeroNota *=  1;
            
            $docxml = '<?xml version="1.0" encoding="ISO-8859-1"?>';
            $docxml.= '<soap12:Envelope xmlns:soap12="'.$this->URLsoap.'" xmlns:xsi="'.$this->URLxsi.'" xmlns:xsd="'.$this->URLxsd.'">';
            $docxml.= '<soap12:Header/>';
            $docxml.= '<soap12:Body>';
            $docxml.= '<ConsultarRpsServicoPrestado xmlns="'.$this->URLsoap.'">';
            $docxml.= "<ConsultarRpsServicoPrestadoEnvio><ccm>$ccm</ccm><cnpj>$cnpj</cnpj><cpf>$cpf</cpf><senha>$senha</senha><numero_rps>$numeroNota</numero_rps><dia_rps>$rps_dia</dia_rps><mes_rps>$rps_mes</mes_rps><ano_rps>$rps_ano</ano_rps></ConsultarRpsServicoPrestadoEnvio>";      
            $docxml.= '</ConsultarRpsServicoPrestado>';
            $docxml.= '</soap12:Body>';
            $docxml.= '</soap12:Envelope>';
                        
            $result = $this->startCurl( $docxml , 'ConsultarRpsServicoPrestado' );
            
            // lendo o retorno da prefeitura
            $doc                     = new DOMDocument('1.0', 'ISO-8859-1');
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput       = true;
            $doc->loadXML( $result );
          
            if( $doc->getElementsByTagName('ConsultarRpsServicoPrestadoResponse') == false ):
            	die( '999;nó ConsultarRpsServicoPrestadoResponse não encontrado !');
            endif;
            
            $ConsultarRpsServicoPrestadoResponse = $doc->getElementsByTagName('ConsultarRpsServicoPrestadoResponse')->item(0);
            
            if( $ConsultarRpsServicoPrestadoResponse->getElementsByTagName('RetornoNota') == false ):
            	die( '999;Nó RetornoNota não encontrado !' );
            endif;
            
            $RetornoNota = $ConsultarRpsServicoPrestadoResponse->getElementsByTagName('RetornoNota')->item(0)->nodeValue;
            // lendo a resposta do retorno
            $doc                     = new DOMDocument('1.0', 'ISO-8859-1');
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput       = true;
            $doc->loadXML( $RetornoNota );
            
            if( $doc->getElementsByTagName('EspelhoNfse') == false ):
            	die( '999;Nó EspelhoNfse não encontrado !' );
            endif;
            
            $EspelhoNfse = $doc->getElementsByTagName('EspelhoNfse')->item(0);

            if( $EspelhoNfse->getElementsByTagName('Nfse') == false ):
            	die( '999;Nó Nfse não encontrado !' );
            endif;
            
            $Nfse = $EspelhoNfse->getElementsByTagName('Nfse')->item(0);
            
            if( $Nfse->getElementsByTagName('IdentificacaoNfse') == false ):
            	die( '999;Nó IdentificacaoNfse não encontrado !' );
            endif;
            
            $IdentificacaoNfse = $Nfse->getElementsByTagName('IdentificacaoNfse')->item(0);
            
            $result = array();

            $result['CodigoVerificacao'] = $IdentificacaoNfse->getElementsByTagName('CodigoVerificacao')==true?$IdentificacaoNfse->getElementsByTagName('CodigoVerificacao')->item(0)->nodeValue:'';
            $result['LinkImpressao'] = $IdentificacaoNfse->getElementsByTagName('LinkImpressao')==true?$IdentificacaoNfse->getElementsByTagName('LinkImpressao')->item(0)->nodeValue:'';
            $result['DataEmissao'] = $IdentificacaoNfse->getElementsByTagName('DataEmissao')==true?$IdentificacaoNfse->getElementsByTagName('DataEmissao')->item(0)->nodeValue:'';
            $result['Competencia'] = $IdentificacaoNfse->getElementsByTagName('Competencia')==true?$IdentificacaoNfse->getElementsByTagName('Competencia')->item(0)->nodeValue:'';
            $result['StatusNfse'] = $IdentificacaoNfse->getElementsByTagName('StatusNfse')==true?$IdentificacaoNfse->getElementsByTagName('StatusNfse')->item(0)->nodeValue:'';
            $DadosNfse = $Nfse->getElementsByTagName('DadosNfse')->item(0);
            $result['RpsNumero'] = $DadosNfse->getElementsByTagName('RpsNumero')==true?$DadosNfse->getElementsByTagName('RpsNumero')->item(0)->nodeValue:'';

            $StatusNfse = $IdentificacaoNfse->getElementsByTagName('StatusNfse')==true?$IdentificacaoNfse->getElementsByTagName('StatusNfse')->item(0)->nodeValue:'';
            $LinkImpressao = $IdentificacaoNfse->getElementsByTagName('LinkImpressao')==true?$IdentificacaoNfse->getElementsByTagName('LinkImpressao')->item(0)->nodeValue:'';
            $RpsNumero = $DadosNfse->getElementsByTagName('RpsNumero')==true?$DadosNfse->getElementsByTagName('RpsNumero')->item(0)->nodeValue:'';
            
            // return $result;
            return $StatusNfse.'|'.$RpsNumero.'|'.$LinkImpressao;

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
      * Divide a string do certificado publico em linhas com 76 caracteres (padrao original)
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