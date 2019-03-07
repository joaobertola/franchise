<?php
require_once('../connect/sessao.php');
//session_start();
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//$data_cadastro = date("Y-m-d");
//
//if ( $name == "" ){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}
?>
<script language="javascript">
function valida(){
	frm = document.form;	
	if(frm.id_modulo.value == ""){
		alert("Falta informar o Item a ser Excluído  !");
		frm.id_modulo.focus();
		return false;
	}
	if(confirm("Deseja realmente excluir o módulo ?")) {
  		confirmaExclusao();
	} 
}

function confirmaExclusao(){
 	frm = document.form;
    frm.action = 'area_restrita/web_control_eliminar_informacoes_bd.php';
	frm.submit();
}
function retorna(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=area_restrita/web_control_eliminar_informacoes_busca_cliente.php';
	frm.submit();
}
</script>
<?php

	if( ($_SESSION['id'] == '163') or ($_SESSION['id'] == '5') ){
		$id_franquia = '1';
	}else{
		$id_franquia = $_SESSION['id'];
	}
	
	if ( $tipo <> 'a' ){
		
		echo "Funcao DESABILITADA. Contate o Departamento de Informática.";
		exit;
	}
	
	$sql = "SELECT
			 c.codloja, l.logon, c.razaosoc, c.nomefantasia
			FROM
			  cs2.cadastro c INNER JOIN
			  cs2.logon l ON c.codloja = l.codloja
			WHERE 
				l.logon LIKE '{$_REQUEST['cod_cliente']}%'";

	$qry = mysql_query($sql);
	$total = mysql_num_rows($qry);
	$razaosoc = mysql_result($qry,0,'razaosoc');
	$nomefantasia = mysql_result($qry,0,'nomefantasia');
	$id_cadastro = mysql_result($qry,0,'codloja');

if($total == "0") { ?>
	<b><div align="center"><u>N&atilde;o foi encontrado o Cliente com C&oacute;digo:</u> <font color="#FF0000"><?=$_REQUEST['cod_cliente']?></font> ou N&atilde;o pertence a sua Franquia</div></b>
<?php
	echo "<p><div align='center'><b><a href='painel.php?pagina1=area_restrita/web_control_eliminar_informacoes_busca_cliente.php'>Retornar</a></b></div>";
exit;
} 

//verificando os modulos que estão ativos
$sql_atendimento = "SELECT COUNT(*)AS total_atendimento FROM base_web_control.atendimento WHERE id_cadastro = '$id_cadastro'";
$qry_atendimento = mysql_query($sql_atendimento);
$total_atendimento = mysql_result($qry_atendimento,0,'total_atendimento');

//modulo 1 ----------------------------------------------------------------------------------------------------
$sql_atendimento = "SELECT COUNT(*)AS total_atendimento FROM base_web_control.atendimento WHERE id_cadastro = '$id_cadastro'";
$qry_atendimento = mysql_query($sql_atendimento);
$total_modulo_1 = mysql_result($qry_atendimento,0,'total_atendimento');


//modulo 2 ----------------------------------------------------------------------------------------------------
$sql_classificacao = "SELECT COUNT(*)AS total_classificacao  FROM base_web_control.classificacao WHERE id_cadastro = '$id_cadastro'";
$qry_classificacao = mysql_query($sql_classificacao);
$total_classificacao = mysql_result($qry_classificacao,0,'total_classificacao');

$sql_produto = "SELECT COUNT(*)AS total_produto FROM base_web_control.produto WHERE id_cadastro = '$id_cadastro'";
$qry_produto = mysql_query($sql_produto);
$total_produto = mysql_result($qry_produto,0,'total_produto');

$sql_nota_fiscal = "SELECT COUNT(*)AS total_nota_fiscal FROM base_web_control.nota_fiscal WHERE id_cadastro = '$id_cadastro'";
$qry_nota_fiscal = mysql_query($sql_nota_fiscal);
$total_nota_fiscal = mysql_result($qry_nota_fiscal,0,'total_nota_fiscal');

if( ($total_classificacao > 0) || ($total_produto > 0) || ($total_nota_fiscal > 0) ){
	$total_modulo_2 = 1;
}


//modulo 3  ----------------------------------------------------------------------------------------------------
$sql_produto_2 = "SELECT COUNT(*)AS total_produto_2 FROM base_web_control.produto WHERE id_cadastro = '$id_cadastro'";
$qry_produto_2 = mysql_query($sql_produto_2);
$total_produto_2 = mysql_result($qry_produto_2,0,'total_produto_2');

$sql_nota_fiscal = "SELECT COUNT(*)AS total_nota_fiscal FROM base_web_control.nota_fiscal WHERE id_cadastro = '$id_cadastro'";
$qry_nota_fiscal = mysql_query($sql_nota_fiscal);
$total_nota_fiscal = mysql_result($qry_nota_fiscal,0,'total_nota_fiscal');

if( ($total_produto_2 > 0) || ($total_nota_fiscal > 0) ){
$total_modulo_3 = 1;
}


//modulo 4 ----------------------------------------------------------------------------------------------------
$sql_nota_fiscal_2 = "SELECT COUNT(*)AS total_nota_fiscal FROM base_web_control.nota_fiscal WHERE id_cadastro = '$id_cadastro'";
$qry_nota_fiscal_2 = mysql_query($sql_nota_fiscal_2);
$total_modulo_4 = mysql_result($qry_nota_fiscal_2,0,'total_nota_fiscal');


//modulo 5 ----------------------------------------------------------------------------------------------------
$sql_venda = "SELECT COUNT(*)AS total_venda FROM base_web_control.venda WHERE id_cadastro = '$id_cadastro'";
$qry_venda = mysql_query($sql_venda);	
$total_venda = mysql_result($qry_venda,0,'total_venda');

$sql_atendimento = "SELECT COUNT(*)AS total_atendimento FROM base_web_control.atendimento WHERE id_cadastro = '$id_cadastro'";
$qry_atendimento = mysql_query($sql_atendimento);	
$total_atendimento = mysql_result($qry_atendimento,0,'total_atendimento');

$sql_cliente = "SELECT COUNT(*)AS total_cliente FROM base_web_control.cliente WHERE id_cadastro = '$id_cadastro'";
$qry_cliente = mysql_query($sql_cliente);	
$total_cliente = mysql_result($qry_cliente,0,'total_cliente');

if( ($total_venda > 0) || ($total_atendimento > 0) || ($total_cliente > 0) ){
	$total_modulo_5 = 1;
}

//modulo 6 ----------------------------------------------------------------------------------------------------
$sql_compromisso = "SELECT COUNT(*)AS total_compromisso FROM base_web_control.compromisso WHERE id_cadastro = '$id_cadastro'";
$qry_compromisso = mysql_query($sql_1);	
$total_modulo_6 = mysql_result($qry_compromisso,0,'total_compromisso');


//modulo 7 ----------------------------------------------------------------------------------------------------
$sql_contas_pagar = "SELECT COUNT(*)AS total_contas_pagar FROM base_web_control.contas_pagar WHERE id_cadastro = '$id_cadastro'";
$qry_contas_pagar = mysql_query($sql_contas_pagar);	
$total_modulo_7 = mysql_result($qry_contas_pagar,0,'total_contas_pagar');

//modulo 8 ----------------------------------------------------------------------------------------------------
$sql_contas_pagar_2 = "SELECT COUNT(*)AS total_contas_pagar_2 FROM base_web_control.contas_pagar WHERE id_cadastro = '$id_cadastro'";
$qry_contas_pagar_2 = mysql_query($sql_contas_pagar_2);	
$total_contas_pagar_2 = mysql_result($qry_contas_pagar_2,0,'total_contas_pagar_2');

$sql_descricao_contas_pagar = "SELECT COUNT(*)AS total_descricao_contas_pagar FROM base_web_control.descricao_contas_pagar WHERE id_cadastro = '$id_cadastro'";
$qry_descricao_contas_pagar = mysql_query($sql_descricao_contas_pagar);	
$total_descricao_contas_pagar = mysql_result($qry_descricao_contas_pagar,0,'total_descricao_contas_pagar');

if( ($total_contas_pagar_2 > 0) || ($total_descricao_contas_pagar > 0) ){
	$total_modulo_8 = 1;
}

//modulo 9 ----------------------------------------------------------------------------------------------------
$sql_forncedor = "SELECT COUNT(*)AS total_forncedor FROM base_web_control.fornecedor 
				  WHERE id_cadastro = '$id_cadastro'  AND tipo_cadastro = 'F'";
$qry_forncedor = mysql_query($sql_forncedor);	
$total_forncedor = mysql_result($qry_forncedor,0,'total_forncedor');

$sql_fornecedor_servico = "SELECT COUNT(*)AS total_forncedor_servico FROM  base_web_control.fornecedor_servico WHERE id_cadastro = '$id_cadastro'";
$qry_fornecedor_servico = mysql_query($sql_fornecedor_servico);	
$total_forncedor_servico = mysql_result($qry_fornecedor_servico,0,'total_forncedor_servico');

$sql_nota_fiscal_2 = "SELECT COUNT(*)AS total_nota_fiscal_2 FROM base_web_control.nota_fiscal WHERE id_cadastro = '$id_cadastro'";
$qry_nota_fiscal_2 = mysql_query($sql_nota_fiscal_2);	
$total_nota_fiscal_2 = mysql_result($qry_nota_fiscal_2,0,'total_nota_fiscal_2');

if( ($total_forncedor > 0) || ($total_forncedor_servico > 0) || ($total_nota_fiscal_2 > 0) ){
	$total_modulo_9 = 1;
}
	
//modulo 10 ----------------------------------------------------------------------------------------------------
$sql_venda_2 = "SELECT COUNT(*)AS total_venda_2 FROM base_web_control.venda WHERE id_cadastro = '$id_cadastro'";
$qry_venda_2 = mysql_query($sql_venda_2);
$total_modulo_10 = mysql_result($qry_venda_2,0,'total_venda_2');

//modulo 11 ----------------------------------------------------------------------------------------------------
$sql_agenda = "SELECT COUNT(*)AS total_agenda FROM base_web_control.fornecedor 
			   WHERE id_cadastro = '$id_cadastro' AND tipo_cadastro = 'C'";
$qry_agenda = mysql_query($sql_agenda);
$total_modulo_11 = mysql_result($qry_agenda,0,'total_agenda');

//modulo 12 ----------------------------------------------------------------------------------------------------
$sql_os = " SELECT COUNT(*)AS total_os FROM base_web_control.ordem_servico a
			INNER JOIN base_web_control.cliente b ON a.id_cliente  = b.id
			WHERE b.id_cadastro = '$id_cadastro'";
$qry_os = mysql_query($sql_os);
$total_modulo_12 = mysql_result($qry_os,0,'total_os');

?>
<p>
<form action="#" method="post" name="form">
<input type="hidden" name="cod_cliente" value="<?=$_REQUEST['cod_cliente']?>">
<input type="hidden" name="id_cadastro" value="<?=$id_cadastro?>">
<table border='0' width='750' align='center' cellpadding='0' cellspacing='1' style='border:1px dashed #E8E8E8; background-color:#FFFFFF'>
<tr>
  <td colspan="2" align="center" class="titulo">Eliminar Informa&ccedil;&otilde;es WEB-CONTROL</td>
</tr>
	<tr>
    	<td bgcolor="#E8E8E8" height="25"><b>Logon de Acesso</b></td>
        <td bgcolor="#F0F0F6"><?=$_REQUEST['cod_cliente']?></td>
    </tr>
    <tr>
    	<td bgcolor="#E8E8E8" height="25"><b>Razão Social</b></td>
        <td bgcolor="#F0F0F6"><?=$razaosoc?></td>
    </tr>
    <tr>
    	<td bgcolor="#E8E8E8" height="25"><b>Nome Fantasia</b></td>
        <td bgcolor="#F0F0F6"><?=$nomefantasia?></td>
    </tr>
<tr>
	<td width="35%" bgcolor="#E8E8E8" height="25"><b>Selecione o item que deseja exlcuir</b></td>
    <td width="65%" height="25" bgcolor="#F0F0F6">
    	<select name="id_modulo" style="width:99%">
        	<option value="">&nbsp;</option>
        	<?php if($total_modulo_1 > 0){?>
            	<option value="1">Atendimento</option>
            <?php } ?>
            
			<?php if($total_modulo_2 > 0){?>
            <option value="2">Classificação & Produto & Entrada de Nota Fiscal</option>
            <?php } ?>
             
            <?php if($total_modulo_3 > 0){?>        
            <option value="3">Produto & Entrada de Nota Fiscal</option>            
            <?php } ?>
            
            <?php if($total_modulo_4 > 0){?>     
            <option value="4">Entrada de Nota Fiscal</option>
            <?php } ?>
            
            <?php if($total_modulo_5 > 0){?>     
            <option value="5">Cliente & Atendimento & Venda</option>
            <?php } ?>
            
            <?php if($total_modulo_6 > 0){?>     
            <option value="6">Compromisso</option>
            <?php } ?>
            
            <?php if($total_modulo_7 > 0){?>     
            <option value="7">Contas á Pagar</option>
            <?php } ?>
            
            <?php if($total_modulo_8 > 0){?>     
            <option value="8">Contas á Pagar & Descrição de Contas á Pagar</option>
            <?php } ?>         
            
            <?php if($total_modulo_9 > 0){?>    
            <option value="9">Fornecedor  & Serviços do Fornecedor & Entrada de Nota Fiscal</option>
            <?php } ?>   
            
            <?php if($total_modulo_10 > 0){?>    
            <option value="10">Venda</option> 
            <?php } ?>   
            
             <?php if($total_modulo_11 > 0){?>    
            <option value="11">Agenda Telefônica - Contato</option>
            <?php } ?>
            
            <?php if($total_modulo_12 > 0){?>    
            <option value="12">Ordem de Serviço</option>
            <?php } ?>
            
        </select>
    </td>
</tr>

<tr>
  <td colspan="2" align="left">
  	<b><font color="#FF0000">
    	As informações que forem excluídas não terão como ser recuperadas
    </font></b>
  </td>
  </tr>
<tr>
	<td height="40"></td>
    <td>
    	<input type="button" value="Confirma a Exclusão" name="exclusao" onclick="valida()" />
        &nbsp;&nbsp;&nbsp;
        <input type="button" value="Retorna" name="exclusao" onclick="retorna()" />
    </td>
</tr>
</table>
</form>

<?php if($_REQUEST['msg'] == "1"){ ?>
	<script language="javascript">alert('Excluído com Sucesso o Item: Atendimento ');</script>
<?php } ?>

<?php if($_REQUEST['msg'] == "2"){ ?>
	<script language="javascript">alert('Excluído com Sucesso os Itens: Classificação | Produto | Entrada de Nota Fiscal ');</script>
<?php } ?>

<?php if($_REQUEST['msg'] == "3"){ ?>
	<script language="javascript">alert('Excluído com Sucesso os Itens: Produto | Entrada de Nota Fiscal ');</script>
<?php } ?>

<?php if($_REQUEST['msg'] == "4"){ ?>
	<script language="javascript">alert('Excluído com Sucesso o Item: Entrada de Nota Fiscal ');</script>
<?php } ?>

<?php if($_REQUEST['msg'] == "5"){ ?>
	<script language="javascript">alert('Excluído com Sucesso os Itens: Cliente | Atendimento | Venda');</script>
<?php } ?>

<?php if($_REQUEST['msg'] == "6"){ ?>
	<script language="javascript">alert('Excluído com Sucesso o Item: Compromisso ');</script>
<?php } ?>

<?php if($_REQUEST['msg'] == "7"){ ?>
	<script language="javascript">alert('Excluído com Sucesso o Item: Contas á Pagar ');</script>
<?php } ?>

<?php if($_REQUEST['msg'] == "8"){ ?>
	<script language="javascript">alert('Excluído com Sucesso os Itens: Contas á Pagar | Descrição de Contas á Pagar ');</script>
<?php } ?>

<?php if($_REQUEST['msg'] == "9"){ ?>
	<script language="javascript">alert('Excluído com Sucesso os Itens: Fornecedor | Serviços do Fornecedor | Entrada de Nota Fiscal ');</script>
<?php } ?>

<?php if($_REQUEST['msg'] == "10"){ ?>
	<script language="javascript">alert('Excluído com Sucesso o Item: Venda ');</script>
<?php } ?>

<?php if($_REQUEST['msg'] == "11"){ ?>
	<script language="javascript">alert('Excluído com Sucesso o Item: Agenda Telefônica - Contato');</script>
<?php } ?>

<?php if($_REQUEST['msg'] == "12"){ ?>
	<script language="javascript">alert('Excluído com Sucesso o Item: Ordem de Serviço');</script>
<?php } ?>