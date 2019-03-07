<?php
require "connect/sessao.php";
?>
<script type="text/javascript" src="../js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" language="javascript">


function ir(){

	form = document.form1;
	form.action = 'painel.php?pagina1=Franquias/b_extrato_venda.php';
	form.submit();
}


//função para aceitar somente numeros em determinados campos


function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}

function soNumeros(v){
    return v.replace(/\D/g,"")
}
// formato mascara data
function data(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que não é digito
    v=v.replace(/(\d{2})(\d)/,"$1/$2")
    v=v.replace(/(\d{2})(\d)/,"$1/$2")

    return v
}

function mostrar(id){
	if (document.getElementById(id).style.display == 'none'){
		document.getElementById(id).style.display = '';
	}
}

function ocultar(id){
	document.getElementById(id).style.display = 'none';
}

function mostrar2(id){
	if (document.getElementById(id).style.display == 'none'){
		document.getElementById(id).style.display = '';
	}
}

function ocultar2(id){
	document.getElementById(id).style.display = 'none';
}


function init(form){
	form.vencimento1.value = '';
	form.vencimento2.value = '';
}

window.onload = function(){
	document.form1.franqueado.focus(); 
}

</script>
<body onLoad="init(document.form1)">

<form name="form1" method="post" action="painel.php?pagina1=Franquias/b_extrato_venda2.php">
	<table width="90%" border="0" align="center">
		<tr class="titulo">
			<td colspan="2">EXTRATO DE CONTRATOS - ( VENDA COMPLETA )</td>
		</tr>
		<tr>
			<td width="25%" class="subtitulodireita">&nbsp;</td>
			<td width="75%" class="campoesquerda">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Franquia</td>
			<td class="campoesquerda">
			<?php
			echo "<select name='franqueado' class='boxnormal'>";
			if ( $_SESSION['id'] == 163 ){
				echo "<option value='todos' selected>Todas as Franquias</option>";
				$sql = "select id, fantasia from franquia where sitfrq=0 and id_franquia_master = 0 order by id";
			}else{
				if ( ( $_SESSION['id'] == '247' ) or ($_SESSION['id'] == '1204') )
					$id = 'where id = 1 or id = 2';
				else
					$id = $_SESSION['id'];
				
				echo "<option value='' selected>Selecione...</option>";
				$sql = "select id, fantasia from franquia $id";
			}
			$resposta = mysql_query($sql,$con);

			while ($array = mysql_fetch_array($resposta)) {
				$franquia   = $array["id"];
				$nome_franquia = $franquia.' - '.$array["fantasia"];
				if($_REQUEST['franqueado'] == $franquia){
					echo "<option value='$franquia' selected>$nome_franquia</option>\n";
				}else{
					echo "<option value='$franquia'>$nome_franquia</option>\n";	
				}					
			}
			echo "</select>";
			 ?>
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Per&iacute;odo de afilia&ccedil;&atilde;o</td>
			<td class="campoesquerda">
        		<input type="text" name="vencimento1" maxlength="10" size="12" onKeyPress="mascara(this,data)" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      	&nbsp;e&nbsp;
      			<input type="text" name="vencimento2" maxlength="10" size="12" onKeyPress="mascara(this,data)" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /> 
      	dd/mm/aaaa
        	</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Ordem por: </td>
			<td class="campoesquerda">
				<table>
					<tr>
						<td class="subtitulopequeno"><input type="radio" name="ordem" value="codloja" onClick="javascript: ocultar('prefiltro');ocultar2('premio1');ocultar2('premio2');return true;" /></td>
						<td class="subtitulopequeno">C&oacute;digo</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Fun&ccedil;&atilde;o</td>
			<td class="campoesquerda">
				<input type="radio" name="funcao" value="C" checked/>
				&nbsp;Consultor &nbsp;&nbsp;
				<input type="radio" name="funcao" value="A"/>
				&nbsp;Agendador&nbsp;&nbsp;
				<select name="consultorAgendador" class="hidden">
					&nbsp;&nbsp;<option value="0">Todos</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="titulo">&nbsp;</td>
		</tr>
        <tr>
        	<td colspan="2" >
<!--	            <table id="prefiltro" style="display:none;" border="0" width="100%" cellpadding="0"/>-->
<!--					<tr>-->
<!--				    <td width="25%" class="subtitulodireita">Tipo de Relat&oacute;rio</td>-->
<!--				    <td width="75%" class="subtitulopequeno"><label style="cursor:pointer"/>Bal&atilde;o / VVI-->
<!--                    	<input type="radio" name="tp_rel" value="1" onClick="javascript: ocultar2('premio2');mostrar2('premio1');return true;"/></label>-->
<!--					&nbsp;&nbsp;&nbsp;&nbsp;-->
<!--			        <label style="cursor:pointer"/>Fixo / Meta-->
<!--                    	<input type="radio" name="tp_rel" value="2" onClick="javascript: ocultar2('premio1');mostrar2('premio2');return true;"/></label>        -->
<!--			        </td>-->
<!--			    </tr>-->
<!--                </table>-->
            </td>
    	</tr>
        
<!--        <tr>-->
<!--        	<td colspan="2" >-->
<!--	            <table id="premio1" style="display:none;" border="0" width="100%" cellpadding="0"/>-->
<!--					<tr class="titulo">-->
<!--                    <td colspan="2">PREMIA&Ccedil;&Atilde;O BAL&Atilde;O / VVI</td>-->
<!--                    -->
<!--                    </tr>-->
<!--                    --><?php //for ( $i = 1 ; $i <= 6 ; $i++ ){ ?>
<!--                    <tr>-->
<!--						<td width="25%" class="subtitulodireita">--><?//=$i?><!-- Contrato</td>-->
<!--						<td width="75%" class="subtitulopequeno">-->
<!--                        	<input type="text" name="valor[]" alt="decimal"> + VVI + -->
<!--                            <input type="text" name="valorvvi[]" value="13000" alt="decimal">-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                    --><?php //} ?>
<!--                    -->
<!--			    </tr>-->
<!--                </table>-->
<!--            </td>-->
<!--    	</tr>-->

		<!--  TABELA 02 -->

        <tr>
        	<td colspan="2" >
	            <table id="premio2" style="display:none;" border="0" width="100%" cellpadding="0"/>
					<tr class="titulo">
                    <td colspan="2">FIXO / META</td>
                    
                    </tr>
                    <?php for ( $j = 1 ; $j <= 4 ; $j++ ){
						if ( $j == 1 ) $jj = " At&eacute; 15";
						elseif ( $j == 2 ) $jj = 20;
						elseif ( $j == 3 ) $jj = 25;
						else $jj = 35;
						
						?>
                    <tr>
						<td width="25%" class="subtitulodireita"><?=$jj?> Contratos</td>
						<td width="75%" class="subtitulopequeno">
                        	<input type="text" name="fixo[]" alt="decimal"> 
                        </td>
                    </tr>
                    <?php } ?>
                    
			    </tr>
                </table>
            </td>
    	</tr>

		<tr>
			<td>&nbsp;</td>
			<td align="center"><input type="submit" value="Enviar Consulta" />
				<input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " /></td>
		</tr>
	</table>

</form>
<script>
	$(document).ready(function(){

		$('input[name="funcao"]').trigger('change');

		$('select[name="franqueado"]').on('change', function(){

			$.ajax({
				url: 'clientes/BuscaConsultorAgendador.php',
				data:{
					action: 'buscarConsultorAgendador',
					id_franquia: $(this).val(),
				},
				type: 'POST',
				success: function(data){

					var arrData = data.split(';');

					if($('input[name="funcao"]:checked').val() == 'C'){

						$('select[name="consultorAgendador"]').html('<option value="0">Todos</option>' +arrData[0]);

					}else{
						$('select[name="consultorAgendador"]').html('<option value="0">Todos</option>' +arrData[1]);
					}

				}
			})

		});

		$('input[name="funcao"]').on('change', function(){

			$.ajax({
				url: 'clientes/BuscaConsultorAgendador.php',
				data:{
					action: 'buscarConsultorAgendador',
					id_franquia: $('select[name="franqueado"] option:selected').val(),
				},
				type: 'POST',
				success: function(data){

					var arrData = data.split(';');

					if($('input[name="funcao"]:checked').val() == 'C'){

						$('select[name="consultorAgendador"]').html('<option value="0">Todos</option>' +arrData[0]);

					}else{
						$('select[name="consultorAgendador"]').html('<option value="0">Todos</option>' +arrData[1]);
					}

				}
			})

		})
	});
</script>