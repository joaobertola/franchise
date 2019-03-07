<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set("display_errors", 1);

ob_start();
session_start();

if (isset($_GET['acesso'])) {
    if ($_GET['acesso'] == 'webcontrolempresas') {
        $_SESSION['SENHA-CONTROLE-CLIENTES'] = $_GET['acesso'];
    } else {
        $msgErro = '<p style="color:red">Chave de acesso não confere.</p>';
    }
}

if (isset($_SESSION['SENHA-CONTROLE-CLIENTES']) && $_SESSION['SENHA-CONTROLE-CLIENTES'] == 'webcontrolempresas') {
    require "../connect/conexao_conecta.php";

    $protocolo = $_GET['id'];
    $sql = ""
            . " SELECT * "
            . " FROM cs2.controle_comercial_visitas_historico as ch  "
            . " INNER JOIN cs2.controle_comercial_visitas as cv ON cv.id = ch.id_visita "
            . " INNER JOIN cs2.consultores_assistente as cc ON cc.id = ch.id_consultor "
            . " WHERE ch.id = $protocolo ";
    $qryPesquisa = mysql_query($sql, $con) or die("Erro MYSQL");
    $reg = mysql_fetch_array($qryPesquisa);
 
    if (isset($_POST['status'])) {
 
        $idVisita = $reg[0];
        $idAgendamento = $reg[1];
        $idStatus = $_POST['status'];
        $idJustificativa = isset($_POST['justificativa']) ? $_POST['status'] : '';
        $CreatedAt = date('Y-m-d H:i:s');

        if (isset($_POST['id_consultor'])) {

            $idConsultor = $_POST['id_consultor'];
            $dataVisita = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['data_visita'])));
            $hora = $_POST['hora_atendimento'];

            $updateHistorico = mysql_query("INSERT INTO cs2.controle_comercial_visitas_historico (id_visita, id_consultor, data_visita, hora, status)VALUES($idAgendamento, $idConsultor, '$dataVisita', '{$hora}',$idStatus)", $con) or die("Erro MYSQL 1");
            if ($updateHistorico) {
                $msgErro = '<p style="color:green">Status atualizado com sucesso.</p>'
                        . '<script>location.reload();</script>'
                        . '';
            }
        } else {
            $updateHistorico = mysql_query("UPDATE cs2.controle_comercial_visitas_historico SET status = $idStatus WHERE id = " . $_GET['id'], $con) or die("Erro MYSQL 1");

            if ($updateHistorico) {
                if (isset($_POST['justificativa']) && !empty($_POST['justificativa'])) {
                    $saveHistoricoQ = ""
                            . " INSERT INTO  "
                            . " cs2.controle_comercial_visitas_historico_status "
                            . " (id_visita, id_agendamento,id_justificativa, id_status, created_at)"
                            . "VALUES('$idVisita','$idAgendamento','$idJustificativa','$idStatus','$CreatedAt')";
                } else {

                    $saveHistoricoQ = ""
                            . " INSERT INTO  "
                            . " cs2.controle_comercial_visitas_historico_status "
                            . " (id_visita, id_agendamento, id_status, created_at)"
                            . "VALUES('$idVisita','$idAgendamento','$idStatus','$CreatedAt')";
                }
                $saveHistorico = mysql_query($saveHistoricoQ, $con);
                if ($saveHistorico) {
                    $msgErro = '<p style="color:green">Status atualizado com sucesso.</p>'
                            . '<script>location.reload();</script>'
                            . '';
                } else {
                    $msgErro = '<p style="color:red">Não foi possível atualizar o status.</p>';
                }
            } else {
                $msgErro = '<p style="color:red">Não foi possível atualizar o status.</p>';
            }
        }
    }


    #print_r($reg);
    ?>
    <h2>Atualização de Status</h2>
    <br/> 
    <?php
    if (isset($msgErro)) {
        echo '<br/>' . $msgErro;
    }
    ?> 
    <table style="width:100%;">
        <tr>
            <td><b>Data Visita:</b> <?php echo date('d/m/Y', strtotime($reg[3])); ?></td>
            <td><b>Hora Visita:</b> <?php echo str_replace('00:00:0', '', $reg[4]); ?>00</td>
            <td><b>Consultor:</b> <?php echo $reg[53]; ?></td>
        </tr>
    </table>
    <br/>
    <form method="post" id="salvar-visita-status">
        <select onchange="setJustificativa(this.value)" style="padding:1%;width:95%;border-radius:5px;background:#222;color:#FFF;" name="status" id="status">
            <?php
            // status $reg[5]
            $qryStatus = mysql_query("SELECT * FROM cs2.controle_comercial_visitas_status ORDER BY nome ASC ", $con) or die("Erro MYSQL");
            while ($status = mysql_fetch_array($qryStatus)) {
                if ($status['id'] == $reg[5]) {
                    echo '<option selected value="' . $status['id'] . '">' . $status['nome'] . '</option>';
                } else {
                    echo '<option value="' . $status['id'] . '">' . $status['nome'] . '</option>';
                }
            }
            ?>
            <option></option>
        </select>
        <br/><br/>
        <div id="justificativa">
        </div>
        <br/>
        <br/>
        <a onclick="salvarVisitaStatus(<?php echo $_REQUEST['id'] ?>)" style="cursor:pointer;padding:10px 20px;border:none;background:green;color:#FFF;border-radius:5px;">SALVAR</a>
        <a onclick="closeModal()" style="cursor:pointer;padding:10px 20px;border:none;background:#999;color:#EEE;border-radius:5px;">CANCELAR</a>
    </form>
    <script>
        function setJustificativa(ref) {
            $('#justificativa').show();
            $('#justificativa').load('clientes/a_controle_visitas_altera_new_justificativa.php?id=' + ref);
        }

        function salvarVisitaStatus(ref) {
            var dados = $('#salvar-visita-status').serialize();
            $.post('clientes/a_controle_visitas_altera_new_status.php?id=' + ref, dados, function (resultado) {
                $('#opem-editar').html(resultado);
            });
        }


    </script>

    <?php
} else {
    ?>
    <h2>OPÇÃO RESTRITA</h2>
    <br/>
    <p>Favor entrar com a senha de segurança</p>
    <?php
    if (isset($msgErro)) {
        echo '<br/>' . $msgErro;
    }
    ?>
    <br/>
    <input type="text" style="width:95%;padding:10px 20px;border:none;background:#999;color:#FFF;border-radius:5px;" name="senha" id="acesso-modal" placeholder="Insira aqui a chave de acesso" value="<?php echo isset($_GET['acesso']) ? $_GET['acesso'] : '' ?>"/>
    <br/><br/>
    <a onclick="validaModal(<?php echo $_REQUEST['id'] ?>)" style="cursor:pointer;padding:10px 20px;border:none;background:#00008B;color:#FFF;border-radius:5px;">ACESSAR</a>
    <a onclick="closeModal()" style="cursor:pointer;padding:10px 20px;border:none;background:#EEE;color:#999;border-radius:5px;">CANCELAR</a>

    <?php
}
?>