-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 15 Janvier 2017 à 12:06
-- Version du serveur :  5.7.11
-- Version de PHP :  5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `tickets`
--
CREATE DATABASE IF NOT EXISTS `tickets` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `tickets`;

-- --------------------------------------------------------

--
-- Structure de la table `donnees_abonnes`
--

CREATE TABLE `donnees_abonnes` (
  `id` int(11) NOT NULL,
  `compte` int(11) NOT NULL,
  `facture` int(11) NOT NULL,
  `abonne` int(11) NOT NULL,
  `date_heure` datetime NOT NULL,
  `duree_reel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `duree_facture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `donnees_abonnes`
--
ALTER TABLE `donnees_abonnes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `donnees_abonnes`
--
ALTER TABLE `donnees_abonnes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
