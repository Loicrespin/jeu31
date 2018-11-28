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
