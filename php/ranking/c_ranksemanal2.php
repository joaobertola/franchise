<script src="https://www.webcontrolempresas.com.br/Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<style>
.fonte{
	font-size: 10px;	
}
</style>
<?php
require "connect/sessao.php";
/*
switch ( $id_franquia ){
    
    case 11:
    case 12:
    case 25:
    case 28:
    case 128:
    case 1375:
    case 1378:
        
        echo "
            <p>
            <br>
            <table width='100%' border='0' cellpadding='0' cellspacing='0'>
	          <tr height='20'>
		      <td align='center' width='100%' class='titulo'>
                          <br>
                          Prezado Franqueado:<br><br><br>Estamos reformulando a Premiacao Semanal.<br><br>Prazo: INAUGURACAO DA NOVA SEDE.
                          <br>
                          <br>
                      </td>
                  </tr>
              </table>
              </p>";
        //break;
        exit;
        
}
*/

function mod($nr1,$nr2){
	$val1 = floor($nr1/$nr2);
	$resto = $nr1 -($val1*$nr2);
	return $val1.'-'.$resto;
}

function grava_ranking($id_franquia,$data_cadastro,$hora_cadastro){
	global $con;
	$sql = "select count(*) qtd from cs2.temp_ranking where id_franquia = $id_franquia";
	$qr_sql = mysql_query($sql,$con) or die ("ERRO: $sql");
	$qtd = mysql_result($qr_sql,0,'qtd');
	if ( empty($qtd) ) $qtd = '0';
	if ( $qtd == 0 ) $sql_insert = "INSERT INTO cs2.temp_ranking(qtd,data,id_franquia) VALUES( 1 , '$data_cadastro $hora_cadastro' , $id_franquia )";
	else $sql_insert = "UPDATE cs2.temp_ranking SET qtd = qtd + 1 WHERE id_franquia = $id_franquia";
	$qr_sqlinsert = mysql_query($sql_insert,$con) or die ("ERRO: $sql_insert");
}

function busca_valor_premio($posicao){
	global $con;
	$sql = "select valor_premio from cs2.premio_ranking_vendas where ranking = '$posicao'";
	$qr_sql = mysql_query($sql,$con) or die ("ERRO: $sql");
	$rank = mysql_result($qr_sql,0,'valor_premio');
	if ( empty($rank) ) $rank = '0';
	return $rank;
}

function grava_estrela($id_franquia){
	global $con;
	$sql = "update cs2.franquia set estrela = estrela + 1 where id = '$id_franquia'";
	$qr_sql = mysql_query($sql,$con) or die ("ERRO: $sql");
}

function grava_dindin_ctacorrente($id_franquia, $primeiro, $ultimo, $valor_premio){
	global $con;
	$dt1 = substr($primeiro,8,2).'/'.substr($primeiro,5,2);
	$dt2 = substr($ultimo,8,2).'/'.substr($ultimo,5,2).'/'.substr($ultimo,0,4);
	
	$texto = "Premia&ccedil;&atilde;o Semanal Parabens ($dt1 a $dt2)";
	$sql = "INSERT INTO cs2.contacorrente(franqueado,data,discriminacao,valor,operacao)
			VALUES( '$id_franquia' , now() , '$texto' , $valor_premio , '0' )";
	$qr_sql = mysql_query($sql,$con) or die ("ERRO: $sql");
}

$primeiro = $_POST['primeiro'];
$ultimo = $_POST['ultimo'];
$automatico = $_POST['automatico'];

# Verifico se o processo do automatico ja foi realizado, se foi aborto a opcao informando o usuario.
# Mensagem:  RANKING JA LANCADO NA TABELA

if ( $automatico == 'on' ){
	
	$sql = "select count(*) qtd from cs2.ranking_franquia where data_inicial = '$primeiro' and data_final='$ultimo'";
	$qr_sql = mysql_query($sql,$con) or die ("ERRO: $sql");
	$qtd_rank = mysql_result($qr_sql,0,'qtd');
	if ( empty($qtd_rank) ) $qtd_rank = '0';
	if ( $qtd_rank > 0 ){
		echo "<script>alert(\" RANKING JA LANCADO NA TABELA !\");</script>";
	}
}

//conta quantas vendas foram realizadas no periodo
$query = "SELECT COUNT(*) FROM cadastro WHERE dt_cad BETWEEN '$primeiro' AND '$ultimo'";
$query = mysql_query($query,$con);
$query = mysql_fetch_array($query);
$total = $query[0];
//caso n�o tiver vendas aparece um alerta e volta � pagina anterior
if (!$total) {
    echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>
              <tr height='20'>
                  <td align='center' width='100%' class='titulo'>Sem vendas registradas neste periodo</td>
              </tr>
          </table>";
} else {


// Limpa a tabela tempor�ria
$command = "Delete from cs2.temp_ranking";
$res = mysql_query($command,$con);

//faz o ranking de venda de acordo ao numero de vendas do periodo

$command = "SELECT id_franquia,dt_cad, hora_cadastro 
            FROM cadastro 
            WHERE dt_cad between '$primeiro' AND '$ultimo' AND sitcli < 2
            AND MID(upper(razaosoc),1,9 ) <> 'FRANQUEAD'
            AND contadorsn != 'S'
            ORDER BY id_franquia,dt_cad,hora_cadastro";			
$res = mysql_query($command,$con);

$reg = 0;
while($registro = mysql_fetch_array($res)){
	
	$id_franquia = $registro['id_franquia'];
	$data_cadastro = $registro['dt_cad'];
	$hora_cadastro = $registro['hora_cadastro'];
	$reg++;
			
	# primeiro dia, verificando se o contrato foi cadastrado ap�s as 18:00:00
	if ( $data_cadastro == $primeiro ){
		if ( strtotime($hora_cadastro) > strtotime("18:00:00") )
			grava_ranking($id_franquia,$data_cadastro,$hora_cadastro);
			//echo "FRANQUEADO FECHOU NO PRIMEIRO DIA AP�S AS 18:00:00 <br>";
	}else if ( $data_cadastro == $ultimo ){
		
		# ultimo dia, verificando se o contrato foi cadastrado at� as 18:00:00
		if ( strtotime($hora_cadastro) <= strtotime("18:00:00") )
			//echo "FRANQUEADO FECHOU NO ULTIMO DIA AT� AS 18:00:00 <br>";
			grava_ranking($id_franquia,$data_cadastro,$hora_cadastro);
	}else{
			grava_ranking($id_franquia,$data_cadastro,$hora_cadastro);		
	}
}

$command = "SELECT a.qtd, a.id_franquia, b.fantasia, b.foto, b.estrela, g.nome, g.email, g.fone, 
                    date_format(a.data,'%d/%m/%Y %H:%i:%S') as data
            FROM temp_ranking a
            INNER JOIN franquia b ON a.id_franquia = b.id
            LEFT OUTER JOIN gerente g ON b.id_gerente = g.id
            ORDER BY a.qtd desc, a.data";
$res = mysql_query($command,$con);
$linhas = mysql_num_rows($res);
$linhas1 = $linhas+3;

$total = 0;

//comeca a tabela
echo "<div class=\"titulo\">RANKING SEMANAL DE VENDAS</div><br>";

?>
<style>
.fundo{
	background-image:url(../img/podium_fundo.png);
	height:400px;
	background-repeat:repeat-x;
}
</style>

<!-- FIM DO ESQUEMA PARA MONTAR O PODIUM-->

        <br />
            <table align="center" width="840" border="0" cellpadding="0" cellspacing="0" class="quente">
                <tr>
                    <td colspan="5" height="1" bgcolor="#999999"></td>
                </tr>
                <tr height="20" class="titulo">
                    <td width="30%" align="center">Posi&ccedil;&atilde;o</td>
                    <td width="25%" align="center">Franqueado</td>
                    <td width="5%" align="center">Qtd</td>
                    <td width="30%" align="center">Foto</td>               
                    <td width="15%" align="center">Gerente Franquias</td>				
                </tr>
                <tr>
                    <td colspan="5" height="1" bgcolor="#666666"></td>
                </tr>
        <?php
	$b = 1;
	for ($a=1; $a<=$linhas; $a++) {
            //caso for igual a 0
            $matriz = mysql_fetch_array($res);
            if ($matriz['id_franquia'] != 0) 
                $franqueado = $matriz['id_franquia'];
            else
                $franqueado = -1;
            //pega quantidade, nome de fantasia e foto
            $idx = $matriz['id_franquia'];
            $qtd = $matriz['qtd'];
            $total += $qtd;
            $nome_franquia	= $matriz['fantasia'];
            $foto	= $matriz['foto'];
            $estrela = $matriz['estrela'];
            $nome = $matriz['nome'];
            $email = $matriz['email'];
            $fone = telefoneConverte($matriz['fone']);
            $data_primeiro = $matriz['data'];
            //
            echo "<tr ";
            if (($a%2) == 0) {
                echo "bgcolor=\"#E5E5E5\">";
            } else {
                echo ">";
            }
            if (($b == 1) && ($idx <> 1 and $idx <> 2)) { # primeiro Lugar
                if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
                    grava_estrela($idx);
                    if ($qtd >= 7){
                        $valor_premio = busca_valor_premio('1');
                        grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
                    }
                }
                ?>
                <td align="center">
                    2 º
                </td>
            <?php
            $b = $b +1;
            } elseif (($b == 2) && ($idx <>1 and $idx <> 2)) {
                    if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
                            grava_estrela($idx);
                            if ($qtd >= 7){
                                    $valor_premio = busca_valor_premio('2');
                                    grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
                            }
                    }
                    ?>
                    <td align="center">
                        3 º
                    </td>
            <?php
		$b = $b +1;
		} elseif (($b == 3) && ($idx <> 1 and $idx <> 2)) {
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
				grava_estrela($idx);
				if ($qtd >= 7){
					$valor_premio = busca_valor_premio('3');
					grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
				}
			}

			?>
                <td align="center">
                    4 º
                </td>
                <?php
			$b = $b +1;
                } elseif (($b == 4) && ($idx <> 1 and $idx <> 2)) {
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
				grava_estrela($idx);
				if ($qtd >= 7){
					$valor_premio = busca_valor_premio('4');
					grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
				}
			}
			?>
                <td align="center">
                    5 º
                </td>
			<?php
			$b = $b +1;
		} elseif (($b == 5) && ($idx <> 1 and $idx <> 2)) {
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
				grava_estrela($idx);
				if ($qtd >= 7){
					$valor_premio = busca_valor_premio('5');
					grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
				}
			}

?>
                <td align="center">
                    6 º
                </td>
<?php
		$b = $b +1;
		} elseif (($b == 6) && ($idx <> 1)){
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
				grava_estrela($idx);
				if ($qtd >= 7){
					$valor_premio = busca_valor_premio('6');
					grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
				}
			}

?>
                <td align="center">
                    7 º
                </td>
<?php
		$b = $b +1;
		} elseif (($b == 7) && ($idx <> 1)) {
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
				grava_estrela($idx);
				if ($qtd >= 7){
					$valor_premio = busca_valor_premio('7');
					grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
				}
			}

?>
                <td align="center">
                    8 º
                </td>
<?php
		$b = $b +1;
		} elseif (($b == 8) && ($idx <> 1)) {
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
				grava_estrela($idx);
				if ($qtd >= 7){
					$valor_premio = busca_valor_premio('8');
					grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
				}
			}

?>
                <td align="center">
                    9 º
                </td>
<?php
		$b = $b +1;
		} elseif (($b == 9) && ($idx <> 1)) {
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
				grava_estrela($idx);
				if ($qtd >= 7){
					$valor_premio = busca_valor_premio('9');
					grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
				}
			}

?>
                <td align="center">
                    10 º
                </td>
<?php

		$b = $b +1;
		} elseif (($b == 10) && ($idx <> 1)) {
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
				grava_estrela($idx);
				if ($qtd >= 7){
					$valor_premio = busca_valor_premio('10');
					grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
				}
			}

?>
                <td align="center">
                    11 º
                </td>
<?php
		$b = $b +1;
		} elseif (($b == 11) && ($idx <> 1)) {
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
			//	$valor_premio = busca_valor_premio('11');
			//	grava_estrela($idx);
			//	grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
			}

?>
<td align="center">
    12 º
</td>
<?php
		$b = $b +1;
		} elseif (($b == 12) && ($idx <> 1)) {
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
			//	$valor_premio = busca_valor_premio('12');
			//	grava_estrela($idx);
			//	grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
			}

?>
<td align="center">13 º
<!-- script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','12 lugar','src','../img/12lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/12lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="12 lugar">
    <param name="movie" value="../img/12lugar.swf" />
    <param name="quality" value="high" />
    <embed src="../img/12lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
  </object></noscript -->
 </td>
<?php
		$b = $b +1;
		} elseif (($b == 13) && ($idx <> 1)) {
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
			//	$valor_premio = busca_valor_premio('13');
			//	grava_estrela($idx);
			//	grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
			}

?>
<td align="center">14 º
<!-- script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','13 lugar','src','../img/13lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/13lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="13 lugar">
    <param name="movie" value="../img/13lugar.swf" />
    <param name="quality" value="high" />
    <embed src="../img/13lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
  </object></noscript -->
 </td>
<?php
		$b = $b +1;
		} elseif (($b == 14) && ($idx <> 1)) {
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
			//	$valor_premio = busca_valor_premio('14');
			//	grava_estrela($idx);
			//	grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
			}

?>
<td align="center">15 º
<!-- script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','14 lugar','src','../img/14lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/14lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="14 lugar">
    <param name="movie" value="../img/14lugar.swf" />
    <param name="quality" value="high" />
    <embed src="../img/14lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
  </object></noscript-->
</td>
<?php
		$b = $b +1;
		} elseif (($b == 15) && ($idx <> 1)) {
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
			//	$valor_premio = busca_valor_premio('15');
			//	grava_estrela($idx);
			//	grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
			}

?>
<td align="center">16 º
<!-- script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','15 lugar','src','../img/15lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/15lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="15 lugar">
    <param name="movie" value="../img/15lugar.swf" />
    <param name="quality" value="high" />
    <embed src="../img/15lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
  </object></noscript -->
 </td>
<?php
		$b = $b +1;
		} elseif (($b == 16) && ($idx <> 1)) {
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
			//	$valor_premio = busca_valor_premio('16');
			//	grava_estrela($idx);
			//	grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
			}

?>
<td align="center">17 º
<!-- script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','16 lugar','src','../img/16lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/16lugar' ); //end AC code
</script>
  <noscript>
  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="16 lugar">
    <param name="movie" value="../img/16lugar.swf" />
    <param name="quality" value="high" />
    <embed src="../img/16lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
  </object>
  </noscript -->
  </td>
<?php
		$b = $b +1;
		} elseif (($b == 17) && ($idx <> 1)) {
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
			//	$valor_premio = busca_valor_premio('17');
			//	grava_estrela($idx);
			//	grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
			}

?>
<td align="center">18 º
<!-- script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','17 lugar','src','../img/17lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/17lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="17 lugar">
    <param name="movie" value="../img/17lugar.swf" />
    <param name="quality" value="high" />
    <embed src="../img/17lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
  </object></noscript -->
</td>
<?php
		$b = $b +1;
		} elseif (($b == 18) && ($idx <> 1)) {
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
			//	$valor_premio = busca_valor_premio('18');
			//	grava_estrela($idx);
			//	grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
			}

?>
<td align="center">19 º
<!-- script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','18 lugar','src','../img/18lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/18lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="18 lugar">
    <param name="movie" value="../img/18lugar.swf" />
    <param name="quality" value="high" />
    <embed src="../img/18lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
  </object></noscript -->
</td>
<?php
		$b = $b +1;
		} elseif (($b == 19) && ($idx <> 1)) {
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
			//	$valor_premio = busca_valor_premio('19');
			//	grava_estrela($idx);
			//	grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
			}

?>
<td align="center">20 º
<!-- script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','19 lugar','src','../img/19lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/19lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="19 lugar">
    <param name="movie" value="../img/19lugar.swf" />
    <param name="quality" value="high" />
    <embed src="../img/19lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
  </object></noscript -->
</td>
<?php
		$b = $b +1;
		} elseif (($b == 20) && ($idx <> 1)) {
			if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
			//	$valor_premio = busca_valor_premio('20');
			//	grava_estrela($idx);
			//	grava_dindin_ctacorrente($idx, $primeiro, $ultimo, $valor_premio);
			}

?>
<td align="center">21 º
<!-- script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','95','title','20 lugar','src','../img/20lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/20lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="95" title="20 lugar">
    <param name="movie" value="../img/20lugar.swf" />
    <param name="quality" value="high" />
    <embed src="../img/20lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="280" height="95"></embed>
  </object></noscript -->
</td>
<?php
		$b = $b +1;
		} else { echo "<td align=\"center\">$a &ordm;</td>";}
		echo "	  <td align=\"center\">";
		if (!empty($estrela)) {
			$resto = mod($estrela,5);

			$array = explode('-',$resto);
			
			$diamante = $array[0];
			$star = $array[1];
			
			for($i=0;$i<$diamante;$i++) {
				echo "<img src=\"../img/diamante.gif\">";
			}
			for($j=1;$j<=$star;$j++){
					echo "<img src=\"../img/estrela.gif\">";
			}

			echo "<br>";
		}
		
		$sql     = "SELECT nome_foto FROM cs2.franquia_foto WHERE id_franquia = $idx";
		$qry_sql = mysql_query($sql,$con);
		$link_foto = '';
		if ( mysql_num_rows($qry_sql) > 0 ){
			while ( $reg_foto = mysql_fetch_array($qry_sql) ){
				$link_foto .= '<img src=area_restrita/'.$reg_foto['nome_foto'].'>';
			}
		}else
			$link_foto = "<img src=ranking/d_gera.php?idx=$idx>";
	

		echo "$nome_franquia<br>Primeiro Ctr: $data_primeiro</td>
			  <td align=\"center\"><font color=\"#006666\">&nbsp;$qtd</font></font></td>
			  <td align=\"center\">$link_foto</td>	
			  <td align=\"center\">
			  	<font color='blue'>$nome</font><br>
				Fone: $fone
				<div class='fonte'>$email
			  </td>
			  ";
	  	 echo "</tr>";
	} //fim do for
	
	$nprimeiro = substr($primeiro,8,2).'/'.substr($primeiro,5,2).'/'.substr($primeiro,0,4);
	$nultimo = substr($ultimo,8,2).'/'.substr($ultimo,5,2).'/'.substr($ultimo,0,4);

	echo "<tr>
				<td colspan=\"5\" align=\"right\" class=\"titulo\">
					<b>Total de vendas no per&iacute;odo:&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#ff6600\">$total</font></b> 
					<br>
					$nprimeiro (ap&oacute;s as 18:00:01) at&eacute; $nultimo (at&eacute; as 18:00:00)<br><br>					
				</td>
			</tr>
			<tr><td colspan=\"5\" align=\"center\"><input type=\"button\" onClick=\"javascript: history.back();\" value=\"       Voltar       \" /><td/></tr>
		</table>";
	}
	
if ( ( $automatico == 'on' ) and ( $qtd_rank == '0' ) ){
	$sql = "INSERT INTO cs2.ranking_franquia(data_inicial,data_final)
			VALUES('$primeiro','$ultimo')";
	$qr_sql = mysql_query($sql,$con) or die ("ERRO: $sql");
}

$res = mysql_close ($con);

?>
<script src="../../../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<p></p>
<?php 
// include "ranking/mensagens.php";
?>