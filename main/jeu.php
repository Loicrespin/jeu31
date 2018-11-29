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
} ?>

<!-- PANNEAU DES JOUEURS -->
<div class="entetepartie panelleft">
	Joueurs :
	<!-- PLAYER 1 -->
	<div id="jeuL1C1" class="panelleftelement">
		<div class="playername"><?php echo nomjoueur($_SESSION['player0'], $link); ?></div>

	</div>
		<!-- PLAYER 2 -->
		<div id="jeuL1C3" class="panelleftelement">
			<div class="playername"><?php echo nomjoueur($_SESSION['player1'], $link); ?></div>
		</div>
 </div>
