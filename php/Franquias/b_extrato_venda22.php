<?php

    $total_geral_repasse = 0;

    if ( $_REQUEST['franqueado'] != 'todos' )
        $complemento = " AND a.id_franquia = ".$_REQUEST['franqueado'];
    else
        $complemento = " AND a.id_franquia = 1 ";

    $sql2 = "SELECT a.id_consultor
             FROM cadastro a
             WHERE a.dt_cad between '$data1' and '$data2'
             $complemento
             GROUP BY a.id_consultor";
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
                        <td align='center'>Consultor&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td align='center'></td>
                        <!-- <td align='center'>&nbsp;V V I</td> -->
                    </tr>";
            $id_consultor = $reg2['id_consultor'];
            $sql_lista_v = "SELECT MID(b.logon,1,LOCATE('S', b.logon) - 1) as logon, a.nomefantasia, date_format(a.dt_cad,'%d/%m/%Y') as dt_cad,
                                   d.nome as vendedor, c.fantasia, a.sitcli, a.pendencia_contratual, a.pendencia_contrato,
                                   a.valor_comissao_vendedor, a.codloja
                            FROM cadastro a
                            INNER JOIN logon b ON a.codloja = b.codloja
                            INNER JOIN franquia c ON a.id_franquia = c.id 
                            LEFT JOIN consultores_assistente d ON a.id_consultor = d.id
                            WHERE 
                                a.dt_cad between '$data1' and '$data2' 
                                AND a.id_consultor = '$id_consultor'
                                $complemento";
            $res22 = mysql_query($sql_lista_v,$con) or die ("Erro SQL : $sql_lista_v");
            $qtd_contrato = 0;
            $Soma_vvi = 0;
            $tot_geral = 0;
            $total_a_pagar = 0;
            $cont = 0;
            if ( mysql_num_rows($res22) > 0 ){
                while ( $reg22 = mysql_fetch_array($res22) ){
                    $cont++;
                    $codloja      = $reg22['codloja'];
                    $logon	  = $reg22['logon'];
                    $nomefantasia = $reg22['nomefantasia'];
                    $dt_cad	  = $reg22['dt_cad'];
                    $vendedor	  = $reg22['vendedor'];
                    if ( $_SESSION['id'] != '163' )
                        $vendedor	= substr($vendedor,0,11);

                    $franquia		     = $reg22['fantasia'];
                    $sitcli		     = $reg22['sitcli'];
                    $pendencia_contratual    = $reg22['pendencia_contratual'];
                    $pendencia_contrato	     = $reg22['pendencia_contrato'];
                    $valor_comissao_vendedor = $reg22['valor_comissao_vendedor'];

                    if ( ($cont%2) == 0) $cor = '#E5E5E5';
                    else $cor = '#FFFFFF';

                    echo "<tr bgcolor='$cor'>
                            <td align='center'>$logon</td>
                            <td align='left'>$nomefantasia</td>
                            <td align='center'>$dt_cad</td>
                            <td align='center'>$franquia</td>
                            <td align='center'>$vendedor</td>";

                    $tot = $sitcli + $pendencia_contratual + $pendencia_contrato;
                    $sit = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

                    if ( $tot == 0){
                        // Verificando pendencia de contrato por nao escaneamento
                        $sql_scan = "SELECT caminho_imagem, imagem FROM base_inform.cadastro_imagem
                                     WHERE codloja = $codloja ";
                        $qry_scan = mysql_query($sql_scan,$con);
                        $caminho_imagem = mysql_result($qry_scan,0,'caminho_imagem');
                        $imagem = mysql_result($qry_scan,0,'imagem');

                        if ( trim($caminho_imagem.$imagem) == '' )
                            $sit = 'Pendente Scan';
                        else
                            $qtd_contrato++;
                    }else{
                        $sit = ' Pendente Ctr';
                    }

                    if ( $sitcli == 2 )
                        $sit = ' Cancelado';
                    elseif ( $pendencia_contrato == 1 ) $sit = ' Irreg/Treinamento';

                    if($valor_comissao_vendedor > 0)
                            $baixa = "<font color='#00CC00'>Ok Pago</font>";
                    else $baixa = '';

                    /*
                    echo "<td>".$valorvvi[0]."$sit $baixa</td>
                    </tr>";
                     */
                    
                    echo "<td></td></tr>";
                    $tot_geral += $tot;
                }

                if ( $tot_geral == 0 ) {

                        $valor_vvi = $valorvvi[0];
                        $valor_vvi = str_replace(',','.',$valor_vvi);
                        $Soma_vvi += $valor_vvi;

                        switch ($qtd_contrato) {
                            case 1: $valor_balao = $valor[0];
                                                break;
                                case 2: $valor_balao = $valor[1];
                                                break;
                                case 3: $valor_balao = $valor[2];
                                                break;
                                case 4: $valor_balao = $valor[3];
                                                break;					
                                case 5: $valor_balao = $valor[4];
                                                break;
                                case 6: $valor_balao = $valor[5];
                                                break;
                        }

                        $valor_balao = str_replace(',','.',$valor_balao);
                        $somabalao = $valor_balao;

                        $Soma_vvi = $qtd_contrato * $Soma_vvi;

                        $total_a_pagar = $somabalao + $Soma_vvi;
                        $total_a_pagar = number_format($total_a_pagar,2);

                        $Soma_vvi = number_format($Soma_vvi,2);
                        @$somabalao = number_format($somabalao,2);

                }

                echo "<tr>
                                <td colspan='5' height='20' align='center'></td>
                                <td><b>Total de Contratos :</b> $qtd_contrato<br> <!-- Balao : $somabalao<br>VVI : $Soma_vvi --></td>
                        </tr>
                        <tr>
                                <td colspan='5' height='20' align='center'>
                                Data Recebimento : ________/________/__________&nbsp;&nbsp;&nbsp;&nbsp;Assinatura:____________________________________________________
                                </td>
                                <td><!-- <b>Total a Pagar : R$ $total_a_pagar</b> --></td>
                        </tr>
                        <tr>
                                <td colspan='6' height='50'><hr></td>
                        </tr>";
                        $total_geral_repasse += $total_a_pagar;	
        }
    }
    echo "</table>";

}
$total_geral_repasse = number_format($total_geral_repasse,2);
/*
echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
                                <tr>
                                        <td align='right'><font size='3'><b>Total Geral de Repasse : $total_geral_repasse</font></b></td>
                                </tr>
                </table>";
  */
?>
