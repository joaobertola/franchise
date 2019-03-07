<?php
$tipo = "N N L L";
$tipo = explode(" ", $tipo);
$padrao_letras = "A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|X|W|Y|Z";
$padrao_numeros = "0|1|2|3|4|5|6|7|8|9";
$array_letras = explode("|", $padrao_letras);
$array_numeros = explode("|", $padrao_numeros);
$senha_restrita = "";
	for ($i=0; $i<sizeOf($tipo); $i++)
	{
		if ($tipo[$i] == "L")
		{
			$senha_restrita.= $array_letras[array_rand($array_letras,1)];
		} 
		else 
		{
			if ($tipo[$i] == "N")
			{
				$senha_restrita.= $array_numeros[array_rand($array_numeros,1)];
			}
		}
	}
echo $senha_restrita;
?>