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

	echo "<fieldset><legend class='enteteconfigpartie'>Veuillez sélectionner le nombre de manche :</legend>";
			echo "<div class='form-group'><select class='form-control' name='manche'>";
			echo "<option selected disabled>Nombre de Manches</option>";
			echo "<option value='one'>1</option>";
	 		echo "<option value='two'>2</option>";
	 		echo "<option value='three'>3</option>";
	 		echo "<option value='four'>4</option>";
			echo "</select></div>";

		echo "<fieldset><legend class='enteteconfigpartie'>Veuillez sélectionner le jeu de carte souhaité :</legend>";
			echo "<div class='form-group'><select class='form-control' name='theme'>";
			echo "<option selected disabled>Jeux de cartes</option>";
			echo "<option value='classique'>classique</option>";
			echo "<option value='rustique'>rustique</option>";
			echo "</select></div>";

	echo "</fieldset><br/>";
	echo "<button class='btn btn-success col-md-offset-4' type='submit' id='launch' name='submit' value='Lancer la partie'>Lancer la partie</button>";
	echo "</form>";
	echo "</div>";

} else {
	echo "<div>Pour pouvoir créer une partie vous devez être connecté</div>";
}
?>
