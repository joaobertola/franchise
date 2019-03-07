<?php
/**
 * @file d_equipamentos_solicitacao_relatorio_detalhado.php
 * @brief
 * @author ARLLON DIAS
 * @date 22/02/2017
 * @version 1.0
 * */
require "connect/sessao.php";
require "connect/conexao_conecta.php";

$idSolicitacao = $_REQUEST['idSolicitacao'];

$sqlItens = "SELECT
                                        fse.id,
                                        fse.qtd,
                                        fse.codigo_barra,
                                        descricao,
                                        fe.id_franquia,
                                        DATE_FORMAT(fe.data_baixa, '%d/%m/%Y') AS data_baixa,
                                        DATE_FORMAT(fe.data_solicitacao, '%d/%m/%Y') AS data_solicitacao,
                                        id_solicitacao,
                                        IFNULL(pcc.controle_qtd,'N') AS controle_qtd
                                    FROM cs2.franquia_solicitacao_equipamento_itens fse
                                    INNER JOIN base_web_control.produto p
                                    ON p.id = fse.id_produto_wc
                                    INNER JOIN cs2.franquia_solicitacao_equipamento fe
                                    ON fe.id = fse.id_solicitacao
                                    INNER JOIN base_web_control.produto_configuracoes_comercial pcc
                                    ON pcc.id_produto = p.id
                                    WHERE id_solicitacao = '$idSolicitacao'; ";
//echo $sqlItens;
//die;
$resItens = mysql_query($sqlItens, $con);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="https://www.webcontrolempresas.com.br/franquias/css/assets/js/mask.js"></script>
<link rel="stylesheet"
      href="https://www.webcontrolempresas.com.br/franquias/css/assets/css/font-awesome.min.css">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="../css/assets/css/bootstrap.min.css">
<!---->
<!-- Optional theme -->
<link rel="stylesheet" href="../css/assets/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="../css/assets/css/sweetalert.css">

<!-- Latest compiled and minified JavaScript -->
<script src="../css/assets/js/bootstrap.min.js"></script>
<script src="../css/assets/js/sweetalert.min.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-12"
             style="background-color: grey; color: white; text-align: center; font-size: 20px; height: 80px;">
            <br>
            SOLICITAÇÃO DE EQUIPAMENTOS PARA FRANQUEADORA
        </div>
        <div class="col-md-12" style="text-align: center;">
            <img src="../img/vendedores1_0.png"/>
        </div>
        <div class="col-md-12">
            <b>Pedido: <?php echo mysql_result($resItens, 0, 'id_solicitacao') ?></b>
            <br/><br/>
        </div>

        <div class="col-md-12">
            Data: <?php echo mysql_result($resItens, 0, 'data_solicitacao') ?>
        </div>
        <div class="col-md-12" style="margin-top: 20px;">
            <!--                    <label for="iptFranquia">-->
            <!--                        Nome da Franquia:-->
            <!--                    </label>-->
            <!--                    <select id="iptFranquia" name="iptFranquia" class="form-control">-->
            <!--                        --><?php //while ($arrFranquia = mysql_fetch_array($resFranquias)) {                                                                                                               ?>
            <!--                            <option-->
            <!--                                value="--><?php //echo $arrFranquia['id']                                                                                                               ?><!--">-->
            <?php //echo $arrFranquia['fantasia'] ?><!--</option>-->
            <!--                        --><?php //}                                                                                                               ?>
            <!--                    </select>-->
            <b>Nome da Franquia: <?php
                $idF = mysql_result($resItens, 0, 'id_franquia');
                $sqlFranquias = "
      SELECT 
            fantasia
      FROM cs2.franquia
      WHERE id=" . $idF;

                $resFranquias = mysql_query($sqlFranquias, $con);

                echo mysql_result($resFranquias, 0, 'fantasia')
                ?></b>
        </div>
    </div>
    <div class="row">
        <table class="table-responsive table-bordered" style="width: 100%;">
            <thead>
                <tr style="background-color: #017ebc; color: white; font-weight: bold;">
                    <td>Qtd</td>
                    <td>Código Barras</td>
                    <td>Descrição</td>
                </tr>
            </thead>
            <tbody>
                <?php
                $resItens2 = mysql_query($sqlItens, $con);
                // PERCORRE OS ITENS DO PEDIDO
                while ($arrItens = mysql_fetch_array($resItens2)) {
                    ?>
                    <tr>
                        <td><?php echo $arrItens['qtd'] ?></td>
                        <td><?php echo $arrItens['codigo_barra'] ?></td>
                        <td><?php echo $arrItens['descricao'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="row text-center" style="margin-top: 25px;">
            <h5>Área Exclusiva da Administração - Autorização do Financeiro</h5>
        </div>
        <div class="row col-md-12"
             style="min-height: 300px; background-color: darkgrey; border: solid; margin-left: 5px; margin-right: 5px;">
            <br/>
            <form id="pedidos" action="" method="post">
                <table class="table-responsive table-bordered" style="width: 100%;">
                    <thead>
                        <tr style="background-color: #017ebc; color: white; font-weight: bold;">
                            <td>Produto</td>
                            <td>Numero de Serie</td> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ht = 2;
                        $resItens2 = mysql_query($sqlItens, $con);
                        $disabled = in_array(intval($id_franquia), array(163, 4)) ? '' : 'disabled';
                        // PERCORRE OS ITENS DO PEDIDO
                        while ($arrItens = mysql_fetch_array($resItens2)) {
                            if ($arrItens['controle_qtd'] == 'N') {
                                ?>
                                <tr>
                                    <td style="font-size:12px;"><?php echo $arrItens['descricao'] ?></td>
                                    <td><?php
                                        if ($arrItens['qtd']) {
                                            for ($i = 0; $i < $arrItens['qtd']; $i++) {
                                                $item = "SELECT id,numb_serie FROM cs2.franquia_solicitacao_equipamento_itens_auth_financeiro
                                    WHERE id_solicitacao = $idSolicitacao AND id_solicitacao_equipamento_itens = " . $arrItens['id'] . " AND qtd = $i ";
                                                if ($refQ = mysql_query($item, $con)) {
                                                    $ref = mysql_fetch_array($refQ);
                                                    echo '<input type="text" name="item[' . $arrItens['id'] . '][' . $i . ']" value="' . $ref['numb_serie'] . '" style="text-transform:uppercase;width:180px;height:20px;padding:2px;margin:5px;" '.$disabled.' />';
                                                } else {
                                                    echo '<input type="text" name="item[' . $arrItens['id'] . '][' . $i . ']" value="" style="text-transform:uppercase;width:180px;height:20px;padding:2px;margin:5px;" '.$disabled.' />';
                                                }
                                            }
                                        }
                                        ?></td> 
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </form>
            <br/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4" style="margin-top: 10px;">
            <div class="col-md-6">
                <button class="btn btn-success" id="btnImprimir" name="btnImprimir">Imprimir</button>
            </div>
            <div class="col-md-6">
                <button class="btn btn-primary" id="btnVoltar" name="btnVoltar">Voltar</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('#btnImprimir').on('click', function () {
            var dados = $('#pedidos').serialize();
            $.post('area_restrita/d_equipamentos_solicitacao_relatorio_detalhado_historico.php?idSolicitacao=<?php echo $_REQUEST['idSolicitacao'] ?>', dados, function () {
                $(this).addClass('hidden');
                $('#btnVoltar').addClass('hidden');
                window.print();
                $(this).removeClass('hidden');
                $('#btnVoltar').removeClass('hidden');
                $('body').css({'font-size': '12px'});
            });
        });

        $('#btnVoltar').on('click', function () {
            location.href = 'painel.php?pagina1=area_restrita/d_equipamentos_solicitacao_relatorio.php';
        });

        setTimeout(function () {
            //$('#btnImprimir').trigger('click');
        }, 1000);
    })
</script>