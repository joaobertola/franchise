<?php
include("../connect/conexao_conecta.php");
$codloja = $_REQUEST['codloja'];
$status = $_REQUEST['status'];
$idfranqueado = $_REQUEST['idfranqueado'];
$cidade = $_REQUEST['cidade'];
$cliente = $_REQUEST['cliente'];


if ($_REQUEST['acao'] == 1){
	$sql = "UPDATE  cadastro SET 
		nome_consultoria = NULL,
		data_consultoria = NULL
		WHERE
		codloja = '$codloja'";
	$qry = mysql_query($sql, $con) or die("Erro SQL : $sql");
	?>
     <script>
	 window.location.href="painel.php?pagina1=Franquias/b_relcligeral.php&status=<?=$_REQUEST['status']?>&id_franquia=<?=$_REQUEST['idfranqueado']?>&cidade=<?=$_REQUEST['cidade']?>";
	 </script>
    <?php
} ?>

<script>
function retorna(){
 	form = document.final;
    form.action = 'painel.php?pagina1=Franquias/b_relcligeral.php&status=<?=$_REQUEST['status']?>&id_franquia=<?=$_REQUEST['idfranqueado']?>&cidade=<?=$_REQUEST['cidade']?>';
	form.submit();
 } 

function confirma(){
 	form = document.final;
    form.action = 'painel.php?pagina1=Franquias/consultoria3.php&status=<?=$_REQUEST['status']?>&id_franquia=<?=$_REQUEST['idfranqueado']?>&cidade=<?=$_REQUEST['cidade']?>';
	form.submit();
 } 
 </script>
<form name="final" method="post">
<input type="hidden" name="acao" value="1">
<input type="hidden" name="codloja" value="<?=$_REQUEST['codloja']?>">
<input type="hidden" name="idfranqueado" value="<?=$_REQUEST['idfranqueado']?>">
<input type="hidden" name="bairro" value="<?=$_REQUEST['bairro']?>">

<table width='50%' border='0' cellpadding='0' cellspacing='0' class='bodyText' style='font-size:8px' align="center">

	<tr><td colspan="2" align="center" height='20' bgcolor='FF9966' style="font-size:12px"><b>Confirma o Cancelamento da Consultoria</b></td></tr>
    <tr bgcolor="#CCCCCC" style="font-size:12px">
    	<td width="50%">C&oacute;digo</td>
        <td ><?=$codloja?></td>
    </tr>
    <tr bgcolor="#CCCCCC" style="font-size:12px">
    	<td width="50%">Empresa</td>
        <td ><?=$cliente?></td>
    </tr>
	<tr>
    	<td colspan="2" align="center" height="50px">
		    <input type="button" value="    Retirar Baixa      " onclick="confirma()" />
		    &nbsp;&nbsp;&nbsp;&nbsp;
		    <input type="button" value="       Voltar     " onclick="retorna()" />
	    </td>
    </tr>
</table>    
</form>