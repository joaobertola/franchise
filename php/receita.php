<?php

function curl_recebe_img($url, $arquivo, $cookief="", $cookiej="") {
	$ch = curl_init ($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	if(!empty($cookief)) {
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookief);
	}
	if(!empty($cookiej)) {
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiej);
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
	$data=curl_exec($ch);
	curl_close ($ch);
	$fp = fopen($arquivo,'w');
	fwrite($fp, $data) or die ("falha ao gravar o arquivo : $arquivo");
	fclose($fp);
	return $arquivo;
}

function curl_captcha_rf($doc, $letras){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "origem=comprovante&search_type=cnpj&cnpj=$doc&idLetra=$letras&submit1=Consultar");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_COOKIEFILE, "receita.txt");
	curl_setopt($ch, CURLOPT_URL, "http://www.receita.fazenda.gov.br/PessoaJuridica/CNPJ/cnpjreva/valida.asp");
	$output=curl_exec($ch);
	$res = substr_count($output, "<table border=\"0\" width=\"100%\">");
	if($res==0){
		return 0; //CAPTCHA ERRADO
	}elseif($res==1){
		return $output; //OK ACHOU A FICHA
	}elseif($res==3){
		return 2; //CNPJ ERRADO
	}
}

function rf_ficha_receita($output){
	$outp = strstr($output, "<!-- || -->");
	$fim = strlen(strstr($output, "<!-- Fim Linha SITUACAO ESPECIAL-->"));
	$out = trim(substr($outp, 0, -$fim));
	$out .="</td></tr></table>";
	$out = str_replace("images/brasao2.gif", "http://www.receita.fazenda.gov.br/PessoaJuridica/CNPJ/cnpjreva/images/brasao2.gif", $out);
	return utf8_encode($out);
}

function rf_ficha_dados($ficha,$pos){
	$atividade = explode("<font face=\"Arial\" style=\"font-size: 6pt\">", $ficha);
	$ex = explode("</td>", $atividade[$pos]);
	$atv = strstr($ex[0], "<font face=\"Arial\" style=\"font-size: 8pt\">");
	$atividade = strip_tags($atv);
	return trim(preg_replace('|\s{2,}|', ' ', $atividade));
}

?>