<?php
require "connect/sessao.php";

$numdoc 	= $_GET['numdoc'];
$situacao 	= $_GET['situacao'];
$vencimento1= $_GET['vencimento1'];
$vencimento2= $_GET['vencimento2'];
$franqueado = $_GET['franqueado'];
$cobranca 	= $_GET['cobranca'];
$ordem 		= $_GET['ordem'];
$lpp 		= $_GET['lpp'];
$periodo 	= $_GET['periodo'];
$pagina 	= $_GET['pagina'];

$comando = "select a.numdoc, b.razaosoc, b.nomefantasia, DATE_FORMAT(a.vencimento,'%d/%m/%Y') as venc, a.valor from titulos a
			inner join cadastro b on a.codloja = b.codloja
			where numdoc='$numdoc'";
$conex = mysql_query($comando, $con);
$matriz = mysql_fetch_array($conex);
$hoje = date('d/m/Y');
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
// formato mascara data
function data(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que n�o � d�gito
    v=v.replace(/(\d{2})(\d)/,"$1/$2")
    v=v.replace(/(\d{2})(\d)/,"$1/$2")

    return v
}

//fun��o para validar clientes no cadastramento
function validaClientes(){
// validar valor pago
d = document.baixa;
if (d.valorpg.value == ""){
	alert("O campo " + d.valorpg.name + " deve ser preenchido!");
	d.valorpg.focus();
	return false;
}
// validar data de pagamento
if (d.datapg.value == ""){
	alert("O campo " + d.datapg.name + " deve ser preenchido!");
	d.datapg.focus();
	return false;
}
return true;
}

function voltarCliente(){
 	frmCli = document.baixa;
    frmCli.action = 'painel.php?pagina1=clientes/a_cons_id.php&id=<?=$_REQUEST['codloja']?>';
	frmCli.submit();
}
</script>
<body>
<form name="baixa" method="post" onSubmit="return validaClientes();" action="Franquias/b_dar_baixa.php" >

<input type="hidden" name="numdoc" value="<?=$_REQUEST['numdoc']?>">
<input type="hidden" name="situacao" value="<?=$_REQUEST['situacao']?>">
<input type="hidden" name="codigo1" value="<?=$_REQUEST['codigo1']?>">
<input type="hidden" name="codigo2" value="<?=$_REQUEST['codigo2']?>">
<input type="hidden" name="vencimento1" value="<?=$_REQUEST['vencimento1']?>">
<input type="hidden" name="vencimento2" value="<?=$_REQUEST['vencimento2']?>">
<input type="hidden" name="franqueado" value="<?=$_REQUEST['franqueado']?>">
<input type="hidden" name="cobranca" value="<?=$_REQUEST['cobranca']?>">
<input type="hidden" name="ordem" value="<?=$_REQUEST['ordem']?>">
<input type="hidden" name="lpp" value="<?=$_REQUEST['lpp']?>">
<input type="hidden" name="pagina" value="<?=$_REQUEST['pagina']?>">
<input type="hidden" name="periodo" value="<?=$_REQUEST['periodo']?>">
<input type="hidden" name="codloja" value="<?=$_REQUEST['codloja']?>">
<input type="hidden" name="retorna" value="<?=$_REQUEST['retorna']?>">

<table width="90%" border="0" align="center">
<tr>
  <td colspan="2" class="titulo">Baixa de T&iacute;tulos</td>
</tr>
<tr>
  <td colspan="2" class="campoesquerda">&nbsp;</td>
  </tr>
<tr>
  <td class="subtitulodireita">N&ordm; do T&iacute;tulo</td>
  <td class="subtitulopequeno"><?php echo $matriz['numdoc']; ?><input name="numdoc" type="hidden" value="<?php echo $matriz['numdoc']; ?>" ></td>
</tr>
<tr>
  <td class="subtitulodireita">Sacado</td>
  <td class="subtitulopequeno"><?php echo $matriz['razaosoc']; ?></td>
</tr>
<tr>
  <td class="subtitulodireita">Nome de Fantas&iacute;a </td>
  <td class="subtitulopequeno"><?php echo $matriz['nomefantasia']; ?></td>
</tr>
<tr>
  <td class="subtitulodireita">Vencimento</td>
  <td class="subtitulopequeno"><?php echo $matriz['venc']; ?></td>
</tr>
<tr>
  <td class="subtitulodireita">Valor</td>
  <td class="subtitulopequeno"><?php echo $matriz['valor']; ?></td>
</tr>
<tr>
  <td class="subtitulodireita">Data de Pagamento </td>
  <td class="subtitulopequeno"><input type="text" name="datapg" size="16" maxlength="10" value="<?php echo $hoje; ?>" onKeyPress="mascara(this,data)" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'; validaData(this);" > 
    dd/mm/aaaa</td>
</tr>
<tr>
  <td class="subtitulodireita">Valor pago </td>
  <td class="subtitulopequeno">
  <input type="text" name="valorpg" size="16" maxlength="12" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeydown="FormataValor(this,20,event,2)" style="text-align:right" >

  </td>
</tr>
  <tr>
    <td class="subtitulodireita">Origem do Pagamento</td>
    <td class="campoesquerda"><select name="origem" class="boxnormal">
      <option value="BANCO">BANCO</option>
      <option value="FRANQUIA" selected="selected">FRANQUIA</option>
							</select>	</td>
  </tr>
<tr>
  <td valign="top" class="subtitulodireita">&nbsp;</td>
  <td class="subtitulopequeno">Juros
    <input type="text" name="juros" size="16" maxlength="12" onKeydown="FormataValor(this,20,event,2)" style="text-align:right" onFocus="this.className='boxover'" class="boxnormal" onBlur="this.className='boxnormal'" >
   Desconto 
  <input type="text" name="desconto" size="16" maxlength="12" onKeydown="FormataValor(this,20,event,2)" style="text-align:right" onFocus="this.className='boxover'" class="boxnormal" onBlur="this.className='boxnormal'" ></td>
</tr>

<tr>
  <td colspan="2" class="campoesquerda">
  	<input type="hidden" name="situacao" value="<?php echo $situacao; ?>" >
	<input type="hidden" name="vencimento1" value="<?php echo $vencimento1; ?>" >
	<input type="hidden" name="vencimento2" value="<?php echo $vencimento2; ?>" >
	<input type="hidden" name="franqueado" value="<?php echo $franqueado; ?>" >
	<input type="hidden" name="cobranca" value="<?php echo $cobranca; ?>" >
	<input type="hidden" name="ordem" value="<?php echo $ordem; ?>" >
	<input type="hidden" name="lpp" value="<?php echo $lpp; ?>" >
	<input type="hidden" name="pagina" value="<?php echo $pagina; ?>" >
  </td>
</tr>
<tr>
  <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
<tr>
  <td colspan="2"><br>
    <p align="center">  
  <input type="submit" name="Submit" value="  Enviar              ">
  <input type="reset" name="reset" value="              Apagar  ">
  <?php if($_REQUEST['retorna'] == '1') { ?>
	  <input type="button" name="voltar" value="Retorna a Tela do Cliente" onClick="voltarCliente()">
  <?php } ?>
    </p></td>
</tr>
</table>
</form>
</body>