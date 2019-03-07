<?php
require "connect/sessao_r.php";
$nome2 = $_SESSION['ss_restrito'];
?>
<script language="javascript">
function trim(str){return str.replace(/^\s+|\s+$/g,"");}//valida espaçoo em branco
function mostrar(id){
	if (document.getElementById(id).style.display == 'none')
	{
		document.getElementById(id).style.display = '';
	}
}

function ocultar(id){
	document.getElementById(id).style.display = 'none';
}

function pesquisa(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=area_restrita/d_repasse_listagem.php';
	frm.submit();
}

function retorna(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=area_restrita/d_crediario_recupere.php';
	frm.submit();
}

function valida(){
	frm = document.form;	
	if (frm.codigo[0].checked){
		if(trim(frm.logon.value) == ""){
			alert("Se a Opção escolhida foi Individual, então deve ser informado o Código !");
			frm.logon.focus();
			return false;
		}	
	}
	 pesquisa();
}
</script> 

<form method="post" action="#" name="form">
<table width="70%" border="0" align="center">
  <tr>
    <td colspan="2" class="titulo">RELAT&Oacute;RIO DE REPASSES</td>
  </tr>
  <tr>
    <td width="38%" class="subtitulodireita">&nbsp;</td>
    <td width="62%" class="campoesquerda">&nbsp;</td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Franquia</td>
    <td class="campoesquerda">
		<?php
    	if (($tipo == "a") || ($tipo == "c") || ($tipo == "d")) {  
			echo "<select name=\"franqueado\">";
				echo "<option value='' selected>Todas as Franquias</option>";
			$sql = "select id, fantasia from franquia where sitfrq=0 order by id";
			$resposta = mysql_query($sql, $con);
			while ($array = mysql_fetch_array($resposta)) {
				$franquia   = $array["id"];
				$nome_franquia = $array["fantasia"];
				echo "<option value=\"$franquia\">$nome_franquia</option>\n";
			}
			echo "</select>";
		}
		else {
			echo $nome_franquia;
			echo "<input name=\"franqueado\" type=\"hidden\" id=\"franqueado\" value= $id_franquia />";
			}
		?>    </td>
  </tr>
  <tr>
    <td class="subtitulodireita"></td>
    <td class="campoesquerda">
        Individual<input type="radio" name="codigo" value="1" onFocus="mostrar('idi');return true;"/>		
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Todos<input type="radio" name="codigo" value="2" onFocus="ocultar('idi');return true;" checked/>     
    </td>
  </tr>  
 
  <tr id="idi" style="display:none;">
    <td class="subtitulodireita">C&oacute;digo&nbsp;</td>
    <td class="campoesquerda"><input type="text" name="logon" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal';"/></td>
  </tr>		
	
   <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td align="center">
    	<input type="button" name="pesq1" value="    Pesquisar    " onclick="valida()" style="cursor:pointer"/>
        &nbsp;&nbsp;&nbsp;
    	<input name="button" type="button" onClick="retorna()" value="       Voltar       " style="cursor:pointer"/>
    </td>
  </tr>
</table>
</form>
