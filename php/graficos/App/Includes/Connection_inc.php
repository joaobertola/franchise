<?php
// In this page, we open the connection to the Database
// Our MySQL database (blueprintdb) for the Blueprint Application
// Function to connect to the DB
function connectToDB() {
    @$link = mysql_connect ('10.2.2.3', 'csinform', 'inform4416#scf');
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }
    $db_selected = mysql_select_db('FusionChartsDB');
    if (!$db_selected) {
        die ('Can\'t use database : ' . mysql_error());
    }
    return $link;
}

function connectToDB_Virtual() {
    @$link = mysql_connect ('10.2.2.7', 'root', 'cntos43');
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }
    $db_selected = mysql_select_db('dbsites');
    if (!$db_selected) {
        die ('Can\'t use database : ' . mysql_error());
    }
    return $link;
}

?>