document.addEventListener("DOMContentLoaded", function () {
    const grid = document.getElementById("events-grid");
    const typeFilter = document.getElementById("filter-type");
    const dateFilter = document.getElementById("filter-date");
    const upcomingBtn = document.getElementById("filter-upcoming");
    const pastBtn = document.getElementById("filter-past");

    if (!grid || !typeFilter || !dateFilter || !upcomingBtn || !pastBtn) {
        return;
    }

    let statut = "upcoming";

    function getCards() {
        return Array.from(grid.querySelectorAll(".card"));
    }

    function updateButtons() {
        if (statut === "upcoming") {
            upcomingBtn.classList.add("btn-primary");
            upcomingBtn.classList.remove("btn-outline");

            pastBtn.classList.add("btn-outline");
            pastBtn.classList.remove("btn-primary");
        } else {
            pastBtn.classList.add("btn-primary");
            pastBtn.classList.remove("btn-outline");

            upcomingBtn.classList.add("btn-outline");
            upcomingBtn.classList.remove("btn-primary");
        }
    }

    function sortCards() {
        const cards = getCards();

        cards.sort((a, b) => {
            const dateA = new Date(a.dataset.date);
            const dateB = new Date(b.dataset.date);

            if (dateFilter.value === "ancien") {
                return dateA - dateB;
            }

            return dateB - dateA;
        });

        cards.forEach(card => grid.appendChild(card));
    }

    function filterCards() {
        const selectedType = typeFilter.value;
        const today = new Date("2026-03-11T00:00:00");

        getCards().forEach(card => {
            const cardType = card.dataset.type;
            const cardDate = new Date(card.dataset.date + "T00:00:00");

            let visible = true;

            if (selectedType !== "" && cardType !== selectedType) {
                visible = false;
            }

            if (statut === "upcoming" && cardDate < today) {
                visible = false;
            }

            if (statut === "past" && cardDate >= today) {
                visible = false;
            }

            card.style.display = visible ? "" : "none";
        });
    }

    function applyFilters() {
        sortCards();
        filterCards();
        updateButtons();
    }

    typeFilter.addEventListener("change", applyFilters);
    dateFilter.addEventListener("change", applyFilters);

    upcomingBtn.addEventListener("click", function (e) {
        e.preventDefault();
        statut = "upcoming";
        applyFilters();
    });

    pastBtn.addEventListener("click", function (e) {
        e.preventDefault();
        statut = "past";
        applyFilters();
    });

    applyFilters();
});