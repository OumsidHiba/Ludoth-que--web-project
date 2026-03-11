
document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".toggle-detail-btn");

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            const id = this.dataset.id;
            const panel = document.getElementById("detail-" + id);

            if (!panel) return;

            const isHidden = panel.hasAttribute("hidden");

            // Fermer tous les autres détails
            document.querySelectorAll(".game-detail-panel").forEach(p => {
                p.setAttribute("hidden", "");
            });

            document.querySelectorAll(".toggle-detail-btn").forEach(btn => {
                btn.textContent = "Voir la fiche";
            });

            // Si le panneau était fermé, on l'ouvre
            if (isHidden) {
                panel.removeAttribute("hidden");
                this.textContent = "Masquer la fiche";
            }
        });
    });
});
