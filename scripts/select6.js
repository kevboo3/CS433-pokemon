const MAXLOCKED = 5;
const BGSCALER = 0.3;

var team = new Team(null);
var locked = new Array(6).fill(false);
var numLocked = 0;

$(function () {
    team = JSON.parse(document.getElementById("teamJSON").innerHTML);
    volume = JSON.parse(document.getElementById("volumeJSON").innerHTML);
    muted = JSON.parse(document.getElementById("mutedJSON").innerHTML);

    music = document.getElementById('bgMusic');
    music.volume = volume * VSCALER * BGSCALER;
    music.muted = muted;
    click = document.getElementById('btnClk');
    click.volume = volume * VSCALER;
    click.muted = muted;

    if (!muted) {
        music.play();
    }

    document.querySelectorAll('button').forEach(button => {
        button.addEventListener('click', () => {
            playSound(click, muted);
        });
    });

    document.getElementById("confirm").addEventListener("click", function () {
        playSound(click, muted);
        let form = document.createElement("form");
        form.style.visibility = "hidden";
        form.method = "POST";
        form.action = "./select_moves.php";

        let ipt = document.createElement("input");
        ipt.name = "team";
        ipt.value = JSON.stringify(team);
        form.appendChild(ipt);
        document.body.appendChild(form);

        ipt = document.createElement("input");
        ipt.name = "volume";
        ipt.value = JSON.stringify(volume);
        form.appendChild(ipt);
        document.body.appendChild(form);

        ipt = document.createElement("input");
        ipt.name = "muted";
        ipt.value = muted;
        form.appendChild(ipt);
        document.body.appendChild(form);

        form.submit();
    });

    document.getElementById("back").addEventListener("click", function () {
        playSound(click, muted);
        let form = document.createElement("form");
        form.style.visibility = "hidden";
        form.method = "POST";
        form.action = "./proj3.php";
        document.body.appendChild(form);

        ipt = document.createElement("input");
        ipt.name = "volume";
        ipt.value = JSON.stringify(volume);
        form.appendChild(ipt);
        document.body.appendChild(form);

        ipt = document.createElement("input");
        ipt.name = "muted";
        ipt.value = JSON.stringify(muted);
        form.appendChild(ipt);
        document.body.appendChild(form);

        form.submit();
    });

    document.getElementById("reroll").addEventListener("click", function () {
        let ids = [];
        for (let i = 0; i < TEAMSIZE; i++) {
            if (locked[i]) {
                ids.push(parseInt(team.pkm[i].id));
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
        console.log(ids);
        console.log(newIds);
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
                window.alert("Only 5 Pokemon can be locked at a time!");
            }
        });
    });

    document.getElementById("sound").addEventListener("click", function () {
        if (muted) {
            music.muted = false;
            click.muted = false;
            muted = false;
            music.currentTime = 0;
            music.play();
        }
        else {
            music.muted = true;
            click.muted = true;
            muted = true;
        }
    });

    document.getElementById("up").addEventListener("click", function () {
        if (!muted) {
            volume = volume + 5;

            if (volume > MAXV) {
                window.alert("Max Volume");
            }
            else {
                music.volume = volume * VSCALER * BGSCALER;
                click.volume = volume * VSCALER;
            }
        }
    });

    document.getElementById("down").addEventListener("click", function () {
        if (!muted) {
            volume = volume - 5;

            if (volume < MINV) {
                window.alert("Minimum Volume");
            }
            else {
                music.volume = volume * VSCALER * BGSCALER;
                click.volume = volume * VSCALER;
            }
        }
    });
});

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