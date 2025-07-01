<?php
require "utils.php";

$ids = explode(",", $_GET["ids"]);
$pkmArr = [];
for ($i = 0; $i < count($ids); $i++) {
    $pkmArr[$i] = makePokemon($ids[$i]);
}
echo json_encode($pkmArr);
?>