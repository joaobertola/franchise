<?php
/**
 * @file rel_comissao_func_mensal.php
 * @brief Arquivo Responsável pelo relatório de comissão
 * @author ARLLON DIAS
 * @date 07/03/2017
 * @version 1.0
 **/

if (empty($_POST['data2I']) || empty($_POST['data2F'])) { ?>

                    <script>
                        alert('Periodo Inicial e Final Obrigatório');
                        location.href = '../php/painel.php?pagina1=area_restrita/d_equipamentos_relatorio.php';
                    </script>

                <?php }elseif (empty($_POST['franqueado']) || $_POST['franqueado'] == 0){ ?>

                    <script>
                        alert('Você deve selecionar uma franquia!');
                        location.href = '../php/painel.php?pagina1=area_restrita/d_equipamentos_relatorio.php';
                    </script>

                <?php } ?>

    <button type="button" id="btnImprimir" class="btnImprimir pull-right" style="margin-top: 15px;">Imprimir Relatório</button>
    <div class="imprimir">

        <?php

                $id_funcionario = $_POST['id_funcionario'];
                $id_franquia = $_POST['franqueado'];
                $strDataInicio = $_POST['data2I'];
                $strDataFim = $_POST['data2F'];
                $id_funcao = $_POST['iptFuncao'];
                $ativo = $_POST['iptAtivo'];

                $strDataInicio = substr($strDataInicio, 6, 4) . '-' . substr($strDataInicio, 3, 2) . '-' . substr($strDataInicio, 0, 2);
                $strDataFim = substr($strDataFim, 6, 4) . '-' . substr($strDataFim, 3, 2) . '-' . substr($strDataFim, 0, 2);

                $sqlFunc = "SELECT
                                  f.id,
                                  f.nome,
                                  fun.descricao AS funcao
                            FROM cs2.funcionario f
                            INNER JOIN cs2.funcao fun
                            ON fun.id = f.id_funcao
                            WHERE IF('$id_funcionario' = 0, 0=0, f.id = '$id_funcionario')
                            AND f.ativo = '$ativo'
                            AND f.id_funcao = '$id_funcao'
                            ORDER BY f.nome ASC";


                $result = mysql_query($sqlFunc, $con);

                while($arrFuncionario = mysql_fetch_array($result)){

                $idFuncionario = $arrFuncionario['id'];

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
                    -- SUM(IFNULL(cv.resultado_demonstrou,0)) AS resultado_demonstrou,
                    SUM( if(cv.filhos_visitou = '1;1;1;1;1;1;1;1', 1 ,0)) AS resultado_demonstrou,
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
                    -- AND ca.situacao = 0
                    -- AND ca.tipo_cliente = 0
                    -- AND ca.tipo_funcionario = 'I'
                    AND f.id = '$idFuncionario'
                    GROUP BY ca.id
                    ORDER BY ca.nome) AS aux

                 ORDER BY total DESC, nome_consultor ASC;" ;


                // echo "<pre>";
                // print_r( $sqlParticipacao );

                 $resParticipacao = mysql_query($sqlParticipacao, $con);

                 @$totalParticipacao = mysql_result($resParticipacao, 0 , 'total');

                ?>
                    <div class="page-break">
                     <table width='100%' border='0' cellpadding='0' cellspacing='0' style="margin-top: 5px;">
                        <tr>
                            <td align="left"  class="titulo" style="text-align: left !important;">Nome do Funcionário:&nbsp;&nbsp;<?php echo $arrFuncionario['nome'] ?></td>
                            <td align="right"  class="titulo" style="text-align: right !important;">Função:&nbsp;&nbsp;<?php echo $arrFuncionario['funcao'] ?></td>
                        </tr>
                    </table>
                     <table width="100%" border="1" cellpadding='0' cellspacing='0' style="margin-top: 5px; font-family: arial, sans-serif; font-size: 12px;">
                     <tr>
                       <td align="right">Premiação Visita Remunerada &nbsp;</td>
                       <td width="20%">&nbsp;<?php echo 'R$ ' . number_format($totalParticipacao, 2, ',', '.') ?></td>
                    </tr>
                    </table>
                    <?php
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

                        ?>

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
                                    <td class="corpoTabela"><?php echo $arrDebCred['tipo_lancamento'] == 'D' ? 'Débito' : 'Crédito' ; ?></td>
                                    <td class="corpoTabela"><?php echo $arrDebCred['descricao'] ?></td>
                                    <td class="corpoTabela"><?php echo 'R$ ' . number_format($arrDebCred['valor'], 2, ',', '.') ?></td>
                                </tr>
                                <?php

                                if($arrDebCred['tipo_lancamento'] == 'D'){
                                    $valorTotalDebito += $arrDebCred['valor'];
                                }else if($arrDebCred['tipo_lancamento'] == 'C'){
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
                                <td colspan="3" align="right" class="corpoTabela" style="color: blue; font-weight: bold;">Total Débito:</td>
                                <td colspan="1"
                                    class="corpoTabela" style="color: blue; font-weight: bold;"><?php echo 'R$ ' . number_format($valorTotalDebito, 2, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" align="right" class="corpoTabela" style="color: blue; font-weight: bold;">Saldo Final(Crédito - Débito):</td>
                                <td colspan="1"
                                    class="corpoTabela" style="color: blue; font-weight: bold;"><?php echo 'R$ ' . number_format($valorTotalCredito - $valorTotalDebito, 2, ',', '.') ?></td>
                            </tr>
                        </table>
                        <table width="100%" border='0' cellpadding='0' cellspacing='0' style="margin-top: 5px;">
                            <tr>
                                <td width="30%" class="corpoTabela">Certifico que foram realizadas as conferências de minhas
                                    premiações.
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

                <?php } ?>
    </div>


