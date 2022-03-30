-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 30 mars 2022 à 16:10
-- Version du serveur :  8.0.21
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `p5`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `libelle_cat` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf32;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `libelle_cat`) VALUES
(1, 'Language de Programmation'),
(2, 'Discord'),
(3, 'Autres');

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `comment` mediumtext NOT NULL,
  `comment_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `valid` tinyint(1) NOT NULL DEFAULT '0',
  `id_post` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_post` (`id_post`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf32;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `nom`, `prenom`, `comment`, `comment_date`, `valid`, `id_post`) VALUES
(2, 'test2', 'prentest2', 'magnifique commentaire222', '2022-02-22 14:09:24', 1, 3),
(3, 'Paris', 'Jean-chrsistophe', 'test', '2022-03-29 10:20:42', 0, 3);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `img` varchar(255) CHARACTER SET utf32 COLLATE utf32_general_ci DEFAULT NULL,
  `summary` varchar(150) NOT NULL,
  `content` longtext CHARACTER SET utf32 COLLATE utf32_general_ci,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_users` int NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '0',
  `id_cat` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_users` (`id_users`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf32;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id`, `title`, `img`, `summary`, `content`, `creation_date`, `id_users`, `actif`, `id_cat`) VALUES
(1, 'La magie du PHP', 'phplogo.png', 'la magie du PHP', '<p>PHP (officiellement, ce sigle est un acronyme récursif pour PHP Hypertext Preprocessor) est un langage de scripts généraliste et Open Source, spécialement conçu pour le développement d\'applications web. Il peut être intégré facilement au HTML</p>', '2022-02-15 14:39:58', 1, 1, 1),
(2, 'Vive Node JS', 'nodejslogo.png', 'Node.js® est un environnement d’exécution JavaScript construit sur le moteur JavaScript V8 de Chrome.', 'En tant qu\'environnement d\'exécution JavaScript asynchrone et orienté événement, Node.js est conçu pour générer des applications extensibles. Dans cet exemple (\"hello world\"), plusieures connexions peuvent être gérées de manière concurrente. À chaque connexion, la fonction de rappel (callback function) est déclenchée, mais si il n\'y a rien à faire, Node.js restera inactif.', '2022-02-13 14:39:58', 2, 1, 1),
(3, 'C#', 'csharplogo.png', 'C# (C sharp [siː.ʃɑːp] en anglais britannique) est un langage de programmation orientée objet, commercialisé par Microsoft depuis 20022 et destiné à d', '<p><strong>C# est un langage</strong> <strong>de programmation orientée</strong> objet, fortement typé, dérivé de C et de C++, ressemblant au langage Java2. Il est utilisé pour développer des applications web, ainsi que des applications de bureau, des services web, des commandes, des widgets ou des bibliothèques de classes2. En C#, une application est un lot de classes où une des classes comporte une méthode Main, comme cela se fait en Java2. C# est destiné à développer sur la plateforme .NET, une pile technologique créée par Microsoft pour succéder à COM. Les exécutables en C# sont subdivisés en assemblies, en namespaces, en classes et en membres de classe3. Un assembly est la forme compilée, qui peut être un programme (un exécutable) ou une bibliothèque de classes (une dll). Un assembly contient le code exécutable en MSIL, ainsi que les symboles. Le code MSIL est traduit en langage machine au moment de l\'exécution par la fonction just-in-time de la plateforme .NET</p>', '2022-02-14 17:09:21', 1, 1, 1),
(28, 'test', NULL, 'test', NULL, '2022-03-30 11:16:19', 1, 0, 1),
(29, 'fdsf', NULL, 'fdsfds', NULL, '2022-03-30 11:17:57', 1, 0, 2);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` int NOT NULL AUTO_INCREMENT,
  `libelle_role` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf32;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `libelle_role`) VALUES
(1, 'ROLE_ADMIN'),
(2, 'ROLE_CLIENT');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `validation_key` varchar(255) DEFAULT NULL,
  `id_role` int NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  KEY `id_role` (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf32;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `username`, `email`, `mdp`, `activated`, `validation_key`, `id_role`) VALUES
(1, 'Paris', 'Jean-chrsistophe', 'parisjc', 'jckparis38@gmail.com', '$2y$12$n8bnTtMLE23bgTpmWxzr/uJpfQq1zz9hZI2bnrXp5Ljk/O7uHzd5u', 1, NULL, 1),
(2, 'Dalleau', 'Pascal', 'dalleaup', '', '$2y$12$n8bnTtMLE23bgTpmWxzr/uJpfQq1zz9hZI2bnrXp5Ljk/O7uHzd5u', 1, NULL, 2),
(6, 'toto', 'toto', 'toto', 'toto@gmail.com', '$2y$10$IpTF30AiK4xWbygrFDgjgObnLbWUNFggJ586WZPwIrQecuHQ6UjLm', 1, 'ef1ed693db', 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
