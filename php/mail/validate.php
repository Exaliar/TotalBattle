<?php 
function sprawdz($test){
    global $db;
    $test = $db->real_escape_string($test);
    return trim(htmlspecialchars($test));
}
?>