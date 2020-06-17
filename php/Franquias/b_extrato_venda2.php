<div align="center" class="noprint">
    <button id="btnImprimir" class="btnImprimir">&nbsp;&nbsp;Imprimir&nbsp;&nbsp;</button>
</div>
<div align="left" class="noprint">
    <input type="button" onClick="location.href = 'painel.php?pagina1=Franquias/b_extrato_venda.php'" value="       Voltar       "/>
</div>
<?php

require "connect/sessao.php";

$franqueado = $_REQUEST['franqueado'];

if (empty($franqueado)) {
    echo "<script>javascript: alert('Selecione uma franquia');history.go(-3)</script>";
    exit;
}
$strDataInicioOrig = $_REQUEST['vencimento1'];
$data1 = $_REQUEST['vencimento1'];
$vencimento1 = $_REQUEST['vencimento1'];
$data1 = substr($data1, 6, 4) . '-' . substr($data1, 3, 2) . '-' . substr($data1, 0, 2);

$strDataFimOrig = $_REQUEST['vencimento2'];
$data2 = $_REQUEST['vencimento2'];
$vencimento2 = $_REQUEST['vencimento2'];

$data2 = substr($data2, 6, 4) . '-' . substr($data2, 3, 2) . '-' . substr($data2, 0, 2);

$ordem = $_REQUEST['ordem'];
$valor = $_REQUEST['valor'];
$valorvvi = $_REQUEST['valorvvi'];
$fixo = $_REQUEST['fixo'];

$complemento = '';


if ($_REQUEST['franqueado'] == 'todos') {
    echo "Favor selecionar uma FRANQUIA.";
}
$complemento = " AND a.id_franquia = " . $_REQUEST['franqueado'];

if ($_REQUEST['consultorAgendador'] != 0) {

    if ($_REQUEST['funcao'] == 'C') {
        $complemento .= " AND d.id = " . $_REQUEST['consultorAgendador'];
        $order = ' ORDER BY d.nome ASC';
    } elseif ($_REQUEST['funcao'] == 'A') {
        $complemento .= " AND e.id =" . $_REQUEST['consultorAgendador'];
        $order = ' ORDER BY e.nome ASC';
    }

}

if ($_REQUEST['funcao'] == 'C') {
    $order = ' ORDER BY d.nome ASC';
} else {
    $order = ' ORDER BY e.nome ASC';
}
if($_POST['ordem'] == 'codloja'){
    $order = ' ORDER BY a.codLoja DESC';
}

if ($_REQUEST['tp_rel'] == '') {

    $sql = "SELECT mid(logon,1,5) as logon, a.nomefantasia, date_format(a.dt_cad,'%d/%m/%Y') as dt_cad, mid(d.nome,1,10) as vendedor, c.fantasia,
                    date_format(a.dt_pgto_comissao_vendedor, '%d/%m/%Y') AS dt_pgto_comissao_vendedor,
                    a.valor_comissao_vendedor, mid(e.nome,1,10) as agendador, a.sitcli, a.pendencia_contratual, a.pendencia_contrato, a.codLoja, a.contadorsn
            FROM cadastro a
            INNER JOIN logon b ON a.codloja = b.codloja 
            INNER JOIN franquia c ON a.id_franquia = c.id
            LEFT JOIN consultores_assistente d ON a.id_consultor = d.id
            LEFT JOIN consultores_assistente e ON e.id = a.id_agendador
            WHERE a.dt_cad between '$data1' and '$data2'
            $complemento";

    echo $sql;

    $sql = $sql . $order;
    $res = @mysql_query($sql, $con) or die ("Erro SQL : $sql");
    $registro = 0;
    $linhas1 = mysql_num_rows($res);
    $linhas1 = $linhas + 3;
    $contratosTotal = 0;
    $contratosPendentes = 0;
    $contratosContador = 0;
    $contratosRegularizados = 0;
    $linhas = mysql_num_rows($res);
    $iAux = 0;
    if (mysql_num_rows($res) > 0) {
        $nomeAux = '';
        $i = 0;
        if ($_POST['ordem'] == 'codloja') {
            
            echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
                    <tr>
                        <td align='center' colspan='7' height='1' bgcolor='#CCCCCC'>RELATORIO DE VENDAS COMPLETAS</td>
                    </tr>
                    <tr>
                        <td align='center' colspan='7' height='1' bgcolor='#CCCCCC'>Período: $strDataInicioOrig à $strDataFimOrig</td>
                    </tr>
                    <tr height='20' bgcolor='' class='subtitulo'>
                        <td align='center'>Código</td>
                        <td align='center'>Nome Fantasia</td>
                        <td align='center'>Data Afiliação</td>
                        <td align='center'>Franquia</td>
                        <td align='center'>Consultor</td>
                        <td align='center' colspan='2'>Situação</td>
                    </tr>
                    <tr>
                        <td colspan='7' height='1' bgcolor='#666666'></td>
                    </tr>";
            while ($reg = mysql_fetch_array($res)) {

                $codloja = $reg['codLoja'];
                $sitcli = $reg['sitcli'];
                $pendencia_contratual = $reg['pendencia_contratual'];
                $pendencia_contrato = $reg['pendencia_contrato'];
                $tot = $sitcli + $pendencia_contratual + $pendencia_contrato;
                $sit = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

                if ($tot == 0) {
                    $sit = '';
                    // Verificando pendencia de contrato por nao escaneamento
                    $sql_scan = "SELECT caminho_imagem, imagem FROM base_inform.cadastro_imagem
                                 WHERE codloja = $codloja ";
                    $qry_scan = @mysql_query($sql_scan, $con);
                    $caminho_imagem = @mysql_result($qry_scan, 0, 'caminho_imagem');
                    $imagem = @mysql_result($qry_scan, 0, 'imagem');

                    if (trim($caminho_imagem . $imagem) == '') {
                        $sit = ' - Pendente Scan';
                    }
                } else {
                    $sit = ' - Pendente Ctr';
                }
                $nome = $reg['vendedor'];
                if ($_POST['funcao'] == 'A') {
                    $nome = $reg['agendador'];
                }
                $registro++;
                $logon = $reg['logon'];
                $nomefantasia = $reg['nomefantasia'];
                $dt_cad = $reg['dt_cad'];
                $vendedor = $reg['vendedor'];
                $agendador = $reg['agendador'];
                $franquia = $reg['fantasia'];
                $dt_pgto_comissao_vendedor = $reg['dt_pgto_comissao_vendedor'];
                $valor_comissao_vendedor = $reg['valor_comissao_vendedor'];
                $contratosTotal++;

                switch ($reg['sitcli']) {

                    case '0' :

                        $situacao = 'Ativo';
                        if($sit == ''){
                            $contratosRegularizados++;
                        }else{
                            $contratosPendentes++;
                        }

                        if($reg['contadorsn'] == 'S'){
                            $contratosContador++;
                            $contratosRegularizados--;
                        }

                        break;

                    case '1' :

                        $situacao = 'Bloqueado';
                        if($sitcli != ''){
                            $contratosPendentes++;
                        }

                        break;

                    case '2' :

                        $situacao = 'Cancelado';
                        if($sitcli != ''){
                            $contratosPendentes++;
                        }

                        break;

                    case '4' :

                        $situacao = 'Bloqueio Virtual';
                        if($sitcli != ''){
                            $contratosPendentes++;
                        }

                        break;

                }

                echo "<tr height='24'";
                if (($registro % 2) == 0) {
                    echo "bgcolor='#E5E5E5'>";
                } else {
                    echo ">";
                }
                echo "  <td align='center'>$logon</td>
                        <td align='left'>$nomefantasia</td>
                        <td align='center'>$dt_cad</td>
                        <td align='center'>$franquia</td>";

                if ($_POST['funcao'] == 'C') {
                    echo "<td align='center'>".substr($vendedor,0,4)."</td>";
                } elseif ($_POST['funcao'] == 'A') {
                    echo "<td align='center'>".substr($agendador,0,4)."</td>";
                }
                echo "<td align='center' colspan='2'>$situacao  $sit</td>";
                echo "</tr>";

                $nomeAux = $reg['vendedor'];
                if ($_POST['funcao'] == 'A') {
                    $nomeAux = $reg['agendador'];
                }

                $iAux++;

            }
            echo '<tr><td colspan="5"></td><td >Total Fechados:</td><td style=text-align:left;">' . $contratosTotal . '</td></td></tr>
                  <tr><td colspan="5"></td><td>Total Pendentes:</td><td style=text-align:left;">' . $contratosPendentes. '</td></td></tr>
                  <tr><td colspan="5"></td><td>Total Contadores:</td><td style=text-align:left;">' . $contratosContador. '</td></td></tr>
                  <tr><td colspan="5"></td><td>Total Regularizados:</td><td style=text-align:left;">' . $contratosRegularizados. '</td></td></tr>
            </table>
        </div>';
            
        } else {
            
            while ($reg = mysql_fetch_array($res)) {

                $codloja = $reg['codLoja'];
                $sitcli  = $reg['sitcli'];
                $pendencia_contratual = $reg['pendencia_contratual'];
                $pendencia_contrato = $reg['pendencia_contrato'];
                $tot = $sitcli + $pendencia_contratual + $pendencia_contrato;
                $sit = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                $contratosTotal++;
                if ($tot == 0) {
                    $sit = '';
                    // Verificando pendencia de contrato por nao escaneamento
                    $sql_scan = "SELECT caminho_imagem, imagem FROM base_inform.cadastro_imagem
                                 WHERE codloja = $codloja ";
                    $qry_scan = @mysql_query($sql_scan, $con);
                    $caminho_imagem = @mysql_result($qry_scan, 0, 'caminho_imagem');
                    $imagem = @mysql_result($qry_scan, 0, 'imagem');

                    if (trim($caminho_imagem . $imagem) == '') {
                        $sit = ' - Pendente Scan';
                    }
                } else {
                    $sit = ' - Pendente Ctr';
                }


                $nome = $reg['vendedor'];
                if ($_POST['funcao'] == 'A') {
                    $nome = $reg['agendador'];
                }

                if ($nomeAux != $nome) {

                    if ($i != 0 || $iAux == $linhas) {
                        echo '
                <tr><td colspan="6" style="text-align: right">Total de Contratos:' . $contratos . '</td></tr>
                <tr><td colspan="6" style="text-align: center">Data Recebimento: _____/_____/______    Assinatura:_____________________________________________________</td></tr>
                <tr><td colspan="6" style="text-align: center">&nbsp;</td></tr>
                </table>
                </div>';
                    }
                    echo "<div class='print'><table width='100%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
                        <tr>
                                <td align='center' colspan='6' height='1' bgcolor='#CCCCCC'>RELATÓRIO DE VENDAS COMPLETAS</td>
                        </tr>
                         <tr>
                                <td align='center' colspan='7' height='1' bgcolor='#CCCCCC'>Período: $strDataInicioOrig à $strDataFimOrig</td>
                        </tr>
                        <tr height='20' class='subtitulo'>
                            <td align='center'>Código</td>
                            <td align='center'>Nome Fantasia</td>
                            <td align='center'>Data Afiliação</td>
                            <td align='center'>Franquia</td>";
                    if ($_POST['funcao'] == 'C') {
                        echo "<td align='center'>Consultor</td>";
                    } elseif ($_POST['funcao'] == 'A') {
                        echo "<td align='center'>Agendador</td>";
                    }
                    echo "              <td align='center' colspan='2'>Situação</td>
                        </tr>
                        <tr>
                                <td colspan='6' height='1' bgcolor='#666666'></td>
                        </tr>";
                    $i++;
                    $contratos = 0;

                }


                $registro++;
                $logon = $reg['logon'];
                $nomefantasia = $reg['nomefantasia'];
                $dt_cad = $reg['dt_cad'];
                $vendedor = $reg['vendedor'];
                $agendador = $reg['agendador'];
                $franquia = $reg['fantasia'];
                $dt_pgto_comissao_vendedor = $reg['dt_pgto_comissao_vendedor'];
                $valor_comissao_vendedor = $reg['valor_comissao_vendedor'];

                switch ($reg['sitcli']) {

                    case '0' :

                        $situacao = 'Ativo';
                        if($sit == ''){
                            $contratos++;
                            $contratosRegularizados++;
                        }else{
                            $contratosPendentes++;
                        }
                        if($reg['contadorsn'] == 'S'){
                            $contratosContador++;
                            $contratosRegularizados--;
                            $situacao .= ' - Contador';
                        }
                        break;

                    case '1' :

                        $situacao = 'Bloqueado';
                        if($sitcli != ''){
                            $contratosPendentes++;
                        }
                        break;

                    case '2' :

                        $situacao = 'Cancelado';
                        if($sitcli != ''){
                            $contratosPendentes++;
                        }
                        break;

                    case '4' :

                        $situacao = 'Bloqueio Virtual';
                        if($sitcli != ''){
                            $contratosPendentes++;
                        }
                        break;

                }

                echo "<tr height='24'";
                if (($registro % 2) == 0) {
                    echo "bgcolor='#E5E5E5'>";
                } else {
                    echo ">";
                }
                echo "  <td align='center'>$logon</td>
                    <td align='left'>$nomefantasia</td>
                                <td align='center'>$dt_cad</td>
                    <td align='center'>$franquia</td>";

                if ($_POST['funcao'] == 'C') {
                    echo "<td align='center'>".substr($vendedor,0,4)."</td>";
                } elseif ($_POST['funcao'] == 'A') {
                    echo "<td align='center'>".substr($agendador,0,4)."</td>";
                }
                echo "<td align='center' colspan='2'>$situacao  $sit</td>";
                echo "
                </tr>";

                $nomeAux = $reg['vendedor'];
                if ($_POST['funcao'] == 'A') {
                    $nomeAux = $reg['agendador'];
                }

                $iAux++;
                if ($iAux == $linhas) {
                    
                    $contratosRegularizados = $contratosTotal - ( $contratosPendentes + $contratosContador );
                    echo '    <tr>
                                <td colspan="6" style="text-align: right">Total de Contratos: ' . $contratos . '</td>
                              </tr>
                              <tr>
                                <td colspan="6" style="text-align: center">Data Recebimento: _____/_____/______    Assinatura:_____________________________________________________</td>
                              </tr>
                              <tr>
                                <td colspan="6" style="text-align: center">&nbsp;</td>
                              </tr>
                            </table>
                       </div>
                       <div>
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="bodyText">
                              <tr>
                                <td colspan="6" style="text-align: right">
                                <hr>Total geral de Contratos: ' . $contratosTotal . '</td>
                              </tr>
                              <tr>
                                <td colspan="6" style="text-align: right">Total geral de Pendentes: ' . $contratosPendentes . '</td>
                              </tr>
                              <tr>
                                <td colspan="6" style="text-align: right">Total geral de Contadores: ' . $contratosContador . '</td>
                              </tr>
                              <tr>
                                <td colspan="6" style="text-align: right">Total geral Regularizados: ' . $contratosRegularizados . '</td>
                              </tr>
                            </table>
                            
                </div>';
                }

            }
        }

    } else {
        echo "<script>alert('NENHUM REGISTRO ENCONTRADO');history.back()</script>";
    }
} else {
    if ($_REQUEST['tp_rel'] == '1')
        include("b_extrato_venda22.php");
    else
        include("b_extrato_venda222.php");
}
?>
<script>

    $('.btnImprimir').on('click', function () {


        var style = '.tablenoborder, \n\
                .tablenoborder > tr, \n\
                .tablenoborder > tr > td  { \n\
                    border:0px\n\
                }\n\
                td{\n\
                    white-space:nowrap;\n\
                }\n\
                .hideonprint{\n\
                    display:none;\n\
                }\n\
                body\n\
                {\n\
                    font-family:arial, \n\
                    sans-serif;\n\
                }\n\
                h5{\n\
                    font-size:16px;\n\
                    text-align:center\n\
                }\n\
                table{\n\
                    font-size:9px !important;width:100%;\n\
                    border-collapse: collapse;\n\
                    border: 1px solid #999;\n\
                }\n\
                table td, \n\
                table th{\n\
                    padding:5px;margin:0; border: 1px solid #999;\n\
                }\n\
                .bold{\n\
                    font-weight: bold;\n\
                }\n\
                .selected{ \n\
                    border-style: solid;\n\
                    border-width: 1px 2px 1px 2px;\n\
                }\n\
                .txtcenter{text-align: center;}';

        /** ABRE O POPUP DE IMPRESSAO **/

        var textHtml = '';

        $.each($('.print'), function () {
            textHtml += $(this).html();
        });

        Popup(textHtml, style);


    });

    function Popup(data, style) {

        var mywindow = window.open('', '#tabelaSemPag');
        mywindow.document.write('<html><head><title></title>');
        //stylesheet
        mywindow.document.write('<style>' + style + '</style>');

        mywindow.document.write('</head><body>');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        setTimeout(function () {
            mywindow.print();
            mywindow.close();
        }, 100); //1 segundo

        return true;

    }
    
</script>