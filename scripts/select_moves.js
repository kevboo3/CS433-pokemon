
var team;
var allMoves;
var curPkmIndex = 0;

$(function () {
    team = JSON.parse(document.getElementById("teamJSON").innerHTML);
    allMoves = JSON.parse(document.getElementById("movesJSON").innerHTML);

    // Confirm Button Listener
    document.getElementById("confirm").addEventListener("click", function () {
        // Posts team data to batttle.php via form
        let form = document.createElement("form");
        form.style.visibility = "hidden";
        form.method = "POST";
        form.action = "./battle.php";
        let ipt = document.createElement("input");
        ipt.name = "team";
        ipt.value = JSON.stringify(team);
        form.appendChild(ipt);
        document.body.appendChild(form);
        form.submit();
    });

    // Back Button Listener
    document.getElementById("back").addEventListener("click", function () {
        // Posts team data to /select6.php via form
        let form = document.createElement("form");
        form.style.visibility = "hidden";
        form.method = "POST";
        form.action = "./select6.php";
        let ipt = document.createElement("input");
        ipt.name = "team";
        ipt.value = JSON.stringify(team);
        form.appendChild(ipt);
        document.body.appendChild(form);
        form.submit();
    });

    // Pokemon Button Listeners
    for (let i = 0; i < TEAMSIZE; i++) {
        document.getElementById("pkm" + i).addEventListener("click", function () {
            if (i != curPkmIndex) {
                updatePage(i);
                curPkmIndex = i;
            }
        });
    }

    // Moves Selection Listener
    elm = document.getElementById("movesInput").children;
    for (let i = 0; i < NUMMOVES; i++) {
        elm[i].addEventListener("change", function (e) {
            let index = e.target.id.at(-1);
            let old;

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
                if (j != index) {                                                    // Avoid box that was changed
                    if (e.target.value != "None") {
                        // Remove new from other lists
                        for (let k = 0; k < boxes[j].length; k++) {                  // Iterate over all options
                            if (boxes[j].children[k].innerHTML == e.target.value) {  // Remove old
                                boxes[j].removeChild(boxes[j].children[k]);
                            }
                        }

                        // Check if "None" needs to be added 
                        found = false;
                        for (let k = 0; k < boxes[j].length; k++) {            // Iterate over all options
                            if (boxes[j].children[k].innerHTML == "None") {    // Check for "None"
                                found = true;
                                break;
                            }
                        }
                        if (!found) {                                          // Add "None" if needed
                            newOpt = document.createElement("option");
                            newOpt.innerHTML = "None";
                            boxes[j].appendChild(newOpt);
                            console.log("append");
                        }
                        if (old != "None") {                                   // Add old to other lists if needed
                            newOpt = document.createElement("option");
                            newOpt.innerHTML = old;
                            boxes[j].appendChild(newOpt);
                        }
                    }
                    else if (e.target.value == "None") {                           // Check if None needs to be removed
                        count = 0;                                                 // Count "None"s selected
                        for (let k = 0; k < NUMMOVES; k++) {                       // Iterate over all boxes
                            for (let l = 0; l < boxes[k].length; l++) {            // Iterate over all options
                                if (boxes[k].children[l].hasAttribute("selected")  // Check for selected "None"s
                                    && boxes[k].children[l].innerHTML == "None") {
                                    count++;
                                    break
                                }
                            }
                        }
                        if (count == 3) {                                               // Remove "None" if 3 are selected
                            for (let k = 0; k < NUMMOVES; k++) {                        // Iterate over all boxes
                                for (let l = 0; l < boxes[k].length; l++) {             // Iterate over all options
                                    if (!boxes[k].children[l].hasAttribute("selected")  // Remove "None" from remaining box
                                        && boxes[k].children[l].innerHTML == "None") {
                                        boxes[k].removeChild(boxes[k].children[l]);
                                        break
                                    }
                                }
                            }
                        }
                        // Add old to other lists
                        newOpt = document.createElement("option");
                        newOpt.innerHTML = old;
                        boxes[j].appendChild(newOpt);
                    }
                } 
            }
            // Update team object
            for (let j = 0; j < allMoves[curPkmIndex].length; j++) {
                if (allMoves[curPkmIndex][j].name == e.target.value) {
                    team.pkm[curPkmIndex].moves[index] = allMoves[curPkmIndex][j];
                    break;
                }
            }
        })
    }
});

function updatePage(i) {
    // Update main image
    let elm = document.getElementById("currPkmImg");
    elm.src = team.pkm[i].img;
    elm.classList.remove(team.pkm[curPkmIndex].types[0].toLowerCase() + "-type")
    elm.classList.add(team.pkm[i].types[0].toLowerCase() + "-type");
    
    // Update stats
    elm = document.getElementById("attrTableBR").children;
    for (let j = 0; j < NUMSTATS; j++) {
        elm[j].innerHTML = Object.values(team.pkm[i].attr)[j];
    }

    // Update moves select
    elm = document.getElementById("movesInput").children;
    for (let j = 0; j < NUMMOVES; j++) {
        elm[j].innerHTML = "";
        let opt = document.createElement("option");
        opt.setAttribute("selected", "");
        if (team.pkm[i].moves[j].name) {
            opt.innerHTML = team.pkm[i].moves[j].name;
        }
        else {
            opt.innerHTML = "None";
        }
        elm[j].appendChild(opt);
    }
    for (let j = 0; j < NUMMOVES; j++) {
        for (let k = 0; k < allMoves[i].length; k++) {
            if (!team.pkm[i].moves.some(move => move.name == allMoves[i][k].name)) {
                let opt = document.createElement("option");
                opt.innerHTML = allMoves[i][k].name;
                elm[j].appendChild(opt);
            }
        }
        let opt = document.createElement("option");
        opt.innerHTML = "None";
        elm[j].appendChild(opt);
    }

    // Update Moves Table
    elm = document.getElementById("movesTableBody");
    elm.innerHTML = "";
    for (let j = 0; j < allMoves[i].length; j++) {
        let tr = document.createElement("tr");
        tr.classList.add("move-result");
        for (let k = 0; k < NUMSTATS; k++) {
            let td = document.createElement("td");
            if (k != 1) {
                td.innerHTML = Object.values(allMoves[i][j])[k];
                if (!td.innerHTML) {
                    td.innerHTML = "None";
                }
            }
            else {
                let img = document.createElement("img");
                img.classList.add("type-icon");
                img.src = "./dataFiles/typeIcons/" + Object.values(allMoves[i][j])[k].toLowerCase() + ".png";
                td.appendChild(img);
            }
            tr.appendChild(td);
        }
        elm.appendChild(tr);
    }
}