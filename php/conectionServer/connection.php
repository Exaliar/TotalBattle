<?php
function connect($dbHost, $dbName, $dbUser, $dbPass){
    $db = @new mysqli($dbHost, $dbName, $dbUser, $dbPass);
    if($db->connect_errno){
        die('Błąd połączenia: '.$db->connect_errno);
    }
    return $db;
}
?>