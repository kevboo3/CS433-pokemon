<?php
require "scripts/utils.php";
// Start the session
session_start();
header('Content-Type: application/json');

// Generate 6 random IDs for the enemy team exluding #131 ditto
$randIds = range(1, 151);
unset($randIds[131]);
shuffle($randIds);
$randIds = array_slice($randIds, 0, TEAMSIZE);
$pdo = makePDO();

// Populate team
$enemyTeam = new Team();
for ($i = 0; $i < TEAMSIZE; $i++) {
    $enemyTeam->pkm[$i] = new Pokemon();
    $enemyTeam->pkm[$i]->id = $randIds[$i];  // Assign random ids to pokemon
}

// Loads pokemon team data given a set of Pokedex Ids, 
foreach ($randIds as $key => $id) {  // Iterates over team
    $enemyTeam->pkm[$key] = makePokemon($id);
}

// Gets Move Data
for ($i = 0; $i < TEAMSIZE; $i++) {  // Iterates over team
    // Generates list of all moves each pokemon can learn by level up
    $stmt = $pdo->prepare("SELECT * FROM Learn WHERE Id = " . $enemyTeam->pkm[$i]->id);
    $stmt->execute();
    $rslt = $stmt->fetch(PDO::FETCH_NUM);
    $allMoves[$i] = [];
    foreach (array_slice($rslt, 2) as $col) {
        $allMoves[$i] += explode(",", $col);
    }
    for ($j = 0; $j < count($allMoves[$i]); $j++) {  // Iterates over all possible moves
        $stmt = $pdo->prepare("SELECT * FROM Moves WHERE Name = \"" . $allMoves[$i][$j] . "\"");
        $stmt->execute();
        $rslt = $stmt->fetch(PDO::FETCH_NUM);
        if (!$rslt) {
            var_dump($stmt);
        }
        $allMoves[$i][$j] = arr2move($rslt);
    }
    // Assigns default moves to team
    for ($j = 0; $j < 4; $j++) {  // Iterates over team moves

        if ($j < count($allMoves[$i])) {
            $enemyTeam->pkm[$i]->moves[$j] = $allMoves[$i][$j];
        } else {
            $enemyTeam->pkm[$i]->moves[$j] = new Move();
        }
        // echo $enemyTeam->pkm[$i]->moves[$j];
    }
}
echo json_encode($_SESSION);

// var_dump($_SESSION);
