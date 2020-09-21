<?php

//echo "<pre>";
//print_r($_SESSION);

if ( $_REQUEST['rel_franquia'] == '' )
	$id_franquia = $_SESSION['id'];
else
	$id_franquia = $_REQUEST['rel_franquia'];

if (  $id_franquia == 1 or $id_franquia == 163 or $id_franquia == 46 )
	$id_franquia = 247;


?><head>
<meta charset="utf-8">
<title>Creating Popup Div | istockphp.com</title>
<link href="area_restrita/style/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"> </script>
<script type="text/javascript" src="area_restrita/js/script.js"></script>
</head>
<script language='javascript'>
function voltar(){
 	d = document.form1;
    d.action = 'painel.php?pagina1=area_restrita/a_solicitacao_valores.php';
	d.submit();
}

function excluir(id,id_franquia){

	var result = confirm("Confirma Exclusao deste registro ?");
	if (result==true) {
	 	d = document.form1;
    	d.action = 'painel.php?pagina1=area_restrita/a_solicitacao_valores_excluir.php&id='+id+'&id_franquia='+id_franquia;
		d.submit();
    
	}
}

function dataNas(c){ // Coloca as / na data
	if(c.value.length ==2 || c.value.length ==5){
		c.value += '/';
	}
}

function validaDat2(campo,dados,idpedido) {
	if ( dados ){
		var date=dados;
		var ardt=new Array;
		var ExpReg=new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
		ardt=date.split("/");
		erro=false;
		if ( date.search(ExpReg)==-1){
			erro = true;
			}
		else if (((ardt[1]==4)||(ardt[1]==6)||(ardt[1]==9)||(ardt[1]==11))&&(ardt[0]>30))
			erro = true;
		else if ( ardt[1]==2) {
			if ((ardt[0]>28)&&((ardt[2]%4)!=0))
				erro = true;
			if ((ardt[0]>29)&&((ardt[2]%4)==0))
				erro = true;
		}
		if (erro) {
			alert('Data Invalida: '+ dados);
			campo.focus();
			campo.value = "";
			return false;
		}
	}
	// Chamando AJAX
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("GET","area_restrita/a_solicitacao_valores_deposito.php?id_pedido="+idpedido+"&data_deposito="+dados,true);
	xmlhttp.send();
	
	return true;
}

</script>


<form name='form1' method='post' action='#' >
<table border='0' align='center' width='700' cellpadding='0' cellspacing='0' class='bodyText'>
	<tr  height="30">
		<td colspan='2' class='titulo' style="text-align:'center'">PESQUISA DE REQUISI&Ccedil;&Atilde;O DE VALORES </td>
	</tr>
	<tr  height="40">
		<td width='200' class="subtitulodireita">Franquia</td>
		<td class="subtitulopequeno">
		<?php
		if (($tipo == "a") || ($tipo == "c") || ($tipo == "d")) {  
			echo "<select name='rel_franquia' class='boxnormal' onchange='this.form.submit();'>";
			if ($tipo <> "b" ) echo "<option value='TODAS' selected>Todas as Franquias</option>";
		
			$sql = "SELECT id, fantasia FROM franquia 
					WHERE sitfrq = 0 AND classificacao <> 'J'
					ORDER BY id";
			$resposta = mysql_query($sql, $con);
			while ($array = mysql_fetch_array($resposta)) {
				$franquia   = $array["id"];
				$nome_franquia = $franquia.' - '.$array["fantasia"];
				if ( $franquia == $id_franquia ) $select = 'selected';
				else $select = '';
				echo "<option value='$franquia' $select>$nome_franquia</option>\n";
			}
			echo "</select>";
		}else {
			echo $nome_franquia;
			echo "<input name='franqueado' type='hidden' id='franqueado' value= $id_franquia />";
		}
		?>
		</td>
	</tr>
	<tr>
		<td colspan='2'>
            <table width='800' border='0' cellpadding='0' cellspacing='1' class='bodyText'>
            
				<thead>
					<tr>
						<td colspan="4" align="center">
							<input type="text" name="pesquisa" size="50" title="Pesquisa &iacute;tens nas requisi&ccedil;&otilde;es" />
							<input type="submit" value="Pesquisar &Iacute;tem" />
						</td>
					</tr>

					<tr bgcolor='FF9966'>
                    	<td align='center' width="120">N&deg; da Requisi&ccedil;&atilde;o</td>
						<td align='center'>Data Solicita&ccedil;&atilde;o</td>
						<td align='center'>Valor</td>
						<td align='center'><font color="#0000FF">Data Dep&oacute;sito ou Pagamento do Boleto</font></td>
                        <td align='center'>...</td>
					</tr>
                    <tr>
						<td colspan='5' height='1' bgcolor='#666666'>
					</td>
			</tr>
				</thead>
                <tbody>
                <?php
				$pesquisa = $_REQUEST['pesquisa'];
				
				$sql = "SELECT SUBDATE(now(), INTERVAL 30 day) data";
		    	$qr = mysql_query($sql, $con)or die("ERRO:  Segundo SQL  ==>  $sql");
    			$campos = mysql_fetch_array($qr);
    			$data30 = substr($campos["data"], 0, 10);


				if ( $pesquisa )
					$sql_compl = " AND b.descricao like '%$pesquisa%'";
				else
					$sql_compl = "";
					
                // Buscando ITENS
				$sql = "SELECT 
							a.id, date_format(a.dt_cad,'%d/%m/%Y') as data_solicitacao , sum(b.valor) AS valor,
							date_format(a.data_deposito,'%d/%m/%Y') as data_deposito 
						FROM cs2.solicitacao_valores a
						INNER JOIN cs2.solicitacao_valores_item b ON a.id = b.id_sol
						WHERE a.dt_cad >= '$data30' AND a.id_franquia = $id_franquia $sql_compl
						GROUP BY a.id";
				$qry = mysql_query($sql, $con) or die("Erro SQL: $sql");
				if ( mysql_num_rows($qry) == 0 ){
					echo "<script>alert('NENHUM REGISTRO FOI ENCONTRADO')</script>";
				}
				$total = 0;
				while ( $reg = mysql_fetch_array($qry) ){
					$id               = $reg['id'];
					$data_solicitacao = $reg['data_solicitacao'];
					$valor            = number_format($reg['valor'],2,',','.');
					$data_deposito    = $reg['data_deposito'];
					if ( $data_deposito == '00/00/0000')
						$data_deposito    = '';
					
					$total += $reg['valor'];
					echo "
						<tr bgcolor='#E5E5E5'>
							<td align='center'> 
								$id
							</td>
							<td align='center'>
								$data_solicitacao
							</td>
							<td align='center'>
								$valor
							</td>
							<td align='center'>";
							
							if ( $_SESSION['ss_tipo'] == 'a' )
								echo "<input type='text' name='data[]' size='10' maxlength='10' onKeyUp='dataNas(this)' onKeyPress='soNumero();' onBlur=\"validaDat2(this,this.value,'$id')\" value='$data_deposito' onchange='grava_data(this)'/>";
							else
		                      	echo "<font color='#0000FF'><b>$data_deposito</b></font>";
					echo "
							</td>
							<td align='center'>
								<table width='100%'>
									<tr>
										<td width='33%' align='center'>
											<a href=\"painel.php?pagina1=area_restrita/a_solicitacao_valores_altera.php&id=$id&id_franquia=$id_franquia\">
											<img src='/franquias/img/edit.gif' title='Visualizar / Alterar'>
											</a>
										</td>
										<td width='33%' align='center'>
								";
					if ( $data_deposito == '' ){
						echo "
									<a href='#' OnClick='excluir($id,$id_franquia)'>
									<img src='/franquias/img/delete.png' title='Excluir Requisi&ccedil;&atilde;o'>
								</a>";
					}
					
					echo "		</td>
								<td width='33%' align='center'>
									<a href=\"painel.php?pagina1=area_restrita/a_solicitacao_valores3.php&envia=N&id_pedido=$id\">
									<img height='18px' src='/franquias/img/print.gif' title='Imprimir'>
									</a>
								</td>
								</tr>
								</table>
							</td>
						</tr>";
                }
				$total = number_format($total,2,',','.');
				?>
                </tbody>
                <tfoot>
                	<tr class="subtitulopequeno">
                    	<td colspan="2">Total</td>
                        <td align="center"><b><?=$total?></b></td>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                </tfoot>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan='2' align="center">
    		<input type="button" value="    VOLTAR    " onclick="voltar()" />
	    </td>
	</tr>
</table>
</form>
