<?php
require "connect/sessao.php";
require "connect/conexao_conecta.php";
require "connect/funcoes.php";


//GRAVA NOS STATUS CASO SEJA SUBMETIDO
if(isset($_POST) && $_POST['rdStatus'] != ''){
  $idSolicitacao = $_POST['txtIdSolicitacao'];
  $sql2 = " UPDATE base_web_control.solicitacao_reativacao
            SET
                status_reativacao    = '".$_POST['rdStatus']."',
                dt_reativacao    = '".$_POST['txtDataStatus']."',
                desc_reativacao        = '".$_POST['txtaDescricao']."'
            WHERE id = $idSolicitacao";

  $qry2 = mysql_query($sql2,$con)or die($sql2);
}

if(isset($_GET['idSolicitacao'])){
  
  $idSolicitacao = $_GET['idSolicitacao'];
  //echo $idSolicitacao;
  $sql = 'SELECT
          sr.id_cadastro,
          sr.logon,
          sr.nome_empresa,
          sr.nome_proprietario,
          sr.telefone,
          sr.email,
          sr.status_reativacao,
          sr.dt_reativacao,
          sr.desc_reativacao,
          sr.dt_creation,
          d.descsit,
          (SELECT
            GROUP_CONCAT("R$ ",REPLACE(REPLACE(REPLACE(FORMAT(valor,2),",",";"),".",","),";","."), " - ", numdoc," ", DATE_FORMAT(vencimento,"%d/%m/%Y") SEPARATOR "<br>")
                                FROM cs2.titulos
                                WHERE referencia <> "MULTA" AND codloja=cad.codLoja AND datapg IS NULL
                                ORDER BY vencimento ASC) AS faturas_em_aberto
          FROM
          base_web_control.solicitacao_reativacao as sr
          LEFT JOIN cs2.cadastro cad
          ON cad.codLoja = sr.id_cadastro
          LEFT JOIN cs2.situacao d on cad.sitcli=d.codsit
          WHERE
          sr.id =' . $idSolicitacao;
          
  $qry = mysql_query($sql, $con) or die($sql);
  $res = mysql_fetch_assoc($qry);

  //print_r($res);
  //exit;
  
}
?>

<style>
  h1{text-align: center}
  table{
    border-collapse: collapse;
    font-size:13px;
    font-family: arial, sans-serif;
  }
  table.tblSolicitacao tr td ,table.tblAtualizar tr td{padding:6px}
  table.tblAtualizar tr td{border: 0px !important}
  table.tblAtualizar{border: 2px solid #444 !important;}
</style>

<h1>Controle de Solicita&ccedil;&atilde;o de Reativa&ccedil;&atilde;o</h1>
<table class="tblSolicitacao" id="tblSolicitacao" border="1" width="50%" align="center"  cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
    <tbody>
      <tr>
        <td width="30%"><strong>ID Solicita&ccedil;&atilde;o:</strong></td>
        <td><?=$idSolicitacao?></td>
      </tr>
      <tr>
        <td><strong>Associado:</strong></td>
        <td><?=$res['logon']?></td>
      </tr>
      <tr>
        <td><strong>Empresa:</strong></td>
        <td><?=$res['nome_empresa']?></td>
      </tr>
      <tr>
        <td><strong>Propriet&aacute;rio:</strong></td>
        <td><?=$res['nome_proprietario']?></td>
      </tr>
      <tr>
        <td><strong>Telefone:</strong></td>
        <td><?=$res['telefone']?></td>
      </tr>
      <tr>
        <td><strong>E-mail:</strong></td>
        <td><?=$res['email']?></td>
      </tr>
    </tbody>
</table>

<?php
  $acao = '';
  if($res['status_reativacao'] == 'S'){ $status = '<span style="color:#12b51a">Reativado</span>'; $acao = 'da Reativacao'; $data = $res['dt_reativacao'];}
  else if($res['status_reativacao'] == 'N'){ $status = '<span style="color:#666">N&atilde;o Reativado</span>';  $acao = 'da Desist&eacirc;ncia';$data = $res['dt_reativacao'];}
  else if($res['status_reativacao'] == ''){ $status = '<span style="color:#f00">Pendente</span>';  $acao = 'da Solicita&ccedil;&atilde;o';$data = $res['dt_creation'];}
?>


<h1>Situação Contratual</h1>
<table class="tblSitContrato" id="" border="1" width="50%" align="center"  cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
    <tbody>
    <tr>
        <td width="30%"><strong>Situação do Contrato:</strong></td>
        <td><?=$res['descsit']?></td>
    </tr>
    <tr>
        <td width="30%"><strong>Qtd Faturas Abertas:</strong></td>
        <td><?=$res['faturas_em_aberto']?></td>
    </tr>
    <?php
    if($res['status_reativacao'] == 'S' || $res['status_reativacao'] == 'N'):
        ?>
        <tr>
            <td width="30%"><strong>Observa&ccedil;&otilde;es:</strong></td>
            <td><?=$res['desc_reativacao']?></td>
        </tr>
    <?php endif;?>
    </tbody>
</table>

<h1>Status</h1>
<table class="tblSolicitacao" id="" border="1" width="50%" align="center"  cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
    <tbody>
      <tr>
        <td width="30%"><strong>Satus:</strong></td>
        <td><?=$status?></td>
      </tr>
      <tr>
        <td width="30%"><strong>Data <?=$acao?>:</strong></td>
        <td><?=date("d/m/Y", strtotime($data))?></td>
      </tr>
      <?php
      if($res['status_reativacao'] == 'S' || $res['status_reativacao'] == 'N'):
      ?>
        <tr>
          <td width="30%"><strong>Observa&ccedil;&otilde;es:</strong></td>
          <td><?=$res['desc_reativacao']?></td>
        </tr>
       <?php endif;?>
    </tbody>
</table>

<h1>Atualizar Status</h1>
<table class="tblAtualizar" id="tblAtualizar" border="1" width="50%" align="center"  cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
    <form name="frmNovoStatus" method="post">
      <input type="hidden" name="txtIdSolicitacao" size="10" value="<?=$idSolicitacao?>" />
    <tbody>
      <tr>
        <td><strong>Novo Status:</strong></td>
        <td>
          <label><input type="radio" name="rdStatus" value="S" /> Reativado.</label>
          <label><input type="radio" name="rdStatus" value="N" /> N&atilde;o Reativado.</label>
        </td>
      </tr>
      <tr>
        <td><strong>Data <span class="spanStatus"></span>:</strong></td>
        <td>
          <input type="text" name="txtDataStatus" size="10" onKeyPress="return formataData(event,this,'##/##/####');"/>
        </td>
      </tr>
      <tr>
        <td><strong>Observa&ccedil;&atilde;o:</strong></td>
        <td>
          <textarea name="txtaDescricao" cols="70" rows="3"></textarea>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: right"><button type="button" name="btnGravaNovoStatusSolicitacao">Gravar Novo Status</button></td>
      </tr>
    </tbody>
    </form>
</table>

<script>
  
  $('button[name=btnGravaNovoStatusSolicitacao]').click(function(e){
    e.preventDefault();
    if ($('input[name=rdStatus]').is(':checked') && $('input[name=txtDataStatus]').val() != '') {
      $('input[name=txtDataStatus]').val(dateToUS($('input[name=txtDataStatus]').val()));
      $('form[name=frmNovoStatus]').submit();
    } else {
      alert('Novo Status e Data Requeridos.');
    }
  });
  
$('input[name=rdStatus]').click(function(){
  var status = $(this).val();
  if (status == 'S') {
    $('#tblAtualizar .spanStatus').text('da Reativacao');
  } else if (status == 'N') {
    $('#tblAtualizar .spanStatus').text('da Desistencia');
  }
});

function formataData(e,src,mask) {
  if(window.event) { _TXT = e.keyCode; }
  else if(e.which) { _TXT = e.which; }
  if(_TXT > 47 && _TXT < 58) {
      var i = src.value.length; var saida = mask.substring(0,1); var texto = mask.substring(i)
  if (texto.substring(0,1) != saida) { src.value += texto.substring(0,1); }
      return true; } else { if (_TXT != 8) { return false; }
  else { return true; }
  }
}

function dateToUS(dataBR)
{
    if (dataBR != '')
    {
        if (dataBR.indexOf(':') > 0)
        {
            var tempo = dataBR.split(' ');
            var dataSplit = tempo[0].split('/');
            dataBR = dataSplit[2] + "-" + dataSplit[1] + "-" + dataSplit[0] + ' ' + tempo[1];
        } else
        {
            if (dataBR.indexOf('/') > 0)
            {
                var dataSplit = dataBR.split('/');
                dataBR = dataSplit[2] + "-" + dataSplit[1] + "-" + dataSplit[0];
            }
        }
    }

    return dataBR
}
  
</script>


