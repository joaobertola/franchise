<?php
/**
 * @file auxiliarTabelaComissao.php
 * @brief
 * @author ARLLON DIAS
 * @date 08/03/2017
 * @version 1.0
 **/
require "connect/sessao.php";
require "connect/funcoes.php";

function geraHtml($idFuncionario, $strDataInicio, $strDataFim, $ativo, $con, $nome, $funcao, $origem )
{

    $dataFiltro = substr($strDataFim,0,7);

    $sqlAfiliacao = "SELECT
                        c.codLoja,
                        c.nomefantasia,
                        f.id,
                        f.nome,
                        fun.descricao AS funcao,
                        @comissaoAfiliacao:=(f.comissao_afiliacao * c.vr_pgto_adesao) / 100 AS comissao_afiliacao_aux ,
                        c.vr_pgto_adesao,
                        DATE_FORMAT(c.dt_cad, '%d/%m/%Y') AS data_afiliacao,
                        c.sitcli,
                                        @pagoComissao:=IF(vr_pgto_comissao IS NOT NULL AND vr_pgto_comissao > 0.00,1,0) AS pago_comissao,
                        (SELECT login FROM base_web_control.webc_usuario WHERE id_cadastro = c.codLoja LIMIT 1) AS codigo,
                        @pendente:= IF(contadorsn = 'S', 'Ativo',IF(c.dt_pgto_adesao IS NULL, 'Pendente Adesão', CONCAT(IF(c.sitcli = 0,IF(pendencia_contrato = 1 || pendencia_contratual = 1 , 'Pendente Ctr', IF((
                        SELECT
                            caminho_imagem
                        FROM base_inform.cadastro_imagem
                        WHERE codloja = c.codLoja
                        LIMIT 1
                        ) IS NULL,'Pendente Scan', 'Ativo')),IF(c.sitcli = 1, 'Bloqueado', IF(c.sitcli = 2, 'Cancelado',IF(c.sitcli = 4,'Bloqueio Virtual', 'Ativo')))),IF(c.contadorsn = 'S', ' - Contador', '')))) AS pendencia,
                        contadorsn,
                        IF(contadorsn = 'N' AND @pagoComissao = '0' AND @pendente = 'Ativo', @comissaoAfiliacao, 0) AS comissao_afiliacao,
                        IF(@pagoComissao = 1,@comissaoAfiliacao,0) AS valor_comissao_pago,
                        (SELECT IF(COUNT(*) > 0,1,0) FROM cs2.funcionario_bonus_afiliacao WHERE id_funcionario = f.id AND tipo_bonus = '20' AND DATE_FORMAT(referencia_bonus,'%Y-%m') = '$dataFiltro') AS pago_bonus20,
                        (SELECT IF(COUNT(*) > 0,1,0) FROM cs2.funcionario_bonus_afiliacao WHERE id_funcionario = f.id AND tipo_bonus = '25' AND DATE_FORMAT(referencia_bonus,'%Y-%m') = '$dataFiltro') AS pago_bonus25
                    FROM cs2.cadastro c
                    INNER JOIN cs2.funcionario f
                    ON f.id_consultor_assistente = c.id_consultor
                    LEFT JOIN cs2.funcao fun
                    ON fun.id = f.id_funcao
                    WHERE c.id_franquia = 1
                    AND f.ativo = '$ativo'
                    AND f.id = '$idFuncionario'
                    AND CONCAT(c.dt_cad) BETWEEN '$strDataInicio' AND '$strDataFim'
                    ORDER BY f.nome ASC";

    $qryAfiliacao = mysql_query($sqlAfiliacao, $con);
    $totalLinhas = mysql_num_rows($qryAfiliacao);
    $totalAfiliacoes = 0;
    $totalAtivos = 0;
    $totalPendentes = 0;
    $totalCancelados = 0;
    $totalContadores = 0;
    $totalPendentesAdesao = 0;
    $totalPagos = 0;
    $pendentesARepassar = 0;
    $totalPendenteAfiliacao = 0;
    
    $dataI = data_mysql_i($strDataInicio);
    $dataF = data_mysql_i($strDataFim);

    $html = "
        <style type='text/css'>
            .quebrapagina {
                 page-break-before: always;
            }
        </style>
        <div class='page-break'>
        <br><br><br>
                <table width='100%' border='0' cellpadding='0' cellspacing='0' style='margin-top: 5px;'>
                    <tr>
                        <td class='titulo' style='text-align: left !important;'>" . $nome . "</td>
                        <td class='titulo' style='text-align: center !important;'> Período: $dataI à $dataF</td>
                    </tr>
                </table>
                <table width='100%' border='1' cellpadding='0' cellspacing='0' style='margin-top: 5px;'>
                <tr>";
                if ( $origem == 'CONTABIL')
                    $html .= "<td colspan='6' class='titulo'>Novas Afiliações</td>";
                else
                    $html .= "<td colspan='7' class='titulo'>Novas Afiliações</td>";
                
                $html .= "</tr>
                <tr>
                    <td width='5%' style='width: 5%;' class='subtitulo'>Código</td>
                    <td width='35%' style='width: 25%;' class='subtitulo'>Empresa</td>
                    <td width='10%' style='width: 10%;' class='subtitulo'>Data Afiliação</td>
                    <td width='30%' style='width: 25%;' class='subtitulo'>Adesão</td>
                    <td width='20%' style='width: 20%;' class='subtitulo'>Comissão 50%</td>
                    <td width='30%' style='width: 25%;' class='subtitulo'>Status</td>";
                
                if ( $origem != 'CONTABIL'){
                    $html .= "<td width='20%' style='width: 15%;' class='subtitulo''>Pago</td>";
                }
                $html .= "</tr>";
    $vr_pago_comissao = 0;
    $vr_pgto_adesao = 0;
    $qtd_afiliacao_contabil = 0;
    while ($arrAfiliacao = mysql_fetch_array($qryAfiliacao)) {
        
        $checked = '';
        $vr_pago_comissao = $vr_pago_comissao + $arrAfiliacao['valor_comissao_pago'];
        $vr_pgto_adesao = $vr_pgto_adesao + $arrAfiliacao['vr_pgto_adesao'];
        $qtd_afiliacao_contabil++;

        $comissaoAfiliacao = (float)$arrAfiliacao['comissao_afiliacao'];
        
        if ($arrAfiliacao['pago_comissao'] == 1) 
            $checked = 'checked';
        
        $pendencia = $arrAfiliacao['pendencia'];
        if($arrAfiliacao['contadorsn'] == 'S'){
            $pendencia = $pendencia . ' - Contador';
        }
        
        if ( $origem != 'CONTABIL'){
            $html .= '<tr>
                        <td width="5%" class="corpoTabela">' . $arrAfiliacao['codigo'] . '</td>
                        <td width="35%" class="corpoTabela">' . $arrAfiliacao['nomefantasia'] . '</td>
                        <td width="10%" class="corpoTabela">' . $arrAfiliacao['data_afiliacao'] . '</td>
                        <td width="30%" class="corpoTabela">R$' . number_format($arrAfiliacao['vr_pgto_adesao'],2,',','.') . '</td>
                        <td width="30%" class="corpoTabela">R$' . number_format(($arrAfiliacao['vr_pgto_adesao']/2),2,',','.') . '</td>
                        <td width="20%" class="corpoTabela">' . $pendencia . '</td>';

                        if ( $origem != 'CONTABIL')
                            $html .= '<td width="20%" class="corpoTabela" align="center">';

                            if ( $origem != 'CONTABIL'){

                                $html .= ' 
                                <input type="checkbox"
                                       name="iptPagoComissao"
                                       id="iptPagoComissao"
                                       class="iptPagoComissao"
                                       data-id_cadastro="' . $arrAfiliacao['codLoja'] . '"
                                       data-valor_comissao="' . $arrAfiliacao['comissao_afiliacao'] . '"
                                       ' . $checked . '>';
                            }
            $html .='</td>
                      </tr>';
            
        }else{
            
            if ( $pendencia == 'Ativo' ){
                $html .= '<tr>
                            <td width="5%" class="corpoTabela">' . $arrAfiliacao['codigo'] . '</td>
                            <td width="35%" class="corpoTabela">' . $arrAfiliacao['nomefantasia'] . '</td>
                            <td width="10%" class="corpoTabela">' . $arrAfiliacao['data_afiliacao'] . '</td>
                            <td width="30%" class="corpoTabela">R$' . number_format($arrAfiliacao['vr_pgto_adesao'],2,',','.') . '</td>
                            <td width="30%" class="corpoTabela">R$' . number_format(($arrAfiliacao['vr_pgto_adesao']/2),2,',','.') . '</td>
                            <td width="20%" class="corpoTabela">' . $pendencia . '</td>';
                $html .='</td>
                          </tr>';
            }else
                $qtd_afiliacao_contabil--;
            
        }

        $totalAfiliacoes = $totalAfiliacoes + 1;

        switch ($arrAfiliacao['pendencia']) {

            case 'Ativo':

                $totalAtivos = $totalAtivos + 1;
                break;
            
            case 'Pendente Ctr':

                $totalPendentes = $totalPendentes + 1;
                $comissaoAfiliacao = 0;
                break;

            case 'Pendente Scan':

                $totalPendentes = $totalPendentes + 1;
                $comissaoAfiliacao = 0;
                break;

            case 'Cancelado':

                $totalCancelados = $totalCancelados + 1;
                break;
            
            case 'Pendente Adesão':

                $totalPendentesAdesao = $totalPendentesAdesao + 1;
                break;

        }

        if ($arrAfiliacao['contadorsn'] == 'S') {
            $totalContadores = $totalContadores + 1;
        }

        if ($arrAfiliacao['pago_comissao'] == 1) {
            $totalPagos = $totalPagos + 1;
        }

        $valorBonus20 = 0.00;
        $valorBonus25 = 0.00;

        if ($totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao) >= 20) {
            $valorBonus20 = 500.00;
            $valorBonus20Pendente = 500.00;
        }

        if ($totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao) >= 25) {
            $valorBonus25 = 500.00;
            $valorBonus25Pendente = 500.00;
        }
        $totalPendenteAfiliacao = $comissaoAfiliacao + $totalPendenteAfiliacao;
        $idFuncionario = $arrAfiliacao['id'];

        $checked20 = '';
        if($arrAfiliacao['pago_bonus20'] == 1){
            $checked20 = 'checked';
            $valorBonus20Pendente = 0;
            $valorBonus20Repassado = 500.00;
        }

        $checked25 = '';
        if($arrAfiliacao['pago_bonus25'] == 1){
            $checked25 = 'checked';
            $valorBonus25Pendente = 0;
            $valorBonus25Repassado = 500.00;
        }
    }
    
    $totalRepassarAfiliacao = $comissaoAfiliacao + $valorBonus20 + $valorBonus25;
    $pendentesARepassar = $totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao + $totalPagos);

    if ( $origem == 'CONTABIL'){
    $html .= '<td colspan="7" class="corpoTabela">&nbsp;</td>
                <tr>
                    <td class="corpoTabela"
                        colspan="7">Afiliações: ' . $qtd_afiliacao_contabil .' | Repassados ' . $totalPagos . '</td>
                </tr>';
    }else{
        $html .= '<td colspan="7" class="corpoTabela">&nbsp;</td>
                    <tr>
                        <td class="corpoTabela"
                            colspan="7">Afiliações: ' . $totalAfiliacoes . ' | Cancelados: ' . $totalCancelados . ' | Pendentes à Pagar: ' . $pendentesARepassar . ' | Contadores: ' . $totalContadores . ' | Pendentes Adesão: ' . $totalPendentesAdesao . ' | Repassados ' . $totalPagos . '</td>
                    </tr>';
    }
                $html .= '<tr>
                    <td colspan="4" align="right" class="corpoTabela">Pendentes à Pagar</td>
                    <td colspan="3"
                        class="corpoTabela">R$ ' . number_format($totalPendenteAfiliacao, 2, ',', '.') . '</td>
                </tr>
                <tr>
                    <td colspan="4" align="right" class="corpoTabela">Total Repassados</td>
                    <td colspan="3"
                        class="corpoTabela">R$ ' . number_format($vr_pago_comissao, 2, ',', '.') . '</td>
                </tr>
                ';

                if ( $totalAfiliacoes >= 20 ){
                
                    $html .= '<tr data-id_funcionario="'.$idFuncionario.'"
                                 data-data_referencia="'.$dataFiltro.'">
                                 <td colspan="4" align="right" class="corpoTabela">Premiação Especial - 20 Afiliações</td>
                                 <td colspan="2" class="corpoTabela">R$ ' . number_format($valorBonus20, 2, ',', '.') . '</td>
                                 <td colspan="1" class="corpoTabela"><input type="checkbox" name="iptPagoBonus20" id="iptPagoBonus20" class="iptPagoBonus20" '.$checked20.'/></td>
                              </tr>';
                }
                
                if ( $totalAfiliacoes >= 25 ){
                    $html .= '<tr data-id_funcionario="'.$idFuncionario.'">
                                 <td colspan="4" align="right" class="corpoTabela">Premiação Especial - 25 Afiliações</td>
                                 <td colspan="2" class="corpoTabela">R$ ' . number_format($valorBonus25, 2, ',', '.') . '</td>
                                 <td colspan="1" class="corpoTabela"><input type="checkbox" name="iptPagoBonus25" id="iptPagoBonus25" class="iptPagoBonus25" '.$checked25.'/></td>
                              </tr>';
                }

               $html .= ' </table>';


    $sqlEquipamentos = "SELECT
                            ce.id,
                            ce.data_compra,
                            DATE_FORMAT(ce.data_venda, '%d/%m/%Y') AS data_venda_label,
                            c.nomefantasia,
                            (SELECT login FROM base_web_control.webc_usuario WHERE id_cadastro = c.codLoja LIMIT 1) AS codigo,
                            GROUP_CONCAT(p.descricao SEPARATOR ' <br>') AS descricao,
                            ced.valor_unitario,
                            f.comissao_equipamento,
                            f.nome,
                            IF(ce.vr_pgto_comissao IS NOT NULL AND ce.vr_pgto_comissao > 0.00,1,0) AS pago_comissao,
                            (SELECT
                                (SUM(valor) - (SELECT IFNULL(SUM(valor_unitario),0) FROM cs2.cadastro_equipamento_descricao WHERE id_cadastro_equipamento = cp.id_venda AND (codigo_barra = '1' OR codigo_barra = '7510103')))
                            FROM cs2.cadastro_equipamento_pagamento cp
                            WHERE id_venda = ce.id)  AS valor_total,
                            (SELECT IF(COUNT(*)>0,0,1) FROM cs2.cadastro_equipamento_pagamento WHERE id_venda = ce.id AND dt_conf_recebimento IS NOT NULL) AS pendente
                        FROM cs2.cadastro_equipamento ce
                        INNER JOIN cs2.cadastro_equipamento_descricao ced
                        ON ced.id_cadastro_equipamento = ce.id
                        LEFT JOIN base_web_control.produto p ON p.id_cadastro=62735 AND (ced.codigo_barra = p.codigo_barra OR ced.codigo_barra = p.identificacao_interna)
                        LEFT JOIN cs2.funcionario f
                        ON f.id_consultor_assistente = ce.id_consultor
                        OR f.id = ce.id_consultor
                        INNER JOIN cs2.cadastro c
                        ON ce.codloja = c.codLoja
                        WHERE data_venda BETWEEN '$strDataInicio' AND '$strDataFim'
                         AND (p.id != '7510103' and p.id != '7788520')
                        AND f.id = '$idFuncionario'
                        AND c.id_franquia = 1
                        GROUP BY ce.id
                        ORDER BY ce.id ASC;";

    $qryEquipamentos = mysql_query($sqlEquipamentos, $con);

    $html .= '<table width="100%" border="1" cellpadding="0" cellspacing="0" class="" style="margin-top: 5px;">
                 <tr>';
        if ( $origem == 'CONTABIL')
            $html .= '<td colspan="7" class="titulo">Equipamentos e Suprimentos</td>';
        else
            $html .= '<td colspan="8" class="titulo">Equipamentos e Suprimentos</td>';
        $html .= '</tr>
                  <tr>
                     <td class="subtitulo">Data</td>
                     <td class="subtitulo">Código</td>
                     <td class="subtitulo">Empresa</td>
                     <td class="subtitulo">Produtos</td>
                     <td class="subtitulo">Valor Venda</td>
                     <td class="subtitulo">Comissão 7%</td>
                     <td class="subtitulo">Status</td>';
        
            if ( $origem != 'CONTABIL')
               $html .= '<td class="subtitulo">Pago</td>';
            
            $html .= '</tr>';

    $totalEquipamentos = mysql_num_rows($qryEquipamentos);
    $valorTotal = 0;
    $valorTotalPago = 0;
    $valorTotalComissao = 0;
    $comissaoEquipamento = 0;
    while ($arrEquipamentos = mysql_fetch_array($qryEquipamentos)) {
        $status = 'Pendente Pagamento';
        $checked = '';

        if ($arrEquipamentos['pago_comissao'] == '1') {
            $checked = 'checked';
        }

        if ($arrEquipamentos['pendente'] == 0) {
            $status = 'OK';
            $valorTotalComissao = $valorTotalComissao + $arrEquipamentos['valor_total'];
        }

        $html .= '<tr style="border: black; border-style: solid;">
                                <td class="corpoTabela">' . $arrEquipamentos['data_venda_label'] . '</td>
                                <td class="corpoTabela">' . $arrEquipamentos['codigo'] . '</td>
                                <td class="corpoTabela">' . $arrEquipamentos['nomefantasia'] . '</td>
                                <td class="corpoTabela">' . $arrEquipamentos['descricao'] . '</td>
                                <td class="corpoTabela">R$ ' . number_format($arrEquipamentos['valor_total'], 2, ',', '.') . '</td>
                                <td class="corpoTabela">R$ ' . number_format($arrEquipamentos['valor_total']*0.07, 2, ',', '.') . '</td>
                                <td class="corpoTabela">'. $status .'</td>';
        if ( $origem != 'CONTABIL')
                      $html .= '<td class="corpoTabela" align="center">
                                    <input type="checkbox"
                                           name="iptPagoEquipamento"
                                           id="iptPagoEquipamento"
                                           class="iptPagoEquipamento"
                                           data-id_equipamento="' . $arrEquipamentos['id'] . '"
                                           data-valor_comissao="' . $arrEquipamentos['valor_total'] * ($arrEquipamentos['comissao_equipamento'] / 100) . '"
                                         ' . $checked . ' >

                                </td>';
                      $html .='</tr>';

        if ($arrEquipamentos['pago_comissao'] == '1') {

            $valorTotalPago = $valorTotalPago + $arrEquipamentos['valor_total'];
        } else {
            $valorTotal = $valorTotal + $arrEquipamentos['valor_total'];
        }
        $comissaoEquipamento = $arrEquipamentos['comissao_equipamento'];
    }
    ?>
    <?php
    $totalAPagar = number_format((($valorTotal) * ($comissaoEquipamento / 100)) + (($totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao + $totalPagos)) * $comissaoAfiliacao) + $valorBonus20 + $valorBonus25, 2, ',', '.');
    $totalPagarSemFormat = (($valorTotalComissao) * ($comissaoEquipamento / 100)) + $totalPendenteAfiliacao + $valorBonus20Pendente + $valorBonus25Pendente - (($valorTotalPago) * ($comissaoEquipamento / 100));
    
    if ( $origem != 'CONTABIL')
        $html .= '<tr>
                <td colspan="4" align="right" class="corpoTabela">Total Geral:</td>
                <td colspan="3"
                    class="corpoTabela">R$ ' . number_format($valorTotal, 2, ',', '.') . '</td>';
    
    if ( $origem != 'CONTABIL')
      $html .= '<td></td>';
      $html .= '
          </tr>
            <tr>
                <td colspan="4" align="right" class="corpoTabela">Equipamentos Produtos:</td>
                <td colspan="3"
                    class="corpoTabela">R$ ' . number_format($valorTotalComissao, 2, ',', '.') . ' x ' . number_format($comissaoEquipamento, 2, '.', '') . '%</td>';
      
    if ( $origem != 'CONTABIL')
      $html .= '<td></td>';
      $html .= '
          </tr>
            <tr>
                <td colspan="4" align="right" class="corpoTabela" style="color: blue; font-weight: bold;">Pendentes à Pagar:</td>
                <td colspan="3"
                    class="corpoTabela" style="color: blue; font-weight: bold;">R$ ' . number_format((($valorTotalComissao - $valorTotalPago) * ($comissaoEquipamento / 100)), 2, ',', '.') . '</td>';
      
    if ( $origem != 'CONTABIL')
      $html .= '<td></td>';
    
    if ( $origem != 'CONTABIL')
      $html .= '
          </tr>
            <tr>
                <td colspan="4" align="right" class="corpoTabela" style="color: blue; font-weight: bold;">Total Repassados:</td>
                <td colspan="3"
                    class="corpoTabela" style="color: blue; font-weight: bold;">R$ ' . number_format((($valorTotalPago) * ($comissaoEquipamento / 100)), 2, ',', '.') . '</td>';
      
    if ( $origem != 'CONTABIL')
      $html .= '<td></td>';
      $html .= '
          </tr>
        </table>
            ';
    
    if ( $origem == 'CONTABIL'){

            $sqlParticipacao = "SELECT
                    aux.nome_consultor,
                    aux.qtd_visitas,
                    aux.resultado_visitou,
                    aux.resultado_demonstrou,
                    aux.resultado_levousuper,
                    aux.resultado_ligougerente,
                    aux.id_consultor,
                    aux.resultado_cartaovisita,
                    (
                       (aux.resultado_visitou * 1) +
                       (aux.resultado_demonstrou * 4) +
                       (aux.resultado_levousuper * 3) +
                       (aux.resultado_ligougerente * 1) +
                       (aux.resultado_cartaovisita * 1)
                    ) AS total,
                    aux.id_consultor AS id,
                    aux.total_visitas,
                    aux.total_cartoes

                FROM (

                    SELECT
                        ca.nome AS nome_consultor,
                        COUNT(cv.id) AS qtd_visitas,
                        SUM(IFNULL(cv.resultado_visitou,0)) AS resultado_visitou,
                        SUM(IFNULL(cv.resultado_demonstrou,0)) AS resultado_demonstrou,
                        SUM(IFNULL(cv.resultado_levousuper,0)) AS resultado_levousuper,
                        SUM(IFNULL(cv.resultado_ligougerente,0)) AS resultado_ligougerente,
                        SUM(IFNULL(cv.resultado_cartaovisita,0)) AS resultado_cartaovisita,
                        SUM(IFNULL(cv.qtd_cartoes,0)) AS total_cartoes,
                        ca.id AS id_consultor,
                        SUM(IF(cv.resultado_visitou != 0 AND cv.resultado_visitou IS NOT NULL,1,
                        IF(cv.resultado_demonstrou != 0 AND cv.resultado_demonstrou IS NOT NULL,1,
                        IF(cv.resultado_levousuper != 0 AND cv.resultado_levousuper IS NOT NULL,1,
                        IF(cv.resultado_cartaovisita != 0 AND cv.resultado_cartaovisita IS NOT NULL,1,
                        IF(cv.resultado_ligougerente != 0 AND cv.resultado_ligougerente IS NOT NULL,1,0)))))) AS total_visitas
                    FROM cs2.consultores_assistente ca
                    LEFT JOIN cs2.controle_comercial_visitas cv
                    ON cv.id_consultor = ca.id
                    LEFT JOIN cs2.funcionario AS f
                    ON f.id_consultor_assistente = ca.id
                    AND CONCAT(cv.data_agendamento) BETWEEN '$strDataInicio' AND '$strDataFim'
                    WHERE FIND_IN_SET(ca.id_franquia ,1)
                    AND f.id = '$idFuncionario'
                    GROUP BY ca.id
                    ORDER BY ca.nome) AS aux

                 ORDER BY total DESC, nome_consultor ASC;" ;

                 $resParticipacao = mysql_query($sqlParticipacao, $con);
                 $totalParticipacao = mysql_result($resParticipacao, 0 , 'total');
                 $resultado_visitou = mysql_result($resParticipacao, 0 , 'resultado_visitou');

                 $html .= '
                        <table width="100%" border="1" cellpadding="0" cellspacing="0" style="margin-top: 5px; font-family: arial, sans-serif; font-size: 12px;">
                           <tr>
                               <td colspan="2" class="titulo">Premiação Visita Remunerada</td>
                           </tr>
                           <tr>
                              <td align="right">Premiação Visita Remunerada &nbsp;</td>
                              <td width="20%">&nbsp;R$ '. number_format($totalParticipacao, 2, ',', '.').'</td>
                           </tr>
                        </table>';

        }
    
    
    // LANÇAMENTOS - DÉBITO e CRÉDITO
    
    $sqlDebCred = "
            SELECT
                valor,
                data_folha,
                tipo_lancamento,
                descricao,
                DATE_FORMAT(data_folha, '%d/%m/%Y') AS data_folha_label
            FROM cs2.lancamento_funcionario
            WHERE id_funcionario = '$idFuncionario'
            AND data_folha BETWEEN '$strDataInicio' AND '$strDataFim'
            ORDER BY tipo_lancamento ASC
        ";
    $resDebCred = mysql_query($sqlDebCred, $con);

    $html .= '<table width="100%" border="1" cellpadding="0" cellspacing="0" class="" style="margin-top: 5px;">
                    <tr>
                        <td colspan="5" class="titulo">Lançamentos</td>
                    </tr>
                    <tr>
                        <td class="subtitulo">Data/Folha</td>
                        <td class="subtitulo">Tipo Lançamento</td>
                        <td class="subtitulo">Descrição</td>
                        <td class="subtitulo">Valor</td>
                    </tr>';
                
    $valorTotalCredito = 0;
    $valorTotalDebito = 0;
    $countAdiantamento = 0;

    while ($arrDebCred = mysql_fetch_array($resDebCred)) {
        $countAdiantamento++;
        
        $debCred = $arrDebCred['tipo_lancamento'] == 'D' ? 'Débito' : 'Crédito';
        
        $html .= '<tr style="border: black; border-style: solid;">
                    <td class="corpoTabela">'. $arrDebCred['data_folha_label'].'</td>
                    <td class="corpoTabela">'. $debCred.'</td>
                    <td class="corpoTabela">'. $arrDebCred['descricao'].'</td>
                    <td class="corpoTabela">R$ ' . number_format($arrDebCred['valor'], 2, ',', '.').'</td>
                  </tr>';
        
        if($arrDebCred['tipo_lancamento'] == 'D'){
            $valorTotalDebito += $arrDebCred['valor'];
        }else if($arrDebCred['tipo_lancamento'] == 'C'){
            $valorTotalCredito += $arrDebCred['valor'];
        }
    }
    $html .='<tr>
                <td colspan="3" align="right" class="corpoTabela">Total Crédito:</td>
                <td colspan="1" class="corpoTabela">R$ ' . number_format($valorTotalCredito, 2, ',', '.').' </td>
              </tr>
              <tr>
                 <td colspan="3" align="right" class="corpoTabela" style="color: blue; font-weight: bold;">Total Débito:</td>
                 <td colspan="1" class="corpoTabela" style="color: blue; font-weight: bold;">R$ ' . number_format($valorTotalDebito, 2, ',', '.').' </td>
              </tr>
              <tr>
                 <td colspan="3" align="right" class="corpoTabela" style="color: blue; font-weight: bold;">Saldo Final(Crédito - Débito):</td>
                 <td colspan="1" class="corpoTabela" style="color: blue; font-weight: bold;">R$ ' . number_format($valorTotalCredito - $valorTotalDebito, 2, ',', '.'). ' </td>
              </tr>';
    
    $html .= '</table>';
    
    if ( $origem == 'CONTABIL'){
        $html .= '<div style="text-align: right;">
                     Sub Total à Repassar: R$ '.number_format( ($totalPagarSemFormat + $valorTotalCredito + $totalParticipacao) - $valorTotalDebito,2,',','.').'
                  </div>';
        
    }else{
        $html .= '<div style="text-align: right;">
                     Sub Total à Repassar: R$ '.number_format( ($totalPagarSemFormat + $valorTotalCredito) - $valorTotalDebito,2,',','.').'
                  </div>';
    }
    $html .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-top: 5px;">
                <tr>';
    
    if ( $origem == 'CONTABIL')
        $certifico = 'Certifico que foram realizadas as devidas conferências de todos o itens e encontram-se corretas.<br><br>';
    else
        $certifico = 'Certifico que foram realizadas as conferências.';

    if ( $origem == 'CONTABIL'){
        
        $totalcomissao        = ($totalPendenteAfiliacao);
        $totalPercentComissao = (($valorTotalComissao * $comissaoEquipamento)/100);
        $totalAdiantamento    = $valorTotalCredito - $valorTotalDebito;

        $html .= '<td style="font-size: 16px">'.$certifico.'</td>
                   </tr>
                   <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Curitiba - PR, ______/ ______ / ______</td>
                    </tr>
                    <tr>
                        <td width="30%"><br><br><br><br>______________________________________________________</td>
                    </tr>
                    <tr>
                        <td class="corpoTabela">'.$nome.'</td>
                    </tr>
                </table>
                <div style="page-break-before: always;"></div>
                <br>
                <br>
                    <table width="100%" border="1" cellpadding="0" cellspacing="0" style="margin-top: 5px;">
                        <thead>
                            <tr>
                                <td class="titulo" style="text-align: left !important;">' . $nome . '</td>
                                <td class="titulo" colspan="2" style="text-align: center !important;"> Período: '.$dataI.' à '.$dataF.'</td>
                            </tr>
                            <tr>
                                <td class="subtitulo">DESCRIÇÃO</td>
                                <td class="subtitulo">REFERÊNCIA</td>
                                <td class="subtitulo">VENCIMENTOS</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="corpoTabela">COMISSÃO AFILIAÇÕES (50% de R$ '.number_format($vr_pgto_adesao, 2, ',', '.').')</td>
                                <td class="corpoTabela">'.$qtd_afiliacao_contabil.'</td>
                                <td class="corpoTabela"> R$ ' .number_format($totalcomissao, 2, ',', '.'). '</td>
                            </tr>
                            <tr>
                                <td class="corpoTabela">COMISSÃO EQUIPAMENTOS ('. number_format($comissaoEquipamento, 2, '.', '').'% de R$ '.number_format($valorTotalComissao, 2, ',', '.').')</td>
                                <td class="corpoTabela">'.$totalEquipamentos.'</td>
                                <td class="corpoTabela"> R$ '.number_format($totalPercentComissao, 2, ',', '.'). '</td>
                            </tr>
                            <tr>
                                <td class="corpoTabela">VALE ADIANTAMENTO</td>
                                <td class="corpoTabela">'.$countAdiantamento.'</td>
                                <td class="corpoTabela"> R$ '.number_format($totalAdiantamento, 2, ',', '.').'</td>
                            </tr>
                            <tr>
                                <td class="corpoTabela">PREMIAÇÃO VISITA REMUNERADA</td>
                                <td class="corpoTabela">'.$resultado_visitou.'</td>
                                <td class="corpoTabela"> R$ '.number_format($totalParticipacao, 2, ',', '.').'</td>
                            </tr>
                            ';
                if ( $totalAfiliacoes >= 20 ){
                
                    $html .= '<tr>
                                 <td class="corpoTabela">PREMIAÇÃO ESPECIAL - 20 AFILIAÇÕES</td>
                                 <td class="corpoTabela">20</td>
                                 <td class="corpoTabela">R$ ' . number_format($valorBonus20, 2, ',', '.') . '</td>
                              </tr>';
                }
                
                if ( $totalAfiliacoes >= 25 ){
                    $html .= '<tr>
                                 <td class="corpoTabela">PREMIAÇÃO ESPECIAL - 25 AFILIAÇÕES</td>
                                 <td class="corpoTabela">25</td>
                                 <td class="corpoTabela">R$ ' . number_format($valorBonus25, 2, ',', '.') . '</td>
                              </tr>';
                }
                
                            
            $html .= '      <tr>
                                <td colspan="2">Sub Total à Repassar:</td>
                                <td class="corpoTabela"> R$ '.number_format(($totalcomissao + $totalPercentComissao + $totalParticipacao) + $totalAdiantamento  + $valorBonus20Pendente + $valorBonus25Pendente, 2, ',', '.').'</td>
                            </tr>
                        </tbody>
                    </table>

                <div style="page-break-before: always;"></div>
                <input type="hidden"  class="iptValorTotalSoma;noprint" value="'.$totalPagarSemFormat.'">';
    }
    echo $html;
}