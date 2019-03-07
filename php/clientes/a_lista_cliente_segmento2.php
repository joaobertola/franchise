<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="<?= 'https://' . $_SERVER["SERVER_NAME"] ?>/franquias/css/assets/js/bootstrap.min.js"></script>
<script src="<?= 'https://' . $_SERVER["SERVER_NAME"] ?>/franquias/js/jquery-ui.min.js"></script>
<link rel="stylesheet"
      href="<?= 'https://' . $_SERVER["SERVER_NAME"] ?>/franquias/css/assets/css/font-awesome.min.css">
<link rel="stylesheet"
      href="<?= 'https://' . $_SERVER["SERVER_NAME"] ?>/franquias/css/assets/css/bootstrap.min.css">
<link rel="stylesheet"
      href="<?= 'https://' . $_SERVER["SERVER_NAME"] ?>/franquias/css/jquery-ui.min.css">
<?php
/**
 * Created by PhpStorm.
 * User: dev02
 * Date: 14/11/2016
 * Time: 10:34
 */
require "connect/sessao.php";
require "connect/sessao_r.php";
require "connect/funcoes.php";

if ($id_franquia == 247 || $id_franquia == 4 || $id_franquia == 163) {
    $id_franquia = 1;
}

$sqlConsultor = "SELECT
                        c.id,
                        c.nome
                 FROM cs2.consultores_assistente c
                 WHERE c.id_franquia = '$id_franquia'
                 AND c.situacao = 0
                 AND c.tipo_cliente = 0
                 AND c.tipo_funcionario = 'I'";

//if($id_franquia == 1){
//    echo $sqlConsultor;
//    die;
//}
$qryConsultor = mysql_query($sqlConsultor, $con);
$filtroData =  ' AND  ce.data_venda BETWEEN DATE(base_web_control.fn_get_mes_inicio_filtro())  AND DATE(NOW()) ';
$consultor = " AND 0=0 ";
if($_POST){

    $idConsultor = $_POST['iptConsultor'];
    $dataInicio = $_POST['dtInicio'];
    $dataFim = $_POST['dtFim'];
    $filtroData =  ' AND  ce.data_venda BETWEEN DATE(base_web_control.fn_get_mes_inicio_filtro())  AND DATE(NOW()) ';
    if($dataInicio != null && isset($dataInicio) && $dataInicio != ''){
        $filtroData = ' AND  ce.data_venda BETWEEN "'.data_mysql($dataInicio).'"  AND "'.data_mysql($dataFim).'"';

    }

    $consultor = " AND c.id = '$idConsultor' ";
    if($idConsultor == '' || $idConsultor == 0){
        $consultor = " AND 0=0 ";
    }

}

$sqlRanking = "
          SELECT
                c.nome,
                DATE_FORMAT(ce.data_venda,'%d/%m/%Y') AS data_venda,
                ced.codigo_barra,
                ced.qtd,
                p.descricao,
                (SELECT login FROM base_web_control.webc_usuario WHERE id_cadastro = ce.codLoja LIMIT 1) AS login
            FROM cs2.consultores_assistente c
            INNER JOIN cs2.cadastro_equipamento ce
            ON c.id = ce.id_consultor
            LEFT JOIN cs2.cadastro_equipamento_descricao ced
            ON ced.id_cadastro_equipamento = ce.id
            LEFT JOIN cs2.produto p
            ON ced.codigo_barra = p.codigo
            WHERE c.situacao = 0
            AND c.tipo_cliente = 0
            AND c.tipo_funcionario = 'I'
            AND c.id_franquia = '$id_franquia'
           $filtroData
            AND (p.descricao = 'BALANÇA ELGIN SA 110'
            OR p.descricao = 'COMPUTADOR BEMATECH RC8400 4GB 2 SERIAIS'
            OR p.descricao = 'IMPRESSORA DE ETIQUETAS ARGOX OS 214 PLUS'
            OR p.descricao = 'GAVETA DE DINHEIRO BEMATECH GD 56'
            OR p.descricao = 'IMPRESSORA TÉRMICA BEMATECH MP4200 TH USB'
            OR p.descricao = 'LEITOR DE CÓDIGO DE BARRAS BEMATECH BR400'
            OR p.descricao = 'LEITOR DE CODIGO DE BARRAS C/ SUPORTE S-500'
            OR p.descricao = 'MONITOR OAC LED WIDSCREEN 15 POL'
            OR p.descricao = 'ANTENA WIRELESS TPLINK'
            OR p.descricao = 'IMPRESSORA TERMICA BEMATECH MP100S SERRILHA')
           $consultor
        ";
//
//echo '<pre>';
//var_dump($sqlRanking);
//die;
$qryRanking = mysql_query($sqlRanking, $con);
?>

<div class="col-md-12 text-center">
    <h5>Relatório detalhado de venda de Equipamentos</h5>
</div>


    <div class="row">
        <div class="col-md-12">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading text-center">
                        <h3 class="panel-title">Filtro</h3>
                    </div>
                    <div class="panel-body">
                        <form id="frmFiltro" action="painel.php?pagina1=clientes/a_controle_visitas10.php" method="post">
                        <div class="col-md-8 col-md-offset-2">
                            <label for="iptConsultor">
                                Consultor:
                            </label>
                            <select id="iptConsultor" name="iptConsultor" class="form-control">
                                <option value="0">--TODOS--</option>
                                <?php while ($rsConsultor = mysql_fetch_array($qryConsultor)) { ?>
                                    <option
                                        value="<?php echo $rsConsultor['id'] ?>"><?php echo $rsConsultor['nome'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-8 col-md-offset-2">
                            <label for="dtInicio">
                                Data Inicio:
                            </label>
                            <input type="text" id="dtInicio" name="dtInicio" class="form-control datepicker">
                        </div>
                        <div class="col-md-8 col-md-offset-2">
                            <label for="dtFim">
                                Data Fim:
                            </label>
                            <input type="text" id="dtFim" name="dtFim" class="form-control datepicker">
                        </div>
                        <div class="col-md-8 col-md-offset-2 text-center">
                                <button class="btn btn-primary col-md-4" id="btnFiltrar" type="button">Filtrar</button>
                                <button class="btn btn-warning col-md-4 pull-right" id="btnLimpar" type="reset">Limpar</button>
                        </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
<div class="row" style="margin-top: 10px;">
    <div class="col-md-12" style="padding: 20px;">
        <div class="col-md-8 col-md-offset-2">
            <form id="frmExcluir" name="frmExcluir" method="post">
                <input type="hidden" name="id" id="id"/>
                <input type="hidden" name="excluir" id="excluir"/>
                <input type="hidden" name="ativar" id="ativar"/>
                <table class="table table-hover table-bordered table-stripped">
                    <thead>
                    <tr>
                        <th>Login</th>
                        <th>Consultor</th>
                        <th>Data Venda</th>
                        <th>Código de Barras</th>
                        <th>Quantidade</th>
                        <th>Descrição</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    $totalQuantidade = 0;
                    while ($rs = mysql_fetch_array($qryRanking)) {
                        $totalQuantidade = (int)$rs['qtd'] + $totalQuantidade ?>
                        <tr style="border: solid 1px;">
                            <td class=""><?php echo $rs['login'] ?></td>
                            <td class=""><?php echo $rs['nome'] ?></td>
                            <td class=""><?php echo $rs['data_venda'] ?></td>
                            <td class=""><?php echo $rs['codigo_barra'] ?></td>
                            <td class=""><?php echo $rs['qtd'] ?></td>
                            <td class=""><?php echo $rs['descricao'] ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="4" style="text-align: right;">Total</td>
                        <td colspan=""><?php echo $totalQuantidade ?></td>
                        <td colspan=""></td>
                    </tr>
                    </tbody>
                </table>

            </form>
        </div>
    </div>

</div>
<script>
    $(document).ready(function(){

        $('.datepicker').datepicker({
            dayNames: ['Domingo', 'Segunda', 'Ter&ccedil;a', 'Quarta', 'Quinta', 'Sexta', 'S&aacute;bado'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'S&aacute;b', 'Dom'],
            dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'S&aacute;b', 'Dom'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            nextText: 'Pr&oacute;ximo',
            prevText: 'Anterior',
            dateFormat: "dd/mm/yy",
            changeMonth: true
            //gotoCurrent: true
        });

        $('#btnFiltrar').click(function(){
            //console.log('aqui estamos');

            var dataInicio = $('#dtInicio').val();
            var dataFim = $('#dtFim').val();
            console.log(dataInicio);
            console.log(dataFim);
            if(dataInicio != null && dataInicio != '' &&  typeof dataInicio != 'undefined'){
                if(dataFim == null || dataFim == '' ||  typeof dataFim == 'undefined'){
                    alert('Favor preencher a data final');
                    return false;
                }
            }

            $('#frmFiltro').submit();
        })

    })
</script>


