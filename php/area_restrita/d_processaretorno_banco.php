<script>
function valida(){
	frm = document.form;
	if(frm.tipo_retorno.value == "0"){
		alert('Falta escolher o Tipo de Retorno !');
		return false;
	}
	if(frm.codbanco.value == "0"){
		alert('Falta escolher o Banco !');
		return false;
	}
	processa();	
}

function vai(){
	frm = document.form;
 	frm.action = 'd_processaretorno_banco.php?iniciar=0';
 	frm.submit();
}

function processa(){
	frm = document.form;
 	frm.action = 'd_processaretorno_banco.php?iniciar=1';
 	frm.submit();
}
</script>

<body bgcolor="#F5F5F5">
<form enctype="multipart/form-data" action="#" method="POST" name="form">
<p>&nbsp;</p>
<table border="0" width="61%" align="center" cellpadding="0" cellspacing="3" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">	
<tr>
  <td colspan="2" align="center" bgcolor="#FFCC00" height="50"><b><div style="font-size: 20px;">Processar Retorno Banco</div></b></td>
</tr>
<tr>
	<td width="40%" height="30" bgcolor="#F5F5F5"><b>&nbsp;Tipo Retorno:</b></td>
    <td width="60%" bgcolor="#F0F0F6">
    	<select name="tipo_retorno" style="width:70%" onChange="vai()">
        	<option value="0">-- Selecione --</option>
            <option value="1" <?php if($_REQUEST['tipo_retorno'] == '1'){?> selected <?php } ?>>Retorno de Cobranca (Mens-Cred-Rec-Bol)</option>
            <option value="2" <?php if($_REQUEST['tipo_retorno'] == '2'){?> selected <?php } ?>>Retorno de Pagamento a Fornecedor (CLIENTE)</option>
            <option value="3" <?php if($_REQUEST['tipo_retorno'] == '3'){?> selected <?php } ?>>Retorno de Pagamento a Fornecedor (FRANQUIA)</option>
            <option value="4" <?php if($_REQUEST['tipo_retorno'] == '4'){?> selected <?php } ?>>Retorno de Pagamento a Funcionarios</option>
            <option value="5" <?php if($_REQUEST['tipo_retorno'] == '5'){?> selected <?php } ?>>Retorno de Pagamento - ANTECIPA&Ccedil;&Atilde;O</option>
        </select>
    </td>
</tr>
<tr>
	<td width="40%" height="30" bgcolor="#F5F5F5"><b>&nbsp;Banco:</b></td>
    <td width="60%" bgcolor="#F0F0F6">
     <select name="codbanco" style="width:70%">     		
     <?php if ($_REQUEST['tipo_retorno'] == '1'){?>    	
        	<option value="001">Banco do Brasil / Bol-Cred-Rec</option>
        	<option value="111">Banco do Brasil / Mensalidades</option>            
            <option value="237" selected>Banco Bradesco / Mensalidades-Bol-Cred-Rec</option>
            <option value="341">Banco Itau / Mensalidades-Bol-Cred-Rec</option>
     <?php } else if ($_REQUEST['tipo_retorno'] == '2' ){?>
            <option value="001">Banco do Brasil</option>
            <option value="237">Banco Bradesco</option>
            <option value="341">Banco Itau</option>
     <?php } else if ($_REQUEST['tipo_retorno'] == '3'){?>
            <option value="001">Banco do Brasil</option>
            <option value="341">Banco ITAU</option>
	<?php } else if ($_REQUEST['tipo_retorno'] == '4'){?>
            <option value="341">Banco ITAU</option>
            <option value="237">Banco BRADESCO</option>
	<?php } else if ($_REQUEST['tipo_retorno'] == '5'){?>
            <option value="237">Cliente - Banco Bradesco</option>
            <option value="341">Franquia - Banco ITAU</option>
     <?php }else{ ?>
     	<option value="0">-- Selecione --</option>
     <?php } ?>
    </select>
    </td>    
</tr>
<tr>
    <td height="50" bgcolor="#F5F5F5"><b>&nbsp;Arquivo do Banco:</b></td>
    <td bgcolor="#F0F0F6"><input name="uploaded" type="file" /></td>
</tr> 
<tr>
    <td align="center" colspan="2">
    <font color="#0000FF"><b><div style="font-size: 14px;">Informe o Banco e o Arquivo e Clique no Botao [Processar Retorno]</div></b></font>
    </td>
</tr> 

<tr>
    <td height="50">&nbsp;</td><td><input type="button" value="Processar Retorno" onClick="valida()" /></td>
</tr>    

</table>

<p>&nbsp;</p>

<p align="center">
<?php
	global $arquivo;
        
	$tipo_retorno = $_POST['tipo_retorno'];
	$banco = $_POST['codbanco'];
	
	if ( $_REQUEST['iniciar']=='1' ){
	
		# Nome do arquivo para processamento - BRADESCO
		$target = "upload/"; 
		$target = $target . basename( $_FILES['uploaded']['name']) ; 
		$ok=1;
		try{
			if( move_uploaded_file($_FILES['uploaded']['tmp_name'], $target) ){
				$arquivo = $target;
				
				if ( $banco == '237' && $tipo_retorno == '1' ){
					echo "processamento somente por NEXXERA";
					//echo "<font color='green'><b><div style='font-size: 20px;'><hr width='50%'>Arquivo Enviado com Sucesso. </font></b></font>";
					// include("d_processa_retorno_cobranca_BRADESCO.php"); - somente nexxera
					
				}
				elseif ( $banco == '001' && $tipo_retorno == '1' ){
					echo "processamento somente por NEXXERA";

					//echo "<font color='green'><b><div style='font-size: 20px;'><hr width='50%'>Arquivo Enviado com Sucesso. </font></b></font>";
					//include("d_processa_retorno_reccred_BBRASIL.php");
					
				}
				elseif ( $banco == '341' && $tipo_retorno == '1' ){
					echo "processamento somente por NEXXERA";

					//echo "<font color='green'><b><div style='font-size: 20px;'><hr width='50%'>Arquivo Enviado com Sucesso. </font></b></font>";
					//include("d_processa_retorno_ITAU.php");
					
				}
				elseif ( $banco == '001' && $tipo_retorno == '2' ){
					
					include("d_processa_retorno_fornecedor_bb_CLIENTE.php");
					
				}elseif ( $banco == '001' && $tipo_retorno == '3' ){
					
					include("d_processa_retorno_fornecedor_bb_FRANQUIA.php");
				
				}elseif ( $banco == '341' && $tipo_retorno == '3' ){
					
					include("d_processa_retorno_fanquia_ITAU.php");
				
				}elseif ( $banco == '111' && $tipo_retorno == '1' ){
					
					echo "processamento somente por NEXXERA";
					//include("d_processa_retorno_cobranca_BBRASIL.php");

				}elseif ( $banco == '237' && $tipo_retorno == '2' ){
					
					include("d_processa_retorno_fornecedor_BRADESCO.php");
				
				}elseif ( $banco == '341' && $tipo_retorno == '2' ){
					
					include("d_processa_retorno_fornecedor_ITAU.php");
					
				}elseif ( $banco == '341' && $tipo_retorno == '4' ){
					
					include("d_processa_retorno_FUNCIONARIOS_ITAU.php");
                                        
				}elseif ( $banco == '237' && $tipo_retorno == '4' ){
					
					include("d_processa_retorno_FUNCIONARIO_BRADESCO.php");
				
				}elseif ( $banco == '237' && $tipo_retorno == '5' ){
					
					include("d_processa_retorno_Antecipacao_BRADESCO.php");
					
				}elseif ( $banco == '341' && $tipo_retorno == '5' ){
					
					include("d_processa_retorno_Antecipacao_fanquia_ITAU.php");
						
				}else{
					echo "<font color='red'><b><div style='font-size: 20px;'><hr width='50%'>Desculpe, [Tipo Retorno]  Invalido. </font></b></font>";
					exit;
				}
			}else{
				echo "<font color='red'><b><div style='font-size: 20px;'><hr width='50%'>Desculpe, houve um problema no envio do arquivo. Contate o programador. </font></b></font>";
				exit;
			}
		} catch (Exception $e) {
			var_dump($e->getMessage());
		}
	}
?>
</p>
</form>