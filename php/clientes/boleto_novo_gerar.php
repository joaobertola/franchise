<?php

require_once("connect/sessao.php");

$codigo     = $_REQUEST['codigo'];
$vencimento = inverteData($_REQUEST['vencimento']);
$valor      = $_REQUEST['valor'];
$valor      = str_replace(".","",$valor);
$valor      = str_replace(",",".",$valor);
$codloja    = $_REQUEST['codloja'];
$obs        = $_REQUEST['obs'];

$numdoc	    = $_REQUEST['numdoc'];
$convenio   = "2188732";

$texto3	   = trim($_REQUEST['texto3']);

function inverteData($p_data_padrao){
    $dia = substr($p_data_padrao, 0,2);
    $mes = substr($p_data_padrao, 3,2);
    $ano = substr($p_data_padrao, 6,9);
    $data_bd.=$ano;
    $data_bd.="-";
    $data_bd.=$mes;
    $data_bd.="-";
    $data_bd.=$dia;
    return ($data_bd);
}

function colocazeros($zeros,$quant){
    $aux=$zeros;
    $tamanho=strlen($zeros);
    $zeros="";

    for($i=1;$i<=$quant-$tamanho;$i++){
        $zeros="0".$zeros;
    }
    $aux ="$zeros$aux";
    return $aux;
}

if($_REQUEST['acao'] == '1'){
    
    //RECUPERA O CODLOJA
    $sql_id = "SELECT codloja FROM cs2.logon WHERE logon LIKE '$codigo%' ";
    $qry_id = mysql_query($sql_id, $con);
    $codloja = mysql_result($qry_id,0,'codloja');
    $total = mysql_num_rows($qry_id);
    if($total == 0){
        $encontrado=FALSE;
        ?>
        <script>
            window.location.href="painel.php?pagina1=clientes/boleto_novo_avulso.php&encontrado=<?=$encontrado?>";
        </script>
        <?php

    }else{
        //GERA O NUMERO RANDOMICO
        $numdoc = '99' . $codloja . rand(1, 99999);
        $encontrado=TRUE;
        ?>
        <script>
            window.location.href="painel.php?pagina1=clientes/boleto_novo_avulso.php&codigo=<?=$codigo?>&codloja=<?=$codloja?>&numdoc=<?=$numdoc?>&titulo=<?=$titulo?>&encontrado=<?=$encontrado?>";
        </script>
        <?php
    }

}elseif($_REQUEST['acao'] == '2'){
    
    $sql_id = "SELECT codloja FROM cs2.logon WHERE logon LIKE '$codigo%' ";
    $qry_id = mysql_query($sql_id, $con);
    $codloja = mysql_result($qry_id,0,'codloja');
    
    //SELECIONA O NUMERO DO TITULO
    $sql_sel = "SELECT contador_recebafacil FROM cs2.controle_boletos";
    $qry_sel = mysql_query($sql_sel, $con);
    $titulo = mysql_result($qry_sel,0,'contador_recebafacil') or die("ERRO SQL [$sql_sel]");
    $cont = strlen($titulo);

    $titulo = colocazeros($titulo, 10);
    $convenio .= $titulo;

    $num_boleto_outros_bancos = substr($convenio,7,10);
    $num_boleto_outros_bancos = str_pad($num_boleto_outros_bancos,10,0,STR_PAD_LEFT);
    $num_boleto_outros_bancos = '9'.$num_boleto_outros_bancos;

    //INCREMENTA O NUMERO DO TITULO
    $sql_incre = "UPDATE cs2.controle_boletos SET contador_recebafacil = contador_recebafacil + 1";
    $qry_incre = mysql_query($sql_incre, $con) or die("ERRO SQL [$sql_incre]");

    //RECUPERA O CNPJ DO CLIENTE
    $sql_insc = "SELECT insc FROM cs2.cadastro WHERE codloja = '$codloja' ";
    $qry_insc = mysql_query($sql_insc, $con) or die("ERRO SQL [$sql_insc]");
    $insc = mysql_result($qry_insc,0,'insc');

    $referencia = 'OUT';

    if ( $texto3 == 'on' )
        $referencia = 'MULTA';

     $numdoc = '99' . $codloja . rand(1, 99999);
     
    //GRAVA O BOLETO E REDIRECIONA PARA IMPRIMIR
    $sql_boleto = "INSERT into cs2.titulos(
                            emissao, vencimento, valor, numdoc, codloja, numboleto, dti, dtf, obs, referencia ,banco_faturado, insc,
                            numboleto_bradesco
                            ) 
                    VALUES(
                            NOW(), '$vencimento', '$valor', '$numdoc', '$codloja', '$num_boleto_outros_bancos', '$vencimento', '$vencimento', 
                            '$obs', '$referencia', '237', '$insc', '$num_boleto_outros_bancos')";
    $qry_boleto = mysql_query($sql_boleto, $con) or die("ERRO SQL [$sql_boleto]");
    ?>
    <script>
        window.location.href="https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?&novoboleto=s&numdoc=<?=$numdoc?>";
    </script>
    <?php
}
?>