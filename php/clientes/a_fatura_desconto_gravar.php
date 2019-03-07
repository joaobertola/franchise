<?php

    require ("connect/sessao.php");

//    echo "";
//    print_r( $_REQUEST );
//    die;
    
    $numdoc = $_REQUEST['numdoc'];
    $obs = $_REQUEST['obs'];
    $nvalor = $_REQUEST['valor'];
    $nvalor = str_replace('.','',$nvalor);
    $nvalor = str_replace(',','.',$nvalor);

    $valor = $_REQUEST['valor'];
    $valor = str_replace('.','',$valor);
    $valor = str_replace(',','.',$valor);
    $tipo_lancamento = $_REQUEST['tipo_lancamento'];
    $codloja = $_REQUEST['codloja'];

    if ( $tipo_lancamento == 'D' )
            $nvalor = $nvalor - ( $nvalor * 2 );

    $sql_insert = "INSERT into cs2.vr_extra_faturado(numdoc, descricao, data, valor, proc )
                   VALUES( '$numdoc' , '$obs' , NOW() , '$nvalor' , 'S' )";
    // $qry_insert = mysql_query($sql_insert,$con) or die("Erro SQL : $sql_insert");

    if ( $tipo_lancamento == 'D' ){

        // DEBITO PARA O CLIENTE

        // Atualizando a tabela de Titulos
        $sql_update = "UPDATE cs2.titulos SET data_movimentacao = NOW() WHERE numdoc = '$numdoc'";
        $qry_update = mysql_query($sql_update,$con) or die("Erro SQL : $sql_update");
        
        // Gravando a Movimentacao de Titulo
        $sql_insert = "INSERT INTO cs2.titulos_movimentacao( numdoc, desconto, descricao )
                       VALUES( '$numdoc', '$valor', '$obs' )";
        $qry_insert = mysql_query($sql_insert,$con) or die("Erro SQL : $sql_insert");

    }else{

        // CREDITO PARA O CLIENTE

        // Atualizando a tabela de Titulos
        $sql_update = "UPDATE cs2.titulos SET data_movimentacao = NOW() WHERE numdoc = '$numdoc'";
        $qry_update = mysql_query($sql_update,$con) or die("Erro SQL : $sql_update");
        
        // Gravando a Movimentacao de Titulo
        $sql_insert = "INSERT INTO cs2.titulos_movimentacao( numdoc, acrescimo, descricao )
                       VALUES( '$numdoc', '$valor' , '$obs' )";
        $qry_insert = mysql_query($sql_insert,$con) or die("Erro SQL : $sql_insert");
    }

    ?>
    <script>
        alert('Registro GRAVADO com sucesso !');
        location.href="painel.php?pagina1=clientes/a_faturas.php";
    </script>