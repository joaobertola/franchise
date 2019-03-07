<?php
require "connect/sessao.php";
require "connect/sessao_r.php";

$con = @mysql_connect("10.2.2.3", "csinform", "inform4416#scf");

$id = $_GET['id'];

$sql = "SELECT * FROM cs2.concorrente WHERE id = ".$id."";

$query = mysql_query( $sql, $con) or die(mysql_error());
$result = array(); 

$result = mysql_fetch_array($query);

switch ($result['forma_cobranca']) {
        case 'M':
            $forma_cobranca = 'Mensal';
        break;

        case 'T':
            $forma_cobranca = 'Trimestral';
        break;     

        case 'S':
            $forma_cobranca = 'Semestral';
        break;      

        case 'A':
            $forma_cobranca = 'Anual';
        break;  

        case 'U':
            $forma_cobranca = 'Tarifa Unica';
        break;                        
}

function formataReal($formataReal) {
    if ($formataReal) {
        $modificado = number_format((float) $formataReal, 2, ',', '.');
        return $modificado;
    } else {
        if (floatval($formataReal) == 0 || intval($formataReal) == 0) {
            return 'R$ 0,00';
        }
    }
}

if (isset($_POST['btnEdit'])) {

        $nome                   = $_POST['nome'];
        $valor_sistema_aux      = str_replace('.', '', $_POST['valor_sistema']);
        $valor_sistema          = str_replace(',', '.', $valor_sistema_aux);
        $forma_cobranca         = $_POST['forma_cobranca'];
        $pag_cadastro_clientes  = $_POST['pag_cadastro_clientes'];
        $pag_controle_estoque   = $_POST['pag_controle_estoque'];
        $pag_nota_fiscal        = $_POST['pag_nota_fiscal'];
        $pag_rel_financeiro     = $_POST['pag_rel_financeiro'];
        $pag_contas_pagar       = $_POST['pag_contas_pagar'];
        $pag_listagem_cliente   = $_POST['pag_listagem_cliente'];
        $pag_email_mkt          = $_POST['pag_email_mkt'];
        $pag_torpedo_mkt        = $_POST['pag_torpedo_mkt'];
        $pag_boletos_cred_carne = $_POST['pag_boletos_cred_carne'];
        $pag_parcel_divida      = $_POST['pag_parcel_divida'];
        $pag_local_devedores    = $_POST['pag_local_devedores'];
        $pag_arquivos_pasta     = $_POST['pag_arquivos_pasta'];
        $pag_consulta_cred      = $_POST['pag_consulta_cred'];
        $pag_negativacao_spc    = $_POST['pag_negativacao_spc'];
        $pag_site               = $_POST['pag_site'];
        $pag_loja               = $_POST['pag_loja'];
        $pag_web_offline        = $_POST['pag_web_offline'];
        $pag_mercado_livre      = $_POST['pag_mercado_livre'];


        $sql = "
            UPDATE cs2.concorrente 
                SET nome                    = '$nome',
                    valor_sistema           = '$valor_sistema',
                    forma_cobranca          = '$forma_cobranca',
                    pag_cadastro_clientes   = '$pag_cadastro_clientes',
                    pag_controle_estoque    = '$pag_controle_estoque',
                    pag_nota_fiscal         = '$pag_nota_fiscal',
                    pag_rel_financeiro      = '$pag_rel_financeiro',
                    pag_contas_pagar        = '$pag_contas_pagar',
                    pag_listagem_cliente    = '$pag_listagem_cliente',
                    pag_email_mkt           = '$pag_email_mkt',
                    pag_torpedo_mkt         = '$pag_torpedo_mkt',
                    pag_boletos_cred_carne  = '$pag_boletos_cred_carne',
                    pag_parcel_divida       = '$pag_parcel_divida',
                    pag_local_devedores     = '$pag_local_devedores',
                    pag_arquivos_pasta      = '$pag_arquivos_pasta',
                    pag_consulta_cred       = '$pag_consulta_cred',
                    pag_negativacao_spc     = '$pag_negativacao_spc',
                    pag_site                = '$pag_site',
                    pag_loja                = '$pag_loja',
                    pag_web_offline         = '$pag_web_offline',
                    pag_mercado_livre       = '$pag_mercado_livre'
            WHERE 
                id = '$id'
            ";  


        $qry_insert = mysql_query( $sql, $con) or die(mysql_error());

        echo "<meta HTTP-EQUIV='refresh' CONTENT='0'>";
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<form name="form2" method="post">
    <table border="0" align="center" width="700">
        <tr>
            <td colspan="3" class="titulo">Visualização Concorrente</td>
        </tr>
        <tr>
            <td class="subtitulodireita">Nome Sistema: </td>
            <td colspan="2" class="subtitulopequeno">
                <input type="text" name="nome" id="nome_sistema" value="<?php echo $result['nome'] ?>">
            </td>
        </tr>

        <tr>
            <td width="200" class="subtitulodireita">Valor do Sistema: </td>
            <td colspan="2" class="subtitulopequeno">
                <input type="text" name="valor_sistema" id="valor_sistema" value="<?php echo formataReal($result['valor_sistema']) ?>">
            </td>
        </tr>
        <tr>
            <td width="200" class="subtitulodireita">Forma Cobrança: </td>
            <td colspan="2" class="subtitulopequeno">
                <select name="forma_cobranca">
                    <option value="M" <?php echo ($result['forma_cobranca'] == 'M')? 'selected="selected"':"" ?> >Mensal</option>
                    <option value="T" <?php echo ($result['forma_cobranca'] == 'T')? 'selected="selected"':"" ?> >Trimestral</option>
                    <option value="S" <?php echo ($result['forma_cobranca'] == 'S')? 'selected="selected"':"" ?> >Semestral</option>
                    <option value="A" <?php echo ($result['forma_cobranca'] == 'A')? 'selected="selected"':"" ?> >Anual</option>
                    <option value="U" <?php echo ($result['forma_cobranca'] == 'U')? 'selected="selected"':"" ?> >Tarifa Unica</option>
                </select>
            </td>            
        </tr>     
        <tr>
            <td width="200" class="subtitulodireita">Soluções Oferecidas: </td>
            <td colspan="2" class="subtitulopequeno">
                <div style="float: left; width: 200px">
                    <label style="width: 100px !important;">Cadastro de Clientes: </label>
                </div>
                <div >
                        <input type="radio" name="pag_cadastro_clientes" value="S" <?php echo($result['pag_cadastro_clientes'] == 'S')? 'checked="checked"':"" ?>> Sim
                        <input type="radio" name="pag_cadastro_clientes" value="N" <?php echo($result['pag_cadastro_clientes'] == 'N')? 'checked="checked"':"" ?>> Não
                </div>
                <div style="float: left; width: 200px">
                    <label style="width: 100px;">Controle Estoque: </label>
                </div>
                <div >
                    <input type="radio" name="pag_controle_estoque" value="S" <?php echo($result['pag_controle_estoque'] == 'S')? 'checked="checked"':"" ?>> Sim
                    <input type="radio" name="pag_controle_estoque" value="N" <?php echo($result['pag_controle_estoque'] == 'N')? 'checked="checked"':"" ?>> Não
                </div>          
                <div style="float: left; width: 200px">
                    <label>Nota Fiscal (NFe, NFc-e, NFs-e): </label>
                </div>
                <div >
                    <input type="radio" name="pag_nota_fiscal" value="S" <?php echo($result['pag_nota_fiscal'] == 'S')? 'checked="checked"':"" ?>> Sim
                    <input type="radio" name="pag_nota_fiscal" value="N" <?php echo($result['pag_nota_fiscal'] == 'N')? 'checked="checked"':"" ?>> Não
                </div>    
                <div style="float: left; width: 200px">
                    <label>Relatórios Financeiros: </label>
                </div>
                <div >
                    <input type="radio" name="pag_rel_financeiro" value="S" <?php echo($result['pag_rel_financeiro'] == 'S')? 'checked="checked"':"" ?>> Sim
                    <input type="radio" name="pag_rel_financeiro" value="N" <?php echo($result['pag_rel_financeiro'] == 'N')? 'checked="checked"':"" ?>> Não
                </div>   
                <div style="float: left; width: 200px">
                    <label>Contas a Pagar e Receber: </label>
                </div>
                <div >
                    <input type="radio" name="pag_contas_pagar" value="S" <?php echo($result['pag_contas_pagar'] == 'S')? 'checked="checked"':"" ?>> Sim
                    <input type="radio" name="pag_contas_pagar" value="N" <?php echo($result['pag_contas_pagar'] == 'N')? 'checked="checked"':"" ?>> Não
                </div>    
                <div style="float: left; width: 200px">
                    <label>Listagem de Novos Clientes: </label>
                </div>
                <div>
                    <input type="radio" name="pag_listagem_cliente" value="S" <?php echo($result['pag_listagem_cliente'] == 'S')? 'checked="checked"':"" ?>> Sim
                    <input type="radio" name="pag_listagem_cliente" value="N" <?php echo($result['pag_listagem_cliente'] == 'N')? 'checked="checked"':"" ?>> Não   
                </div>    
                <div style="float: left; width: 200px">
                    <label>E-mail Marketing Massificado: </label>
                </div>
                <div>
                    <input type="radio" name="pag_email_mkt" value="S" <?php echo($result['pag_email_mkt'] == 'S')? 'checked="checked"':"" ?>> Sim
                    <input type="radio" name="pag_email_mkt" value="N" <?php echo($result['pag_email_mkt'] == 'N')? 'checked="checked"':"" ?>> Não      
                </div> 
                <div style="float: left; width: 200px">
                    <label>Torpedo Marketing Massificado: </label>
                </div>
                <div>
                    <input type="radio" name="pag_torpedo_mkt" value="S" <?php echo($result['pag_torpedo_mkt'] == 'S')? 'checked="checked"':"" ?>> Sim
                    <input type="radio" name="pag_torpedo_mkt" value="N" <?php echo($result['pag_torpedo_mkt'] == 'N')? 'checked="checked"':"" ?>> Não   
                </div>    
                <div style="float: left; width: 200px">
                    <label>Boletos | Crédiário | Carnê </label>
                </div>
                <div>
                    <input type="radio" name="pag_boletos_cred_carne" value="S" <?php echo($result['pag_boletos_cred_carne'] == 'S')? 'checked="checked"':"" ?>> Sim
                    <input type="radio" name="pag_boletos_cred_carne" value="N" <?php echo($result['pag_boletos_cred_carne'] == 'N')? 'checked="checked"':"" ?>> Não    
                </div>    
                <div style="float: left; width: 200px">
                    <label>Parcelamento de Dívidas </label>
                </div>
                <div>
                    <input type="radio" name="pag_parcel_divida" value="S" <?php echo($result['pag_parcel_divida'] == 'S')? 'checked="checked"':"" ?>> Sim
                    <input type="radio" name="pag_parcel_divida" value="N" <?php echo($result['pag_parcel_divida'] == 'N')? 'checked="checked"':"" ?>> Não  
                </div>     
                <div style="float: left; width: 200px">
                    <label>Localização de Devedores </label>
                </div>
                <div>
                    <input type="radio" name="pag_local_devedores" value="S" <?php echo($result['pag_local_devedores'] == 'S')? 'checked="checked"':"" ?>> Sim
                    <input type="radio" name="pag_local_devedores" value="N" <?php echo($result['pag_local_devedores'] == 'N')? 'checked="checked"':"" ?>> Não   
                </div>   
                <div style="float: left; width: 200px">
                    <label>Arquivos e Pastas On-line Web </label>
                </div>
                <div>
                    <input type="radio" name="pag_arquivos_pasta" value="S" <?php echo($result['pag_arquivos_pasta'] == 'S')? 'checked="checked"':"" ?>> Sim
                    <input type="radio" name="pag_arquivos_pasta" value="N" <?php echo($result['pag_arquivos_pasta'] == 'N')? 'checked="checked"':"" ?>> Não  
                </div>   
                <div style="float: left; width: 200px">
                    <label>Consulta de Crédito </label>
                </div>
                <div>
                    <input type="radio" name="pag_consulta_cred" value="S" <?php echo($result['pag_consulta_cred'] == 'S')? 'checked="checked"':"" ?>> Sim
                    <input type="radio" name="pag_consulta_cred" value="N" <?php echo($result['pag_consulta_cred'] == 'N')? 'checked="checked"':"" ?>> Não
                </div>   
                <div style="float: left; width: 200px">
                    <label>Negativação SPC / SERASA </label>
                </div>
                <div>
                    <input type="radio" name="pag_negativacao_spc" value="S" <?php echo($result['pag_negativacao_spc'] == 'S')? 'checked="checked"':"" ?>> Sim
                    <input type="radio" name="pag_negativacao_spc" value="N" <?php echo($result['pag_negativacao_spc'] == 'N')? 'checked="checked"':"" ?>> Não
                </div>     
                <div style="float: left; width: 200px">
                    <label>Site na Internet </label>
                </div>
                <div>
                    <input type="radio" name="pag_site" value="S" <?php echo($result['pag_site'] == 'S')? 'checked="checked"':"" ?>> Sim
                    <input type="radio" name="pag_site" value="N" <?php echo($result['pag_site'] == 'N')? 'checked="checked"':"" ?>> Não
                </div> 
                <div style="float: left; width: 200px">
                    <label>Loja Virtual (E-Commerce) </label>
                </div>
                <div>
                    <input type="radio" name="pag_loja" value="S" <?php echo($result['pag_loja'] == 'S')? 'checked="checked"':"" ?>> Sim
                    <input type="radio" name="pag_loja" value="N" <?php echo($result['pag_loja'] == 'N')? 'checked="checked"':"" ?>> Não
                </div>   
                <div style="float: left; width: 200px">
                    <label>Acesso Web ou Off-Line </label>
                </div>
                <div>
                    <input type="radio" name="pag_web_offline" value="S" <?php echo($result['pag_web_offline'] == 'S')? 'checked="checked"':"" ?>> Sim
                    <input type="radio" name="pag_web_offline" value="N" <?php echo($result['pag_web_offline'] == 'N')? 'checked="checked"':"" ?>> Não
                </div>  
                <div style="float: left; width: 200px">
                    <label>Integração Mercado Livre</label>
                </div>
                <div>
                    <input type="radio" name="pag_mercado_livre" value="S" <?php echo($result['pag_mercado_livre'] == 'S')? 'checked="checked"':"" ?>> Sim
                    <input type="radio" name="pag_mercado_livre" value="N" <?php echo($result['pag_mercado_livre'] == 'N')? 'checked="checked"':"" ?>> Não
                </div>  

            </td>
        </tr> 
        <tr>
            <td colspan="3" align="center">
                <input name="btnEdit" type="submit" value="Gravar">
                <input name="button" type="button" class="voltar" value="Voltar">
            </td>
        </tr>        
    </table>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" type="text/javascript"></script>

<script>
    $("#valor_sistema").maskMoney({thousands:".", decimal:",", symbol:"R$", showSymbol:true, symbolStay:true});

    $('.voltar').click(function () {
       window.location.href = "painel.php?pagina1=clientes/cadastrar_concorrentes.php"         
    });    
</script>