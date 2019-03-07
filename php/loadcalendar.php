<?php

$m = date('m');
$y = date('Y');
if (isset($_REQUEST['mes'])) {
    $m = $_REQUEST['mes'];
}
$ds = date('w', strtotime($y . '-' . $m . '-01'));
$t = date('t', strtotime($y . '-' . $m . '-01'));

function getMes($m)
{
    if ($m < 10) {
        $m = str_replace('0', '', $m);
    }
    $meses = array(1 => 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
    return strtoupper($meses[$m]);
}

echo '<table class="table table-bordered">
            <thead>
                <tr><th class="text-left"><a style="color:#000;font-size:12px;"></a></th><th colspan="4" class="text-center">' . getMes($m) . '</th><th class="text-left"></th></tr>
              </thead>
        <tr>
    ';

$ref = 0;
for ($i = 1; $i <= 31; $i++) {
    if ($ref == 6) {
        echo '</tr><tr>';
        $ref = 0;
    }
    $ref++;
    echo '<td class="col-md-1"><input type="checkbox" name="iptDiasSemana[]" class="dia-' . $i . '" value="' . $i . '" />' . $i . '</td>';

}
echo '</tr>
                                </table>';
?>