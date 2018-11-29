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

//Fonction permettant de générer une couleur aléatoire en hexa
	function randomColor(){
		$hex = '#';
		//Create a loop.
	foreach(array('r', 'g', 'b') as $color){
	    //Random number between 0 and 255.
	    $val = mt_rand(0, 255);
	    //Convert the random number into a Hex value.
	    $dechex = dechex($val);
	    //Pad with a 0 if length is less than 2.
	    if(strlen($dechex) < 2){
	        $dechex = "0" . $dechex;
	    }
	    //Concatenate
	    $hex .= $dechex;
	}
    return $hex;
}

//Fonction permettant de vérifier que le pseudonyme choisit par l'utilisateur est disponible au moment de l'inscription
function checkAvailability($pseudo, $link) {
	$req = "SELECT pseudo FROM humain WHERE pseudo = '" . $pseudo . "';";
	$ans = executeQuery($link, $req);
	return !(mysqli_fetch_assoc($ans));
}

//Fonction permettant d'enregistrer un nouvel utilisateur (pseudonyme, nom, prenom et mot de passe) dans la base de données
function register($pseudo, $nom, $prenom, $hashPwd, $link) {
	$req = "SELECT max(idJoueur) FROM joueur;";
	$ans = executeQuery($link, $req);
	foreach ($ans as $line) {
		foreach ($line as $val) {
			$nb = $val;
		}
	}
	if ($nb == NULL) {
		$nb = 1;
		$req = "ALTER TABLE joueur AUTO_INCREMENT = 1;";
		executeUpdate($link, $req);
	} else {
$nb++;
}
$color = randomColor();

$req = "INSERT INTO joueur (couleur) VALUES ('" . $color ."');";
executeUpdate($link, $req);

	$req = "INSERT INTO humain (pseudo, nomJ, prenomJ, dateCreationCompte, val_hachage, Joueur_idJoueur) VALUES ('". $pseudo . "', '" . $nom . "', '" . $prenom . "', CURRENT_TIMESTAMP , '" . $hashPwd . "', '" . $nb . "');";
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

//Fonction de retour de l'id du joueur connecté
function idJoueur0($pseudo, $link) {
	$req = "SELECT Joueur_idJoueur FROM humain WHERE pseudo = '" . $pseudo . "';";
	$ans = executeQuery($link, $req);
	foreach ($ans as $line) {
		foreach ($line as $val) {
			return $val;
		}
	}
}

//Fonction retournant le pseudo du joueur en jeu
function nomjoueur($id, $link) {
	$req = "SELECT pseudo FROM humain WHERE Joueur_idJoueur = '" . $id . "';";
	$ans = executeQuery($link, $req);
	foreach ($ans as $line) {
		foreach ($line as $val) {
			return $val;
		}
	}
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

//Partie

//Fonction permettant d'ajouter des joueurs dans une partie
function getPlayersInGame($link, $pseudo) {
	$req = "SELECT J.Joueur_idJoueur, J.pseudo FROM humain J WHERE J.pseudo != '$pseudo';";
	$ans = executeQuery($link, $req);

	$list = "";
	$playable = False;
	$errMessage = "<p>Il semble que vous soyez le seul joueur inscrit sur le jeu. Pour jouer il vous faut au moins une autre personne.</p>";
	foreach ($ans as $line) {
					$list .= "<input type='checkbox' name='player[]' value='$line[Joueur_idJoueur]'/><span class='entete'>$line[pseudo]</span><br/>";
					$playable = True;
}
	return ($playable ? $list : $errMessage);
}

//Jeu

//Fonction permettant de créer une partie dans la base de données et retourner son identifiant
function createNewGame($link, $players, $manche)
 {

	$req = "SELECT max(idPartie) FROM partie;";
	$ans = executeQuery($link, $req);
	foreach ($ans as $line) {
		foreach ($line as $val) {
			$nb = $val;
		}
	}
	if ($nb == NULL) {
		$nb = 1;
		$req = "ALTER TABLE Partie AUTO_INCREMENT = 1;";
		executeUpdate($link, $req);
	} else {
		$nb++;
	}
 $req = "INSERT INTO partie (nbManches, debutPartie) VALUES ('" . $manche . "', CURRENT_TIMESTAMP);";
	if (executeUpdate($link, $req)) {

		$i = 0;
		foreach ($players as $person) {
			$req = "INSERT INTO joue (Partie_idPartie, Joueur_idJoueur) VALUES ($nb, '" . $person . "');";
			$i++;
			executeUpdate($link, $req);
	}
}
	return $nb;
}

//Fonction permettant de créer une pioche via l'identifiant de la partie
function createNewDeck($link, $id, $style) {
	$req = "SELECT * FROM cartes WHERE codeC LIKE '$style%' ORDER BY RAND();";
	$ans = executeQuery($link, $req);
	$i = 0;
	while ($card = mysqli_fetch_array($ans)) {
			$i++;
			$req = "INSERT INTO jeu_carte (idJeu, CARTES_idC, Partie_idP) VALUES ($i, '". $card['idC'] ."', $id);";
			executeQuery($link, $req);
	}
}

?>
