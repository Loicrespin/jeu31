<!-- GAME -->
<?php if (!isset($_GET['id']) && (!isset($_SESSION['player0']) || $_SESSION['player0'] === "")) {
	header("Location: index.php");
}?>
<?php if (isset($_GET['id'])) {
	$_SESSION['id'] = $_GET['id'];
	$fullPlayers = getGame($link, $_GET['id']);
	for ($i = 0; $i < 4; $i++) {
		if (isset($_SESSION['player' . $i])) {
			unset($_SESSION['player' . $i]);
		}
	}
	$i = 0;
	foreach ($fullPlayers as $oneFull) {
		$_SESSION["player$i"] = $oneFull['player'];
		$i++;
	}
	$_SESSION['nbPlayers'] = $i;
	$_SESSION['currPlayer'] = 0;

	if (isset($_SESSION['card'])) {
	    unset($_SESSION['card']);
    }
}
if($_SESSION['isIA'] == true && $_SESSION['currPlayer'] == 1) {
	IaPlay($link, $_SESSION['player1']);
	$_SESSION['Iaaction'] = IaPlay($link, $_SESSION['player1']);
}
ob_start();
?>

<!-- PANNEAU DES JOUEURS -->
<div class="entetepartie panelleft">
	Victoires :
	<!-- PLAYER 1 -->
	<div id="jeuL1C1" class="panelleftelement">
		<div class="playername">
			<?php
			echo '<div style="color: '.getColor($link, $_SESSION['player0']).'">';
			echo nomjoueur($_SESSION['player0'], $link);
			echo " : ";
			echo getTotalSetWinInGame($link, nomjoueur($_SESSION['player0'], $link), $_SESSION['id']);
			echo "</div>";?>
		</div>
	</div>
		<!-- PLAYER 2 -->
		<div id="jeuL1C2" class="panelleftelement">
			<div class="playername">
<?php
			if($_SESSION['isIA'] == true) {
					echo '<div style="color:'.getColor($link, $_SESSION['player1']).'">';
					echo nomIa($link, $_SESSION['player1']);
					echo " : ";
					echo getTotalSetWinInGame($link, nomIa($link, $_SESSION['player1']), $_SESSION['id']);
					echo "</div>";
				} else {
					 echo '<div style="color:'.getColor($link, $_SESSION['player1']).'">';
					 echo nomjoueur($_SESSION['player1'], $link);
					 echo " : ";
					 echo getTotalSetWinInGame($link, nomjoueur($_SESSION['player1'], $link), $_SESSION['id']);
					 echo "</div>";
				 }
?>
		 </div>
		</div>
 </div>

 <div id="jeuL1C2" class="event">
	 <div class="entetepartie">Evénements :	</div>
	 <?php
	 if (isset($_GET['end'])) {
	 	if ($_GET['end'] == "endmanche") {
			echo "<div class='eventspeech2'>La manche est finies ! <br/>";
			if($_SESSION['bestscore1'] > $_SESSION['bestscore2']) {
				echo "Le joueur $_SESSION[gagnant] gagne la manche ! $_SESSION[bestscore1] contre $_SESSION[bestscore2] !</div>";
			} else {
				if($_SESSION['isIA'] == true) {
						echo "Le'ia $_SESSION[gagnant] gagne la manche ! $_SESSION[bestscore2] contre $_SESSION[bestscore1] !</div>";
				} else {
				echo "Le joueur $_SESSION[gagnant] gagne la manche ! $_SESSION[bestscore2] contre $_SESSION[bestscore1] !</div>";
			}
			}

			$_SESSION['main'] = array();
			$_SESSION['main2'] = array();

				header("Location: index.php?page=working&action=nextset");

		} else if ($_GET['end'] == "endgame") {
			echo "<div class='eventspeech2'>La partie est finies ! <br />";
			echo "Le joueur " . gameWinner($link, $_SESSION['id']) . " gagne la partie !<br> Pour un total de " . getTotalSetWinInGame($link, gameWinner($link, $_SESSION['id']), $_SESSION['id']) . " manches gagnée sur $_SESSION[manche] !</div>";


			echo "<form action='index.php' method='POST' name='commandes'>";
			echo "</br></br><button class='btn btn-warning' type='submit' value='retour'>Accueil</button>";

			$_SESSION['main'] = array();
			$_SESSION['main2'] = array();
		}
} else {
	 if (isset($_GET['err'])) {
	 switch ($_GET['err']) {
		 case "dejapioche": {
			 echo "<p class='eventspeech2'>Vous avez déja pioché !</p>";
			 break;
		 }
		  case "manycard": {
					echo "<p class='eventspeech2'>Vous avez trop de carte en jeu défaussez vous d'une carte !</p>";
				break;
			}
			case "notselect" : {
						echo "<p class='eventspeech2'>Vous devez cliquez sur la carte que vous voulez défausser !</p>";
				break;
			}
			case "dejadefausse" : {
				echo "<p class='eventspeech2'>Vous ne pouvez pas défausser !<br> (Rappel : 3 cartes en main minimum)</p>";
				break;
			}
			case "notpioche" : {
				echo "<p class='eventspeech2'>Vous devez piochez et vous défaussez avant de conclure votre tour ! </p>";
				break;
			}
			case "J1cogner" : {
				echo "<p class='eventspeech2'>Attention le joueur 1 à cogné c'est votre dernier tour ! </p>";
				break;
			}
			case "J2cogner" : {
				echo "<p class='eventspeech2'>Attention le joueur 2 à cogné c'est votre dernier tour ! </p>";
				break;
			}

		 default:
			 break;
	 }
 }
   echo "<p class='eventspeech'>Manche : $_SESSION[currManche] / $_SESSION[manche]</p>";
	 if($_SESSION['isIA'] == true && $_SESSION['currPlayer'] == 1) {
		 	echo "<p class='eventspeech'>Tour de ". nomIa($link, $_SESSION['player' . $_SESSION['currPlayer']]) ." !</p>";
	 } else {
	 		echo "<p class='eventspeech'>Tour de ". nomjoueur($_SESSION['player' . $_SESSION['currPlayer']], $link) ." !</p>";
 	 }
	 if (isset($_GET['card'])) {
		 $card = getCardName($link, $_GET['card']);
		 $_SESSION['card'] = $card;
		 if($card == "endDeck") {
			  echo "<p class='eventspeech2'>Fin de la pioche récupération de la défausse !</p>";
		 }
		 echo "<p class='eventspeech2'>Vous avez tiré la carte : $card !</p>";
	 }
	  if (isset($_GET['defausse'])) {
			 $card = getCardName($link, $_GET['defausse']);
			  echo "<p class='eventspeech2'>Vous vous êtes défaussez de la carte : $card !</p>";
		}
	}?>
</div>

<!-- PIOCHES -->
<div id="jeuL2">
 <div id="jeuL2C1" class="panelright">
	 <div class="entetepartielow">Pioches</div>
	<div class="piochesize"><img id="pile" alt="Pile de cartes" src="images/dosdecarte.jpg"></div>
	 <div class='piochesizedef'><?php
	 if (isset($_GET['card'])) {
		 getImage($link, $_GET['card']);
	 }?>
	 </div>
	 <p class="entetepartielowdef">Défausse</p>
	 <div class='piochesize2'><?php
		getImage($link, end($_SESSION['deckdefausse']));?>
	</div><?php if (!isset($_GET['end'])) {
			echo "<div class='defausseButton'> <form action='index.php?page=working&action=piochedefausse' method='POST' name='commandes'>";
			echo "</br></br><button class='btn btn-danger' type='submit' value='piochedefausse'>Piocher carte défausse</button> </div>";
		}?>
	 </form>
 </div>
</div>

<!-- MAIN -->
<div id="jeuL3">
	<div id="jeuL3C3" class="panelcenter">
		<span class="panelcentercard"><?php
			if($_SESSION['isIA'] == true && $_SESSION['currPlayer'] == 1) {
			  echo "<p class='scoremainpaneltitle'>L'ia est en trains de jouer ... </p>";
			} else {
			if($_SESSION['currPlayer'] == 0)
			{
				foreach ($_SESSION['main'] as $value) {
				echo '<form method="post">';
				getImage($link, $value);
				echo '</form>';
				}
			} else {
				foreach ($_SESSION['main2'] as $value) {
				echo '<form method="post">';
				getImage($link, $value);
				echo '</form>';
			}
		}
	}
		//Si une carte est clicker
		if(!empty($_POST)) {
		foreach ($_POST as $key => $value) {
			$idcard = (int)$key;
		}
		$_SESSION['defausse'] = $idcard;
	}?>
		</span>
	</div>
</div>

<!-- ACTION BAR -->
<div id="jeuL4">
	<div id="jeuL4C4" class="panelbottom">
		<div class="scoremainpanel"><?php
			if($_SESSION['isIA'] == true && $_SESSION['currPlayer'] == 1)
			{

			} else {
				echo"<p class='scoremainpaneltitle'>
					Score Main :
				</p>
				<p class='scoremainpanelelement'>
					Rouge : ";
						if($_SESSION['currPlayer'] == 0) {
							echo calculScoreRED($link, $_SESSION['main']);
						 } else {
								echo calculScoreRED($link, $_SESSION['main2']);
						}
			echo "</p>
				<p class='scoremainpanelelement'>
					Noir : ";
						if($_SESSION['currPlayer'] == 0) {
								echo calculScoreBLACK($link,  $_SESSION['main']);
						 } else {
								echo calculScoreBLACK($link,  $_SESSION['main2']);
						}
				echo "</p>";
			}?>
	</div>
		<span class="panelbottombutton"><?php
			if($_SESSION['isIA'] == true && $_SESSION['currPlayer'] == 1) {

			} else {
		 if (!isset($_GET['end'])) {
				echo "<form action='index.php?page=working&action=card' method='POST' name='commandes'>";
				echo "</br></br><button class='btn btn-primary' type='submit' value='Tirer une carte'>Tirer une carte</button>";
			}
	 echo "</form>";
		 // <!-- Bouton de cogne -->
		 if (!isset($_GET['end'])) {
				 echo "<form action='index.php?page=working&action=cogner' method='POST' name='commandes'>";
				 echo "</br></br><button class='btn btn-success' type='submit' value='Cogner'>Cogner</button>";
			 }
	 	echo "</form>";
		// <!-- Bouton de défausse  -->
		if (!isset($_GET['end'])) {
				echo "<form action='index.php?page=working&action=defausse' method='POST' name='commandes'>";
				echo "</br></br><button class='btn btn-danger' type='submit' value='Défausser'>Défausser</button>";
			}
		 echo "</form>";
		 // <!-- Bouton de fin de tour  -->
		  if (!isset($_GET['end'])) {
				 echo "<form action='index.php?page=working&action=endturn' method='POST' name='commandes'>";
				 echo "</br></br><button class='btn btn-warning' type='submit' value='endTurn'>Fin du tour</button>";
			 }
			echo "</form>";
		}
		 ob_end_flush();?>
		</span>
	</div>
</div>
