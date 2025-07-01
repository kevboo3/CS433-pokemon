
var team;
var allMoves;
var curPkmIndex = 0;

$(function () {
    team = JSON.parse(document.getElementById("teamJSON").innerHTML);
    allMoves = JSON.parse(document.getElementById("movesJSON").innerHTML);

    document.getElementById("confirm").addEventListener("click", function () {
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

    elm = document.getElementById("movesInput").children;
    for (let j = 0; j < NUMMOVES; j++) {
        elm[j].innerHTML = "";
        let opt = document.createElement("option");
        opt.selected = "selected";
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
            if (!team.pkm[i].moves.some(move => move.name != "None") {
                let opt = document.createElement("option");
                opt.innerHTML = "None";
                elm[j].appendChild(opt);
            }
        }
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
}