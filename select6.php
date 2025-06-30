<?php
require "scripts/setup.php";  // Helper Functions   !!!CHAGE TO "scripts/utils.php" AFTER MOVING setup()!!!
setup();                      // Setup DB           !!!MOVE TO PARENT PAGE WHEN READY!!!

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
conLog("Random IDs: " . implode(", ", $randIds));
$allMoves = [NULL, NULL, NULL, NULL, NULL, NULL];  // Array of possible moves for each pokemon

// Connect to database
$pdo = makePDO();

// Loads pokemon team data given a set of Pokedex Ids, 
for ($i = 0; $i < TEAMSIZE; $i++) {  // Iterates over team
    $stmt = $pdo->prepare("SELECT * FROM Pokedex WHERE Id = " . $team->pkm[$i]->id);
    $stmt->execute();
    $rslt = $stmt->fetchAll(PDO::FETCH_NUM)[0];
    $pkm = $team->pkm[$i];
    $pkm->name = $rslt[1];
    $pkm->types = array_slice($rslt, 2, 2);
    $pkm->attr = arr2attr(array_slice($rslt, 4));
    $pkm->hp = $pkm->attr->hp;
    $pkm->img = FPATH . IPATH . int2id($pkm->id) . str_replace(" ", "_", str_replace("'", "&#39", $pkm->name)) . ".png";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Choose your Pokémon</title>
    <link rel="stylesheet" href="styles/shared.css">
    <link rel="stylesheet" href="styles/select6.css">
</head>
<body>
    <h1>Choose Your Team</h1>
    <!-- Header row -->
    <div class="bor header-row">
        <span class="left">Name Types</span>
        <span class="right">
            <?php foreach ($team->pkm[0]->attr as $key => $stat): ?>
                <?php if ($key != "legendary"): ?>
                    <span class="stat <?= $key ?>-stat"><?= ucfirst($key) ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </span>
    </div>
    <!-- Pokémon entries randomized now -->
   <?php foreach ($team->pkm as $pkm): ?>
    <button type="submit" class="entry"
        onmouseover="this.style.background='yellow';"
        onmouseout="this.style.background='white';">
        <div class="left">
            <img src="<?= $pkm->img ?>" alt="<?= $pkm->name ?>">
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
            <?php foreach ($pkm->attr as $key => $stat): ?>
                <?php if ($key != "legendary"): ?>
                    <span class="stat <?= $key ?>-stat"><?= $stat ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </button>
<?php endforeach; ?>
<form method="POST" action="select_moves.php" >
    <input type="hidden" name="team" value='<?= serialize($team) ?>'>
</form>
</body>
</html>