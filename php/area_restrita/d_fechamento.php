<?php
require "connect/sessao_r.php";

$a_partir = date("m") - 3;
//$a_partir = 2 - 3;

if ($a_partir < 1) {
    $a_partir = $a_partir * -1;
    $aa_partir = 12 - $a_partir;
} else {
    $aa_partir = $a_partir;
}

function mesExtensoFechamento($p_numero_mes) {
    if ($p_numero_mes == 1)
        $mes = "Janeiro";
    elseif ($p_numero_mes == 2)
        $mes = "Fevereiro";
    elseif ($p_numero_mes == 3)
        $mes = "Março";
    elseif ($p_numero_mes == 4)
        $mes = "Abril";
    elseif ($p_numero_mes == 5)
        $mes = "Maio";
    elseif ($p_numero_mes == 6)
        $mes = "Junho";
    elseif ($p_numero_mes == 7)
        $mes = "Julho";
    elseif ($p_numero_mes == 8)
        $mes = "Agosto";
    elseif ($p_numero_mes == 9)
        $mes = "Setembro";
    elseif ($p_numero_mes == 10)
        $mes = "Outubro";
    elseif ($p_numero_mes == 11)
        $mes = "Novembro";
    elseif ($p_numero_mes == 12)
        $mes = "Dezembro";
    return $mes;
}

function dateDiff2($sDataInicial, $sDataFinal) {
    $sDataI = explode("-", $sDataInicial);
    $sDataF = explode("-", $sDataFinal);
    $nDataInicial = mktime(0, 0, 0, $sDataI[1], $sDataI[0], $sDataI[2]);
    $nDataFinal = mktime(0, 0, 0, $sDataF[1], $sDataF[0], $sDataF[2]);
    return ($nDataInicial > $nDataFinal) ?
            floor(($nDataInicial - $nDataFinal) / 86400) : floor(($nDataFinal - $nDataInicial) / 86400);
}

$nome2 = $_SESSION['ss_restrito'];
if (!isset($nome2) && ($tipo != "a"))
    exit;

//recebe as vari�veis
$go = $_POST['go'];
$franqueado = $_POST['franqueado'];
$mes = $_POST['mes'];
$ano = $_POST['ano'];

//declara mes e ano atual
$mes_atual = date('m');
$ano_atual = date('y');

//echo "<pre>";
//print_r( $_POST );
//die;

if (empty($go)) {
    ?>
    <script language="javascript">
    //fun��o para aceitar somente numeros em determinados campos
        function mascara(o, f) {
            v_obj = o
            v_fun = f
            setTimeout("execmascara()", 1)
        }

        function execmascara() {
            v_obj.value = v_fun(v_obj.value)
        }
        function soNumeros(v) {
            return v.replace(/\D/g, "")
        }
    </script>
    <style type="text/css">
        form {margin: 30px 50px 0;}
        form input, select {
            font-family: Arial;
            font-size: 8pt;
        }
    </style>
    <br><br><br>
    <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" >
        <table width=70% border="0" align="center">
            <tr class="titulo">
                <td colspan="2">Fechamento de Franquias</td>
            </tr>
            <tr>
                <td width="173" class="subtitulodireita">&nbsp;</td>
                <td width="224" class="campoesquerda">&nbsp;</td>
            </tr>
            <tr>
                <td class="subtitulodireita"><label for="mes">M&ecirc;s e Ano de Refer&ecirc;ncia</label></td>
                <td class="campoesquerda">
                    <select name="mes" id="mes">
    <?php
    $xx = -1;
    for ($i = $aa_partir; $i <= $aa_partir + 3; $i++) {
        if ($i > 12) {
            $xx++;
            $j = $xx + 1;
        } else {
            $j = $i;
        }
        ?>
        <option value="<?= $j ?>" <?php if ($mes_atual == $j + 1) {
            echo "selected";
        } ?>><?= mesExtensoFechamento($j) ?></option>
    <?php } ?>   
        
                    </select>
                    
                    
                    <?php
                    $ano = date('Y');
                    echo "<select name='ano' style='width:25%'>
                             <option value='$ano'>$ano</option>
                                 <option value='".($ano-1)."'>".($ano-1)."</option>
                          </select>";
                    ?>
                    <input type="hidden" name="go" value="ingressar" /> </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Franquia</td>
                <td class="campoesquerda">
                    <?php
                    if (($tipo == "a") || ($tipo == "c")) {
                        echo "<select name=\"franqueado\">";
                        $sql = "select * from franquia where sitfrq=0 and id_franquia_master = 0 order by id";
                        $resposta = mysql_query($sql,$con);
                        while ($array = mysql_fetch_array($resposta)) {
                            $franquia = $array["id"];
                            $nome_franquia = $array["fantasia"];
                            echo "<option value=\"$franquia\">$nome_franquia</option>\n";
                        }
                        echo "</select>";
                    } else {
                        echo $nome_franquia;
                        echo "<input name=\"franqueado\" type=\"hidden\" id=\"franqueado\" value= $id_franquia; />";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">&nbsp;</td>
                <td class="campoesquerda">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo">&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td align="right">
                    <input name="enviar" type="submit" id="enviar" value="       Verificar" />  </td>
            </tr>
        </table>
    </form>
    <?php
} // if go=null

if ($go == 'ingressar') {

    $sql = "SELECT classificacao from cs2.franquia where id=$franqueado";
    $qr = mysql_query($sql, $con)or die("ERRO:  Segundo SQL  ==>  $sql");
    $classificacao = mysql_result($qr, 0, 'classificacao');

    # Verifico se existe na tabela o fechamento
    $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);

    $sql1 = "SELECT count(*) qtd, detalhe 
             FROM fechamento_franquia
             WHERE id_franquia='$franqueado' AND mes_ano = '$mes-$ano'
             GROUP BY id_franquia,mes_ano";
    $ql1 = mysql_query($sql1, $con);
    while ($array = mysql_fetch_array($ql1)) {
        $qtd = $array['qtd'];
        $fechamento = $array['detalhe'];
        $fechamento = str_replace(chr(13), '<br>', $fechamento);
        $fechamento = str_replace(' ', '&nbsp;', $fechamento);
    }
    if (empty($qtd))
        $qtd = '0';

    if ($qtd == 0) {

        if ($classificacao != 'X') {
            //linha A
            $sql1 = "SELECT count(*), sum(valor) 
                 FROM titulos a
                 INNER JOIN cadastro b ON a.codloja=b.codloja 
                 WHERE b.id_franquia='$franqueado' AND MONTH(vencimento)='$mes' AND Year(vencimento)='$ano'";
            $ql1 = mysql_query($sql1, $con);
            while ($array = mysql_fetch_array($ql1)) {
                $fatot = $array['count(*)'];
                $fatotal = $array['sum(valor)'];
            }
            
            // Calculo do Imposto 5%
            $iss_faturamento = $fatotal * 0.05;
                            
            //linha B
            if ($mes == 1) {
                $mes_bloqueio = 12;
                $ano_bloqueio = $ano - 1;
            } else {
                $mes_bloqueio = $mes - 1;
                if ($mes_bloqueio < 10)
                    $mes_bloqueio = "0" . $mes_bloqueio;
                $ano_bloqueio = $ano;
            }
            $sql2 = "SELECT a.tpcons, MID(c.nome,1,30) nome,SUM(a.qtdcons) qtd, c.vr_custo, 
                        SUM( (a.qtdcons * c.vr_custo) ) custo 
                 FROM totcons a 
                 INNER JOIN cadastro b ON a.codloja = b.codloja
                 INNER JOIN valcons c  ON a.tpcons = c.codcons
                 WHERE b.id_franquia = '$franqueado' AND mid(a.dtsoma,1,7) = '$ano-$mes' 
                 GROUP BY a.tpcons";

            //inclusao de restrito local
            $sql3 = "SELECT count(*), (count(*) * c.vr_custo) as valor 
                 FROM consulta.alertas a
                 INNER JOIN cs2.cadastro b on a.codloja=b.codloja
                 INNER JOIN cs2.valcons  c on c.codcons='B201'
                 WHERE b.id_franquia='$franqueado' AND month(a.data_cadastro)='$mes_bloqueio' AND 
                 YEAR(a.data_cadastro)='$ano_bloqueio' AND situacao = 'N' and destino_negativos = 1
                GROUP BY b.id_franquia";
            $ql3 = mysql_query($sql3, $con);
            while ($array = mysql_fetch_array($ql3)) {
                $qtdbis = $array['count(*)'];
                $vlrbis = $array['valor'];
            }

            // Exclusao Registro LOCAL
            $sql5 = "SELECT count(*) , (count(*) * c.vr_custo) as valor FROM consulta.alertas a
                inner join cs2.cadastro b on a.codloja=b.codloja
                inner join cs2.valcons  c on c.codcons='C201'
                where b.id_franquia='$franqueado' and month(a.data_exclusao)='$mes_bloqueio' and
                Year(a.data_exclusao)='$ano_bloqueio' AND situacao = 'E' and destino_negativos = 1
                GROUP BY b.id_franquia";
            $ql5 = mysql_query($sql5, $con);
            while ($array = mysql_fetch_array($ql5)) {
                $qtddis = $array['count(*)'];
                $vlrdis = $array['valor'];
            }
            // bonificacao dos codigos demonstrativos RESTRITO LOCAL
            $sql11 = "SELECT count(*), (count(*) * c.vr_custo) as valor FROM consulta.alertas a
                INNER JOIN cs2.cadastro b on a.codloja=b.codloja
                INNER JOIN cs2.valcons  c on c.codcons='B201'
                INNER JOIN cs2.logon d on a.codloja=d.codloja
                WHERE b.id_franquia = '$franqueado' AND month(a.data_cadastro)='$mes_bloqueio' AND
                Year(a.data_cadastro) = '$ano_bloqueio' AND a.situacao = 'N' AND a.destino_negativos = 1
                and d.franqueado = 'S' GROUP BY b.id_franquia";
            $ql11 = mysql_query($sql11, $con);
            while ($array = mysql_fetch_array($ql11)) {
                $bonif_inc_local = $array['valor'];
            }
            // bonificacao dos codigos demonstrativos Exclusao RESTRITO LOCAL
            $sql13 = "SELECT count(*), (count(*) * c.vr_custo) as valor FROM consulta.alertas a
                inner join cs2.cadastro b on a.codloja=b.codloja
                inner join cs2.valcons  c on c.codcons='C201'
                inner join cs2.logon d on a.codloja=d.codloja
                where b.id_franquia='$franqueado' and month(a.data_exclusao)='$mes_bloqueio' and
                Year(a.data_exclusao)='$ano_bloqueio' and d.franqueado = 'S' AND a.situacao = 'E' and a.destino_negativos = 1
                GROUP BY b.id_franquia";
            $ql13 = mysql_query($sql13, $con);
            while ($array = mysql_fetch_array($ql13)) {
                $bonif_exc_local = $array['valor'];
            }
            $bonif_cod_demonstra = $bonif_inc_local + $bonif_inc_serasa + $bonif_exc_local + $bonif_exc_serasa;
            // Bonificacao PESQUISAS
            // Master at� 300 e Localiza at� 200
            $sql13 = "select b.id_franquia, b.razaosoc, b.nomefantasia, a.logon, a.codloja,
 (select count(*) from cons where codloja=a.codloja AND MONTH(amd)='$mes_bloqueio' and YEAR(amd)='$ano_bloqueio' AND debito='A0200') as A0200,
 (select count(*) from cons where codloja=a.codloja AND MONTH(amd)='$mes_bloqueio' and YEAR(amd)='$ano_bloqueio' AND debito='A0230') as A0230,
 (select count(*) from cons where codloja=a.codloja AND MONTH(amd)='$mes_bloqueio' and YEAR(amd)='$ano_bloqueio' AND debito='F0200') as F0200,
 (select count(*) from cons where codloja=a.codloja AND MONTH(amd)='$mes_bloqueio' and YEAR(amd)='$ano_bloqueio' AND debito='A0232') as A0232,
 (select sum(qtd_registro) from cons_localiza where codloja=a.codloja AND MONTH(data)='$mes_bloqueio' and YEAR(data)='$ano_bloqueio' AND tipo_consulta='A0231') as A0231
from logon a
inner join cadastro b on a.codloja = b.codloja
where b.id_franquia = '$franqueado' and a.franqueado = 'S'";
            $ql13 = mysql_query($sql13, $con);
            while ($array = mysql_fetch_array($ql13)) {
                $A0200 = $array['A0200'];
                $F0200 = $array['F0200'];
                $tot_200 += $A0200 + $F0200;
                $A0230 = $array['A0230'];
                $A0232 = $array['A0232'];
                $A0231 = $array['A0231'];
                $tot_230 += $A0230 + $A0232;
                $tot_231 += $A0231;
                $codloja = $array['codloja'];
            }
            if ($tot_200 >= 300)
                $tot_200 = 300;
            if ($tot_230 >= 200)
                $tot_230 = 200;
            if ($tot_231 >= 5000)
                $tot_231 = 5000;

            # buscando o valor de custo do correios
                        # Buscando o valor de custo
            $sql13 = "SELECT vr_custo from cs2.valcons";
            $ql13 = mysql_query($sql13, $con);
            while ($array = mysql_fetch_array($ql13)) {
                $custo_correio = $array['vr_custo'];
            }
            
            # Buscando o valor de custo
            $sql13 = "  SELECT codcons, valorcons from cs2.valconscli 
                        WHERE codloja=$codloja and ( codcons = 'A0200' or codcons = 'A0230')";
            $ql13 = mysql_query($sql13, $con);
            while ($array = mysql_fetch_array($ql13)) {
                $codcons = $array['codcons'];
                $valorcons = $array['valorcons'];
                if ($codcons == 'A0200')
                    $vr_A0200 = $tot_200 * $valorcons;
                if ($codcons == 'A0230')
                    $vr_A0230 = $tot_230 * $valorcons;
                if ($codcons == 'A0231')
                    $vr_A0231 = $tot_231 * $valorcons;
            }
            $total_bonificacao = $vr_A0200 + $vr_A0230 + $vr_A0231;

            //linha C
            $sql7 = "select a.tpcons,c.nome,a.qtdcons,a.qtdboni,a.valunit,a.valtot, c.vr_custo, (a.qtdcons-a.qtdboni) qtd,
            ((a.qtdcons - a.qtdboni) * c.vr_custo) C1, a.valtot - ((a.qtdcons - a.qtdboni) * c.vr_custo) lucro 
            from totcons a
            inner join cadastro b on a.codloja=b.codloja
            inner join valcons c on a.tpcons = c.codcons
            where b.id_franquia = '$franqueado' and a.valtot > 0 and mid(dtsoma,1,7)='$ano-$mes'";
            $ql7 = mysql_query($sql7, $con);
            while ($array = mysql_fetch_array($ql7)) {
                $tpcons = $array['tpcons'];
                switch ($tpcons) {
                    case 'A0100' :
                        $NomeA0100 = $array['nome'];
                        $Qtd_consA0100 = $Qtd_consA0100 + $array['qtd'];
                        $Tot_CobradoA0100 = $Tot_CobradoA0100 + $array['valtot'];
                        $Vr_CustoA0100 = $array['vr_custo'];
                        $Tot_CustoA0100 = $Tot_CustoA0100 + $array['C1'];
                        $Tot_GeralA0100 = $Tot_GeralA0100 + $array['lucro'];
                        break;
                    case 'A0115' :
                        $Nome115 = $array['nome'];
                        $Qtd_cons115 = $Qtd_cons115 + $array['qtd'];
                        $Tot_Cobrado115 = $Tot_Cobrado115 + $array['valtot'];
                        $Vr_Custo115 = $array['vr_custo'];
                        $Tot_Custo115 = $Tot_Custo115 + $array['C1'];
                        $Tot_Geral115 = $Tot_Geral115 + $array['lucro'];
                        break;
                    case 'A0200' :
                        $NomeA0200 = $array['nome'];
                        $Qtd_consA0200 = $Qtd_consA0200 + $array['qtd'];
                        $Tot_CobradoA0200 = $Tot_CobradoA0200 + $array['valtot'];
                        $Vr_CustoA0200 = $array['vr_custo'];
                        $Tot_CustoA0200 = $Tot_CustoA0200 + $array['C1'];
                        $Tot_GeralA0200 = $Tot_GeralA0200 + $array['lucro'];
                        break;
                    case 'A0201' :
                        $NomeA0201 = $array['nome'];
                        $Qtd_consA0201 = $Qtd_consA0201 + $array['qtd'];
                        $Tot_CobradoA0201 = $Tot_CobradoA0201 + $array['valtot'];
                        $Vr_CustoA0201 = $array['vr_custo'];
                        $Tot_CustoA0201 = $Tot_CustoA0201 + $array['C1'];
                        $Tot_GeralA0201 = $Tot_GeralA0201 + $array['lucro'];
                        break;
                    case 'A0202' :
                        $NomeA0202 = $array['nome'];
                        $Qtd_consA0202 = $Qtd_consA0202 + $array['qtd'];
                        $Tot_CobradoA0202 = $Tot_CobradoA0202 + $array['valtot'];
                        $Vr_CustoA0202 = $array['vr_custo'];
                        $Tot_CustoA0202 = $Tot_CustoA0202 + $array['C1'];
                        $Tot_GeralA0202 = $Tot_GeralA0202 + $array['lucro'];
                        break;
                    case 'A0203' :
                        $Nome203 = $array['nome'];
                        $Qtd_cons203 = $Qtd_cons203 + $array['qtd'];
                        $Tot_Cobrado203 = $Tot_Cobrado203 + $array['valtot'];
                        $Vr_Custo203 = $array['vr_custo'];
                        $Tot_Custo203 = $Tot_Custo203 + $array['C1'];
                        $Tot_Geral203 = $Tot_Geral203 + $array['lucro'];
                        break;
                    case 'A0207' :
                        $Nome207 = $array['nome'];
                        $Qtd_cons207 = $Qtd_cons207 + $array['qtd'];
                        $Tot_Cobrado207 = $Tot_Cobrado207 + $array['valtot'];
                        $Vr_Custo207 = $array['vr_custo'];
                        $Tot_Custo207 = $Tot_Custo207 + $array['C1'];
                        $Tot_Geral207 = $Tot_Geral207 + $array['lucro'];
                        break;
                    case 'A0208' :
                        $Nome208 = $array['nome'];
                        $Qtd_cons208 = $Qtd_cons208 + $array['qtd'];
                        $Tot_Cobrado208 = $Tot_Cobrado208 + $array['valtot'];
                        $Vr_Custo208 = $array['vr_custo'];
                        $Tot_Custo208 = $Tot_Custo208 + $array['C1'];
                        $Tot_Geral208 = $Tot_Geral208 + $array['lucro'];
                        break;
                    case 'A0230' :
                        $Nome230 = $array['nome'];
                        $Qtd_cons230 = $Qtd_cons230 + $array['qtd'];
                        $Tot_Cobrado230 = $Tot_Cobrado230 + $array['valtot'];
                        $Vr_Custo230 = $array['vr_custo'];
                        $Tot_Custo230 = $Tot_Custo230 + $array['C1'];
                        $Tot_Geral230 = $Tot_Geral230 + $array['lucro'];
                        break;
                    case 'A0231' :
                        $Nome231 = $array['nome'];
                        $Qtd_cons231 = $Qtd_cons231 + $array['qtd'];
                        $Tot_Cobrado231 = $Tot_Cobrado231 + $array['valtot'];
                        $Vr_Custo231 = $array['vr_custo'];
                        $Tot_Custo231 = $Tot_Custo231 + $array['C1'];
                        $Tot_Geral231 = $Tot_Geral231 + $array['lucro'];
                        break;
                    case 'A0232' :
                        $Nome232 = $array['nome'];
                        $Qtd_cons232 = $Qtd_cons232 + $array['qtd'];
                        $Tot_Cobrado232 = $Tot_Cobrado232 + $array['valtot'];
                        $Vr_Custo232 = $array['vr_custo'];
                        $Tot_Custo232 = $Tot_Custo232 + $array['C1'];
                        $Tot_Geral232 = $Tot_Geral232 + $array['lucro'];
                        break;
                    case 'A0301' :
                        $NomeA0301 = $array['nome'];
                        $Qtd_consA0301 = $Qtd_consA0301 + $array['qtd'];
                        $Tot_CobradoA0301 = $Tot_CobradoA0301 + $array['valtot'];
                        $Vr_CustoA0301 = $array['vr_custo'];
                        $Tot_CustoA0301 = $Tot_CustoA0301 + $array['C1'];
                        $Tot_GeralA0301 = $Tot_GeralA0301 + $array['lucro'];
                        break;
                    case 'A0302' :
                        $NomeA0302 = $array['nome'];
                        $Qtd_consA0302 = $Qtd_consA0302 + $array['qtd'];
                        $Tot_CobradoA0302 = $Tot_CobradoA0302 + $array['valtot'];
                        $Vr_CustoA0302 = $array['vr_custo'];
                        $Tot_CustoA0302 = $Tot_CustoA0302 + $array['C1'];
                        $Tot_GeralA0302 = $Tot_GeralA0302 + $array['lucro'];
                        break;
                    case 'A0400' :
                        $Nome400 = $array['nome'];
                        $Qtd_cons400 = $Qtd_cons400 + $array['qtd'];
                        $Tot_Cobrado400 = $Tot_Cobrado400 + $array['valtot'];
                        $Vr_Custo400 = $array['vr_custo'];
                        $Tot_Custo400 = $Tot_Custo400 + $array['C1'];
                        $Tot_Geral400 = $Tot_Geral400 + $array['lucro'];
                        break;
                    case 'A0401' :
                        $Nome401 = $array['nome'];
                        $Qtd_cons401 = $Qtd_cons401 + $array['qtd'];
                        $Tot_Cobrado401 = $Tot_Cobrado401 + $array['valtot'];
                        $Vr_Custo401 = $array['vr_custo'];
                        $Tot_Custo401 = $Tot_Custo401 + $array['C1'];
                        $Tot_Geral401 = $Tot_Geral401 + $array['lucro'];
                        break;
                    case 'A0402' :
                        $Nome402 = $array['nome'];
                        $Qtd_cons402 = $Qtd_cons402 + $array['qtd'];
                        $Tot_Cobrado402 = $Tot_Cobrado402 + $array['valtot'];
                        $Vr_Custo402 = $array['vr_custo'];
                        $Tot_Custo402 = $Tot_Custo402 + $array['C1'];
                        $Tot_Geral402 = $Tot_Geral402 + $array['lucro'];
                        break;
                    case 'A0403' :
                        $Nome403 = $array['nome'];
                        $Qtd_cons403 = $Qtd_cons403 + $array['qtd'];
                        $Tot_Cobrado403 = $Tot_Cobrado403 + $array['valtot'];
                        $Vr_Custo403 = $array['vr_custo'];
                        $Tot_Custo403 = $Tot_Custo403 + $array['C1'];
                        $Tot_Geral403 = $Tot_Geral403 + $array['lucro'];
                        break;
                    case 'A0404' :
                        $Nome404 = $array['nome'];
                        $Qtd_cons404 = $Qtd_cons404 + $array['qtd'];
                        $Tot_Cobrado404 = $Tot_Cobrado404 + $array['valtot'];
                        $Vr_Custo404 = $array['vr_custo'];
                        $Tot_Custo404 = $Tot_Custo404 + $array['C1'];
                        $Tot_Geral404 = $Tot_Geral404 + $array['lucro'];
                        break;
                    case 'A0405' :
                        $Nome405 = $array['nome'];
                        $Qtd_cons405 = $Qtd_cons405 + $array['qtd'];
                        $Tot_Cobrado405 = $Tot_Cobrado405 + $array['valtot'];
                        $Vr_Custo405 = $array['vr_custo'];
                        $Tot_Custo405 = $Tot_Custo405 + $array['C1'];
                        $Tot_Geral405 = $Tot_Geral405 + $array['lucro'];
                        break;
                    case 'A0406' :
                        $Nome406 = $array['nome'];
                        $Qtd_cons406 = $Qtd_cons406 + $array['qtd'];
                        $Tot_Cobrado406 = $Tot_Cobrado406 + $array['valtot'];
                        $Vr_Custo406 = $array['vr_custo'];
                        $Tot_Custo406 = $Tot_Custo406 + $array['C1'];
                        $Tot_Geral406 = $Tot_Geral406 + $array['lucro'];
                        break;
                    case 'A0407' :
                        $Nome407 = $array['nome'];
                        $Qtd_cons407 = $Qtd_cons407 + $array['qtd'];
                        $Tot_Cobrado407 = $Tot_Cobrado407 + $array['valtot'];
                        $Vr_Custo407 = $array['vr_custo'];
                        $Tot_Custo407 = $Tot_Custo407 + $array['C1'];
                        $Tot_Geral407 = $Tot_Geral407 + $array['lucro'];
                        break;
                    case 'A0408' :
                        $Nome408 = $array['nome'];
                        $Qtd_cons408 = $Qtd_cons408 + $array['qtd'];
                        $Tot_Cobrado408 = $Tot_Cobrado408 + $array['valtot'];
                        $Vr_Custo408 = $array['vr_custo'];
                        $Tot_Custo408 = $Tot_Custo408 + $array['C1'];
                        $Tot_Geral408 = $Tot_Geral408 + $array['lucro'];
                        break;
                    case 'A0410' :
                        $Nome410 = $array['nome'];
                        $Qtd_cons410 = $Qtd_cons410 + $array['qtd'];
                        $Tot_Cobrado410 = $Tot_Cobrado410 + $array['valtot'];
                        $Vr_Custo410 = $array['vr_custo'];
                        $Tot_Custo410 = $Tot_Custo410 + $array['C1'];
                        $Tot_Geral410 = $Tot_Geral410 + $array['lucro'];
                        break;
                    case 'A0700' :
                        $NomeA0700 = $array['nome'];
                        $Qtd_consA0700 = $Qtd_consA0700 + $array['qtd'];
                        $Tot_CobradoA0700 = $Tot_CobradoA0700 + $array['valtot'];
                        $Vr_CustoA0700 = $array['vr_custo'];
                        $Tot_CustoA0700 = $Tot_CustoA0700 + $array['C1'];
                        $Tot_GeralA0700 = $Tot_GeralA0700 + $array['lucro'];
                        break;
                    case 'A0710' :
                        $Nome710 = $array['nome'];
                        $Qtd_cons710 = $Qtd_cons710 + $array['qtd'];
                        $Tot_Cobrado710 = $Tot_Cobrado710 + $array['valtot'];
                        $Vr_Custo710 = $array['vr_custo'];
                        $Tot_Custo710 = $Tot_Custo710 + $array['C1'];
                        $Tot_Geral710 = $Tot_Geral710 + $array['lucro'];
                        break;
                    case 'A0711' :
                        $Nome711 = $array['nome'];
                        $Qtd_cons711 = $Qtd_cons711 + $array['qtd'];
                        $Tot_Cobrado711 = $Tot_Cobrado711 + $array['valtot'];
                        $Vr_Custo711 = $array['vr_custo'];
                        $Tot_Custo711 = $Tot_Custo711 + $array['C1'];
                        $Tot_Geral711 = $Tot_Geral711 + $array['lucro'];
                        break;
                    case 'F0115' :
                        $NomeF115 = $array['nome'];
                        $Qtd_consF115 = $Qtd_consF115 + $array['qtd'];
                        $Tot_CobradoF115 = $Tot_CobradoF115 + $array['valtot'];
                        $Vr_CustoF115 = $array['vr_custo'];
                        $Tot_CustoF115 = $Tot_CustoF115 + $array['C1'];
                        $Tot_GeralF115 = $Tot_GeralF115 + $array['lucro'];
                        break;
                    case 'F0200' :
                        $NomeF200 = $array['nome'];
                        $Qtd_consF200 = $Qtd_consF200 + $array['qtd'];
                        $Tot_CobradoF200 = $Tot_CobradoF200 + $array['valtot'];
                        $Vr_CustoF200 = $array['vr_custo'];
                        $Tot_CustoF200 = $Tot_CustoF200 + $array['C1'];
                        $Tot_GeralF200 = $Tot_GeralF200 + $array['lucro'];
                        break;
                    case 'F0301' :
                        $NomeF301 = $array['nome'];
                        $Qtd_consF301 = $Qtd_consF301 + $array['qtd'];
                        $Tot_CobradoF301 = $Tot_CobradoF301 + $array['valtot'];
                        $Vr_CustoF301 = $array['vr_custo'];
                        $Tot_CustoF301 = $Tot_CustoF301 + $array['C1'];
                        $Tot_GeralF301 = $Tot_GeralF301 + $array['lucro'];
                        break;
                    case 'F0710' :
                        $NomeF710 = $array['nome'];
                        $Qtd_consF710 = $Qtd_consF710 + $array['qtd'];
                        $Tot_CobradoF710 = $Tot_CobradoF710 + $array['valtot'];
                        $Vr_CustoF710 = $array['vr_custo'];
                        $Tot_CustoF710 = $Tot_CustoF710 + $array['C1'];
                        $Tot_GeralF710 = $Tot_GeralF710 + $array['lucro'];
                        break;
                    case 'U0200' :
                        $NomeU0200 = $array['nome'];
                        $Qtd_consU0200 = $Qtd_consU0200 + $array['qtd'];
                        $Tot_CobradoU0200 = $Tot_CobradoU0200 + $array['valtot'];
                        $Vr_CustoU0200 = $array['vr_custo'];
                        $Tot_CustoU0200 = $Tot_CustoU0200 + $array['C1'];
                        $Tot_GeralU0200 = $Tot_GeralU0200 + $array['lucro'];
                        break;
                    case 'U0201' :
                        $NomeU0201 = $array['nome'];
                        $Qtd_consU0201 = $Qtd_consU0201 + $array['qtd'];
                        $Tot_CobradoU0201 = $Tot_CobradoU0201 + $array['valtot'];
                        $Vr_CustoU0201 = $array['vr_custo'];
                        $Tot_CustoU0201 = $Tot_CustoU0201 + $array['C1'];
                        $Tot_GeralU0201 = $Tot_GeralU0201 + $array['lucro'];
                        break;
                    case 'U0202' :
                        $NomeU0202 = $array['nome'];
                        $Qtd_consU0202 = $Qtd_consU0202 + $array['qtd'];
                        $Tot_CobradoU0202 = $Tot_CobradoU0202 + $array['valtot'];
                        $Vr_CustoU0202 = $array['vr_custo'];
                        $Tot_CustoU0202 = $Tot_CustoU0202 + $array['C1'];
                        $Tot_GeralU0202 = $Tot_GeralU0202 + $array['lucro'];
                        break;
                    case 'U0208' :
                        $NomeU0208 = $array['nome'];
                        $Qtd_consU0208 = $Qtd_consU0208 + $array['qtd'];
                        $Tot_CobradoU0208 = $Tot_CobradoU0208 + $array['valtot'];
                        $Vr_CustoU0208 = $array['vr_custo'];
                        $Tot_CustoU0208 = $Tot_CustoU0208 + $array['C1'];
                        $Tot_GeralU0208 = $Tot_GeralU0208 + $array['lucro'];
                        break;
                    case 'U0301' :
                        $NomeU0301 = $array['nome'];
                        $Qtd_consU0301 = $Qtd_consU0301 + $array['qtd'];
                        $Tot_CobradoU0301 = $Tot_CobradoU0301 + $array['valtot'];
                        $Vr_CustoU0301 = $array['vr_custo'];
                        $Tot_CustoU0301 = $Tot_CustoU0301 + $array['C1'];
                        $Tot_GeralU0301 = $Tot_GeralU0301 + $array['lucro'];
                        break;
                    case 'U0710' :
                        $NomeU0710 = $array['nome'];
                        $Qtd_consU0710 = $Qtd_consU0710 + $array['qtd'];
                        $Tot_CobradoU0710 = $Tot_CobradoU0710 + $array['valtot'];
                        $Vr_CustoU0710 = $array['vr_custo'];
                        $Tot_CustoU0710 = $Tot_CustoU0710 + $array['C1'];
                        $Tot_GeralU0710 = $Tot_GeralU0710 + $array['lucro'];
                        break;
                }//fim switch
                $totex = $totex + $array['lucro'];
            }//fim while
            # Calculo Tarifa Boleto
            $sql8 = "SELECT count(*) * (select vr_custo from cs2.valcons where codcons = 'CBC' ) qtd FROM titulos a
                inner join cadastro b on a.codloja=b.codloja
                where b.id_franquia='$franqueado' and MONTH(vencimento)='$mes' and Year(vencimento)='$ano'";
            $ql8 = mysql_query($sql8, $con);
            $cons8 = mysql_fetch_array($ql8);
            $cusbolcorr = $cons8['qtd'];
            $cusbolcorrn = number_format($cusbolcorr, 2, ',', '.');

            //linha E
            if ($mes >= 3) {
                $xmes = $mes - 2;
                $xano = $ano;
            } else {
                $xmes = ($mes + 12) - 2;
                $xano = $ano - 1;
            }

            $sql9 = "SELECT count(*), sum(valor) total FROM titulos a
                    INNER JOIN cadastro b on a.codloja=b.codloja
                    LEFT OUTER JOIN pedidos_cancelamento c on a.codloja=c.codloja
                    WHERE b.id_franquia='$franqueado' and dt_cad <= '$xano-$xmes-31' 
                        and pendencia_contratual = 1 and sitcli < 2 and
                    MONTH(vencimento)='$mes' and Year(vencimento)='$ano' AND c.data_documento is NULL
                    GROUP BY b.codloja";
            $ql9 = mysql_query($sql9, $con);
            $contpen = 0;
            $somfat = 0;
            while ($array = mysql_fetch_array($ql9)) {
                $contpen++;
                $somfat += $array['total'];
            }
            //linha J
            $sql10 = "SELECT count(*), sum(valor) total, origem_pgto FROM titulos a
                inner join cadastro b on a.codloja=b.codloja
                where b.id_franquia='$franqueado' and MONTH(vencimento)='$mes' and Year(vencimento)='$ano' group by origem_pgto";
            $ql10 = mysql_query($sql10, $con);
            while ($array = mysql_fetch_array($ql10)) {
                $oripgt = $array['origem_pgto'];
                if ($oripgt <> 'BANCO') {
                    $qtdrep = $qtdrep + $array['count(*)'];
                    $somrep = $somrep + $array['total'];
                    $somrepn = $somrep;
                    $somrepn_exibe = number_format($somrep, 2, ',', '.');
                }
            }

            # Material Publicit�rio 6 meses
            $sql = "SELECT DATEDIFF(now(), dt_cad) dias from cs2.franquia where id=$franqueado";
            $qr = mysql_query($sql, $con)or die("ERRO:  Segundo SQL  ==>  $sql");
            $campos = mysql_fetch_array($qr);
            $dias = $campos["dias"];
            if ($dias > 180) {
                $sql_qtd_ctr = "SELECT count(*) qtd FROM cs2.cadastro where sitcli < 2 and id_franquia = $franqueado";
                $qry_qtdctr = mysql_query($sql_qtd_ctr, $con)or die("ERRO:  Segundo SQL  ==>  $sql_qtd_ctr");
                if (mysql_num_rows($qry_qtdctr) > 0) {
                    $reg = mysql_fetch_array($qry_qtdctr);
                    $qtd = $reg["qtd"];

                    function material_public($faixa1, $faixa2) {
                        global $con;
                        $sql_mat = "SELECT valor FROM cs2.tabela_material_publicitario
                WHERE faixa_inicio = $faixa1 AND faixa_final = $faixa2";
                        $qry_mat = mysql_query($sql_mat, $con);
                        $valor = mysql_result($qry_mat, 0, 'valor');
                        mysql_free_result($qry_mat);
                        return $valor;
                    }

                    if ($qtd <= 150)
                        $material_public = material_public(1, 150);
                    else if ($qtd > 150 and $qtd <= 400)
                        $material_public = material_public(151, 400);
                    else
                        $material_public = material_public(401, 50000);
                }
                $material_public = '750.00';
            } else
                $material_public = '0.00';

            $TOT_D = $cusbolcorr + $material_public + $iss_faturamento;
            $TOT_D = number_format($TOT_D, 2, ',', '.');

            $material_public = number_format($material_public, 2, ',', '.');

            // participacao mensal da franqueadora e as franquias de acordo ao n�mero de vendas
            $sql17 = "select count(*) totvendas 
                from cs2.cadastro where dt_cad like '$ano_bloqueio-$mes_bloqueio%' and id_franquia = '$franqueado'";
            $qr17 = mysql_query($sql17, $con);
            $totvendas = mysql_result($qr17, 0, "totvendas");

            function participacao_franquia_franqueadora($qtd_ctr_vendido) {
                global $con;
                $sql_part = "SELECT participacao_franquia, participacao_franqueadora 
                         FROM tabela_participacao_franquia 
                         WHERE faixa_venda_inicio <= '$qtd_ctr_vendido' AND faixa_venda_final >= '$qtd_ctr_vendido'";
                $qry_part = mysql_query($sql_part, $con);
                $valor = mysql_result($qry_part, 0, 'participacao_franquia') . ';' . mysql_result($qry_part, 0, 'participacao_franqueadora');
                mysql_free_result($qry_part);
                return $valor;
            }

            $comissao = participacao_franquia_franqueadora($totvendas);
            $vlr = explode(';', $comissao);

            $part_franquia = $vlr[0];
            $coef_franquia = $part_franquia / 100;

            $part_franqueadora = $vlr[1];
            $coef_franqueadora = $part_franqueadora / 100;
            ?>
            <br />
            <table width="750">
                <tr>
                    <td class="pageName" colspan="2">Relat&oacute;rio de Repasses</td>
                </tr>
                <tr class="bodyText">
                    <td>Franquia:
                            <?php
                            $resposta = mysql_query("select razaosoc from franquia where id='$franqueado'", $con);
                            $consulta = mysql_fetch_array($resposta);
                            echo $consulta["razaosoc"];
                            ?>    </td>
                    <td>M&ecirc;s de Refer&ecirc;ncia: <?php echo $mes." - ".$ano; ?></td>
                </tr>
                <tr class="bodyText">
                    <td>Total de Contratos Fechados: <?php echo $totvendas; ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table width="750" class="bodyText">
                            <tr class="titulo">
                                <td colspan="3"><div align="left">A) Faturamento (Pacotes, Licen&ccedil;as, Solu&ccedil;&otilde;es e Pesquisas) - <font color="#0000FF" style="font-size:10px">( Total de Clientes : <?php echo $fatot; ?> )</font></div></td>
                                <td><div  align="right">R$ <?php echo number_format($fatotal, 2, ',', '.'); ?></div></td>
                            </tr>

                            <?php
                            
                            //pega o extrato de boletos crediario/recupere pagos em banco
                            $sql15 = "select count(*) qtd, sum(valorpg) soma from cs2.titulos_recebafacil a
                  inner join cs2.cadastro b on a.codloja = b.codloja
                  where month(a.datapg) = '$mes_bloqueio' and Year(a.datapg) = '$ano_bloqueio' 
                        and ( tp_titulo = '2' or tp_titulo = '3' )
                        and id_franquia = '$franqueado'";
                            $qr15 = mysql_query($sql15, $con);
                            $qtd_1 = mysql_result($qr15, 0, "qtd");
                            $soma_1 = mysql_result($qr15, 0, "soma");

                            $sql16 = "select count(*) qtd, sum(valorpg) soma from cs2.titulos_recebafacil a
                  inner join cs2.cadastro b on a.codloja = b.codloja
                  where month(a.datapg) = '$mes_bloqueio' and Year(a.datapg) = '$ano_bloqueio' 
                        and ( tp_titulo = '2' or tp_titulo = '3' )
                        and id_franquia = '$franqueado' and descricao_repasse like '%CLIENTE RECEBEU O T%'";
                            $qr16 = mysql_query($sql16, $con);
                            $qtd_2 = mysql_result($qr16, 0, "qtd");
                            $soma_2 = mysql_result($qr16, 0, "soma");

                            $NomeCR = "Servi&ccedil;os (Credi&aacute;rio / Recupere )";
                            $Qtd_consCR = $qtd_1 - $qtd_2;
                            $Tot_CobradoCR = $soma_1 - $soma_2;
                            $Tot_GeralCR = ($Tot_CobradoCR * 2.5) / 100;
                            //fim crediario / recupere
                            ?>
                            <tr class="titulo">
                                <td colspan="3"><div align="left">A1) T&iacute;tulos Liquidados (Credi&aacute;rio/Recupere/Boleto) - <font color="#0000FF" style="font-size:10px">( Qtd Titulos : <?php echo " $Qtd_consCR - R$ ".number_format($Tot_CobradoCR, 2, ',', '.');?> x 2,5% )</font></div></td>
                                <td><div  align="right">R$ <?php echo number_format($Tot_GeralCR, 2, ',', '.'); ?></div></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="titulo"><div align="left">B) Custo de todas as Solu&ccedil;&otilde;es e Pesquisas</div></td>
                                <td class="titulo">&nbsp;</td>
                            </tr>

                            <tr>
                                <td class="total">Nome Solu&ccedil;&otilde;es/Pesquisas</td>
                                <td class="total">Qtd.  Realizada</td>
                                <td class="total">Vr. Custo</td>
                                <td class="total">Vr. Total</td>
                            </tr>
                            <?php
                            // busca o valor de custo da licenca

                            $sql_custo = "SELECT vr_custo FROM cs2.valcons WHERE codcons = 'LSOF'";
                            $ql_custo = mysql_query($sql_custo, $con);
                            $custo_licenca = mysql_result($ql_custo, 0, 'vr_custo');
                            
                            $total_licenc = ($fatot * $custo_licenca);
                            $total_licenca = number_format($total_licenc, 2, ',', '.');
                            $ql2 = mysql_query($sql2, $con);
                            while ($array = mysql_fetch_array($ql2)) {
                                $nomcons = $array['nome'];
                                $qtdcons = $array['qtd'];
                                $vrcusto = $array['vr_custo'];
                                $vrtoral = $array['custo'];
                                $totconsrel = $totconsrel + $vrtoral;
                                $vrcuston = number_format($vrcusto, 2, ',', '.');
                                $vrtoraln = number_format($vrtoral, 2, ',', '.');
                                echo "<tr class=\"campoesquerda\">
                    <td class=\"tabela\">$nomcons</td>
                    <td align=center>$qtdcons</td>
                    <td align=right>R$ $vrcuston</td>
                    <td align=right>R$ $vrtoraln</td>
                </tr>";
                            }

                            $tot_localiza_novos_cliente = 0;

                            $nmes = $mes - 1;
                            $nano = $ano;
                            if ($mes == 1) {
                                $nmes = '12';
                                $nano = $ano - 1;
                            }

                            $dt_inicio = "$nano-$nmes-01";
                            $dt_fim = "$nano-$nmes-31";

                            // Cobrando CONSULTA LOCALIZA NOVOS CLIENTES

                            $sql_custo = "SELECT vr_custo FROM cs2.valcons WHERE codcons = 'A0231'";
                            $ql_custo = mysql_query($sql_custo, $con);
                            $custo = mysql_result($ql_custo, 0, 'vr_custo');

                            $sql_novos = "SELECT a.codloja, SUM(a.qtd_registro) as soma
                      FROM cons_localiza a
                      INNER JOIN logon b     ON a.codloja = b.codloja
                      INNER JOIN cadastro c  ON a.codloja = c.codloja
                      WHERE
                           c.id_franquia = '$franqueado' AND a.data BETWEEN '$dt_inicio' AND '$dt_fim'
                           GROUP BY a.codloja";
                            $ql_novos = mysql_query($sql_novos, $con);
                            while ($array = mysql_fetch_array($ql_novos)) {
                                $tot_localiza_novos_cliente += $array['soma'];
                            }

                            $soma_localiza = ( $custo * $tot_localiza_novos_cliente);

                            echo "<tr class=\"campoesquerda\">
                    <td class=\"tabela\">Localiza Max - Novos Clientes</td>
                    <td align=center>$tot_localiza_novos_cliente</td>
                    <td align=right>R$ $custo</td>
                    <td align=right>R$ $soma_localiza</td>
                    
                </tr>";
                            $totconsrel = $totconsrel + $soma_localiza;


                            // Cobrando POSTAGEM CORREIO - CREDIARIO/RECUPERE / BOLETO

                            $sql_custo = "SELECT vr_custo FROM cs2.valcons WHERE codcons = 'CC101'";
                            $ql_custo = mysql_query($sql_custo, $con);
                            $custo = mysql_result($ql_custo, 0, 'vr_custo');

                            $sql_novos = "SELECT DISTINCT(a.chavebol), a.codloja FROM cs2.titulos_recebafacil a
                      INNER JOIN cadastro b ON a.codloja = b.codloja
                      WHERE a.data_impresso BETWEEN '$dt_inicio' AND '$dt_fim'
                            and b.id_franquia = $franqueado";
                            $ql_novos = mysql_query($sql_novos, $con);
                            $qtd_boleto = mysql_num_rows($ql_novos);
                            $tot_boleto = $qtd_boleto * $custo;

                            echo "<tr class=\"campoesquerda\">
                    <td class=\"tabela\">Postagem Correio - Cred/Rec/Bol</td>
                    <td align=center>$qtd_boleto</td>
                    <td align=right>R$ $custo</td>
                    <td align=right>R$ $tot_boleto</td>
                </tr>";
                            $totconsrel = $totconsrel + $tot_boleto;

                            // Cobrando Encaminhamento para Protesto

                            $sql_custo = "SELECT vr_custo FROM cs2.valcons WHERE codcons = 'T0001'";
                            $ql_custo = mysql_query($sql_custo, $con);
                            $custo = mysql_result($ql_custo, 0, 'vr_custo');

                            $sql_novos = "SELECT count(*) as soma, a.codloja from consulta.alertas a
                      INNER JOIN cadastro b ON a.codloja = b.codloja
                      WHERE a.dt_envio_cartorio BETWEEN '$dt_inicio' AND '$dt_fim'
                            and b.id_franquia = $franqueado";
                            $tot_localiza_novos_cliente = 0;
                            $ql_novos = mysql_query($sql_novos, $con);
                            while ($array = mysql_fetch_array($ql_novos)) {
                                $tot_localiza_novos_cliente += $array['soma'];
                            }

                            $soma_localiza = ( $custo * $tot_localiza_novos_cliente);

                            echo "<tr class=\"campoesquerda\">
                        <td class=\"tabela\">Encaminhamento para Protesto</td>
                        <td align=center>$tot_localiza_novos_cliente</td>
                        <td align=right>R$ $custo</td>
                        <td align=right>R$ $soma_localiza</td>
            </tr>";
                            $totconsrel = $totconsrel + $soma_localiza;

                            // Cobrando .... Recomende o Cliente

                            $sql_custo = "SELECT vr_custo FROM cs2.valcons WHERE codcons = 'T0002'";
                            $ql_custo = mysql_query($sql_custo, $con);
                            $custo = mysql_result($ql_custo, 0, 'vr_custo');

                            $sql_novos = "SELECT count(*) as soma, a.codloja FROM cs2.relacionamento_consumidor a
                      INNER JOIN cadastro b ON a.codloja = b.codloja
                      WHERE a.amd BETWEEN '$dt_inicio' AND '$dt_fim'
                            and b.id_franquia = $franqueado";
                            $tot_localiza_novos_cliente = 0;
                            $ql_novos = mysql_query($sql_novos, $con);
                            while ($array = mysql_fetch_array($ql_novos)) {
                                $tot_localiza_novos_cliente += $array['soma'];
                            }

                            $soma_localiza = ( $custo * $tot_localiza_novos_cliente);

                            echo "<tr class=\"campoesquerda\">
                    <td class=\"tabela\">Recomende o Cliente</td>
                    <td align=center>$tot_localiza_novos_cliente</td>
                    <td align=right>R$ $custo</td>
                    <td align=right>R$ $soma_localiza</td>
                </tr>";
                            $totconsrel = $totconsrel + $soma_localiza;

                            //Continuacao...

                            echo "<tr class=\"campoesquerda\">
                    <td class=\"tabela\">Custo Licenças - Softwares/Soluções</td>
                    <td align=center>$fatot</td>
                    <td align=right>R$ $custo_licenca</td>
                    <td align=right>R$ $total_licenca</td>
                    
                </tr>";
                            $totconsrel = $totconsrel + $total_licenc;

                            $sql_custo = "SELECT vr_custo, nome FROM cs2.valcons WHERE codcons = 'TBE01'";
                            $ql_custo = mysql_query($sql_custo, $con);
                            $custo = mysql_result($ql_custo, 0, 'vr_custo');
                            $texto = mysql_result($ql_custo, 0, 'nome');

                            // TITULOS LIQUIDADOS NO ESTABELECIMENTO

                            $sql4 = "SELECT count(*) qtd_bx from titulos_recebafacil a
                 INNER JOIN cadastro b ON a.codloja = b.codloja
                 WHERE 
                  a.valorpg > 0 
                  AND MONTH(a.datapg)='$mes' and Year(a.datapg)='$ano'
                  AND a.descricao_repasse = 'CLIENTE RECEBEU O TITULO NO ESTABELECIMENTO'
                  AND b.id_franquia = '$franqueado'";

                            $qry_sql4 = mysql_query($sql4, $con);
                            $qtd_cliente_baixa = mysql_result($qry_sql4, 0, 'qtd_bx');
                            $tot_cliente_baixa = number_format($qtd_cliente_baixa * $custo, 2, ',', '.');
                            echo "<tr class=\"campoesquerda\">
                    <td class=\"tabela\">$texto</td>
                    <td align=center>$qtd_cliente_baixa</td>
                    <td align=right>R$ $custo</td>
                    <td align=right>R$ $tot_cliente_baixa</td>
                </tr>";
                            $totconsrel = $totconsrel + $qtd_cliente_baixa;
                            ?>
                        </table>
                        <table class="bodyText" width="750">
                            <tr>
                                <td class="total"><div align="left">Total de Custos das Solu&ccedil;&otilde;es e Pesquisas</div></td>
                                <td align="right" class="total"><div align="right"><?php
                            $totconsrel1 = number_format($totconsrel, 2, ',', '.');
                            echo "R$ " . $totconsrel1;
                            ?></div></td>
                            </tr>
                            <tr>
                                <td><strong>BLOQUEIO DEVEDORES - Registro on Line Brasil: <?php echo $qtdbis; ?></strong></td>
                                <td align="right"><strong><?php
                                        $vlrbis1 = number_format($vlrbis, 2, ',', '.');
                                        echo "R$ " . $vlrbis1;
                                        ?></strong></td>
                            </tr>
                            <tr>
                                <td><strong>DESBLOQUEIO DEVEDORES - Resgistro on Line Brasil: <?php echo $qtddis; ?></strong></td>
                                <td align="right"><strong><?php
                                            $vlrdis1 = number_format($vlrdis, 2, ',', '.');
                                            echo "R$ " . $vlrdis1;
                                            ?></strong></td>
                            </tr>
                            <tr>
                                <td><strong>Bonifica&ccedil;&atilde;o dos C&oacute;digos Demonstrativos (Bloqueio e Desbloqueio):</strong></td>
                                <td align="right"><b><?php
                                            $bonif_cod_demonstra1 = number_format($bonif_cod_demonstra, 2, ',', '.');
                                            echo "R$ " . $bonif_cod_demonstra1;
                                            ?></b></td>
                            </tr>
                            <tr>
                                <td><strong>Bonifica&ccedil;&atilde;o dos C&oacute;digos Demonstrativos (Pesquisas <?= $tot_200 ?> Master - <?= $tot_230 ?> Localiza - <?= $tot_231 ?> Localiza Novos Clientes):</strong></td>
                                <td align="right"><b><?php
                                            $bonif_cod_demonstra1 += number_format($total_bonificacao, 2, ',', '.');
                                            $total_bonificacaox = number_format($total_bonificacao, 2, ',', '.');
                                            echo "R$ " . $total_bonificacaox;
                                            ?></b></td>
                            </tr>
                            <tr>
                                <td class="total"><div align="left"><strong>Total Geral</strong></div></td>
                                <td align="right" class="total"><font size="-1"><div align="right"><strong><?php
                                        $totgeral = $totconsrel + $vlrbis + $vlrbs + $vlrdis + $totvlrds - ($bonif_cod_demonstra + $total_bonificacao);
                                        $totgeral1 = number_format($totgeral, 2, ',', '.');
                                        echo "R$ " . $totgeral1;
                                        ?></strong></div></font></td>
                            </tr>
                        </table>

                        <table width="750">
                            <tr>
                                <td class="titulo"><div align="left">C) Custo Cobran&ccedil;a Banc&aacute;ria + Postagem Correio  <font style="font-size:10px">( Total de Clientes : <?php echo $fatot; ?> x R$ <?php echo number_format($custo_correio,2,',','.'); ?> )</font></div></td>
                                <td align="right" class="titulo"><div align="right"><?php echo "R$ ".$cusbolcorrn; ?></div></td>
                            </tr>
                            <tr>
                                <td class="titulo"><div align="left">&nbsp;&nbsp;&nbsp;&nbsp;Material Publicit&aacute;rio ( Kit Material Completo )</div></td>
                                <td align="right" class="titulo"><div align="right"><?php echo "R$ ".$material_public; ?></div></td>
                            </tr>
                            <tr>
                                <td class="titulo"><div align="left">&nbsp;&nbsp;&nbsp;&nbsp;ISS - Imposto de Notas de Servi&ccedil;os (5%)</div></td>
                                <td align="right" class="titulo"><div align="right"><?php echo "R$ ".number_format($iss_faturamento, 2, ',', '.'); ?></div></td>
                            </tr>
                            <tr>
                                <td class="total"><div align="right">TOTAL ( ITEM C )</div></td>
                                <td align="right" class="total"><font size="-1"><div align="right"><?php echo "R$ ".$TOT_D; ?></div></font></td>
                            </tr>
                            <tr>
                                <td class="titulo"><div align="left">D) <font color="#FF0000">Contratos Pendentes a mais de 60 dias (<?php echo $contpen; ?>) - Soma das Faturas</font></div></td>
                                <td align="right" class="titulo"><div align="right"><font color="#FF0000"><?php
                                        $somfatu = number_format($somfat, 2, ',', '.');
                                        echo "R$ " . $somfatu;
                                        ?></font></div></td>
                            </tr>

                            <tr>
                                <?php
                                $sql_taxa_extra_dezembro = "SELECT count(*)as qtd, sum(b.valor) AS total, ( sum(b.valor) * 0.05 ) as receber FROM cs2.cadastro a
                                                            Inner join vr_extra_faturado b on mid(numdoc,5,6) = a.codloja
                                                            where a.id_franquia = '$franqueado' and b.descricao = 'Tx Adicional mes-Dezembro conf. Clausula 11º e 12º'
                                                            and mid(numdoc,1,4) = '" . substr($ano, 2, 2) . $mes . "'";
                                $ql_taxa = mysql_query($sql_taxa_extra_dezembro, $con);
                                while ($array_taxa = mysql_fetch_array($ql_taxa)) {
                                    $qtd_taxa = $array_taxa['qtd'];
                                    $total_taxa = $array_taxa['total'];
                                    $receber_taxa = $array_taxa['receber'];
                                }
                                ?>
                                <td class="titulo"><div align="left">E)<font color="#0000FF"> Taxa Adicional de Dezembro ( <?= $qtd_taxa ?> Clientes Total = <?= number_format($total_taxa, 2, ',', '.') ?>) - B&ocirc;nus 5%</font></div></td>
                                <td align="right" class="titulo"><div align="right"><font color="#0000FF"><?php echo "R$ ".number_format($receber_taxa, 2, ',', '.') ?></font></div></td>
                            </tr>
                            <tr>
                                <td class="titulo"><div align="left">F)<font color="#FF0000"> Faturas &agrave; pagar (<?php echo $qtdrep; ?>)</font></div></td>
                                <td class="titulo"><div align="right"><font color="#FF0000"><?php echo "R$ ".$somrepn_exibe; ?></font></div></td>
                            </tr>
                            <tr>
                                <td class="titulo"><div align="left">G) Participa&ccedil;&atilde;o da &quot;Franqueadora <?php echo $part_franqueadora; ?>%&quot;&nbsp;&nbsp;(A+A1-B-C-D) x <?php echo $part_franqueadora;?>%</div></td>
                                <td class="titulo"><div align="right">
                                    <?php
                                        // echo "[$somfat]";
                                        $part40 = ( ($fatotal + $Tot_GeralCR ) - ( $totgeral + $cusbolcorr + $material_public + $somfat + $iss_faturamento) ) * $coef_franqueadora;
                                        $part40n = number_format($part40, 2, ',', '.');
                                        echo "R$ " . $part40n;
                                        ?></div></td>
                            </tr>
                            <tr>
                                <td class="titulo"><div align="left">H) Participa&ccedil;&atilde;o da &quot;Franquia <?php echo $part_franquia; ?>%&quot;&nbsp;&nbsp;(A+A1-B-C-D) x <?php echo $part_franquia; ?>%</div></td>
                                <td align="right" class="titulo"><div align="right">
                                        <?php
                                        
                                        $Tot_GeralCR = number_format($Tot_GeralCR, 2, '.', '');
                                        $material_public = number_format($material_public, 2, '.', '');
                                        $iss_faturamento = number_format($iss_faturamento, 2, '.', '');
                                        $part60 = ( ($fatotal + $Tot_GeralCR ) - ( $totgeral+$cusbolcorr+$material_public+$iss_faturamento+$somfat) ) * $coef_franquia;
                                        $part60m = $part60; 
                                        $part60n = number_format($part60, 2, ',', '.');
                                        echo "R$ ".$part60n;
                                        ?></div></td>
                            </tr>
                            <tr>
                                <td class="titulo"><div align="left">I) Lucro da Franquia ( H +<font color="#0000FF"> E</font> )</div></td>
                                <td align="right" class="titulo"><div align="right"><font color="#0000FF">
                                        <?php
                                        $total_L = ( $part60m + $receber_taxa + $parl40m );
                                        $total_L = number_format($total_L, 2, ',', '.');
                                        echo "R$ ".$total_L;
                                        ?></font></div></td>
                            </tr>
                            <tr>
                                <td class="titulo"><div align="left">J) Reten&ccedil;&atilde;o p/ Franqueadora ( G + D ) - <font color="#0000FF"> E</font></div></td>
                                <td align="right" class="titulo"><div align="right"><font color="#0000FF">
                                        <?php
                                        $lucro_franqueadora = ( $part40 + $somfat ) - $receber_taxa;
                                        $lucro_franqueadora = number_format($lucro_franqueadora, 2, ',', '.');
                                        echo "R$ ".$lucro_franqueadora;
                                        ?></font></div></td>
                            </tr>
                            <tr>
                                <td class="titulo"><div align="left">L) Reten&ccedil;&atilde;o p/ Fornecedores ( B + C )</div></td>
                                <td align="right" class="titulo"><div align="right"><b>
                                            <?php
                                            $retma = ( $totgeral + $cusbolcorr + $material_public + $iss_faturamento );
                                            $retmat = number_format($retma, 2, ',', '.');
                                            echo "R$ ".$retmat;
                                            ?></b></div></td>
                            </tr>
                        </table></td>
                </tr>
            </table>
            <br />
            <?php
            }else{
            // Calculo da micro franquia
            // echo "Em.. Desenvolvimento.";
            ?>
            <table width="750">
                <tr>
                    <td width="50%"><img src="../img/logowebcontrol.png" border="0" /></td>
                    <td class="pageName">Relat&oacute;rio de Repasses</td>
                </tr>
                <tr class="bodyText">
                    <td>Franquia:
                        <?php
                        $resposta = mysql_query("select razaosoc from franquia where id='$franqueado'", $con);
                        $consulta = mysql_fetch_array($resposta);
                        echo $consulta["razaosoc"];
                        ?>    
                    </td>
                    <td>M&ecirc;s de Refer&ecirc;ncia: <?php echo $mes." - ".$ano; ?></td>
                </tr>
                <tr class="bodyText">
                    <td>Total de Contratos Fechados: <?php echo $totvendas; ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table width="750" class="bodyText">
                            <tr class="titulo">
                                <?php
                                $sql = "SELECT count(*) as total, sum(valor) as valor 
                                FROM titulos a 
                                INNER JOIN cadastro b ON a.codloja=b.codloja 
                                WHERE b.id_franquia = '$franqueado'
                                AND MONTH(vencimento) = '$mes' 
                                AND Year(vencimento)='$ano'";
                                $qr = mysql_query($sql,$con);
                                $qtd = mysql_result($qr,0,'total');
                                $vrtotal_aaa = mysql_result($qr,0,'valor');
                                ?>
                                <td colspan="3"><div align="left">A) Faturamento Total (<?= $qtd ?>)</div></td>
                                <td><div  align="right">R$ <?php echo number_format($vrtotal_aaa, 2, ',', '.'); ?></div></td>
                            </tr>
                            <tr class="titulo">
                                <?php
                                $sql = "SELECT count(*) as total, sum(valor) as valor 
                                FROM titulos a 
                                INNER JOIN cadastro b ON a.codloja=b.codloja 
                                WHERE b.id_franquia = '$franqueado'
                                AND MONTH(vencimento) = '$mes' 
                                AND Year(vencimento)='$ano'
                                AND datapg is not null
                                AND origem_pgto = 'BANCO'";
                                $qr = mysql_query($sql,$con);
                                $qtd = mysql_result($qr,0,'total');
                                $vrtotal_bbb = mysql_result($qr,0,'valor');
                                ?>
                                <td colspan="3"><div align="left">B) Faturas Pagas em Banco (<?= $qtd ?>)</div></td>
                                <td><div  align="right">R$ <?php echo number_format($vrtotal_bbb, 2, ',', '.'); ?></div></td>
                            </tr>
                            <tr class="titulo">
                                <?php
                                $sql = "SELECT count(*) as total, sum(valor) as valor 
                                FROM titulos a 
                                INNER JOIN cadastro b ON a.codloja=b.codloja 
                                WHERE b.id_franquia = '$franqueado'
                                AND MONTH(vencimento) = '$mes' 
                                AND Year(vencimento)='$ano'
                                AND datapg is null";
                                $qr = mysql_query($sql,$con);
                                $qtd = mysql_result($qr,0,'total');
                                $vrtotal_ccc = mysql_result($qr,0,'valor');
                                ?>
                                <td colspan="3"><div align="left">C) Faturas Pendentes de Pagamentos (<?= $qtd ?>)</div></td>
                                <td><div  align="right">R$ <?php echo number_format($vrtotal_ccc, 2, ',', '.'); ?></div></td>
                            </tr>
                            <tr class="titulo">
                                <?php
                                // LINHA D
                                if ( $mes >= 3 ){
                                $xmes = $mes - 2;
                                $xmes = str_pad($xmes,2,0,Str_PAD_LEFT);
                                $xano = $ano;
                                }else{
                                $xmes = ( $mes + 12) - 2;
                                $xmes = str_pad($xmes,2,0,Str_PAD_LEFT);
                                $xano = $ano - 1 ;
                                }
                                $sql = "SELECT count(*) as total, sum(valor) as valor 
                                FROM titulos a 
                                INNER JOIN cadastro b ON a.codloja=b.codloja 
                                LEFT OUTER JOIN pedidos_cancelamento c ON a.codloja = c.codloja
                                WHERE b.id_franquia = '$franqueado'
                                AND dt_cad <= '$xano-$xmes-31'
                                AND pendencia_contratual = 1
                                AND sitcli < 2
                                AND MONTH(vencimento) = '$mes' 
                                AND Year(vencimento)='$ano'
                                AND c.data_documento is NULL";
                                $qr = mysql_query($sql,$con);
                                $qtd = mysql_result($qr,0,'total');
                                $vrtotal_ddd = mysql_result($qr,0,'valor');
                                ?>
                                <td colspan="3"><div align="left">D) Contratos Pendentes a mais de 60 dias (<?= $qtd ?>) - Soma das Faturas</div></td>
                    <td><div  align="right">R$ <?php echo number_format($vrtotal_ddd, 2, ',', '.'); ?></div></td>
                </tr>
                <tr class="titulo">
                    <?php
                    $sql = "SELECT c.valor AS soma FROM franquia a  
                    INNER JOIN cadastro b ON a.id = b.id_franquia 
                    INNER JOIN titulos c ON b.codloja = c.codloja 
                    INNER JOIN logon d ON b.codloja = d.codloja 
                    WHERE a.id = '$franqueado
                    AND sitcli < 2 
                    AND month(c.vencimento) = '$mes'
                    AND Year(c.vencimento) = '$ano' 
                    AND d.franqueado = 'S'";
                    $qr = mysql_query($sql,$con);
                    $soma = mysql_result($qr,0,'soma');
                    ?>
                    <td colspan="3"><div align="left">E) Código Demonstrativo</div></td>
                    <td><div  align="right">R$ <?php echo number_format($soma, 2, ',', '.'); ?></div></td>
                </tr>
                <tr class="titulo">
                    <?php
                    $vrtotal_fff = $vrtotal_ddd + $vrtotal_eee;
                    ?>
                    <td colspan="3"><div align="left">F) Retenção Franqueadora ( D + E )</div></td>
                    <td><div  align="right">R$ <?php echo number_format($vrtotal_fff, 2, ',', '.'); ?></div></td>
                </tr>
            </table>
        </td>

    </tr>
    <?php       
    }
    }else{
        // J� FOI PROCESSADO - MOSTRA O QUE EST� CADASTRADO NO BANCO DE DADOS.
        ?>
        <table width="100%">
            <tr>
                <td class="noprint"><font face="Courier New, Courier, monospace" size="-1"><?php echo $fechamento; ?></font></td>
            </tr>
        </table>
        <?php
        }
    } # FIM do Ingressar
?>