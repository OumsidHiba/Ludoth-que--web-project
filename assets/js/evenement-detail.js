const evenements = {
  1: {
    type: "Jeu du jeudi",
    badgeClass: "badge badge-blue",
    image: "assets/img/catan.jpg",
    titre: "Tournoi de Catan",
    date: "Jeudi 20 mars 2026",
    heure: "18h00 – 21h00",
    lieu: "Salle A3, Campus ECE Paris",
    participation: "Ouverte à tous les étudiants",
    description: [
      "Venez participer à notre tournoi amical de Catan ! Que vous soyez un joueur expérimenté ou un débutant, cette soirée est l'occasion parfaite de vous affronter dans une ambiance conviviale.",
      "Inscriptions sur place. Des collations et boissons seront disponibles."
    ]
  },
  2: {
    type: "Soirée jeux",
    badgeClass: "badge badge-green",
    image: "assets/img/Soire_loup_garou.jpeg",
    titre: "Soirée Loup-Garou",
    date: "Vendredi 21 mars 2026",
    heure: "20h00 – 23h30",
    lieu: "Hall principal, Campus ECE Paris",
    participation: "Ouverte à tous",
    description: [
      "Grande soirée Loup-Garou avec plusieurs parties et une ambiance immersive.",
      "Venez avec vos amis pour une soirée pleine de bluff et de fun."
    ]
  },
  3: {
    type: "Occasionnel",
    badgeClass: "badge badge-purple",
    image: "assets/img/Escape_game_nuit.jpeg",
    titre: "Escape Game géant",
    date: "Samedi 22 mars 2026",
    heure: "14h00 – 17h00",
    lieu: "Amphithéâtre, Campus ECE Paris",
    participation: "Sur inscription",
    description: [
      "Participez à un escape game grandeur nature sur le campus.",
      "Énigmes, coopération et défi au programme."
    ]
  },
  4: {
    type: "Salle du jeudi",
    badgeClass: "badge badge-teal",
    image: "assets/img/Salles.jpeg",
    titre: "Salle ouverte",
    date: "Jeudi 13 mars 2026",
    heure: "12h00 – 14h00",
    lieu: "Salle B12, Campus ECE Paris",
    participation: "Libre d'accès",
    description: [
      "Venez jouer librement pendant la pause.",
      "Jeux variés disponibles sur place."
    ]
  },
  5: {
    type: "Jeu du jeudi",
    badgeClass: "badge badge-blue",
    image: "assets/img/le_jeu_azul.jpeg",
    titre: "Découverte : Azul",
    date: "Jeudi 27 mars 2026",
    heure: "18h00 – 20h30",
    lieu: "Salle A3, Campus ECE Paris",
    participation: "Ouverte à tous",
    description: [
      "Découvrez Azul dans une ambiance détendue.",
      "Présentation des règles au début."
    ]
  },
  6: {
    type: "Soirée jeux",
    badgeClass: "badge badge-green",
    image: "assets/img/gaming.jpeg",
    titre: "Nuit du jeu",
    date: "Vendredi 4 avril 2026",
    heure: "20h00 – 06h00",
    lieu: "Hall principal, Campus ECE Paris",
    participation: "Ouverte à tous les étudiants",
    description: [
      "Marathon gaming toute la nuit.",
      "Plusieurs espaces de jeux et animations prévues."
    ]
  }
};

const params = new URLSearchParams(window.location.search);
const id = params.get("id");
const event = evenements[id];

if (event) {
    document.getElementById("event-emoji").innerHTML = `
  <img src="assets/img/catan.jpg" alt="test">
`;
  document.getElementById("event-emoji").innerHTML = `<img src="${event.image}" alt="${event.titre}">`;

  document.getElementById("event-type").textContent = event.type;
  document.getElementById("event-type").className = event.badgeClass;
  document.getElementById("event-title").textContent = event.titre;
  document.getElementById("event-date").textContent = event.date;
  document.getElementById("event-time").textContent = event.heure;
  document.getElementById("event-location").textContent = event.lieu;
  document.getElementById("event-participation").textContent = event.participation;

  const descContainer = document.getElementById("event-description");
  event.description.forEach(paragraph => {
    const p = document.createElement("p");
    p.textContent = paragraph;
    p.style.color = "var(--text-light)";
    p.style.lineHeight = "1.8";
    p.style.marginTop = "12px";
    descContainer.appendChild(p);
  });
} else {
  document.querySelector(".section").innerHTML = `
    <h2>Événement introuvable</h2>
    <p>Cet événement n'existe pas.</p>
    <a href="evenements.php">Retour aux événements</a>
  `;
}