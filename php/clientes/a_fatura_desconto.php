<meta charset="iso-8859-1" />
<?php
    require ("connect/sessao.php");
?>
<meta charset="iso-8859-9" />
<script type="text/javascript" src="../../js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="../../js/jquery.maskedinput-1.1.1.js"></script>
<script type="text/javascript" src="../../js/jquery.meio.mask.js"></script>
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
    
function Chr(AsciiNum)
{
    return String.fromCharCode(AsciiNum)
}

function mostra_dados(venc){
    
    frm = document.form;
    // Texto 1
    if ( frm.texto1.checked ){
         frm.obs.value = 'DESCONTO - Taxa Adicional Mes-Dezembro'+Chr(13);
    } else {
        // texto2 desmarcado, limpar do textarea
        var newtext = frm.obs.value;
        frm.obs.value = newtext.replace('DESCONTO - Taxa Adicional Mes-Dezembro' , '');
    }
    // Texto 2
    if ( frm.texto2.checked ){
         frm.obs.value = 'DESCONTO - Licenças de Software e Soluções Exclusivas'+Chr(13);
    } else {
        // texto2 desmarcado, limpar do textarea
        var newtext = frm.obs.value;
        frm.obs.value = newtext.replace('DESCONTO - Licenças de Software e Soluções Exclusivas' , '');
    }
    // Texto 3
    if ( frm.texto3.checked ){
         frm.obs.value = 'REFERENTE - Mensalidade com vencimento em ' + venc + Chr(13);
    } else {
        // texto3 desmarcado, limpar do textarea
        var newtext = frm.obs.value;
        frm.obs.value = newtext.replace('REFERENTE - Mensalidade com vencimento em ' + venc, '');
    }
    // Texto 4
    if ( frm.texto4.checked ){
         frm.obs.value = 'DESCONTO - Pesquisas e/ou Bloqueios'+Chr(13);
    } else {
        // texto4 desmarcado, limpar do textarea
        var newtext = frm.obs.value;
        frm.obs.value = newtext.replace('DESCONTO - Pesquisas e/ou Bloqueios' , '');
    }
}

function trim(str){
    return str.replace(/^\s+|\s+$/g,"");
}//valida espa�o em branco

function confirma(){
    
    frm = document.form;
    if( trim(frm.valor.value) == ""){
        alert("Falta Informar a VALOR !");
        frm.valor.focus();
        return false;
    }
    if( trim(frm.valor.value) == "0,00"){
        alert("Falta Informar a VALOR !");
        frm.valor.focus();
        return false;
    }
    if( trim(frm.tipo_lancamento.value) == "I"){
        alert("Selecione o Tipo de Lan�amento !");
        frm.tipo_lancamento.focus();
        return false;
    }
    if( trim(frm.obs.value) == ""){
        alert("Digite ou selecione um CONTE�DO no campo Mensagem !");
        frm.tipo_lancamento.focus();
        return false;
    }   

    frm.action = 'painel.php?pagina1=clientes/a_fatura_desconto_gravar.php';
    frm.submit();
}


function alerta_excluir(){
    if(confirm("CONFIRMA O EXCLUS�O DESTE LAN�AMENTO ?")) {
    } else {
        return false
    }
}

window.onload = function() {
    document.form.tipo_lancamento.focus(); 
}
</script>

<?php
    require ("connect/sessao.php");
    
    $codloja = $_REQUEST['codloja'];
    $numdoc = $_REQUEST['numdoc'];
    
    // Buscando dados do cliente e do titulo
    $sql = "SELECT a.razaosoc, CAST(MID(b.logon,1,6) AS UNSIGNED) AS logon, date_format(c.vencimento,'%d/%m/%Y') AS vencimento, c.valor, a.codloja FROM cs2.cadastro a
            INNER JOIN cs2.logon b ON a.codloja = b.codloja 
            INNER JOIN cs2.titulos c on a.codloja = b.codloja
            WHERE a.codloja = '$codloja' and c.numdoc = '$numdoc'";
    $qry_sql = mysql_query($sql,$con) or die('Erro SQL, contate a WEBCONTROLEMPRESAS');
    
    $registro = mysql_fetch_array($qry_sql);
    $razaosoc = $registro['razaosoc'];
    $logon = $registro['logon'];
    $vencimento = $registro['vencimento'];
    $valor = $registro['valor'];
    $codloja = $registro['codloja'];

    ?>
    <form method="post" action="#" name="form">
        <table align='center' width='750' border='0' cellpadding='0' cellspacing='1'>
            <tr>
                <td colspan='11' class='titulo' align="center">LAN&Ccedil;AMENTO DE VALORES EM FATURA</td>
            </tr>
            <tr>
                <td colspan='10'>&nbsp;</td>
            </tr>
            <tr>
                <td colspan='2' class='subtitulodireita'>C&oacute;digo</td>
                <td colspan='9' class='subtitulopequeno'><?=$logon?></td>
            </tr>
        
                    <tr>
                        <td colspan='2' class='subtitulodireita'>Raz&atilde;o Social</td>
                        <td colspan='9' class='subtitulopequeno'><?=$razaosoc?></td>
                    </tr>
                    <tr>
                        <td colspan='2' class='subtitulodireita'>Documento</td>
                        <td colspan='5' class='subtitulopequeno'><?=$numdoc?></td>
                        <td width="10%" class='subtitulodireita'>Vencimento:</td>
                        <td width="10%" class='subtitulopequeno'><?php echo $vencimento; ?></td>
                        <td width="10%" class='subtitulodireita'>Valor : </td>
                        <td width="10%" class='subtitulopequeno'>R$ <?=$valor?></td>
                    </tr>
                    <tr>
                        <td colspan='2'>&nbsp;</td>
                        <td colspan='9'>&nbsp;</td>
                    </tr>
                    
                    <tr>
                        <td colspan='2' class="subtitulodireita">Tipo de Lan&ccedil;amento</td>
                        <td colspan='9' class="campoesquerda">
                            <select name="tipo_lancamento">
                                <option value="I">... Selecione ...</option>
                                <option value="D">D&eacute;bito</option>
                                <option value="C">Cr&eacute;dito</option>
                             </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' class="subtitulodireita">Valor</td>
                        <td colspan='9' class="campoesquerda">
                            <input type="text" name="valor"  alt="decimal" id="valor" style="width:20%;"/>
                            <input type="hidden" name="numdoc" value="<?=$numdoc?>" />
                            <input type="hidden" name="codloja" value="<?=$codloja?>" />
                        </td>
                    </tr>
                    
                <tr>
                    <td colspan='2' class="subtitulodireita">Mensagem</td>
                    <td colspan='9' class="campoesquerda">
                        <input type="checkbox" name="texto1" onclick="mostra_dados('')"/> 
                        DESCONTO - Taxa Adicional Mes-Dezembro
                        <br>
                        <input type="checkbox" name="texto2" onclick="mostra_dados('')"/> 
                        DESCONTO - Licen&ccedil;as de Software e Solu&ccedil;&otilde;es Exclusivas
                        <br>
                        <input type="checkbox" name="texto3" onclick="mostra_dados('<?=$vencimento?>')"/> 
                        REFERENTE - Mensalidade com vencimento em <?=$vencimento?>
                        <br>
                        <input type="checkbox" name="texto4" onclick="mostra_dados('')"/> 
                        DESCONTO - Pesquisas e/ou Bloqueios       
                    </td>
                </tr>
                <tr>  
                    <td colspan='2' class="subtitulodireita">&nbsp;</td>
                    <td colspan='9' class="campoesquerda" valign="top"><textarea style="width:90%;" rows='5' name='obs'></textarea></td>
                </tr>
                <tr>
                    <td colspan='11'>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='11' align="center"><input type="button" value="  GRAVAR   REGISTRO  " name="Confirma" onclick="confirma()" style="cursor:pointer"/></td>
                </tr>
                
                <tr>
                    <td colspan='11'>&nbsp;</td>
                </tr> 
                
                <tr>
                    <td colspan='11' class='titulo' align="center">Lançamentos Cadastrados para este TÍTULO</td>
                <tr>
                    <td colspan='11'>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='7' class="subtitulodireita">Descri&ccedil;&atilde;o</td>
                    <td class="subtitulodireita">Data</td>
                    <td colspan="2" class="subtitulodireita">Valor</td>
                    <td class="subtitulodireita">...</td>
                </tr>
                <?php
                
                    $sql_desconto = "SELECT Id, descricao,  date_format(data,'%d/%m/%Y') as data, valor FROM cs2.vr_extra_faturado WHERE numdoc = $numdoc ORDER BY Id";
                    $qry_desconto = mysql_query($sql_desconto, $con ) or die ("Erro SQL: $sql_desconto");
                    while ( $reg = mysql_fetch_array($qry_desconto) ){
                        $id = $reg['Id'];
                        $descricao = $reg['descricao'];
                        $data = $reg['data'];
                        $valor = $reg['valor'];
                        echo "<tr>
                                <td colspan='7' class='subtitulopequeno'>$descricao</td>
                                <td class='subtitulopequeno'>$data</td>
                                <td colspan='2' class='subtitulopequeno'>$valor</td>
                                <td align='center'>
                                    <a  href='painel.php?pagina1=clientes/a_fatura_desconto_excluir.php&numdoc=$numdoc&id=$id' onMouseOver=\"window.status='Exclus�o de Lan�amento'; return true\" title='Clique para Excluir o Lan�amento' onclick='return alerta_excluir()'><IMG SRC='../img/exc.gif' width='16' height='16' border='0'></a>
                                </td>
                                
                              </tr>";
                    }
                 ?>
        </table>
    </form>