<?php

require_once('Exercice.class.php');

class Seance{

	private $seanceDate;
	private $seanceDureeEchauffement;
	private $seanceDureeRetourCalme;
	private $seanceNbTours;
    private $seanceExercices;

    const DB_HOST = "localhost";
    const DB_USER = "root";
    const DB_PASSWORD = "";
    const DB_BASENAME = "NFA021_PROJET";

    private static $messages=array();

    function __construct($seanDate, $seanDureeEchauf=0, $dureeCalme=0, $nbTours=0,$tableau=array()){
    	$this->seanceDate=$seanDate;
    	$this->seanceDureeEchauffement=$seanDureeEchauf;
    	$this->seanceDureeRetourCalme=$dureeCalme;
    	$this->seanceNbTours=$nbTours;
        $this->seanceExercices= $tableau;
    }
    
    function getSeanceDate(){
    	return $this->seanceDate;
    }

    function getDureeEchauffement(){
    	return $this->seanceDureeEchauffement;
    }

    function getDureeRetourCalme(){
    	return $this->seanceDureeRetourCalme;
    }

    function getSeanceNbTours(){
    	return $this->seanceNbTours;
    }

    function getSeanceExercices(){
        return $this->seanceExercices;
    }

    function afficheFormSeance(){
    	echo<<<HTML
    		<form class="seance" method="POST">
    			<label class="form-lbl" for="nbRounds">Nombre de rounds</label>
    			<input type="numeric" class="form-input" min="0" max="10" id="nbRounds" name="nbRounds"><br>
    			
    			<label class="form-lbl" for="dureeEchaufMin">Durée de l'échauffement</label>
    			<input type="numeric" class="form-input"  id="dureeEchaufMin" name="dureeEchaufMin" min="0" max="59">
    			:
    			<input type="numeric" class="form-input"  id="dureeEchaufSec" name="dureeEchaufSec" min="0" max="59"><br>

    			<label class="form-lbl" for="dureeCalmeMin">Durée de retour au calme</label>
    			<input type="numeric" class="form-input"  id="dureeCalmeMin" name="dureeCalmeMin" min="0" max="59">
    			:
    			<input type="numeric" class="form-input"  id="dureCalmeSec" name="dureeCalmeSec" min="0" max="59"><br>

    		</form>
HTML;
    }

    private function createPDO(){
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");

        try{
            $pdo = new PDO('mysql:host='.self::DB_HOST, self::DB_USER, self::DB_PASSWORD,$options);
        }catch(PDOException $e){
            $message="erreur de connexion";
        }
        return $pdo;
    }

    public function listeExercicesFromDB(){
        $message="";
        $listeExercices=array();
        $i=0;

        $pdo=$this->createPDO();

        $requete="USE ".self::DB_BASENAME;
        $resultRequete=$pdo->query($requete);

        try{
            $requete="SELECT EXERCICE_intitule FROM EXERCICES;";
            //echo $requete;
            $resultRequete=$pdo->query($requete); 
        }catch (PDOException $e){
            $message="erreur de requête";
        }
        try{
            //recherche si le login saisi existe déja
            while($res = $resultRequete->fetch()){
                //$resultRequete->setFetchMode(PDO::FETCH_ASSOC);
                $listeExercices[$i]=$res['EXERCICE_intitule'];
                $i++;
            } 
        }catch (PDOException $e){
            $message="erreur retour de requête";
        }
        $this->messageErreur=$message;
        return $listeExercices;
        
        
    }

    public function listeExercicesCaloriesFromDB(){
        $message="";
        $listeExercices=array();
        $i=0;

        $pdo=$this->createPDO();

        $requete="USE ".self::DB_BASENAME;
        $resultRequete=$pdo->query($requete);

        try{
            $requete="SELECT EXERCICE_intitule, EXERCICE_calories FROM EXERCICES;";
            //echo $requete;
            $resultRequete=$pdo->query($requete); 
        }catch (PDOException $e){
            $message="erreur de requête";
        }
        try{
            //recherche si le login saisi existe déja
            while($res = $resultRequete->fetch()){
                //$resultRequete->setFetchMode(PDO::FETCH_ASSOC);
                $listeExercices[$i][0]=$res['EXERCICE_intitule'];
                $listeExercices[$i][1]=$res['EXERCICE_calories'];
                $i++;
            } 
        }catch (PDOException $e){
            $message="erreur retour de requête";
        }
        $this->messageErreur=$message;
        return $listeExercices;
        
        
    }


    public static function seanceFromForm(){
        setlocale(LC_TIME, 'fra_fra');
        $nbExercices=0;
        $seanceDureeEchauffement=0;
        $seanceDureeRetourcalme=0;
        $seanceNbTours=0;
        $exercices=array();        
        if(isset($_POST['validerSeance'])){
            $seanceDureeEchauffement=intval($_POST['dureeEchaufMin'])*60+intval($_POST['dureeEchaufSec']);
            $seanceDureeRetourcalme=intval($_POST['dureeCalmeMin'])*60+intval($_POST['dureeCalmeSec']);
            $seanceNbTours=intval($_POST['nbRounds']);
            $nbExercices=intval($_POST['nbExercices']);
        }
        for($i=1;$i<$nbExercices+1;$i++){
            $exoIntitule=$_POST['selectExercice_exercice'.$i];
            $exoTempsEffort=intval($_POST['minutes_exercice'.$i])*60+intval($_POST['secondes_exercice'.$i]);
            $exoTempsRepos=intval($_POST['recupMinutes_exercice'.$i])*60+intval($_POST['recupSecondes_exercice'.$i]);
            $exoRang=intval($_POST['hidden_exercice'.$i]);
            $exoCalories=intval($_POST['calories_exercice'.$i]);
            $exercices[]=new Exercice($exoIntitule,$exoTempsEffort,$exoTempsRepos,$exoRang,$exoCalories);
        }

        $seance=new Seance(strftime('%d/%m/%y'),$seanceDureeEchauffement,$seanceDureeRetourcalme,$seanceNbTours,$exercices);
        return $seance;
    }

    public function seanceDBfromObjet(){
        $pdo=$this->createPDO();

        $requete="USE ".self::DB_BASENAME;
        $resultRequete=$pdo->query($requete);

        //-------------------insertion de la seance dans la table SEANCES 
        try{
            $requete="INSERT INTO SEANCES(SEANCE_date,SEANCE_TempsEchauffement,SEANCE_TempsRetourCalme,SEANCE_nbTours,SEANCE_user_id) VALUES (:dt,:echauff,:calme,:nbTours,1);";
            //echo $requete;
            $resultRequete=$pdo->prepare($requete)->execute(['dt'=>$this->seanceDate,'echauff'=>$this->seanceDureeEchauffement,'calme'=>$this->seanceDureeRetourCalme,'nbTours'=>$this->seanceNbTours]); 
        }catch (PDOException $e){
            $message="erreur de requête";
        }
        echo 'requete seance : '.$requete.'<br>';


        //-------------------insertion des Exercices dans la table CONTIENT, qui stocke les exercices effectués dans la séance 
        $seanceId=$pdo->lastInsertId();

        $requete='INSERT INTO CONTIENT (CONTIENT_seanceId,CONTIENT_exerciceIntitule, CONTIENT_tempsEffort,CONTIENT_tempsRepos,CONTIENT_rangExercice) VALUES ';


        for($i=0;$i<count($this->seanceExercices);$i++){
            $requete.='('.$seanceId.','.$pdo->quote($this->seanceExercices[$i]->getExoIntitule()).','.$this->seanceExercices[$i]->getExoTempsEffort().','.$this->seanceExercices[$i]->getExoTempsRepos().','.$this->seanceExercices[$i]->getExoRang().')';
            if($i<count($this->seanceExercices)-1){
                $requete.=',';
            }
            if($i==count($this->seanceExercices)-1){
                $requete.=';';
            }
        }
        echo 'requete contient : '.$requete.'<br>';
        $resultRequete=$pdo->prepare($requete)->execute();


    }

//<input type="time" class="form-input" step=1 min="00:00:00" max="23:59:59" id="dureeEchauf" name="dureeEchauf">

}

?>