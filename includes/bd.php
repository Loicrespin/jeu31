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

//Fonction permettant d'ajouter des joueurs dans une partie
function getIaInGame($link) {
	$req = "SELECT Joueur_idJoueur, strategie FROM ia;";
	$ans = executeQuery($link, $req);

	$list = "";
	$playable = False;
	$errMessage = "<p>Il semble que vous soyez le seul joueur inscrit sur le jeu. Pour jouer il vous faut au moins une autre personne.</p>";
	foreach ($ans as $line) {
					$list .= "<input type='checkbox' name='player[]' value='$line[Joueur_idJoueur]'/><span class='entete'>$line[strategie]</span><br/>";
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

//fonction metant fin a la partie
function endGame ($link, $idp, $vainqueur) {
			$req = "UPDATE partie SET vainqueurPartie = '" .$vainqueur. "', finPartie = CURRENT_TIMESTAMP  WHERE idPartie = ". $idp .";";
			executeUpdate($link, $req);
}

//fonction retournant le nom du vainqueur de la partie
function gameWinner($link, $idp) {
	$req = "SELECT vainqueurPartie FROM partie WHERE idPartie = " . $idp . ";";
	$ans = executeQuery($link, $req);
	foreach ($ans as $line) {
		foreach ($line as $val) {
			return $val;
		}
	}
}

//Fonction qui ajoute une nouvelle manche
function createNewSet($link, $id)
{
	$req = "SELECT max(idManche) FROM manche;";
	$ans = executeQuery($link, $req);
	foreach ($ans as $line) {
		foreach ($line as $val) {
			$nb = $val;
		}
	}
	if ($nb == NULL) {
		$nb = 1;
		$req = "ALTER TABLE manche AUTO_INCREMENT = 1;";
		executeUpdate($link, $req);
	} else {
		$nb++;
	}

	$req = "INSERT INTO manche (debutManche, Partie_idPartie) VALUES (CURRENT_TIMESTAMP, '" . $id . "');";
	executeUpdate($link, $req);

	return $nb;
}

//Fonction qui set le score du joueur1 d'une manche
function setMancheScoreJ1($link, $idm, $score) {
	$req = "UPDATE manche SET ScoreJ1 = $score WHERE idManche = ". $idm ."";
	executeUpdate($link, $req);
}

//Fonction qui set le score du joueur1 d'une manche
function setMancheScoreJ2($link, $idm, $score) {
	$req = "UPDATE manche SET ScoreJ2 = $score WHERE idManche = ". $idm ."";
	executeUpdate($link, $req);
}

//Retourne le nombre de manche gagné par un joeur sur une partie
function getTotalSetWinInGame($link, $joueur, $idp) {
		$req = "SELECT COUNT(idManche) FROM manche WHERE vainqueurManche = '".$joueur ."' AND Partie_idPartie = ". $idp."";
		$ans = executeUpdate($link, $req);
		foreach ($ans as $line) {
			foreach ($line as $val) {
				return $val;
			}
		}
}

function setEnding($link, $idm, $name) {
	$req = "UPDATE manche SET vainqueurManche =  '" . $name ."' , finManche = CURRENT_TIMESTAMP WHERE idManche = ". $idm .";";
	executeUpdate($link, $req);
}

//Fonction qui crée un nouveau tour
function addNewTurn($link, $idp, $idm)
{
	$req = "SELECT max(idTour) FROM tour;";
	$ans = executeQuery($link, $req);
	foreach ($ans as $line) {
		foreach ($line as $val) {
			$nb = $val;
		}
	}
	if ($nb == NULL) {
		$nb = 1;
		$req = "ALTER TABLE tour AUTO_INCREMENT = 1;";
		executeUpdate($link, $req);
	} else {
		$nb++;
	}

	$req = "INSERT INTO tour (Manche_idManche, Manche_Partie_idPartie) VALUES ('" . $idm . "', '" . $idp . "');";
	executeUpdate($link, $req);

	return $nb;
}

function addTurnScore($link, $score, $idt, $idp, $idm)
{
	$req = "UPDATE tour SET scoreTotal = $score  WHERE idTour = ". $idt ." AND Manche_idManche = '". $idm ."' AND Manche_Partie_idPartie = ". $idm .";";
	executeUpdate($link, $req);
}

//Fonction qui ajoute une action
function addAction($link, $action, $idt, $idm, $idp, $idj) {
	$req = "SELECT max(idAction) FROM action;";
	$ans = executeQuery($link, $req);
	foreach ($ans as $line) {
		foreach ($line as $val) {
			$nb = $val;
		}
	}
	if ($nb == NULL) {
		$nb = 1;
		$req = "ALTER TABLE action AUTO_INCREMENT = 1;";
		executeUpdate($link, $req);
	} else {
		$nb++;
	}

	$req = "INSERT INTO action (nomAction, Tour_idTour, Tour_Manche_idManche, Tour_Manche_Partie_idPartie, Joueur_idJoueur) VALUES ('" . $action . "', '" . $idt . "', '" . $idm . "', '" . $idp . "', '" . $idj . "');";
	executeUpdate($link, $req);
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

//Fonction permettant de créer une pioche via la d
function createNewDeckFromDeff($link, $id, $card) {
	$i = 0;
	foreach($card as $key => $value) {
			$i++;
			$req = "INSERT INTO jeu_carte (idJeu, CARTES_idC, Partie_idP) VALUES ($i, $value, $id) ORDER BY RAND();";
			executeQuery($link, $req);
		}
}

//Cette fonction retourne le type d'une carte piochéé et la supprime dans la bdd
function getCard($link, $id) {
	$req = "SELECT * FROM jeu_carte WHERE Partie_idP = $id ORDER BY RAND() LIMIT 1;";
	$ans = executeQuery($link, $req);
	if (mysqli_num_rows($ans) > 0) {
		$card = mysqli_fetch_array($ans);
		$req = "DELETE FROM jeu_carte WHERE idJeu = ". $card['idJeu'] ." AND Cartes_idC = '". $card['Cartes_idC'] ."' AND Partie_idP = ". $card['Partie_idP'] .";";
		executeQuery($link, $req);
		return $card['Cartes_idC'];
	} else {
		return "endDeck";
	}
}

//Fonction qui retourne le nom de la carte
function getCardName($link, $id) {
	$req = "SELECT nomC FROM cartes WHERE idC = '$id';";
	$ans = executeQuery($link, $req);
	foreach ($ans as $line) {
		foreach ($line as $val) {
			return $val;
		}
	}
}

//Retourne le code de la carte pour déterminer sa couleur
function getColorCard($link, $id) {
	$req = "SELECT codeC FROM cartes WHERE idC = '$id';";
	$ans = executeQuery($link, $req);
	foreach ($ans as $line) {
		foreach ($line as $val) {
			if(substr($val, -1) == "C" || substr($val, -1) == "K") {
				return "red";
			} else {
				return "black";
			}
		}
	}
}

//fonction qui retourne la valeur de la carte
function getScore($link, $id) {
		$req = "SELECT points FROM cartes WHERE idC = '$id';";
		$ans = executeQuery($link, $req);
		foreach ($ans as $line) {
			foreach ($line as $val) {
				return $val;
			}
		}
	}

function getImage($link, $id) {
	$req = "SELECT contenu FROM cartes WHERE idC = '$id';";
	$result = executeQuery($link, $req);
	$row = mysqli_fetch_array($result);

	echo '<input type="image" src="data:image/png;base64,'.base64_encode( $row['contenu']).'" name="'. $id .'" value="'. $id .'" />';

}

function getColor($link, $id) {
	$req = "SELECT couleur FROM joueur WHERE idJoueur = '$id';";
	$ans = executeQuery($link, $req);
	foreach ($ans as $line) {
		foreach ($line as $val) {
			return $val;
		}
	}
}
?>
