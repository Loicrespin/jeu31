<!-- Page d'inscription -->
<?php
	//Gestion du formulaire d'inscription
	if (isset($_POST['submitI'])) {
		$full = True;
		$inputs = ["pseudo", "nom", "prenom", "passF", "passS"];
		foreach ($inputs as $value) {
			if (trim($_POST[$value]) === "") {
				$full = False;
			}
		}
		if ($full == True) {
			if ($_POST["passF"] === $_POST["passS"]) {
				$pseudo = $_POST["pseudo"];
				$nom = $_POST["nom"];
				$prenom = $_POST["prenom"];
				$pass = $_POST["passF"];
				if (checkAvailability($pseudo, $link)) {
					$hashPwd = password_hash($pass, PASSWORD_DEFAULT);
					$registered = False;
					if (register($pseudo, $nom, $prenom, $hashPwd, $link)) {
						$registered = True;
						$_SESSION['pseudo'] = $pseudo;
					}
				} else {
					$availError = true;
				}
			} else {
				$passError = True;
			}
		}
	}
	//FIN Gestion du formulaire d'inscription

	//Messages d'erreur et de confirmation
	if (isset($passError) && $passError) {
		echo "<div class='messageError col-md-offset-1'>Les mots de passe ne correspondent pas !</div><br/>";
	}
	if (isset($availError) && $availError) {
		echo "<div class='messageError col-md-offset-1'>Pseudo déjà pris !(</div><br/>";
	}
	if (isset($registered)) {
		if ($registered) {
			echo "<div class='messageError col-md-offset-1'>Utilisateur " . $_SESSION['pseudo'] . " enregistré !</div><br />";
			$_SESSION['id_Joueur0'] = idJoueur0($pseudo, $link);
			header("Location: index.php?page=accueil");
		} else {
			echo "<div class='messageError col-md-offset-1'>Problème lors de l'inscription dans la base de donnée !</div>";
		}
	}
	//FIN Messages d'erreur et de confirmation

?>

	<!-- Formulaire d'inscription -->
	<div><span class='connexion'>Inscrivez-vous</span><br/><br/>
	<form action="./index.php?page=inscription" method="POST" name="insForm">

	<div class='form-group col-xs-5 col-md-offset-1'>
	<label for='id1' class='entete'>Pseudo :</label>
	<input id="id1" class='form-control' type="text" name="pseudo" required="true"></input><br/>

	<label for='id2' class='entete'>Nom :</label>
	<input id="id2" class='form-control' type="text" name="nom" required="true"></input><br/>

	<label for='id3' class='entete'>Prénom :</label>
	<input id="id3" class='form-control' type="text" name="prenom" required="true"></input><br/>

	<label for='id4' class='entete'>Mot de passe : </label>
	<input id="id4" class='form-control' type="password" name="passF" required="true"></input><br/>

	<label for='id5' class='entete'>Confirmation du mdp :</label>
	<input id="id5" class='form-control' type="password" name="passS" required="true"></input><br/>

	<button id="id6" class="btn btn-success col-sm-offset-3 entete" type="submit" name="submitI" value="S'inscrire">S'incrire</button>
	</form><br/>
	</div>
  </div>
	<!-- FIN Formulaire d'inscription -->
