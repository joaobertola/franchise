<?php
/*
	1 - Coloca a mascara de telefone (00)0000-0000
	2 - Converte de 00/00/0000 para 0000-00-00
	3 - converte de 0000-00-00 para 00/00/0000
	4 - coloca mascar de CPF 000.000.000-00
	5 - coloca mascar de CNPJ 00000000000000 para 00.000.000/0000-00
	6 - coloca mascar de CEP 00000000 para 00.000-000
	7-  Retorna a data atual no padrão Inglês (YYYY-MM-DD):
	8 - RETORNA A HORA ATUAL (Hora e Minuto)
	9-  Retorna a data atual no padrão Inglês (DD-MM-YYYY):
	10- limpa a data de 00/00/0000 para 00000000
	11- formata valor que vem do banco
	12- limpa mascara de CPF
	13- limpa mascara de CNPJ
	14- tira espaço em branco e aspas simples
	15- tira aspas simples
	16- calcula data para geração de boleto
	17- calcula a prestação para a geração de boleto
	18- limpa mascara de telefone para gravar no banco de dados 
	19- retorna o mes por extenso
	20- função que tras desde o ano 2000 ate o ano corrente
	21- tira aspas simples e transforma em maiuscula
	22- transforma valor real para formato de BD
	23- valida codigo de barra para ver se tem ponto
    24- valida codigo de barra para ver se começa com zero
	25- transforma DATETIME em Data - Hora
	26- mascara numero passado por javaScript para data busca banco 00000000 0000-00-00
	27- mascara numero passado por javaScript para data normal 00000000 0000-00-00
	28- mascara para formatar a placa 
	29- valor monetario por extenso
*/

//1 - Coloca a mascara de telefone (00)0000-0000
function telefoneConverte($p_telefone){
     if ($p_telefone == '') {
	   return ('');	   
	 } else { 	   
	   $a = substr($p_telefone, 0,2);   
	   $b = substr($p_telefone, 2,4);   
	   $c = substr($p_telefone, 6,4);   
	   
	   $telefone_mascarado  = "(";
   	   $telefone_mascarado .= $a;
	   $telefone_mascarado .= ")&nbsp;";
	   $telefone_mascarado .= $b;
	   $telefone_mascarado .= "-";
	   $telefone_mascarado .= $c;
	   return ($telefone_mascarado);
	 }  
}

function mascara_celular_wc($celular){

	if ( strlen($celular) == 10 )
		$saida = '('.substr($celular,0,2).') '.substr($celular,2,4).'-'.substr($celular,6,4);
	else
		$saida = '('.substr($celular,0,2).')'.substr($celular,2,5).'-'.substr($celular,7,4);
	return $saida;
	
}

//2 - Converte de 00/00/0000 para 0000-00-00
function converteDataGravaBanco($p_data_padrao)
{
       $dia = substr($p_data_padrao, 0,2);
	   $mes = substr($p_data_padrao, 3,2);
	   $ano = substr($p_data_padrao, 6,9);	
	   $data_bd.=$ano;
	   $data_bd.="-";
	   $data_bd.=$mes;
	   $data_bd.="-";
	   $data_bd.=$dia;
	   return ($data_bd);
} 

//3 - converte de 0000-00-00 para 00/00/0000
function converteDataBancoView($p_data_banco)
{
 	   $dia = substr($p_data_banco, 8,2);   
	   $mes = substr($p_data_banco, 5,2);   
	   $ano = substr($p_data_banco, 0,4);   
	   $data_view.=$dia;
	   $data_view.="/";
	   $data_view.=$mes;
	   $data_view.="/";
	   $data_view.=$ano;
	   return ($data_view);
} 

//4 - coloca mascar de CPF 00000000000 para 000.000.000-00
function mascaraCpf($p_cpf_banco)
{
 	   $a = substr($p_cpf_banco, 0,3);   
	   $b = substr($p_cpf_banco, 3,3);   
	   $c = substr($p_cpf_banco, 6,3);   
	   $d = substr($p_cpf_banco, 9,2);   
	   $cpf_view.=$a;
	   $cpf_view.=".";
	   $cpf_view.=$b;
	   $cpf_view.=".";
	   $cpf_view.=$c;
	   $cpf_view.="-";
	   $cpf_view.=$d;	
	   return ($cpf_view);
}

//5 - coloca mascar de CNPJ 00000000000000 para 00.000.000/0000-00
function mascaraCnpj($p_cnpj_banco)
{
 	   $a = substr($p_cnpj_banco, 0,2);   
	   $b = substr($p_cnpj_banco, 2,3);   
	   $c = substr($p_cnpj_banco, 5,3);   
	   $d = substr($p_cnpj_banco, 8,4);   
	   $e = substr($p_cnpj_banco, 12,2);   
	   $cnpj_view.=$a;
	   $cnpj_view.=".";
	   $cnpj_view.=$b;
	   $cnpj_view.=".";
	   $cnpj_view.=$c;
	   $cnpj_view.="/";
	   $cnpj_view.=$d;	
	   $cnpj_view.="-";	
	   $cnpj_view.=$e;
	   return ($cnpj_view);
}

//6 - coloca mascar de CEP 00000000 para 00.000-000
function mascaraCep($p_cep_banco)
{
 	   $a = substr($p_cep_banco, 0,2);   
	   $b = substr($p_cep_banco, 2,3);   
	   $c = substr($p_cep_banco, 5,3);   
	   $cep_view.=$a;
	   $cep_view.=".";
	   $cep_view.=$b;
	   $cep_view.="-";
	   $cep_view.=$c;
	   return ($cep_view);
}

//7- Retorna a data atual no padrão Inglês (YYYY-MM-DD):
function dataAtualBd() {
	$dia = date('d');
	$mes = date('m');
	$ano = date('Y');
	$data_en = "$ano-$mes-$dia";	
	return $data_en;
}

//8 - RETORNA A HORA ATUAL (Hora e Minuto)
function horaAtual(){
	//$hora = date("H:i");
	$_hora = mysql_query("SELECT CURTIME() AS hora");
	$hora = mysql_result($_hora,0,'hora');
	$hora = substr($hora, 0, 8);  
	return $hora;
}

//9- Retorna a data atual no padrão Inglês (DD/MM/YYYY):
function dataAtualView() {
	$dia = date('d');
	$mes = date('m');
	$ano = date('Y');
	$data_pt = "$dia/$mes/$ano";	
	return $data_pt;
}

//10-limpa a data de 00/00/0000 para 00000000
function limpaBarraData($p_data)
{
	$parte = explode('/', $p_data);
	$formata = strtotime($parte[1]."/".$parte[0]."/".$parte[2]);
	return $formata;
}

//11-formata valor que vem do banco
function formataNumero($p_numero, $casas_decimais)
{
	if ( $casas_decimais == 3 )
		return number_format($p_numero,3,',','.'); 
	else
		return number_format($p_numero,2,',','.'); 
}

//12- limpa mascara de CPF
function limpaMascaraCpf($p_numero){
	$p_numero = str_replace(".","",$p_numero);
	$p_numero = str_replace("-","",$p_numero);
	return $p_numero;
}

//13- limpa mascara de CNPJ
function limpaMascaraCnpj($p_numero){
	$p_numero = str_replace(".","",$p_numero);
	$p_numero = str_replace("/","",$p_numero);
	return $p_numero;
}

//14- tira espaço em branco e aspas
function tiraEspacoEmBranco($p_paramento){
	$p_paramento = str_replace(" ","",$p_paramento);
	$p_paramento = str_replace("'","",$p_paramento);
	$p_paramento = trim($p_paramento);
	return $p_paramento;
}

//15- tira aspas simples
function tiraAspasSimples($p_paramento){
	$p_paramento = str_replace("'","",$p_paramento);
	return $p_paramento;
}

//16- calcula data para geração de boleto
function SomarData($data, $dias, $meses, $ano)
{
   //passe a data no formato dd/mm/yyyy 
   $data = explode("/", $data);
   $newData = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses,
     $data[0] + $dias, $data[2] + $ano) );
   return $newData;
}

//17- calcula a prestação para a geração de boleto
function CalculaPrestacao($PVista,$Entrada,$qtd_parcela,$tx_mensal)
{
	$VFin = $PVista-$Entrada;
	$tx_mensal = $tx_mensal/100.00;
	$R = $VFin * $tx_mensal * pow( (1+$tx_mensal),$qtd_parcela)/(pow((1+$tx_mensal),$qtd_parcela)-1);
	return $R;
}

//18- limpa mascara de telefone para gravar no banco de dados 
function limparMascaraTelefone($p_telefone)
{
	$telefone = str_replace("-","",$p_telefone);
	$telefone = str_replace("(","",$telefone);
	$telefone = str_replace(")","",$telefone);
	$telefone = str_replace(" ","",$telefone);
	return ($telefone);
}

//19 - retorna o mes por extenso
function mesExtenso($p_numero_mes){
	if($p_numero_mes == 1)      $mes = "Janeiro";
	elseif($p_numero_mes == 2)  $mes = "Fevereiro";
	elseif($p_numero_mes == 3)  $mes = "Março";
	elseif($p_numero_mes == 4)  $mes = "Abril";
	elseif($p_numero_mes == 5)  $mes = "Maio";
	elseif($p_numero_mes == 6)  $mes = "Junho";
	elseif($p_numero_mes == 7)  $mes = "Julho";
	elseif($p_numero_mes == 8)  $mes = "Agosto";
	elseif($p_numero_mes == 9)  $mes = "Setembro";
	elseif($p_numero_mes == 10) $mes = "Outubro";
	elseif($p_numero_mes == 11) $mes = "Novembro";
	elseif($p_numero_mes == 12) $mes = "Dezembro";
	return $mes;
}

//20-função que tras desde o ano 2000 ate o ano corrente
function trasAno(){
	$ano = date('Y');
?>
<select name="ano" class="boxnormal" onfocus="this.className='boxover'" onblur="this.className='boxnormal';" style="width:15%">
<?php    
	for($i=2000; $i<=$ano; $i++){		
?>
	<option value="<?=$i?>" <?php if($i == $ano){?> selected="selected"<?php } ?>><?=$i?></option>
<?php }
	echo "</select>";
}

//21- tira aspas simples e transforma em maiuscula
function tiraAspasSimplesTransformaMaiuscula($p_paramento){
	$p_paramento = str_replace("'","",$p_paramento);
	$p_paramento = strtoupper($p_paramento);
	$p_paramento = trim($p_paramento);
	return $p_paramento;
}

//22- transforma valor real para formato de BD Ex: 1.000,00 para 1000.00
function transformaRealParaBd($p_paramento){
	$p_paramento = str_replace(".","",$p_paramento);
	$p_paramento = str_replace(",",".",$p_paramento);
	return $p_paramento;
}

//23 - valida codigo de barra para ver se tem ponto
function validaCodigoBarraPonto($p_paramento){
	$p_paramento = str_replace(".","a",$p_paramento);
	return $p_paramento;
}

//24 - valida codigo de barra para ver se começa com zero
function validaCodigoBarraZero($p_paramento){
	$p_paramento = substr($p_paramento, 0, 1);  // retorna o primeiro caracter
	if($p_paramento == 0)$p_paramento = 'a';
	return $p_paramento;
}

//25 - transforma DATETIME em Data - Hora
function converteDataHoraBancoView($p_paramento)
{
 	   $dia = substr($p_paramento, 8,2);   
	   $mes = substr($p_paramento, 5,2);   
	   $ano = substr($p_paramento, 0,4);   	   
	   $hora = substr($p_paramento, 11,8);  	   
	   $data_hora_view.=$dia;
	   $data_hora_view.="/";
	   $data_hora_view.=$mes;
	   $data_hora_view.="/";
	   $data_hora_view.=$ano;	   
	   $data_hora_view.=" - ";	   
	   $data_hora_view.=$hora;
	   
	   return ($data_hora_view);
} 

//26- mascara numero passado por javaScript para data busca banco 00000000 0000-00-00
function transformaNumeroDataMascaradaBd($p_parametro){
	   $dt_busca  = substr($p_parametro, 4,4)."-";   
	   $dt_busca .= substr($p_parametro, 2,2)."-";   
	   $dt_busca .= substr($p_parametro, 0,2);   	   
	   return ($dt_busca);	 
}

//27- mascara numero passado por javaScript para data normal 00000000 0000-00-00
function transformaNumeroDataMascaradaView($p_parametro){
	   $dt_busca  = substr($p_parametro, 0,2)."/";   
	   $dt_busca .= substr($p_parametro, 2,2)."/";   
	   $dt_busca .= substr($p_parametro, 0,4);   	   
	   return ($dt_busca);	 
}

//28-limpa CPF-CNPJ para gravar no Bd
function limpaMascaraCpfCnpjCep($p_numero){
	$p_numero = str_replace(".","",$p_numero);
	$p_numero = str_replace("-","",$p_numero);
	$p_numero = str_replace("/","",$p_numero);
	return $p_numero;
}

//28-formata 0800 para mostar
function mascaraFoneGratuito($p_parametro){
	 $p_parametro_tmp   = substr($p_parametro, 0,4)."-";   
	 $p_parametro_tmp  .= substr($p_parametro, 4,3)."-";   
	 $p_parametro_tmp  .= substr($p_parametro, 7,4);   
	 return $p_parametro_tmp;
}

//formata a placa para mostrar
function mascaraFormataPlaca($p_parametro){
	 $p_parametro_tmp   = substr($p_parametro, 0,3)."-";   
	 $p_parametro_tmp  .= substr($p_parametro, 3,4); 
	 $p_parametro_tmp  = strtoupper($p_parametro_tmp);
	 return $p_parametro_tmp;
}

function placa($p_parametro){
	   $placa  = substr($p_parametro, 0,3)."-";   
	   $placa .= substr($p_parametro, 3,4);
	   $placa = strtoupper($placa);    
	   return ($placa);	 
}

function substitui($value){ 
	$trocaeste=array( "(", ")","'","Ö","Ç","Ü","Ú","Ó","Ô","Õ","Ò","Ã","Â","Á","À","É","Í",";","'","´", " "); 
	$poreste=array( "", "","","O","C","U","U","O","O","O","O","A","A","A","A","E","I","","","",""); 
	$value=str_replace($trocaeste,$poreste,$value); 
	$value = preg_replace("@[^A-Za-z0-9<> /,.\-_]+@i","",$value); 
	$value = strtolower($value);
	return $value; 
}

function tiraAspasSimplesEspacoBranco($p_parametro){
	$p_parametro = str_replace("'","",$p_parametro);
	$p_parametro = str_replace(" ","",$p_parametro);
	return $p_parametro;
}

//gerar thumb
function geraThumb($photo, $output, $new_height){
    $source = imagecreatefromstring(file_get_contents($photo));
    list($width, $height) = getimagesize($photo);
    if ($height>$new_height)
    {
		$new_width = ($new_height/$height) * $width;
        $thumb = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($thumb, $output, 100);
    }
    else
    {
        copy($photo, $output);
    }
}

//valor monetario por extenso
function extenso_webcontrol($valor=0, $maiusculas=false)
{
    // verifica se tem virgula decimal
    if (strpos($valor,",") > 0)
    {
      // retira o ponto de milhar, se tiver
      $valor = str_replace(".","",$valor);

      // troca a virgula decimal por ponto decimal
      $valor = str_replace(",",".",$valor);
    }

        $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões",
"quatrilhões");

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
"quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
"sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
"dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis",
"sete", "oito", "nove");

        $z=0;

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        for($i=0;$i<count($inteiro);$i++)
                for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
                        $inteiro[$i] = "0".$inteiro[$i];

        $fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
        for ($i=0;$i<count($inteiro);$i++) {
                $valor = $inteiro[$i];
                $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
                $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
                $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

                $r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd &&
$ru) ? " e " : "").$ru;
                $t = count($inteiro)-1-$i;
                $r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
                if ($valor == "000")$z++; elseif ($z > 0) $z--;
                if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
                if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) &&
($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }

         if(!$maiusculas){
                          return($rt ? $rt : "zero");
         } elseif($maiusculas == "2") {
                          return (strtoupper($rt) ? strtoupper($rt) : "Zero");
         } else {
                          return (ucwords($rt) ? ucwords($rt) : "Zero");
         }
}

function mascara_alt($qtd){
	if ( $qtd == 2 )
		return 'decimal'; 
	else if ( $qtd == 3 )
		return 'decimal3';
}
?>