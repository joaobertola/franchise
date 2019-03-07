$(document).ready(function(){
    
    /*
     *busca por cliente via id na modal de iniciar nova ordem de atediment
     **/
    
    $('input[name=iptBuscaIdCliente]').keydown(function(e){
        e.stopPropagation();
        var keyCode = e.keyCode || e.charCode || e.which;
        
        switch (keyCode) {
            case 13:/* asterisco */
            
                console.log('enter');
                /* faz ajax */
                
                var idCliente = $('#modalIniciaAtendimento input[name=iptBuscaIdCliente]').val();
                                
                $.ajax({
                    type: "POST",
                    url: "inc/ajax_requests.php?action=buscaClienteById",
                    data: {idCliente: idCliente},
                    dataType: "json",
                    success: function(result){
                        console.log(result);
                        if (result.length > 0) {
                            /*
                             * esconde div erro
                             * */
                            $('#modalIniciaAtendimento .divErroResultado').hide();
                            /*
                             *apaga texto caso exista
                             **/
                            $('#modalIniciaAtendimento .divErroResultado .pError').text('');
                            /*
                             *mostra bloco com dados do cliente
                             **/
                            $('#modalIniciaAtendimento .divResultadoCliente').show();
                            
                            /*
                             *preenche dados do cliente
                             **/
                            var razaosocial = result[0].razaosoc;
                            if (!razaosocial) {
                                razaosocial = 'Não encontrado.'
                            }
                            var cpfcnpj = result[0].cpfcnpj_doc;
                            if (!cpfcnpj) {
                                cpfcnpj = 'Não encontrado.'
                            }
                            
                            $('#modalIniciaAtendimento .divResultadoCliente .spanIdCliente').text(result[0].codloja);
                            $('#modalIniciaAtendimento .divResultadoCliente .spanRazaoCliente').text(razaosocial);
                            $('#modalIniciaAtendimento .divResultadoCliente .spanCnpjCliente').text(cpfcnpj);
                            
                            /*
                             *colocar dados em form para enviar a outra pagina
                             */
                            $('#modalIniciaAtendimento .divResultadoCliente input[name=iptBuscaIdCliente').val();
                            $('#modalIniciaAtendimento .divResultadoCliente input[name=iptIdCliente]').val(result[0].codloja);
                            
                                
                        } else {
                            /*
                            * esconde row resultado
                            * */
                           $('#modalIniciaAtendimento .divResultadoCliente').hide();
                           /*
                            * mostra linha de erro
                            * */
                           $('#modalIniciaAtendimento .divErroResultado').show();
                           $('#modalIniciaAtendimento .divErroResultado .pError').text(result['mensagem'] + "\n ID: " + result['idcliente']);
                        }
                    },
                    error: function (error){
                        console.log('erro lista-atendimento-func.js');
                        console.log(error);
                        
                    }
                });
                
            break;
        }
        
    });
    
    $('#btnNovaOrdem').click(function(){
        /*
         *fecha modal id cliente inicia atendiment
         **/
        $('#modalIniciaAtendimento').modal('hide');
        
        /*
         *submete formulario com os dados do cliente selecionado
         **/
        $('form[name=frmDadosCliente]').submit();
        
    });
    
});