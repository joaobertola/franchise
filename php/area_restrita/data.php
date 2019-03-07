<?php
function fvenc($data,$dt_base) {
$d_data = substr($data,6,2);
$m_data = substr($data,4,2);
$a_data = substr($data,0,4);
$d_base = substr($dt_base,6,2);
$m_base = substr($dt_base,4,2);
$a_base = substr($dt_base,0,4);

$dias_data = floor(gmmktime (0,0,0,$m_data,$d_data,$a_data)/ 86400);
$dias_base = floor(gmmktime (0,0,0,$m_base,$d_base,$a_base)/ 86400);
$val = $dias_data - $dias_base;
return $val;
}

function BarCode($barcode) {

$a_seq = array(array(),array());
if (strlen($barcode)%2 != 0 ) {
   //numero de digitos tem que ser par
   return false;
}
//codificacao dos numeros
//$a_num = array(array(),array());
$a_num[1] = array(1 => "0","1","2","3","4","5","6","7","8","9");
$m1 = array(1 => "1", "1", "3", "3", "1");
$m2 = array(1 =>"3", "1", "1", "1", "3");
$m3 = array(1 =>"1", "3", "1", "1", "3");
$m4 = array(1 =>"3", "3", "1", "1", "1");
$m5 = array(1 =>"1", "1", "3", "1", "3");
$m6 = array(1 =>"3", "1", "3", "1", "1");
$m7 = array(1 =>"1", "3", "3", "1", "1");
$m8 = array(1 =>"1", "1", "1", "3", "3");
$m9 = array(1 =>"3", "1", "1", "3", "1");
$m10 = array(1 =>"1", "3", "1", "3", "1");
$a_num[2] = array(1 =>$m1,$m2,$m3,$m4,$m5,$m6,$m7,$m8,$m9,$m10);

// inicio do codigo de barras
$a_seq[1] = array(1 => "P","B","P","B");
$a_seq[2] = array(1 => "1","1","1","1");

for ($cc = 1;$cc<=strlen($barcode);$cc++){ 
    
    $impar = ($cc%2 != 0);
    if ($impar) {
       $a_tmp = array();
       array_pad ($a_tmp, 10,"");
    }

    $digito = substr($barcode, $cc-1, 1);
    
    $pts = array_search ( $digito, $a_num[1]);

    for ($nn=1;$nn<=5;$nn++){
        if ($impar) {
           $a_tmp[$nn * 2 - 1] = $a_num[2][$pts][$nn];
        }else{
           $a_tmp[$nn * 2 - 0] = $a_num[2][$pts][$nn];
        }
    }

    if (!$impar ) {
       for ($tt=1;$tt<=10;$tt++){
           if ($tt%2 != 0) {
              array_push ($a_seq[1],"P");
           }else{
               array_push ($a_seq[1],"B");
           }
	       array_push ($a_seq[2],$a_tmp[$tt]); 
       }
    }

}

// final do codigo de barras
array_push($a_seq[1], "P");
array_push($a_seq[2], "3");
array_push($a_seq[1], "B");
array_push($a_seq[2], "1");
array_push($a_seq[1], "P");
array_push($a_seq[2], "1");
array_push($a_seq[1], "B");
array_push($a_seq[2], "1");

// gera codigo html

$linha2de5 =  '';
for ($ii = 1; $ii<=count($a_seq[1]);$ii++){
    if ($a_seq[1][ $ii] == "P"){
       $arq_gif = "area_restrita/p.gif";
    }else{
       $arq_gif = "area_restrita/b.gif";
    }
    $linha2de5 .= '<img SRC="'.$arq_gif.'" width="'.$a_seq[2][ $ii].'" height="60">';
}
return $linha2de5;
}

function dig10bb($codigo) {
   //local vv, soma
   $vv = 2;
   $soma = $di = $resto = $mult = $dig = $dezsup = 0;
   for( $di = strlen($codigo);$di>0; $di--) {
      $mult = (substr($codigo, $di-1, 1)) * $vv;
      $verdade = "S" ;
      while ($verdade =="S") {
         if (strlen(trim($mult)) > 1) {
            $mult = (substr(trim($mult),0, 1)) + (substr(trim($mult),strlen(trim($mult))-1 ,1));
         } else {
          $verdade = "N";
         }
      }
      $soma = $soma + $mult;
      if ($vv ==  2) {
         $vv = 1;
      } else {
         $vv = 2;
      }
   }
   $dezsup = (floor($soma / 10) + 1) * 10;
   $resto  = $dezsup - $soma;
   if ($resto == 10){
      $dig = "0";
  } else {
      $dig = str_pad($resto,'0',1,STR_PAD_LEFT);
  }
return $dig ;
}

function mod($nr1,$nr2){
	//Fun?o para apresentar resto de uma divis?
	$val1 = floor($nr1/$nr2);
	$resto = $nr1 -($val1*$nr2);
	return $resto;
}


function dig11bar($codigo,$tam){
   $xx   = 1;
   $soma = 0;
   for ($di = 1; $di<=$tam;$di++){
      $xx ++;
      if ($xx>9){
        $xx=2;
        }
      $soma += substr($codigo,$tam - $di,1) * $xx;
   }
   $resto = mod($soma, 11);
   $dig = 11 - $resto;
   if ($dig > 9){
      $dig= "1";
  } else{
      $dig = str_pad($dig,'0',1,STR_PAD_LEFT);
   }
return $dig;
}

?>
