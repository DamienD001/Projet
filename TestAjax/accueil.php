<?php	
	session_start();
	spl_autoload_register(function ($class) {
    include $class.'.class.php';
});
?>


<!DOCTYPE html>
<html>
	<head>
		<title>ACCUEIL</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="activite1.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	</head>

	<body>
		
		<nav>
			<ul>
				<li><a href="accueil.php" class="bouton"><span class="glyphicon glyphicon-home">  ACCUEIL</span></a></li>
				<li><a href="pageAuthentification.php" class="bouton"><span class="glyphicon glyphicon-log-in">  S'IDENTIFIER</span></a></li>
				<li><a href="pageInscription.php" class="bouton"><span class="glyphicon glyphicon-user">  S'INSCRIRE</span></a></li>
				<li><a href="testCreaFormJS.php" class="bouton"><span class="glyphicon glyphicon-cog">  SEANCE</span></a></li>
				
				<?php
					if(Membre::estConnecte()){
						echo<<<HTML
						<li><a href="deconnect.php" class="bouton" ><span class="glyphicon glyphicon-user">  SE DECONNECTER</span></a></li>
						<li><a href="monCompte.php" class="bouton" ><span class="glyphicon glyphicon-user">  MON COMPTE</span></a></li>
HTML;
					}
				?>
			</ul>
		</nav>

		<section>
			<h1>BIENVENUE !</h1>			
		</section>

	</body>

</html>