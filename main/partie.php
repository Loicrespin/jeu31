<?php
if (isset($_SESSION['pseudo'])) {
	if (isset($_GET['err'])) {
		switch ($_GET['err']) {
			case "empty": {
				echo "<div class='messageError col-md-offset-1'>Vous n'avez pas sélectionné d'adversaire !</div><br />";
				break;
			}
			case "toomany": {
				echo "<div class='messageError col-md-offset-1'>Vous devez sélectionner 1 adversaires maximum !</div><br />";
				break;
			}

			case "emptyset" : {
				echo "<div class='messageError col-md-offset-1'>Vous devez parmètrer le nombre de manche !</div><br />";
				break;
			}
			default:
				break;
		}
	}
	?>

<span class="connexion col-md-9">Configurer une nouvelle partie</span>

<?php
	echo "<div class='col-lg-9'> <form action='index.php?page=working&action=newGame' method='POST' name='partieForm'>";
	echo "<fieldset><legend class='enteteconfigpartie'>Veuillez sélectionner vore adversaire joueurs :</legend>";
	echo getPlayersInGame($link, $_SESSION['pseudo']) . "<br/>";

	echo "<span class='connexion'>OU</span>";

	echo "<fieldset><legend class='enteteconfigpartie'>Veuillez sélectionner votre adversaires ia :</legend>";
	echo getIaInGame($link);

	echo "<fieldset class='visibility'><legend class='enteteconfigpartie'>Paramétrage de l'ia (optionnel) :</legend>";

	echo "<label for='id1' class='entete'>Nom ia :</label>";
	echo "<input id='id1' class='form-control' type='text' name='nomIa'></input><br/>";

	echo "<label for='id1' class='entete'>Chance de cogner :</label>";
	echo "<input id='id1' class='form-control' type='text' name='chanceCogner'></input><br/>";

	echo "<label for='id2' class='entete'>Chance de Piocher :</label>";
	echo "<input id='id2' class='form-control' type='text' name='chancePiocher'></input><br/>";

	echo "<label for='id3' class='entete'>Chance de fin de tour :</label>";
	echo "<input id='id3' class='form-control' type='text' name='chanceFinTour'></input><br/>";

	echo "<button class='btn btn-warning col-md-offset-4' type='submit' id='launch' name='submit' value='setIA'>paramétrer ia</button>";
	echo "</fieldset>";

	echo "<fieldset><legend class='enteteconfigpartie'>Veuillez sélectionner le nombre de manche :</legend>";
			echo "<div class='form-group'><select class='form-control' name='manche'>";
			echo "<option selected disabled>Nombre de Manches</option>";
			echo "<option value='1'>1</option>";
	 		echo "<option value='3'>3</option>";
	 		echo "<option value='5'>5</option>";
			echo "</select></div>";

		echo "<fieldset><legend class='enteteconfigpartie'>Veuillez sélectionner le jeu de carte souhaité :</legend>";
			echo "<div class='form-group'><select class='form-control' name='theme' >";
			echo "<option selected disabled>Jeux de cartes</option>";
			echo "<option value='C'>classique</option>";
			echo "<option value='F'>fantastique</option>";
			echo "</select></div>";

	echo "</fieldset><br/>";
	echo "<button class='btn btn-success col-md-offset-4' type='submit' id='launch' name='submit' value='Lancer la partie'>Lancer la partie</button>";
	echo "</form>";
	echo "</div>";

} else {
	echo "<div>Pour pouvoir créer une partie vous devez être connecté</div>";
}
?>
