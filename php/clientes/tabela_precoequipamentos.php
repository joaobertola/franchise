<?php
/**
 * Created by PhpStorm.
 * User: dev02
 * Date: 08/12/2016
 * Time: 10:51
 */

//require "../connect/sessao.php";
require "../connect/conexao_conecta.php";
//
$sql = "SELECT
             descricao,
             custo_medio_venda
       FROM base_web_control.produto p
       INNER JOIN base_web_control.produto_pedido_equipamento ppe
       ON ppe.id_produto = p.id
       WHERE p.id_cadastro = 62735 AND p.ativo = 'A'
       ORDER BY descricao ASC";

$qry = mysql_query($sql, $con);


?>
<script src="../../css/assets/js/jquery-3.1.1.min.js"></script>
<style type="text/css">
    body {
        -webkit-print-color-adjust: exact;
    }
</style>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="../../css/assets/css/bootstrap.min.css">
<!---->
<!-- Optional theme -->
<link rel="stylesheet" href="../../css/assets/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="../../css/assets/js/bootstrap.min.js"></script>

<body >
<div>

<table width="100%">
    <thead>
        <!-- <img src="../../img/cabecalhoContrato.png"> -->
        <img src="../../img/cabecalhoContrato3.png">
    </thead>
</table>

<table width="100%" border="1" style="margin-top: 5px; font-size: 8px; !IMPORTANT">

    <thead>
    <tr>
        <th style="text-align: center; padding-left: 5px;">Opção</th>
        <th style="text-align: center; padding-left: 5px;">Qtde</th>
        <th style="margin: 5px;">Equipamento/Suprimento</th>
        <th style="padding-left: 5px;" width="15%">Serie</th>
        <th style="padding-left: 5px;">Forma de Pagamento</th>
    </tr>
    </thead>
    <tbody style="font-size: 10px">
    <?php while ($res = mysql_fetch_array($qry)) { ?>
        <tr style="font-size: 11px !IMPORTANT">
            <td style="text-align: center; padding-left: 5px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            <td style="padding-left: 5px;">&nbsp;&nbsp;&nbsp;</td>
            <td style="padding-left: 5px;"><?php echo $res['descricao'] ?></td>
            <td style="padding-left: 5px;" width="15%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td style="padding-left: 5px;"><?php echo '6  X  R$' . number_format($res['custo_medio_venda'] / 6, 2, ',', '.') ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="4" style="padding: 12px; text-align: right">TOTAL DA COMPRA</td>
        <td></td>
    </tr>
    </tbody>
</table>

<table width="100%" style="margin-top: 0px;">

    <tbody>
    <tr style="border: solid 1px;">
        <td width="30%">Forma de Pagamento:</td>
        <td>(&nbsp;&nbsp;&nbsp;) Dinheiro</td>
        <td>(&nbsp;&nbsp;&nbsp;) Cartão</td>
        <td>(&nbsp;&nbsp;&nbsp;) Cheque</td>
        <td>(&nbsp;&nbsp;&nbsp;)<span style="font-size: 11px;"> Pgto à vista 5% de desconto</span></td>
    </tr>
    </tbody>

</table>
</br>
<div style="text-align: justify; font-size: 11px !IMPORTANT;" >

    <p>
        Pelo presente, a empresa supra referenciada no preambulo do Contrato denominada COMPRADORA adere a WC
        SISTEMAS E EQUIPAMENTOS DE INFORMÁTICA LTDA, denominada neste como VENDEDORA, portadora do CNPJ de
        nº 08.745.918/0001-71 sediada na AV Cândido de Abreu, nº 70, Conj 404, Centro Cívico, na cidade de
        Curitiba-PR, como fornecedora de Equipamentos e Produtos de Automação Comercial.
        </br> Com a assinatura neste Contrato de Compra de Equipamentos, e com a aprovação de todas as cláusulas
        contratuais abaixo alinhavadas, declaro-me ter recebido todos os equipamentos assinalados, e me vinculo a
        esta empresa VENDEDORA, garantindo o pagamento das parcelas pactuadas, e se submetendo as cláusulas contratuais abaixo:
    </p>

<!--    <strong><p style="text-align: center; font-size: 12px;"> DO OBJETO DO CONTRATO </p></strong>-->
    <p>
        <strong>DO OBJETO DO CONTRATO - Clausula 1º -</strong> O presente contrato tem como OBJETO a compra do(s) Equipamento(s) de
        Informática assinalado(s) no CONTRATO DE COMPRA DE EQUIPAMENTOS E SUPRIMENTOS com os devido(s) valor(es) relacionado(s).
    </p>

<!--    <strong><p style="text-align: center; font-size: 12px;"> DAS OBRIGAÇÕES </p></strong>-->
    <p>
        <strong>DAS OBRIGAÇÕES - Clausula 2º - </strong> O VENDEDOR realizou no ato da assinatura deste a ENTREGA
        IMEDIATA do(s) Equipamentos assinalados, realizando a devida instalação, o treinamento aos Usuários, e deixando(os)
        em pleno funcionamento.
        Parágrafo Único: O VENDEDOR não se responsabiliza em realizar a comunicação e integração dos Equipamentos
        vendidos e entregues, com Softwares que não sejam fornecidos pela WC SISTEMAS – WEB CONTROL EMPRESAS.

    </p>

<!--    <strong><p style="text-align: center; font-size: 12px;"> DO PAGAMENTO </p></strong>-->
    <p >
        <strong>DO PAGAMENTO - Clausula 3º - </strong>O COMPRADOR pagará ao VENDEDOR, pela compra do(s) Equipamento(s)
        objeto deste contrato, a quantia descrita no CAMPO TOTAL DA COMPRA, a serem pago(s) conforme o(s) meio(s) de
        pagamento(s) aprovado pelo Financeiro da VENDEDORA. O Depto Financeiro da VENDEDORA emitirá via e-mail a
        nota fiscal dos produtos vendidos, sendo apresentada à COMPRADORA para as devidas garantias dos Equipamentos pelo fabricante.
        </br>Parágrafo Único:  O não pagamento das parcelas na data acertada no presente instrumento acarretará a
        COMPRADORA E FIADORES a multa e juros do valor da parcela inadimplente conforme legislação em vigor.
        Caso a COMPRADORA e FIADORES permaneçam inadimplentes por mais de 10 dias, estão cientes que serão incluídos
        nos Cadastros de Devedores dos Órgãos de Proteção ao Crédito (SCPC, SPC e SERASA), e terão todos os acessos
        aos Sistemas e Softwares da WC SISTEMAS – WEB CONTROL interrompidos em sua totalidade até a liquidação das pendências.

    </p>

<!--    <strong><p style="text-align: center; font-size: 12px;">  </p></strong>-->
    <p>
        <strong>DA FIANÇA - Clausula 4º - </strong>OS FIADORES, ao final qualificados e assinados, comparecem
        no presente instrumento, na qualidade de devedores solidários e principais pagadores das obrigações
        da COMPRADORA, respondendo pelo integral cumprimento de todas as cláusulas do presente contrato e pelas
        obrigações atribuídas a COMPRADORA, responsabilizando-se pelo pagamento pontual do principal, bem como os
        juros, multas, correção monetária, encargos, tributos, honorários advocatícios, custas e despesas judiciais.
    </p>

<!--    <strong><p style="text-align: center; font-size: 12px;"> CONDIÇÕES GERAIS </p></strong>-->
    <p>
        <strong>CONDIÇÕES GERAIS - Clausula 5º - </strong> A VENDEDORA não se responsabilizará pelos danos causados
        nos Equipamentos por negligência da COMPRADORA, e por problemas decorrentes do não uso conforme as normas técnicas
        constantes no manual que acompanha o produto.
    </p>

<!--    <strong><p style="text-align: center; font-size: 10px;"> DO FORO </p></strong>-->
    <p>
        <strong>DO FORO - Clausula 6º - </strong> Para dirimir quaisquer controvérsias oriundas do CONTRATO DE COMPRA
        E VENDA, as partes elegem o Foro da Comarca de Curitiba – PR.
    </p>

    <p>
        Por estarem assim justos e contratados, firmam o presente.
    </p>

</div>

<table style="float: bottom !IMPORTANT">
    <thead>
    <img src="../../img/rodapeContrato.png">
    </thead>
</table>
</div>

</body>
<script>
       this.print();
       $(document).ready(function(){
           window.close();
       })
</script>