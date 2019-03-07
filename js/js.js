function iniciaAjax()
{
	//verifica se o navegado √© o Iternet Explorer ou outros navegadores
	if(window.ActiveXObject)
	{
		//est√¢ncia o objeto ActiveX
		ajax = new ActiveXObject("Microsoft.XMLHTTP");				
	}
	else
	{
		ajax = new XMLHttpRequest();
	}
	
	return ajax;
}

function carregando()
{
	//limpa as cidades j√° existentes
	document.getElementById('cidades').innerHTML = "";
	//pega o local onde a combo de cidades ser√° exibida]
	var local = document.getElementById('cidades');
	
	//cria uma combo select
	var combo = document.createElement('select');
	combo.setAttribute('name','cidade');
	combo.setAttribute('id','cidade');	
	
	var opcao = document.createElement('option');
	opcao.setAttribute('value', 00);
	opcao.appendChild(document.createTextNode("Carregando..."));
	
	//adiciona essa op√ß√£o na combo
	combo.appendChild(opcao);
	
	//coloca a combo dentro do div
	local.appendChild(combo);
}

function mostrarCidades(idEstado)
{
	//informa que est√° carregando as cidades
	carregando();	
	
	//inicia o AJAX
	ajax = iniciaAjax();
	
	ajax.onreadystatechange = mostrarCidades2;
	
	//abre a conex√£o com o servidor
	ajax.open("GET", "../sub_categoria_xml.php?idEstado="+idEstado);

	//envia a requisi√ß√£o para o servidor
	ajax.send();	
}

function mostrarCidades2()
{
	//verifica o status da requisi√ß√£o, se for o processamento est√° completo 
	if (ajax.readyState == 4) 
	{     		
		//verifica o n√∫mero do status, se for diferente de 200 tem algum erro 
		if (ajax.status == 200) 
		{
            var xml = ajax.responseXML;
			if(xml != null)
			{
				if(xml.hasChildNodes())
				{	
					//limpa as cidades j√° existentes
					document.getElementById('cidades').innerHTML = "";
					
					//pega o local onde a combo de cidades ser√° exibida]
					var local = document.getElementById('cidades');
					
					//cria uma combo select
					var combo = document.createElement('select');
					combo.setAttribute('name','cidade');
					combo.setAttribute('id','cidade');
					
					//pega todas as cidades qae retornou do XML
					var nos = xml.getElementsByTagName('cidade');
					
					//faz um loop para percorrer todas as tags produto
					for(cont = 0; cont < nos.length; cont++)
					{
						//verifica se √© o IE
						if(window.ActiveXObject)
						{						
							var idCidade = nos[cont].childNodes[0].firstChild.nodeValue;
							var cidade = nos[cont].childNodes[1].firstChild.nodeValue;
						}
						else
						{
							var idCidade = nos[cont].childNodes[1].firstChild.nodeValue;
							var cidade = nos[cont].childNodes[3].firstChild.nodeValue;
						}	
						
						//cria um option do select
						var opcao = document.createElement('option');
						opcao.setAttribute('value', idCidade);
						opcao.appendChild(document.createTextNode(cidade));
						
						//adiciona essa opÁ„o na combo
						combo.appendChild(opcao);
						
					}
					
					//coloca a combo dentro do div
					local.appendChild(combo);
				}
			}
        } 
		else 
		{
            alert("Houve um problema ao carregar a lista de cidades:\n" + ajax.statusText);
        }		
    } 	
}

function alteraDestaque(id, campo, valor){
	//inicia o ajax
	ajax = iniciaAjax();
	
	//abre a conexao com o servidor
	//alert("../produtos_altera_destaque.php?id=" + id +"&campo=" + campo + "&valor=" + valor);
	ajax.open("GET", "../produtos_altera_destaque.php?id=" + id +"&campo=" + campo + "&valor=" + valor);	
	
	//envia a requisicao para o servidor
	ajax.send();	
}

function alteraMensalidade(id, assinatura, valor){
	//inicia o ajax
	ajax = iniciaAjax();
	
	ajax.onreadystatechange = alteraMensalidade2;
	
	//abre a conexao com o servidor
	//alert("../altera_mensalidade.php?id=" + id +"&assinatura=" + assinatura +"&valor=" + valor);
	ajax.open("GET","../altera_mensalidade.php?id=" + id +"&assinatura=" + assinatura +"&valor=" + valor);	
	//envia a requisicao para o servidor
	ajax.send();	
}
function alteraMensalidade2()
{
	//verifica o status da requisi√ß√£o, se for o processamento est√° completo 
	if (ajax.readyState == 4) 
	{     		
		//verifica o n√∫mero do status, se for diferente de 200 tem algum erro 
		if (ajax.status == 200) 
		{
			document.getElementById('mensalidade').innerHTML = ajax.responseText;
			
			//inicia o ajax
			ajax = iniciaAjax();
			
			ajax.onreadystatechange = alteraGrid;
			
			//abre a conexao com o servidor
//			alert("../php/area_restrita/d_tabela_inform.php?codigo="+document.getElementById('cod').value);
			ajax.open("GET","../php/area_restrita/d_tabela_inform.php?codigo="+document.getElementById('cod').value);	
			//envia a requisicao para o servidor
			ajax.send();	
		} 
		else 
		{
            alert("Houve um problema ao carregar:\n" + ajax.statusText);
        }		
    }	
}

function alteraGrid(){
	//verifica o status da requisi√ß√£o, se for o processamento est√° completo 
	if (ajax.readyState == 4) 
	{     		
		//verifica o n√∫mero do status, se for diferente de 200 tem algum erro 
		if (ajax.status == 200) 
		{	
			//document.getElementById('grid').innerHTML = "";
			document.getElementById('grid').innerHTML = ajax.responseText;
		} 
		else 
		{
            alert("Houve um problema ao carregar:\n" + ajax.statusText);
        }		
    }
	
}

function alteraMensalidadeTotal(id, campo){
	//inicia o ajax
	ajax = iniciaAjax();
	
	//abre a conexao com o servidor
	//alert("../produtos_altera_destaque.php?id=" + id +"&valor=" + campo);
	ajax.open("GET", "../altera_mensalidade_total.php?id=" + id +"&valor=" + campo);	
	
	//envia a requisicao para o servidor
	ajax.send();	
}

function mascaraHellas(valor, id, mascara, evento)
{
	/////////////////////////////////////////////////////////////////////////////////
	//                                 MODELO                                      //
	//onkeyup="mascaraHellas(this.value, this.id, '####.##.##.#######-#/#', event)"//
	/////////////////////////////////////////////////////////////////////////////////
	
	
	//inicializa a variavel que vai conter o valor final
	var valorFinal = "";
	
	//verifica o que foi digitada para que seja verificado se È somente n˙meros ou n„o
	var tecla = evento.keyCode;	
	
	//manetem o tamanho original do campo sem retirar a m·scara
	var valorOriginal = valor;
	
	//inicializa um array com todos os caracteres que ser„o retirado
	var arrNaoPermitidos = new Array("-", ".", "/", "\\", "|", "(", ")", ":", " ", "*");
	
	//retira qualquer m·scatra que j· tenho sido colocada
	for(i1=0;i1<valor.length;i1++)
	{
		for(i2=0;i2<arrNaoPermitidos.length;i2++)
		{
			if(valor.charAt(i1) == arrNaoPermitidos[i2])
			{
				valor = valor.toString().replace( arrNaoPermitidos[i2], "" );
			}	
		}	
	}	
	
		
	//verifica se foi precionado o backspae
	if(tecla != 8)
	{			
		//verifica se j· n„o ultrapassou o tamanha m·ximo da m·scara
		if(mascara.length >= valorOriginal.length)
		{			
			//loop em cima do valor do campo sem a m·scara
			jaTemMascara = false;
			for(i=0;i<valor.length;i++)
			{			
				//verifica se a string j· recebeu alguma m·scara ou n„o
				if(jaTemMascara == false)
				{
					//verifica se o tipo da entrada de dados tem que ser nÈmerica
					if(mascara.charAt(i) == "#")
					{
						//verifica se foi digitado somente n˙meros
						if(((tecla > 95) && (tecla < 106)) || ((tecla > 47) && (tecla < 58)) || tecla == 9 || tecla == 16)
						{
							//0 = 96 ou 48
							//1 = 97 ou 49
							//2 = 98 ou 50
							//3 = 99 ou 51
							//4 = 100 ou 52
							//5 = 101 ou 53
							//6 = 102 ou 54
							//7 = 103 ou 55
							//8 = 104 ou 56
							//9 = 105 ou 57
							//tecla == 9 = tab
							valorFinal = valorFinal  + valor.charAt(i);
						}
						else//se n„o foi digitado um n˙mero È retirado o caracter da string
						{
							valorFinal = valorOriginal.substring(0, valorOriginal.length -1);
						}					
					}
					else if(mascara.charAt(i) == "@")//verifica se o tipo da entrada È qualquer caracter
					{
						valorFinal = valorFinal  + valor.charAt(i);
					}
					else//se n„o for quelaquer caracter È algum elemento da m·scara
					{
						//verifica se o prÛxima depois da m·scara È n˙merica 
						if(mascara.charAt(i + 1) == "#")
						{
							//verifica se foi digitado somente n˙meros
							if(((tecla > 95) && (tecla < 106)) || ((tecla > 47) && (tecla < 58)) || tecla == 9 || tecla == 16)
							{
								//0 = 96 ou 48
								//1 = 97 ou 49
								//2 = 98 ou 50
								//3 = 99 ou 51
								//4 = 100 ou 52
								//5 = 101 ou 53
								//6 = 102 ou 54
								//7 = 103 ou 55
								//8 = 104 ou 56
								//9 = 105 ou 57
								//tecla == 9 = tab
								valorFinal = valorFinal + mascara.charAt(i + jaTemMascara)  + valor.charAt(i);			
								jaTemMascara = jaTemMascara + 1;	
							}
							else//se n„o foi digitado um n˙mero È retirado o caracter da string
							{
								valorFinal = valorOriginal.substring(0, valorOriginal.length -1);
							}
						}
						else// se n„o È n˙merico ent„o pode ser qualuqer caracter
						{
							valorFinal = valorFinal + mascara.charAt(i + jaTemMascara)  + valor.charAt(i);			
							jaTemMascara = jaTemMascara + 1;
						}					
					}
				}
				else//else da verificaÁ„o da m·scara
				{
					//verifica se foi digitado somente n˙meros
					if(mascara.charAt(i + jaTemMascara) == "#")
					{
						//verifica se foi digitado somente n˙meros
						if(((tecla > 95) && (tecla < 106)) || ((tecla > 47) && (tecla < 58)) || tecla == 9 || tecla == 16)
						{
							//0 = 96 ou 48
							//1 = 97 ou 49
							//2 = 98 ou 50
							//3 = 99 ou 51
							//4 = 100 ou 52
							//5 = 101 ou 53
							//6 = 102 ou 54
							//7 = 103 ou 55
							//8 = 104 ou 56
							//9 = 105 ou 57
							//tecla == 9 = tab
							valorFinal = valorFinal  + valor.charAt(i);
						}
						else//se n„o foi digitado um n˙mero È retirado o caracter da string
						{
							valorFinal = valorOriginal.substring(0, valorOriginal.length -1);
						}
					}
					else if(mascara.charAt(i + jaTemMascara) == "@")//verifica se o tipo da entrada È qualquer caracter
					{
						valorFinal = valorFinal  + valor.charAt(i);
					}
					else
					{
						//verifica se foi digitado somente n˙meros
						if(mascara.charAt(i + jaTemMascara +1) == "#")
						{
							//verifica se foi digitado somente n˙meros
							if(((tecla > 95) && (tecla < 106)) || ((tecla > 47) && (tecla < 58)) || tecla == 9 || tecla == 16)
							{
								//0 = 96 ou 48
								//1 = 97 ou 49
								//2 = 98 ou 50
								//3 = 99 ou 51
								//4 = 100 ou 52
								//5 = 101 ou 53
								//6 = 102 ou 54
								//7 = 103 ou 55
								//8 = 104 ou 56
								//9 = 105 ou 57
								//tecla == 9 = tab
								valorFinal = valorFinal + mascara.charAt(i + jaTemMascara)  + valor.charAt(i);			
								jaTemMascara = jaTemMascara + 1;	
							}
							else//se n„o foi digitado um n˙mero È retirado o caracter da string
							{
								valorFinal = valorOriginal.substring(0, valorOriginal.length -1);
							}
						}
						else// se n„o È n˙merico ent„o pode ser qualuqer caracter
						{
							valorFinal = valorFinal + mascara.charAt(i + jaTemMascara)  + valor.charAt(i);			
							jaTemMascara = jaTemMascara + 1;
						}							
					}	
				}//fim da verificaÁ„o da m·scara	
			}	
		}
		else
		{
			valorFinal = valorOriginal.substring(0, mascara.length);	
		}//final da verificaÁ„o do tamanha m·ximo da string
	}
	else
	{
		//valorFinal = valorOriginal.substring(0, valorOriginal.length -1)
		valorFinal = valorOriginal.substring(0, valorOriginal.length);		
	}//final da verificaÁ„o do backspace
	document.getElementById(id).value = valorFinal;
}

function formatarMoedaVersaoNova(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
    if (whichCode == 13) return true;
    key = String.fromCharCode(whichCode); // Valor para o cÛdigo da Chave
    if (strCheck.indexOf(key) == -1) return false; // Chave inv·lida
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
        objTextBox.value += SeparadorDecimal + aux. substr(len - 2, len);
    }
    return false;
}


function Limpar(valor, validos) {
// retira caracteres invalidos da string
var result = "";
var aux;
for (var i=0; i < valor.length; i++) {
aux = validos.indexOf(valor.substring(i, i+1));
if (aux>=0) {
result += aux;
}
}
return result;
}

//Formata n˙mero tipo moeda usando o evento onKeyDown

function Formata(campo,tammax,teclapres,decimal) {
var tecla = teclapres.keyCode;
vr = Limpar(campo.value,"0123456789");
tam = vr.length;
dec=decimal

if (tam < tammax && tecla != 8){ tam = vr.length + 1 ; }

if (tecla == 8 )
{ tam = tam - 1 ; }

if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 )
{

if ( tam <= dec )
{ campo.value = vr ; }

if ( (tam > dec) && (tam <= 5) ){
campo.value = vr.substr( 0, tam - 2 ) + "," + vr.substr( tam - dec, tam ) ; }
if ( (tam >= 6) && (tam <= 8) ){
campo.value = vr.substr( 0, tam - 5 ) + "." + vr.substr( tam - 5, 3 ) + "," + vr.substr( tam - dec, tam ) ; 
}
if ( (tam >= 9) && (tam <= 11) ){
campo.value = vr.substr( 0, tam - 8 ) + "." + vr.substr( tam - 8, 3 ) + "." + vr.substr( tam - 5, 3 ) + "," + vr.substr( tam - dec, tam ) ; }
if ( (tam >= 12) && (tam <= 14) ){
campo.value = vr.substr( 0, tam - 11 ) + "." + vr.substr( tam - 11, 3 ) + "." + vr.substr( tam - 8, 3 ) + "." + vr.substr( tam - 5, 3 ) + "," + vr.substr( tam - dec, tam ) ; }
if ( (tam >= 15) && (tam <= 17) ){
campo.value = vr.substr( 0, tam - 14 ) + "." + vr.substr( tam - 14, 3 ) + "." + vr.substr( tam - 11, 3 ) + "." + vr.substr( tam - 8, 3 ) + "." + vr.substr( tam - 5, 3 ) + "," + vr.substr( tam - 2, tam ) ;}
} 

}