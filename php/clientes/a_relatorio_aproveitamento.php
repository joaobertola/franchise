<script language="javascript">
function voltar(){
    frm = document.relatorio;
    frm.action = 'painel.php?pagina1=clientes/a_controle_visitas3.php';
    frm.submit();
}
</script>

<form name="relatorio" method="post" action="#" >

<?php

$sql_pesquisa = "
    SELECT 
        ( SELECT nome FROM cs2.consultores_assistente WHERE id = id_consultor ) as nome_consultor,
        sum(resultado_visitou) as resultado_visitou,
        sum(resultado_demonstrou) as resultado_demonstrou,
        sum(resultado_levousuper) as resultado_levousuper,
        sum(resultado_ligougerente) as resultado_ligougerente,
        id_consultor, resultado_cartaovisita
          
    FROM cs2.controle_comercial_visitas 
    $sql_cont
    GROUP BY id_consultor
    ORDER BY nome_consultor
    ";
$qry_pesquisa = mysql_query($sql_pesquisa) or die ("Erro MYSQL \n".mysql_error()."\n");
if ( mysql_num_rows($qry_pesquisa) == 0 ){
	echo "	
	<table width='950' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
            <tr>
                <td align='center' colspan='12' height='1' bgcolor='#CCCCCC'>
                    <input type='button' value='  Voltar  ' style='cursor:pointer' onClick=\"document.location='painel.php?pagina1=clientes/a_controle_visitas3.php'\"/>
                </td>
            </tr>
            <tr>
                <td align='center' colspan='7' height='1' bgcolor='#CCCCCC'>RELAT&Oacute;RIO DE APROVEITAMENTO COMERCIAL</td>
            </tr>
            <tr>
                <td align='center' colspan='7' height='1' bgcolor='#CCCCCC'><font size='2' color='#FF0000'> Nenhum registro encontrado !<b></font></td>
            </tr>	
	</table>";
	exit;
}

echo "	
    <table width='950' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
        <tr>
            <td align='center' colspan='12' class=\"titulo\"'>RELATÓRIO DE CONTROLE COMERCIAL</td>
        </tr>
        <tr>
            <td colspan='12' height='1' bgcolor='#CCCCCC' align='center'>
                FRANQUIA: $nom_franquia <br>
                CONSULTOR: $xrel_consultor <br>
                PERIODO : ".$_REQUEST['rel_datai']." a ".$_REQUEST['rel_dataf']."
            </td>
        </tr>
    </table>
    <br>
    <table width='200' border='0' cellpadding='0' cellspacing='0' class='bodyText' bgcolor='#E0F7FA'>
        <tr>
            <td><b>Procedimento</b></td>
            <td><b>B&ocirc;nus</b></td>
        </tr>
        <tr>
            <td>Hor�rio Correto</td>
            <td>R$ 1,00</td>
        </tr>
        <tr>
            <td>Demonstra��o Completa</td>
            <td>R$ 4,00</td>
        </tr>
        <tr>
            <td>Super Pasta Equipamentos</td>
            <td>R$ 3,00</td>
        </tr>
        <tr>
            <td>Ligou p/ gerente</td>
            <td>R$ 1,00</td>
        </tr>
        <tr>
            <td>Cart�es de Visita</td>
            <td>R$ 1,00</td>
        </tr>
    </table>
    <br>
    <table width='950' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
        <tr  bgcolor='E5E5E5'>
            <td >Nome do Profissional</td>
            <td align='center'>Visitas Agendadas</td>
            <td align='center'>Horario Correto</td>
            <td align='center'>Demonstracao Completa</td>
            <td align='center'>Super Pasta</td>
            <td align='center'>Ligou p/ Gerente</td>
            <td align='center'>Cart�es de Visita</td>
            <td>B�nus Aproveitamento</td>
        </tr>";
        $qtd_reg = 0;
        while ( $reg = mysql_fetch_array($qry_pesquisa) ){
            $qtd_reg++;
            $id_consultor       = $reg['id_consultor'];
            $nome_consultor       = substr($reg['nome_consultor'],0,25);
            $resultado_visitou       = $reg['resultado_visitou'];
            $resultado_demonstrou       = $reg['resultado_demonstrou'];
            $resultado_levousuper       = $reg['resultado_levousuper'];
            $resultado_ligougerente       = $reg['resultado_ligougerente'];
            $resultado_cartaovisita       = $reg['resultado_cartaovisita'];
            $real_visitou = $resultado_visitou * 1;
            $real_demonstrou = $resultado_demonstrou * 4;
            $real_levousuper = $resultado_levousuper * 3;
            $real_ligougerente = $resultado_ligougerente * 1;
            $real_cartaovisita = $resultado_cartaovisita * 1;
            
            $soma = $real_visitou + $real_demonstrou + $real_levousuper + $real_ligougerente+$real_cartaovisita;
            
            $mostra_soma = number_format($soma,2,',','.');
            
            $sql_total_visita = "
                SELECT COUNT(*) as qtd
                FROM cs2.controle_comercial_visitas 
                $sql_cont
                AND id_consultor = $id_consultor";
            $qry_visitas = mysql_query($sql_total_visita) or die ("Erro MYSQL");
            $qtd_visitas = mysql_result($qry_visitas,0, 'qtd');
            
            echo "<tr height='22'";
            if ( ( $qtd_reg % 2 ) == 0 ) {
                    echo "bgcolor='#E5E5E5'>";
            } else {
                    echo ">";
            }
            echo "
                    <td>$nome_consultor</td>
                    <td bgcolor='#FFF8E1' align='center' >$qtd_visitas</td>
                    <td align='center' >$resultado_visitou</td>
                    <td bgcolor='#E0F7FA' align='center' >$resultado_demonstrou</td>
                    <td align='center' >$resultado_levousuper</td>
                    <td align='center' >$resultado_ligougerente</td>
                    <td align='center' >$resultado_cartaovisita</td>
                    <td bgcolor='#E0F7FA'>R$ $mostra_soma</td>
                  </tr>";
            
            $total_visitas += $qtd_visitas;
            $total_soma += $soma;

        }
        $total_soma = number_format($total_soma,2,',','.');
echo " 
        <tr>
            <td colspan='7' align='right'><b>Total do B�nus Aproveitamento&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
            <td colspan='1'><b>R$ $total_soma</b></td> 
        </tr>
    </table>
    <table width='950' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
        <tr>
            <td align='center'>
                <input type='button' value='  VOLTAR  ' onClick='voltar()' />
            </td>
        </tr>
    </table>
";
      
?>
</form>