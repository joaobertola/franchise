<?php
//	require ("connect/sessao.php");
	
	print_r( $_REQUEST );
	
	$codloja = $_REQUEST['codloja'];
	$numdoc = $_REQUEST['numdoc'];
?>
<table align='center' width='750' border='0' cellpadding='0' cellspacing='1'>
	 		<tr>
				<td colspan='11' class='titulo' align="center">LANÇAMENTO DE VALORES EM FATURA</td>
			</tr>
			<tr>
				<td colspan='10'>&nbsp;</td>
			</tr>
            <tr>
				<td colspan='2' class='subtitulodireita'>Código</td>
				<td colspan='9' class='subtitulopequeno'><?=$codloja?></td>
			</tr>

			<tr>
				<td colspan='2' class='subtitulodireita'>Razão Social</td>
				<td colspan='8' class='subtitulopequeno'><?=$razaosoc?></td>
			</tr>
			<tr>
				<td colspan='2' class='subtitulodireita'>Nº Documento</td>
				<td colspan='4' class='subtitulopequeno'><?=$numdoc?></td>
				<td width="11%" class='subtitulodireita'>Vencimento:</td>
				<td width="11%" class='subtitulopequeno'><?=$vencimento?></td>
				<td width="6%" class='subtitulodireita'>Valor : </td>
				<td width="6%" class='subtitulopequeno'><?=$valor?></td>
			</tr>
			<tr>
				<td colspan='10'>&nbsp;</td>
			</tr>
            
            <tr>
				<td colspan='2'>Tipo de Lançamento</td>
                <td colspan='4'>
                	<select name="tipo_lancamento">
                    	<option value="I">... Selecione ...</option>
						<option value="D">Débito</option>
                        <option value="C">Crédito</option>
                     </select>
                </td>
			</tr>
            <tr>
				<td colspan='2'>Valor</td>
                <td colspan='4'>
                	<input type="text" name="valor" id="valor" maxlength="10">
                </td>
			</tr>
            
		<tr>
			<td colspan='2' class="subtitulodireita">Mensagem</td>
			<td colspan='4' class="campoesquerda">
				<input type="checkbox" name="texto1" onclick="mostra_dados()"/> 
				DESCONTO - Taxa Adicional Mes-Dezembro
				<br>
				<input type="checkbox" name="texto2" onclick="mostra_dados()"/> 
				DESCONTO - Licenças de Software e Soluções Exclusivas
				<br>
				<input type="checkbox" name="texto3" onclick="mostra_dados()"/> 
				REFERENTE - Mensalidade com vencimento em 00/00/0000
				<br>
				<input type="checkbox" name="texto4" onclick="mostra_dados()"/> 
				DESCONTO - Pesquisas e/ou Bloqueios       
			</td>
		</tr>
		<tr>  
			<td colspan='2' class="subtitulodireita">&nbsp;</td>
			<td colspan='4' class="campoesquerda" valign="top"><textarea style="width:80%;" rows='5' name='obs'></textarea></td>
		</tr>
		<tr>
			<td colspan='10'>&nbsp;</td>
		</tr>
            
	 	<tr>
			<td rowspan='$linhas1' width='10%' bgcolor='#999999'></td>
		</tr>
		<tr height='20' bgcolor='FF9966'>
				<td align='center'  width='10%'>Data</td>
				<td align='center'  width='10%'>Tipo</td>
				<td align='center'  width='10%'>Valor</td>
				<td align='center'  width='14%'>Mensagem</td>
				<td align='center'  width='6%'>Btn Excluir</td>
		</tr>
		<tr>
			<td colspan='10' height='1' bgcolor='#666666'></td>
		</tr>
</table>

