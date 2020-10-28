<script>
function mostra($arq){
    window.open('https://www.webcontrolempresas.com.br/franquias/php/area_restrita/nexxera/'+$arq);
    return;
}
</script>

<?php

require "connect/sessao.php";
require "connect/funcoes.php";

$situacao = $_REQUEST['situacao'];

$data1 = $_REQUEST['data1'];
$data1 = substr($data1,6,4).'-'.substr($data1,3,2).'-'.substr($data1,0,2);

$data2 = $_REQUEST['data2'];
$data2 = substr($data2,6,4).'-'.substr($data2,3,2).'-'.substr($data2,0,2);


if (  $situacao == '0' ){

    // mensalidade

    $sql = "SELECT 
                     date_format(a.vencimento, '%d/%m/%Y') as vencimento, 
                     a.numboleto_bradesco, a.valor, 
                     date_format(a.datapg, '%d/%m/%Y') as datapg, 
                     a.valorpg,CAST(MID(b.logon,1,6) AS UNSIGNED) as codigo, c.razaosoc, origem_pgto 
                 FROM cs2.titulos a
            INNER JOIN cs2.logon b on a.codloja = b.codloja
            INNER JOIN cs2.cadastro c on a.codloja = c.codloja
            WHERE a.datapg between '$data1' AND '$data2'
            ORDER BY logon ";
    $qry = mysql_query($sql, $con) or die ("Erro : $sql");

    if ( mysql_num_rows($qry) > 0 ){

        $dados = "<table style=\"font-family:'Courier New', Courier, monospace; font-size:15px !important;  letter-spacing:-1px;\">
                 <tr height='20' bgcolor='87b5ff'>
                    <td>Data Pgto</td>
                    <td>Cliente</td>
                    <td>Boleto</td>
                    <td>Vencimento</td>
                    <td>Valor</td>
                    <td>Valor Pago</td>
                    <td>Origem Pgto</td>
                  </tr>";
        $qtd_banco = 0;
        $qtd_franquia = 0;
        $total_pago_banco = 0;
        $total_pago_franquia = 0;
        while ( $reg = mysql_fetch_array($qry) ){

            $codigo = $reg['codigo'];
            $cliente = $reg['razaosoc'];
            $numboleto_bradesco = $reg['numboleto_bradesco'];
            $vencimento = $reg['vencimento'];
            $valor = $reg['valor'];
            $data_pago = $reg['datapg'];
            $valor_pago = $reg['valorpg'];
            $origem_pgto = $reg['origem_pgto'];
            $total_pago += $valor_pago;
                
                if ( $origem_pgto == 'BANCO'){
                    
                    $qtd_banco++;
                    $total_pago_banco += $valor_pago;
                    
                }elseif ( $origem_pgto == 'FRANQUIA'){
                    $qtd_franquia++;
                    $total_pago_franquia += $valor_pago;
                }
                
                $cor = " bgcolor='#E5E5E5' ";
            if ( $valor_pago < $valor ){
                $cor = " bgcolor='#FF0A0A' ";
            }
                
                $valor = number_format( $valor , 2 , ',', '.');
            $valor_pago = number_format( $valor_pago , 2 , ',', '.');

            $dados .="<tr $cor>
                    <td style='font-size:10px'>$data_pago</td>
                    <td style='font-size:10px'>$codigo - $cliente</td>
                    <td style='font-size:10px'>$numboleto_bradesco</td>
                    <td style='font-size:10px'>$vencimento</td>
                    <td align='right' style='font-size:10px'>$valor</td>
                    <td align='right' style='font-size:10px'>$valor_pago</td>
                    <td align='right' style='font-size:10px'>$origem_pgto</td>
                  </tr>";

        }

                $total_pago = number_format( $total_pago , 2 , ',', '.');

        $dados .="<tr><td colspan='6' align='right'>$qtd_banco Titulos pagos em BANCO - Totalizando R$ : ".number_format( $total_pago_banco , 2 , ',', '.')."</td></tr>";

                $dados .="<tr><td colspan='6' align='right'>$qtd_franquia Titulos pagos em FRANQUIA - Totalizando R$ : ".number_format( $total_pago_franquia , 2 , ',', '.')."</td></tr>";
                
        $dados .="<tr><td colspan='6' align='right'>Total (MENSALIDADES) Recebido(s): $total_pago</td></tr>";

        $dados .= "</table>";       
    }


}elseif (  $situacao == '1' ){

    // crediario / recupere / boleto

    $sql = "SELECT
                date_format(a.datapg, '%d/%m/%Y') as datapgto, 
                date_format(a.vencimento, '%d/%m/%Y') as vencimento, 
                a.numboleto_bradesco, a.valor, a.datapg,CAST(MID(b.logon,1,6) AS UNSIGNED) as logon,
                a.valorpg, a.cpfcnpj_devedor,
                (
                   SELECT Nom_Nome FROM base_inform.Nome_Brasil
               WHERE Nom_CPF = a.cpfcnpj_devedor LIMIT 1
                ) as nome
            FROM cs2.titulos_recebafacil a
            INNER JOIN cs2.logon b ON a.codloja = b.codloja
            WHERE a.datapg between '$data1' AND '$data2' and descricao_repasse is null
            order by nome";
    $qry = mysql_query($sql, $con) or die ("Erro : $sql");

    if ( mysql_num_rows($qry) > 0 ){

        $dados = "<table style=\"font-family:'Courier New', Courier, monospace; font-size:15px !important;  letter-spacing:-1px;\">
                 <tr height='20' bgcolor='87b5ff'>
                    <td>Data Pgto</td>
                    <td>Código</td>
                    <td>Consumidor</td>
                    <td>Boleto</td>
                    <td>Vencimento</td>
                    <td>Valor</td>
                    <td>Valor Pago</td>
                  </tr>";

        while ( $reg = mysql_fetch_array($qry) ){

            $cpfcnpj_devedor = $reg['cpfcnpj_devedor'];

            $cliente = $reg['nome'];

            $numboleto_bradesco = $reg['numboleto_bradesco'];
            $vencimento = $reg['vencimento'];
            $valor = $reg['valor'];
            $data_pago = $reg['datapgto'];
            $valor_pago = $reg['valorpg'];
            $codigo = $reg['logon'];
                
            $total_pago += $valor_pago;

            $cor = " bgcolor='#E5E5E5' ";
            if ( $valor_pago > $valor ){
                $cor = " bgcolor='#b3d9ff' ";
            }

                $valor = number_format( $valor , 2 , ',', '.');
            $valor_pago = number_format( $valor_pago , 2 , ',', '.');

            $dados .="<tr $cor>
                    <td style='font-size:10px'>$data_pago</td>
                    <td style='font-size:10px'>$codigo</td>
                    <td style='font-size:10px'>$cliente</td>
                    <td style='font-size:10px'>$numboleto_bradesco</td>
                    <td style='font-size:10px'>$vencimento</td>
                    <td align='right' style='font-size:10px'>$valor</td>
                    <td align='right' style='font-size:10px'>$valor_pago</td>
                  </tr>";

        }

                $total_pago = number_format( $total_pago , 2 , ',', '.');


        $dados .="<tr><td colspan='6' align='right'>Total (CRED/REC/BOL) Recebido(s): $total_pago</td></tr>";

        $dados .= "</table>";       
    }

}elseif (  $situacao == '2' ){

    $sql = "SELECT 
                date_format(data_processamento, '%d/%m/%Y') as data, data_processamento, hora_processamento, nome_arquivo_processado, registros_arquivo,
                banco, envio_retorno, empresa, link_confirmacao_registro_tabela 
            FROM cs2.titulos_processamento_nexxera
            WHERE data_processamento BETWEEN '$data1' AND '$data2'
            ORDER BY data_processamento,hora_processamento";
    $qry = mysql_query($sql, $con) or die ("Erro : $sql");

    if ( mysql_num_rows($qry) > 0 ){

        $dados = "<table align='center' width='85%' border='0' cellpadding='0' cellspacing='1' class='bodyText'>
                 <tr>
                    <td colspan='6' class='titulo' style='height:50px'>PROCESSAMENTOS NEXXERA</td>
                 </tr>
                 <tr>
                    <td class='titulo'>Data</td>
                    <td class='titulo'>Hora</td>
                    <td class='titulo'>Empresa</td>
                    <td class='titulo'>Banco</td>
                    <td class='titulo'>Envio/Retorno</td>
                    <td class='titulo'>Arquivo</td>
                  </tr>";

        while ( $reg = mysql_fetch_array($qry) ){

            $data_processamento = $reg['data'];
            $hora_processamento = $reg['hora_processamento'];
            $empresa = $reg['empresa'];
            $banco = $reg['banco'];
            $envio_retorno = $reg['envio_retorno'] == 'E' ? 'Envio' : 'Retorno';
            $nome_arquivo_processado = $reg['nome_arquivo_processado'];
            $nome_arquivo_processado = str_replace('/home/skyunix/outbox/','',$nome_arquivo_processado);
            $nome_arquivo_processado = str_replace('/home/skyunix/inbox/','',$nome_arquivo_processado);
            
            if ( $reg['envio_retorno'] == 'E' )
                $nome_arquivo_processado = 'envio/'.$nome_arquivo_processado;
            else
                $nome_arquivo_processado = 'retorno/'.$nome_arquivo_processado;
                
            $dados .= "<tr height='20' bgcolor='87b5ff'>
                    <td align='center'>$data_processamento</td>
                    <td align='center'>$hora_processamento</td>
                    <td align='center'>$empresa</td>
                    <td align='center'>$banco</td>
                    <td align='center'>$envio_retorno</td>
                    <td ><a onclick= mostra('$nome_arquivo_processado')>$nome_arquivo_processado</a></td>
                  </tr>";

        }
        $dados .= '</table>';
    }

}

echo $dados;

die;