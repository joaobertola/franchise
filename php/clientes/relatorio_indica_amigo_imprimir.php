<?php
/**
 * Created by PhpStorm.
 * User: SimÃ©ia
 * Date: 05/08/2016
 * Time: 09:37
 */

require "../connect/sessao.php";
require "../connect/conexao_conecta.php";


function datetoUS($dataBrasil) {
    $d = explode('/', $dataBrasil);
    $d[2] = empty($d[2]) ? '' : $d[2];
    $d[1] = empty($d[1]) ? '' : $d[1];
    $d[0] = empty($d[0]) ? '' : $d[0];
    $dtOK = $d[2] . '-' . $d[1] . '-' . $d[0];
    return $dtOK;
}
$separator = '--------------------------------------------------<br>';
$ids = '';
//echo count($_POST['iptIdIndique']);
//die;
for($i = 0; $i < count($_POST['iptIdIndique']); $i++){

    if($i == 0){
        $ids .= $_POST['iptIdIndique'][$i];
    }else{
        $ids .= ',' . $_POST['iptIdIndique'][$i];
    }

}
$sql = "SELECT
            a.id,
            a.codigo_associado,
            c.nomefantasia,
            c.socio1,
            c.fone,
            CONCAT(c.cidade , ' / ', c.uf) AS cidade,
            f.fantasia,
            a.nome_amigo,
            a.fone_amigo1,
            a.fone_amigo2,
            IFNULL(cv.nome, c.vendedor) AS consultor
        FROM base_web_control.indica_amigo a
        INNER JOIN cs2.cadastro c
        ON c.codLoja = a.id_cadastro
        INNER JOIN cs2.franquia f
        ON f.id = c.id_franquia
        LEFT JOIN cs2.consultores_assistente cv
        on c.id_consultor = cv.id
        WHERE FIND_IN_SET(a.id, '$ids');
";

//var_dump($sql);
//die;
$qry = mysql_query( $sql,$con);
$totalRegistros = mysql_num_rows($qry);

$html = '<style>
@page { margin: 0.0in 0.0in 0.0in 0.0in;}
body {
    display: block;
    margin: 0px;
    margin-top: 25px;
    margin-right: 0.5px;
    margin-bottom: 3px;
    margin-left: 0.5px;
    }
</style>';
$html .= '<div id="docPrint" style="font-family:\'Courier New\', Courier, monospace; font-size:10px !important;  letter-spacing:-1px; font-weight: bold; word-wrap: break-word; margin-left: 5px;">';
$j = 1;
while($arrItemResult = mysql_fetch_array($qry)){

    $html .="<strong style='text-align: center;'>ID: ". $arrItemResult['id']."</strong>";
    $html .="<br><strong>DADOS DO AFILIADO:</strong>";

    $html .="<br/><strong>C&oacute;digo do Associado:</strong>". $arrItemResult['codigo_associado'];
    $html .="<br/><strong>Empresa do Afiliado:</strong>" . $arrItemResult['nomefantasia'];
    $html .="<br/><strong>Proprietario:</strong>" . $arrItemResult['socio1'];
    $html .="<br/><strong>Telefone:</strong>" . $arrItemResult['fone'];
    $html .="<br/><strong>Cidade:</strong>" . $arrItemResult['cidade'];
    $html .="<br/><strong>Franquia Respons&aacute;vel:</strong>" . $arrItemResult['fantasia'];
    $html .="<br/><strong>Consultor:</strong>" . $arrItemResult['consultor'];
    $html .="<br/><br/>";
    $html .= $separator;
    $html .="<strong>DADOS DO AMIGO INDICADO:</strong>";
    $html .="<br/><strong>Nome:</strong>" . $arrItemResult['nome_amigo'];
    $html .="<br/><strong>Telefone 1:</strong>" . $arrItemResult['fone_amigo1'];
    $html .="<br/><strong>Telefone 2:</strong>" . $arrItemResult['fone_amigo2'] ;

    if($j != $totalRegistros){
        $html .= '<br><br><br><br>';
        $html .= $separator;
    }

    $j++;
}
$html.= '</div>';

$html.= '<script type="text/javascript">
    setTimeout(function(){
    this.print();
    window.history.go(-1);
    },1500);</script>';
echo $html;
?>