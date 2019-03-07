<?php
/**
 * @author Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
 * @version 2015083101
 * @copyright 
 * @package nfse
 * @name validacoes.class.php
 */
if(!defined('_NFSE')):
	die("Hacking detectado, seu ip ".$_SERVER['REMOTE_ADDR']." foi logado !");
endif;

class Validacoes 
{
	public static $errorMessage;
	private $soma = 0;
	private $erro = '';
	private $ie;
	private $ie_len;
	private $ie_numero;
	private $estado;
	private $erroInscricao;
	
	/**
	 * * Funções para verificar se o cpf e valido
	 *
	 * @author Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
	 * @copyright Copyright &copy; 2006, Miguel Angelo Crosariol
	 * @license http://creativecommons.org/licenses/by-nc-sa/2.0/br Commons
	 *          Creative
	 * @version 20061009
	 * @param
	 *        	string chk_nCpf, o cpf a ser validado
	 * @return bool true para valido e false para invalido
	 */
	public static function isValidCPF($chk_nCpf) 
	{
		$cpf_limpo = null;
		$tam_cpf = strlen ( $chk_nCpf );
		for($i = 0; $i < $tam_cpf; $i ++):
			$carac = substr ( $chk_nCpf, $i, 1 );
			// verifica se o codigo asc refere-se a 0-9
			if (ord ( $carac ) >= 48 && ord ( $carac ) <= 57):
				$cpf_limpo .= $carac;
			endif;
		endfor;
		
		if (strlen ( $cpf_limpo ) != 11):
			self::$errorMessage = 'Tamanho '.strlen ( $cpf_limpo ).' do cpf com somente os números não é válido';
			return false;
		endif;
		
		if (in_array ( $cpf_limpo, array (
				"12345678909",
				"33333333333",
				"44444444444",
				"55555555555",
				"66666666666",
				"77777777777",
				"88888888888",
				"99999999999",
				"00000000000" 
		) )):
			self::$errorMessage = 'Numero do cpf é um padrão não válido';
			return false;
		endif;
		
		// achar o primeiro digito verificador
		$soma = 0;
		for($i = 0; $i < 9; $i ++):
			$soma += ( int ) substr ( $cpf_limpo, $i, 1 ) * (10 - $i);
		endfor;
		
		if ($soma == 0):
			self::$errorMessage = 'Soma do primeiro digito verificador não é válido';
			return false;
		endif;
		
		$primeiro_digito = 11 - ($soma % 11);
		if ($primeiro_digito > 9):
			$primeiro_digito = 0;
		endif;
		
		if (substr ( $cpf_limpo, 9, 1 ) != $primeiro_digito):
			self::$errorMessage = 'Primeiro digito verificador não é válido !';
			return false;
		endif;
		
		// acha o segundo digito verificador
		$soma = 0;
		for($i = 0; $i < 10; $i ++):
			$soma += intval ( substr ( $cpf_limpo, $i, 1 ) * (11 - $i) );
		endfor;
		
		$segundo_digito = 11 - ($soma % 11);
		if ($segundo_digito > 9):
			$segundo_digito = 0;
		endif;
		
		if (substr ( $cpf_limpo, 10, 1 ) != $segundo_digito):
			self::$errorMessage = 'Segundo digito verificador não é válido';
			return false;
		endif;
		
		return true;
	}
	
	/**
	 * * Funcao para verificar se o cnpj e valido
	 *
	 * @author Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
	 * @copyright Copyright &copy; 2006, Miguel Angelo Crosariol
	 * @license http://creativecommons.org/licenses/by-nc-sa/2.0/br Commons
	 *          Creative
	 * @version 20060622
	 * @param
	 *        	string chk_nCnpj, o cnpj a ser validado
	 * @return bool true para valido e false para invalido
	 */
	public static function isValidCNPJ($chk_nCnpj) 
	{
		$cnpj_limpo = null;
		$soma1 = 0;
		$tam_cnpj = strlen ( $chk_nCnpj );
		for($i = 0; $i < $tam_cnpj; $i ++):
			$carac = substr ( $chk_nCnpj, $i, 1 );
			// verifica se o codigo asc refere-se a 0-9
			if (ord ( $carac ) >= 48 && ord ( $carac ) <= 57):
				$cnpj_limpo .= $carac;
			endif;
		endfor;
		
		if (strlen ( $cnpj_limpo ) != 14):
			self::$errorMessage = 'Tamanho '.strlen ( $cpf_limpo ).' do cnpj com somente os números não é válido';
			return false;
		endif;
		
		if (in_array ( $cnpj_limpo, array (
				"12345678901230",
				"11111111111111",
				"22222222222222",
				"33333333333333",
				"44444444444444",
				"55555555555555",
				"66666666666666",
				"77777777777777",
				"88888888888888",
				"99999999999999" 
		) )):
			self::$errorMessage = 'Numero do cnpj é um padrão não válido';
			return false;
		endif;
		
		// acha o primeiro digito verificador
		$i = 0;
		$num = 5;
		for($i = 0; $i < 12; $i ++):
			if ($num - $i == 1):
				$num = 13;
			endif;
			$soma1 += intval ( substr ( $cnpj_limpo, $i, 1 ) * ($num - $i) );
		endfor;
		
		if ($soma1 == 0):
			self::$errorMessage = 'Soma do primeiro digito verificador não é válido';
			return false;
		endif;
		
		$primeiro_digito = 11 - ($soma1 % 11);
		if ($primeiro_digito > 9):
			$primeiro_digito = 0;
		endif;
		
		if (substr ( $cnpj_limpo, 12, 1 ) != $primeiro_digito):
			self::$errorMessage = 'Primeiro digito verificador não é válido !';
			return false;
		endif;
		
		// acha o segundo digito verificador
		$i = 0;
		$soma2 = 0;
		$num = 6;
		for($i = 0; $i < 13; $i ++):
			if ($num - $i == 1):
				$num = 14;
			endif;
			$soma2 += intval ( substr ( $cnpj_limpo, $i, 1 ) * ($num - $i) );
		endfor;
		
		if ($soma2 == 0):
			self::$errorMessage = 'Soma do segundo digito verificador não é válido';
			return false;
		endif;
		
		$segundo_digito = 11 - ($soma2 % 11);
		if ($segundo_digito > 9):
			$segundo_digito = 0;
		endif;
		
		if (substr ( $cnpj_limpo, 13, 1 ) != $segundo_digito):
			self::$errorMessage = 'Segundo digito verificador não é válido !';
			return false;
		endif;
		
		return true;
	}
	
	public static function ifValidUF( $uf )
	{
		if( !array_key_exists( $uf , array(
				'AC' => 'Acre',
				'AL' => 'Alagoas',
				'AP' => 'Amapa',
				'AM' => 'Amazonas',
				'BA' => 'Bahia',
				'CE' => 'Ceara',
				'DF' => 'Distrito Federal',
				'GO' => 'Goias',
				'ES' => 'Espirito Santo',
				'MA' => 'Maranhao',
				'MT' => 'Mato Grosso',
				'MS' => 'Mato Grosso do Sul',
				'MG' => 'Minas Gerais',
				'PA' => 'Para',
				'PB' => 'Paraiba',
				'PR' => 'Parana',
				'PE' => 'Pernambuco',
				'PI' => 'Piaui',
				'RJ' => 'Rio de Janeiro',
				'RN' => 'Rio Grande do Norte',
				'RS' => 'Rio Grande do Sul',
				'RO' => 'Rondonia',
				'RR' => 'Roraima',
				'SP' => 'Sao Paulo',
				'SC' => 'Santa Catarina',
				'SE' => 'Sergipe',
				'TO' => 'Tocantins'
		))):
			return false;
		endif;
		return true;
	}
	
	/**
	 * * verifica se uma data e valida
	 *
	 * @author Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
	 * @copyright Copyright &copy; 2006, Miguel Angelo Crosariol
	 * @license http://creativecommons.org/licenses/by-nc-sa/2.0/br Commons
	 *          Creative
	 * @version 20080303
	 * @param
	 *        	string chk_cData
	 * @param
	 *        	string ord DD/MM/YYYY
	 * @return bool true ou false
	 *        
	 */
	public static function isValidData($chk_cData) 
	{
		if (empty ( $chk_cData )):
			self::$errorMessage = "Data nao foi informada !";
			return false;
		endif;
		
		if (! preg_match ( '/^((0?[1-9]|[12][0-9]|3[01])[\/](0?[1-9]|1[012])[\/](19|20)[0-9]{2})*$/', $chk_cData )):
			self::$errorMessage = "Formato da Data $chk_cData invalido ou ano inferior a 1900 !";
			return false;
		endif;
		
		list ( $chk_nDay, $chk_nMonth, $chk_nYear ) = explode ( '/', $chk_cData );
		
		if (! checkdate ( $chk_nMonth, $chk_nDay, $chk_nYear )):
			self::$errorMessage = "Data $chk_cData inválida !";
			return false;
		endif;
		
		return true;
	}
	
	public static function isValidDataRange( $inicio , $fim )
	{
		if ( empty ( $inicio ) ||empty ( $fim ) ):
			self::$errorMessage = "Necessário data inicial e final!";
			return false;
		endif;
	
		if (! preg_match ( '/^((0?[1-9]|[12][0-9]|3[01])[\/](0?[1-9]|1[012])[\/](19|20)[0-9]{2})*$/', $inicio )):
			self::$errorMessage = "Formato da Data incial $inicio invalida ou ano inferior a 1900 !";
			return false;
		endif;
	
		if (! preg_match ( '/^((0?[1-9]|[12][0-9]|3[01])[\/](0?[1-9]|1[012])[\/](19|20)[0-9]{2})*$/', $fim )):
			self::$errorMessage = "Formato da Data final $fim invalida ou ano inferior a 1900 !";
			return false;
		endif;
		
		list ( $iDay, $iMonth, $iYear ) = explode ( '/', $inicio );
		list ( $fDay, $fMonth, $fYear ) = explode ( '/', $fim );
		
		if ( mktime(0,0,0,$iMonth,$iDay,$iYear) > mktime(0,0,0,$fMonth,$fDay,$fYear) ):
			self::$errorMessage = "Data inicial $inicio deve ser menor ou igual a data final $fim !";
			return false;
		endif;	
		return true;
	}
	
	public static function isValidAno($chk_ano) 
	{
		if (empty ( $chk_ano )):
			self::$errorMessage = "Ano não foi informado !";
			return false;
		endif;
		
		if (! is_numeric ( $chk_ano )):
			self::$errorMessage = "Ano informado não é um numero !";
			return false;
		endif;
		
		if (! checkdate ( 1, 1, $chk_ano )):
			self::$errorMessage = "Ano de $chk_ano não é um ano válido !";
			return false;
		endif;
		
		return true;
	}
	
	public static function isValidCep($chk_cCep) 
	{
		if (empty ( $chk_cCep )):
			self::$errorMessage = "O cep não foi informado !";
			return false;
		endif;
		
		if (! preg_match ( "/^(([0-9]{2})([.]?)([0-9]{3})([-]?)([0-9]{3}))?$/", $chk_cCep )):
			self::$errorMessage = "O cep não é válido !";
			return false;
		endif;
		
		return true;
	}
	
	public static function isValidEmail( $email , $vservidor = 1 ) 
	{
		if (empty ( $email )):
			self::$errorMessage = "Email não foi informado !";
			return false;
		endif;
		
		if (! preg_match ( '/^([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[@]([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[.]([a-z,A-Z]){2,3}([0-9,a-z,A-Z])?$/', $email )):
			self::$errorMessage = "O email informado não tem um formato válido !";
			return false;
		endif;
		
		if ($vservidor === 1):
			// verifica se o dominio e valido
			$email = strtolower ( $email );
			$arr1 = preg_split ( "/@/", $email );
			$url = "www.";
			// nome = $arr1[0];
			$servidor = $url . $arr1 [1];
			if (! @fsockopen ( $servidor, 80 )):
				self::$errorMessage = "O dominio $servidor do e-mail " . $email . " não é válido !";
			endif;
		endif;
		
		return true;
	}
	
	public static function isValidURL($url) 
	{
		if (! preg_match ( '|^(http(s)?://)?[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url )):
			return false;
		endif;
		return true;
	}
}
?>