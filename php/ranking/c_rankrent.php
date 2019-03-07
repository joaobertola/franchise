<?php
require "connect/sessao.php";

$classificacao = $_SESSION['ss_classificacao'];

$mes_atual = date('m');
$ano_atual = date('y');
?>
<body>
<form method="post" action="painel.php?pagina1=ranking/c_rankrent1.php">
    <table width="70%" border="0" align="center">
        <tr>
            <td colspan="2" class="titulo">RANKING DE VENDAS </td>
        </tr>
        <tr>
            <td width="38%" class="subtitulodireita">&nbsp;</td>
            <td width="62%" class="campoesquerda">&nbsp;</td>
        </tr>
        <tr>
            <td class="subtitulodireita">Ano</td>
            <td class="campoesquerda"><select name="ano" size="1" class="formulariopequeno" >
                    <?php
                      $ano = date('Y');
                      $anox = date('y');
                      echo "[$ano] [$anox]";
                      for ( $i = 1; $i <=10 ; $i++ ){
                          echo "<option value='$ano' ";
                          if ($ano_atual == $anox){ echo "selected"; }
                          echo "> $ano </option>";
                          $ano--;
                          $anox--;                          
                      }
                      ?>
                </select></td>
        </tr>
        <tr>
            <td class="subtitulodireita">M&ecirc;s</td>
            <td class="campoesquerda"><select name="mes" size="1">
                    <option value="01" <?php if ($mes_atual == "01"){ echo "selected"; }?>>Janeiro</option>
                    <option value="02" <?php if ($mes_atual == "02"){ echo "selected"; }?>>Fevereiro</option>
                    <option value="03" <?php if ($mes_atual == "03"){ echo "selected"; }?>>Mar&ccedil;o</option>
                    <option value="04" <?php if ($mes_atual == "04"){ echo "selected"; }?>>Abril</option>
                    <option value="05" <?php if ($mes_atual == "05"){ echo "selected"; }?>>Maio</option>
                    <option value="06" <?php if ($mes_atual == "06"){ echo "selected"; }?>>Junho</option>
                    <option value="07" <?php if ($mes_atual == "07"){ echo "selected"; }?>>Julho</option>
                    <option value="08" <?php if ($mes_atual == "08"){ echo "selected"; }?>>Agosto</option>
                    <option value="09" <?php if ($mes_atual == "09"){ echo "selected"; }?>>Setembro</option>
                    <option value="10" <?php if ($mes_atual == "10"){ echo "selected"; }?>>Outubro</option>
                    <option value="11" <?php if ($mes_atual == "11"){ echo "selected"; }?>>Novembro</option>
                    <option value="12" <?php if ($mes_atual == "12"){ echo "selected"; }?>>Dezembro</option>
                </select></td>
        </tr>
        <tr>
            <td class="subtitulodireita">
            </td>
            <td class="campoesquerda"><?php echo $nome_franquia; ?></td>
        </tr>
        <tr>
            <td colspan="2" class="titulo">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="center"><input type="submit" name="pesq1" value="    Pesquisar    " />
                <input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " /></td>
        </tr>
    </table>
</form>
</body>