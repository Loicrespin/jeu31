-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 09 déc. 2018 à 18:51
-- Version du serveur :  5.7.23
-- Version de PHP :  7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `p1710336`
--

-- --------------------------------------------------------

--
-- Structure de la table `action`
--

DROP TABLE IF EXISTS `action`;
CREATE TABLE IF NOT EXISTS `action` (
  `idAction` int(11) NOT NULL AUTO_INCREMENT,
  `nomAction` varchar(45) DEFAULT NULL,
  `Tour_idTour` int(11) NOT NULL,
  `Tour_Manche_idManche` int(11) NOT NULL,
  `Tour_Manche_Partie_idPartie` int(11) NOT NULL,
  `Joueur_idJoueur` int(11) NOT NULL,
  PRIMARY KEY (`idAction`,`Tour_idTour`,`Tour_Manche_idManche`,`Tour_Manche_Partie_idPartie`),
  KEY `fk_Action_Tour1_idx` (`Tour_idTour`,`Tour_Manche_idManche`,`Tour_Manche_Partie_idPartie`),
  KEY `fk_Action_Joueur1_idx` (`Joueur_idJoueur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `cartes`
--

DROP TABLE IF EXISTS `cartes`;
CREATE TABLE IF NOT EXISTS `cartes` (
  `idC` int(11) NOT NULL,
  `codeC` varchar(45) NOT NULL,
  `nomC` varchar(255) NOT NULL,
  `contenu` mediumblob,
  `points` int(3) NOT NULL,
  PRIMARY KEY (`idC`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `humain`
--

DROP TABLE IF EXISTS `humain`;
CREATE TABLE IF NOT EXISTS `humain` (
  `pseudo` varchar(20) NOT NULL,
  `nomJ` varchar(45) DEFAULT NULL,
  `prenomJ` varchar(45) DEFAULT NULL,
  `dateCreationCompte` datetime DEFAULT NULL,
  `val_hachage` varchar(100) DEFAULT NULL,
  `Joueur_idJoueur` int(11) NOT NULL,
  PRIMARY KEY (`pseudo`,`Joueur_idJoueur`),
  KEY `fk_Joueur_H_Joueur1_idx` (`Joueur_idJoueur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ia`
--

DROP TABLE IF EXISTS `ia`;
CREATE TABLE IF NOT EXISTS `ia` (
  `strategie` varchar(20) NOT NULL,
  `chanceCogner` int(2) DEFAULT NULL,
  `chancePiocher` int(2) DEFAULT NULL,
  `chanceFinTour` int(3) DEFAULT NULL,
  `Joueur_idJoueur` int(11) NOT NULL,
  PRIMARY KEY (`strategie`,`Joueur_idJoueur`),
  KEY `fk_IA_Joueur1_idx` (`Joueur_idJoueur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `jeu_carte`
--

DROP TABLE IF EXISTS `jeu_carte`;
CREATE TABLE IF NOT EXISTS `jeu_carte` (
  `idJeu` int(11) NOT NULL AUTO_INCREMENT,
  `Cartes_idC` int(11) NOT NULL,
  `Partie_idP` int(11) NOT NULL,
  PRIMARY KEY (`idJeu`,`Cartes_idC`,`Partie_idP`),
  KEY `fk_JeuDeCarte_Cartes_idx` (`Cartes_idC`),
  KEY `fk_JeuDeCarte_Partie1_idx` (`Partie_idP`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `joue`
--

DROP TABLE IF EXISTS `joue`;
CREATE TABLE IF NOT EXISTS `joue` (
  `Partie_idPartie` int(11) NOT NULL AUTO_INCREMENT,
  `Joueur_idJoueur` int(11) NOT NULL,
  PRIMARY KEY (`Partie_idPartie`,`Joueur_idJoueur`),
  KEY `fk_joue_Joueur1_idx` (`Joueur_idJoueur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `joueur`
--

DROP TABLE IF EXISTS `joueur`;
CREATE TABLE IF NOT EXISTS `joueur` (
  `idJoueur` int(11) NOT NULL AUTO_INCREMENT,
  `couleur` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idJoueur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `manche`
--

DROP TABLE IF EXISTS `manche`;
CREATE TABLE IF NOT EXISTS `manche` (
  `idManche` int(11) NOT NULL AUTO_INCREMENT,
  `debutManche` datetime DEFAULT NULL,
  `finManche` datetime DEFAULT NULL,
  `vainqueurManche` varchar(45) DEFAULT NULL,
  `ScoreJ1` int(5) DEFAULT NULL,
  `ScoreJ2` int(5) DEFAULT NULL,
  `Partie_idPartie` int(11) NOT NULL,
  PRIMARY KEY (`idManche`,`Partie_idPartie`),
  KEY `fk_Manche_Partie1_idx` (`Partie_idPartie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `partie`
--

DROP TABLE IF EXISTS `partie`;
CREATE TABLE IF NOT EXISTS `partie` (
  `idPartie` int(11) NOT NULL AUTO_INCREMENT,
  `vainqueurPartie` varchar(45) DEFAULT NULL,
  `nbManches` int(2) DEFAULT NULL,
  `debutPartie` datetime DEFAULT NULL,
  `finPartie` datetime DEFAULT NULL,
  PRIMARY KEY (`idPartie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tour`
--

DROP TABLE IF EXISTS `tour`;
CREATE TABLE IF NOT EXISTS `tour` (
  `idTour` int(11) NOT NULL AUTO_INCREMENT,
  `scoreTotal` int(5) DEFAULT NULL,
  `Manche_idManche` int(11) NOT NULL,
  `Manche_Partie_idPartie` int(11) NOT NULL,
  PRIMARY KEY (`idTour`,`Manche_idManche`,`Manche_Partie_idPartie`),
  KEY `fk_Tour_Manche1_idx` (`Manche_idManche`,`Manche_Partie_idPartie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `action`
--
ALTER TABLE `action`
  ADD CONSTRAINT `fk_Action_Joueur1` FOREIGN KEY (`Joueur_idJoueur`) REFERENCES `joueur` (`idJoueur`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Action_Tour1` FOREIGN KEY (`Tour_idTour`,`Tour_Manche_idManche`,`Tour_Manche_Partie_idPartie`) REFERENCES `tour` (`idTour`, `Manche_idManche`, `Manche_Partie_idPartie`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `humain`
--
ALTER TABLE `humain`
  ADD CONSTRAINT `fk_Joueur_H_Joueur1` FOREIGN KEY (`Joueur_idJoueur`) REFERENCES `joueur` (`idJoueur`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `ia`
--
ALTER TABLE `ia`
  ADD CONSTRAINT `fk_IA_Joueur1` FOREIGN KEY (`Joueur_idJoueur`) REFERENCES `joueur` (`idJoueur`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `jeu_carte`
--
ALTER TABLE `jeu_carte`
  ADD CONSTRAINT `fk_JeuDeCarte_Cartes` FOREIGN KEY (`Cartes_idC`) REFERENCES `cartes` (`idC`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_JeuDeCarte_Partie1` FOREIGN KEY (`Partie_idP`) REFERENCES `partie` (`idPartie`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `joue`
--
ALTER TABLE `joue`
  ADD CONSTRAINT `fk_joue_Joueur1` FOREIGN KEY (`Joueur_idJoueur`) REFERENCES `joueur` (`idJoueur`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_joue_Partie1` FOREIGN KEY (`Partie_idPartie`) REFERENCES `partie` (`idPartie`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `manche`
--
ALTER TABLE `manche`
  ADD CONSTRAINT `fk_Manche_Partie1` FOREIGN KEY (`Partie_idPartie`) REFERENCES `partie` (`idPartie`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `tour`
--
ALTER TABLE `tour`
  ADD CONSTRAINT `fk_Tour_Manche1` FOREIGN KEY (`Manche_idManche`,`Manche_Partie_idPartie`) REFERENCES `manche` (`idManche`, `Partie_idPartie`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
