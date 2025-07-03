/*
 * File: select6.js 
 * Author: Justin C
 * Description: Interactive content for select6.js
 * Requires utils.js to be in preceeding script tag 
*/

// Global Variables
var team;             // The current team of pokemon
var allMoves;         // Array of all possible moves 
var curPkmIndex = 0;  // Incex of the current pokemon

$(function () {
    team = JSON.parse(document.getElementById("teamJSON").innerHTML);       // Gets team data
    allMoves = JSON.parse(document.getElementById("movesJSON").innerHTML);  // Gets moves data
    volume = JSON.parse(document.getElementById("volumeJSON").innerHTML);   // Gets volume setting
    muted = JSON.parse(document.getElementById("mutedJSON").innerHTML);     // Gets muted setting

    // Gets Audio Tags And Sets Volume
    music = document.getElementById('bgMusic');
    music.volume = volume * VSCALER;
    music.muted = muted;

    click = document.getElementById('btnClk');
    click.volume = volume * VSCALER;
    click.muted = muted;

    if (!muted) {  // Starts music
        music.play();
    }

    // Event For Button Click Sound
    document.getElementById("menu").addEventListener('click', function () {
        playSound(click, muted);  // Plays sound if not muted
    });

    // Confirm Button Listener
    document.getElementById("confirm").addEventListener("click", function () {
        // Create Form
        let form = document.createElement("form");
        form.style.visibility = "hidden";
        form.method = "POST";
        form.action = "./battle.php";

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
        ipt.value = JSON.stringify(muted);
        form.appendChild(ipt);

        // Append Form And Submit
        document.body.appendChild(form);
        form.submit();  // Redirects to battle.php
    });

    // Back Button Listener
    document.getElementById("back").addEventListener("click", function () {
        // Create Form
        let form = document.createElement("form");
        form.style.visibility = "hidden";
        form.method = "POST";
        form.action = "./select6.php";

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
        ipt.value = JSON.stringify(muted);
        form.appendChild(ipt);

        // Append Form And Submit
        document.body.appendChild(form);
        form.submit();
    });

    // Pokemon Button Listeners
    for (let i = 0; i < TEAMSIZE; i++) {
        document.getElementById("pkm" + i).addEventListener("click", function () {
            if (i != curPkmIndex) {
                playSound(click, muted);  // Plays sound if not muted
                updatePage(i);
                curPkmIndex = i;        
            }
        });
    }

    // Moves Selection Listeners
    elm = document.getElementById("movesInput").children;
    for (let i = 0; i < NUMMOVES; i++) {
        elm[i].addEventListener("change", function (e) {
            let index = e.target.id.at(-1);  // Pokemon index
            let old;                         // Old move name

            // Change selected box
            for (let j = 0; j < e.target.children.length; j++) {         // Iterate over all options
                if (e.target.children[j].hasAttribute("selected")) {     // Remove old selection
                    old = e.target.children[j].innerHTML;
                    e.target.children[j].removeAttribute("selected");
                } 
                if (e.target.children[j].innerHTML == e.target.value) {  // Add new selection
                    e.target.children[j].setAttribute("selected", "");
                }
            }

            // Update other boxes
            let boxes = document.getElementById("movesInput").children;
            for (let j = 0; j < NUMMOVES; j++) {
                if (j != index) {                                                       // Avoid box that was changed
                    if (e.target.value != "None") {                                     // If new move is not "None"
                        // Remove new from other lists
                        for (let k = 0; k < boxes[j].length; k++) {                     // Iterate over all options
                            if (boxes[j].children[k].innerHTML == e.target.value) {     // Remove old
                                boxes[j].removeChild(boxes[j].children[k]);
                            }
                        }

                        // Check if "None" needs to be added 
                        found = false;                                                  // If pokemon has non-"None" move
                        for (let k = 0; k < boxes[j].length; k++) {                     // Iterate over all options
                            if (boxes[j].children[k].innerHTML == "None") {             // Check for "None"
                                found = true;
                                break;
                            }
                        }
                        if (!found) {                                                   // Add "None" if needed
                            newOpt = document.createElement("option");                  // Create new option element 
                            newOpt.innerHTML = "None";                                  // Set option
                            boxes[j].appendChild(newOpt);                               // Append option to select element
                        }
                        if (old != "None") {                                            // Add old to other lists if needed
                            newOpt = document.createElement("option");                  // Create new option element 
                            newOpt.innerHTML = old;                                     // Set option
                            boxes[j].appendChild(newOpt);                               // Append option to select element
                        }
                    }

                    // Check if None needs to be removed
                    else if (e.target.value == "None") {
                        count = 0;                                                      // Count "None"s selected
                        for (let k = 0; k < NUMMOVES; k++) {                            // Iterate over all boxes
                            for (let l = 0; l < boxes[k].length; l++) {                 // Iterate over all options
                                if (boxes[k].children[l].hasAttribute("selected")       // Check for selected "None"s
                                    && boxes[k].children[l].innerHTML == "None") {
                                    count++;
                                    break
                                }
                            }
                        }
                        if (count == 3) {                                               // Remove "None" if 3 are selected
                            for (let k = 0; k < NUMMOVES; k++) {                        // Iterate over all boxes
                                for (let l = 0; l < boxes[k].length; l++) {             // Iterate over all options
                                    if (!boxes[k].children[l].hasAttribute("selected")  // If box has non-selected "None" move
                                        && boxes[k].children[l].innerHTML == "None") {
                                        boxes[k].removeChild(boxes[k].children[l]);     // Remove option
                                        break
                                    }
                                }
                            }
                        }
                        // Add old to other lists
                        newOpt = document.createElement("option");                      // Create new option element
                        newOpt.innerHTML = old;                                         // Set option
                        boxes[j].appendChild(newOpt);                                   // Append option to select element
                    }
                } 
            }
            // Update team object
            for (let j = 0; j < allMoves[curPkmIndex].length; j++) {                    // Iterates over all moves
                if (allMoves[curPkmIndex][j].name == e.target.value) {                  // If move == new move
                    team.pkm[curPkmIndex].moves[index] = allMoves[curPkmIndex][j];      // Update pokemon's move in team
                    break;
                }
            }
        })
    }

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
                window.alert("Max Volume");
            }
            else {                // Updates music and click volume
                music.volume = volume * VSCALER;
                click.volume = volume * VSCALER;
            }
        }
    });

    // Event For Volume Down Button
    document.getElementById("down").addEventListener("click", function () {
        if (!muted) {             // Only updates if not muted
            volume = volume - 5;

            if (volume < MINV) {  // Clamps volume and alerts user
                window.alert("Minimum Volume");
            }
            else {                // Updates music and click volume
                music.volume = volume * VSCALER;
                click.volume = volume * VSCALER;
            }
        }
    });
});

// updatePage()
// Handles updating the page when a new pokemon is selected
function updatePage(i) {
    // Update Selected Pokeomn Image And Text
    let elm = document.getElementById("currPkmImg");
    elm.src = team.pkm[i].img;                                                        // Update image
    elm.classList.remove(team.pkm[curPkmIndex].types[0].toLowerCase() + "-type")      // Remove old background
    elm.classList.add(team.pkm[i].types[0].toLowerCase() + "-type");                  // Add new background

    // Update Selected Pokeomn Stats
    elm = document.getElementById("attrTableBR").children;
    for (let j = 0; j < NUMSTATS; j++) {                                              // Iterate over stats
        elm[j].innerHTML = Object.values(team.pkm[i].attr)[j];
    }

    // Update Selected Moves
    elm = document.getElementById("movesInput").children;
    for (let j = 0; j < NUMMOVES; j++) {                                              // Iterate over input boxes
        elm[j].innerHTML = "";                                                        // Clear old options
        let opt = document.createElement("option");                                   // Create new option element
        opt.setAttribute("selected", "");                                             // Add selected attribute
        if (team.pkm[i].moves[j].name) {                                              // Set option
            opt.innerHTML = team.pkm[i].moves[j].name;
        }
        else {                                                                        // If no move is selected
            opt.innerHTML = "None";
        }
        elm[j].appendChild(opt);                                                      // Append option to select element
    }
    // Update Possible Moves
    for (let j = 0; j < NUMMOVES; j++) {                                              // Iterate over input boxes
        for (let k = 0; k < allMoves[i].length; k++) {                                // Iterate over possible moves
            if (!team.pkm[i].moves.some(move => move.name == allMoves[i][k].name)) {  // If the move is not selected
                let opt = document.createElement("option");                           // Create new option element 
                opt.innerHTML = allMoves[i][k].name;                                  // Set option
                elm[j].appendChild(opt);                                              // Append option to select element
            }
        }
        // Append "None" option
        let opt = document.createElement("option");          
        opt.innerHTML = "None";
        elm[j].appendChild(opt);
    }

    // Update Moves Table
    elm = document.getElementById("movesTableBody");
    elm.innerHTML = "";                                                               // Clear the table
    for (let j = 0; j < allMoves[i].length; j++) {                                    // Iterate over possible moves
        let tr = document.createElement("tr");                                        // Create new table row element
        tr.classList.add("move-result");
        for (let k = 0; k < NUMSTATS; k++) {                                          // Iterate over move stats
            let td = document.createElement("td");                                    // Create new table data element
            if (k != 1) {                                                             // If not the type column
                td.innerHTML = Object.values(allMoves[i][j])[k];                      // Set data
                if (!td.innerHTML) {                                                  // If move has no effect
                    td.innerHTML = "None";
                }
            }
            else {                                                                    // If the type column
                // Update Type Image
                let img = document.createElement("img");
                img.classList.add("type-icon");
                img.src = "./dataFiles/typeIcons/" + Object.values(allMoves[i][j])[k].toLowerCase() + ".png";
                td.appendChild(img);
            }
            tr.appendChild(td);                                                       // Append table date to table row
        }
        elm.appendChild(tr);                                                          // Append table row to table body
    }
}