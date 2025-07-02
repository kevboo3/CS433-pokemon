<?php
require "scripts/setup.php";
setup();                      // Setup DB

if (isset($_POST["volume"])) {
    $volume = $_POST["volume"];
    $muted = $_POST["muted"];
}
else {
    $volume = 50;
    $muted = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pokémon Battle</title>
    <link rel="stylesheet" href="styles/proj3.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./scripts/utils.js"></script>
    <script src="./scripts/proj3.js"></script>
</head>
<body>
<audio id="bgMusic" muted loop hidden>
    <source src="dataFiles/audio/songs/Pokémon_X &_Y_-_Title_Screen_(HQ).mp3">
</audio>
<audio id="btnClk" muted hidden>
    <source src="dataFiles/audio/sfx/Pokemon_(A_Button).mp3">
</audio>
<div id="background-blur"></div>
<div id="start-screen">
    <img src="dataFiles/images/International-Pokemon-logo.png" alt="Game Logo" id="logo">
    <h1>Pokémon Battle</h1>
    <div id="button-container">
        <button id="start-btn">Start Game</button>
        <button id="sound">Toggle Sound</button>
        <button id="up">Volume Up</button>
        <button id="down">Volume Down</button>
    </div>
</div>
<div id="volumeJSON" hidden><?= $volume ?></div>
<div id="mutedJSON" hidden><?= $muted ?></div>
</body>
</html>
