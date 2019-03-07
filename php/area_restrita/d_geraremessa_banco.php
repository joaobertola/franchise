
<body bgcolor="#F5F5F5">
<form enctype="multipart/form-data" action="#" method="POST">
<input name="iniciar" value="1" type="hidden">
<p>&nbsp;</p>
<table border="0" width="61%" align="center" cellpadding="0" cellspacing="5" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">	
<tr>
  <td colspan="2" align="center" bgcolor="#FFCC00" height="50"><b>
  <div style="font-size: 20px;">Gerar Remessa - Clientes / Franquia</div></b></td>
</tr>
<tr>
	<td width="40%" height="30" bgcolor="#F5F5F5"><b>&nbsp;Tipo Remessa:</b></td>
    <td width="60%" bgcolor="#F0F0F6">
    	<select name="tipo_remessa" style="width:90%">
        	<option value="000" selected>Selecione</option>
                <option value="frq_itau">WC (Banco ITAU) para Franquias</option>
        	<option value="cli_ispcn_237">ISPCN (Banco Bradesco) = Clientes Somente BRADESCO</option>
                <option value="cli_ispcn_341">ISPCN (Banco ITAU) = Clientes ITAU e Outros Bancos (EXCETO BRADESCO)</option>
        	<option value="antecipacli">ANTECIPA&Ccedil;&Atilde;O CLIENTES - Todos os bancos</option>
            <option value="antecipafrq">ANTECIPA&Ccedil;&Atilde;O FRANQUIAS - Todos os bancos</option>
        </select>
    </td>
</tr>
 
<tr>
  <td align="center" colspan="2">
    
    <div style="font-size: 14px;"><font color="#0000FF"><b>Informe o Tipo da Remessa  e Clique no Bot&atilde;o [Gerar Arquivo]</b></font></div>
    </td>
</tr> 

<tr>
    <td height="50">&nbsp;</td><td><input type="submit" value="Gerar Arquivo" /></td>
</tr>    

</table>

<p>&nbsp;</p>

<p align="center">
<?php
	global $arquivo;

	$tipo_retorno = $_POST['tipo_remessa'];
	$banco = $_POST['codbanco'];
	
	if ( $_POST['iniciar']=='1' ){
		if ( $tipo_retorno == 'cli_inf' ){
			
			include("d_gerar_remessa_Fornecedor_BBrasil.php");
			
		}else if ( $tipo_retorno == 'frq_inf' ){
			
			include("d_gerar_remessa_Franquia_BBrasil.php");
		
		}else if ( $tipo_retorno == 'cli_int' ){
			
			include("d_gerar_remessa_Fornecedor_Intense_BBrasil.php");
			
		}else if ( $tipo_retorno == 'frq_int' ){
			
			include("d_gerar_remessa_Franquia_Intense_BBrasil.php");
		
		}else if ( $tipo_retorno == 'frq_itau' ){
			
			//include("d_gerar_remessa_FRANQUIA_ISSPC_ITAU.php");
			include("d_gerar_remessa_FRANQUIA_WC_ITAU.php");
			
		}else if ( $tipo_retorno == 'cli_ispcn' ){
			
			include("d_gerar_remessa_Fornecedor_Ispcn_BBrasil.php");
			
		}else if ( $tipo_retorno == 'frq_ispcn' ){
			
			include("d_gerar_remessa_Franquia_Ispcn_BBrasil.php");		
		
		}elseif ( $tipo_retorno == 'cli_ispcn_237' ){
			
			include("d_gerar_remessa_Fornecedor_Ispcn_BBradesco.php");
			
		}elseif ( $tipo_retorno == 'cli_ispcn_341' ){
			
			include("d_gerar_remessa_Fornecedor_Ispcn_ITAU.php");
		
		}elseif ( $tipo_retorno == 'antecipacli' ){
			
			include("d_gerar_remessa_Antecipacao_BBradesco.php");
			
		}elseif ( $tipo_retorno == 'antecipafrq' ){
			
			include("d_gerar_remessa_Antecipacao_ITAU_Franquia.php");
		}
		
		else{
			echo "<font color='red'><b><div style='font-size: 20px;'><hr width='50%'>Desculpe, Tipo de Remessa inv&aacute;lida. Contate o programador. </font></b></font>";
			exit;
		}
	}
?>
</p>
</form>