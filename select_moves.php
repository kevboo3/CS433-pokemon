<?php
require "scripts/utils.php";
session_start();
$team = $_SESSION["team"];
$curPkm = $team->pkm[0];
$pdo = makePDO();

// Gives each pokemon it's first 4 levelup moves by default
// If pokemon has less than 4 levelup moves, sets remaining move names to NULL
for ($i = 0; $i < TEAMSIZE; $i++) {  // Iterates over team
    $stmt = $pdo->prepare("SELECT Level FROM Learn WHERE Id = " . $team->pkm[$i]->id);
    $stmt->execute();
    $rslt = explode(",", $stmt->fetch(PDO::FETCH_ASSOC)["Level"]);
    for ($j = 0; $j < 4; $j++) {  // Iterates over moves
        $team->pkm[$i]->moves[$j] = new Move();
        if ($j < count($rslt)) {
            $team->pkm[$i]->moves[$j]->name = $rslt[$j];
        }
        else{
            $team->pkm[$i]->moves[$j]->name = NULL;
        }
    }
}

// Gets move stats for assigned moves
for ($i = 0; $i < TEAMSIZE; $i++) {  // Iterates over team
    for ($j = 0; $j < 4; $j++) {  // Iterates over moves
        if ($team->pkm[$i]->moves[$j]->name) {  // If name != NULL
            $stmt = $pdo->prepare("SELECT * FROM Moves WHERE Name = \"" .  $team->pkm[$i]->moves[$j]->name . "\"");
            $stmt->execute();
            $rslt = $stmt->fetch(PDO::FETCH_NUM);
            $team->pkm[$i]->moves[$j] = arr2move($rslt);
        }
    }
}

// Generates list of all moves each pokemon can learn by level up
for ($i = 0; $i < TEAMSIZE; $i++) {  // Iterates over team
    $stmt = $pdo->prepare("SELECT Level FROM Learn WHERE Name = \"" . $team->pkm[$i]->name . "\"");
    $stmt->execute();
    $allMoves[$i] = explode(",", $stmt->fetch(PDO::FETCH_NUM)[0]);
    for ($j = 0; $j < count($allMoves[$i]); $j++) {
        $stmt = $pdo->prepare("SELECT * FROM Moves WHERE Name = \"" . $allMoves[$i][$j] . "\"");
        $stmt->execute();
        $rslt = $stmt->fetch(PDO::FETCH_NUM);
        $allMoves[$i][$j] = arr2move($rslt);
    }
}
$posMoves = $allMoves[0];
?>
<!DOCTYPE html>
<html lang="en">
<!-- our reference  -->
<!-- https://play.pokemonshowdown.com/teambuilder -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/shared.css">
    <link rel="stylesheet" href="styles/select_moves.css">
    <title>Select your moves</title>
</head>
<body>
<div class="container">
    <h1>Select Your Pokemon's Moves!</h1>
    <div class="team">
        <span class="pokemon-buttons">
            <?php for($i = 0; $i < 2; $i++): ?> 
                <?php for($j = 0; $j < 3; $j++): ?>
                    <span><?= $team->pkm[$j + $i * 3]->name ?></span>
                <?php endfor; ?>
                <?php for($j = 0; $j < 3; $j++): ?>
                    <button class="<?= strtolower($team->pkm[$j + $i * 3]->types[0]) ?>-type" style="background-image: url('<?= $team->pkm[$j + $i * 3]->img ?>');" id="pkm<?= $j + $i * 3 ?>"></button>
                <?php endfor; ?>
            <?php endfor; ?>
        </span>
    </div>
    <div class="current-pokemon">
        <div>Selecting Moves For:</div>
        <img id="currPkmImg" class="active-pokemon <?= strtolower($curPkm->types[0])?>-type" src="<?= $curPkm->img ?>">
        <div class="curr-name-type">
            <span id="nameTxt"><?= $curPkm->name ?></span>
            <span id="typeIcons">
                <img class="type-icon" src="<?= FPATH . TPATH . strtolower($curPkm->types[0])?>.png" alt='<?= $curPkm->types[0] ?>'>
                <?php if ($curPkm->types[1]): ?>
                    <img class="type-icon" src="<?= FPATH . TPATH . strtolower($curPkm->types[1])?>.png" alt='<?= $curPkm->types[1] ?>'>
                <?php endif; ?>
            </span>
        </div>
        <div id="statsSpan">
            <table id="attrTable" class="attr-table">
                <thead>
                <?php foreach ($curPkm->attr as $key => $stat): ?>
                    <?php if ($key <> "legendary"): ?>
                        <th class="<?= strtolower($key) ?>-stat"><?= ucfirst($key) ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
                </thead>
                <tbody >
                    <tr>
                    <?php foreach (array_slice((array) $curPkm->attr, 0, -1) as $key => $stat): ?>
                        <td class="<?= strtolower($key) ?>-stat"><?= $stat ?></td>
                    <?php endforeach; ?>
                    </tr>
                <tbody>
            </table>
        </div>
        <div class="moves-selection">
            <span>Selected Moves: </span>
            <div class="moves-input">
            <?php 
                foreach ($curPkm->moves as $key => $curMove) {
                    echo "<input list='levelList$key' id='move$key' value='" . ($curMove->name ?? "None") . "'>\n";
                    echo "<datalist id ='levelList$key'>\n";
                    foreach ($posMoves as $posMove) {
                        $valid = True;
                        foreach ($curPkm->moves as $move) {
                            if ($move->name == $posMove->name
                                and $curMove->name != $posMove->name) {
                                $valid = False;
                                break;
                            }
                        }
                        if ($valid) {
                            echo "<option value='" . $posMove->name . "'>\n";
                        }
                    }
                    foreach ($curPkm->moves as $move) {
                        if ($move->name
                            or !$curMove->name) {
                            echo "<option value='None'>\n";
                            break;
                        }
                    }
                    echo "</datalist>\n";
                }
            ?>
            </div>
            <div id="navButtons">
                <span>
                    <button id="back">Change Team</button>
                    <button id="update">Update Moves</button>
                    <button id="next">Confirm Team</button>
                </span>
            </div>
        </div>
    </div>
    <table class="moves-table">
        <h3>Move Stats</h3>
        <thead>
            <th>Name</th>
            <th>Type</th>
            <th>Category</th>
            <th>Power</th>
            <th>Accuracy</th>
            <th>PP</th>
            <th>Effect</th>
        </thead>
        <tbody>
        <?php 
            foreach ($posMoves as $key => $move) {
                echo "<tr class='move-result'>";
                echo "<td id='moveName$key'>" . $move->name . "</td>";
                echo "<td id='moveType$key'><img class='type-icon' src='" . FPATH . TPATH . strtolower($move->type) . ".png' alt='" . strtolower($move->type) . "'></td>";
                echo "<td id='moveCat$key'>" . $move->category . "</td>";
                echo "<td id='movePow$key'>" . $move->power . "</td>";
                echo "<td id='moveAcc$key'>" . ($move->accuracy == -1 ? "∞" : $move->accuracy) . "</td>";
                echo "<td id='movePP$key'>" . $move->pp . "</td>";
                echo "<td id='moveEff$key'>" . ($move->effect ?? "None") . "</td>";
                echo "</tr>";
            }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>