<?php if (empty($_POST['dataInicial']) || empty($_POST['dataFinal'])) : ?>
    <script>
        alert('Periodo Inicial e Final Obrigatório');
        location.href = '../php/painel.php?pagina1=area_restrita/rel_brincadeira.php';
    </script>
<?php elseif (empty($_POST['franquiaPontuacao']) || $_POST['franquiaPontuacao'] == 0) : ?>
    <script>
        alert('Você deve selecionar uma franquia!');
        location.href = '../php/painel.php?pagina1=area_restrita/rel_brincadeira.php';
    </script>
<?php endif; ?>

<?php

function cmp($a, $b)
{
    if ($a["pontuacao"] != $b["pontuacao"]) {
        return $a["pontuacao"] < $b["pontuacao"];
    } else {
        return $a["data_venda"] > $b["data_venda"];
    }
}

$id_funcionario = $_POST['id_funcionario'];
$id_franquia    = $_POST['franquiaPontuacao'];
$strDataInicio  = $_POST['dataInicial'];
$strDataFim     = $_POST['dataFinal'];
$ativo          = $_POST['iptAtivo'];
$ranking        = [];

/** ============================ TABELA INICIAL DE PONTOS ============================
 *     MÓDULO, PRODUTO OU SERVIÇO            |            QTDE DE PONTOS
 *  ==================================================================================
 *  Contrato com Módulo de Loja Virtual      |               2 PONTOS
 *  Serviço de Montagem de Loja Virtual      |               3 PONTOS
 *  Contrato PREMIUM (MENSALIDADE > 89,90)   |               1 PONTO
 *  Contrato PLATINUM (MENSALIDADE > 99,90)  |               2 PONTOS
 */

$pontosModuloLV         = 2;
$pontosMontagemLV       = 3;
$pontosContratoPremium  = 1;
$pontosContratoPlatinum = 2;

/** ========================== TABELA DE PONTOS DO RANKING ===========================
 *     MÓDULO, PRODUTO OU SERVIÇO            |            QTDE DE PONTOS
 *  ==================================================================================
 *  Qualquer Contrato                        |               1 PONTO
 *  Pedido de Equipamento                    |               0,5 PONTO
 *  Montagem de Loja Virtual                 |               0,5 PONTO
 */

$pesoContrato          = 1;
$pesoPedidoEquipamento = 0.5;
$pesoMontagemLV        = 0.5;


$strDataInicio = substr($strDataInicio, 6, 4) . '-' . substr($strDataInicio, 3, 2) . '-' . substr($strDataInicio, 0, 2);
$strDataFim = substr($strDataFim, 6, 4) . '-' . substr($strDataFim, 3, 2) . '-' . substr($strDataFim, 0, 2);

$qryFuncionario = "SELECT f.id, f.id_consultor_assistente, f.nome AS nome, fun.descricao
FROM cs2.funcionario f
INNER JOIN cs2.funcao fun ON fun.id = f.id_funcao
WHERE IF('$id_funcionario' = '0', 0 = 0, f.id = '$id_funcionario') AND id_funcao = '9' AND f.ativo = 'S'
ORDER BY f.nome ASC";

$rstFuncionario = mysql_query($qryFuncionario, $con);

while ($arrFuncionario = mysql_fetch_array($rstFuncionario)) {

    $id_funcionario = $arrFuncionario['id'];
    $id_consultor   = $arrFuncionario['id_consultor_assistente'];
    $nome           = $arrFuncionario['nome'];

    // Verifico o número de contratos com módulo de loja virtual 
    $sqlModuloLV = "SELECT COUNT(c.codloja) AS modulo_loja_virtual
    FROM cs2.cadastro c
    WHERE c.sitcli = 0 
    AND c.dt_cad BETWEEN '$strDataInicio'
    AND '$strDataFim'
    AND c.contadorSN = 'N'
    AND c.modulo_loja_virtual IS NOT NULL 
    AND c.id_consultor = $id_consultor";
    $resultModuloLV = mysql_query($sqlModuloLV, $con);
    while ($rml = mysql_fetch_assoc($resultModuloLV)) {
        $numeroModulosLV = $rml['modulo_loja_virtual'];
    }

    // Verifico o número de contratos PREMIUM (MAIORES QUE 89,90)
    $sqlPremium = "SELECT COUNT(c.codloja) AS contratos_premium
    FROM cs2.cadastro c
    WHERE c.sitcli = 0 
    AND c.dt_cad BETWEEN '$strDataInicio'
    AND '$strDataFim'
    AND c.contadorSN = 'N' 
    AND c.tx_mens BETWEEN '89.90' AND '99.90' 
    AND c.id_consultor = $id_consultor";
    $resultPremium = mysql_query($sqlPremium, $con);
    while ($rp = mysql_fetch_assoc($resultPremium)) {
        $numeroPremium = $rp['contratos_premium'];
    }

    // Verifico o número de contratos PLATINUM (MAIORES QUE 99,90)
    $sqlPlatinum = "SELECT COUNT(c.codloja) AS contratos_platinum
    FROM cs2.cadastro c
    WHERE c.sitcli = 0 AND c.dt_cad BETWEEN '$strDataInicio'
    AND '$strDataFim'
    AND c.contadorSN = 'N' 
    AND c.tx_mens > '99.90' 
    AND c.id_consultor = $id_consultor";
    $resultPlatinum = mysql_query($sqlPlatinum, $con);
    while ($rpm = mysql_fetch_assoc($resultPlatinum)) {
        $numeroPlatinum = $rpm['contratos_platinum'];
    }

    // Verifico os Contratos independente do valor 
    $sqlContratos = "SELECT c.dt_cad, c.codloja, c.razaosoc, c.tx_mens, c.modulo_loja_virtual
    FROM cs2.cadastro c
    WHERE c.sitcli = 0 
    AND c.dt_cad BETWEEN '$strDataInicio'
    AND '$strDataFim'
    AND c.contadorSN = 'N'
    AND c.id_consultor = $id_consultor";
    $resultContratos = mysql_query($sqlContratos, $con);

    // Verifico os Equipamentos 
    $sqlEquipamentos = "SELECT ce.data_venda, ce.codloja, c.razaosoc, p.descricao
    FROM cs2.cadastro_equipamento ce
    INNER JOIN cs2.cadastro_equipamento_descricao ced ON ced.id_cadastro_equipamento = ce.id
    INNER JOIN cs2.cadastro c ON ce.codloja = c.codloja
    LEFT JOIN base_web_control.produto p ON p.id_cadastro = 62735 AND (ced.codigo_barra = p.codigo_barra OR ced.codigo_barra = p.identificacao_interna)
    WHERE ce.data_venda BETWEEN '$strDataInicio' 
    AND '$strDataFim '
    AND ced.codigo_barra NOT IN ('147258','016','7896586816760','23','07','7898938113328','7899718701513','015','7899018412546','2993500215204','35655138', '7896586816769', '7510103') 
    AND ce.venda_finalizada = 'S' 
    AND ce.id_consultor = $id_funcionario
    GROUP BY ce.id";
    $resultEquipamentos = mysql_query($sqlEquipamentos, $con);

    // Verifico as Montagens 
    $sqlMontagens = "SELECT ce.data_venda, ce.codloja, c.razaosoc, p.descricao
    FROM cs2.cadastro_equipamento ce
    INNER JOIN cs2.cadastro_equipamento_descricao ced ON ced.id_cadastro_equipamento = ce.id
    INNER JOIN cs2.cadastro c ON ce.codloja = c.codloja
    LEFT JOIN base_web_control.produto p ON p.id_cadastro = 62735 AND (ced.codigo_barra = p.codigo_barra OR ced.codigo_barra = p.identificacao_interna)
    WHERE ce.data_venda BETWEEN '$strDataInicio'
    AND '$strDataFim'
    AND ced.codigo_barra = '147258'
    AND ce.venda_finalizada = 'S'
    AND ce.id_consultor = $id_funcionario";
    $resultMontagens = mysql_query($sqlMontagens, $con);

    $numeroContratos    = mysql_num_rows($resultContratos);
    $numeroEquipamentos = mysql_num_rows($resultEquipamentos);
    $numeroMontagens    = mysql_num_rows($resultMontagens);

    $pontosValidacao = ($numeroModulosLV * $pontosModuloLV) + ($numeroMontagens * $pontosMontagemLV) + ($numeroPremium * $pontosContratoPremium) + ($numeroPlatinum * $pontosContratoPlatinum);

    $pontos = ($numeroContratos * $pesoContrato) + ($numeroEquipamentos * $pesoPedidoEquipamento) + ($numeroMontagens * $pesoMontagemLV);

    $ranking[$id_funcionario]['nome']             = $nome;
    $ranking[$id_funcionario]['contratos']        = $resultContratos;
    $ranking[$id_funcionario]['equipamentos']     = $resultEquipamentos;
    $ranking[$id_funcionario]['montagens']        = $resultMontagens;
    $ranking[$id_funcionario]['pontos_validacao'] = $pontosValidacao;
    $ranking[$id_funcionario]['pontuacao']        = $pontos;
}

usort($ranking, 'cmp');

?>
<button type="button" id="btnImprimir" class="btnImprimir pull-right" style="position: relative;margin-bottom: 15px;float: none !important;">Imprimir Relatório</button>
<div class="imprimir">
    <?php foreach ($ranking as $id => $r) : ?>
        <div class="titulo" style="position: relative;">
            <span style="width: 50%;position: absolute;left:0"><?= $r['nome'] . ' - PRÉ ' . $r['pontos_validacao'] . ' PONTOS | RANKING ' . $r['pontuacao'] . ' PONTOS'; ?></span>
            <span style="width: 50%;position: absolute;right:0"><?= date('d/m/Y', strtotime($strDataInicio)) . ' à ' . date('d/m/Y', strtotime($strDataFim)); ?></span>
        </div>
        <!-- Contratos  -->
        <table width="100%" border="1" cellpadding="0" cellspacing="0" style="margin-top: 5px;margin-bottom:20px">
            <thead>
                <tr>
                    <th class="subtitulo">Data Cadastro</th>
                    <th class="subtitulo">Código</th>
                    <th class="subtitulo">Empresa</th>
                    <th class="subtitulo">Tipo Contrato</th>
                    <th class="subtitulo">Módulo L.V.</th>
                    <th class="subtitulo">Pontuação</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($rc = mysql_fetch_assoc($r['contratos'])) : ?>
                    <tr>
                        <td class="corpoTabela"><?= date('d/m/Y', strtotime($rc['dt_cad'])); ?></td>
                        <td class="corpoTabela"><?= $rc['codloja']; ?></td>
                        <td class="corpoTabela"><?= $rc['razaosoc']; ?></td>
                        <?php
                        $tipoContrato = 'Normal';
                        if ($rc['tx_mens'] >= 89.90 && $rc['tx_mens'] < 99.90) {
                            $tipoContrato = "Premium";
                        } else if ($rc['tx_mens'] >= 99.90) {
                            $tipoContrato = "Platinum";
                        }
                        ?>
                        <td class="corpoTabela"><?= $tipoContrato; ?></td>
                        <td class="corpoTabela"><?= !empty($rc['modulo_loja_virtual']) ? 'R$ ' . str_replace(',', '.', $rc['modulo_loja_virtual']) : '--'; ?></td>
                        <td class="corpoTabela"><?= 1 * $pesoContrato; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- Equipamentos  -->
        <table width="100%" border="1" cellpadding="0" cellspacing="0" style="margin-bottom: 25px;">
            <thead>
                <tr>
                    <th class="subtitulo">Data Venda</th>
                    <th class="subtitulo">Pedido</th>
                    <th class="subtitulo">Empresa</th>
                    <th class="subtitulo">Produto</th>
                    <th class="subtitulo">Pontuação</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($re = mysql_fetch_assoc($r['equipamentos'])) : ?>
                    <tr>
                        <td class="corpoTabela"><?= date('d/m/Y', strtotime($re['data_venda'])); ?></td>
                        <td class="corpoTabela"><?= $re['codloja']; ?></td>
                        <td class="corpoTabela"><?= $re['razaosoc']; ?></td>
                        <td class="corpoTabela"><?= $re['descricao']; ?></td>
                        <td class="corpoTabela"><?= 1 * $pesoPedidoEquipamento; ?></td>
                    </tr>
                <?php endwhile; ?>
                <?php while ($rm = mysql_fetch_assoc($r['montagens'])) : ?>
                    <tr>
                        <td class="corpoTabela"><?= date('d/m/Y', strtotime($rm['data_venda'])); ?></td>
                        <td class="corpoTabela"><?= $rm['codloja']; ?></td>
                        <td class="corpoTabela"><?= $rm['razaosoc']; ?></td>
                        <td class="corpoTabela"><?= $rm['descricao']; ?></td>
                        <td class="corpoTabela"><?= 1 * $pesoMontagemLV; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
    <div class="page-break exibirTotal hidden">

    </div>
</div>