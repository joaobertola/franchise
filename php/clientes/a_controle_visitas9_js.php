<?php
/**
 * Created by PhpStorm.
 * User: Arllon Dias
 * Date: 25/10/2016
 * Time: 10:21
 */
require "../connect/conexao_conecta.php";
require "../connect/sessao.php";
require "../connect/sessao_r.php";
require "../connect/funcoes.php";

function mask($val, $mask) {
    $maskared = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mask) - 1; $i++) {
        if ($mask[$i] == '#') {
            if (isset($val[$k]))
                $maskared .= $val[$k++];
        } else {
            if (isset($mask[$i]))
                $maskared .= $mask[$i];
        }
    }
    return $maskared;
}

$idFranquiaOrig = $id_franquia;

if ($id_franquia == 4 || $id_franquia == 163 || $id_franquia == 247) {
    $id_franquia = 1;
    $idFranquiaOrig = 247;
}


if (isset($_REQUEST['a'])) {


    if ($_REQUEST['a'] == 1) {

        $sqlLog = "
            SELECT
                id,
                id_sms_automatico,
                DATE_FORMAT(data_envio, '%d/%m/%Y') AS data_envio,
                telefones,
                data_envio AS ordenacao
            FROM cs2.envio_sms
            WHERE id_sms_automatico != 0
            ORDER BY ordenacao DESC ;
        ";
        $qryLog = mysql_query($sqlLog, $con);
        ?>
        <table class="table">
            <thead>
                <tr style="font-weight: bold">
                    <th>DATA</th>
                    <th>ID</th>
                    <th>ID CAMPANHA</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalTelefones = 0;
                while ($aResultLog = mysql_fetch_array($qryLog)) {
                    $aResultTelefones = substr_count($aResultLog['telefones'], ',');
                    $totalTelefones += $aResultTelefones;
                    ?>
                    <tr style="border: solid 1px;" data-id="<?php echo $aResultLog['id_sms_automatico'] ?>">
                        <td style="padding: 5px;" class=""><?php echo $aResultLog['data_envio'] ?></td>
                        <td style="padding: 5px;"><?php echo $aResultLog['id_sms_automatico'] ?></td>
                        <td style="padding: 5px;" class=""><a
                                onclick="visualizarTelefones(<?php echo $aResultLog['id'] ?>)" style="cursor: pointer;">Visualizar Telefones
                                (<?php echo $aResultTelefones ?>)</a><a class="esconderTelefones hidden"
                                                                    style="cursor: pointer;">Esconder
                                Telefones</a></td>
                    </tr>
                    <?php
                    /*
                     * for ($j = 0; $j < count($aResultTelefones); $j++) { ?> <tr class="hidden tr<?php echo $aResultLog['id_sms_automatico'] ?>"><td colspan="2" style="margin-left: 10px;"><?php echo mask($aResultTelefones[$j], '(##) ####-####') ?></td><td></td></tr><?php }
                     */
                    ?>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="2" style="font-weight: bold;">Total de Envios:</td>
                    <td colspan="1"
                        style="text-align: left; font-weight: bold;"><?php echo $totalTelefones ?></td>
                </tr>
            </tbody>
        </table>
        <?php
    }

    if ($_REQUEST['a'] == 2) {

        $id = $_REQUEST['id'];

        $sqlLog = "
            SELECT 
                telefones,
                data_envio AS ordenacao
            FROM cs2.envio_sms
            WHERE id = $id
            ORDER BY ordenacao DESC ;
        ";
        $qryLog = mysql_query($sqlLog, $con);

        echo '<table class="table">'
        . '     <tbody>';

        while ($aResultLog = mysql_fetch_array($qryLog)) {
            $telefones = explode(',', $aResultLog['telefones']);
            if ($telefones) {
                echo '<tr>';
                $i = 0;
                foreach ($telefones as $k => $v) {
                    $i++;
                    if ($i == 5) {
                        echo '</tr><tr>';
                        $i = 0;
                    }
                    echo '<td>' . $v . '</td>';
                }
                echo '</tr>';
            }
        }
        echo '     <tbody>'
        . '  </table>';
    }

    if($_REQUEST['a'] == 3) {
        $id = $_REQUEST['id'];

        $sqlCampanha = "
                SELECT
            id,
            mensagem,
            ativo,
            dias_do_mes
        FROM apoio.sms_automatico WHERE id = $id";  

        $qry = mysql_query($sqlCampanha, $con);

        while($linha = mysql_fetch_array($qry)){

            $json_array[] = [
                'id' => $linha['id'],
                'mensagem' => $linha['mensagem'],
                'ativo' => $linha['ativo'],
                'dias_do_mes' => $linha['dias_do_mes']
            ];

        }

        echo json_encode($json_array);  
    }

    if($_REQUEST['a'] == 4) {
        echo "caiu aqui";
        


        print_r($qry);
    }    
}