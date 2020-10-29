<style type="text/css" media="print">
.bodyText {
	font:11px Arial, Helvetica, sans-serif;
	color:#666666;
	line-height:20px;
	margin-top:0px;
	}
.tabela {
	font-family: Tahoma, Verdana, Arial;
	font-size: 10px;
	border: 1px solid #D1D7DC;
	padding: 3px;
}
</style>
<?php
	
include("../../../validar2.php");

global $conexao,$arquivo;
conecex();

$erro = '';
$QUITADO = '';
$ENTRADA_CONFIRMADA = '';
$ERROS_REG_TITULOS = '';
$RECEBA_F = '';
$RECEBA_A = '';
$cont_nao = 0;                            
$cont_liq = 0;
$total_registro_ok = 0;

function espaco($espa,$quant){
$aux=$espa;
$tamanho=strlen($espa);
$zeros="";
for($i=1;$i<=$quant-$tamanho;$i++){
        $zeros = "&nbsp;".$zeros;
}
$aux ="$aux$zeros";
return $aux;
}


function quita_titulo($i_titulo, $i_valor_titulo, $i_total_recebido, $i_juros_titulo, $pagamento ){
	
    global $conexao, $cont_nao, $NAO_ENC, $RECEBA_F;
    $sql = "SELECT a.codloja,a.valor,a.numdoc, date_format(a.vencimento,'%d/%m/%Y') as vencimento,
                b.nomefantasia,b.cidade,
                a.cpfcnpj_devedor, c.Nom_Nome, b.banco_cliente, b.agencia_cliente,
                b.conta_cliente, b.cpfcnpj_doc, b.nome_doc, a.tp_titulo, a.datapg,
                MID(d.logon,1,LOCATE('S', d.logon) - 1) AS logon
            FROM cs2.titulos_recebafacil a
            INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
            LEFT OUTER JOIN base_inform.Nome_Brasil c ON a.cpfcnpj_devedor = c.Nom_CPF
            INNER JOIN cs2.logon d ON b.codloja = d.codloja
            WHERE a.numboleto_bradesco = '$i_titulo'
            GROUP BY a.numboleto_bradesco";
    $qr_sql = mysql_query($sql,$conexao) or die ("ERRO: $sql");
    $qtd  = mysql_num_rows($qr_sql);
    if ( $qtd > 0 ){
            $dados = mysql_fetch_array($qr_sql);
            $tp_tit = $dados['tp_titulo'];
            $Valor_Bol = $dados['valor'];
            $vencimento = $dados['vencimento'];
            $codloja = $dados['codloja'];
            $logon = $dados['logon'];
            $nomefantasia = $dados['nomefantasia'];
            # Atualizando tabela titulo_recebafacil
            $sql_update = " UPDATE cs2.titulos_recebafacil 
                            SET 
                                datapg = '$pagamento',
                                valorpg='$i_valor_titulo',
                                juros='$i_juros_titulo' 
                            WHERE numboleto_bradesco = '$i_titulo'";
            $qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");
            # Verificando se o titulo j� est� na tabela Conta Corrente Receba F�cil
            $sql_cta = "SELECT count(*) as qtd FROM cs2.contacorrente_recebafacil WHERE numboleto = '$i_titulo'";
            $qr_cta = mysql_query($sql_cta,$conexao) or die ("ERRO: $sql_cta");
            $qtd = mysql_result($qr_cta,0,'qtd');
            if ( empty($qtd) ) $qtd = 0;
            if ( $qtd == 0 ){
                $Text = ' Titulo Receb. Bco : '.$dados['cpfcnpj_devedor'].' '.$dados['Nom_Nome'];
                $Text = str_replace(chr(39),'',$Text);
                $Text = str_replace(chr(47),'',$Text);
                // verifico o saldo do cliente
                $sql_sdo = "SELECT saldo FROM cs2.contacorrente_recebafacil
                            WHERE codloja = '$codloja' order by id desc limit 1";
                $qr_sdo = mysql_query($sql_sdo,$conexao) or die ("ERRO: $sql_sdo");
                $saldo = mysql_result($qr_sdo,0,'saldo');
                if ( empty($saldo) ) $saldo = '0';
                $tx_adm = 0;
                $tx_adm = ( $i_valor_titulo * 0.02 );
                $saldo += $i_valor_titulo;
                $saldo = ($saldo - ( 4.95 + $tx_adm) );
                $sql_ins = "INSERT INTO cs2.contacorrente_recebafacil(
                                numboleto,codloja,data,discriminacao,venc_titulo,
                                valor_titulo,valor,saldo,datapgto,tx_adm)
                            SELECT numboleto_bradesco,a.codloja, now(),'$Text', a.vencimento, a.valor,
                                    '$i_valor_titulo','$saldo','$pagamento','$tx_adm'
                            FROM cs2.titulos_recebafacil a
                            INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
                            WHERE a.numboleto_bradesco='$i_titulo'";
                $qr_ins = mysql_query($sql_ins,$conexao) or die ("ERRO(X): $sql_ins");
            }
        $RECEBA_F .= '<tr><td>'.substr($logon.' - '.$nomefantasia,0,35).'</td>';
        $RECEBA_F .= '<td>'.$dados['cpfcnpj_devedor'].'</td>';
        $RECEBA_F .= '<td>'.substr($dados['Nom_Nome'],0,35).'</td>';
        $RECEBA_F .= '<td width="120">'.$i_titulo.'</td>';
        $RECEBA_F .= '<td width="120">'.$Vencimento.'</td>';
        $RECEBA_F .= '<td width="120" align="right">'.number_format($dados['valor'],2,',','.').'</td>';
        $RECEBA_F .= '<td width="120" align="right">'.number_format($i_valor_titulo,2,',','.').'</td></tr>';
    }else{
        
        $cont_nao++;
        $NAO_ENC .= espaco($i_numdoc,20).espaco('',3).espaco($i_titulo,20).' '.espaco($vencimento,10).espaco('',4).$i_valor_titulo."<br>";
        
        
    }
}

$arquivo = 'upload/CB190400.RST';
$arquivo = 'upload/luciano.RET';

$linha  = file($arquivo);
$total  = count($linha); //Conta as linhas

for($i=0;$i<$total;$i++){
    # Cabe�alho do Arquivo
    $lin = $linha[$i];
    if ( $i == 0 ){
        $pagamento = '20'.substr($lin,98,2).'-'.substr($lin,96,2).'-'.substr($lin,94,2);
    }else{
        # Trailler do Arquivo
        if ( substr($lin,0,1) == '1' ){
            $dt_pagamento     = '20'.substr($lin,114,2).'-'.substr($lin,112,2).'-'.substr($lin,110,2);
            $cod_movimento    = trim(substr($lin,108,02));
            $i_titulo         = trim(substr($lin,70,11));
            
            $i_valor_titulo   = substr($lin,152,13)/100;
            $i_valor_pago     = substr($lin,253,13)/100;
            
            $i_total_recebido = $i_valor_titulo + $i_juros_titulo;
            $i_valor_titulo   = $i_total_recebido;
            
            $sql = "SELECT a.codloja,a.valor,a.numdoc,
                    date_format(a.vencimento,'%d/%m/%Y') as vencimento,b.razaosoc,b.cidade,b.nomefantasia
                    FROM cs2.titulos a 
                    INNER JOIN cs2.cadastro b ON a.codloja = b.codloja 
                    WHERE a.numboleto_bradesco = '$i_titulo'";
            $qr_sql = mysql_query($sql,$conexao) or die ("ERRO: $sql");
            $qtd  = mysql_num_rows($qr_sql);
            if ( $qtd > 0 ){
                
                // TITULOS DE MENSALIDADES
                
                $reg = mysql_fetch_array($qr_sql);
                $Valor_Bol    = $reg['valor'];
                $codloja      = $reg['codloja'];
                $Num_Doc      = $reg['numdoc'];
                $Vencimento   = $reg['vencimento'];
                $lojista      = substr($reg['razaosoc'],0,25).'/'.substr($reg['cidade'],0,15);
                $nomefantasia = substr($reg['nomefantasia'],0,25);

                # Buscando o logon do cliente
                $sql_logon = "SELECT MID(logon,1,LOCATE('S', logon) - 1) as logon from cs2.logon
                              WHERE codloja = $codloja ";
                $qr_logon = mysql_query($sql_logon,$conexao) or die ("ERRO: $sql");
                $qtd_logon = mysql_num_rows($qr_logon);
                if ( $qtd_logon > 0 ){
                    $reg_logon = mysql_fetch_array($qr_logon);
                    $logon = $reg_logon['logon'];
                }
                $lojista = $logon.' '.$lojista;

                if ( $cod_movimento == '00' or $cod_movimento == '17'){

                    // Entrada Confirmada

                    $total_registro_ok++;
                    $ENTRADA_CONFIRMADA .= '<tr>
                                                <td>'.$codloja.' - '.$nomefantasia.'</td>
                                                <td></td>
                                                <td>'.espaco($Num_Doc,15).espaco('',2).$i_titulo.'</td>
                                                <td>'.$Vencimento.'</td>
                                                <td>'.$Valor_Bol.'</td>
                                                <td>'.$msg.'</td>
                                            </tr>';

                }elseif ( $cod_movimento == '06' ){
                        
                    // TITULOS PAGOS

                    $cont_liq++;
                    
                    if ( $Valor_Bol > $i_total_recebido ){
                        
                        $erro .="<tr>
                                   <td>$lojista</td>
                                   <td>$Num_Doc</td>
                                   <td>$i_titulo</td>
                                   <td>$Vencimento</td>
                                   <td>$Valor_Bol</td>
                                   <td>$i_total_recebido</td>
                                   <td align='center'><font color='red'>Diverg�ncia de Valores<br>Titulo baixado</font></td>";
                    }

                    $QUITADO .= "<tr height='22'";
                    if (($cont_liq%2) == 0) {
                        $QUITADO .= "bgcolor='#E5E5E5' ";
                    }
                    $QUITADO .= " style='font-size:12px';font-family:verdana>";

                    $QUITADO .= '   
                                    <td>'.$lojista.'</td>
                                    <td>'.$Num_Doc.'</td>
                                    <td>'.$i_titulo.'</td>
                                    <td>'.$Vencimento.'</td>
                                    <td>R$ '.number_format($Valor_Bol,2,',','.').'</td>
                                    <td>R$ '.number_format($i_valor_titulo,2,',','.').'</td>
                                </tr>';

                    // Quita_fatura_Bradesco($i_titulo,$codloja,$i_juros_titulo,$pagamento,$i_valor_titulo,$i_juros_titulo,$nomefantasia,$Valor_Bol);
                    // verifica_titulos($codloja);
                    $valor_total_quitado += $Valor_Bol;
                        
                }else{

                    $ERROS_REG_TITULOS .= '<tr>
                                                <td>'.$lojista.'</td>
                                                <td>'.$i_titulo.'</td>
                                                <td>'.$Vencimento.'</td>
                                                <td>'.$Valor_Bol.'</td>
                                                <td>'.$multa.'</td>
                                                <td>'.$msg.'</td>
                                            </tr>';
                }
                
            }else{
                
                # NAO ACHEI O BOLETO COMO MENSALIDADE, PESQUISA NA TABELA TITULOS_RECEBAFACIL
                $sql = "SELECT  a.codloja,a.valor,a.numdoc, date_format(a.vencimento,'%d/%m/%Y') as vencimento,
                                b.nomefantasia,b.cidade,
                                a.cpfcnpj_devedor, c.Nom_Nome, b.banco_cliente, b.agencia_cliente,
                                b.conta_cliente, b.cpfcnpj_doc, b.nome_doc, a.tp_titulo, a.datapg,
                                MID(d.logon,1,LOCATE('S', d.logon) - 1) AS logon
                        FROM cs2.titulos_recebafacil a
                        INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
                        LEFT OUTER JOIN base_inform.Nome_Brasil c ON a.cpfcnpj_devedor = c.Nom_CPF
                        INNER JOIN cs2.logon d ON b.codloja = d.codloja
                        WHERE a.numboleto_bradesco = '$i_titulo'
                        GROUP BY a.numboleto_bradesco";
                $qr_sql = mysql_query($sql,$conexao) or die ("ERRO: $sql");
                $qtd  = mysql_num_rows($qr_sql);
                if ( $qtd > 0 ){
                    
                    $dados = mysql_fetch_array($qr_sql);
                    $tp_tit = $dados['tp_titulo'];
                    $Valor_Bol = $dados['valor'];
                    $Vencimento = $dados['vencimento'];
                    $codloja = $dados['codloja'];
                    $logon = $dados['logon'];
                    $nomefantasia = $dados['nomefantasia'];
                    $nomeDevedor = $dados['Nom_Nome'];

                    # Atualizando tabela titulo_recebafacil
                    
                    if ( $cod_movimento == '06' or $cod_movimento == '17'){
                        
                        // Pagamento confirmado

                        $sql_update = " UPDATE cs2.titulos_recebafacil 
                                    SET 
                                        datapg = '$pagamento',
                                        valorpg='$i_valor_titulo',
                                        juros='$i_juros_titulo' 
                                    WHERE numboleto_bradesco = '$i_titulo'";
                        $qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");
                        # Verificando se o titulo j� est� na tabela Conta Corrente Receba F�cil
                        $sql_cta = "SELECT count(*) as qtd FROM cs2.contacorrente_recebafacil WHERE numboleto = '$i_titulo'";
                        $qr_cta = mysql_query($sql_cta,$conexao) or die ("ERRO: $sql_cta");
                        $qtd = mysql_result($qr_cta,0,'qtd');
                        if ( empty($qtd) ) $qtd = 0;
                        if ( $qtd == 0 ){
                            $Text = ' Titulo Receb. Bco : '.$dados['cpfcnpj_devedor'].' '.$dados['Nom_Nome'];
                            // verifico o saldo do cliente
                            $sql_sdo = "SELECT saldo FROM cs2.contacorrente_recebafacil
                                                    WHERE codloja = '$codloja' order by id desc limit 1";
                            $qr_sdo = mysql_query($sql_sdo,$conexao) or die ("ERRO: $sql_sdo");
                            $saldo = mysql_result($qr_sdo,0,'saldo');
                            if ( empty($saldo) ) 
                                    $saldo = '0';
                            $tx_adm = 0;
                            $tx_adm = ( $i_valor_titulo * 0.02 );
                            $saldo += $i_valor_titulo;
                            $saldo = ($saldo - ( 4.95 + $tx_adm) );
                            $sql_ins = "INSERT INTO cs2.contacorrente_recebafacil(
                                            numboleto,codloja,data,discriminacao,venc_titulo,
                                            valor_titulo,valor,saldo,datapgto,tx_adm)
                                        SELECT numboleto_bradesco,a.codloja, now(),'$Text', a.vencimento, a.valor,
                                                '$i_valor_titulo','$saldo','$pagamento','$tx_adm'
                                        FROM cs2.titulos_recebafacil a
                                        INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
                                        WHERE a.numboleto_bradesco='$i_titulo'";
                            $qr_ins = mysql_query($sql_ins,$conexao) or die ("ERRO(X): $sql_ins");
                        }
                        $RECEBA_F .= '<tr><td>'.substr($logon.' - '.$nomefantasia,0,35).'</td>';
                        $RECEBA_F .= '<td>'.$dados['cpfcnpj_devedor'].'</td>';
                        $RECEBA_F .= '<td>'.substr($dados['Nom_Nome'],0,35).'</td>';
                        $RECEBA_F .= '<td width="120">'.$i_titulo.'</td>';
                        $RECEBA_F .= '<td width="120">'.$Vencimento.'</td>';
                        $RECEBA_F .= '<td width="120" align="right">'.number_format($dados['valor'],2,',','.').'</td>';
                        $RECEBA_F .= '<td width="120" align="right">'.number_format($i_valor_titulo,2,',','.').'</td></tr>';
                    
                        
                    }else if ( $cod_movimento == '02' ){
                    
                        // Entrada Confirmada
                        $total_registro_ok++;
                        $ENTRADA_CONFIRMADA .= "<tr height='22'";
			if (($total_registro_ok%2) == 0) {
                            $ENTRADA_CONFIRMADA .= "bgcolor='#E5E5E5' ";
			}
                        $ENTRADA_CONFIRMADA .= " style='font-size:12px';font-family:verdana>";
                        
                        $ENTRADA_CONFIRMADA .= '
                                                    <td>'.$logon.' - '.$nomefantasia.'</td>
                                                    <td>'.$nomeDevedor.'</td>
                                                    <td>'.$i_titulo.'</td>
                                                    <td>'.$Vencimento.'</td>
                                                    <td>'.$Valor_Bol.'</td>
                                                    <td></td>
                                                    <td>'.$msg.'</td>
                                                </tr>';
                        
                    }
                    
                }else{

                    // Pesquisa na tabela de [titulos_recebafacil_excluidos]
                    $sql_tit_exc = "SELECT count(*) as qtd  FROM cs2.titulos_recebafacil_excluidos 
                                    WHERE numboleto_bradesco = '$i_titulo'";
                    $qry_tit_exc = mysql_query( $sql_tit_exc, $conexao ) or die("ERRO : $sql_tit_exc");
                    $qtd_tit_exc = mysql_result($qry_tit_exc,0,'qtd');

                    if ( $qtd_tit_exc > 0 ){

                        $sql_ajuda = "  INSERT INTO cs2.titulos_recebafacil
                                        SELECT * FROM cs2.titulos_recebafacil_excluidos
                                        WHERE numboleto_bradesco = '$i_titulo'";
                        $qry_ajuda = mysql_query($sql_ajuda,$conexao) or die("ERRO : $sql_ajuda");

                        $sql_ajuda = "DELETE FROM cs2.titulos_recebafacil_excluidos
                                      WHERE numboleto_bradesco = '$i_titulo'";
                        $qry_ajuda = mysql_query($sql_ajuda,$conexao) or die("ERRO : $sql_ajuda");

                        //quita_titulo($i_titulo, $i_valor_titulo, $i_total_recebido, $i_juros_titulo, $pagamento);

                    }else{

                        // Verificando se o titulo � de ANTECIPACAO
                        $sql_ant = "SELECT a.id_antecipacao, a.valor, b.nomefantasia, MID(c.logon,1,LOCATE('S', c.logon) - 1) logon
                                    FROM cs2.titulos_antecipacao a
                                    INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
                                    INNER JOIN cs2.logon c ON a.codloja = c.codloja
                                    WHERE a.numboleto = '$i_titulo'";
                        $qry_ant = mysql_query($sql_ant,$conexao) or die ("ERRO(X): $sql_ant");
                        if ( mysql_num_rows($qry_ant) > 0 ){

                            $valor = mysql_result($qry_ant,0,'valor');
                            $id_antecipacao = mysql_result($qry_ant,0,'id_antecipacao');
                            $nomefantasia = mysql_result($qry_ant,0,'nomefantasia');
                            $logon = mysql_result($qry_ant,0,'logon');

                            if ( $valor == '' ) $valor = 0;
                            if ( $valor > $i_total_recebido ){
                                $erro .="============================================================================================<br>";
                                $erro .="TITULO DE ANTECIPACAO DE CREDITO<br>";
                                $erro .="Corrup��o de registro !!! Verifique: <br>";
                                $erro .="                            Titulo : $i_titulo        Documento: $Num_Doc<br>";
                                $erro .="                            Cliente: ID = $codloja -  $logon<br>";
                                $erro .="                         Vencimento: $Vencimento<br>";
                                $erro .="                    Valor do Titulo: $Valor_Bol  Valor Pago: $i_total_recebido<br>";
                                $erro .="TITULO BAIXADO COM O VALOR DE      : $i_total_recebido<br>";
                                $erro .="============================================================================================<br>";
                                $cont_liq++;
                            }
                            // baixa o pagamento do cliente
                            $sql_ant = "UPDATE cs2.titulos_antecipacao 
                                        SET 
                                            valorpg = '$i_total_recebido', datapg = '$dt_pagamento'
                                        WHERE numboleto = '$i_titulo'";
                            $qry_ant = mysql_query($sql_ant,$conexao) or die ("ERRO(X): $sql_ant");
                            // Baixando o pagamento do emprestimo
                            $sql_ant = "UPDATE cs2.cadastro_emprestimo 
                                        SET 
                                            valor_pagamento = '$i_total_recebido', data_pagamento = '$dt_pagamento'
                                        WHERE id = '$id_antecipacao'";
                            $qry_ant = mysql_query($sql_ant,$conexao) or die ("ERRO(X): $sql_ant");

                            $RECEBA_A .= 'Cliente          : '.$logon.' - '.$nomefantasia.'<br>';
                            $RECEBA_A .= 'Numero do Boleto : '.$i_titulo.'<br>';
                            $RECEBA_A .= 'Valor Pago       : '.$i_valor_titulo.'<br>';
                            $RECEBA_A .= '-----------------------------------------------------------------------------------<br><br>';

                        }else{
                            // Eh  n�o achei mesmo, foi deletado na RAIZ
                            $cont_nao++;
                            $NAO_ENC .= espaco($i_numdoc,20).espaco('',3).espaco($i_titulo,20).' '.espaco($vencimento,10).espaco('',4).$i_valor_titulo."<br>";
                        }
                    }
                }
            }
        }
    }
} // For
$saida = "<hr><br></div><div style='font-size: 15px; font-family: Courier New, Courier, monospace;'>";
$saida .= "Inform System".espaco('','15')."Cobran�a Banc�ria".espaco('','10')."BRADESCO".espaco('','10')."Data Pgto: $pagamento<br><br>";

if ( $cont_nao > 0 ){
    $saida  .= "TITULOS NAO ENCONTRADO =======================================================================<br><br>";
    $saida  .= "Cliente    N� Documento     N� Boleto         Vencimento   Vlr Titulo<br><br>";
    $saida  .= "$NAO_ENC<br>";
    $saida  .= "==============================================================================================<br><br>";
    $saida  .= "Listado(s) ".colocazeros($cont_nao,4)." Titulo(s)<br><br>";
}
$saida .= "TITULOS QUITADOS =============================================================================<br><br>";
$saida .= espaco('N� Docum.',17).espaco('N� Boleto',11).' Vencimento '.espaco('Vr T�tulo',13)." Nome/Cidade <br><br>";
$saida .= "$QUITADO";
$saida .= "==============================================================================================<br>";
$saida .= "Listado(s) ".colocazeros($cont_liq,4)." Titulo(s)     Totalizando : ".number_format($valor_total_quitado,2,',', '.')."<br><br>";
if ( strlen($RECEBA_F) > 0 ){
    $saida  .= "<br><br>";
    $saida  .= "TITULOS RECEBA-F�CIL =========================================================================<br><br>";
    $saida  .= "
            <table>
                    $RECEBA_F
            </table>";
}
if ( strlen($RECEBA_A) > 0 ){
    $saida  .= "<br><br>";
    $saida  .= "TITULOS ANTECIPACAO CREDITO ===============================================================<br><br>";
    $saida  .= "$RECEBA_A";
}

$saida .= "</div>";

// echo $saida;

if ( $erro ){
    echo "
    <table width='1000' border='0' align='center' style='bgcolor='#FF0000'>
        <tr style='font-size:14px' align='center'; bgcolor='#FF0000'>
            <td colspan='7'>TITULOS - CORROMPIDOS</td>
        </tr>
        <tr bgcolor='#CCCCCC' style='font-size:12px';font-family:verdana>
            <th align='left'>Lojista</th>
            <th align='left'>Num Doc</th>
            <th align='left'>Titulo</th>
            <th align='left'>Vencimento</th>
            <th>Valor Titulo</th>
            <th>Valor Pago</th>
            <th>Msg</th>
        </tr>
        $erro
    </table><br>";
}

if ( $ENTRADA_CONFIRMADA ){
    echo "
    <table width='1000' border='0' align='center'>
        <tr style='font-size:14px' align='center'; bgcolor='#00CCFF'>
            <td colspan='6'>Registro de Titulos com sucesso - Entradas</td>
        </tr>
        <tr bgcolor='#CCCCCC' style='font-size:12px';font-family:verdana>
            <th align='left'>Lojista</th>
            <th align='left'>Cliente</th>
            <th align='left'>Titulo</th>
            <th>Vencimento</th>
            <th>Valor Titulo</th>
            <th>Msg</th>
        </tr>
        $ENTRADA_CONFIRMADA
        <tr>
            <td colspan='6' bgcolor='#CCCCCC' style='font-size:14px';font-family:verdana>$total_registro_ok Titulo(s) Registrado com Sucesso</td>
        </tr>
    </table>";
}

if ( $QUITADO ){
    echo "
    <table width='1000' border='0' align='center'>
        <tr style='font-size:14px' align='center'; bgcolor='#00CC33'>
            <td colspan='6'>TITULOS QUITADOS</td>
        </tr>
        <tr bgcolor='#CCCCCC' style='font-size:12px';font-family:verdana>
            <th align='left'>Lojista</th>
            <th align='left'>Num. Doc.</th>
            <th align='left'>Titulo</th>
            <th>Vencimento</th>
            <th>Valor Titulo</th>
            <th>Valor Pago</th>
        </tr>
        $QUITADO
        <tr>
            <td colspan='5' bgcolor='#CCCCCC' style='font-size:14px';font-family:verdana>$cont_liq Titulo(s) QUITADOS.</td>
            <td colspan='1' bgcolor='#CCCCCC' style='font-size:14px';font-family:verdana>R$ ".number_format($valor_total_quitado,2,',', '.')."</td>
        </tr>
    </table>";
}

exit;
    
    
    
    
    include("class.phpmailer.php");
    try {
        $mail = new PHPMailer();
        $mail->IsSendmail(); // telling the class to use SendMail transport
        $mail->IsSMTP(); //ENVIAR VIA SMTP
        $mail->Host = "10.2.2.7"; //SERVIDOR DE SMTP 
        $mail->SMTPAuth = true; //ATIVA O SMTP AUTENTICADO
        $mail->Username = "cpd@webcontrolempresas.com.br"; //EMAIL PARA SMTP AUTENTICADO
        $mail->Password = "#9%kxP*-11"; //SENHA DO EMAIL PARA SMTP AUTENTICADO
        $mail->From = "cpd@webcontrolempresas.com.br"; //E-MAIL DO REMETENTE
        $mail->FromName = "CPD - Web Control Empresas"; //NOME DO REMETENTE
        $mail->AddAddress("administrativo@webcontrolempresas.com.br","Administrativo - Web Control Empresas"); //E-MAIL DO DESINAT�RIO, NOME DO DESINAT�RIO
        $mail->WordWrap = 50; // ATIVAR QUEBRA DE LINHA
        $mail->IsHTML(true); //ATIVA MENSAGEM NO FORMATO HTML
        $mail->Subject = "Retorno Cobranca BRADESCO"; //ASSUNTO DA MENSAGEM
        $mail->Body = $saida; //MENSAGEM NO FORMATO HTML
        $mail->Send();
        echo "ATEN��O >>  Este relatorio foi enviado para o EMAIL : administrativo@webcontrolempresas.com.br</p>\n";
    } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
    }
?>