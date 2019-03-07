<?php

require "connect/sessao.php";

$hoje = date('d/m/Y');

$acao = $_REQUEST['acao'];

if ( !$acao ){
?>
<form method="post" action="#" name='listacompleta' id='listacompleta'>
	<table width=70% border="0" align="center">
		<tr class="titulo">
			<td colspan="2">Lan&ccedil;amento de Equipamentos e Cobran&ccedil;a para Franqueados</td>
		</tr>
	    <tr>	
			<td class="subtitulodireita">&nbsp;</td>
			<td class="campoesquerda">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Franquia:</td>
			<td class="subtitulopequeno">
				<div id="cidadeAjax">
					<?php
					echo "<select name='id_franquia' id='id_franquia'>";
					$sql = "SELECT id, fantasia FROM franquia WHERE classificacao <> 'J' order by fantasia";
					$resposta = mysql_query($sql);
					$txt_franquia = "<option value='0'>Selecione a Franquia</option>";
					while ($array = mysql_fetch_array($resposta))
					{
						$franquia   = $array["id"];
						$nome_franquia = $array["fantasia"];
						$txt_franquia .="<option value=\"$franquia\">$nome_franquia</option>\n";
					}
					echo $txt_franquia;
					echo "</select>";
					?>
				</div>
			</td>                                                                                
		</tr>
		<tr>
			<td colspan="2" class="titulo" align="center">
            	<input type="hidden" name="acao" value="iniciar" />
				<input type="submit" name="enviar" value="    Confirmar    " />
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td></td>
		</tr>
	</table>
</form>
<?php
} else{
	?>
    <script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
	<script language="JavaScript" src="../js/jquery.meio.mask.js" type="text/javascript"></script>

	<script>
	
		window.onload = function(){
			document.listacompra.codigo_barras.focus(); 
		}

		$('#data_venda').mask('99/99/9999');
		
		function number_format(number, decimals, dec_point, thousands_sep) {

			number = (number + '')
					.replace(/[^0-9+\-Ee.]/g, '');
			var n = !isFinite(+number) ? 0 : +number,
					prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
					sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
					dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
					s = '',
					toFixedFix = function (n, prec) {
						var k = Math.pow(10, prec);
						return '' + (Math.round(n * k) / k)
								.toFixed(prec);
					};
			// Fix for IE parseFloat(0.55).toFixed(0) = 0;
			s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
					.split('.');
			if (s[0].length > 3) {
				s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
			}
			if ((s[1] || '')
					.length < prec) {
				s[1] = s[1] || '';
				s[1] += new Array(prec - s[1].length + 1)
						.join('0');
			}
			return s.join(dec);
		}

		function pesquisa_produto(codigo){
			if ( codigo != '' ){
				var req = new XMLHttpRequest();
				req.open('GET', 'carrega_produto.php?codigo='+codigo , false);
				req.send(null);
				if (req.status != 200) return '';
				var retorno = req.responseText;
		
				if ( retorno.trim() == '' ){
					alert('PRODUTO NÃO CADASTRADO');
					$('#codigo_barras').focus();
				}else{
					var array      = retorno.split('][');
					$('#nome_produto').text(array[0]);
					$('#valor_produto').val( number_format(array[1],2,',','.'));
					$('#codigo_barras').val(array[2]);
				}
			}
		}
		
		function grava_produto(){
			
			var id_franquia = $('#id_franquia').val();
			var codigo      = $('#codigo_barras').val();
			var nserie      = $('#numero_serie').val();
			var idvenda     = $('#iptIdVenda').val();
			
			$.ajax({
				url: "salva_produto.php", //teste somente para retornar success
				data: {
						id_franquia: id_franquia,
						codigo_barra: codigo,
						numero_serie: nserie,
						id_venda: idvenda
				},
				type: "POST",
				async: false,
				success: 
					function( dataResult ) {
					
						var retorno2   = dataResult;
						var array      = retorno2.split('][');
						var idvenda2   = (array[0]).trim();
						var tabela     = array[1];
						
						$( ".divNumeroPedido" ).text( idvenda2 );
	                    $( "input[name=iptIdVenda]" ).val( idvenda2 );
										
						$('#tabela_compra').html(tabela);
						
						$('#codigo_barras').val('');	
						$('#numero_serie').val('');
						$('#codigo_barras').focus();
				
					}
				});

		}
		
		function mostra_parcelas(qtd_parc, valor_total, idVenda){
			
			
			var html = '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="bodyText" >';
			
			var j = 0;
			$('#parcelas').html('');
			
			var hoje = $('#data_venda').val();

//			var hoje = $('#vencimento1').val();
			
			// var hoje = new Date();
            var dia = hoje.substr(0,2);
            var mes = hoje.substr(3,2);
            var ano = hoje.substr(6,4);
            var d = dia;
			var valor_parcela = valor_total / qtd_parc;
			
			var j = 0;
			for (var i = 0; i < qtd_parc; i++) {
				
				j++;
				
				var num = i + 1; // número da parcela

				mes++; // adiantamos o mês
				// para os meses que possui 30 dias e não 31
				if (mes == 4 || mes == 6 || mes == 9 || mes == 11) {
					if (dia == 31)
						dia = 30; // retroagimos para o dia 30
				} else {
					dia = d; // caso contrário recuperamos a informação do dia
				}
				if (mes == 2) { // se o mês é fevereiro
					if (dia == 31 || dia == 30) { // e os dias são 30 ou 31
						// muda o dia para o último dia do mês considerando ano bisexto
						dia = (new Date(ano, 1, 29).getMonth() == 1) ? 29 : 28;
					}
				}
				
				// se o mês passou de dezembro, viramos o ano
				if (mes > 12) {
					mes = 1;
					ano++;
				}
	
				//Add p zero
				if(mes < 10){
					mes = '0'+mes;
				}
				
				var vencimento = dia + '/' + mes + '/' + ano;

				html += '<tr style="text-align:right">\n\
							<td width="50%">\n\
							\n\
							</td>\n\
							<td>\n\
								'+j	+'&deg; - \n\
							</td>\n\
							<td>\n\
								Valor\n\
							</td>\n\
							<td>\n\
								R$ '+number_format(valor_parcela,2,'.',',')+'\n\							</td>\n\
							<td>\n\
								Vencimento\n\
							</td>\n\
							<td>\n\
								<input type="text" id="vencimento'+j+'" name=vencimento[] alt="decimal" value="'+vencimento+'" >\n\
							</td>\n\
						</tr>';
				 
			}
			html += '</table>';
			html += '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="bodyText" >';
			html += '<tr style="text-align:right">\n\
						<td colspan="6">\n\
							<input type="button" value=" CONFIRMAR D&Eacute;BITO " onClick="ConfirmarDebito('+idVenda+')" >\n\
						</td>\n\
					</tr>\n\
					</table>';
					
			$('#parcelas').html(html);
		}
		
		function ConfirmarDebito(Venda){
			
			frm = document.listacompra;
    		frm.action = 'painel.php?pagina1=salva_venda.php';
			frm.submit();
			
		}
		
	</script>
    <form method="post" action="#" name='listacompra' id='listacompra'>
        <table width=70% border="0" align="center">
            <tr class="titulo">
                <td colspan="2">Lan&ccedil;amento de Equipamentos e Cobran&ccedil;a para Franqueados</td>
            </tr>
            <tr>	
                <td class="campoesquerda" colspan="2">&nbsp;</td>
            </tr>
            <tr>	
                <td class="campoesquerda" colspan="2" style="text-align:right">
                	PEDIDO N&deg;.
					<span class="divNumeroPedido"></span>
					<input type="hidden" name="iptIdVenda" id="iptIdVenda" value="">
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Franquia:</td>
                <td class="subtitulopequeno">
					<?php
                    $sql = "select fantasia from franquia where id = ".$_REQUEST['id_franquia'];
                    $resposta = mysql_query($sql);
                    echo mysql_result($resposta,0,"fantasia");
                    ?>
                </td>                                                                                
            </tr>
			<tr>
				<td class="subtitulodireita">C&oacute;digo de Barras:</td>
                <td class="subtitulopequeno">
                	<input type="hidden" name="id_franquia" id="id_franquia" value="<?php echo $_REQUEST['id_franquia']; ?>"/>
                    <input type="hidden" name="id_venda" id="id_venda_registro" value=""/>
                	<input type="text" value="" name="codigo_barras" id="codigo_barras" onblur="pesquisa_produto(this.value)"/>
                </td>
			</tr>
			<tr>
				<td class="subtitulodireita">Descri&ccedil;&atilde;o:</td>
                <td class="subtitulopequeno">
                	<span id="nome_produto"></span>
                </td>
			</tr>
            <tr>
				<td class="subtitulodireita">N&deg; de S&eacute;rie:</td>
                <td class="subtitulopequeno">
                	<input type="text" value="" name="numero_serie" id="numero_serie"/>
                </td>
			</tr>
            <tr>
                <td colspan="2" class="titulo" align="center">
                    <input type="button" name="enviar" value="    Confirmar    " onclick="grava_produto()" />
                </td>
            </tr>
            
            <tr>	
                <td class="campoesquerda" colspan="2">&nbsp;</td>
            </tr>
            <tr>
            	<td colspan="2">
		            <span id="tabela_compra"></span>
                </td>
            </tr>
            <tr>
            	<td colspan="2">
		            <span id="parcelas"></span>
                </td>
            </tr>
            
        </table>
        
    </form>
	<?php
}
?>