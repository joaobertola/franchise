<?php
/**
 * @file rel_assistente_automacao.php
 * @brief
 * @author ARLLON DIAS
 * @date 01/02/2017
 * @version 1.0
 **/

if (empty($_POST['data2I']) || empty($_POST['data2F'])) { ?>

    <script>
        alert('Periodo Inicial e Final Obrigatório');
        location.href = '../php/painel.php?pagina1=area_restrita/d_equipamentos_relatorio.php';
    </script>

<?php } elseif (empty($_POST['franqueado']) || $_POST['franqueado'] == 0) { ?>

    <script>
        alert('Você deve selecionar uma franquia!');
        location.href = '../php/painel.php?pagina1=area_restrita/d_equipamentos_relatorio.php';
    </script>

<?php }

$id_funcionario = $_POST['id_funcionario'];
$id_franquia = $_POST['franqueado'];
$strDataInicio = $_POST['data2I'];
$strDataFim = $_POST['data2F'];
$id_funcao = $_POST['iptFuncao'];

$strDataInicio = substr($strDataInicio, 6, 4) . '-' . substr($strDataInicio, 3, 2) . '-' . substr($strDataInicio, 0, 2);
$strDataFim = substr($strDataFim, 6, 4) . '-' . substr($strDataFim, 3, 2) . '-' . substr($strDataFim, 0, 2);

$sqlFunc = "
              SELECT
                    f.id,
                    f.nome,
                    fun.descricao as funcao
              FROM cs2.funcionario f
              INNER JOIN cs2.funcao fun
              ON fun.id = f.id_funcao
              WHERE fun.id = '$id_funcao'
              AND IF('$id_funcionario' = 0, 0=0, f.id = '$id_funcionario')";

$resFunc = mysql_query($sqlFunc,$con);



?>
<button type="button" id="btnImprimir" class="btnImprimir pull-right" style="margin-top: 15px;">Imprimir Relatório
</button>
<div class="imprimir">
    <?php while($arrFunc = mysql_fetch_array($resFunc)) { ?>

        <div class="page-break">
            <table width='100%' border='0' cellpadding='0' cellspacing='0' style="margin-top: 5px;">
                <tr>
                    <td align="left"  class="titulo" style="text-align: left !important;">Nome do Funcionário:&nbsp;&nbsp;<?php echo $arrFunc['nome'] ?></td>
                    <td align="right"  class="titulo" style="text-align: right !important;">Função:&nbsp;&nbsp;<?php echo $arrFunc['funcao'] ?></td>
                </tr>
            </table>
            <?php

            $idFuncionario = $arrFunc['id'];

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
                                         AND (p.id != '7510103' and p.id != '7788520'
                      )
                                        AND f.id = '$idFuncionario'
                                        AND c.id_franquia = 1
                                        AND ce.codLoja != 23096
                                        GROUP BY ce.id
                                        ORDER BY ce.id ASC;";
            //                    if($idFuncionario == 263){
            //                        echo '<pre>';
            //                        echo $sqlEquipamentos;
            //                        die;
            //                    }
            $qryEquipamentos = mysql_query($sqlEquipamentos, $con);


            ?>

            <table width='100%' border='1' cellpadding='0' cellspacing='0' class='' style="margin-top: 5px;">
                <tr>
                    <td colspan="6" class="titulo">Equipamentos e Suprimentos</td>
                </tr>
                <tr>
                    <td class="subtitulo">Data</td>
                    <td class="subtitulo">Código</td>
                    <td class="subtitulo">Empresa</td>
                    <td class="subtitulo">Produtos</td>
                    <td class="subtitulo">Valor Venda</td>
                    <td class="subtitulo">Status</td>
                </tr>

                <?php
                $totalEquipamentos = mysql_num_rows($qryEquipamentos);
                $valorTotal = 0;
                $comissaoEquipamento = 0;
                $valorTotalComissao = 0;
                while ($arrEquipamentos = mysql_fetch_array($qryEquipamentos)) {

                    $status = 'Pendente Pagamento';
                    if($arrEquipamentos['pendente'] == 0){
                        $status = 'OK';
                        $valorTotalComissao = $valorTotalComissao + $arrEquipamentos['valor_total'];
                    }?>
                    <tr style="border: black; border-style: solid;">
                        <td class="corpoTabela"><?php echo $arrEquipamentos['data_venda_label'] ?></td>
                        <td class="corpoTabela"><?php echo $arrEquipamentos['codigo'] ?></td>
                        <td class="corpoTabela"><?php echo $arrEquipamentos['nomefantasia'] ?></td>
                        <td class="corpoTabela"><?php echo $arrEquipamentos['descricao'] ?></td>
                        <td class="corpoTabela"><?php echo 'R$ ' . number_format($arrEquipamentos['valor_total'], 2, ',', '.') ?></td>
                        <td class="corpoTabela"><?php echo $status ?></td>
                    </tr>
                    <?php $valorTotal = $valorTotal + $arrEquipamentos['valor_total'];
                    $comissaoEquipamento = $arrEquipamentos['comissao_equipamento'];
                }
                ?>
                <tr>
                    <td colspan="4" align="right" class="corpoTabela">Total Geral:</td>
                    <td colspan="1"
                        class="corpoTabela"><?php echo 'R$ ' . number_format($valorTotal, 2, ',', '.') ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4" align="right" class="corpoTabela">Premiações Equipamentos Produtos:</td>
                    <td colspan="1"
                        class="corpoTabela"><?php echo 'R$ ' . number_format($valorTotalComissao, 2, ',', '.') . ' x ' . number_format($comissaoEquipamento, 2, '.', '') . '%' ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4" align="right" class="corpoTabela">Total:</td>
                    <td colspan="1"
                        class="corpoTabela"><?php echo 'R$ ' . number_format(($valorTotalComissao * ($comissaoEquipamento / 100)), 2, ',', '.') ?></td>
                    <td></td>
                </tr>
            </table>

            <?php

            $idFuncionario = $arrFunc['id'];
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
                                        ORDER BY tipo_lancamento ASC";
            //    echo $sqlDebCred;
            $resDebCred = mysql_query($sqlDebCred, $con); ?>

            <table width='100%' border='1' cellpadding='0' cellspacing='0' class=''
                   style="margin-top: 5px;">
                <tr>
                    <td colspan="5" class="titulo">Lançamentos</td>
                </tr>
                <tr>
                    <td class="subtitulo">Data/Folha</td>
                    <td class="subtitulo">Tipo Lançamento</td>
                    <td class="subtitulo">Descrição</td>
                    <td class="subtitulo">Valor</td>
                </tr>

                <?php
                $valorTotalCredito = 0;
                $valorTotalDebito = 0;
                while ($arrDebCred = mysql_fetch_array($resDebCred)) { ?>
                    <tr style="border: black; border-style: solid;">
                        <td class="corpoTabela"><?php echo $arrDebCred['data_folha_label'] ?></td>
                        <td class="corpoTabela"><?php echo $arrDebCred['tipo_lancamento'] == 'D' ? 'Débito' : 'Crédito'; ?></td>
                        <td class="corpoTabela"><?php echo $arrDebCred['descricao'] ?></td>
                        <td class="corpoTabela"><?php echo 'R$ ' . number_format($arrDebCred['valor'], 2, ',', '.') ?></td>
                    </tr>
                    <?php

                    if ($arrDebCred['tipo_lancamento'] == 'D') {
                        $valorTotalDebito += $arrDebCred['valor'];
                    } else if ($arrDebCred['tipo_lancamento'] == 'C') {
                        $valorTotalCredito += $arrDebCred['valor'];
                    }
                }
                ?>
                <tr>
                    <td colspan="3" align="right" class="corpoTabela">Total Crédito:</td>
                    <td colspan="1"
                        class="corpoTabela"><?php echo 'R$ ' . number_format($valorTotalCredito, 2, ',', '.') ?></td>
                </tr>
                <tr>
                    <td colspan="3" align="right" class="corpoTabela" style="color: blue; font-weight: bold;">Total
                        Débito:
                    </td>
                    <td colspan="1"
                        class="corpoTabela"
                        style="color: blue; font-weight: bold;"><?php echo 'R$ ' . number_format($valorTotalDebito, 2, ',', '.') ?></td>
                </tr>
                <tr>
                    <td colspan="3" align="right" class="corpoTabela" style="color: blue; font-weight: bold;">Saldo
                        Final(Crédito - Débito):
                    </td>
                    <td colspan="1"
                        class="corpoTabela"
                        style="color: blue; font-weight: bold;"><?php echo 'R$ ' . number_format($valorTotalCredito - $valorTotalDebito, 2, ',', '.') ?></td>
                </tr>
            </table>

            <table width="100%" border='0' cellpadding='0' cellspacing='0' style="margin-top: 5px;">
                <tr>
                    <td width="30%" class="corpoTabela">Certifico que foram realizadas as conferências de minhas
                        Premiações.
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                    <td width="30%" class="">______________________________________________________</td>
                    <td></td>
                </tr>
                <tr>
                    <td width="30%" align="center" class="corpoTabela">Nome do Funcionário</td>
                    <td></td>
                </tr>

            </table>
        </div>



    <?php }?>
</div>
