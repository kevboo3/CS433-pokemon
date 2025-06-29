<?php
session_start();
require "scripts/utils.php";
$team = $_SESSION["team"];
$curPkm = $team[0];
$allPosMoves = $_SESSION["posMoves"];
$posMoves = $allPosMoves[0];
var_dump($allPosMoves);
?>
<!DOCTYPE html>
<html lang="en">
<!-- our reference  -->
<!-- https://play.pokemonshowdown.com/teambuilder -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="select_moves.css">
    <title>Select your moves</title>
</head>
<body>
<div class="container">
    <h1>Select Your Pokemon's Moves!</h1>
    <div class="team">
        <span class="pokemon-buttons">
            <?php
                for($i = 0; $i < 2; $i++) {
                    for($j = 0; $j < 3; $j++){
                        echo "<span>" . $team[$j + $i * 3]["Name"] . "</span>\n";
                    }
                    for($j = 0; $j < 3; $j++){
                        echo "<button style='background-image: url(\"" . $team[$j + $i * 3]["Img"] . "\");' id='pkm". $j + $i * 3 ."'></button>";
                    }
                }
            ?>
        </span>
    </div>
    <div class="current-pokemon">
        <p style="text-align: center;">Selecting Moves For:</p>
        <img id="currPkmImg" class="active-pokemon" src=<?= $curPkm["Img"]?>>
        <div class="curr-name-type">
            <span>Name: 
                <span id="nameTxt"><?= $curPkm["Name"]?></span>
            </span>
            <span>Type: 
                <span id="typeTxt"><?= $curPkm["Attr"]["Type2"] ? $curPkm["Attr"]["Type1"] . " & " . $curPkm["Attr"]["Type2"] : $curPkm["Attr"]["Type1"]?></span>
            </span>
        </div>
        <div id="statsSpan">
            <table id="attrTable" class="attr-table">
            <thead>
                <tr>
                    <th>Total</th>
                    <th>Hp</th>
                    <th>Attack</th>
                    <th>Defense</th>
                    <th>Sp. Att</th>
                    <th>Sp. Def</th>
                    <th>Speed</th>
                </tr>
            </thead>
            <tbody >
                <tr>
                <?php 
                    foreach (array_slice($curPkm["Attr"], 2, 7) as $attr) {
                        echo "<td>$attr</td>";
                    }
                ?>
                </tr>
            <tbody>
            </table>
        </div>
        <div class="moves-selection">
            <label class="move-label">Selected Moves: </label>
            <div class="moves-input">
            <?php 
                foreach ($curPkm["Moves"] as $key => $curMove) {
                    echo "<input list='levelList$key' id='move$key' value='" . ($curMove["Name"] ?? "None") . "'>\n";
                    echo "<datalist id ='levelList$key'>\n";
                    foreach ($posMoves as $posMove) {
                        $valid = True;
                        foreach ($curPkm["Moves"] as $move) {
                            if ($move["Name"] == $posMove["Name"]
                                and $curMove["Name"] != $posMove["Name"]) {
                                $valid = False;
                                break;
                            }
                        }
                        if ($valid) {
                            echo "<option value='" . $posMove["Name"] . "'>\n";
                        }
                    }
                    foreach ($curPkm["Moves"] as $move) {
                        if ($move["Name"]
                            or !$curMove["Name"]) {
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
                echo "<td id='moveName$key'>" . $move["Name"] . "</td>";
                echo "<td id='moveType$key'>" . $move["Type"] . "</td>";
                echo "<td id='moveCat$key'>" . $move["Category"] . "</td>";
                echo "<td id='movePow$key'>" . $move["Power"] . "</td>";
                echo "<td id='moveAcc$key'>" . ($move["Accuracy"] == -1 ? "∞" : $move["Accuracy"]) . "</td>";
                echo "<td id='movePP$key'>" . $move["PP"] . "</td>";
                echo "<td id='moveEff$key'>" . ($move["Effect"] ?? "None") . "</td>";
                echo "</tr>";
            }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>