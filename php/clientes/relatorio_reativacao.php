<?php
require "connect/sessao.php";
require "connect/conexao_conecta.php";
require "connect/funcoes.php";
//if franquias tipo C e tipo A ve tudo, senao ve so o da propria franquia


$where = ($_SESSION['ss_tipo'] == 'b')? ' WHERE cad.id_franquia = ' . $_SESSION['id'] : '';  
  
$sql = 'SELECT
          sr.id,
          sr.id_cadastro,
		  sr.logon,
		  sr.nome_empresa,
		  sr.nome_proprietario,
		  sr.telefone,
		  sr.email,
		  sr.status_reativacao,
		  sr.dt_creation,
		  cad.id_franquia,
		  frq.fantasia as nome_franquia,
		  d.descsit,
		  (SELECT COUNT(*)
                                FROM cs2.titulos
                                WHERE referencia <> "MULTA" AND codloja=cad.codLoja AND datapg IS NULL) AS qtd_faturas_abertas

        FROM base_web_control.solicitacao_reativacao AS sr
        INNER JOIN cs2.cadastro AS cad ON cad.codloja = sr.id_cadastro 
        INNER JOIN cs2.franquia AS frq ON frq.id = cad.id_franquia
        LEFT JOIN cs2.situacao d on cad.sitcli=d.codsit'
        .$where.
        ' GROUP BY cad.codloja ORDER BY sr.id DESC ';
        
$qry = mysql_query($sql,$con) or die($sql);
//echo $sql;
$total = mysql_num_rows($qry);
//$res = mysql_fetch_array($qry);
//echo '<pre>';
//print_r($res);
//echo '</pre>';
//exit;
?>
<style>
  h1{text-align: center}
  table{
    border-collapse: collapse;
    font-size:12px;
    font-family: arial, sans-serif;
  }
  table.tblSolicitaReativacao tr td{padding:6px}
  @media print {
	  .noprint{display:none}
	}
	
  table.tblSolicitaReativacao tr:hover{
	background: #ddd;
	cursor: pointer;
  }
</style>
<div id="div2Print">
  <h1>Relat&oacute;rio de Solicita&ccedil;&atilde;o de Reativa&ccedil;&atilde;o</h1>
  <div class="noprint" style="text-align:center">
	<button name="btnImprimirRel">Imprimir Relat&oacute;rio</button>
	<br>
	<br>
  </div>
  
  <table border="1" class="tblSolicitaReativacao" id="tblSolicitaReativacao" width="90%" align="center" cellpadding="4" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
	<thead>
	  <tr  bgcolor="#CFCFCF">
		<th>Data Solicita&ccedil;&atilde;o</th>
		<th>Franquia</th>
		<th>Associado</th>
		<th>Nome Empresa</th>
		<th>Propriet&aacute;rio</th>
		<th>Telefone</th>
		<th>E-mail</th>
		<th>C&oacute;d. Cliente</th>
		<th>Status</th>
		<th>Situação do Contrato</th>
		<th>Qtd Fat. Abertas</th>
	  </tr>
	</thead>
	<tbody>
	  <?php
	  $a_cor = array("#eee", "#FFFFFF");
	  $cont=0;
	  while($res = mysql_fetch_array($qry)){
		$cont++;
		$status = '<span style="color:#f00;">Pendente</span>';
		if($res['status_reativacao'] == 'S'){ $status = '<span style="color:#12b51a;">Reativado</span>';}
		else if($res['status_reativacao'] == 'N'){$status = '<span style="color:#666;">N&atilde;o Reativado</span>';}
		?>
		<tr data-id="<?=$res['id']?>" bgcolor="<?=$a_cor[$cont % 2]?>">
		  <td style="text-align:center"><?=date("d/m/Y", strtotime($res['dt_creation']))?></td>
		  <td><?=$res['nome_franquia']?></td>
		  <td style="text-align:center"><?=$res['logon']?></td>
		  <td><?=$res['nome_empresa']?></td>
		  <td><?=$res['nome_proprietario']?></td>
		  <td style="white-space:nowrap;"><?=$res['telefone']?></td> 
		  <td style="white-space:nowrap;" ><?=$res['email']?></td> 
		  <td style="text-align:center"><?=$res['id_cadastro']?></td>
		  <td style="text-align:center"><?=$status?></td>
		  <td style="text-align:center"><?=$res['descsit']?></td>
		  <td style="text-align:center"><?=$res['qtd_faturas_abertas']?></td>
		</tr>
		<?php
	  }
	  ?>
	  
	</tbody>
  </table>
</div>
<script>
  
  $('#tblSolicitaReativacao tbody tr').click(function(){
	var idIndicacao = $(this).attr('data-id');
	window.location = 'painel.php?pagina1=clientes/relatorio_reativacao_altera.php&idSolicitacao='+idIndicacao;
  });

  $('button[name=btnImprimirRel]').click(function(){
	 function Popup(data, style){
       var mywindow = window.open('', '#div2Print');
       mywindow.document.write('<html><head><title></title>');
       /* stylesheet*/
       mywindow.document.write('<style>' + style + '</style>');
       mywindow.document.write('</head><body >');
       mywindow.document.write(data);
       mywindow.document.write('</body></html>');
    
       mywindow.document.close(); // necessary for IE >= 10
       mywindow.focus(); // necessary for IE >= 10
    
       setTimeout(function () {
            mywindow.print();
            mywindow.close();
       }, 100); //1 segundo
    
       return true;
    }

    var style = 'h1{text-align: center}\n\
			  table{\n\
				border-collapse: collapse;\n\
				font-size:13px;\n\
				font-family: arial, sans-serif;\n\
			  }\n\
			  table.tblIndicaAmigo tr td{padding:2px}\n\
			  @media print {\n\
				  .noprint{display:none}\n\
				}';
    
    Popup( $("#div2Print").html(), style );
  });
</script>