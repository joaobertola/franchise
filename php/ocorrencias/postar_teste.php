<script type="text/javascript">
function voltar(){
	frm = document.form;
	frm.action = 'principal.php';
	frm.submit();
}

var yyns4=window.Event?true:false; var yy_mt = 0; var yy_ml = 0;
if (yyns4) document.captureEvents(Event.MOUSEMOVE);
document.onmousemove = YY_Mousetrace;
yy_tracescript = '';

function YY_Mousetrace(evnt) {

   browser = navigator.appName;
   if (browser=="Microsoft Internet Explorer") {
       if (yyns4){
           if (evnt.pageX){
               yy_ml=evnt.pageX;  yy_mt=evnt.pageY;
           }
       } else {
           yy_ml=(event.clientX + document.body.scrollLeft);
           yy_mt=(event.clientY + document.body.scrollTop);
       }
       if (yy_tracescript) {
           eval(yy_tracescript)
		}
	}
}

function YY_Layerfx(yyleft,yytop,yyfnx,yyfny,yydiv,yybilder,yyloop,yyto,yycnt,yystep) {
   browser = navigator.appName;
   if (browser=="Microsoft Internet Explorer") {

     if ((document.layers) || (document.all)) {
       eval("myfunc=yyfnx.replace(/x/gi, yycnt)");
       with (Math) {
           yynextx= eval(myfunc)
       }
       eval("myfunc=yyfny.replace(/x/gi, yycnt)");
       with (Math) {
           yynexty= eval(myfunc)
       }
       yycnt=(yyloop && yycnt>=yystep*yybilder)?0:yycnt+yystep;
       if (document.layers){
           eval(yydiv+".top="+(yynexty+yytop))
           eval(yydiv+".left="+(yynextx+yyleft))
       }
       if (document.all){
           eval("yydiv=yydiv.replace(/.layers/gi, '.all')");
           eval(yydiv+".style.pixelTop="+(yynexty+yytop));
           eval(yydiv+".style.pixelLeft="+(yynextx+yyleft));
       }
       argStr='YY_Layerfx('+yyleft+','+yytop+',"'+yyfnx+'","'+yyfny+'","'+yydiv+'",'+yybilder+','+yyloop+','+yyto+','+yycnt+','+yystep+')';
       if (yycnt<=yystep*yybilder){
           eval(yydiv+".yyto=setTimeout(argStr,yyto)");
       }
   }
     }
}

function ValidaFormConsultaMais(Form) {
	DivConsulta.style.display = "block"; 
	this.document.form.envia.disabled = true;
	YY_Mousetrace('',',document.YY_Mousetrace1');
	YY_Layerfx(8,18,'yy_ml','yy_mt','document.layers[\'DivConsulta\']',50000,false,0,0,10);
	frm = document.form;
	frm.action = 'galeria_bd.php';
	frm.submit();
}

</script>

<body topmargin="0" class="fundo">

<div id="DivConsulta" style="display:none; position:absolute; width:100px; height:100px; z-index:21; left:250px; top:291px"> 
<table align="center" width="500px" height="50px" border="0" cellspacing="1" cellpadding="0" bgcolor="#FF6A6A">
<tr> 
<td width="10%" align="center"><img src="../img/ajax-loader.gif" height="50px"></td>
<td width="90%" align="center">
<font style="font-size:18px">Por favor aguarde enviando foto(s).</font></td>
</td>
</tr>
</table>
</div>

<form method="post" action="#" name="form" enctype='multipart/form-data'>
<input type="hidden" name="fot_id" value="<?=$fot_id?>"/>
<input type="hidden" name="fot_origem" value="<?=$fot_origem?>"/>
<input type="hidden" name="acao" value="7">

<table border="0" width="77%" align="center" cellpadding="2" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
    <tr align="center" class="topo"><td colspan="2">Alteração de Logomarca</td></tr>
    <tr>
        <td width="40%" class="frm_input_1">Informe a Imagem para Alterar a Logomarca</td>
        <td width="60%" class="frm_input_2"><input type="file" name="foto[]" class="boxnormal" onBlur="this.className='boxnormal'" onFocus="this.className='boxover'" style="width:50%" /></td>
    </tr>    
</table>

<table border="0" width="95%" align="center" cellpadding="0" cellspacing="1">
<tr height="50">
    <td align="center">
   <input type="button"  name="envia" id="envia" value="Salvar" onClick="ValidaFormConsultaMais(document.form);" class="botao">
    &nbsp;&nbsp;&nbsp;
   <input type="button" name="Voltar" value="Voltar" onClick="voltar()" class="botao">   
    </td>
</tr>
</table>

</form>
</body>
<?php
exit;

require "connect/sessao.php";
include "ocorrencias/config.php";
$codloja = $_POST['codloja'];
$comando = "select razaosoc from cadastro where codloja='$codloja'";
$conex = mysql_query($comando, $con);;
$matriz = mysql_fetch_array($conex);
$hoje = date('d/m/Y H:i');
?>
<script language="javascript">
<!-- 
/*function textCounter(field, countfield, maxlimit) {
if (field.value.length > maxlimit)
field.value = field.value.substring(0, maxlimit);
else 
countfield.value = maxlimit - field.value.length;
}*/
// -->


function valida(){
	frm = document.postar;
	if (frm.atendente2.value == ""){
		alert("O campo Atendente deve ser preenchido !");
		frm.atendente2.focus();
		return false;
	}
	/*if (frm.ocorrencia.value == ""){
		alert("O campo Ocorrência deve ser preenchido !");
		frm.ocorrencia.focus();
		return false;
	}*/
	frm.action = 'ocorrencias/inserir.php';
	frm.submit();
}

function novo(){
 	frm = document.postar;
    frm.action = 'painel.php?pagina1=Franquias/b_cadatendente.php';
	frm.submit();
} 

var yyns4=window.Event?true:false; var yy_mt = 0; var yy_ml = 0;
if (yyns4) document.captureEvents(Event.MOUSEMOVE);
document.onmousemove = YY_Mousetrace;
yy_tracescript = '';

function YY_Mousetrace(evnt) {

   browser = navigator.appName;
   if (browser=="Microsoft Internet Explorer") {
       if (yyns4){
           if (evnt.pageX){
               yy_ml=evnt.pageX;  yy_mt=evnt.pageY;
           }
       } else {
           yy_ml=(event.clientX + document.body.scrollLeft);
           yy_mt=(event.clientY + document.body.scrollTop);
       }
       if (yy_tracescript) {
           eval(yy_tracescript)
		}
	}
}

function YY_Layerfx(yyleft,yytop,yyfnx,yyfny,yydiv,yybilder,yyloop,yyto,yycnt,yystep) {
   browser = navigator.appName;
   if (browser=="Microsoft Internet Explorer") {

     if ((document.layers) || (document.all)) {
       eval("myfunc=yyfnx.replace(/x/gi, yycnt)");
       with (Math) {
           yynextx= eval(myfunc)
       }
       eval("myfunc=yyfny.replace(/x/gi, yycnt)");
       with (Math) {
           yynexty= eval(myfunc)
       }
       yycnt=(yyloop && yycnt>=yystep*yybilder)?0:yycnt+yystep;
       if (document.layers){
           eval(yydiv+".top="+(yynexty+yytop))
           eval(yydiv+".left="+(yynextx+yyleft))
       }
       if (document.all){
           eval("yydiv=yydiv.replace(/.layers/gi, '.all')");
           eval(yydiv+".style.pixelTop="+(yynexty+yytop));
           eval(yydiv+".style.pixelLeft="+(yynextx+yyleft));
       }
       argStr='YY_Layerfx('+yyleft+','+yytop+',"'+yyfnx+'","'+yyfny+'","'+yydiv+'",'+yybilder+','+yyloop+','+yyto+','+yycnt+','+yystep+')';
       if (yycnt<=yystep*yybilder){
           eval(yydiv+".yyto=setTimeout(argStr,yyto)");
       }
   }
     }
}

function ValidaFormConsultaMais(Form) {
	DivConsulta.style.display = "block"; 
	this.document.postar.envia.disabled = true;
	YY_Mousetrace('',',document.YY_Mousetrace1');
	YY_Layerfx(8,18,'yy_ml','yy_mt','document.layers[\'DivConsulta\']',50000,false,0,0,10);
	
	frm = document.postar;
	if (frm.atendente2.value == ""){
		alert("O campo Atendente deve ser preenchido !");
		frm.atendente2.focus();
		return false;
	}
	/*if (frm.ocorrencia.value == ""){
		alert("O campo Ocorrência deve ser preenchido !");
		frm.ocorrencia.focus();
		return false;
	}*/
	frm.action = 'ocorrencias/inserir.php';
	frm.submit();
}

</script>

<body>
<div id="DivConsulta" style="display:none; position:absolute; width:100px; height:100px; z-index:21; left:250px; top:291px"> 
<table align="center" width="500px" height="50px" border="0" cellspacing="1" cellpadding="0" bgcolor="#FF6A6A">
<tr> 
<td width="10%" align="center"><img src="../img/ajax-loader.gif" height="50px"></td>
<td width="90%" align="center">
<font style="font-size:18px">Por favor enviando a ocorrência.</font></td>
</td>
</tr>
</table>
</div>

<form name="postar" method="post" action="#">
<table width="90%" border="0" align="center">
<tr>
  <td colspan="2" class="titulo">REGISTRAR UM ATENDIMENTO</td>
</tr>
<tr>
  <td class="campoesquerda">&nbsp;</td>
  <td class="campoesquerda">(*) preenchimento obrigat&oacute;rio</td>
  </tr>
<tr>
  <td class="subtitulodireita">ID do cliente:</td>
  <td class="subtitulopequeno"><?php echo $codloja; ?><input name="codigo" type="hidden" value="<?php echo $codloja; ?>" ></td>
</tr>
<tr>
  <td class="subtitulodireita">Nome do Cliente:</td>
  <td class="subtitulopequeno">
  	<?php echo $matriz['razaosoc']; ?>
    <input name="codigo2" type="hidden" value="<?php echo $matriz['razaosoc']; ?>" >
  </td>
</tr>
<tr>
  <td class="subtitulodireita">Franqu&iacute;a:</td>
  <td class="subtitulopequeno"><?php
	$sql = "select * from franquia where id='$id_franquia' order by id";
	$resposta = mysql_query($sql);

	while ($array = mysql_fetch_array($resposta))
	{
		$id		= $array["id"];
		$nome_franquia	= $array["fantasia"];
		
		echo $nome_franquia;
	}
	?>
    <input type="hidden" name="franquia" value="<?php echo $id; ?>" ></td>
</tr>
<tr>
  <td class="subtitulodireita">Tipo de Ocorr&ecirc;ncia</td>
  <td class="subtitulopequeno"><select name="tipo_ocorr" class="boxnormal">
    <option value="1">Cobran&ccedil;a</option>
    <option value="2">Atendimento</option>
    <option value="3">Administrativo</option>
    <option value="4">Comercial</option>
  </select></td>
</tr>
<tr>
  <td class="subtitulodireita">Atendente * </td>
  <td class="subtitulopequeno"><?
	if ($tipo == "b") $frq =  " WHERE franquia='$id_franquia'";
	else $frq = " WHERE franquia IN(1, 163, 5) ";
	
	echo "<select name='atendente2' class='boxnormal' onChange='foco()'>";
	echo "<option value=''>.: Selecione :.</option>";
	$sql = "select id, atendente from cs2.atendentes $frq AND situacao = 'A' order by atendente";
//	$sql = "select id, atendente from cs2.atendentes WHERE franquia='$id_franquia' order by atendente";
	$resposta = mysql_query($sql);
	while ($array = mysql_fetch_array($resposta)) {
		$id_atendente   = $array["id"];
		$nome_atendente = $array["atendente"];
		echo "<option value=\"$id_atendente\">$nome_atendente</option>\n";
	}
	echo "</select>";
  ?>&nbsp;<input type="button" name="Cadastrar Novo Atendente" value="Cadastrar Novo Atendente" onclick="novo()" /></td>
</tr>
</tr>
<tr>
  <td class="subtitulodireita">Data e hora atual:</td>
  <td class="subtitulopequeno"><?php echo $hoje; ?></td>
</tr>
<tr>
  <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
<tr>
  <td colspan="2" align="center">
  <input type="button" name="Submit" value="  Enviar              " onclick="valida()">
  <input type="button"  name="envia" id="envia" value="Salvar" onClick="ValidaFormConsultaMais(document.postar);">
  <input type="reset" name="reset" value="              Apagar  ">
   </td>
</tr>
</table>
</form>
</body>