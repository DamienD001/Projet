<?php	
	spl_autoload_register(function ($class) {
    include $class.'.class.php';   
});
    session_start();
    

        $seance=$_SESSION['seance'];
        if(is_null($seance)){
            echo "seance null";
        }else{
            echo ($_SESSION['seance']->getSeanceDate());
        }

        // try{
        //     $nb=$seance->getSeanceNbTours();
        //     if(is_null($nb)){
        //         echo "null!!!";
        //     }else{
        //         echo "resultat : ".$nb;
        //     }
            
        // }catch(Exception $e){
        //     echo "erreur recup nb";
        // }
        
         

        ?>