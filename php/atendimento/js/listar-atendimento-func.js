$(document).ready(function(){
    
    /*
     *set datatable to table
     **/
    //$('#tblListaAtendimento').DataTable();
    constructDataTable();
    
    /* busca dados departamentos para select da busca */
    //buscaDepartamentos();
    
    /*
     *clique no botao novo atendimento
     **/
    $('#btnNovoAtendimento, .aNovoAtendimento').click(function(){
        /*
         *vai para pagina que registra novo atendimento
         **/
        //window.location.href="novo_atendimento.php";
        
        /*
         *abre modal para busca cliente
         **/
        $('#modalIniciaAtendimento').modal('show');
    });
    
    /*
     * Clique na TR da tabela lista de atendimento
     **/
    //$('#tblListaAtendimento > tbody > tr .linker, #tblListaAtendimento > aDetalhes').click(function(){
    $('#tblListaAtendimento > tbody > tr, #tblListaAtendimento').on('click', '.linker, .aDetalhes', function(){
        /* pega id */
        console.log($(this).parent().find('.tdProtocolo').html());
        //var idCliente = $(this).parent().find('.tdIdCliente').html();
        var protocolo = $(this).parent().find('.tdProtocolo').html();
        
        /*
         * busca dados do atendimento pelo id do cliente
         * */
        
        $.ajax({
            type: "POST",
            url: "inc/ajax_requests.php?action=buscaAtByProtocol",
            data: {protocolo: protocolo},
            dataType: "json",
            success: function(result){
                
                var res = result[0];
                console.log(res);
                
                /* tratando status */
                var status =  'P' == res.status ? '<span class="red">Pendente</span>'
                            : 'A' == res.status ?  '<span class="blue">Em andamento</span>'
                            : 'R' == res.status ? '<span class="grey">Resolvido</span>'
                            : '<span class="red">Pendente</span>';
               
                
                
                /* tratando servicos solicitados  neste atendimento */
                //var solicitados = '';
                //
                //solicitados += '1' == res.solicitado_nfe ? '<li>NFe - Nota Fiscal Eletrônica</li>' : '';
                //solicitados += '1' == res.solicitado_nfce ? '<li>NFCe - Nota Fiscal do Consumidor Eletrônica</li>' : '';
                //solicitados += '1' == res.solicitado_cupomfiscal ? '<li>Cupom Fiscal</li>' : '';
                //solicitados += '1' == res.solicitado_nfse ? '<li>NFSe - Nota de Serviço Eletrônica' : '';
                //solicitados += '1' == res.solicitado_cte ? '<li>CTe - Conhecimento de Transporte Eletrônico</li>' : '';
                //solicitados += '1' == res.solicitado_mdfe ? '<li>MDFe - Manifesto de Frete Eletrônico</li>' : '';
                //
                //solicitados = solicitados != '' ? '<ul>' + solicitados + '</ul>' : 'Nenhuma liberação solicitada.';
                
                /**
                 *preenche campos da modal com dados
                 **/
                $('#modalDetalhes .spanProtocolo').text(res.protocolo);
                $('#modalDetalhes .spanShowStatusModal').html(status);
                $('#modalDetalhes .spanDataHora').text(res.datahora);
                $('#modalDetalhes .spanSolicitanteDepto').text(res.nome_solicitante + ' / ' + res.depto_solicitante);
                $('#modalDetalhes .spanCodcliente').text(res.cod_cliente);
                $('#modalDetalhes .spanEmpresa').text(res.razao_cliente);
                $('#modalDetalhes .spanDeptoDestino').text(res.depto_destino);
                //$('#modalDetalhes .spanSolicitacoes ').html(solicitados);
                $('#modalDetalhes .spanAssunto ').text(res.assunto);
                $('#modalDetalhes .spanDescricao ').text(res.descricao);
                
                /* prenche inputs para manter os dados importantes */
                 $('#modalDetalhes input[name=iptProtocolo]').val(res.protocolo);
                 $('#modalDetalhes input[name=iptDepartamento]').val(res.subdepto_destino);
                 $('#modalDetalhes input[name=iptIdAtendimento]').val(res.id);
                 
                 /* se o status for resolvido esconde botao baixar */
                 
                 'R' == res.status ? $('#btnBaixarAtendimento').hide() : $('#btnBaixarAtendimento').show();
                 
                /*
                *mostra modal com detalhes do atendmento
                **/
                $('#modalDetalhes').modal('show');
                
            },
            error: function (error){
                console.log(error);
                
            }
        });

        
    });
    
    $('#selectAlteraStatus').change(function(e){
        
        var novoStatus = this.value;
        var protocolo = $('#modalDetalhes input[name=iptProtocolo]').val();
        
        var data = {
            'novoStatus' : novoStatus,
            'protocolo' : protocolo
        };
       
        $.ajax({
            type: "POST",
            url: "inc/ajax_requests.php?action=alteraStatus",
            data: data,
            dataType: "json",
            success: function(result){
                console.log('sucesso');
                console.log(result);
                
                /* pega alteração feita e esconde select e mostra div com o novo status */
                
                /* html do status */
                var htmlStatus = novoStatus == 'P' ? '<span class="red">Pendente</span>' : novoStatus == 'A' ? '<span class="blue">Em andamento</span>' : '<span class="red">Pendente</span>';
                
                /**
                 * * preenche novo status
                 */
                $('.spanShowStatusModal').html(htmlStatus);                
                /*
                *mostra span status
                **/
               $('.spanShowStatusModal').show();
               /*
                *essconde select pra mduar stattus
                **/
               $('.spanSelectStatusModal').hide();
                
            },
            error: function (error){
                console.log('erros');
                console.log(error);
                
            }
        });
        
    });
    
    $('#btnFecharModalDetalhes').click(function(){
       constructDataTable();
    });
    
    /*
     *clique em alterar status dentro da modal de detalhes
     **/
    $('.aAlterarStatus').click(function(){
        /*
         *esconde span status
         **/
        $('.spanShowStatusModal').hide();
        /*
         *mostra select pra mduar stattus
         **/
        $('.spanSelectStatusModal').show();
    });
    
    /*
     *clique no botao baixar atendimento dentro da modal detalhes
     **/
    $('#btnBaixarAtendimento').click(function(){
        /*
         *esconde modal detalhes
         **/
        $('#modalDetalhes').modal('hide');
        /*
         *pega protocolo
         **/
        $('#modalBaixarAtendimento input[name=iptProtocoloAt]').val($('#modalDetalhes input[name=iptProtocolo]').val());
        $('#modalBaixarAtendimento input[name=iptDeptoResp]').val($('#modalDetalhes input[name=iptDepartamento').val());
        /*
         *mostra modal de baixar atendimento
         **/
        $('#modalBaixarAtendimento').modal('show');
    });
    
    $('#btnImprimirAtendimento, #btnVerAtendimento').click(function(){
        var idOrdem = $('#modalDetalhes input[name=iptIdAtendimento]').val();
        location.href="ordem_visualizacao.php?idOrdem=" + idOrdem;
    });
    
    
    
    /* baixando atendimento */
    $('#btnModalBaixar').click(function(){
        
        var data = $('#modalBaixarAtendimento form[name=frmBaixa]').serialize();
        
        //console.log(data);
       
        $.ajax({
            type: "POST",
            url: "inc/ajax_requests.php?action=baixarAtendimento",
            data: data,
            dataType: "json",
            success: function(result){
                //console.log('sucesso');
                //console.log(result);
                console.log('aqueeee');
                /* fecha modal */
                $('#modalBaixarAtendimento').modal('hide');
                constructDataTable();
                
            },
            error: function (error){
                console.log('erros');
                console.log(error);
                
            }
        });
    });
    
    /* redirecionando atendimento */
    
    $('#btnModalRedirecionar').click(function(){
        
        var data = $('#modalRedirecionarAtendimento form[name=frmRedireciona]').serialize();
        
        //console.log(data);
       
        $.ajax({
            type: "POST",
            url: "inc/ajax_requests.php?action=redirAtendimento",
            data: data,
            dataType: "json",
            success: function(result){
                //console.log('sucesso');
                console.log(result);
                console.log('aqueeee');
                /* fecha modal */
                $('#modalRedirecionarAtendimento').modal('hide');
                constructDataTable();
                
                /* imprimir/enviar email */
                var idOrdem = $('#modalRedirecionarAtendimento input[name=iptIdAtendimento]').val();
                location.href="ordem_visualizacao.php?geracao=sim&idOrdem=" + idOrdem;
            },
            error: function (error){
                console.log('erros');
                console.log(error);
                
            }
        });
    });
    
    /* ao selecionar na busca a buscar pelo departamento destino
     * */
    
    $('select[name=iptTipoBuscar]').change(function(e){
        /* ao mudar, mostra o selecto dos departamentos */
        //console.log(this.value);
        if ('depto' == this.value) {
            /* msotra select se for depto */
            $('select[name=iptDepartamentos]').show();
            /* econde input dado p busca */
            $('input[name=iptDadoBuscar]').hide();
        } else {
            /* esconde select deptos */
            $('select[name=iptDepartamentos]').hide();
            /* mostra  input */
            $('input[name=iptDadoBuscar]').show();
        }
    });
    
    $('.aAlterarDestino').click(function(){
        /* esconde modal delathes */
        $('#modalDetalhes').modal('hide');
        /*
         *pega protocolo
         **/
        $('#modalRedirecionarAtendimento input[name=iptProtocoloAt]').val($('#modalDetalhes input[name=iptProtocolo]').val());
        $('#modalRedirecionarAtendimento input[name=iptDeptoResp]').val($('#modalDetalhes input[name=iptDepartamento').val());
        $('#modalRedirecionarAtendimento input[name=iptIdAtendimento]').val($('#modalDetalhes input[name=iptIdAtendimento').val());
        /* mostra modal redir */
        $('#modalRedirecionarAtendimento').modal('show');
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
    
});


function constructDataTable(){
    
    /* pegar dados da url se tiver */
    var codCliente = getParameterByName('codCliente') != '' ? '&codCliente=' + getParameterByName('codCliente') : '';
    
    /* busca */
    var tipoBuscar         = getParameterByName('iptTipoBuscar')    != '' ? '&tipoBuscar='         + getParameterByName('iptTipoBuscar')    : '';
    var dadoBuscar         = getParameterByName('iptDadoBuscar')    != '' ? '&dadoBuscar='         + getParameterByName('iptDadoBuscar')    : '';
    var buscarDepartamento = getParameterByName('iptDepartamentos') != '' ? '&buscarDepartamento=' + getParameterByName('iptDepartamentos') : '';
    
    console.log(codCliente + tipoBuscar + dadoBuscar + buscarDepartamento);
    
    $('#tblListaAtendimento').DataTable({
        "bDestroy": true,
        "aoColumns": [
                { "sClass": "linker tdProtocolo" },
                { "sClass": "linker" },
                { "sClass": "linker" },
                { "sClass": "linker" },
                { "sClass": "linker tdIdCliente" },
                { "sClass": "linker" },
                { "sClass": "linker" },
                { "sClass": "linker" },
                //{ "sClass": "txtcenter tdIcons" }
            ],
        "order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "inc/ajax_requests.php?action=lista_atendimentos" + codCliente + tipoBuscar + dadoBuscar + buscarDepartamento, // json datasource
            type: "post", // method  , by default get            
            error: function (error) {  // error handling
                console.log('ERROR');
                console.log(error);
                
                //$(".employee-grid-error").html("");
                //$("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
                //$("#employee-grid_processing").css("display", "none");
            }                
        },
    });
}

/* bsuca departamentos para o select da busca de departamentos */

//function buscaDepartamentos(){
//    
//    var data = $('form[name=frmBuscaAtendimentos]').serialize();
//    
//    $.ajax({
//        type: "POST",
//        url: "inc/ajax_requests.php?action=buscaSubDepartamentos",
//        data: data,
//        dataType: "json",
//        success: function(result){
//            //console.log('sucesso');
//            console.log(result);
//            console.log('aqueeee');
//            /* fecha modal */
//            //$('#modalBaixarAtendimento').modal('hide');
//            //constructDataTable();
//            
//            $.each(result, function(k, v){
//                //console.log(k + '>' + v);
//                $.each(v, function(x, y){
//                    console.log(x + '>>' + y);
//                });
//            });
//            //$('#iptDepartamentos').append('\n\
//            //                              <option>\n\
//            //                              ');
//            
//        },
//        error: function (error){
//            console.log('erros');
//            console.log(error);
//            
//        }
//    });
//    
//    
//    
//}



/* pega valor da url como $GET do php */
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}