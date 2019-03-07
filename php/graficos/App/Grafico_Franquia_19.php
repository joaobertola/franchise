<?php
include("Includes/Connection_inc.php");
include("Includes/PageLayout.php");
include("DataGen.php");

$idfranquia = $_REQUEST['franqueado'];

if ( ($idfranquia == '9999999') or (empty($idfranquia)) ){
    $nome_franquia = 'Todas as Franquias - útimos 12 Meses';
} else{
    $selecao = " a.id_franquia = $idfranquia AND "; 
    $nome_franquia = nome_franquia($idfranquia);
}
?>
<HTML>
<HEAD>
    <TITLE>
        Gráficos WEB CONTROL EMPRESAS
    </TITLE>


    <link rel="icon" href="imgs/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="graficos/App/css/morris.css">
    <script src="graficos/App/js/jquery.min.js"></script>
    <script src="graficos/App/js/raphael-min.js"></script>
    <script src="graficos/App/js/morris.min.js"></script>    
</HEAD>

<style type="text/css">
    #overlay 
    { 
      position:absolute;
      left:57%;
      top:50%;
      margin-left:-110px;
      margin-top:-40px;
    }      

</style>

<?php
    //Render page headers
    // if (!empty($_REQUEST['franqueado'])){
    //     echo render_pageHeader();
    // }
?>

<input type="hidden" id="selecao" value="<?php echo $selecao ?>">
<div style="text-align: center;background-color: white;">
    <h1 style="font-family: arial black;">Notas Fiscal ( NFC-e )</h1>
    <p style="font-family: arial black; font-size: 22px; margin: 0px;"><?php echo $nome_franquia ?></p>
</div>
<div id="overlay">
  <img src="graficos/App/ajax-loader.gif" alt="Be patient..." />
</div>  
<div id="chart" style="background-color: white; height: 300px;"></div>
<br>
<br>
<br>
<table align='center' cellspacing='0' cellpadding='0'>
    <tr>
        <td align='left'>
        <?php if (!empty($_REQUEST['franqueado'])){ ?>
        <a href="painel.php?pagina1=graficos/i_graficos.php">Retorna</a>
        <?php } ?>
        </td>
    </tr>
    <tr>
    <td align='center'>
    </td>
    </tr>
    
    <?php if( ($_REQUEST['id_franquia_session'] != '163') and ($_REQUEST['id_franquia_session'] != "") ){ ?>
        <tr><td bgcolor="#CCCCCC">&nbsp;</td></tr>
        <tr>
        <td align="center">
          <iframe src="Grafico_Franquia_14.php" width="100%" height="700" frameborder="0" scrolling="0"></iframe>
        </td>
        </tr>   
            <tr><td height="100">&nbsp;</td></tr>
    <?php } ?>
</table>
</BODY>
</HTML>

<script type="text/javascript">
    var selecao = $("#selecao").val();

    $("#overlay").css("display", "block");

    $.ajax({
        url: "graficos/App/GraficoAjax.php",
        method: "POST",
        data: {
            action: "grafico_franquia_19",
            selecao: selecao
        },
        dataType: 'json',
        success: function (res) {

        $("#overlay").css("display", "none");

            Morris.Bar({
              element: 'chart',
              data: res,
              xkey: ['y'],
              ykeys: ['x'],
              labels: ['Notas'],
              hideHover: 'auto',

              horizontal: true,
              stacked: true,
              xLabelAngle: 25
            });   

            var items = $("#chart").find( "svg" ).find("rect");
            $.each(items,function(index,v){
                var value = res[index].x;
                var newY = parseFloat( $(this).attr('y') - 20 );
                var halfWidth = parseFloat( $(this).attr('width') / 2 );
                var newX = parseFloat( $(this).attr('x') ) +  halfWidth;
                var output = '<text style="text-anchor: middle; font: 12px sans-serif;" x="'+newX+'" y="'+newY+'" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#000000" font-size="12px" font-family="sans-serif" font-weight="normal" transform="matrix(1,0,0,1,0,6.875)"><tspan dy="3.75">'+value+'</tspan></text>';
                $("#chart").find( "svg" ).append(parseSVG(output));
            });  
        }      
    });

    function parseSVG(s) {
        var div= document.createElementNS('http://www.w3.org/1999/xhtml', 'div');
        div.innerHTML= '<svg xmlns="http://www.w3.org/2000/svg">'+s+'</svg>';
        var frag= document.createDocumentFragment();
        while (div.firstChild.firstChild)
            frag.appendChild(div.firstChild.firstChild);
        return frag;
    }       
</script>
