<?php
/**
 * Created by PhpStorm.
 * User: dev02
 * Date: 24/11/2016
 * Time: 13:28
 */
if (!file_exists("connect/funcoes.php")) {
    require_once('../connect/funcoes.php');
} else {
    require "connect/funcoes.php";
}
if (!file_exists("connect/sessao.php")) {
    require_once('../connect/sessao.php');
} else {
    require "connect/sessao.php";
}
if (!file_exists("connect/conexao_conecta.php")) {
    require_once('../connect/conexao_conecta.php');
} else {
    require "connect/conexao_conecta.php";
}
$idFranquiaPesquisa = $id_franquia;
if ($id_franquia == 4 || $id_franquia == 163) {
    $idFranquiaPesquisa = 1;
}
$sqlWhere = '';
if ($_POST['iptAtendimento']) {

    $atendimento = $_POST['iptAtendimento'];

    if ($atendimento == 0) {
        $sqlWhere = '';
    } elseif ($atendimento == 1) {
        $sqlWhere = ' AND ac.id_atendimento = 1 ';
    } elseif ($atendimento == 2) {
        $sqlWhere = ' AND ac.id_atendimento = 2 ';
    }

}

if($_POST['iptDataInicio']){

    if(empty($_POST['iptDataFim'])){ ?>

        <script>
            alert('Preencha a Data Fim');
        </script>

        <?php exit;
    }
    $dataInicio = data_mysql($_POST['iptDataInicio']);
    $dataFim = data_mysql($_POST['iptDataFim']);

    $sqlWhere .= " AND ac.data_baixa BETWEEN '$dataInicio' AND '$dataFim'";

}

if($_POST['iptDataFim']){

    if(empty($_POST['iptDataInicio'])){ ?>

        <script>
            alert('Preencha a Data Ínicio');
        </script>

        <?php exit;
    }

}

$sqlLista1 = "
        SELECT SQL_CALC_FOUND_ROWS
            c.insc,
            c.razaosoc,
            c.codLoja AS id_cadastro,
            c.nomefantasia,
            c.socio1 AS proprietario,
            CONCAT(SUBSTR(REPLACE(c.celular, ' ', ''),1,2), ' ', SUBSTR(REPLACE(c.celular, ' ', ''),3,5), '-',SUBSTR(REPLACE(c.celular, ' ', ''),8,5) ) AS celular,
            c.email,
            CONCAT(SUBSTR(REPLACE(c.fone_res, ' ', ''),1,2), ' ', SUBSTR(REPLACE(c.celular, ' ', ''),3,4), '-',SUBSTR(REPLACE(c.celular, ' ', ''),7,5) ) AS fone_res,
            (SELECT
                COUNT(*)
            FROM base_web_control.venda v
            INNER JOIN base_web_control.venda_notas_eletronicas vnf
                ON vnf.id_venda = v.id
            WHERE v.id_cadastro = c.codLoja
            AND v.situacao = 'C'
            AND vnf.tipo_nota = 'NFC'
            AND vnf.ambiente_nf = 1) AS qtd_nota,
             (SELECT
                COUNT(*)
            FROM base_web_control.venda v
            INNER JOIN base_web_control.venda_notas_eletronicas vnf
                ON vnf.id_venda = v.id
            WHERE v.id_cadastro = c.codLoja
            AND v.situacao = 'C'
            AND vnf.tipo_nota = 'NFE'
            AND vnf.ambiente_nf = 1) AS qtd_nota_eletronica,
            DATE_FORMAT(c.dt_cad, '%d/%m/%Y') AS data_cadastro,
            (SELECT
                login
            FROM base_web_control.webc_usuario
            WHERE id_cadastro = c.codLoja
            LIMIT 1) AS login,
            nfe,
            nfce,
            cte,
            nfse,
            c.liberacao_receita_nfc,
            c.liberacao_receita_nfe,
            c.contador_nome,
            CONCAT(SUBSTR(REPLACE(c.contador_telefone, ' ', ''),1,2), ' ', SUBSTR(REPLACE(c.contador_telefone, ' ', ''),3,4), '-',SUBSTR(REPLACE(c.contador_telefone, ' ', ''),7,5) ) AS fone_res,
            CONCAT(SUBSTR(REPLACE(c.contador_celular, ' ', ''),1,2), ' ', SUBSTR(REPLACE(c.contador_celular, ' ', ''),3,5), '-',SUBSTR(REPLACE(c.contador_celular, ' ', ''),8,5) ),
            c.contador_email1,
            o.descricao AS operadora,
            ac.urgencia,
            ac.data_baixa AS data_baixa,
            ac.id_atendimento
        FROM cs2.cadastro c
        LEFT JOIN cs2.operadora o
        ON o.id = c.id_operadora
        INNER JOIN cs2.atendimento_cadastros ac
        ON ac.id_cadastro = c.codLoja
        WHERE c.sitcli = 0
        AND contadorsn = 'N'
        AND ac.motivo_baixa != 'M'
        AND ac.motivo_baixa != 'O'
        AND (id_franquia = $idFranquiaPesquisa OR classificacao = 'X')
        $sqlWhere
        HAVING (qtd_nota = 1
        AND qtd_nota_eletronica = 1 OR data_baixa IS NOT NULL)
        ORDER BY ac.urgencia DESC, nfe ASC, nfce ASC, dt_cad DESC ;
    ";
//echo '<pre>';
//echo $sqlLista1;
//die;
$res = mysql_query($sqlLista1, $con);

$sqlTotal = "SELECT FOUND_ROWS() AS qtd";
$resTotal = mysql_query($sqlTotal,$con);
$qtdTotal = mysql_result($resTotal,0,'qtd');

?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="../css/assets/css/bootstrap.min.css">
<!---->
<!-- Optional theme -->
<link rel="stylesheet" href="../css/assets/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="../css/assets/css/jquery-ui.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="../css/assets/js/bootstrap.min.js"></script>
<script src="../css/assets/js/jquery-ui.min.js"></script>
<div class="col-md-12 text-center">
    <h3><span class="lista">Relatório de Baixados</span></h3>
</div>
<div class="row col-md-12" style="margin-bottom: 20px;">
    <form action="painel.php?pagina1=clientes/relatorio_habilitacao_notas.php" method="post">
        <div class="col-md-4">
            <select id="iptAtendimento" name="iptAtendimento" class="form-control">
                <option value="0">TODOS</option>
                <option value="1">Atendimento 1</option>
                <option value="2">Atendimento 2</option>
            </select>
        </div>
        <div class="col-md-6">
            <div class="col-md-1">
                <label for="iptDataInicio">Data:</label>
            </div>
            <div class="col-md-4">
                <input type="text" id="iptDataInicio" name="iptDataInicio" class="form-control datepicker">
            </div>
            <div class="col-md-4">
                <input type="text" id="iptDataFim" name="iptDataFim" class="form-control datepicker">
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </form>
</div>
<div class="lista1 col-md-12">
    <table class="table table-bordered table-striped table-hover">
        <thead>
        <tr>
            <td class="text-center">Código</td>
            <td class="text-center">Data Afiliação</td>
            <td class="text-center">Cliente</td>
            <td class="text-center">Atendente</td>
        </tr>
        </thead>
        <tbody>
        <?php
        $i = 1;
        $idxLista1 = 0;
        $linhas = mysql_num_rows($res);
        if ($linhas > 0) {
            while ($arrLista1 = mysql_fetch_array($res)) {
//            echo '<pre>';
//            var_dump($arrLista1);
//            die;

                ?>
                <tr class="text-center" data-id_cadastro="<?php echo $arrLista1['id_cadastro'] ?>"
                    data-tr_id="<?php echo $i ?>">
                    <td class="mostrar" style="cursor: pointer;"><?php echo $arrLista1['login'] ?></td>
                    <td class="mostrar" style="cursor: pointer;"><?php echo $arrLista1['data_cadastro'] ?></td>
                    <td class="col-md-3 mostrar"
                        style="cursor: pointer;"><?php echo $arrLista1['nomefantasia'] ?></td>
                    <td>
                        <?php echo $arrLista1['id_atendimento'] == '1' ? 'Atendimento 1' : 'Atendimento 2' ?>
                    </td>
                </tr>
                <?php
                $i++;
                $idxLista1++;
            }
        } else { ?>
            <tr>
                <td colspan="4" class="text-center">Nenhum registro encontrado!</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <div class="row" style="margin-left: 5px; font-size: 14px; font-weight: bold;">
        Total: <?php echo $qtdTotal ?> Contratos
    </div>
</div>
<script>

    function enviarEmailContador(tipoRegime) {

        $.ajax({
            url: '../php/enviaEmailContador.php',
            data: {
                login: $('#iptLogin').val(),
                email: $('#iptEmail').val(),
                razaosoc: $('#iptRazaoSoc').val(),
                nomefantasia: $('#iptNomeFantasia').val(),
                tipoRegime: tipoRegime,
                cnpj: $('#iptCnpj').val(),
                lista: $('#iptLista').val()

            },
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                alert('E-mail Enviado com sucesso!');
                $('#modalEmailContador').modal('hide');
            }
        })

    }

    $(document).on('click', '.enviarEmailContador', function () {

        var emailContador = $(this).data('email_contador');
        var razaoSocial = $(this).data('razaosoc');
        var cnpj = $(this).data('cnpj');
        var login = $(this).data('login');
        var nomeFantasia = $(this).data('nome_fantasia');
        var lista = $(this).data('lista');

        $('#iptEmail').val(' ');
        $('#iptRazaoSoc').val(' ');
        $('#iptCnpj').val(' ');
        $('#iptLogin').val(' ');
        $('#iptNomeFantasia').val(' ');
        $('#iptLista').val(' ');


        $('#iptEmail').val(emailContador);
        $('#iptRazaoSoc').val(razaoSocial);
        $('#iptCnpj').val(cnpj);
        $('#iptLogin').val(login);
        $('#iptNomeFantasia').val(nomeFantasia);
        $('#iptLista').val(lista);

        $('#modalEmailContador').modal('show');

    });

    $(document).ready(function () {

        $('.datepicker').datepicker({
            dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'S&aacute;bado'],
            dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
            dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'S&aacute;b', 'Dom'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            nextText: 'Próximo',
            prevText: 'Anterior',
            dateFormat: 'dd/mm/yy'
        });

        $('#btnSimplesNacional').on('click', function () {

            enviarEmailContador('SN');

        });

        $('#btnNormal').on('click', function () {
            enviarEmailContador('N');
        });

        $('.btnHabilitarNF').on('click', function () {

            var login = $(this).data('login');
            var id_cadastro = $(this).data('cadastro');
            var razaosoc = $(this).data('razaosoc');

            window.open('../php/painel.php?pagina1=area_restrita/d_nota_fiscal2.php&codloja=' + id_cadastro + '&logon=' + login + '&razaosoc=' + razaosoc + '&hnf=true');

        });

        $('.btnCadastrarOcorrencia').on('click', function () {

            var login = $(this).data('login');
            var id_cadastro = $(this).data('cadastro');
            var razaosoc = $(this).data('razaosoc');

            window.open('../php/painel.php?pagina1=ocorrencias/postar.php&codloja=' + id_cadastro);

        });

        $('#iptSelLista').change(function () {

            if ($(this).val() == '1') {
                $('.lista1').removeClass('hidden');
                $('.lista2').addClass('hidden');
                $('.lista').html('Atendimento 1');
            } else {
                $('.lista2').removeClass('hidden');
                $('.lista1').addClass('hidden');
                $('.lista').html('Atendimento 2');
            }

        });

        $(document).on('click', '.mostrar', function () {

            var id = $(this).parent().data('tr_id');

            $('.div' + id).removeClass('hidden');
            $(this).removeClass('mostrar');
            $(this).addClass('esconder');

        });

        $(document).on('click', '.esconder', function () {

            var id = $(this).parent().data('tr_id');

            $('.div' + id).addClass('hidden');
            $(this).addClass('mostrar');
            $(this).removeClass('esconder');

        });

//        $('.iptReceitaNFE').change(function(){
//
//            var id_cadastro = $(this).parent().parent().data('id_cadastro');
//            console.log(id_cadastro);
//
//        });

        $(document).on('click', '.iptReceitaNFE', function () {
            var id_cadastro = $(this).data('id_cadastro');
            var value = $(this).data('value');
            var objSpan = $(this);

            $.ajax({

                url: 'clientes/habilitacaoNotas.php',
                data: {
                    action: 'receitaNFE',
                    id_cadastro: id_cadastro,
                    value: value
                },
                type: 'POST',
                dataType: 'json',
                success: function (data) {

                    if (data.mensagem == 1) {
                        if (value == 'S') {
                            objSpan.parent().html('<span style="color: darkgreen;" class="glyphicon glyphicon-ok-sign"></span>NFE - <span style="cursor: pointer;" class="iptReceitaNFE" data-id_cadastro="' + id_cadastro + '" data-value="N"><u>Desabilitar</u></span>');
                        } else {
                            objSpan.parent().html('<span style="color: darkred;" class="glyphicon glyphicon-remove"></span>NFE - <span style="cursor: pointer;" class="iptReceitaNFE" data-id_cadastro="' + id_cadastro + '" data-value="S"><u>Habilitar</u></span>');
                        }
                    } else {
                        alert('ocorreu um erro ao salvar');
                    }

                }


            })
        });


        $(document).on('click', '.iptReceitaNFC', function () {
            var id_cadastro = $(this).data('id_cadastro');
            var value = $(this).data('value');
            var objSpan = $(this);

            $.ajax({

                url: 'clientes/habilitacaoNotas.php',
                data: {
                    action: 'receitaNFC',
                    id_cadastro: id_cadastro,
                    value: value
                },
                type: 'POST',
                dataType: 'json',
                success: function (data) {

                    if (data.mensagem == 1) {
                        if (value == 'S') {
                            objSpan.parent().html('<span style="color: darkgreen;" class="glyphicon glyphicon-ok-sign"></span>NFC - <span style="cursor: pointer;" class="iptReceitaNFC" data-id_cadastro="' + id_cadastro + '" data-value="N"><u>Desabilitar</u></span>');
                        } else {
                            objSpan.parent().html('<span style="color: darkred;" class="glyphicon glyphicon-remove"></span>NFC - <span style="cursor: pointer;" class="iptReceitaNFC" data-id_cadastro="' + id_cadastro + '" data-value="S"><u>Habilitar</u></span>');
                        }
                    } else {
                        alert('ocorreu um erro ao salvar');
                    }

                }


            })

        });

        $(document).on('click', '.iptUrgencia', function () {

            var idCadastro = $(this).data('id');

            $.ajax({
                url: 'clientes/habilitacaoNotas.php',
                data: {
                    action: 'marcarUrgencia',
                    id_cadastro: idCadastro
                },
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                }
            })

        });

        $(document).on('click', '.btnBaixar', function () {

            var login = $(this).data('login');
            var idCadastro = $(this).data('id');

            var isConfirm = confirm('Tem certeza que deseja baixar o código : ' + login + ' da listagem?');
            var objLinha = $(this).parent().parent();


            console.log(idCadastro);
            console.log(login);
            if (isConfirm) {

                $.ajax({
                    url: 'clientes/habilitacaoNotas.php',
                    data: {
                        action: 'baixarListagem',
                        id_cadastro: idCadastro
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function (data) {

                        objLinha.remove();
                    }
                })
            }

        });

    });

</script>

