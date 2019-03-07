
<body bgcolor="#F5F5F5">
<form enctype="multipart/form-data" action="#" method="POST">
<input name="iniciar" value="1" type="hidden">
<p>&nbsp;</p>
<table border="0" width="61%" align="center" cellpadding="0" cellspacing="5" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">	
<tr>
  <td colspan="2" align="center" bgcolor="#FFCC00" height="50"><b>
  <div style="font-size: 20px;">Gerar Remessa - SAL&Aacute;RIOS / VT - VR</div></b></td>
</tr>
<tr>
	<td width="40%" height="30" bgcolor="#F5F5F5"><b>&nbsp;Tipo Remessa:</b></td>
    <td width="60%" bgcolor="#F0F0F6">
    	<select name="tipo_remessa" style="width:90%">
        	<option value="000" selected>Selecione</option>
            <option value="func_salario_itau">SISPAG SALARIO / VT+VR - BANCO ITAU</option>
            <option value="func_salario_bradesco">SISPAG SALARIO / VT+VR - BANCO BRADESCO</option>
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

    if ( $_POST['iniciar']=='1' ){

        if ( $tipo_retorno == 'func_salario_itau' ){

            include("d_gerar_remessa_SALARIO_FUNCIONARIO_ITAU.php");

        }else if ( $tipo_retorno == 'func_salario_bradesco' ){

            include("d_gerar_remessa_SALARIO_FUNCIONARIO_BRADESCO.php");

        }else{

            echo "<font color='red'><b><div style='font-size: 20px;'><hr width='50%'>Desculpe, Tipo de Remessa inv&aacute;lida. Contate o programador. </font></b></font>";
            exit;

        }
    }
?>
</p>
</form>
