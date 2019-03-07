<?php
require "connect/sessao_r.php";

function dateDiff2($sDataInicial, $sDataFinal){  
	$sDataI = explode("-", $sDataInicial);
	$sDataF = explode("-", $sDataFinal);
	$nDataInicial = mktime(0, 0, 0, $sDataI[1], $sDataI[0], $sDataI[2]);
	$nDataFinal = mktime(0, 0, 0, $sDataF[1], $sDataF[0], $sDataF[2]); 
	return ($nDataInicial > $nDataFinal) ? 
	       floor(($nDataInicial - $nDataFinal)/86400) : floor(($nDataFinal - $nDataInicial)/86400);
}

$nome2 = $_SESSION['ss_restrito'];

//echo "<PRE>";
//print_r($_SESSION);

if($_SESSION['ss_classificacao'] == "J"){
	$sql_master = "SELECT razaosoc FROM franquia WHERE id = '{$_SESSION['id_master']}'";
	$qry_master = mysql_query($sql_master);
	$franquia = mysql_result($qry_master,0,'razaosoc');	
}else{
	$franquia = $_SESSION['fantasia'];	
}

//recebe as vari�veis
$go	= $_POST['go'];

$franqueado = $_SESSION['id'];
$mes = $_POST['mes'];
$ano = $_POST['ano'];

//declara mes e ano atual
$mes_atual = date('m');
$ano_atual = date('y');
?>
<script language="javascript">
//fun��o para aceitar somente numeros em determinados campos
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function soNumeros(v){
    return v.replace(/\D/g,"")
}

function relatorio(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=franqueado_jr/repasse_listagem.php';
	frm.submit();
 }
 
function refresch(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=franqueado_jr/repasse.php';
	frm.submit();
 } 
</script>
<style type="text/css">
form {margin: 30px 50px 0;}
form input, select {
	font-family: Arial;
	font-size: 8pt;
}
</style>
<br>
<form method="post" action="#" name="form">
<table width=70% border="0" align="center">
  <tr class="titulo">
    <td colspan="2">Repasse - Franqueado Junior</td>
  </tr>
  <tr>
    <td width="30%" class="subtitulodireita">&nbsp;</td>
    <td width="70%" class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita"><label for="mes">M&ecirc;s e Ano de Refer&ecirc;ncia</label></td>
    <td class="campoesquerda">
      	<select name="mes" id="mes">
          <option value="01" <?php if ($mes_atual == "02"){ echo "selected"; }?>>Janeiro</option>
          <option value="02" <?php if ($mes_atual == "03"){ echo "selected"; }?>>Fevereiro</option>
          <option value="03" <?php if ($mes_atual == "04"){ echo "selected"; }?>>Mar&ccedil;o</option>
          <option value="04" <?php if ($mes_atual == "05"){ echo "selected"; }?>>Abril</option>
          <option value="05" <?php if ($mes_atual == "06"){ echo "selected"; }?>>Maio</option>
          <option value="06" <?php if ($mes_atual == "07"){ echo "selected"; }?>>Junho</option>
          <option value="07" <?php if ($mes_atual == "08"){ echo "selected"; }?>>Julho</option>
          <option value="08" <?php if ($mes_atual == "09"){ echo "selected"; }?>>Agosto</option>
          <option value="09" <?php if ($mes_atual == "10"){ echo "selected"; }?>>Setembro</option>
          <option value="10" <?php if ($mes_atual == "11"){ echo "selected"; }?>>Outubro</option>
          <option value="11" <?php if ($mes_atual == "12"){ echo "selected"; }?>>Novembro</option>
          <option value="12" <?php if ($mes_atual == "01"){ echo "selected"; }?>>Dezembro</option>
		</select>
      <select name="ano" size="1" >
        <option value="2015" <?php if ($ano_atual == "15"){ echo "selected"; }?>> 2015 </option>
        <option value="2014" <?php if ($ano_atual == "14"){ echo "selected"; }?>> 2014 </option>
        <option value="2013" <?php if ($ano_atual == "13"){ echo "selected"; }?>> 2013 </option>
    	<option value="2012" <?php if ($ano_atual == "12"){ echo "selected"; }?>> 2012 </option>
        <option value="2011" <?php if ($ano_atual == "11"){ echo "selected"; }?>> 2011 </option>
        <option value="2010" <?php if ($ano_atual == "10"){ echo "selected"; }?>> 2010 </option>
        <option value="2009" <?php if ($ano_atual == "09"){ echo "selected"; }?>> 2009 </option>
        <option value="2008" <?php if ($ano_atual == "08"){ echo "selected"; }?>> 2008 </option>
        <option value="2007" <?php if ($ano_atual == "07"){ echo "selected"; }?>> 2007 </option>
        <option value="2006"> 2006 </option>
        <option value="2005"> 2005 </option>
      </select>
      <input type="hidden" name="go" value="ingressar" />	</td>
  </tr>
	<tr>
		<td class="subtitulodireita">&nbsp;</td>
	    <td class="campoesquerda">&nbsp;</td>
	</tr>
   
   <?php if($tipo == "a"){?>
   <tr>
   		<td class="subtitulodireita">Franquia MASTER</td>
        <td class="campoesquerda">
        <select name="franqueado" style="width:85%">
        	<option value="0">&nbsp;</option>
        <?php
			$sql = "SELECT id, fantasia FROM franquia WHERE classificacao != 'J' ORDER BY id";
			$resposta = mysql_query($sql,$con);
			while ($array = mysql_fetch_array($resposta))
			{
				$id = $array["id"];
				$nome_franquia = $array["fantasia"];
				if($franqueado == $id){
					echo "<option value='$id'  selected='selected'>$id - $nome_franquia</option>";
				}else
					echo "<option value='$id'>$id - $nome_franquia</option>";
					
			}
		?>
        </select>&nbsp;<input type="button" value="OK" onclick="refresch()" style="cursor:pointer"></td>
   </tr> 
   <?php }else{ ?> 
   <tr>
		<td class="subtitulodireita">Franquia MASTER</td>
	    <td class="campoesquerda"><?=$franquia?></td>
   </tr>
   <?php } ?>
   
   <tr>
    <td class="subtitulodireita">Franquia Junior</td>
    <td class="campoesquerda">
    <select name="id_franquia_jr" style="width:85%">
		<option value="0">&nbsp;</option>
      
      
      
      <?php
	  
	  print_r($_SESSION);
	  		# Caso id_master for igual a ZERO, id_master ser� igual a id
			$xid = $_SESSION['id_master'];
			$sql_jr = "	SELECT id, id_franquia_master, razaosoc FROM cs2.franquia 
						WHERE 1=1 ";
			if($tipo == "a"){
				$sql_jr .= " AND id_franquia_master = '{$_REQUEST['franqueado']}' ";
			}else{
				$franqueado =  $_SESSION["id"];
				if ( $xid == '0' ) $sql_jr .= " AND id_franquia_master = '$franqueado' ";
				else $sql_jr .= " AND id_franquia_master = '$xid' AND id = '$franqueado'";
			}
			$sql_jr .= " AND classificacao = 'J' AND id_franquia_master > 0";
		   
		   echo $sql_jr;
		   
		   $resp_jr = mysql_query($sql_jr,$con);
		   if(mysql_num_rows($resp_jr) > 0){		   
			   while($row_jr = mysql_fetch_array($resp_jr)){
					$id_franquia_jr_row = $row_jr["id"];
					$id_franquia_master = $row_jr["id_franquia_master"];
					$razaosoc  = $row_jr["razaosoc"];
					echo "<option value='$id_franquia_jr_row' selected='selected'>$id_franquia_jr_row - $razaosoc</option>";							
			   }		
		   }	
       ?>
      </select>
      </td>
   </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">
		<input name="enviar" type="button" id="enviar" value="         Verificar" onclick="relatorio()" style="cursor:pointer"/>	</td>
  </tr>
</table>
</form>
