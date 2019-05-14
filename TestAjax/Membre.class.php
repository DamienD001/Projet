<?php 

class Membre{

	private $_id;
	private $_nom;
	private $_prenom;
	//private $_fonction;
	private $_email;
	//private $_dateNaiss;
	private $_mot_de_passe;
	private $_re_mot_de_passe;
	private $_statut;
    private $messageErreur;

	const USER_SESSION = "user_session";
    const DB_HOST = "localhost";
    const DB_USER = "root";
    const DB_PASSWORD = "";
    const DB_BASENAME = "NFA021_PROJET";
    


    //-----------------le constructeur----------------------
    public function __construct(string $ident = "", string $nam="", string $forenam="", string $adresseMail = "",string $pwd = "",string $repPwd = "",string $statut = "UTILISATEUR") {
        $this->_id = $ident;
        $this->_nom = $nam;
		$this->_prenom = $forenam;
        $this->_email = $adresseMail;
        $this->_mot_de_passe = $pwd;
        $this->_re_mot_de_passe = $repPwd;
        $this->_statut = $statut;
        $this->messageErreur='';
    }

    //-----------------les getters---------------------
    public function getId() : string {
        return $this->_id;
    }

	public function getName() : string {
        return $this->_nom;
    }

    public function getForename() : string {
        return $this->_prenom;
    }

	public function getEmail() : string {
        return $this->_email;
    }

    public function getMotDePasse() : string {
        return $this->_mot_de_passe;
    }

    public function getReMotDePasse() : string {
        return $this->_re_mot_de_passe;
    }

	public function getStatut() : string {
        return $this->_statut;
    }

 	public function getMessageErreur() : string {
        return $this->messageErreur;
    }

    //----------------fin des getters------------------------


    public function afficheFormInscription(){
    	echo<<<HTML
    	<form class="formulaire" method="POST" action="">
			<fieldset>
				<legend><h1>S'enregistrer</h1></legend>
				<input type="text" name="nom" placeholder="nom" required>
				<div id="messageNom"></div>
				<input type="text" name="prenom" placeholder="prenom" required>
				<div id="messagePrenom"></div>
				
				<input type="email" name="adrMail" placeholder="adrMail" required>
				<div id="messageAdrMail"></div>
				
				<input type="password" name="pwd" placeholder="pwd" required>
				<div id="messagePwd"></div>
				<input type="password" name="repeatPwd" placeholder="repeatPwd" required>
				<div id="messageRepeatPwd"></div>
				<div class="formulaire_Boutons">		
					<input type="submit" class="bouton" name="envoyer" value="Envoyer">
					<input type="reset" class="bouton" name="effacer" value="Effacer">
				</div>
			</fieldset>
		</form>
HTML;
    }

	public function afficheFormAuthentification(){
    	echo<<<HTML
    	<form class="formulaire" method="POST" action="">
			<fieldset>
				<legend><h1>Se connecter</h1></legend>			
				<input type="email" name="adrMail" placeholder="adrMail" required>
				<div id="messageAdrMail"></div>
				<input type="password" name="pwd" placeholder="pwd" required>
				<div id="messagePwd"></div>
				<div class="formulaire_Boutons">		
					<input type="submit" class="bouton" name="envoyer" value="Envoyer">
					<input type="reset" class="bouton" name="effacer" value="Effacer">
				</div>
			</fieldset>
		</form>
HTML;
    }

    public static function objetFromFormAuthentification(){
		$adresseMail = "";
        $motDePasse = "";       
        if(isset($_POST['adrMail']))
            $adresseMail = $_POST['adrMail'];
        if(isset($_POST['pwd']))
            $motDePasse = $_POST['pwd'];
        
        return new Membre("","","",$adresseMail,$motDePasse,"");
    }

	public static function objetFromFormInscription(){
		$nom='';
		$prenom='';
		$fonction='';
		$adrMail='';
		$dateNaiss='';
		$pwd='';
		$repeatPwd='';

		if(isset($_POST['nom'])){
			$nom=$_POST['nom'];
		}
		if(isset($_POST['prenom'])){
			$prenom=$_POST['prenom'];
		}
		
		if(isset($_POST['adrMail'])){
			$adrMail=$_POST['adrMail'];
		}
		
		if(isset($_POST['pwd'])){
			$pwd=$_POST['pwd'];
		}
		if(isset($_POST['repeatPwd'])){
			$repeatPwd=$_POST['repeatPwd'];
		}

		return new Membre("",$nom,$prenom,$adrMail,$pwd,$repeatPwd,"USER");
    }

    public function afficheInfosMembre(){
    	echo"<p>";
    	echo $this->_nom."<br>";
		echo $this->_prenom."<br>";
		echo $this->_email."<br>";
		echo $this->_mot_de_passe."<br>";
		echo $this->_re_mot_de_passe."<br>";		
		echo"</p>";
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

	// public function connexion(){
	// 	$requete="USE ".self::DB_BASENAME.";";
		
 //        try{
 //        	$pdo=$this->createPDO();
 //            $resultRequete=$pdo->query($requete);
 //        }catch(PDOException $e){
 //            $this->messageErreur="erreur de sélection de la base";
 //        }
	// 	return $resultRequete;
	// }


	function checkAdrMailInscription():bool{
		$trouve=false;
		$message="";

		$pdo=$this->createPDO();

		$requete="USE ".self::DB_BASENAME;
		$resultRequete=$pdo->query($requete);

		$adresse=$this->_email;

		try{
			$adresseQuote=$pdo->quote($adresse);
            $requete="SELECT USER_adresseMail FROM UTILISATEURS WHERE USER_adresseMail IN (".$adresseQuote.")";
            $resultRequete=$pdo->query($requete); 
        }catch (PDOException $e){
            $message="erreur de requête";
        }
        try{
            //recherche si le login saisi existe déja
            while(($res = $resultRequete->fetch()) && $trouve!=true){
        		$resultRequete->setFetchMode(PDO::FETCH_ASSOC);
                if($res['USER_adresseMail']===$adresse){
                    $trouve=true;
                    $message="l'adresse ".$res['USER_adresseMail']." existe<br>";
                }else{
                    $trouve=false;
                    $message="OK, l'adresse ".$res['USER_adresseMail']." n'existe pas<br>";
                }
            } 
        }catch (PDOException $e){
            $message="erreur retour de requête";
        }
        $this->messageErreur=$message;
        return (!$trouve);
	}

    //-----------------vérification du mot de passe
	public function checkPasswordInscription(){
		$okPwd=true;
		$pwd=$this->_mot_de_passe;
		$rePwd=$this->_re_mot_de_passe;

		if(strlen($pwd)<8){
			$okPwd=false;
			$this->messageErreur="le mot de passe doit comporter au moins 8 caractères<br>";
		}
		if($rePwd!=$pwd){
			$okPwd=false;
			$this->messageErreur.="la confirmation du mot de passe a échoué<br>";
		}
		return $okPwd;
	}


	public function checkConnexion(){
		
		$valideConnection=false;
		$message="connexion impossible";

		$pdo=$this->createPDO();

		$requete="USE ".self::DB_BASENAME;
		$resultRequete=$pdo->query($requete);

		$adresse=$this->_email;
		$pwd=sha1($this->_mot_de_passe);
        //echo $adresse." / ".$pwd;

		try{
			$adresseQuote=$pdo->quote($adresse);
			$pwdQuote=$pdo->quote($pwd);
            $requete="SELECT USER_adresseMail,USER_motDePasse FROM UTILISATEURS WHERE USER_adresseMail IN (".$adresseQuote.")";
            $resultRequete=$pdo->query($requete); 
        }catch (PDOException $e){
            $message="erreur de requête";
        }
        try{
            //recherche si le login saisi existe déja
            while(($res = $resultRequete->fetch()) && $valideConnection!=true){
        		$resultRequete->setFetchMode(PDO::FETCH_ASSOC);
                if($res['USER_adresseMail']===$adresse && $res['USER_motDePasse']===$pwd){
                    $valideConnection=true;
                    $message="la connexion au compte ".$res['USER_adresseMail']."  est accordée<br>";
                }else{
                    $valideConnection=false;
                    $message="connexion au compte ".$res['USER_adresseMail']."/".$adresse." refusée<br>";
                }
            } 
        }catch (PDOException $e){
            $message="erreur retour de requête";
        }
        $this->messageErreur=$message;
        return $valideConnection;
	}


	public function inscription(){

		$pdo=$this->createPDO();

		$requete="USE ".self::DB_BASENAME;
		$resultRequete=$pdo->query($requete);

		$nomQuote=$pdo->quote($this->_nom);
		$prenomQuote=$pdo->quote($this->_prenom);
		$adrQuote=$pdo->quote($this->_email);
		$pwdQuote=$pdo->quote(sha1($this->_mot_de_passe));
		$statutQuote=$pdo->quote("UTILISATEUR");

		$requete=<<<SQL
	        INSERT INTO UTILISATEURS (USER_nom, USER_prenom, USER_adresseMail,USER_motDePasse,USER_statut) VALUES ($nomQuote,$prenomQuote,$adrQuote,$pwdQuote,$statutQuote);
SQL;
		try{
			echo $requete;
			$pdo->prepare($requete)->execute();
		}catch(PDOException $e){
	        $this->messageErreur="erreur d'écriture";
	    }
    }

    public function membreFromBDD(){
    	$pdo=$this->createPDO();
		$adresse=$this->getEmail();
    	$adresseQuote=$pdo->quote($adresse);
    	
    	$requete="USE ".self::DB_BASENAME;
		$resultRequete=$pdo->query($requete);

		try{
            $requete="SELECT * FROM UTILISATEURS WHERE USER_adresseMail IN (".$adresseQuote.")";    
            $resultRequete=$pdo->query($requete);
            //$resultRequete=$pdo->prepare($requete)->execute();

        }catch (PDOException $e){
            $message="erreur de requête";
        }
        $resultRequete->setFetchMode(PDO::FETCH_ASSOC);
		while(($res = $resultRequete->fetch())){
            $newMembre=new Membre($res['USER_id'],$res['USER_nom'],$res['USER_prenom'],$res['USER_adresseMail'],"","",$res['USER_statut']);
        } 
        return $newMembre;

    }

    public function listeUtilisateurs():array{

    	$pdo=$this->createPDO();

		$requete="USE ".self::DB_BASENAME;
		$resultRequete=$pdo->query($requete);

		try{
            $requete="SELECT * FROM UTILISATEURS";    
            $resultRequete=$pdo->query($requete);
        }catch (PDOException $e){
            $message="erreur de requête";
        }
        $resultRequete->setFetchMode(PDO::FETCH_ASSOC);
        //crée un tableau contenant tous les résultats de la requête
        $tableauUtilisateurs=array();
        try{
            $i=0;
            while(($res = $resultRequete->fetch())){
                $tableauUtilisateurs[$i]=new Membre("",$res['USER_nom'],$res['USER_prenom'],$res['USER_adresseMail'],"","",$res['USER_statut']);
                $i++;
            } 
        }catch (PDOException $e){
            $message="erreur retour de requête";

        }
        return $tableauUtilisateurs;


    }


     //connecte l'utilisateur
    public function connexion() : void {
        // $_SESSION['compteur']=0;
        $_SESSION[self::USER_SESSION] = $this;
    }
    
    //déconnecte l'utilisateur
    public static function deconnexion() : void {
        // $_SESSION['compteur']=0;
        $_SESSION[self::USER_SESSION] = null;
        unset($_SESSION[self::USER_SESSION]);

    }
    
    //teste si l'utilisateur est connecté
    public static function estConnecte() : bool {
        return isset($_SESSION[self::USER_SESSION]) && ($_SESSION[self::USER_SESSION] != null);
    }




}

?>