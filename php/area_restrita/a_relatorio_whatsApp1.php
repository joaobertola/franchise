<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="https://www.webcontrolempresas.com.br/franquias/css/assets/js/bootstrap.min.js"></script>
<script src="https://www.webcontrolempresas.com.br/franquias/css/assets/js/mask.js"></script>

<script>
    function ConsultaCampanha(campanha){
        $.ajax({
            url: "area_restrita/a_relatorio_whatsApp2.php",
            type: "POST",
            data: {
                'id_campanha': campanha
            },
            dataType: 'json',
            success: function(json) {
                if (json != false) {

                    // recebi retorno
                    alert('adadsa');

                } else {
                    console.log(data);
                }
            }
        });
    }
</script>

<link rel="stylesheet"
      href="https://www.webcontrolempresas.com.br/franquias/css/assets/css/font-awesome.min.css">
<link rel="stylesheet"
      href="https://www.webcontrolempresas.com.br/franquias/css/assets/css/bootstrap.min.css">

<?php

require "connect/sessao.php";
//require "connect/conexao_conecta.php";

      echo "<pre>";
 //   print_r( $_REQUEST );

    $foneP = $_REQUEST['telefone'];
    $fone = trim(preg_replace("/[^0-9]/", "", $_REQUEST['telefone']));
    $dti = trim($_REQUEST['dti']);
    $dtf = trim($_REQUEST['dtf']);

    // Verificando se o cliente DIGITOU O TELEFONE PARA PESQUISA

    if ( $fone != '' ){

        // veio telefone, pesquisar somente por ele
        $dti = substr($dti,6,4).'-'.substr($dti,3,2).'-'.substr($dti,0,2);
        $dtf = substr($dtf,6,4).'-'.substr($dtf,3,2).'-'.substr($dtf,0,2);
        $sqlComp = '';
        if ( $dti != '' )  $sqlComp .= " AND b.data_envio >= '$dti' ";
        if ( $dtf != '' )  $sqlComp .= " AND b.data_envio <= '$dtf' ";

        $sql = "SELECT 
                    DATE_FORMAT(b.inicio_envio,'%d/%m/%Y %H:%i:%s') AS inicio_envio,
                    DATE_FORMAT(b.fim_envio,'%d/%m/%Y %H:%i:%s') AS fim_envio,
                    b.encerrado, 
                    CASE a.retorno
                        WHEN 1 THEN 'Ainda não verificado'
                        WHEN 2 THEN 'Aguardando envio da mensagem'
                        WHEN 3 THEN 'Enviando mensagem'
                        WHEN 4 THEN 'Mensagem Enviada'
                        WHEN 5 THEN 'Mensagem Entregue'
                        WHEN 6 THEN 'Número telefone Inválido'
                        WHEN 7 THEN 'Telefone Não possui WhatsApp'
                    END
                    AS retorno,
                    a.id_campanha 
                FROM cs2.campanha_whatsApp_retorno a
                INNER JOIN cs2.campanha_whatsApp_envio b ON a.id_campanha = b.id_campanha_retorno
                WHERE telefone = '$fone' $sqlComp";
        $qry = mysql_query( $sql, $con) or die('Erro comando SQL :'.$sql);
        if ( mysql_num_rows( $qry ) > 0 ){
            ?>
            <form id="frmViewFone" name="frmViewFone" method="#">
                <table class="tblIndicaAmigo" id="tblIndicaAmigo" border="1" width="80%" align="center" cellspacing="0"
               style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
                    <thead>
                        <tr><td colspan="5" align="center"><b>Relatório de Envio - WhatsApp</b></td></tr>
                        <tr bgcolor="#CFCFCF">
                            <th>Campanha</th>
                            <th>Telefone</th>
                            <th>Data Início</th>
                            <th>Data Término</th>
                            <th>Status</th>
                        </tr>
                    </thead>
            <?php
            while ($reg = mysql_fetch_array($qry)){
                ?>
                <tr>
                   <td class="tdSel"><?= $reg['id_campanha'] ?></td>
                   <td class="tdSel"><?= $foneP ?></td>
                   <td class="tdSel"><?= $reg['inicio_envio'] ?></td>
                   <td class="tdSel"><?= $reg['fim_envio'] ?></td>
                    <td class="tdSel"><?= $reg['retorno'] ?></td>
                </tr>
                <?php
            }
            ?>
                </table>
                <table class="tblIndicaAmigo" id="tblIndicaAmigo" width="80%" align="center" cellspacing="0">
                    <tr align="center">
                        <td colspan="5"><input type="button" name="retorno" value="Nova Consulta" onClick="history.back()"></td>
                    </tr>    
                </table>
            </form>
            <?php
        }else{
            echo "<script>alert('Nenhum registro encontrado para o telefone informado');history.back()</script>";
            die;

        }
    }else{

        // NAO DIGITOU TELEFONE
        if ( $dti == '' ){
            echo "<script>alert('Favor informar a data inicial para pesquisa');history.back()</script>";
            die;
        }
        if ( $dtf == '' ){
            echo "<script>alert('Favor informar a data final para pesquisa');history.back()</script>";
            die;
        }
        $dti = substr($dti,6,4).'-'.substr($dti,3,2).'-'.substr($dti,0,2);
        $dtf = substr($dtf,6,4).'-'.substr($dtf,3,2).'-'.substr($dtf,0,2);
        $sql = "SELECT 
                    id_campanha_retorno,
                    DATE_FORMAT(data_envio,'%d/%m/%Y') AS data_envio,
                    DATE_FORMAT(inicio_envio,'%d/%m/%Y %H:%i:%s') AS inicio_envio,
                    DATE_FORMAT(fim_envio,'%d/%m/%Y %H:%i:%s') AS fim_envio,
                    CASE encerrado 
                       WHEN 's' THEN 'Sim'
                       ELSE 'Não'
                    END AS encerrado
                FROM cs2.campanha_whatsApp_envio
                WHERE data_envio >= '$dti' AND data_envio <= '$dtf'";
        $qry = mysql_query( $sql, $con) or die('Erro comando SQL :'.$sql);
        if ( mysql_num_rows( $qry ) > 0 ){
            ?>
            <form id="frmViewFone" name="frmViewFone" method="#">
                <table class="tblIndicaAmigo" id="tblIndicaAmigo" border="1" width="80%" align="center" cellspacing="0"
               style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
                    <thead>
                        <tr><td colspan="6" align="center"><b>Relatório de Campanhas - WhatsApp</b></td></tr>
                        <tr bgcolor="#CFCFCF">
                            <th style="text-align:center">Campanha</th>
                            <th style="text-align:center">Data Processamento</th>
                            <th style="text-align:center">Data Início</th>
                            <th style="text-align:center">Data Término</th>
                            <th style="text-align:center">Encerrado?</th>
                            <th>...</th>
                        </tr>
                    </thead>
                    <body>
                        <?php
                        while ($reg = mysql_fetch_array($qry)){
                            $id_campanha = trim($reg['id_campanha_retorno']);
                            if ( $reg['encerrado'] != 'Sim' ) $back = "style='background-color:#EC7063'";
                            else $back = '';
                            ?>
                            <tr <?= $back ?> >
                                <td style="text-align:center"><?= $reg['id_campanha_retorno'] ?></td>
                                <td style="text-align:center"><?= $reg['data_envio'] ?></td>
                                <td style="text-align:center"><?= $reg['inicio_envio'] ?></td>
                                <td style="text-align:center"><?= $reg['fim_envio'] ?></td>
                                <td style="text-align:center"><?= $reg['encerrado'] ?></td>
                                <td align="center"><a href="#" onclick="ConsultaCampanha('<?php echo $id_campanha; ?>')"><span class="glyphicon glyphicon-search"></span></a></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </body>
                </table>
                <table class="tblIndicaAmigo" id="tblIndicaAmigo" width="80%" align="center" cellspacing="0">
                    <tr align="center">
                        <td colspan="5"><input type="button" name="retorno" value="Nova Consulta" onClick="history.back()"></td>
                    </tr>    
                </table>
            </form>
                    <?php
            
        }
    }


?>