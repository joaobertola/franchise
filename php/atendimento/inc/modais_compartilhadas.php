<script src="js/modais_compartilhadas.js"></script>
<!-- modal inciar novo atendimento -->
<div id="modalIniciaAtendimento" class="modal fade modalMinmin">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">
                    Nova Ordem de Atendimento
                </h4>
            </div>
            <div class="modal-body"> <!-- corpo da modal -->
                <div class="row">
                    <div class="col-md-12">
                        <label form="iptBuscaIdCliente">Código do cliente:</label>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <input type="text" name="iptBuscaIdCliente" class="form-control" placeholder="Digite o código do cliente e pressione ENTER" />
                    </div>
                </div>
                <div class="row divErroResultado" style="display: none">
                    <div class="col-md-12">
                        <p class="pError red"></p>
                    </div>
                </div>
                <div class="row divResultadoCliente" style="display: none">
                    <div class="col-md-12">
                        <h5>Cliente encontrado:</h5>
                        <strong>ID:</strong> <span class="spanIdCliente"></span>
                        <br/><strong>Razão Social:</strong> <span class="spanRazaoCliente"></span>
                        <br/><strong>CPF / CNPJ:</strong> <span class="spanCnpjCliente"></span>
                        <form name="frmDadosCliente" method="post" action="novo_atendimento.php">
                            <input type="hidden" name="iptIdCliente" />
                        </form>
                    </div>
                    <div class="col-md-12 margin-top-10">
                        <button type="button" class="btn btn-primary right" id="btnNovaOrdem">
                            Gerar Ordem de Atendimento para Este Cliente.
                        </button>
                    </div>
                </div>
            </div> <!-- fim do corpo da modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="">
                    Cancelar
                </button>
                <!--<button type="button" class="btn btn-primary" id="btnNovaOrdem" disabled="disabled">
                    Gerar Ordem de Atendimento
                </button>-->
            </div>
        </div>
    </div>
</div>