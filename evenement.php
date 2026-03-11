<?php
include "includes/header.php";
?>

<main>
  <section class="section">
    <div class="section-divider"></div>
    <h2 class="section-title">Tous les événements</h2>
    <p class="section-subtitle">Découvrez les événements passés et à venir organisés par l'association</p>

    <div class="filters">

      <select id="filter-type" class="filter-select">
        <option value="">Type : Tous</option>
        <option value="Salle du jeudi">Salle du jeudi</option>
        <option value="Jeu du jeudi">Jeu du jeudi</option>
        <option value="Soirée jeux">Soirée jeux</option>
        <option value="Événement occasionnel">Événement occasionnel</option>
      </select>

      <select id="filter-date" class="filter-select">
        <option value="recent">Date : Plus récent</option>
        <option value="ancien">Date : Plus ancien</option>
      </select>

      <button id="filter-upcoming" class="btn btn-primary btn-sm">À venir</button>
      <button id="filter-past" class="btn btn-outline btn-sm">Passés</button>

    </div>
    <script src="assets/js/filtre-search.js"></script>
    <div class="grid grid-3" id="events-grid">
  <div class="card" data-type="Jeu du jeudi" data-date="2026-03-20">
    <div class="card-img-placeholder">
      <img src="assets/img/catan.jpg" alt="Catan">
    </div>
    <div class="card-body">
      <span class="badge badge-blue">Jeu du jeudi</span>
      <h3 class="card-title" style="margin-top:8px">Tournoi de Catan</h3>
      <p class="card-meta">📅 Jeudi 20 mars 2026 · 18h00</p>
      <p class="card-meta">📍 Salle A3</p>
      <p class="card-meta" style="margin-top:8px">Tournoi amical ouvert à tous les étudiants.</p>
      <a href="evenement-detail-v2.php?id=1" class="btn btn-outline btn-sm">Voir détails →</a>
    </div>
  </div>

  <div class="card" data-type="Soirée jeux" data-date="2026-03-21">
    <div class="card-img-placeholder">
      <img src="assets/img/Soire_loup_garou.jpeg" alt="loup garou">
    </div>
    <div class="card-body">
      <span class="badge badge-green">Soirée jeux</span>
      <h3 class="card-title" style="margin-top:8px">Soirée Loup-Garou</h3>
      <p class="card-meta">📅 Vendredi 21 mars 2026 · 20h00</p>
      <p class="card-meta">📍 Hall principal</p>
      <p class="card-meta" style="margin-top:8px">Grande soirée avec ambiance garantie !</p>
      <a href="evenement-detail-v2.php?id=2" class="btn btn-outline btn-sm">Voir détails →</a>
    </div>
  </div>

  <div class="card" data-type="Événement occasionnel" data-date="2026-03-22">
    <div class="card-img-placeholder">
      <img src="assets/img/Escape_game_nuit.jpeg" alt="escape game">
    </div>
    <div class="card-body">
      <span class="badge badge-purple">Événement occasionnel</span>
      <h3 class="card-title" style="margin-top:8px">Escape Game géant</h3>
      <p class="card-meta">📅 Samedi 22 mars 2026 · 14h00</p>
      <p class="card-meta">📍 Amphithéâtre</p>
      <p class="card-meta" style="margin-top:8px">Escape Game grandeur nature sur le campus.</p>
      <a href="evenement-detail-v2.php?id=3" class="btn btn-outline btn-sm">Voir détails →</a>
    </div>
  </div>

  <div class="card" data-type="Salle du jeudi" data-date="2026-03-13">
    <div class="card-img-placeholder">
      <img src="assets/img/Salles.jpeg" alt="salle de jeux">
    </div>
    <div class="card-body">
      <span class="badge badge-teal">Salle du jeudi</span>
      <h3 class="card-title" style="margin-top:8px">Salle ouverte</h3>
      <p class="card-meta">📅 Jeudi 13 mars 2026 · 12h00</p>
      <p class="card-meta">📍 Salle B12</p>
      <p class="card-meta" style="margin-top:8px">Venez jouer librement pendant la pause.</p>
      <a href="evenement-detail-v2.php?id=4" class="btn btn-outline btn-sm">Voir détails →</a>
    </div>
  </div>

  <div class="card" data-type="Jeu du jeudi" data-date="2026-03-27">
    <div class="card-img-placeholder">
      <img src="assets/img/le_jeu_azul.jpeg" alt="Azul">
    </div>
    <div class="card-body">
      <span class="badge badge-blue">Jeu du jeudi</span>
      <h3 class="card-title" style="margin-top:8px">Découverte : Azul</h3>
      <p class="card-meta">📅 Jeudi 27 mars 2026 · 18h00</p>
      <p class="card-meta">📍 Salle A3</p>
      <p class="card-meta" style="margin-top:8px">Venez découvrir ce jeu primé !</p>
      <a href="evenement-detail-v2.php?id=5" class="btn btn-outline btn-sm">Voir détails →</a>
    </div>
  </div>

  <div class="card" data-type="Soirée jeux" data-date="2026-04-04">
    <div class="card-img-placeholder">
      <img src="assets/img/gaming.jpeg" alt="nuit du jeu">
    </div>
    <div class="card-body">
      <span class="badge badge-green">Soirée jeux</span>
      <h3 class="card-title" style="margin-top:8px">Nuit du jeu</h3>
      <p class="card-meta">📅 Vendredi 4 avril 2026 · 20h00</p>
      <p class="card-meta">📍 Hall principal</p>
      <p class="card-meta" style="margin-top:8px">Marathon gaming toute la nuit !</p>
      <a href="evenement-detail-v2.php?id=6" class="btn btn-outline btn-sm">Voir détails →</a>
    </div>
  </div>
</div>
  </section>
</main>
<?php
include "includes/footer.php";
?>
