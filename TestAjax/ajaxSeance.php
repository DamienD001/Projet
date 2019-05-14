<?php	
	
	spl_autoload_register(function ($class) {
    include $class.'.class.php';
});
    session_start();


            //if(isset($_POST['validerSeance'])){
                //$seance=Seance::seanceFromForm();
                $seance=$_SESSION['seance'];
                $tablExercices=$seance->getSeanceExercices();
                
                $nbExercices=count($seance->getSeanceExercices());
                $json_exercice=array();
                for($i=0;$i<$nbExercices;$i++){
                    $json_exercice[]=array($seance->getSeanceExercices()[$i]->getExoIntitule(),$seance->getSeanceExercices()[$i]->getExoTempsEffort(),$seance->getSeanceExercices()[$i]->getExoTempsRepos(),$seance->getSeanceExercices()[$i]->getExoRang(),$seance->getSeanceExercices()[$i]->getExoCalories());
                }
                echo json_encode(array($seance->getSeanceDate(),$seance->getDureeEchauffement(),$seance->getDureeRetourCalme(),$seance->getSeanceNbTours(),$json_exercice));
                //echo json_encode();


        ?>