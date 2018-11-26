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
		if (isset($_POST['submit'])) {
			$players = $_POST['player'];
			if (empty($players)) {
				header("Location: index.php?page=partie&err=empty");
			} else {
				if (count($players) > 3) {
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
					$_SESSION['player0'] = $_SESSION['pseudo'];
					$count = 1;
					foreach ($players as $val) {
						$keyPlayer = "player" . $count;
						$_SESSION[$keyPlayer] = $val;
						$count++;
					}
					$_SESSION['nbPlayers'] = $count;

					for ($i = 0; $i < 4; $i++) {
						if (isset($_SESSION['color' . $i])) {
							unset($_SESSION['color' . $i]);
						}
					}
					$colors = array();
					$notSetColors = array();
					for ($i = 0; $i < 4; $i++) {
						$keyColor = "color" . $i;
						if (isset($_POST[$keyColor])) {
							array_push($colors, $_POST[$keyColor]);
						}
					}
					$allColors = ['blue', 'purple', 'red', 'yellow'];
					foreach ($allColors as $val) {
						$nb = count(array_keys($colors, $val));
						if ($nb > 1) {
							header("Location: index.php?page=partie&err=samecolor");
							exit;
						} else if ($nb == 0) {
							array_push($notSetColors, $val);
						}
					}
					$nbNotSet = $_SESSION['nbPlayers'] - count($colors);
					$rand = array();
					for ($i = 0; $i < $nbNotSet; $i++) {
						$alea;
						do {
							$alea = rand(0, count($notSetColors) -1);
						} while (in_array($alea, $rand));
						array_push($rand, $alea);
						array_push($colors, $notSetColors[$alea]);
					}
					for ($i = 0; $i < count($colors); $i++) {
						$keyColor = "color" . $i;
						$_SESSION[$keyColor] = $colors[$i];
					}

					if (isset($_POST['size'])) {
						$_SESSION['dim'] = intval($_POST['size']);
					} else {
						$_SESSION['dim'] = 5;
					}
					array_unshift($players, $_SESSION['player0']);
					$_SESSION['currPlayer'] = 0;
					$_SESSION['id'] = createNewGame($link, $players, $colors, $_SESSION['dim']);
                    createNewPawn($link, $players, $colors, $_SESSION['id']);
					createNewDeck($link, $_SESSION['id']);
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
		$_SESSION['card'] = getCard($link, $_SESSION['id']);
		$ans = $_SESSION['card'];
		if ($ans == "endDeck") {
			header("Location: index.php?page=jeu&end=deck");
			exit;
		} else {
			if ($_SESSION['currPlayer'] == 0) {
				addTurn($link, $_SESSION['id']);
			}
			addAction($link, $ans, $_SESSION['currPlayer'], $_SESSION['id']);
			header("Location: index.php?page=jeu&card=$ans");
		}
		break;
	}

	//Déplacement d'un pion
	case "pawn": {
		if (isset($_POST['submit'])) {
			$pawn = $_POST['submit'];
		    $nb = substr($_SESSION['card'], 12, 1);
		    $nbCases = intval($nb);
		    $gameOver = deplacement($link, $nbCases, $pawn, $_SESSION['id']);
			if ($gameOver === True) {
				header("Location: index.php?page=jeu&end=" . $_SESSION['currPlayer']);
			} else {
				$start = $_SESSION['currPlayer'];
				$next = $start;
				$loop = True;
				do {
					$next = ($next + 1) % $_SESSION['nbPlayers'];
					$player = $_SESSION['player'. $next];
					$pawns = getAvaiblePawns($link, $player, $_SESSION['id']);
					if (!empty($pawns)) {
						$loop = False;
						$_SESSION['currPlayer'] = $next;
					} else {
						if ($start == $next) {
							$loop = False;
							header("Location: index.php?page=jeu&end=pawns");
							exit;
						}
					}
				} while ($loop);
				header("Location: index.php?page=jeu$gameOver");
				exit;
			}
		}
		break;
	}

	//Activation d'une carte spéciale
	case "activate": {
		$happened = "";
		if (isset($_POST['submit'])) {
			$player = $_SESSION['player' . $_SESSION['currPlayer'] ];

			switch ($_POST['submit']) {
				case "earthquake": {
					$happened = earthquake($link, $_SESSION['id']);
					break;
				}
				case "black_magician": {
					$happened = black_magician($link, $player, $_SESSION['id']);
					break;
				}
				case "warrior": {
					$happened = warrior($link, $player, $_SESSION['id']);
					break;
				}
                case "oeuf_chance": {
                    $happened = oeuf_chance($link, $_SESSION['id']);
                    break;
                }
                case "oeuf_malchance": {
                    $happened = oeuf_malchance($link, $_SESSION['id']);
                    break;
                }
                case "magician": {
                    $happened = magician($link, $_SESSION['id']);
                    break;
                }
				default:
					break;
			}
			//Gestion de l'absence des pions
			$start = $_SESSION['currPlayer'];
			$next = $start;
			$loop = True;
			do {
				$next = ($next + 1) % $_SESSION['nbPlayers'];
				$player = $_SESSION['player'. $next];
				$pawns = getAvaiblePawns($link, $player, $_SESSION['id']);
				if (!empty($pawns)) {
					$loop = False;
					$_SESSION['currPlayer'] = $next;
				} else {
					if ($start == $next) {
						$loop = False;
						header("Location: index.php?page=jeu&end=pawns");
						exit;
					}
				}
			} while ($loop);
		}
		header("Location: index.php?page=jeu$happened");
		exit;
		break;
	}
	default:
		break;
	}
}
?>
