<?php
session_start();
require "setup.php";
?>
<!DOCTYPE html>
<html>
<?php setup();?>
<head>
<?php
$team = [NULL, NULL, NULL, NULL, NULL, NULL];  // Team Array
$pkmKeys = ["Id", "Name", "Attr", "Hp", "Status", "Moves", "Img"];
$nums = range(1, 151);                         // All Pokedex Ids
shuffle($nums);                                // Randomize Ids
for ($i = 0; $i < 6; $i++) {                   // Assign Random Team of Pokedex Ids
    $team[$i] = ["Id" => intval($nums[$i])];
}

// Given a random set of Pokedex Ids, loads pokemon team data
$pdo = makePDO();
for ($i = 0; $i < 6; $i++) {  // Iterates over team
    $stmt = $pdo->prepare("SELECT * FROM Pokedex WHERE Id = " . $team[$i]["Id"]);
    $stmt->execute();
    $rslt = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $team[$i][$pkmKeys[1]] = $rslt[0]["Name"];
    $team[$i][$pkmKeys[2]] = array_slice($rslt[0], 2);
    $team[$i][$pkmKeys[3]] = $team[$i][$pkmKeys[2]]["Hp"];
    $team[$i][$pkmKeys[4]] = NULL;
    $team[$i][$pkmKeys[5]] = NULL;
    $team[$i][$pkmKeys[6]] = FPATH . IPATH . int2id($team[$i][$pkmKeys[0]]) . $team[$i][$pkmKeys[1]] . ".png";
}


// Gives each pokemon it's first 4 levelup moves by default
// If pokemon has less than 4 levelup moves, sets remaining move names to NULL
for ($i = 0; $i < 6; $i++) {  // Iterates over team
    $stmt = $pdo->prepare("SELECT Level FROM Learn WHERE Id = " . $team[$i]["Id"]);
    $stmt->execute();
    $rslt = explode(",", $stmt->fetch(PDO::FETCH_ASSOC)["Level"]);
    for ($j = 0; $j < 4; $j++) {  // Iterates over moves
        if ($j < count($rslt)) {
            $team[$i]["Moves"][$j]["Name"] = $rslt[$j];
        }
        else{
            $team[$i]["Moves"][$j]["Name"] = NULL;
        }
    }
}

// Gets move stats for assigned moves
for ($i = 0; $i < 6; $i++) {  // Iterates over team
    for ($j = 0; $j < 4; $j++) {  // Iterates over moves
        if ($team[$i]["Moves"][$j]["Name"]) {  // If name != NULL
            $stmt = $pdo->prepare("SELECT * FROM Moves WHERE Name = \"" . $team[$i]["Moves"][$j]["Name"] . "\"");
            $stmt->execute();
            $rslt = $stmt->fetch(PDO::FETCH_ASSOC);
            $team[$i]["Moves"][$j] += $rslt;
        }
    }
}
$_SESSION["team"] = $team;
?>
</head>
<body>
<form method="POST" action="select_moves.php">
  <input type="submit">
</form>
</body>
</html>