document.addEventListener("DOMContentLoaded", () => {
    // создаём контейнер для диалога один раз
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
        <div style="position: relative; max-width: 90%; max-height: 90%;">
            <button id="closeFlash"
                style="position:absolute;top:-40px;right:0;
                padding:8px 12px;cursor:pointer;">✖</button>
            <div id="flashContainer" style="width:800px;height:600px;"></div>
        </div>
    `;
    document.body.appendChild(modal);

    const flashContainer = modal.querySelector("#flashContainer");
    const closeBtn = modal.querySelector("#closeFlash");

    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
        flashContainer.innerHTML = ""; // очистка, чтобы останавливать SWF
    });

    // обработчик на все ссылки с классом board_flash
    document.querySelectorAll(".board_flash").forEach(link => {
        link.addEventListener("click", e => {
            e.preventDefault();

            const swfUrl = link.getAttribute("href");

            flashContainer.innerHTML = ""; // очистка
            const ruffle = window.RufflePlayer.newest();
            const player = ruffle.createPlayer();
            flashContainer.appendChild(player);
            player.load(swfUrl);

            player.style.width = "100%";
            // player.style.height = "100%";

            modal.style.display = "flex";
        });
    });
});