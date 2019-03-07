<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML> 
	<HEAD>
    	<TITLE> New Document </TITLE>
        <META NAME="Generator" CONTENT="EditPlus">
        <META NAME="Author" CONTENT="">
        <META NAME="Keywords" CONTENT="">

        <META NAME="Description" CONTENT="">



		<script src="../js/jquery-3.1.1.min.js"></script>
		<script src="../js/jquery.dropdownPlain.js"></script>
		<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
		<script type="text/javascript" src="../js/jquery.maskedinput-1.1.1.js"></script>
		<script type="text/javascript" src="../js/jquery.meio.mask.js"></script>
		<script type="text/javascript" src="../js/funcoesJavaDiversas.js"></script>
		<script language="javascript">

		jQuery(function($){
		   $("#data1").mask("99/99/9999");
		   $("#data2").mask("99/99/9999");
		   $("#data3").mask("99/99/9999");
		   $("#data4").mask("99/99/9999");
		});
		
		(function($){
		    // call setMask function on the document.ready event
		      $(function(){
		        $('input:text').setMask();
		      }
		    );
		  })(jQuery);
  
		function Mostra(idDiv,idImg){
			div = document.getElementById(idDiv);
			img = document.getElementById(idImg);
		    if(div.style.display == 'none'){
				div.style.display = 'block';
				div1.style.display = 'none';
				img.src='../../img/menos1.gif';
				img1.src='../../img/mais1.gif';
			}else{
				div.style.display = 'none';
				img.src='../../img/mais1.gif';
		    }
		}
		
		function teste(protocol){
			window.open('d_relatorio_antecipacao3.php?protocolo='+protocol);
		}
		
		function Mostra_2(idDiv1,idImg1){
			div1 = document.getElementById(idDiv1);
			img1 = document.getElementById(idImg1);
		    if(div1.style.display == 'none'){
				div1.style.display = 'block';
				div.style.display = 'none';
				img1.src='../../img/menos1.gif';
				img.src='../../img/mais1.gif';
			}else{
				div1.style.display = 'none';
				img1.src='../../img/mais1.gif';
		    }
		}
		
		function setar(item){
			if ( item == 1 ){
				document.form1.data1.focus();
				
			}else if ( item == 2 ){
				document.form1.data3.focus();
			}
		}
		
		function PopupCenter(url) {
			w = screen.width;
			h = screen.hight;
		    //var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
	    	//var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;
			var dualScreenLeft = 0;
			var dualScreenTop = 0;
	    	var left = ((screen.width / 2) - (w / 2)) + dualScreenLeft;
	    	var top = ((screen.height / 2) - (h / 2)) + dualScreenTop;
							
	    	var newWindow = window.open(url, '', 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
		    if (window.focus) {
	    	    newWindow.focus();
		    }
		}
		
		function pesquisando(datai,dataf,acao){
			if ( acao == 'antecipado' ){
				datai = document.form1.elements.data1.value;
				dataf = document.form1.elements.data2.value;
			}
			if ( acao == 'atrasado' ){
				datai = document.form1.elements.data3.value;
				dataf = document.form1.elements.data4.value;
			}
			PopupCenter("d_relatorio_antecipacao2.php?acao="+acao+"&datai="+datai+"&dataf="+dataf);
		}
		</script>
	</HEAD>
	<BODY>

<?php
session_start();

function diferenca_entre_datas($data,$dt_base,$formato) {

	if ( $formato == 'DD/MM/AAAA' ){
		$d_data = substr($data,0,2);
		$m_data = substr($data,3,2);
		$a_data = substr($data,6,4);
		$d_base = substr($dt_base,0,2);
		$m_base = substr($dt_base,3,2);
		$a_base = substr($dt_base,6,4);
	}else{
		return "FORMATO INVALIDO";
		exit;
	}
	$dias_data = floor(gmmktime (0,0,0,$m_data,$d_data,$a_data)/ 86400);
	$dias_base = floor(gmmktime (0,0,0,$m_base,$d_base,$a_base)/ 86400);
	$val = $dias_data - $dias_base;
	return $val;
}

$name = $_SESSION["ss_name"];
$tipo = $_SESSION["ss_tipo"];

function corZebrada(){
	return $cor_grid = '#F0F0F0';	
}

require "../connect/conexao_conecta.php";

$comando = "SELECT SUM(saldo) AS saldo FROM cs2.contacorrente_antecipacao WHERE origem = 'SDO'";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res);
$saldo		 = $matriz['saldo'];
$montante    = number_format($saldo,2,',','.');

# Quanto foi antecipado ?

$soma_antecipado = 0;
$soma_liquidado = 0;
$soma_atrasado = 0;
$soma_avencer_confirmado = 0;
$soma_avencer_nao_confirmado = 0;

$sql = "SELECT * FROM cadastro_emprestimo ORDER BY id";
$qry = mysql_query($sql,$con);
while ( $reg = mysql_fetch_array($qry) ){

	$id                       = $reg['id'];
	$codloja                  = $reg['codloja'];
	$data_solicitacao         = $reg['data_solicitacao'];
	$data_vencimento          = $reg['data_vencimento'];
	$data_vencimento          = substr($data_vencimento,8,2).'/'.
								substr($data_vencimento,5,2).'/'.
								substr($data_vencimento,0,4);
	
	$vr_emprestimo_solicitado = $reg['vr_emprestimo_solicitado'];
	$valor_parcela            = $reg['valor_parcela'];
	$data_pagamento           = $reg['data_pagamento'];
	$valor_pagamento          = $reg['valor_pagamento'];
	$depositado_cta_cliente   = $reg['depositado_cta_cliente'];
	$data_deposito            = $reg['data_deposito'];
	$protocolo                = $reg['protocolo'];
	
	if ( $depositado_cta_cliente == 'S' )
		$soma_antecipado_confirmado[$protocolo] = $vr_emprestimo_solicitado;
	else
		$soma_antecipado_nao_confirmado[$protocolo] = $vr_emprestimo_solicitado;

	if ( $valor_pagamento > 0 )
		$soma_liquidado += $valor_pagamento;

	$dt_base = date('d/m/Y');
	$avencer = diferenca_entre_datas($data_vencimento,$dt_base,'DD/MM/AAAA');
	
	if ( $avencer < 0 and $valor_pagamento == 0)
		$soma_atrasado += $valor_parcela;
	
	
	if ( $avencer > 0 and $valor_pagamento == 0 ){
		if ( $depositado_cta_cliente == 'S' ){
			$soma_avencer_confirmado += $valor_parcela;
		}else{
			$soma_avencer_nao_confirmado += $valor_parcela;
		}
	}
}


$sql = "SELECT * FROM cadastro_emprestimo_franquia ORDER BY id";
$qry = mysql_query($sql,$con);
while ( $reg = mysql_fetch_array($qry) ){

	$id                       = $reg['id'];
	$codloja                  = $reg['codloja'];
	$data_solicitacao         = $reg['data_solicitacao'];
	$data_vencimento          = $reg['data_vencimento'];
	$data_vencimento          = substr($data_vencimento,8,2).'/'.
								substr($data_vencimento,5,2).'/'.
								substr($data_vencimento,0,4);
	
	$vr_emprestimo_solicitado = $reg['vr_emprestimo_solicitado'];
	$valor_parcela            = $reg['valor_parcela'];
	$data_pagamento           = $reg['data_pagamento'];
	$valor_pagamento          = $reg['valor_pagamento'];
	$depositado_cta_cliente   = $reg['depositado_cta_cliente'];
	$data_deposito            = $reg['data_deposito'];
	$protocolo                = $reg['protocolo'];
	
	if ( $depositado_cta_cliente == 'S' )
		$soma_antecipado_confirmado[$protocolo] = $vr_emprestimo_solicitado;
	else
		$soma_antecipado_nao_confirmado[$protocolo] = $vr_emprestimo_solicitado;

	if ( $valor_pagamento > 0 )
		$soma_liquidado += $valor_pagamento;

	$dt_base = date('d/m/Y');
	$avencer = diferenca_entre_datas($data_vencimento,$dt_base,'DD/MM/AAAA');
	
	if ( $avencer < 0 and $valor_pagamento == 0)
		$soma_atrasado += $valor_parcela;
	
	
	if ( $avencer > 0 and $valor_pagamento == 0 ){
		if ( $depositado_cta_cliente == 'S' ){
			$soma_avencer_confirmado += $valor_parcela;
		}else{
			$soma_avencer_nao_confirmado += $valor_parcela;
		}
	}
}


foreach ($soma_antecipado_confirmado as $dados) {
	$total_soma_antecipado_confirmado += $dados;
}
$mostra_soma_antecipado_confirmado     = number_format($total_soma_antecipado_confirmado,2,',','.');

foreach ($soma_antecipado_nao_confirmado as $dados2) {
	$total_antecipado_nao_confirmado += $dados2;
}
$mostra_soma_antecipado_nao_confirmado = number_format($total_antecipado_nao_confirmado,2,',','.');

$total_antecipado = $total_antecipado_nao_confirmado + $total_soma_antecipado_confirmado;
$total_antecipado =  number_format($total_antecipado,2,',','.');

$mostra_soma_atrasado     = number_format($soma_atrasado,2,',','.');
$mostra_soma_liquidado = number_format($soma_liquidado,2,',','.');

$mostra_soma_avencer_confirmado = number_format($soma_avencer_confirmado,2,',','.');
$mostra_soma_avencer_nao_confirmado = number_format($soma_avencer_nao_confirmado,2,',','.');

$total_avencer = $soma_avencer_confirmado + $soma_avencer_nao_confirmado;
$total_avencer = number_format($total_avencer,2,',','.');

?>

<form name='form1' method="post" action="<?php $_SERVER['PHP_SELF']; ?>" >
<table width=60% align="center" border="1">
	<tr>
		<td colspan="2" align="center" class="titulo">Relat&oacute;rio de Antecipa&ccedil;&atilde;o de Boletos</td>
	</tr>
	<tr>
		<td width="173" class="subtitulodireita">&nbsp;</td>
		<td width="224" class="campoesquerda">&nbsp;</td>
	</tr>
	<tr>
		<td class="subtitulodireita"><img src='../../img/mais1.gif'/> Montante</td>
		<td class="campoesquerda">R$ <?=$montante?></td>
	</tr>
	<tr>
		<td colspan="2" class="subtitulodireita">
			<table width="100%">
				<tr>
					<td colspan="2" class="subtitulodireita">
                    
                    <a style='cursor: pointer;' class='abre_fecha' onclick="Mostra('conteudo0','img0'); setar(1)"><img id='img0' src='../../img/mais1.gif'/><font color="#0000FF"> Quanto foi Antecipado</font></a></td>
				</tr>
					<tr>
					<td width="173" class="subtitulodireita">&nbsp;&nbsp;&nbsp;&nbsp;&bull; Confirmado</td>
					<td width="224" class="campoesquerda">R$ <?=$mostra_soma_antecipado_confirmado?></td>
				</tr>
				<tr>
					<td class="subtitulodireita">&nbsp;&nbsp;&nbsp;&nbsp;&bull; Nao confirmado</td>
					<td class="campoesquerda">R$ <?=$mostra_soma_antecipado_nao_confirmado?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>-----------------</td>
				</tr>
				<tr>
					<td class="subtitulodireita">&nbsp;</td>
					<td class="campoesquerda">R$ <?=$total_antecipado?></td>
				</tr>
                
				<tr>
                	<td colspan="2">
                    	
						<div id='conteudo0' style='display: none;'>
                        <table bgcolor="#99CCCC" width="100%">
                        <tr>
                        <td>
						<p>Informe o per&iacute;odo<br>
   	                    <input type="text" id="data1" name="data1"/> at&eacute; 
       	                <input type="text" id="data2" name="data2"/>&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="button" name="pesquisa" value="Pesquisar" onClick="pesquisando(data1,data2,'antecipado')"</p>
                        </td>
                        </tr>
                        </table>
						</div>
					</td>
				</tr>
			</table>
	    </td>
	</tr>
	<tr>
		<td class="subtitulodireita"><img src='../../img/mais1.gif'/> Quanto foi Liquidado</td>
		<td class="campoesquerda">R$ <?=$mostra_soma_liquidado?></td>
	</tr>
    
   
	<tr>
		<td colspan="2" class="subtitulodireita">
			<table width="100%">
				<tr>
					<td colspan="2" class="subtitulodireita">
                    
                    <a style='cursor: pointer;' class='abre_fecha' onclick="Mostra_2('conteudo1','img1'); setar(2)"><img id='img1' src='../../img/mais1.gif'/><font color="#0000FF"> Quanto est&aacute; Atrasado</font></a></td>
				</tr>
					<tr>
					<td width="173" class="subtitulodireita">&nbsp;&nbsp;&nbsp;&nbsp;&bull; Confirmado</td>
					<td width="224" class="campoesquerda">R$ <?=$mostra_soma_atrasado?></td>
				</tr>

				<tr>
                	<td colspan="2">
						<div id='conteudo1' style='display: none;'>
                        <table bgcolor="#99CCCC" width="100%">
                        <tr>
                        <td>
						<p>Informe o per&iacute;odo<br>
   	                    <input type="text" id="data3" name="data3"/> at&eacute; 
       	                <input type="text" id="data4" name="data4"/>&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="button" name="pesquisa" value="Pesquisar" onClick="pesquisando(data3,data4,'atrasado')"</p></p>
                        </td>
                        </tr>
                        </table>
						</div>
					</td>
				</tr>
			</table>
	    </td>
	</tr>
    
    
	<tr>
		<td colspan="2" class="subtitulodireita">
			<table width="100%">
				<tr>
					<td colspan="2" class="subtitulodireita"><img src='../../img/mais1.gif'/> Quanto &agrave; vencer</td>
				</tr>
					<tr>
					<td width="173" class="subtitulodireita">&nbsp;&nbsp;&nbsp;&nbsp;&bull; Confirmado</td>
					<td width="224" class="campoesquerda">R$ <?=$mostra_soma_avencer_confirmado?></td>
				</tr>
				<tr>
					<td class="subtitulodireita">&nbsp;&nbsp;&nbsp;&nbsp;&bull; Nao confirmado</td>
					<td class="campoesquerda">R$ <?=$mostra_soma_avencer_nao_confirmado?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
                    <td>-----------------</td>
				</tr>
				<tr>
					<td class="subtitulodireita">&nbsp;</td>
					<td class="campoesquerda">R$ <?=$total_avencer?></td>
				</tr>
			</table>
	    </td>
	</tr>
</table>

<form name='form1' method="post" action="<?php $_SERVER['PHP_SELF']; ?>" >
<table width=60% align="center" border="1">
	<tr>
		<td colspan="4" align="center" class="titulo">Extrato de Conta Corrente - Antecipa&ccedil;&atilde;o</td>
	</tr>
    <tr>
    	<td>Data</td>
        <td>Descricao</td>
        <td>Valor</td>
        <td>&nbsp;</td>
    </tr>
    <?php
    $sql_conta = "SELECT origem, codloja, date_format(data_lancamento,'%d/%m/%Y') as data_lancamento, 
						 operacao, valor, descricao, protocolo 
				  FROM contacorrente_antecipacao
				  ORDER BY id";
	$qry_cta  = mysql_query($sql_conta,$con);
	$cor_grid = corZebrada();
	$a_cor    = array('#FFFFFF', $cor_grid);
	$cont     = 0;
	while ( $reg = mysql_fetch_array($qry_cta) ){
		$cont++;
		$cor    = $a_cor[$cont % 2];
		$origem = $reg['origem'];
		$codloja = $reg['codloja'];
		$data = $reg['data_lancamento'];
		$operacao = $reg['operacao'];
		$descricao = $reg['descricao'];
		$nprotocolo = $reg['protocolo'];
		
		if ( $operacao == '0' ) $mop = "<font color='#0000FF'>C</font>";
		else $mop = "<font color='#FF0000'>D</font>";
		$valor = number_format($reg['valor'],2,',','.');
		
		if ( $origem == 'SDO' ){
			$descricao = 'SALDO INICIAL'; 
			$mop = '';
			$saldo_i += $reg['valor'];
		}else{
			$saldo_i -= $reg['valor'];
			$descricao = "<a href='#' onClick='teste(\"$nprotocolo\")'>$descricao</a>";
		}
		echo "<tr align='left' style='cursor:pointer' class='grid_form_relatorio' bgcolor='$cor' onMouseOver='set_bgcolor($cont, color_over);' onMouseOut='set_bgcolor($cont, '$cor')>
				<td>$data</td>
				<td>$descricao</td>
				<td align='right'>$valor</td>
				<td>$mop</td>
			  </tr>
			  ";
	}
	if ( $saldo_i >= 0 )
		$saldo_i = "<font color='#0000FF'><b>".number_format($saldo_i,2,',','.')."</b></font>";
	else
		$saldo_i = "<font color='#FF0000'><b>".number_format($saldo_i,2,',','.')."</b></font>";
	?>
    <tr>
    	<td colspan="2">Saldo Atual</td>
        <td align="right"><?=$saldo_i?></td>
        <td>&nbsp;</td>
    </tr>
</table>
</form>		
		
	</BODY>
</HTML>