<style>
    select[readonly],
    input[readonly] {
        background: #eee;
        /*Simular campo inativo - Sugestão @GabrielRodrigues*/
        pointer-events: none;
        touch-action: none;
    }
</style>
<?php
require_once "connect/sessao.php";
require_once "connect/conexao_conecta.php";
require_once "connect/funcoes.php";

if ($_POST['id_anuncio']) {
    foreach ($_POST as $i => $v) {
        ${$i} = trim(utf8_decode(utf8_encode($v)));
    }

    switch ($tipo) {
        case 'S':
            if (isset($_FILES['banner']) && !empty($_FILES['banner']['name']) && $_FILES['banner']['size'] > 0) {
                if (!empty($caminhoBanner)) {
                    unlink(str_replace('franquias/', '../', substr($caminhoBanner, strpos($caminhoBanner, 'franquias/'))));
                }

                $dominio = 'anunciantes/' . $codloja;

                $tmp_name = $_FILES['banner']['tmp_name'];
                $error    = $_FILES['banner']['error'];
                $typeFile = substr($_FILES['banner']['name'], (strpos($_FILES['banner']['name'], '.')));

                $nameUpload = time() . $typeFile;

                $caminhoUpload     = '../' . $dominio;
                $caminhoBD = $_SERVER['HTTP_ORIGIN'] . '/franquias/' . $dominio . '/' . $nameUpload;

                if (!is_dir($caminhoUpload)) {
                    mkdir($caminhoUpload, 0777, true);
                }

                move_uploaded_file($tmp_name, $caminhoUpload . '/' . $nameUpload);
            }

            if (!empty($caminhoBD)) {
                $atualizaBanner = ", banner = '{$caminhoBD}'";
            }

            $sql = "UPDATE cs2.anunciantes 
                        SET tipo = '{$tipo}', data_inicio = '{$data_inicio}', data_fim = '{$data_fim}', 
                            ativo = '{$situacao}', tipo_sistema = '{$tipo_sistema}', url = '{$url}' {$atualizaBanner}
                        WHERE id = {$_POST['id_anuncio']}";
            $qry = mysql_query($sql, $con);

            if ($qry) {
                echo "<p><label style='color:blue'>Editado com sucesso!</label></p>";
            } else {
                echo "<p><label style='color:red'>Não foi possível editar o anúncio!</label></p>";
            }

            $qry = mysql_close($con);

            break;
    }
} elseif ($_GET['id_anuncio']) {
    $codAnuncio = $_GET['id_anuncio'];
    $sql = "SELECT c.nomefantasia, a.* FROM cs2.anunciantes a 
    LEFT JOIN cs2.cadastro c
    ON a.codloja = c.codloja 
    WHERE a.id = $codAnuncio";

    $qry     = mysql_query($sql, $con) or die($sql);
    $total     = mysql_num_rows($qry);

    if ($total > 0) {
        $anuncio = mysql_fetch_assoc($qry);
?>
        <form enctype="multipart/form-data" action="" method="post">
            <input type="hidden" name="id_anuncio" value="<?= $anuncio['id'] ?>" />
            <input type="hidden" name="caminhoBanner" value="<?= $anuncio['banner'] ?>" />

            <table border="0" align="center" width="640">
                <thead bgcolor="#CFCFCF">
                    <tr>
                        <th colspan="2" class="titulo">EDITAR ANUNCIANTE</th>
                    </tr>
                </thead>
                <tbody bgcolor="#CFCFCF">
                    <tr>
                        <th>ID do cliente</th>
                        <td><input type='text' name='codloja' value='<?= $anuncio['codloja'] ?>' readonly /></td>
                    </tr>
                    <tr>
                        <th>Nome Fantasia</th>
                        <td><input type='text' name='nomefantasia' value='<?= $anuncio['nomefantasia'] ?>' readonly /></td>
                    </tr>
                    <tr>
                        <th>Tipo</th>
                        <td>
                            <select name='tipo' readonly="readonly" tabindex="-1" aria-disabled="true">
                                <option value='B' <?php echo $anuncio['tipo'] == 'B' ? 'selected' : '' ?>>Boleto</option>
                                <option value='C' <?php echo $anuncio['tipo'] == 'C' ? 'selected' : '' ?>>Consultor</option>
                                <option value='S' <?php echo $anuncio['tipo'] == 'S' ? 'selected' : '' ?>>Sistema</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
                <tbody bgcolor="#CFCFCF" id="sistema">
                    <tr>
                        <th>Data Início</th>
                        <td>
                            <input type='date' name='data_inicio' value='<?= $anuncio['data_inicio'] ?>' required />
                        </td>
                    </tr>
                    <tr>
                        <th>Data Fim</th>
                        <td>
                            <input type='date' name='data_fim' value='<?= date('Y-m-d', strtotime($anuncio['data_fim'])) ?>' required />
                        </td>
                    </tr>
                    <tr>
                        <th>Situação</th>
                        <td>
                            <select name='situacao'>
                                <option value='S' <?php echo $anuncio['ativo'] == 'S' ? 'selected' : '' ?>>Ativo</option>
                                <option value='N' <?php echo $anuncio['ativo'] == 'N' ? 'selected' : '' ?>>Nao Ativo</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Banner</th>
                        <td><input type="file" name="banner"></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <label for="type_lead">Lead</label>
                            <input id="type_lead" type="radio" name="tipo_sistema" value="lead" <?php echo $anuncio['tipo_sistema'] == 'lead' ? 'checked' : '' ?>>

                            <label for="type_link">Link</label>
                            <input id="type_link" type="radio" name="tipo_sistema" value="link" <?php echo $anuncio['tipo_sistema'] == 'link' ? 'checked' : '' ?>>
                        </td>
                    </tr>
                    <tr id="link">
                        <td colspan=2 align="center">
                            <input type="text" name="url" placeholder="url" style="width: 98%;" value='<?= $anuncio['url'] ?>'>
                        </td>
                    </tr>
                </tbody>
                <tfooter>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type='submit' name='editar_anunciantes' value="Salvar" />
                            <input type='reset' name='' value='Limpar' />
                        </td>
                    </tr>
                </tfooter>
            </table>
        </form>
<?php
    } else {
        echo "<p>Anúncio não encontrado!</p>";
    }
} else {
    echo "<p>Codigo do anuncio não passado!</p>";
}
?>

<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
<script language="JavaScript" src="../js/jquery.meio.mask.js" type="text/javascript"></script>

<script>
    $(document).ready(function() {
        let tipo = $('select[name=tipo] option').filter(':selected').val();
        esconderTipo(tipo);

        let tipoSistema = $('input[name = "tipo_sistema"]:checked').val();
        esconderTipoSistema(tipoSistema);

        $('select[name=tipo]').change(function() {
            var valor = $('select[name=tipo] option').filter(':selected').val();

            esconderTipo(valor)
        });

        $('input[name = "tipo_sistema"]').change(function() {
            var valor = $('input[name = "tipo_sistema"]:checked').val();

            esconderTipoSistema(valor);
        });
    });

    function esconderTipo(valor) {
        switch (valor) {
            case 'S':
                $('#sistema').show();
                break;
            default:
                $('#sistema').hide();
                break;
        }
    }

    function esconderTipoSistema(valor) {
        switch (valor) {
            case 'lead':
                $('#link').hide();
                break;
            case 'link':
                $('#link').show();
                break;
        }
    }
</script>