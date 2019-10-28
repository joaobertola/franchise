<?php

$id_franquia = $_REQUEST['id'];
$idtable = $_REQUEST['tabela'];

$con = @mysql_pconnect("10.2.2.3", "csinform", "inform4416#scf");

$sql = "SELECT codcons, nome, valor FROM cs2.valcons WHERE
             codcons IN (
                 'A0700', 'A0230', 'A0200', 'A0710', 'A0202', 'A0700', 'A0203', 'A0201','A0207', 
                 'A0208', 'A0301', 'A0406', 'A0408', 'A0405', 'A0407', 'A0410', 'A0400', 'A0401', 
                 'A0404', 'A0403', 'A0402', 'AB201', 'T0001', 'T0002','B201', 'C201', 'B201', 'U0200',
                 'U0201','U0202','U0301','A0710','A0208', 'D201', 'E201', 'A0302' , 'A0711' , 'A0115' , 
                 'A0231', 'SIT', 'LVTC', 'EM001', 'TM001', 'EM001','LE001', 'CREPR','WM001'
                 ) ORDER BY codcons ASC ";
$qry = mysql_query($sql);
while ($rs = mysql_fetch_array($qry)) {
    $codcons = $rs['codcons'];
    if ($codcons == 'A0201') {
        $A0201_valor = $rs['valor'];
        $A0201_nome = $rs['nome'];
    } else if ($codcons == 'A0115') {
        $A0115_valor = $rs['valor'];
        $A0115_nome = $rs['nome'];
    } else if ($codcons == 'A0230') {
        $A0230_valor = $rs['valor'];
        $A0230_nome = $rs['nome'];
    } else if ($codcons == 'A0231') {
        $A0231_valor = $rs['valor'];
        $A0231_nome = $rs['nome'];
    } else if ($codcons == 'A0200') {
        $A0200_valor = $rs['valor'];
        $A0200_nome = $rs['nome'];
    } else if ($codcons == 'A0710') {
        $A0710_valor = $rs['valor'];
        $A0710_nome = $rs['nome'];
    } else if ($codcons == 'A0202') {
        $A0202_valor = $rs['valor'];
        $A0202_nome = $rs['nome'];
    } else if ($codcons == 'A0700') {
        $A0700_valor = $rs['valor'];
        $A0700_nome = $rs['nome'];
    } else if ($codcons == 'A0203') {
        $A0203_valor = $rs['valor'];
        $A0203_nome = $rs['nome'];
    } else if ($codcons == 'A0207') {
        $A0207_valor = $rs['valor'];
        $A0207_nome = $rs['nome'];
    } else if ($codcons == 'A0208') {
        $A0208_valor = $rs['valor'];
        $A0208_nome = $rs['nome'];
    } else if ($codcons == 'A0301') {
        $A0301_valor = $rs['valor'];
        $A0301_nome = $rs['nome'];
    } else if ($codcons == 'A0302') {
        $A0302_valor = $rs['valor'];
        $A0302_nome = $rs['nome'];
    } else if ($codcons == 'A0406') {
        $A0406_valor = $rs['valor'];
        $A0406_nome = $rs['nome'];
    } else if ($codcons == 'A0408') {
        $A0408_valor = $rs['valor'];
        $A0408_nome = $rs['nome'];
    } else if ($codcons == 'A0405') {
        $A0405_valor = $rs['valor'];
        $A0405_nome = $rs['nome'];
    } else if ($codcons == 'A0407') {
        $A0407_valor = $rs['valor'];
        $A0407_nome = $rs['nome'];
    } else if ($codcons == 'A0410') {
        $A0410_valor = $rs['valor'];
        $A0410_nome = $rs['nome'];
    } else if ($codcons == 'A0400') {
        $A0400_valor = $rs['valor'];
        $A0400_nome = $rs['nome'];
    } else if ($codcons == 'A0401') {
        $A0401_valor = $rs['valor'];
        $A0401_nome = $rs['nome'];
    } else if ($codcons == 'A0404') {
        $A0404_valor = $rs['valor'];
        $A0404_nome = $rs['nome'];
    } else if ($codcons == 'A0403') {
        $A0403_valor = $rs['valor'];
        $A0403_nome = $rs['nome'];
    } else if ($codcons == 'A0402') {
        $A0402_valor = $rs['valor'];
        $A0402_nome = $rs['nome'];
    } else if ($codcons == 'A0711') {
        $A0711_valor = $rs['valor'];
        $A0711_nome = $rs['nome'];
    } else if ($codcons == 'AB201') {
        $AB201_valor = $rs['valor'];
        $AB201_nome = $rs['nome'];
    } else if ($codcons == 'T0001') {
        $T0001_valor = $rs['valor'];
        $T0001_nome = $rs['nome'];
    } else if ($codcons == 'T0002') {
        $T0002_valor = $rs['valor'];
        $T0002_nome = $rs['nome'];
    } else if ($codcons == 'B201') {
        $B201_valor = $rs['valor'];
        $B201_nome = $rs['nome'];
    } else if ($codcons == 'C201') {
        $C201_valor = $rs['valor'];
        $C201_nome = $rs['nome'];
    } else if ($codcons == 'B201') {
        $B201_valor = $rs['valor'];
        $B201_nome = $rs['nome'];
    } else if ($codcons == 'U0200') {
        $U0200_valor = $rs['valor'];
        $U0200_nome = $rs['nome'];
    } else if ($codcons == 'U0201') {
        $U0201_valor = $rs['valor'];
        $U0201_nome = $rs['nome'];
    } else if ($codcons == 'U0202') {
        $U0202_valor = $rs['valor'];
        $U0202_nome = $rs['nome'];
    } else if ($codcons == 'U0301') {
        $U0301_valor = $rs['valor'];
        $U0301_nome = $rs['nome'];
    } else if ($codcons == 'A0710') {
        $A0710_valor = $rs['valor'];
        $A0710_nome = $rs['nome'];
    } else if ($codcons == 'A0208') {
        $A0208_valor = $rs['valor'];
        $A0208_nome = $rs['nome'];
    } else if ($codcons == 'D201') {
        $D201_valor = $rs['valor'];
        $D201_nome = $rs['nome'];
    } else if ($codcons == 'E201') {
        $E201_valor = $rs['valor'];
        $E201_nome = $rs['nome'];
    } else if ($codcons == 'SIT') {
        $SIT_valor = $rs['valor'];
        $SIT_nome = $rs['nome'];
    } else if ($codcons == 'LVTC') {
        $LVTC_valor = $rs['valor'];
        $LVTC_nome = $rs['nome'];
    } else if ($codcons == 'EM001') {
        $EM001_valor = $rs['valor'];
        $EM001_nome = $rs['nome'];
    } else if ($codcons == 'TM001') {
        $TM001_valor = $rs['valor'];
        $TM001_nome = $rs['nome'];
    } else if ($codcons == 'WM001') {
        $WM001_valor = $rs['valor'];
        $WM001_nome = $rs['nome'];
    } else if ($codcons == 'LE001') {
        $LE001_valor = $rs['valor'];
        $LE001_nome = $rs['nome'];
    } else if ($codcons == 'CREPR') {
        $CREPR_valor = $rs['valor'];
        $CREPR_nome = $rs['nome'];
    }
}

/*
$comando = "select * from franquia where id='$id'";
$res     = mysql_query ($comando, $con);
$matriz  = mysql_fetch_array($res);
$matriz['tx_adesao'];
*/
//homologa&ccedil;&atilde;o
if (	($id_franquia == 163) or ($id_franquia == 4) or ($id_franquia == 46) or ($id_franquia == 59)	){
	$id_franquia = '1';
	/*if ($idtable == 1){
	}*/
}
$sqx = "SELECT  tx_adesao FROM cs2.franquia WHERE id = '$id_franquia'";

// echo $id_franquia;exit;

$qrx = mysql_query($sqx);
while ($rss = mysql_fetch_array($qrx)) {
// 	echo '<pre> ';
// 	echo $rss['tx_adesao'];
    $homologa = $rss['tx_adesao'];
    $homologa = number_format($homologa, 2, ',', '.');
}

//nome da franquia responsavel
$sql_resp = "SELECT endereco, fone1, fantasia from cs2.franquia WHERE id= '{$_REQUEST['id']}'";
$qry_resp = mysql_query($sql_resp);
$endereco = mysql_result($qry_resp, 0, 'endereco');
//$fone     = telefoneConverte(mysql_result($qry_resp,0,'fone1'));
$nome = mysql_result($qry_resp, 0, 'fantasia');

$valorHomolog = $_GET['vlrHomolog'];
if($_GET['id'] == 163 || $_GET['id'] == 4 || $_GET['id'] == 247){
// Verificando se o valor não está sendo alterado pela URL
    if($valorHomolog != 100 && $valorHomolog != 150 && $valorHomolog != 200){
        $valorHomolog = 200;
    }

    $valorHomolog = 'R$ ' . number_format($valorHomolog,2);
}else{
    $valorHomolog = 'R$ ' . $homologa;
}


?>
    <!DOCUMENT html>
    <html>
    <header>
        <title></title>
        <link rel="stylesheet" href="tabelacss/bootstrap.min.css"/>
        <link rel="stylesheet" href="tabelacss/style.css"/>
        <style type="text/css" media="all">
            @media print {
                html, body {
                    margin: 0;
                    padding: 0;
                    background: #FFF;
                    font-size: 9.5pt;
                }

                .container, .container div {
                    width: 100%;
                    margin: 0;
                    padding: 0;
                }

                .template {
                    overflow: hidden;
                }

                img {
                    width: 100%;
                }
                .table{width:100%;}
            }
        </style>
    </header>
    <body>
    <div class="wrap container">
        <table class="table">
            <tr>
                <td colspan="4" class="txtcenter">
                    <?php if ($_REQUEST['tabela'] == 2) {
                        ?>
                        <h4>TABELA DE PRE&Ccedil;OS - EXCLUSIVA PARA CONTADORES</h4>
                        <?php
                    }
                    if ($_REQUEST['tabela'] == 1) {
                        ?>
                        <h4>TABELA DE PRE&Ccedil;OS - PEQUENA E M&Eacute;DIA EMPRESA</h4>
                        <?php
                    } ?>
                </td>
            </tr>
            <tr>
                <td width="15%"></td>
                <td width="70%" colspan="2">
                    <table class="table ccinq table-bordered">
                        <tr class="txtleft">
                            <td width="50%"><strong>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)&nbsp;&nbsp;&nbsp; Mensalidade
                                    Plus</strong></td>
                            <?php if ($_REQUEST['tabela'] == 2) {
                                ?>
                                <td width="50%">R$ 00,00</td><?php
                            } else {
                                ?>
                                <td width="50%">R$ 79,90</td><?PHP
                            }
                            ?>
                        </tr>
                        <tr class="txtleft">
                            <td width="50%"><strong>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)&nbsp;&nbsp;&nbsp; Mensalidade
                                    Premium</strong></td>
                            <?php if ($_REQUEST['tabela'] == 2) {
                                ?>
                                <td width="50%">R$ 00,00</td><?php
                            } else {
                                ?>
                                <td width="50%">R$ 129,90</td><?PHP
                            }
                            ?>
                        </tr>
                        <tr class="txtleft">
                            <td width="50%"><strong>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)&nbsp;&nbsp;&nbsp; Mensalidade
                                    Platinum</strong></td>
                            <?php if ($_REQUEST['tabela'] == 2) {
                                ?>
                                <td width="50%">R$ 00,00</td><?php
                            } else {
                                ?>
                                <td width="50%">R$ 169,90</td><?PHP
                            }
                            ?>

                        </tr>
                        <tr class="txtleft">
                            <td width="50%"><strong>Licen&ccedil;a de Software</strong></td>
                            <?php if ($_REQUEST['tabela'] == 2) {
                                ?>
                                <td width="50%">R$ 00,00</td><?php
                            } elseif($_REQUEST['tabela'] == 1) {
                                ?>
                                <td width="50%">R$ 19,90</td><?php
                            } elseif($_REQUEST['tabela'] == 3){ ?>
                                <td width="50%">R$ 29,90</td>
                           <?php }
                            ?>

                        </tr>
                        <tr class="txtleft">
                            <td width="50%"><strong>Deslocamento T&eacute;cnico no local (cidades relacionadas no site)</strong></td>
                            <td width="50%">R$ 35,00</td>
                        </tr>
                    </table>
                </td>
                <td width="15%"></td>
            </tr>
            <tr>
                <td width="15%"></td>
                <td width="70%" colspan="2">
                    <table class="table ccinq">
                        <tr>
                            <th colspan="2" class="trBordas" style="background: #f3f3f3 !important">
                                GEST&Atilde;O EMPRESARIAL
                            </th>
                        </tr>
                        <tr>
                            <td>Todos os M&oacute;dulos</td>
                            <td>Incluso na Mensalidade</td>
                        </tr>
                    </table>
                </td>
                <td width="15%"></td>
            </tr>

            <tr>
                <td width="15%"></td>
                <td width="70%" colspan="2">
                    <table class="table ccinq">
                        <tr>
                            <th colspan="2" class="trBordas" style="background: #f3f3f3 !important">
                                FORMAS DE RECEBIMENTO
                            </th>
                        </tr>
                        <tr>
                            <td>Or&ccedil;amentos</td>
                            <td>Incluso na Mensalidade</td>
                        </tr>
                        <tr>
                            <td>Ordem de Servi&ccedil;os</td>
                            <td>Incluso na Mensalidade</td>
                        </tr>
                        <tr>
                            <td>Vendas Consignadas</td>
                            <td>Incluso na Mensalidade</td>
                        </tr>
                        <tr>
                            <td>Frente de Caixa</td>
                            <td>Incluso na Mensalidade</td>
                        </tr>
                        <tr>
                            <td>Emiss&atilde;o (Boletos, Crediários)</td>
                            <td>2,5% T.A.</td>
                        </tr>
                        <tr>
                            <td>Emiss&atilde;o de Carnê Próprio</td>
                            <td>R$ <?php echo number_format($CREPR_valor,2,',','.');?> (p/parcela)</td>
                        </tr>
                        <tr>
                            <td>Promiss&oacute;rias</td>
                            <td>Incluso na Mensalidade</td>
                        </tr>
                    </table>
                </td>
                <td width="15%"></td>
            </tr>

            <tr>
                <td width="15%"></td>
                <td width="70%" colspan="2">
                    <table class="table ccinq">
                        <tr>
                            <th colspan="2" class="trBordas" style="background: #f3f3f3 !important">
                                RECEBER DE DEVEDORES
                            </th>
                        </tr>
                        <tr>
                            <td>Parcelar D&eacute;bitos no Boleto</td>
                            <td>2,5% T.A.</td>
                        </tr>
                        <tr>
                            <td>Localizar Pessoas e Empresas</td>
                            <td>R$ <?= $A0230_valor ?></td>
                        </tr>
                        <tr>
                            <td>Negativa&ccedil;&atilde;o e Exclus&atilde;o de Devedores</td>
                            <td>R$ <?= $D201_valor ?></td>
                        </tr>
                        <tr>
                            <td>Encaminhamento para Protesto</td>
                            <td>R$ <?= $T0001_valor ?></td>
                        </tr>
                        <tr>
                            <td>Recomenda&ccedil;&otilde;es Negativas</td>
                            <td>R$ <?= $T0002_valor ?></td>
                        </tr>
                    </table>
                </td>
                <td width="15%"></td>
            </tr>

            <tr>
                <td width="15%"></td>
                <td width="70%" colspan="2">
                    <table class="table ccinq">
                        <tr>
                            <th colspan="2" class="trBordas" style="background: #f3f3f3 !important">
                                CONSULTAS DE CR&Eacute;DITO
                            </th>
                        </tr>
                        <tr>
                            <td class="txtcenter" colspan="2">
                                Consultar no sistema v&aacute;rias modalidades.
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="15%"></td>
            </tr>

            <tr>
                <td width="15%"></td>
                <td width="70%" colspan="2">
                    <table class="table ccinq">
                        <tr>
                            <th colspan="2" class="trBordas" style="background: #f3f3f3 !important">
                                CONSULTAS DE VE&Iacute;CULOS
                            </th>
                        </tr>
                        <tr>
                            <td class="txtcenter" colspan="2">
                                Consultar no sistema v&aacute;rias modalidades.
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="15%"></td>
            </tr>

            <tr>
				<td width="15%"></td>
                <td width="70%" colspan="2">
                    <table class="table ccinq">
                        <tr>
                            <th colspan="2" class="trBordas" style="background: #f3f3f3 !important">
                                AUMENTAR CLIENTES E FATURAMENTO
                            </th>
                        </tr>
                        <tr>
                            <td>Mala direta novos clientes</td>
                            <td>R$ <?= $A0231_valor ?> / unidade</td>
                        </tr>
                        <tr>
                            <td>E-mail Marketing</td>
                            <td>R$ <?= $EM001_valor ?> / unidade</td>
                        </tr>
                        <tr>
                            <td>Torpedos Marketing Celular</td>
                            <td>R$ <?= $TM001_valor ?> / unidade</td>
                        </tr>
                        <tr>
                            <td>WhatsApp Marketing</td>
                            <td>R$ <?= $WM001_valor ?> / unidade</td>
                        </tr>
                        <tr>
                            <td>Lista de Empresas por Segmento</td>
                            <td>R$ <?= $LE001_valor ?> / unidade</td>
                        </tr>
                    </table>
                </td>
				<td width="15%"></td>
            </tr>

            <tr>
                <td width="15%"></td>
                <td width="70%" colspan="2">
                    <table class="table ccinq">
                        <tr>
                            <th colspan="4" class="trBordas" style="background: #f3f3f3 !important">
                                SITES
                            </th>
                        </tr>
                        <tr>
                            <td>Site Institucional</td>
                            <td>Incluso na mensalidade</td>
                        </tr>
                        <tr>
                            <td>Loja Virtual</td>
                            <td>Incluso na mensalidade</td>
                        </tr>
                    </table>
                </td>
                <td width="15%"></td>
            </tr>

            <tr>
                <td width="15%"></td>
                <td width="70%" colspan="2">
                    <table class="table ccinq">
                        <tr>
                            <th colspan="2" class="trBordas" style="background: #f3f3f3 !important">
                                NOTAS FISCAIS
                                <br/>(Sistema homologado na Secretaria da Fazenda - SEFAZ)
                            </th>
                        </tr>
                        <tr>
                            <td>NF-e / NFCe / NFSe / Cupom Fiscal</td>
                            <td>Incluso na mensalidade</td>
                        </tr>
                        <tr>
                            <td>SPED Fiscal / SPED Cont&aacute;bil</td>
                            <td>Incluso na mensalidade</td>
                        </tr>
                        <tr>
                            <td>CT-e - Conhecimento de Transporte</td>
                            <td>Em desenvolvimento</td>
                        </tr>
                        <tr>
                            <td>MDF-e - Manifesto Eletr&ocirc;nico de Documentos Fiscais</td>
                            <td>Em desenvolvimento</td>
                        </tr>
                        <tr>
                            <td>e-Social</td>
                            <td>Em desenvolvimento</td>
                        </tr>
                    </table>
                </td>
                <td width="15%"></td>
            </tr>

            <tr>
                <td width="15%"></td>
                <td width="70%" colspan="2">
                    <table class="table ccinq">
                        <tr>
                            <th colspan="2" class="trBordas" style="background: #f3f3f3 !important">
                                IMPORTA&Ccedil;&Atilde;O DE DADOS DE OUTROS SISTEMAS
                            </th>
                        </tr>
                        <tr>
                            <td class="txtcenter" colspan="2">
                                Incluso na mensalidade
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="15%"></td>
            </tr>

            <tr>
                <td width="15%"></td>
                <td width="70%" colspan="2">
                    <table class="table ccinq">
                        <tr>
                            <th colspan="2" class="trBordas" style="background: #f3f3f3 !important">
                                ACESSO NOS DISPOSITIVOS MOBILE
                            </th>
                        </tr>
                        <tr>
                            <td>Celulares e Tablets</td>
                            <td>Incluso na mensalidade</td>
                        </tr>
                    </table>
                </td>
                <td width="15%"></td>
            </tr>

        </table>
        <table class="table table-bordered" style="margin-top: 5px">
            <tr>
                <td class="txtcenter" style="background: #f3f3f3 !important">
                    RECIBO/ACEITE
                </td>
                <td class="txtcenter" style="background: #f3f3f3 !important">
                <?php 
                if ($_REQUEST['tabela'] == 2 || $_REQUEST['tabela'] == 3) {
                	echo 'R$ 0,00';
				} else {							
                	echo $valorHomolog;
				}
				?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Recebemos de __________________________________________________
                    <BR/>
                    Referente ao pagamento de Taxa de Homologa&ccedil;&atilde;o do Sistema (J&aacute; INCLUSO O
                    TREINAMENTO E CONSULTORIA).
                    <BR/>
                    Obs.: Nenhum outro valor dever&aacute; ser pago no ato da afilia&ccedil;&atilde;o.
                    <BR/>
                </td>
            </tr>
            <tr>
                <td class="txtcenter">
                    <BR/>____________________________________
                    <br/>
                    Consultor de Solu&ccedil;&otilde;es
                </td>
                <td class="txtcenter">
                    <BR/>____________________________________
                    <br/>
                    Aceite do Associado
                </td>
            </tr>
        </table>
    </div>
    </body>
    </html>

<?php

function extenso($valor = 0, $maiusculas = false)
{
    $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
    $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");

    $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
    $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
    $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
    $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

    $z = 0;

    $valor = number_format($valor, 2, ".", ".");
    $inteiro = explode(".", $valor);
    for ($i = 0; $i < count($inteiro); $i++)
        for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
            $inteiro[$i] = "0" . $inteiro[$i];

    $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
    for ($i = 0; $i < count($inteiro); $i++) {
        $valor = $inteiro[$i];
        $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
        $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
        $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

        $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
        $t = count($inteiro) - 1 - $i;
        $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
        if ($valor == "000") $z++; elseif ($z > 0) $z--;
        if (($t == 1) && ($z > 0) && ($inteiro[0] > 0)) $r .= (($z > 1) ? " de " : "") . $plural[$t];
        if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
    }

    if (!$maiusculas) {
        return ($rt ? $rt : "zero");
    } else {
        //return (ucwords($rt) ? ucwords($rt) : "Zero");
        return (strtolower($rt));
    }
}

?>