<?php
require "connect/sessao.php";

$go = $_POST['go'];

if (empty($go)) {
$lpp = "8"; // Numero de mensagens por pagina

$sql = "select a.id, a.nome, b.fantasia, a.depoimento from cs2.depoimento a
		inner join cs2.franquia b on a.franquia = b.id order by id desc LIMIT 0, 30";
$conex = mysql_query($sql, $con);
$ordem = mysql_num_rows($conex);

$ql2 = "select a.id, a.nome, b.fantasia, a.depoimento from cs2.depoimento a
					inner join cs2.franquia b on a.franquia = b.id order by id desc";
$sql2 = mysql_query($ql2, $con);
$total = mysql_num_rows($sql2); // Esta funcao ira retornar o total de linhas na tabela
$paginas = ceil($total / $lpp); // Retorna o total de paginas
if(!isset($pagina)) { $pagina = 0; } // Especifica uma valor para variavel pagina caso a mesma nao esteja setada
$inicio = $pagina * $lpp; // Retorna qual sera a primeira linha a ser mostrada no MySQL
$sql2 = mysql_query("select a.id, a.nome, b.fantasia, a.depoimento from cs2.depoimento a
                     inner join cs2.franquia b on a.franquia = b.id order by id desc
                     LIMIT $inicio, $lpp", $con); // Executa a query no MySQL com o limite de linhas.

?>
<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="#000000" style="padding:0">
	<tr>
    	<td align="center">
    	<table width="750" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td rowspan="2"><img src="../img/minhafranquia_01.gif" width="86" height="195" /></td>
                <td><img src="../img/minhafranquia_02.gif" width="580" height="54" /></td>
                <td rowspan="2"><img src="../img/minhafranquia_03.gif" width="84" height="195" /></td>
            </tr>
            <tr>
                <td><img src="../img/minhafranquia_04.gif" width="580" height="141" /></td>
            </tr>
        </table>
        </td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background:url(../../../images/bg_content.jpg); background-repeat:repeat-x">
	<tr>
    	<td>
        	<table style="border-right: #c2c2c2 1px solid; border-left: #c2c2c2 1px solid" cellpadding="0" cellspacing="0" width="580" bgcolor="#FFFFFF" border="0" align="center">
                <tr>
                    <td class="normal">Escreva aqui a hist&oacute;ria da sua Franquia, o seu inicio, quem voc&ecirc; era, suas dificuldades passadas, e como fez para chegar ao seu sucesso, testemunhando assim a sua vit&oacute;ria</td>
                </tr>
                <tr>
                	<td valign="top" width="580" background="../../../images/topocinza.jpg" height="40">
                    	<table height="30" cellpadding="0" width="98%" align="right">
                        	<tr>
                            	<td valign="top"><font face="arial" color="#666666" size="2"><b>Comente aqui</b></font></td>
                            </tr>
                        </table>                    </td>
                </tr>
                <tr>
                	<td>
                    <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
                    <table border="0" class="normal" width="575">
                      <tr>
                        <td width="80">Nome</td>
                        <td width="319"><input name="nome" type="text" size="40" maxlength="30" class="boxnormal" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" ></td>
                      </tr>
                      <tr>
                        <td>Franquia</td>
                        <td>
						<?php
						if (($tipo == "a") || ($tipo == "c")) {  
							echo "<select name=\"franqueado\" class=boxnormal>";
							$sql = "select * from cs2.franquia where tipo='b' order by id";
							$resposta = mysql_query($sql, $con);
							while ($array = mysql_fetch_array($resposta))
								{
								$franquia   = $array["id"];
								$nome_franquia = $array["fantasia"];
								echo "<option value=\"$franquia\">$nome_franquia</option>\n";
								}
							echo "</select>";
						}
						else {
							echo $nome_franquia;
							echo "<input name=\"franqueado\" type=\"hidden\" id=\"franqueado\" value= $id_franquia; />";
							}
						?>                        </td>
                      </tr>
                      <tr>
                        <td>Depoimento</td>
                        <td><textarea name="depoimento" cols="60" rows="6" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"></textarea></td>
                      </tr>
                      <tr>
                        <td><input type="hidden" name="go" value="cadastrar" /></td>
                        <td><input type="submit" name="Submit" value="Comentar!" class="botao3d"></td>
                      </tr>
                    </table>
                    </form>                    </td>
                </tr>
                <tr>
                	<td valign="top" width="580" background="../../../images/topocinza.jpg" height="40">
                    	<table height="30" cellpadding="0" width="98%" align="right">
                        	<tr>
                            	<td valign="top"><font face="arial" color="#666666" size="2"><b>Palavras do Diretor</b></font></td>
                            </tr>
                        </table>                    </td>
                </tr>
                <tr>
                    <td align="center"><textarea name="textarea" cols="100" rows="9" class="boxover" style="background-color:#FFCC66">
                Olá meus amigos franqueados Web Control Empresas,

                Apesar de atualmente ser Diretor da Web Control Empresas, me toca compartilhar minha historia também, ou seja,  MINHA FRANQUIA ,  MINHA VIDA. Para os que ainda não conhecem minha história, saibam que também fui um franqueado como todos vocês durante 14 anos em um de nossos concorrentes.


                Nasci em Goiânia em 1974,  garoto humilde, de família pobre, e com um sonho: Ter sucesso profissional e mudar de vida !!   Era um rapaz muito tímido, sempre de boné escondendo o rosto,  quando as pessoas chegavam na minha casa eu me fechava no quarto de vergonha de cumprimentar.  Com 14 anos arrumei meu 1º emprego em um supermercado como empacotador, e ali fiquei por 2 meses.   Logo em seguida recebi um convite de meu cunhado para ver uma vaga de vendedor na check-check,  mas odiei o convite pois odiava vendas, não era a minha praia, mesmo assim recebi um treinamento de "10 minutos" e peguei o material.  O 1º dia de trabalho na rua foi o suficiente para entregar o material,  me sentia um derrotado, pois quando cheguei na frente do dono do supermercado onde tinha trabalhado, e ele que acabou explicando que tipo de produto eu vendia, é mole ?, não consegui falar uma frase sequer !!     Odiei a experiência e entreguei o material no outro dia e disse que isso era profissão de quem falava muito !!

                Voltei a ser um empacotador no mesmo supermercado até deixar cair 6 caixas de garrafas de cerveja no chão,  aí lascou,   meu salário foi todo escorrendo ralo adentro.

                Eu gostei do produto da tal check-check, só não tinha dom pra coisa,  mas resolvi voltar lá e me dar uma chance,  peguei o material de novo, e peguei minha bicicleta, coloquei a pastinha na garupa e saí pedalando muito pra vender aquele troço de novo.  No 2º dia  de vendedor entrei em um mercadinho e o dono estava com um cheque sem fundo na mão ... ai  ai ... disse que agora é a hora,  e me apresentei e com poucas palavras o empresário disse:  ESTAVA TE ESPERANDO A MUITO TEMPO GAROTO,  onde eu assino ?

                Os dias se passaram e em 4 meses passei ser o melhor vendedor daquela empresa e fui promovido a Gerente de Vendas.

                Como gerente de vendas apresentei os melhores resultados que já tinham visto naquela franquia e como um bom sonhador pensei,  puxa como eu gostaria de ter a minha franquia.

                Surgiu uma oportunidade de montar uma franquia na cidade de Foz do Iguaçu,  no fim do mundo, e pensei, é agora,  vou dar a minha vida pra poder mudar de vida.

                Nunca tinha saído da cidade de Goiânia, e queria montar uma franquia,  mas sem dinheiro como faria ?  Peguei a velha Lavadora Brastemp da minha mãe e vendi por R$ 300,00.

                Os  R$ 300,00 era suficiente para comer durante 1 mês somente na cidade de Foz do Iguaçu. Com esse dinheiro aluguei uma salinha,  coloquei somente uma mesinha e um sofazinho bem velho.  Aluguei um “cafofo” de  3 metros por 3  ... um quadradinho e ali coloquei meu colchão fininho no chão e fiquei por 1 ano.   Neste período de 1 ano conto em minhas mãos quantas vezes comi comida de verdade,  minha alimentação era pão com mortadela todo dia,  quando comia comida era porque meus queridos vendedores me pagava um almoço.

                Apareceram os primeiros vendedores dos anúncios,  1 deles hoje é minha testemunha viva e presente:  Carlinhos (Franquia de Londrina I).  Testemunhou minhas dificuldades extremas e dou graças por terem entrado pela porta da minha franquia.

                Comecei minha Franquia em Janeiro 1994 e no mês de Dezembro 1994 eu já tinha 800 clientes em toda região, e comprei meu primeiro carro, e ainda dormia no chão, mas já comia uma macarronada com carne moída de vez em quando.  Ohh saudade daquelas macarronadas .  rsrsr

                Nosso desempenho foi tão bom que a Diretoria nos chamou e nos deu a oportunidade de abrir outras franquias no estado do Paraná,  e não perdemos tempo,  abri outra franquia em Cascavel,  outra em Londrina,  outra em Maringá,  outra em Ponta Grossa  e  finalmente  chegamos a Capital tão sonhada de Curitiba.

                Trabalhamos muito e com todas as minhas forças,  sempre pensando nos nossos vendedores como os únicos responsáveis pelo nosso crescimento,  e consegui  2.400 EMPRESÁRIOS AFILIADOS no Estado do Paraná.

                Com todo sucesso já alcançado,  ainda não era suficiente, precisava alcançar o Brasil, de Norte a Sul, de Leste a Oeste,  e com muita astúcia e muita seriedade, vendi minha parte da Franquia e investi todo o meu patrimônio, economias, influencias e experiência,   e muito e muito trabalho,   FUNDAMOS A WEB CONTROL EMPRESAS.

                Com o inicio cheio de dificuldades firmamos muitas parcerias, e outras nem nos receberam, mesmo assim nunca perdemos as esperanças,  com vitórias e derrotas, nunca desistimos.

                Começou a instalação da 1º Franquia Web Control Empresas (Curitiba) e hoje já somos 8 Franquias.

                Meu sonho, nosso sonho: Alcançar todas as cidades do Brasil e tornar a WEB CONTROL EMPRESAS a MAIOR,  a COMPLETA, a MAGNIFICA empresa de informações e soluções de TODO TERRITORIO BRASILEIRO.

                Com todo sucesso já alcançado,  ainda não era suficiente, precisava alcançar o Brasil, de Norte a Sul, de Leste a Oeste,  e com muita astúcia e muita seriedade, vendi minha parte da Franquia e investi todo o meu patrimônio, economias, influências e experiência,   e muito e muito trabalho,   FUNDAMOS A Web Control Empresas.

                Com o início cheio de dificuldades firmamos muitas parcerias, e outras nem nos receberam, mesmo assim nunca perdemos as esperanças,  com vitórias e derrotas, nunca desistimos.

                Wellington Fernandes
                Diretor Web Control Empresas
                        </textarea>
                        <br />.
                        </td>
                      </tr>
                <tr>
                	<td valign="top" width="580" background="../../../images/topocinza.jpg" height="40">
                    	<table height="30" cellpadding="0" width="98%" align="right">
                        	<tr>
                            	<td valign="top"><font face="arial" color="#666666" size="2"><b>Depoimentos de Sucesso</b></font></td>
                            </tr>
                        </table>                    </td>
                </tr>
          <tr>
                	<td>
<?php
if ($total == 0) {
	echo "N&atilde;o h&aacute; nenhuma ocorr&ecirc;ncia registrada at&eacute; o presente momento.";
} else {
	while($valor = mysql_fetch_array($sql2)) {
?>
            <table width="500" class="bodyText" align="center">
                <tr>
                    <td width="150"><strong>Nome</strong></td>
                    <td><?php echo $valor[nome]; ?></td>
                </tr>
                <tr>
                    <td><strong>Franquia</strong></td>
                    <td><?php echo $valor[fantasia]; ?></td>
                </tr>
                
                <tr class="campoesquerda">
                    <td valign="top"><strong>Descri&ccedil;&atilde;o:</strong></td>
                    <td><?php echo $valor[depoimento]; ?></td>
                </tr>
            </table>
	<hr noshade="noshade" size="1" width="60%" color="#c0c8c0" align="center" />
    <?php
	} //fim while
} //fim else
?>					</td>
                </tr>
                <tr>
                	<td align="center" class="normal">
<?php
// Paginacao
if($pagina > 0) {
   $menos = $pagina - 1;
   $url = "$paginacao[link]pagina1=ranking/c_minhafranquia.php&pagina=$menos";
   echo "<a href=\"$url\" class=\"bodyText\" onMouseOver=\"window.status='Anterior'; return true\">Anterior</a>"; // Vai para a pagina anterior
}
for($i=0;$i<$paginas;$i++) { // Gera um loop com o link para as paginas
   $url = "$paginacao[link]pagina1=ranking/c_minhafranquia.php&pagina=$i";
   echo " | <a href=\"$url\" class=\"bodyText\" onMouseOver=\"window.status='Pagina $i'; return true\">$i</a>";
}
if($pagina < ($paginas - 1)) {
   $mais = $pagina + 1;
   $url = "$paginacao[link]pagina1=ranking/c_minhafranquia.php&pagina=$mais";
   echo " | <a href=\"$url\" class=\"bodyText\" onMouseOver=\"window.status='Proxima'; return true\">Próxima</a>";
}
?>
<br />
Temos um total de <b><?php echo $ordem ?></b> <?php if ($ordem == "1") echo "depoimento"; else echo "depoimentos"; ?>!                    </td>
                </tr>
            </table>
<table cellpadding="0" cellspacing="0" width="580" bgcolor="#FFFFFF" border="0" align="center">
            	<tr>
                	<td valign="top" background="../../../images/bottom.jpg" height="50"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?php
} //fim empty go

if ($go=='cadastrar') {
	$nome = $_POST['nome'];
	$franqueado = $_POST['franqueado'];
	$depoimento = $_POST['depoimento'];
	
	if($nome == "" || $franqueado == "" || $depoimento == ""){
		echo "<script>
			  alert(\"Não foi possível enviar sua mensagem. \\nVolte e complete o formulário corretamente!\");
			  window.location = 'javascript:history.back(-1)';
			  </script>";
	} else {
		$sql = "insert into cs2.depoimento(nome, franquia, depoimento) values ('$nome', '$franqueado', '$depoimento')";
		$qr = mysql_query($sql, $con);
		mysql_close($con);
		echo "<script>alert(\"Depoimento cadastrado com sucesso!\");</script>";
		echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=ranking/c_minhafranquia.php\";>";
	}
	exit;
}
?>