<!-- modal detalhes o atendimento -->
<div id="modalDetalhes" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">
                    Ver Detalhes Atendimento
                </h4>
            </div>
            <div class="modal-body"> <!-- corpo da modal -->
                
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="iptProtocolo" />
                        <table class="table table-bordered table-striped margin-top-10">
                            <tr>
                                <th>
                                    Ordem No.:
                                </th>
                                <th>
                                    <span class="spanProtocolo"></span>
                                    <input type="hidden" name="iptProtocolo" class="form-control" />
                                    <input type="hidden" name="iptIdAtendimento" class="form-control" />
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    Status do Atendimento:
                                </td>
                                <td>
                                    <span class="spanShowStatusModal red"></span>
                                    <span class="spanSelectStatusModal" style="display:none">
                                        <select class="selectpicker" name="iptAlteraStatus" id="selectAlteraStatus">
                                            <option value="0"> Selecione </option>
                                            <option value="A"> Em andamento </option>
                                            <option value="P"> Pendente </option>
                                        </select>
                                    </span>
                                    <a class="aAlterarStatus right"><span class="glyphicon glyphicon-pencil" title="Alterar Status" alt="Alterar Status"></span> Alterar Status</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Data/Hora:
                                </td>
                                <td>
                                    <span class="spanDataHora"></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Solicitante / Depto:
                                </td>
                                <td>
                                    <span class="spanSolicitanteDepto"></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Cód. Cliente:
                                </td>
                                <td>
                                    <span class="spanCodcliente"></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Empresa:
                                </td>
                                <td>
                                    <span class="spanEmpresa"></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Depto. Destino:
                                </td>
                                <td>
                                    <span class="spanDeptoDestino"></span>
                                    <input type="hidden" name="iptDepartamento" class="form-control" />
                                    <a class="aAlterarDestino right"><span class="glyphicon glyphicon-send" title="Alterar Destino" alt="Alterar Destino"></span> Alterar Destino</a>
                                </td>
                            </tr>
                            <!--<tr>-->
                            <!--    <td>-->
                            <!--        Liberação de Módulos:-->
                            <!--    </td>-->
                            <!--    <td>-->
                            <!--        <span class="spanSolicitacoes">-->
                            <!--        </span>-->
                            <!--    </td>-->
                            <!--</tr>-->
                            <tr>
                                <td>
                                    Assunto:
                                </td>
                                <td>
                                    <span class="spanAssunto"></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Descrição:
                                </td>
                                <td>
                                    <span class="spanDescricao"></span>
                                </td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button id="btnBaixarAtendimento" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-down"></span> Baixar Atendimento</button>
                        <button id="btnVerAtendimento" class="btn btn-primary"><span class="glyphicon glyphicon-eye-open"></span> Ver Detalhes</button>
                        <button id="btnImprimirAtendimento" class="btn btn-primary right"><span class="glyphicon glyphicon-print"></span> Imprimir Atendimento</button>
                    </div>
                </div>
            </div> <!-- fim do corpo da modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btnFecharModalDetalhes">
                    Fechar
                </button>
                <!--<button type="button" class="btn btn-primary" id="btnConfirmaAlteracao">-->
                <!--    Confirma Alteração-->
                <!--</button>-->
            </div>
        </div>
    </div>
</div>

<!-- modal mudar Baixar atendimento -->
<div id="modalBaixarAtendimento" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">
                    Baixar Atendimento
                </h4>
            </div>
            <div class="modal-body"> <!-- corpo da modal -->
                <form name="frmBaixa">
                    <input type="hidden" name="iptProtocoloAt" class="form-control" />
                    <input type="hidden" name="iptDeptoResp" class="form-control" />
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label form="iptResponsavelBaixa">Responsável pela Baixa:</label>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <input type="text" name="iptResponsavelBaixa" class="form-control" />
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label form="iptDescricaoBaixa">Descreva detalhes da resolução do problema:</label>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <textarea name="iptDescricaoBaixa" class="form-control" row="5" ></textarea>
                        </div>
                    </div>
                </form>
            </div> <!-- fim do corpo da modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btnFecharModalDetalhes">
                    Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btnModalBaixar">
                    Baixar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- modal mudar destino -->
<div id="modalRedirecionarAtendimento" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">
                    Redirecionar Atendimento
                </h4>
            </div>
            <div class="modal-body"> <!-- corpo da modal -->
                <form name="frmRedireciona">
                    <input type="hidden" name="iptProtocoloAt" class="form-control" />
                    <input type="hidden" name="iptDeptoResp" class="form-control" />
                    <input type="hidden" name="iptIdAtendimento" class="form-control" />
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label form="iptResponsavelRedir">Responsável pelo Redirecionamento:</label>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <input type="text" name="iptResponsavelRedir" class="form-control" />
                        </div>
                    </div>
                    
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label form="iptDeptoRedir">Departamento:</label>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            
                            <?php
                            foreach($groupedDeparts as $depart => $items){
                               
                               /* aqui faz a principal
                                */
                               echo '
                                   <div class="row radio">
                                       <div class="col-md-12">
                                           <div class="superItem">
                                               <a id="item' . $depart . '"><strong><span class="glyphicon glyphicon-share-alt"></span> ' . $depart . '</strong></a>
                                           </div>
                                       </div>
                                   </div>
                               ';
                               
                               /* dentro do outro foreach monta os radios*/
                               echo '<div class="item' . $depart . '" style="display:none">';
                               //echo '<pre>';
                               //print_r($item);
                               //echo '<pre>';
                               foreach($items as $item){
                                   echo '
                                       <div class="row radio">
                                           <div class="col-md-offset-1 col-md-10">
                                               <label>
                                                   <input type="radio" name="iptDestino" value="' . $item['subdeptoid']. '" class="radio" />
                                                   ' . $item['nome_subdepartamento']. '
                                               </label>
                                           </div>
                                       </div>
                                   ';
                               }
                               
                               echo '</div>';
                               
                               
                            }
                           ?>
                        </div>
                    </div>
                    
                    
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label form="iptDescricaoRedir">Motivo do redirecionamento/orientações:</label>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <textarea name="iptDescricaoRedir" class="form-control" row="5" ></textarea>
                        </div>
                    </div>
                </form>
            </div> <!-- fim do corpo da modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btnFecharModalDetalhes">
                    Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btnModalRedirecionar">
                    Redirecionar
                </button>
            </div>
        </div>
    </div>
</div>