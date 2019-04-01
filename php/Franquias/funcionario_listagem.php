<?php
require("connect/sessao_r.php");

$sql = "
SELECT
  f.vt, CONCAT(f.banco, ' / ', f.agencia,  ' / ', f.conta) AS conta, f.salario, 
  f.id, f.nome, f.cpf, date_format(f.data_admissao, '%d/%m/%Y') AS data_admissao, f.adiantamento,
  CASE f.ativo
		WHEN 'S' THEN '<font color=green>Sim</font>'
		WHEN 'N' THEN '<font color=red>N&atilde;o</font>'
	END AS ativo,
  f.rg,
  e.nome_empresa
  
FROM
  funcionario f
  INNER JOIN cs2.empregador e ON e.id = f.id_empregador
WHERE ativo = '{$_REQUEST['lista_ativo']}' 
ORDER BY f.nome ASC";
$qry = mysql_query($sql, $con);
$total = mysql_num_rows($qry);

if ($_REQUEST['lista_ativo'] == 'S') {
    $situacao = "N";
    $descricao = "Lista os Inativo(s)";
} else {
    $situacao = "S";
    $descricao = "Lista os Ativo(s)";
}

function mascaraCpfFuncionario($p_cpf_banco)
{
    $a = substr($p_cpf_banco, 0, 3);
    $b = substr($p_cpf_banco, 3, 3);
    $c = substr($p_cpf_banco, 6, 3);
    $d = substr($p_cpf_banco, 9, 2);
    $cpf_view .= $a;
    $cpf_view .= ".";
    $cpf_view .= $b;
    $cpf_view .= ".";
    $cpf_view .= $c;
    $cpf_view .= "-";
    $cpf_view .= $d;
    return ($cpf_view);
}

?>
<link rel="stylesheet" href="../css/assets/css/bootstrap.min.css">
<!---->
<!-- Optional theme -->
<link rel="stylesheet" href="../css/assets/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="../css/assets/js/bootstrap.min.js"></script>
<style>
    .link_simples:link {
        text-decoration: none;
        color: blue;
    }

    .link_simples:visited {
        text-decoration: none;
        color: blue;
    }

    .link_simples:hover {
        text-decoration: none;
        color: blue;
    }

    .link_simples:active {
        text-decoration: none;
        color: blue;
    }
</style>
<ul style="margin-left: 50px;">
    <a href="painel.php?pagina1=Franquias/funcionario_novo.php"><b><font color="#FF6600">Novo
                Cadastro</b></font></a>
    <span>|</span>
    <a href="painel.php?pagina1=Franquias/cadastrofuncoes_view.php"><b><font color="#FF6600">Cadastrar Funções</b></font></a>
    <span>|</span>
    <a
        href="painel.php?pagina1=Franquias/funcionario_listagem.php&lista_ativo=<?= $situacao ?>"><b><font
                color="#FF6600"><?= $descricao ?></font></b></a>
</ul>
<form name="form" method="post" action="Franquias/funcionario_atualiza_dados.php">
    <table border="0" width="90%" align="center" cellpadding="0" cellspacing="1" class="bodyText">

        <tr class="titulo">
            <td align="left">Nome</td>
            <td align="left">RG</td>
            <td align="left">Empresa</td>
            <td width="30%" align="left">Banco / Agencia / Conta</td>
            <td width="13%" align="left">Salario</td>
            <td width="13%" align="left">VT + VR</td>
            <td width="13%" align="left">Adiantamento</td>
            <td width="13%" align="center">Ativo</td>
        </tr>
        <?php
        $cont = 1;
        $a_cor = array("#FFFFFF", "#E5E5E5");
        while ($rs = mysql_fetch_array($qry)) {
            $cont++;
            $cor = $a_cor[$cont % 2];
            $salario = $rs['salario'];
            $salario = number_format($salario, 2, ',', '.');
            $id = $rs['id'];

            $vt = $rs['vt'];
            $vt = number_format($vt, 2, ',', '.');

            $adi = $rs['adiantamento'];
            $adi = number_format($adi, 2, ',', '.');

            ?>
            <tr class="tabela" align="left" bgcolor="<?= $cor ?>">
                <td width="45%"><a href="painel.php?pagina1=Franquias/funcionario_alterar.php&id=<?= $rs['id'] ?>"
                                   class="link_simples"><?= $rs['nome'] ?></a></td>
                <td width="10%"><?= $rs['rg'] ?></td>
                <td width="10%"><?= $rs['nome_empresa'] ?></td>
                <td width="35%"><?= $rs['conta'] ?></td>
                <td width="5%">
                    <input type="hidden" name="func[]" value="<?= $id ?>"/>
                    <input type="text" name="salario[]" value="<?= $salario ?>"
                           onKeydown="FormataValor(this,20,event,2)" style="text-align:right" size=10/>
                </td>
                <td width="5%">
                    <input type="text" name="vt[]" value="<?= $vt ?>" onKeydown="FormataValor(this,20,event,2)"
                           style="text-align:right" size=10/>
                </td>
                <td width="5%">
                    <input type="text" name="adianta[]" value="<?= $adi ?>" onKeydown="FormataValor(this,20,event,2)"
                           style="text-align:right" size=10/>
                </td>
                <td align="center" width="5%"><?= $rs['ativo'] ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="5" align="right" height="30">Total de <b><?= $total ?></b> Registro(s) Encontrado(s)</td>
        </tr>
        <tr>
            <td colspan="5" align="center" height="30">
                <input type="submit" name="grava" value="    Gravar Altera&ccedil;&otilde;es    "/>
            </td>
        </tr>
    </table>
</form>