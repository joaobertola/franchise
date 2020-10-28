<?php

	$total_geral_repasse = 0;

    $sql2 = "SELECT a.vendedor FROM cadastro a
             WHERE a.dt_cad between '$data1' and '$data2'
             AND a.id_franquia = $franqueado
             GROUP BY a.vendedor";
    $res2 = mysql_query($sql2,$con) or die ("Erro SQL : $sql");
    $registro2 = 0;
    if ( mysql_num_rows($res2) > 0 ){
            while ( $reg2 = mysql_fetch_array($res2) ){

                echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
                        <tr>
                                <td align='center' colspan='6' height='1' bgcolor='#CCCCCC'>RELAT&Oacute;RIO DE AFILIA&Ccedil;&Otilde;ES - VENDA COMPLETA<br>Per&iacute;odo: $vencimento1 &agrave; $vencimento2</td>
                        </tr>
                        <tr height='20' bgcolor='FF9966'>
                                <td align='center'>Codigo</td>
                                <td align='center'>Nome Fantasia</td>
                                <td align='center'>Data Afiliacao</td>
                                <td align='center'>Franquia</td>
                                <td align='center'>Aut�nomo</td>
                                <td align='center'>Situa��o</td>
                        </tr>";
                $vendedor = $reg2['vendedor'];
                $sql_lista_v = "SELECT CAST(MID(b.logon,1,6) AS UNSIGNED) as logon, a.nomefantasia, date_format(a.dt_cad,'%d/%m/%Y') as dt_cad, 
                                    mid(d.nome,1,10) as vendedor, c.fantasia, a.sitcli, a.pendencia_contratual, a.pendencia_contrato,
                                    a.vr_pgto_fixo
                                FROM cadastro a
                                INNER JOIN logon b ON a.codloja = b.codloja
                                INNER JOIN franquia c ON a.id_franquia = c.id 
                                LEFT JOIN consultores_assistente d ON a.id_consultor = d.id
                                WHERE a.id_franquia = $franqueado
                                  AND a.dt_cad between '$data1' and '$data2' 
                                  AND a.vendedor = '$vendedor'
                                ORDER BY a.dt_cad";
                $res22 = mysql_query($sql_lista_v,$con) or die ("Erro SQL : $sql_lista_v");
                $qtd_contrato = 0;
                $tot_geral = 0;
                $total_a_pagar = 0;
                $valor_balao = 0;
                $tot = 0;
                if ( mysql_num_rows($res22) > 0 ){
                        while ( $reg22 = mysql_fetch_array($res22) ){

                            $logon	  = $reg22['logon'];
                            $nomefantasia = $reg22['nomefantasia'];
                            $dt_cad	  = $reg22['dt_cad'];
                            $vendedor	  = $reg22['vendedor'];

                            if ( $_SESSION['id'] != '163' )
                                $vendedor = substr($vendedor,0,5);

                            $franquia		  = $reg22['fantasia'];
                            $sitcli		  = $reg22['sitcli'];
                            $pendencia_contratual = $reg22['pendencia_contratual'];
                            $pendencia_contrato	  = $reg22['pendencia_contrato'];
                            $vr_pgto_fixo	  = $reg22['vr_pgto_fixo'];
                            echo "<tr>
                                    <td align='center'>$logon</td>
                                    <td align='left'>$nomefantasia</td>
                                    <td align='center'>$dt_cad</td>
                                    <td align='center'>$franquia</td>
                                    <td align='center'>$vendedor</td>";

                            $tot = $sitcli + $pendencia_contratual + $pendencia_contrato;
                            $sit = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

                            if ( $tot == 0){
                                $qtd_contrato++;
                            }else{
                                $sit = ' Pendente';
                            }
                            if ( $sitcli == 2 )
                                $sit = ' Cancelado';
                            if ( $pendencia_contrato == 1 ) $sit = ' Irreg/Treinamento';

                            if($vr_pgto_fixo > 0)
                                    $baixa = "<font color='#00CC00'>Ok Pago</font>";
                            else $baixa = '';

                            echo "<td>$sit $baixa</td>
                            </tr>";

                            $tot_geral += $tot;
                        }
                        if ( $tot_geral == 0 ) {

                            if ( $qtd_contrato <= 19 )
                                $valor_balao = $fixo[0];
                            if ( $qtd_contrato >= 20 and $qtd_contrato <= 24 ) 
                                $valor_balao = $fixo[1];
                            if ( $qtd_contrato >= 25 and $qtd_contrato <= 34 ) 
                                $valor_balao = $fixo[2];
                            if ( $qtd_contrato > 34 ) 
                                $valor_balao = $fixo[3];

                            $total_a_pagar = $valor_balao;

                        }

                        echo "<tr>
                                        <td colspan='5' height='20' align='center'></td>
                                        <td><b>Total de Contratos :</b> $qtd_contrato<br>Fx/Meta : $valor_balao</td>
                                </tr>
                                <tr>
                                        <td colspan='5' height='20' align='center'>
                                        Data Recebimento : ________/________/__________&nbsp;&nbsp;&nbsp;&nbsp;Assinatura:____________________________________________________
                                        </td>
                                        <td><b>Total a Pagar : R$ $total_a_pagar</b></td>
                                </tr>
                                <tr>
                                        <td colspan='6' height='50'><hr></td>
                                </tr>";

                        $valor_balao = str_replace('.','',$valor_balao);
                        $valor_balao = str_replace(',','.',$valor_balao);

                        $total_geral_repasse += $valor_balao;	
                    }
            }
            echo "</table>";

    }
    $total_geral_repasse = number_format($total_geral_repasse,2);
    echo "  <table width='100%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
                <tr>
                    <td align='right'><font size='3'><b>Total Geral de Repasse : $total_geral_repasse</font></b></td>
                </tr>
            </table>";
?>
