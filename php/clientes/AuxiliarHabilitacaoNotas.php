<?php
/**
 * @file AuxiliarHabilitacaoNotas.php
 * @brief
 * @author ARLLON DIAS
 * @date 31/03/2017
 * @version 1.0
 **/

require "../connect/sessao.php";
require "../connect/conexao_conecta.php";


if ($_POST) {

    switch ($_POST['action']) {

        case 'paginacao':

            $lista = $_POST['lista'];
            $inicio = $_POST['inicio'];

            $i = $_POST['inicio'] * 2;
            $idxLista1 = 0;

            $sqlLista1 = "
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
                AND ac.id_atendimento = $lista
                WHERE c.sitcli = 0
                AND (id_franquia = 1 OR classificacao = 'X')
                AND ac.data_baixa IS NULL
                AND contadorsn = 'N'
                HAVING (qtd_nota = 0
                OR qtd_nota_eletronica = 0)
                ORDER BY ac.urgencia desc, nfe ASC, nfce ASC, dt_cad DESC
                LIMIT $inicio,50;
                ";
            $res = mysql_query($sqlLista1, $con);

            while ($arrLista1 = mysql_fetch_array($res)) {
//            echo '<pre>';
//            var_dump($arrLista1);
//            die;
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
                <tr class="text-center" data-id_cadastro="<?php echo $arrLista1['id_cadastro'] ?>" data-login="<?php echo $arrLista1['login'] ?>" data-senha="<?php echo $arrLista1['senha'] ?>"
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
            }

        break;

        case 'buscaAvancada':

            $busca = $_POST['busca'];

            $sqlLista1 = "
                SELECT
                *
              FROM (SELECT
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
                WHERE c.sitcli = 0
                AND (id_franquia = 1 OR classificacao = 'X')
                AND ac.data_baixa IS NULL
                AND contadorsn = 'N'
                HAVING (qtd_nota = 0
                OR qtd_nota_eletronica = 0)
                ORDER BY ac.urgencia desc, nfe ASC, nfce ASC, dt_cad DESC) AS aux
                WHERE login = '$busca' OR nomefantasia LIKE '%$busca%'";
//            echo '<pre>';
//            echo $sqlLista1;
//            die;
            $res = mysql_query($sqlLista1, $con);

            while ($arrLista1 = mysql_fetch_array($res)) {
//            echo '<pre>';
//            var_dump($arrLista1);
//            die;
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
                <tr class="text-center" data-id_cadastro="<?php echo $arrLista1['id_cadastro'] ?>" data-login="<?php echo $arrLista1['login'] ?>" data-senha="<?php echo $arrLista1['senha'] ?>"
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
            }
        break;
    }


}