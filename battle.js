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
function printTeam(session) {
  for (i = 0; i < 6; i++) {
    console.log(" pokemon " + i + " name: " + " " + session.team[i].Name);

    console.log(
      " pokemon " +
        i +
        " attributes: " +
        "attack: " +
        session.team[i].Attr.Att +
        " hp: " +
        session.team[i].Attr.Hp +
        " def: " +
        session.team[i].Attr.Def +
        " spAtt: " +
        session.team[i].Attr.SpAtt +
        " spDef: " +
        session.team[i].Attr.SpDef +
        " speed: " +
        session.team[i].Attr.Speed +
        " legendary: " +
        session.team[i].Attr.Legendary +
        " Type1: " +
        session.team[i].Attr.Type1 +
        " Type2: " +
        session.team[i].Attr.Type2 +
        " Total: " +
        session.team[i].Attr.Total
    );
    console.log(" pokemon " + i + " hp: " + " " + session.team.Hp);
    console.log(" pokemon " + i + " status: " + " " + session.team.Status);
    console.log(" ==pokemon " + i + " moves== ");

    //[!] consider none moves
    for (k = 0; k < 4; k++) {
      console.log(
        " pokemon " +
          i +
          " move " +
          k +
          " name: " +
          session.team[i].Moves[k].Name +
          " type: " +
          session.team[i].Moves[k].Type +
          " category: " +
          session.team[i].Moves[k].Category +
          " power: " +
          session.team[i].Moves[k].Power +
          " accuracy: " +
          session.team[i].Moves[k].Accuracy +
          " pp: " +
          session.team[i].Moves[k].PP +
          " effect: " +
          session.team[i].Moves[k].Effect
      );
    }
    console.log("pokemon " + i + " img: " + session.team[i].Img);
    console.log("=============================== ");
  }
}

function printEnemyTeam(session) {
  for (i = 0; i < 6; i++) {
    console.log(
      " pokemon " + i + " name: " + " " + session.enemyTeam.pkm[i].name
    );
    console.log(
      " pokemon " + i + " type 1: " + session.enemyTeam.pkm[i].types[0]
    );
    console.log(
      " pokemon " + i + " type 2: " + session.enemyTeam.pkm[i].types[1]
    );
    console.log(
      " pokemon " +
        i +
        " attributes: " +
        "total: " +
        session.enemyTeam.pkm[i].attr.total +
        " hp: " +
        session.enemyTeam.pkm[i].attr.hp +
        " atk: " +
        session.enemyTeam.pkm[i].attr.atk +
        " def: " +
        session.enemyTeam.pkm[i].attr.def +
        " spAtk: " +
        session.enemyTeam.pkm[i].attr.spAtk +
        " spDef: " +
        session.enemyTeam.pkm[i].attr.spDef +
        " speed: " +
        session.enemyTeam.pkm[i].attr.speed +
        " legendary: " +
        session.enemyTeam.pkm[i].attr.legendary
    );
    console.log(" pokemon " + i + " hp: " + " " + session.enemyTeam.pkm[i].hp);
    console.log(
      " pokemon " + i + " status: " + " " + session.enemyTeam.pkm[i].status
    );
    console.log(" ==pokemon " + i + " moves== ");

    //[!] consider none moves
    for (k = 0; k < 4; k++) {
      console.log(
        " pokemon " +
          i +
          " move " +
          k +
          " name: " +
          session.enemyTeam.pkm[i].moves[k].name +
          " type: " +
          session.enemyTeam.pkm[i].moves[k].type +
          " category: " +
          session.enemyTeam.pkm[i].moves[k].category +
          " power: " +
          session.enemyTeam.pkm[i].moves[k].power +
          " accuracy: " +
          session.enemyTeam.pkm[i].moves[k].accuracy +
          " pp: " +
          session.enemyTeam.pkm[i].moves[k].pp +
          " effect: " +
          session.enemyTeam.pkm[i].moves[k].effect
      );
    }
    console.log("pokemon " + i + " img: " + session.enemyTeam.pkm[i].img);
    console.log("=============================== ");
  }
}

async function initBattle() {
  // const json = "./encode_session.php";

  team = JSON.parse(document.getElementById("teamJSON")).innerHTML;
  console.log("team");

  //check if the json file can be reached
  // try {
  //   // fetch is used for making http request and since are ssession is at a link we an access it
  //   const response = await fetch(json);

  //   if (!response.ok) {
  //     throw new Error(`Response status: ${response.status}`);
  //   }
  //   //parse encoded json
  //   const session = await response.json();
  //   // printTeam(session);
  //   // printEnemyTeam(session);
  //   console.log(session);
  //   // document.getElementById("pokemon1-name").innerHTML = "";
  // } catch (error) {
  //   console.log("Error:", error);
  // }
}

initBattle();

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
};

//battleEnd

/*
 -if the user won provide assign them thier earn exp
 prompt them if they want to teach thier pokemon a new move/ forget one
 -then generate a new battle or generate the item shop ()
*/
//function battleEnd(){}
