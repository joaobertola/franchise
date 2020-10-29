<?php
require "connect/sessao.php";

$contano = $_REQUEST['contano'];
$contmes = $_REQUEST['contmes'];
$opcao = $_REQUEST['opcao'];
$canceladoprecancelado = $_REQUEST['canceladoprecancelado'];
$ordenacao = $_REQUEST['ordenacao'];
$franqueado = $_REQUEST['franqueado'];

if ($opcao == 0)
    $sitcli = ' AND b.sitlog = 0 and  a.sitcli = 0 ';
elseif ($opcao == 1)
    $sitcli = ' AND b.sitlog = 1 and  a.sitcli = 0 ';
elseif ($opcao == 2)
    $sitcli = ' AND a.sitcli = 2 ';

if ($franqueado == 'todos')
    $frq = "";
else
    $frq = "and a.id_franquia=$franqueado";

if ($contano === "todos") {
    $periodo = "%";
} else {
    if ($contmes == "todos")
        $periodo = "$contano-%";
    else
        $periodo = "$contano-$contmes%";
}

$comando = "select a.codloja,MID(b.logon,1,LOCATE('S', b.logon) - 1) as logon, a.nomefantasia, IF(a.id_consultor = 0 || a.id_consultor = '' || a.id_consultor IS NULL,a.vendedor, mid(d.nome,1,10)) as vendedor,
                a.valor_comissao_vendedor, a.vr_pgto_fixo, date_format(a.dt_pgto_fixo, '%d/%m/%Y') AS dt_pgto_fixo,
                date_format(a.dt_pgto_comissao_vendedor, '%d/%m/%Y') AS dt_pgto_comissao_vendedor,
                date_format(a.dt_cad, '%d/%m/%Y') AS data, c.fantasia,
                date_format(a.dt_pgto_adesao, '%d/%m/%Y') AS dt_pgto_adesao,
                a.vr_pgto_adesao
            from cadastro a
            inner join logon b on a.codloja = b.codloja
            inner join franquia c on a.id_franquia = c.id
            left outer join consultores_assistente d on a.id_consultor = d.id
            where a.dt_cad like '$periodo' $sitcli $frq
            group by a.codloja order by $ordenacao";


if ($opcao == 2)
    $comando = "select a.codloja,MID(b.logon,1,LOCATE('S', b.logon) - 1) as logon, a.nomefantasia, IF(a.id_consultor = 0 || a.id_consultor = '' || a.id_consultor IS NULL,a.vendedor, ca.nome) AS vendedor,
                    a.valor_comissao_vendedor, a.vr_pgto_fixo, date_format(a.dt_pgto_fixo, '%d/%m/%Y') AS dt_pgto_fixo,
                    date_format(a.dt_pgto_comissao_vendedor, '%d/%m/%Y') AS dt_pgto_comissao_vendedor,
                    date_format(a.dt_cad, '%d/%m/%Y') AS data, c.fantasia,
                    date_format(d.data_documento, '%d/%m/%Y') AS datacanc,
                    date_format(a.dt_pgto_adesao, '%d/%m/%Y') AS dt_pgto_adesao,
                    a.vr_pgto_adesao
                from cadastro a
                inner join logon b on a.codloja = b.codloja
                inner join franquia c on a.id_franquia = c.id
                inner join pedidos_cancelamento d on a.codloja = d.codloja
                left join consultores_assistente ca
                on ca.id = a.id_consultor
                where d.data_documento like '$periodo' $sitcli $frq
                group by a.codloja order by $ordenacao";

if ($opcao == 4)
    $comando = "select count(*), a.codloja, b.razaosoc as fantasia, mid(a.nomefantasia,1,25) nomefantasia, a.uf, a.cidade,
                    a.bairro, a.end, a.cep, a.fone, a.fax, a.email, a.tx_mens, a.boleto, a.carteira, a.diapagto,
                    date_format(a.dt_cad, '%d/%m/%Y') as data, a.sitcli, d.descsit,MID(e.logon,1,LOCATE('S', e.logon) - 1) as logon,
                    date_format(a.dt_pgto_fixo, '%d/%m/%Y') AS dt_pgto_fixo,
                    a.vr_pgto_fixo,	date_format(a.dt_pgto_comissao_vendedor, '%d/%m/%Y') AS dt_pgto_comissao_vendedor,
                    a.valor_comissao_vendedor, IF(a.id_consultor = 0 || a.id_consultor = '' || a.id_consultor IS NULL,a.vendedor, ca.nome) AS vendedor,
                    date_format(a.dt_pgto_adesao, '%d/%m/%Y') AS dt_pgto_adesao,
                    a.vr_pgto_adesao
                from cs2.cadastro a
                inner join cs2.franquia b on a.id_franquia=b.id
                left join cs2.situacao d on a.sitcli=d.codsit
                left Join cs2.logon e On a.codloja=e.codloja
                left outer join cs2.pedidos_cancelamento f on a.codloja=f.codloja
                left join consultores_assistente ca
                on ca.id = a.id_consultor
                where sitcli<2 $frq and pendencia_contratual = 1  and f.data_documento is NULL
                group by a.codloja order by a.dt_cad";

$res = mysql_query($comando, $con);
$linhas = @mysql_num_rows($res);
//$linhas1 = $linhas + 3;

//echo "<pre>".$comando;

if ($linhas == "0") {
    echo "<table  class=\"table table-striped table-responsive col65\" align=\"center\">
<thead>
                    <tr>
                    <th><h4 class=\"text-center\">Nenhum cliente cadastrado neste periodo!</h4></th></tr></thead></table>";
} else {
    echo "<table  class=\"table table-striped table-responsive col100\" align=\"center\">
<thead>
                    <tr>
                            <th colspan='16'><h4 class=\"text-center\">EXTRATO DE CONTRATOS DO MES $contmes/$contano</h4></th>
                    </tr></thead>";
    ?>
   <thead>
    <tr height="20">
        <th class="text-center">ID <?php //echo $_SESSION['id']?></th>
        <th class="text-center">Codigo</th>
        <th class="text-center">Nome de Fantasia</th>
        <th class="text-center">Dt Afilia&ccedil;&atilde;o</th>
        <th class="text-center">Dt Cancelamento</th>
        <th class="text-center">Franquia</th>
        <th class="text-center">Vendedor</th>
        <th class="text-center">Dt Pgto<br>VVI</th>
        <th class="text-center">Vr Pg<br>VVI</th>
        <th class="text-center">&nbsp;</th>
        <th class="text-center">Dt Pgto<br>Fixo</th>
        <th class="text-center">Vr Pg<br>Fixo</th>
        <th class="text-center">&nbsp;</th>
        <th class="text-center">Dt Pgto<br>Ades&atilde;o</th>
        <th class="text-center">Vr Pg<br>Ades&atilde;o</th>
        <th class="text-center">&nbsp;</th>
    </tr>
</thead
<tbody>
    <?php
    for ($a = 1; $a <= $linhas; $a++) {
        $matriz = mysql_fetch_array($res);
        $id = $matriz['codloja'];
        $logon = $matriz['logon'];
        $nomef = $matriz['nomefantasia'];
        $data = $matriz['data'];
        $vendedor = $matriz['vendedor'];
        $datacanc = $matriz['datacanc'];
        $fantasia = $matriz['fantasia'];
        $dt_pgto_comissao_vendedor = $matriz['dt_pgto_comissao_vendedor'];
        $valor_comissao_vendedor = $matriz['valor_comissao_vendedor'];

        $dt_pgto_fixo = $matriz['dt_pgto_fixo'];
        $vr_pgto_fixo = $matriz['vr_pgto_fixo'];
        
        $dt_pgto_adesao = $matriz['dt_pgto_adesao'];
        $vr_pgto_adesao = $matriz['vr_pgto_adesao'];

        if (empty($datacanc))
            $datacanc = "-";
        $string = $nomef;
        $limite = 20;
        $sizeName = strlen($string);
        //
        echo "<tr height=\"22\"";
        if (($a % 2) == 0) {
            echo "bgcolor=\"#E5E5E5\">";
        } else {
            echo ">";
        }
        echo " 	<td align=\"center\">$id</td>
                    <td align=\"center\">$logon</td>
            <td>&nbsp;";
        for ($num = 0; $num < $limite; $num++) {
            print($string[$num]);
        }
        if ($sizeName > $limite) {
            echo"...";
        }
        echo "</a></td>
            <td class=\"text-center\">$data</td>
                    <td class=\"text-center\">$datacanc</td>
                    <td class=\"text-center\">$fantasia</td>
                    <td class=\"text-center\">$vendedor</td>
                    <td class=\"text-center\">$dt_pgto_comissao_vendedor</td>
                    <td class=\"text-center\">";
        if ($valor_comissao_vendedor > 0) {
            echo number_format($valor_comissao_vendedor, 2, ',', '.');
        }
        echo "</td><td align='center'>";

        if ($valor_comissao_vendedor == 0) {
            ?>
            	
            	<?php if ($_SESSION['id'] != 4){ ?>
	           		 <a href="painel.php?pagina1=Franquias/inserir_baixa.php&codloja=<?= $id ?>&contano=<?= $_REQUEST['contano'] ?>&contmes=<?= $_REQUEST['contmes'] ?>&opcao=<?= $_REQUEST['opcao'] ?>&ordenacao=<?= $_REQUEST['ordenacao'] ?>&canceladoprecancelado=<?= $_REQUEST['canceladoprecancelado'] ?>&franqueado=<?= $_REQUEST['franqueado'] ?>&destino_pgto=VVI&logon=<?= $logon ?>&empresa=<?= $nomef ?>">Bx VVI</a>
	            <?php }else{ ?>
	            	<a href="#">Bx VVI</a>
	            <?php }?>	
            		
           
            <?php } else { ?>
            <font color="#00CC00">Ok Pg</font>
        <?php
        }
        echo "</td>";

        echo "<td class=\"text-center\">$dt_pgto_fixo</td>";
        echo "<td class=\"text-center\">";
        if ($vr_pgto_fixo != '0') {
            echo number_format($vr_pgto_fixo, 2, ',', '.');
        }
        echo "</td><td class='text-center'>";
        if ($vr_pgto_fixo == '0') {
            ?>
	            <?php if ($_SESSION['id'] != 4){ ?>
	           		<a href="painel.php?pagina1=Franquias/inserir_baixa.php&codloja=<?= $id ?>&contano=<?= $_REQUEST['contano'] ?>&contmes=<?= $_REQUEST['contmes'] ?>&opcao=<?= $_REQUEST['opcao'] ?>&ordenacao=<?= $_REQUEST['ordenacao'] ?>&canceladoprecancelado=<?= $_REQUEST['canceladoprecancelado'] ?>&franqueado=<?= $_REQUEST['franqueado'] ?>&destino_pgto=FIX&logon=<?= $logon ?>&empresa=<?= $nomef ?>">Bx FX</a>
	            <?php }else{ ?>
	            	<a href="#">Bx FX</a>
	            <?php }?>
            <?php } else { ?>
            <font color="#00CC00">Ok Pg</font>
        <?php
        }
        echo "</td>";
        
        echo "<td class=\"text-center\">$dt_pgto_adesao</td>";
        echo "<td class=\"text-center\">";
        if ($vr_pgto_adesao != '0') {
            echo number_format($vr_pgto_adesao, 2, ',', '.');
        }
        echo "</td><td class='text-center'>";
        if ($vr_pgto_adesao == '0') {
            ?>
            	<?php if ($_SESSION['id'] != 4){ ?>
            		<a href="painel.php?pagina1=Franquias/inserir_baixa.php&codloja=<?= $id ?>&contano=<?= $_REQUEST['contano'] ?>&contmes=<?= $_REQUEST['contmes'] ?>&opcao=<?= $_REQUEST['opcao'] ?>&ordenacao=<?= $_REQUEST['ordenacao'] ?>&canceladoprecancelado=<?= $_REQUEST['canceladoprecancelado'] ?>&franqueado=<?= $_REQUEST['franqueado'] ?>&destino_pgto=ADE&logon=<?= $logon ?>&empresa=<?= $nomef ?>">Bx Imp</a>
            	<?php } else { ?>	
            		<a href="#">Bx Imp</a>
            	<?php 	}	?>
            <?php } else { ?>
            	<?php if ($_SESSION['id'] != 4){ ?>
		            <a  href="#" onclick="confirma(<?= $id ?>)">
		            <font color="#00CC00">Ok Pg</font></a>
				<?php }	else{ ?>
		            <a  href="#" >
		            <font color="#00CC00">Ok Pg</font></a>
				<?php }?> 
        <?php
        }
        echo "</td>";
        
        echo "</tr>";
    }
    $a = $a - 1;
    echo "
                <tr height=\"20\">
                    <td colspan=\"14\" class=\"text-right\" align=\"center\">Total de Clientes do Periodo $contmes/$contano &nbsp;</td>
                    <td class=\"text-right\"><b> $a</b> Clientes</td>
                    <td class=\"text-right\">&nbsp;</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                <td colspan='16'>
                <input type=\"button\" onClick=\"javascript: history.back();\" value=\"Voltar\" class=\"botao3d\"  />
</td>
</tr>
</tfoot>
            </table>";
}
$res = mysql_close($con);

if ($_REQUEST['ok_baixa'] == 1) {
//	echo "<script>alert('Baixa realizada com sucesso!');</script>";
}
?>
    <script type="text/javascript">
        function confirma(id) {
            var r = confirm("Você tem certeza que deseja remover a baixa?");
            // console.log(r);
            if (r == true) {
                location.href = 'painel.php?pagina1=Franquias/retirarBaixa.php&codloja=' + id;
            }

        }

    </script>