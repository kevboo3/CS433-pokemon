
//possible game states 
//init battle
let isPlayer1Turn = true;
let isPlayerLeaderDead = false;
let isPlayer1TeamDead = false;
let isEnemyDead = false;

currPkm = 0;

//a sample do turn function
// const do_turn = (player, enemy) => {
//     activate_effects(player.active_pokemon);
//     use_move(player.active_pokemon, enemy.active_pokemon, player_select_move());
// }

//finds the move object in moves_data.csv based on the moves name
async function get_move(target_move) {
    const target = `http://localhost/proj3/CS433-pokemon/dataFiles/moves_data.csv`;

    const res = await fetch(target, {
        method: 'get',
        headers: {
            'content-type': 'text/csv;charset=UTF-8',
        }
    });

    const move_list = await res.text();
    var found_move = await move_list.split("\n");
    found_move = await found_move.map(item => {
        return (item.split(","))
    });

    var found_move = await found_move.find(move => {
        if (move[0] == target_move) {
            return (move);
        }
    })
    console.log("Found move: ", found_move)
    const new_move = { name: found_move[0], type: found_move[1], category: found_move[2], power: found_move[3], accuracy: found_move[4], pp: found_move[5], effect: found_move[6], effect1: found_move[7], effect2: found_move[8] }

    return (await new_move);
}

//does damage effects to pokemon
// const activate_effects = (pokemon) => {
//     if (pokemon.burn) {
//         pokemon.health = Math.max(0, pokemon.health - pokemon.health / 16);
//     }
//     if (pokemon.charge) {
//         pokemon.health = Math.max(0, pokemon.health - pokemon.charge);
//         pokemon.charge = 0;
//     }

    if (pokemon.DOT > 0) {
        pokemon.health = Math.max(0, pokemon.health - pokemon.DOT_damage);
        pokemon.DOT -= 1;
    }
    if (pokemon.poison) {
        pokemon.health = Math.max(1, pokemon.health - pokemon.health / 16);
    }
}

//attempts to use a move
const use_move = (caster, target, move) => {

    // console.log(caster);
    // console.log(target);
    // console.log(move);

    if (caster.sleep > 0) {
        caster.sleep -= 1;
        return (null);
    }

    if (caster.flinch) {
        caster.flinch = false;
        return (null)
    }

    if (caster.confuse && Math.random() <= 0.50) {
        //TODO: get the move pound instead of a string
        caster.health = Math.max(0, caster.health - damage(caster, caster, "Pound"))
    }
    if (caster.freeze) {

        return (null)
    }

    if (caster.paralyze && Math.random() <= 0.25) {
        return (null);
    }

    if (move.effect1 == "Multihit") {
        for (i = 0; i < move.effect2; i++) {
            if (moveHits(caster, target, move)) {
                move_effect(caster, target, move)
            }
        }
    }

    if (moveHits(caster, target, move)) {
      
      move_effect(caster, target, move)
      console.log("the move hit!!!");
    } else {

        console.log(" move didnt didnt hit :(");
    }

}

//applies status effects and damage of a move
const move_effect = (caster, target, move) => {
    effect1 = move.tags[0];
    effect2 = move.tags[1];

    if (effect1 == "Paralyze/Burn/Freeze") {
        if (Math.random() < 0.2) {
            target.paralyze = 0.2;
        }
        if (Math.random() < 0.2) {
            target.burn = 0.2;
        }
        if (Math.random() < 0.2) {
            target.freeze = 0.2;
        }
    }
    if (effect2 == "Double") {
        value = 2;
    }
    if (effect2 == "Maybe") {
        if (Math.random() < 0.2) {
            damage(caster, target, move);
            target.health -= damage(caster, target, move);
        }
    }
    if (effect1 == "A buff") {
        caster.attack += value * 20;
    }
    if (effect1 == "A debuff") {
        target.attack -= Math.max(value * 20, 0);
    }
    if (effect1 == "Burn") {
        target.burn = true;
        target.freeze = false;
    }
    if (effect1 == "Charge") {
        target.charge += damage(caster, target, move);
    }
    if (effect1 == "Confuse") {
        target.confuse = true;
    }
    if (effect1 == "D buff") {
        caster.defense = value * 20;
    }
    if (effect1 == "D debuff") {
        target.defense -= Math.max(value * 20, 0);
    }
    if (effect1 == "DOT") {
        target.DOT = 4 + Math.floor(Math.random() * 2)
        target.DOT_damage = move.power
    }
    if (effect1 == "E buff") {
        caster.evasion = value * 20;
    }
    if (effect1 == "Flat") {
        if (effect2 > 1) {
            target.health -= effect2;
        }
        else {
            target.health -= effect2 * target.health;
        }
    }
    if (effect1 == "Flinch") {
        target.flinch = true;
    }
    if (effect1 == "Freeze") {
        target.freeze = true;
    }
    if (effect1 == "Heal") {
        if (effect2 > 1) {
            caster.health += effect2;
        }
        else {
            caster.health += effect2 * target.health;
        }
    }
    if (effect1 == "Insta") {
        target.health = 0;
    }
    if (effect1 == "Paralyze") {
        target.paralyze = true;
        if (target.paralyzeSpeedDecrease == false) {
            target.paralyzeSpeedDecrease = true;
            target.speed = target.speed * 0.25;
        }
    }
    if (effect1 == "Poison") {
        target.poison = true;
    }
    if (effect1 == "Recoil") {
        caster.health = caster.health - caster.health * effect2;
    }
    if (effect1 == "S buff") {
        caster.speed = value * 20;
    }
    if (effect1 == "S debuff") {
        target.speed -= Math.max(value * 20, 0);
    }
    if (effect1 == "SA buff") {
        caster.specialAttack = value * 20;
    }
    if (effect1 == "SD buff") {
        caster.specialDefense = value * 20;
    }
    if (effect1 == "SD debuff") {
        target.specialDefense -= Math.max(value * 20, 0);
    }
    if (effect1 == "Sleep") {
        var rand_num = Math.random();
        if (rand_num < 1 / 8) {
            target.sleep = Math.max(target, sleep, 1);
        }
        else if (rand_num < 1 / 8 + 1 / 4) {
            target.sleep = Math.max(target, sleep, 2);
        }
        else {
            target.sleep = Math.max(target.sleep, Math.floor(Math.random() * 5) + 2)
        }
        target.sleep = true;
    }
    console.log("targeting enemies health");
    target.hp = Math.max(0, target.hp - damage(caster, target, move));
    console.log(target.hp);
}

//calculates if a move hits
const moveHits = (caster, target, move) => {
    console.log("im in move hits");
    console.log("move.accuracy " + move.accuracy);
    let hitProb = move.accuracy;
    let randNum = Math.floor(Math.random() * 256);
    return hitProb > randNum;
}

//calculates the damage of a move
const damage = (caster, target, move) => {
    console.log("in damage");
    var level = 1;
    var critThreshold = move.effect1 == "High Crit" || move.effect2 == "High Crit" ? Math.min(8 * Math.floor(caster.attr.speed / 2), 255) : Math.floor(caster.attr.speed / 2);
    console.log("in critThreshold " + critThreshold);

    var crit = critThreshold > Math.floor(Math.random() * 256) ? 2 : 1;
    console.log("in crit " + crit);

    var STAB = target.types[0] == move.type || target.types[1] == move.type ? 1.5 : 1;
    console.log("in STAB " + STAB);

    var attack = move.type == "special" ? caster.attr.spAtk : caster.attr.atk;
    var defense = move.type == "special" ? caster.attr.spDef : caster.attr.def;
    var damage = (((2 * level / 5 * crit + 2) * move.power * attack / defense) / 50 + 2) * STAB * getTypeAdvantage(move.type, target.types[0]) * getTypeAdvantage(move.type, target.types[1]);
    console.log("damage is" + damage);
    return (damage);
}
// TODO get the type advantage
const getTypeAdvantage = (attackerType, defenderType) => {
    return (1);
}



function initBattle() {

    // let newIds = [];
    // $.get("./scripts/get_pokemon.php", { "ids": newIds }, setPokemon, "json");

    team = JSON.parse(document.getElementById("teamJSON").innerHTML);
    enemyTeam = JSON.parse(document.getElementById("enemyTeamJSON").innerHTML);
    console.log(team);
    console.log(enemyTeam);


    document.getElementById("pokemon1-name").innerHTML = team.pkm[0].name;
    document.getElementById("pokemon1-hp").innerHTML = team.pkm[0].hp;
    document.getElementById("pokemon1-hp").style.width = ((team.pkm[0].hp / team.pkm[0].hp) * 100) + "%";
    document.getElementById("player1-active-pokemon").src = team.pkm[0].img;
    document.getElementById("move1").innerHTML = team.pkm[0].moves[0].name + "<br>" + team.pkm[0].moves[0].type;
    document.getElementById("move2").innerHTML = team.pkm[0].moves[1].name + "<br>" + team.pkm[0].moves[1].type;
    document.getElementById("move3").innerHTML = team.pkm[0].moves[2].name + "<br>" + team.pkm[0].moves[2].type;
    document.getElementById("move4").innerHTML = team.pkm[0].moves[3].name + "<br>" + team.pkm[0].moves[3].type;

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

    // query the db for a ran pokemon and give it 4 random moves
    //enemy pokemon
    document.getElementById("enemy-pokemon-name").innerHTML = enemyTeam.pkm[0].name;
    document.getElementById("enemy-pokemon-hp").innerHTML = enemyTeam.pkm[0].hp;
    document.getElementById("enemy-pokemon-hp").style.width = ((enemyTeam.pkm[0].hp / enemyTeam.pkm[0].hp) * 100) + "%";
    document.getElementById("player2-active-pokemon").src = enemyTeam.pkm[0].img;

}

 
  // //if its the cpus turn
  function cpuTurn(){
    console.log("====in cpu turn===");
    
    cpuMove = Math.floor(Math.random()*3);

    console.log("cpu used"+cpuMove);
    //check if move pp is not 0
    if (enemyTeam.pkm[currPkm].moves[cpuMove].pp != 0) {
        console.log(enemyTeam.pkm[currPkm].moves[cpuMove].name + " can be used pp is " + enemyTeam.pkm[currPkm].moves[cpuMove].pp);

        moveInfo = enemyTeam.pkm[currPkm].moves[cpuMove];
        use_move( enemyTeam.pkm[cpuMove],team.pkm[currPkm], moveInfo);
        //show new heal and status effect
        document.getElementById("pokemon1-hp").innerHTML = team.pkm[0].hp;
        document.getElementById("pokemon1-hp").style.width = ((team.pkm[0].hp / team.pkm[0].attr.hp) * 100) + "%";
        console.log("width of health bar"+((team.pkm[0].hp / team.pkm[0].attr.hp) * 100));
    }   

  }
//battle

/*
todo
 -switch between the user turn to pick moves and the cpus turn ( prevent the user from clicking any buttons
 when its the cpus turn)
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
            if (team.pkm[currPkm].moves[0].pp != 0) {
                console.log(team.pkm[currPkm].moves[0].name + " can be used pp is " + team.pkm[currPkm].moves[0].pp);

                //[!] home
                moveInfo = team.pkm[currPkm].moves[0];
                use_move(team.pkm[currPkm], enemyTeam.pkm[0], moveInfo);
                console.log("enemy pokemon:", enemyTeam.pkm[0])
                //show new heal and status effect
                document.getElementById("enemy-pokemon-hp").innerHTML = enemyTeam.pkm[0].hp;
                document.getElementById("enemy-pokemon-hp").style.width = ((enemyTeam.pkm[0].hp / enemyTeam.pkm[0].attr.hp) * 100) + "%";
                console.log("width of health bar" + ((enemyTeam.pkm[0].hp / enemyTeam.pkm[0].attr.hp) * 100));

                //remove pp
                moveInfo.pp -= 1;
                console.log("pp: ", moveInfo.pp)
            }
            cpuTurn();
        });

        const move2 = document.getElementById("move2");
        move2.addEventListener("click", function () {
            console.log("move2 was selected");

            //check if move pp is not 0
            if (team.pkm[currPkm].moves[1].pp != 0) {
                console.log(team.pkm[currPkm].moves[1].name + " can be used pp is " + team.pkm[currPkm].moves[1].pp);

                moveInfo = team.pkm[currPkm].moves[1];
                use_move(team.pkm[currPkm], enemyTeam.pkm[0], moveInfo);
                //show new heal and status effect
                document.getElementById("enemy-pokemon-hp").innerHTML = enemyTeam.pkm[0].hp;
                document.getElementById("enemy-pokemon-hp").style.width = ((enemyTeam.pkm[0].hp / enemyTeam.pkm[0].attr.hp) * 100) + "%";
                console.log("width of health bar" + ((enemyTeam.pkm[0].hp / enemyTeam.pkm[0].attr.hp) * 100));

                //remove pp
                moveInfo.pp -= 1;
                console.log("pp: ", moveInfo.pp)
            }

        });
        const move3 = document.getElementById("move3");
        move3.addEventListener("click", function () {
            console.log("move2 was selected");

            //check if move pp is not 0
            if (team.pkm[currPkm].moves[2].pp != 0) {
                console.log(team.pkm[currPkm].moves[2].name + " can be used pp is " + team.pkm[currPkm].moves[2].pp);

                moveInfo = team.pkm[currPkm].moves[2];
                use_move(team.pkm[currPkm], enemyTeam.pkm[0], moveInfo);
                //show new heal and status effect
                document.getElementById("enemy-pokemon-hp").innerHTML = enemyTeam.pkm[0].hp;
                document.getElementById("enemy-pokemon-hp").style.width = ((enemyTeam.pkm[0].hp / enemyTeam.pkm[0].attr.hp) * 100) + "%";
                console.log("width of health bar" + ((enemyTeam.pkm[0].hp / enemyTeam.pkm[0].attr.hp) * 100));

                //remove pp
                moveInfo.pp -= 1;
                console.log("pp: ", moveInfo.pp)
            }

        });
        const move4 = document.getElementById("move4");
        move4.addEventListener("click", function () {
            console.log("move2 was selected");

            //check if move pp is not 0
            if (team.pkm[currPkm].moves[3].pp != 0) {
                console.log(team.pkm[currPkm].moves[3].name + " can be used pp is " + team.pkm[currPkm].moves[3].pp);

                moveInfo = team.pkm[currPkm].moves[3];
                use_move(team.pkm[currPkm], enemyTeam.pkm[0], moveInfo);
                //show new heal and status effect
                document.getElementById("enemy-pokemon-hp").innerHTML = enemyTeam.pkm[0].hp;
                document.getElementById("enemy-pokemon-hp").style.width = ((enemyTeam.pkm[0].hp / enemyTeam.pkm[0].attr.hp) * 100) + "%";
                console.log("width of health bar" + ((enemyTeam.pkm[0].hp / enemyTeam.pkm[0].attr.hp) * 100));

                //remove pp
                moveInfo.pp -= 1;
                console.log("pp: ", moveInfo.pp)

            }

        });

        const switch1 = document.getElementById("pokemon1-img-name");
        switch1.addEventListener("click", function () {
            console.log("switch1 was selected");
            if (team.pkm[0].hp > 0) {
                currPkm = 0
            }
            updatePlayer1Pokemon()
        })

        const switch2 = document.getElementById("pokemon2-img-name");
        switch2.addEventListener("click", function () {
            console.log("switch2 was selected");
            if (team.pkm[1].hp > 0) {
                currPkm = 1
            }
            updatePlayer1Pokemon()
        })

        const switch3 = document.getElementById("pokemon3-img-name");
        switch3.addEventListener("click", function () {
            console.log("switch3 was selected");
            if (team.pkm[2].hp > 0) {
                currPkm = 2
            }
            updatePlayer1Pokemon()
        })

        const switch4 = document.getElementById("pokemon4-img-name");
        switch4.addEventListener("click", function () {
            console.log("switch4 was selected");
            if (team.pkm[3].hp > 0) {
                currPkm = 3
            }
            updatePlayer1Pokemon()
        })

        const switch5 = document.getElementById("pokemon5-img-name");
        switch5.addEventListener("click", function () {
            console.log("switch5 was selected");
            if (team.pkm[4].hp > 0) {
                currPkm = 4
            }
            updatePlayer1Pokemon()
        })

        const switch6 = document.getElementById("pokemon6-img-name");
        switch6.addEventListener("click", function () {
            console.log("switch6 was selected");
            if (team.pkm[5].hp > 0) {
                currPkm = 5
            }
            updatePlayer1Pokemon()
        })

        
    }

    if(isPlayer1Turn ==false){
      cpuTurn();
    }




}



function updatePlayer1Pokemon() {
    document.getElementById("pokemon1-name").innerHTML = team.pkm[currPkm].name;
    document.getElementById("pokemon1-hp").innerHTML = team.pkm[currPkm].hp;
    document.getElementById("pokemon1-hp").style.width = ((team.pkm[currPkm].hp / team.pkm[currPkm].hp) * 100) + "%";
    document.getElementById("player1-active-pokemon").src = team.pkm[currPkm].img;
    document.getElementById("move1").innerHTML = team.pkm[currPkm].moves[0].name + "<br>" + team.pkm[currPkm].moves[0].type;
    document.getElementById("move2").innerHTML = team.pkm[currPkm].moves[1].name + "<br>" + team.pkm[currPkm].moves[1].type;
    document.getElementById("move3").innerHTML = team.pkm[currPkm].moves[2].name + "<br>" + team.pkm[currPkm].moves[2].type;
    document.getElementById("move4").innerHTML = team.pkm[currPkm].moves[3].name + "<br>" + team.pkm[currPkm].moves[3].type;
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
