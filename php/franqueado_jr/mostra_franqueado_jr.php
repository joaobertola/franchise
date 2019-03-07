<?php
    session_start();

    $name = $_SESSION["ss_name"];
    $tipo = $_SESSION["ss_tipo"];

    $id = $_GET['id'];
    if (empty($id)) $id = $_POST['id'];

    $comando = "select * from franquia where id='$id'";

    $res = mysql_query ($comando, $con);
    $matriz = mysql_fetch_array($res); 
    $id = $matriz['id'];
?>

<script>
    function novo_franqueado_jr(){
        frm = document.form;
        frm.action = 'painel.php?pagina1=franqueado_jr/cad_franqueado_jr.php';
        frm.submit();
    }
    function altera_franqueado_jr(){
        frm = document.form;
        frm.action = 'painel.php?pagina1=franqueado_jr/alt_franqueado_jr.php&id=<?=$matriz['id']?>';
        frm.submit();
    }
</script>
<body>
    <form name="form" method="post" action="#" />
        <table border="0" align="center" width="80%">
            <tr>
                <td colspan="2" class="titulo"> FRANQUEADO JUNIOR</td>
            </tr>
            <tr>
                <td class="subtitulodireita">ID do Franqueado</td>
                <td class="campojustificado"><?php echo $matriz['id']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Senha</td>
                <td class="subtitulopequeno"><?php echo $matriz['senha']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Nome do Franqueado</td>
                <td class="subtitulopequeno"><?php echo $matriz['fantasia']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">CPF/CNPJ</td>
                <td class="subtitulopequeno"><?php echo $matriz['cpfcnpj']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Endere&ccedil;o</td>
                <td class="subtitulopequeno"><?php echo $matriz['endereco']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Bairro</td>
                <td class="subtitulopequeno"><?php echo $matriz['bairro']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">UF</td>
                <td class="subtitulopequeno"><?php echo $matriz['uf']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Cidade</td>
                <td class="subtitulopequeno"><?php echo $matriz['cidade']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">CEP</td>
                <td class="subtitulopequeno"><?php echo $matriz['cep']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Telefone Residencial</td>
                <td class="subtitulopequeno"><?php echo $matriz['fone1']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Telefone Celular</td>
                <td class="subtitulopequeno"><?php echo $matriz['fone2']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">E-mail</td>
                <td class="subtitulopequeno"><?php echo $matriz['email']; ?></td>
            </tr>
            <tr>
                <td colspan="3" class="titulo">Dados Banc&aacute;rios</td>
            </tr>
            <tr>
            <tr>
                <td class="subtitulodireita">Comiss&atilde;o Franqueado JUNIOR</td>
                <td class="subtitulopequeno" colspan="2">
                    <?php echo $matriz['comissao_frqjr']; ?> %
                </td>
            <tr>
            <tr>
                <td class="subtitulodireita">Banco</td>
                <td class="subtitulopequeno">
                <?php
                $banco = $matriz['banco'];
                $sql = "select * from consulta.banco where banco='$banco'";
                $resposta = mysql_query($sql,$con);
                while ($array = mysql_fetch_array($resposta)) {
                        $bco  = $array["banco"];
                        $nbanco = $array["nbanco"];
                        echo "$bco - $nbanco";
                }
                ?>
                </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Agencia</td>
                <td class="subtitulopequeno"><?php echo $matriz['agencia']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Conta</td>
                <td class="subtitulopequeno">
                    <?php 
                    $tpconta = $matriz['tpconta'];
                    if ($tpconta == 2) echo "Poupan&ccedil;a";
                    else echo "Conta Corrente";
                    echo " - ";
                    echo $matriz['conta']; ?>    </td>
            </tr>
            <tr>
                <td class="subtitulodireita">Titular</td>
                <td class="subtitulopequeno"><?php echo $matriz['titular']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">CPF Titular</td>
                <td class="subtitulopequeno"><?php echo $matriz['cpftitular']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Taxa de Implanta&ccedil;&atilde;o</td>
                <td class="subtitulopequeno"><?php echo $matriz['tx_adesao']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Taxa do Pacote</td>
                <td class="subtitulopequeno"><?php echo $matriz['tx_pacote']; ?></td>
            </tr>
            <tr>
                <td class="subtitulodireita">Taxa de Software</td>
                <td class="subtitulopequeno"><?php echo $matriz['tx_software']; ?></td>
            </tr>
            <tr>
                <td colspan="2" class="titulo">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr class="PAACDestaque">
                <td colspan="2">O login de acesso do franqueado  ser&aacute; <u><font color="#FF0000"><?php echo $matriz['id']; ?></font></u> e a senha <u><font color="#FF0000"><?php echo $matriz['senha']; ?></font></u></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr align="right">
                <td colspan="2"><input name="incluir" type="button" value="                Incluir novo Franqueado" onClick="novo_franqueado_jr()"/></td>
            </tr>
            <tr align="right">
                <td colspan="2"><input name="alterar" type="button" value="Alterar os dados do Franqueado" onClick="altera_franqueado_jr()"/></td>
            </tr>
        </form>
    </table>
    <?php
    $res = mysql_query ($comando, $con);
    $res = mysql_close ($con);
    ?>
</body>
<?php if($_REQUEST['Sucesso'] == 1){ ?>
    <script language="javascript">alert("Franqueado Junior gravado com sucesso!");</script>
<?php } ?>