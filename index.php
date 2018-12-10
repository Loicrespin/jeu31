<?php
	session_start();

	require_once("includes/bd.php");
	require_once('includes/fonctions.php');

	$link = getConnection($dbHost, $dbUser, $dbPwd, $dbName);
	if (!$link) {
		header("Location: index.php?err=link");
	} else {
		$_SESSION['link'] = $link;
	}?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<title>Projet BDW1</title>
	<link rel="stylesheet" media="all" type="text/css" href="./css/styles.css">
	<link rel="shortcut icon" type="image/x-icon" href="images/couleurs.png"/>
	<!-- Meta-Tags -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="keywords" content="Game Robo a Responsive Web Template, Bootstrap Web Templates, Flat Web Templates, Android Compatible Web Template, Smartphone Compatible Web Template, Free Webdesigns for Nokia, Samsung, LG, Sony Ericsson, Motorola Web Design">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //Meta-Tags -->

<!-- Custom-Stylesheet-Links -->
<!-- Bootstrap-CSS -->	  <link rel="stylesheet" href="css/bootstrap.min.css"  type="text/css" media="all">
<!-- Index-Page-CSS -->	  <link rel="stylesheet" href="css/style.css"		   type="text/css" media="all">
<!-- Animate-CSS -->	  <link rel="stylesheet" href="css/animate-custom.css" type="text/css" media="all">
<!-- //Custom-Stylesheet-Links -->

<!-- Fonts -->
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Montserrat:400,700"	   type="text/css" media="all">
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,100,300,500" type="text/css" media="all">
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Press+Start+2P"		   type="text/css" media="all">
<!-- //Fonts -->

<!-- Font-Awesome-File-Links -->
<!-- CSS --> <link rel="stylesheet" href="css/font-awesome.min.css" 	 type="text/css" media="all">
<!-- TTF --> <link rel="stylesheet" href="fonts/fontawesome-webfont.ttf" type="text/css" media="all">
<!-- //Font-Awesome-File-Links -->

</head>
<!-- Header -->
<body data-spy="scroll" data-target=".navbar" data-offset="50">

<div class="agileheader" id="agileitshome">

	<!-- Navigation -->
	<div class="w3lsnavigation">
		<nav class="navbar navbar-inverse agilehover-effect wthreeeffect navbar-default">

			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<!-- Logo -->
				<div class="logo">
					<a class="navbar-brand logo-w3l button" href="index.php"><span style="color:#C03000;">JEU</span>-31</a>
				</div>
				<!-- //Logo -->
			</div>

	<div id="navbar" class="navbar-collapse navbar-right collapse">
		<ul class="nav navbar-nav navbar-right cross-effect" id="cross-effect">
			  	<li><a href="index.php">Accueil</a></li>
			    <li><a id="ins" href="index.php?page=inscription">S'inscrire</a></li>
			    <?php if (isset($_SESSION['pseudo'])) {
			    	echo "<li><a href='index.php?page=partie'>Lancer une partie</a></li>";
						echo "<script  type='text/javascript'> document.getElementById('ins').style.display = 'none'</script>";
			    } ?>
			   <li><a href="index.php?page=statistiques">Statistiques</a></li>
			   <li><a href="index.php?page=apropos">A propos</a></li>
			    <?php if (isset($_SESSION['pseudo'])) {
			   		echo "<li><a href='index.php?page=working&action=deco'>Déconnexion</a></li>";
				} ?>
				</ul>
			</div>
			</nav>
	</div>
	<!-- //Navigation -->

	<!-- Slider -->
	<div class="slider">
		<ul class="rslides" id="slider">
			<li class="first-slide w3ls">
				<img src="images/cartes.jpg" alt="Game Robo">
				<div class="heading">
					<div class="col-md-offset-4">
						<main>
						<?php
							$nomPage = 'main/accueil.php'; // page par défaut
							if(isset($_GET['page'])) { // verification du parametre "page"
								if(file_exists(addslashes('main/'.$_GET['page'].'.php'))) // le fichier existe
									$nomPage = addslashes('main/'.$_GET['page'].'.php');
									else
									$nomPage = 'includes/fatalError.php';
							}
							include($nomPage); // inclut le contenu
						?>
						</main>
					</div>
				</div>
				</div>
			</li>
	</div>
	<!-- //Slider -->
</div>
<!-- //Header -->

	<?php include('static/footer.php'); ?>

	<!-- Custom-JavaScript-File-Links -->

	<!-- Default-JavaScript -->   <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
	<!-- Bootstrap-JavaScript --> <script type="text/javascript" src="js/bootstrap.min.js"></script>

	<!-- Tab-JavaScript -->
		<!-- <script src="js/cbpFWTabs.js"></script> -->
		<script>
			(function() {
				[].slice.call( document.querySelectorAll( '.tabs' ) ).forEach( function( el ) {
					new CBPFWTabs( el );
				});
			})();
		</script>
	<!-- //Tab-JavaScript -->

	<!-- Smooth-Scrolling-JavaScript -->
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){
					event.preventDefault();
					$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
				});
			});
		</script>
	<!-- //Smooth-Scrolling-JavaScript -->

</body>
</html>
