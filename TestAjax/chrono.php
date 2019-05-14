<?php	
	
	spl_autoload_register(function ($class) {
    include $class.'.class.php';
    
});
session_start();
?>

<!DOCTYPE html>
<html>

	<head>
		<title>Paramètres de séance</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="projet.css">
		<script src="scriptsJS.js"></script>
		<script>
			var indice=0;
			var leJSON;

		</script>

	</head>

	<body>



		
		<?php

			//require_once('Membre.class.php');

			$seance=Seance::seanceFromForm();
			$seance=$_SESSION['seance'];
			//echo(var_dump($seance));
			//echo('Date :'.$seance->getSeanceDate().'<br>');
			//echo('Date :'.$_SESSION['seance']->getSeanceDate().'<br>');
			//echo('Nb tours :'.$_SESSION['seance']->getSeanceNbTours().'<br>');
			//echo('Echauffement :'.$_SESSION['seance']->getDureeEchauffement().'<br>');
			//echo('Calme :'.$_SESSION['seance']->getDureeRetourCalme().'<br>');
			echo "la liste des intitulés en session :<br>";
			$tablExercices=$_SESSION['seance']->getSeanceExercices();
			for($i=0;$i<count($tablExercices);$i++){
				echo($tablExercices[$i]->getExoIntitule()."<br>");
			}
			echo "le json en php :<br>";

			$nbExercices=count($_SESSION['seance']->getSeanceExercices());
			$json_exercice=array();
			for($i=0;$i<$nbExercices;$i++){
				$json_exercice[]=array($_SESSION['seance']->getSeanceExercices()[$i]->getExoIntitule(),$_SESSION['seance']->getSeanceExercices()[$i]->getExoTempsEffort(),$_SESSION['seance']->getSeanceExercices()[$i]->getExoTempsRepos(),$_SESSION['seance']->getSeanceExercices()[$i]->getExoRang());
			}
			echo json_encode(array($_SESSION['seance']->getSeanceDate(),$_SESSION['seance']->getDureeEchauffement(),$_SESSION['seance']->getDureeRetourCalme(),$_SESSION['seance']->getSeanceNbTours(),$json_exercice))."<br>";
			echo "<script>seanceJSON=detailSeance();</script>";
			
		?>

		<script>
			<!--
			//----------------------ne pas utiliser. Preferer la classe seance etendue
			// alert("mon JSON : "+seanceJSON[4][0][0]);
			// var seanceJS=new Seance(seanceJSON);
			// alert("echauffement : "+seanceJS.echauffement);
			// alert("calme : "+seanceJS.calme);
			// alert("nb d'exo : "+seanceJS.nbExercices);
			// alert("intitule 0 : "+seanceJS.exoIntitule(0));
			// alert("effort 0 : "+seanceJS.exoEffort(0));
			// alert("calories 0 : "+seanceJS.exoCalories(0));
			// alert("intitule 1 : "+seanceJS.exoIntitule(1));
			// alert("repos 1 : "+seanceJS.exoRepos(1));
			// alert("calories 1 : "+seanceJS.exoCalories(1));
			//----------------------


			var seanceJS=new SeanceEtendue(seanceJSON);
			seanceJS.ajouteExos(seanceJSON);
			alert('nb exos etendus : '+seanceJS.nbExoEtendus);
			alert('seanceJS.exoEtendus[0].intitule : '+seanceJS.exoEtendus[0].intitule);

			var detail="";
			for(i=0;i<seanceJS.nbExoEtendus;i++){
				detail+=seanceJS.exoEtendus[i].intitule+"/";
			}
			alert(detail);


		</script>

		<section id="affichage">
				
			<div class="conteneur">
				<div id="listeExo">
					<span>liste exo</span>
				</div>
			</div>

			<div class="conteneur">
				<div id="calories">
					<span> 50 calories</span>
				</div>
				<div id="decompte">
					<span> 00:40</span>
				</div>
			</div>

			<div class="conteneur">
				<div id="compteTours">
					<span> 1 / 4</span>
				</div>
			</div>

			<form>
				<input type="button" id="startSeance" onclick="start()" value="START">
				<input type="button" id="stopSeance" onclick="stop()" value="STOP">
			</form>

			<form method="post" action="recapSeance.php">
				<input type="submit" id="saveSeance" value="ENREGISTRER">
			</form>

		</section>


		<script>
			alert("nombre d'exos : "+seanceJS.nbExo);
			affichageIndicateur("listeExo",seanceJS.exoEtendus[1].intitule);
			affichageIndicateur("calories",seanceJS.exoEtendus[1].calories);
			affichageIndicateur("decompte",seanceJS.exoEtendus[1].duree);
			affichageIndicateur("compteTours",seanceJS.exoEtendus[1].numTour+' / '+seanceJS.nbTours);

		</script>

		<!-- <div class="grille">
			<div class="listeExo">
				<span>liste exo</span>
			</div>

			<div class="calories">
				<span> 50 calories</span>
			</div>

			<div class="compteTours">
				<span> 1 / 4</span>
			</div>

			<div class="decompte">
				<span> 00:40</span>
			</div>

			<div class="progression">
				<span> 15% </span>
			</div>

		</div> -->




		

	</body>

</html>
	

