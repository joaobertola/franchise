<?php
require "connect/sessao.php";

$go 	= $_POST['go'];
$franqueado = $_POST['franqueado'];

if (empty($go)) {
  ?>
  <br>
  <form name="form1" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
    <table border="0" align="center" width="70%">
      <tr class="titulo">
        <td colspan="2">CORRESPONDÊNCIA PARA FRANQUIAS</td>
      </tr>
      <tr>
        <td class="subtitulodireita" width="173">&nbsp;</td>
        <td class="subtitulopequeno" width="224">&nbsp;</td>
      </tr>

      <tr>
        <td class="subtitulodireita">&nbsp;</td>
        <td class="subtitulopequeno">
          <?php
          echo "<select name=\"franqueado\" class=boxnormal >";
          $sql = "select * from franquia where tipo='b' and sitfrq < 2 order by id";
          $resposta = mysql_query($sql, $con);
          while ($array = mysql_fetch_array($resposta))
          {
            $franquia   = $array["id"];
            $nome_franquia = $array["fantasia"];
            echo "<option value=\"$franquia\">$franquia - $nome_franquia</option>\n";
          }
          echo "</select>";
          ?>
          <input type="hidden" name="go" value="ingressar" />    </td>
      </tr>
      <tr>
        <td class="subtitulodireita">&nbsp;</td>
        <td class="subtitulopequeno">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" class="titulo">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="center">
          <input type="submit" value="Imprimir" name="envia" onclick="return check(this.form);"/>
          <input name="voltar" type="button" onClick="javascript: history.back();" value="    Voltar   " />    </td>
      </tr>
    </table>
  </form>
<?php } // fim if go=null

if ($go=='ingressar') {
  $sql = "select razaosoc, endereco, bairro, cidade, uf, cep from franquia where id = '$franqueado'";
  $qr = mysql_query($sql, $con);
  $franquia = strtoupper(mysql_result($qr,0,"razaosoc"));
  $endereco = strtoupper(mysql_result($qr,0,"endereco"));
  $bairro = strtoupper(mysql_result($qr,0,"bairro"));
  $cidade = strtoupper(mysql_result($qr,0,"cidade"));
  $uf = mysql_result($qr,0,"uf");
  $cep = mysql_result($qr,0,"cep");
  ?>
  <table>
    <tr>
      <td colspan="2"><img src="https://www.webcontrolempresas.com.br/images/tesoura.gif"  /><hr noshade="noshade" style="border:dashed" size="1" width="90%" color="#c0c8c0"  /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><h4>REMETENTE:</h4></td>
      <td><h2>WEB CONTROL EMPRESAS.</h2></td>
    </tr>
    <tr>
      <td><h4>ENDERE&Ccedil;O:</h4></td>
      <td><h2>AV. C&Acirc;NDIDO DE ABREU N&ordm; 70, CONJ. 404</h2></td>
    </tr>
    <tr>
      <td><h4>BAIRRO:</h4></td>
      <td><h2>CENTRO C&Iacute;VICO</h2></td>
    </tr>
    <tr>
      <td><h4>CIDADE / UF:</h4></td>
      <td><h2>CURITIBA / PR</h2></td>
    </tr>
    <tr>
      <td><h4>CEP:</h4></td>
      <td><h2>80.530-000</h2></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><img src="https://www.webcontrolempresas.com.br/images/tesoura.gif"  /><hr noshade="noshade" style="border:dashed" size="1" width="90%" color="#c0c8c0"  /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><h4>DESTINAT&Aacute;RIO:</h4></td>
      <td><h2><?php echo $franquia; ?></h2></td>
    </tr>
    <tr>
      <td><h4>ENDERE&Ccedil;O:</h4></td>
      <td><h2><?php echo $endereco; ?></h2></td>
    </tr>
    <tr>
      <td><h4>BAIRRO:</h4></td>
      <td><h2><?php echo $bairro; ?></h2></td>
    </tr>
    <tr>
      <td><h4>CIDADE / UF:</h4></td>
      <td><h2><?php echo $cidade." / ".$uf; ?></h2></td>
    </tr>
    <tr>
      <td><h4>CEP:</h4></td>
      <td><h2><?php echo $cep; ?></h2></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr class="noprint">
      <td align="right">
        <input type="button" value="Imprimir" onClick='JavaScript:self.print()'>
      </td>
      <td><input name="button" type="button" onClick="javascript: history.back();" value="  Voltar  " /></td>
  </table>

  <?php
} // fim if go=ingressar
?>