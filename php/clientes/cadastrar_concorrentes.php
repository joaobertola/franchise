<?php
require "connect/sessao.php";
require "connect/sessao_r.php";

$con = @mysql_connect("10.2.2.3", "csinform", "inform4416#scf");

$sql = "SELECT * FROM cs2.concorrente order by nome";

$query = mysql_query( $sql, $con) or die(mysql_error());
$result = array(); 

while($row = mysql_fetch_array($query)) {
   $result[] = $row;
}

if (isset($_POST['btnSalvar'])) {
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

        $sql = "INSERT INTO cs2.concorrente(nome, valor_sistema, forma_cobranca, pag_cadastro_clientes, pag_controle_estoque, pag_nota_fiscal, pag_rel_financeiro, pag_contas_pagar, pag_listagem_cliente, pag_email_mkt, pag_torpedo_mkt, pag_boletos_cred_carne, pag_parcel_divida, pag_local_devedores, pag_arquivos_pasta, pag_consulta_cred, pag_negativacao_spc, pag_site, pag_loja, pag_web_offline, pag_mercado_livre) VALUES('$nome','$valor_sistema','$forma_cobranca', '$pag_cadastro_clientes','$pag_controle_estoque', '$pag_nota_fiscal', '$pag_rel_financeiro', '$pag_contas_pagar', '$pag_listagem_cliente', '$pag_email_mkt', '$pag_torpedo_mkt', '$pag_boletos_cred_carne', '$pag_parcel_divida', '$pag_local_devedores', '$pag_arquivos_pasta', '$pag_consulta_cred', '$pag_negativacao_spc', '$pag_site', '$pag_loja', '$pag_web_offline', '$pag_mercado_livre')";

        $qry_insert = mysql_query( $sql, $con) or die(mysql_error());

        echo "<meta HTTP-EQUIV='refresh' CONTENT='0'>";
}

if (isset($_GET['id_dell'])) {
    $sql = "DELETE FROM cs2.concorrente WHERE id = ".$_GET['id_dell']."";
    mysql_query( $sql, $con) or die(mysql_error());

    echo "<meta HTTP-EQUIV='refresh' CONTENT='0'>";
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<form name="form2" method="post">
    <table border="0" align="center" width="700">
        <tr>
            <td colspan="3" class="titulo">Cadastrar Concorrentes</td>
        </tr>
        <tr>
            <td class="subtitulodireita">Nome Sistema: </td>
            <td colspan="2" class="subtitulopequeno">
                <input type="text" name="nome" id="nome_sistema" onkeydown="upperCaseF(this)">
            </td>
        </tr>

        <tr>
            <td width="200" class="subtitulodireita">Valor do Sistema: </td>
            <td colspan="2" class="subtitulopequeno">
                <input type="text" name="valor_sistema" id="valor_sistema" value="">
            </td>
        </tr>
        <tr>
            <td width="200" class="subtitulodireita">Forma Cobrança: </td>
            <td colspan="2" class="subtitulopequeno">
                <select name="forma_cobranca">
                    <option value="M">Mensal</option>
                    <option value="T">Trimestral</option>
                    <option value="S">Semestral</option>
                    <option value="A">Anual</option>
                    <option value="U"> Tarifa Unica</option>
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
                    <input type="radio" name="pag_cadastro_clientes" value="S"> Sim
                    <input type="radio" name="pag_cadastro_clientes" value="N" checked> Não
                </div>
                <div style="float: left; width: 200px">
                    <label style="width: 100px;">Controle Estoque: </label>
                </div>
                <div >
                    <input type="radio" name="pag_controle_estoque" value="S"> Sim
                    <input type="radio" name="pag_controle_estoque" value="N" checked> Não
                </div>          
                <div style="float: left; width: 200px">
                    <label>Nota Fiscal (NFe, NFc-e, NFs-e): </label>
                </div>
                <div >
                    <input type="radio" name="pag_nota_fiscal" value="S"> Sim
                    <input type="radio" name="pag_nota_fiscal" value="N" checked> Não
                </div>    
                <div style="float: left; width: 200px">
                    <label>Relatórios Financeiros: </label>
                </div>
                <div >
                    <input type="radio" name="pag_rel_financeiro" value="S"> Sim
                    <input type="radio" name="pag_rel_financeiro" value="N" checked> Não
                </div>   
                <div style="float: left; width: 200px">
                    <label>Contas a Pagar e Receber: </label>
                </div>
                <div >
                    <input type="radio" name="pag_contas_pagar" value="S"> Sim
                    <input type="radio" name="pag_contas_pagar" value="N" checked> Não
                </div>    
                <div style="float: left; width: 200px">
                    <label>Listagem de Novos Clientes: </label>
                </div>
                <div>
                    <input type="radio" name="pag_listagem_cliente" value="S"> Sim
                    <input type="radio" name="pag_listagem_cliente" value="N" checked> Não                    
                </div>    
                <div style="float: left; width: 200px">
                    <label>E-mail Marketing Massificado: </label>
                </div>
                <div>
                    <input type="radio" name="pag_email_mkt" value="S"> Sim
                    <input type="radio" name="pag_email_mkt" value="N" checked> Não                
                </div> 
                <div style="float: left; width: 200px">
                    <label>Torpedo Marketing Massificado: </label>
                </div>
                <div>
                    <input type="radio" name="pag_torpedo_mkt" value="S"> Sim
                    <input type="radio" name="pag_torpedo_mkt" value="N" checked> Não             
                </div>    
                <div style="float: left; width: 200px">
                    <label>Boletos | Crédiário | Carnê </label>
                </div>
                <div>
                    <input type="radio" name="pag_boletos_cred_carne" value="S"> Sim
                    <input type="radio" name="pag_boletos_cred_carne" value="N" checked> Não          
                </div>    
                <div style="float: left; width: 200px">
                    <label>Parcelamento de Dívidas </label>
                </div>
                <div>
                    <input type="radio" name="pag_parcel_divida" value="S"> Sim
                    <input type="radio" name="pag_parcel_divida" value="N" checked> Não        
                </div>     
                <div style="float: left; width: 200px">
                    <label>Localização de Devedores </label>
                </div>
                <div>
                    <input type="radio" name="pag_local_devedores" value="S"> Sim
                    <input type="radio" name="pag_local_devedores" value="N" checked> Não       
                </div>   
                <div style="float: left; width: 200px">
                    <label>Arquivos e Pastas On-line Web </label>
                </div>
                <div>
                    <input type="radio" name="pag_arquivos_pasta" value="S"> Sim
                    <input type="radio" name="pag_arquivos_pasta" value="N" checked> Não   
                </div>   
                <div style="float: left; width: 200px">
                    <label>Consulta de Crédito </label>
                </div>
                <div>
                    <input type="radio" name="pag_consulta_cred" value="S"> Sim
                    <input type="radio" name="pag_consulta_cred" value="N" checked> Não
                </div>   
                <div style="float: left; width: 200px">
                    <label>Negativação SPC / SERASA </label>
                </div>
                <div>
                    <input type="radio" name="pag_negativacao_spc" value="S"> Sim
                    <input type="radio" name="pag_negativacao_spc" value="N" checked> Não
                </div>     
                <div style="float: left; width: 200px">
                    <label>Site na Internet </label>
                </div>
                <div>
                    <input type="radio" name="pag_site" value="S"> Sim
                    <input type="radio" name="pag_site" value="N" checked> Não
                </div> 
                <div style="float: left; width: 200px">
                    <label>Loja Virtual (E-Commerce) </label>
                </div>
                <div>
                    <input type="radio" name="pag_loja" value="S"> Sim
                    <input type="radio" name="pag_loja" value="N" checked> Não
                </div>   
                <div style="float: left; width: 200px">
                    <label>Acesso Web ou Off-Line </label>
                </div>
                <div>
                    <input type="radio" name="pag_web_offline" value="S"> Sim
                    <input type="radio" name="pag_web_offline" value="N" checked> Não
                </div>  
                <div style="float: left; width: 200px">
                    <label>Integração Mercado Livre</label>
                </div>
                <div>
                    <input type="radio" name="pag_mercado_livre" value="S"> Sim
                    <input type="radio" name="pag_mercado_livre" value="N" checked> Não
                </div>                                                                    
            </td>
        </tr> 
        <tr>
            <td colspan="3" align="center">
                <input type="submit"  id="btnSalvar" name="btnSalvar" value="Gravar">
            </td>
        </tr>
    </table>
</form>

<table border='0' width='850' align='center' cellpadding='0' cellspacing='1' style='border:1px dashed #E8E8E8; background-color:#FFFFFF'>
    <tr>
        <td colspan="7" align="center" class="titulo">Listagem de Concorrentes</td>
    </tr>
    <tr bgcolor="#FF9900">
        <td><b>Nome</b></td>
        <td><b>Valor do Sistema</b></td>
        <td><b>Forma Cobrança</b></td>
        <td><b>Visualizar</b></td>
        <td><b>Editar</b></td>
        <td><b>Excluir</b></td>
    </tr>
    <?php foreach ($result as $value): ?>
        <?php 
            switch ($value['forma_cobranca']) {
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
        ?>
        <tr bgcolor="<?=$cor?>">
            <td><?php echo $value['nome'] ?></td>
            <td><?php echo formataReal($value['valor_sistema']) ?></td>
            <td><?php echo $forma_cobranca ?></td>
            <td><a href="painel.php?pagina1=clientes/visualizar_concorrentes.php&id=<?php echo $value['id'] ?>"><li class="fa fa-search"></li></a></td>
            <td><a href="painel.php?pagina1=clientes/editar_concorrentes.php&id=<?php echo $value['id'] ?>"><li class="fa fa-pencil-square-o"></li></a></td>
            <td><a  class="excluir" id='<?php echo $value['id'] ?>'><i class="fa fa-minus" style="font-size:24px; padding-left: 35%;"></i></td>
        </tr>
    <?php endforeach ?>
</table>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" type="text/javascript"></script>

<script>
$('.excluir').click(function () {
    var id = 0;
    id = $(this).attr('id');

    $.ajax({
        url: '../php/clientes/excluir_concorrentes.php',
        data: {
            id: id
        },
        type: 'POST',
        dataType: 'text',
        success: function (data) {
            location.reload();
        }
    });             
});

function upperCaseF(a){
    setTimeout(function(){
        a.value = a.value.toUpperCase();
    }, 1);
}

$("#valor_sistema").maskMoney({thousands:".", decimal:",", symbol:"R$", showSymbol:true, symbolStay:true});

</script>
