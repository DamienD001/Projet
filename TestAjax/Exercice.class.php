<?php

class Exercice{

	private $exoIntitule;
	private $exoTempsEffort;
	private $exoTpsRepos;
	private $exoRang;
	private $exoCalories;

    const DB_HOST = "localhost";
    const DB_USER = "root";
    const DB_PASSWORD = "";
    const DB_BASENAME = "NFA021_PROJET";

	// const USER_SESSION = "user_session";
 //    const DB_HOST = "localhost";
 //    const DB_USER = "root";
 //    const DB_PASSWORD = "";
 //    const DB_BASENAME = "NFA021_PROJET";


	function __construct(string $exoIntitule="",$exoTempsEffort=0, $exoTempsRepos=0, $exoRang=0,$exoCalories=0){
		$this->exoIntitule=$exoIntitule;
		$this->exoTempsEffort=$exoTempsEffort;
		$this->exoTpsRepos=$exoTempsRepos;
		$this->exoRang=$exoRang;
		$this->exoCalories=$exoCalories;

	}

	function getExoIntitule(){
		return $this->exoIntitule;
	}

	function getExoTempsEffort(){
		return $this->exoTempsEffort;
	}

	function getExoTempsRepos(){
		return $this->exoTpsRepos;
	}

	function getExoRang(){
		return $this->exoRang;
	}

	function getExoCalories(){
		return $this->exoCalories;
	}

	function afficheSelectExercice(){
		echo<<<HTML
			<label class="form-lbl" for="intitule">Exercice</label>
			<select id="intitule" name="intitule">
HTML;

		$listeExo=$this->listeExercices();
		for($i=0;$i<count($listeExo);$i++){
			echo '<option>'.$listeExo[$i].'</option>';
		}

		echo<<<HTML
			</select>
			<label class="form-lbl" for="dureeExerciceMin">Durée de l'exercice</label>
    		<input type="numeric" class="form-input"  id="dureeExerciceMin" name="dureeExerciceMin" min="0" max="59">
    		:
    		<input type="numeric" class="form-input"  id="dureeExerciceSec" name="dureeExerciceSec" min="0" max="59">
HTML;
	}

	public function afficheFormExercice(){
		echo '<form class="exercice" method="POST">';
    	$this->afficheSelectExercice();

    	if(isset($_POST['ajouterExo'])){
    		$this->afficheSelectExercice();
    	}

    	echo '<input type="submit" name="ajouterExo" id="ajouterExo" value="Ajouter un exercice">';
    	echo '</form>';
    	unset($_POST['ajouterExo']);
	}


	public function listeExercices(){
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

	



	private function createPDO(){
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");

        try{
            $pdo = new PDO('mysql:host='.self::DB_HOST, self::DB_USER, self::DB_PASSWORD,$options);
        }catch(PDOException $e){
            $message="erreur de connexion";
        }
        return $pdo;
    }

}

?>
