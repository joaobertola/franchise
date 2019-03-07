<?php
require "connect/sessao.php";
include "ocorrencias/config.php";
$codloja = $_POST['codloja'];
if(!isset($codloja) || empty($codloja)){
  $codloja = $_REQUEST['codloja'];
}
$comando = "select razaosoc from cadastro where codloja='$codloja'";
$conex = mysql_query($comando, $con);;
$matriz = mysql_fetch_array($conex);
$hoje = date('d/m/Y H:i');
?>
<script language="javascript">

//fun��o para validar clientes no cadastramento
function validaClientes(){
// validar atendente
d = document.postar;
if (d.atendente2.value == ""){
    alert("O campo Atendente deve ser preenchido!");
    d.atendente2.focus();
    return false;
}
// validar ocorrencia
if (d.ocorrencia.value == ""){
    alert("O campo " + d.ocorrencia.name + " deve ser preenchido!");
    d.ocorrencia.focus();
    return false;
}
return true;
}

function novo(){
    form = document.postar;
    form.action = 'painel.php?pagina1=Franquias/b_cadatendente.php';
    form.submit();
} 

function foco(){
    form = document.postar;
    form.ocorrencia.focus();
} 

function Chr(AsciiNum)
{
    return String.fromCharCode(AsciiNum)
}

function mostra_dados(){
	frm = document.postar;
        frm.ocorrencia.value = '';
        var newtext = frm.ocorrencia.value;
	if ( frm.texto1.checked ) 
            newtext = newtext + 'Enviado Boleto e se comprometeu a pagar dia ';
	if ( frm.texto2.checked )
            newtext = newtext + 'Se comprometeu em pagar dia ';
	if ( frm.texto3.checked )
             newtext = newtext + 'Não tem dinheiro para pagar no momento ';
	if ( frm.texto4.checked )
            newtext = newtext + 'Sem contato com o responsável de pagamento, deixei recado ';
	if ( frm.texto5.checked )
            newtext = newtext + 'Telefones não atendem ou Desligados, Whats não responde ';
	if ( frm.texto6.checked )
            newtext = newtext + 'Não vai pagar porque não concorda com alguma situação ou falhas ';
	if ( frm.texto7.checked )
            newtext = newtext + 'Só paga se cancelar o contrato ';
	if ( frm.texto8.checked )
            newtext = newtext + 'Desliga o telefone na cara ';
	if ( frm.texto9.checked )
            newtext = newtext + 'Xinga, desfere palavrões e desliga ';
	if ( frm.texto10.checked )
            newtext = newtext + 'Fechou a Empresa ';
	if ( frm.texto11.checked )
            newtext = newtext + 'Vendeu a Empresa ';
	if ( frm.texto12.checked )
            newtext = newtext + 'Não vai pagar e vai procurar PROCON, JUSTIÇA, PROCESSO, ADVOGADO ';
	if ( frm.texto13.checked )
            newtext = newtext + 'Cobrança de Cheques sem Fundos ';
	if ( frm.texto14.checked )
            newtext = newtext + 'Resgate de Equipamentos(Realizado Acordo) ';
	if ( frm.texto15.checked )
            newtext = newtext + 'OBSERVAÇÃO IMPORTANTE: ';
	
        frm.ocorrencia.value = newtext;
        frm.ocorrencia.focus();

}

</script>
<form name="postar" method="post" onSubmit="return validaClientes();" action="ocorrencias/inserir.php" >
<table width="90%" border="0" align="center">
    <tr>
        <td colspan="2" class="titulo">REGISTRAR UM ATENDIMENTO</td>
    </tr>
    <tr>
        <td class="campoesquerda">&nbsp;</td>
        <td class="campoesquerda">(*) preenchimento obrigat&oacute;rio</td>
    </tr>
    <tr>
        <td class="subtitulodireita">ID do cliente:</td>
        <td class="subtitulopequeno"><?php echo $codloja; ?><input name="codigo" type="hidden" value="<?php echo $codloja; ?>" ></td>
    </tr>
    <tr>
        <td class="subtitulodireita">Nome do Cliente:</td>
        <td class="subtitulopequeno">
            <?php echo $matriz['razaosoc']; ?>
            <input name="codigo2" type="hidden" value="<?php echo $matriz['razaosoc']; ?>" >
        </td>
    </tr>
    <tr>
      <td class="subtitulodireita">Franqu&iacute;a:</td>
      <td class="subtitulopequeno">
          <?php
            $sql = "select * from franquia where id='$id_franquia' order by id";
            $resposta = mysql_query($sql, $con);

            while ($array = mysql_fetch_array($resposta))
            {
                $id		= $array["id"];
                $nome_franquia	= $array["fantasia"];
                echo $nome_franquia;
            }
            ?>
        <input type="hidden" name="franquia" value="<?php echo $id; ?>" ></td>
    </tr>
    <tr>
        <td class="subtitulodireita">Tipo de Ocorr&ecirc;ncia</td>
        <td class="subtitulopequeno">
            <select name="tipo_ocorr" class="boxnormal">
                <option value="1">Cobran&ccedil;a</option>
                <option value="2">Atendimento</option>
                <option value="3">Administrativo</option>
                <option value="4">Comercial</option>
            </select>
        </td>
    </tr>
    <tr>
        <td class="subtitulodireita">Atendente * </td>
        <td class="subtitulopequeno">
            <?php
            if ($tipo == "b") $frq =  " WHERE franquia='$id_franquia'";
            else $frq = " WHERE franquia IN(1, 163, 5) ";
            echo "<select name='atendente2' class='boxnormal' onChange='foco()'>";
            echo "<option value=''>.: Selecione :.</option>";
            $sql = "select id, atendente from cs2.atendentes $frq AND situacao = 'A' order by atendente";
            $resposta = mysql_query($sql, $con);
            while ($array = mysql_fetch_array($resposta)) {
                    $id_atendente   = $array["id"];
                    $nome_atendente = $array["atendente"];
                    echo "<option value=\"$id_atendente\">$nome_atendente</option>\n";
            }
            echo "</select>";
            ?>
            &nbsp;<input type="button" name="Cadastrar Novo Atendente" value="Cadastrar Novo Atendente" onclick="novo()" />
        </td>
    </tr>
    <tr>
        <td class="subtitulodireita">Mensagem</td>
        <td class="campoesquerda">
            <input type="checkbox" name="texto1" onclick="mostra_dados()"/> 
            Enviado Boleto e se comprometeu a pagar dia
            <br>
            <input type="checkbox" name="texto2" onclick="mostra_dados()"/> 
            Se comprometeu em pagar dia
            <br>
            <input type="checkbox" name="texto3" onclick="mostra_dados()"/> 
            Não tem dinheiro para pagar no momento
            <br>
            <input type="checkbox" name="texto4" onclick="mostra_dados()"/> 
            Sem contato com o responsável de pagamento, deixei recado
            <br>
            <input type="checkbox" name="texto5" onclick="mostra_dados()"/> 
            Telefones não atendem ou Desligados, Whats não responde.
            <br>
            <input type="checkbox" name="texto6" onclick="mostra_dados()"/> 
            Não vai pagar porque não concorda com alguma situação ou falhas
            <br>
            <input type="checkbox" name="texto7" onclick="mostra_dados()"/> 
            Só paga se cancelar o contrato
            <br>
            <input type="checkbox" name="texto8" onclick="mostra_dados()"/> 
            Desliga o telefone na cara
            <br>
            <input type="checkbox" name="texto9" onclick="mostra_dados()"/> 
            Xinga, desfere palavrões e desliga
            <br>
            <input type="checkbox" name="texto10" onclick="mostra_dados()"/> 
            Fechou a Empresa
            <br>
            <input type="checkbox" name="texto11" onclick="mostra_dados()"/> 
            Vendeu a Empresa
            <br>
            <input type="checkbox" name="texto12" onclick="mostra_dados()"/> 
            Não vai pagar e vai procurar PROCON, JUSTIÇA, PROCESSO, ADVOGADO
            <br>
            <input type="checkbox" name="texto13" onclick="mostra_dados()"/> 
            Cobrança de Cheques sem Fundos
            <br>
            <input type="checkbox" name="texto14" onclick="mostra_dados()"/> 
            Resgate de Equipamentos(Realizado Acordo)
            <br>
            <input type="checkbox" name="texto15" onclick="mostra_dados()"/> 
            OBSERVAÇÃO IMPORTANTE:
            <br>
	</td>
    </tr>
  
    <tr>
        <td valign="top" class="subtitulodireita">Descri&ccedil;&atilde;o*:</td>
        <td class="subtitulopequeno">
            <textarea name="ocorrencia" id="ocorrencia" wrap=physical style="width:99%" rows="4" onKeyDown="textCounter(this.form.ocorrencia,this.form.remLen,600);" onKeyUp="textCounter(this.form.ocorrencia,this.form.remLen,600);"></textarea>
        </td>
    </tr>
    <tr>
        <td class="subtitulodireita">Data e hora atual:</td>
        <td class="subtitulopequeno"><?php echo $hoje; ?></td>
    </tr>
    <tr>
        <td colspan="2" class="titulo">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <input type="submit" name="Submit" value="  Enviar              ">
            <input type="reset" name="reset" value="              Apagar  ">
        </td>
    </tr>
</table>
</form>