<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="battle.css" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <title>Document</title>
</head>

<body>
    <h1 style="  text-align: center; ">Battle Screen (temp text)</h1>
    <div class="room">
        <div class="battle-arena">
            <div class="health-bars-row">
                <div class="pokemon-health-display pokemon1-health">
                    <p class="pokemon1-name">Squirtle</p>
                    <div class="w3-light-grey w3-round-large" style="width:100%;">
                        <div class="w3-container w3-green w3-round-xlarge" style="width:15%">15%</div>
                    </div>
                </div>

                <div class="pokemon-health-display pokemon2-health">
                    <p class="pokemon2-name">Charmander</p>
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

    <div id="battle-controls"></div>
    <div id="party"></div>


</body>

</html>