<?php
require "connect/sessao_r.php";
$nome2 = $_SESSION['ss_restrito'];
if (!isset($nome2) && ($tipo != "a")) exit;

$codloja  = $_GET['codloja'];
$razaosoc = $_GET['razaosoc'];
$logon 	  = $_GET['logon'];
?>
<script>
//função para validar clientes no cadastramento
function validaClientes(){
// validar valor pago
d = document.recomenda;
if (d.descricao.value == ""){
	alert("O campo " + d.descricao.name + " deve ser preenchido!");
	d.descricao.focus();
	return false;
}
// validar data de pagamento
if (d.valor.value == ""){
	alert("O campo " + d.valor.name + " deve ser preenchido!");
	d.valor.focus();
	return false;
}
return true;
}
</script>

<form name="recomenda" onSubmit="return validaClientes();" method="post" action="painel.php?pagina1=Franquias/b_recomenda2.php">
<table border="0" align="center" width="643">
  <tr>
    <td colspan="2" class="titulo" align="center">INDICA&Ccedil;&Atilde;O DE CLIENTES </td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
  </tr>
  <tr>
    <td width="50%" class="subtitulodireita">ID</td>
    <td width="50%" class="subtitulopequeno">
    	<?php echo $codloja; ?>
		<input type="hidden" name="codloja" value="<?php echo $codloja; ?>"  />
	</td>
  </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo de Cliente </td>
    <td class="subtitulopequeno">
      <?php echo $logon; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Raz&atilde;o Social</td>
    <td class="subtitulopequeno"><?php echo $razaosoc; ?></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Descri&ccedil;&atilde;o</td>
    <td class="subtitulopequeno">
      <input type="text" name="descricao" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" size="50" maxlength="200" />
    </td>
  </tr>
  <!--<tr>
    <td class="subtitulodireita">Enviado SERASA</td>
    <td class="formulario" <?php /*if ($matriz['autorizado_serasa'] == "AUTORIZADO") {
		echo "bgcolor=\"#33CC66\"";}
		else {
		echo "bgcolor=\"#FF0000\"";} */
		?>><font color="#FFFFFF"><?php //echo $matriz['autorizado_serasa']; ?></font></td>
  </tr>-->
  <tr>
    <td class="subtitulodireita">Valor</td>
    <td class="subtitulopequeno">
      <input type="text" name="valor" size="16" maxlength="12" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeydown="FormataValor(this,20,event,2)" style="text-align:right" >
    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">D&eacute;bito</td>
    <td class="subtitulopequeno"><label>
      <select name="tipo" class="boxnormal">
        <option value="CREDITO">Cr&eacute;dito</option>
		<option value="DEBITO">D&eacute;bito</option>
      </select>
    </label></td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
  </tr>

  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="Submit" value="  Enviar              " />
    <input type="button" onClick="javascript: history.back();" value="              Voltar" /></td>
  </tr>
</table>
</form>