<?php
session_start();
$team = $_SESSION["team"];
$curPkm = $team[0];
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
                    echo "<span>" . $team[$j + $i * 3]["Name"] . "</span>";
                }
                for($j = 0; $j < 3; $j++){
                    echo "<button style=\"background-image: url('" . $team[$j + $i * 3]["Img"] . "');\" id='pkm". $j + $i * 3 ."'></button>";
                }
            }
            ?>
        </span>
    </div>
    <div class="current-pokemon">
        <p style="text-align: center;">Selected Pokemon: </p>
        <img id="currPkmImg" class="active-pokemon" src=<?php echo $curPkm["Img"]?>>
        <div class="curr-name-type">
            <span>Name: 
                <span id="nameTxt"><?php echo $curPkm["Name"]?></span>
            </span>
            <span>Type: 
                <span id="typeTxt">
                    <?php echo $curPkm["Attr"]["Type2"] ? $curPkm["Attr"]["Type1"] . " & " . $curPkm["Attr"]["Type2"] : $curPkm["Attr"]["Type1"]?>
                </span>
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
                    <td><?php echo $curPkm["Attr"]["Total"]?></td>
                    <td><?php echo $curPkm["Attr"]["Hp"]?></td>
                    <td><?php echo $curPkm["Attr"]["Att"]?></td>
                    <td><?php echo $curPkm["Attr"]["Def"]?></td>
                    <td><?php echo $curPkm["Attr"]["SpAtt"]?></td>
                    <td><?php echo $curPkm["Attr"]["SpDef"]?></td>
                    <td><?php echo $curPkm["Attr"]["Speed"]?></td>
                </tr>
            <tbody>
            </table>
        </div>
        <div class="moves-selection">
            <label class="move-label">Selected Moves: </label>
            <div class="moves-buttons">
            <?php 
                foreach ($curPkm["Moves"] as $key => $move) {
                    echo "<button id='move$key'>" . $move["Name"] ?? "None" . "</button>";
                }
            ?>
            </div>
        </div>
    </div>
    <table class="move-list">
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
            foreach ($curPkm["Moves"] as $key => $move) {
                echo "<tr class='move-result'>";
                if ($move["Name"]) {
                    echo "<td id='moveName$key'>" . $move["Name"] . "</td>";
                    echo "<td id='moveType$key'>" . $move["Type"] . "</td>";
                    echo "<td id='moveCat$key'>" . $move["Category"] . "</td>";
                    echo "<td id='movePow$key'>" . $move["Power"] . "</td>";
                    echo "<td id='moveAcc$key'>" . $move["Accuracy"] . "</td>";
                    echo "<td id='movePP$key'>" . $move["PP"] . "</td>";
                    echo "<td id='moveEff$key'>" . ($move["Effect"] ?? "None") . "</td>";
                }
                else {
                    echo "<td id='moveName$key'>None</td>";
                    echo "<td id='moveType$key'>None</td>";
                    echo "<td id='moveCat$key'>None</td>";
                    echo "<td id='movePow$key'>0</td>";
                    echo "<td id='moveAcc$key'>0</td>";
                    echo "<td id='movePP$key'>0</td>";
                    echo "<td id='moveEff$key'>None</td>";
                }
                echo "</tr>";
            }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>