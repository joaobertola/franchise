<?php
require "connect/sessao.php";

$codloja 			= $_GET['numdoc'];
$banco_faturado 	= $_GET['situacao'];
$numboleto			= $_GET['numboleto'];
$numboletobradesco	= $_GET['numboletobradesco'];
$numboletoitau 		= $_GET['numboletoitau'];

$comando = "SELECT  numdoc, numboleto, numboleto_bradesco, numboleto_itau, numboleto_hsbc,
					valorpg, date_format(datapg,'%d/%m/%Y') as datapg, 
					date_format(vencimento,'%d/%m/%Y') as vencimento, valor
			FROM cs2.titulos_recebafacil 
			WHERE
				(
				numboleto = '$numboleto' or 
				numboleto_bradesco = '$numboletobradesco' or 
				numboleto_itau = '$numboletoitau'
				)
			";
$conex = mysql_query($comando, $con);
$matriz = mysql_fetch_array($conex);
$valorpg = mysql_result($conex, 0 ,'valorpg');
$hoje = mysql_result($conex, 0 ,'datapg');

if ( $valorpg > 0 )
	$mensagem = '<b><font color="red">T&Iacute;TULO BAIXADO ANTERIORMENTE</font></b>';

if ( $valorpg == 0 )
	$hoje = date('d/m/Y');
?>
<script language="javascript">
//função para aceitar somente numeros em determinados campos
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
    v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
    v=v.replace(/(\d{2})(\d)/,"$1/$2")
    v=v.replace(/(\d{2})(\d)/,"$1/$2")

    return v
}

//função para validar clientes no cadastramento
function validardados(){
	// validar valor pago
	d = document.baixa;
	if (d.valorpg.value == ""){
		alert("O campo Valor do Pagamento deve ser preenchido!");
		d.valorpg.focus();
		return false;
	}
	// validar data de pagamento
	if (d.datapg.value == ""){
		alert("O campo Data de Pagamento deve ser preenchido!");
		d.datapg.focus();
		return false;
	}
    d.action = 'painel.php?pagina1=clientes/a_baixa_titulo_cred_rec_bol_final.php';
	d.submit();
}

function voltarCliente(){
 	frmCli = document.baixa;
    frmCli.action = 'painel.php?pagina1=clientes/a_baixa_titulo_cred_rec_bol.php';
	frmCli.submit();
}




</script>
<body>
<form name="baixa" method="post" action="#" >

<table width="90%" border="0" align="center">
<tr>
  <td colspan="2" class="titulo">Baixa de T&iacute;tulos - Cred/Rec/Bol</td>
</tr>
<tr>
  <td colspan="2" class="campoesquerda">&nbsp;</td>
  </tr>
<tr>
  <td class="subtitulodireita">N&ordm; do T&iacute;tulo</td>
  <td class="subtitulopequeno"><?php echo $matriz['numboleto']; ?><input name="numdoc" type="hidden" value="<?php echo $matriz['numdoc']; ?>" ></td>
</tr>

<tr>
  <td class="subtitulodireita">N&ordm; do T&iacute;tulo Bradesco</td>
  <td class="subtitulopequeno"><?php echo $matriz['numboleto_bradesco']; ?><input name="numdoc" type="hidden" value="<?php echo $matriz['numboleto_bradesco']; ?>" ></td>
</tr>

<tr>
  <td class="subtitulodireita">N&ordm; do T&iacute;tulo Itau</td>
  <td class="subtitulopequeno"><?php echo $matriz['numboleto_itau']; ?><input name="numdoc" type="hidden" value="<?php echo $matriz['numboleto_itau']; ?>" ></td>
</tr>
<tr>
  <td class="subtitulodireita">Vencimento</td>
  <td class="subtitulopequeno"><?php echo $matriz['vencimento']; ?></td>
</tr>

<tr>
  <td class="subtitulodireita">Valor do Titulo</td>
  <td class="subtitulopequeno"><?php echo $matriz['valor']; ?></td>
</tr>

<tr>
  <td class="subtitulodireita">Data de Pagamento </td>
  <?php
  	if ( $valorpg > 0 ){
  ?>
   <td class="subtitulopequeno"><?=$hoje?></td>
  <?php }else{ ?>
  <td class="subtitulopequeno"><input type="text" name="datapg" size="16" maxlength="10" value="<?php echo $hoje; ?>" onKeyPress="mascara(this,data)" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'; validaData(this);" > 
    dd/mm/aaaa</td>
    <?php } ?>
</tr>

<tr>
  <td class="subtitulodireita">Valor pago </td>
  <?php
  	if ( $valorpg > 0 ){
  ?>
		<td class="subtitulopequeno"><?=$valorpg?></td>
  <?php }else{ ?>
  		<td class="subtitulopequeno">
  <input type="text" name="valorpg" size="16" maxlength="12" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeydown="FormataValor(this,20,event,2)" style="text-align:right" >
  </td>
  <?php } ?>
</tr>

<tr>
  <td colspan="2" class="titulo"><?=$mensagem?></td>
  </tr>
<tr>
  <td colspan="2"><br>
    <p align="center"> 
    <?php
      if ( empty($valorpg) ){ ?>
		  <input type="button" name="Submit" value="  CONFIRMAR RECEBIMENTO  " onClick="validardados()">
    <?php } ?>
  <input type="button" name="voltar" value="   CANCELAR " onClick="voltarCliente()">
    </p></td>
</tr>
</table>
</form>
</body>