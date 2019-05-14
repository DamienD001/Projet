<?php	
	//session_start();
	spl_autoload_register(function ($class) {
    include $class.'.class.php';
});

    $seance=new Seance(date('d/m/Y'),0,0,0);
	//echo var_dump($seance->listeExercices());
    $listeExercices=implode(";",$seance->listeExercices());
    $listeExercices=$seance->listeExercicesFromDB();
    echo json_encode($listeExercices);
    //echo $listeExercices;


	//$strjson='{"prenom":"Damien","nom":"Delatour"}';
    //echo $strjson;
?>