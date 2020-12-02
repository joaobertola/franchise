<?php

require "connect/sessao_r.php";

$a_partir = date("m") - 3;

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
                <td colspan="2">Fechamento de Comissão - Franquia</td>
            </tr>
            <tr>
                <td width="173" class="subtitulodireita">&nbsp;</td>
                <td width="224" class="campoesquerda">&nbsp;</td>
            </tr>
            <tr>
                <td width="173" class="subtitulodireita">Destino</td>
                <td width="224" class="campoesquerda">

                    <input type="radio" name='escolha' value='video' checked="" > Vídeo
                    <input type="radio" name='escolha' value='auto' > Automático

                </td>
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
                    echo "<select name=\"franqueado\">";
                    echo "<option value='TODOS'>TODAS AS FRANQUIAS</option>\n";
                    $sql = "SELECT id, fantasia FROM cs2.franquia WHERE sitfrq=0 AND id_franquia_master = 0 ORDER BY id";
                    $resposta = mysql_query($sql,$con);
                    while ($array = mysql_fetch_array($resposta)) {
                        $franquia = $array["id"];
                        $nome_franquia = $array["fantasia"];
                        echo "<option value='$franquia'>$nome_franquia</option>\n";
                    }
                    echo "</select>";
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

    echo "<pre>";

    $mes = $_REQUEST['mes'];
    $ano = $_REQUEST['ano'];

    $escolha = $_REQUEST['escolha'];
    $franquia = $_REQUEST['franqueado'];


    if ( $escolha == 'auto'){
        // Ver se já foi processado, se sim, nega e volta
        $sql = "SELECT count(*) AS qtd FROM cs2.fechamento_franquia WHERE mes_ano = '$mes-$ano'";
        $qr = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        $Qtd_Processamento = mysql_result($qr, 0, 'qtd');

        if ( $Qtd_Processamento > 0 ){

            echo "<script>alert('Processamento já realizado anteriormente. Verifique !!! ')</script>";
            die;
        }
    }

    if ($mes == 1) {
        $xmes = 12;
        $xano = $ano - 1;
    } else {
        $xmes = $mes - 1;
        $xano = $ano;
    }

    if ( $franquia == 'TODOS' )
        $sql_loop = "SELECT id, fantasia FROM cs2.franquia WHERE classificacao = 'M'";
    else
        $sql_loop = "SELECT id, fantasia FROM cs2.franquia WHERE id = $franquia";
    $qry_loop = mysql_query($sql_loop, $con);
    while ($array_loop = mysql_fetch_array($qry_loop)) {

        $franqueado = $array_loop['id'];
        $nome_franquia = $array_loop["fantasia"];

        $sql = "SELECT count(*) AS qtdCliente FROM cadastro 
                WHERE id_franquia='$franqueado' AND MONTH(dt_cad)='$mes' AND Year(dt_cad)='$xano' AND contadorSN = 'N'";
        $qr = mysql_query($sql, $con)or die("ERRO:  Segundo SQL  ==>  $sql");
        $Qtd_Cliente_Mes_Fechamento = mysql_result($qr, 0, 'qtdCliente');
   
        // $linha .= "======================================================================================================<br>";
        $linha .= "Total de Contratos no Mes do Fechamento $mes/$xano : ( $Qtd_Cliente_Mes_Fechamento )<br>";
        $linha .= "<br>";

        // QUANTIDADE DE CLIENTES FECHADO NO ULTIMOS 3 MESES ANTERIORES
        $sql = "SELECT 
                   concat(substr(subdate(substr(NOW(),1,10), interval 3 month),1,7),'-01') as data_ini,
                   concat(substr(subdate(substr(NOW(),1,10), interval 1 month),1,7),'-31') as data_fim ";
        $qr = mysql_query($sql, $con)or die("ERRO:  Segundo SQL  ==>  $sql");
        $Data_Inicio = mysql_result($qr, 0, 'data_ini');
        $Data_Fim = mysql_result($qr, 0, 'data_fim');
   
        $sql = "SELECT ( count(*) / 3 ) AS qtdCliente FROM cs2.cadastro 
                WHERE id_franquia=$franqueado AND dt_cad BETWEEN '$Data_Inicio' AND '$Data_Fim' AND contadorSN = 'N'";
        $qr = mysql_query($sql, $con)or die("ERRO:  Segundo SQL  ==>  $sql");
        $Qtd_Cliente = mysql_result($qr, 0, 'qtdCliente');

        $Data_Inicio = substr($Data_Inicio,8,2).'/'.substr($Data_Inicio,5,2).'/'.substr($Data_Inicio,0,4);
        $Data_Fim    = substr($Data_Fim,8,2).'/'.substr($Data_Fim,5,2).'/'.substr($Data_Fim,0,4);

        $linha .= "Média de Contratos Fechados nos últimos 3 meses ( $Data_Inicio a $Data_Fim ) : (".round($Qtd_Cliente).")<br>";

        $linha .= "======================================================================================================<br>";
        $linha .= "<br>";


        $sql = "SELECT participacao_franquia, participacao_franqueadora 
                FROM tabela_participacao_franquia 
                WHERE faixa_venda_inicio <= $Qtd_Cliente AND faixa_venda_final >= $Qtd_Cliente";
        $qr = mysql_query($sql, $con)or die("ERRO:  Segundo SQL  ==>  $sql");
        $tx1 = mysql_result($qr, 0, 'participacao_franquia');
        $tx2 = mysql_result($qr, 0, 'participacao_franqueadora');

        // ITEM A
        $sql = "SELECT count(*) AS qtdTitulo, sum(valor) AS ValorTitulo FROM cs2.titulos a
                INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
                WHERE b.id_franquia=$franqueado AND MONTH(vencimento)='$mes' AND Year(vencimento)='$ano'";
        $qr = mysql_query($sql, $con)or die("ERRO:  Segundo SQL  ==>  $sql");
        $Qtd_Titulo = mysql_result($qr, 0, 'qtdTitulo');
        $ItemA      = mysql_result($qr, 0, 'ValorTitulo');

        $Iss_Faturamento = $ItemA * 0.05;

        $linha .= "A) Faturamento (Pacotes,Licenças,Soluções e Pesquisas) - (Total de Clientes : ".STR_PAD($Qtd_Titulo,5,0,STR_PAD_LEFT).")    R$ ".STR_PAD(number_format($ItemA,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";
        $linha .= "<br>";


        // ITEM A1
        $sql = "SELECT count(*) qtd, sum(valorpg) soma FROM titulos_recebafacil a
                INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
                WHERE month(a.datapg)='$xmes' AND Year(a.datapg)='$xano' AND ( tp_titulo = '2' OR tp_titulo = '3' ) AND b.id_franquia = $franqueado";
        $qr = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        $Qtd_Crediario = mysql_result($qr, 0, 'qtd');
        $Tot_Crediario = mysql_result($qr, 0, 'soma');

        //
        $sql = "SELECT count(*) qtd, sum(valorpg) soma FROM titulos_recebafacil a 
                INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
                WHERE month(a.datapg)='$xmes' AND Year(a.datapg)='$xano' 
                      AND ( tp_titulo = '2' OR tp_titulo = '3' ) 
                      AND b.id_franquia = $franqueado
                      AND descricao_repasse like '%CLIENTE RECEBEU O T%'";
        $qr = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        $Qtd_Crediario = $Qtd_Crediario - mysql_result($qr, 0, 'qtd');
        $Tot_Crediario = $Tot_Crediario - mysql_result($qr, 0, 'soma');

        $linha .= "A1) Titulos Liquidados (Cred/Rec/Boleto) - ( Qtd Ttulos: ".str_pad($Qtd_Crediario, 5, 0, STR_PAD_LEFT)." - R$ ".number_format($Tot_Crediario,2,',','.')." x 2,5% )   R$ ".number_format($Tot_Crediario*0.025,2,',','.')."<br>";

        $ItemA1 = ( $Tot_Crediario * 0.025 );

        //ITEM B
        $ItemB = 0;
        $sql = "SELECT a.tpcons, mid(c.nome,1,40) AS nome_cons, SUM(a.qtdcons) AS qtd_cons, 
                       c.vr_custo, SUM( (a.qtdcons * c.vr_custo) ) AS total_custo
                FROM totcons a
                INNER JOIN cadastro b ON a.codloja = b.codloja
                INNER JOIN valcons c ON a.tpcons = c.codcons
                WHERE b.id_franquia='$franqueado' AND MID(a.dtsoma,1,7)='$xano-".str_pad($mes,2,0,STR_PAD_LEFT)."' 
                GROUP BY a.tpcons";
        $qry = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");

        $linha .= "<br>";
        $linha .= "B) Custo de todas as Soluções e Pesquisas<br>";
        $linha .= "<br>";
        $linha .= "Nome das Solucoes/Pesquisas                 Qtd. Realizada                Vr. Custo          Vr. Total<br>";
        $linha .= "======================================================================================================<br>";
        $linha .= "<br>";

        while ($array = mysql_fetch_array($qry)) {

            $nome_cons   = $array['nome_cons'];
            $qtd_cons    = $array['qtd_cons'];
            $vr_custo    = $array['vr_custo'];
            $total_custo = $array['total_custo'];

            $linha .= str_pad($nome_cons,40,' ',STR_PAD_RIGHT).'        '.
                      str_pad( $qtd_cons,6,0,STR_PAD_LEFT).'               R$ '.
                      str_pad( number_format($vr_custo,2,',','.'),10,' ',STR_PAD_RIGHT).'      R$ '.
                      str_pad( number_format($total_custo,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

            $ItemB = $ItemB + $total_custo;
        };

        $dt_inicio = $xano . '-' . $xmes . '-01';
        $dt_fim    = $xano . '-' . $xmes . '-31';

        // Cobrando CONSULTA LOCALIZA NOVOS CLIENTES

        $sql = "SELECT vr_custo FROM cs2.valcons WHERE codcons = 'A0231'";
        $qr = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        $custo = mysql_result($qr, 0, 'vr_custo');

        $sql = "SELECT SUM(a.qtd_registro) as tot_localiza_novos_cliente
                FROM cons_localiza a
                INNER JOIN logon b     ON a.codloja = b.codloja
                INNER JOIN cadastro c  ON a.codloja = c.codloja
                WHERE
                   c.id_franquia = '$franqueado' AND a.data BETWEEN '$dt_inicio' AND '$dt_fim'";
        $qr = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        $tot_localiza_novos_cliente = mysql_result($qr, 0, 'tot_localiza_novos_cliente');

        $lixo = $custo * $tot_localiza_novos_cliente;

        $linha .= str_pad('Localiza Max - Novos Clientes',48,' ',STR_PAD_RIGHT).
                  str_pad($tot_localiza_novos_cliente,6,'0',STR_PAD_LEFT).'               R$ '.
                  str_pad(number_format($custo,2,',','.'),10,' ',STR_PAD_RIGHT).'      R$ '.
                  str_pad(number_format($lixo,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        $ItemB = $ItemB + $lixo;

        // Cobrando POSTAGEM CORREIO - CREDIARIO/RECUPERE / BOLETO

        $sql = "SELECT vr_custo FROM cs2.valcons WHERE codcons = 'CC101'";
        $qr = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        $custo = mysql_result($qr, 0, 'vr_custo');

        $tot_postagem = 0;

        $sql = "SELECT DISTINCT(a.chavebol), a.codloja FROM cs2.titulos_recebafacil a
                INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
                WHERE b.id_franquia = '$franqueado' AND a.data_impresso BETWEEN '$dt_inicio' AND '$dt_fim'
                GROUP BY a.codloja";
        $qry = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        while ($array = mysql_fetch_array($qry)) {
            $tot_postagem++;
        } 

        $lixo = $custo * $tot_postagem;

        $linha .= str_pad('Postagem Correio - Cred/Rec/Bol',48,' ',STR_PAD_RIGHT).
                  str_pad($tot_postagem,6,'0',STR_PAD_LEFT).'               R$ '.
                  str_pad(number_format($custo,2,',','.'),10,' ',STR_PAD_RIGHT).'      R$ '.
                  str_pad(number_format($lixo,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        $ItemB = $ItemB + $lixo;

        // Cobrando Encaminhamento para Protesto
        $tot_encaminhamento = 0;
        $sql = "SELECT vr_custo FROM cs2.valcons WHERE codcons = 'T0001'";
        $qr = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        $custo = mysql_result($qr, 0, 'vr_custo');

        $sql = "SELECT count(*), a.codloja from consulta.alertas a
                INNER JOIN cadastro b ON a.codloja = b.codloja
                WHERE b.id_franquia = '$franqueado' AND a.dt_envio_cartorio BETWEEN '$dt_inicio' AND '$dt_fim'
                GROUP BY a.codloja";
        $qry = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        while ($array = mysql_fetch_array($qry)) {
            $tot_encaminhamento++;
        } 

        $lixo = $custo * $tot_encaminhamento;

        $linha .= str_pad('Encaminhamento para Protesto',48,' ',STR_PAD_RIGHT).
                  str_pad($tot_encaminhamento,6,'0',STR_PAD_LEFT).'               R$ '.
                  str_pad(number_format($custo,2,',','.'),10,' ',STR_PAD_RIGHT).'      R$ '.
                  str_pad(number_format($lixo,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        $ItemB = $ItemB + $lixo;

        // Cobrando RECOMENDE O CLIENTE

        $tot_recomende = 0;
        $sql = "SELECT vr_custo FROM cs2.valcons WHERE codcons = 'T0002'";
        $qr = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        $custo = mysql_result($qr, 0, 'vr_custo');

        $sql = "SELECT count(*), a.codloja from cs2.relacionamento_consumidor a
                INNER JOIN cadastro b ON a.codloja = b.codloja
                WHERE b.id_franquia = '$franqueado' AND a.amd BETWEEN '$dt_inicio' AND '$dt_fim'
                GROUP BY a.codloja";
        $qry = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        while ($array = mysql_fetch_array($qry)) {
            $tot_recomende++;
        } 

        $lixo = $custo * $tot_recomende;

        $linha .= str_pad('Recomende o Cliente',48,' ',STR_PAD_RIGHT).
                  str_pad($tot_recomende,6,'0',STR_PAD_LEFT).'               R$ '.
                  str_pad(number_format($custo,2,',','.'),10,' ',STR_PAD_RIGHT).'      R$ '.
                  str_pad(number_format($lixo,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        $ItemB = $ItemB + $lixo;

        // Custo de Licença de Software......

        $sql = "SELECT vr_custo FROM cs2.valcons WHERE codcons = 'LSOF'";
        $qr = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        $custo_lic = mysql_result($qr, 0, 'vr_custo');

        $total_solucoes = $custo_lic * $Qtd_Titulo;

        $ItemB = $ItemB + $total_solucoes;


        // Custo Titulo baixado no Estabelecimento
        $sql = "SELECT vr_custo, nome FROM cs2.valcons WHERE codcons = 'TBE01'";
        $qr = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        $custo_BX = mysql_result($qr, 0, 'vr_custo');
        $texto_BX = mysql_result($qr, 0, 'nome');

        // BAIXA DE TITULO NO ESTABELECIMENTO
        $sql = "SELECT count(*) qtd_bx from titulos_recebafacil a
                INNER JOIN cadastro b ON a.codloja = b.codloja
                WHERE a.valorpg > 0
                  AND b.id_franquia = $franqueado
                  AND a.descricao_repasse = 'CLIENTE RECEBEU O TITULO NO ESTABELECIMENTO'
                  AND MONTH(a.datapg)='$mes' and Year(a.datapg)='$ano'";
        $qr = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        $Qtd_BX = mysql_result($qr, 0, 'qtd_bx');

        if ( $Qtd_BX == '' ) 
            $Qtd_BX = '0';

        $ItemB = $ItemB + ($Qtd_BX * $custo_BX) ;

        // Custo TORPEDO MARKETING

        $sql = "SELECT vr_custo, nome FROM cs2.valcons WHERE codcons = 'TM001'";
        $qr = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        $custo_torpedo = mysql_result($qr, 0, 'vr_custo');
        $texto_torpedo = mysql_result($qr, 0, 'nome');

        $Qtd_Torpedo = 0;

        $sql01 = "SELECT a.listas FROM base_web_control.torpedo_campanha a
                  INNER JOIN cs2.cadastro b ON a.id_cadastro = b.codloja
                  WHERE b.id_franquia = '$franqueado' AND a.status_campanha = 'E' 
                     AND a.dt_last_update BETWEEN '$xano-$xmes-01 00:00:01' AND '$xano-$xmes-31 23:59:59'";
        $qry01 = mysql_query($sql01,$con) or die ("$sql01");
        while ( $reg = mysql_fetch_array( $qry01 ) ){
            $listas  = unserialize($reg['listas']);
            foreach ($listas as $v) {
                if (!empty($v)) {
                    $idLista = $v;
                    $sql02 = "SELECT count(telefone) AS qtd FROM base_web_control.torpedo_lista_telefones
                              WHERE id_lista = $idLista";
                    $qry02 = mysql_query($sql02,$con) or die ("$sql02");
                    $Qtd_Torpedo += mysql_result($qry02, 0, 'qtd');
                }
            }
        }

        $ItemB = $ItemB + ($Qtd_Torpedo*$custo_torpedo) ;

        // Custo WHATSAPP MARKETING

        $Qtd_WhatsApp = 0;

        $sql = "SELECT vr_custo, nome FROM cs2.valcons WHERE codcons = 'WM001'";
        $qr = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        $custo_whatsapp = mysql_result($qr, 0, 'vr_custo');
        $texto_whatsapp = mysql_result($qr, 0, 'nome');

        $sql01 = "SELECT a.listas FROM base_web_control.whatsapp_campanha a
                  INNER JOIN cs2.cadastro b ON a.id_cadastro = b.codloja
                  WHERE b.id_franquia = $franqueado AND a.status_campanha = 'E'
                    AND a.dt_last_update BETWEEN '$xano-$xmes-01 00:00:01' AND '$xano-$xmes-31 23:59:59'";
        $qry01 = mysql_query($sql01,$con) or die ("$sql01");
        while ( $reg = mysql_fetch_array( $qry01 ) ){
            $listas  = unserialize($reg['listas']);
            foreach ($listas as $v) {
                if (!empty($v)) {
                    $idLista = $v;
                    $sql02 = "SELECT count(telefone) AS qtd FROM base_web_control.whatsapp_lista_telefones
                              WHERE id_lista = $idLista";
                    $qry02 = mysql_query($sql02,$con) or die ("$sql02");
                    $Qtd_WhatsApp += mysql_result($qry02, 0, 'qtd');
                }
            }
        }

        $ItemB = $ItemB + ($Qtd_WhatsApp*$custo_whatsapp) ;

        $soma  = $ItemB;

        $linha .=  str_pad('Custo Licencas - Softwares/Solucoes',48,' ',STR_PAD_RIGHT).
                   str_pad($Qtd_BX,6,'0',STR_PAD_LEFT).'               R$ '.
                   str_pad(number_format($custo_lic,2,',','.'),10,' ',STR_PAD_RIGHT).'      R$ '.
                   str_pad(number_format($total_solucoes,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        $linha .=  str_pad($texto_BX,48,' ',STR_PAD_RIGHT).
                   str_pad($Qtd_Titulo,6,'0',STR_PAD_LEFT).'               R$ '.
                   str_pad(number_format($custo_lic,2,',','.'),10,' ',STR_PAD_RIGHT).'      R$ '.
                   str_pad(number_format($Qtd_BX*$custo_BX,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        $linha .=  str_pad($texto_torpedo,48,' ',STR_PAD_RIGHT).
                   str_pad($Qtd_Torpedo,6,'0',STR_PAD_LEFT).'               R$ '.
                   str_pad(number_format($custo_torpedo,2,',','.'),10,' ',STR_PAD_RIGHT).'      R$ '.
                   str_pad(number_format($Qtd_Torpedo*$custo_torpedo,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        $linha .=  str_pad($texto_whatsapp,48,' ',STR_PAD_RIGHT).
                   str_pad($Qtd_WhatsApp,6,'0',STR_PAD_LEFT).'               R$ '.
                   str_pad(number_format($custo_whatsapp,2,',','.'),10,' ',STR_PAD_RIGHT).'      R$ '.
                   str_pad(number_format($Qtd_WhatsApp*$custo_whatsapp,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        $linha .= "======================================================================================================<br>";
        $linha .= str_pad('Total de Custos das Solucoes e Pesquisas',87,' ',STR_PAD_RIGHT).' R$ '.
                  str_pad(number_format($ItemB,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";



        // inclusao de RESTRITO LOCAL;

        $sql = "SELECT count(*) AS qtd, (count(*) * c.vr_custo) AS total_custo FROM consulta.alertas a
                INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
                INNER JOIN cs2.valcons  c ON c.codcons='B201'
                WHERE b.id_franquia='$franqueado' AND month(a.data_cadastro)='$xmes' AND Year(a.data_cadastro)='$xano'
                    AND a.situacao = 'N' AND a.destino_negativos = 1
                GROUP BY b.id_franquia";
        $qr = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        $qtd = mysql_result($qr, 0, 'qtd');
        $total_custo = mysql_result($qr, 0, 'total_custo');

        if ( $total_custo == '' ) $total_custo = 0;
        $vtotal_custo = number_format($total_custo,2,',','.');

        $linha .= str_pad('BLOQUEIO DEVEDORES - Registro On Line Brasil : '.$qtd,87,' ',STR_PAD_RIGHT).
                  ' R$ '.str_pad($vtotal_custo,10,' ',STR_PAD_LEFT)."<br>";

        $soma += $total_custo;

        // Exclusao RESTRITO LOCAL

        $sql = "SELECT count(*) , (count(*) * c.vr_custo), c.vr_custo FROM consulta.alertas a
                INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
                INNER JOIN cs2.valcons  c ON c.codcons='C201'
                WHERE b.id_franquia='$franqueado' AND month(a.data_exclusao)='$xmes' AND Year(a.data_exclusao)='$xano'
                      AND a.situacao = 'E' AND a.destino_negativos = 1
                GROUP BY b.id_franquia";
        $qr = mysql_query($sql, $con) or die("ERRO:  Segundo SQL  ==>  $sql");
        $qtd = mysql_result($qr, 0, 'qtd');
        $total_custo = mysql_result($qr, 0, 'total_custo');

        if ( $total_custo == '' ) $total_custo = 0;

        $linha .= str_pad('DESBLOQUEIO DEVEDORES - Registro On Line Brasil : '.$qtd,87,' ',STR_PAD_RIGHT).
                  ' R$ '.str_pad(number_format($total_custo,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        $soma = $soma + $total_custo;

        // BONIFICACAO dos codigos demonstrativos INCLUSAO RESTRITO LOCAL

        $sql = "SELECT (count(*) * c.vr_custo) AS total_custo FROM consulta.alertas a
                INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
                INNER JOIN cs2.valcons  c ON c.codcons='B201'
                INNER JOIN cs2.logon d ON a.codloja=d.codloja
                WHERE b.id_franquia='$franqueado' 
                    AND month(a.data_cadastro)='$xmes' 
                    AND Year(a.data_cadastro)='$xano' 
                    AND d.franqueado = 'S'
                    AND a.situacao = 'N' 
                    AND a.destino_negativos = 1
                GROUP BY b.id_franquia";
        $qr = mysql_query($sql, $con) or die("ERRO: Segundo SQL ==> $sql");

        $bonif_inc_local = mysql_result($qr, 0, 'total_custo');

        // BONIFICACAO dos codigos demonstrativos EXCLUSAO RESTRITO LOCAL

        $sql = "SELECT (count(*) * c.vr_custo) AS total_custo FROM consulta.alertas a
                INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
                INNER JOIN cs2.valcons  c ON c.codcons='C201'
                INNER JOIN cs2.logon d ON a.codloja=d.codloja
                WHERE b.id_franquia='$franqueado' 
                    AND month(a.data_exclusao)='$xmes'
                    AND Year(a.data_exclusao)='$xano' 
                    AND d.franqueado = 'S'
                    AND a.situacao = 'E' 
                    AND a.destino_negativos = 1
                GROUP BY b.id_franquia";
        $qr = mysql_query($sql, $con) or die ( "ERRO: Segundo SQL ==> $sql" );
        $bonif_exc_local = mysql_result($qr, 0, 'total_custo');

        $soma -= ($bonif_inc_local + $bonif_exc_local);

        $linha .= str_pad('Bonif Codigos Demonstrativos (Bloq e Desb) : '.$qtd,87,' ',STR_PAD_RIGHT).
                  ' R$ '.str_pad(number_format($bonif_exc_local,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        // BONIFICACAO MASTER E LOCALIZA
        $tot_200 = 0;
        $tot_230 = 0;
        $sql = "SELECT b.id_franquia, b.razaosoc, b.nomefantasia, a.logon, a.codloja,
                       (SELECT count(*) from cons WHERE codloja=a.codloja AND MONTH(amd)='$xmes' AND YEAR(amd)='$xano' AND debito='A0200') AS A0200,
                       (SELECT count(*) from cons WHERE codloja=a.codloja AND MONTH(amd)='$xmes' AND YEAR(amd)='$xano' AND debito='A0230') AS A0230,
                       (SELECT count(*) from cons WHERE codloja=a.codloja AND MONTH(amd)='$xmes' AND YEAR(amd)='$xano' AND debito='F0200') AS F0200,
                       (SELECT count(*) from cons WHERE codloja=a.codloja AND MONTH(amd)='$xmes' AND YEAR(amd)='$xano' AND debito='A0232') AS A0232, 
                       (SELECT sum(qtd_registro) from cons_localiza WHERE codloja=a.codloja AND MONTH(data)='$xmes' AND YEAR(data)='$xano' AND tipo_consulta='A0231') AS A0231
                FROM logon a
                INNER JOIN cadastro b ON a.codloja = b.codloja
                WHERE b.id_franquia = '$franqueado' AND a.franqueado = 'S'";
        $qr = mysql_query($sql, $con) or die ( "ERRO: Segundo SQL ==> $sql" );


        while ($array = mysql_fetch_array($qr)) {
            $A0200 = $array["A0200"];
            $F0200 = $array["F0200"];
            $A0231 = $array["A0231"];
            $tot_200 += ($A0200 + $F0200);
            $tot_231 += $A0231;

            $A0230 = $array["A0230"];

            $A0200 = $array["A0200"];
            $A0232 = $array["A0232"];
            $A0200 = $array["A0200"];
            $A0200 = $array["A0200"];

            $tot_230 += ($A0230 + $A0232);

            $codloja =  $array["codloja"];
        }

        if ( $tot_200 >= 300 ) $tot_200 = 300;
        if ( $tot_230 >= 200 ) $tot_230 = 200;
        if ( $tot_231 >= 5000 ) $tot_231 = 5000;

        if ( $codloja <> '' ){

            // Buscando o valor de custo
            $sql = "SELECT codcons, valorcons FROM cs2.valconscli
                    WHERE codloja='$codloja' AND ( codcons = 'A0200' OR codcons = 'A0230')";
            $qr = mysql_query($sql, $con) or die ( "ERRO: Segundo SQL ==> $sql" );
            while ($array = mysql_fetch_array($qr)) {
               $codcons   = $array['codcons'];
               $valorcons = $array['valorcons'];
               if ( $codcons == 'A0200' ) $vr_A0200 = $tot_200 * $valorcons;
               if ( $codcons == 'A0230' ) $vr_A0230 = $tot_230 * $valorcons;
               if ( $codcons == 'A0231' ) $vr_A0231 = $tot_231 * $valorcons;
            }
        
            $total_bonificacao = $vr_A0200 + $vr_A0230 + $vr_A0231;
        }
        else
            $total_bonificacao = 0;

        $linha .= str_pad("Bonif Codigos Demonstrativos ($tot_200 Master - $tot_230 Localiza - $tot_231 Localiza Novos Clientes)",87,' ',STR_PAD_RIGHT).
                  ' R$ '.str_pad(number_format($total_bonificacao,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        $soma -= $total_bonificacao;

        $linha .= "======================================================================================================<br>";
        $linha .= str_pad('Total Geral',87,' ',STR_PAD_RIGHT).
                  ' R$ '.
                  str_pad(number_format($soma,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        // ITEM C

        $sql = "SELECT count(*) * (SELECT vr_custo FROM cs2.valcons WHERE codcons = 'CBC') AS total FROM titulos a
                INNER JOIN cadastro b on a.codloja=b.codloja
                WHERE b.id_franquia='$franqueado' AND MONTH(vencimento)='$mes' AND Year(vencimento)='$ano'";
        $qr = mysql_query($sql, $con) or die ( "ERRO: Segundo SQL ==> $sql" );
        $total = mysql_result($qr, 0, 'total');

        $ItemC = $total;
   
        $linha .= "<br>";
        $linha .= str_pad('C) Custo Cobranca Bancaria + Postagem Correio + Conf. Grafica ('.str_pad($Qtd_Titulo,5,0,STR_PAD_LEFT).' x 6,45)',87,' ',STR_PAD_RIGHT).
                  ' R$ '.
                  str_pad(number_format($ItemC,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        // Material Publicitario 6 meses

        $sql = "SELECT DATEDIFF(now(), dt_cad) AS dias FROM cs2.franquia WHERE id = $franqueado";
        $qr = mysql_query($sql, $con) or die ( "ERRO: Segundo SQL ==> $sql" );
        $dias = mysql_result($qr, 0, 'dias');
        if ( $dias > 180 ) 
            $Mat_Public = 750;
        else $Mat_Public = 0;

        $linha .= str_pad('   Material Publicitario ( Kit Material Completo )',87,' ',STR_PAD_RIGHT).
                  ' R$ '.str_pad(number_format($Mat_Public,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        $linha .= str_pad('   ISS - Imposto de Notas de Servicos (5%)',87,' ',STR_PAD_RIGHT).
                  ' R$ '.str_pad(number_format($Iss_Faturamento,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        $linha .= "======================================================================================================<br>";

        $ItemC = $ItemC + $Mat_Public + $Iss_Faturamento;

        $linha .= str_pad('Total item C',87,' ',STR_PAD_RIGHT).' R$ '.str_pad(number_format($ItemC,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        // ITEM D
        $data = str_pad($xano,4,0,STR_PAD_LEFT).'-'.str_pad($xmes-1,2,0,STR_PAD_LEFT).'-31';

        $sql = "SELECT count(*) AS qtd,sum(valor) AS soma_total FROM titulos a 
                INNER JOIN cadastro b ON a.codloja=b.codloja 
                LEFT OUTER JOIN pedidos_cancelamento c ON a.codloja=c.codloja 
                WHERE b.id_franquia='$franqueado' AND dt_cad <= '$data' AND pendencia_contratual = 1 AND sitcli < 2 AND month(vencimento)='$mes' AND Year(vencimento)='$ano' AND c.data_documento is NULL";
        $qr = mysql_query($sql, $con) or die ( "ERRO: Segundo SQL ==> $sql" );
        $Qtd_Pende = mysql_result($qr, 0, 'qtd');
        $ItemD = mysql_result($qr, 0, 'soma_total');

        $linha .= "<br>";
        $linha .= str_pad('D) Contratos Pendentes a mais de 60 dias  ( '.str_pad($Qtd_Pende,5,0,STR_PAD_LEFT).' ) - Soma das Faturas ->',87,' ',STR_PAD_RIGHT).
                  ' R$ '.
                  str_pad(number_format($ItemD,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";


        // TAXA ADICIONAL MES DEZEMBRO

        $sql = "SELECT count(*) AS qtd, SUM(b.valor) AS total, ( SUM(b.valor) * 0.05 ) AS receber FROM cs2.cadastro a
                INNER JOIN vr_extra_faturado b ON mid(numdoc,5,6) = a.codloja
                WHERE a.id_franquia = '$franqueado' AND b.descricao = 'Tx Adicional mes-Dezembro conf. Clausula 11º e 12º'
                      AND mid(numdoc,1,4) = ".substr($ano,2,2).$mes;
        $qr = mysql_query($sql, $con) or die ( "ERRO: Segundo SQL ==> $sql" );

        while ($array = mysql_fetch_array( $qr) ){
            $qtd_taxa   = $array['qtd'];
            $total_taxa = $array['total'];
            $ItemE      = $array['receber'];
        }

        $linha .= str_pad("E) Taxa Adicional de Dezembro ( $qtd_taxa Clientes Total = $total_taxa ) Bonus 5%",87,' ',STR_PAD_RIGHT).
                  ' R$ '.
                  str_pad(number_format($ItemE,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";


        // FATURAS PENDENTES DE PAGAMENTO

        $sql = "SELECT count(*) AS qtd, SUM(valor) AS soma_valor, origem_pgto FROM titulos a 
                INNER JOIN cadastro b ON a.codloja=b.codloja 
                WHERE b.id_franquia='$franqueado' AND MONTH(vencimento)='$mes' AND Year(vencimento)='$ano' 
                GROUP BY origem_pgto";
        $qr = mysql_query($sql, $con) or die ( "ERRO: Segundo SQL ==> $sql" );

        $qtdx = 0;
        $ItemJ = 0;
        while ($array = mysql_fetch_array( $qr) ){
            $origem_pgto = $array['origem_pgto'];
            $soma_valor  = $array['soma_valor'];
            $qtd         = $array['qtd'];

            if ( $origem_pgto <> 'BANCO' ){
               $qtdx  += $qtd;
               $ItemJ += $soma_valor;
           }
        }

        $linha .= str_pad("F) Faturas a Pagar ($qtdx)",87,' ',STR_PAD_RIGHT).
                  ' R$ '.str_pad(number_format($ItemJ,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        $ItemG = ( ( $ItemA + $ItemA1 ) - ( $soma + $ItemC + $ItemD ) ) * ($tx2/100);

        $linha .= "<br>";
        $linha .= str_pad('G) Participacao da "Franqueadora '.$tx2.'%" - (A+A1-B-C-D) x '.$tx2.'%',87,' ',STR_PAD_RIGHT).
                  ' R$ '.str_pad(number_format($ItemG,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        $ItemH = ( ( $ItemA + $ItemA1 ) - ( $soma + $ItemC + $ItemD ) ) * ($tx1/100);

        $linha .= "<br>";
        $linha .= str_pad('H) Participacao da "Franquia '.$tx1.'%" - (A+A1-B-C-D) x '.$tx1.'%',87,' ',STR_PAD_RIGHT).
                  ' R$ '.str_pad(number_format($ItemH,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        $ItemI = $ItemH + $ItemE;

        $linha .= "<br>";
        $linha .= str_pad('I) Lucro da Franquia ( H + E )',87,' ',STR_PAD_RIGHT).
                  ' R$ '.str_pad(number_format($ItemI,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";


        $ItemJ = ( $ItemG + $ItemD ) - $ItemE;

        $linha .= "<br>";
        $linha .= str_pad('J) Retencao p/ Franqueadora ( G + D ) - E',87,' ',STR_PAD_RIGHT).
                  ' R$ '.str_pad(number_format($ItemJ,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";


        $ItemL = ( $soma + $ItemC );

        $linha .= "<br>";
        $linha .= str_pad('L) Retencao p/ Fornecedores ( B + C )',87,' ',STR_PAD_RIGHT).
                  ' R$ '.str_pad(number_format($ItemL,2,',','.'),10,' ',STR_PAD_LEFT)."<br>";

        if ( $escolha == 'video'){

            $linhav  = "------------------------------------------------------------------------------------------------------<br>";
            $linhav .= "WEB CONTROL EMPRESAS                                                                       ".date('d/m/Y')."<br>";
            $linhav .= "Relatório Financeiro - Franquia                                                            ".date('H:m:i')."<br>";
            $linhav .= "------------------------------------------------------------------------------------------------------<br>";
            $linhav .= "Franquia       : ".$nome_franquia."<br>";
            $linhav .= "Mês Referência : $mes - $ano <br>";
            $linhav .= "------------------------------------------------------------------------------------------------------<br>";
            $linhav .= $linha;
            $linhav .= "<br>";
            $linhav .= "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br>";
            $linhav .= "<br>";
            $linhav .= "<br>";
            $linha = '';
            echo $linhav;

        }else{

            $linhav  = "------------------------------------------------------------------------------------------------------<br>";
            $linhav .= "WEB CONTROL EMPRESAS                                                                       ".date('d/m/Y')."<br>";
            $linhav .= "Relatório Financeiro - Franquia                                                            ".date('H:m:i')."<br>";
            $linhav .= "------------------------------------------------------------------------------------------------------<br>";
            $linhav .= "Franquia       : ".$nome_franquia."<br>";
            $linhav .= "Mês Referência : $mes - $ano <br>";
            $linhav .= "------------------------------------------------------------------------------------------------------<br>";
            $linhav .= $linha;
            $linhav .= "<br>";
            $linhav .= "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br>";
            $linhav .= "<br>";
            $linhav .= "<br>";
            $linha = '';

            // Insere na tabela conta corrente o Item J e o Item L
            $txt = "Retencao p/ Franqueadora ( G + D ) - E  Ref.: $mes/$ano";
            $sql = "INSERT INTO cs2.contacorrente(franqueado,data,operacao,discriminacao,valor)
                    VALUES( $franqueado, now(), '1', '$txt', '$ItemJ' )";
            $qr = mysql_query($sql, $con) or die ( "ERRO: Segundo SQL ==> $sql" );

            $txt = "Retencao p/ Fornecedores ( B + C )  Ref.: $mes/$ano";
            $sql = "INSERT INTO cs2.contacorrente(franqueado,data,operacao,discriminacao,valor)
                    VALUES( $franqueado, now(), '1', '$txt', '$ItemL' )";
            $qr = mysql_query($sql, $con) or die ( "ERRO: Segundo SQL ==> $sql" );

            // insere na tabela que ja foi processado
            $sql = "INSERT INTO cs2.fechamento_franquia(id_franquia,data,mes_ano,detalhe)
                    VALUES('$franqueado',now(),'$mes-$ano','$linhav')";
            $qr = mysql_query($sql, $con) or die ( "ERRO: Segundo SQL ==> $sql" );

        }
    }
    echo "<script>alert('Término do processamento')</script>";
} # FIM do Ingressar
?>