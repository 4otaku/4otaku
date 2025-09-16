document.addEventListener("DOMContentLoaded", () => {
    const modal = document.createElement("div");
    modal.style.cssText = `
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.8);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    `;
    modal.innerHTML = `
        <div id="dialogWrapper" style="position: relative;">
            <div style="position:absolute;top:-40px;right:0;display:flex;gap:8px;">
                <button id="size800x600">800x600</button>
                <button id="size50">50%</button>
                <button id="size90">90%</button>
                <button id="closeFlash">✖</button>
            </div>
            <div id="flashContainer" style="width:100%;height:100%;background:#000;"></div>
        </div>
    `;
    document.body.appendChild(modal);

    const dialogWrapper = modal.querySelector("#dialogWrapper");
    const flashContainer = modal.querySelector("#flashContainer");
    const closeBtn = modal.querySelector("#closeFlash");

    function setCookie(name, value, days = 365) {
        const d = new Date();
        d.setTime(d.getTime() + (days*24*60*60*1000));
        document.cookie = `${name}=${value};expires=${d.toUTCString()};path=/`;
    }

    function getCookie(name) {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? match[2] : null;
    }

    function setSize(mode, save = true) {
        if (mode === "800x600") {
            dialogWrapper.style.width = "800px";
            dialogWrapper.style.height = "600px";
        } else if (mode === "50") {
            dialogWrapper.style.width = "50%";
            dialogWrapper.style.height = "50%";
        } else if (mode === "90") {
            dialogWrapper.style.width = "90%";
            dialogWrapper.style.height = "90%";
        }
        if (save) setCookie("flashSize", mode);
    }

    modal.querySelector("#size800x600").addEventListener("click", () => setSize("800x600"));
    modal.querySelector("#size50").addEventListener("click", () => setSize("50"));
    modal.querySelector("#size90").addEventListener("click", () => setSize("90"));

    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
        flashContainer.innerHTML = "";
    });

    document.querySelectorAll(".board_flash").forEach(link => {
        link.addEventListener("click", e => {
            e.preventDefault();

            const savedSize = getCookie("flashSize");
            if (savedSize) {
                setSize(savedSize, false);
            } else {
                setSize("800x600", false);
            }

            const swfUrl = link.getAttribute("href");
            flashContainer.innerHTML = "";

            const ruffle = window.RufflePlayer.newest();
            const player = ruffle.createPlayer();
            player.style.width = "100%";
            player.style.height = "100%";
            flashContainer.appendChild(player);
            player.load(swfUrl);

            modal.style.display = "flex";
        });
    });
});