<?php

require "connect/sessao.php";
require "connect/conexao_conecta.php";
require "connect/funcoes.php";

function mes($mes){
    switch ($mes){
      case  '1'  : return 'JANEIRO';
      case  '2'  : return 'FEVEREIRO';
      case  '3'  : return 'MARÇO';
      case  '4'  : return 'ABRIL';
      case  '5'  : return 'MAIO';
      case  '6'  : return 'JUNHO';
      case  '7'  : return 'JULHO';
      case  '8'  : return 'AGOSTO';
      case  '9'  : return 'SETEMBRO';
      case  '10' : return 'OUTUBRO';
      case  '11' : return 'NOVEMBRO';
      case  '12' : return 'DEZEMBRO';
    }
}

$sql = "SELECT
           UPPER(b.nome) as nome, UPPER(a.descricao) AS descricao, a.valor, a.data_folha, UPPER(c.descricao) as funcao,
           if ( date_format(a.data_folha,'%d') <= 25  , date_format(a.data_folha,'%m')*1 , date_format(a.data_folha,'%m')+1 ) as mes_comp,
           date_format(a.data_folha,'%Y') as ano_comp
        FROM cs2.lancamento_funcionario a
        INNER JOIN cs2.funcionario b ON a.id_funcionario = b.id
        INNER JOIN cs2.funcao c ON b.funcao = c.id
        WHERE a.id = ".$_REQUEST['id'];
$qry = mysql_query($sql,$con) or die("$sql");
$mes_comp = mes(mysql_result($qry,0,'mes_comp'));
$ano_comp = mysql_result($qry,0,'ano_comp');
?>

<table width='90%' border='1' cellpadding='0' cellspacing='0' class='bodyText'>
    <tr>
        <td colspan="3" align='center' style='font-size:15px'class='titulo'>RECIBO DE ADIANTAMENTO SALARIAL</td>
   </tr>
   <tr height='20'>
      <td width='25%'>&nbsp;&nbsp;EMPRESA</td>
      <td colspan="2">&nbsp;&nbsp;WC SISTEMAS E EQUIPAMENTO DE INFORMÁTICA LTDA</td>
   </tr>
   <tr height='20'>
      <td colspan="1">&nbsp;&nbsp;EMPREGADO</td>
      <td colspan="2">&nbsp;&nbsp;<?php echo mysql_result($qry,0,'nome');?></td>
   </tr>
   <tr height='20'>
      <td width='10%'>&nbsp;&nbsp;FUNÇÃO</td>
      <td colspan="2">&nbsp;&nbsp;<?php echo mysql_result($qry,0,'funcao');?></td>
   </tr>
   <tr height='20'>
      <td width='10%'>&nbsp;&nbsp;COMPETÊNCIA MÊS</td>
      <td colspan="2">&nbsp;&nbsp;<?php echo "$mes_comp  / $ano_comp";?></td>
   </tr>
   <tr height='20'>
      <td colspan="3"></td>
   </tr>
   <tr height='20'>
      <td colspan="1">&nbsp;&nbsp;Quantidade</td>
      <td colspan="1">&nbsp;&nbsp;Descrição</td>
      <td colspan="1">&nbsp;&nbsp;Valor</td>
   </tr>
   <tr height='20'>
      <td colspan="1">&nbsp;&nbsp;1</td>
      <td colspan="1">&nbsp;&nbsp;<?php echo mysql_result($qry,0,'descricao');?></td>
      <td style='font-size:14px'>&nbsp;&nbsp;R$ <?php echo number_format(mysql_result($qry,0,'valor'),2,',','.');?></td>
   </tr>
   <tr height='20'>
      <td colspan="3"></td>
   </tr>
   <tr height='20'>
      <td colspan="3"></td>
   </tr>
   <tr height='20'>
      <td></td>
      <td align='right'>&nbsp;&nbsp;Valor Total&nbsp;&nbsp;</td>
      <td style='font-size:14px'>&nbsp;&nbsp;R$ <?php echo number_format(mysql_result($qry,0,'valor'),2,',','.');?></td>
   </tr>
   <tr height='20'>
      <td colspan="1">&nbsp;&nbsp;Valor por Extenso</td>
      <td colspan="2">&nbsp;&nbsp;<?php echo strtoupper(extenso(mysql_result($qry,0,'valor')));?></td>
   </tr>
   <tr height='20'>
      <td colspan="3"></td>
   </tr>
   <tr height='60'>
      <td colspan="3">
          Declaro, para os efeitos, ter recebido a título de ADIANTAMENTO SALARIAL, a importância acima,
          em consonância com o disposto no art. 462, caput, da CLT, tenho a plena ciência de que o respectivo 
          valor será descontado pelo empregador, quando do pagamento de minha remuneração mensal relativa a folha 
          de pagamento do mês subsequente.
       </td>
   </tr>   
   <tr height='80'>
      <td colspan='1'>&nbsp;&nbsp;Data: _____/_____/______</td>
      <td colspan='2' align='center'>Assinatura: ____________________________________</td>
   </tr>   
</table>
<script>
    window.print();
</script>