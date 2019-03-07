<?php
require "connect/sessao.php";
require "connect/sessao_r.php";

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<!--<script type="text/javascript" src="../../../inform/js/prototype.js"></script>-->

<script type="text/javascript" >
	$(document).keydown(function(e){

		if(e.keyCode == 13){
			localizarVisitas();
		}

	});

function localizarVisitas() {
	$.ajax({
		url: 'clientes/a_controle_visitas_listagem.php?protocolo='+document.forms[0].protocolo.value,
		type: 'POST',
		action: 'lista_registro',
		data:({
			foo: 'bar'
		}),
		success:function(results) {
			$("#lista_registro").html(results);
		}
	});
}

function cancelar(){
 	d = document.form1;
    d.action = 'painel.php?pagina1=clientes/a_controle_visitas0.php';
	d.submit();
}

window.onload = function(){
	document.form1.protocolo.focus(); 
}
</script>
<form name="form1" method="post" action="#" >
	<table border="0" align="center" width="850">
		<tr>
                    <td colspan="3" class="titulo">Localizador de Visitas</td>
		</tr>
		<tr>
			<td width="200" class="subtitulodireita">N&uacute;mero da Visita</td>
			<td colspan="2" class="subtitulopequeno">
    			<input name="protocolo" type="text" id="protocolo" size="10" maxlength="6" onFocus="this.className='boxover'" onKeyPress="soNumero();"/>
            	&nbsp;&nbsp;
		   		<button type="button" onClick="localizarVisitas();">Localizar</button></td>
		</tr>
		<tr>
	    	<td colspan="3" class="subtitulopequeno">
				<div id="lista_registro"></div>
			</td>
	    </tr>
		<tr>
	    	<td colspan="3" align="center">
				<button type="button" onClick="cancelar();">Voltar</button></td>
			</td>
	    </tr>
	</table>
</form>

<script>
	$(document).on('keypress', function(e){
		if($('#protocolo').is(':focus') && e.keyCode == 13){
			console.log('aquiii');
			e.preventDefault();
			e.stopPropagation();
			localizarVisitas();

		}
	})
</script>