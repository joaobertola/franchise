<?php
include('connect/conexao_conecta.php');

if ( empty($id_franquia) )
	$id_franquia = $_REQUEST['id_franquia'];

function CalculaPrestacao2($PVista,$Entrada,$qtd_parcela,$tx_mensal)
{
	$VFin = $PVista-$Entrada;
	$tx_mensal = $tx_mensal/100.00;
	$R = $VFin * $tx_mensal * pow( (1+$tx_mensal),$qtd_parcela)/(pow((1+$tx_mensal),$qtd_parcela)-1);
	return $R;
}

$qtd_parcela      = $_REQUEST['qtd_parcela'];
$valor_solicitado = $_REQUEST['vlr'];
$logon            = $_SESSION['usuario'].'S'.$_SESSION['senha'];

$hoje = date('Y-m-d');
$data = date('d/m/Y');

$sql_lim_disp = "SELECT a.limite_credito, a.banco, a.agencia, a.conta,
						a.tpconta, a.cpftitular, a.titular, b.nbanco 
				 FROM cs2.franquia a
				 INNER JOIN consulta.banco b ON a.banco = b.banco
				 WHERE a.id = $id_franquia";
$qry_lim = mysql_query($sql_lim_disp,$con) or die("Erro SQL: $sql_lim_disp");
$limite_credito  = mysql_result($qry_lim,0,'limite_credito');
$banco_cliente   = mysql_result($qry_lim,0,'banco');
$agencia_cliente = mysql_result($qry_lim,0,'agencia');
$conta_cliente   = mysql_result($qry_lim,0,'conta');
$nbanco          = mysql_result($qry_lim,0,'nbanco');
//$conta_cliente   = substr($conta_cliente,0,strlen($conta_cliente)-1).'-'.substr($conta_cliente,-1,1);

$tpconta         = mysql_result($qry_lim,0,'tpconta');
$tpconta         = ($tpconta    == '1' ? 'CONTA CORRENTE' : 'CONTA POUPANÇA');
$cpfcnpj_doc     = mysql_result($qry_lim,0,'cpftitular');
//$cpfcnpj_doc 	 = maskara($cpfcnpj_doc);
$nome_doc        = mysql_result($qry_lim,0,'titular');
$limite_credito *= 1;



$valor_solicitado = str_replace('.','',$valor_solicitado);
$valor_solicitado = str_replace(',','.',$valor_solicitado);

$sql = "SELECT sum(valor_parcela) AS valor 
		FROM cs2.cadastro_emprestimo_franquia 
		WHERE id_franquia = $id_franquia AND valor_pagamento IS NULL";
$qry = mysql_query($sql,$con) or die("Erro SQL: $sql");
$valor_parcela_avencer = mysql_result($qry,0,'valor');
$valor_parcela_avencer *= 1; 
$valor_solicitado *= 1;
$valor = $valor_solicitado;
$limite_disponivel = $limite_credito - $valor_parcela_avencer;

if ( $limite_disponivel < 0 ) $limite_disponivel = 0;

if ( $valor_solicitado > 0 ){
	if ( $limite_disponivel < $valor_solicitado ){
		$limite_disponivel = number_format($limite_disponivel,2,',','.');	
		$valor_solicitado  = number_format($valor_solicitado,2,',','.');
		echo "<hr><br><div align='center'>DESCULPE !!!   O Limite de Cr&eacute;dito para Antecipa&ccedil;&atilde;o de Boletos dispon&iacute;vel é menor que o valor solicitado.<br><br>Limite Dispon&iacute;vel: R$ $limite_disponivel <br><br>Valor solicitado: R$ $valor_solicitado<br><br>
		<input type='button' value='VOLTAR' onClick='history.go(-1)'>
		</div><hr>";
		exit;
	}
}

if ( $valor_solicitado == 0 ) $valor_solicitado = '';
else $valor_solicitado  = number_format($valor_solicitado,2,',','.');
$mostra_limite = number_format($limite_disponivel,2,',','.');

$mensagem = "Sua Franquia possui um Limite de Cr&eacute;dito para <br>Antecipa&ccedil;&atilde;o no valor de : R$ $mostra_limite";


switch ($qtd_parcela){
	case 0 : $tamanho = 250; break;
	case 1 : $tamanho = 500; break;
	case 2 : $tamanho = 520; break;
	case 3 : $tamanho = 540; break;
	case 4 : $tamanho = 560; break;
	case 5 : $tamanho = 580; break;
	case 6 : $tamanho = 600; break;
}
$tamanho_borda = $tamanho + 5; 

?> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Pop Up</title>
 
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">

function abrir2(){
	document.getElementById('popup_crediario2').style.display = 'block';
}
function fechar2(){

	document.getElementById('popup_crediario2').style.display = 'none';
}
function confirma(){
	f = document.form;
	f.action = '#';
	f.submit();  	
}

function Solicitar(){
	f = document.form;
	f.action = 'painel.php?pagina1=area_restrita/antecipa_credito.php';
	f.submit();
}

$(document).ready(function() {
	
		var maskHeight = $(document).height();
		var maskWidth = $(window).width();
	
		$('#mask').css({'width':maskWidth,'height':maskHeight});
 
		$('#mask').fadeIn(500);	
		$('#mask').fadeTo("slow",0.8);
	
		//Get the window height and width
		var winH = $(window).height();
		var winW = $(window).width();
              
		$('#dialog2').css('top',  winH/2-$('#dialog2').height()/2);
		$('#dialog2').css('left', winW/2-$('#dialog2').width()/2);
	
		$('#dialog2').fadeIn(2000); 
	
	$('.window .close').click(function (e) {
		e.preventDefault();
		$('#mask').hide();
		$('.window').hide();
	});		
	
	$('#mask').click(function () {
		$(this).hide();
		$('.window').hide();
	});			
	
});
</script>
 
<style type="text/css">
 
#mask {
  position:absolute;
  left:0;
  top:0;
  z-index:9000;
  background-color:transparent;
  display:none;
}
  
#boxes .window {
  position:absolute;
  left:0;
  top:0;
  width:440px;
  height:200px;
  display:none;
  z-index:9999;
  padding:20px;
}
 
#boxes #dialog2 {
		position: absolute;
		top: 8%;
		left: 25%;
		width: 550px;
		height: <?=$tamanho_borda?>px;
		padding: 10px 10px 2px 10px;
		border-width: 5px;
		border-color:#87b5ff;
		color: #000066;
		display: none;
		border-style: solid;
		background: #999;
}
.close{display:block; text-align:center;}
 
</style>
</head>
<body>
	<div id="boxes">
        <!-- Janela Modal -->
		<div id="dialog2" class="window">
			<form name='form' method='post' action='#'>
				<table border='0' width='550px' height='<?=$tamanho?>px' align='center' cellpadding='3' cellspacing='1' style='border: 1px solid #F5F5F5; background-color:#FFFFFF'>
					<tr>
						<td colspan='2' bgcolor='87b5ff' align='center'><b><font style='font-size:17px'> <?=$mensagem?> </font></b></td>
					</tr>
        <tr>
            <td width='50%' bgcolor='#F5F5F5'><b><font style='font-size:15px'>Valor que deseja Antecipar</font></b></td>
            <td width='50%' bgcolor='#ECF8FF'><font style='font-size:15px'>
            	<input type='text' id='vlr' name='vlr' maxlength="12" size="16" class="boxnormal"
                          onblur="this.className='boxnormal'" onFocus="this.className='boxover'"
                          onkeydown="FormataValor(this,20,event,2)" onKeyPress="soNumero()" value="<?=$valor_solicitado?>"></font>
            </td>
        </tr>
        <tr >
            <td bgcolor='#F5F5F5'><b><font style='font-size:15px'>N&ordm; Parcelas</font></b></td>
            <td bgcolor='#ECF8FF'>
                <font style='font-size:15px'>
                    <select style='width:20%' name="qtd_parcela">
                    	<?php
						  for ( $j = 1 ; $j <= 8 ; $j++){
                          	if ( $qtd_parcela == $j ) $select = "selected";
							else $select = '';
							echo "<option value='$j' $select> $j </option>";
                           } ?>
                    </select>
                </font>
            </td>
        </tr>
        <tr>
            <td height='30'>&nbsp;</td>
            <td>
                <input type='button' name='Simular' value='Simular' onClick='confirma()' style='cursor:pointer'/>			
                <input type='button' name='Fechar' value='Fechar' style='cursor:pointer' class="close" />
            </td>
        </tr>
        <div id="calculo" style="display:none">
	        <tr style="font-family:Verdana, Geneva, sans-serif; font-size:9px">
    	        <td colspan='2'>
                    	<?php
						if( $valor_solicitado > 0 ){							
							$tabela = "<table border='1' width='100%'>";
							$nparcela = CalculaPrestacao2($valor,0,$qtd_parcela,'5');
							$vencimento = date('d/m/Y');
							for ( $i = 1 ; $i<= $qtd_parcela ; $i++ ){
								$vencimento = SomarData($vencimento, '0', '1', '0');
								$nvr_parcela = number_format($nparcela, 2, ',', '.');
								$tabela .= "<tr>
    	        		   	        <td>$i&ordm; Vencimento</td>
		        	           	    <td>$vencimento</td>
		            	           	<td>R$ $nvr_parcela</td>
                                    <input type='hidden' name='vr_parcela' value='$nvr_parcela' >   
			                    </tr>";
							} 
                    		$tabela .= "</table>";
							echo $tabela;
							?>
                            <table border='0' width='100%'>
								<tr>
									<td colspan=2>&nbsp;</td>
								</tr>
                            	<tr>
									<td colspan=2 align='center'>Conta cadastrada para recebimento da antecipação:</td>
								</tr>
								<tr>
									<td colspan=2><hr></td>
								</tr>
                                <tr>
									<td width='150px'>Banco</td>
                                    <td><?php echo "$banco_cliente - $nbanco";?></td>
                                </tr>
                                <tr>
                                    <td>Agência</td>
                                    <td><?=$agencia_cliente?></td>
                                </tr>
                                <tr>
                                    <td>Tipo Conta</td>
                                    <td><?=$tpconta?></td>
                                </tr>
                                <tr>
                                    <td>Nº da Conta</td>
                                    <td><?=$conta_cliente?></td>
                                </tr>
                                <tr>
                                    <td>CPF/CNPJ do Titular</td>
                                    <td><?=$cpfcnpj_doc?></td>
                                </tr>
                                <tr>
                                    <td>TITULAR</td>
                                    <td><?=$nome_doc?></td>
                                </tr>
                                <tr>
                                    <td colspan=2>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan=2 style="color:#F00">Obs.: Caso queira alterar a conta para recebimento da antecipação, entre em contato com a Franqueadora.</td>
                                </tr>
                                <tr>
                                    <td colspan=2>&nbsp;</td>
                                </tr>
								<tr>
									<td colspan=2><hr></td>
								</tr>
                                
                            </table>                            
                            <tr>
        	    				<td colspan='2' align='center'><input type='button' name='Solicitar Antecipação' value='Solicitar Antecipação' onClick='Solicitar()' style='cursor:pointer' /></td>
	        				</tr>
					<?php }?>
        	    </td>
	        </tr>
    	    
        </div>
        <tr style="font-family:Verdana, Geneva, sans-serif; font-size:9px">
            <td colspan='2'> - O cr&eacute;dito ser&aacute; enviado em até 24 Hs.</td>
        </tr>
        <tr style="font-family:Verdana, Geneva, sans-serif; font-size:9px">
            <td colspan='2'> - Taxa de Antecipa&ccedil;&atilde;o 5% ao m&ecirc;s.</td>
        </tr>
        <tr style="font-family:Verdana, Geneva, sans-serif; font-size:9px">
            <td colspan='2'><font color="#0000FF"><b>Obs: Pague sua fatura em dia e tenha mais este benef&iacute;cio.<b></font></td>
        </tr>

    </table>
</form>
			</div>
<!-- Fim Janela Modal-->
 
<!-- Mascara para cobrir a tela -->
<div id="mask"></div>
 
</div>
 
</body>
</html>