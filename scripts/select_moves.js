
var team;
var allMoves;
var curPkmIndex = 0;

$(function () {
    team = JSON.parse(document.getElementById("teamJSON").innerHTML);
    allMoves = JSON.parse(document.getElementById("movesJSON").innerHTML);

    for (let i = 0; i < TEAMSIZE; i++) {
        document.getElementById("pkm" + i).addEventListener("click", function () {
            if (i != curPkmIndex) {
                updatePage(i);
                curPkmIndex = i;
            }
        });
    }
});

function updatePage(i) {
    let elm = document.getElementById("currPkmImg");
    elm.src = team.pkm[i].img;
    elm.classList.remove(team.pkm[curPkmIndex].types[0].toLowerCase() + "-type")
    elm.classList.add(team.pkm[i].types[0].toLowerCase() + "-type");
    elm = document.getElementById("attrTableBR").children;
    for (let j = 0; j < NUMSTATS; j++) {
        elm[j].innerHTML = Object.values(team.pkm[i].attr)[j];
    }


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
    console.log(elm);
}