<?php
$pokemonList = [
    [
        "img" => "./proj3_images/1st Generation/007Squirtle.png",
        "name" => "Squirtle",
        "types" => ["Water"],
        "abilities" => ["Torrent", "Rain Dish"],
        "stats" => [44, 48, 65, 50, 64, 43, 314]
    ],
    [
        "img" => "./proj3_images/1st Generation/004Charmander.png",
        "name" => "Charmander",
        "types" => ["Fire"],
        "abilities" => ["Blaze", "Solar Power"],
        "stats" => [39, 52, 43, 60, 50, 65, 309]
    ],
    [
        "img" => "./proj3_images/1st Generation/001Bulbasaur.png",
        "name" => "Bulbasaur",
        "types" => ["Grass", "Poison"],
        "abilities" => ["Overgrow", "Chlorophyll"],
        "stats" => [45, 49, 49, 65, 65, 45, 318]
    ],
    [
        "img" => "./proj3_images/1st Generation/025Pikachu.png",
        "name" => "Pikachu",
        "types" => ["Electric"],
        "abilities" => ["Static", "Lightning Rod"],
        "stats" => [35, 55, 40, 50, 50, 90, 320]
    ],
    [
        "img" => "./proj3_images/1st Generation/143Snorlax.png",
        "name" => "Snorlax",
        "types" => ["Normal"],
        "abilities" => ["Immunity", "Thick Fat"],
        "stats" => [160, 110, 65, 65, 110, 30, 540]
    ],
    [
        "img" => "./proj3_images/1st Generation/150Mewtwo.png",
        "name" => "Mewtwo",
        "types" => ["Psychic"],
        "abilities" => ["Pressure", "Unnerve"],
        "stats" => [106, 110, 90, 154, 90, 130, 680]
    ]
];
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
        <span class="left1">Name Types Abilities</span>
        <span class="right1">HP Atk Def SpA SpD Spe BST</span>
    </div>

    <!-- Pokémon entries( not random static entries) -->
    <?php foreach ($pokemonList as $pokemon): ?>
        <div class="entry">
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
                <span class="abilities">
                    <?= implode(", ", $pokemon['abilities']) ?>
                </span>
            </div>
            <div class="right">
                <?php foreach ($pokemon['stats'] as $stat): ?>
                    <span class="stat"><?= $stat ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</body>
</html>
