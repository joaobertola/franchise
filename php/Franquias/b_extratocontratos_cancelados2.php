<script language="javascript">

function PopupCenter(url) {
	w = screen.width;
	h = screen.hight;
	var dualScreenLeft = 0;
	var dualScreenTop = 0;
   	var left = ((screen.width / 2) - (w / 2)) + dualScreenLeft;
   	var top = ((screen.height / 2) - (h / 2)) + dualScreenTop;
					
   	var newWindow = window.open(url, '', 'scrollbars=yes, width=' + 600 + ', height=' + 500 + ', top=' + top + ', left=' + left);
    if (window.focus) {
   	    newWindow.focus();
    }
}
		
function ver_cancelado(dti,dtf,vend,frq){
	PopupCenter("Franquias/b_extratocontratos_cancelados3.php?dti="+dti+"&dtf="+dtf+"&vendedor="+vend+"&franquia="+frq);
}
</script>
<?php
require "connect/sessao.php";

$contano     = $_REQUEST['contano'];
$contmes     = $_REQUEST['contmes'];
$franqueado  = $_REQUEST['franqueado'];

if ($franqueado == 'todos') $frq = "";
else $frq = "and a.id_franquia=$franqueado";

if ($contano === "todos") {
    $periodo = "%";
} else {
    if ($contmes == "todos") $periodo = "$contano-%";
    else $periodo = "$contano-$contmes%";
}

$comando = "select count(*) qtd , a.id_consultor
            from cadastro a
            inner join pedidos_cancelamento b on a.codloja = b.codloja
            where b.data_documento like '$periodo' $frq
            group by a.id_consultor
            order by qtd desc";

$sql = "select subdate(now(), interval 183 day) data";
$qr = mysql_query($sql,$con) or die ("ERRO:  Segundo SQL  ==>  $sql");
$campos = mysql_fetch_array($qr);
$data_hoje   = date('Y-m-d');
$data_limite = substr($campos["data"],0,10);

$comando = "SELECT 
                count(*) qtd , a.id_consultor, func.nome as vendedor, func.ativo,
                (SELECT count(*) FROM cs2.cadastro 
                 WHERE dt_cad BETWEEN '$data_limite' AND '$data_hoje' AND id_consultor = a.id_consultor 
                ) AS qtd_afiliados,
                
                (SELECT count(*) FROM cs2.cadastro 
                 WHERE id_consultor = a.id_consultor 
                ) AS qtd_afiliados_geral,
								
                (SELECT count(*) FROM cs2.cadastro aa
                 INNER JOIN pedidos_cancelamento bb ON aa.codloja = bb.codloja
                 INNER JOIN cs2.consultores_assistente ff  ON aa.id_consultor = ff.id
                 INNER JOIN cs2.funcionario funcc ON aa.id_consultor = funcc.id_consultor_assistente
                 WHERE id_consultor = a.id_consultor
                ) AS qtd_cancelados_geral,
                
                (SELECT sum( tit.valor) FROM cs2.cadastro aaa
                 INNER JOIN pedidos_cancelamento bbb ON aaa.codloja = bbb.codloja
                 INNER JOIN cs2.consultores_assistente fff  ON aaa.id_consultor = fff.id
                 INNER JOIN cs2.funcionario funccc ON aaa.id_consultor = funccc.id_consultor_assistente
                 INNER JOIN cs2.titulos tit ON tit.codloja = aaa.codloja
                 WHERE id_consultor = a.id_consultor AND tit.valorpg > 0
                ) as total_pago,
								 
                (SELECT sum( tit.valor) FROM cs2.cadastro aaa
                 INNER JOIN pedidos_cancelamento bbb ON aaa.codloja = bbb.codloja
                 INNER JOIN cs2.consultores_assistente fff  ON aaa.id_consultor = fff.id
                 INNER JOIN cs2.funcionario funccc ON aaa.id_consultor = funccc.id_consultor_assistente
                 INNER JOIN cs2.titulos tit ON tit.codloja = aaa.codloja
                 WHERE id_consultor = a.id_consultor AND tit.valorpg IS NULL
                ) as total_nao_pago,
                
                (
		 SELECT SUM( valor_pgto ) FROM cs2.contacorrente_funcionario
		 WHERE id_func = func.id
		) as total_pago_funcionario
                
            FROM cadastro a
            INNER JOIN pedidos_cancelamento b ON a.codloja = b.codloja
            INNER JOIN cs2.consultores_assistente f  ON a.id_consultor = f.id
            INNER JOIN cs2.funcionario func ON a.id_consultor = func.id_consultor_assistente
            WHERE b.data_documento BETWEEN '$data_limite' AND '$data_hoje' $frq
            GROUP BY a.id_consultor
            ORDER BY qtd DESC";

$res = mysql_query ($comando, $con);
$linhas = mysql_num_rows ($res);
$linhas1 = $linhas+3;

if ($linhas == "0")
	{
	echo "
		<table width='70%' border='0' cellpadding='0' cellspacing='0'>
		<tr>
		<td>
		<table width='1000' border='0' cellpadding='0' cellspacing='0'>
                    <tr height='20' class='titulo'>
                    <td align=\"center\" width=\"100%\">Nenhum cliente cadastrado neste periodo!</td></tr></table>";
	}
	else
	{
	echo "<table align='center'  width='1000' border='0' cellpadding='0' cellspacing=\"1\" class=\"bodyText\">
                <tr>
                    <td colspan='13' class='titulo'>RANKING DE AFILIADOS x CANCELADOS</td>
                </tr>";
	?>		
			
            <tr height="20" bgcolor="#B6CBF6">
				<td align="center" width="20%" >Consultor</td>
				<td align="center" width="10%" >Ativo/Demitido<br>6 Meses</td>
				<td align="center" width="10%" >Qtd Cancelados<br>6 Meses</td>
				<td align="center" width="10%" >Qtd Afiliados<br>6 Meses</td>
				<td align="center" width="10%" >Qtd Afiliados<br>Geral</td>
				<td align="center" width="10%" >Qtd Cancelados<br>Geral</td>
				<td align="center" width="10%" >Saldo de Contratos</td>
				<td align="center" width="10%" >Total Tit. Pagos</td>
				<td align="center" width="10%" >Total Tit. NAO Pagos</td>
				<td align="center" width="10%" >Total Pago Funcionario</td>
			</tr>
			
	<?php
      for ($a=1; $a<=$linhas; $a++)
	  	{
	  	$matriz = mysql_fetch_array($res);
	  	$vendedor               = $matriz['vendedor'];
                $ativo                  = $matriz['ativo'] == 'S' ? 'Ativo' : 'Demitido';
		$qtd_cancelado          = $matriz['qtd'];
		$qtd_afiliado           = $matriz['qtd_afiliados'];
		$qtd_afiliados_geral    = $matriz['qtd_afiliados_geral'];
		$qtd_cancelados_geral   = $matriz['qtd_cancelados_geral'];
		$saldo                  = $qtd_afiliados_geral - $qtd_cancelados_geral;
                $total_pago             = number_format($matriz['total_pago'],2,',','.');
                $total_nao_pago         = number_format($matriz['total_nao_pago'],2,',','.');
                $total_pago_funcionario = number_format($matriz['total_pago_funcionario'],2,',','.');
                
		echo "<tr height=\"22\"";
		if (($a%2) == 0) {
			echo "bgcolor=\"#E5E5E5\">";
		} else {
			echo ">";
		}
	  	echo " 	<td align='center'>$vendedor</td>
                        <td align='center'>$ativo</td>
			<!-- 
                        <td align='center'><a href='#' onclick=\"ver_cancelado('$data_limite','$data_hoje','$vendedor','$franqueado')\">$qtd_cancelado</a></td>
                            -->
			<td align='center'>$qtd_cancelado</a></td>
			<td align='center'>$qtd_afiliado</td>
			<td align='center'>$qtd_afiliados_geral</a></td>
			<td align='center'>$qtd_cancelados_geral</td>
			<td align='center'>$saldo</td>
			<td align='center'>$total_pago</td>
			<td align='center'>$total_nao_pago</td>
			<td align='center'>$total_pago_funcionario</td>
	  	     </tr>";			
		}
		$a = $a - 1;
		echo "
		</table>
		</td>
		<td>
			<div id='cancelados'></div>
		</td>
		</tr>
		</table>";
	}
$res = mysql_close ($con);
?>
<div align="center"><input type="button" onClick="javascript: history.back();" value="       Voltar       " /></div>