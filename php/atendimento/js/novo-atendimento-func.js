$(document).ready(function(){ 
    
    /*
     * gerando o protocolo ao carregar o DOM da pagina
     * */
    var tempo = generateProtocolTime();
    $('input[name=iptProtocoloAt]').val(tempo['protocolo']);
    $('input[name=iptDataHora]').val(tempo['datahora']);
    
    $('.spanNOrdem').text(tempo['protocolo']);
    $('.spanData').text(tempo['data']);
    $('.spanHora').text(tempo['hora']);
    
    /* ao editar campo nome apaga o id */
    $('#iptSolicitante').keydown(function(e){
        /* limpa ID */
        $('#iptIdSolicitante').val('');
    });
    
    
    /* clique no item departamento */
    
    $('.superItem').on('click', 'a', function(e){
        /* esconder todas as divs Item* */
        $.each($('div[class*=item]'), function (){
            $(this).hide();
        });
        
        //procurar class corepondente id e mostrar
        $('.' + this.id).show();
        
    });
    
    /*
     *keyup do campo solicitante
     **/
    
    $(document).on('keyup', 'input[name=iptSolicitante]', function() {
        autocomplet(this.id, 'list_'+this.id);
    });
    
    /*
     *clique no botao enviar ordem grava no database
     **/
    
    $('#btnGravaOrdem').click(function(e){
    
        e.preventDefault();
        
        var formData = $('form[name=frmOrdemAtendimento]').serialize();
        
        $.ajax({
                url: 'inc/ajax_requests.php?action=registraOrdem',
                type: 'POST',
                data: {
                    formData:formData
                    },
                success:function(data){
                   
                    var data = $.parseJSON(data);
                    //console.log(data.lastId);
                    //lastId
                    location.href="ordem_visualizacao.php?geracao=sim&idOrdem=" + data.lastId;
                    
                    
                    /* vai pra impressao */
                }
                ,error: function(e){
                    console.log(e);
                }
        });
            
        
    });
        
    $('#iptAssuntoAtt').on('keydown blur', function(){
        this.value = this.value.toUpperCase();
    });

});

/*
 *method autocomplete para campo solicitante
 **/

/**** função autocomplete solicitante (funcionarios) - simeia 03-09-15 */
function autocomplet(solicitante_id, list_solicitante_id) {
	var min_length = 0; // min caracters to display the autocomplete
	var keyword = $('#'+solicitante_id).val();
	if (keyword.length >= min_length) {
		$.ajax({
			url: 'inc/ajax_requests.php?action=solicitanteAutocomplete',
			type: 'POST',
			data: {
                            keyword:keyword,
                            solicitante_id: solicitante_id,
                            list_solicitante_id: list_solicitante_id
                            },
			success:function(data){
                            /*
                             *limpa dados do html
                             **/
                            $('#'+list_solicitante_id).html('');
                            /*
                             *caso tenha resultados
                             **/
                            if (data.length > 0) {
                                /*
                                 * mostra ul lista
                                 * */
                                $('#'+list_solicitante_id).show();
                                
                                /*
                                 *declarando variavel html com valor da ul
                                 **/
                                var html = $('#'+list_solicitante_id).html();
                                
                                /*
                                 *a cada dado trazido, vai montando os itens da lista
                                 **/
                                $.each(JSON.parse(data), function(k, v){
                                    /*
                                     *adicionando item de lista no html
                                     **/
                                    //html += '<li onclick="set_item("'+v.nome+'", "'+v.id+'", "'+list_solicitante_id+'")">'+v.nome+'</li>';
                                    html += '<li onclick="set_item(\'' + v.nome + '\', \'' + v.id + '\', \'' + list_solicitante_id + '\')">'+v.nome+'</li>';
                                });
                                /*
                                 * coloca html na ul
                                 * */
                                $('#'+list_solicitante_id).html(html);
                            } else {
                                $('#'+list_solicitante_id).hide();
                                $('#'+list_solicitante_id).html('');
                            }
                            
			}
		});
	} else {
		$('#'+list_solicitante_id).hide();
	}
}



// set_item : this function will be executed when we select an item no autocomplete
function set_item(item, solicitante_id, list_solicitante_id) {
  
    // change input value
    $('#iptIdSolicitante').val(solicitante_id);
    $('#iptSolicitante').val(item);
    // hide proposition list
    $('#'+list_solicitante_id).hide();
}


/*
 *method que cria o protocolo unico baasedo nos milisegundos
 **/

function generateProtocolTime() {
    
    var timestamp = [];
    
    var now = new Date();
    
    var ano = now.getFullYear().toString();
    var mes = now.getMonth()+1; //+1 porque ele pega janeiro como mes 0
    var dia = now.getDate(); //-1 porque ele pega um dia a mais
        
    var mes2 = ("0" + (now.getMonth()+1)).slice(-2); //+1 porque ele pega janeiro como mes 0
    var dia2 = ("0" + (now.getDate())).slice(-2); //-1 porque ele pega um dia a mais
    var hora = now.getHours().toString();
    var minuto = now.getMinutes().toString();
    var segundo = now.getSeconds().toString();
    var milisegundo = now.getMilliseconds().toString();
    
    timestamp['protocolo'] = ano +'.'+ mes + dia +'.'+ hora + minuto  +'.'+  segundo + milisegundo;
    timestamp['data'] = dia2 + '/' + mes2 + '/' + ano;
    timestamp['hora'] = hora + ':' + minuto + ':' + segundo;
    timestamp['datahora'] = ano + '-' + mes2 + '-' + dia2 + ' ' + hora + ':' + minuto + ':' + segundo;
    
    return timestamp;
}
























