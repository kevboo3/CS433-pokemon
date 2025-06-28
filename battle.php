<!DOCTYPE html>
<html lang="en">
<!-- todo -->
<!-- rename pokemon 1 to user-pokemon and pokemon2 to enemy-pokemon  -->
<!-- add health bars to other party members  -->
<!-- add background image  -->
<!-- status effect messages  -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="battle.css" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <title>Battle Screen</title>
</head>

<body>
    <h1 style="  text-align: center; ">Battle Screen</h1>
    <div class="room">
        <div class="battle-arena">
            <!-- <img src="./proj3_images/arena/bg-city.jpg" class="arena" style="background-position: top;"> -->
            <div class="health-bars-row">
                <!-- pokemon1-health -->
                <div class="pokemon-health-display">
                    <p class="pokemon1-name">Squirtle <small>L5</small></p>
                    <div class="w3-light-grey w3-round-large" style="width:100%;">
                        <div class="w3-container w3-green w3-round-xlarge" style="width:20%">20%</div>
                    </div>
                </div>

                <!-- pokemon2-health -->
                <div class="pokemon-health-display pokemon2-health">
                    <p class="pokemon2-name">Charmander <small>L6</small></p>
                    <div class="w3-light-grey w3-round-large" style="width:100%;">
                        <div class="w3-container w3-green w3-round-xlarge" style="width:80%">80%</div>
                    </div>
                </div>
            </div>

            <div class="pokemon-images-row">
                <img src="./proj3_images/1st Generation/007Squirtle.png" alt="Squirtle" class="player1-active-pokemon">
                <img src="./proj3_images/1st Generation/004Charmander.png" alt="Charmander" class="player2-active-pokemon">
            </div>
        </div>




    </div>


    <!-- <img src="./proj3_images/1st Generation/004Charmander.png" alt="" id="player2-active-pokemon"> -->
    </div>

    <div id="battle-controls" style="text-align: center;" class="battle-controls">
        <button class="pokemon1-move">Move 1 <br><small>Normal</small><br></button>
        <button class="pokemon1-move">Move 2<br><small>Water</small><br></button>
        <button class="pokemon1-move">Move 3<br><small>Rock</small><br></button>
        <button class="pokemon1-move">Move 4<br><small>Normal</small><br></button>
    </div>
    <div id="party" class="party" style="text-align: center;">
        <p>Switch</p>
        <button class=" pokemon1">
            <img src="./proj3_images/1st Generation/007Squirtle.png" class="party-icon">
            pkmn1
        </button>
        <button class="pokemon2">
            <img src="./proj3_images/1st Generation/010Caterpie.png" class="party-icon">
            pkmn2</button>
        <button class="pokemon3">
            <img src="./proj3_images/1st Generation/014Kakuna.png" class="party-icon">

            pkmn3</button>
        <button class="pokemon4">
            <img src="./proj3_images/1st Generation/017Pidgeotto.png" class="party-icon">

            pkmn4</button>
        <button class="pokemon5">
            <img src="./proj3_images/1st Generation/012Butterfree.png" class="party-icon">

            pkmn5</button>
        <button class="pokemon6">
            <img src="./proj3_images/1st Generation/025Pikachu.png" class="party-icon">

            pkmn6</button>
    </div>


</body>

</html>