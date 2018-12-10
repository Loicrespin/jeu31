<?php if (isset($_GET['action'])) {
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
			if(isset($_POST['player'])) {
					$players = $_POST['player'];
					$_SESSION['isIA'] = false;
			} else if(isset($_POST['ia'])){
				$players = $_POST['ia'];
				$_SESSION['isIA'] = true;
			} else if(isset($_POST['chanceCogner']) && isset($_POST['chancePiocher']) && isset($_POST['chanceFinTour'])) {
			createIa($link, $_POST['nomIa'], $_POST['chanceCogner'], $_POST['chancePiocher'], $_POST['chanceFinTour']);
			header("Location: index.php?page=partie");
			}



	if (empty($players)) {
		header("Location: index.php?page=partie&err=empty");
	} else {
		if (count($players) < 1) {
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
						header("Location: index.php?page=partie&err=emptyset");
					}

					if(isset($_POST['theme'])) {
						$_SESSION['style'] = $_POST['theme'];
					} else {

					}

			array_unshift($players, $_SESSION['player0']);
			$_SESSION['currManche'] = 1;
			$_SESSION['currPlayer'] = 0;
			$_SESSION['id'] = createNewGame($link, $players, $manche);
			$_SESSION['id_manche'] = createNewSet($link, $_SESSION['id']);
			$_SESSION['id_tour'] = addNewTurn($link, $_SESSION['id'], $_SESSION['id_manche']);
			$_SESSION['piocher'] = 0;
			$_SESSION['alreadycogne'] = false;
			createNewDeck($link, $_SESSION['id'], $_SESSION['style']);

			//SEt la main de départ
			for($i = 0; $i < 3; $i++) {
				$cartArray[$i] = getCard($link, $_SESSION['id']);
				$cartArray2[$i] = getCard($link, $_SESSION['id']);
			}

			$_SESSION['main'] = $cartArray;
			$_SESSION['main2'] = $cartArray2;

			$_SESSION['eventAction'] ="";
		  $_SESSION['Iaaction'] = "none";
			$_SESSION['defausse'] = 0;
			$_SESSION['deckdefausse'] = array();
			array_push($_SESSION['deckdefausse'],  getCard($link, $_SESSION['id']));
								if(calculScoreRED($link, $_SESSION['main']) >= calculScoreBLACK($link, $_SESSION['main'])) {
									$score = calculScoreRED($link, $_SESSION['main']);
										$_SESSION['bestscore1'] = $score;
								} else {
									$score = calculScoreBLACK($link, $_SESSION['main']);
										$_SESSION['bestscore1'] = $score;
								}

							if(calculScoreRED($link, $_SESSION['main2']) >= calculScoreBLACK($link, $_SESSION['main2'])) {
								$score = calculScoreRED($link, $_SESSION['main2']);
									$_SESSION['bestscore2'] = $score;
							} else {
								$score = calculScoreBLACK($link, $_SESSION['main2']);
										$_SESSION['bestscore2'] = $score;
							}

						addTurnScore($link, $score, $_SESSION['id_tour'], $_SESSION['id'], $_SESSION['id_manche']);

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
			$ans = $_SESSION['card'];

			if ($ans == "endDeck") {
			createNewDeckFromDeff($link, $_SESSION['id'], $_SESSION['deckdefausse']);
			header("Location: index.php?page=jeu&card=$ans");
			}

			//Ajout de la carte en main
			if($_SESSION['currPlayer'] == 0) {
				 array_push($_SESSION['main'], $_SESSION['card']);
			} else {
				array_push($_SESSION['main2'], $_SESSION['card']);
			}

			addAction($link, "Pioche", 	$_SESSION['id_tour'], 	$_SESSION['id_manche'], 	$_SESSION['id'], 	$_SESSION['player' . $_SESSION['currPlayer']]);

			header("Location: index.php?page=jeu&card=$ans");
	 }
			break;
		}

		//Defausse de carte
		case "defausse": {
			if($_SESSION['defausse'] == 0) {
				header("Location: index.php?page=jeu&err=notselect");
			} else if (count($_SESSION['main']) == 3 && count($_SESSION['main2']) == 3) {
					header("Location: index.php?page=jeu&err=dejadefausse");
			} else {
				$_SESSION['defausselast'] = $_SESSION['defausse'];
				//supression de la carte en main
				if($_SESSION['currPlayer'] == 0) {
					array_splice($_SESSION['main'], array_search($_SESSION['defausse'], $_SESSION['main']),1);

				} else {
					array_splice($_SESSION['main2'], array_search($_SESSION['defausse'], $_SESSION['main2']),1);

				}

				 		array_push($_SESSION['deckdefausse'],  $_SESSION['defausse']);
						addAction($link, "Defausse", 	$_SESSION['id_tour'], 	$_SESSION['id_manche'], 	$_SESSION['id'], 	$_SESSION['player' . $_SESSION['currPlayer']]);

						$ans = $_SESSION['defausse'];

						header("Location: index.php?page=jeu&defausse=$ans");
					}
			break;
		}

		//Piocher dans la défausse
		case "piochedefausse" : {
				if ($_SESSION['piocher'] == 1) {
					header("Location: index.php?page=jeu&err=dejapioche");
			} else {
				$_SESSION['piocher'] = 1;

				$_SESSION['defausselast'] = end($_SESSION['deckdefausse']);

				//Ajout de la carte en main
				if($_SESSION['currPlayer'] == 0) {
					 array_push($_SESSION['main'], $_SESSION['defausselast']);
				} else {
					array_push($_SESSION['main2'], $_SESSION['defausselast']);
				}

				array_splice($_SESSION['deckdefausse'], array_search($_SESSION['defausselast'], $_SESSION['deckdefausse']),1);

				$ans = $_SESSION['defausselast'];

				addAction($link, "Pioche Defausse", 	$_SESSION['id_tour'], 	$_SESSION['id_manche'], 	$_SESSION['id'], 	$_SESSION['player' . $_SESSION['currPlayer']]);
				header("Location: index.php?page=jeu&piochedefausse=$ans");
			}
			break;
		}

		//Cogner
		case "cogner": {
			if($_SESSION['alreadycogne'] == true) {

			header("Location: index.php?page=working&action=endturn");

			} else {

				$_SESSION['piocher'] = 0;
				$_SESSION['alreadycogne'] = true;

				if($_SESSION['currPlayer'] == 0) {
					setMancheScoreJ1($link, $_SESSION['id_manche'], $_SESSION['bestscore1']);
					addAction($link, "cogner", 	$_SESSION['id_tour'], 	$_SESSION['id_manche'], 	$_SESSION['id'], 	$_SESSION['player' . $_SESSION['currPlayer']]);
					$_SESSION['eventAction'] = "Attention le joueur 1 à cogné il vous reste un dernier tour !";
				} else {
					setMancheScoreJ2($link,$_SESSION['id_manche'], $_SESSION['bestscore2']);
					addAction($link, "cogner", 	$_SESSION['id_tour'], 	$_SESSION['id_manche'], 	$_SESSION['id'], 	$_SESSION['player' . $_SESSION['currPlayer']]);
					if($_SESSION['isIA'] == true) {
						$_SESSION['eventAction'] = "Attention l'ia à cognée il vous reste un dernier tour !";
					} else {
						$_SESSION['eventAction'] = "Attention le joueur 2 à cogné il vous reste un dernier tour !";
					}
				}

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

		//FIN TOUR
		case "endturn": {
			if (count($_SESSION['main']) > 3 || count($_SESSION['main2']) > 3) {
				header("Location: index.php?page=jeu&err=manycard");
			}
			if($_SESSION['alreadycogne'] == true) {
				addAction($link, "fintour", 	$_SESSION['id_tour'], 	$_SESSION['id_manche'], 	$_SESSION['id'], 	$_SESSION['player' . $_SESSION['currPlayer']]);

				if($_SESSION['currPlayer'] == 0) {
					setMancheScoreJ1($link, $_SESSION['id_manche'], $_SESSION['bestscore1']);
				} else {
					setMancheScoreJ2($link, $_SESSION['id_manche'], $_SESSION['bestscore2']);
				}

				if($_SESSION['bestscore1'] > $_SESSION['bestscore2'] && $_SESSION['bestscore1'] < 32) {
					$_SESSION['gagnant'] = nomjoueur($_SESSION['player' . 0], $link);
					setEnding($link, $_SESSION['id_manche'], $_SESSION['gagnant']);
				} else {
						if($_SESSION['isIA'] == true) {
							$_SESSION['gagnant'] = nomIa($link, $_SESSION['player' . 1]);
							setEnding($link, $_SESSION['id_manche'], 	$_SESSION['gagnant']);
						} else {

						$_SESSION['gagnant'] = nomjoueur($_SESSION['player' . 1], $link);
						setEnding($link, $_SESSION['id_manche'], 	$_SESSION['gagnant']);
					}
				}

				//fin de partie
				if($_SESSION['currManche'] == $_SESSION['manche']) {
					if($_SESSION['isIA'] == true) {
						if(getTotalSetWinInGame($link, nomjoueur($_SESSION['player' . 0], $link), $_SESSION['id']) > getTotalSetWinInGame($link, nomIa($link, $_SESSION['player' . 1]), $_SESSION['id']))
						{
								endGame($link, $_SESSION['id'], nomjoueur($_SESSION['player' . 0], $link));
						} else {
								endGame($link, $_SESSION['id'] , nomIa($link, $_SESSION['player' . 1]));
						}
					} else {
						if(getTotalSetWinInGame($link, nomjoueur($_SESSION['player' . 0], $link), $_SESSION['id']) > getTotalSetWinInGame($link, nomjoueur($_SESSION['player' . 1], $link), $_SESSION['id']))
					{
						endGame($link, $_SESSION['id'], nomjoueur($_SESSION['player' . 0], $link));
					} else {
							endGame($link, $_SESSION['id'] ,nomjoueur($_SESSION['player' . 1], $link));
					}
				}
					header("Location: index.php?page=jeu&end=endgame");
				} else {
					header("Location: index.php?page=jeu&end=endmanche");
				}

		} else {
			$_SESSION['eventAction'] ="";
			addAction($link, "fintour", 	$_SESSION['id_tour'], 	$_SESSION['id_manche'], 	$_SESSION['id'], 	$_SESSION['player' . $_SESSION['currPlayer']]);

			if($_SESSION['currPlayer'] == 0) {
					if(calculScoreRED($link, $_SESSION['main']) >= calculScoreBLACK($link, $_SESSION['main'])) {
						$score = calculScoreRED($link, $_SESSION['main']);
						$_SESSION['bestscore1'] = $score;
					} else {
						$score = calculScoreBLACK($link, $_SESSION['main']);
						$_SESSION['bestscore1'] = $score;
					}
			} else {
				if(calculScoreRED($link, $_SESSION['main2']) >= calculScoreBLACK($link, $_SESSION['main2'])) {
					$score = calculScoreRED($link, $_SESSION['main2']);
					$_SESSION['bestscore2'] = $score;
				} else {
					$score = calculScoreBLACK($link, $_SESSION['main2']);
					$_SESSION['bestscore2'] = $score;
				}
			}

			$score = 0;
			$_SESSION['piocher'] = 0;
			$start = $_SESSION['currPlayer'];
			$next = $start;
			$next = ($next + 1) % $_SESSION['nbPlayers'];
			$player = $_SESSION['player'. $next];
			$_SESSION['currPlayer'] = $next;

				addTurnScore($link, $score, $_SESSION['id_tour'], $_SESSION['id'], $_SESSION['id_manche']);
				$_SESSION['id_tour'] = addNewTurn($link, $_SESSION['id'], $_SESSION['id_manche']);

			header("Location: index.php?page=jeu");
		}
			break;
		}

		case "nextset" : {
				$_SESSION['currManche']++;
				$_SESSION['eventAction'] ="";
				$_SESSION['bestscore1'] = 0;
				$_SESSION['bestscore2'] = 0;
				$_SESSION['id_manche'] = createNewSet($link, $_SESSION['id']);
				$_SESSION['id_tour'] = addNewTurn($link, $_SESSION['id'], $_SESSION['id_manche']);
				$_SESSION['piocher'] = 0;
				$_SESSION['alreadycogne'] = false;
				createNewDeck($link, $_SESSION['id'], $_SESSION['style']);

				//Set la main de départ
				for($i = 0; $i < 3; $i++) {
					$cartArray[$i] = getCard($link, $_SESSION['id']);
					$cartArray2[$i] = getCard($link, $_SESSION['id']);
				}

				$_SESSION['main'] = $cartArray;
				$_SESSION['main2'] = $cartArray2;

				$_SESSION['defausse'] = 0;
				$_SESSION['deckdefausse'] = array();

				array_push($_SESSION['deckdefausse'],  getCard($link, $_SESSION['id']));

						if(calculScoreRED($link, $_SESSION['main']) >= calculScoreBLACK($link, $_SESSION['main'])) {
							$score = calculScoreRED($link, $_SESSION['main']);
								$_SESSION['bestscore1'] = $score;
						} else {
							$score = calculScoreBLACK($link, $_SESSION['main']);
								$_SESSION['bestscore1'] = $score;
						}
					if(calculScoreRED($link, $_SESSION['main2']) >= calculScoreBLACK($link, $_SESSION['main2'])) {
						$score = calculScoreRED($link, $_SESSION['main2']);
							$_SESSION['bestscore2'] = $score;
					} else {
						$score = calculScoreBLACK($link, $_SESSION['main2']);
								$_SESSION['bestscore2'] = $score;
					}

					addTurnScore($link, $score, $_SESSION['id_tour'], $_SESSION['id'], $_SESSION['id_manche']);

			header("Location: index.php?page=jeu");
			break;
		}
	default:
		break;
	}
}?>
