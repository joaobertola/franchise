<?php

global $conexao, $arquivo, $origem_arquivo;

global $erro ,$QUITADO, $TIT_REG, $TIT_REGM, $TIT_NREG, $TIT_NREGM, $RECEBA_F, $RECEBA_A, $cont_nao, $cont_liq;

$usermy="csinform";
$passwordmy="inform4416#scf";
$nomedb="consulta";
$conexao=@mysql_pconnect("10.2.2.3",$usermy,$passwordmy)or die ("Prezado Cliente, <br><br> Estamos em manuten&ccedil;&atilde;o em Nosso Banco de Dados, dentro de instantes a conex&atilde;o ser&aacute; estabelecida novamente. <br><br>Atenciosamente, <br><br>Departamento de TI.");
$bd=mysql_select_db($nomedb,$conexao) or die("Nao foi posivel selecionar o banco de dados contate o administrador erro 30");

include("class.phpmailer.php");
    
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

function colocazeros($zeros, $quant) {
    $aux = $zeros;
    $tamanho = strlen($zeros);
    $zeros = "";

    for ($i = 1; $i <= $quant - $tamanho; $i++) {
    $zeros = "0" . $zeros;
    }
    $aux = "$zeros$aux";
    return $aux;
}

function verifica_titulos($codloja) {
    global $conexao;
    # Data do servidor 20 dias atraz.
    $nsql = "SELECT Date_Format( SUBDATE(NOW(), INTERVAL 20 DAY),'%Y-%m-%d') data";
    $qr_nsql = mysql_query($nsql, $conexao) or die("Erro:  $nsql");
    $reg = mysql_fetch_array($qr_nsql);
    $data_limite = $reg['data'];
    # Verificando se o cliente possui algum titulo em atraso a mais de 20 dias
    $nsql = "SELECT count(*) qtd FROM cs2.titulos 
             WHERE vencimento < '$data_limite' AND datapg IS NULL AND codloja='$codloja'";
    $xqr_nsql = mysql_query($nsql, $conexao) or die("Erro:  $nsql");
    $reg = mysql_fetch_array($xqr_nsql);
    $qtd = $reg['qtd'];
    if (empty($qtd))
    $qtd = '0';
    if ($qtd == '0') {
    # Não tem titulo em aberto, desbloqueia o cliente, caso esteja bloqueado.
    $nsql = "UPDATE cs2.logon SET sitlog='0' WHERE codloja='$codloja' AND sitlog='1'";
    $xqr_nsql = mysql_query($nsql, $conexao) or die("Erro:  $nsql");
    } else {
    # Tem titulo em aberto e o cliente já está bloqueado, portanto permanece como está.
    # Resumindo.. Não faz nada
    }
}

function Quita_fatura_Bradesco($i_titulo, $codloja, $i_juros_titulo, $pagamento, $i_valor_titulo, $i_juros_titulo, $nomefantasia, $Valor_Bol) {

    global $conexao;
    if (empty($i_juros_titulo))
    $i_juros_titulo = '0';

    # Verificando a classificacao da franquia   
    $sql_f = "  SELECT a.id_franquia, b.classificacao, b.comissao_frqjr 
                FROM cs2.cadastro a
                INNER JOIN cs2.franquia b on a.id_franquia = b.id
                WHERE a.codloja = $codloja";
    $qr_f = mysql_query($sql_f, $conexao) or die("Erro:  $sql_f");
    $classificacao = mysql_result($qr_f, 0, 'classificacao');
    $id_franquia = mysql_result($qr_f, 0, 'id_franquia');
    $comissao_frqjr = mysql_result($qr_f, 0, 'comissao_frqjr') * 1;

    # Confirmando pagamento
    $xsql = "UPDATE cs2.titulos
                SET origem_pgto='BANCO', datapg='$pagamento', 
                    valorpg = '$i_valor_titulo', juros='$i_juros_titulo' 
             WHERE numboleto_bradesco = '$i_titulo'";
    $qr_xsql = mysql_query($xsql, $conexao) or die("Erro:  $xsql");
    // Inserindo dados na conta corrente ( para ser listado no relatorio de franquia )
    // buscando o franqueado pelo logon do cliente
    $sql_cta = "SELECT count(*) qtd FROM cs2.contacorrente WHERE numboleto = '$i_titulo'";
    $qr_cta = mysql_query($sql_cta, $conexao) or die("Erro:  $sql_cta");
    $array_cta = mysql_fetch_array($qr_cta);
    $qtd = $array_cta['qtd'];
    if ($qtd == 0) {
    # O TITULO PAGO AINDA NAO FOI LANÇADO NA CONTA CORRENTE, PORTANTO EFETUANDO O PROCESSO DE LANÇAMENTO
    # localizando o logon do cliente
    $xsql = "SELECT MID(logon,1,5) logon FROM cs2.logon WHERE codloja=$codloja";
    $qr_xsql = mysql_query($xsql, $conexao) or die("Erro:  $xsql");
    $reg = mysql_fetch_array($qr_xsql);
    $logon = $reg['logon'];
    if (empty($logon))
        $logon = 'S/ COD';

    if ($classificacao == "X") {
        $perc = $comissao_frqjr;
        $i_valor_titulo = $Valor_Bol * ($perc / 100);
        $Text = $logon . ' ' . $nomefantasia . " [Participa&ccedil;&atilde;o $perc %]";
    } else {
        $Text = $logon . ' ' . $nomefantasia . ' [Titulo Recebido Banco]';
        $i_valor_titulo = 'a.valorpg';
    }

    $xsql = "INSERT INTO cs2.contacorrente
                        (numboleto,franqueado,data,discriminacao,venc_titulo,valor_titulo,valor)
                 SELECT numboleto_bradesco, b.id_franquia, now(), '$Text', a.vencimento, a.valor, $i_valor_titulo 
                 FROM cs2.titulos a 
                 INNER JOIN cs2.cadastro b on a.codloja=b.codloja
                 WHERE a.numboleto_bradesco =' $i_titulo'";
    $qr_xsql = mysql_query($xsql, $conexao) or die("Erro:  $xsql");
    } else {
    # O TITULO PAGO JÁ FOI LANÇADO A CONTA CORRENTE, PORTANTO NAO PODE SER LANÇADO NOVAMENTE.
    }
}

function quita_titulo($i_titulo, $i_valor_titulo, $i_total_recebido, $i_juros_titulo, $pagamento ){
    
    global $conexao, $cont_nao, $NAO_ENC, $RECEBA_F;
    $sql = "SELECT a.codloja,a.valor,a.numdoc, date_format(a.vencimento,'%d/%m/%Y') as vencimento,
                   b.nomefantasia,b.cidade,
                   a.cpfcnpj_devedor, c.Nom_Nome, b.banco_cliente, b.agencia_cliente,
                   b.conta_cliente, b.cpfcnpj_doc, b.nome_doc, a.tp_titulo, a.datapg,
                   mid(d.logon,1,5) AS logon, a.emissao
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
            $emissao = $dados['emissao'];
            # Atualizando tabela titulo_recebafacil
            $sql_update = " UPDATE cs2.titulos_recebafacil 
                            SET 
                                datapg = '$pagamento',
                                valorpg='$i_valor_titulo',
                                juros='$i_juros_titulo' 
                            WHERE numboleto_bradesco = '$i_titulo'";
            $qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");
            # Verificando se o titulo ja esta na tabela Conta Corrente Receba F�cil
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
                $tx_adm = ( $i_valor_titulo * 0.025 );
                $saldo += $i_valor_titulo;

                $time_inicial = strtotime('2017-01-01');
                $time_final = strtotime($emissao);
                // Calcula a diferença de segundos entre as duas datas:
                $diferenca = $time_final - $time_inicial; // 19522800 segundos
                // Calcula a diferença de dias
                $diferenca_dias = (int) floor($diferenca / (60 * 60 * 24));
                // Após 01/01/2017
                if ( $diferenca >= 0 ) $tarifa = 2.25;
                else $tarifa = 4.95;

                $saldo = ($saldo - ( $tarifa + $tx_adm) );
                    
                $sql_ins = "INSERT INTO cs2.contacorrente_recebafacil(
                                numboleto,codloja,data,discriminacao,venc_titulo,
                                valor_titulo,valor,saldo,datapgto,tx_adm,tarifa_bancaria)
                            SELECT numboleto_bradesco,a.codloja, now(),'$Text', a.vencimento, a.valor,
                                    '$i_valor_titulo','$saldo','$pagamento','$tx_adm',$tarifa
                            FROM cs2.titulos_recebafacil a
                            INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
                            WHERE a.numboleto_bradesco='$i_titulo'";
                $qr_ins = mysql_query($sql_ins,$conexao) or die ("ERRO(X): $sql_ins");
            }
        $RECEBA_F .= "<tr style='font-size: 10px'><td>".substr($logon.' - '.$nomefantasia,0,35).'</td>';
        $RECEBA_F .= '<td>'.$dados['cpfcnpj_devedor'].'</td>';
        $RECEBA_F .= '<td>'.substr($dados['Nom_Nome'],0,35).'</td>';
        $RECEBA_F .= "<td width='120'>".$i_titulo.'</td>';
        $RECEBA_F .= "<td width='120'>".$Vencimento.'</td>';
        $RECEBA_F .= "<td width='120' align='right'>".number_format($dados['valor'],2,',','.').'</td>';
        $RECEBA_F .= "<td width='120' align='right'>".number_format($i_valor_titulo,2,',','.').'</td></tr>';
    }else{
        $cont_nao++;
        $NAO_ENC .= espaco($i_numdoc,20).espaco('',3).espaco($i_titulo,20).' '.espaco($vencimento,10).espaco('',4).$i_valor_titulo."<br>";
    }
}

function processa_linha_banco($i_titulo, $dt_pagamento, $i_valor_titulo, $i_juros_titulo, $i_total_recebido, $i_bco_recebedor, $i_valor_pago)
{
    global $conexao,$NAO_ENC,$QUITADO,$RECEBA_F,$erro,$RECEBA_A,$cont_liq,$valor_total_quitado;
    
    // PROCURA COMO MENSALIDADE, SE NAO ACHAR VAI PARA TABELA TITULO RECEBA FACIL
    
    $sql = "SELECT a.codloja,a.valor,a.numdoc,a.vencimento,b.razaosoc,b.cidade,b.nomefantasia
            FROM cs2.titulos a 
            INNER JOIN cs2.cadastro b ON a.codloja = b.codloja 
            WHERE a.numboleto_bradesco = '$i_titulo' or a.numboleto = '$i_titulo' ";
    $qr_sql = mysql_query($sql,$conexao) or die ("ERRO: $sql");
    $qtd  = mysql_num_rows($qr_sql);
    if ( $qtd > 0 ){
        $reg = mysql_fetch_array($qr_sql);
        $Valor_Bol = $reg['valor'];
        $codloja = $reg['codloja'];
        $Num_Doc = $reg['numdoc'];
        $Vencimento = $reg['vencimento'];
        $outros = substr($reg['razaosoc'],0,25).'/'.substr($reg['cidade'],0,15);
        $nomefantasia = substr($reg['nomefantasia'],0,25);

        # Buscando o logon do cliente
        $sql_logon = "SELECT mid(logon,1,5) as logon from cs2.logon
                      WHERE codloja = $codloja ";
        $qr_logon = mysql_query($sql_logon,$conexao) or die ("ERRO: $sql");
        $qtd_logon  = mysql_num_rows($qr_logon);
        if ( $qtd_logon > 0 ){
            $reg_logon = mysql_fetch_array($qr_logon);
            $logon = $reg_logon['logon'];
        }

        if ( $Valor_Bol > $i_total_recebido ){
            $erro .="============================================================================================<br>";
            $erro .="Corrupcao de registro !!! Verifique: <br>";
            $erro .="                            Titulo : $i_titulo        Documento: $Num_Doc<br>";
            $erro .="                            Cliente: ID = $codloja -  $logon<br>";
            $erro .="                         Vencimento: $Vencimento<br>";
            $erro .="                    Valor do Titulo: $Valor_Bol  Valor Pago: $i_total_recebido<br>";
            $erro .="TITULO BAIXADO COM O VALOR DE      : $i_total_recebido<br>";
            $erro .="============================================================================================<br>";
            $cont_liq++;
            Quita_fatura_Bradesco($i_titulo,$codloja,$i_juros_titulo,$dt_pagamento,$i_valor_titulo,$i_juros_titulo,$nomefantasia,$Valor_Bol);
            verifica_titulos($codloja);
            $QUITADO .= espaco($Num_Doc,16).espaco('',2).$i_titulo.' '.$Vencimento.' '.espaco($Valor_Bol,10).' '.$outros.'<br>';
            $valor_total_quitado += $Valor_Bol;
        }else{
            $cont_liq++;
            Quita_fatura_Bradesco($i_titulo,$codloja,$i_juros_titulo,$dt_pagamento,$i_valor_titulo,$i_juros_titulo,$nomefantasia,$Valor_Bol);
            verifica_titulos($codloja);
            $QUITADO .= espaco($Num_Doc,15).espaco('',2).$i_titulo.' '.$Vencimento.' '.espaco($Valor_Bol,10).' '.$outros.'<br>';
            $valor_total_quitado += $Valor_Bol;
        }

    }else{

        # NAO ACHEI O BOLETO COMO MENSALIDADE, PESQUISA NA TABELA TITULOS_RECEBAFACIL
        $sql = "SELECT  a.codloja,a.valor,a.numdoc, date_format(a.vencimento,'%d/%m/%Y') as vencimento,
                        b.nomefantasia,b.cidade,
                        a.cpfcnpj_devedor, c.Nom_Nome, b.banco_cliente, b.agencia_cliente,
                        b.conta_cliente, b.cpfcnpj_doc, b.nome_doc, a.tp_titulo, a.datapg,
                        mid(d.logon,1,5) AS logon, a.emissao
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
            $emissao = $dados['emissao'];

            # Atualizando tabela titulo_recebafacil
            $sql_update = " UPDATE cs2.titulos_recebafacil 
                            SET 
                                datapg = '$dt_pagamento',
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
                $tx_adm = ( $i_valor_titulo * 0.025 );
                $saldo += $i_valor_titulo;
                
                $time_inicial = strtotime('2017-01-01');
                $time_final = strtotime($emissao);
                // Calcula a diferença de segundos entre as duas datas:
                $diferenca = $time_final - $time_inicial; // 19522800 segundos
                // Calcula a diferença de dias
                $diferenca_dias = (int) floor($diferenca / (60 * 60 * 24));
                // Após 01/01/2017
                if ( $diferenca >= 0 ) $tarifa = 2.25;
                else $tarifa = 4.95;
                
                $saldo = ($saldo - ( $tarifa + $tx_adm) );
                
                $sql_ins = "INSERT INTO cs2.contacorrente_recebafacil(
                                numboleto, codloja, data, discriminacao, venc_titulo,
                                valor_titulo, valor, saldo, datapgto, tx_adm, tarifa_bancaria)
                            SELECT numboleto_bradesco,a.codloja, now(),'$Text', a.vencimento, a.valor,
                                    '$i_valor_titulo','$saldo','$dt_pagamento','$tx_adm', $tarifa
                            FROM cs2.titulos_recebafacil a
                            INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
                            WHERE a.numboleto_bradesco='$i_titulo'";
                $qr_ins = mysql_query($sql_ins,$conexao) or die ("ERRO(X): $sql_ins");
            }
            $RECEBA_F .= "<tr style='font-size: 10px'><td>".substr($logon.' - '.$nomefantasia,0,35).'</td>';
            $RECEBA_F .= '<td>'.$dados['cpfcnpj_devedor'].'</td>';
            $RECEBA_F .= '<td>'.substr($dados['Nom_Nome'],0,35).'</td>';
            $RECEBA_F .= "<td width='120'>".$i_titulo.'</td>';
            $RECEBA_F .= "<td width='120'>".$Vencimento.'</td>';
            $RECEBA_F .= "<td width='120' align='right'>".number_format($dados['valor'],2,',','.').'</td>';
            $RECEBA_F .= "<td width='120' align='right'>".number_format($i_valor_titulo,2,',','.').'</td></tr>';

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

                quita_titulo($i_titulo, $i_valor_titulo, $i_total_recebido, $i_juros_titulo, $dt_pagamento);

            }else{

                // Verificando se o titulo � de ANTECIPACAO
                $sql_ant = "SELECT a.id_antecipacao, a.valor, b.nomefantasia, mid(c.logon,1,5) logon
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
                        $erro .="Corrupcao de registro !!! Verifique: <br>";
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

@$processado_nexxera = $argv[1]; // somente vira pela nexxera

if( $processado_nexxera ){
   $origem_arquivo = 'NEXXERA';

   $dirs = scandir('/home/skyunix/inbox');
   foreach ($dirs as $dir) {
      if ($dir <> '.' && $dir <> '..') {

         $nome_arquivo = $diretorio . '/' . $dir;
     $nome_arquivo = str_replace('/','',$nome_arquivo);
         if ( substr($nome_arquivo,0,7) == 'cob237_'){
        processa_arquivo('/home/skyunix/inbox/'.$nome_arquivo);
            die;
         }
      }
   }
   $arquivo = $processado_nexxera;
}else{
   $origem_arquivo = 'WEB';
   processa_arquivo($arquivo);   
}

echo "$arquivo";
die;

function processa_arquivo($arquivo){

    global $conexao, $origem_arquivo, $QUITADO, $TIT_REG, $TIT_REGM, $TIT_NREG, $TIT_NREGM, $RECEBA_F, $RECEBA_A, $cont_nao, $cont_liq;

    $linha  = file($arquivo);
    $total  = count($linha); //Conta as linhas

    $dados_arquivo = '';
    for($i=0;$i<$total;$i++){
        # Cabecalho do Arquivo
        $dados_arquivo .= $linha[$i];
        $lin = $linha[$i];
        if ( $i == 0 ){
            $pagamento = '20'.substr($lin,98,2).'-'.substr($lin,96,2).'-'.substr($lin,94,2);
            $data_movimento = '20'.substr($lin,98,2).'-'.substr($lin,96,2).'-'.substr($lin,94,2);
            $banco_registro = substr($lin,76,3);
            $convenio = substr($lin,39,7);
        }else{
            # Trailler do Arquivo
            if ( substr($lin,0,1) == '1' ){
                
                $agencia_registro = substr($lin,25,4);
                $conta_registro   = substr($lin,29,8);
                $dt_pagamento     = '20'.substr($lin,114,2).'-'.substr($lin,112,2).'-'.substr($lin,110,2);
                $cod_movimento    = trim(substr($lin,108,2));
                $tipo_arquivo     = trim(substr($lin,37,6));
                $num_lote         = trim(substr($lin,43,2));
                $contrato         = trim(substr($lin,45,17));
                $i_titulo         = trim(substr($lin,70,11));

                $i_valor_pago     = substr($lin,253,13)/100;
                $i_juros_titulo   = substr($lin,266,13)/100;
                //$i_total_recebido = $i_valor_pago + $i_juros_titulo;
                $i_total_recebido = $i_valor_pago;
                $i_valor_titulo   = $i_total_recebido;
                
                $vencimento = substr($lin,146,6);
                $valor_original_tit = substr($lin,152,13)/100;
                
                $i_bco_recebedor  = substr($lin,165,3);
                
                $cod_ocorrencia    = trim(substr($lin,108,2));
                $data_credito      = trim(substr($lin,295,6));
                $cod_liquidacao    = '';
                
                $cod_retorno       = trim(substr($lin,318,10));
                
                // Dados do Titular do Titulo
                // Nao vem no Bradesco
                // $nome_consumidor   = espaco(trim(substr($lin,324,30)),30);
                
                switch ( $cod_ocorrencia ){
                    
                    case '06': // Liquidacao Normal
                    case '17': // Liquidacao após baixa ou liquidacao de titulo nao registrado
                              
                        if ( $data_credito != '' ){
                            if ( $i_valor_pago > 0 ){
                                processa_linha_banco($i_titulo, $dt_pagamento, $i_valor_titulo, $i_juros_titulo, $i_total_recebido, $i_bco_recebedor, $i_valor_pago);
                            }
                        }
                        break;
                    
                    case '02' : // ENTRADA CONFIRMADA
                        
                        // Verificando se o titulo é de mensalidade ou Boleto Emitido pelo Cliente
                        // $i_titulox = str_pad($i_titulo,11,0,STR_PAD_LEFT); // alterado luciano para titulos sem o 9
                        
                        $i_titulox = $i_titulo * 1;
                        $sql22 = "SELECT id FROM cs2.titulos WHERE numboleto = '$i_titulox' OR numboleto_bradesco = '$i_titulox'";
                        $qr22 = mysql_query($sql22,$conexao) or die ("ERRO: $sql22");

                        $qtd = mysql_num_rows($qr22);
                        
                        if ( $qtd > 0 ){
                            
                            // Eh titulo de mensalidade
                            $id_titulo = mysql_result($qr22,0,'id');
                            $sql_update = " UPDATE cs2.titulos 
                                            SET 
                                                num_lote = '$num_lote',
                                                banco_registro = '$banco_registro',
                                                agencia_registro = '$agencia_registro',
                                                conta_registro = '$conta_registro',
                                                cod_liquidacao = '$cod_liquidacao',
                                                data_registro = '$data_movimento'
                                            WHERE id = '$id_titulo'";
                            $qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");
                            $TIT_REGM .= $i_titulo.' '.$vencimento.' R$ '.espaco($valor_original_tit,14).' '.$nome_consumidor.'<BR>';
                            $cont_reg_mens++;
                            $valor_total_registrado_mens += $i_valor_titulo;

                        }else{

                            $sql_update = " UPDATE cs2.titulos_recebafacil 
                                            SET 
                                                num_lote = '$num_lote',
                                                banco_registro = '$banco_registro',
                                                agencia_registro = '3451',
                                                conta_registro = '325490',
                                                data_registro = '$data_movimento'
                                            WHERE numboleto_itau = '$i_titulox'";
                            
                            $qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");
                            $TIT_REG .= $i_titulo.' '.$vencimento.' R$ '.$valor_original_tit.'<BR>';
                            
                            $cont_reg++;
                            $valor_total_registrado += $i_valor_titulo;
                            
                        }
                        break;
                    
                    case '03' :
                    case '24' : // ENTRADA REJEITADA
                        
                        $i_titulox = str_pad($i_titulo,11,0,STR_PAD_LEFT);
                        $sql22 = "SELECT id FROM cs2.titulos WHERE numboleto = '$i_titulox' OR numboleto_bradesco = '$i_titulox'";
                        $qr22 = mysql_query($sql22,$conexao) or die ("ERRO: $sql22");
                        $qtd = mysql_num_rows($qr22);
                        if ( $qtd > 0 ){

                            // Eh titulo de mensalidade
                            $id_titulo = mysql_result($qr22,0,'id');
                            $sql_update = "UPDATE 
                                              cs2.titulos 
                                           SET 
                                              data_movimentacao = NOW()
                                           WHERE id = '$id_titulo'";
                            $qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");
                            
                            
                            if ( $cod_retorno != ''  ){
                                $nome_erro = '';
                                for( $j=1; $j <= 5; $j++  ){
                                    if     ($j == 1 ) $pos_i = 0;
                                    elseif ($j == 2 ) $pos_i = 2;
                                    elseif ($j == 3 ) $pos_i = 4;
                                    elseif ($j == 4 ) $pos_i = 6;
                                    elseif ($j == 5 ) $pos_i = 8;

                                    $cod_erro = substr($cod_retorno,$pos_i,2);
                                    if ( $cod_erro > 0 ){
                                        $sql_2 = "SELECT descricao FROM cs2.boleto_codigo_ocorrencia
                                                  WHERE banco = '237' and nota = '$cod_ocorrencia' and codigo = '$cod_erro'";
                                        $qry_2 = mysql_query($sql_2,$conexao) or die ("ERRO: $sql_2");
                                        $nome_erro .= mysql_result($qry_2,0,'descricao');
                                    }
                                }
                                $sql2 = "SELECT 
                                            b.login, a.valor, date_format(a.vencimento,'%d/%m/%Y') as vencimento
                                         FROM cs2.titulos a
                                         INNER JOIN base_web_control.webc_usuario b ON a.codloja = b.id_cadastro
                                         WHERE a.numdoc = '$contrato'
                                         LIMIT 1";
                                $qry_2 = mysql_query($sql2,$conexao) or die ("ERRO: $sql2");
                                if (mysql_num_rows($qry_2) > 0 ){
                                    $login    = trim(mysql_result($qry_2,0,'login'));
                                    $vencimento = mysql_result($qry_2,0,'vencimento');
                                    $vencimento = espaco($vencimento,10);
                                    $valor      = mysql_result($qry_2,0,'valor');
                                    $valor      = number_format($valor,2,',','.');
                                    $valor      = espaco($valor,14);
                                    $i_titulo   = espaco($i_titulo,16);
                                }
                                $TIT_NREGM .= "MENSALIDADE: $login - Boleto: $i_titulo - Vencimento: $vencimento - Valor: R$ $valor - Motivo: $nome_erro<BR>";
                            }

                        }else{

                            // Atualizando para reenvio no proximo lote
                            $sql_update = " UPDATE cs2.titulos_recebafacil 
                                            SET 
                                                data_alteracao = NOW()
                                            WHERE numboleto_bradesco = '$i_titulo'";
                            $qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");

                            if ( $cod_retorno != ''  ){
                                $nome_erro = '';
                                for( $j=1; $j <= 5; $j++  ){
                                    if     ($j == 1 ) $pos_i = 0;
                                    elseif ($j == 2 ) $pos_i = 2;
                                    elseif ($j == 3 ) $pos_i = 4;
                                    elseif ($j == 4 ) $pos_i = 6;
                                    elseif ($j == 5 ) $pos_i = 8;

                                    $cod_erro = substr($cod_retorno,$pos_i,2);
                                    if ( $cod_erro > 0 ){
                                        $sql_2 = "SELECT descricao FROM cs2.boleto_codigo_ocorrencia
                                                  WHERE banco = '237' and codigo = '$cod_erro' and nota = '20'";
                                        $qry_2 = mysql_query($sql_2,$conexao) or die ("ERRO: $sql_2");
                                        @$nome_erro .= mysql_result($qry_2,0,'descricao');
                                    }
                                }
                                $sql2 = "SELECT 
                                            cpfcnpj_devedor, valor, date_format(vencimento,'%d/%m/%Y') as vencimento
                                         FROM cs2.titulos_recebafacil
                                         WHERE chavebol = ".$contrato;
                                $qry_2 = mysql_query($sql2,$conexao) or die ("ERRO: $sql2");

                                @$cpfcnpj    = trim(mysql_result($qry_2,0,'cpfcnpj_devedor'));

                                if ( $cpfcnpj == '' ){
                                    $cpfcnpj    = espaco('Ctr('.$contrato.')',20);
                                }else{
                                    if ( strlen($cpfcnpj) <= 11 )
                                        $cpfcnpj    = espaco(mascaraCpf($cpfcnpj),20);
                                    else
                                        $cpfcnpj    = espaco(mascaraCnpj($cpfcnpj),20);
                                }
                                @$vencimento = mysql_result($qry_2,0,'vencimento');
                                $vencimento = espaco($vencimento,10);
                                @$valor      = mysql_result($qry_2,0,'valor');
                                $valor      = number_format($valor,2,',','.');
                                $valor      = espaco($valor,14);
                                $i_titulo   = espaco($i_titulo,16);

                                $TIT_NREG .= $i_titulo.' '.$vencimento.' R$ '.$valor.' '.$cpfcnpj.' '.$nome_consumidor.' - Motivo: '.$nome_erro.'<BR>';

                            }
                        }
                        break;

                    case '10' : // BAIXA SIMPLES
                        
                        //expirado = 0 - Nao
                        //expirado = 1 - Sim
                        
                        $sql_update = " UPDATE cs2.titulos_recebafacil 
                                        SET 
                                            expirado = '1',
                                            data_baixa_contabilidade = '$data_movimento'
                                        WHERE numboleto_bradesco = '$i_titulo'";
                        $qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");
                        
                        $sql2 = "SELECT 
                                    cpfcnpj_devedor, valor, date_format(vencimento,'%d/%m/%Y') as vencimento
                                 FROM cs2.titulos_recebafacil
                                 WHERE numboleto_bradesco = '$i_titulo'";
                        $qry_2 = mysql_query($sql2,$conexao) or die ("ERRO: $sql2");
                        $vencimento = mysql_result($qry_2,0,'vencimento');
                        $vencimento = espaco($vencimento,10);
                        $valor      = mysql_result($qry_2,0,'valor');
                        $valor2     = mysql_result($qry_2,0,'valor');
                        $valor      = number_format($valor,2,',','.');
                        $valor      = espaco($valor,14);
                        
                        if ( $valor2 == 0){
                            $sql_update = " UPDATE cs2.titulos_recebafacil_excluidos 
                                            SET 
                                                expirado = '1',
                                                data_baixa_contabilidade = '$data_movimento'
                                            WHERE numboleto_bradesco = '$i_titulo'";
                            $qr_update = mysql_query($sql_update,$conexao) or die ("ERRO: $sql_update");

                            $sql2 = "SELECT 
                                        cpfcnpj_devedor, valor, date_format(vencimento,'%d/%m/%Y') as vencimento
                                     FROM cs2.titulos_recebafacil_excluidos
                                     WHERE numboleto_bradesco = '$i_titulo'";
                            $qry_2 = mysql_query($sql2,$conexao) or die ("ERRO: $sql2");
                            $vencimento = mysql_result($qry_2,0,'vencimento');
                            $vencimento = espaco($vencimento,10);
                            $valor      = mysql_result($qry_2,0,'valor');
                            $valor2     = mysql_result($qry_2,0,'valor');
                            $valor      = number_format($valor,2,',','.');
                            $valor      = espaco($valor,14);
                        }
                        $TIT_EXP .= $i_titulo.' '.$vencimento.' R$ '.$valor.'<BR>';
                        $cont_exp++;
                        $valor_total_exp += $valor2;
                        break;
                        
                        
                    default: // NAO FAZ NADA
                        break;
                
                }
            }
        }
    } // For
    
    $saida = "<hr><br><div style='font-size: 10px; font-family: Courier New, Courier, monospace;'>";
    $saida .= "Web Control".espaco('','15')."Cobranca Bancaria".espaco('','10')."BRADESCO".espaco('','10')."Data Pgto: $pagamento<br><br>";

    if ( ! empty($erro) ){
        $saida  .= "TITULOS -- CORROMPIDOS =======================================================================<br><br>";
        $saida .= $erro;
    }
    if ( $cont_nao > 0 ){
        $saida  .= "TITULOS NAO ENCONTRADO =======================================================================<br><br>";
        $saida  .= "Cliente    No. Documento     No. Boleto         Vencimento   Vlr Titulo<br><br>";
        $saida  .= "$NAO_ENC<br>";
        $saida  .= "==============================================================================================<br><br>";
        $saida  .= "Listado(s) ".colocazeros($cont_nao,4)." Titulo(s)<br><br>";
    }
    $saida .= "TITULOS QUITADOS - MENSALIDADE =============================================================================<br><br>";
    $saida .= espaco('No. Docum.',17).espaco('No. Boleto',11).' Vencimento '.espaco('Vlr Titulo',13)." Nome/Cidade <br><br>";
    $saida .= "$QUITADO";
    $saida .= "==============================================================================================<br>";
    $saida .= "Listado(s) ".colocazeros($cont_liq,4)." Titulo(s)     Totalizando : ".number_format($valor_total_quitado,2,',', '.')."<br><br>";
    if ( strlen($RECEBA_F) > 0 ){
        $saida  .= "<br><br>";
        $saida  .= "TITULOS QUITADOS - BOLETO/CREDIARIO/RECUPERE =========================================================================<br><br>";
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
    
    // TITULOS REGISTRADOS
    
    if ( strlen($TIT_REG) > 0 ){
        $saida  .= "<br><br>";
        $saida  .= "TITULOS REGISTRADOS COM SUCESSO - BOLETO/CREDIARIO/RECUPERE =============================================================<br><br>";
        $saida  .= "$TIT_REG";
        $saida .= "==============================================================================================<br>";
        $saida .= "Listado(s) ".colocazeros($cont_reg,4)." Titulo(s)     Totalizando : ".number_format($valor_total_registrado,2,',', '.')."<br><br>";
    }
    
    if ( strlen($TIT_REGM) > 0 ){
        $saida  .= "<br><br>";
        $saida  .= "TITULOS REGISTRADOS COM SUCESSO - MENSALIDADE =============================================================<br><br>";
        $saida  .= "
                <table>
                        $TIT_REGM
                </table>";
         $saida .= "==============================================================================================<br>";
        $saida .= "Listado(s) ".colocazeros($cont_reg_mens,4)." Titulo(s)     Totalizando : ".number_format($valor_total_registrado_mens,2,',', '.')."<br><br>";
    }
    
    // TITULOS NAO REGISTRADOS
    
    if ( strlen($TIT_NREG) > 0 ){
        $saida  .= "<br><br>";
        $saida  .= "TITULOS NAO REGISTRADOS - BOLETO/CREDIARIO/RECUPERE =====================================================================<br><br>";
        $saida  .= "
                <table>
                        $TIT_NREG
                </table>";
    }

    if ( strlen($TIT_NREGM) > 0 ){
        $saida  .= "<br><br>";
        $saida  .= "TITULOS NAO REGISTRADOS - MENSALIDADE =====================================================================<br><br>";
        $saida  .= "
                <table>
                        $TIT_NREGM
                </table>";
    }
    
    if ( strlen($TIT_EXP) > 0 ){
        $saida  .= "<br><br>";
        $saida  .= "TITULOS EXPIRADOS =====================================================================<br><br>";
        $saida  .= "
                <table>
                        $TIT_EXP
                </table>";
        $saida .= "==============================================================================================<br>";
        $saida .= "Listado(s) ".colocazeros($cont_exp,4)." Titulo(s)     Totalizando : ".number_format($valor_total_exp,2,',', '.')."<br><br>";
    }
    
    $saida .= "</div>";

    // Gravando na tabela o processamento
    /* Nome do Arquivo, Data do Processamento, Mensagem Enviada, Convenio (WC ou ISPCN), Tipo do Processamento (Nexxera,Web)*/
    if ( $convenio == '4972103' ) $empresa = 'WC';
    else if ( $convenio == '4852239' ) $empresa = 'ISPCN';
    
    $sql_grava = "INSERT INTO cs2.titulos_processamento_nexxera
                     (data_processamento,hora_processamento,nome_arquivo_processado,registros_arquivo,texto_processamento,banco,envio_retorno, empresa)
                  VALUES( NOW(), NOW(),'$arquivo','$dados_arquivo',".'"'.$saida.'"'.",'237','R','$empresa')";
    $qr_grava = mysql_query($sql_grava,$conexao) or die ("ERRO: $sql_grava");
                    
    shell_exec("mv $arquivo /var/www/html/franquias/php/area_restrita/nexxera/retorno/");

    try {
        $mail = new PHPMailer();
        $mail->IsSendmail(); // telling the class to use SendMail transport
        $mail->IsSMTP(); //ENVIAR VIA SMTP
        $mail->Host = "10.2.2.7"; //SERVIDOR DE SMTP 
        $mail->SMTPAuth = true; //ATIVA O SMTP AUTENTICADO
        $mail->Username = "cpd@webcontrolempresas.com.br"; //EMAIL PARA SMTP AUTENTICADO
        $mail->Password = "#9%kxP*-11"; //SENHA DO EMAIL PARA SMTP AUTENTICADO
        $mail->From = "cpd@webcontrolempresas.com.br"; //E-MAIL DO REMETENTE 
        $mail->FromName = "CPD - WEBCONTROL"; //NOME DO REMETENTE
        $mail->AddAddress("administrativo@webcontrolempresas.com.br","Administrativo - WEBCONTROL"); //E-MAIL DO DESINAT�RIO, NOME DO DESINAT�RIO 
        $mail->AddBCC('luciano@webcontrolempresas.com.br', 'Luciano Mancini - Diretor de Tecnologia');
        $mail->WordWrap = 50; // ATIVAR QUEBRA DE LINHA
        $mail->IsHTML(true); //ATIVA MENSAGEM NO FORMATO HTML
        $mail->Subject = "Retorno Cobranca BRADESCO"; //ASSUNTO DA MENSAGEM
        $mail->Body = $saida; //MENSAGEM NO FORMATO HTML
        $mail->Send();
        echo "ATENCAO >>  Este relatorio foi enviado para o EMAIL : administrativo@webcontrolempresas.com.br</p>\n";
    } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
    }
    
} // Fim da funcao

?>
