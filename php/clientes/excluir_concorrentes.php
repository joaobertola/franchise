<?php

$con = @mysql_connect("10.2.2.3", "csinform", "inform4416#scf");

$sql = "DELETE FROM cs2.concorrente WHERE id = ".$_POST['id']."";
mysql_query( $sql, $con) or die(mysql_error());

