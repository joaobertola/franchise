<script type="text/javascript" src="../../../inform/js/prototype.js"></script>

<script>
function pisca(item){
    var ob = document.getElementById(item);
    if (ob.style.color=="white"){
        ob.style.color="black";
    }else{
        ob.style.color="white";
    }
}
</script>

<table border="0" align="center" width="700" bordercolor="#CCCCCC">
    <tr>
        <td colspan="3">
            <center>
                <a href="./area_restrita/upload/arquivo_solicitacao/SCRIPT PARA REAJUSTE DE PACOTES.pdf">
                    <div id="piscar" style="color:red">
                        SCRIPT PARA REAJUSTE DE MENSALIDADE
                    </div>
                </a>
                <br>
                <a href="./area_restrita/upload/ARGUMENTACAO PARA REAJUSTE MENSALIDADE.pdf">
                    <div id="piscar" style="color:red">
                        ARGUMENTAÇÃO PARA REAJUSTE MENSALIDADE / SERVIÇOS
                    </div>
                </a>
            </center>
        </td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td class="titulo" colspan="3">Sua Franquia se comprometeu com a Franquedora</td>
    </tr>
    <tr bgcolor="#87b5ff" align="center">
        <td>Comprometimento</td>
        <td>Contratos Fechados</td>
        <td>Saldo</td>
    </tr>
    <tr bgcolor="#E5E5E5">
        <td align="center"><?php echo $_REQUEST['mc']?></td>
        <td align="center"><?php echo $_REQUEST['tf']?></td>
        <td align="center">
            <b><?php
                $restante = $_REQUEST['ft'];
                if ( $restante > 0 )
                        echo "<font color='#FF0000'>Faltam $restante</font>";
                else
                        echo "<font color='#0000FF'>Parab&eacute;ns... Voc&ecirc; cumpriu sua meta.</font>";

                ?>
            </b>
        </td>
    </tr>
</table>
<p>
<form name="form1" method="post" action="#" >
    <table border="0" align="center" width="700" bordercolor="#999999">
        <tr>
            <td class="titulo"><br>SISTEMA&nbsp;&nbsp;DE&nbsp;&nbsp;CONTROLE&nbsp;&nbsp;COMERCIAL</td>
        </tr>
        <tr>
            <td height="25">&nbsp;</td>
        </tr>
        <tr>
            <td class="subtitulopequeno" height="25"><a href="painel.php?pagina1=clientes/lista_consultores.php&id_franquia=<?php echo $_SESSION['id']?>">Cadastrar Assistentes ou Consultores</a></td>
        </tr>
        <tr>
            <td class="subtitulopequeno" height="25"><a href="painel.php?pagina1=clientes/a_controle_visitas1aa.php">Cadastrar Agendamentos</a></td>
        </tr>
        <tr>
            <td class="subtitulopequeno" height="25"><a href="painel.php?pagina1=clientes/a_controle_visitas2.php">Localizador de Visitas</a></td>
        </tr>
        <tr>
            <td class="subtitulopequeno" height="25"><a href="painel.php?pagina1=clientes/a_controle_visitas3.php">Relat&oacute;rios Comerciais</a></td>
        </tr>
        <tr>
            <td class="subtitulopequeno" height="25"><a href="painel.php?pagina1=clientes/a_controle_visitas4.php">Relat&oacute;rios de Tarefas Comerciais</a></td>
        </tr>
        <tr>
            <td class="subtitulopequeno" height="25"><a href="painel.php?pagina1=clientes/a_controle_visitas5.php">Imprimir Agendamento Consultor</a></td>
        </tr>
        <?php if($id_franquia == 4 || $id_franquia == 163 || $id_franquia == 247 || $id_franquia = 1){ ?>
                <tr>
                    <td class="subtitulopequeno" height="25"><a href="painel.php?pagina1=clientes/a_controle_visitas6.php">Cadastrar Desafios</a></td>
                </tr>
                <tr>
                    <td class="subtitulopequeno" height="25"><a href="painel.php?pagina1=clientes/a_controle_visitas8.php">Cadastrar Campeões</a></td>
                </tr>
        <?php } ?>

        <?php if($id_franquia == 4 || $id_franquia == 163 || $id_franquia == 247 || $id_franquia = 1){ ?>
                <tr>
                    <td class="subtitulopequeno" height="25"><a href="painel.php?pagina1=clientes/a_controle_visitas7.php">Relatório de Participa&ccedil;&atilde;o</a></td>
                </tr>
        <?php } ?>

        <?php if($id_franquia == 163){ ?>
                <tr>
                    <td class="subtitulopequeno" height="25"><a href="painel.php?pagina1=clientes/a_controle_visitas9.php">Campanha Torpedos</a></td>
                </tr>
        <?php } ?>
        <tr>
            <td class="subtitulopequeno" height="25"><a href="painel.php?pagina1=clientes/a_controle_visitas10.php">Relatório Equipamentos</a></td>
        </tr>
        <?php if($id_franquia == 163){ ?>
            <tr>
                <td class="subtitulopequeno" height="25"><a href="painel.php?pagina1=clientes/a_lista_cliente_segmento1.php">Lista por Segmentos</a></td>
            </tr>
        <?php } ?>
        <?php if($id_franquia == 163 OR $id_franquia == 4){ ?>
            <tr>
                <td class="subtitulopequeno" height="25"><a href="painel.php?pagina1=clientes/cadastrar_concorrentes.php">Cadastrar Concorrentes</a></td>
            </tr>
        <?php } ?>                
        <tr>
            <td height="25">&nbsp;</td>
        </tr>

    </table>
<!--	<table border="0" align="center"  class="table table-striped table-responsive col55 " >-->
<!--		--><?php //include("a_controle_oficina.php"); ?>
<!--	</table>-->