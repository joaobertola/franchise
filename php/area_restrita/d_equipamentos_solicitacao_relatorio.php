<?php
/**
 * @file d_equipamentos_solicitacao_relatorio.php
 * @brief
 * @author ARLLON DIAS
 * @date 14/02/2017
 * @version 1.0
 * */
require "connect/sessao.php";
require "connect/conexao_conecta.php";

$sqlFranquias = "
      SELECT
            id,
            fantasia
      FROM cs2.franquia
      WHERE sitfrq=0 AND classificacao != 'J' ORDER BY id";

$resFranquias = mysql_query($sqlFranquias, $con);

$idFranquia = 0;
if ($_POST) {

    $idFranquia = $_POST['iptFranquia'];
}
// RETORNA OS PEDIDOS DA FRANQUIA SELECIONADA
$sqlPedidosFranquia = "SELECT
                                fp.id,
                                fp.id_franquia,
                                DATE_FORMAT(fp.data_solicitacao, '%d/%m/%Y') AS data_solicitacao,
                                DATE_FORMAT(fp.data_baixa, '%d/%m/%Y') AS data_baixa,
                                f.fantasia,
                                IFNULL(fp.data_baixa,1) AS confirmar_envio
                           FROM cs2.franquia_solicitacao_equipamento fp
                           INNER JOIN cs2.franquia f
                            ON f.id = fp.id_franquia
                           WHERE IF('$idFranquia' = 0, 0=0, f.id = '$idFranquia')";

$resPedidoFranquia = mysql_query($sqlPedidosFranquia, $con);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="https://www.webcontrolempresas.com.br/franquias/css/assets/js/mask.js"></script>
<link rel="stylesheet"
      href="https://www.webcontrolempresas.com.br/franquias/css/assets/css/font-awesome.min.css">

<link rel="stylesheet" href="../css/assets/css/bootstrap.min.css">

<link rel="stylesheet" href="../css/assets/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="../css/assets/css/sweetalert.css">

<script src="../css/assets/js/bootstrap.min.js"></script>
<script src="../css/assets/js/sweetalert.min.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-12"
             style="background-color: grey; color: white; text-align: center; font-size: 20px; height: 80px;">
            <br>
            Solicitações Realizadas
        </div>
    </div>
    <form method="post">
        <div class="col-md-12" style="margin-top: 20px;">
            <div class="col-md-6">
                <label for="iptFranquia">
                    Nome da Franquia:
                </label>
                <select id="iptFranquia" name="iptFranquia" class="form-control">
                    <option value="0">TODAS</option>
                    <?php while ($arrFranquia = mysql_fetch_array($resFranquias)) { 
                        if (in_array(intval($id_franquia), array(163, 4)) || intval($arrFranquia['id']) == intval($id_franquia)) { ?>
                        <option <?php echo $idFranquia == $arrFranquia['id'] ? 'selected' : '' ?>
                            value="<?php echo $arrFranquia['id'] ?>"><?php echo $arrFranquia['fantasia'] ?></option>
                        <?php }
                         } ?>
                </select>
            </div>
        </div>
        <div class="col-md-2" style="margin-top: 15px; margin-left: 13px;">
            <button type="submit" class="btn btn-success">Filtrar</button>
        </div>
    </form>
    <div class="col-md-12" style="margin-top: 25px;">
        <table class="table table-responsive table-bordered table-striped">
            <thead>
                <tr style="background-color: #017ebc; color: white; font-weight: bold;">
                    <td align="center">Pedido</td>
                    <td align="center">Data</td>
                    <td align="center">Franquia</td>
                    <td align="center">Ações</td>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                if (mysql_num_rows($resPedidoFranquia) == 0) {
                    ?>
                    <tr>
                        <td colspan="5" align="center">Nenhum Registro Encontrado.</td>
                    </tr>
                    <?php
                } else {
                    // PRIMEIRO PERCORRE OS PEDIDDOS DA FRANQUIA
                    while ($arrPedidosFranquia = mysql_fetch_array($resPedidoFranquia)) {
                        $idSolicitacao = $arrPedidosFranquia['id'];
                        $gerencia = in_array(intval($id_franquia), array(163, 4));
                        if (true == $gerencia || intval($arrPedidosFranquia['id_franquia']) == intval($id_franquia)) { ?>
                            <tr>
                                <td style="cursor:pointer;" class="visualizarItens" data-id="<?php echo $arrPedidosFranquia['id'] ?>"><?php echo $arrPedidosFranquia['id'] ?></td>
                                <td style="cursor:pointer;" class="visualizarItens" data-id="<?php echo $arrPedidosFranquia['id'] ?>"><?php echo $arrPedidosFranquia['data_solicitacao'] ?></td>
                                <td style="cursor:pointer;" class="visualizarItens" data-id="<?php echo $arrPedidosFranquia['id'] ?>"><?php echo $arrPedidosFranquia['fantasia'] ?></td>
                                <td style="cursor:pointer;" align="center"><?php
                                    if ($arrPedidosFranquia['confirmar_envio'] != 1) {
                                        echo '<span class="visualizarItens" data-id="' . $arrPedidosFranquia['id'] . '">Envio Confirmado (' . $arrPedidosFranquia['data_baixa'] . ') </span>';
                                    } elseif ($arrPedidosFranquia['confirmar_envio'] == 1 && true == $gerencia) {
                                        echo '<span title="Confirmar Envio" class="glyphicon glyphicon-arrow-right confirmarEnvio" data-id="' . $arrPedidosFranquia['id'] . '" style="color: darkgreen; cursor: pointer;"></span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-4 col-md-offset-4">
        <button class="btn btn-primary" id="btnVoltar">Voltar</button>
    </div>
</div>
<script>
    $(document).ready(function () {

        // Visualiza os itens do pedido
        $('.visualizarItens').on('click', function () {

            var idLinha = $(this).data('id');

            location.href = 'painel.php?pagina1=area_restrita/d_equipamentos_solicitacao_relatorio_detalhado.php&idSolicitacao=' + idLinha;

        });

        // Confirma o envio do Pedido para a franquia
        $('.confirmarEnvio').on('click', function () {

            var pedido = $(this).data('id');

            swal({
                title: "Atenção",
                text: "Deseja confirmar o envio do Pedido: " + pedido + "?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Sim, confirmar!",
                cancelButtonText: "Não!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
                    function (isConfirm) {
                        if (isConfirm) {

                            $.ajax({
                                url: '../php/salvar_equipamentos_franquia.php',
                                data: {
                                    action: 'confirmarEnvio',
                                    pedido: pedido
                                },
                                type: 'POST',
                                dataType: 'json',
                                success: function (data) {

                                    if (data.mensagem == 1) {
                                        swal("Sucesso!", "Confirmação realizada com sucesso!.", "success");
                                        setTimeout(function () {
                                            location.reload();
                                        }, 1500);

                                    }

                                }
                            });
                        } else {
                            swal("Cancelado", "Confirmação cancelada", "error");
                        }
                    });

        });

        $('#btnVoltar').on('click', function () {

            location.href = 'painel.php?pagina1=area_restrita/d_equipamentos0.php';

        });

    });
</script>