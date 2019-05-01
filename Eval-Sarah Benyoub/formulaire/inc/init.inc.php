<?php

// --- Ouverture de session :
session_start();

// --- Connexion à la base de données :
$pdo = new PDO('mysql:host=localhost;dbname=immobilier', 'root','', array(
	PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
	PDO::MYSQL_ATTR_INIT_COMMAND =>'SET NAMES utf8',
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
	)
);

// --- Variables :
$error = '';
$html = '';

// --- Chemins :
define('RACINE_SITE', '/Eval-Sarah Benyoub/formulaire/');
define('RACINE_SERVEUR', $_SERVER['DOCUMENT_ROOT']);

// --- Autres inclusions :
require_once('fonctions.inc.php');
?>