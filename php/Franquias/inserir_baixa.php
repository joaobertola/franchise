
<?php
include("connect/conexao_conecta.php");
?>
<script type="text/javascript" src="../../js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="../../js/jquery.maskedinput-1.1.1.js"></script>
<script type="text/javascript" src="../../js/jquery.meio.mask.js"></script>


<script language="javascript">
jQuery(function($){
   $("#data_id").mask("99/99/9999");     
});

(function($){
$(
	function(){
		$('input:text').setMask();
	}
);
})(jQuery);

function trim(str){return str.replace(/^\s+|\s+$/g,"");}//valida espa�o em branco

function valida(){
    
    frm = document.form;
    if(trim(frm.data_pgto.value) == ""){
        alert("Falta informar a Data de Pagamento !");
        frm.data_pgto.focus();
        return false;
    }
    if(trim(frm.valor_pgto.value) == "0,00"){
        alert("Falta informar o Valor de Pagamento !");
        frm.valor_pgto.focus();
        return false;
    }
    if(trim(frm.valor_pgto.value) == ""){
        alert("Falta informar o Valor de Pagamento !");
        frm.valor_pgto.focus();
        return false;
    }
    frm.action = 'Franquias/inserir_baixa2.php';
    frm.submit();
    
}

window.onload = function(){document.form.data_pgto.focus();} 
</script>

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/tabela.css" rel="stylesheet" type="text/css" />

</head>

<form name="form" method="post" action="#">
    <input type="hidden" name="acao" value="1">
    <input type="hidden" name="codloja" value="<?=$_REQUEST['codloja']?>">
    <input type="hidden" name="contano" value="<?=$_REQUEST['contano']?>">
    <input type="hidden" name="contmes" value="<?=$_REQUEST['contmes']?>">
    <input type="hidden" name="opcao" value="<?=$_REQUEST['opcao']?>">
    <input type="hidden" name="ordenacao" value="<?=$_REQUEST['ordenacao']?>">
    <input type="hidden" name="canceladoprecancelado" value="<?=$_REQUEST['canceladoprecancelado']?>">
    <input type="hidden" name="franqueado" value="<?=$_REQUEST['franqueado']?>">
    <input type="hidden" name="destino_pgto" value="<?=$_REQUEST['destino_pgto']?>">
    <p>&nbsp;</p>

    <?php
    
    $empresa = $_REQUEST['logon'].' - '.$_REQUEST['empresa'];

    if ( $_REQUEST['destino_pgto'] == 'VVI' ) $destino = 'Baixa VVI';
    elseif ( $_REQUEST['destino_pgto'] == 'FIX' ) $destino = 'Baixa Fx';
    else $destino = 'Baixa Ades&atilde;o';
    
    ?>

    <table border="0" width="99%" align="center" cellpadding="2" cellspacing="1" class="borda_tabela">
        <tr>
            <td colspan="2" class="titulo">Informe Data e Valor ( <?=$destino?> ) </td>
        </tr>
        <tr
            <td width="30%" class="subtitulodireita">Cliente</td>
            <td width="70%" class="subtitulopequeno"><?=$empresa?></td>
        </tr>
        <tr>
            <td width="30%" class="subtitulodireita">Data Pagamento</td>
            <td width="70%" class="subtitulopequeno">
                <input id="data_id" name="data_pgto" value="<?=date("d/m/Y")?>" type="text" class="boxnormal" style="width:30%" maxlength="10" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
                (*)
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">Valor Pago</td>
            <td class="subtitulopequeno">
                <input name="valor_pgto" type="text" id="valor" class="boxnormal" style="width:30%"  maxlength="10"  onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" alt="decimal" />
                (*)
            </td>
        </tr>
        <tr class="altura_button">    
            <td>&nbsp;</td>
            <td>
                <input type="button" value="Confirma&nbsp;" class="botao_padrao" onclick="valida()"/>&nbsp;&nbsp;&nbsp;<input type="button" value="Voltar&nbsp;" class="botao_padrao" onclick="history.back()"/>
            </td>
        </tr>
    </table>

</form>

<script>
    var mask = {
        money: function() {
            var el = this
                ,exec = function(v) {
                v = v.replace(/\D/g,"");
                v = new String(Number(v));
                var len = v.length;
                if (1== len)
                    v = v.replace(/(\d)/,"0.0$1");
                else if (2 == len)
                    v = v.replace(/(\d)/," 0.$1");
                else if (len > 2) {
                    v = v.replace(/(\d{2})$/,'.$1');
                }
                return  v;
            };

            setTimeout(function(){
                el.value = exec(el.value);
            },1);
        }

    }

    $(function(){
        $('#valor').bind('keypress',mask.money)
    });
    </script>