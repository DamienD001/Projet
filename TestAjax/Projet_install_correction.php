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
        USE NFA021_PROJET;
        CREATE TABLE IF NOT EXISTS SEANCES (
            SEANCE_id integer NOT NULL PRIMARY KEY,
            SEANCE_date DATE NOT NULL,
            SEANCE_TempsEchauffement integer NOT NULL,
            SEANCE_TempsRetourCalme integer NOT NULL,
            SEANCE_nbTours integer NOT NULL,
            SEANCE_user_id integer NOT NULL
        ) ENGINE=innoDB;
        ALTER TABLE SEANCES ADD FOREIGN KEY (SEANCE_user_id) REFERENCES UTILISATEURS(USER_id);
       
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
