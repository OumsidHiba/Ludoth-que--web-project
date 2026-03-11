<?php
include "includes/header.php";
?>
<main>
  <section class="section">
    <div class="section-divider"></div>
    <h2 class="section-title">Contactez-nous</h2>
    <p class="section-subtitle">Une question, une suggestion ? N'hésitez pas à nous écrire !</p>

    <div class="contact-grid">
      <!-- Infos -->
      <div>
        <h3 style="font-size:18px;font-weight:700;color:var(--navy);margin-bottom:16px">Nos coordonnées</h3>
        <div class="contact-info-card">
          <div class="contact-icon">📧</div>
          <div>
            <div style="font-size:12px;color:var(--text-light)">E-mail</div>
            <div style="font-size:15px;font-weight:600;color:var(--navy)">ludotheque@ece.fr</div>
          </div>
        </div>
        <div class="contact-info-card">
          <div class="contact-icon">📍</div>
          <div>
            <div style="font-size:12px;color:var(--text-light)">Adresse</div>
            <div style="font-size:14px;font-weight:600;color:var(--navy)">Campus ECE Paris</div>
            <div style="font-size:12px;color:var(--text-light)">10 Rue Sextius Michel, 75015 Paris</div>
          </div>
        </div>

        <h3 style="font-size:18px;font-weight:700;color:var(--navy);margin:24px 0 12px">Réseaux sociaux</h3>
        <div class="footer-social" style="display:flex;gap:10px">
          <a href="#" style="width:42px;height:42px;border-radius:10px;background:#E4405F;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px">IG</a>
          <a href="#" style="width:42px;height:42px;border-radius:10px;background:#5865F2;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px">DC</a>
          <a href="#" style="width:42px;height:42px;border-radius:10px;background:var(--teal);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px">TW</a>
        </div>

        <div style="margin-top:28px;padding:20px;background:rgba(26,26,62,.03);border-radius:12px;border:1px solid rgba(26,26,62,.06)">
          <h4 style="font-size:14px;font-weight:700;color:var(--navy);margin-bottom:8px">🕐 Horaires du bureau</h4>
          <p style="font-size:13px;color:var(--text-light);line-height:1.8">
            Lundi – Vendredi : 12h00 – 14h00<br>
            Jeudi : 12h00 – 20h00 (salle ouverte)<br>
            Fermé le week-end et pendant les vacances
          </p>
        </div>
      </div>

      <!-- Formulaire -->
      <div style="background:#fff;padding:32px;border-radius:12px;border:1px solid var(--border);box-shadow:var(--shadow)">
        <h3 style="font-size:18px;font-weight:700;color:var(--navy);margin-bottom:20px">Envoyez-nous un message</h3>
        <form>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Nom</label>
              <input type="text" class="form-input" placeholder="Votre nom">
            </div>
            <div class="form-group">
              <label class="form-label">Prénom</label>
              <input type="text" class="form-input" placeholder="Votre prénom">
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">E-mail</label>
            <input type="email" class="form-input" placeholder="votre.email@exemple.com">
          </div>
          <div class="form-group">
            <label class="form-label">Objet</label>
            <input type="text" class="form-input" placeholder="Sujet de votre message">
          </div>
          <div class="form-group">
            <label class="form-label">Message</label>
            <textarea class="form-textarea" placeholder="Écrivez votre message ici..."></textarea>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Envoyer le message</button>
        </form>
      </div>
    </div>
  </section>

</main>
<?php
include "includes/footer.php";    
?>

