isPlayer1Turn = true;
isPlayerLeaderDead = false;
isPlayer1TeamDead = false;
//possible game states to
//init battle
/*

-recieve the users 6 pokemon and moves
-generate the random pokemon (1 or more to fight)

- store them in a local arr or reference the $session arr
*/

function initBattle() {

  let newIds = [];
  // $.get("./scripts/get_pokemon.php", { "ids": newIds }, setPokemon, "json");


  team = JSON.parse(document.getElementById("teamJSON").innerHTML);
  enemyTeam = JSON.parse(document.getElementById("enemyTeamJSON").innerHTML);
  console.log(team);
  console.log(enemyTeam);


  document.getElementById("pokemon1-name").innerHTML = team.pkm[0].name;
  document.getElementById("pokemon1-hp").innerHTML = team.pkm[0].hp;
  document.getElementById("pokemon1-hp").style.width = ((team.pkm[0].hp/team.pkm[0].hp)*100)+"%";
  document.getElementById("player1-active-pokemon").src = team.pkm[0].img;
  document.getElementById("move1").innerHTML = team.pkm[0].moves[0].name+"<br>"+team.pkm[0].moves[0].type;
  document.getElementById("move2").innerHTML = team.pkm[0].moves[1].name+"<br>"+team.pkm[0].moves[1].type;
  document.getElementById("move3").innerHTML = team.pkm[0].moves[2].name+"<br>"+team.pkm[0].moves[2].type;
  document.getElementById("move4").innerHTML = team.pkm[0].moves[3].name+"<br>"+team.pkm[0].moves[3].type;

  // [!] fix icons not showing up
  document.getElementById("pokemon1-img").src = team.pkm[0].img;
  document.getElementById("pokemon1-img-name").innerHTML = team.pkm[0].name;
  document.getElementById("pokemon2-img").src = team.pkm[1].img;
  document.getElementById("pokemon2-img-name").innerHTML = team.pkm[1].name;
  document.getElementById("pokemon3-img").src = team.pkm[2].img;
  document.getElementById("pokemon3-img-name").innerHTML = team.pkm[2].name;
  document.getElementById("pokemon4-img").src = team.pkm[3].img;
  document.getElementById("pokemon4-img-name").innerHTML = team.pkm[3].name;
  document.getElementById("pokemon5-img").src = team.pkm[4].img;
  document.getElementById("pokemon5-img-name").innerHTML = team.pkm[4].name;
  document.getElementById("pokemon6-img").src = team.pkm[5].img;
  document.getElementById("pokemon6-img-name").innerHTML = team.pkm[5].name;

  //enemy pokemon
  document.getElementById("enemy-pokemon-name").innerHTML = enemyTeam.pkm[0].name;
  document.getElementById("enemy-pokemon-hp").innerHTML = enemyTeam.pkm[0].hp;
  document.getElementById("enemy-pokemon-hp").style.width = ((enemyTeam.pkm[0].hp/enemyTeam.pkm[0].hp)*100)+"%";
  document.getElementById("player2-active-pokemon").src = enemyTeam.pkm[0].img;
 
  
  // query the db for a rand pokemon and give it 4 random moves
  
}

//battle

/*
todo
 -switch between the user turn to pick moves and the cpus turn ( prevent the user from clicking any buttons
 when its the cpius turn)
    -who goes first is based on what pokemon has a higher speed stay

 -check if  the users pokemon is dead (can only switch), if all pokemon are dead game over, if the cpus lead pokemon is dead or if
   are all the cpus pokemon are dead

 -perform the move selected by the user by querying the typebonus move properties.  then use said data to attack the user (modify heatl bar and status effect message if needed)
 -continue unstill either team in dead
 -keep tack of what pokemon landed kills to keep track of potential exp if the user wins
 

 [!] idk how many balls to give maybe they'll start of with 3
 -catching will capture the enemy pokemon and if succesful will grant no exp and will prompt the user to replace 
 a team member or to release to pokemon to the wild
 */
function Battle() {
  if (
    isPlayer1Turn == true &&
    isPlayerLeaderDead == false &&
    isPlayer1TeamDead == false
  ) {
    //let the user pick an attack or switch

    // listen for a single button press

    const move1 = document.getElementById("move1");
    move1.addEventListener("click", function () {
    console.log("move1 was selected");

      //check if move pp is not 0

      // query the db to see the type of move it is phys or state

      //store the damage and typing

      //query the opponents type to finalize damage calculation

      //attempt inflict damage by editing the oppenent hp value in $session

      // query move description to see if it will inclict a status effect on the opponent

      // decrease PP by 1

      //
    });
  }
}

window.onload = function () {
  Battle();
  initBattle();
};

//battleEnd

/*
 -if the user won provide assign them thier earn exp
 prompt them if they want to teach thier pokemon a new move/ forget one
 -then generate a new battle or generate the item shop ()
*/
//function battleEnd(){}
