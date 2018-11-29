<?php
if (isset($_GET['action'])) {
	switch ($_GET['action']) {

	//Déconnexion
	case "deco": {
		$_SESSION = array();
		header("Location: index.php");
		break;
	}
	//FIN Déconnexion

	//Connexion
	case "co": {
		if (isset($_POST['submitC'])) {
			if (isset($_POST['pseudo']) && isset($_POST['pass'])) {
				$pseudo = $_POST['pseudo'];
				$pass = $_POST['pass'];
				if (checkConnection($pseudo, $pass, $link)) {
					$_SESSION['id_Joueur0'] = idJoueur0($pseudo, $link);
					$_SESSION['pseudo'] = $pseudo;
					header("Location: index.php");
				} else {
					header("Location: index.php?err=unknown");
				}
			} else {
				header("Location: index.php?err=notFullyFilled");
			}
		} else {
			header("Location: index.php");
		}
		break;
	}
	//FIN Connexion

	//Création d'une nouvelle partie
	case "newGame": {
		if (isset($_POST['submit'])) {
	$players = $_POST['player'];

	if (empty($players)) {
		header("Location: index.php?page=partie&err=empty");
	} else {
		if (count($players) > 2) {
			header("Location: index.php?page=partie&err=toomany");
		} else {
			for ($i = 0; $i < 4; $i++) {
				if (isset($_SESSION['player' . $i])) {
					unset($_SESSION['player' . $i]);
				}
			}

			$_SESSION['player0'] = $_SESSION['id_Joueur0'];
			$count = 1;
			foreach ($players as $val) {
				$keyPlayer = "player" . $count;
				$_SESSION[$keyPlayer] = $val;
				$count++;
			}

				$_SESSION['nbPlayers'] = $count;

			if (isset($_POST['manche'])) {
						$manche = $_POST['manche'];
					} else {
						$_SESSION['manche'] = 1;
					}

					if(isset($_POST['theme'])) {
						$style = $_POST['theme'];
					} else {

					}

			array_unshift($players, $_SESSION['player0']);
			$_SESSION['currPlayer'] = 0;
			$_SESSION['id'] = createNewGame($link, $players, $manche);
			createNewDeck($link, $_SESSION['id'], $style);
			header("Location: index.php?page=jeu&new=true");
		}
	}
} else {
	header("Location: index.php");
}
break;
	}

	//Tirage d'une carte
	case "card": {

		}

		//Defausse de carte
		case "defausse": {

		}

		//Cogner
		case "cogner": {

		}

	default:
		break;
	}
}
?>
