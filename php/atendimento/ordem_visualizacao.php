<?php
    include_once('header.php');
    
    
    require_once ( 'classes/DbConnection.class.php' );
    
    require_once('classes/NovoAtendimento.class.php');
    
    //$idCliente = $_POST['iptIdCliente'];
    $idOrdem = $_REQUEST['idOrdem'];
    
    if($idOrdem){
        try{
            $objOrdem = new NovoAtendimento();
            $dadosOrdem = $objOrdem->buscaOrdemById($idOrdem);
            $dadosOrdem = $dadosOrdem[0];
            //echo "<pre>";
            //print_r( $dadosOrdem );
            //echo "</pre>";
            
            $dadosClientes = $objOrdem->buscaCliente($dadosOrdem['cod_cliente']);
            $logonCliente = $dadosClientes[0][0];
            $siteCliente = $dadosClientes[1][0];
            $dadosCliente = $dadosClientes[2][0];;
            //echo "<pre>";
            //print_r( $dadosClientes );
            //echo "</pre>";
            
            $historicoCliente = $objOrdem->buscaHistoricoCliente($dadosOrdem['cod_cliente']);
            //$historicoCliente = $historicoCliente[0];
            //echo "<pre>";
            //print_r( $historicoCliente );
            //echo "</pre>";
            
            //busca historico desta ordem de atendimento
            //buscaHistoricoOrdem
            //$historicoOrdem = $objOrdem->buscaHistoricoOrdem($idOrdem);
            //echo "<pre>";
            //print_r( $historicoOrdem );
            //echo "</pre>";
            
        } catch (Exception $e){
            echo $e;
        }
        
    }

?>
<script src="js/ordem-visualizacao.js"></script>
<article class="row">
    <section class="col-md-offset-2 col-md-8 bwell">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <h2>
                            Ordem de Atendimento Gerada (<?=$dadosOrdem['protocolo']?>)
                        </h2>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-offset-1 col-md-10 documentoViewToPrint">
                        <table class="table" style="width: 100%">
                            <tr>
                                <td colspan="2" class="noborder">
                                    <h4>
                                        Ordem de Atendimento No. <?=$dadosOrdem['protocolo']?>
                                        <input type="hidden" name="iptProtocoloEnviar" value="<?=$dadosOrdem['protocolo']?>" />
                                    </h4>
                                </td>
                            </tr>
                            <tr>
                                <td width="25%" class="noborder">
                                    <strong>Data de Emissão:</strong>
                                </td>
                                <td class="noborder">
                                    <?=$dadosOrdem['datahora']?>
                                </td>
                            </tr>
                            <tr>
                                <td class="noborder">
                                    <strong>Solicitante / Departamento: </strong>
                                </td>
                                <td class="noborder">
                                    <?php echo $dadosOrdem['nome_solicitante'] . ' / ' . $dadosOrdem['depto_solicitante'];?>
                                </td>
                            </tr>
                            <tr>
                                <td class="noborder">
                                    <strong>Departamento Destino: </strong>
                                </td>
                                <td class="noborder">
                                    <?=$dadosOrdem['depto_destino']?>
                                </td>
                            </tr>
                            <tr>
                                <td class="noborder">
                                   <strong> Assunto: </strong>
                                </td>
                                <td class="noborder">
                                    <?=$dadosOrdem['assunto']?>
                                </td>
                            </tr>
                            <tr>
                                <td class="noborder"  colspan="2">
                                    <strong>Descrição do Problema: </strong>
                                    <br/>
                                    <div class="divDescricaoProblema">
                                        <?=$dadosOrdem['descricao']?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border-top:0px;">
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <td colspan="2"><strong>DADOS DO CLIENTE</strong></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>ID</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['codloja'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Funcionário Franquia</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['nome_franquia'];?>
                                            </td>
                                        </tr>
                                        <tr class="blue bold">
                                            <td>
                                                <strong>Código de Cliente</strong>
                                            </td>
                                            <td>
                                                <?=$logonCliente['logon'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Razão Social</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['razaosoc'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>CNPJ</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['cpfcnpj_doc'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Endereço</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['end'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Número</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['numero'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Complemento</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['complemento'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Bairro</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['bairro'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>UF</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['uf'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Cidade</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['cidade'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Cep</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['cep'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Telefone</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['fone'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Fax</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['fax'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Celular</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['celular'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Telefone Residencial</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['fone_res'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Site Virtual Flex</strong>
                                            </td>
                                            <td>
                                                <?=$siteCliente['siteurl'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                               <strong> E-mail</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['email'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Proprietário 1</strong>
                                            </td>
                                            <td>
                                                Nome: <?=$dadosCliente['socio1'];?>
                                                <br/>CPF 1: <?=$dadosCliente['cpfsocio1'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Proprietário 2</strong>
                                            </td>
                                            <td>
                                                Nome: <?=$dadosCliente['socio2'];?>
                                                <br/>CPF 2: <?=$dadosCliente['cpfsocio2'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Segmento Empresarial</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['ramo_atividade'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Vendedor</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['vendedor'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Franqueado</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['nome_franquia'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Data Afiliação</strong>
                                            </td>
                                            <td>
                                                <?=$dadosCliente['dt_cad'];?>
                                            </td>
                                        </tr>
                                        
                                    </table>
                                    
                                    <!-- TABELA SOLICITAÇÃO DE USO -->
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <th class="txtcenter" colspan="2">
                                                SOLICITAÇÃO DE USO
                                                <br/>NFe - NFCe - Cupom Fiscal - NFSe - CTe - MDFe
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Módulos Liberados</strong>
                                            </td>
                                            <td>
                                                <?php
                                                    echo $dadosOrdem['solicitado_nfe'] == 1 ?         '<span class="glyphicon glyphicon-ok green"></span> NFe - Nota Fiscal Eletrônica (Liberado)<br/>'                : '<span class="glyphicon glyphicon-remove red""></span> NFe - Nota Fiscal Eletrônica<br/>';
                                                    echo $dadosOrdem['solicitado_nfce'] == 1 ?        '<span class="glyphicon glyphicon-ok green"></span> NFCe - Nota Fiscal do Consumidor Eletrônica (Liberado)<br/>' : '<span class="glyphicon glyphicon-remove red""></span> NFCe - Nota Fiscal do Consumidor Eletrônica<br/>';
                                                    echo $dadosOrdem['solicitado_cupomfiscal'] == 1 ? '<span class="glyphicon glyphicon-ok green"></span> Cupom Fiscal (Liberado)<br/>'                                : '<span class="glyphicon glyphicon-remove red""></span> Cupom Fiscal<br/>';
                                                    echo $dadosOrdem['solicitado_nfse'] == 1 ?        '<span class="glyphicon glyphicon-ok green"></span> NFSe - Nota de Serviço Eletrônica (Liberado)<br/>'           : '<span class="glyphicon glyphicon-remove red""></span> NFSe - Nota de Serviço Eletrônica<br/>';
                                                    echo $dadosOrdem['solicitado_cte'] == 1 ?         '<span class="glyphicon glyphicon-ok green"></span> CTe - Conhecimento de Transporte Eletrônico (Liberado)<br/>' : '<span class="glyphicon glyphicon-remove red""></span> CTe - Conhecimento de Transporte Eletrônico<br/>';
                                                    echo $dadosOrdem['solicitado_mdfe'] == 1 ?        '<span class="glyphicon glyphicon-ok green"></span> MDFe - Manifesto de Frete Eletrônico (Liberado)<br/>'        : '<span class="glyphicon glyphicon-remove red""></span> MDFe - Manifesto de Frete Eletrônico<br/>';
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                
                                    <!-- TABELA HISTÓRICO ATENDIMENTO -->
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <th class="txtcenter" colspan="3">
                                                HISTÓRICO DE ATENDIMENTO DESTE CLIENTE (Últimos 5)
                                            </th>
                                        </tr>
                                        <tr class="bold">
                                            <td class="txtcenter">
                                                Data
                                            </td>
                                            <td class="txtcenter">
                                                Protocolo
                                            </td>
                                            <td class="txtcenter">
                                                Assunto
                                            </td>
                                        </tr>
                                        <?php
                                            if($historicoCliente){
                                                foreach($historicoCliente as $oHistorico){
                                                    if( $oHistorico['protocolo'] != $dadosOrdem['protocolo']){
                                                        echo '
                                                            <tr>
                                                                <td>' . $oHistorico['datahora'] . '</td>
                                                                <td>' . $oHistorico['protocolo'] . '</td>
                                                                <td>' . $oHistorico['assunto'] . '</td>
                                                            </tr>
                                                        ';
                                                    }
                                                    
                                                }
                                            } else {
                                                echo '
                                                    <tr>
                                                        <td colspan="3">Nenhum atendimento registrado.</td>
                                                    </tr>
                                                ';
                                            }
                                        
                                        ?>
                                        
                                    </table>
                                </td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
                <div class="row margin-top-35">
                    <div class="col-md-offset-1 col-md-10">
                        <button class="btn btn-primary" name="btnImprimirOrdem" ><span class="glyphicon glyphicon-print"></span> Imprimir Ordem</button>
                        <button class="btn btn-primary right" name="btnEmailOrdem" ><span class="glyphicon glyphicon-envelope"></span> Reenviar por E-mail</button>
                        <span class="processandoEmail" style="display: none;"></span>
                        <span class="processadoEmail green" style="display: none">Email enviado com sucesso!&nbsp;&nbsp;&nbsp;</span>
                        <span class="processadoEmailErro red" style="display: none">Erro no envio. Contacte o Departamento de TI.&nbsp;&nbsp;&nbsp;</span>
                        <br/><br/>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>
<?php
    include_once('footer.php');
?>