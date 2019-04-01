<?php
require "connect/sessao.php";

if (isset($_GET['var']) && $_GET['var'] == "return") {
	//header("Location: ".$_SERVER['HTTP_REFERER']);
	echo '<script language="javascript">location.href=\"'.$_SERVER['HTTP_REFERER'].'\"</script>';
}
?>
<script type="text/javascript" language="javascript">
//função para aceitar somente numeros em determinados campos
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
// formato mascara data
function data(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que n�o � d�gito
    v=v.replace(/(\d{2})(\d)/,"$1/$2")
    v=v.replace(/(\d{2})(\d)/,"$1/$2")

    return v
}

function habilitaVenc() {
	d = document.form1;
	if (d.situacao.value === "and a.datapg is null and a.vencimento<=now()"){
		d.vencimento1.disabled = true;
		d.vencimento2.disabled = true;
		d.periodo.disabled = true;
		d.vencimento1.value = "";
		d.vencimento2.value = "";
		d.cobranca.checked = true;
		d.vencimento1.style.backgroundColor = "#CCCCCC";
		d.vencimento2.style.backgroundColor = "#CCCCCC";
		
	//	d.vencimento1.disabled = false;
	//	d.vencimento2.disabled = false;
	//	d.cobranca.disabled = false;
	//	d.cobranca.checked = false;
	//	d.vencimento1.style.backgroundColor = "#FFFFFF";
	//	d.vencimento2.style.backgroundColor = "#FFFFFF";
	//	d.periodo.disabled = false;


	}
	else {
		d.vencimento1.disabled = false;
		d.vencimento2.disabled = false;
		d.cobranca.disabled = false;
		d.cobranca.checked = false;
		d.vencimento1.style.backgroundColor = "#FFFFFF";
		d.vencimento2.style.backgroundColor = "#FFFFFF";
		d.periodo.disabled = false;
	}
	return true;
}

</script>

<form name="form1" method="post" action="painel.php?pagina1=Franquias/b_baixaf.php">
<table width="90%" border="0" align="center">
  <tr class="titulo">
    <td colspan="2">RELATORIOS DE FATURAS</td>
  </tr>
  <tr>
    <td width="25%" class="subtitulodireita">&nbsp;</td>
    <td width="75%" class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Situa&ccedil;&atilde;o</td>
    <td class="campoesquerda"><select name="situacao" onChange="habilitaVenc(this)" class="boxnormal">
      <option value=" " selected="selected">Todos</option>
      <option value="and a.datapg is not null ">Quitados</option>
      <option value="and a.datapg is null and a.vencimento<=now()">N&atilde;o Quitados</option>
							</select>	</td>
  </tr>
  <?php
		if ($tipo == "a"){
			 echo "<tr><td class=\"subtitulodireita\">C&oacute;digo</td><td class=\"campoesquerda\"><input name=\"codigo1\" type=\"text\" id=\"codigo1\" size=\"10\" maxlength=\"7\" onKeyPress=\"mascara(this,soNumeros)\" onFocus=\"this.className='boxover'\" class=\"boxnormal\" onBlur=\"this.className='boxnormal'\" /></td></tr>";
		}
  ?>
  <tr>
    <td class="subtitulodireita">    
		  Vencimento entre <input type="checkbox" value="1" name="periodo" checked /></td>
    <td class="campoesquerda">
		<input type="text" name="vencimento1" maxlength="10" size="12" onKeyPress="mascara(this,data)" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      	&nbsp;e&nbsp;
      	<input type="text" name="vencimento2" maxlength="10" size="12" onKeyPress="mascara(this,data)" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /> 
      	dd/mm/aaaa	</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Franquia</td>
    <td class="campoesquerda">
			<?php
		if (($tipo == "a") || ($tipo == "c") || ($tipo == "d")) {  
			echo "<select name=\"franqueado\" class=\"boxnormal\" >";
			if ($tipo <> "b" ) echo "<option value=\"todos\" selected>Todas as Franquias</option>";
			$sql = "select id, fantasia from franquia where sitfrq=0 and id_franquia_master = 0 order by id";
			$resposta = mysql_query($sql, $con);
			while ($array = mysql_fetch_array($resposta)) {
				$franquia   = $array["id"];
				$nome_franquia2 = $franquia.' - '.$array["fantasia"];
				echo "<option value=\"$franquia\">$nome_franquia2</option>\n";
			}
			echo "</select>";
		}
		else {
			echo $nome_franquia2;
			echo "<input name=\"franqueado\" type=\"hidden\" id=\"franqueado\" value= $id_franquia />";
			}
			?>
	 </td>
  </tr>
  <tr>
    <td class="subtitulodireita">
      <input type="checkbox" name="cobranca" id="cobranca"></td>
    <td class="campoesquerda">Excluir <font color="#FF0000"><B>PODRE</B></font> da lista de Cobran&ccedil;a</td>
  </tr>
  <tr>
    <td class="subtitulodireita">
      <input type="checkbox" name="flag_multa" id="flag_multa"></td>
    <td class="campoesquerda">Excluir boleto de MULTA da lista de Cobran&ccedil;a</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Ordem por: </td>
    <td class="campoesquerda">
		<table>
			<tr>
			  <td class="subtitulopequeno"><input type="radio" name="ordem" value="numdoc" /></td>
			  <td class="subtitulopequeno">Documento</td>
		  </tr>
			<tr>
			  <td class="subtitulopequeno"><input type="radio" name="ordem" value="logon" checked /></td>
			  <td class="subtitulopequeno">C&oacute;digo</td>
		  </tr>
			<tr>
			  <td class="subtitulopequeno"><input type="radio" name="ordem" value="c.razaosoc" /></td>
			  <td class="subtitulopequeno">Raz&atilde;o</td>
		  </tr>
			<tr>
			  <td class="subtitulopequeno"><input type="radio" name="ordem" value="valor" /></td>
			  <td class="subtitulopequeno">Valor</td>
		  </tr>
			<tr>
			  <td class="subtitulopequeno"><input type="radio" name="ordem" value="a.vencimento asc" /></td>
			  <td class="subtitulopequeno">Vencimento</td>
		  </tr>
			<tr>
			  <td class="subtitulopequeno"><input type="radio" name="ordem" value="datapg" /></td>
			  <td class="subtitulopequeno">Dt. Pagto. </td>
			</tr>
		</table>    </td>
  </tr>
  <!--
  <tr>
    <td class="subtitulodireita">N&uacute;mero de registros por p&aacute;gina</td>
    <td class="campoesquerda"><select name="lpp" class="boxnormal">
	  <option value="0">Lista em p&aacute;gina &uacute;nica</option>
	  <option value="10">10</option>
      <option value="20">20</option>
      <option value="30">30</option>
	  <option value="50">50</option>
    </select></td>
  </tr>
  <tr>
    <td class="subtitulodireita">
      <input type="checkbox" name="excel">
    </td>
    <td class="campoesquerda">&nbsp;Gerar uma planilha do Excel</td>
  </tr>
  -->
	<?php
	if ($tipo == "a"){	
	?>
	<tr>
	    <td class="subtitulodireita">Atualizar Tabela Titulo ?</td>
    	<td class="campoesquerda">
        	<select name="at_tabela" class="boxnormal">
      			<option value="SIM">Sim</option>
      			<option value="NAO" selected="selected">N&atilde;o </option>
			</select>	
		</td>
  	</tr>
  	<?php
    }
    ?>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda"><?php echo $nome_franquia; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center"><input type="submit" value="Enviar Consulta" />
    <input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " /></td>
  </tr>
</table>
</form>