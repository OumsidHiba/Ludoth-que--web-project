<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "includes/session.php";
require_once "lib/phpmailer/PHPMailer.php";
require_once "lib/phpmailer/SMTP.php";
require_once "lib/phpmailer/Exception.php";

$message = "";
$messageType = "";

$nom = "";
$email = "";
$sujet = "";
$contenu = "";

if (isset($_SESSION["user_id"])) {
    $nom = trim(($_SESSION["prenom"] ?? "") . " " . ($_SESSION["nom"] ?? ""));
    $email = $_SESSION["email"] ?? "";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $sujet = trim($_POST["sujet"] ?? "");
    $contenu = trim($_POST["contenu"] ?? "");

    if (empty($nom) || empty($email) || empty($sujet) || empty($contenu)) {
        $message = "Tous les champs sont obligatoires.";
        $messageType = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Adresse e-mail invalide.";
        $messageType = "error";
    } else {
        $mail = new PHPMailer(true);

        try {
            $destinataire = "kakeuleslie@gmail.com";

            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "kakeuleslie@gmail.com";
            $mail->Password = "badc whby tqcl awim";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = "UTF-8";

            $mail->setFrom("kakeuleslie@gmail.com", "Site Ludothèque");
            $mail->addAddress($destinataire);
            $mail->addReplyTo($email, $nom);

            $mail->isHTML(false);
            $mail->Subject = "[Ludothèque] Nouveau message de contact : " . $sujet;
            $mail->Body =
"Nom : $nom

Email : $email

Sujet : $sujet

Message :
$contenu";

            $mail->send();

            $message = "Votre message a bien été envoyé. Nous vous répondrons dès que possible.";
            $messageType = "success";

            if (!isset($_SESSION["user_id"])) {
                $nom = "";
                $email = "";
            }

            $sujet = "";
            $contenu = "";
        } catch (Exception $e) {
            $message = "Erreur lors de l'envoi du message : " . $mail->ErrorInfo;
            $messageType = "error";
        }
    }
}

include "includes/header.php";
?>

<main class="contact-page">
    <div class="container">

        <section class="contact-hero">
            <div class="contact-hero-content">
                <h1>Contactez-nous</h1>
                <p>
                    Une question sur la ludothèque, les emprunts, les locations
                    ou les événements ? L’association est à votre écoute.
                </p>
            </div>
        </section>

        <section class="contact-grid">
            <div class="contact-card">
                <h2>Informations de contact</h2>
                <p class="contact-text">
                    Retrouvez ici les principaux moyens pour joindre l’association
                    et suivre son actualité.
                </p>

                <div class="contact-info-list">
                    <div class="contact-info-item">
                        <span class="contact-info-icon">📧</span>
                        <div>
                            <span class="contact-info-label">E-mail</span>
                            <span class="contact-info-value">ludotheque@ece.fr</span>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <span class="contact-info-icon">📍</span>
                        <div>
                            <span class="contact-info-label">Campus</span>
                            <span class="contact-info-value">ECE Paris</span>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <span class="contact-info-icon">📅</span>
                        <div>
                            <span class="contact-info-label">Événements</span>
                            <span class="contact-info-value">Salle du jeudi, Jeu du jeudi, soirées jeux</span>
                        </div>
                    </div>
                </div>

                <div class="contact-socials">
                    <a href="#" class="contact-social social-instagram">Instagram</a>
                    <a href="#" class="contact-social social-discord">Discord</a>
                    <a href="#" class="contact-social social-twitter">Réseau</a>
                </div>
            </div>

            <div class="contact-card">
                <h2>Formulaire de contact</h2>
                <p class="contact-text">
                    Utilisez ce formulaire pour nous envoyer directement votre message.
                </p>

                <?php if (!empty($message)): ?>
                    <div class="message <?= htmlspecialchars($messageType) ?>">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="contact-form">
                    <div class="form-group">
                        <label class="form-label" for="nom">Nom</label>
                        <input
                            type="text"
                            id="nom"
                            name="nom"
                            class="form-input"
                            value="<?= htmlspecialchars($nom) ?>"
                            placeholder="Votre nom"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Adresse e-mail</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-input"
                            value="<?= htmlspecialchars($email) ?>"
                            placeholder="votre.email@exemple.com"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="sujet">Sujet</label>
                        <input
                            type="text"
                            id="sujet"
                            name="sujet"
                            class="form-input"
                            value="<?= htmlspecialchars($sujet) ?>"
                            placeholder="Sujet de votre message"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="contenu">Message</label>
                        <textarea
                            id="contenu"
                            name="contenu"
                            class="form-textarea"
                            placeholder="Écrivez votre message ici..."
                            required
                        ><?= htmlspecialchars($contenu) ?></textarea>
                    </div>

                    <button type="submit" class="btn-main contact-btn">
                        Envoyer le message
                    </button>
                </form>
            </div>
        </section>

        <section class="contact-faq">
            <div class="contact-faq-card">
                <h2>Questions fréquentes</h2>

                <div class="contact-faq-list">
                    <div class="contact-faq-item">
                        <h3>Qui peut utiliser la ludothèque ?</h3>
                        <p>
                            Tous les utilisateurs disposant d’un compte peuvent consulter
                            le catalogue. Les services disponibles dépendent ensuite du
                            statut membre ou non-membre.
                        </p>
                    </div>

                    <div class="contact-faq-item">
                        <h3>Comment devenir membre ?</h3>
                        <p>
                            Le statut membre est attribué par l’association selon ses
                            modalités internes. Vous pouvez nous contacter pour en savoir plus.
                        </p>
                    </div>

                    <div class="contact-faq-item">
                        <h3>Comment suivre mes demandes ?</h3>
                        <p>
                            Une fois connecté, rendez-vous dans <strong>Mon Compte</strong>
                            pour suivre l’état de vos demandes et consulter votre historique.
                        </p>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>

<?php include "includes/footer.php"; ?>