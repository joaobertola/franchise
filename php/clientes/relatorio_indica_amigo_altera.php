<script language="javascript">
    
function deletar(idReg,idRegLog){
    if (confirm("Confirma a EXCLUSÃO deste Registro ?")) {
    } else {
        return false
    }
    var req = new XMLHttpRequest();
    req.open('POST', "clientes/relatorio_indica_amigo_salva.php?action=deletaRegistro&iptIdIndicacao="+idReg+"&iptIdIndicacaoLog=" + idRegLog, false);
    req.send(null);
    if (req.status != 200)
        return '';
    
    location.href = "../php/painel.php?pagina1=clientes/relatorio_indica_amigo_altera.php&idIndicacao="+idReg;
}
</script>
<?php
require "connect/sessao.php";
require "connect/conexao_conecta.php";
require "connect/funcoes.php";

function retornaMesesBonificar($idIndicadao, $con) {

    $sqlAux = "DELETE FROM base_web_control.tmp_meses_label";
    mysql_query($sqlAux, $con);

   // $sqlAux = "CREATE TABLE base_web_control.tmp_meses_label(num_mes INT, mes_label VARCHAR(20));";
   // mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_meses_label(num_mes,mes_label)
    VALUES('1','JAN');";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_meses_label(num_mes,mes_label)
    VALUES('2','FEV');";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_meses_label(num_mes,mes_label)
    VALUES('3','MAR');";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_meses_label(num_mes,mes_label)
    VALUES('4','ABR');";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_meses_label(num_mes,mes_label)
    VALUES('5','MAI');";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_meses_label(num_mes,mes_label)
    VALUES('6','JUN');";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_meses_label(num_mes,mes_label)
    VALUES('7','JUL');";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_meses_label(num_mes,mes_label)
    VALUES('8','AGO');";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_meses_label(num_mes,mes_label)
    VALUES('9','SET');";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_meses_label(num_mes,mes_label)
    VALUES('10','OUT');";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_meses_label(num_mes,mes_label)
    VALUES('11','NOV');";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_meses_label(num_mes,mes_label)
    VALUES('12','DEZ');";
    mysql_query($sqlAux, $con);

    $sqlAux = "DELETE FROM base_web_control.tmp_datas;";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_datas(data_fatura)
    SELECT NOW();";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_datas(data_fatura)
    SELECT NOW() + INTERVAL 1 MONTH;";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_datas(data_fatura)
    SELECT NOW() + INTERVAL 2 MONTH;";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_datas(data_fatura)
    SELECT NOW() + INTERVAL 3 MONTH;";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_datas(data_fatura)
    SELECT NOW() + INTERVAL 4 MONTH;";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_datas(data_fatura)
    SELECT NOW() + INTERVAL 5 MONTH;";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_datas(data_fatura)
    SELECT NOW() + INTERVAL 6 MONTH;";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_datas(data_fatura)
    SELECT NOW() + INTERVAL 7 MONTH;";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_datas(data_fatura)
    SELECT NOW() + INTERVAL 8 MONTH;";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_datas(data_fatura)
    SELECT NOW() + INTERVAL 9 MONTH;";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_datas(data_fatura)
    SELECT NOW() + INTERVAL 10 MONTH;";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_datas(data_fatura)
    SELECT NOW() + INTERVAL 11 MONTH;";
    mysql_query($sqlAux, $con);
    
    $sqlAux = "DELETE FROM base_web_control.tmp_fat_bonificada;";
    mysql_query($sqlAux, $con);

    $sqlAux = "INSERT INTO base_web_control.tmp_fat_bonificada(fat_bonificada)
    SELECT
        DISTINCT ia2.fatura_bonificar
        FROM base_web_control.indica_amigo ia
        LEFT JOIN base_web_control.indica_amigo ia2
        ON ia2.id_cadastro = ia.id_cadastro
        LEFT JOIN base_web_control.indica_amigo_log i
        ON i.id_indicacao = ia2.id
        WHERE ia.id = '$idIndicadao'
        AND ia2.fatura_bonificar IS NOT NULL;";
    mysql_query($sqlAux, $con);

    $sqlAux = "DELETE
        td.*
    FROM base_web_control.tmp_datas td
    INNER JOIN base_web_control.tmp_meses_label tml
    ON tml.num_mes = MONTH(data_fatura)
    INNER JOIN base_web_control.tmp_fat_bonificada tfb
    ON tfb.fat_bonificada = CONCAT(tml.mes_label,'/',YEAR(td.data_fatura));";
    mysql_query($sqlAux, $con);

    $sqlAux = "
        SELECT 0 AS id, 'DEPOSITO C/C' AS descricao
        UNION
        SELECT
            id, CONCAT(tml.mes_label,'/',YEAR(td.data_fatura)) AS descricao
    FROM base_web_control.tmp_datas td
    INNER JOIN base_web_control.tmp_meses_label tml
    ON tml.num_mes = MONTH(td.data_fatura)
        ORDER BY id ASC
    LIMIT 6
        ";
    return mysql_query($sqlAux, $con);
 
}

if ($_GET['msg'] == 2) {
    ?>
    <script>
        alert('Ocorreu um erro ao salvar');
    </script>
    <?php
}

$idIndicacao = $_GET['idIndicacao'];

$qryFatBonificar = retornaMesesBonificar($idIndicacao, $con);

//GRAVA NOS STATUS CASO SEJA SUBMETIDO

if (isset($_POST) && $_POST['rdStatus'] != '') {
    //print_r($_POST);
    $novoCliente = ($_POST['txtCodNovoCliente']) ? $_POST['txtCodNovoCliente'] : '';

    $fatura_bonificada = $_POST['fatura_bonificar'];
    $id = $_POST['txtIdIndicacao'];

    $sql3 = "INSERT INTO
          base_web_control.indica_amigo_log(
          id_indicacao,
          status_indicacao,
          cod_cliente_vr,
          dt_nota,
          desc_nota
          )
          values(
          '" . $_POST['txtIdIndicacao'] . "',
          '" . $_POST['rdStatus'] . "',
          '" . $novoCliente . "',
          '" . $_POST['txtDataStatus'] . "',
          '" . $_POST['txtaDescricao'] . "'
          )
          ";
    $qry3 = mysql_query($sql3, $con) or die($sql3);

    $idAgendador = 0;
    $idAgendador = $_POST['idagendador'];

    $sqlUpdate = "UPDATE base_web_control.indica_amigo SET fatura_bonificar = '$fatura_bonificada', id_agendador='$idAgendador' WHERE id = '$id'";
//    echo "$sqlUpdate"; die;
    $qry = mysql_query($sqlUpdate, $con);
}

if (isset($_GET['idIndicacao'])) {
    $idIndicacao = $_GET['idIndicacao'];
    $sql = 'SELECT
          ia.id_cadastro, 
          ia.codigo_associado, 
          cad.nomefantasia as nome_associado, 
          ia.nome_amigo, 
          ia.fone_amigo1, 
          ia.fone_amigo2, 
          ia.dt_creation,
          ia.dt_last_update, 
          ia.tipo_recebimento,
          ia.quem_indicou,
          ia.funcao_empresa,
          ia.conta_bancaria,
          ia.banco,
          ia.agencia,
          ia.tipo_conta,
          ia.n_conta,
          ia.nome_titular,
          cad.id_franquia as id_franquia, 
          frq.fantasia as nome_franquia,
          ca.nome as nome_consultor,
          ia.id_agendador,
          ia.fatura_bonificar
          FROM base_web_control.indica_amigo AS ia
          INNER JOIN cs2.cadastro AS cad ON cad.codloja = ia.id_cadastro 
          INNER JOIN cs2.franquia AS frq ON frq.id = cad.id_franquia
          LEFT JOIN cs2.consultores_assistente ca
          ON ca.id = cad.id_consultor
          WHERE ia.id = ' . $idIndicacao;
//    echo $sql;
//    die;
    $qry = mysql_query($sql, $con);
    $res = mysql_fetch_assoc($qry);

    //consultar status
    $sql2 = " SELECT
                a.id, a.id_indicacao, a.status_indicacao, a.cod_cliente_vr, a.dt_nota, a.desc_nota, a.dt_creation, 
                if ( d.pendencia_contratual = 1 , 'PENDENTE','REGULAR') as pendencia_contratual, 
                date_format(d.dt_regularizacao ,'%d/%m/%Y') as dt_regularizacao, a.num_doc
            FROM 
                base_web_control.indica_amigo_log as a 
--            INNER JOIN
--                base_web_control.indica_amigo_log as b ON b.id_indicacao = a.id_indicacao
            LEFT OUTER JOIN
                base_web_control.webc_usuario AS c ON a.cod_cliente_vr = c.login AND login_master = 'S'
            LEFT OUTER JOIN
                cs2.cadastro AS d ON c.id_cadastro = d.codloja
            WHERE a.id_indicacao = $idIndicacao ORDER BY a.id DESC";

    $qry2 = mysql_query($sql2, $con);
    $total = mysql_num_rows($qry2);
    //limit 1
    $sql4 = "SELECT
                a.id, a.id_indicacao, a.status_indicacao, a.cod_cliente_vr, a.dt_nota, a.desc_nota, a.dt_creation,
                if ( d.pendencia_contratual = 1 , 'PENDENTE','REGULAR') as pendencia_contratual,
                date_format(d.dt_regularizacao ,'%d/%m/%Y') as dt_regularizacao, a.num_doc
            FROM
                base_web_control.indica_amigo_log as a
            INNER JOIN
                base_web_control.indica_amigo_log as b ON b.id_indicacao = a.id_indicacao
            LEFT OUTER JOIN
                base_web_control.webc_usuario AS c ON a.cod_cliente_vr = c.login
            LEFT OUTER JOIN
                cs2.cadastro AS d ON c.id_cadastro = d.codloja
            WHERE a.id_indicacao = $idIndicacao ORDER BY a.id DESC LIMIT 1";

    $qry4 = mysql_query($sql4, $con);
    $res4 = mysql_fetch_assoc($qry4);

    $dt_regularizacao = $res4['dt_regularizacao'];
     
    
    if ($res4['pendencia_contratual'] == 'REGULAR')
        $dt_regularizacao = " ($dt_regularizacao)";

    $foneAmigo = ($res['fone_amigo2']) ? $res['fone_amigo1'] . ' / ' . $res['fone_amigo2'] : $res['fone_amigo1'];
}
if ($id_franquia == 4 || $id_franquia == 247 || $id_franquia == 163) {
    $id_franquia_aux = 1;
}
 
?>
<style>
    h1 {
        text-align: center
    }

    table {
        border-collapse: collapse;
        font-size: 13px;
        font-family: arial, sans-serif;
    }

    table.tblIndicacao tr td, table.tblIndicacaoAtu tr td, table.tblAtualizar tr td {
        padding: 6px
    }

    @media print {
        .noprint {
            display: none
        }
    }

    table.tblIndicacao tr:hover, table.tblIndicacaoAtu tr:hover {
        background: #ddd;
    }

    table.tblAtualizar tr td {
        border: 0px !important
    }

    table.tblAtualizar {
        border: 2px solid #444 !important;
    }

    .vermais {
        text-align: center;
        margin-top: 10px
    }

    .vermais a {
        text-decoration: underline;
        color: #00f;
        cursor: pointer
    }

    .escondermais {
        text-align: center;
        margin-top: 10px
    }

    .escondermais a {
        text-decoration: underline;
        color: #00f;
        cursor: pointer
    }

</style>
<h1>Controle de Indicação</h1>
<form id="frmAlterarIndicacao" name="frmAlterarIndicacao" method="post"
      action="clientes/relatorio_indica_amigo_salva.php">
    <input type="hidden" name="action" value="atualizaAgendadora">
    <table class="tblIndicacao" id="tblIndicacao" border="1" width="50%" align="center" cellspacing="0"
           style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
        <tbody>
            <tr>
                <td width="30%"><strong>ID Indicação:</strong></td>
                <td><?= $idIndicacao ?></td>
        <input type="hidden" id="iptIdCadastro" value="<?= $res['id_cadastro'] ?>">
        </tr>
        <tr>
            <td><strong>Data Indicação:</strong></td>
            <td><?= date("d/m/Y", strtotime($res['dt_creation'])) ?></td>
        </tr>
        <tr>
            <td><strong>Franquia:</strong></td>
            <td><?= $res['nome_franquia'] ?></td>
        </tr>
        <tr>
            <td><strong>Associado:</strong></td>
            <td><?= $res['codigo_associado'] . ' - ' . $res['nome_associado'] ?></td>
        </tr>
        <tr>
            <td><strong>Amigo Indicado:</strong></td>
            <td><?= $res['nome_amigo'] ?></td>
        </tr>
        <tr>
            <td><strong>Consultor:</strong></td>
            <td><?= $res['nome_consultor'] ?></td>
        </tr>
        <tr>
            <td><strong>Telefone:</strong></td>
            <td><?= $foneAmigo ?></td>
        </tr>

        <tr>
        <input type="hidden" id="iptIdIndicacao" name="iptIdIndicacao" value="<?php echo $_GET['idIndicacao'] ?>">
        <td><strong>Agendadoras:</strong></td>
        <td><?php
            $taked = false;
            $idFranq = $res['id_franquia'];
            #$sql_fatura = "SELECT id, nome FROM cs2.consultores_assistente WHERE id_franquia = '$idFranq' AND tipo_cliente = 1 AND situacao = 0 AND tipo_funcionario = 'I'";
            $sql_fatura = "SELECT id, nome FROM cs2.consultores_assistente WHERE id_franquia = '$idFranq' AND tipo_cliente = 1 AND situacao = 0";
            $qr_fatura = mysql_query($sql_fatura, $con);
            echo "<select name='agendador' id='selAgendador'>";
            echo "<option value='0'>... Selecione ...</option>";
            while ($xRes = mysql_fetch_array($qr_fatura)) {

                $selected = '';
                if ($xRes['id'] == $res['id_agendador']) {
                    $selected = 'selected';
                    $taked = true;
                }
                echo "<option value=" . $xRes['id'] . " $selected>{$xRes['nome']}</option>";
            }
            echo "</select>";
            ?>
        </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <button type="button" name="btnSalvarInfo" id="btnSalvarInfo">Salvar</button>
            </td>
        </tr>
        </tbody>
    </table>
</form>
<?php
if ($res['tipo_recebimento']) {
    ?>
    <h1>Dados para Bônus</h1>
    <table width="50%" align="center" cellspacing="0">
        <tr>
            <td style="border:1px solid #999;padding:5px;"><b>Tipo Receimento</b></td>
            <td style="border:1px solid #999;padding:5px;"><?php echo ($res['tipo_recebimento'] == 1) ? ' Quero abater na proxima Fatura. ' : ' Quero R$ 100,00 Depositado na Minha Conta. ' ?></td>
        </tr>
        <?php if ($res['tipo_recebimento'] == 2) { ?>
            <tr>
                <td style="border:1px solid #999;padding:5px;"><b>Quem Indicou?</b></td>
                <td style="border:1px solid #999;padding:5px;"><?php echo $res['quem_indicou'] ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #999;padding:5px;"><b>Função Empresa:</b></td>
                <td style="border:1px solid #999;padding:5px;"><?php echo $res['funcao_empresa'] ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #999;padding:5px;"><b>Conta Bancária:</b></td>
                <td style="border:1px solid #999;padding:5px;"><?php echo $res['n_conta'] ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #999;padding:5px;"><b>Banco:</b></td>
                <td style="border:1px solid #999;padding:5px;"><?php echo $res['banco'] ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #999;padding:5px;"><b>Agência:</b></td>
                <td style="border:1px solid #999;padding:5px;"><?php echo $res['agencia'] ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #999;padding:5px;"><b>Tipo Conta:</b></td>
                <td style="border:1px solid #999;padding:5px;"><?php echo (($res['tipo_conta'] == 1) ? 'Conta Corrente' : 'Conta PoupanÃ§a') ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #999;padding:5px;"><b>CPF:</b></td>
                <td style="border:1px solid #999;padding:5px;"><?php echo $res['conta_bancaria'] ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #999;padding:5px;"><b>Nome Titular:</b></td>
                <td style="border:1px solid #999;padding:5px;"><?php echo $res['nome_titular'] ?></td>
            </tr>
        <?php } ?>
    </table>
    <?php
}
?>
<?php
if ($taked == false) {
//  $status = 'PENDENTE';
    $statusAgendadoras = '<h1 style="color:#f00">Status: PENDENTE </h1>';
} else {
//  $status = 'REPASSADO AO CONSULTOR';
    $statusAgendadoras = '<h1 style="color:#6bb9f0">Status: REPASSADO AO CONSULTOR</h1>';
}
?>

<?php
if ($res4):

    $idIndicacaoNew = $res4['id_indicacao'];
    $status = '';
    $acao = '';
    $idLog = $res4['id'];
    $numDoc = $res4['num_doc'];
    if ($res4['status_indicacao'] == 'VR') {
        $status = 'Venda Realizada.';
        $acao = 'da Venda';
    } else if ($res4['status_indicacao'] == 'SI') {
        $status = 'Sem Interessse.';
        $acao = 'do Desinteresse';
    } else if ($res4['status_indicacao'] == 'RE') {
        $status = 'Reagendado.';
        $acao = 'Reagendada';
    } else if ($res4['status_indicacao'] == 'SC') {
        $status = 'Sem Contato.';
        $acao = 'da Tentativa de Contato';
    }else if ($res4['status_indicacao'] == 'JC') {
        $status = 'Já era Cliente.';
        $acao = 'da Tentativa de Contato';
    }else if ($res4['status_indicacao'] == 'AC') {
        $status = 'Auto Indicou.';
        $acao = 'da Tentativa de Contato';
    }else if ($res4['status_indicacao'] == 'RP') {
        $status = 'Repetido.';
        $acao = 'da Tentativa de Contato';
    }else if ($res4['status_indicacao'] == 'TE') {
        $status = 'Telefone(s) Errado(s).';
        $acao = 'da Tentativa de Contato';
    }else if ($res4['status_indicacao'] == 'CC') {
        $status = 'Associado Cancelou.';
        $acao = 'da Tentativa de Contato';
    }else if ($res4['status_indicacao'] == 'CA') {
        $status = 'Amigo Indicado Cancelou.';
        $acao = 'da Tentativa de Contato';
    }else if ($res4['status_indicacao'] == 'CT') {
        $status = 'Contador (Nao gera bonificacao)';
        $acao = 'da Tentativa de Contato';
    }else if ($res4['status_indicacao'] == 'SA') {
        $status = 'Segmento nao atendido';
        $acao = 'da Tentativa de Contato';
    }else if ($res4['status_indicacao'] == 'AD') {
        $status = 'Associado desconhece a indicação';
        $acao = 'da Tentativa de Contato';
    }else if ($res4['status_indicacao'] == 'ID') {
        $status = 'Indicação desconhece Associado';
        $acao = 'da Tentativa de Contato';
    }    
    ?>
    <h1>Última Atualização</h1>
    <table class="tblIndicacaoAtu" id="" border="1" width="50%" align="center" cellspacing="0"
           style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
        <tbody>
            <tr>
                <td width="30%"><strong>Status:</strong></td>
                <?php
                if ($id_franquia == 163) { ?>
                    <td> <?= $status ?></td>
                    <td width="15px" align='center'><img src="../img/icone-fechar.png" title='Excluir Registro' onclick='return deletar(<?=$idIndicacaoNew?>,<?= $idLog ?>)'></td>
                <?php }else{ ?>
                    <td colspan='2'> <?= $status ?></td>
                <?php } ?>
            </tr>
            <tr>
                <td><strong>Data <?= $acao ?>:</strong></td>
                <td colspan="2"><?= date("d/m/Y", strtotime($res4['dt_nota'])) ?></td>
            </tr>
            <?php
            if ($res4['status_indicacao'] == 'VR') {
                ?>
                <tr>
                    <td><strong>Código do Novo Cliente:</strong></td>
                    <td colspan="2"><?= $res4['cod_cliente_vr'] . ' - ' . $res4['pendencia_contratual'] . $dt_regularizacao ?></td>
                </tr>
            <?php }
            ?>
            <tr>
                <td><strong>Descrição:</strong></td>
                <td><?= $res4['desc_nota'] ?></td>
                <td>
                    <?php if ($res4['status_indicacao'] == 'VR' && $id_franquia == 163) { ?>
                        Fatura Bonificada:
                        <?php
                        $sql_fatura = "
                                SELECT 'DEPOSITO C/C' AS numdoc, 'DEPOSITO C/C' as vencimento
                                UNION
                                SELECT a.numdoc, date_format(a.vencimento,'%d/%m/%Y') as vencimento FROM cs2.titulos a
                                INNER JOIN base_web_control.webc_usuario b ON a.codloja = b.id_cadastro
                                WHERE b.login = " . $res['codigo_associado'] . " group by a.numdoc";
                        $qr_fatura = mysql_query($sql_fatura, $con) or die($sql_fatura);
                        echo "<select name='fatura' onchange='gravaNumDoc(this.value, $idLog)'>";
                        echo "<option value='0'>... Selecione ...</option>";
                        while ($xRes = mysql_fetch_array($qr_fatura)) {
                            $selected = '';

                            if ($xRes['numdoc'] == $numDoc) {
                                $selected = 'selected';
                            }

                            echo "<option value=" . $xRes['numdoc'] . " $selected>{$xRes['vencimento']}</option>";
                        }
                        echo "</select>";
                    }
                    ?>

                </td>
            </tr>
            <?php if ($res4['status_indicacao'] == 'VR' && ($id_franquia == 163 || $id_franquia == 4)) { ?>
                <tr>
                    <td>Fatura à Bonificar:</td>
                    <td colspan="2">
                        <select id="fatura_bonificar_vr" name="fatura_bonificar_vr" onchange='gravaFatBonificar(this.value, <?php echo $idIndicacaoNew ?>)'>
                            <option value="">Selecione</option>
                            <?php
                            // echo "<option value='DEPOSITO C/C'>DEPOSITO C/C</option>";
                            while ($resAux = mysql_fetch_array($qryFatBonificar)) {
                                echo "<option value='{$resAux['descricao']}'>{$resAux['descricao']}</option>";
                            }
                            if ($res['fatura_bonificar'] != '') {
                                echo "<option value='{$res['fatura_bonificar']}' selected>{$res['fatura_bonificar']}</option>";
                            }
                            echo "</select>";
                        }
                        ?>
                </td>
            </tr>
            <tr>
                <td><strong>Data desta atualiza&ccedil;&atilde;o:</strong></td>
                <td colspan="2"><?= date("d/m/Y", strtotime($res4['dt_creation'])) ?></td>
            </tr>
        </tbody>
    </table>
    <?php if ($total > 1): ?>
        <div class="vermais">
            <a class="aVerHistorico">Ver Hist&oacute;rico Completo de Atualiza&ccedil;&otilde;es.</a>
        </div>
    <?php endif; ?>
    <?php
else:
    echo $statusAgendadoras;
endif;
?>

<?php
if ($total > 0):
    echo '<div class="divTblHidden" style="display:none"><center><h2>Hist&oacute;rico de Atualiza&ccedil;&otilde;es</h2></center>';
    while ($res5 = mysql_fetch_array($qry2)) {
        $status = '';
        $acao = '';
        if ($res5['status_indicacao'] == 'VR') {
            $status = 'Venda Realizada.';
            $acao = 'da Venda';
        } else if ($res5['status_indicacao'] == 'SI') {
            $status = 'Sem Interessse.';
            $acao = 'do Desinteresse';
        } else if ($res5['status_indicacao'] == 'RE') {
            $status = 'Reagendado.';
            $acao = 'Reagendada';
        } else if ($res5['status_indicacao'] == 'SC') {
            $status = 'Sem Contato.';
            $acao = 'da Tentativa de Contato';
        }else if ($res5['status_indicacao'] == 'JC') {
            $status = 'Ja Era Cliente.';
            $acao = 'da Tentativa de Contato';
        }else if ($res5['status_indicacao'] == 'AC') {
            $status = 'Auto Indicou.';
            $acao = 'da Tentativa de Contato';
        }else if ($res5['status_indicacao'] == 'RP') {
            $status = 'Repetido.';
            $acao = 'da Tentativa de Contato';
        }else if ($res5['status_indicacao'] == 'TE') {
            $status = 'Telefone(s) Errado(s).';
            $acao = 'da Tentativa de Contato';
        }else if ($res5['status_indicacao'] == 'CC') {
            $status = 'Associado Cancelou.';
            $acao = 'da Tentativa de Contato';
        }else if ($res5['status_indicacao'] == 'CA') {
            $status = 'Amigo Indicado Cancelou.';
            $acao = 'da Tentativa de Contato';
        }else if ($res4['status_indicacao'] == 'CT') {
            $status = 'Contador (Nao gera bonificacao)';
            $acao = 'da Tentativa de Contato';
        }else if ($res4['status_indicacao'] == 'SA') {
            $status = 'Segmento nao atendido';
            $acao = 'da Tentativa de Contato';
        }else if ($res4['status_indicacao'] == 'AD') {
            $status = 'Associado desconhece a indicação';
            $acao = 'da Tentativa de Contato';
        }else if ($res4['status_indicacao'] == 'ID') {
            $status = 'Indicação desconhece Associado';
            $acao = 'da Tentativa de Contato';
        }
        ?>
        <table class="tblIndicacaoAtu tblHidden" id="" border="1" width="50%" align="center" cellspacing="0"
               style="margin-top:20px;border-top:2px solid #333 !important;border: 1px solid #D1D7DC; background-color:#FFFFFF">
            <tbody>
                <tr>
                    <td width="30%"><strong>Status:</strong></td>
                    <td><?= $status ?></td>
                </tr>
                <tr>
                    <td><strong>Data <?= $acao ?>:</strong></td>
                    <td><?= date("d/m/Y", strtotime($res5['dt_nota'])) ?></td>
                </tr>
                <?php
                if ($res5['status_indicacao'] == 'VR') {
                    ?>
                    <tr>
                        <td><strong>C&oacute;digo do Novo Cliente:</strong></td>
                        <td><?= $res5['cod_cliente_vr'] ?></td>
                    </tr>
                <?php }
                ?>
                <tr>
                    <td><strong>Descri&ccedil;&atilde;o:</strong></td>
                    <td><?= $res5['desc_nota'] ?></td>
                </tr>
                <tr>
                    <td><strong>Data desta atualiza&ccedil;&atilde;o:</strong></td>
                    <td><?= date("d/m/Y", strtotime($res5['dt_creation'])) ?></td>
                </tr>
            </tbody>
        </table>

        <?php
    }
    echo '<div class="escondermais">
  <a class="esconderHistorico">Esconder Hist&oacute;rico.</a>
</div>
</div>';
endif;
?>
<?php if ($res4['status_indicacao'] != 'VR') { ?>
    <h1>Atualizar</h1>
    <table class="tblAtualizar" id="tblAtualizar" border="1" width="70%" align="center" cellspacing="0"
           style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
        <form name="frmNovoStatus" method="post">
            <input type="hidden" name="txtIdIndicacao" size="10" value="<?= $idIndicacao ?>"/>
            <input type="hidden" name="idagendador" id="idagendador" size="10" value="0"/>
            <tbody>
                <tr>
                    <td><strong>Novo Status:</strong></td>
                    <td>
                        <table width="100%">
                            <tr>
                               <td><label><input type="radio" name="rdStatus" value="VR"/> Venda Realizada.</label></td>
                               <td><label><input type="radio" name="rdStatus" value="SI"/> Não tem Interesse.</label></td>
                               <td><label><input type="radio" name="rdStatus" value="RE"/> Reagendamento.</label></td>
                            </tr>
                            <tr>
                               <td><label><input type="radio" name="rdStatus" value="SC"/> Sem Contato.</label></td>
                               <td><label><input type="radio" name="rdStatus" value="JC"/> Já era Cliente.</label></td>
                               <td><label><input type="radio" name="rdStatus" value="AC"/> Auto Indicou.</label></td>
                            </tr>
                            <tr>
                               <td><label><input type="radio" name="rdStatus" value="RP"/> Repetido.</label></td>
                               <td><label><input type="radio" name="rdStatus" value="TE"/> Telefone(s) Errado(s).</label></td>
                               <td><label><input type="radio" name="rdStatus" value="CC"/> Associado Cancelou.</label></td>
                            </tr>
                            <tr>
                               <td><label><input type="radio" name="rdStatus" value="CA"/> Amigo Indicado Cancelou.</label></td>
                               <td><label><input type="radio" name="rdStatus" value="CT"/> Contador (Não gera bonificação)</label></td>
                               <td><label><input type="radio" name="rdStatus" value="SA"/> Segmento não atendido.</label></td>
                            </tr>
                            <tr>
                               <td><label><input type="radio" name="rdStatus" value="AD"/> Associado desconhece a indicação</label></td>
                                <td><label><input type="radio" name="rdStatus" value="ID"/> Indicação desconhece Associado.</label></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="trHidden" style="display: none">
                    <td><strong>C&oacute;digo Novo Cliente:</strong></td>
                    <td>
                        <input type="text" name="txtCodNovoCliente" size="10"/>
                    </td>
                </tr>
                <tr>
                    <td><strong>Data <span class="spanStatus"></span>:</strong></td>
                    <td>
                        <input type="text" name="txtDataStatus" size="10"
                               onKeyPress="return formataData(event, this, '##/##/####');"/>
                    </td>
                </tr>
                <tr>
                    <td><strong>Descri&ccedil;&atilde;o:</strong></td>
                    <td>
                        <textarea name="txtaDescricao" cols="70" rows="3"></textarea>
                    </td>
                </tr>
                <tr class="trHidden" style="display: none">

                    <td><strong>Fat. á Bonificar:</strong></td>
                    <td>
                        <select id="fatura_bonificar" name="fatura_bonificar" disabled="disabled"><?php ?>
                            <?php
                            while ($resAux = mysql_fetch_array($qryFatBonificar)) {
                                echo "<option value='{$resAux['descricao']}'>{$resAux['descricao']}</option>";
                            }
                            if ($res['fatura_bonificar'] != '') {
                                echo "<option value='{$res['fatura_bonificar']}' selected>{$res['fatura_bonificar']}</option>";
                            }
                            echo "</select>";
                            ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right">
                        <button type="button" name="btnGravaNovoStatusIndicacao">Gravar Novo Status</button>
                    </td>
                </tr>
            </tbody>
        </form>
    </table>

<?php } ?>
<div align="center" style="margin-top: 30px;">
    <button id="btnVoltar" onclick="location.href = 'painel.php?pagina1=clientes/relatorio_indica_amigo.php'">Voltar</button>
</div>
<script>

    $('input[name="txtCodNovoCliente"]').blur(function () {
        $.ajax({
            url: 'clientes/relatorio_indica_amigo_salva.php',
            data: {
                action: 'buscaDataCadastroIndicado',
                codigoIndicado: $('input[name="txtCodNovoCliente"]').val()
            },
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                $('input[name="txtDataStatus"]').val(data.data_venda);
            }
        });


        $.ajax({
            url: 'clientes/relatorio_indica_amigo_salva.php',
            data: {
                action: 'buscaCodigoExiste',
                codigoIndicado: $('input[name="txtCodNovoCliente"]').val(),
                idCadastro: $('#iptIdCadastro').val()
            },
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.existe == 1) {

                    alert('Atenção, já existe uma bonificação relacionada a este código! ID: ' + data.id);
                    location.href = "../php/painel.php?pagina1=clientes/relatorio_indica_amigo_altera.php&idIndicacao=<?php echo $_GET['idIndicacao'] ?>";
                }
            }
        })

    });


    $('#btnSalvarInfo').click(function () {
        $('#frmAlterarIndicacao').submit();
    });

    $('.vermais a.aVerHistorico').click(function () {
        $('.divTblHidden').removeAttr('style');
        $('.vermais').attr('style', 'display:none');
        $('.escondermais').removeAttr('style', 'display:none');
    });

    $('.escondermais a.esconderHistorico').click(function () {
        $('.divTblHidden').attr('style', 'display:none');
        $('.escondermais').attr('style', 'display:none');
        $('.vermais').removeAttr('style', 'display:none');
    });

    $('button[name=btnGravaNovoStatusIndicacao]').click(function (e) {
        e.preventDefault();
        if ($('input[name=rdStatus]').is(':checked') && $('input[name=txtDataStatus]').val() != '') {
            $('input[name=txtDataStatus]').val(dateToUS($('input[name=txtDataStatus]').val()));
            $('form[name=frmNovoStatus]').submit();
        } else {
            alert('Novo Status e Data Requeridos.');
        }
    });

    function formataData(e, src, mask) {
        if (window.event) {
            _TXT = e.keyCode;
        } else if (e.which) {
            _TXT = e.which;
        }
        if (_TXT > 47 && _TXT < 58) {
            var i = src.value.length;
            var saida = mask.substring(0, 1);
            var texto = mask.substring(i)
            if (texto.substring(0, 1) != saida) {
                src.value += texto.substring(0, 1);
            }
            return true;
        } else {
            if (_TXT != 8) {
                return false;
            } else {
                return true;
            }
        }
    }

    $('input[name=rdStatus]').click(function () {
        var status = $(this).val();
        if (status == 'VR') {
            $('#tblAtualizar .spanStatus').text('da Venda');
            $('#tblAtualizar tr.trHidden').removeAttr('style');
            $('#fatura_bonificar').removeAttr('disabled');
        } else if (status == 'SI') {
            $('#tblAtualizar .spanStatus').text('do Desinteresse');
            $('#tblAtualizar tr.trHidden').attr('style', 'display: none');
            $('#fatura_bonificar').attr('disabled', 'disabled');
        } else if (status == 'RE') {
            $('#tblAtualizar .spanStatus').text('Reagendada');
            $('#tblAtualizar tr.trHidden').attr('style', 'display: none');
            $('#fatura_bonificar').attr('disabled', 'disabled');
        } else if (status == 'SC') {
            $('#tblAtualizar .spanStatus').text('da Tentativa de Contato');
            $('#tblAtualizar tr.trHidden').attr('style', 'display: none');
            $('#fatura_bonificar').attr('disabled', 'disabled');
        }
    });

    function dateToUS(dataBR) {
        if (dataBR != '') {
            if (dataBR.indexOf(':') > 0) {
                var tempo = dataBR.split(' ');
                var dataSplit = tempo[0].split('/');
                dataBR = dataSplit[2] + "-" + dataSplit[1] + "-" + dataSplit[0] + ' ' + tempo[1];
            } else {
                if (dataBR.indexOf('/') > 0) {
                    var dataSplit = dataBR.split('/');
                    dataBR = dataSplit[2] + "-" + dataSplit[1] + "-" + dataSplit[0];
                }
            }
        }

        return dataBR
    }

    function gravaNumDoc(numDoc, id) {

        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.open("GET", "clientes/atualiza_doc_indicacao.php?num_doc=" + numDoc + "&id=" + id, true);
        xmlhttp.send();
    }

    function gravaFatBonificar(numDoc, id) {

        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.open("GET", "clientes/atualiza_doc_indicacao.php?num_doc=" + numDoc + "&id=" + id + "&fatBoni=1", true);
        xmlhttp.send();
    }

    $('#selAgendador').on('change', function () {
        $("#idagendador").val($(this).val());
    })

</script>

<?php
if( $_REQUEST['imprimir'] == 'sim' ){ ?>
   <script>window.print();</script>
<?php }
?>
