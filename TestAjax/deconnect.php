<?php
spl_autoload_register(function ($class) {
    include $class.'.class.php';
});

session_start();

Membre::deconnexion();

header("Location: accueil.php");

?>