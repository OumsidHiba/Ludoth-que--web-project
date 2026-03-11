<?php
// 1. Connexion à la base de données
$host = "localhost";
$dbname = "ludotheque";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// 2. Récupération des événements
// On trie par date pour que les plus proches apparaissent en premier
$stmt = $pdo->query("SELECT * FROM evenement ORDER BY date_evenement ASC");
$evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
      <?php foreach ($evenements as $event): 
          // Détermination de la couleur du badge selon le type
          $badgeClass = 'badge-teal';
          if (stripos($event['type'], 'Jeu') !== false) $badgeClass = 'badge-blue';
          if (stripos($event['type'], 'Soirée') !== false) $badgeClass = 'badge-green';
          if (stripos($event['type'], 'occasionnel') !== false) $badgeClass = 'badge-purple';
          
          // Formatage de l'heure (pour enlever les secondes si présentes)
          $heure = date("H\hi", strtotime($event['heure_evenement']));
          // Formatage de la date pour l'affichage
          setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
          $dateAffichage = strftime("%A %d %B %Y", strtotime($event['date_evenement']));
      ?>
      
      <div class="card" 
           data-type="<?php echo htmlspecialchars($event['type']); ?>" 
           data-date="<?php echo $event['date_evenement']; ?>">
        
        <div class="card-img-placeholder">
          <img src="assets/img/<?php echo htmlspecialchars($event['image']); ?>" 
               alt="<?php echo htmlspecialchars($event['titre']); ?>">
        </div>
        
        <div class="card-body">
          <span class="badge <?php echo $badgeClass; ?>">
            <?php echo htmlspecialchars($event['type']); ?>
          </span>
          
          <h3 class="card-title" style="margin-top:8px">
            <?php echo htmlspecialchars($event['titre']); ?>
          </h3>
          
          <p class="card-meta">📅 <?php echo ucwords($dateAffichage); ?> · <?php echo $heure; ?></p>
          <p class="card-meta">📍 <?php echo htmlspecialchars($event['lieu_evenement'] ?? 'Lieu non défini'); ?></p>
          
          <p class="card-meta" style="margin-top:8px">
            <?php echo htmlspecialchars($event['description_courte'] ?? 'Pas de description disponible.'); ?>
          </p>
          
          <a href="evenement-detail-v2.php?id=<?php echo $event['id_evenement']; ?>" class="btn btn-outline btn-sm">
            Voir détails →
          </a>
        </div>
      </div>
      
      <?php endforeach; ?>
    </div>
  </section>
</main>

<?php
include "includes/footer.php";
?>