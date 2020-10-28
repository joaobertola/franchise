<?php
require "connect/sessao.php";

$codigo = $_REQUEST['codigo'];

if (($tipo == "a") || ($tipo == "c")) {
$resulta = mysql_query("select a.logon, b.id_franquia from logon a
						inner join cadastro b on a.codloja=b.codloja
						where CAST(MID(a.logon,1,6) AS UNSIGNED)='$codigo'", $con);
} else {
$resulta = mysql_query("select a.logon, b.id_franquia from logon a
						inner join cadastro b on a.codloja=b.codloja
						where CAST(MID(a.logon,1,6) AS UNSIGNED)='$codigo' and id_franquia='$id_franquia'", $con);
}
$linha = mysql_num_rows($resulta);
if ($linha == 0)
{
	echo "<script>alert(\"Cliente nao existe ou nao pertence a sua franquia!\"); javascript: history.back();</script>";
}
else 
{
$comando = "select a.codloja, a.razaosoc, a.nomefantasia, date_format(a.dt_cad, '%d/%m/%Y') as data, a.sitcli,
			d.descsit, a.tx_mens from cadastro a
			inner join situacao d on a.sitcli=d.codsit
			inner join logon e on a.codloja=e.codloja
			where CAST(MID(e.logon,1,6) AS UNSIGNED)='$codigo'";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res);
$codloja = $matriz['codloja'];

$command = "
			SELECT a.id, a.codcons, b.nome, a.valorcons, b.vr_custo, c.qtd 
			FROM valconscli a 
			INNER JOIN valcons b on a.codcons=b.codcons
			LEFT JOIN bonificadas c ON a.codloja = c.codloja AND a.codcons = c.tpcons
			WHERE a.codloja = '$codloja' ORDER BY a.id ";
$result = mysql_query ($command, $con);
$linhas = mysql_num_rows ($result);
$linhas1 = $linhas + 3;
?>
<script type="text/javascript" src="../../js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="../../js/jquery.maskedinput-1.1.1.js"></script>
<script type="text/javascript" src="../../js/jquery.meio.mask.js"></script>

<script language="JavaScript1.2">


<!--
//(function($){
//  $(function(){
//	$('input:text').setMask();
//  }
//);
//})(jQuery);

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

function alterar(){
	frm = document.form;
	frm.action = "Franquias/altera_tabela_preco.php";
	frm.submit();
}

function voltar(){
	frm = document.form;
	frm.action = "painel.php?pagina1=Franquias/b_tabelapreco.php";
	frm.submit();
}
//-->

window.onload = function(){ document.form.tx_mens.focus();  }
</script>
<body>
<form name="form" method="post" action="#">
<input type="hidden" name="codloja" value="<?=$codloja?>">
<input type="hidden" name="codigo" value="<?=$codigo?>">

<table border="0" align="center" width="70%" cellpadding="0" cellspacing="1">
  <tr>
    <td colspan="2" class="titulo" align="center">TABELA DE PRE&Ccedil;OS DO CLIENTE: <font color="red"><?php echo $matriz['nomefantasia']; ?></font></td>
  </tr>
  <tr>
    <td class="subtitulodireita">ID</td>
    <td class="subtitulopequeno"><?php echo $matriz['codloja']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo de Cliente </td>
    <td class="campojustificado"><?php echo $codigo; ?></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Raz&atilde;o Social</td>
    <td class="subtitulopequeno"><?php echo $matriz['razaosoc']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Cliente desde</td>
    <td class="subtitulopequeno"><?php echo $matriz['data']; ?></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Mensalidade</td>
    <td valign="top" class="subtitulopequeno">R$&nbsp;<input type="text" name="tx_mens" alt="decimal" value="<?=$matriz['tx_mens']?>" style="width:10%" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" ></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Tabela de Pre&ccedil;os</td>
    <td>
		<table width="100%" border="0" cellpadding="1" cellspacing="1">
	 		<tr>
				<td colspan="7" height="1" bgcolor="#999999"></td>
			</tr>
	 		<tr>
				<td rowspan="<?php echo $linhas1; ?>" width="1" bgcolor="#999999"></td>
			</tr>
            
			<tr height="22">
				<td align="center" class="campoesquerda" width="15%">&nbsp;C&oacute;digo</td>
				<td align="center" class="campoesquerda">&nbsp;Produto</td>
				<td align="center" class="campoesquerda" width="15%">&nbsp;Venda</td>
				<td align="center" class="campoesquerda" width="15%">&nbsp;Gratuidade</td>
				<td rowspan="<?php echo $linhas1; ?>" width="1" bgcolor="#999999"></td>
			</tr>
			<?php 
			$a_cor = array('#BECFE5', '#B0C4DE');
			for ($a=1; $a<=$linhas; $a++) {
				$matrix = mysql_fetch_array($result);
				$id = $matrix['id'];
				$codcons = $matrix['codcons'];
				$produto = $matrix['nome'];
				$venda = $matrix['valorcons'];
				$custo = $matrix['vr_custo'];
				$gratuidade = $matrix['qtd'];
			?>
            <tr style="font-family:Arial, Helvetica, sans-serif; font-size:11px">
                <td bgcolor="<?=$a_cor[$a % 2]?>"><?=$codcons?><input type="hidden" name="codcons[]" value="<?=$codcons?>" style="width:70%"></td>
                <td bgcolor="<?=$a_cor[$a % 2]?>"><?=$produto?></td>
                <td bgcolor="<?=$a_cor[$a % 2]?>">
                <input type="hidden" name="id[]" value="<?=$id?>" style="width:70%">
                <input type="text" name="venda[]" alt="decimal" value="<?=$venda?>" class="venda" style="width:65%" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'"">
                </td>
                <td bgcolor="<?=$a_cor[$a % 2]?>">
				<input type="text" name="qtd[]" value="<?=$gratuidade?>" maxlength="3" style="width:65%" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" ></td>
            </tr>
        <?php            
				}
				echo "<tr>
						<td colspan=\"6\" align=\"right\" height=\"1\" bgcolor=\"#666666\"></td>
					</tr>";
			?>			
		</table>	</td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Status</td>
    <td class="formulario" <?php 
        if ($matriz['sitcli'] == 0) {
            echo "bgcolor=\"#33CC66\"";
        } else {
            echo "bgcolor=\"#FF0000\"";
        } ?> >
    <font color="#FFFFFF"><?php echo $matriz['descsit']; ?></font></td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp; </td>
  </tr>
  <?php
  
    $sql_h = "SELECT date_format(data,'%d/%m/%Y %H:%m:%i') as data,alteracoes FROM cs2.cadastro_alteracoes WHERE codloja = ".$codloja;
    $qry_h = mysql_query($sql_h,$con);
    if ( $qry_h ){
        echo "<tr>
                 <td colspan='2' class='titulo'>Histórico de Alterações</td>
              </tr>";
        while ( $reg_h = mysql_fetch_array($qry_h) ){
            $registro++;
            $dat = $reg_h['data'];
            $alt = $reg_h['alteracoes'];
            echo "<tr ";
            if (($registro % 2) == 0) {
                echo "bgcolor='#E5E5E5'>";
            } else {
                echo ">";
            }
            echo "<td>$dat</td>
                  <td>$alt</td>
                </tr>";   
      }
      echo "<tr>
               <td colspan='2' class='titulo'>&nbsp; </td>
            </tr>";
  }
  ?>
  <tr class="nzoprint">
    <td align="center" colspan="2">
        <input type="button" name="Imprimir" value="Imprimir" onClick="window.print()" style="cursor:pointer;">
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input name="button" type="button" onClick="voltar()" value="Voltar" style="cursor:pointer;" />
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?php if( ($_SESSION['id'] == 163) or ($_SESSION['id'] == 46) or ($_SESSION['id'] == 4) ) { ?>
        <input name="button" type="button" onClick="alterar()" value="Alterar" style="cursor:pointer;" />
        <?php } ?>
    </td>
</tr>

  <tr align="right" class="noprint"><td colspan="2">&nbsp;</td></tr>
  
</table>
<?php $res = mysql_close ($con);
} ?>
</form>
</body>

<?php if($_REQUEST['msg'] == 1) { ?>
	<script language="javascript">alert('Alterado com sucesso ! ');</script>
<?php } ?>