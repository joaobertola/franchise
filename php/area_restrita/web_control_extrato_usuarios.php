<?php
require "connect/sessao.php";

$mes_atual = date('m');
$ano_atual = date('y');
?>
<script type="text/javascript" language="javascript">
function habilitaVenc() {
	d = document.form1;
	if (d.contano.value === "todos"){
		d.contmes.disabled = true;
		d.contmes.value = "todos";
		d.contmes.style.backgroundColor = "#CCCCCC";
	}
	else {
		d.contmes.disabled = false;
		d.contmes.style.backgroundColor = "#FFFFFF";
	}
return true;
}

window.onload = function() {
	document.form1.codigo.focus(); 
}
</script>
<style type"text/css">
form {margin: 30px 50px 0;}
form input, select {
	font-family: Arial;
	font-size: 8pt;
}
</style>

<form name="form1" method="post" action="painel.php?pagina1=area_restrita/web_control_listagem_usuarios.php">
<table width=90% border="0" align="center" cellpadding="1" cellspacing="1">
  <tr class="titulo">
    <td colspan="2" align="center" height="40">EXTRATO DE CONTRATOS WEB-CONTROL</td>
  </tr>
  
  <tr>
    <td width="30%" class="subtitulodireita">Ano</td>
    <td width="70%" class="campoesquerda">
    <select name="contano" size="1" class="formulariopequeno" onChange="habilitaVenc(this)" style="width:20%">
      <?php 
	  $ano = date("Y");
	  for($i=2010; $i <= $ano; $i++){ ?>
        <option value="<?=$i?>" <?php if($ano == $i){?> selected="selected" <?php } ?>><?=$i?></option>
      <?php } ?>       
      <option value="todos">Todos</option>      
    </select>        
	</td>
  </tr>
  <tr>
    <td class="subtitulodireita">M&ecirc;s</td>
    <td class="campoesquerda">
	<select name="contmes" size="1" class="formulariopequeno" style="width:20%">
	  <option value="0">Todos</option>
	  <option value="01" <?php if ($mes_atual == "01"){ echo "selected"; }?>>Janeiro</option>
      <option value="02" <?php if ($mes_atual == "02"){ echo "selected"; }?>>Fevereiro</option>
      <option value="03" <?php if ($mes_atual == "03"){ echo "selected"; }?>>Mar&ccedil;o</option>
      <option value="04" <?php if ($mes_atual == "04"){ echo "selected"; }?>>Abril</option>
      <option value="05" <?php if ($mes_atual == "05"){ echo "selected"; }?>>Maio</option>
      <option value="06" <?php if ($mes_atual == "06"){ echo "selected"; }?>>Junho</option>
      <option value="07" <?php if ($mes_atual == "07"){ echo "selected"; }?>>Julho</option>
      <option value="08" <?php if ($mes_atual == "08"){ echo "selected"; }?>>Agosto</option>
      <option value="09" <?php if ($mes_atual == "09"){ echo "selected"; }?>>Setembro</option>
      <option value="10" <?php if ($mes_atual == "10"){ echo "selected"; }?>>Outubro</option>
      <option value="11" <?php if ($mes_atual == "11"){ echo "selected"; }?>>Novembro</option>
      <option value="12" <?php if ($mes_atual == "12"){ echo "selected"; }?>>Dezembro</option>
	</select>    </td>
  </tr>
  <tr>
    <td class="subtitulodireita"><input type="radio" value="AI" name="ativo" checked /></td>
    <td class="campoesquerda">Extrato de <font color="#FF0000">TODOS</font> os Usu&aacute;rios </td>
  </tr>
  <tr>
    <td class="subtitulodireita"><input type="radio" value="A" name="ativo" /></td>
    <td class="campoesquerda">Extrato de Usu&aacute;rios  <font color="#FF0000">ATIVOS</font></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita"><input type="radio" value="I" name="ativo" /></td>
    <td class="campoesquerda">Extrato de Usu&aacute;rios <font color="#FF0000">CANCELADOS </font></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Ordenar por </td>
    <td class="campoesquerda">
    	<input type="radio" name="ordenacao" value="c.logon" checked />C&oacute;digo&nbsp;&nbsp;
        <input type="radio" name="ordenacao" value="a.nomefantasia" />Nome de fantas&iacute;a&nbsp;&nbsp;      
        <input type="radio" name="ordenacao" value="b.id_cadastro" />Data de contrata&ccedil;&atilde;o&nbsp;&nbsp; 
        <?php if ($tipo == "a"){ ?>
        <input type="radio" name="ordenacao" value="b.login_master" />Master&nbsp;&nbsp;
        <input type="radio" name="ordenacao" value="b.ativo" />Ativo
        <?php } ?>
    </td>
  </tr>
  
  <?php if ( ($tipo == "a") or ($tipo == "c") ){ ?>
  <tr>
    <td class="subtitulodireita">Franquia</td>
    <td class="campoesquerda">		
        	<select name="franqueado" style="width:70%">
            	<option value="0">Todas</option>
			<?php    
                $sql = "SELECT id, fantasia FROM franquia WHERE sitfrq=0 ORDER BY id";
                $resposta = mysql_query($sql, $con);
                while ($rs = mysql_fetch_array($resposta)){?>
                    <option value="<?=$rs['id']?>"><?=$rs['id']?> - <?=$rs['fantasia']?></option>
            <?php }	?>	
            </select>    	
    </td>
  </tr>
  <?php }	?>	  
  
  <tr>
    <td class="subtitulodireita">C&oacute;digo</td>
    <td class="campoesquerda"><input maxlength="6" type="text" name="codigo" class="formulariopequeno" style="width:20%">&nbsp;<font color="#FF0000"><b><u>Se for escolhida essa op&ccedil;&atilde;o pode ser ignorada as restantes acima</u></b></font></td>
  </tr>
          
  <tr>
   <td height="40">&nbsp;</td>
   <td>
   	<input type="submit" name="pesq1" value="    Pesquisar    " />
    &nbsp;&nbsp;
    <input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " />
   </td>
    </tr>
</table>
</form>