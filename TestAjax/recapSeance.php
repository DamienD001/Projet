<?php

spl_autoload_register(function ($class) {
    include $class.'.class.php';

});

session_start();

$seance=$_SESSION['seance'];
if(Membre::estConnecte()){
	$utilisateur = $_SESSION[Membre::USER_SESSION];
	$userId=$utilisateur->membreFromBDD()->getId();
	echo $utilisateur->membreFromBDD()->getForename()."<br>";
	echo $seance->getDureeEchauffement().'<br>';
	$seance->seanceDBfromObjet();
}else{
	echo "aucun utilisateur connecté";
}




?>