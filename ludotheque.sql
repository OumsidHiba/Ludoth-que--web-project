-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 11 mars 2026 à 13:52
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ludotheque`
--

-- --------------------------------------------------------

--
-- Structure de la table `demande`
--

DROP TABLE IF EXISTS `demande`;
CREATE TABLE IF NOT EXISTS `demande` (
  `id_demande` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int DEFAULT NULL,
  `id_jeu` int DEFAULT NULL,
  `type_demande` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_demande` date DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  PRIMARY KEY (`id_demande`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_jeu` (`id_jeu`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `demande`
--

INSERT INTO `demande` (`id_demande`, `id_utilisateur`, `id_jeu`, `type_demande`, `statut`, `date_demande`, `date_debut`, `date_fin`) VALUES
(1, 4, 1, 'emprunt', 'validée', '2026-03-08', '2026-03-09', '2026-03-23'),
(2, 3, 4, 'location', 'validée', '2026-03-08', '2026-03-10', '2026-03-17'),
(3, 2, 5, 'location', 'en attente', '2026-03-09', '2026-03-12', '2026-03-19'),
(4, 1, 3, 'location', 'refusée', '2026-03-09', '2026-03-13', '2026-03-20'),
(5, 4, 2, 'emprunt', 'en attente', '2026-03-10', '2026-03-14', '2026-03-21'),
(6, 5, 7, 'emprunt', 'validée', '2026-03-10', '2026-03-11', '2026-03-18'),
(7, 4, 2, 'emprunt', 'refusée', '2026-03-11', '2026-03-13', '2026-03-20'),
(8, 5, 6, 'emprunt', 'validée', '2026-03-11', '2026-03-16', '2026-03-19');

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

DROP TABLE IF EXISTS `evenement`;
CREATE TABLE IF NOT EXISTS `evenement` (
  `id_evenement` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default.jpg',
  `date_evenement` date DEFAULT NULL,
  `heure_evenement` time DEFAULT NULL,
  `lieu` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `categorie` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_evenement`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `evenement`
--

INSERT INTO `evenement` (`id_evenement`, `titre`, `image`, `date_evenement`, `heure_evenement`, `lieu`, `description`, `categorie`) VALUES
(1, 'Salle ouverte du jeudi', 'Salles.jpeg', '2026-03-12', '18:00:00', 'Salle SC 215', 'La salle de jeux est ouverte à tous les étudiants pour des parties libres.', 'Salle du jeudi'),
(2, 'Jeu du jeudi - Tournoi Catan', 'catan.jpg', '2026-03-19', '18:30:00', 'Salle EM 220', 'Tournoi convivial autour du jeu Catan, sur inscription.', 'Jeu du jeudi'),
(3, 'Soirée jeux spéciale Loup-Garou', 'Soire_loup_garou.jpeg', '2026-03-21', '20:00:00', 'Salle SC 210', 'Grande soirée d’animation autour des jeux d’ambiance et de bluff.', 'Soirée jeux'),
(4, 'Escape Game géant', 'Escape_game_nuit.jpeg', '2026-03-28', '19:00:00', 'Salle EM 115', 'Événement exceptionnel organisé par l’association pour tous les étudiants.', 'Événement occasionnel'),
(5, 'Jeu du jeudi - Découverte Dixit', 'le_jeu_azul.jpeg', '2026-04-02', '18:00:00', 'Salle EM 009', 'Session découverte de Dixit avec animation par les membres du bureau.', 'Jeu du jeudi'),
(6, 'Tournoi de Uno', 'gaming.jpeg', '2026-03-27', '15:30:00', 'Cantine', 'Venez participer au grand tournoi de uno organisé par le BDE. Pleins de prix à gagner !!!', 'Événement occasionnel'),
(7, 'Tournoi de cartes', 'gaming.jpeg', '2026-03-27', '17:00:00', 'EM 220', 'Venez participer à notre tournoi de cartes organisé entre les ING3. On a hâte de vous revoir !', 'Événement occasionnel');

-- --------------------------------------------------------

--
-- Structure de la table `jeu`
--

DROP TABLE IF EXISTS `jeu`;
CREATE TABLE IF NOT EXISTS `jeu` (
  `id_jeu` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temps_jeu_moyen` int DEFAULT NULL,
  `nb_joueurs_min` int DEFAULT NULL,
  `nb_joueurs_max` int DEFAULT NULL,
  `difficulte_apprentissage` int DEFAULT NULL,
  `difficulte_jeu` int DEFAULT NULL,
  `statut` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regles` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_jeu`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `jeu`
--

INSERT INTO `jeu` (`id_jeu`, `nom`, `temps_jeu_moyen`, `nb_joueurs_min`, `nb_joueurs_max`, `difficulte_apprentissage`, `difficulte_jeu`, `statut`, `regles`, `description`, `image`) VALUES
(1, 'Catan', 75, 3, 4, 2, 3, 'en stock', 'Règles de placement et commerce.', 'Jeu de stratégie et de gestion de ressources.', 'catan.jpg'),
(2, 'Uno', 20, 2, 10, 1, 1, 'en stock', 'Associer couleurs et chiffres.', 'Jeu de cartes rapide et convivial.', 'uno.jpg'),
(3, 'Pandemic', 45, 2, 4, 2, 3, 'emprunté', 'Jeu coopératif contre les maladies.', 'Jeu coopératif de gestion de crise.', 'pandemic.jpg'),
(5, 'Dixit', 30, 3, 6, 1, 2, 'indisponible', 'Faire deviner une carte illustrée.', 'Jeu créatif basé sur l’imagination.', 'dixit.jpg'),
(6, 'Action-verité', 30, 5, 10, 1, 1, 'emprunté', 'juste dire la vérité', 'jouer en groupe de 2', 'jeu_69b15aac536bf.png');

-- --------------------------------------------------------

--
-- Structure de la table `occuper`
--

DROP TABLE IF EXISTS `occuper`;
CREATE TABLE IF NOT EXISTS `occuper` (
  `id_utilisateur` int NOT NULL,
  `id_role` int NOT NULL,
  `date_debut_mandat` date DEFAULT NULL,
  `date_fin_mandat` date DEFAULT NULL,
  PRIMARY KEY (`id_utilisateur`,`id_role`),
  KEY `id_role` (`id_role`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `occuper`
--

INSERT INTO `occuper` (`id_utilisateur`, `id_role`, `date_debut_mandat`, `date_fin_mandat`) VALUES
(6, 1, '2026-01-01', '2026-12-31'),
(5, 5, '2026-01-01', '2026-12-31'),
(4, 3, '2026-01-01', '2026-12-31');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `id_reservation` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int DEFAULT NULL,
  `id_jeu` int DEFAULT NULL,
  `id_evenement` int DEFAULT NULL,
  `date_reservation` date DEFAULT NULL,
  `statut` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_reservation`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_jeu` (`id_jeu`),
  KEY `id_evenement` (`id_evenement`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`id_reservation`, `id_utilisateur`, `id_jeu`, `id_evenement`, `date_reservation`, `statut`) VALUES
(1, 4, 1, 2, '2026-03-11', 'validée'),
(2, 3, 2, 2, '2026-03-11', 'en attente'),
(3, 1, 2, 5, '2026-03-20', 'validée'),
(4, 4, 1, NULL, '2026-03-12', 'en attente');

-- --------------------------------------------------------

--
-- Structure de la table `role_bureau`
--

DROP TABLE IF EXISTS `role_bureau`;
CREATE TABLE IF NOT EXISTS `role_bureau` (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `libelle_role` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role_bureau`
--

INSERT INTO `role_bureau` (`id_role`, `libelle_role`) VALUES
(1, 'utilisateur'),
(2, 'utilisateur'),
(3, 'utilisateur'),
(4, 'utilisateur'),
(5, 'admin'),
(6, 'president');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut_membre` tinyint(1) NOT NULL DEFAULT '0',
  `role` enum('utilisateur','admin','president') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'utilisateur',
  `date_inscription` date DEFAULT NULL,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `email`, `mot_de_passe`, `statut_membre`, `role`, `date_inscription`) VALUES
(1, 'Kakeu', 'Leslie', 'kakeuleslie@gmail.com', '$2y$10$SsBxcDRPpenSHX4xvOXDUeXJZT8QLDAI.mMKXIO4Hpkqn5FWOjN8i', 0, 'utilisateur', '2026-03-01'),
(2, 'Hiba', 'Oumsid', 'hibaoumsid@gmail.com', '$2y$10$tAujJsTQBhCkT7GlDqVMaO6jgXC1u8whaV4toIlBvJe0lnODGRL2u', 0, 'utilisateur', '2026-03-02'),
(3, 'moi', 'toi', 'moi@gmail.com', '$2y$10$/cr9JqghALaJNBIF5EAr8.DbeeXGe9kgo47s198TfE9XXJ3Z6bGi6', 0, 'utilisateur', '2026-03-03'),
(4, 'membre', 'test', 'membretest@gmail.com', '$2y$10$Owi5I5F.8NvZwDe3XSe9iOT6kr3JT3EyCoDRPpqVJ7UAee9lJBSmC', 1, 'utilisateur', '2026-03-04'),
(5, 'admin', 'test', 'admintest@gmail.com', '$2y$10$VePL0oGHSM6Wa4qd.yVE8epwU6HLPhFLI3Te57yNfnD0WjkNIRW/y', 1, 'admin', '2026-03-05'),
(6, 'president', 'test', 'presitest@gmail.com', '$2y$10$SkV4xikOET5QyrmskB.u0uLDN6oj0wZk1zCDv7nkSwhMHSuPxTZ.S', 1, 'president', '2026-03-06'),
(7, 'Annaelle', 'Meka', 'annaellemeka@gmail.com', '$2y$10$TDSt8dCAGd52cAL6frQOe.6GEBlj.pvHKG/wfVReUBbybylXo1QBW', 0, 'utilisateur', '2026-03-11');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
