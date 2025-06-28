<!DOCTYPE html>
<html lang="en">
<!-- our reference  -->
<!-- https://play.pokemonshowdown.com/teambuilder -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./select_moves.css">
    <title>Select your moves</title>
</head>
<h2>Select your moves</h2>

<body>
    <div class="move-list">
        <div class="party">
            <button>pkmn</button>
            <button>pkmn</button>
            <button>pkmn</button>
            <button>pkmn</button>
            <button>pkmn</button>
            <button>pkmn</button>
        </div>
        <div class="party"></div>
        <div class="current-pokemon-info">

            <div class="current-pokemon">
                <img src="./proj3_images/1st Generation/007Squirtle.png" alt="Squirtle" class="active-pokemon">
                <p>current pokemon's name <small>L5</small></p>
                <p>Type: Water</p>
                <label for="" class="move-label">Moves</label>
                <input type="text" name="move1" class="move-textbox">
                <input type="text" name="move2" class="move-textbox">
                <input type="text" name="move3" class="move-textbox">
                <input type="text" name="move4" class="move-textbox">

            </div>
        </div>
        <table class="move-list
        ">
            <!-- hh -->

            <h3>Moves</h3>
            <!-- Name,Type,Category,Power,Accuracy,PP,Effect -->
            <th>Name</th>
            <th>Type</th>
            <th>Category</th>
            <th>Power</th>
            <th>Accuracy</th>
            <th>PP</th>
            <th>Effect</th>



            <tr class="move-result">
                <td class="namecol">Bubble</td>
                <td class="typecol">Water</td>
                <td class="categorycol">Special</td>
                <td class="accuracycol">40</td>
                <td class="ppcol">100</td>
                <td class="effectcol">30</td>
                <td class="descriptioncol">May lower opponent's Speed.</td>
            </tr>
            <tr class="move-result">
                <td class="namecol"> test2</td>
                <td class="typecol">Normal</td>
                <td class="categorycol">Physical</td>
                <td class="accuracycol">70</td>
                <td class="ppcol">800</td>
                <td class="effectcol">10</td>
                <td class="descriptioncol">May explode both players pokemon,</td>
            </tr>

        </table>


    </div>



</body>

</html>