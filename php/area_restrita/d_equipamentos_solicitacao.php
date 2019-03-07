<?php
/**
 * @file d_equipamentos_solicitacao.php
 * @brief Tela de solicitação de equipamentos para Franquias
 * @author ARLLON DIAS
 * @date 14/02/2017
 * @version 1.0
 **/

require "connect/sessao.php";
require "connect/conexao_conecta.php";

//echo '<pre>';
//var_dump($_SESSION);
//die;

$sqlFranquias = "
      SELECT
            id,
            fantasia
      FROM cs2.franquia
      WHERE sitfrq=0 AND classificacao != 'J' ORDER BY id";

$resFranquias = mysql_query($sqlFranquias, $con);

$sqlProdutos = "
      SELECT
            id,
            codigo_barra,
            descricao
      FROM base_web_control.produto
      WHERE id_cadastro = 62735
      AND ativo = 'A'
      AND id != '7788520'
      AND id != '7510103'
      ORDER BY descricao ASC";

$resProdutos = mysql_query($sqlProdutos, $con);
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="https://www.webcontrolempresas.com.br/franquias/css/assets/js/bootstrap.min.js"></script>
<script src="https://www.webcontrolempresas.com.br/franquias/css/assets/js/mask.js"></script>
<link rel="stylesheet"
      href="https://www.webcontrolempresas.com.br/franquias/css/assets/css/font-awesome.min.css">
<link rel="stylesheet"
      href="https://www.webcontrolempresas.com.br/franquias/css/assets/css/bootstrap.min.css">


<div class="container">
    <div class="row">
        <form id="frmSolicitarEquipamentosFranquia" name="frmSolicitarEquipamentosFranquia"
              action="salvar_equipamentos_franquia.php" method="post">
            <div class="col-md-12"
                 style="background-color: grey; color: white; text-align: center; font-size: 20px; height: 80px;">
                <br>
                SOLICITAÇÃO DE EQUIPAMENTOS PARA FRANQUEADORA
            </div>
            <div class="col-md-12" style="text-align: center;">
                <img src="../img/vendedores1_0.png"/>
            </div>
            <div class="col-md-12">
                Data: <?php echo date('d/m/Y') ?>
            </div>
            <div class="col-md-12" style="margin-top: 20px;">
                <b>Nome da Franquia: <?php echo $_SESSION['fantasia'] ?></b>
            </div>
            <div class="col-md-12" style="margin-top: 25px;">
                <input type="hidden" value="<?php echo $_SESSION['fantasia'] ?>" id="iptFranquia"
                       name="iptFranquia">
                <table class="table table-responsive table-bordered table-striped">
                    <thead>
                    <tr style="background-color: #017ebc; color: white; font-weight: bold;">
                        <td align="center">Item</td>
                        <td align="center">Qtde</td>
                        <td align="center">Equipamento/Suprimento</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    while ($arrProduto = mysql_fetch_array($resProdutos)) { ?>
                        <tr>
                            <td align="center"><?php echo $i ?></td>
                            <td>
                                <input type="number" maxlength="2" value="0" id="iptQtd" name="iptQtd[]">
                                <input type="hidden" value="<?php echo $arrProduto['codigo_barra'] ?>"
                                       id="iptCodigoBarra" name="iptCodigoBarra[]">
                                <input type="hidden" value="<?php echo $arrProduto['id'] ?>" id="iptIdProduto"
                                       name="iptIdProduto[]">
                                <input type="hidden" value="<?php echo $arrProduto['descricao'] ?>" id="iptIdProduto"
                                       name="iptDescricao[]">

                            </td>
                            <td><?php echo $arrProduto['descricao'] ?></td>
                        </tr>
                        <?php $i++;
                    } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-8 col-md-offset-2">
                <div class="col-md-4">
                    <button class="btn btn-success" type="submit" id="btnEnviar">Enviar p/ Franqueadora</button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary" type="button" id="btnVoltar">Voltar</button>
                </div>
                <div class="col-md-5">
                    <button class="btn btn-default" type="button" id="btnImprimirSolicitacao">Imprimir Formulário (Em
                        Branco)
                    </button>
                </div>
            </div>
        </form>
    </div>
    <script>

        $(document).ready(function () {

            $('#btnVoltar').on('click', function () {
                location.href = '../php/painel.php?pagina1=area_restrita/d_equipamentos0.php'
            });

            $('#btnImprimirSolicitacao').on('click', function () {
                location.href = '../php/painel.php?pagina1=area_restrita/d_equipamentos_solicitacao_impressao.php'
            })

        });

    </script>