<?php
define("FPATH", "./dataFiles/gen1/");
// Generate 6 random IDs (between 1 and 151)
$randomq = [];
for ($i = 0; $i < 6; $i++) {
    $holder=rand(1, 151);
    while(in_array($holder,$randomq)){
            $holder=rand(1, 151);
    }
    $randomq[$i]= $holder;
}
?>
<!-- checking to see if IDs match to displayed pokemon -->
<script>
    const randomq = <?php echo json_encode($randomq); ?>;
    console.log("Random IDs:", randomq);
</script>

<?php
try {
    // Connect to database
    $pdo = new PDO("mysql:host=localhost;dbname=proj3", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Build IN clause
    $placeholders = implode(",", array_fill(0, count($randomq), "?"));
    $sql = "SELECT ID, NAME, TYPE1, TYPE2, TOTAL, HP, ATT, DEF, SPATT, SPDEF, SPEED
            FROM pokedex
            WHERE ID IN ($placeholders)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($randomq);

    // Fetch all matching rows into an array
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Build $pokemonList from $rows
    $pokemonList = [];

    foreach ($rows as $row) {
        //adjusts '7'->'007' '10'->'010' and leave 3digit numbers alone
        $id = str_pad($row['ID'], 3, "0", STR_PAD_LEFT);
        $name = $row['NAME'];
        $tempName = str_replace([' ', "'", ], ['_', '&#39;'], $name);       
        $img = FPATH . "{$id}{$tempName}.png";

        $types = [$row['TYPE1']];
        if (!empty($row['TYPE2']) && $row['TYPE2'] !== $row['TYPE1']) {
            $types[] = $row['TYPE2'];
        }

        $pokemonList[] = [
            "img" => $img,
            "name" => $name,
            "types" => $types,
            "stats" => [
                $row['HP'],
                $row['ATT'],
                $row['DEF'],
                $row['SPATT'],
                $row['SPDEF'],
                $row['SPEED'],
                $row['TOTAL']
            ]
        ];
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Choose your Pokémon</title>
    <link rel="stylesheet" href="select6.css">
</head>
<body>
    <h1>Choose one to add to your team</h1>

    <!-- Header row -->
    <div class="bor header-row">
        <span class="left1">Name Types</span>
        <span class="right1">HP Atk Def SpA SpD Spe BST</span>
    </div>

    <!-- Pokémon entries randomized now -->
    <?php foreach ($pokemonList as $pokemon): ?>
        <div class="entry"
        onmouseover="this.style.background='yellow';"
        onmouseout="this.style.background='white';"
        >
            <div class="left">
                <img src="<?= $pokemon['img'] ?>" alt="<?= $pokemon['name'] ?>">
                <span class="name"><?= $pokemon['name'] ?></span>
                <span class="types">
                    <?php foreach ($pokemon['types'] as $type): ?>
                        <img class="type-icon"
                             src="./pokeTypeIcons/<?= strtolower($type) ?>.png"
                             alt="<?= $type ?>">
                    <?php endforeach; ?>
                </span>
            </div>
            <div class="right">
                <?php foreach ($pokemon['stats'] as $stat): ?>
                    <span class="stat"><?= $stat ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <!-- be able to select the pokemon and then send it off somewhere -->
<?php foreach ($pokemonList as $pokemon): ?>
  <form action="select_confirm.php" method="POST">
    <input type="hidden" name="name" value="<?= $pokemon['name'] ?>">
    <input type="hidden" name="img" value="<?= $pokemon['img'] ?>">
    <input type="hidden" name="types" value="<?= implode(",", $pokemon['types']) ?>">
    <input type="hidden" name="stats" value="<?= implode(",", $pokemon['stats']) ?>">
  </form>
<?php endforeach; ?>

</body>
</html>
