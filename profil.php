<?php
require_once("inc/init.inc.php");
//--------------------------------- TRAITEMENTS PHP ---------------------------------//
if(!internauteEstConnecte()) 
{
	header("location:connexion.php");
}

$contenu .= '<div class="cadre"><h2> Voici vos informations de profil </h2>';
$contenu .= '<p> Nom: ' . $_SESSION['membre']['nom'] . '<br/>';
$contenu .= 'Prénom: ' . $_SESSION['membre']['prenom'] . '<br>';
$contenu .= 'E-Mail: ' . $_SESSION['membre']['email'] . '<br>';
$contenu .= 'Ville: ' . $_SESSION['membre']['ville'] . '<br>';
$contenu .= 'Code Postal: ' . $_SESSION['membre']['code_postal'] . '<br>';
$contenu .= 'Adresse: ' . $_SESSION['membre']['adresse'] . '<br>';
$contenu .= 'Solde actuel: ' . $_SESSION['membre']['solde'] . '€</p></div><br /><br />';

$contenu .= '<a href="membres.php">Modifier vos informations de profil</a>';
	
//--------------------------------- AFFICHAGE HTML ---------------------------------//
require_once("inc/haut.inc.php");
echo $contenu;
require_once("inc/bas.inc.php");