<?php
# Verificando se o documento do cliente foi escaneado
    
if ($_REQUEST['enviar'] == 1) {

    $logon = $_REQUEST['logon'];
    $email = $_REQUEST['email'];
    $enderecoFoto = $_REQUEST['endereco_foto'];

    $args = array(
        'logon' => $logon, //nao e master aqui!
        'email' => $email,
        'endereco_foto' => $enderecoFoto,
    );

    // TRANSFORMA O ARRAY EM PARAMETROS PARA A URL
    $field_string = http_build_query($args);
    $url = 'http://10.2.2.8/enviarEmailContrato.php?' . $field_string;
    //echo $url;
    // CURL PARA ENVIO DOS SMS
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_URL, $url);
    $result = curl_exec($handle);
    //close connection
    curl_close($handle);
    
    if ($result) {
        ?>

        <script>alert('E-mail enviado com sucesso!')</script>
    <?php } else {
        ?>
        <script>alert('Ocorreu um erro ao enviar o e-mail!')</script>
        <?php
    }
}

function texto_1($entrada) {
    if ($entrada == 'CTR')         $saida = 'CONTRATO';
    elseif ($entrada == 'CF')      $saida = 'CONTRATO FRENTE';
    elseif ($entrada == 'CV')      $saida = 'CONTRATO VERSO';
    elseif ($entrada == 'TB')      $saida = 'TABELA DE PRE&Ccedil;O';
    elseif ($entrada == 'DT')      $saida = 'DECLARA&Ccedil;&Atilde;O DE TREINAMENTO / TERMO DE RESPONSABILIDADE';
    elseif ($entrada == 'RC')      $saida = 'ROTEIRO DE CONFER&Ecirc;NCIA';
    elseif ($entrada == 'R0')      $saida = 'REATIVACAO DE CONTRATO';
    elseif ($entrada == 'AT')      $saida = 'AUTORIZACAO DE TRANSFERENCIA DE VALORES';
    elseif ($entrada == 'C1')      $saida = 'CONSULTORIA E TREINAMENTO - 1';
    elseif ($entrada == 'C2')      $saida = 'CONSULTORIA E TREINAMENTO - 2';
    elseif ($entrada == 'C3')      $saida = 'CONSULTORIA E TREINAMENTO - 3';
    elseif ($entrada == 'C4')      $saida = 'CONSULTORIA E TREINAMENTO - 4';
    elseif ($entrada == 'C5')      $saida = 'CONSULTORIA E TREINAMENTO - 5';
    elseif ($entrada == 'C6')      $saida = 'CONSULTORIA E TREINAMENTO - 6';
    elseif ($entrada == 'C7')      $saida = 'CONSULTORIA E TREINAMENTO - 7';
    elseif ($entrada == 'C8')      $saida = 'CONSULTORIA E TREINAMENTO - 8';
    elseif ($entrada == 'C9')      $saida = 'CONSULTORIA E TREINAMENTO - 9';
    elseif ($entrada == 'C10')     $saida = 'CONSULTORIA E TREINAMENTO - 10';
    elseif ($entrada == 'C11')     $saida = 'CONSULTORIA E TREINAMENTO - 11';
    elseif ($entrada == 'PCE')     $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 1';
    elseif ($entrada == 'PCE2')    $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 2';
    elseif ($entrada == 'PCE3')    $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 3';
    elseif ($entrada == 'PCE4')    $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 4';
    elseif ($entrada == 'PCE5')    $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 5';
    elseif ($entrada == 'PCE6')    $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 6';
    elseif ($entrada == 'PCE7')    $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 7';
    elseif ($entrada == 'PCE8')    $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 8';
    elseif ($entrada == 'PCE9')    $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 9';
    elseif ($entrada == 'PCE10')   $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 10';
    elseif ($entrada == 'PCE11')   $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 11';
    elseif ($entrada == 'PCE12')   $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 12';
    elseif ($entrada == 'PCE13')   $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 13';
    elseif ($entrada == 'PCE14')   $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 14';
    elseif ($entrada == 'PCE15')   $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 15';
    elseif ($entrada == 'PCE16')   $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 16';
    elseif ($entrada == 'PCE17')   $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 17';
    elseif ($entrada == 'PCE18')   $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 18';
    elseif ($entrada == 'PCE19')   $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 19';
    elseif ($entrada == 'PCE20')   $saida = 'PEDIDO DE EQUIPAMENTOS E SUPRIMENTOS - 20';
    elseif ($entrada == 'CC')      $saida = 'CARTA DE CANCELAMENTO';
    elseif ($entrada == 'CRC')     $saida = 'CARTA RESPOSTA DE CANCELAMENTO';
    elseif ($entrada == 'TDC')     $saida = 'TERMO DE DEVOLUCAO DE CHEQUE(S)';
    elseif ($entrada == 'PAP')     $saida = 'NOTIFICACAO PROCON (PAGINA 1)';
    elseif ($entrada == 'PAP2')    $saida = 'NOTIFICACAO PROCON (PAGINA 2)';
    elseif ($entrada == 'PAP3')    $saida = 'NOTIFICACAO PROCON (PAGINA 3)';
    elseif ($entrada == 'RNP1')    $saida = 'RESPOSTA NOTIFICACAO PROCON (PAGINA 1)';
    elseif ($entrada == 'RNP2')    $saida = 'RESPOSTA NOTIFICACAO PROCON (PAGINA 2)';
    elseif ($entrada == 'PAJ')     $saida = 'PETICAO DE ACORDO JURIDICO';
    elseif ($entrada == 'ATC')     $saida = 'AUTORIZACAO DE TRANSFERENCIA DE CONTA PARA TERCEIROS';
    return $saida;
}

function gravar_imagens_email($enderecofoto) {
    if (count($enderecofoto) > 0) {
        foreach ($enderecofoto as $arquivo) {
            $arq = str_replace('http://contrato.webcontrolempresas.com.br/inform/contrato/', '', $arquivo);
            shell_exec("rm -rf  area_restrita/contrato/$arq");
            $ret = shell_exec("wget $arquivo");
            shell_exec("mv $arq area_restrita/contrato/");
        }
    }
}

# Verificando se o contrato foi Digitalizado
$sql_digitalizado = "SELECT id, tp_imagem, caminho_imagem
FROM base_inform.cadastro_imagem
WHERE codloja = '{$_REQUEST['codloja']}'";
$qry_sql = mysql_query($sql_digitalizado, $con) or die("Erro SQL: $sql_digitalizado");
?>

<script src="../js/pdfobject.js"></script>
<script src="../js/pdfobject.min.js"></script> 

<body>
    <form name="form" method="post" action="#">

        <input type="hidden" name="enviar" value="1">

        <table width="100%" align="center">
            <tr>
                <td width="100%" align="center" class="titulo">C&Oacute;PIA DE DOCUMENTOS</td>
            </tr>

            <?php
            if (mysql_num_rows($qry_sql) > 0) {
                $i = 1;
                while ($registro = mysql_fetch_array($qry_sql)) {
                    $i++;
                    ?>
                    <tr>
                        <td align="center" bgcolor="#FF6600"><?= texto_1($registro['tp_imagem']) ?></td>
                    </tr>
                    <tr>
                        <td>
                            <?php
                            $xarq = $registro['caminho_imagem'];
                            $tipo_arq = strtoupper(substr($xarq, strpos($xarq, '.'), 4));
                            $arquivo = 'http://contrato.webcontrolempresas.com.br/inform/' . $xarq;

                            if ($xarq == '') {
                                ?>
                                    <img src="area_restrita/d_copia_documentos_ver.php?id=<?= $registro['id'] ?>" width="100%" height="100%">
                                <?php
                            } else {
                                if ($tipo_arq == '.JPG') {
                                    $enderecofoto[] .= 'http://contrato.webcontrolempresas.com.br/inform/' . $registro['caminho_imagem'];
                                    $endereco = 'http://contrato.webcontrolempresas.com.br/inform/' . $xarq;
                                    ?>
                                    <input type="hidden" name="endereco_foto[]" value="<?= $endereco ?>">
                                    <img src='http://contrato.webcontrolempresas.com.br/inform/<?= $xarq ?>' width="100%" height="100%">
                                    <?php
                                } else {

                                    echo "<input type='hidden' name='endereco_foto[]' value='http://contrato.webcontrolempresas.com.br/inform/$xarq'>";
                                    
                                    $xarq = str_replace('contrato/','',$xarq);
                                    shell_exec("rm -rf  /var/www/html/franquias/php/$xarq");
                                    shell_exec("rm -rf  /var/www/html/franquias/arq_tmp/$xarq");
                                    $ret = shell_exec("wget $arquivo");
                                    shell_exec("mv /var/www/html/franquias/php/$xarq /var/www/html/franquias/php/arq_tmp/");
                                    $file = 'https://www.webcontrolempresas.com.br/franquias/php/arq_tmp/'.$xarq; 
                                    echo '<iframe src="'.$file.'" width="800" height="600"></iframe>';
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                gravar_imagens_email($enderecofoto);
            }
            ?>
        </table>
        <table width="100%">
            <tr>
                <td colspan="2" width="100%" align="center" bgcolor="#FF6600">ENVIAR DOCUMENTOS POR EMAIL</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td width="20%">Informe o endere&ccedil;o de Email :</td>
                <td align="left">
                    <input type="text" style="width:40%" name="email" id="email" value="<?= $_REQUEST['email_franquia'] ?>">
                    &nbsp;&nbsp;
                    <input type="submit" name="envio" value="Enviar Email">
                </td>
            </tr>
        </table>
    </form>
</body>