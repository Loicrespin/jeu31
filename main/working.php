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
		if (count($players) > 1) {
			header("Location: index.php?page=partie&err=toomany");
		} else {
			for ($i = 0; $i < 4; $i++) {
				if (isset($_SESSION['player' . $i])) {
					unset($_SESSION['player' . $i]);
				}
			}

			if (isset($_SESSION['card'])) {
					unset($_SESSION['card']);
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
						$_SESSION['manche'] = $manche;
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
			$_SESSION['id_manche'] = createNewSet($link, $_SESSION['id']);
			$_SESSION['id_tour'] = addNewTurn($link, $_SESSION['id'], $_SESSION['id_manche']);
			$_SESSION['piocher'] = 0;
			createNewDeck($link, $_SESSION['id'], $style);

			//SEt la main de départ
			for($i = 0; $i < 3; $i++) {
				$cartArray[$i] = getCard($link, $_SESSION['id']);
				$cartArray2[$i] = getCard($link, $_SESSION['id']);
			}

			$_SESSION['main'] = $cartArray;
			$_SESSION['main2'] = $cartArray2;

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
		if($_SESSION['piocher'] == 1) {
			header("Location: index.php?page=jeu&err=dejapioche");
		} else {
			$_SESSION['piocher'] = 1;
			$_SESSION['card'] = getCard($link, $_SESSION['id']);

			//Ajout de la carte en main
			if($_SESSION['currPlayer'] == 0) {
				 array_push($_SESSION['main'], $_SESSION['card']);
			} else {
				array_push($_SESSION['main2'], $_SESSION['card']);
			}

			addAction($link, "Pioche", 	$_SESSION['id_tour'], 	$_SESSION['id_manche'], 	$_SESSION['id'], 	$_SESSION['player' . $_SESSION['currPlayer']]);
			$ans = $_SESSION['card'];
			if ($ans == "endDeck") {
			header("Location: index.php?page=jeu&end=deck");
				exit;
			}
			header("Location: index.php?page=jeu&card=$ans");
	}
	break;
		}

		//Defausse de carte
		case "defausse": {
				
			break;
		}

		//Cogner
		case "cogner": {

			break;
		}

		//Fin du tour
		case "endturn": {
			if (count($_SESSION['main']) >= 3 || count($_SESSION['main2']) >= 3) {
				header("Location: index.php?page=jeu&err=manycard");
			} else {
			$_SESSION['piocher'] = 0;
			$start = $_SESSION['currPlayer'];
			$next = $start;
			$next = ($next + 1) % $_SESSION['nbPlayers'];
			$player = $_SESSION['player'. $next];
			$_SESSION['currPlayer'] = $next;

			$_SESSION['id_tour'] = addNewTurn($link, $_SESSION['id'], $_SESSION['id_manche']);

			header("Location: index.php?page=jeu");
		}
			break;
		}

	default:
		break;
	}
}
?>
