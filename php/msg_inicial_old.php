<?php
$sql_total_contratos = "SELECT qtd_contrato_mes FROM cs2.franquia WHERE id = '{$_SESSION['id']}'";
$qry_total_contratos = mysql_query($sql_total_contratos, $con);
$meta_mes_contrato = mysql_result($qry_total_contratos,0,'qtd_contrato_mes');

$ano_mes = date("Y-m");

$sql_contratos_mes = "select a.codloja, mid(b.logon,1,5) as logon, a.nomefantasia, a.vendedor,
			date_format(a.dt_cad, '%d/%m/%Y') AS data, c.fantasia from cadastro a
			inner join logon b on a.codloja = b.codloja
			inner join franquia c on a.id_franquia = c.id
			where a.dt_cad like '{$ano_mes}%'  and id_franquia='{$_SESSION['id']}'
			group by a.codloja order by logon";								  
$qry_contratos_mes = mysql_query($sql_contratos_mes, $con);
$total_contrato_mes_fechado = mysql_num_rows($qry_contratos_mes);
$total_falta = $meta_mes_contrato - $total_contrato_mes_fechado; 

$cnx_email = @mysql_pconnect("10.2.2.7", "root", "cntos43");
$sql_frame = "SELECT * FROM dbsites.tbl_escolhaframe WHERE esc_ativo = 'S' ORDER BY esc_data_ativacao DESC LIMIT 10 ";
$qry_frame = mysql_query($sql_frame, $cnx_email) or die ("Erro SQL ao listar os Frames : $sql_frame");

	$dia = date('%d');
	$_dia = date('d');
	if( ($_dia == 06) or ($_dia == 12) or ($_dia == 29) or ($_dia == 03) or ($_dia == 17) or ($_dia == 31) or 
	    ($_dia == 24) or ($_dia == 01) or ($_dia == 15) or ($_dia == 09) or ($_dia == 19) or ($_dia == 20) or 
		($_dia == 26) or ($_dia == 02) or ($_dia == 16) or ($_dia == 22) or ($_dia == 08) or ($_dia == 18) or 
		($_dia == 07) or ($_dia == 21) or ($_dia == 28) or ($_dia == 13) or ($_dia == 04) or ($_dia == 14) or
		($_dia == 27) or ($_dia == 05)){		
		$largura = '520';
		$altura  = '800';	
	}
	
	if( ($_dia == 10) or ($_dia == 23) or ($_dia == 30) ){
		$largura = '650';
		$altura  = '450';	
	} 
	
	if( ($_dia == 11) or ($_dia == 25) ){
		$largura = '650';
		$altura  = '525';	
	}
	
?>

<script language="javascript">
	window.open('mensagem/popup_web_geral.php', 'popup', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,width='+<?=$largura?>+',height='+<?=$altura?>+',left='+280+', top='+100+',screenX='+0+',screenY='+0+'');

function relatorio(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=clientes/relatorio_cliente_pendencia_email.php&opcao=1';
	frm.submit();
} 

function popup_You_Tube(p_video){
	
	if ( p_video == 'terca01' )
		link_video = 'http://www.youtube.com/watch?v=Ne3UbFo_64s';
	else if ( p_video == 'terca02' )
		link_video = 'http://www.youtube.com/watch?v=Ne3UbFo_64s';
	else if ( p_video == 'terca03' )
		link_video = 'http://www.youtube.com/watch?v=Ne3UbFo_64s';
	else if ( p_video == 'terca04' )
		link_video = 'http://www.youtube.com/watch?v=Ne3UbFo_64s';
	else if ( p_video == 'quarta' )
		link_video = 'http://www.youtube.com/watch?v=Ne3UbFo_64s';
	else if ( p_video == 'quinta' )
		link_video = 'http://www.youtube.com/watch?v=Ne3UbFo_64s';
		
	youtube = window.open(link_video, 'youtube', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width='+750+',height='+500+',left='+350+', top='+200+',screenX='+350+',screenY='+200+''); 
}  
</script>

<form name="form" method="post" action="#">
<table border="0" align="center" width="100%"><tr><td>

<table border="0" width="95%" align="center" cellpadding="5" cellspacing="1">
	<tr>
		<td colspan="5" align="center"><?php if($_SESSION['id_master'] == 0) { ?>
<input type="button" value="Pend&ecirc;ncia(s) de sua Franquia" onclick="relatorio()"  style="cursor:pointer" />
<?php } ?>
		</td>
	</tr>
    <tr>
		<td colspan="5" h><p>&nbsp;</p></td>
    </tr>
	<tr>
		<td colspan="5"  bgcolor="#FF33CC" align="center"><a href="../php/clientes/documentos/SCRIPT-para-REAJUSTE-DE-PACOTES.doc" target="_blank"><b>Script para Reajuste de Pacotes (DOC 46KB)</b></a></td>
  </tr> 
    <tr>
		<td colspan="5"><p>&nbsp;</p></td>
    </tr>

<!--

<tr>
	<td colspan="5" align="center">
		<table width="60%" cellpadding="1" cellspacing="1" border="0"style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
    		<tr>
	        	<td colspan="2">
    	        	<div align="center">
<p><font style="font-size:20px; color:#F00"><b>III REUNI�O NACIONAL DE FRANQUIAS WEB CONTROL EMPRESAS</b></font></p></div>
<hr>
				</td>
			</tr>
            <tr>
            	<td width="50%">
	            	<div align="center" font color="#FF6600" style="font-size:16px"><b>LAUDAS</b></font>
                    </div>
				</td>
            	<td>
                	<div align="center" font color="#FF6600" style="font-size:16px"><b>V&Iacute;DEOS</b></font>
                    </div>
				</td>
			</tr>
            <tr>
            	<td colspan="2">
                	<hr/>
                </td>
            </tr>
            <tr>
            	<td align="center" width="50%">
            	  <p>&deg; <a href="javascript:window.open('http://www.webcontrolempresas.com.br/download/3a_reuniao_terca.swf')">Ter&ccedil;a-Feira</a></p></td>
                <td align="center">
                <p>&deg; <a href="#">Video 1<br />
                <p>&deg; <a href="#">Video 2<br />
                <p>&deg; <a href="#">Video 3<br />
                <p>&deg; <a href="#">Video 4<br />
                </a></p></td>
			</tr>
			<tr>
            	<td colspan="2">
                	<hr/>
                </td>
            </tr>
            <tr>
              <td align="center">&deg; <a href="javascript:window.open('http://www.webcontrolempresas.com.br/download/3a_reuniao_quarta.swf')">Quarta-Feira<br />
              </a></td>
              <td align="center">
              	&deg; <a href="#" onclick="popup_You_Tube('quarta')">V&iacute;deo 1</a></td>
            </tr>
			<tr>
            	<td colspan="2">
                	<hr/>
                </td>
            </tr>
            <tr>
              <td align="center">&deg; <a href="javascript:window.open('http://www.webcontrolempresas.com.br/download/3a_reuniao_quinta.swf')">Quinta-Feira</a></td>
              <td align="center">
              	&deg; <a href="#" onclick="popup_You_Tube('quinta')">V&iacute;deo 1</a></td>
            </tr>
            
		</table>
	</td>
</tr>

->

<!--

3a. REUNIAO NACIONAL DE FRANQUIAS
<tr>
	<td colspan="5" align="center">
    <table width="60%" cellpadding="1" cellspacing="1" border="1">
    	<tr>
        	<td>
            
            <div align="center">
<p><font style="font-size:20px; color:#F00"><b>III REUNI�O NACIONAL DE FRANQUIAS WEB CONTROL EMPRESAS</b></font></p></div>
<hr>
<p>Prezados franqueados,</p>
<div align="justify">
<p>Comunicamos a todas as franquias Web Control Empresas que  realizaremos nossa <b>III REUNI�O NACIONAL  DE FRANQUIAS WEB CONTROL EMPRESAS</b> nos dias <font color="#FF0000"><b><u>6,  7, 8 e 9 de Janeiro de 2014 das 8h �s 18h.�  - Traje dos participantes: Social.</u></b></font></p>
</div>
<div align="justify"><p>A �NFASE DA NOSSA REUNI�O � TOTALMENTE COMERCIAL E TRATAREMOS  DE ASSUNTOS DA MAIOR IMPORT�NCIA DE TODOS OS FRANQUEADOS.</p>
</div>
<div align="justify">
<p>Para sobrevivermos no  ano de 2014 precisamos nos adaptar a nova realidade do mercado adotando todas as  estrat�gias que ser�o abordadas pelo Franqueador.</p>
</div>
<div align="left">
<ol>
  <li>Nova Meta M�nina Semanal;</li>
  <li>Estrutura  humana para o comercial;</li>
  <li>Expans�o  da Franquia na sua �rea de atua��o;</li>
  <li>Novo  Modelo de Premia��o Semanal para as Franquias;</li>
  <li>Novas  estrat�gias de contrata��o de vendedores;</li>
  <li>Obje��es  dos candidatos durante a entrevista comercial;</li>
  <li>Novas  estrat�gias de atua��o no mercado;</li>
  <li>Novo  Material a ser trabalhado com as equipes de vendas;</li>
</ol>
</div>
<p>� obrigat�ria a presen�a do Administrativo e Comercial das  franquias.</p>
<div align="justify">
<p>Na aus�ncia de qualquer franqueado, o mesmo n�o ter�  condi��es de desenvolver nenhum trabalho dentro das Diretrizes do Franqueador para  o exerc�cio de 2014 e por isso os acessos ser�o bloqueados at� a devida  reciclagem de <b>DUAS SEMANAS</b> nas  depend�ncias do Franqueador.</p>
</div>
<div align="left">
<p>Atenciosamente,</p>
<p><b>Ananias N. Teixeira</b><br />
  Diretor Comercial � Franquias e Expans�o<br />
  Web Control Empresas � Sites, Solu��es e Pesquisas</p>
</div>
  		</td>
        </tr>
        </table>
    </td>
</tr>

-->

<!--
<tr>
	<td colspan="5" align="center"><a href="../php/clientes/documentos/SCRIPT-PARA-COBRANCA.pdf" target="_blank"><b><font style="font-size:22px" color="#FF0000"><blink>NOVO SCRIPT COBRAN�AS - EVITANDO OBJE��ES</blink></font></b></a></td></tr>
</tr> 
-->
<!--
<tr><td colspan="5" align="center"><a href="clientes/documentos/COMUNICADO-aS-FRANQUIAS.pdf" target="_blank"><blink><b><font color="red" style="font-size:20px">2� REUNI�O NACIONAL DE FRANQUIAS WEB CONTROL EMPRESAS <br>NOVAS DIRETRIZES COMERCIAIS A PARTIR DE 2013</font></b></blink></a></td></tr>
-->


<!--tr><td colspan="5" align="center"><a href="clientes/documentos/TELE-TREINAMENTO-OBRIGATORIO_11_02_2012.pdf" target="_blank"><blink><b><font color="#0000FF" style="font-size:20px">TELE TREINAMENTO OBRIGATORIO - VIRTUAL FLEX 2.0</font></b></blink></a></td></tr>
<tr><td colspan="5" align="center"><a href="clientes/documentos/ESCALA_DE_TELE_TREINAMENTO_11_05_2012.pdf" target="_blank"><blink><b><font color="#0000FF" style="font-size:20px">ESCALA DE TELE TREINAMENTO</font></b></blink></a></td></tr>



<tr><td colspan="5" align="center">Minha Franquia se comprometeu com a Diretoria em <b><font style="font-size:25px" color="#0033FF">< ?=$meta_mes_contrato?></font></b> contratos este m�s.</td></tr>
<tr><td colspan="5" align="center">Faltam <b><font style="font-size:25px" color="#FF0000">< ?=$total_falta?></font></b> para cumprir minha PALAVRA DE FRANQUEADO.</td></tr>
<tr><td colspan="5" height="40">&nbsp;</td></tr>

<!--
<tr>
<td align="center" height="70">
<font color="#FF6600" size="+2"><b>COMO REVERTER UM CANCELAMENTO<p> E DEIXAR O CLIENTE FELIZ</b></font></td>
</tr>

<tr><td align="center" height="70"><img src="../img/logo2.jpg" width="78" height="64"></td>
</tr>

<tr><td align="center" height="70"><a href="clientes/documentos/COMO_REVERTER_CANCELAMENTO.pdf" target="_blank">
<font color="#0033FF" size="+1"><b><u>Clique Aqui e Saiba como</u></b></font></a>
</td></tr>
->


<!--
<tr>
<td align="center" colspan="5">
<font color="#FF6600" style="font-size:14px"><b>COMO EXPLICAR PARA O CLIENTE SOBRE A<br> TAXA ADICIONAL DE DEZEMBRO</b></font>
<br>
<a href="clientes/documentos/SCRIPT_PARA_TAXA_EXTRA_RESUMIDO_12_12_2011.doc" target="_blank"><font color="#0033FF" size="+1"><b><u>Clique Aqui</u></b></font></a><p>
</td>
</tr>
-->



<!--tr><td align="center" height="70" colspan="5"><img src="../img/logo2.jpg" width="78" height="64"></td>
</tr--

<tr><td align="center" height="70" colspan="5"><a href="clientes/documentos/SCRIPT_PARA_TAXA_EXTRA_RESUMIDO_12_12_2012.doc" target="_blank">
<font color="#0033FF" size="+1"><b><u>Clique Aqui</u></b></font></a>
</td></tr>
-->




<tr><td align="center" colspan="5"><a href="clientes/documentos/SCRIPT-PARA-REVERTER-CANCELAMENTO.doc" target="_blank">
<font color="#FF6600" style="font-size:16px"><b><u>COMO REVERTER UM CANCELAMENTO<p> E DEIXAR O CLIENTE FELIZ</u></b></font></a>
</td></tr>



<tr><td width="70%">&nbsp;</td>
<td width="30%" colspan="4" rowspan="9" valign="top" align="left">

<?php $dia = date("H"); ?>


<?php if( ($dia == 01) or ($dia == 10) or ($dia == 19)  ) { ?>
<iframe src="https://www.webcontrolempresas.com.br/franquias/php/graficos/App/Grafico_Franquia_12.php" name="mural" width="650" height="400" marginwidth="0" marginheight="0" Frameborder="0" align="center"></iframe>
<?php } ?>

<?php if( ($dia == 02) or ($dia == 11) or ($dia == 20)  )  { ?>
<iframe src="https://www.webcontrolempresas.com.br/franquias/php/graficos/App/Grafico_Franquia_13.php" name="mural" width="800" height="400" marginwidth="0" marginheight="0" Frameborder="0" align="center"></iframe>
<?php } ?>

<?php if( ($dia == '03') or ($dia == '12') or ($dia == '21') )  { ?>
<iframe src="https://www.webcontrolempresas.com.br/franquias/php/graficos/App/grafico_recupere_msg_inicial.php" name="mural" width="680" height="400" marginwidth="0" marginheight="0" Frameborder="0" align="center"></iframe>
<?php } ?>

<?php if( ($dia == '04') or ($dia == '13') or ($dia == '22')  )  { ?>
<iframe src="https://www.webcontrolempresas.com.br/franquias/php/graficos/App/grafico_crediario_boleto_msg_inicial.php" name="mural" width="680" height="400" marginwidth="0" marginheight="0" Frameborder="0" align="center"></iframe>
<?php } ?>

<?php if( ($dia == '05') or ($dia == '14') or ($dia == '23') )  { ?>
<iframe src="https://www.webcontrolempresas.com.br/franquias/php/graficos/App/grafico_recomende_cliente_msg_inicial.php" name="mural" width="680" height="400" marginwidth="0" marginheight="0" Frameborder="0" align="center"></iframe>
<?php } ?>

<?php if( ($dia == '06') or ($dia == '15') or ($dia == '24') )  { ?>
<iframe src="https://www.webcontrolempresas.com.br/franquias/php/graficos/App/grafico_registro_on_line.php" name="mural" width="680" height="400" marginwidth="0" marginheight="0" Frameborder="0" align="center"></iframe>
<?php } ?>

<?php if( ($dia == '07') or ($dia == '16') )  { ?>
<iframe src="https://www.webcontrolempresas.com.br/franquias/php/graficos/App/grafico_web_control_msg_inicial.php" name="mural" width="680" height="400" marginwidth="0" marginheight="0" Frameborder="0" align="center"></iframe>
<?php } ?>

<?php if( ($dia == '08') or ($dia == '17'))  { ?>
<iframe src="https://www.webcontrolempresas.com.br/franquias/php/graficos/App/grafico_encaminha_cartorio.php" name="mural" width="680" height="400" marginwidth="0" marginheight="0" Frameborder="0" align="center"></iframe>
<?php } ?>

<?php if( ($dia == '09') or ($dia == '18') )  { ?>
<iframe src="https://www.webcontrolempresas.com.br/franquias/php/graficos/App/grafico_contabil_msg_inicial.php" name="mural" width="680" height="400" marginwidth="0" marginheight="0" Frameborder="0" align="center"></iframe>
<?php } ?>


</td></tr>




<tr><td height="30"><b><a href="clientes/documentos/JORNAL_MERCADO_VIRTUAL.pdf">
  <font color="#0000FF" style="font-size:14px">JORNAL DO MERCADO VIRTUAL</font><font color="#FF6600" style="font-size:10px"> <br> (PARA GERENTE DE VENDAS)</font></a></b></td></tr>

<tr><td height="30"><b><a href="clientes/documentos/CARTILHA_COMERCIAL_FRANQUIAS_V2.doc">
  <font color="#0000FF" style="font-size:14px">CARTILHA COMERCIAL </font><font color="#FF6600" style="font-size:10px"> <br> (PARA GERENTE DE VENDAS)</font></a></b></td></tr>

<tr><td height="30"><b><a href="clientes/documentos/SCRIPT_DE_VENDAS_1.doc"><font color="#0000FF" style="font-size:14px">SCRIPT DE VENDAS 1 </font><font color="#FF6600" style="font-size:10px"><br> (MUDAR O CLIENTE PARA UMA GRANDE AVENIDA)</font></a></b></td></tr>

<tr><td height="30"><b><a href="clientes/documentos/SCRIPT_DE_VENDAS_2.doc"><font color="#0000FF" style="font-size:14px">SCRIPT DE VENDAS 2 </font><font color="#FF6600" style="font-size:10px"><br> (7 SUPER NOVIDADES)</font></a></b></td></tr>

<tr><td height="30"><b><a href="clientes/documentos/SCRIPT_PARA_TELEMARKETING.doc"><font color="#0000FF" style="font-size:14px">SCRIPT ASSISTENTE COMERCIAL</font><font color="#FF6600" style="font-size:10px"><br> (AGENDAMENTOS PARA VENDEDORES)</font></a></b></td></tr>

<tr><td height="30"><b><a href="clientes/documentos/FORMULARIO_PARA_DEMONSTRACAO_TELEMARKETING.doc"><font color="#0000FF" style="font-size:14px">FORMULARIO DE DEMONSTRA&Ccedil;&Atilde;O </font><font color="#FF6600" style="font-size:10px"><br> (PARA TELEMARKETING)</font></a></b></td></tr>

<tr><td height="30"><b><a href="clientes/documentos/TAXA_DE_IMPLANTACAO_SUGESTAO.doc"><font color="#0000FF" style="font-size:14px">TAXA DE IMPLANTA&Ccedil;&Atilde;O</font><font color="#FF6600" style="font-size:10px"><br>(SUGEST&Atilde;O � PRODU&Ccedil;&Atilde;O 100 CONTRATOS POR M&Ecirc;S)</font></a></b></td></tr>

<tr><td height="30"><b><a href="../php/clientes/documentos/SCRIPT-PARA-COBRANCA.pdf"><font color="#0000FF" style="font-size:14px">SCRIPT PARA COBRAN�AS</font><font color="#FF6600" style="font-size:10px"><br>(COBRAN�A R�PIDA EVITANDO OBJE��ES)</font></a></b></td></tr>





<!--
<tr><td height="30"><b><a href="clientes/documentos/JORNAL_MERCADO_VIRTUAL.pdf">
  <font color="#0000FF" style="font-size:14px">JORNAL DO MERCADO VIRTUAL</font><font color="#FF6600" style="font-size:10px"> <br> (PARA GERENTE DE VENDAS)</font></a></b></td></tr>

<tr><td height="30"><b><a href="clientes/documentos/CARTILHA_COMERCIAL_FRANQUIAS_V2.doc">
  <font color="#0000FF" style="font-size:14px">CARTILHA COMERCIAL </font><font color="#FF6600" style="font-size:10px"> <br> (PARA GERENTE DE VENDAS)</font></a></b></td></tr>

<tr><td height="30"><b><a href="clientes/documentos/SCRIPT_DE_VENDAS_1.doc"><font color="#0000FF" style="font-size:14px">SCRIPT DE VENDAS 1 </font><font color="#FF6600" style="font-size:10px"><br> (MUDAR O CLIENTE PARA UMA GRANDE AVENIDA)</font></a></b></td></tr>

<tr><td height="30"><b><a href="clientes/documentos/SCRIPT_DE_VENDAS_2.doc"><font color="#0000FF" style="font-size:14px">SCRIPT DE VENDAS 2 </font><font color="#FF6600" style="font-size:10px"><br> (7 SUPER NOVIDADES)</font></a></b></td></tr>

<tr><td height="30"><b><a href="clientes/documentos/SCRIPT_PARA_TELEMARKETING.doc"><font color="#0000FF" style="font-size:14px">SCRIPT PARA TELEMARKETING </font><font color="#FF6600" style="font-size:10px"><br> (AGENDAMENTOS PARA VENDEDORES)</font></a></b></td></tr>

<tr><td height="30"><b><a href="clientes/documentos/FORMULARIO_PARA_DEMONSTRACAO_TELEMARKETING.doc"><font color="#0000FF" style="font-size:14px">FORMULARIO DE DEMONSTRA&Ccedil;&Atilde;O </font><font color="#FF6600" style="font-size:10px"><br> (PARA TELEMARKETING)</font></a></b></td></tr>

<tr><td height="30"><b><a href="clientes/documentos/TAXA_DE_IMPLANTACAO_SUGESTAO.doc"><font color="#0000FF" style="font-size:14px">TAXA DE IMPLANTA&Ccedil;&Atilde;O</font><font color="#FF6600" style="font-size:10px"><br>(SUGEST&Atilde;O � PRODU&Ccedil;&Atilde;O 100 CONTRATOS POR M&Ecirc;S)</font></a></b></td></tr>

<tr><td height="30"><b><a href="../php/clientes/documentos/SCRIPT-PARA-COBRANCA.pdf"><font color="#0000FF" style="font-size:14px">SCRIPT PARA COBRAN�AS</font><font color="#FF6600" style="font-size:10px"><br>(COBRAN�A R�PIDA EVITANDO OBJE��ES)</font></a></b></td></tr>
-->
<tr>
  <td colspan="5" align="center"></td></tr>
<tr><td colspan="5">
  <table border="1" align="center" width="100%" cellpadding="0" cellspacing="1" bordercolor="#FF6600">
  <tr>
    <td colspan="3" align="center"><b><font color="#000000" style="font-size:18px">Alguns modelos de Sites</font><font color="#FF6600"> criados pelos pr�prios clientes</font></b></td></tr>
  <tr><td width="33%"><a href="http://www.intimamentemaismulher.loj.net.br" target="_blank"><font color="#0033FF">www.intimamentemaismulher.loj.net.br</font></a></td>
      <td width="33%"><a href="http://www.festcar.veic.net.br" target="_blank"><font color="#0033FF">www.festcar.veic.net.br</font></a></td>
      <td width="34%"><a href="http://www.floricquatroestacoes.vfx.net.br" target="_blank"><font color="#0033FF">www.floricquatroestacoes.vfx.net.br</font></a></td>
  </tr>

<tr><td><a href="http://www.deaphotobookart.fotog.net.br" target="_blank"><font color="#0033FF">www.deaphotobookart.fotog.net.br</font></a></td>
<td><a href="http://www.danubiacolchoes.vfx.net.br" target="_blank"><font color="#0033FF">www.danubiacolchoes.vfx.net.br</font></a></td>
<td><a href="http://www.dogsecats.loj.net.br" target="_blank"><font color="#0033FF">www.dogsecats.loj.net.br</font></a></td>
</tr>


<tr><td><a href="http://www.bonogrill.rest.net.br" target="_blank"><font color="#0033FF">www.bonogrill.rest.net.br</font></a></td>
<td><a href="http://www.metalurgicanunes.vfx.net.br" target="_blank"><font color="#0033FF">www.metalurgicanunes.vfx.net.br</font></a></td>
<td><a href="http://www.livrariacasadooleiro.vfx.net.br/" target="_blank"><font color="#0033FF">www.livrariacasadooleiro.vfx.net.br</font></a></td>
</tr>

<tr><td><a href="http://www.camilalingerie.loj.net.br/" target="_blank"><font color="#0033FF">www.camilalingerie.loj.net.br</font></a></td>
<td><a href="http://www.balaomagico.vfx.net.br" target="_blank"><font color="#0033FF">www.balaomagico.vfx.net.br</font></a></td>
<td><a href="http://www.midiacriativo.repres.net.br" target="_blank"><font color="#0033FF">www.midiacriativo.repres.net.br</font></a></td>
</tr>

<tr><td><a href="http://www.bmcorretora.vfx.net.br/" target="_blank"><font color="#0033FF">www.bmcorretora.vfx.net.br</font></a></td>
<td><a href="http://www.ribamarsports.loj.net.br/" target="_blank"><font color="#0033FF">www.ribamarsports.loj.net.br</font></a></td>
<td><a href="http://www.lualuamodaintima.loj.net.br/" target="_blank"><font color="#0033FF">www.lualuamodaintima.loj.net.br</font></a></td>
</tr>

<tr>
<td><a href="http://www.pjgcabelosecia.cosm.net.br/" target="_blank"><font color="#0033FF">www.pjgcabelosecia.cosm.net.br</font></a></td>
<td><a href="http://www.uniaopetshop.loj.net.br" target="_blank"><font color="#0033FF">www.uniaopetshop.loj.net.br</font></a></td>
<td><a href="http://www.mayaracabeleireiros.cosm.net.br/" target="_blank"><font color="#0033FF">www.mayaracabeleireiros.cosm.net.br</font></a></td>
</tr>


<tr>
<td><a href="http://www.lookboutique.loj.net.br/" target="_blank"><font color="#0033FF">www.lookboutique.loj.net.br</font></a></td>
<td><a href="http://www.serigrafiamendes.loj.net.br/" target="_blank"><font color="#0033FF">www.serigrafiamendes.loj.net.br</font></a></td>
<td><a href="http://www.cilahciza.loj.net.br/" target="_blank"><font color="#0033FF">www.cilahciza.loj.net.br</font></a><a href="http://www.rodolfogravacoes.loj.net.br/" target="_blank"></a></td>
</tr>

<tr>
<td><a href="http://www.hiperbellamodulados.vfx.net.br/" target="_blank"><font color="#0033FF">www.hiperbellamodulados.vfx.net.br</font></a></td>
<td><a href="http://www.fmsalgados.loc.net.br/" target="_blank"><font color="#0033FF">www.fmsalgados.loc.net.br</font></a></td>
<td><a href="http://www.realceacessorios.loj.net.br/" target="_blank"><font color="#0033FF">www.realceacessorios.loj.net.br</font></a></td>
</tr>

<tr>
<td><a href="http://www.iduc.vfx.net.br/" target="_blank"><font color="#0033FF">www.iduc.vfx.net.br</font></a></td>
<td><a href="http://www.torneadoradoisamigos.vfx.net.br/" target="_blank"><font color="#0033FF">www.torneadoradoisamigos.vfx.net.br</font></a></td>
<td><a href="http://www.bebetravesso.loj.net.br/" target="_blank"><font color="#0033FF">www.bebetravesso.loj.net.br</font></a></td>
</tr>

<tr>
<td><a href="http://www.racemotos.loj.net.br/" target="_blank"><font color="#0033FF">www.racemotos.loj.net.br</font></a></td>
<td><a href="http://www.coracaodenoiva.loj.net.br/" target="_blank"><font color="#0033FF">www.coracaodenoiva.loj.net.br</font></a></td>
<td><a href="http://www.salaoliriosdocampo.loj.net.br/" target="_blank"><font color="#0033FF">www.salaoliriosdocampo.loj.net.br</font></a></td>
</tr>

<tr>
<td \><a href="http://www.dryservice.vfx.net.br/" target="_blank"><font color="#0033FF">www.dryservice.vfx.net.br</font></a></td>
<td><a href="http://www.cilahciza.loj.net.br/" target="_blank"></a></td>
<td>&nbsp;</td>
</tr>


<tr>
  </table>
<table border="0" width="95%" align="center" cellpadding="5" cellspacing="1">
<tr><td align="center">(Se clientes da sua Franquia possuem sites bonitos favor enviar o endere&ccedil;o ao Depto. de Franquias)</td></tr>
</table>  
  
</td></tr>
<tr><td colspan="5">&nbsp;</td></tr>
<tr><td colspan="5" height="60" align="center"><b>&Uacute;LTIMOS MODELOS DE SITES <br><font color="#FF6600" style="font-size:12px">LAN&Ccedil;AMENTOS</font></b></td></tr>
</table>

<table border="0" width="95%" align="center" cellpadding="5" cellspacing="1">
<script language="javascript">
function verSite(p_caminho_foto){
popup = open('http://'+p_caminho_foto+'.vfx.net.br/', 'popup', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width='+1150+',height='+700+',left='+50+', top='+50+',screenX='+0+',screenY='+0+''); 
}
</script>
<?php
	$cont=0;
	$conta_coluna=-1;
	while($rs_frame = mysql_fetch_array($qry_frame)){
		$cont++;
		$conta_coluna++;
		
		if($conta_coluna == 0){
			echo " <tr align='center'>";
		}		
		?>
        <td>
            <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
            <tr><td align="center" width="20%"><a href='#' onClick=verSite('<?=$rs_frame['esc_caminho']?>_v2')>
            <img src='http://www.vfx.net.br/<?=$rs_frame['esc_caminho']?>/site.jpg' title='Clique para visualizar o site' height='100px' width='150px' border='0'></a></td></tr>
            <tr><td align="center"><?=$rs_frame['esc_ramoatividade']?></td></tr></table>
        </td>
		<?php
		if($conta_coluna == 4){
			echo "</tr>";
			$conta_coluna=-1;
		}		
	}
	echo "</table>";
?>
<tr><td>&nbsp;</td></tr>
</table>


</td></tr></table>

<!--
<tr><td colspan="2" height="50">&nbsp;</td></tr>
<tr><td colspan="2" align="center" height="50" bgcolor="#EE9A49">
<a href="Franquias/tabela_preco_padrao.php?id=< ?=$_SESSION['id']?>&tabela=1">
<h3><font color="#0000FF"><b><u>
VOC&Ecirc; QUER VENDER MAIS ??<br>
TABELA ESPECIAL DIA DOS NAMORADOS<br>
(validade at&eacute; 30/07/2011)
</u></b></font></h3>

</a>
</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
-->
</form>
<?php exit; ?>
<table border="0" width="65%" align="center" cellpadding="0" cellspacing="1">
<tr><td align="center"><font style="font-size:30px">Convoca��o para a</font></td></tr>

<tr>
  <td align="center"><b><font style="font-size:30px">1� REUNI�O </font> <font style="font-size:30px" color="#FF0000"><u>EXTRA-ORDIN�RIA</u></font></b></td></tr>

<tr><td align="center"><font style="font-size:30px">Franquias Web Control Empresas</font></td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td><font style="font-size:25px">Prezados Franqueados,<p></font></td></tr>

<tr>
  <td><font style="font-size:25px">Convocamos toda rede de franquias para a 1� REUNI�O EXTRA-ORDIN&Aacute;RIA obrigat&oacute;ria das Franquias Web Control Empresas.</font></td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td align="center"><font style="font-size:25px">Realizaremos a reuni�o no dia<br></font> <font color="#0000FF" style="font-size:25px"><b>06/06/2011 das 8:00 hs as 18:00 hs.</b></font></td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td><font style="font-size:25px">Os assuntos tratados ser�o os seguintes:</font></td></tr>

<tr><td><font style="font-size:20px" color="#FF0000"><b>1� - TAXA DE ADES�O �NICA NACIONAL - TABELADA E CONFERIDA COM O CLIENTE PELA FRANQUEADORA.</font></b></td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td><font style="font-size:20px" color="#FF0000"><b>2� - LAN�AMENTO DO VIRTUAL FLEX � TUDO QUE O EMPRESARIO PRECISA DENTRO DO SEU PROPRIO SITE (treinamento para franquias).</font></b></td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td><font style="font-size:20px" color="#FF0000"><b>3� - NOVAS T�CNICAS DE ABORDAGEM, NOVO FOCO DE CLIENTES  E MONTAGEM DE EQUIPE DE VENDAS.</font></b></td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td><font style="font-size:25px">A reuni�o emergencial sera realizada em apenas um dia e o almo�o correr� por conta da Web Control Empresas.</font></td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td align="center"><img src="../img/bourbon.jpg"></td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td>
<b><font style="font-size:16px" color="#0000FF">
DIA:   06/06 das   8:00 as 18:00  hs  ( 1 DIA)<br>
LOCAL: 	HOTEL BOURBON<br>
ENDERE�O: RUA CANDIDO LOPES, 102 CENTRO<br>
FONE: (41) 3221-4633    0800 701 8181<br>
REFER�NCIA:   PROXIMO PRA�A TIRADENTES<br>
</font></b>
</td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td>
<b><font style="font-size:16px">
Obs 1  -  Todos os franqueados �COMERCIAL E ADMINISTRATIVO� dever�o participar.<br>
Obs 2  -  Despesas com transporte e hospedagem correr�o por conta de cada Franqueado.  O Depto de Franquias possui uma rela��o de hot�is de diversos n�veis de acordo com sua prefer�ncia.
</font></b>
</td></tr>

<tr><td></td></tr>

</table>



<?php
exit;
   $_dia = date('d');
	if( ($_dia == 09)or($_dia == 10)or($_dia == 23)or($_dia == 24)or($_dia == 11)or($_dia == 12)or($_dia == 25)or($_dia == 26) ){
		$largura = '670';
		$altura  = '595';
	}else{
		$largura = '500';
		$altura  = '700';	
	}
?>

	<script language="javascript">
	window.open('mensagem/popup_web_geral.php', 'popup', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,width='+<?=$largura?>+',height='+<?=$altura?>+',left='+280+', top='+100+',screenX='+0+',screenY='+0+'');
	</script>

<?php
$minuto = date("i");

$sql_hora = "SELECT CURTIME() AS hora";
$qry_hora = mysql_query($sql_hora);
$hora = mysql_result($qry_hora,0,'hora');
$hora = substr($hora, 0,3);

$minuto = substr($minuto, -1);

if( ($minuto >=0) and ($minuto < 5) ){
	$minutoxx = date("i");
	$minuto1 = substr($minutoxx, 0,1);
	$hora .= $minuto1;
	$hora .= '0';
}elseif( ($minuto >=5) and ($minuto <= 9) ){
	$minutoxx = date("i");
	$minuto1 = substr($minutoxx, 0,1);
	$hora .= $minuto1;
	$hora .= '5';
}

$s = "SELECT * FROM cs2.imagem_encontro WHERE hora = '$hora'";
$q = mysql_query($s);
if(mysql_num_rows($q) == '0'){
	$s = "SELECT imagem FROM imagem_encontro ORDER BY rand() LIMIT 1 ";
	$q = mysql_query($s);	
	$imagem = mysql_result($q,0,'imagem');
}else{
	$imagem = mysql_result($q,0,'imagem');
}

$tempo = date("H:i");
if( ($tempo > '08:00') and ($tempo < '08:10')){
	$imagem = "fundo_ladroes.png";		  
}
if( ($tempo > '09:00') and ($tempo < '09:10')){
	$imagem = "fundo_ladroes.png";		  
}
if( ($tempo > '11:00') and ($tempo < '11:30')){
	$imagem = "fundo_ladroes.png";		  
}
if( ($tempo > '12:00') and ($tempo < '12:10')){
	$imagem = "fundo_ladroes.png";		  
}
if( ($tempo > '17:00') and ($tempo < '17:10')){
	$imagem = "fundo_ladroes.png";		  
}
if( ($tempo > '18:00') and ($tempo < '18:10')){
	$imagem = "fundo_ladroes.png";		  
}

//echo "=> ".$tempo;
//echo "<br>-< ".$imagem;
	
?>


<table width="90%" cellspacing="1" cellpadding="0" align="center" border="0">
<tr><td align="center"><img src="../img/logo_inform_pagina_inicial.png" height="250px" width="550px"></td></tr>

<tr><td align="center"><font style="font-size:30px"><b>GRANDE LAN�AMENTO</b></font></td></tr>

<tr><td align="center"><b>adiado para</b></td></tr>

<tr><td align="center"><font style="font-size:30px"><b>"QUINTA - FEIRA"</b></font></td></tr>

<tr><td align="center"><font style="font-size:30px"><b>19/05/2011</b></font></td></tr>

<tr><td align="center"><font style="font-size:30px"><b>as 10:30 hs</b></font></td></tr>

</table>

<!-- LAUDAS RANDOMICAS -->
<!-- table width="90%" cellspacing="1" cellpadding="0" class="bodyText" align="center" border="0">
<tr><td align="center"><img src="../img/7_encontro/<?=$imagem?>"></td></tr>
</table -->


<!--
<table width="90%" cellspacing="1" cellpadding="0" class="bodyText" align="center" border="0">
<tr>
<td align="center" height="70">
< font color="#FF6600" size="+2"><b>COMO EXPLICAR PARA O CLIENTE SOBRE A<p> <b>TAXA ADICIONAL DE DEZEMBRO</b></ font></td>
</tr>

<tr><td align="center" height="70"><img src="../img/logo2.jpg" width="78" height="64"></td>
</tr>

<tr><td align="center" height="70"><a href="clientes/documentos/SCRIPT_ PARA_TAXA_ EXTRA.doc" target="_blank">
<font color="#0033FF" size="+1"><b><u>Clique Aqui</u></b></font></a>
</td></tr>
</table>
--> 
<?php exit ;?>
-->
<p>
<hr color="#FFCC00" width="95%">
<p>
<table width="90%" cellspacing="1" cellpadding="0" align="center" border="0">
<tr>
<td align="center" height="70">
<font size="+2"><b>
Convoca��o para o<br>
<font color="#FF4500">7� Grande Encontro Nacional de Franquias</font><br>
Web Control Empresas<br>
</b></font>

<font size="+6" color="#FF0000"><blink>Faltam <?php echo $dia = 14 - date("d");	?> dias</blink></font>
</td>
</tr>
</table>

<table width="90%" bgcolor="#FAC402" height="234" border="0" cellpadding="0" cellspacing="9" align="center">
<tr align="center">
<td width="50%"><img src="../../web/img/1_evento_2010.jpg" width="305" height="229" alt=""></td>
<td width="50%"><img src="../../web/img/4_evento_2010.jpg" width="305" height="229" alt=""></td>
</tr>

<tr><td colspan="2" align="center"><img src="../../web/img/rede_11.jpg" width="618" height="32" alt=""></td></tr>
</table>

<table width="90%" cellspacing="1" cellpadding="0" align="center" border="0">
<tr><td align="left" height="70"><b>Prezado Franqueado,</b></td>
</tr>

<tr><td align="left">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atrav�s deste convocamos Vossa Senhoria a comparecer no 7� ENCONTRO NACIONAL DE FRANQUIAS Web Control Empresas onde trataremos de assuntos pertinentes ao nosso neg�cio e de fundamental import�ncia para o crescimento de todos.<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A sua presen�a � <font color="#FF0000"><b>OBRIGAT�RIA</b></font>, pois visa estabelecer v�rios procedimentos para o excelente andamento de nossos trabalhos no <font color="#FF0000"><b>ANO DE 2011.</b></font><p>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Temos certeza que nestes 2 (Dois) dias voc� vai conhecer v�rias estrat�gias e NOVAS SOLU��ES que trar�o grandes resultados em suas vendas e excelentes retornos financeiros em sua vida.<p>

<font color="#FF0000"><b>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ATEN��O: A SUA AUS&Ecirc;NCIA ACARRETAR� NO BLOQUEIO AUTOM�TICO DE SUA FRANQUIA E DE SEUS ACESSOS at� a sua presen�a PESSOALMENTE NA FRANQUEADORA para o treinamento de 4 (QUATRO) DIAS.
<p>
	
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;INFELIZMENTE das 72 FRANQUIAS PARTICIPANTES no ULTIMO ENCONTRO tivemos  4 (QUATRO)  FRANQUIAS BLOQUEADAS E CANCELADAS por motivo de AUS�NCIA.
</b></font>
</td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td align="center"><img src="../img/bourbon.jpg" width="200px" height="150px"></td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td align="justify">
<font color="#0033FF"><b>
DIA:   14 e 15/03/2011   das   8:00 as 21:00  hs  <font size="+2">( 2 DIAS)</font><br>
LOCAL: 	HOTEL BOURBON<br>
ENDERE�O: RUA CANDIDO LOPES, 102 CENTRO<br>
FONE: (41) 3221-4633    0800 701 8181<br>
REFER�NCIA:   PROXIMO PRA�A TIRADENTES<br>
</b></font>
</td></tr>


<tr><td align="justify">
<br><b>Obs 1 - </b>Traje Social Completo. (vamos tirar fotos para home-page)
<br><b>Obs 2 - </b>Todos os franqueados <b>"COMERCIAL E ADMINISTRATIVO"</b> dever�o participar.
<br><b>Obs 3 - </b>Acompanhantes n�o s�cios favor consultar o Depto de Franquias.
<br><b>Obs 4 - </b>Ser� custeado pela Franqueadora somente as refei��es como: Almo�o e Coffe-Breaks nos dias do Encontro.
<br><b>Obs 5 - </b>Despesas com transporte e hospedagem correr�o por conta de cada Franqueado.  O Depto de Franquias possui uma rela��o de hot�is de diversos n�veis de acordo com sua prefer�ncia.
<br><b>Obs 6 - </b>A pauta de nosso Encontro ser� repassada no dia.
</font>       
</td></tr>
<tr><td height="50">&nbsp;</td></tr>

<tr><td align="center"><b><font size="+2">PROGRAMA��O</font></b></td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td align="left"><u><b>14 de Mar�o de 2011,  Segunda - feira</b></u></td></tr>
<tr><td align="left"><b>N�mero previsto:</b> 120 pessoas</td></tr>
<tr><td align="left">
<b>08:00</b>� Inicio da reuni�o <b>� 1� andar �</b>  (120 pessoas  -  70 franquias)<p>
12:00 � Almo�o  -  fora do hotel.<p>
13:30 -  Retorno Almo�o<p>
16:00� Coffee Break � Foyer II �  (120 pessoas  -  70 franquias)<p>
21:00 � Encerramento
</td></tr>


<tr><td align="left"><b><u>15 de Mar�o de 2011,  Ter�a - Feira</u></b></td></tr>
<tr><td align="left"><b>N�mero previsto:</b> 120 pessoas</td></tr>
<tr><td align="left">
<b>08:00</b> � Inicio da reuni�o <b>� 1� andar �</b>  (120 pessoas  -  70 franquias)<p>
12:00 � Almo�o  -  fora do hotel.<p>
13:30 -  Retorno Almo�o<p>
16:00� Coffee Break � Foyer II �  (120 pessoas  -  70 franquias)<p>
21:00 � Encerramento
</td></tr>

<tr><td height="50">&nbsp;</td></tr>
</table>


<?
exit;
?>


<p>&nbsp;</p>
<table width="90%" cellspacing="1" cellpadding="0" class="bodyText" align="center" border="0">
<tr>
<td align="center" height="70">
<font color="#FF6600" size="+2"><b>TAXA ADICIONAL M�S DE DEZEMBRO<p></b></font></td>
</tr>

<tr><td align="center" height="70"><img src="../img/logo2.jpg" width="78" height="64"></td>
</tr>

<tr><td align="center" height="70"><a href="clientes/documentos/SCRIPT_PARA_TAXA_EXTRA_RESUMIDO.doc" target="_blank">
<font color="#0033FF" size="+1"><b><u>Clique aqui - 8 passos de como explicar para os clientes</u></b></font>
</td></tr>

<tr><td align="center" height="70"><a href="clientes/documentos/FORMULARIO_ISENCAOO_DE_TAXA_EXTRA.doc" target="_blank">
<font color="#0033FF" size="+1"><b><u>Clique aqui - formul�rio de isen��o caso necess�rio</u></b></font>
</td></tr>

</table>


<table border="0" width="95%" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">

<tr><td align="center"><font color="#0000FF"><b><h1>RECONHECIMENTO NACIONAL</h1></b></font></td></tr>

<tr><td align="center"><font color="#0000FF"><b><h3>(Uma vit�ria da Diretoria, funcion�rios e de TODA A REDE DE FRANQUIAS)</h3></b></font></td></tr>

<tr><td height="35"><b>Para: Web Control Empresas Servi�os de Prote��o ao Cr�dito Nacional Ltda.</b></td></tr>

<tr><td height="35"><b>A/C Diretoria Administrativa, Expans�o e Tecnologia</b></td></tr>

<tr><td height="35"><b>Prezados Senhores:</b></td></tr>

<tr><td height="35" align="justify">A Revista <b>Top of Business</b> informa � empresa <b>Web Control Empresas Servi�os de Prote��o ao Cr�dito Nacional Ltda</b>, que aguarda at� o pr�ximo dia 19 de Novembro a CONFIRMA��O para receber o <b>Trof�u Empreendedores de Sucesso</b> no pr�ximo dia 04 de Dezembro, �s 21 horas em cerim�nia social, no <b>Sheraton Rio Hotel & Resort / Rio de Janeiro</b>. Estar�o presentes, personalidades e celebridades, na ocasi�o estar� sendo entregue o Trof�u Empreendedores de Sucesso, al�m de ve�culos de comunica��o que estar�o dando cobertura � grande noite, a Revista Top of Business estar� publicando mat�ria sobre a empresa <b>Web Control Empresas Servi�os de Prote��o ao Cr�dito Nacional Ltda</b> em sua edi��o especial.</td></tr>

<tr><td height="35"></td></tr>

<tr><td height="35">
<b>Data:</b> 04 de Dezembro de 2010 - S�bado<br>
<b>Hor�rio:</b> 21 horas<br>
<b>Local:</b> Sheraton Rio Hotel & Resort - Av. Niemeyer, 121 -Leblon / Rio de Janeiro/RJ - www.sheraton-rio.com<br>
<b>Traje:</b> Social
</td></tr>

<tr><td height="35"></td></tr>

<tr><td height="35">
Desde j�, contamos com a sua presen�a.<p>

Atenciosamente
</td></tr>

<tr><td height="35">
<b>Maximiliano Correia</b><br>
<b>Fone:</b> 55 51.3028.2067 - 55 51 3013.5286<br>
<b>Celular:</b> 55 51 9292 6990<br>
<b>Nextel:</b> ID: 104593*3<br>
<b>Skype:</b> felipe.dutra.84<br>
<b>Primax Eventos International</b><br> 
</td></tr>

</table>

<?php exit; ?>
<table border="0" width="95%" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">

<tr><td align="center"><font color="#0000FF"><b><h3>SCRIPT  -  REAJUSTE ATENDIMENTO AO CLIENTE QUE POSSUI D�VIDAS </h3></b></font></td></tr>

<tr><td height="35">- Por gentileza Senhor, qual a sua d�vida ??</td></tr>

<tr><td height="50">- A sua liga��o para tratar desse assunto � importante, pois alem de comunicar ao Sr (a) pelo E-MAIL INTELIGENTE, tambem lhe informamos no campo observa��o na fatura pelo correio.</td></tr>

<tr><td height="50">- O reajuste Sr XXXXXXX � inevit�vel, todos os custos foram repassados por nossos FORNECEDORES, inclusive voc� j� deve ter percebido estes aumentos no mercado em que vivemos em todos os aspectos (telefonia, informatica, softwares, links de comunica��o, atualiza��o de informa��es, custos operacionais, e muitos outros relacionados a nossa empresa). </td></tr>

<tr><td height="100">- N�o existe empresa como a Web Control Empresas no mercado nacional que est� trabalhando 24 hs para desenvolver solu��es para sua empresa, e s� � poss�vel manter a QUALIDADE e AGILIDADE de nossas SOLU��ES E PESQUISAS se houver <font color="#548DE3"><b>INVESTIMENTOS para os AVAN�OS NECESS�RIOS</b></font> para melhorar sua empresa em todas as �reas.</td></tr>

<tr><td height="50">- O Sr (a) j� percebeu as INUMERAS SOLU��ES que nossa empresa vem criando para MELHORAR TODA A SUA EMPRESA ??</td></tr>

<tr><td height="50">N�s trabalhamos muito para que sua empresa <font color="#548DE3"><b>CRES�A E SE MANTENHA FORTE</b></font>,  mas para isso precisamos manter a qualidade de nossos servi�os.</td></tr>

<tr><td height="50">- Se o Sr (a) fosse pagar um PROGRAMADOR para desenvolver todas estas solu��es, com certeza ele te cobraria uns  R$ 3.000,00  a  R$ 4.000,00 de entrada � vista,  e mais a mensalidade que iria variar entre R$ 300,00  a  R$ 600,00 por m�s.   E gostaria que fizesse uma levantamento de PRE�O para o Sr (a) verificar se estamos lhe faltando a verdade.</td></tr>

<tr><td height="50">- O Sr (a) j� parou pra analisar o quanto voc� paga barato pra Web Control Empresas ?? para lhe fornecer tudo o que o Sr tem dispon�vel ??,  j� parou pra pensar quantas solu��es sua empresa tem a qualquer hora e momento ??</td></tr>

<tr><td height="50"><font color="#548DE3"><b>Fique Tranquilo que o Sr (a) est� pagando um pre�o BEM ABAIXO do mercado, e s�o as MELHORES SOLU��ES QUE EXISTEM NA ATUALIDADE.</b></font></td></tr>

<tr><td height="50"><hr></td></tr>

<tr><td height="50">A Web Control Empresas deseja ao Sr. um �timo dia e tamb�m um  m�s de excelentes neg�cios,  e n�o deixe de usufruir de nossas solu��es que lhe trar� muito crescimento pra sua empresa.</td></tr>

	
</table>












<?php
exit;
include("Franquias/7_encontro.php");

?>

<script language="javascript">
	window.open('https://www.webcontrolempresas.com.br/franquias/comunicado/aviso.htm', 'aviso', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width='+580+',height='+400+',left='+200+', top='+70+',screenX='+0+',screenY='+0+'');
</script>
<?php
function dataMostra($p_paramento){
 	   $dia = substr($p_paramento, 8,2);   
	   $mes = substr($p_paramento, 5,2);   
	   $ano = substr($p_paramento, 0,4);   	   
	   $hora = substr($p_paramento, 11,8);  	   
	   $data_hora_view.=$dia;
	   $data_hora_view.="/";
	   $data_hora_view.=$mes;
	   $data_hora_view.="/";
	   $data_hora_view.=$ano;	   
	   $data_hora_view.=" - ";	   
	   $data_hora_view.=$hora;	   
	   return ($data_hora_view);
} 

require_once("connect/conexao_conecta.php");

$sql = "SELECT
  c.brasil, c.adversario, c.apostador, c.data_hora_lance,
  j.situacao, f.fantasia, j.data_hora_jogo, j.adversario AS nome_adversario, j.id AS imagem
FROM
  copa c INNER JOIN
  jogo j ON c.id_jogo = j.id INNER JOIN
  franquia f ON f.id = c.id_franquia
WHERE
  j.situacao = 'A'
ORDER BY c.id";
$qry  = mysql_query($sql);
$qry_tmp  = mysql_query($sql);
$total = mysql_num_rows($qry);
$acumulado =  number_format($total * 10,2,',','.'); 
$data_hora_jogo = dataMostra(mysql_result($qry_tmp,0,'data_hora_jogo'));
$nome_adversario = mysql_result($qry_tmp,0,'nome_adversario');
$imagem = mysql_result($qry_tmp,0,'imagem');

$a_cor = array('#FFFFFF', '#F6F6F9');
$cont=1;
?>
<p>
<table border="0" width="95%" align="center" cellpadding="0" cellspacing="2">
<tr>
<td width="49%" align="center"><img src="../img/espanha.jpg" width="150px" height="70px"></td>
<td width="2%" align="center"></td>
<td width="49%" align="center"><img src="../img/holanda.jpg" width="150px" height="70px"></td>
</tr>
<tr>
<td align="center"><b>Espanha</b></td>
<td width="2%" align="center"></td>
<td align="center"><b>Holanda</b></td>
</tr>

</table>

<table border="0" width="95%" align="center" cellpadding="0" cellspacing="2">
<tr>
  <td width="1%">
  <!--script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','150','height','95','title','1 lugar','src','../img/1lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/1lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="150" height="95" title="1 lugar">
    <param name="movie" value="../img/1lugar.swf" />
    <param name="quality" value="high" />
    <embed src="../img/1lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="150" height="95"></embed>
  </object>
</noscript-->
  </td>
  <td align="center"><p>  
  <div align="left">
    &nbsp;&nbsp;&nbsp;&nbsp;<a href="ganhadores_1_bolao.php?id_jogo=1"><font color="#0000FF"><b>Ganhadores do 1� Bol�o ( Brasil X Costa do Marfim )</b></font></a><p>

  &nbsp;&nbsp;&nbsp;&nbsp;<a href="ganhadores_1_bolao.php?id_jogo=2"><font color="#0000FF"><b>Ganhadores do 2� Bol�o ( Brasil X Portugal )</b></font></a><p>

  &nbsp;&nbsp;&nbsp;&nbsp;<a href="ganhadores_1_bolao.php?id_jogo=3"><font color="#0000FF"><b>Ganhadores do 3� Bol�o ( Brasil X Chile )</b></font></a><p>
    &nbsp;&nbsp;&nbsp;&nbsp;<a href="ganhadores_1_bolao.php?id_jogo=4"><font color="#0000FF"><b>Ganhadores do 4� Bol�o ( Brasil X Holanda )</b></font></a><p>
    &nbsp;&nbsp;&nbsp;&nbsp;<a href="ganhadores_1_bolao.php?id_jogo=5"><font color="#0000FF"><b>Ganhadores do 5� Bol�o ( Espanha X Holanda )</b></font></a>

    <!-- < a href="painel.php?pagina1=clientes/a_download_tabelas.php"><font color="#0000FF"><u><b>TABELA SUPER-PROMO��O COPA DO MUNDO 2010 <br>(Documentos Diversos - Departamento Comercial - TABELA DE PRE�OS)</u><br>
    <font color="#009900"><u>Agora � a hora de TRIPLICAR a sua carteira de clientes</u></font>
  
</font>

--></td>
  <td width="1%"><!--script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','150','height','95','title','1 lugar','src','../img/1lugar','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../img/1lugar' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="150" height="95" title="1 lugar">
    <param name="movie" value="../img/1lugar.swf" />
    <param name="quality" value="high" />
    <embed src="../img/1lugar.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="150" height="95"></embed>
  </object>
</noscript -->

</td>

</tr>


</table>

<p>
<div align="center"><b>Jogo dia <?=$data_hora_jogo?></b></div>
<table border="0" width="95%" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">

<tr bgcolor="#FFCC99">
  <td width="7%" align="center"><img src="../img/espanha.jpg" width="25px"></td>
  <td width="2%" align="center">&nbsp;</td>
  <td width="7%" align="center"><img src="../img/holanda.jpg" width="25px"></td>
  <td width="44%" align="left"><b>Nome da Franquia</b></td>
  <td width="20%" align="left"><b>Apostador</b></td>
  <td width="20%" align="center"><b>Data do Lance</b></td>
</tr>

<?php 
while($rs = mysql_fetch_array($qry)){
$cont++;
if($rs['adversario'] > $rs['brasil']){
	//$traira = " :-( Tra�ra )-:";
	$traira = "";

}else{
	$traira = "";
?>
<tr bgcolor="<?=$a_cor[$cont % 2]?>">
<?php
}
?>
  <td align="center"><?=$rs['brasil']?></td>
  <td align="center"><b>X</b></td>
  <td align="center"><?=$rs['adversario']?></td>
  <td align="left"><?=$rs['fantasia']?></td>
  <td align="left"><?=strtoupper($rs['apostador'])?>&nbsp;<?=$traira?></td>
  <td align="center"><?=dataMostra($rs['data_hora_lance'])?></td>
</tr>
<?php } ?>
</table>

<table border="0" width="95%" align="center" cellpadding="0" cellspacing="1">

<tr><td colspan="6" align="center"><font style="font-size:18px" color="#FF0000"><b>Total ACUMULADO R$ <?=$acumulado?></b></font></td></tr>
</table>

<table border="0" width="95%" align="center" cellpadding="0" cellspacing="1" style="border: 0px solid #D1D7DC; background-color:#FFFFFF">
<tr><td align="center" height="30"><a href="painel.php?pagina1=Franquias/bolao_copa_do_mundo.php"><b><font style="font-size:18px" color="#0000FF"><u>Clique aqui e d� o seu Palpite</u></font></b></a></td></tr>
</table>
<p>
<?php
exit;

?>
<table border="0" width="95%" align="center" cellpadding="0" cellspacing="2" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
<tr><td>
<p><b>Aos</b><br>
Respons�veis pela Franquia,</td></tr>

<tr><td align="center" height="50"><b><u>AUMENTE AS VENDAS COM A COPA DO MUNDO</u></b></td></tr>

<tr><td align="center" height="50"><b><font color="#009900"><u>CARTELINHAS DA COPA DO MUNDO AFRICA DO SUL 2010</u></font></b></td></tr>

<tr><td height="50">Prezados Senhores,</td></tr>

<tr><td height="50">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A <b>Web Control Empresas Servi�os de Prote��o ao Cr�dito Nacional</b>, envia a voc� caro Franqueado  1.000 CARTELINHAS DA COPA DO MUNDO  -  AFRICA DO SUL 2010  !!!</td></tr>

<tr><td height="50">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;O objetivo �  ENTREGAR  o mais r�pido possivel, pois a COPA  come�a  j�  na PROXIMA SEMANA  e  muitas pessoas gostam de ACOMPANHAR OS JOGOS (empres�rios,  funcionarios, e outros)</td></tr>

<tr><td height="50">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Abaixo segue alguns locais onde ter�o  SUCESSO NA ENTREGA DE FORMA R�PIDA e cair� na m�o de PESSOAS COMO EMPRES�RIOS E  FUNCIONARIOS DE EMPRESAS:</td></tr>

<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF9900">- RECEP��O DOS EDIFICIOS COMERCIAIS</td></tr>
<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF9900">- RECEP��O DE ESTACIONAMENTOS</td></tr>
<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF9900">- BALC�ES DE SUPERMERCADOS</td></tr>
<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF9900">- BALC�ES DE FARMACIAS</td></tr>
<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF9900">- e distribuir para TODOS OS SEUS VENDEDORES lhe ajudar a entregar.</td></tr>

<tr><td height="50">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N�O ESQUECER DE BATER O CARIMBO DE SUA FRANQUIA  com seus n�meros e celulares dos vendedores.</td></tr>

<tr><td height="50">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Aproveitem,  QUANTO MAIS PESSOAS colocar a m�o nas cartelinhas,  MAIS EMPRESAS v�o ligar para sua franquia pedindo AFILIA��O.</td></tr>

<tr><td height="100" align="center"><font color="#FF9900"><b>DEUS ABEN�OA QUEM TRABALHA  !!!   O pregui�oso s� perece.</b></font></td></tr>

<tr><td>
Wellington Fernandes<br>
Ananias Teixeira<br>
Web Control Empresas - Pesquisas e Solu��es Inteligentes<br>
Atual - Moderna - Completa<br>
</td></tr>

</table>


<?php exit; ?>
<table width=90% border="0" align="center">


<tr>
  <td height="30" align="center">
    <a href="painel.php?pagina1=clientes/a_download_tabelas.php"><font color="#0000FF"><u><b>AS NOVAS TABELAS DE PRE�OS PERSONALIZADAS DA SUA FRANQUIA EST&Atilde;O DISPON&Iacute;VEIS<br>
(DOCUMENTO DIVERSOS - TABELA DE PRE�OS)</b>
</u></font></a>
</td></tr>


<tr><td height="30" align="center">
    <a href="clientes/documentos/script_atendimento_software_solucoes.pdf" target="_blank"><font color="#FF0000"><u>SCRIPT PARA RECLAMA��O DE CLIENTES SOBRE LICEN�A - SOFTWARE DE SOLU��ES</u></font></a>
</td></tr>

</table>

<style>
.borda{
	border-collapse: collapse;
	font-size: 12px;
	font-family:Verdana, Arial, sans-serif;
}
</style>

<table class="borda" border="0" width="80%" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF"> 
<tr>
  <td colspan="2" align="center">&nbsp;</td>
</tr>

<tr>
  <td colspan="2" align="center"><b><font color="#0033FF" size="+1">Por que cobrar as Licen�as de Softwares e Solu��es Exclusivas ???</font></b></td>
  </tr>
  
<tr>
  <td colspan="2" align="center">
  	<table border="1" align="center" width="100%" cellpadding="0" cellspacing="5"><tr><td bgcolor="#FFCC66" align="center">
    A Web Control Empresas � um neg�cio, e como tal visa o LUCRO, qualquer coisa fora<br>
    do lucro n�o faz parte da empresa, do neg�cio, dos CLIENTES,  dos <br>
    fornecedores, da franquia e da franqueadora, e todos os envolvidos.
    </td></tr></table>
    </td>
 </tr>

<tr>
  <td colspan="2" align="center">&nbsp;</td>
</tr>

<tr>
  <td colspan="2" align="center"><font color="#FF0000"><b>SCRIPT PARA ATENDIMENTO DE CLIENTES</b></font></td>
  </tr>

<tr>
  <td colspan="2" align="center">
    COMO ATENDER AQUELE CLIENTE QUE LIGAR RECLAMANDO DA<br> 
    COBRAN�A DA <font color="#0033FF"><b>"LICEN�AS � SOFTWARES DE SOLU��ES"</b></font> ???</td>
  </tr>

<tr>
  <td colspan="2"><hr color="#0033FF" width="80%"></td>
  </tr>

<tr>
  <td width="5%">&nbsp;</td>
  <td width="95%"><b>a)</b>  Bom dia Sr. (a) xxxxxxxxx,  que bom que o Senhor(a) nos ligou, precis�vamos mesmo falar com o Senhor(a).<p>
</td></tr>

<tr>
  <td>&nbsp;</td>
  <td><b>b)  Sr.(a) xxxxxxxx,  em 1� lugar vou lhe explicar o motivo da cobran�a das LICEN�AS -  SOFTWARES DE SOLU��ES:</b><p></td></tr>

<tr>
  <td></td>
  <td align="justify" bgcolor="#E8E8E8"><b><font color="#0033FF">1�</font></b> - Todos os nossos Softwares s�o LICENCIADOS, e essas licen�as  geram uma manuten��o mensal, em virtude disso � que est�o sendo cobrados. A Software House, empresa desenvolvedora das solu��es, iniciou a cobran�a a partir de abril de 2010 e,  para que n�o fosse cobrado por m�dulos, a Web Control Empresas fechou um acordo para que fosse cobrado um valor �nico por todos os SOFTWARES DE SOLU��ES, desta forma conseguimos fechar por um valor simb�lico de R$ 2,15 por m�dulo de SOLU��O, totalizando os R$ 12,90.<p></td>
</tr>

<tr>
  <td></td>
  <td align="justify" bgcolor="#F5F5F5"><b><font color="#0033FF">2�</font></b> - Todos os nossos SOFTWARES DE SOLU��ES s�o homologados pela Secretaria da Receita Federal, inclusive estamos desenvolvendo uma GRANDE SOLU��O para todos os empres�rios do Brasil, estamos homologando os Cart�es de D�bito e Cr�dito, e assim que a Receita Federal nos conceda essa homologa��o o Sr.(a) poder� fazer vendas COM NOSSAS SOLU��ES nos CART�ES DE CR�DITO E D�BITO sem precisar contratar as maquininhas de cart�es. O Sr.(a) vai economizar de R$ 80,00 a R$ 240,00 por m�s com as SOLU��ES da Web Control Empresas, pois o Sr.(a) n�o precisar� das maquininhas de cart�o.<p></td>
</tr>

<tr>
  <td></td>
  <td align="justify" bgcolor="#E8E8E8"><b><font color="#0033FF">3�</font></b> - Outra quest�o a ser observada � que continuamos sendo a MAIOR EMPRESA NACIONAL DE SOLU��ES para a Micro, Pequena e M�dia empresa, para que sua empresa cres&ccedil;a e se fortale�a com o MENOR PRE&Ccedil;O DO MERCADO NACIONAL.
    <p></td>
</tr>

<tr>
  <td></td>
  <td align="justify" bgcolor="#F5F5F5"><b><font color="#0033FF">4�</font> - Sr.(a) xxxxxx, aproveitando a sua liga��o, eu gostaria de LEMBRAR ao Senhor(a) que nossas PESQUISAS e SOLU��ES s�o as Melhores do mercado nacional, e est�o a disposi��o da sua empresa. S�o elas:</b><p></td>
</tr>

<tr>
  <td></td>
  <td bgcolor="#F5F5F5"><b>a) <font color="#0033FF">Localiza Max</font></b> � Localiza��o M�xima de pessoas.</td>
</tr>

<tr>
  <td></td>
  <td bgcolor="#F5F5F5"><b>b) <font color="#0033FF">Web Control Empresas </font></b>� O Sr.(a) controla toda sua empresa, desde o estoque at� sua carteira de clientes e muito mais.</td>
</tr>

<tr>
  <td></td>
  <td bgcolor="#F5F5F5"><b>c) <font color="#0033FF">Encaminhamento � Cart�rio de protesto</font></b> � O devedor fica com seu nome sujo em todos os sistemas de informa��es e �rg�os p�blicos que existem no Pa�s.</td>
</tr>

<tr>
  <td></td>
  <td bgcolor="#F5F5F5"><b>d) <font color="#0033FF">Recomende o Cliente</font></b> � � a �nica maneira de outros empres�rios conhecerem o relacionamento do consumidor com a sua empresa.</td>
</tr>

<tr>
  <td></td>
  <td bgcolor="#F5F5F5"><b>e) <font color="#0033FF">Credi�rio System</font></b> � Aumento das suas vendas tr�s vezes mais, vendendo no Boleto.</td>
</tr>

<tr>
  <td></td>
  <td bgcolor="#F5F5F5"><b>f) <font color="#0033FF">Recupere System</font></b> � Parcelamento da d�vida para seus devedores, e n�s fornecemos o envelope vermelho (Notifica��o de Cobran�a).</td>
</tr>

<tr>
  <td></td>
  <td><p>Prezado Sr.(a) xxxxxxxxxx, obrigado pela sua liga��o e, sempre que o Sr.(a) precisar estaremos a disposi��o para ajudar sua empresa a crescer e se fortalecer no mercado.</td>
</tr>


<tr>
  <td></td>
   <td>Sr.(a) xxxxxxxxx, tenha um excelente  dia e bons neg�cios.</td>
</tr>

</table>

<?php exit; ?>
<p>
<b>Prezados Franqueados:</b><p>

Comunicamos a toda rede de Franquias que o <font color="#FF0000"><b>Sr. Dimi</b></font> n�o faz mais parte do nosso quadro de funcion�rios.<br>
Informamos que quem assume o setor � <b><font color="#0033FF">SELMA PEREIRA DA SILVA</font></b>.<br> 
Tamb�m informamos que a Srta. Selma foi corretamente treinada pela pr�pria Diretoria para assumir os procedimentos administrativos junto as Franquias.<p>
Quaisquer solicita��es dever�o ser encaminhadas para:<p>
<font color="#0033FF">
<b>e-mail:</b> franquias@webcontrolempresas.com.br<br>
<b>msn:</b>    franquias@webcontrolempresas.com.br
</font>



<?php exit; ?>
Em reuni�o com o Corpo Jur�dico da Web Control Empresas <font color="#FF9900"><b>(Dr Carlos Roberto Ferreira Munhoz Costa,  Dr Paulo Roberto Munhoz Costa Filho,  e Dr Gil Duarte Silva)</b></font>  ficou decidido o que se segue abaixo.	Todos os procedimentos seguintes dar�o a Web Control Empresas Servi�os de Prote��o ao Cr�dito Nacional e toda a sua rede de franqueados  mais autoridade junto ao Judici�rio Brasileiro.<p>


<b><font color="#0000FF">PEDIDO DO CLIENTE ATRAV�S DE CARTA DE CANCELAMENTO</font></b><p>

<div align="justify">A Franquia dever� enviar para Franqueadora pelo correio o formul&aacute;rio de cancelamento anexado com  a CARTA DE CANCELAMENTO ORIGINAL assinada pelo propriet�rio ou procurador COM FIRMA RECONHECIDA EM CARTORIO dentro do intervalo de 30 dias (antes) da data de renova��o autom�tica.   N�o ser�o aceitas cartas fora do prazo contratual. (n�o ser�o aceitos: fax, e-mails e cartas pr�-impressas).  As cartas de cancelamentos enviadas fora do prazo contratual ser�o devolvidas para a Franquia.  Cumprindo na integra  a 14� clausula contratual.</div>
<p>

<b><font color="#0000FF">PEDIDO DE CANCELAMENTO PELA  FRANQUIA POR INADIMPLENCIA DO CLIENTE</font></b><p>

Dever� ser observado os seguintes procedimentos:<p>
<div align="justify">
	<p><b>a)</b> O formul&aacute;rio de cancelamento dever&aacute; ser enviado para a Matriz com o protocolo de negativa&ccedil;&atilde;o no nosso banco de dados. <br />
    <b>b)</b> A documenta��o do cliente dever� estar 100% regularizada (contrato, termo de responsabilidade, tabela, declara��o de treinamento).<br>  
	    <b>c)</b> O cliente dever� estar inadimplente com no m�nimo 30 dias de atraso com suas mensalidades.<br>
	    <b>d)</b> Clientes que tiveram renegocia��o de tabela de revers�o de cancelamento ter�o sua solicita��o de cancelamento (em caso de inadimpl�ncia)  atendidas somente ap�s 180 (cento e oitenta) dias ap�s a data de altera��o.<br> 
	    <b>e)</b> Dever� o franqueado NEGATIVAR A EMPRESA INADIMPLENTE em nosso Banco de Dados em conjunto  com a Equifax do Brasil, observando que o BLOQUEIO s� poder� ser efetuado pelo FRANQUEADO PESSOA JURIDICA.  Caso a Franquia ainda n�o tem CNPJ  n�o poder� ser cancelado nenhum contrato por INADIMPLENCIA.<br>
	  O protocolo de comprova&ccedil;&atilde;o do BLOQUEIO dever� estar em ANEXO a solicita��o de cancelamento por inadimpl�ncia. <br>
	A Franquia dever&aacute; enviar a solicita&ccedil;&atilde;o pelo correio.
</div><p>

<b><font color="#0000FF">PEDIDO DE CANCELAMENTO PELA FRANQUIA POR IRREGULARIDADES DIVERSAS</font></b><br>
<b><font color="#0000FF">(Falta de confer�ncia, fraude contratual, falta de documenta��es, e outros)</font></b>

<div align="justify">
  <p>Para cancelamentos de contratos enquadrados nesta categoria,  s� ser�o realizados com a emiss�o da FATURA �NICA referente ao res�duo contratual dos meses restantes do contrato. <br>
 A Franquia dever&aacute; enviar o formul&aacute;rio pelo correio.</p>
</div>
<p>

<b><font color="#0000FF">PEDIDO DE CANCELAMENTO POR MOTIVO FAL&Ecirc;NCIA</font></b>
<p>Caso a Empresa comprove a sua fal&ecirc;ncia ser&aacute; solicitado a comprova&ccedil;&atilde;o de baixa na Receita Federal anexada ao pedido de cancelamento pelo franqueado.
<div align="justify">	
  
</div><p>


Na certeza da observa��o de todos,<p>


Wellington Fernandes<br>
Depto Administrativo<br>
Web Control Empresas Servi�os de Prote��o ao Cr�dito Nacional Ltda<br>
Pesquisas e Solu��es Inteligentes

    
    </td>
  </tr>
</table>


<?php exit;?>
<table width=90% border="0" align="center">
<tr><td height="30">
	<a href="clientes/documentos/TERMO_DE_NEGATIVACAO_EQUIFAX_COMUNICADO_03_02_2010.pdf" target="_blank"><font color="#FF0000"><u>Comunicado de Termo de Negativa��o Equifax (PDF - 52 KB)</u></font></a><br>
</td></tr>

<tr><td height="30">
    <a href="clientes/documentos/MULTA_POR_FRAUDE_PREMIACAO_SEMANAL_03_02_2010.pdf" target="_blank"><font color="#FF0000"><u>Multa por Fraude na Premia��o Semanal (PDF - 50 KB)</u></font></a>
</td></tr>

<tr><td>&nbsp;</td></tr>
  <tr>
    <td align="justify">    
    <div align="center"><font color="#FF0000">
    <b>RECICLAGEM DE FRANQUIAS</b></div><p>
    
<b>Prezados Franqueados:</b>
    <p>
	Conforme comunicado sobre a META MIN&Iacute;MA DE 15 CONTRATOS, os franqueados que por ventura n&atilde;o conseguiram consolidar, dever&atilde;o comparecer para reciclagem na Franqueadora nas datas 01, 02, 03 e 04 de Mar&ccedil;o de 2010 das 09:00 Hs &agrave;s 22:00 Hs.    
    <p>O comparecimento &eacute; obrigat&oacute;rio para o excelente desempenho dos trabalhos na franquia que est&aacute; em dificuldade.    
    <p>O franqueado que n&atilde;o consegue cumprir a meta m&iacute;nima prova que tem defici&ecirc;ncias e que precisam ser REPARADAS E RESTAURADAS URGENTEMENTE.    
    <p>A franquia enquadrada no assunto em pauta dever&aacute; observar est&aacute; chamada, sob pena de n&atilde;o cumprir regulamentos contratuais com a Franqueadora. Caso a n&atilde;o observa&ccedil;&atilde;o a franquia ser&aacute; impedida de continuar suas atividades normais por falta de comparecimento ao treinamento obrigat&oacute;rio.
    <p>Agradecemos a aten&ccedil;&atilde;o de Todos.
    <p>
    Ananias Nascimento Teixeira<br />
Diretor Comercial<br />
Web Control Empresas Servi&ccedil;os de Prote&ccedil;&atilde;o ao Cr&eacute;dito Nacional Ltda<br />
Pesquisas e Solu&ccedil;&otilde;es Inteligentes</p>
    <p></td></tr>
</table>


<!--table width=90% border="0" align="center">
<tr><td height="50"><p><p><b>Prezados Franqueados:</b></p>
  <p></td></tr>
<tr>
  <td height="30">
    <p>Por determina&ccedil;&atilde;o da DIRETORIA <u>n&atilde;o ser&aacute; mais permitido</u> &agrave; partir desta data o FECHAMENTO DE CONTRATOS com a categoria <b><font color="#FF0000"><u>ADVOGADOS E ASSESS&Oacute;RIA J&Uacute;RIDICA.</u></font></b></p>
    <p>Em rela&ccedil;&atilde;o aos antigos afiliados,  esse segmento poder&aacute; rescindir o contrato com a Web Control Empresas a qualquer momento sem o pagamento de multa contratual.</p>
    <p>Wellington Fernandes<br />
Ananias Nascimento Teixeira<br />
Depto Administrativo/Comercial<br />
Web Control Empresas Servi&ccedil;os de Prote&ccedil;&atilde;o ao Cr&eacute;dito Nacional Ltda<br />
Pesquisas e Solu&ccedil;&otilde;es Inteligentes</p></td>
</tr>

</table -->
<?php exit; ?>


<hr color="#FFCC00" width="95%">
<p align="center">

<img src="../../images/web_control.jpg">

<p align="center"><font color="#0033FF"><b>WEB CONTROL EMPRESAS - NOVIDADES - NOVIDADES - WEB CONTROL EMPRESAS</b></font><p>

Prezados Franqueados:<p>

� com muito prazer que ANUNCIAMOS PARA TODAS AS FRANQUIAS DO BRASIL que o servi�o WEB CONTROL EMPRESAS ser� fornecido SEM CUSTO ADICIONAL por tempo indeterminado aos nossos Associados (ANTIGOS E NOVOS).<p>

Esta medida IMEDIATA � a melhor estrategia no momento para tomarmos o MERCADO, e consequentemente sairmos VENCEDORES.<p>

A Logica � simples: O WEB CONTROL VAZIO N�O TEM VALOR MONET�RIO ....... J� O WEB CONTROL CHEIO DE INFORMA��ES DOS EMPRES�RIOS, o valor � IMENSUR�VEL !!!<p>

Diante disso ADOTAMOS A MEDIDA NACIONAL DE N�O COBRARMOS NADA DE MENSALIDADE e a TAXA DE ADES�O E SOLU��ES permanecem as mesmas SEM COBRAR OS R$ 80,00 !!!!<p>

Agradecemos a aten��o de todos,<p>


Wellington Fernandes<br>
Ananias Nascimento Teixeira<br>
Depto Administrativo/Comercial<br>
Web Control Empresas Servi�os de Prote��o ao Cr�dito Nacional Ltda<br>
Pesquisas e Solu��es Inteligentes<br>

<!-- 
<table width="90%" cellspacing="1" cellpadding="0" class="bodyText" align="center" border="0">
<tr>
<td align="center" height="70">
<font color="#FF6600" size="+2"><b>COMO EXPLICAR PARA O CLIENTE SOBRE A<p> <b>TAXA ADICIONAL DE DEZEMBRO</b></p></b></font></font></td>
</tr>

<tr><td align="center" height="70"><img src="../img/logo2.jpg" width="78" height="64"></td>
</tr>

<tr><td align="center" height="70"><a href="clientes/documentos/SCRIPT_ PARA_TAXA_ EXTRA.doc" target="_blank">
<font color="#0033FF" size="+1"><b><u>Clique Aqui</u></b></font></a>
</td></tr>
</table>
-->

<?php
exit;
?>
<p>&nbsp;</p>
<table width="90%" cellspacing="1" cellpadding="0" class="bodyText" align="center" border="0">
<tr>
<td align="center" height="70">
<font color="#FF6600" size="+2"><b>COMO REVERTER UM CANCELAMENTO<p> E DEIXAR O CLIENTE FELIZ</b></font></td>
</tr>

<tr><td align="center" height="70"><img src="../img/logo2.jpg" width="78" height="64"></td>
</tr>

<tr><td align="center" height="70"><a href="clientes/documentos/COMO_REVERTER_CANCELAMENTO.pdf" target="_blank">
<font color="#0033FF" size="+1"><b><u>Clique Aqui e Saiba como</u></b></font>
</td></tr>

</table>

<?php
exit;
?>
<p>&nbsp;</p>
<table width="90%" cellspacing="1" cellpadding="0" class="bodyText" align="left">
  <tr class="menu">
    <td class="titulo">
	<div align="justify">
Prezados Franqueados:<p>

<font color="#0033FF">1 - TERMO DE RESPONSABILIDADE E DECLARA��O DE TREINAMENTO</font>
<p>

O TERMO DE RESPONSABILIDADE e a DECLARA��O DE TREINAMENTO que vai anexada no contrato MUDOU e j� est� disponibizada na P&Aacute;GINA DE FRANQUIAS - DOCUMENTOS DIVERSOS - DEPTO COMERCIAL.<br>

Solicitamos que IMPRIMEM e atualizem URGENTE com seus CONSULTORES/VENDEDORES.<br>

So aceitaremos os antigos at� dia 05/12/2009.<p>&nbsp;</p>

<font color="#0033FF">2 - ROTEIRO DE CONFER&Ecirc;NCIA</font>
<p>

Tamb&eacute;m ALTERAMOS o ROTEIRO DE CONFER&Ecirc;NCIA CONTRATUAL visando assim uma MELHOR CONFIRMA��O dando enfase as nossas solu�&otilde;es, deixando o empres�rio realmente conhecedor de nossas PESQUISAS E SOLU��ES. ATEN��O: QUEM CONFERE O CONTRATO N�O TEM DOR DE CABE�A FUTURA .... isso � ESSENCIAL para a SAUDE FINANCEIRA DO CAIXA DA FRANQUIA.<p>&nbsp;</p>

<font color="#0033FF">3 - V�RIOS SCRIPTS DE ATENDIMENTOS para seus funcionarios e para o proprio Franqueado.</font>
<p>

Foi disponibilizado nos DOCUMENTOS DIVERSOS - DEPTO ADMINISTRATIVO v�rios SCRIPTS para melhorar o ATENDIMENTO de seus funcion&aacute;rios, s�o eles abaixo:<p>

<font color="#FF6600"><b>
* SCRIPT DE ATENDIMENTO PARA BLINDAGEM DE CLIENTES.<br>

* SCRIPT DE EXPLICA��O DA TAXA ADICIONAL DE DEZEMBRO<br>

* SCRIPT DE EXPLICA��O DO REAJUSTE DE MENSALIDADE.<br>

* SCRIPT PARA COBRAN�A DE INADIMPLENTES<br>

* SCRIPT PARA REVERS�O DE CANCELAMENTOS DE V�RIOS MOTIVOS. 
</b></font>
<br>
</div>

<div align="left">
<p>Um abra�o a todos.
<p>

Wellington Fernandes<br>
Diretor Adminstrativo<br>
Web Control Empresas Servi�os de Prote��o ao Cr�dito Nacional<br>
Pesquisas e Solu��es Inteligentes<br>
</div>
</td>
  </tr>
</table>
