<?php
define("FPATH", "./dataFiles/");
define("IPATH", "gen1/");
define("TPATH", "typeIcons/");
define("POKEDATA", "pokemon_data.csv");
define("TYPEDATA", "type_bonus.csv");
define("MOVEDATA", "moves_data.csv");
define("LEARNDATA", "learn_data.json");
define("TEAMSIZE", 6);

class Attributes {
    public $total;
    public $hp;
    public $atk;
    public $def;
    public $spAtk;
    public $spDef;
    public $speed;
    public $legendary;
}

class Pokemon {
    public $id;
    public $name;
    public $types;
    public $attr;
    public $hp;
    public $status;
    public $moves;
    public $img;
}

class Team {
    public $pkm;
}

class Move {
    public $name;
    public $type;
    public $category;
    public $power;
    public $accuracy;
    public $pp;
    public $effect;
}

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
    echo "<script>console.log(\"" . $str . "\" );</script>";
}
// int2id()
// Converts int to 0 padded id string
function int2id($n) {
    return sprintf("%03d", $n);
}

function arr2attr($arr) {
    $attr = new Attributes();
    $i = 0;
    foreach ($attr as &$value) {
        $value = $arr[$i];
        $i++;
    }
    return $attr;
}

function arr2move($arr) {
    $move = new Move();
    $i = 0;
    foreach ($move as &$value) {
        $value = $arr[$i];
        $i++;
    }
    return $move;
}
?>