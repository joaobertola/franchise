<?php
/**
 * Created by PhpStorm.
 * User: dev02
 * Date: 24/11/2016
 * Time: 13:28
 */

echo " SISTEMA EM MANUNTENÇÃO !!! ";
die;

require "connect/sessao.php";
require "connect/conexao_conecta.php";
require "connect/funcoes.php";

ini_set('display_errors', 1);

$idFranquiaPesquisa = $id_franquia;
if ($id_franquia == 4 || $id_franquia == 163) {
    $idFranquiaPesquisa = 1;
}

$sqlLista1 = "  SELECT SQL_CALC_FOUND_ROWS
                    c.insc,
                    c.razaosoc,
                    c.codLoja AS id_cadastro,
                    c.nomefantasia,
                    c.socio1 AS proprietario,
                    CONCAT(SUBSTR(REPLACE(c.celular, ' ', ''),1,2), ' ', SUBSTR(REPLACE(c.celular, ' ', ''),3,5), '-',SUBSTR(REPLACE(c.celular, ' ', ''),8,5) ) AS celular,
                    c.email,
                    CONCAT(SUBSTR(REPLACE(c.fone, ' ', ''),1,2), ' ', SUBSTR(REPLACE(c.fone, ' ', ''),3,4), '-',SUBSTR(REPLACE(c.fone, ' ', ''),7,5) ) AS fone_res,
                    (SELECT
                        COUNT(*)
                    FROM base_web_control.venda v
                    INNER JOIN base_web_control.venda_notas_eletronicas vnf
                    ON vnf.id_venda = v.id
                    WHERE v.id_cadastro = c.codLoja
                    -- AND v.situacao = 'C'
                    AND vnf.tipo_nota = 'NFC'
                    AND vnf.ambiente_nf = 1) AS qtd_nota,
                    (SELECT
                        COUNT(*)
                    FROM base_web_control.venda v
                    INNER JOIN base_web_control.venda_notas_eletronicas vnf
                    ON vnf.id_venda = v.id
                    WHERE v.id_cadastro = c.codLoja
                    -- AND v.situacao = 'C'
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
                    CONCAT(SUBSTR(REPLACE(c.contador_telefone, ' ', ''),1,2), ' ', SUBSTR(REPLACE(c.contador_telefone, ' ', ''),3,4), '-',SUBSTR(REPLACE(c.contador_telefone, ' ', ''),7,5) ) AS contador_telefone,
                    CONCAT(SUBSTR(REPLACE(c.contador_celular, ' ', ''),1,2), ' ', SUBSTR(REPLACE(c.contador_celular, ' ', ''),3,5), '-',SUBSTR(REPLACE(c.contador_celular, ' ', ''),8,5) ) AS contador_celular,
                    c.contador_email1,
                    o.descricao AS operadora,
                    ac.urgencia,
                    ac.envio_email,
                    (SELECT
                            senha
                    FROM base_web_control.webc_usuario
                    WHERE id_cadastro = c.codLoja
                    AND login_master = 'S' LIMIT 1) AS senha,
                     (SELECT
                        IFNULL(DATEDIFF(NOW(),vencimento),0)
                    FROM cs2.titulos
                    WHERE codloja = c.codLoja
                    AND datapg IS NULL
                    ORDER BY vencimento ASC
                    LIMIT 1) AS diasAtraso,
                    (SELECT sitlog FROM cs2.logon WHERE codloja=c.codLoja LIMIT 1) AS sitlog
                FROM cs2.cadastro c
                LEFT JOIN cs2.operadora o
                ON o.id = c.id_operadora
                INNER JOIN cs2.atendimento_cadastros ac
                ON ac.id_cadastro = c.codLoja
                AND ac.id_atendimento = 1
                WHERE c.sitcli = 0
                AND id_franquia = $idFranquiaPesquisa
                AND ac.data_baixa IS NULL
                HAVING (qtd_nota = 0
                OR qtd_nota_eletronica = 0)
                ORDER BY ac.urgencia desc, nfe ASC, nfce ASC, dt_cad DESC
                LIMIT 1,50;
";

//echo '<pre>';
//echo $sqlLista1;
//die;
$res = mysql_query($sqlLista1, $con);
$sqlContLista1 = "SELECT FOUND_ROWS() AS qtd";
$resCont1 = mysql_query($sqlContLista1, $con);
$qtdLista1 = mysql_result($resCont1, 0, 'qtd');


$i = 0;
$idxPar = 0;
$idxImpar = 0;


$sqlLista2 = "
                SELECT SQL_CALC_FOUND_ROWS
                    c.insc,
                    c.razaosoc,
                    c.codLoja AS id_cadastro,
                    c.nomefantasia,
                    c.socio1 AS proprietario,
                    CONCAT(SUBSTR(REPLACE(c.celular, ' ', ''),1,2), ' ', SUBSTR(REPLACE(c.celular, ' ', ''),3,5), '-',SUBSTR(REPLACE(c.celular, ' ', ''),8,5) ) AS celular,
                    c.email,
                    CONCAT(SUBSTR(REPLACE(c.fone, ' ', ''),1,2), ' ', SUBSTR(REPLACE(c.fone, ' ', ''),3,4), '-',SUBSTR(REPLACE(c.fone, ' ', ''),7,5) ) AS fone_res,
                    (SELECT
                          COUNT(*)
                    FROM base_web_control.venda v
                    INNER JOIN base_web_control.venda_notas_eletronicas vnf
                    ON vnf.id_venda = v.id
                    WHERE v.id_cadastro = c.codLoja
                    -- AND v.situacao = 'C'
                    AND vnf.tipo_nota = 'NFC'
                    AND vnf.ambiente_nf = 1) AS qtd_nota,
                    (SELECT
                            COUNT(*)
                    FROM base_web_control.venda v
                    INNER JOIN base_web_control.venda_notas_eletronicas vnf
                    ON vnf.id_venda = v.id
                    WHERE v.id_cadastro = c.codLoja
                    -- AND v.situacao = 'C'
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
                    CONCAT(SUBSTR(REPLACE(c.contador_telefone, ' ', ''),1,2), ' ', SUBSTR(REPLACE(c.contador_telefone, ' ', ''),3,4), '-',SUBSTR(REPLACE(c.contador_telefone, ' ', ''),7,5) ) AS contador_telefone,
                    CONCAT(SUBSTR(REPLACE(c.contador_celular, ' ', ''),1,2), ' ', SUBSTR(REPLACE(c.contador_celular, ' ', ''),3,5), '-',SUBSTR(REPLACE(c.contador_celular, ' ', ''),8,5) ) AS contador_celular,
                    c.contador_email1,
                    o.descricao AS operadora,
                    ac.urgencia,
                    ac.envio_email,
                    (SELECT senha FROM base_web_control.webc_usuario WHERE id_cadastro = c.codLoja AND login_master = 'S' LIMIT 1) AS senha,
                      (SELECT
                        IFNULL(DATEDIFF(NOW(),vencimento),0)
                    FROM cs2.titulos
                    WHERE codloja = c.codLoja
                    AND datapg IS NULL
                    ORDER BY vencimento ASC
                    LIMIT 1) AS diasAtraso,
                    (SELECT sitlog FROM cs2.logon WHERE codloja=c.codLoja LIMIT 1) AS sitlog
                FROM cs2.cadastro c
                LEFT JOIN cs2.operadora o
                ON o.id = c.id_operadora
                INNER JOIN cs2.atendimento_cadastros ac
                ON ac.id_cadastro = c.codLoja
                AND ac.id_atendimento = 2
                WHERE c.sitcli = 0
                AND (id_franquia = $idFranquiaPesquisa OR classificacao = 'X')
                AND ac.data_baixa IS NULL
                AND contadorsn = 'N'
                HAVING (qtd_nota = 0
                OR qtd_nota_eletronica = 0)
                ORDER BY ac.urgencia desc, nfe asc, nfce asc, dt_cad DESC
                LIMIT 1,50;
";
$res2 = mysql_query($sqlLista2, $con);
$sqlContLista2 = "SELECT FOUND_ROWS() AS qtd";

$resCont2 = mysql_query($sqlContLista2, $con);
$qtdLista2 = mysql_result($resCont2, 0, 'qtd');


?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="../css/assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/assets/css/sweetalert.css">
<!---->
<!-- Optional theme -->
<link rel="stylesheet" href="../css/assets/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="../css/assets/js/bootstrap.min.js"></script>
<script src="../css/assets/js/sweetalert.min.js"></script>
<div class="row" style="margin-bottom: 20px; margin-left: 5px; margin-top: 20px;">
    <div class="col-md-3">
        <label for="iptSelLista">Busca: </label>
        <input id="iptBusca" name="iptBusca" class="form-control"/>
        <button class="btn btn-success" id="btnFiltrar" style="margin-top: 20px;">Filtrar</button>
    </div>
</div>
<?php if ($id_franquia == 163) { ?>
    <div class="row" style="margin-bottom: 20px; margin-left: 5px; margin-top: 20px;">
        <div class="col-md-3">
            <strong>
                Total de Registros: <?php echo (int)$qtdLista1 + (int)$qtdLista2; ?>
            </strong>
        </div>
    </div>
    <div class="row col-md-3 pull-right">
        <button type="button" class="btn btn-primary btnRelBaixados">Relatório de Baixados</button>
    </div>
<?php } ?>
<div class="col-md-12 text-center">
    <h3><span class="lista">Atendimento 1</span></h3>
</div>
<div class="lista1 col-md-12">
    <table class="table table-bordered table-striped table-hover">
        <thead>
        <tr>
            <td class="text-center">Código</td>
            <td class="text-center">Data Afiliação</td>
            <td class="text-center">Cliente</td>
            <td colspan="3" class="text-center">NF Habilitadas</td>
            <td class="text-center">Certificado</td>
            <td class="text-center">Tributação</td>
            <td colspan="2" class="text-center">Habilitar NF Receita</td>
            <td colspan="" class="text-center">Urgência</td>
            <?php if ($id_franquia == 163) { ?>
                <td colspan="" class="text-center">Baixar</td>
            <?php } ?>
        </tr>
        </thead>
        <tbody class="tbodyLista1" id="tbodyLista1">
        <?php
        $i = 1;
        $idxLista1 = 0;

        while ($arrLista1 = mysql_fetch_array($res)) {

            $idCadastro = $arrLista1['id_cadastro'];
            $insc = $arrLista1['insc'];
            $idCadastro = $arrLista1['id_cadastro'];

            $sqlCertificado = "SELECT
                            *
                        FROM certificadosclientes.certificado
                        WHERE cod_cliente = '$idCadastro'
                        AND cnpj = '$insc';";


            $rsCert = mysql_query($sqlCertificado, $connf);
            $certificado = 0;
            if (mysql_num_rows($rsCert) > 0) {
                $certificado = 1;
            }

            $sqlTributacao = "SELECT
                                    *
                                FROM base_web_control.cadastro_imposto_padrao
                                WHERE id_cadastro = '$idCadastro'
                                AND nfe_tipo_ambiente = 'P'
                                AND nfe_sequencia_nota != ''
                                AND nfe_sequencia_nota IS NOT NULL
                                AND nfce_tipo_ambiente = 'P'
                                AND nfce_csc_token IS NOT NULL
                                AND nfce_csc_token != ''
                                AND nfce_idtoken != ''
                                AND nfce_idtoken IS NOT NULL
                                AND nfce_data_ativacao IS NOT NULL
                                AND nfce_data_ativacao != ''
                                AND nfce_hora_ativacao IS NOT NULL
                                AND nfce_hora_ativacao != ''
                                AND nfce_sequencia_nota IS NOT NULL
                                AND nfce_sequencia_nota != ''
                                ";

            $rsTrib = mysql_query($sqlTributacao, $con);
            $tributacao = 0;
            if (mysql_num_rows($rsTrib) > 0) {
                $tributacao = 1;
            }

            $sqlDataInicio = "SELECT
                                    DATE_FORMAT(MIN(data),'%d/%m/%Y') AS data_inicio
                                FROM cs2.historico_nfe
                                WHERE codLoja = $idCadastro
                                AND (mensagem LIKE '%Nfe%' || mensagem LIKE '%Nfc%')";

            $rs = mysql_query($sqlDataInicio, $con);
            $dataInicio = mysql_fetch_array($rs);
            $dataInicio = $dataInicio[0];
            $cor = '';
            // VERIFICA SE ESTÁ INADIMPLENTE
            if($arrLista1['diasAtraso'] > 0){
                $cor = 'style="background-color: #CD5C5C;"';
            }

            // VERIFICA SE ESTÁ BLOQUEADO
            if($arrLista1['sitlog'] == '1'){
                $cor = 'style="background-color: #EEDD82;"';
            }
            ?>


            <tr class="text-center" data-id_cadastro="<?php echo $arrLista1['id_cadastro'] ?>"
                data-login="<?php echo $arrLista1['login'] ?>" data-senha="<?php echo $arrLista1['senha'] ?>"
                data-tr_id="<?php echo $i ?>"
                <?php echo $cor ?>>
                <td class="logarWc" style="cursor: pointer;"><?php echo $arrLista1['login'] ?></td>
                <td class="mostrar" style="cursor: pointer;"><?php echo $arrLista1['data_cadastro'] ?></td>
                <td class="col-md-3 mostrar"
                    style="cursor: pointer;"><?php echo $arrLista1['nomefantasia'] ?></td>
                <td class="mostrar"
                    style="cursor: pointer;"><?php echo $arrLista1['nfe'] == 'S' ? 'NFE <span class="glyphicon glyphicon-ok-sign" style="color: darkgreen"></span>' : 'NFE <span class="glyphicon glyphicon-remove" style="color: darkred"></span>' ?></td>
                <td class="mostrar"
                    style="cursor: pointer;"><?php echo $arrLista1['nfce'] == 'S' ? 'NFC <span class="glyphicon glyphicon-ok-sign" style="color: darkgreen"></span>' : 'NFC <span class="glyphicon glyphicon-remove" style="color: darkred"></span>' ?></td>
                <td class="mostrar"
                    style="cursor: pointer;"><?php echo $arrLista1['nfse'] == 'S' ? 'NFS <span class="glyphicon glyphicon-ok-sign" style="color: darkgreen"></span>' : 'NFS <span class="glyphicon glyphicon-remove" style="color: darkred"></span>' ?></td>
                <td class="mostrar"
                    style="cursor: pointer;"><?php echo $certificado == 1 ? '<span class="glyphicon glyphicon-ok-sign" style="color: darkgreen"></span>' : '<span class="glyphicon glyphicon-remove" style="color: darkred"></span>' ?></td>
                <td class="mostrar"
                    style="cursor: pointer;"><?php echo $tributacao == 1 ? '<span class="glyphicon glyphicon-ok-sign" style="color: darkgreen"></span>' : '<span class="glyphicon glyphicon-remove" style="color: darkred"></span>' ?></td>
                <td>
                    <?php echo $arrLista1['liberacao_receita_nfc'] == 'N' ? '<span style="color: darkred;" class="glyphicon glyphicon-remove"></span>NFC - <span style="cursor: pointer;" class="iptReceitaNFC" data-id_cadastro="' . $idCadastro . '" data-value="S"><u>Habilitar</u></span>' : '<span style="color: darkgreen;" class="glyphicon glyphicon-ok-sign"></span>NFC -<span style="cursor: pointer;" class="iptReceitaNFC" data-id_cadastro="' . $idCadastro . '" data-value="N"><u>Desabilitar</u></span>' ?>
                </td>
                <td>
                    <?php echo $arrLista1['liberacao_receita_nfe'] == 'N' ? '<span style="color: darkred;" class="glyphicon glyphicon-remove"></span>NFE -<span style="cursor: pointer;" class="iptReceitaNFE" data-id_cadastro="' . $idCadastro . '" data-value="S"><u>Habilitar</u></span>' : '<span style="color: darkgreen;" class="glyphicon glyphicon-ok-sign"></span>NFE -<span style="cursor: pointer;" class="iptReceitaNFE" data-id_cadastro="' . $idCadastro . '" data-value="N"><u>Desabilitar</u></span>' ?>
                </td>
                <td class="iptUrgencia"
                    style="cursor: pointer;">
                    <input type="checkbox" name="iptUrgencia" id="iptUrgencia" class="iptUrgencia" value="1"
                           data-id="<?php echo $arrLista1['id_cadastro'] ?>" <?php echo $arrLista1['urgencia'] == 1 ? 'checked' : '' ?>>
                </td>
                <?php if ($id_franquia == 163) { ?>
                    <td colspan="" class="text-center"><span class="glyphicon glyphicon-arrow-down btnBaixar"
                                                             data-id="<?php echo $arrLista1['id_cadastro'] ?>"
                                                             data-login="<?php echo $arrLista1['login'] ?>"
                                                             style="color: darkgreen; cursor: pointer;"></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td colspan="12" class="hidden div<?php echo $i ?>">
                    <div class="col-md-12 div<?php echo $i ?> hidden" style="margin-top: 5px; margin-bottom: 5px;">
                        <div class="col-md-4 pull-left">
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="text-left">
                                    Proprietário: <?php echo $arrLista1['proprietario'] ?>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="text-left">
                                    Telefone : <?php echo trim($arrLista1['fone_res']) ?>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="text-left">
                                    Celular : <?php echo trim($arrLista1['celular']) ?>
                                    - <?php echo $arrLista1['operadora']; ?>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="text-left">
                                    E-mail: <a
                                        href="mailto: <?php echo trim($arrLista1['email']) ?>"><u><?php echo trim($arrLista1['email']) ?></u></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="text-left">
                                    Contador: <?php echo $arrLista1['contador_nome'] ?>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="text-left">
                                    Telefone : <?php echo trim($arrLista1['contador_telefone']) ?>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="text-left">
                                    Celular : <?php echo trim($arrLista1['contador_celular']) ?>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="text-left">
                                    E-mail: <a
                                        href="mailto: <?php echo trim($arrLista1['contador_email1']) ?>"><u><?php echo trim($arrLista1['contador_email1']) ?></u></a>
                                </div>
                            </div>
                        </div>

                        <div class="row col-md-12">
                            <div class="col-md-4 text-center">
                                <button id="btnHabilitarNF" name="btnHabilitarNF"
                                        class="btnHabilitarNF btn btn-success col-md-10"
                                        data-login="<?php echo $arrLista1['login'] ?>"
                                        data-cadastro="<?php echo $arrLista1['id_cadastro'] ?>"
                                        data-razaosoc="<?php echo $arrLista1['razaosoc  '] ?>">Habilitar Nota Fiscal
                                </button>
                            </div>
                            <div class="col-md-4 text-center">
                                <button id="enviarEmailContador" name="enviarEmailContador"
                                        class="enviarEmailContador btn btn-default col-md-10"
                                        data-login="<?php echo $arrLista1['login'] ?>"
                                        data-cadastro="<?php echo $arrLista1['id_cadastro'] ?>"
                                        data-razaosoc="<?php echo $arrLista1['razaosoc'] ?>"
                                        data-nome_fantasia="<?php echo $arrLista1['nomefantasia'] ?>"
                                        data-email_contador="<?php echo $arrLista1['contador_email1'] ?>"
                                        data-cnpj="<?php echo $arrLista1['insc'] ?>"
                                        data-lista="1">Enviar Email
                                    Contador<?php echo $arrLista1['envio_email'] == 'S' ? '&nbsp;&nbsp;<span class="glyphicon glyphicon-ok-sign" style="color: darkgreen"></span>' : '' ?>

                                </button>
                            </div>
                            <div class="col-md-4 text-center">
                                <button id="btnCadastrarOcorrencia" name="btnCadastrarOcorrencia"
                                        class="btnCadastrarOcorrencia btn btn-primary col-md-10"
                                        data-login="<?php echo $arrLista1['login'] ?>"
                                        data-cadastro="<?php echo $arrLista1['id_cadastro'] ?>"
                                        data-razaosoc="<?php echo $arrLista1['razaosoc  '] ?>">Registrar Ocorrência
                                </button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>


            <?php
            $i++;
            $idxLista1++;
        } ?>
        </tbody>
    </table>
    <table class="table table-bordered table-striped table-hover">
        <tbody>
        <tr>
            <input type="hidden" id="iptTotalRegistrosLista1" name="iptTotalRegistrosLista1"
                   value="<?php echo (int)$qtdLista1 - 50 ?>">
            <input type="hidden" id="iptPaginaLista1" name="iptPaginaLista1" value="1">
            <td align="center" class="paginacaoLista1" style="cursor: pointer"><span
                    class="glyphicon glyphicon-plus-sign" style="font-size: 15px; color: darkgreen"></span>&nbsp;(Mais
                <span class="qtdRegistrosLista1"><?php echo (int)$qtdLista1 - 50 ?></span>)
            </td>
        </tr>
        </tbody>
    </table>
</div>
<?php
$idxLista2 = 0;
?>
<div class="lista2 col-md-12 hidden">
    <table class="table table-bordered table-striped table-hover">
        <thead>
        <tr>
            <td class="text-center">Código</td>
            <td class="text-center">Data Afiliação</td>
            <td class="text-center">Cliente</td>
            <td colspan="3" class="text-center">NF Habilitadas</td>
            <td class="text-center">Certificado</td>
            <td class="text-center">Tributação</td>
            <td colspan="2" class="text-center">Habilitar NF Receita</td>
            <td colspan="" class="text-center">Data Início</td>
            <td colspan="" class="text-center">Urgência</td>
            <?php if ($id_franquia == 163) { ?>
                <td colspan="" class="text-center">Baixar</td>
            <?php } ?>
        </tr>
        </thead>
        <tbody id="tbodyLista2" class="tbodyLista2">
        <?php
        while ($arrLista2 = mysql_fetch_array($res2)) {

            $idCadastro = $arrLista2['id_cadastro'];
            $insc = $arrLista2['insc'];

            $sqlCertificado = "SELECT
                            *
                        FROM certificadosclientes.certificado
                        WHERE cod_cliente = '$idCadastro'
                        AND cnpj = '$insc';";

            $rsCert = mysql_query($sqlCertificado, $connf);

            $certificado = 0;
            if (mysql_num_rows($rsCert) > 0) {
                $certificado = 1;
            }

            $sqlTributacao = "SELECT
                                    *
                                FROM base_web_control.cadastro_imposto_padrao
                                WHERE id_cadastro = '$idCadastro'
                                AND nfe_tipo_ambiente = 'P'
                                AND nfe_sequencia_nota != ''
                                AND nfe_sequencia_nota IS NOT NULL
                                AND nfce_tipo_ambiente = 'P'
                                AND nfce_csc_token IS NOT NULL
                                AND nfce_csc_token != ''
                                AND nfce_idtoken != ''
                                AND nfce_idtoken IS NOT NULL
                                AND nfce_data_ativacao IS NOT NULL
                                AND nfce_data_ativacao != ''
                                AND nfce_hora_ativacao IS NOT NULL
                                AND nfce_hora_ativacao != ''
                                AND nfce_sequencia_nota IS NOT NULL
                                AND nfce_sequencia_nota != ''
                                ";

            $rsTrib = mysql_query($sqlTributacao, $con);
            $tributacao = 0;
            if (mysql_num_rows($rsCert) > 0) {
                $tributacao = 1;
            }

            $sqlDataInicio = "SELECT
                                    DATE_FORMAT(MIN(data),'%d/%m/%Y') AS data_inicio
                                FROM cs2.historico_nfe
                                WHERE codLoja = $idCadastro
                                AND (mensagem LIKE '%Nfe%' || mensagem LIKE '%Nfc%')";

            $rs = mysql_query($sqlDataInicio, $con);
            $dataInicio = mysql_fetch_array($rs);
            $dataInicio = $dataInicio[0];

            $cor = '';
            // VERIFICA SE ESTÁ INADIMPLENTE
            if($arrLista1['diasAtraso'] > 0){
                $cor = 'style="background-color: #CD5C5C;"';
            }

            // VERIFICA SE ESTÁ BLOQUEADO
            if($arrLista1['sitlog'] == '1'){
                $cor = 'style="background-color: #EEDD82;"';
            }

            ?>
            <tr class="text-center" data-id_cadastro="<?php echo $arrLista2['id_cadastro'] ?>"
                data-login="<?php echo $arrLista2['login'] ?>" data-senha="<?php echo $arrLista2['senha'] ?>"
                data-tr_id="<?php echo $i ?>"
                <?php echo $cor ?>>
                <td class="logarWc" style="cursor: pointer;"><?php echo $arrLista2['login'] ?></td>
                <td class="mostrar" style="cursor: pointer;"><?php echo $arrLista2['data_cadastro'] ?></td>
                <td class="col-md-3 mostrar"
                    style="cursor: pointer;"><?php echo $arrLista2['nomefantasia'] ?></td>
                <td class="mostrar"
                    style="cursor: pointer;"><?php echo $arrLista2['nfe'] == 'S' ? 'NFE <span class="glyphicon glyphicon-ok-sign" style="color: darkgreen"></span>' : 'NFE <span class="glyphicon glyphicon-remove" style="color: darkred"></span>' ?></td>
                <td class="mostrar"
                    style="cursor: pointer;"><?php echo $arrLista2['nfce'] == 'S' ? 'NFC <span class="glyphicon glyphicon-ok-sign" style="color: darkgreen"></span>' : 'NFC <span class="glyphicon glyphicon-remove" style="color: darkred"></span>' ?></td>
                <td class="mostrar"
                    style="cursor: pointer;"><?php echo $arrLista2['nfse'] == 'S' ? 'NFS <span class="glyphicon glyphicon-ok-sign" style="color: darkgreen"></span>' : 'NFS <span class="glyphicon glyphicon-remove" style="color: darkred"></span>' ?></td>
                <td class="mostrar"
                    style="cursor: pointer;"><?php echo $certificado == 1 ? '<span class="glyphicon glyphicon-ok-sign" style="color: darkgreen"></span>' : '<span class="glyphicon glyphicon-remove" style="color: darkred"></span>' ?></td>
                <td class="mostrar"
                    style="cursor: pointer;"><?php echo $tributacao == 1 ? '<span class="glyphicon glyphicon-ok-sign" style="color: darkgreen"></span>' : '<span class="glyphicon glyphicon-remove" style="color: darkred"></span>' ?></td>

                <td>
                    <?php echo $arrLista2['liberacao_receita_nfc'] == 'N' ? '<span style="color: darkred;" class="glyphicon glyphicon-remove"></span>NFC - <span style="cursor: pointer;" class="iptReceitaNFC" data-id_cadastro="' . $idCadastro . '" data-value="S"><u>Habilitar</u></span>' : '<span style="color: darkgreen;" class="glyphicon glyphicon-ok-sign"></span>NFC -<span style="cursor: pointer;" class="iptReceitaNFC" data-id_cadastro="' . $idCadastro . '" data-value="N"><u>Desabilitar</u></span>' ?>
                </td>
                <td>
                    <?php echo $arrLista2['liberacao_receita_nfe'] == 'N' ? '<span style="color: darkred;" class="glyphicon glyphicon-remove"></span>NFE -<span style="cursor: pointer;" class="iptReceitaNFE" data-id_cadastro="' . $idCadastro . '" data-value="S"><u>Habilitar</u></span>' : '<span style="color: darkgreen;" class="glyphicon glyphicon-ok-sign"></span>NFE -<span style="cursor: pointer;" class="iptReceitaNFE" data-id_cadastro="' . $idCadastro . '" data-value="N"><u>Desabilitar</u></span>' ?>
                </td>
                <td>
                    <?php echo $dataInicio ?>
                </td>
                <td class="iptUrgencia"
                    style="cursor: pointer;">
                    <input type="checkbox" name="iptUrgencia" id="iptUrgencia" class="iptUrgencia" value="1"
                           data-id="<?php echo $arrLista2['id_cadastro'] ?>" <?php echo $arrLista2['urgencia'] == 1 ? 'checked' : '' ?>>
                </td>
                <?php if ($id_franquia == 163) { ?>
                    <td colspan="" class="text-center"><span class="glyphicon glyphicon-arrow-down btnBaixar"
                                                             data-id="<?php echo $arrLista2['id_cadastro'] ?>"
                                                             data-login="<?php echo $arrLista2['login'] ?>"
                                                             style="color: darkgreen; cursor: pointer;"></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td colspan="13" class=" div<?php echo $i ?> hidden">
                    <div class="col-md-12 div<?php echo $i ?> hidden" style="margin-top: 5px; margin-bottom: 5px;">
                        <div class="col-md-4 pull-left">
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="text-left">
                                    Proprietário: <?php echo $arrLista2['proprietario'] ?>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="text-left">
                                    Telefone : <?php echo trim($arrLista2['fone_res']) ?>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="text-left">
                                    Celular : <?php echo trim($arrLista2['celular']) ?>
                                    - <?php echo $arrLista2['operadora']; ?>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="text-left">
                                    E-mail: <a
                                        href="mailto: <?php echo trim($arrLista2['email']) ?>"><u><?php echo trim($arrLista2['email']) ?></u></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="text-left">
                                    Contador: <?php echo $arrLista2['contador_nome'] ?>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="text-left">
                                    Telefone : <?php echo trim($arrLista2['contador_telefone']) ?>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="text-left">
                                    Celular : <?php echo trim($arrLista2['contador_celular']) ?>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="text-left">
                                    E-mail: <a
                                        href="mailto: <?php echo trim($arrLista2['contador_email1']) ?>"><u><?php echo trim($arrLista2['contador_email1']) ?></u></a>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="col-md-4 text-center">
                                <button id="btnHabilitarNF" name="btnHabilitarNF"
                                        class="btnHabilitarNF btn btn-success col-md-10"
                                        data-login="<?php echo $arrLista2['login'] ?>"
                                        data-cadastro="<?php echo $arrLista2['id_cadastro'] ?>"
                                        data-razaosoc="<?php echo $arrLista2['razaosoc  '] ?>">Habilitar Nota Fiscal
                                </button>
                            </div>
                            <div class="col-md-4 text-center">
                                <button id="enviarEmailContador" name="enviarEmailContador"
                                        class="enviarEmailContador btn btn-default col-md-10"
                                        data-login="<?php echo $arrLista2['login'] ?>"
                                        data-cadastro="<?php echo $arrLista2['id_cadastro'] ?>"
                                        data-razaosoc="<?php echo $arrLista2['razaosoc'] ?>"
                                        data-email_contador="<?php echo $arrLista2['contador_email1'] ?>"
                                        data-nome_fantasia="<?php echo $arrLista2['nomefantasia'] ?>"
                                        data-cnpj="<?php echo $arrLista2['insc'] ?>"
                                        data-lista="2">Enviar Email Contador
                                </button>
                            </div>
                            <div class="col-md-4 text-center">
                                <button id="btnCadastrarOcorrencia" name="btnCadastrarOcorrencia"
                                        class="btnCadastrarOcorrencia btn btn-primary col-md-10"
                                        data-login="<?php echo $arrLista2['login'] ?>"
                                        data-cadastro="<?php echo $arrLista2['id_cadastro'] ?>"
                                        data-razaosoc="<?php echo $arrLista2['razaosoc'] ?>">Registrar Ocorrência
                                </button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>

            <?php
            $i++;
            $idxLista2++;
        }
        ?>
        </tbody>
    </table>
    <table class="table table-bordered table-striped table-hover">
        <tbody>
        <tr>
            <input type="hidden" id="iptTotalRegistrosLista2" name="iptTotalRegistrosLista2"
                   value="<?php echo (int)$qtdLista2 - 50 ?>">
            <input type="hidden" id="iptPaginaLista2" name="iptPaginaLista2" value="1">
            <td align="center" style="cursor: pointer" class="paginacaoLista2"><span
                    class="glyphicon glyphicon-plus-sign" style="font-size: 15px; color: darkgreen"></span>&nbsp;(Mais
                <span class="qtdRegistrosLista2"><?php echo (int)$qtdLista2 - 50 ?></span>)
            </td>
        </tr>
        </tbody>
    </table>

</div>
<div class="modal fade" tabindex="-1" role="dialog" id="modalEmailContador">
    <div id="" class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Enviar E-mail Contador</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="iptEmail">E-mail</label>
                    <input type="text" name="iptEmail" id="iptEmail" class="form-control"/>
                    <input type="hidden" name="iptRazaoSoc" id="iptRazaoSoc" class="form-control"/>
                    <input type="hidden" name="iptCnpj" id="iptCnpj" class="form-control"/>
                    <input type="hidden" name="iptLogin" id="iptLogin" class="form-control"/>
                    <input type="hidden" name="iptNomeFantasia" id="iptNomeFantasia" class="form-control"/>
                    <input type="hidden" name="iptLista" id="iptLista" class="form-control"/>
                    <input type="hidden" name="iptIdCadastro" id="iptIdCadastro" class="form-control"/>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <button type="button" id="btnSimplesNacional" class="btn btn-primary">Simples Nacional</button>
                    </div>
                    <div class="col-md-3 col-md-offset-1">
                        <button type="button" id="btnNormal" class="btn btn-primary">Normal</button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" id="btnPendencia" class="btn btn-primary">Pendência</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->
<form name="loginWebControl" id="loginWebControl" method="post"
      action="https://www.webcontrolempresas.com.br/webcontrol/efetiva/LoginEfetiva.php">
    <input type="hidden" name="iptIdCadastro">
    <input type="hidden" name="iptSenha">
    <input type='submit' id="btnEnviarNovaAba" class="hidden" onclick="this.form.target='_blank';return true;"
           style="display: none;">
</form>

<div class="modal fade" tabindex="-1" role="dialog" id="modalBaixa">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header" style="text-align: center;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Motivo da Baixa</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin: 15px;">
                    <input type="radio" name="iptMotivoBaixa" id="iptMotivoBaixa" checked value="M"> MEI<br>
                    <input type="radio" name="iptMotivoBaixa" id="iptMotivoBaixa" value="N"> Não utilizará todas as NF's<br>
                    <input type="radio" name="iptMotivoBaixa" id="iptMotivoBaixa" value="O"> Outros
                </div>
            </div>
            <div class="modal-footer" style="text-align: right">
                <input type="hidden" id="iptLoginBaixa">
                <input type="hidden" id="iptCadastroBaixa">
                <button type="button" id="btnSalvarBaixa" class="btn btn-primary pull-right" style="width: 80px;">Salvar</button>
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal" style="width: 80px; margin-left: 10px; margin-right: 10px;">Fechar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
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
                lista: $('#iptLista').val(),
                idCadastro: $('#iptIdCadastro').val()

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
        var idCadastro = $(this).data('cadastro');

        $('#iptEmail').val(' ');
        $('#iptRazaoSoc').val(' ');
        $('#iptCnpj').val(' ');
        $('#iptLogin').val(' ');
        $('#iptNomeFantasia').val(' ');
        $('#iptLista').val(' ');
        $('#iptIdCadastro').val(' ');


        $('#iptEmail').val(emailContador);
        $('#iptRazaoSoc').val(razaoSocial);
        $('#iptCnpj').val(cnpj);
        $('#iptLogin').val(login);
        $('#iptNomeFantasia').val(nomeFantasia);
        $('#iptLista').val(lista);
        $('#iptIdCadastro').val(idCadastro);

        $('#modalEmailContador').modal('show');

    });

    $(document).ready(function () {


        $('.logarWc').on('click', function () {

            var login = $(this).parent().data('login');
            var senha = $(this).parent().data('senha');

            $('input[name="iptIdCadastro"]').val(login);
            $('input[name="iptSenha"]').val(senha);

            $('#btnEnviarNovaAba').trigger('click');
        });

        $('#btnSimplesNacional').on('click', function () {

            enviarEmailContador('SN');

        });

        $('#btnNormal').on('click', function () {
            enviarEmailContador('N');
        });


        $('#btnPendencia').on('click', function () {
            enviarEmailContador('P');
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

            window.open('../php/painel.php?pagina1=ocorrencias/mensagens.php&codloja=' + id_cadastro);

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

            $('#iptLoginBaixa').val(login);
            $('#iptCadastroBaixa').val(idCadastro);

            $('#modalBaixa').modal('show');


        });



        $('.btnRelBaixados').on('click', function () {

            window.open('../php/painel.php?pagina1=clientes/relatorio_habilitacao_notas.php')

        });


        $('#btnSalvarBaixa').on('click', function(){

            $.ajax({
                url: 'clientes/habilitacaoNotas.php',
                data: {
                    action: 'baixarListagem',
                    id_cadastro: $('#iptCadastroBaixa').val(),
                    motivo: $('input[name="iptMotivoBaixa"]:checked').val()
                },
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    location.reload();
                }
            })

        })

    });


    $(document).on('click', '.paginacaoLista1', function () {

        var proximoRegistro = (parseInt($('#iptPaginaLista1').val()) * 50) + 1;
        var registrosRestantes = $('#iptTotalRegistrosLista1').val() - 50;
        swal({title: "", text: "Carregando...", showConfirmButton: false, imageUrl: "clientes/loading2.gif"});
        $.ajax({
            url: 'clientes/AuxiliarHabilitacaoNotas.php',
            data: {
                action: 'paginacao',
                inicio: proximoRegistro,
                lista: 1

            },
            type: 'POST',
            success: function (data) {
                //console.log(data);
                $('#tbodyLista1').append(data);
                swal.close();
            }
        });

        if (registrosRestantes <= 0) {
            registrosRestantes = 0;
            $('.paginacaoLista1').remove();
        }
        $('.qtdRegistrosLista1').html(registrosRestantes);

        $('#iptTotalRegistrosLista1').val(registrosRestantes);
        $('#iptPaginaLista1').val(parseInt($('#iptPaginaLista1').val()) + 1);


    });

    $(document).on('click', '.paginacaoLista2', function () {

        var proximoRegistro = (parseInt($('#iptPaginaLista2').val()) * 50) + 1;
        var registrosRestantes = $('#iptTotalRegistrosLista2').val() - 50;
        swal({title: "", text: "Carregando...", showConfirmButton: false, imageUrl: "clientes/loading2.gif"});
        $.ajax({
            url: 'clientes/AuxiliarHabilitacaoNotas.php',
            data: {
                action: 'paginacao',
                inicio: proximoRegistro,
                lista: 2

            },
            type: 'POST',
            success: function (data) {
                //console.log(data);
                $('#tbodyLista2').append(data);
                swal.close();
            }
        });

        if (registrosRestantes <= 0) {
            registrosRestantes = 0;
            $('.paginacaoLista2').remove();
        }
        $('.qtdRegistrosLista2').html(registrosRestantes);

        $('#iptTotalRegistrosLista2').val(registrosRestantes);
        $('#iptPaginaLista2').val(parseInt($('#iptPaginaLista2').val()) + 1);


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

    $(document).on('click', '.logarWc', function () {

        var login = $(this).parent().data('login');
        var senha = $(this).parent().data('senha');

        $('input[name="iptIdCadastro"]').val(login);
        $('input[name="iptSenha"]').val(senha);

        $('#btnEnviarNovaAba').trigger('click');
    });


    $('#btnFiltrar').on('click', function () {

        if($('#iptBusca').val() == ''){
            location.reload();
            return false;
        }
        swal({title: "", text: "Carregando...", showConfirmButton: false, imageUrl: "clientes/loading2.gif"});
        $.post('clientes/AuxiliarHabilitacaoNotas.php', {
            action: 'buscaAvancada',
            busca: $('#iptBusca').val()
        }, function (data) {
            $('#tbodyLista1').html(data);
            $('.paginacaoLista1').remove();
            swal.close();
        });
    });


</script>

