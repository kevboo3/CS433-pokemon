<?php
require "scripts/utils.php";

if (array_key_exists("team", $_POST)){
    $team = json_decode($_POST["team"]);
}
else {
    // Generate 6 random IDs between 1 and 151 excluding 132 (Ditto has no moves)
    $randIds = range(1, 151);
    unset($randIds[131]);
    shuffle($randIds);
    $randIds = array_slice($randIds, 0, TEAMSIZE);

    // Populate team
    $team = new Team(); 
    for ($i = 0; $i < TEAMSIZE; $i++) {     
        $team->pkm[$i] = new Pokemon();
        $team->pkm[$i]->id = $randIds[$i];  // Assign random ids to pokemon
    }

    // Loads pokemon team data given a set of Pokedex Ids, 
    foreach ($randIds as $key => $id) {  // Iterates over team
        $team->pkm[$key] = makePokemon($team->pkm[$key]->id);
    }
}

$volume = $_POST["volume"];
$muted = $_POST["muted"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Choose your Pokémon</title>
    <link rel="stylesheet" href="styles/shared.css">
    <link rel="stylesheet" href="styles/select6.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./scripts/utils.js"></script>
    <script src="./scripts/select6.js"></script>
</head>
<body>
<audio id="bgMusic" muted loop hidden>
    <source src="dataFiles/audio/songs/Route_8-XY.mp3">
</audio>
<audio id="btnClk" muted hidden>
    <source src="dataFiles/audio/songs/Button.mp3">
</audio>
<div class="center">           <!-- Center block of content -->
<div class="container">        <!-- Pokedex Grey Border -->
    <h1>Choose Your Team</h1> 
    <div class="center">       <!-- Center block of content -->
    <div class="screen">       <!-- Pokedex Screen -->
    <!-- Screen Content -->
            <div class="bor header-row">
                <span class="left">Pokemon Types</span>
                <span class="right">
                    <?php foreach ($team->pkm[0]->attr as $key => $stat): ?>
                        <?php if ($key != "legendary"): ?>
                            <span class="stat <?= $key ?>-stat"><?= ucfirst($key) ?></span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </span>
            </div>
            <?php foreach ($team->pkm as $i => $pkm): ?>
                <button type="button" class="entry" id="pkm<?= $i ?>">
                    <div class="left">
                        <img src="<?= $pkm->img ?>" name="<?= $pkm->id ?>">
                        <span class="name"><?= $pkm->name ?></span>
                        <span class="types">
                            <?php foreach ($pkm->types as $type): ?>
                                <?php if ($type): ?>
                                    <img class="type-icon"
                                            src="<?= FPATH . TPATH . strtolower($type) ?>.png"
                                            alt="<?= $type ?>">
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </span>
                    </div>
                    <div class="right">
                        <?php foreach ($pkm->attr as $j => $stat): ?>
                            <?php if ($j != "legendary"): ?>
                                <span class="stat <?= $j ?>-stat" name="stat<?= $j ?>"><?= $stat ?></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </button>
            <?php endforeach; ?>
    </div>
    </div>
    <div class="center"> 
    <div class="menu">
        <button type="button" class="back" id="back">Back</button>
        <button id="sound">Toggle Sound</button>
        <button id="up">Volume Up</button>
        <button id="down">Volume Down</button>
        <button type="button" class="reroll" id="reroll">Reroll Pokemon</button>
        <button type="button" class="confirm" id="confirm">Confirm Team</button>
    </div>
    </div> 
</div>
</div>
<div id="teamJSON" hidden><?= json_encode($team) ?></div>
<div id="volumeJSON" hidden><?= $volume ?></div>
<div id="mutedJSON" hidden><?= $muted ?></div>
</body>
</html>