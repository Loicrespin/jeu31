<?php

$dbHost = "localhost";
$dbUser = "root";
$dbPwd = "";
$dbName = "p1710336";

//Interaction avec la base de données
//Fonction permettant à l'utilisateur de se connecter à la base de données et renvoyant false en cas d'erreur

function getConnection($dbHost, $dbUser, $dbPwd, $dbName) {
	$connexion = mysqli_connect($dbHost, $dbUser, $dbPwd, $dbName);
	if (mysqli_connect_errno()) {
		return False;
	} else {
		return $connexion;
	}
}

//Fonction permettant d'exécuter les requêtes SQL

function executeQuery($link, $query) {
	$send = mysqli_query($link, $query);
	if ($send === False) {
		echo "Error : " . $query . "<br />";
	}
	return $send;
}

//Fonction permettant de réaliser une insertion,une mise à jour ou une délétion dans la base de données

function executeUpdate($link, $query) {
	$send = mysqli_query($link, $query);
	if (!$send) {
		echo "Error : " . $query . "<br>";
	}
	return $send;
}

//Fonction permettant de fermer la connexion avec la base de données

function closeConnexion($link) {
	mysqli_close($link);
}

//Inscription et connexion

//Fonction permettant de vérifier que le pseudonyme choisit par l'utilisateur est disponible au moment de l'inscription

function checkAvailability($pseudo, $link) {
	$req = "SELECT pseudo FROM humain WHERE pseudo = '" . $pseudo . "';";
	$ans = executeQuery($link, $req);
	return !(mysqli_fetch_assoc($ans));
}

//Fonction permettant d'enregistrer un nouvel utilisateur (pseudonyme, nom, prenom et mot de passe) dans la base de données

function register($pseudo, $nom, $prenom, $hashPwd, $link) {
	$req = "INSERT INTO humain (pseudo, nomJ, prenomJ, dateCreationCompte, val_hachage, Joueur_idJoueur) VALUES ('". $pseudo . "', '" . $nom . "', '" . $prenom . "', CURRENT_TIMESTAMP , '" . $hashPwd . "', 1);";
	return executeUpdate($link, $req);
}

//Fonction permettant de vérifier le pseudonyme et le mot de passe de l'utilisateur

function checkConnection($pseudo, $pass, $link) {
	$req = "SELECT val_hachage FROM humain WHERE pseudo = '" . $pseudo . "';";
	$ans = executeQuery($link, $req);
	foreach ($ans as $line) {
		foreach ($line as $val) {
			return password_verify($pass, $val);
		}
	}
	return false;
}

//Accueil

//Fonction affichant le nombre de personnes inscrites sur la base de données

function getAllUsers($link) {
	$req = "SELECT count(pseudo) FROM humain;";
	$ans = executeQuery($link, $req);
	foreach ($ans as $line) {
		foreach ($line as $val) {
			return $val;
		}
	}
}


//Fonction affichant le nombre de parties jouées dans le mois

function getTotalGamesPlayedMonth($link) {
	$req = "SELECT count(idPartie) FROM partie WHERE debutPartie > DATE_SUB(CURDATE(), INTERVAL 1 MONTH);";
	$ans = executeQuery($link, $req);
	foreach ($ans as $line) {
		foreach ($line as $val) {
			return $val;
		}
	}
}



?>
