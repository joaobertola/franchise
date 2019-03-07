$(document).ready(function(){

    /* envia por email e impressao se vier da geração
     * */
    
    var enviarPorEmail = getParameterByName('geracao');
    // se existir esse parametro quer dizer que veio da geração entao envia por email,
    //isso e necessario porque essa pagina tb e usada pra visualizar somente
    if(enviarPorEmail) {
        enviarEmail();
        ImprimitOA();
    }
    
    
    /* clique no btn para imprimir */
    $('button[name=btnImprimirOrdem]').click(function(){
        ImprimitOA();
    });
    
    /* clique no btn para enviar pro email */
    $('button[name=btnEmailOrdem]').click(function(){
        enviarEmail();
    });
    
    /*
     *comando control + p
     **/
    
    $(document).keydown(function(e){
	
	var keyCode = e.keyCode || e.charCode || e.which;
	
	/* se pressionado control and tecla P (80) */
	if (e.ctrlKey && e.keyCode == 80) {
	    /* previne compotamento padrao que seria imprmir toda a pagina */
	    e.preventDefault();
	    /* chama funcao de imprimir relatorio */
	    ImprimitOA();
	}
	
    });
    
    
});

function ImprimitOA(){
    
    function Popup(data, style){
        var mywindow = window.open('', '.documentoViewToPrint');
        mywindow.document.write('<html><head><title></title>');
        /* stylesheet*/
        mywindow.document.write('<style>' + style + '</style>');
	
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
     
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10
	  
        setTimeout(function () {
             mywindow.print();
             mywindow.close();
        }, 100); //1 segundo
     
        return true;
    }
    /* estilo da impressao */
    var style = 'body{font-size:10px}\n\
                 h4{font-size:22px;text-align:center}\n\
                 table{font-size:10px;margin-top:10px;}\n\
                .table-bordered, .table-bordered td { \n\
                    border: 1px solid #ccc;\n\
                  }\n\
                  .table {\n\
                    width: 100%;\n\
                    max-width: 100%;\n\
                    margin-bottom: 0px;\n\
                    font-size:10px;\n\
                    }\n\
                    .fontsize20{\n\
                    font-size:20px;\n\
                    }\n\
                  table{ \n\
                    border-spacing: 0;\n\
                    border-collapse: collapse;\n\
                    }\n\
                    table td{\n\
                    padding:2px !important;}\n\
                    .txtcenter{text-align:center}  \n\
                    .noborder, .noborder td{border:0}\n\
                    .noborderOut{border:0}\n\
                    body{font-size:11px;color:#333;font-family:arial,sans-serif}\n\
                    .documentoViewToPrint table tr td {\n\
                        padding: 5px 10px;\n\
                    }\n\
                table tr th{\n\
                    background: #cccccc;\n\
                    font-weight: bold;\n\
                    }\n\
                .divDescricaoProblema{\n\
                    border: 1px solid #ccc;\n\
                    padding: 10px;\n\
                    margin: 10px 0;\n\
                }\n\
                .bold{font-weight:bold;}\n\
                .glyphicon {\n\
                    position: relative;\n\
                    top: 1px;\n\
                    display: inline-block;\n\
                    font-family: "Glyphicons Halflings";\n\
                    font-style: normal;\n\
                    font-weight: 400;\n\
                    line-height: 1;\n\
                    -webkit-font-smoothing: antialiased;\n\
                    -moz-osx-font-smoothing: grayscale;\n\
                }\n\
                .glyphicon-ok:before {\n\
                    content: url("imgs/ok.png");\n\
                }\n\
                .glyphicon-remove:before {\n\
                    content: url("imgs/remove.png");\n\
                }\n\
                "';        
     
    Popup( $(".documentoViewToPrint").html(), style );
     
}

function enviarEmail() {
    
    //console.log($(".documentoViewToPrint").html());
    var style = '<style>body{font-size:10px}\n\
                 h4{font-size:22px;text-align:center}\n\
                 table{font-size:10px;margin-top:10px;}\n\
                .table-bordered, .table-bordered td { \n\
                    border: 1px solid #ccc;\n\
                  }\n\
                  .table {\n\
                    width: 100%;\n\
                    max-width: 100%;\n\
                    margin-bottom: 0px;\n\
                    font-size:10px;\n\
                    }\n\
                    .fontsize20{\n\
                    font-size:20px;\n\
                    }\n\
                  table{ \n\
                    border-spacing: 0;\n\
                    border-collapse: collapse;\n\
                    }\n\
                    table td{\n\
                    padding:2px !important;}\n\
                    .txtcenter{text-align:center}  \n\
                    .noborder, .noborder td{border:0}\n\
                    .noborderOut{border:0}\n\
                    body{font-size:11px;color:#333;font-family:arial,sans-serif}\n\
                    .documentoViewToPrint table tr td {\n\
                        padding: 5px 10px;\n\
                    }\n\
                table tr th{\n\
                    background: #cccccc;\n\
                    font-weight: bold;\n\
                    }\n\
                .divDescricaoProblema{\n\
                    border: 1px solid #ccc;\n\
                    padding: 10px;\n\
                    margin: 10px 0;\n\
                }\n\
                .bold{font-weight:bold;}\n\
                .glyphicon {\n\
                    position: relative;\n\
                    top: 1px;\n\
                    display: inline-block;\n\
                    font-family: "Glyphicons Halflings";\n\
                    font-style: normal;\n\
                    font-weight: 400;\n\
                    line-height: 1;\n\
                    -webkit-font-smoothing: antialiased;\n\
                    -moz-osx-font-smoothing: grayscale;\n\
                }\n\
                .glyphicon-ok:before {\n\
                    content: url("imgs/ok.png");\n\
                }\n\
                .glyphicon-remove:before {\n\
                    content: url("imgs/remove.png");\n\
                }\n\
                </style>'; 
    
    var data = {
        'html': $(".documentoViewToPrint").html(),
        'protocolo': $(".documentoViewToPrint input[name=iptProtocoloEnviar]").val(),
        'style': style
        };
    //console.log(data);
    
    //mostra icone de processamento de envio
    $('.processandoEmail').show();
    /* enviado com sucesso esconde */
    $('.processadoEmail').hide();
    
    $.ajax({
            url:'inc/envia-email.php',
            type:'POST',
            data:data,
            dataType: 'json',
            success:function(data) {
                  console.log(data);
                  //esconde icone de processamento
                  
                  $('.processandoEmail').hide();
                  // mostra email enviado
                  $('.processadoEmail').text(data.resultado);
                  $('.processadoEmail').show();
            },
            error:function(e){
                /* mostra erro */
                $('.processadoEmailErro').show();
                /* debug pourpose */
                console.log(e);
            }
      });
    
}



/* pega valor da url como $GET do php */
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}







