<?php


ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

//require "connect/sessao.php";
//require "connect/funcoes.php";
//require "smtp.class.php";


$con = @mysql_pconnect("10.2.2.3", "csinform", "inform4416#scf");
if (!$con) {
	echo 'Erro na conexao com o Servidor<br>';
	echo mysql_error();
	exit;
} else {
	$database = mysql_select_db("cs2",$con);
	if (!$database) {
		echo 'Erro na conexão com o Banco de dados<br>';
		echo mysql_error();
	}
}

// Seleciona  os clientes que irão receber a NOTA FISCAL DE SERVIÇO.

$sql_nota = "SELECT a.codloja, a.insc, a.razaosoc, a.end, a.numero, a.complemento, a.bairro, 
					a.cidade, a.uf, a.cep, a.fone, a.email,
					b.vencimento, b.valor, m.id_estado, m.sigla
			 FROM cs2.cadastro a
			 INNER JOIN cs2.titulos b ON a.codloja = b.codloja
			 INNER JOIN base_web_control.nfe_uf e ON a.uf = e.sigla
			 INNER JOIN base_web_control.nfe_municipio m ON a.cidade = m.descricao AND e.id = m.id_estado
			 WHERE a.emitir_nfs = 'S' AND a.sitcli < 2
			 	   AND month(b.vencimento) = 12 and year(vencimento) = '2012'";
$qry_nota = mysql_query($sql_nota, $con) or die("Erro ao selecionar os clientes de NOTA FISCAL");

if ( mysql_num_rows($qry_nota) > 0 ){
	while ( $reg = mysql_fetch_array( $qry_nota ) ){
		$codloja		= $reg['codloja'];
		$insc 			= $reg['insc'];
		$razaosoc 		= $reg['razaosoc'];
		$end 			= $reg['end'];
		$numero 		= $reg['numero'];
		$complemento	= $reg['complemento'];
		
		# dados de testes abaixo
		$insc 			= '07658516000177';
		$razaosoc 		= 'INFORMFOZ ASSESSORIA EMPRESARIAL LTDA';
		$end 			= 'EDMUNDO DE BARROS';
		$numero 		= '326';
		$complemento	= '1 Piso Sala 17';
		
		$endereco		= "$end, $numero - $complemento";
		$endereco		= trim( $endereco );
		
		$bairro			= $reg['bairro'];
		$bairro			= 'CENTRO';
		
		$cidade			= $reg['cidade'];
		$cidade			= 'FOZ DO IGUAÇU';
		
		$uf				= $reg['uf'];
		$uf				= 'PR';
		
		$cep			= $reg['cep'];
		$cep			= '85851120';
		
		$fone			= $reg['fone'];
		$email			= $reg['email'];
		
		$id_estado		= $reg['id_estado'];
		$sigla			= $reg['sigla'];
		$id_municipio	= $id_estado.$sigla;
		
		$valor			= $reg['valor'];

		$xml = gera_xml($codloja,$insc,$razaosoc,$endereco, $bairro, $cidade, $id_municipio, $fone, $email, $valor, $uf, $cep );
		
		echo "<pre>";
		print_r( $xml );

		envia_nota_prefeitura($xml);
		
		echo "<br><hr><br>";
		
		exit;
		
	}
}

function gera_xml($codloja,$insc,$razaosoc,$endereco, $bairro, $cidade, $id_municipio, $fone, $email, $valor, $uf, $cep ){
	//coloca a data de hoje
	$data = date('Y-m-d').'T'.date('H:i:s');
	$numero_lote = 1;
	$numero_nota = 1;
	$aliquota = 5;
	$iss = $valor * $aliquota / 100;
	$iss = number_format($iss, 2, ".", "");
	$aliq = $aliquota / 100;
	$valor = number_format($valor, 2, ".", "");
	
	$XML = "<?xml version=\"1.0\" encoding=\"utf-8\" ?> 
<EnviarLoteRpsEnvio xmlns=\"http://isscuritiba.curitiba.pr.gov.br/iss/nfse.xsd\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://isscuritiba.curitiba.pr.gov.br/iss/nfse.xsd&quot;\">
    <LoteRps id=\"$numero_lote\">
		<NumeroLote>$numero_lote</NumeroLote> 
		<Cnpj>06866893000139</Cnpj> 
		<InscricaoMunicipal>150504796831</InscricaoMunicipal> 
		<QuantidadeRps>1</QuantidadeRps> 
		<ListaRps>
			<Rps>
				<InfRps>
					<IdentificacaoRps>
						<Numero>$numero_nota</Numero> 
						<Serie>F</Serie> 
						<Tipo>1</Tipo> 
					</IdentificacaoRps>
					<DataEmissao>$data</DataEmissao> 
					<NaturezaOperacao>2</NaturezaOperacao> 
					<OptanteSimplesNacional>2</OptanteSimplesNacional> 
					<IncentivadorCultural>2</IncentivadorCultural> 
					<Status>1</Status> 
					<Servico>
						<Valores>
							<ValorServicos>$valor</ValorServicos> 
							<IssRetido>1</IssRetido> 
							<ValorIss>$iss</ValorIss> 
							<BaseCalculo>$valor</BaseCalculo> 
							<Aliquota>$aliq</Aliquota> 
							<ValorLiquidoNfse>$valor</ValorLiquidoNfse> 
						</Valores>
						<ItemListaServico>001</ItemListaServico> 
						<Discriminacao>Licenciamento ou cessao de direito de uso de programas de computadores.</Discriminacao> 
						<CodigoMunicipio>4106902</CodigoMunicipio> 
					</Servico>
					<Prestador>
						<Cnpj>06866893000139</Cnpj> 
						<InscricaoMunicipal>150504796831</InscricaoMunicipal> 
					</Prestador>
					<Tomador>
						<IdentificacaoTomador>
							<CpfCnpj>
								<Cnpj>$insc</Cnpj> 
							</CpfCnpj>
						</IdentificacaoTomador>
						<RazaoSocial>$razaosoc</RazaoSocial> 
						<Endereco>
							<Endereco>$endereco</Endereco> 
							<Bairro>$bairro</Bairro> 
							<CodigoMunicipio>$id_municipio</CodigoMunicipio> 
							<Uf>$uf</Uf> 
							<Cep>$cep</Cep> 
						</Endereco>
						<Contato>
							<Telefone>$fone</Telefone> 
							<Email>$email</Email> 
						</Contato>
					</Tomador>
				</InfRps>
			</Rps>
		</ListaRps>
	</LoteRps>
	
	<Signature Id=\ID\">
          <SignedInfo Id=\"ID\" xmlns=\"http://www.w3.org/2000/09/xmldsig#\">
            <CanonicalizationMethod xsi:nil=\"true\" />
            <SignatureMethod xsi:nil=\"true\" />
            <Reference xsi:nil=\"true\" />
            <Reference xsi:nil=\"true\" />
          </SignedInfo>
          <SignatureValue Id=\"ID\" xmlns=\"http://www.w3.org/2000/09/xmldsig#\" />
          <KeyInfo Id=\"ID\" xmlns=\"http://www.w3.org/2000/09/xmldsig#\" />
          <Object Id=\"ID\" MimeType=\"string\" Encoding=\"anyURI\" xmlns=\"http://www.w3.org/2000/09/xmldsig#\">xml</Object>
          <Object Id=\"ID\" MimeType=\"string\" Encoding=\"anyURI\" xmlns=\"http://www.w3.org/2000/09/xmldsig#\">xml</Object>
     </Signature>

</EnviarLoteRpsEnvio>";
	return $XML;	
}

function envia_nota_prefeitura($xml){
	
	try {
		//Ambiente de PRODUCAO
		
		/*
		
		$cliente = new SoapClient('https://isscuritiba.curitiba.pr.gov.br/Iss.NfseWebService/NfseWs.asmx?wsdl');
		$result = $cliente->ValidarXml( array('xml' => $xml) );   // Valida o XML
		*/
		
		//assinaturaXML($xml, $tagid, $tagfn, $outDir, '06866893000139');
		
		// Ambiente de HOMOLOGACAO
		$cliente = new SoapClient('https://isscuritiba.curitiba.pr.gov.br/Iss.NfseWebService/nfsews.asmx?wsdl');
		$result = $cliente->RecepcionarXml( array(
							'xml' => $xml, 
							'metodo' =>  'RecepcionarLoteRps'
							
							) );   // Valida o XML
		echo "Resultado:";
		print_r( $result );
		
		foreach ($result as $rs){
			return utf8_decode($rs);
		}
	}catch (SoapFault $e){
		return $e->getMessage();
	}

	
}

//ASSINATURA DO XML
##############################
#RETIRADO E ADAPTADO DO NFEPHP
##############################
function assinaturaXML($nfe, $tagid, $tagfn, $outDir, $cnpj)
{   
   $pathCertP12     = "inform system.pfx";

   $tagid = "LoteRps";
   //TAG DO XML A SER REMOVIDA PARA SER INSERIDA A ASSINATURA
   $tagfn = "</EnviarLoteRpsEnvio></RecepcionarLoteRps>";

   //DIRETORIO TEMP
   $tmpDir          = "lib/temp/";
   //CAMINHO DA CHAVE
   $pathKey         = "";
   
   $tmpINname = tempnam($tmpDir , 'in');
   
   if (file_exists($tmpINname)) {
      unlink($tmpINname);
   }
   
   $tmpINname  = $tmpINname.'.xml';
   
   //LIMPA O ARQUIVO DOS lf E cr
   $nfe = preg_replace('/[\n\r\t]/', '', $nfe);
   
   echo "<pre>";
   //EXTRAI O ID DA NFE
   $xmldoc = new DOMDocument();                                 //INICIA OBJETO DOM
   $xmldoc -> preservWhiteSpace = FALSE;                          //ELIMINA ESPAÇOS EM BRANCO
   
   
   $xmldoc->formatOutput = FALSE;
   $xmldoc->loadXML($nfe,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);



   $infNFe = $xmldoc->getElementsByTagName($tagid)->item(0);    //CRIA UM OBJETO DOM COM O CONTEUDO DO NODE INFNFE
   
   $id = trim($infNFe->getAttribute("Id"));                     //EXTRAI O ID DA nf, SERÁ¡ NECESSÁRIO ADIANTE NO NODE DA ASSINATURA
   $Id = $id;

   unset($infNFe); //LIMPA A VARIÁVEL
   
   $nfe = $xmldoc->saveXML();
   $tmpOUTname = $outDir . $id . '.xml';   
   
   if (file_exists($tmpOUTname)) {
      unlink($tmpOUTname);
   }
   
   //LIMPA O ARQUIVO DOS lf E cr
   $order   = array("\r\n", "\n", "\r");
   $replace = '';
   $nfe     = str_replace($order, $replace, $nfe);
   
   // EXTRAI O CERTIFICADO
   $pkcs12 = file_get_contents($pathCertP12);
   $certs  = array();
   
   $passKey = "CMBdigita1";
   
   $bResp  = openssl_pkcs12_read($pkcs12, &$certs, $passKey);
   
  
   
   //print_r($bResp);
   exit;
   
   $certX509 = '';
   $data     = '';
   $arCert   = explode("\n", $certs['cert']);
   
   foreach ($arCert AS $curData)
   {
      if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) != 0 && strncmp($curData, '-----END CERTIFICATE', 20) != 0 )
      {
         $data .= trim($curData)."\n";
      }
   }
   $certX509 = $data;
   $certX509 = substr($certX509,0,-1);
   
   //CRIAÇÃO DO TEMPLATE
   $tplsign = '<Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315" /><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1" /><Reference URI="#' . $id . '"><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature" /><Transform Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315" /></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1" /><DigestValue/></Reference></SignedInfo><SignatureValue/><KeyInfo><X509Data><X509Certificate>'.$certX509.'</X509Certificate></X509Data></KeyInfo></Signature>';
   
   //REMOVE A FINALIZAÇÃO DA TAG </EnviarLoteRpsEnvio></RecepcionarLoteRps> PARA INSERIR O TEMPLATE
   $tamanho_tag = strlen($tagfn);
   
   $nfe = substr($nfe,0,-$tamanho_tag);
   $nfe = $nfe.$tplsign.$tagfn;
   //REMOVE ESSA TAG DUPLICADA PELO DOM
   $nfe = str_replace("<?xml version=\"1.0\"?>","",$nfe);
   
   //SALVA A NFE COM O TEMPLATE PARA A ASSINATURA
   file_put_contents($tmpOUTname,$nfe);   
   
   //MONTA O COMANDO PARA ASSINAR
   $cmd = "xmlsec1 sign --id-attr:Id $tagid --output $tmpOUTname --pkcs12 $pathCertP12 --privkey $pathKey --pwd $passKey $tmpINname 2>&1";
   
   //EXECUTA O COMANDO NA SHELL E RETORNA O RESULTADO EM $read
   $read = shell_exec($cmd);
   
   if (file_exists($tmpINname)) {
      unlink($tmpINname);
   }
   
   return $tmpOUTname;
}



?>