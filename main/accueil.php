<?php
if (isset($_GET['newia'])) {
	switch ($_GET['newia']) {
    case "newIa" : {
      if(createIa($link, $_POST['nomIa'], $_POST['chancePiocher'], $_POST['chanceCogner'], $_POST['chanceEndturn']) == true && $_POST['nomIa'] < 100 && $_POST['chancePiocher'] < 100 && $_POST['chanceCogner'] < 100 && $_POST['chanceEndturn'] < 100){
				echo "<div class='messageError col-md-offset-1'>Nouvelle ia crée !</div><br />";
			} else {
					echo "<div class='messageError col-md-offset-1'>De 0 à 100 les valeurs !</div><br />";
			}
      break;
    }
    default:
    break;
  }
}

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

	//Paramètrer son ia

	echo "<fieldset><legend class='enteteconfigpartie'>Créer une ia : (optionel)</legend>";
	echo"<form action='./index.php?page=accueil&newia=newIa' method='POST' name='iaForm'>";

	echo "<div class='form-group col-xs-5 col-md-offset-1'>";
	echo "<label for='id1' class='entete'>Nom ia :</label>";
	echo "<input id='id1' class='form-control' type='text' name='nomIa' required='true'></input><br/>";

	echo "<label for='id1' class='entete'>Chance de piocher :</label>";
	echo "<input id='id1' class='form-control' type='number' name='chancePiocher' required='true'></input><br/>";

	echo "<label for='id2' class='entete'>Chance de  cogner :</label>";
	echo "<input id='id2' class='form-control' type='number' name='chanceCogner' required='true'></input><br/>";

	echo "<label for='id3' class='entete'>Chance de passer son tour :</label>";
	echo "<input id='id3' class='form-control' type='number' name='chanceEndturn' required='true'></input><br/>";

	echo "<button class='btn btn-warning col-md-offset-4' type='submit' id='newIa' name='submit' value='newIa'>Créer une Ia</button>";
	echo "</div>";
	echo "</form>";
	echo "</fieldset><br/>";


	//Messages sanitaires

	echo "<div class='tabinfogame col-md-offset-1'><p>Attention ! Jouer comporte des risques. A consomer avec modération.</p>";
	echo "<p>Si vous êtes photosensible ou sujet à des crises d'épilepsie demandez conseil à votre médecin.</p>";

}
?>
