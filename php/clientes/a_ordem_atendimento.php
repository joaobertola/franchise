<?php
require "connect/sessao_r.php";
?>
<script type="text/javascript">

    $(document).ready(function () {
        $('#franqueado').change(function (e) {
            var dados = (this.value);
            $("input[name=id_franquia]").val(dados);
            $("input[name=nome_franquia]").val($(this).find('option:selected').text());
        });
    });

    function confirmar() {
        frm = document.getElementById('novo').submit()
    }

</script>

<form name="cadAtendente" method="post" action="" >
    <table border="0" align="center" width="640">
        <tr>
            <td colspan="2" class="titulo">Ordens de Atendimento</td>
        </tr>
        <tr>
            <td class="subtitulodireita">&nbsp;</td>
            <td class="subtitulopequeno">(*) Preenchimento obrigat&oacute;rio</td>
        </tr>
        <tr>
            <td class="subtitulodireita">C&oacute;digo da Franquia</td>
            <td class="subtitulopequeno">
                <?php
                if (($tipo == "a") || ($tipo == "c")) {
                    echo "<select name='franqueado' id='franqueado' class=boxnormal>";
                    $sql = "select * from cs2.franquia where tipo='b' and classificacao = 'M' order by id";
                    $resposta = mysql_query($sql, $con);
                    echo "<option value='0'>.. Selecione a Franquia ..</option>\n";
                    echo "<option value='x'>TODAS AS FRANQUIAS</option>\n";
                    while ($array = mysql_fetch_array($resposta)) {
                        $franquia = $array["id"];
                        $nome_franquia = $array["fantasia"];
                        echo "<option value=\"$franquia\">$nome_franquia</option>\n";
                    }
                    echo "</select>";
                } else {
                    echo $nome_franquia;
                    echo "<input name=\"franqueado\" type=\"hidden\" id=\"franqueado\" value= $id_franquia; />";
                    echo '<script>
					setTimeout(function(){
						$("input[name=id_franquia]").val("' . $id_franquia . '");
						$("input[name=nome_franquia]").val("' . $nome_franquia . '");
						}, 300);
				</script>';
                }
                ?> 
                <input type="hidden" name="go" value="cadastrar">
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">&nbsp;</td>
            <td class="subtitulopequeno">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" class="titulo">&nbsp;</td>
        </tr>
    </table>
    <table align="center">
        <tr align="center">
            <td>
                <input name="submit" type="button" value="Confirmar           " onclick="confirmar()" />
            </td>
            <td>
                <input name="submit2" type="reset" value="             Cancela" />        </td>
        </tr>
    </table>
</form>
<form id="novo" method="POST" action="https://webcontrolempresas.com.br/atendimento/index.php" target="_blank">
    <input type="hidden" name="id_franquia" value="" />
    <input type="hidden" name="nome_franquia" value="" />
    <input type="hidden" name="id_cliente" value="" />
</form>