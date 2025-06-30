<?php
define("FPATH", "./dataFiles/");
define("IPATH", "gen1/");
define("POKEDATA", "pokemon_data.csv");
define("TYPEDATA", "type_bonus.csv");
define("MOVEDATA", "moves_data.csv");
define("LEARNDATA", "learn_data.json");

// makePDO()
// Creates PDO with proper params
function makePDO() {
    $pdo = new PDO("mysql:host=localhost;dbname=Proj3", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}
// conLog()
// Logs messages to javascript console
function conLog($str) {
    echo "<script>console.log(\"SQLSetup: " . $str . "\" );</script>";
}
// int2id()
// Converts int to 0 padded id string
function int2id($n) {
    return sprintf("%03d", $n);
}
?>