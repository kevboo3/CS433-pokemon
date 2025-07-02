/*
 * File: select6.js 
 * Author: Justin C
 * Description: Interactive content for select6.js
 * Requires utils.js to be in preceeding script tag 
*/

// Global Constants
const MAXLOCKED = 5;                    // Max number of locked pokemon
const BGSCALER = 0.3;                   // Scales background music down. This song is loud!

// Global Variables
var team = new Team(null);              // The current team on screen
var locked = new Array(6).fill(false);  // Array of which pokemon are locked
var numLocked = 0;                      // Number of locked pokemon

$(function () {
    // Gets Data Passed From Previous Page
    team = JSON.parse(document.getElementById("teamJSON").innerHTML);      // Gets team data
    volume = JSON.parse(document.getElementById("volumeJSON").innerHTML);  // Gets volume setting
    muted = JSON.parse(document.getElementById("mutedJSON").innerHTML);    // Gets muted setting

    // Gets Audio Tags And Sets Volume
    music = document.getElementById('bgMusic');
    music.volume = volume * VSCALER * BGSCALER;
    music.muted = muted;

    click = document.getElementById('btnClk');
    click.volume = volume * VSCALER;
    click.muted = muted;

    if (!muted) {  // Starts Music
        music.play();
    }

    // Event For Button Click Sound
    document.querySelectorAll('button').forEach(button => {
        button.addEventListener('click', () => {
            playSound(click, muted);  // Plays sound if not muted
        });
    });

    // Event For Confirm Button
    document.getElementById("confirm").addEventListener("click", function () {
        // Create Form
        let form = document.createElement("form");
        form.style.visibility = "hidden";
        form.method = "POST";
        form.action = "./select_moves.php";

        // Adds Team JSON String To $_POST
        let ipt = document.createElement("input");
        ipt.name = "team";
        ipt.value = JSON.stringify(team);
        form.appendChild(ipt);            

        // Adds Volume To $_POST
        ipt = document.createElement("input");
        ipt.name = "volume";
        ipt.value = JSON.stringify(volume);
        form.appendChild(ipt);

        // Adds Mute to $_POST
        ipt = document.createElement("input");
        ipt.name = "muted";
        ipt.value = muted;
        form.appendChild(ipt);

        // Append Form And Submit
        document.body.appendChild(form);
        form.submit();  // Redirects to select_moves.php
    });

    // Event For Back Button
    document.getElementById("back").addEventListener("click", function () {
        // Create Form
        let form = document.createElement("form");
        form.style.visibility = "hidden";
        form.method = "POST";
        form.action = "./proj3.php";

        // Adds Volume To $_POST
        ipt = document.createElement("input");
        ipt.name = "volume";
        ipt.value = JSON.stringify(volume);
        form.appendChild(ipt);

        // Adds Mute to $_POST
        ipt = document.createElement("input");
        ipt.name = "muted";
        ipt.value = JSON.stringify(muted);
        form.appendChild(ipt);

        // Append Form And Submit
        document.body.appendChild(form);
        form.submit();  // Redirects to proj3.php
    });

    // Event For Reroll Button
    document.getElementById("reroll").addEventListener("click", function () {
        let ids = [];                                       // Array of urrent pokemon ids
        for (let i = 0; i < TEAMSIZE; i++) {                // Iterate over team
            if (locked[i]) {                                // Appends if locked
                ids.push(parseInt(team.pkm[i].id));
            }
        }
        let newIds = [];                                    // Array of new pokemon ids
        for (let i = 0; i < TEAMSIZE - numLocked; i++) {    // For number of pokemon not locked
            let n;                                          // New id
            do {
                n = genId()                                 // Generate valid id
            }
            while (newIds.includes(n) || ids.includes(n));  // Ensure no duplicate pokemon 
            newIds.push(n);
        }
        newIds = newIds.join(",");                          // Convert newIds to CSV for get request

        // Gets new pokemon from server. Handled by setPokemon()
        $.get("./scripts/get_pokemon.php", { "ids": newIds }, setPokemon, "json");
    });

    // Event For Pokemon Entries
    document.querySelectorAll(".entry").forEach(e => {
        e.addEventListener("click", function () {
            if (e.classList.contains("locked")) {  // Unlocks pokemon 
                e.classList.remove("locked");
                locked[e.id[e.id.length - 1]] = false;
                numLocked--;    
            }
            else if (numLocked < MAXLOCKED) {      // Locks pokemon
                e.classList.add("locked");
                locked[e.id[e.id.length - 1]] = true;
                numLocked++;
            }
            else if (numLocked === MAXLOCKED) {    // Prevents locking all pokemon
                window.alert("Only 5 Pokemon can be locked at a time!");
            }
        });
    });

    // Event For Sound Toggle Button
    document.getElementById("sound").addEventListener("click", function () {
        if (muted) {  // Unmutes and starts music
            music.muted = false;
            click.muted = false;
            muted = false;
            music.currentTime = 0;
            music.play();
        }
        else {        // Mutes 
            music.muted = true;
            click.muted = true;
            muted = true;
        }
    });

    // Event For Volume Up Button
    document.getElementById("up").addEventListener("click", function () {
        if (!muted) {             // Only updates if not muted
            volume = volume + 5;
            if (volume > MAXV) {  // Clamps volume and alerts user
                volume = MAXV;
                window.alert("Max Volume");
            }
            else {                // Updates music and click volume
                music.volume = volume * VSCALER * BGSCALER;
                click.volume = volume * VSCALER;
            }
        }
    });

    // Event For Volume Down Button
    document.getElementById("down").addEventListener("click", function () {
        if (!muted) {             // Only updates if not muted
            volume = volume - 5;

            if (volume < MINV) {  // Clamps volume and alerts user
                volume = MINV;
                window.alert("Minimum Volume");
            }
            else {                // Updates music and click volume
                music.volume = volume * VSCALER * BGSCALER;
                click.volume = volume * VSCALER;
            }
        }
    });
});

// setPokemon()
// Handles data returned from get_pokemon.php
function setPokemon(newPkm) {
    let curNew = 0;                                                         // Index for currently replaced pokemon
    for (let i = 0; i < TEAMSIZE; i++) {                                    // Iterates over team
        if (!locked[i]) {
            team.pkm[i] = newPkm[curNew];                                   // Updates pokemon in team

            // Updates Stats On Screen
            pkm = team.pkm[i];
            let elm = document.getElementById("pkm" + i).children[1];       // Selects pokemon entry righthand side
            for (let j = 0; j < NUMSTATS; j++) {                            // Updates stats on screen
                elm.children[j].innerHTML = Object.values(pkm.attr)[j];
            }
            elm = document.getElementById("pkm" + i).children[0].children;  // Selects pokemon entry lefthand side
            for (let j = 0; j < elm.length; j++) {                          // Iterates over entries
                switch (j) {
                    case 0:                                                 // Updates pokemon id and image on screen
                        elm[j].name = pkm.id;
                        elm[j].src = pkm.img;
                        break;
                    case 1:                                                 // Updates pokemon name on screen
                        elm[j].innerHTML = pkm.name;
                        break;
                    case 2:                                                 // Updates pokemon type on screen
                        elm[j].innerHTML = "";                              // Clears images
                        for (let k = 0; k < pkm.types.length; k++) {
                            if (!pkm.types[k]) {                            // If pokemon has only 1 type
                                break;
                            }
                            // Create New Type Image Element
                            let img = document.createElement("img");
                            img.classList.add("type-icon");
                            img.src = "./dataFiles/typeIcons/" + pkm.types[k].toLowerCase() + ".png";
                            img.alt = pkm.types[k];

                            elm[j].appendChild(img);                        // Apends image to entry
                        }
                        break;
                    default:                                                // Log unexpected case
                        console.log("Switch Statement Error! Unmatched Case " + j);

                }
            }
            curNew++;
        }
    }
}