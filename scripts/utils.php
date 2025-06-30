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
    public $total;      // Int
    public $hp;         // Int
    public $atk;        // Int
    public $def;        // Int
    public $spAtk;      // Int
    public $spDef;      // Int
    public $speed;      // Int
    public $legendary;  // Bool
}

class Pokemon {
    public $id;      // Int
    public $name;    // String
    public $types;   // String[2]
    public $attr;    // Attributes
    public $hp;      // Int
    public $status;  // String
    public $moves;   // Move()[4]
    public $img;     // String
}

class Team {
    public $pkm;  // Pokemon()[6]
}

class Move {
    public $name;      // String
    public $type;      // String
    public $category;  // String
    public $power;     // Int
    public $accuracy;  // Int
    public $pp;        // Int
    public $effect;    // String
    public $tags;      // String[2]
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
        if ($i === 7) {
            $value = [$arr[$i], $arr[$i + 1]];
            break;
        }
        $i++;
    }
    return $move;
}
?>