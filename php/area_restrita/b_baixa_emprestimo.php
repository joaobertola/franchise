<?php
require "connect/sessao.php";

$id_emprestimo = $_GET['id_emprestimo'];

$comando = "SELECT a.vr_emprestimo_solicitado, a.valor_parcela, a.descricao_deposito, 
				   date_format(a.data_vencimento,'%d/%m/%Y') AS vencimento,
				   a.numero_parcela, b.fantasia, a.id_franquia
			FROM cs2.cadastro_emprestimo_franquia a
			INNER JOIN cs2.franquia b ON a.id_franquia = b.id
			WHERE a.id = $id_emprestimo";
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
    d.action = 'painel.php?pagina1=area_restrita/b_baixa_emprestimo2.php';
	d.submit();
}

function voltarCliente(){
 	frmCli = document.baixa;
    frmCli.action = 'painel.php?pagina1=clientes/a_cons_id.php&id=<?=$_REQUEST['codloja']?>';
	frmCli.submit();
}

window.onload = function(){
	document.baixa.valorpg.focus(); 
}

</script>
<body>
<form name="baixa" method="post" onSubmit="return validaClientes();" action="Franquias/b_dar_baixa.php" >
	<input type="hidden" name="id_emprestimo" value="<?=$id_emprestimo?>">
    <input type="hidden" name="id_franquia" value="<?php echo $matriz['id_franquia']; ?>">
	<table width="90%" border="0" align="center">
        <tr>
			<td colspan="3" class="titulo">Baixa de D&eacute;bitos de Antecipa&ccedil;&atilde;o</td>
        </tr>
        <tr>
			<td colspan="3" class="campoesquerda">&nbsp;</td>
        </tr>
        <tr>
            <td class="subtitulodireita">Franquia</td>
            <td colspan="2" class="subtitulopequeno"><?php echo $matriz['fantasia']; ?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Parcela</td>
            <td colspan="2" class="subtitulopequeno"><?php echo $matriz['numero_parcela']; ?></td>
        </tr>
        <tr>
          <td class="subtitulodireita">Vencimento</td>
          <td colspan="2" class="subtitulopequeno"><?php echo $matriz['vencimento']; ?></td>
        </tr>
        <tr>
          <td class="subtitulodireita">Valor Compra/Empr&eacute;stimo</td>
          <td colspan="2" class="subtitulopequeno">R$ <?php echo number_format($matriz['vr_emprestimo_solicitado'],2,',','.'); ?></td>
        </tr>

        <tr>
          <td class="subtitulodireita">Valor Parcela</td>
          <td colspan="2" class="subtitulopequeno">R$ <?php echo number_format($matriz['valor_parcela'],2,',','.'); ?></td>
        </tr>
        <tr>
          <td class="subtitulodireita">Data de Pagamento </td>
          <td colspan="2" class="subtitulopequeno"><input type="text" name="datapg" size="16" maxlength="10" value="<?php echo $hoje; ?>" onKeyPress="mascara(this,data)" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal';" ></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Valor pago </td>
            <td colspan="2" class="subtitulopequeno"><input type="text" name="valorpg" size="16" maxlength="12" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeydown="FormataValor(this,20,event,2)" style="text-align:right" value="<?php echo number_format($matriz['valor_parcela'],2,',','.'); ?>" ></td>
        </tr>
        <tr>
          <td colspan="3" class="titulo">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="3"><br>
            <p align="center">  
          <input type="submit" name="Submit" value="  Confirmar Recebimento  ">
            </p></td>
        </tr>
</table>
</form>
</body>