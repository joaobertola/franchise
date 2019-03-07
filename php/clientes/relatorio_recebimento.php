<?
require "connect/sessao.php";

?>
<!--<script type="text/javascript" src="js/jquery.js"></script>-->
<script type="text/javascript">
    var matched, browser;

    jQuery.uaMatch = function (ua) {
        ua = ua.toLowerCase();

        var match = /(chrome)[ \/]([\w.]+)/.exec(ua) ||
            /(webkit)[ \/]([\w.]+)/.exec(ua) ||
            /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
            /(msie) ([\w.]+)/.exec(ua) ||
            ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) ||
            [];

        return {
            browser: match[1] || "",
            version: match[2] || "0"
        };
    };

    matched = jQuery.uaMatch(navigator.userAgent);
    browser = {};

    if (matched.browser) {
        browser[matched.browser] = true;
        browser.version = matched.version;
    }

    // Chrome is Webkit, but Webkit is also Safari.
    if (browser.chrome) {
        browser.webkit = true;
    } else if (browser.webkit) {
        browser.safari = true;
    }

    jQuery.browser = browser;

    jQuery(function ($) {
//	$("#datai").mask("99/99/9999");
//	$(".dataf").mask("99/99/9999");
    });

    //(function($){
    //  $(function(){
    //	$('input:text').setMask();
    //  }
    //);
    //})(jQuery);


    function Listar() {
        frm = document.form;
        if (frm.datai.value == "") {
            alert("Falta informar o Periodo ! ");
            frm.datai.focus();
            return false;
        }
        var ndatai = frm.datai.value;
        var ndataf = frm.dataf.value;
        frm.action = 'painel.php?pagina1=clientes/relatorio_recebimento2.php&datai=' + ndatai + '&dataf=' + ndataf;
        frm.submit();

    }

    //function formatar(mascara, documento){
    //  console.log()
    //  var i = documento.value.length;
    //  var saida = mascara.substring(0,1);
    //  var texto = mascara.substring(i);
    //
    //  if (texto.substring(0,1) != saida){
    //    documento.value += texto.substring(0,1);
    //  }
    //}

    window.onload = function () {
        document.form.datai.focus();
    }
</script>
<form method="post" action="" name="form">
    <table width=70% align="center">
        <tr>
            <td colspan="2" align="center" class="titulo">RELAT&Oacute;RIO DE RECEBIMENTO</td>
        </tr>
        <tr>
            <td width="173" class="subtitulodireita">&nbsp;</td>
            <td width="224" class="campoesquerda">&nbsp;</td>
        </tr>
        <tr>
            <td class="subtitulodireita">Per&iacute;odo</td>
            <td class="campoesquerda">
                <input type="text" name="datai" id="datai" size="10" maxlength="10"/> até
                <input type="text" name="dataf" id="dataf" class="dataf" size="10" maxlength="10"/>
                <input type="button" id="btnListar" name="btnListar" value="Listar" onclick="Listar()"/>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="titulo">&nbsp;</td>
        </tr>
    </table>
</form>
<script>
    $(document).ready(function () {

        $('#datai').keydown(function (e) {
            console.log($('#datai').val().length);
            if (e.keyCode != 8 && e.keyCode != 46) {

                if ($('#datai').val().length == 2) {
                    $('#datai').val($('#datai').val() + '/');
                } else if ($('#datai').val().length == 5) {
                    $('#datai').val($('#datai').val() + '/');
                } else if ($('#datai').val().length == 10) {
                    $('#dataf').focus();
                }

            }
        })

        $('#dataf').keydown(function (e) {
            if (e.keyCode != 8 && e.keyCode != 46) {
                if ($('#dataf').val().length == 2) {
                    $('#dataf').val($('#dataf').val() + '/');
                } else if ($('#dataf').val().length == 5) {
                    $('#dataf').val($('#dataf').val() + '/');
                }
            }
        })

    });

    $(document).keydown(function (e) {

        if ($('#dataf').is(':focus') && e.keyCode == 13) {
            $('#btnListar').click();
        }
        if(e.keyCode == 9){
            e.preventDefault();
            e.stopPropagation();
            console.log('ué');
            $('#dataf').focus();
        }

    });
</script>