
$(function () {
    music = document.getElementById('bgMusic');
    music.muted = true;
    music.volume = volume * VSCALER;

    click = document.getElementById('btnClk');
    click.muted = true;
    click.volume = volume * VSCALER;

    document.getElementById("button-container").addEventListener("click", function () {
        if (!muted) {
            if (!click.paused) { 
                click.pause(); 
                click.currentTime = 0; 
                click.play(); 
            } else {
                click.play(); 
            }
        }
    });

    document.getElementById("start-btn").addEventListener("click", function () {
        // Posts team data to batttle.php via form
        let form = document.createElement("form");
        form.style.visibility = "hidden";
        form.method = "POST";
        form.action = "./select6.php";
        let ipt = document.createElement("input");
        ipt.name = "volume";
        ipt.value = JSON.stringify(volume);
        form.appendChild(ipt);
        ipt = document.createElement("input");
        ipt.name = "muted";
        ipt.value = JSON.stringify(muted);
        form.appendChild(ipt);
        document.body.appendChild(form);
        form.submit();
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
                music.volume = volume * VSCALER;
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
                music.volume = volume * VSCALER;
                click.volume = volume * VSCALER;
            }
        }
    });
});