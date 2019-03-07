<?php
    require_once ( 'classes/DbConnection.class.php' );
    
    require_once('classes/NovoAtendimento.class.php');

    /* instacia objeto */
    $objAtendimento = new NovoAtendimento();
    
    try{
        $subdepartamentos = $objAtendimento->listaSubDepartamentos();
            
            $groupedDeparts = array();
            $groupedDepartsName = array();
            
            /* agrupando por departamento */
            foreach($subdepartamentos as $key => $item)
            {
               $groupedDeparts[$item['nome_departamento']][$key] = $item;
               //$groupedDepartsName[] = [$item['nome_departamento']];
            }
        
    } catch (Exception $e){
        echo $e;
    }
?>

<script src="js/listar-atendimento-func.js"></script>
<div id="wrap">
  <!--<div id="main" class="container">-->
    <article class="row">
        <section class="col-md-offset-2 col-md-8 bwell">
        <!--<article class="row bwell">-->
            <section class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <h5>
                            Atendimentos Realizados
                        </h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form name="frmBuscaAtendimentos" class="navbar-form navbar-left" role="search" method="get" action="?">
                            <div class="form-group">
                                <select class="selectpicker form-control" name="iptTipoBuscar">
                                    <option>-- Selecione --</option>
                                    <option value="codigo">Código do Cliente</option>
                                    <option value="depto">Depto.Destino</option>
                                    <option value="empresa">Empresa</option>
                                </select>
                                <!-- select de departamentos -->
                                <select class="selectpicker form-control" name="iptDepartamentos" style="display: none">
                                    <option>-- Selecione --</option>
                                    <?php
                                        foreach($groupedDeparts as $depart => $items){
                                            foreach($items as $item){
                                                echo '<option value="' . $item['subdeptoid'] . '">' . $depart . ' > ' . $item['nome_subdepartamento'] . '</option>';
                                            }
                                        }
                                    ?>
                                </select>
                                <!-- input search -->
                                <input type="text" name="iptDadoBuscar" class="form-control" placeholder="digite sua busca...">
                            </div>
                            <button type="submit" class="btn btn-default">Procurar</button>
                        </form>
                        <button class="btn btn-primary right margin-bottom-35 margin-top-10" id="btnNovoAtendimento"><span class="glyphicon glyphicon-plus-sign"></span> Novo Atendimento</button>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped fontsize10" id="tblListaAtendimento">
                            <thead>
                                <tr>
                                    <th>Protocolo</th>
                                    <th>Data - Hora</th>
                                    <th>Depto. Solicitante</th>
                                    <th>Nome Solicitante</th>
                                    <th>Cod. Cliente</th>
                                    <th>Empresa</th>
                                    <th>Depto. Destino</th>
                                    <th>Status</th>
                                    <!--<th>Ações</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <!-- conteudo preenchido pelo datatable ajax -->
                            </tbody>
                        </table>  
                    </div>
                </div>
            </section>
        </article>
    <!--</div>-->
</div>
                
<!-- MODAIS -->

<?php
    include_once('inc/modal_listar_atendimento.php');
    include_once('inc/modais_compartilhadas.php');
?>



