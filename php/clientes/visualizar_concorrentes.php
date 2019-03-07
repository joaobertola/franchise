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
        $modificado = 'R$ ' . number_format((float) $formataReal, 2, ',', '.');
        return $modificado;
    } else {
        if (floatval($formataReal) == 0 || intval($formataReal) == 0) {
            return 'R$ 0,00';
        }
    }
}
?>

<form name="form2" method="post">
    <table border="0" align="center" width="700">
        <tr>
            <td colspan="3" class="titulo">Visualização Concorrente</td>
        </tr>
        <tr>
            <td class="subtitulodireita">Nome Sistema: </td>
            <td colspan="2" class="subtitulopequeno">
                <input type="text" name="nome" id="nome_sistema" value="<?php echo $result['nome'] ?>" readonly>
            </td>
        </tr>

        <tr>
            <td width="200" class="subtitulodireita">Valor do Sistema: </td>
            <td colspan="2" class="subtitulopequeno">
                <input type="text" name="valor_sistema" id="valor_sistema" value="<?php echo formataReal($result['valor_sistema']) ?>" readonly>
            </td>
        </tr>
        <tr>
            <td width="200" class="subtitulodireita">Forma Cobrança: </td>
            <td colspan="2" class="subtitulopequeno">
				<input type="text" name="" value="<?php echo $forma_cobranca ?>" readonly> 
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
                	<input type="radio" name="pag_controle_estoque" value="N" checked <?php ($result['pag_controle_estoque'] == 'N')? 'checked="checked"':"" ?>> Não
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
                <input name="button" type="button" onclick="javascript: history.back();" value="       Voltar       ">
            </td>
        </tr>        
    </table>
</form>