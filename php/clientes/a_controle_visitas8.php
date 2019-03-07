
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="../js/jquery.cropit.js"></script>

<?php
/**
 * Created by PhpStorm.
 * User: Nadiny Costacurta
 * Date: 05/08/2016
 * Time: 09:14
 */

require "connect/sessao.php";
require "connect/sessao_r.php";
require "connect/funcoes.php";



if($id_franquia == 4 || $id_franquia == 163 || $id_franquia == 247){
    $id_franquia = 1;
}
if($_POST){

    $strDataIncl = data_mysql($_POST['iptDataIncl']);

    if(!isset($strDataIncl) || empty($strDataIncl)) { ?>
        <script>alert('Todos os campos são obrigatórios')</script>
    <?php }else {

        $sql = "INSERT INTO cs2.campeos_consultores_assistentes(data_cadastro,data_log,id_franquia)
                VALUES('$strDataIncl', NOW(),'$id_franquia')";

        $qry_insert = mysql_query( $sql, $con) or die("Falha ao gravar o registro.");

        $id = mysql_insert_id($con);


        if(!empty($_POST['iptFoto'])){

            $img_base = $_POST['iptFoto'];

            $img = substr($img_base, 22);

            //array da img e modificação da extensão
            $info_base = 'data:image/jpg;base64,'.$img;


            $info_img = explode(',', $info_base);


            $base64_img = explode('/', $info_img[0]);


            $extensao_base = explode(';', $base64_img[1]);

            //extensão
            $extensao = $extensao_base[0];

            $data = base64_decode($img);

            //tamanho da imagem
//            $size = filesize($data);

//            if($size > 5242880){ ?>
<!--                <script>alert("Tamanho máximo do arquivo 1 é de 5MB!");</script>-->
<!---->
<!--                --><?php
//                $sql = "DELETE FROM cs2.campeos_consultores_assistentes WHERE id = '$id'";
//
//                $qry_insert = mysql_query( $sql, $con) or die("Falha ao gravar o registro.");
//                die;
//            }

            $nome_imagem1 = 'campeos_'.$id;

            $caminho_imagem1 = "clientes/fotos_campeos/" . $nome_imagem1 . '.' .'jpg';

            if(file_exists($caminho_imagem1)){
                unlink($caminho_imagem1);
            }

            file_put_contents($caminho_imagem1, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $info_base)));

        }

    }

}

?>
<style>
    .cropit-preview {
        background-color: #f8f8f8;
        background-size: cover;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-top: 7px;
        width: 294px;
        height: 212px;
    }

    .cropit-preview-image-container {
        cursor: move;
    }

    .image-size-label {
        margin-top: 10px;
    }

    input, .export {
        display: block;
    }

    button {
        margin-top: 10px;
    }

    .splash .controls-wrapper .slider-wrapper .cropit-image-zoom-input.custom, .demos .demo-wrapper .controls-wrapper .slider-wrapper .cropit-image-zoom-input.custom {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        height: 5px;
        background: #eee;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        outline: none;
    }


</style>
<?php

$sql_sel = "
    SELECT
        id,
        DATE_FORMAT(data_cadastro,'%d/%m/%Y') AS data_cadastro
    FROM cs2.campeos_consultores_assistentes
    WHERE id_franquia = '$id_franquia'";
$qry = mysql_query($sql_sel, $con);

?>


<script language="javascript">
    function MM_formtCep(e,src,mask) {
        if(window.event) { _TXT = e.keyCode; }
        else if(e.which) { _TXT = e.which; }
        if(_TXT > 47 && _TXT < 58) {
            var i = src.value.length; var saida = mask.substring(0,1); var texto = mask.substring(i)
            if (texto.substring(0,1) != saida) { src.value += texto.substring(0,1); }
            return true; } else { if (_TXT != 8) { return false; }
        else { return true; }
        }
    }
</script>

<form name="form1" method="post" action="" enctype="multipart/form-data" id="form1">
    <table border="0" align="center" width="700">
        <tr>
            <td colspan="2" class="titulo"><br>CADASTRAR CAMPEÃO</td>
        </tr>
        <tr>
            <td width="200" class="subtitulodireita">&nbsp;</td>
            <td class="subtitulopequeno">&nbsp;</td>
        </tr>
        <tr>
            <td class="subtitulodireita">Data de Cadastro</td>
            <td class="subtitulopequeno"><input name="iptDataIncl" type="text" id="iptDataIncl" value="<?php echo date('d/m/Y')?>" size="15" maxlength="10"  onFocus="this.className='boxover'" onKeyPress="return MM_formtCep(event,this,'##/##/####');" onBlur="this.className='boxnormal'" /></td>
        </tr>
        <tr>
            <td class="subtitulodireita">Imagem</td>
            <td class="subtitulopequeno">
                <div class="image-editor" style="margin-top: 5px">
                    <input type="file" class="cropit-image-input" id="cropped-img" >
                    <div class="cropit-preview"></div>
                    <div class="image-size-label">
                        Imagem Redimensionada
                    </div>
                    <input type="range" class="cropit-image-zoom-input">
                    <input type="hidden" class="hidden-image-data" id="iptFoto" name="iptFoto" />
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="3" align="center">
                <button type="submit" id="btnSalvar" name="btnSalvar">Gravar</button>
            </td>
        </tr>
    </table>
</form>

<div class="row">
    <form id="frmExcluir" name="frmExcluir" method="post" action="clientes/a_remover_campeao.php">
        <input type="hidden" name="id" id="id"/>
        <table border="0" align="center" width="800">

            <thead>
                <tr style="font-weight: bold">
                    <td style="text-align: center" class="subtitulodireita">ID</td>
                    <td style="text-align: center" class="subtitulodireita">Data</td>
                    <td style="text-align: center" class="subtitulodireita">Ação</A></td>
                </tr>
            </thead>

            <?php while($rs = mysql_fetch_array($qry)) { ?>
                <tr>
                    <td class="subtitulopequeno"><?php echo $rs['id']?></td>
                    <td class="subtitulopequeno"><?php echo $rs['data_cadastro']?></td>
                    <td class="subtitulopequeno" style="text-align: center;"><a class="btnRemover" onclick="removerCampeao(<?php echo $rs['id']; ?>)">Remover</a> </td>
                </tr>
            <?php } ?>

        </table>
    </form>
</div>

<script>
    function removerCampeao(id){

        var form = document.forms.frmExcluir;

        form.id.value = id;

        form.submit();

    }

    $(function() {
        $('.image-editor').cropit();

        $('#form1').on('submit', function(){
            // Move cropped image data to hidden input
            var imageData = $('.image-editor').cropit('export', {
                type: 'image/jpg'
            });

            $('.hidden-image-data').val(imageData);
        });
    });
</script>
