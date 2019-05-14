



<?php

spl_autoload_register(function ($class) {
    include $class.'.class.php';
});

session_start();
$utilisateur = Membre::objetFromFormAuthentification();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>authentification</title>
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
		<?php
			$utilisateur->afficheFormAuthentification();
			//$okAdr=false;
			//$okPwd=false;
			
			if(isset($_POST['envoyer'])){
				$utilisateur->objetFromFormAuthentification();
				//$utilisateur->afficheInfosMembre();	

				if($utilisateur->checkConnexion()){
					$utilisateur=$utilisateur->membreFromBDD();
					$utilisateur->connexion();
					header("location:monCompte.php");
					//$utilisateur->afficheInfosMembre();	
					//echo $utilisateur->getName();
					//$tablUtil=$utilisateur->listeUtilisateurs();

// 					echo<<<HTML

// 						<table>
// 							<caption>Liste des utlisateurs</caption>
// 							<thead>
// 								<tr>
// 									<th>Nom</th>
// 									<th>Prénom</th>
// 									<th>Email</th>
// 									<th>Date de naissance</th>
// 								</tr>
// 							</thead>
// 							<tbody>
// HTML;
// 					for($i=0;$i<count($tablUtil);$i++){
// 						echo "<tr>";
// 						echo "<td>".$tablUtil[$i]->getName()."</td>";			
// 						echo "<td>".$tablUtil[$i]->getForename()."</td>";
// 						echo "<td>".$tablUtil[$i]->getEmail()."</td>";
// 						echo "<td>".$tablUtil[$i]->getDateNaissance()."</td>";
// 						echo "</tr>";
// 					}

// echo<<<HTML
// 							</tbody>
// 						</table>

// HTML;
				}else{
					echo $utilisateur->getMessageErreur()."<br>";
					
				}

			}
			
		?>

		</section>

	</body>
</html>


