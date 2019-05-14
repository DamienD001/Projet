<?php	
	session_start();
	spl_autoload_register(function ($class) {
    include $class.'.class.php';
});

	$seance=Seance::seanceFromForm();
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

		</script>

	</head>

	<body>

		<form method="POST" id="formSeance" action="">
			<fieldset id="fieldSeance">

				<!--à retirer ultérieurement-->
					<input type="hidden" id="nbExercices" name="nbExercices">
					<label id="verifHidden"></label>
				<!-- -->

				<label class="form-lbl" for="nbRounds">Nombre de rounds</label>
				<input class="form-input-num" type="numeric" id="nbRounds" name="nbRounds" onchange="fctTotal()"><br>

				<label class="form-lbl" for="dureeEchaufMin">Durée d'échauffement</label>
				<input class="form-input-time" type="numeric" id="dureeEchaufMin" name="dureeEchaufMin" value="00" onchange="fctTotal()">
				:
				<input class="form-input-time" type="numeric" id="dureeEchaufSec" name="dureeEchaufSec" value="00" onchange="fctTotal()"><br>

				<label class="form-lbl" for="dureeCalmeMin">Durée de retour au calme</label>
				<input class="form-input-time" type="numeric" id="dureeCalmeMin" name="dureeCalmeMin" value="00" onchange="fctTotal()">
				:
				<input class="form-input-time" type="numeric" id="dureeCalmeSec" name="dureeCalmeSec" value="00" onchange="fctTotal()"><br>

			</fieldset>
			
			<fieldset id="fieldExercices">
				<div id="resultat"></div>
			</fieldset>

			<input class="form-input-btn" type ="button" onclick="ajouteForm()" value ="Ajouter exercice">

			<fieldset>
				<p>durée totale : <span id="dureeTotaleSeance"></span></p>
				<p>durée totale : <span id="minutesTotalSeance"></span>:<span id="secondesTotalSeance"></span></p>
			</fieldset>

			<!--<input class="form-input-btn" type="submit" id="validerSeance" name="validerSeance" value="Valider la séance" onclick="detailSeance()">
			-->
			<input class="form-input-btn" type="submit" id="validerSeance" name="validerSeance" value="Valider la séance">


		</form>

		<div id="resultatAjax"></div>


<!------------------------test -------------- -->
		<?php
			if(isset($_POST['validerSeance'])){

				$seance=Seance::seanceFromForm();
				$_SESSION['seance']=$seance;
				//echo('Date :'.$seance->getSeanceDate().'<br>');
				echo('Date :'.$_SESSION['seance']->getSeanceDate().'<br>');
				echo('Nb tours :'.$_SESSION['seance']->getSeanceNbTours().'<br>');
				echo('Echauffement :'.$_SESSION['seance']->getDureeEchauffement().'<br>');
				echo('Calme :'.$_SESSION['seance']->getDureeRetourCalme().'<br>');
				$tablExercices=$_SESSION['seance']->getSeanceExercices();
				for($i=0;$i<count($tablExercices);$i++){
					echo("toto".$i);
					echo($tablExercices[$i]->getExoIntitule());
				}
				echo "le json en php :<br>";

				$nbExercices=count($_SESSION['seance']->getSeanceExercices());
				$json_exercice=array();
				for($i=0;$i<$nbExercices;$i++){
					$json_exercice[]=array($_SESSION['seance']->getSeanceExercices()[$i]->getExoIntitule(),$_SESSION['seance']->getSeanceExercices()[$i]->getExoTempsEffort(),$_SESSION['seance']->getSeanceExercices()[$i]->getExoTempsRepos(),$_SESSION['seance']->getSeanceExercices()[$i]->getExoRang());
				}
				echo json_encode(array($_SESSION['seance']->getSeanceDate(),$_SESSION['seance']->getDureeEchauffement(),$_SESSION['seance']->getDureeRetourCalme(),$_SESSION['seance']->getSeanceNbTours(),$json_exercice))."<br>";
				echo "<script>detailSeance();</script>";
				header("location:chrono.php");
			}else{
				echo("problème");
			}

			



		?>
<!------------------------fin du test -------------- -->

		<script>
			
			var contenuSeance="";
			var listeExercices=listeExercices_V3();
			

			


		</script>

	</body>

</html>