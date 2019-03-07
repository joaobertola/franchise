<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<style>
    .titulo {
        width:100%;
        font-weight: bold;
        font-size: 10pt;
        color: black;
        font-family: Arial;
        height: 20px;
        background-color: #CCCCCC;
        text-align: center;
        text-transform:uppercase;
    }
    .titulo h1 {
        font-size:20px;
    }
    table {
        width:100%;
        display: table;
        border-collapse: separate;
        border-spacing: 2px;
        border-color: grey;
    }
    tbody {
        display: table-row-group;
        vertical-align: middle;
        border-color: inherit;
    }
    .label {
        width:25%;
        text-align:right;
        font-size: 8pt;
        color: black;
        font-family: Arial;
        background-color: rgba(1,124,194,0.8);
        padding-right: 5px;
    }
    .campo {
        width:75%;
        font-size: 8pt;
        color: black;
        font-family: Arial;
        background-color: lightsteelblue;
        text-align: left;
        padding-left: 5px;
    }
    .campo input, .campo select {
        width:50%;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form action="" id="form-devolucao-correspondencia-correios" onsubmit="return altera_cliente();">
                <table>
                    <tbody>
                        <tr>
                            <td colspan="2" class="titulo">Devolução de Correspondência Correios</td>
                        </tr>
                        <tr>
                            <td class="label">Cliente</td>
                            <td class="campo"><input type="text" name="cliente" id="cliente" placeholder="Cliente" onkeyup="busca_cliente();" required></td>
                        </tr>
                        <!-- INICIO CARREGA DADOS CLIENTE -->
                        <tr>
                            <td class="label">&nbsp;</td>
                            <td class="campo">
                                <div class="form-group mt-2" id="carrega_dados_cliente" style="display:none;">
                                    <div><b>Razão Social:&nbsp;</b><span id="razao_social"></span></div>
                                    <div><b>Fantasia:&nbsp;</b><span id="fantasia"></span></div>
                                    <div><b>Cidade/UF:&nbsp;</b><span id="cidade_uf"></span></div>
                                    <div>
                                        <b>Telefones:&nbsp;</b>
                                        <span id="telefones">
                                            <span id="fone">Fone</span> |
                                            <span id="fax">Fax</span> |
                                            <span id="celular">Celular</span> |
                                            <span id="residencial">Residencial</span>
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- FIM CARREGA DADOS CLIENTE -->
                        <tr>
                            <td class="label">Atendente</td>
                            <td class="campo"><input type="text" name="atendente" id="atendente" placeholder="Atendente" required></td>
                        </tr>
                        <tr>
                            <td class="label">Data Devolução</td>
                            <td class="campo"><input type="text" name="data_devolucao" id="data_devolucao" placeholder="dd/mm/aaaa" onkeyup="mascara(this, mdata);" value="<?php echo date('d/m/Y') ?>" required></td>
                        </tr>
                        <tr>
                            <td class="label">Tipo de Documento</td>
                            <td class="campo"><input type="text" name="tipo_documento" id="tipo_documento" placeholder="Tipo do Documento" value="Boleto de Mensalidade" required></td>
                        </tr>
                        <tr>
                            <td class="label">Motivo Devolução</td>
                            <td class="campo">
                                <select name="motivo_devolucao" id="motivo_devolucao" required>
                                    <option value="0">Selecione</option>
                                    <option value="1">Mudou-se</option>
                                    <option value="2">Endereço Insuficiente</option>
                                    <option value="3">Não Existe o Nº Indicado</option>
                                    <option value="4">Desconhecido</option>
                                    <option value="5">Não Procurado</option>
                                    <option value="6">Ausente</option>
                                    <option value="7">Falecido</option>
                                    <option value="8">Recusado</option>
                                    <option value="9">Outros</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Contato Realizado?</td>
                            <td class="campo">
                                <select name="entrado_contato" id="entrado_contato" onchange="habilita_nome_contato(this.value);" required>
                                    <option value="0">Selecione</option>
                                    <option value="S">Sim</option>
                                    <option value="N" selected>Não</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Nome do Contato</td>
                            <td class="campo"><input type="text" name="falado_com" id="falado_com" placeholder="Nome do Contato" required></td>
                        </tr>
                        <!-- ATUALIZAÇÃO DE DADOS -->
                        <tr>
                            <td class="label">CEP</td>
                            <td class="campo"><input type="text" name="cep" id="cep" placeholder="CEP" required></td>
                        </tr>                        
                        <tr>
                            <td class="label">Endereço</td>
                            <td class="campo"><input type="text" name="endereco" id="endereco" placeholder="Endereço" required></td>
                        </tr>
                        <tr>
                            <td class="label">Número</td>
                            <td class="campo"><input type="text" name="numero" id="numero" placeholder="Número" required></td>
                        </tr>
                        <tr>
                            <td class="label">Bairro</td>
                            <td class="campo"><input type="text" name="bairro" id="bairro" placeholder="Bairro" required></td>
                        </tr>
                        <tr>
                            <td class="label">Cidade</td>
                            <td class="campo"><input type="text" name="cidade" id="cidade" placeholder="Cidade" required></td>
                        </tr>
                        <tr>
                            <td class="label">UF</td>
                            <td class="campo"><input type="text" name="uf" id="uf" placeholder="UF" required></td>
                        </tr>
                        <tr>
                            <td class="label">E-mail</td>
                            <td class="campo"><input type="text" name="email" id="email" placeholder="E-mail" onblur="valida_email();" required></td>
                        </tr>
                        <tr>
                            <input type="hidden" name="action" value="alterar_cliente">
                            <input type="hidden" name="codloja" id="codloja" >
                            <td class="label">&nbsp;</td>
                            <td class="campo"><button>Confirmar Alteração de Dados</button></td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<script>

    var consulta_cep = false;
    $('#cliente').focus();

    function limpa_formulário_cep() {
        // Limpa valores do formulário de cep.
        $("#rua").val("");
        $("#bairro").val("");
        $("#cidade").val("");
        $("#uf").val("");
        $("#ibge").val("");
    }

    function formata_numero_telefone(numero) {
        if(numero.length == 10){
            return '(' + numero.substring(0,2) + ') ' + numero.substring(2,6) + '-' + numero.substring(6,10);    
        }else if(numero.length == 11){
            return '(' + numero.substring(0,2) + ') ' + numero.substring(2,7) + '-' + numero.substring(7,11);
        }
        
        return numero;
    }
    
    //Quando o campo cep perde o foco.
    function pesquisa_cep() {

        consulta_cep = true;

        if(consulta_cep == true) {
            //Nova variável "cep" somente com dígitos.
            var cep = $("#cep").val().replace(/\D/g, '');

            //Verifica se campo cep possui valor informado.
            if (cep != "") {

                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;

                //Valida o formato do CEP.
                if(validacep.test(cep)) {

                    //Preenche os campos com "..." enquanto consulta webservice.
                    $("#endereco").val("...");
                    $("#bairro").val("...");
                    $("#cidade").val("...");
                    $("#uf").val("...");

                    //Consulta o webservice viacep.com.br/
                    $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                        if (!("erro" in dados)) {
                            //Atualiza os campos com os valores da consulta.
                            $("#endereco").val(dados.logradouro);
                            $("#bairro").val(dados.bairro);
                            $("#cidade").val(dados.localidade);
                            $("#uf").val(dados.uf);
                        } //end if.
                        else {
                            //CEP pesquisado não foi encontrado.
                            limpa_formulário_cep();
                            alert("CEP não encontrado.");
                        }
                    });
                } //end if.
                else {
                    //cep é inválido.
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        }

    };

    // VALIDA E-MAIL
    function valida_email() {
        var email = $("#email").val();
        var usuario = email.substring(0, email.indexOf("@"));
        var dominio = email.substring(email.indexOf("@")+ 1, email.length);
        
        if ((usuario.length >=1) &&
            (dominio.length >=3) && 
            (usuario.search("@")==-1) && 
            (dominio.search("@")==-1) &&
            (usuario.search(" ")==-1) && 
            (dominio.search(" ")==-1) &&
            (dominio.search(".")!=-1) &&      
            (dominio.indexOf(".") >=1)&& 
            (dominio.lastIndexOf(".") < dominio.length - 1)) {
                return true;
        }
        else {
            alert("E-mail inválido");
            email.focus();
            return false;
        }
    }

    // MASCARA DATA
    function mascara(o,f) {
        v_obj = o;
        v_fun = f;
        setTimeout("execmascara()",1);
    }
    function execmascara() {
        v_obj.value=v_fun(v_obj.value);
    }
    function mdata(v) {
        v=v.replace(/\D/g,"");
        v=v.replace(/(\d{2})(\d)/,"$1/$2");
        v=v.replace(/(\d{2})(\d)/,"$1/$2");

        v=v.replace(/(\d{2})(\d{2})$/,"$1$2");
        return v;
    }

    // HABILITA NOME CONTATO
    // function habilita_nome_contato(entrado_contato) {
    //     if(entrado_contato == "S") {
    //         $('#falado_com').prop("disabled", false);
    //     } else {
    //         $('#falado_com').prop("disabled", true);
    //     }
    // }
    
    var request = false;

    // BUSCA CLIENTE
    function busca_cliente() {
        var cliente_pesq = $('#cliente').val();
        var id_franquia = <?php echo $_SESSION['id'];?>;
        if(cliente_pesq.length > 4) {
            if(request){
                window.ajax_.abort();
            }
            window.ajax_ = $.ajax({
                url : './devolucao/devolucao_clientes.php',
                method: 'post',
                data: {"login":cliente_pesq,"action":"pesquisar_cliente","idFranquia":id_franquia},
                dataType: 'json',
                success: function(json_data) {
                    console.log(json_data);
                    $('#carrega_dados_cliente').css('display','block');
                    $('#codloja').val(json_data.codloja);
                    $('#razao_social').html(json_data.razaosoc);
                    $('#fantasia').html(json_data.nomefantasia);
                    $('#cidade_uf').html(json_data.cidade+'/'+json_data.uf);
                    $('#fone').html('Principal: '+formata_numero_telefone(json_data.fone));
                    $('#fax').html('Fax: '+formata_numero_telefone(json_data.fax));
                    $('#celular').html('Celular: '+formata_numero_telefone(json_data.celular));
                    $('#residencial').html('Residencial: '+formata_numero_telefone(json_data.fone_res));
                    // Endereço
                    $('#cep').val(json_data.cep);
                    $("#endereco").val(json_data.end);
                    $("#bairro").val(json_data.bairro);
                    $("#cidade").val(json_data.cidade);
                    $("#uf").val(json_data.uf);
                    $('#numero').val(json_data.numero);
                    // Fim Endereço
                    $('#email').val(json_data.email);
                    $('#atendente').focus();
                    //pesquisa_cep();
                },
                error:function(XMLHttpRequest, textStatus, errorThrown) { 
                   alert('ATENÇÃO !!! -> Cliente não encontrado ou não pertence a sua FRANQUIA' ); 
                }

            });
            request = true;
        }
    }

    // ALTERA CLIENTE
    function altera_cliente() {
        var dados = $('#form-devolucao-correspondencia-correios').serialize();
        $.ajax({
            url : './devolucao/devolucao_clientes.php',
            method: 'POST',
            data: dados,
            success: function(data) {
                //console.log(data);
                if(data == 1) {
                    $('#cliente').val('');
                    $('#atendente').val('');
                    $('#carrega_dados_cliente').html('');
                    $('#carrega_dados_cliente').css('display','none');
                    $('#motivo_devolucao').val('0');
                    $('#entrado_contato').val('N');
                    $('#falado_com').val('');
                    $('#cep').val('');
                    $("#endereco").val('');
                    $("#bairro").val('');
                    $("#cidade").val('');
                    $("#uf").val('');
                    $('#numero').val('');
                    $('#email').val('');
                    alert("Os dados foram atualizados com sucesso!");
                } else {
                    alert("Erro ao atualizar os dados!");
                }
            }
        });
        return false;
    }

</script>