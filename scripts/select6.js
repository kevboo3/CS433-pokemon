const TEAMSIZE = 6;
const STATSARR = ["total", "hp", "atk", "def", "spAtk", "spDef", "speed"];
const NUMSTATS = STATSARR.length;
const MAXLOCKED = 3;

var team = new Team(null);
var locked = new Array(6).fill(false);
var numLocked = 0;

$(function () {
    team.pkm = getPageData();
    console.log(team.pkm[0]);
    document.getElementById("confirm").addEventListener("click", function () {
        let json = JSON.stringify(team);
        let form = document.createElement("form");
        form.style.visibility = "hidden";
        form.method = "POST";
        form.action = "./select_moves.php";
        let ipt = document.createElement("input");
        ipt.name = "team";
        ipt.value = JSON.stringify(team);
        form.appendChild(ipt);
        document.body.appendChild(form);
        form.submit();
    });

    document.getElementById("reroll").addEventListener("click", function () {
        let ids = [];
        for (let i = 0; i < TEAMSIZE; i++) {
            if (locked[i]) {
                ids.push(team.pkm[i].id);
            }
        }
        let newIds = [];
        for (let i = 0; i < TEAMSIZE - numLocked; i++) {
            let n;
            do {
                n = genId()
            }
            while (newIds.includes(n) || ids.includes(n));
            newIds.push(n);
        }
        newIds = newIds.join(",");
        $.get("./scripts/get_pokemon.php", { "ids": newIds }, setPokemon, "json");
    });

    document.querySelectorAll(".entry").forEach(e => {
        e.addEventListener("click", function () {
            if (e.classList.contains("locked")) {
                e.classList.remove("locked");
                locked[e.id[e.id.length - 1]] = false;
                numLocked--;
            }
            else if (numLocked < MAXLOCKED) {
                e.classList.add("locked");
                locked[e.id[e.id.length - 1]] = true;
                numLocked++;
            }
            else if (numLocked === MAXLOCKED) {
                window.alert("Only 3 Pokemon can be locked at a time!");
            }
        });
    });
})

function getPageData() {
    let pkmArr = [];
    let attrArr = [];
    for (let i = 0; i < TEAMSIZE; i++) {
        attrArr.push([]);
        let elm = document.getElementById("pkm" + i).children[1];
        for (let j = 0; j < NUMSTATS; j++) {
            attrArr[i].push(elm.children[j].innerHTML);
        }
        attrArr[i].push(null);
        attrArr[i] = new Attributes(...attrArr[i]);
        elm = document.getElementById("pkm" + i).children[0].children;
        pkmArr.push([]);
        let img = "";
        for (let j = 0; j < elm.length; j++) {
            switch (j) {
                case 0:
                    pkmArr[i].push(elm[j].name);
                    img = "./" + elm[j].currentSrc.split(/\/(?=dataFiles)/)[1];
                    break;
                case 1:
                    pkmArr[i].push(elm[j].innerHTML);
                    break;
                case 2:
                    pkmArr[i].push(elm[j].innerHTML.match(/(?<=typeIcons\/).+(?=.png)/g));
                    if (pkmArr[i][j].length !== j) {
                        pkmArr[i][j].push(null);
                    }
                    else {
                        pkmArr[i][j][1] = pkmArr[i][j][1].charAt(0).toUpperCase() + pkmArr[i][j][1].slice(1);
                    }
                    pkmArr[i][j][0] = pkmArr[i][j][0].charAt(0).toUpperCase() + pkmArr[i][j][0].slice(1);
                    break;
                default:
                    console.log("Switch Statement Error! Unmatched Case " + j);
            }
        }
        pkmArr[i].push(attrArr[i], attrArr[i][1], null, null, img);
        pkmArr[i] = new Pokemon(...pkmArr[i]);
    }
    return pkmArr;
}

function setPokemon(newPkm) {
    let curNew = 0;
    for (let i = 0; i < TEAMSIZE; i++) {
        if (!locked[i]) {
            team.pkm[i] = newPkm[curNew];
            pkm = team.pkm[i];
            let elm = document.getElementById("pkm" + i).children[1];
            for (let j = 0; j < NUMSTATS; j++) {
                elm.children[j].innerHTML = Object.values(pkm.attr)[j];
            }
            elm = document.getElementById("pkm" + i).children[0].children;
            for (let j = 0; j < elm.length; j++) {
                switch (j) {
                    case 0:
                        elm[j].name = pkm.id;
                        elm[j].src = pkm.img;
                        break;
                    case 1:
                        elm[j].innerHTML = pkm.name;
                        break;
                    case 2:
                        elm[j].innerHTML = "";
                        for (let k = 0; k < pkm.types.length; k++) {
                            if (!pkm.types[k]) {
                                break;
                            }
                            let img = document.createElement("img");
                            img.classList.add("type-icon");
                            img.src = "./dataFiles/typeIcons/" + pkm.types[k].toLowerCase() + ".png";
                            img.alt = pkm.types[k];
                            elm[j].appendChild(img);
                        }
                        break;
                    default:
                        console.log("Switch Statement Error! Unmatched Case " + j);

                }
            }
            curNew++;
        }
    }
}