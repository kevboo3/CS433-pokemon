<?php
require "scripts/utils.php";
$team = json_decode($_POST["team"]);
$curPkm = $team->pkm[0];
$pdo = makePDO();

// Gets Move Data
for ($i = 0; $i < TEAMSIZE; $i++) {  // Iterates over team
    // Generates list of all moves each pokemon can learn by level up
    $stmt = $pdo->prepare("SELECT * FROM Learn WHERE Id = " . $team->pkm[$i]->id);
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
        if(!$rslt){
            var_dump($stmt);
        }
        $allMoves[$i][$j] = arr2move($rslt);
    }
    // Assigns default moves to team
    for ($j = 0; $j < 4; $j++) {  // Iterates over team moves
        if ($j < count($allMoves[$i])) {
            $team->pkm[$i]->moves[$j] = $allMoves[$i][$j];
        }
        else{
            $team->pkm[$i]->moves[$j] = new Move();
        }
    }
}
$posMoves = $allMoves[0];
$volume = $_POST["volume"];
$muted = $_POST["muted"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/shared.css">
    <link rel="stylesheet" href="styles/select_moves.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./scripts/utils.js"></script>
    <script src="./scripts/select_moves.js"></script>
    <title>Select your moves</title>
</head>
<body>
<audio id="bgMusic" muted loop hidden>
    <source src="dataFiles/audio/songs/Pokemon_HeartGold_&_SoulSilver_OST.mp3">
</audio>
<audio id="btnClk" muted hidden>
    <source src="dataFiles/audio/sfx/Pokemon_(A_Button).mp3">
</audio>
<div class="center">           <!-- Center block of content -->
    <div class="container">        <!-- Pokedex Grey Border -->
        <h1>Select Your Pokemon's Moves!</h1>
        <div class="center">       <!-- Center block of content -->
            <div class="screen">       <!-- Pokedex Screen -->
                <div class="top-row">
                    <div class="team">
                        <div>Your Team:</div>
                        <span class="pokemon-buttons">
                            <?php for($i = 0; $i < 2; $i++): ?> 
                                <?php for($j = 0; $j < 3; $j++): ?>
                                    <span><?= $team->pkm[$j + $i * 3]->name ?></span>
                                <?php endfor; ?>
                                <?php for($j = 0; $j < 3; $j++): ?>
                                    <button class="<?= strtolower($team->pkm[$j + $i * 3]->types[0]) ?>-type" style='background-image: url("<?= str_replace("'", "&#39;", $team->pkm[$j + $i * 3]->img) ?>");' id="pkm<?= $j + $i * 3 ?>"></button>
                                <?php endfor; ?>
                            <?php endfor; ?>
                        </span>
                        <div class="moves-selection">
                            <span>Selected Moves:</span>
                            <div id="movesInput" class="moves-input">
                                <?php foreach ($curPkm->moves as $i => $curMove): ?>
                                    <select id='move<?= $i ?>'>
                                    <?php for ($j = 0; $j < count($posMoves); $j++):
                                        $valid = True;
                                        foreach ($curPkm->moves as $move) {
                                            if ($move->name == $posMoves[$j]->name
                                                and $curMove->name != $posMoves[$j]->name) {
                                                $valid = False;
                                                break;
                                            }
                                        }
                                        if ($valid): ?>
                                            <option <?= $j - $i === 0 ? "selected" : "" ?>><?= $posMoves[$j]->name ?></option>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <?php foreach ($curPkm->moves as $move):
                                        if ($move->name
                                            or !$curMove->name): ?>
                                                <option>None</option>
                                        <?php break; endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="current-pokemon">
                        <span>Selceted Pokemon:</span>
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
                                    <?php if ($key != "legendary"): ?>
                                        <th class="<?= $key ?>-stat"><?= ucfirst($key) ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </thead>
                                <tbody>
                                    <tr id ="attrTableBR">
                                    <?php foreach (array_slice((array) $curPkm->attr, 0, -1) as $key => $stat): ?>
                                        <td class="<?= $key ?>-stat"><?= $stat ?></td>
                                    <?php endforeach; ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="moves-data">
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
                        <tbody id="movesTableBody">
                        <?php foreach ($posMoves as $key => $move): ?>
                            <tr class='move-result'>
                                <td><?= $move->name ?></td>
                                <td>
                                    <img class="type-icon" src="<?= FPATH . TPATH . strtolower($move->type) ?>.png" alt="<?= strtolower($move->type) ?>">
                                </td>
                                <td><?= $move->category ?></td>
                                <td><?= $move->power ?></td>
                                <td><?= $move->accuracy == -1 ? "∞" : $move->accuracy ?></td>
                                <td><?= $move->pp ?></td>
                                <td><?= $move->effect ?? "None" ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="center"> 
            <div class="menu">
                <button type="button" class="back" id="back">Back</button>
                <button id="sound">Toggle Sound</button>
                <button id="up">Volume Up</button>
                <button id="down">Volume Down</button>
                <button type="button" class="confirm" id="confirm">Confirm Moves</button>
            </div>
        </div> 
    </div>
</div>
<div id="teamJSON" hidden><?= json_encode($team) ?></div>
<div id ="movesJSON" hidden><?= json_encode($allMoves) ?></div>
<div id="volumeJSON" hidden><?= $volume ?></div>
<div id="mutedJSON" hidden><?= $muted ?></div>
</body>
</html>