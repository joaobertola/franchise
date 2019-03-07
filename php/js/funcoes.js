//=============================================================================
// Autor: Cl�bio Rodrigues
// Data: 26/01/2007
// Fun��o: Formata valores monet�rios ao digitar
//=============================================================================
//////////////////////////////////////////////////////
// Formatar campos moeda dinamicamente
//////////////////////////////////////////////////////
function FormataValor(campo,tammax,teclapres) {
 var tecla = teclapres.keyCode;
 //if (tecla < 48 || tecla > 57)  event.returnValue = false;
 vr = campo.value;
 vr = vr.replace( "/", "" );
 vr = vr.replace( "/", "" );
 vr = vr.replace( ",", "" );
 vr = vr.replace( ".", "" );
 vr = vr.replace( ".", "" );
 vr = vr.replace( ".", "" );
 vr = vr.replace( ".", "" );
 tam = vr.length;
 
 if (tam < tammax && tecla != 8){ tam = vr.length + 1 ; }
 
 if (tecla == 8 ){ tam = tam - 1 ; }
  
 if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){
  if ( tam <= 2 ){ 
    campo.value = vr ; }
   if ( (tam > 2) && (tam <= 5) ){
    campo.value = vr.substr( 0, tam - 2 ) + ',' + vr.substr( tam - 2, tam ) ; }
   if ( (tam >= 6) && (tam <= 8) ){
    campo.value = vr.substr( 0, tam - 5 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
   if ( (tam >= 9) && (tam <= 11) ){
    campo.value = vr.substr( 0, tam - 8 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
   if ( (tam >= 12) && (tam <= 14) ){
    campo.value = vr.substr( 0, tam - 11 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
   if ( (tam >= 15) && (tam <= 17) ){
    campo.value = vr.substr( 0, tam - 14 ) + '.' + vr.substr( tam - 14, 3 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ;}
 }  
 
}
//=============================================================================
// Autor: Rog�rio Vaz
// Data: 10/10/2003
// Fun��o: N�o permite a digita��o de letras nos campos somente num�ricos
//=============================================================================
function soNumero() {
    var tecla;
    tecla = event.keyCode;
    if (tecla < 48 || tecla > 57)  event.returnValue = false;
}

function soNumeroPAAC() {
    var tecla;
    tecla = event.keyCode;
    if (tecla < 46 || tecla > 57 || tecla == 47)  event.returnValue = false;
}

//converte o conte�do do formulario em maiusculo depois q o mouse sair
function maiusculo(obj)
{
obj.value = obj.value.toUpperCase();
}


function ValidaEmail(campo) {
	if
	( 
	(posicao = campo.value.indexOf("\"")) != -1 ||
	(posicao = campo.value.indexOf("�")) != -1 ||
	(posicao = campo.value.indexOf(";")) != -1 || 
	(posicao = campo.value.indexOf(":")) != -1 || 
	(posicao = campo.value.indexOf("|")) != -1 || 
	(posicao = campo.value.indexOf("\\")) != -1 || 
	
	(posicao = campo.value.indexOf("�")) != -1 ||
	(posicao = campo.value.indexOf("�")) != -1 ||  
	(posicao = campo.value.indexOf("�")) != -1 ||  
	(posicao = campo.value.indexOf("�")) != -1 ||  
	(posicao = campo.value.indexOf("�")) != -1 ||    

	(posicao = campo.value.indexOf("�")) != -1 ||
	(posicao = campo.value.indexOf("�")) != -1 ||  
	(posicao = campo.value.indexOf("�")) != -1 ||  
	(posicao = campo.value.indexOf("�")) != -1 ||  
	(posicao = campo.value.indexOf("�")) != -1 ||    
	 
	(posicao = campo.value.indexOf("�")) != -1 ||
	(posicao = campo.value.indexOf("�")) != -1 ||  
	(posicao = campo.value.indexOf("�")) != -1 ||  
	(posicao = campo.value.indexOf("�")) != -1 ||  
	(posicao = campo.value.indexOf("�")) != -1 ||    
	
	(posicao = campo.value.indexOf("�")) != -1 ||
	(posicao = campo.value.indexOf("�")) != -1 ||  
	
	(posicao = campo.value.indexOf("[")) != -1 || 
	(posicao = campo.value.indexOf("]")) != -1 ||
	(posicao = campo.value.indexOf("{")) != -1 || 
	(posicao = campo.value.indexOf("}")) != -1 || 
	(posicao = campo.value.indexOf("<")) != -1 || 
	(posicao = campo.value.indexOf(">")) != -1 || 
	(posicao = campo.value.indexOf("*")) != -1 || 
	(posicao = campo.value.indexOf("+")) != -1 || 
	(posicao = campo.value.indexOf("%")) != -1 || 
	(posicao = campo.value.indexOf("$")) != -1 || 
	(posicao = campo.value.indexOf("!")) != -1 || 
	(posicao = campo.value.indexOf("?")) != -1 || 
	(posicao = campo.value.indexOf("#")) != -1 || 
	(posicao = campo.value.indexOf("'")) != -1 || 
	(posicao = campo.value.indexOf("=")) != -1 || 
	(posicao = campo.value.indexOf("�")) != -1 || 
	(posicao = campo.value.indexOf("�")) != -1 
	) {
		campo.value = campo.value.substring(0,posicao);			
	}	else if( (posicao = campo.value.indexOf("  ")) != -1 ) {
		campo.value = campo.value.substring(0,posicao + 1);			
	}	
}

function ValidaTexto(campo) {	
	var CaracteresInvalidos = new Array("\"","�","-",";",":","|","\\",
		"�","�","�","�","�","�","�","�","�","�","�","�","�","�","�",
		"�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�",		
		"�","�","[","]","{","}","<",">","*","+","%","$","!","?","#",
		"_","'","=","�","�");
	var CaracteresValidos = new Array(" "," "," "," "," "," "," ",
		"a","e","i","o","u","a","e","i","o","u","a","e","i","o","u",
		"A","E","I","O","U","A","E","I","O","U","A","E","I","O","U","A","O",
		"a","o"," "," "," "," "," "," "," "," "," "," "," "," "," ",
		" "," "," ","C","c");
		
	for (i = 0; i < CaracteresInvalidos.length; i++){
		posicao = campo.value.indexOf(CaracteresInvalidos[i]);
		if (posicao != -1){			
			campo.value = campo.value.substring(0,posicao) + CaracteresValidos[i];			
		}	else if( (posicao = campo.value.indexOf("  ")) != -1 ) {
			campo.value = campo.value.substring(0,posicao + 1);			
		}
	}
	
}



// script feito pelo Sergio - Web Control Empresas(10/05/07)
// fun��o para converter decimais ao formato brasil (real)
function MascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
    if (whichCode == 13) return true;
    key = String.fromCharCode(whichCode); // Valor para o c�digo da Chave
    if (strCheck.indexOf(key) == -1) return false; // Chave inv�lida
    len = objTextBox.value.length;
    for(i = 0; i < len; i++)
        if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal)) break;
    aux = '';
    for(; i < len; i++)
        if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0) objTextBox.value = '';
    if (len == 1) objTextBox.value = '0'+ SeparadorDecimal + '0' + aux;
    if (len == 2) objTextBox.value = '0'+ SeparadorDecimal + aux;
    if (len > 2) {
        aux2 = '';
        for (j = 0, i = len - 3; i >= 0; i--) {
            if (j == 3) {
                aux2 += SeparadorMilesimo;
                j = 0;
            }
            aux2 += aux.charAt(i);
            j++;
        }
        objTextBox.value = '';
        len2 = aux2.length;
        for (i = len2 - 1; i >= 0; i--)
        objTextBox.value += aux2.charAt(i);
        objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);
    }
    return false;
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

// fun��o para validar cadastro de vendedores
function validaVendedor(){
		//validar nome
		d = document.incluivend;
		if (d.nomec.value == ""){
			alert("O campo " + d.nomec.name + " deve ser preenchido!");
			d.nomec.focus();
			return false;
		}
		//validar email
		if (d.email.value == ""){
			alert("O campo " + d.email.name + " deve ser preenchido!");
			d.email.focus();
			return false;
		}
		//validar email(verificao de endereco eletronico)
		parte1 = d.email.value.indexOf("@");
		parte2 = d.email.value.indexOf(".");
		parte3 = d.email.value.length;
		if (!(parte1 >= 3 && parte2 >= 6 && parte3 >= 9)) {
			alert("O campo " + d.email.name + " deve ser conter um endereco eletronico!");
			d.email.focus();
			return false;
		}
		//validar cpf
		if (d.cpf.value == ""){
			alert("O campo " + d.cpf.name + " deve ser preenchido!");
			d.cpf.focus();
			return false;
		}
		//validar cpf(verificacao se contem apenas numeros)
		if (isNaN(d.cpf.value)){
			alert("O campo " + d.cpf.name + " deve conter apenas numeros!");
			d.cpf.focus();
			return false;
		}
		//validar data de nascimento
		erro=0;
		hoje = new Date();
		anoAtual = hoje.getFullYear();
		barras = d.nasce.value.split("/");
		if (barras.length == 3){
			dia = barras[0];
			mes = barras[1];
			ano = barras[2];
			resultado = (!isNaN(dia) && (dia > 0) && (dia < 32)) && (!isNaN(mes) && (mes > 0) && (mes < 13)) && (!isNaN(ano) && (ano.length == 4) && (ano <= anoAtual && ano >= 1900));
			if (!resultado) {
				alert("Formato de data invalido!");
				d.nasce.focus();
				return false;
			}
		} else {
			alert("Formato de data invalido!");
			d.nasce.focus();
			return false;
		}
		//validar sexo
		if (!d.sexo[0].checked && !d.sexo[1].checked) {
			alert("Escolha o sexo!")
			return false;
		}
		return true;
	}


function validaFranqueado(){
//validar nome da franquia
d = document.cadfranqueado;
if (d.franquia.value == ""){
	alert("O campo " + d.franquia.name + " deve ser preenchido!");
	d.franquia.focus();
	return false;
}
// validar raz�o social
if (d.razao.value == ""){
	alert("O campo " + d.razao.name + " deve ser preenchido!");
	d.razao.focus();
	return false;
}
//validar cnpj
if (d.cnpj.value == ""){
	alert("O campo " + d.cnpj.name + " deve ser preenchido!");
	d.cnpj.focus();
	return false;
}
//validar endere�o
if (d.endereco.value == ""){
	alert("O campo " + d.endereco.name + " deve ser preenchido!");
	d.endereco.focus();
	return false;
}
//validar bairro
if (d.bairro.value == ""){
	alert("O campo " + d.bairro.name + " deve ser preenchido!");
	d.bairro.focus();
	return false;
}
//validar UF
if (d.uf.value == ""){
	alert("O campo " + d.uf.name + " deve ser preenchido!");
	d.uf.focus();
	return false;
}
//validar cep
if (d.cep.value == ""){
	alert("O campo " + d.cep.name + " deve ser preenchido!");
	d.cep.focus();
	return false;
}
//validar email
if (d.email.value == ""){
	alert("O campo " + d.email.name + " deve ser preenchido!");
	d.email.focus();
	return false;
}
//validar email(verificao de endereco eletronico)
parte1 = d.email.value.indexOf("@");
parte2 = d.email.value.indexOf(".");
parte3 = d.email.value.length;
if (!(parte1 >= 3 && parte2 >= 6 && parte3 >= 9)) {
	alert("O campo " + d.email.name + " deve ser conter um endereco eletronico!");
	d.email.focus();
	return false;
}
//validar cpf
if (d.cpf1.value == ""){
	alert("O campo " + d.cpf1.name + " deve ser preenchido!");
	d.cpf1.focus();
	return false;
}
//validar cpf(verificacao se contem apenas numeros)
/*if (isNaN(d.cpf1.value)){
	alert("O campo " + d.cpf1.name + " deve conter apenas numeros!");
	d.cpf1.focus();
	return false;
}*/

return true;
}

// fun��o para cadastrar equipamentos
function validaEqp(){
		d = document.cadeqp;
		//validar codigo
		if (d.codigo.value == ""){
			alert("O campo " + d.codigo.name + " deve ser preenchido!");
			d.codigo.focus();
			return false;
		}
		//validar codigo(verificacao se contem apenas numeros)
		if (isNaN(d.codigo.value)){
			alert("O campo " + d.codigo.name + " deve conter apenas numeros!");
			d.codigo.focus();
			return false;
		}
		//validar sexo
		if (!d.sexo[0].checked && !d.sexo[1].checked) {
			alert("Escolha o sexo!")
			return false;
		}
		return true;
	}

//fun��o para validar clientes no cadastramento
function validaClientes(){
//validar nome da franquia
d = document.cadfranqueado;
if (d.franquia.value == ""){
	alert("O campo " + d.franquia.name + " deve ser preenchido!");
	d.franquia.focus();
	return false;
}
// validar raz�o social
if (d.razao.value == ""){
	alert("O campo " + d.razao.name + " deve ser preenchido!");
	d.razao.focus();
	return false;
}
// validar nome fantasia
if (d.nomef.value == ""){
	alert("O campo " + d.nomef.name + " deve ser preenchido!");
	d.nomef.focus();
	return false;
}

//validar telefone
if (d.telefone.value == ""){
	alert("O campo " + d.telefone.name + " deve ser preenchido!");
	d.telefone.focus();
	return false;
}
//validar email
if (d.email.value == ""){
	alert("O campo " + d.email.name + " deve ser preenchido!");
	d.email.focus();
	return false;
}
//validar email(verificao de endereco eletronico)
parte1 = d.email.value.indexOf("@");
parte2 = d.email.value.indexOf(".");
parte3 = d.email.value.length;
if (!(parte1 >= 3 && parte2 >= 6 && parte3 >= 9)) {
	alert("O campo " + d.email.name + " deve ser conter um endereco eletronico!");
	d.email.focus();
	return false;
}
//validar nome do primeiro propriet�rio
if (d.nome_prop1.value == ""){
	alert("O campo " + d.nome_prop1.name + " deve ser preenchido!");
	d.nome_prop1.focus();
	return false;
}
//validar cpf
if (d.cpf1.value == ""){
	alert("O campo " + d.cpf1.name + " deve ser preenchido!");
	d.cpf1.focus();
	return false;
}

return true;
}
//abre um popup
function abrir(url) { 
window.open(url+'.php','500x400','toolbar=no,status=no,scrollbars=yes,location=no,menubar=no,directories=no,width=400,height=300');
}

var ieBlink = (document.all)?true:false;
function doBlink(){
	
	if(ieBlink){
		obj = document.getElementsByTagName('BLINK');
		for(i=0;i<obj.length;i++){
			tag=obj[i];
			tag.style.visibility=(tag.style.visibility=='hidden')?'visible':'hidden';
		}
	}
}

//Validar data na baixa de faturas
function validaData(campo)
{
	if (campo.value!="")
	{
		erro=0;
		hoje = new Date();
		anoAtual = hoje.getFullYear();
		mesAtual = hoje.getMonth();
		diaAtual = hoje.getDate();
		barras = campo.value.split("/");
		if (barras.length == 3)
		{
			dia = barras[0];
			mes = barras[1];
			ano = barras[2];
			resultado = (!isNaN(dia) && (dia > 0) && (dia < 32)) && (!isNaN(mes) && (mes > 0) && (mes < 13)) && (!isNaN(ano) && (ano.length == 4) && (ano >= anoAtual) );
			if (!resultado)
			{
				alert("Data invalidax.");
				campo.focus();
				return false;
			}
		 } 
		 else
		 {
			 alert("Data invalida.");
			 campo.focus();
			 return false;
		 }
	return true;
	}
}

//valida se foi introducido um cpf correto
function valida_cpf(cpf)
{
	var numeros, digitos, soma, i, resultado, digitos_iguais;
	digitos_iguais = 1;
	if (cpf.length < 11)
		return false;
	for (i = 0; i < cpf.length - 1; i++)
		if (cpf.charAt(i) != cpf.charAt(i + 1))
		{
		digitos_iguais = 0;
		break;
		}
	if (!digitos_iguais)
	{
		numeros = cpf.substring(0,9);
		digitos = cpf.substring(9);
		soma = 0;
		for (i = 10; i > 1; i--)
			  soma += numeros.charAt(10 - i) * i;
		resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
		if (resultado != digitos.charAt(0))
			  return false;
		numeros = cpf.substring(0,10);
		soma = 0;
		for (i = 11; i > 1; i--)
		  soma += numeros.charAt(11 - i) * i;
		resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
		if (resultado != digitos.charAt(1))
		  return false;
		return true;
	}
	else
		return false;
}

//valida se foi introducido um cnpj correto
function valida_cnpj(cnpj)
{
	var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
	digitos_iguais = 1;
	if (cnpj.length < 14 && cnpj.length < 15)
		return false;
	for (i = 0; i < cnpj.length - 1; i++)
		if (cnpj.charAt(i) != cnpj.charAt(i + 1))
		{
			digitos_iguais = 0;
			break;
		}
	if (!digitos_iguais)
	{
		tamanho = cnpj.length - 2
		numeros = cnpj.substring(0,tamanho);
		digitos = cnpj.substring(tamanho);
		soma = 0;
		pos = tamanho - 7;
		for (i = tamanho; i >= 1; i--)
		{
			soma += numeros.charAt(tamanho - i) * pos--;
			if (pos < 2)
				pos = 9;
		}
		resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
		if (resultado != digitos.charAt(0))
			return false;
		tamanho = tamanho + 1;
		numeros = cnpj.substring(0,tamanho);
		soma = 0;
		pos = tamanho - 7;
		for (i = tamanho; i >= 1; i--)
		{
			soma += numeros.charAt(tamanho - i) * pos--;
			if (pos < 2)
				pos = 9;
		}
		resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
		if (resultado != digitos.charAt(1))
			return false;
		return true;
	}
	else
		return false;
}

//valida se foi digitado corretamente os campos cpf/cnpj nas consultas
function validadoc(){
	d = document.incclient;
	if (d.insc.value != ""){
	    tamanho = d.insc.value.length;
		if ( tamanho != 11 && tamanho != 14 ){
			alert(" CPF ou CNPJ Inv�lidos !");
			d.insc.focus();
			return false;
		}else{
			if ( tamanho == 11 ){
				alert( "N�o � Permitido cadastro com CPF, [Contate o Departamento de Franquias] !");
				d.insc.value = "";
				d.insc.focus();
				return false;
				/*if ( valida_cpf(d.insc.value) == false) {
				//	alert( "CPF Inv�lido !");
				//	d.insc.focus();  
				///*	return false;
				}*/
			}
			if ( tamanho == 14 )
				if ( valida_cnpj(d.insc.value) == false){
					alert( "CNPJ Inv�lido !");
					d.insc.focus();
					return false;
				}
		}
		return true;
	}
}

//valida se foi digitado corretamente os campos cpf/cnpj nas consultas
function validadoc_2(){
	d = document.incclient;
	if (d.insc.value != ""){
	    tamanho = d.insc.value.length;
		if ( tamanho != 11 && tamanho != 14 ){
			alert(" CPF ou CNPJ Inv�lidos !");
			d.insc.focus();
			return false;
		}else{
			if ( tamanho == 11 ){
				return false;
				if ( valida_cpf(d.insc.value) == false) {
					alert( "CPF Inv�lido !");
					d.insc.focus();  
					return false;
				}
			}
			if ( tamanho == 14 )
				if ( valida_cnpj(d.insc.value) == false){
					alert( "CNPJ Inv�lido !");
					d.insc.focus();
					return false;
				}
		}
		return true;
	}
}

//abre nova janela para saber o CEP no site dos correios
function BuscaCep() {
	window.open ('busca_cep.html','buscaCep','scrollbars=no,resizable=no,width=780,height=500');
}

function showStatus (sMsg) {
	window.status = sMsg;
	return true;
}

//bloquear bot�o direito
document.oncontextmenu = function(){return false}