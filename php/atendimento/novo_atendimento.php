<?php
    include_once('header.php');
    
    
    require_once ( 'classes/DbConnection.class.php' );
    
    require_once('classes/NovoAtendimento.class.php');
    
    $idCliente = $_REQUEST['iptIdCliente'];
    
    if($idCliente){
        /* instacia objeto */
        $objAtendimento = new NovoAtendimento();
        
        try{
            $dadosClientes = $objAtendimento->buscaCliente($idCliente);
            $logonCliente = $dadosClientes[0][0];
            $siteCliente = $dadosClientes[1][0];
            $dadosCliente = $dadosClientes[2][0];
            //echo "<pre>";
            //print_r( $dadosClientes );
            //echo "</pre>";
            
            //busca historico desse cliente ara mostrar na nova ordem
            $historicosCliente = $objAtendimento->buscaHistoricoCliente($dadosCliente['codloja']);
            //print_r($historicosCliente);
            
        } catch (Exception $e){
            echo $e;
        }
        
        /*
         *pegando todos os departamentos
         **/
        try{
            $departamentos = $objAtendimento->listaDepartamentos();
            //print_r($departamentos);
            
        } catch (Exception $e){
            echo $e;
        }
        
        /*
         *pegando todos os subdepartamentos agrupados por departamento
         **/
        try{
            $subdepartamentos = $objAtendimento->listaSubDepartamentos();
            
            $groupedDeparts = array();
            $groupedDepartsName = array();
            
            /* agrupando por departamento */
            foreach($subdepartamentos as $key => $item)
            {
               $groupedDeparts[$item['nome_departamento']][$key] = $item;
            }

            
        } catch (Exception $e){
            echo $e;
        }
        
    }

?>
<script src="js/novo-atendimento-func.js"></script>

<article class="row">
    <section class="col-md-offset-2 col-md-8 bwell">
        <?php
        if(!$idCliente){
        ?>
        <div class="row">
            <div class="col-md-12 alert alert-danger">
                <strong>Não há nenhum cliente selecionado.</strong>
                 <a href="#modalIniciaAtendimento" data-toggle="modal">Clique aqui para selecionar um cliente.</a>
            </div>
        </div>
        <?php } else { ?>
            <!-- caso exista cliente postado -->
            <form name="frmOrdemAtendimento" method="post" action="">
                <input type="hidden" name="iptIdCliente" value="<?=$dadosCliente['codloja'];?>" />
                <input type="hidden" name="iptRazaoCliente" value="<?=$dadosCliente['razaosoc'];?>" />
                <div class="row">
                    <div class="col-md-12">
                        <h2>
                            Ordem de Atendimento No. <span class="spanNOrdem"></span>
                            <input type="hidden" name="iptProtocoloAt" />
                        </h2>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                    
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row form-group">
                                    <div class="col-md-2">
                                        <label>
                                            Data:
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="spanData">22/02/2015</span>
                                        <input type="hidden" name="iptDataHora" />
                                    </div>
                                    <div class="col-md-2">
                                        <label>
                                            Hora:
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="spanHora">14:15</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>
                                            Depto Solicitante:
                                        </label>
                                    </div>
                                    <div class="col-md-4 selectpicker">
                                        <select name="iptDeptoSolicitante" class="selectpicker form-control">
                                            <option value="0"> Selecione </option>
                                            <?php
                                            
                                            if($departamentos){
                                                foreach($departamentos as $departamento){
                                                    echo "<option value='" . $departamento['id'] . "'> " . $departamento['nome_departamento'] . " </option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>
                                            Solicitante:
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="hidden" name="iptIdSolicitante" id="iptIdSolicitante" />
                                        <input type="text" name="iptSolicitante" id="iptSolicitante" value="" class="form-control" autocomplete="off" />
                                        <ul id="list_iptSolicitante" class="listaSolicitanteDinamyc" style="display: none"></ul>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 featuredTitle">
                                        <span class="glyphicon glyphicon-send"></span> SETOR DE DESTINO:
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-offset-2 col-m-10">
                                        <div class="row">
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
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-12 margin-top-35 featuredTitle">
                                        <span class="glyphicon glyphicon-info-sign"></span> DETALHES DO PROBLEMA:
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="iptObservacoes">Assunto:</label>
                                    </div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <input type="text" name="iptAssunto" id="iptAssuntoAtt" class="form-control" placeholder="Escreva aqui ao que se refere este atendimento" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="iptObservacoes">Observações Importantes:</label>
                                    </div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <textarea name="iptObservacoes" class="textarea form-control" rows="5" placeholder="Descreva aqui o problema detalhadamente" ></textarea>
                                    </div>
                                </div>
                                
                                <!-- dados do cliente -->
                                
                                
                                <div class="row">
                                    <div class="col-md-12 margin-top-35 featuredTitle">
                                        <span class="glyphicon glyphicon-user"></span> DADOS DO CLIENTE WEB CONTROL EMPRESAS
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                        if($dadosCliente){
                                        ?>
                                            <table class="table table-striped table-bordered">
                                                <tr>
                                                    <td>
                                                        ID
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['codloja'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Funcionário Franquia
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['nome_franquia'];?>
                                                    </td>
                                                </tr>
                                                <tr class="blue bold">
                                                    <td>
                                                        Código de Cliente
                                                    </td>
                                                    <td>
                                                        <?=$logonCliente['logon'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Razão Social
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['razaosoc'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        CNPJ
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['cpfcnpj_doc'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Endereço
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['end'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Número
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['numero'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Complemento
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['complemento'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Bairro
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['bairro'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        UF
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['uf'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Cidade
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['cidade'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Cep
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['cep'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Telefone
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['fone'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Fax
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['fax'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Celular
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['celular'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Telefone Residencial
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['fone_res'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Site Virtual Flex
                                                    </td>
                                                    <td>
                                                        <?=$siteCliente['siteurl'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        E-mail
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['email'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Proprietário 1
                                                    </td>
                                                    <td>
                                                        Nome: <?=$dadosCliente['socio1'];?>
                                                        <br/>CPF 1: <?=$dadosCliente['cpfsocio1'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Proprietário 2
                                                    </td>
                                                    <td>
                                                        Nome: <?=$dadosCliente['socio2'];?>
                                                        <br/>CPF 2: <?=$dadosCliente['cpfsocio2'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Segmento Empresarial
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['ramo_atividade'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Vendedor
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['vendedor'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Franqueado
                                                    </td>
                                                    <td>
                                                        <?=$dadosCliente['nome_franquia'];?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Data Afiliação
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
                                                        Módulos Liberados
                                                    </td>
                                                    <td class="checkbox">
                                                        <label  style="display: none" >
                                                            <input type="checkbox" name="iptCfe" value="S" <?php if($dadosCliente['nfe'] == 'S'){ echo "checked"; }?>/>
                                                        </label>
                                                            <?php if($dadosCliente['nfe'] == 'S'){ 
                                                                echo '<span class="glyphicon glyphicon-ok green"></span> ';
                                                            } else {
                                                                echo '<span class="glyphicon glyphicon-remove red"></span> ';
                                                            }
                                                            ?>
                                                            NFe - Nota Fiscal Eletrônica
                                                        <br/>
                                                        <label style="display: none" >
                                                            <input type="checkbox" name="iptNfce" value="S" <?php if($dadosCliente['nfce'] == 'S'){ echo "checked"; }?>/>
                                                        </label>
                                                            <?php if($dadosCliente['nfce'] == 'S'){ 
                                                                echo '<span class="glyphicon glyphicon-ok green"></span> ';
                                                            } else {
                                                                echo '<span class="glyphicon glyphicon-remove red"></span> ';
                                                            }
                                                            ?>
                                                            NFCe - Nota Fiscal do Consumidor Eletrônica
                                                        <br/>
                                                        <label style="display: none">
                                                            <input type="checkbox" name="iptCupom" value="S" <?php if($dadosCliente['cfiscal'] == 'S'){ echo "checked"; }?> />
                                                        </label>
                                                            <?php if($dadosCliente['cfiscal'] == 'S'){ 
                                                                echo '<span class="glyphicon glyphicon-ok green"></span> ';
                                                            } else {
                                                                echo '<span class="glyphicon glyphicon-remove red"></span> ';
                                                            }
                                                            ?>
                                                            Cupom Fiscal
                                                        <br/>
                                                        <label style="display: none">
                                                            <input type="checkbox" name="iptNfse" value="S" <?php if($dadosCliente['nfse'] == 'S'){ echo "checked"; }?> />
                                                        </label>
                                                            <?php if($dadosCliente['nfse'] == 'S'){ 
                                                                echo '<span class="glyphicon glyphicon-ok green"></span> ';
                                                            } else {
                                                                echo '<span class="glyphicon glyphicon-remove red"></span> ';
                                                            }
                                                            ?>
                                                            NFSe - Nota de Serviço Eletrônica
                                                        <br/>
                                                        <label style="display: none" >
                                                            <input type="checkbox" name="iptCte" value="S" <?php if($dadosCliente['cte'] == 'S'){ echo "checked"; }?>/>
                                                        </label>
                                                            <?php if($dadosCliente['cte'] == 'S'){ 
                                                                echo '<span class="glyphicon glyphicon-ok green"></span> ';
                                                            } else {
                                                                echo '<span class="glyphicon glyphicon-remove red"></span> ';
                                                            }
                                                            ?>
                                                            CTe - Conhecimento de Transporte Eletrônico
                                                        <br/>
                                                        <label style="display: none" >
                                                            <input type="checkbox" name="iptMDFe" value="S" <?php if($dadosCliente['mdfe'] == 'S'){ echo "checked"; }?>/>
                                                        </label>
                                                            <?php if($dadosCliente['mdfe'] == 'S'){ 
                                                                echo '<span class="glyphicon glyphicon-ok green"></span> ';
                                                            } else {
                                                                echo '<span class="glyphicon glyphicon-remove red"></span> ';
                                                            }
                                                            ?>
                                                            MDFe - Manifesto de Frete Eletrônico
                                                    </td>
                                                </tr>
                                            </table>
                                        <?php
                                        }  else {
                                         ?>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>
                                                        Não existe nenhum cliente selecionado. <a href="#modalIniciaAtendimento" data-toggle="modal">Selecione um cliente</a>.
                                                    </td>
                                                </tr>
                                            </table>
                                        <?php 
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 margin-top-35 featuredTitle">
                                        <span class="glyphicon glyphicon-list-alt"></span> HISTÓRICO DE ATENDIMENTO DESTE CLIENTE
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                            if($historicosCliente) {
                                        ?>
                                            <!-- TABELA HISTÓRICO CLIENTE -->
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="txtcenter">
                                                            Protocolo
                                                        </th>
                                                        <th class="txtcenter">
                                                            Data/Hora
                                                        </th>
                                                        <th class="txtcenter">
                                                            Assunto
                                                        </th>
                                                        <th class="txtcenter">
                                                            Depto. Responsável
                                                        </th>
                                                        <th class="txtcenter">
                                                            Status
                                                        </th>
                                                        <th class="txtcenter">
                                                            Ações
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    foreach($historicosCliente as $historicoCliente){
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <?=$historicoCliente['protocolo'];?>
                                                        </td>
                                                        <td>
                                                            <?=$historicoCliente['datahora'];?>
                                                        </td>
                                                        <td>
                                                            <?=$historicoCliente['assunto'];?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo $historicoCliente['nome_departamento'] . ' <span class="glyphicon glyphicon-chevron-right"></span> ' . $historicoCliente['nome_subdepartamento'];?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if($historicoCliente['status'] == 'P'){
                                                                echo "<span class='red'>Pendente</span>";
                                                            } else if($historicoCliente['status'] == 'A'){
                                                                echo "<span class='blue'>Em Andamento</span>";
                                                            } else if($historicoCliente['status'] == 'R'){
                                                                echo "<span class='grey'>Resolvido</span>";
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="txtcenter">
                                                            <span class="glyphicon glyphicon-eye-open"></span>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        <?php
                                            } else {
                                         ?>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>
                                                        Não há histórico de atendimento para este cliente.
                                                    </td>
                                                </tr>
                                            </table>
                                        <?php 
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary right" name="btnGravaOrdem" id="btnGravaOrdem" ><span class="glyphicon glyphicon-ok"></span> Enviar Ordem</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php
        } // fim else existir cliente no POST
        ?>
    </section>
</article>
<?php
    include_once('inc/modais_compartilhadas.php');
    include_once('footer.php');
?>