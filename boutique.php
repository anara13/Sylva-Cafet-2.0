<?php
require_once("inc/init.inc.php");

//affichage des différentes catégories
$categories_des_produits = executeRequete("SELECT DISTINCT categorie 
										   FROM produit");
$contenu .= '<div class="boutique-gauche">';
$contenu .= "<ul>";
while($cat = $categories_des_produits->fetch_assoc())
{
	$contenu .= "<li><a href='?categorie="	. $cat['categorie'] . "'>" . $cat['categorie'] . "</a></li>";
}
$contenu .= "</ul>";
$contenu .= "</div>";
//affichage des produits
$contenu .= '<div class="boutique-droite">';
if(isset($_GET['categorie']))
{
	$donnees = executeRequete("SELECT id_produit,reference,titre,photo,prix 
							   FROM produit 
							   WHERE categorie='$_GET[categorie]'");	
	while($produit = $donnees->fetch_assoc())
	{
		$contenu .= '<div class="boutique-produit">';
		$contenu .= "<h3>$produit[titre]</h3>";
		$contenu .= "<a href=\"fiche_produit.php?id_produit=$produit[id_produit]\"><img src=\"$produit[photo]\" width=\"130\" height=\"100\" /></a>";
		$contenu .= "<p>$produit[prix] €</p>";
		$contenu .= '<a href="fiche_produit.php?id_produit=' . $produit['id_produit'] . '">Voir la fiche</a>';
		$contenu .= '</div>';
	}
}
$contenu .= '</div>';
//affichage html
require_once("inc/haut.inc.php");
echo $contenu;
require_once("inc/bas.inc.php");