<?php
/**
 * @author Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
 * @version 2015083101
 * @copyright 
 * @package nfse
 * @name funcoes.class.php
 */
if(!defined('_NFSE')):
	die("Hacking detectado, seu ip ".$_SERVER['REMOTE_ADDR']." foi logado !");
endif;

class Funcoes
{
	public static $errorMessage;
	
	/**  cryptografa uma string utilizando como chave a Keysize
	 *
	 * @author    Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
	 * @copyright Copyright &copy; 2009, Miguel Angelo Crosariol 
	 * @license   http://creativecommons.org/licenses/by-nc-sa/2.0/br Commons Creative 
	 * @version   20090408 
	 * @param     string $data valor a cryptografado
	 * @return    string retorna o valor cryptografado */
	public static function encrypt($data) {
		#$td = mcrypt_module_open ( MCRYPT_TripleDES, "", MCRYPT_MODE_ECB, "" );
		#$iv = mcrypt_create_iv ( mcrypt_enc_get_iv_size ( $td ), MCRYPT_RAND );
		#$data = mcrypt_ecb ( MCRYPT_TripleDES, Keysize, $data, MCRYPT_ENCRYPT, $iv ); << deprecated
		#return bin2hex ( $data );
		$td = mcrypt_module_open('tripledes', '', 'ecb', '');
		$iv = mcrypt_create_iv( mcrypt_enc_get_iv_size($td) , MCRYPT_RAND );
		$key = substr( Keysize , 0 , mcrypt_enc_get_key_size($td) );
		mcrypt_generic_init( $td , $key , $iv );
		$encrypted_data = mcrypt_generic( $td , $data );
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return bin2hex ( $encrypted_data );		
	}
	
	/**  descryptografa uma string utilizando como chave a Keysize
	 *
	 * @author    Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
	 * @copyright Copyright &copy; 2009, Miguel Angelo Crosariol 
	 * @license   http://creativecommons.org/licenses/by-nc-sa/2.0/br Commons Creative 
	 * @version   20090408 
	 * @param     string $data valor a descryptografado
	 * @return    string retorna o valor descryptografado */
	public static function decrypt($data)
	{
		#$td = mcrypt_module_open ( MCRYPT_TripleDES, "", MCRYPT_MODE_ECB, "" );
		#$iv = mcrypt_create_iv ( mcrypt_enc_get_iv_size ( $td ), MCRYPT_RAND );
		$ndata = '';
		for($i = 0; $i < strlen ( $data ); $i += 2) {
			$ndata .= pack ( "C", hexdec ( substr ( $data, $i, 2 ) ) );
		}
		#return mcrypt_ecb ( MCRYPT_TripleDES, Keysize, $ndata, MCRYPT_DECRYPT, $iv ); << deprecated		
		$td = mcrypt_module_open('tripledes', '', 'ecb', '');
		$iv = mcrypt_create_iv( mcrypt_enc_get_iv_size($td) , MCRYPT_RAND );
		$key = substr( Keysize , 0 , mcrypt_enc_get_key_size($td) );
		mcrypt_generic_init( $td , $key , $iv );
		$encrypted_data = mdecrypt_generic( $td , $ndata );
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return $encrypted_data;
		
		
		
	}
	
	/**  cryptografa a url passada via get
	 *
	 * @author    Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
	 * @copyright Copyright &copy; 2009, Miguel Angelo Crosariol 
	 * @license   http://creativecommons.org/licenses/by-nc-sa/2.0/br Commons Creative 
	 * @version   20090408 
	 * @param     string $url url a cryptografada
	 * @return    string retorna a url cryptografada */
	public static function encodeGetString( $url ) 
	{
		$fin = '';
		$pos_debut = strpos ( $url, "?" );
		if ($pos_debut === false):
			return $url;
		endif;
		$pos_fin = strpos ( $url, " " );
		if ($pos_fin):
			$pos_long = $pos_fin - $pos_debut - 1;
			$fin = substr ( $url, $pos_fin );
		else:
			$pos_long = strlen ( $url ) - $pos_debut - 1;
		endif;
		$debut = substr ( $url, 0, $pos_debut + 1 );
		$param = substr ( $url, $pos_debut + 1, $pos_long );
		$encrypt = funcoes::encrypt ( $param );
		return $debut . "nfse=" . $encrypt . $fin;
	}
	
	/** * Fun��o decodifica as variaveis via GET
	 *
	 * @author    Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
	 * @copyright Copyright &copy; 2006, Miguel Angelo Crosariol 
	 * @license   http://creativecommons.org/licenses/by-nc-sa/2.0/br Commons Creative 
	 * @version   20060622 
	 * @param     string $url, a url completa que vai ser descriptografada
	 * @return    retorna as variaveis obtidas via GET */
	public static function decodeGetString( $url ) {
		if (empty ( $url )):
			return false;
		endif;
		$output = array ();
		$param = funcoes::decrypt ( $url );
		parse_str ( $param, $output );
		foreach ( $output as $k => $v ):
			$_REQUEST [$k] = $v;
		endforeach;
		return true;
	}

	/** * Fun��o troca os caracteres acentuados pelos nao acentuados
	 *
	 * @author    Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
	 * @copyright Copyright &copy; 2006, Miguel Angelo Crosariol 
	 * @license   http://creativecommons.org/licenses/by-nc-sa/2.0/br Commons Creative 
	 * @version   20060818 
	 * @param     string $texto o texto a ser verificado
	 * @return    string retorna texto sem acentuacao */	
	public static function remover_acentos($str, $enc = "UTF-8")
	{
		$acentos = array(
		'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
		'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
		'C' => '/&Ccedil;/',
		'c' => '/&ccedil;/',
		'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
		'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
		'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
		'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
		'N' => '/&Ntilde;/',
		'n' => '/&ntilde;/',
		'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
		'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
		'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
		'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
		'Y' => '/&Yacute;/',
		'y' => '/&yacute;|&yuml;/',
		'a.' => '/&ordf;/',
		'o.' => '/&ordm;/');
		
	   	$str = preg_replace($acentos,
	                       array_keys($acentos),
	                       htmlentities($str,ENT_NOQUOTES, $enc));
		$str = str_replace('&nbsp;',' ',$str);
		return mb_strtoupper($str,$enc);
	}
	
	/** * Funcao que formata uma string removendo acentos , espacos em branco, etc
	 *
	 * @author    Miguel Angelo Crosariol <miguel at crosariol dot com dot br>
	 * @copyright Copyright &copy; 2006, Miguel Angelo Crosariol 
	 * @license   http://creativecommons.org/licenses/by-nc-sa/2.0/br Commons Creative 
	 * @version   20111209
	 * @param     string $texto o texto a ser verificado
	 * @return    string retorna texto formatado */	
	public static function format_string($str, $enc = "UTF-8" ,$case = "upper")
	{
		$acentos = array(
		'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
		'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
		'C' => '/&Ccedil;/',
		'c' => '/&ccedil;/',
		'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
		'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
		'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
		'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
		'N' => '/&Ntilde;/',
		'n' => '/&ntilde;/',
		'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
		'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
		'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
		'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
		'Y' => '/&Yacute;/',
		'y' => '/&yacute;|&yuml;/',
		'a.' => '/&ordf;/',
		'o.' => '/&ordm;/');
		
		if ($case=="lower")
			$str = mb_strtolower($str,$enc);
		else
			$str = mb_strtoupper($str,$enc);		
		
		$str = trim($str); // remove espacos no inicio e fim
		$str = preg_replace('/\s(?=\s)/', '',$str); // eliminando mais de um espaco na string
	   	$str = preg_replace($acentos,array_keys($acentos),htmlentities($str,ENT_NOQUOTES, $enc));
		$str = preg_replace('/&nbsp;/',' ',$str);
		#$str = preg_replace("/'/"," ",$str);
		#$str = preg_replace('/&/','i',$str);

		# caractere seq��ncia de escape , pelo manual da nfe paulista
		$str = preg_replace('/</','&lt;',$str);
		$str = preg_replace('/>/','&gt;',$str);
		#$str = preg_replace('/&/','&amp;',$str);
		$str = preg_replace('/"/','&quot;',$str);
		$str = preg_replace("/'/","&#39;",$str);
		
		return $str;
	}
	
	public static function formata_valor_xml($fm)
	{
		$fm = preg_replace("/[^[:digit:]]/","",$fm);
		$fm = str_pad($fm,15,'0',STR_PAD_LEFT);
		$fm = substr($fm,0,13).'.'.substr($fm,13,2);
		$fm = sprintf("%01.2f",$fm);
		return $fm;
	}
	
	public static function FormataCpfCnpj($num)
	{
		$num = preg_replace ( "/[^[:digit:]]/", "", $num );
		if (strlen ( $num ) == 11):
		return substr ( $num, 0, 3 ) . '.' . substr ( $num, 3, 3 ) . '.' . substr ( $num, 6, 3 ) . '-' . substr ( $num, 9, 2 );
		elseif (strlen ( $num ) == 14):
		return substr ( $num, 0, 2 ) . '.' . substr ( $num, 2, 3 ) . '.' . substr ( $num, 5, 3 ) . '/' . substr ( $num, 8, 4 ) . '-' . substr ( $num, 12, 2 );
		else:
		return $num;
		endif;
	}	
}
?>