<?php
// Start the session
$team = json_decode($_POST["team"]);
?>


<!-- todo: -->
<!-- rename pokemon 1 to user-pokemon and pokemon2 to enemy-pokemon  -->
<!-- add health bars to other party members  -->
<!-- add background image  -->
<!-- status effect messages  -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="battle.css" />
    <!-- <script src="./battle.js"></script> -->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <title>Battle Screen</title>
</head>

<body>
    <!-- generate session data -->

    <h1 style="  text-align: center; ">Battle Screen</h1>
    <div class="room">
        <div class="battle-arena">
            <!-- <img src="./proj3_images/arena/bg-city.jpg" class="arena" style="background-position: top;"> -->
            <div class="health-bars-row">
                <!-- pokemon1-health -->
                <div class="pokemon-health-display">
                    <p class="pokemon1-name" id="pokemon1-name"></p>
                    <div class="w3-light-grey w3-round-large" style="width:100%;" >
                        <div class="w3-container w3-green w3-round-xlarge" style="width:100%" id="pokemon1-hp">100%</div>
                    </div>
                </div>

                <!-- pokemon2-health -->
                <div class="pokemon-health-display pokemon2-health">
                    <p class="pokemon2-name">Charmander <small>L6</small></p>
                    <div class="w3-light-grey w3-round-large" style="width:100%;">
                        <div class="w3-container w3-green w3-round-xlarge" style="width:100%">100%</div>
                    </div>
                </div>
            </div>

            <div class="pokemon-images-row">
                <img src="./dataFiles/gen1/007Squirtle.png" alt="Squirtle" class="player1-active-pokemon" id="player1-active-pokemon">
                <img src="./dataFiles/gen1/004Charmander.png" alt="Charmander" class="player2-active-pokemon" id="player2-active-pokemon">
            </div>
        </div>




    </div>


    <!-- <img src="./dataFiles/gen1/004Charmander.png" alt="" id="player2-active-pokemon"> -->
    </div>

    <div id="battle-controls" style="text-align: center;" class="battle-controls">
        <button class="pokemon1-move" id="move1">Move 1 <br> <br>
    </button>
        <button class="pokemon1-move" id="move2">Move 2</button>
        <button class="pokemon1-move" id="move3">Move 3</button>
        <button class="pokemon1-move" id="move4">Move 4</button>
    </div>
    <div id="party" class="party" style="text-align: center;">
        <p>Switch</p>
        <button class=" pokemon1" id="pokemon1-img-name">
            <img src="./dataFiles/gen1/007Squirtle.png" class="party-icon" id="pokemon1-img">
            pkmn1
        </button>
        <button class="pokemon2" id="pokemon2-img-name">
            <img src="./dataFiles/gen1/010Caterpie.png" class="party-icon" id="pokemon2-img">
            pkmn2</button>
        <button class="pokemon3" id="pokemon3-img-name">
            <img src="./dataFiles/gen1/014Kakuna.png" class="party-icon" id="pokemon3-img">

            pkmn3</button>
        <button class="pokemon4" id="pokemon4-img-name">
            <img src="./dataFiles/gen1/017Pidgeotto.png" class="party-icon" id="pokemon4-img">

            pkmn4</button>
        <button class="pokemon5" id="pokemon5-img-name">
        <img src="./dataFiles/gen1/012Butterfree.png" class="party-icon" id="pokemon5-img">

            pkmn5</button>
        <button class="pokemon6" id="pokemon6-img-name">
        <img src="./dataFiles/gen1/025Pikachu.png" class="party-icon" id="pokemon6-img">

            pkmn6</button>
    </div>

    <script src="./battle.js"></script>
    <div id="teamJSON" hidden><?= json_encode($team) ?></div>

</body>

</html>