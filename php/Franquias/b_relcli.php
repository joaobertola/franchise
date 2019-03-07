<?
require "connect/sessao.php";
?>
<script src="../js/funcoes.js"></script>


<script>

	function MM_formtCep(e,src,mask) {
		if(window.event) { _TXT = e.keyCode; }
		else if(e.which) { _TXT = e.which; }
		if(_TXT > 47 && _TXT < 58) {
			var i = src.value.length; var saida = mask.substring(0,1); var texto = mask.substring(i)
			if (texto.substring(0,1) != saida) { src.value += texto.substring(0,1); }
		return true; } else { if (_TXT != 8) { return false; }
		else { return true; }
	}
}
  function Carrega_bairro_Cidade_Franquia(){
	alert('ok');
}
</script>

<script src="prototype.js"></script>
<script language="javascript">   
function CarregaCidade_Franquia(id_franquia) {
	if(id_franquia){
		var myAjax = new Ajax.Updater('cidadeAjax','carrega_cidade_franquias.php?id_franquia='+id_franquia,
		{
			method : 'get',
		});
	}
}
</script>


<body>
<form method="post" action="painel.php?pagina1=Franquias/b_relcligeral.php" name='listacompleta' id='listacompleta'>
<table width=70% border="0" align="center">
  <tr class="titulo">
    <td colspan="2">INFORME OS PAR&Acirc;METROS PARA PESQUISA </td>
  </tr>	
        <td class="subtitulodireita">&nbsp;</td>
      <td class="campoesquerda">&nbsp;</td>
  </tr>
      <tr>
        <td class="subtitulodireita">Listar clientes</td>
        <td class="campoesquerda"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="5%"><input type="radio" name="status" value="1" checked="checked" />            </td>
            <td>Ativos/Bloqueados</td>
          </tr>
          <tr>
            <td width="5%"><input type="radio" name="status" value="2" />            </td>
            <td>Cancelados</td>
          </tr>
          <tr>
            <td width="5%"><input type="radio" name="status" value="3" />            </td>
            <td>Todos</td>
          </tr>
        </table></td>
      </tr>
	  
      <tr>
        <td class="subtitulodireita">Franquia</td>
        <td class="subtitulopequeno">
	<?php
    	if ( $id_franquia == 4 or $id_franquia == 46 or $id_franquia == 163 or $id_franquia == 1204 ) $todas = '';
		else $todas = "AND id = $id_franquia";
		echo "<select name='id_franquia' id='id_franquia' onchange='CarregaCidade_Franquia(this.value)'>";
		$sql = "select * from franquia where sitfrq='0' $todas order by id";
		$resposta = mysql_query($sql, $con);
		$txt_franquia = "<option value='0'>Selecione a Franquia</option>";
		while ($array = mysql_fetch_array($resposta))
			{
			$franquia   = $array["id"];
			$nome_franquia = $array["fantasia"];
			$txt_franquia .="<option value=\"$franquia\">$nome_franquia</option>\n";
			}
			echo $txt_franquia;
		echo "</select>";
		?>
        </td>
      </tr>

      <tr>
		<td class="subtitulodireita">Escolha o cidade:</td>
		<td class="subtitulopequeno">
          <div id="cidadeAjax">
			<select name="cidade" id="cidade" onClick="Carrega_bairro_Cidade_Franquia()">
				<option value="">Selecione a cidade</option>
			</select>
		  </div>
		</td>                                                                                
	  </tr>

  <tr>
    <td colspan="2" class="titulo" align="center">
    	<input type="submit" name="enviar" value="    Pesquisar    " />
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td></td>
  </tr>
</table>
</form>
<br>
<form method="post" action="painel.php?pagina1=Franquias/b_relatendimento_externo.php" name='listacompleta' id='listacompleta'>
<table width=70% border="0" align="center">
  <tr class="titulo">
    <td colspan="2">Relat&oacute;rio de Atendimento Externo</td>
  </tr>	
        <td class="subtitulodireita">&nbsp;</td>
      <td class="campoesquerda">&nbsp;</td>
  </tr>
	  
      <tr>
        <td class="subtitulodireita">Per&iacute;odo</td>
        <td class="subtitulopequeno">
        	<input type="text" name="data1" id="data1" onChange="" onKeyPress="return MM_formtCep(event,this,'##/##/####');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="10"> a
            <input type="text" name="data2" id="data2" onChange="" onKeyPress="return MM_formtCep(event,this,'##/##/####');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="10">
            
        </td>
      </tr>

      <tr>
		<td class="subtitulodireita">Franquiax:</td>
		<td class="subtitulopequeno">
          <div id="cidadeAjax">
            <?php
                if (($tipo == "a") || ($tipo == "c")) $todas = '';
                else $todas = "AND id = $id_franquia";
		echo "<select name='id_franquia' id='id_franquia' onchange='CarregaCidade_Franquia(this.value)'>";
		echo $sql = "select * from franquia where sitfrq='0' $todas order by id";
		$resposta = mysql_query($sql, $con);
		$txt_franquia = "<option value='0'>Selecione a Franquia</option>";
		while ($array = mysql_fetch_array($resposta))
			{
			$franquia   = $array["id"];
			$nome_franquia = $array["fantasia"];
			$txt_franquia .="<option value=\"$franquia\">$nome_franquia</option>\n";
			}
			echo $txt_franquia;
		echo "</select>";
		?>
          </div>
		</td>                                                                                
	  </tr>

  <tr>
    <td colspan="2" class="titulo" align="center">
    	<input type="submit" name="enviar" value="    Processar    " />
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td></td>
  </tr>
</table>
</form>



</body>
</html>
