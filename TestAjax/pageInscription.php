<?php

spl_autoload_register(function ($class) {
    include $class.'.class.php';
});

session_start();
$utilisateur = Membre::objetFromFormInscription();

?>

<!DOCTYPE html>
<html>
	<head>
		<title>connexion</title>
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
						
						//echo "le nom : ".$utilisateur->getEmail();
						//echo ($_SESSION[Membre::USER_SESSION]->getEmail());
						//echo ($_SESSION[Membre::USER_SESSION]->afficheInfosMembre());
						//echo ($_SESSION[Membre::USER_SESSION]->getName());
					}
				?>
			</ul>
		</nav>

		<section>



		<?php
			$utilisateur->afficheFormInscription();
			$okAdr=false;
			$okPwd=false;
			
			if(isset($_POST['envoyer'])){
				$utilisateur->objetFromFormInscription();
				//$utilisateur->afficheInfosMembre();	

				if($utilisateur->checkAdrMailInscription()){
					echo "adresse OK<br>";
					$okAdr=true;
				}else{
					echo $utilisateur->getMessageErreur();
				}

				if($utilisateur->checkPasswordInscription()){
					echo "password OK<br>";
					$okPwd=true;
				}else{
					echo $utilisateur->getMessageErreur();
				}
				
				if($utilisateur->getMotDePasse()!=$utilisateur->getReMotDePasse()){
					echo "non confirmé<br>";
				}
				//echo $utilisateur->getMotDePasse()." /// ".$utilisateur->getReMotDePasse();

				if($okAdr && $okPwd){
					$utilisateur->inscription();
				}
			}
			
		?>

		</section>

	</body>
</html>


