<title>Inform System</title>
<link href="sct/geral.css" rel="stylesheet" type="text/css">
<script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<script type="text/javascript" src="../web_control/js/jquery.js"></script>
<script type="text/javascript" src="../web_control/js/jquery.maskedinput-1.1.1.js"></script>
<script type="text/javascript" src="../web_control/js/jquery.meio.mask.js"></script>

<script type="text/javascript">
// função q preenche automáticamente o CEP
function addEvent(obj, evt, func) {
  if (obj.attachEvent) {
    return obj.attachEvent(("on"+evt), func);
  } else if (obj.addEventListener) {
    obj.addEventListener(evt, func, true);
    return true;
  }
  return false;
}

function XMLHTTPRequest() {
  try {
    return new XMLHttpRequest(); // FF, Safari, Konqueror, Opera, ...
  } catch(ee) {
    try {
      return new ActiveXObject("Msxml2.XMLHTTP"); // activeX (IE5.5+/MSXML2+)
    } catch(e) {
      try {
        return new ActiveXObject("Microsoft.XMLHTTP"); // activeX (IE5+/MSXML1)
      } catch(E) {
        return false; // doesn't support
      }
    }
  }
}

function buscarEndereco() {
var campos = {
  validcep: document.getElementById("validcep"),
  cep: document.getElementById("cep"),
  logradouro: document.getElementById("logradouro"),
  numero: document.getElementById("numero"),
  complemento: document.getElementById("complemento"),// IMPLEMENTADO NA VERSÃO 4.0
  bairro: document.getElementById("bairro"),
  localidade: document.getElementById("localidade"),
  uf: document.getElementById("uf")
};

var ajax = XMLHTTPRequest();

ajax.open("GET", ("../franquias/client.php?cep="+campos.cep.value.replace(/[^\d]*/, "")), true);

  ajax.onreadystatechange = function() {
  if (ajax.readyState == 1) {
  campos.logradouro.disabled = true;
  campos.logradouro.value = "carregando...";
  campos.bairro.disabled = true;
  campos.localidade.disabled = true;
  campos.bairro.value = "carregando...";
  
  campos.numero.disabled = true;// IMPLEMENTADO NA VERSÃO 4.0
  campos.numero.value = "carregando...";

  campos.complemento.disabled = true;// IMPLEMENTADO NA VERSÃO 4.0
  campos.complemento.value = "carregando...";

  campos.uf.disabled = true;
  campos.localidade.value = "carregando...";
  } else if (ajax.readyState == 4) {
  if(ajax.responseText == false){
    campos.validcep.innerHTML = "Cep invalido !!!";
    campos.logradouro.disabled = false;
    campos.logradouro.value = "";
    campos.numero.disabled = false;// IMPLEMENTADO NA VERSÃO 4.0
    campos.numero.value = "";// IMPLEMENTADO NA VERSÃO 4.0
    campos.complemento.disabled = false;// IMPLEMENTADO NA VERSÃO 4.0
    campos.complemento.value = "";// IMPLEMENTADO NA VERSÃO 4.0
    campos.bairro.disabled = false;
    campos.localidade.disabled = false;
    campos.bairro.value = "";
    campos.uf.disabled = false;
    campos.localidade.value = "";
  }else{
    campos.validcep.innerHTML = "";
    var r = ajax.responseText, i, logradouro, complemento, numero, bairro, localidade, uf;
    logradouro = r.substring(0, (i = r.indexOf(':')));
    campos.logradouro.disabled = false;
    campos.logradouro.value = unescape(logradouro.replace(/\+/g," "));
	<!-- IMPLEMENTADO NA VERSÃO 4.0 -->
	r = r.substring(++i);
    complemento = r.substring(0, (i = r.indexOf(':')));
    campos.complemento.disabled = false;
    campos.complemento.value = unescape(complemento.replace(/\+/g," "));

    r = r.substring(++i);
    bairro = r.substring(0, (i = r.indexOf(':')));
    campos.bairro.disabled = false;
    campos.bairro.value = unescape(bairro.replace(/\+/g," "));
    r = r.substring(++i);
    localidade = r.substring(0, (i = r.indexOf(':')));
    campos.localidade.disabled = false;
    campos.localidade.value = unescape(localidade.replace(/\+/g," "));
	
	<!-- IMPLEMENTADO NA VERSÃO 4.0 -->
	r = r.substring(++i);
    numero = r.substring(0, (i = r.indexOf(':')));
    campos.numero.disabled = false;
    campos.numero.value = unescape(numero.replace(/\+/g," "));


    r = r.substring(++i);
    uf = r.substring(0, (i = r.indexOf(';')));
    campos.uf.disabled = false;
    i = campos.uf.options.length;
    while (i--) {
      if (campos.uf.options[i].getAttribute("value") == uf) {
      break;
      }
    }
    campos.uf.selectedIndex = i;
  }
  }
};
ajax.send(null);
}


window.addEvent(
  window,
  "load",
  function() {window.addEvent(document.getElementById("cep"), "blur", buscarEndereco);}
);


function MM_formtCep(e,src,mask) {
if(window.event) { _TXT = e.keyCode; }
else if(e.which) { _TXT = e.which; }
if(_TXT > 47 && _TXT < 58) {
 var i = src.value.length; var saida = mask.substring(0,1); var texto = mask.substring(i)
 if (texto.substring(0,1) != saida) { src.value += texto.substring(0,1); }
    return true; } else { if (_TXT != 8) { return false; }
 else { return true; }
}
}

</script>

<script language="JavaScript">


jQuery(function($){
   $("#fone").mask("(99) 9999-9999");
});

(function($){
// call setMask function on the document.ready event
  $(function(){
	$('input:text').setMask();
  }
);
})(jQuery);

ScrollSpeed = 300;  // milliseconds between scrolls
ScrollChars = 4;    // chars scrolled per time period

function SetupTicker() {
	msg = "Bem vindos a Inform System";
    RunTicker();
}

function RunTicker() {
	window.setTimeout('RunTicker()',ScrollSpeed);
	window.status = msg;
	msg = msg.substring(ScrollChars) + msg.substring(0,ScrollChars);
}

SetupTicker();

function click() {
	if (event.button==2) {
    }
}

document.onmousedown=click

dCol='000000';//date colour.
fCol='000000';//face colour.
sCol='000000';//seconds colour.
mCol='000000';//minutes colour.
hCol='000000';//hours colour.
ClockHeight=40;
ClockWidth=40;
ClockFromMouseY=0;
ClockFromMouseX=100;

//Alter nothing below! Alignments will be lost!

d=new Array("DOMINGO","SEGUNDA","TERÇA","QUARTA","QUINTA","SEXTA","SÁBADO");
m=new Array("JANEIRO","FEVEREIRO","MARÇO","ABRIL","MAIO","JUNHO","JULHO","AGOSTO","SETEMBRO","OUTUBRO","NOVEMBRO","DEZEMBRO");
date=new Date();
day=date.getDate();
year=date.getYear();
if (year < 2000) year=year+1900;
TodaysDate=" "+d[date.getDay()]+" "+day+" "+m[date.getMonth()]+" "+year;
D=TodaysDate.split('');
H='...';
H=H.split('');
M='....';
M=M.split('');
S='.....';
S=S.split('');
Face='1 2 3 4 5 6 7 8 9 10 11 12';
font='Arial';
size=1;
speed=0.6;
ns=(document.layers);
ie=(document.all);
Face=Face.split(' ');
n=Face.length;
a=size*7;
ymouse=0;
xmouse=0;
scrll=0;
props="<font face="+font+" size="+size+" color="+fCol+">";
props2="<font face="+font+" size="+size+" color="+dCol+">";
Split=360/n;
Dsplit=360/D.length;
HandHeight=ClockHeight/4.5
HandWidth=ClockWidth/4.5
HandY=-7;
HandX=-2.5;
scrll=0;
step=0.06;
currStep=0;
y=new Array();x=new Array();Y=new Array();X=new Array();
for (i=0; i < n; i++){y[i]=0;x[i]=0;Y[i]=0;X[i]=0}
Dy=new Array();Dx=new Array();DY=new Array();DX=new Array();
for (i=0; i < D.length; i++){Dy[i]=0;Dx[i]=0;DY[i]=0;DX[i]=0}
if (ns){
for (i=0; i < D.length; i++)
document.write('<layer name="nsDate'+i+'" top=0 left=0 height='+a+' width='+a+'><center>'+props2+D[i]+'</font></center></layer>');
for (i=0; i < n; i++)
document.write('<layer name="nsFace'+i+'" top=0 left=0 height='+a+' width='+a+'><center>'+props+Face[i]+'</font></center></layer>');
for (i=0; i < S.length; i++)
document.write('<layer name=nsSeconds'+i+' top=0 left=0 width=15 height=15><font face=Arial size=3 color='+sCol+'><center><b>'+S[i]+'</b></center></font></layer>');
for (i=0; i < M.length; i++)
document.write('<layer name=nsMinutes'+i+' top=0 left=0 width=15 height=15><font face=Arial size=3 color='+mCol+'><center><b>'+M[i]+'</b></center></font></layer>');
for (i=0; i < H.length; i++)
document.write('<layer name=nsHours'+i+' top=0 left=0 width=15 height=15><font face=Arial size=3 color='+hCol+'><center><b>'+H[i]+'</b></center></font></layer>');
}
if (ie){
document.write('<div id="Od" style="position:absolute;top:0px;left:0px"><div style="position:relative">');
for (i=0; i < D.length; i++)
document.write('<div id="ieDate" style="position:absolute;top:0px;left:0;height:'+a+';width:'+a+';text-align:center">'+props2+D[i]+'</div></font>');
document.write('</div></div>');
document.write('<div id="Of" style="position:absolute;top:0px;left:0px"><div style="position:relative">');
for (i=0; i < n; i++)
document.write('<div id="ieFace" style="position:absolute;top:0px;left:0;height:'+a+';width:'+a+';text-align:center">'+props+Face[i]+'</div></font>');
document.write('</div></div>');
document.write('<div id="Oh" style="position:absolute;top:0px;left:0px"><div style="position:relative">');
for (i=0; i < H.length; i++)
document.write('<div id="ieHours" style="position:absolute;width:16px;height:16px;font-family:Arial;font-size:16px;color:'+hCol+';text-align:center;font-weight:bold">'+H[i]+'</div>');
document.write('</div></div>');
document.write('<div id="Om" style="position:absolute;top:0px;left:0px"><div style="position:relative">');
for (i=0; i < M.length; i++)
document.write('<div id="ieMinutes" style="position:absolute;width:16px;height:16px;font-family:Arial;font-size:16px;color:'+mCol+';text-align:center;font-weight:bold">'+M[i]+'</div>');
document.write('</div></div>')
document.write('<div id="Os" style="position:absolute;top:0px;left:0px"><div style="position:relative">');
for (i=0; i < S.length; i++)
document.write('<div id="ieSeconds" style="position:absolute;width:16px;height:16px;font-family:Arial;font-size:16px;color:'+sCol+';text-align:center;font-weight:bold">'+S[i]+'</div>');
document.write('</div></div>')
}
(ns)?window.captureEvents(Event.MOUSEMOVE):0;
function Mouse(evnt){
ymouse = (ns)?evnt.pageY+ClockFromMouseY-(window.pageYOffset):event.y+ClockFromMouseY;
xmouse = (ns)?evnt.pageX+ClockFromMouseX:event.x+ClockFromMouseX;
}
(ns)?window.onMouseMove=Mouse:document.onmousemove=Mouse;
function ClockAndAssign(){
time = new Date ();
secs = time.getSeconds();
sec = -1.57 + Math.PI * secs/30;
mins = time.getMinutes();
min = -1.57 + Math.PI * mins/30;
hr = time.getHours();
hrs = -1.575 + Math.PI * hr/6+Math.PI*parseInt(time.getMinutes())/360;
if (ie){
Od.style.top=window.document.body.scrollTop;
Of.style.top=window.document.body.scrollTop;
Oh.style.top=window.document.body.scrollTop;
Om.style.top=window.document.body.scrollTop;
Os.style.top=window.document.body.scrollTop;
}
for (i=0; i < n; i++){
 var F=(ns)?document.layers['nsFace'+i]:ieFace[i].style;
 F.top=y[i] + ClockHeight*Math.sin(-1.0471 + i*Split*Math.PI/180)+scrll;
 F.left=x[i] + ClockWidth*Math.cos(-1.0471 + i*Split*Math.PI/180);
 }
for (i=0; i < H.length; i++){
 var HL=(ns)?document.layers['nsHours'+i]:ieHours[i].style;
 HL.top=y[i]+HandY+(i*HandHeight)*Math.sin(hrs)+scrll;
 HL.left=x[i]+HandX+(i*HandWidth)*Math.cos(hrs);
 }
for (i=0; i < M.length; i++){
 var ML=(ns)?document.layers['nsMinutes'+i]:ieMinutes[i].style;
 ML.top=y[i]+HandY+(i*HandHeight)*Math.sin(min)+scrll;
 ML.left=x[i]+HandX+(i*HandWidth)*Math.cos(min);
 }
for (i=0; i < S.length; i++){
 var SL=(ns)?document.layers['nsSeconds'+i]:ieSeconds[i].style;
 SL.top=y[i]+HandY+(i*HandHeight)*Math.sin(sec)+scrll;
 SL.left=x[i]+HandX+(i*HandWidth)*Math.cos(sec);
 }
for (i=0; i < D.length; i++){
 var DL=(ns)?document.layers['nsDate'+i]:ieDate[i].style;
 DL.top=Dy[i] + ClockHeight*1.5*Math.sin(currStep+i*Dsplit*Math.PI/180)+scrll;
 DL.left=Dx[i] + ClockWidth*1.5*Math.cos(currStep+i*Dsplit*Math.PI/180);
 }
currStep-=step;
}
function Delay(){
scrll=(ns)?window.pageYOffset:0;
Dy[0]=Math.round(DY[0]+=((ymouse)-DY[0])*speed);
Dx[0]=Math.round(DX[0]+=((xmouse)-DX[0])*speed);
for (i=1; i < D.length; i++){
Dy[i]=Math.round(DY[i]+=(Dy[i-1]-DY[i])*speed);
Dx[i]=Math.round(DX[i]+=(Dx[i-1]-DX[i])*speed);
}
y[0]=Math.round(Y[0]+=((ymouse)-Y[0])*speed);
x[0]=Math.round(X[0]+=((xmouse)-X[0])*speed);
for (i=1; i < n; i++){
y[i]=Math.round(Y[i]+=(y[i-1]-Y[i])*speed);
x[i]=Math.round(X[i]+=(x[i-1]-X[i])*speed);
}
ClockAndAssign();
setTimeout('Delay()',20);
}
if (ns||ie)window.onload=Delay;

var 
month= new Array();
month[0]="January";	
month[1]="February";
month[2]="March";
month[3]="April";
month[4]="May";
month[5]="June";
month[6]="July";
month[7]="August";
month[8]="September";
month[9]="October";
month[10]="November";
month[11]="December";

var 
day= new Array();
day[0]="Sunday";
day[1]="Monday";
day[2]="Tuesday";
day[3]="Wednesday";
day[4]="Thursday";
day[5]="Friday";
day[6]="Saturday";


today = new Date();
dtStr = today.getDate();
dyStr = (day[today.getDay()]);
mthStr = (month[today.getMonth()]);
yrStr = today.getFullYear();
gmt = today.toGMTString();
exp = gmt;

suf="th";
if (dtStr==1 || dtStr==21 || dtStr==31) {suf="st";}
if (dtStr==2 || dtStr==22) {suf="nd";}
if (dtStr==3 || dtStr==23) {suf="rd";}

date="Today is: " + dyStr + ", " + dtStr + suf + " " + mthStr + ", " + yrStr;

function sivamtime() {
	now=new Date();
	hour=now.getHours();
	min=now.getMinutes();
	sec=now.getSeconds();

if (min<=9) {
	min="0"+min;
 }
if (sec<=9) {
	sec="0"+sec;
 }
if (hour>12) {
	hour=hour-12;
	add=" p.m";
 }
else {
	hour=hour;
	add=" a.m";
 }
if (hour==12) {
	add=" p.m";
 }
if (hour==00) {
	hour="12";
 }

time = " - " + ((hour<=11) ? "0"+hour : hour) + ":" + min + ":" + sec 
	+ add;
document.title = date + time;
setTimeout("sivamtime()", 1000);
}

//função para converter tudo em maiúsculo
function maiusculo(obj) {
	obj.value = obj.value.toUpperCase();
}
//função para aceitar somente numeros em determinados campos
function soNumero() {
    var tecla;
    tecla = event.keyCode;
    if (tecla < 48 || tecla > 57)  event.returnValue = false;
}
// formato mascara CNPJ, telefone e CPF
function formatar(mascara, documento){
  var i = documento.value.length;
  var saida = mascara.substring(0,1);
  var texto = mascara.substring(i)
  
  if (texto.substring(0,1) != saida){
	documento.value += texto.substring(0,1);
  }
}

function envia(){
 	frm = document.senddata;	
    frm.action = 'index.php?web=sendfranquia';
	frm.submit();
} 
 
function trim(str){return str.replace(/^\s+|\s+$/g,"");}//valida espaço em branco

function valida(){
	frm = document.senddata;	
	if(trim(frm.nome.value) == ""){
		alert("Falta informar o Nome !");
		frm.nome.focus();
		return false;
	}
	if(trim(frm.cpf.value) == ""){
		alert("Falta informar o CPF !");
		frm.cpf.focus();
		return false;
	}	
	/*if(isNaN(frm.cpf.value)){
		alert("Digite apenas números no CPF !")
		frm.cpf.focus();
		return false
	}*/	
	if(trim(frm.logradouro.value) == ""){
		alert("Falta informar o Endereço !");
		frm.endereco.focus();
		return false;
	}
	if(trim(frm.fone.value) == ""){
		alert("Falta informar o Telefone !");
		frm.fone.focus();
		return false;
	}
	if(trim(frm.cel.value) == ""){
		alert("Falta informar o Celular !");
		frm.cel.focus();
		return false;
	}
	/*if(frm.fone.value.length < 10){
		alert("Telefone inválido, dever ser informado o DDD e o Número!");
		frm.fone.focus();
		return false;
	}*/
	/*if(isNaN(frm.fone.value)){
		alert("Digite apenas números no Telefone !")
		frm.fone.focus();
		return false
	}*/
	if(trim(frm.email.value) == ""){
		alert("Falta informar o E-mail !");
		frm.email.focus();
		return false;
	}
	if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(frm.email.value))) {
		alert("É necessário o preenchimento de um endereço de E-MAIL Válido ! ");
		frm.email.focus();
		return false;
	}
	 envia();
}
</script>


<script type="text/javascript">
/* Máscaras ER */
function xmascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("xexecmascara()",1)
}
function xexecmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function mtel(v){
    v=v.replace(/\D/g,"");             //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
    v=v.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca hífen entre o quarto e o quinto dígitos
    return v;
}
function id( el ){
	return document.getElementById( el );
}
window.onload = function(){
	id('cel').onkeypress = function(){
		xmascara( this, mtel );
	}
	id('cel2').onkeypress = function(){
		xmascara( this, mtel );
	}
}
</script>


<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" width="100%" cellpadding="10" cellspacing="0">
	<tr>
		<td align="justify">

		<p>
		  <script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','314','height','23','title','Seja Franqueado','src','swf/tit_franquia','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','swf/tit_franquia' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="314" height="23" title="Seja Franqueado">
            <param name="movie" value="swf/tit_franquia.swf">
            <param name="quality" value="high">
            <embed src="swf/tit_franquia.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="314" height="23"></embed>
	      </object></noscript>
		</p>
		<ul>
		  <li>O Franchising <b>INFORM SYSTEM</b> atrai aquele que procura expandir um neg&oacute;cio r&aacute;pidamente sem precisar investir muito, com a seguran&ccedil;a e vantagens de fazer parte de uma empresa de SUCESSO COMPROVADO e que contem um dos Banco de Dados mais completos do Brasil.		</li>
		</ul>
		<ul>
		  <li> Crescimento descontrolado da inadimpl&ecirc;ncia, a dificuldade de Receber dos Inadimplentes, a necessidade de Aumentar as Vendas, a impossibilidade de se Localizar Pessoas e Clientes, e muitas outras necessidades, tornou a busca por Pesquisas e Solu&ccedil;&otilde;es uma pr&aacute;tica comum e extremamente necess&aacute;ria para qualquer comerciante, prestadores de servi&ccedil;os, profissionais liberais e principalmente para micro, pequeno e m&eacute;dio empres&aacute;rio.
	      </li>
		</ul></td>
  </tr>
	<tr>
	  <td>

       <table width="631" bgcolor="#FAC402" height="234" border="0" cellpadding="0" cellspacing="9" align="center">
        
        <tr align="center">
        	<td width="50%"><img src="img/1_evento_2010.jpg" width="305" height="229" alt=""></td>
            <td width="50%"><img src="img/2_evento_2010.jpg" width="305" height="229" alt=""></td>
        </tr>
        
        <tr align="center">
        	<td width="50%"><img src="img/3_evento_2010.jpg" width="305" height="229" alt=""></td>
            <td width="50%"><img src="img/4_evento_2010.jpg" width="305" height="229" alt=""></td>
        </tr>
        
        <!-- tr><td colspan="2" align="center"><img src="img/rede_11.jpg" width="618" height="32" alt=""></td></tr -->
      </table>
      
      
	  	<!-- table width="631" height="234" border="0" cellpadding="0" cellspacing="0" align="center">
			<tr>
				<td colspan="7">
					<img src="img/rede_01.jpg" width="631" height="5" alt=""></td>
			</tr>
			<tr>
			  <td height="229" rowspan="4">
					<img src="img/rede_02.jpg" width="9" height="225" alt=""></td>
				<td>
				  <img src="img/rede_13.jpg" width="150" height="225" alt=""></td>
				<td>
				  <img src="img/rede_14.jpg" width="157" height="225" alt=""></td>
				<td rowspan="2">
					<img src="img/rede_05.jpg" width="6" height="210" alt=""></td>
				<td>
				  <img src="img/rede_15.jpg" width="152" height="225" alt=""></td>
				<td>
				  <img src="img/rede_16.jpg" width="153" height="225" alt=""></td>
				<td rowspan="4">
					<img src="img/rede_08.jpg" width="4" height="205" alt=""></td>
		  </tr>          
			
		</table>
        
      <table width="631" height="253" border="0" cellpadding="0" cellspacing="0" align="center">
			<tr>
				<td colspan="7">
				  <img src="img/rede_01.jpg" width="631" height="5" alt=""></td>
			</tr>
			<tr>
				<td rowspan="4">
					<img src="img/rede_02.jpg" width="9" height="248" alt=""></td>
				<td>
				  <img src="img/rede_13.jpg" width="150" height="204" alt=""></td>
				<td>
					<img src="img/rede_14.jpg" width="157" height="204" alt=""></td>
				<td rowspan="2">
					<img src="img/rede_05.jpg" width="6" height="210" alt=""></td>
				<td>
				  <img src="img/rede_15.jpg" width="152" height="204" alt=""></td>
				<td>
					<img src="img/rede_16.jpg" width="153" height="204" alt=""></td>
				<td rowspan="4">
					<img src="img/rede_08.jpg" width="4" height="248" alt=""></td>
		  </tr>
          
			<tr>
				<td colspan="2">
					<img src="img/rede_09.jpg" width="307" height="6" alt=""></td>
				<td colspan="2">
					<img src="img/rede_10.jpg" width="305" height="6" alt=""></td>
			</tr>
			<tr>
				<td colspan="5">
					<img src="img/rede_11.jpg" width="618" height="32" alt=""></td>
			</tr>
			<tr>
				<td colspan="5">
					<img src="img/rede_12.jpg" width="618" height="6" alt=""></td>
			</tr>
		</table -->
        
	  </td>
  </tr>
	<tr>
		<td><p>A franquia <strong>INFORM SYSTEM</strong> &eacute; um excelente neg&oacute;cio, e com um custo operacional baixo, e tais caracter&iacute;sticas s&atilde;o respons&aacute;veis pela viabilidade e para o futuro do neg&oacute;cio.</p>
		  <p>N&atilde;o perca tempo, veja as cidades dispon&iacute;veis e procure informa&ccedil;&otilde;es de como implantar sua franquia. </p>
	  <p><b><font color="#ff6600">1&ordm; Passo:</font></b> Entrar em contato com o DEPARTAMENTO DE FRANQUIAS E EXPANS&Atilde;O nos telefones abaixo:</p>
	  <ul>
        <li> (41) 8838-0774&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Respons&aacute;vel do setor: <b><font color="#ff6600">Sr. Danillo Araujo.</font></b></li>
	    
	    </ul>	  
	  <p><b><font color="#ff6600">2&ordm; Passo:</font></b> Preencher o formul&aacute;rio abaixo e encaminh&aacute;-lo.</p></td>
  </tr>
</table>
<div class="hr"></div>
<div align="left"><br>

<form name="senddata" method="post" action="#">
  <br>
  <TABLE style="FONT-SIZE: 11px; FONT-FAMILY: verdana" cellSpacing=0 cellPadding=5>
    <TBODY>
      <TR> 
        <TD align="right"><B><font color="#FF0000">*</font>&nbsp;Nome :</B></TD>
        <TD>
            <input type="text" name="nome" onBlur="maiusculo(this);" style="FONT-SIZE: 11px; FONT-FAMILY: verdana;" maxlength="80" size="50">
        </TD>
      </TR>
      <TR> 
        <TD align="right"><B><font color="#FF0000">*</font>&nbsp;CPF ou CNPJ :</B></TD>
        <TD><input type="text" name="cpf" onKeyPress="soNumero()" style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength=18 size=20 >&nbsp;Informe apenas números
        	<input type="hidden" name="nacionalidade" value="Brasileiro"></TD>
      </TR>

 <tr>
    <td align="right"><B><font color="#FF0000">*</font>&nbsp;CEP :</B></td>
    <td class="subtitulopequeno">
		<table>
		  	<tr>
				<td width="267"><input type="text" name="cep" id="cep" onChange="" onKeyPress="return MM_formtCep(event,this,'#####-###');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="9" />
				<font color="#FF0000">(*)</font></td>
				<td width="10"><div id="validcep" style="color: #FF0000;"></div></td>
			</tr>
		</table>	</td>
	
  </tr>
  
      <TR> 
        <TD align="right"><B><font color="#FF0000">*</font>&nbsp;Endere&ccedil;o :</B></TD>
        <TD>
            <input type="text"  onBlur="maiusculo(this);" style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength=30 size=40 name="logradouro" id="logradouro">
        </TD>
      <TR>
	      <TD align="right"><B>N&uacute;mero :</B></TD>
          <TD><input type="text" onKeyPress="soNumero();" style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength=25 size=10 name="numero"> <font color="#FF0000">(*)</font></TD>
      </TR>
      <TR>
	      <TD align="right"><B>Complemento :</B></TD>
          <TD><input type="tComplementoext"  onBlur="maiusculo(this);" style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength=25 size=20 name="complemento" id="complemento" ></TD>

      </TR>
      <TR>
	      <TD align="right"><B>Bairro :</B></TD>
          <TD><input type="tComplementoext"  onBlur="maiusculo(this);" style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength=25 size=20 name="bairro" id="bairro"> <font color="#FF0000">(*)</font></TD>

      </TR>
      
      <TR> 
        <TD align="right"><p><B>Cidade</B> :</p></TD>
        <TD><input type="text" name="localidade" id="localidade"onBlur="maiusculo(this);" style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength=30 size=22> <font color="#FF0000">(*)</font></TD>
      </TR>
      <tr>
      	<TD align="right"><p><B>UF</B> :</p></TD>
        <td>
        
        <select name="uf" id="uf">
      <option value="">-- selecione --</option>
      <option value="AC">Acre</option>
      <option value="AL">Alagoas</option>
      <option value="AP">Amap&aacute;</option>
      <option value="AM">Amazonas</option>
      <option value="BA">Bahia</option>
      <option value="CE">Cear&aacute;</option>
      <option value="DF">Distrito Federal</option>
      <option value="ES">Esp&iacute;rito Santo</option>
      <option value="GO">Goi&aacute;s</option>
      <option value="MA">Maranh&atilde;o</option>
      <option value="MT">Mato Grosso</option>
      <option value="MS">Mato Grosso do Sul</option>
      <option value="MG">Minas Gerais</option>
      <option value="PA">Par&aacute;</option>
      <option value="PB">Para&iacute;ba</option>
      <option value="PR">Paran&aacute;</option>
      <option value="PE">Pernambuco</option>
      <option value="PI">Piau&iacute;</option>
      <option value="RJ">Rio de Janeiro</option>
      <option value="RN">Rio Grande do Norte</option>
      <option value="RS">Rio Grande do Sul</option>
      <option value="RO">Rond&ocirc;nia</option>
      <option value="RR">Roraima</option>
      <option value="SC">Santa Catarina</option>
      <option value="SP">S&atilde;o Paulo</option>
      <option value="SE">Sergipe</option>
      <option value="TO">Tocantins</option>
    </select><font color="#FF0000">(*)</font> 
        </TD>
      </TR>
      <TR> 
        <TD align="right"><b><font color="#FF0000">*</font>&nbsp;Telefone Fixo :</b></TD>
        <TD><input type="text" name="fone" id="fone" style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength="12" size="18"> 
        <strong> <font color="#FFFFFF" size="1">-</font><strong></strong><font color="#FF0000">*
        </font>
     </TD>
      </TR>
      <TR> 
        <TD align="right"><b><font color="#FF0000">*</font>&nbsp;Celular 1 :</b></TD>
        <TD><input type="text" name="cel" id="cel" style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength="15" size="18"></TD>
      </TR>
		<TR> 
        <TD align="right"><b><font color="#FF0000">*</font>&nbsp;Celular 2 :</b></TD>
        <TD><input type="text" name="cel2" id="cel2" style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength="15" size="18"></TD>
      </TR>
      <TR> 
        <TD align="right"><b><font color="#FF0000">*</font>&nbsp;E-mail :</b></TD>
        <TD><strong> 
          <INPUT style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength=100 size=50 name=email>
          <strong> <font color="#FFFFFF" size="1">-</font></strong> </strong></TD>
      </TR>
      <TR> 
        <TD align="right"><strong>Tempo que reside na <br>cidade citada acima :</strong></TD>
        <TD><textarea name=tempo cols=49 rows=3 id="tempo" style="FONT-SIZE: 11px; FONT-FAMILY: verdana"></textarea></TD>
      </TR>
      <TR> 
        <TD align="right"><p><strong>Cidade / </strong><strong>Regi&atilde;o de interesse <br> para atua&ccedil;&atilde;o 
          da franquia :</strong></p></TD>
        <TD><textarea name=interesse cols=49 rows=3 id="interesse" style="FONT-SIZE: 11px; FONT-FAMILY: verdana"></textarea></TD>
      </TR>
      <TR> 
        <TD align="right"><strong>Observa&ccedil;&otilde;es Diversas / Mensagem :</strong></TD>
        <TD><textarea name=obs cols=49 rows=3 id="obs" style="FONT-SIZE: 11px; FONT-FAMILY: verdana"></textarea></TD>
      </TR>
      
      <TR> 
        <TD align="right"></TD>
        <TD>Campos com <b><font color="#FF0000">*</font></b> são de preenchimento obrigatório</TD>
      </TR>
      
    </TBODY>
  </TABLE>
  <table border="0" cellspacing="0" cellpadding="0" align="center">
    <tr> 
      <td width="100" height="50"><i><b> 
          <input type="button" name="enviar2" value="Enviar" onClick="valida()">
          </b></i></td>
      <td width="100"><i><b> 
        <input type="reset" name="reset" value="Limpar">
        </b></i></td>
    </tr>
  </table>
</form>
</div>
<div class="hr"></div>