<?php

//Gestion d'erreur de connexion à la base de donnée

if (isset($_GET['err']) && $_GET['err'] === "link") {
	echo "<div class='messageError col-md-offset-1'>Erreur : Impossible de se connecter à la base de donnée !</div><br />";
}


//Partie SI déconnecté

if (!isset($_SESSION['pseudo'])) {

	//Gestion des erreurs du formulaire

	if (isset($_GET['err']) && $_GET['err'] === "unknown") {
		echo "<div class='messageError col-md-offset-1'>Utilisateur inconnu ou mot de passe erroné !</div><br/>";
	}


	//Formulaire de connexion

	echo "<div><span class='connexion'>Connectez-vous</span> <br/><br/><br/><br/>
	<form action='./index.php?page=working&action=co' method='POST' name='connForm'>

	<div class='form-group col-xs-5 col-md-offset-1'>
	<label for='id1' class='entete'>Pseudo : </label>
	<input id='id1'  class='form-control' type='text' name='pseudo' required='true'></input><br/>

	<label for='id1' class='entete'>Mot de passe : </label>
	<input id='id2' class='form-control' type='password' name='pass' required='true'></input><br/><br/><br/>

	<button id='id3' class='btn btn-success col-sm-offset-3 entete' type='submit' name='submitC' value='Se connecter'>Se connecter</button>
	</form><br/><br/><br/>

	<div class='col-xs-offset-2 entetefooter'>
	<p>Pas encore de compte ?</p>
	</div>
	<a class='col-lg-offset-3 entetefooterlink' href='./index.php?page=inscription'>Inscrivez-vous !</a>
	</div><br/></div>";


	//Quelques statistiques

	if (isset($link)) {
		echo "<div class='tabinfoco'>Nombres de joueurs : " . getAllUsers($link) ." Nombres de parties depuis un mois : "  . getTotalGamesPlayedMonth($link) . "</div>";
	}

} else {


	//Statistiques si joueur Co

	echo "<div class='messageCo'>Bonjour " . $_SESSION['pseudo'] ." !</div><br/><br/>";
	echo "<div class='statsentete'>Vos stats : </div>";
	echo "<div id='tabcostats'><table><tr><th> Nombres de parties depuis un mois </th><th> Date de la dernière partie </th></tr>";
	


	//Bouton lancer une partie

	echo "<br/><form action='./index.php?page=partie' method='POST' name='goPartie'>
	<button id='launch' class='btn btn-success col-sm-offset-2' type='submit' name='main' value='Lancer une nouvelle partie'>Lancer une nouvelle partie</button>
	</form>";


	//Messages sanitaires

	echo "<div class='tabinfogame col-md-offset-1'><p>Attention ! Jouer comporte des risques. A consomer avec modération.</p>";
	echo "<p>Si vous êtes photosensible ou sujet à des crises d'épilepsie demandez conseil à votre médecin.</p>";

}
?>
