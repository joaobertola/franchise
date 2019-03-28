<?
require "connect/sessao.php";
?>
<script language="javascript">
    function check3(Form) {
        var retorno = true;
        if (document.consCodigo.codigo.value == "") {
            window.alert("Informe um Código de Cliente!");
            document.consCodigo.codigo.focus();
            return false;
        }
        if (document.consCodigo.codigo.value == 0) {
            window.alert("Informe um Código diferente de 0");
            document.consCodigo.codigo.focus();
            return false;
        }
        document.consCodigo.submit();
        return (true);
    }

    //fun��o para aceitar somente numeros em determinados campos
    function soNumero() {
        var tecla;
        tecla = event.keyCode;
        if (tecla < 48 || tecla > 57)  event.returnValue = false;
    }

    window.onload = function () {
        document.consCodigo.codigo.focus();
    }

</script>
<body>
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr class="titulo">
        <td colspan="3">TABELAS DE PRE&Ccedil;OS</td>
    </tr>
    <tr>
        <td width="30%" class="subtitulodireita">&nbsp;</td>
        <td class="subtitulopequeno">&nbsp;</td>
        <td width="30%" class="subtitulopequeno">&nbsp;</td>
    </tr>
    <form name="consCodigo" method="post" action="painel.php?pagina1=Franquias/b_tabelapreco1.php">
        <tr>
            <td class="subtitulodireita">C&oacute;digo do cliente</td>
            <td class="campoesquerda"><input name="codigo" size="12" maxlength="6" class="boxnormal"
                                             OnKeyPress="soNumero();" onFocus="this.className='boxover'"
                                             onBlur="this.className='boxnormal'"/></td>
            <td class="campoesquerda"><input type="button" value="Envia" name="B12" onClick="return check3(this.form);"
                                             style="cursor:pointer;"/></td>
        </tr>
    </form>
    <tr>
        <td class="subtitulodireita">&nbsp;</td>
        <td class="subtitulopequeno"><?php echo $nome_franquia; ?></td>
        <td class="subtitulopequeno">&nbsp;</td>
    </tr>
    <tr>
        <td class="subtitulodireita">&nbsp;</td>
        <td class="subtitulopequeno">&nbsp;</td>
        <td class="subtitulopequeno">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="titulo">&nbsp;</td>
    </tr>
</table>
</body>
<script>

    $(document).keydown(function (e) {

        if (e.keyCode == 13) {
            $('input[name="B12"]').click();
        }

    });

</script>