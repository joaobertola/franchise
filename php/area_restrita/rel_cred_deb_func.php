<?php
/**
 * @file rel_cred_deb_func.php
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
$ativo = $_POST['iptAtivo'];

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
              AND f.ativo = '$ativo'
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



<?php }?>
</div>
