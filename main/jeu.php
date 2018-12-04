<!-- GAME -->
<?php if (!isset($_GET['id']) && (!isset($_SESSION['player0']) || $_SESSION['player0'] === "")) {
	header("Location: index.php");
} ?>

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
} ?>


<!-- PANNEAU DES JOUEURS -->
<div class="entetepartie panelleft">
	Joueurs :
	<!-- PLAYER 1 -->
	<div id="jeuL1C1" class="panelleftelement">
		<div class="playername">
			<?php
			echo '<div style="color: '.getColor($link, $_SESSION['player0']).'">';
			echo nomjoueur($_SESSION['player0'], $link);
			echo " :";
			echo '</div>';
			?>
		</div>
	</div>
		<!-- PLAYER 2 -->
		<div id="jeuL1C2" class="panelleftelement">
			<div class="playername">
				<?php
				 echo '<div style="color: '.getColor($link, $_SESSION['player1']).'">';
				 echo nomjoueur($_SESSION['player1'], $link);
				 echo " :";
				 echo '</div>';
				 ?> </div>
		</div>
 </div>

 <div id="jeuL1C2" class="event">
	 <div class="entetepartie">Evénements :	</div>
	 <?php
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
		 default:
			 break;
	 }
 }
	 echo "<p class='eventspeech'>Tour de ". nomjoueur($_SESSION['player' . $_SESSION['currPlayer']], $link) ."</p>";
	 if (isset($_GET['card'])) {
		 $card = getCardName($link, $_GET['card']);
		 $_SESSION['card'] = $card;
		 echo "<p class='eventspeech2'>Vous avez tiré la carte $card !</p>";
	 }
 ?>
</div>

<!-- PIOCHES -->
<div id="jeuL2">
 <div id="jeuL2C1" class="panelright">
	 <div class="entetepartielow">Pioches</div>
	 <div class="piochesize"><img id="pile" alt="Pile de cartes" src="images/dosdecarte.jpg"></div>
	 <div class='piochesize2'>
	 <?php if (isset($_GET['card'])) {
		 getImage($link, $_GET['card']);
	 } ?>
	 </div>
	 <p class="entetepartielowdef">Défausse</p>
 </div>
</div>

<!-- MAIN -->
<div id="jeuL3">
	<div id="jeuL3C3" class="panelcenter">
		<span class="panelcentercard">
			<?php
			if($_SESSION['currPlayer'] == 0)
			{
				foreach ($_SESSION['main'] as $value) {
					getImage($link, $value);
				}
			} else {
				foreach ($_SESSION['main2'] as $value) {
					getImage($link, $value);
			}
		}
			?>
		</span>
	</div>
</div>

<!-- ACTION BAR -->
<div id="jeuL4">
	<div id="jeuL4C4" class="panelbottom">
		<span class="panelbottombutton">
		<!-- Bouton de pioche -->
		<?php if (!isset($_GET['end'])) {
				echo "<form action='index.php?page=working&action=card' method='POST' name='commandes'>";
				echo "</br></br><button class='btn btn-primary' type='submit' value='Tirer une carte'>Tirer une carte</button>";
			}
		 ?>
	 </form>
		 <!-- Bouton de cogne -->
		 <?php if (!isset($_GET['end'])) {
				 echo "<form action='index.php?page=working&action=cogner' method='POST' name='commandes'>";
				 echo "</br></br><button class='btn btn-success' type='submit' value='Cogner'>Cogner</button>";
			 }
			?>
		</form>
		<!-- Bouton de défausse  -->
		<?php if (!isset($_GET['end'])) {
				echo "<form action='index.php?page=working&action=defausse' method='POST' name='commandes'>";
				echo "</br></br><button class='btn btn-danger' type='submit' value='Défausser'>Défausser</button>";
			}
		 ?>
		 </form>
		 <!-- Bouton de fin de tour  -->
		 <?php if (!isset($_GET['end'])) {
				 echo "<form action='index.php?page=working&action=endturn' method='POST' name='commandes'>";
				 echo "</br></br><button class='btn btn-warning' type='submit' value='Défausser'>Fin du tour</button>";
			 }
			?>
			</form>
		</span>
	</div>
</div>
