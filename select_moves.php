<!-- 
* File: select6.js 
 * Author: Justin C & Tobias H 
 * Description: Page where user selects their pokemon team
-->
<?php
require "scripts/utils.php";
$team = json_decode($_POST["team"]);
$curPkm = $team->pkm[0];
$pdo = makePDO();

// Generates List Of All Moves Each Pokemon Can Learn
for ($i = 0; $i < TEAMSIZE; $i++) {                  // Iterates over team
    // Quesries Database
    $stmt = $pdo->prepare("SELECT * FROM Learn WHERE Id = " . $team->pkm[$i]->id);
    $stmt->execute();
    $rslt = $stmt->fetch(PDO::FETCH_NUM);

    // Loads Moves into Array
    $allMoves[$i] = [];
    foreach (array_slice($rslt, 2) as $col) {        // Explode CSV to list
        $allMoves[$i] += explode(",", $col);
    }
    for ($j = 0; $j < count($allMoves[$i]); $j++) {  // Iterates over all possible moves
        $stmt = $pdo->prepare("SELECT * FROM Moves WHERE Name = \"" . $allMoves[$i][$j] . "\"");
        $stmt->execute();
        $rslt = $stmt->fetch(PDO::FETCH_NUM);
        $allMoves[$i][$j] = arr2move($rslt);         // Create Move object
    }
    // Assigns default moves to team
    for ($j = 0; $j < 4; $j++) {                     // Iterates over team moves
        if ($j < count($allMoves[$i])) {             // Assign first level up moves by default
            $team->pkm[$i]->moves[$j] = $allMoves[$i][$j];
        }
        else{                                        // Assign empty Move if pokemon has less than 4 moves
            $team->pkm[$i]->moves[$j] = new Move();
        }
    }
}
$volume = $_POST["volume"]; // Get volume setting
$muted = $_POST["muted"];   // Get mute setting
$posMoves = $allMoves[0];   // List of move for default pokemon
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/shared.css">
    <link rel="stylesheet" href="styles/select_moves.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">  <!-- Link to font -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./scripts/utils.js"></script>
    <script src="./scripts/select_moves.js"></script>
    <title>Select Your Moves</title>
</head>
<body>
<audio id="bgMusic" muted loop hidden>                    <!-- Background Music -->
    <source src="dataFiles/audio/songs/Pokemon_HeartGold_&_SoulSilver_OST.mp3">
</audio>
<audio id="btnClk" muted hidden>                          <!-- Button Click SFX -->
    <source src="dataFiles/audio/sfx/Pokemon_(A_Button).mp3">
</audio>
<div class="center">                                                        <!-- Center block of content -->
    <div class="container">                                                     <!-- Pokedex Grey Border -->
        <h1>Select Your Pokemon's Moves!</h1>                                       <!-- Page Title -->
        <div class="center">                                                            <!-- Center block of content -->
            <div class="screen">                                                        <!-- Pokedex Screen -->
                <div class="top-row">                                                       <!-- Top Row -->
                    <div class="team">                                                          <!-- Team Block -->
                        <div>Your Team:</div>                                                       <!-- Team Block Lable -->
                        <span class="pokemon-buttons">                                                  <!-- Team Block Buttons -->
                            <?php for($i = 0; $i < 2; $i++): ?>                                             <!-- Team Block Button -->
                                <?php for($j = 0; $j < 3; $j++): ?>
                                    <span <?= $i == 0 ? "" : "class='middle-text'" ?>><?= $team->pkm[$j + $i * 3]->name ?></span>
                                <?php endfor; ?>
                                <?php for($j = 0; $j < 3; $j++): ?>
                                    <button class="<?= strtolower($team->pkm[$j + $i * 3]->types[0]) ?>-type" style='background-image: url("<?= str_replace("'", "&#39;", $team->pkm[$j + $i * 3]->img) ?>");' id="pkm<?= $j + $i * 3 ?>"></button>
                                <?php endfor; ?>
                            <?php endfor; ?>
                        </span>
                        <div class="moves-selection">                                           <!-- Moves Selection -->
                            <span>Selected Moves:</span>                                            <!-- Moves Selection Title -->
                            <div id="movesInput" class="moves-input">                               <!-- Moves Selection Input -->
                                <?php foreach ($curPkm->moves as $i => $curMove): ?>                    <!-- Moves Selection Input Dropbox -->
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
                                        if ($valid): ?>                                                     <!-- Moves Selection Input Dropbox Option-->
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
                    <div class="current-pokemon">                                               <!-- Current Pokemon Display --> 
                        <span>Selceted Pokemon:</span>                                              <!-- Current Pokemon Display Title --> 
                        <img id="currPkmImg" class="active-pokemon <?= strtolower($curPkm->types[0])?>-type" src="<?= $curPkm->img ?>">  <!-- Current Pokemon Display Image --> 
                        <div class="curr-name-type">                                                <!-- Current Pokemon Display Name and Types --> 
                            <span id="nameTxt"><?= $curPkm->name ?></span>                              <!-- Current Pokemon Display Name -->
                            <span id="typeIcons">                                                       <!-- Current Pokemon Display Types -->
                                <img class="type-icon" src="<?= FPATH . TPATH . strtolower($curPkm->types[0])?>.png" alt='<?= $curPkm->types[0] ?>'>
                                <?php if ($curPkm->types[1]): ?>
                                    <img class="type-icon" src="<?= FPATH . TPATH . strtolower($curPkm->types[1])?>.png" alt='<?= $curPkm->types[1] ?>'>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div id="statsSpan">                                                        <!-- Current Pokemon Display Stats --> 
                            <table id="attrTable" class="attr-table">                                   <!-- Current Pokemon Display Stats Table --> 
                                <thead>
                                <?php foreach ($curPkm->attr as $key => $stat): ?>                          <!-- Current Pokemon Display Stats Table Header -->
                                    <?php if ($key != "legendary"): ?>
                                        <th class="<?= $key ?>-stat"><?= ucfirst($key) ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </thead>
                                <tbody>
                                    <tr id ="attrTableBR">                                              <!-- Current Pokemon Display Stats Row -->
                                    <?php foreach (array_slice((array) $curPkm->attr, 0, -1) as $key => $stat): ?>
                                        <td class="<?= $key ?>-stat"><?= $stat ?></td>
                                    <?php endforeach; ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="moves-data">                                                    <!-- Current Pokemon Move Data -->                     
                    <table class="moves-table">                                                 <!-- Current Pokemon Move Data Table -->
                        <h3>Move Stats</h3>                                                         <!-- Current Pokemon Move Data Table Title -->
                        <thead>                                                                         <!-- Current Pokemon Move Data Table Header -->
                            <th>Name</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Power</th>
                            <th>Accuracy</th>
                            <th>PP</th>
                            <th>Effect</th>
                        </thead>
                        <tbody id="movesTableBody">                                                 <!-- Current Pokemon Move Data Table -->
                            <?php foreach ($posMoves as $key => $move): ?>            
                                <tr class='move-result'>                                                 <!-- Current Pokemon Move Data Table Entries -->
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
        <div class="center">                                                                              <!-- Center block of content -->               
            <div id="menu" class="menu">                                                                       <!-- Menu Buttons -->
                <button type="button" class="back" id="back">Back</button>                                     <!-- Back Button -->
                <button id="sound">Toggle Sound</button>                                                       <!-- Toggle Sound Button -->
                <button id="up">Volume Up</button>                                                             <!-- Volume Up Button -->
                <button id="down">Volume Down</button>                                                         <!-- Volume Down Button -->
                <button type="button" class="confirm" id="confirm">Confirm Moves</button>                      <!-- Confirm Button -->
            </div>
        </div> 
    </div>
</div>
<div id="teamJSON" hidden><?= json_encode($team) ?></div>                                                 <!-- Team Data For JS -->
<div id ="movesJSON" hidden><?= json_encode($allMoves) ?></div>                                           <!-- Moves Data For JS --> 
<div id="volumeJSON" hidden><?= $volume ?></div>                                                          <!-- Volume Data For JS -->
<div id="mutedJSON" hidden><?= $muted ?></div>                                                            <!-- Mute Data For JS -->
</body>
</html>