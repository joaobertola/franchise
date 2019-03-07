<?php
/**
 * @file rel_comissao_func.php
 * @brief Arquivo Responsável pelo relatório de comissão
 * @author ARLLON DIAS
 * @date 06/01/2017
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

                <?php }

                $id_funcionario = $_POST['id_funcionario'];
                $id_franquia = $_POST['franqueado'];
                $strDataInicio = $_POST['data2I'];
                $strDataFim = $_POST['data2F'];
                $id_funcao = $_POST['iptFuncao'];

                $strDataInicio = substr($strDataInicio, 6, 4) . '-' . substr($strDataInicio, 3, 2) . '-' . substr($strDataInicio, 0, 2);
                $strDataFim = substr($strDataFim, 6, 4) . '-' . substr($strDataFim, 3, 2) . '-' . substr($strDataFim, 0, 2);

                $sqlAfiliacao = "SELECT
                                    c.codLoja,
                                    c.nomefantasia,
                                    f.id,
                                    f.nome,
                                    fun.descricao AS funcao,
                                    f.comissao_afiliacao,
                                    DATE_FORMAT(c.dt_cad, '%d/%m/%Y') AS data_afiliacao,
                                    c.sitcli,
                                    c.contadorsn,
                                    (SELECT login FROM base_web_control.webc_usuario WHERE id_cadastro = c.codLoja LIMIT 1) AS codigo,
                                    IF ( c.sitcli = 2,
                                          'Cancelado',
                                          IF ( c.contadorsn = 'S',
                                               'Contador',
                                               IF(c.dt_pgto_adesao IS NULL, 'Pendente Adesão', CONCAT(IF(c.sitcli = 0,IF(pendencia_contrato = 1 || pendencia_contratual = 1 , 'Pendente Ctr', IF((
                                               SELECT
                                                   caminho_imagem
                                               FROM base_inform.cadastro_imagem
                                               WHERE codloja = c.codLoja
                                               LIMIT 1
                                               ) IS NULL,'Pendente Scan', 'Ativo')),IF(c.sitcli = 1, 'Bloqueado', IF(c.sitcli = 2, 'Cancelado',IF(c.sitcli = 4,'Bloqueio Virtual', 'Ativo')))),IF(c.contadorsn = 'S', 'Contador', '')))
                                             )
                                       ) AS pendencia

                                FROM cs2.funcionario f
                                LEFT JOIN cs2.cadastro c
                                ON f.id_consultor_assistente = c.id_agendador
                                AND IF('$id_franquia' = 0, 0=0, c.id_franquia = '$id_franquia')
                                AND CONCAT(c.dt_cad) BETWEEN '$strDataInicio' AND '$strDataFim'
                                AND c.id_franquia = 1
                                LEFT JOIN cs2.funcao fun
                                ON fun.id = f.id_funcao
                                WHERE  f.ativo = '$ativo'
                                AND IF('$id_funcao' = 0, 0=0, f.id_funcao = '$id_funcao')
                                AND IF('$id_funcionario' = 0,0=0,f.id = '$id_funcionario')
                                ORDER BY f.nome ASC";

                $qryAfiliacao = mysql_query($sqlAfiliacao, $con);

                $strNomeFuncionario = '';
                $i = 0;
                $totalLinhas = mysql_num_rows($qryAfiliacao);
                $totalAfiliacoes = 0;
                $totalAtivos = 0;
                $totalPendentes = 0;
                $totalCancelados = 0;
                $totalContadores = 0;
                $totalPendentesAdesao = 0;
                $nome = '';
                $funcao = '';
                ?>
                <button type="button" id="btnImprimir" class="btnImprimir pull-right" style="margin-top: 15px;">Imprimir Relatório</button>
                <div class="imprimir">
                <?php
                while ($arrAfiliacao = mysql_fetch_array($qryAfiliacao)){

                if ($i == 0){

                $idFuncionario = $arrAfiliacao['id'];
                $comissaoAfiliacao = $arrAfiliacao['comissao_afiliacao'];

                ?>
                    <div class="page-break">
                    <table width='100%' border='0' cellpadding='0' cellspacing='0' style="margin-top: 5px;">
                        <tr>
                            <td align="left"  class="titulo" style="text-align: left !important;">Nome do Funcionário:&nbsp;&nbsp;<?php echo $arrAfiliacao['nome'] ?></td>
                            <td align="right"  class="titulo" style="text-align: right !important;">Função:&nbsp;&nbsp;<?php echo $arrAfiliacao['funcao'] ?></td>
                        </tr>
                    </table>
                    <table width="100%" border="1" cellpadding='0' cellspacing='0' style="margin-top: 5px;">
                    <tr>
                        <td colspan="5" class="titulo">Novas Afiliações</td>
                    </tr>
                    <tr>
                        <td width="5%" style="width: 5%;" class="subtitulo">Código</td>
                        <td width="35%" style="width: 35%;" class="subtitulo">Empresa</td>
                        <td width="10%" style="width: 10%;" class="subtitulo">Data Afiliação</td>
                        <td width="30%" style="width: 30%;" class="subtitulo">Funcionário</td>
                        <td width="20%" style="width: 20%;" class="subtitulo">Status</td>
                    </tr>

                <?php }elseif ($strNomeFuncionario != $arrAfiliacao['nome']){
                $valorBonus25 = 0.00;
                $valorBonus35 = 0.00;
                $valorBonus45 = 0.00;

                if($totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao) >= 25){
                    $valorBonus25 = 150.00;
                }

                if($totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao) >= 35){
                    $valorBonus35 = 200.00;
                }

                if($totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao) >= 45){
                    $valorBonus45 = 250.00;
                }

                    ?>
                    <tr>
                        <td colspan="5" class="corpoTabela">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="corpoTabela" colspan="5"><?php echo 'Afiliações: ' . $totalAfiliacoes . ' | Cancelados: '. $totalCancelados . ' | Pendentes: '. $totalPendentes . ' | Contadores: ' . $totalContadores . ' | Pendentes Adesão: ' . $totalPendentesAdesao?></td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right" class="corpoTabela">Premiação Afiliações</td>
                        <td class="corpoTabela"><?php echo $totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao) . ' x R$ ' . number_format($comissaoAfiliacao, 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right" class="corpoTabela">Bônus 25 Afiliações </td>
                        <td class="corpoTabela"><?php echo 'R$ ' . number_format($valorBonus25, 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right" class="corpoTabela">Bônus 35 Afiliações</td>
                        <td class="corpoTabela"><?php echo 'R$ ' . number_format($valorBonus35, 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right" class="corpoTabela">Bônus 45 Afiliações</td>
                        <td class="corpoTabela"><?php echo 'R$ ' . number_format($valorBonus45, 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right" class="corpoTabela">Total</td>
                        <td class="corpoTabela"><?php echo 'R$ ' . number_format((($totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao)) * $comissaoAfiliacao) + $valorBonus25 + $valorBonus35 + $valorBonus45, 2, ',', '.') ?></td>
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
                                        ORDER BY tipo_lancamento ASC";

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
                    <div class="page-break">
                        <table width='100%' border='0' cellpadding='0' cellspacing='0' style="margin-top: 5px;">
                            <tr>
                                <td align="left" class="titulo" style="text-align: left !important;">Nome do Funcionário:&nbsp;&nbsp;<?php echo $arrAfiliacao['nome'] ?></td>
                                <td align="right"  class="titulo" style="text-align: right !important;">Função:&nbsp;&nbsp;<?php echo $arrAfiliacao['funcao'] ?></td>
                            </tr>
                        </table>
                        <table width="100%" border="1" cellpadding='0' cellspacing='0' style="margin-top: 5px;">
                            <tr>
                                <td colspan="5" class="titulo">Novas Afiliações</td>
                            </tr>
                            <tr>
                                <td width="5%" style="width: 5%;" class="subtitulo">Código</td>
                                <td width="35%" style="width: 35%;" class="subtitulo">Empresa</td>
                                <td width="10%" style="width: 10%;" class="subtitulo">Data Afiliação</td>
                                <td width="30%" style="width: 30%;" class="subtitulo">Funcionário</td>
                                <td width="20%" style="width: 20%;" class="subtitulo">Status</td>
                            </tr>

                            <?php
                            $totalAfiliacoes = 0;
                            $totalAtivos = 0;
                            $totalPendentes = 0;
                            $totalCancelados = 0;
                            $totalContadores = 0;
                            $totalPendentesAdesao = 0;
                            $idFuncionario = $arrAfiliacao['id'];
                            $comissaoAfiliacao = $arrAfiliacao['comissao_afiliacao'];
                            }

                            $strNomeFuncionario = $arrAfiliacao['nome'];
                            $i++;

                            if($arrAfiliacao['codigo'] != ''){
                            ?>

                            <tr>
                                <td width="5%" class="corpoTabela"><?php echo $arrAfiliacao['codigo'] ?></td>
                                <td width="35%" class="corpoTabela"><?php echo $arrAfiliacao['nomefantasia'] ?></td>
                                <td width="10%" class="corpoTabela"><?php echo $arrAfiliacao['data_afiliacao'] ?></td>
                                <td width="30%" class="corpoTabela"><?php echo $arrAfiliacao['nome'] ?></td>
                                <td width="20%" class="corpoTabela"><?php echo $arrAfiliacao['pendencia'] ?></td>
                            </tr>

                            <?php }
                            if($arrAfiliacao['codigo'] != ''){
                            $totalAfiliacoes = $totalAfiliacoes + 1;


                            switch ($arrAfiliacao['pendencia']) {

                                case 'Ativo':

                                    $totalAtivos = $totalAtivos + 1;

                                    break;
                                case 'Pendente Ctr':

                                    $totalPendentes = $totalPendentes + 1;

                                    break;

                                case 'Pendente Scan':

                                    $totalPendentes = $totalPendentes + 1;

                                    break;

                                case 'Cancelado':

                                    $totalCancelados = $totalCancelados + 1;

                                    break;

                                case 'Pendente Adesão':

                                    $totalPendentesAdesao = $totalPendentesAdesao + 1;

                                    break;

                            }
                            }

                            if ($arrAfiliacao['contadorsn'] == 'S') {
                                $totalContadores = $totalContadores + 1;
                            }

                    $nome = $arrAfiliacao['nome'];
                    $funcao = $arrAfiliacao['funcao'];



                            if ($totalLinhas == $i && $id_funcionario == 0) {
                                $idFuncionario = $arrAfiliacao['id'];
                                $comissaoAfiliacao = $arrAfiliacao['comissao_afiliacao'];
                                $nomeFuncionario = $arrAfiliacao['nome'];
                                $funcao = $arrAfiliacao['funcao'];


                                $valorBonus25 = 0.00;
                                $valorBonus35 = 0.00;
                                $valorBonus45 = 0.00;
                                if($totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao) >= 25){
                                    $valorBonus25 = 150.00;
                                }

                                if($totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao) >= 35){
                                    $valorBonus35 = 200.00;
                                }

                                if($totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao) >= 45){
                                    $valorBonus45 = 250.00;
                                }

?>
                            <tr>
                                <td colspan="5" class="corpoTabela">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="corpoTabela" colspan="5"><?php echo 'Afiliações: ' . $totalAfiliacoes . ' | Cancelados: '. $totalCancelados . ' | Pendentes: '. $totalPendentes . ' | Contadores: ' . $totalContadores . ' | Pendentes Adesão: ' . $totalPendentesAdesao?></td>
                            </tr>
                            <!--                    <tr>-->
                            <!--                        <td colspan="4" align="right" class="corpoTabela">Cancelados</td>-->
                            <!--                        <td class="corpoTabela">--><?php //echo $totalCancelados ?><!--</td>-->
                            <!--                    </tr>-->
                            <!--                    <tr>-->
                            <!--                        <td colspan="4" align="right" class="corpoTabela">Pendentes</td>-->
                            <!--                        <td class="corpoTabela">--><?php //echo $totalPendentes ?><!--</td>-->
                            <!--                    </tr>-->
                            <!--                    <tr>-->
                            <!--                        <td colspan="4" align="right" class="corpoTabela">Contadores</td>-->
                            <!--                        <td class="corpoTabela">--><?php //echo $totalContadores ?><!--</td>-->
                            <!--                    </tr>-->
                            <tr>
                                <td colspan="4" align="right" class="corpoTabela">Premiação Afiliaçoes</td>
                                <td class="corpoTabela"><?php echo $totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao) . ' x R$ ' . number_format($comissaoAfiliacao, 2, ',', '.') ?></td>
                            </tr>
                             <tr>
                                <td colspan="4" align="right" class="corpoTabela">Bônus 25 Afiliações</td>
                                <td class="corpoTabela"><?php echo 'R$ ' . number_format($valorBonus25, 2, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right" class="corpoTabela">Bônus 35 Afiliações</td>
                                <td class="corpoTabela"><?php echo 'R$ ' . number_format($valorBonus35, 2, ',', '.') ?></td>
                            </tr>
                             <tr>
                        <td colspan="4" align="right" class="corpoTabela">Bônus 45 Afiliações</td>
                        <td class="corpoTabela"><?php echo 'R$ ' . number_format($valorBonus45, 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right" class="corpoTabela">Total</td>
                        <td class="corpoTabela"><?php echo 'R$ ' . number_format((($totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao)) * $comissaoAfiliacao) + $valorBonus25 + $valorBonus35 + $valorBonus45, 2, ',', '.') ?></td>
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
                                        ORDER BY tipo_lancamento ASC";

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
                            <?php }

                            }

                            if ($id_funcionario != 0){
                              $valorBonus25 = 0.00;
                              $valorBonus35 = 0.00;
                              $valorBonus45 = 0.00;

                              if($totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao) >= 25){
                                  $valorBonus25 = 150.00;
                              }

                              if($totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao) >= 35){
                                  $valorBonus35 = 200.00;
                              }

                              if($totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao) >= 45){
                                  $valorBonus45 = 250.00;
                              }
                        ?>


                           <tr>
                        <td colspan="5" class="corpoTabela">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="corpoTabela" colspan="5"><?php echo 'Afiliações: ' . $totalAfiliacoes . ' | Cancelados: '. $totalCancelados . ' | Pendentes: '. $totalPendentes . ' | Contadores: ' . $totalContadores . ' | Pendentes Adesão: ' . $totalPendentesAdesao?></td>
                    </tr>
<!--                    <tr>-->
<!--                        <td colspan="4" align="right" class="corpoTabela">Cancelados</td>-->
<!--                        <td class="corpoTabela">--><?php //echo $totalCancelados ?><!--</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td colspan="4" align="right" class="corpoTabela">Pendentes</td>-->
<!--                        <td class="corpoTabela">--><?php //echo $totalPendentes ?><!--</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td colspan="4" align="right" class="corpoTabela">Contadores</td>-->
<!--                        <td class="corpoTabela">--><?php //echo $totalContadores ?><!--</td>-->
<!--                    </tr>-->
                    <tr>
                        <td colspan="4" align="right" class="corpoTabela" style="color: blue; font-weight: bold;">premiação Afiliaçoes</td>
                        <td class="corpoTabela" style="color: blue; font-weight: bold;"><?php echo $totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao) . ' x R$ ' . number_format($comissaoAfiliacao, 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                                <td colspan="4" align="right" class="corpoTabela">Bônus 25 Afiliações</td>
                                <td class="corpoTabela"><?php echo 'R$ ' . number_format($valorBonus25, 2, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right" class="corpoTabela">Bônus 35 Afiliações</td>
                                <td class="corpoTabela"><?php echo 'R$ ' . number_format($valorBonus35, 2, ',', '.') ?></td>
                            </tr>
                             <tr>
                        <td colspan="4" align="right" class="corpoTabela">Bônus 45 Afiliações</td>
                        <td class="corpoTabela"><?php echo 'R$ ' . number_format($valorBonus45, 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right" class="corpoTabela">Total</td>
                        <td class="corpoTabela"><?php echo 'R$ ' . number_format((($totalAfiliacoes - ($totalCancelados + $totalPendentes + $totalContadores + $totalPendentesAdesao)) * $comissaoAfiliacao) + $valorBonus25 + $valorBonus35 + $valorBonus45, 2, ',', '.') ?></td>
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
                                        ORDER BY tipo_lancamento ASC";

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

                        <table width="100%" border='0' cellpadding='0' cellspacing='0' class=''
                               style="margin-top: 5px;">
                            <tr>
                                <td width="30%" class="corpoTabela">Certifico que foram realizadas as conferências de
                                    minhas premiações.
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