/*
 * File: proj3.js 
 * Author: Justin C
 * Description: Interactive content for proj3.php
 * Requires utils.js to be in preceeding script tag 
*/

// Wait For Document to be ready
$(function () {
    volume = JSON.parse(document.getElementById("volumeJSON").innerHTML);  // Gets volume setting
    muted = JSON.parse(document.getElementById("mutedJSON").innerHTML);    // Gets muted setting

    // Gets Audio Tags And Sets Volume
    music = document.getElementById('bgMusic');
    music.volume = volume * VSCALER;

    click = document.getElementById('btnClk');
    click.volume = volume * VSCALER;

    // Read Muted In
    if (muted === 1) {  //  Will be 1 first time loading the page
        muted = true;
    }
    else {
        if (!muted) {  // 
            music.play();
        }
    }
    music.muted = muted;
    click.muted = muted;

    // Event For Button Click Sound
    document.getElementById("button-container").addEventListener("click", function () {
        playSound(click, muted);
    });

    // Event For Confirm Button
    document.getElementById("start-btn").addEventListener("click", function () {
        // Create Form
        playSound(click, muted);
        let form = document.createElement("form");
        form.style.visibility = "hidden";
        form.method = "POST";
        form.action = "./select6.php";

        // Adds Volume To $_POST
        let ipt = document.createElement("input");
        ipt.name = "volume";
        ipt.value = JSON.stringify(volume);
        form.appendChild(ipt);
        document.body.appendChild(form);

        // Adds Mute to $_POST
        ipt = document.createElement("input");
        ipt.name = "muted";
        ipt.value = JSON.stringify(muted);
        form.appendChild(ipt);

        // Append Form And Submit
        document.body.appendChild(form);
        form.submit();  // Redirects to select6.php
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