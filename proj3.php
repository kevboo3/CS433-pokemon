<?php
require "scripts/setup.php";
setup();                        // Setup DB

if (isset($_POST["volume"])) {  // If Redirected from selected6.php
    $volume = $_POST["volume"];
    $muted = $_POST["muted"];
}
else {                          // If fresh instance
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
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">  <!-- Link to font -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./scripts/utils.js"></script>
    <script src="./scripts/proj3.js"></script>
</head>
<body>
<audio id="bgMusic" muted loop hidden>                                                     <!-- Background Music -->
    <source src="dataFiles/audio/songs/Pokémon_X &_Y_-_Title_Screen_(HQ).mp3">
</audio>
<audio id="btnClk" muted hidden>                                                           <!-- Button Click SFX -->
    <source src="dataFiles/audio/sfx/Pokemon_(A_Button).mp3">
</audio>
<div id="background-blur"></div>                                                           <!-- Background Blur -->
<div id="start-screen">
    <img src="dataFiles/images/International-Pokemon-logo.png" alt="Game Logo" id="logo">  <!-- Pokemon Logo -->
    <h1>Pokémon Battle</h1>                                                                <!-- Title -->
    <div id="button-container">                                                            <!-- Menu Buttons -->
        <button id="start-btn">Start Game</button>                                             <!-- Volume Down Button -->
        <button id="sound">Toggle Sound</button>                                               <!-- Toggle Sound Button -->
        <button id="up">Volume Up</button>                                                     <!-- Volume Up Button -->
        <button id="down">Volume Down</button>                                                 <!-- Volume Down Button -->
    </div>
</div>
<div id="volumeJSON" hidden><?= $volume ?></div>                                            <!-- Volume Data For JS -->
<div id="mutedJSON" hidden><?= $muted ?></div>                                              <!-- Mute Data For JS -->
</body>
</html>
