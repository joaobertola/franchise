<?php
/**
 * @file salvar_equipamentos_franquia.php
 * @brief
 * @author ARLLON DIAS
 * @date 14/02/2017
 * @version 1.0
 **/
require_once('connect/sessao.php');
require_once('connect/conexao_conecta.php');
require_once('Franquias/class.phpmailer.php');
require_once('Franquias/class.smtp.php');

if ($_POST['action']) {

    switch ($_POST['action']) {

        case 'confirmarEnvio' :

            $idPedido = $_POST['pedido'];

            $sql = "UPDATE cs2.franquia_solicitacao_equipamento SET situacao = 'B', data_baixa = NOW() WHERE id = '$idPedido'";
            $res = mysql_query($sql, $con);

            $arrRetorno['mensagem'] = 0;
            if ($res) {
                $arrRetorno['mensagem'] = 1;
            }

            echo json_encode($arrRetorno);

            break;


    }

} else {

    $idFranquia = $_SESSION['id'];
    $insereVenda = 1;
//    echo '<pre>';
//    var_dump($_POST);
//    die;


for ($i = 0; $i < count($_POST['iptQtd']); $i++) {

    $quantidade = $_POST['iptQtd'][$i];
    $codigoBarras = $_POST['iptCodigoBarra'][$i];
    $idProduto = $_POST['iptIdProduto'][$i];
    $iptDescricao = $_POST['iptDescricao'][$i];
    if ($quantidade > 0) {

        if ($insereVenda == 1) {

            $sqlInsertSolicitacao = " INSERT INTO cs2.franquia_solicitacao_equipamento(
                                id_franquia,
                                data_solicitacao,
                                situacao)
                            VALUES(
                                '$idFranquia',
                                NOW(),
                                'A');";

            mysql_query($sqlInsertSolicitacao, $con);
            $idSolicitacao = mysql_insert_id($con);

            $html = "Nova Solicitação de Produtos " . $_POST['iptFranquia'] . " Nº: " . $idSolicitacao . "<br><br>";

        }

        $sqlInsertItens = "INSERT INTO cs2.franquia_solicitacao_equipamento_itens(
                                id_solicitacao,
                                id_produto_wc,
                                codigo_barra,
                                qtd
                            )
                            VALUES(
                                '$idSolicitacao',
                                '$idProduto',
                                '$codigoBarras',
                                '$quantidade'
                            )";

        mysql_query($sqlInsertItens, $con);


        $insereVenda = 0;

        $html.= $quantidade . " - ". $iptDescricao . '<br>';


    }
}
if ($insereVenda == 1) { ?>

    <script>
        alert('Nenhum produto selecionado com quantidade superior a 0!');
        location.href = 'painel.php?pagina1=area_restrita/d_equipamentos_solicitacao.php';
    </script>

<?php }

?>
    <?php

    $assunto = "Novo Pedido de Equipamentos";

    $to = 'gerencia01curitiba@webcontrolempresas.com.br';
    //$to = 'arllon.dias05@gmail.com';
    $cc = 'luciano@webcontrolempresas.com.br';

    include_once('Franquias/class.send.php');

    # Enviando Email
    $contato = new SendEmail();
    $contato -> nomeEmail      = "Departamento Financeiro"; // Nome do Responsavel que vai receber o E-Mail
    $contato -> paraEmail      = $to; // Email que vai receber a mensagem
    $contato -> copiaEmail     = $cc;
    $contato -> configHost     = "10.2.2.7"; // Endereço do seu SMTP
    $contato -> configPort     = 25; // Porta usada pelo seu servidor. Padrão 25
    $contato -> configUsuario  = "financeiro@webcontrolempresas.com.br"; // Login do email que ira utilizar
    $contato -> configSenha    = "infsys321"; // Senha do email
    $contato -> remetenteEmail = "financeiro@webcontrolempresas.com.br"; // E-mail que vai ser exibido no remetente da mensagem
    $contato -> remetenteNome  = "Web Control Empresas";    // Um nome para o remetente
    $contato -> assuntoEmail   = $assunto; // Assunto da mensagem
    $contato -> conteudoEmail  = $html;// Conteudo da mensagem.
    $contato -> confirmacao    = 1; // Se for 1 exibi a mensagem de confirmação
    $contato -> mensagem       = ""; // Mensagem de Confirmacao
    $contato -> erroMsg        = "[ $codloja ] Uma mensagem de erro aqui";// pode colocar uma mensagem de erro aqui!!
    $contato -> confirmacaoErro = 1; // Se voce colocar 1 ele exibi o erro que ocorreu no erro se for 0 não exibi o erro uso geralmente para verifiar se ta pegando.
    // envia a mensagem

    $contato -> enviar();
    ?>

    <script>
       alert('Solicitação Realizada com sucesso!');
       location.href = 'painel.php?pagina1=area_restrita/d_equipamentos_solicitacao_relatorio_detalhado.php&idSolicitacao=<?php echo $idSolicitacao ?>';
    </script>

<?php
}
 ?>