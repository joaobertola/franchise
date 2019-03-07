<?php
ob_start();
session_start();


if ($_SESSION['SENHA-CONTROLE-CLIENTES'] == 'webcontrolempresas') {
    require "../connect/conexao_conecta.php";
    $status = $_GET['id'];
    // status $reg[5]
    $GqryStatus = mysql_query("SELECT * FROM cs2.controle_comercial_visitas_status WHERE id = $status ", $con) or die("Erro MYSQL");
    $statusQ = mysql_fetch_array($GqryStatus);
    

    $qryStatus = mysql_query("SELECT * FROM cs2.controle_comercial_visitas_justificativa WHERE id_status = $status ORDER BY nome ASC ", $con) or die("Erro MYSQL");
   
    if (mysql_num_rows($qryStatus) > 0) {
        echo '<select style="padding:1%;width:95%;border-radius:5px;background:#222;color:#FFF;" name="justificativa">';
        while ($status = mysql_fetch_array($qryStatus)) {
            if ($status['id'] == $reg[3]) {
                echo '<option selected value="' . $status['id'] . '">' . $status['nome'] . '</option>';
            } else {
                echo '<option value="' . $status['id'] . '">' . $status['nome'] . '</option>';
            }
        }
        echo '</select>';
    }

    if ($statusQ['sigla'] == 'F') {
        ?> 
        <input style="padding:1%;width:200px;border-radius:5px;background:#222;color:#FFF;" name="data_visita" id="data_visita" value="<?php echo date('d/m/Y') ?>"/> 
        <a onclick="openCalendar1('data_visita')" class="pull-left">
            <img width="20" src="https://webcontrolempresas.com.br/franquias/calendario.png"/>
        </a>
        <div id="calendario-1" style="display:none;position:absolute;z-index: 9;float:left;width:200px;min-height:120px;background:#FFF;">
        </div>
        <div style="width:100%;clear:both;marign:5px;display:block;"></div>
        <br/>
        <?php
        $id_franquia = $_SESSION['id'];
        if ($id_franquia == 4 || $id_franquia == 5 || $id_franquia == 163 || $id_franquia == 247)
            $id_franquia = 1;

        $sql_sel = "SELECT * FROM cs2.consultores_assistente WHERE id_franquia = '$id_franquia'
         AND tipo_cliente = '0' AND situacao IN('0') ORDER BY situacao, nome";
        $qry = mysql_query($sql_sel, $con);
        echo "<select style='padding:1%;width:230px;border-radius:5px;background:#222;color:#FFF;' name='id_consultor' id='id_consultor' style='width:65%'>";
        ?>
        <option value="">Selecionar</option>
        <?php
        while ($rs = mysql_fetch_array($qry)) {
            if ($rs['situacao'] == "0") {
                $sit = "Ativo";
            } elseif ($rs['situacao'] == "1") {
                $sit = "Bloqueado";
            } elseif ($rs['situacao'] == "2") {
                $sit = "Cancelado";
            }
            ?>
            <?php if ($_REQUEST['id_consultor'] == $rs['id']) { ?>
                <option value="<?= $rs['id'] ?>" selected><?= $rs['nome'] ?> - <?= $sit ?></option>
            <?php } else { ?>
                <option value="<?= $rs['id'] ?>"><?= $rs['nome'] ?> - <?= $sit ?></option>
            <?php } ?>
        <?php } ?>
        </select>
        <a onClick="verifica()" id="verifica">Verificar
            hor&aacute;rios ocupados</a>
        <p id="verifica-m"></p>
        <br/> 
        <input style="padding:1%;width:200px;border-radius:5px;background:#222;color:#FFF;" name="hora_atendimento" id="hora_atendimento" value="" placeholder="Horário: 00:00"/> 
        <br/>

        <script>
            function verifica() {
                jQuery.get('clientes/carrega_horario_consultor_new.php?id_consultor=' + document.getElementById('id_consultor').value + '&data_agendamento=' + document.getElementById('data_visita').value + '&id_franquia=<?= $id_franquia ?>', function (verif) {
                    jQuery('#verifica-m').html(verif);
                    console.log(verif);
                });
            }

            function openCalendar1() {
                jQuery('#calendario-1').load('clientes/calendario.php');
                jQuery('#calendario-1').slideToggle(0);
            }

            function actionCalendar(ref) {
                jQuery('#calendario-1').load('clientes/calendario.php?' + ref);
            }

            function setData(ref) {
                jQuery('#calendario-1').slideToggle();
            }

            function setValCalendar(dataOld, data) {
                jQuery('#data_visita').val(data);
                jQuery('#calendario-1').slideToggle(0);
            }
        </script>
        <?php
    }
} else {
    ?>
    <option value=""> Não foi possível listar</option>
    <?php
}
?>