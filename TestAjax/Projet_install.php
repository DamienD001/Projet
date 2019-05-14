<?php

	const DB_HOST = "localhost";
    const DB_USER = "root";
    const DB_PASSWORD = "";
    const DB_BASENAME = "NFA021_PROJET";


	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");

    try{
        $pdo = new PDO('mysql:host='.DB_HOST, DB_USER, DB_PASSWORD,$options);

    }catch(PDOException $e){
        $message="erreur de connexion";
        echo $message;
    }

    $requete =<<<SQL
        CREATE DATABASE IF NOT EXISTS NFA021_PROJET DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
        USE NFA021_PROJET;
        CREATE TABLE IF NOT EXISTS UTILISATEURS (
            USER_id integer NOT NULL PRIMARY KEY AUTO_INCREMENT,
            USER_nom varchar(100) NOT NULL,
            USER_prenom varchar(100) NOT NULL,
            USER_adresseMail varchar(100) NOT NULL,
            USER_motDePasse varchar(255) NOT NULL,
            USER_statut varchar(15) NOT NULL DEFAULT 'UTILISATEUR'
        ) ENGINE=innoDB;
        CREATE TABLE IF NOT EXISTS SEANCES (
            SEANCE_id integer NOT NULL PRIMARY KEY AUTO_INCREMENT,
            SEANCE_date DATE NOT NULL,
            SEANCE_TempsEchauffement integer NOT NULL,
            SEANCE_TempsRetourCalme integer NOT NULL,
            SEANCE_nbTours integer NOT NULL,
            SEANCE_user_id integer NOT NULL
        ) ENGINE=innoDB;
        ALTER TABLE SEANCES ADD FOREIGN KEY (SEANCE_user_id) REFERENCES UTILISATEURS(USER_id);
        CREATE TABLE IF NOT EXISTS EXERCICES (
            EXERCICE_intitule varchar(100) NOT NULL,
            EXERCICE_calories integer NOT NULL
        ) ENGINE=innoDB;
        CREATE TABLE IF NOT EXISTS CONTIENT (
            CONTIENT_seanceId integer NOT NULL,
            CONTIENT_exerciceIntitule varchar(100) NOT NULL,
            CONTIENT_tempsEffort integer NOT NULL,
            CONTIENT_tempsRepos integer NOT NULL,
            CONTIENT_rangExercice integer NOT NULL,
            PRIMARY KEY(CONTIENT_seanceId,CONTIENT_exerciceIntitule,CONTIENT_rangExercice)
        ) ENGINE=innoDB;
SQL;
	echo $requete;
$pdo->prepare($requete)->execute();



    $listeExercices=array(
        array("pompes",900),
        array("squats",750),
        array("burpees",1100),
        array("fentes",1000)
    );

    $intitule='';
    $calories=0;

    $requete='INSERT INTO EXERCICES (EXERCICE_intitule, EXERCICE_calories) VALUES (:intitule,:calories);';
    echo $requete;
    $resultatRequete=$pdo->prepare($requete);
    $resultatRequete->bindParam('intitule',$intitule,PDO::PARAM_STR);
    $resultatRequete->bindParam('calories',$calories,PDO::PARAM_INT);

    for($i=0;$i<count($listeExercices);$i++){
        $intitule=$listeExercices[$i][0];
        $calories=$listeExercices[$i][1];
        $resultatRequete->execute();
    }


//USE DB_BASENAME;
//UPDATE COMPTE_USER SET USER_STATUT='ADMIN' WHERE USER_adresseMail IN ($nomAdmin);

/*


*/

?>
