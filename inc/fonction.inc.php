<?php
function executeRequete($req)
{
	global $mysqli; 
	$resultat = $mysqli->query($req); 
	if (!$resultat) 
	{
		die("Erreur sur la requete sql.<br />Message : " . $mysqli->error . "<br />Code: " . $req);
	}
	return $resultat;
}
//------------------------------------
function debug($var, $mode = 1) 
{
		echo '<div style="background: orange; padding: 5px; float: right; clear: both; ">';
		$trace = debug_backtrace(); 
		$trace = array_shift($trace);
		echo "Debug demandé dans le fichier : $trace[file] à la ligne $trace[line].<hr />";
		if($mode === 1)
		{
			echo "<pre>"; print_r($var); echo "</pre>";
		}
		else
		{
			echo "<pre>"; var_dump($var); echo "</pre>";
		}
	echo '</div>';
}
//------------------------------------
function internauteEstConnecte()
{  
	if(!isset($_SESSION['membre']) /*&& !internauteVerifie()*/) 
	{
		return false;
	}
	else
	{
		return true;
	}
}

function internauteEstConnecteEtEstPasAdmin()
{ 
	if(internauteEstConnecte() && $_SESSION['membre']['statut'] == 0) 
	{
			return true;
	}
	return false;
}
//------------------------------------
function internauteEstConnecteEtEstAdmin()
{ 
	if(internauteEstConnecte() && $_SESSION['membre']['statut'] >= 1) 
	{
			return true;
	}
	return false;
}
function internauteEstConnecteEtEstAdministratif()
{ 
	if(internauteEstConnecte() && $_SESSION['membre']['statut'] == 1) 
	{
			return true;
	}
	return false;
}
function internauteEstConnecteEtEstRestaurateur()
{ 
	if(internauteEstConnecte() && $_SESSION['membre']['statut'] == 2) 
	{
			return true;
	}
	return false;
}

/*function internauteVerifie()
{ 
	if($_SESSION['membre']['verification'] == 1) 
	{
			return true;
	}
	return false;
	echo 'Votre profil n\'a pas encore été vérifé, veuillez réessayer plus tard.';
}*/

function creationDuPanier()
{
   if (!isset($_SESSION['panier']))
   {
      $_SESSION['panier']=array();
      $_SESSION['panier']['titre'] = array();
      $_SESSION['panier']['id_produit'] = array();
      $_SESSION['panier']['quantite'] = array();
      $_SESSION['panier']['prix'] = array();
   }
}

function ajouterProduitDansPanier($titre,$id_produit,$quantite,$prix)
{
	creationDuPanier(); 
    $position_produit = array_search($id_produit,  $_SESSION['panier']['id_produit']); 
    if ($position_produit !== false)
    {
         $_SESSION['panier']['quantite'][$position_produit] += $quantite ;
    }
    else 
    {
        $_SESSION['panier']['titre'][] = $titre;
        $_SESSION['panier']['id_produit'][] = $id_produit;
        $_SESSION['panier']['quantite'][] = $quantite;
		$_SESSION['panier']['prix'][] = $prix;
    }
}
//------------------------------------
function montantTotal()
{
   $total=0;
   for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) 
   {
      $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
   }
   return round($total,2);
}

function calculSoldeRestant()
{
	$membreConnectesolde = executeRequete("SELECT solde 
										  FROM membre 
										  WHERE id_membre=".$_SESSION['membre']['id_membre']."");
	$total=0;
	for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) 
	{
	   $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];	   
	   $soldeRestant=$_SESSION['membre']['solde']-$total;
	}
	
	return 'Solde restant après commande : ' .$soldeRestant. '€';
}
function remplacerSoldeUtilisateur()//remplace le solde de l'utilisateur par celui restant après la commande
{
	$total=0;
	for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) 
	{
	   $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];	   
	   $soldeRestant=$_SESSION['membre']['solde']-$total;
	   $_SESSION['membre']['solde']=$soldeRestant;
	   executeRequete("
	   UPDATE membre 
	   SET solde=$soldeRestant 
	   WHERE id_membre=".$_SESSION['membre']['id_membre']."");
	
	}
	$_SESSION['membre']['solde']=$soldeRestant;
	return 'Votre solde actuel est maintenant de '.$soldeRestant. '€.';
}

function retirerproduitDuPanier($id_produit_a_supprimer)
{
	$position_produit = array_search($id_produit_a_supprimer,  $_SESSION['panier']['id_produit']);
	if ($position_produit !== false)
    {
		array_splice($_SESSION['panier']['titre'], $position_produit, 1);
		array_splice($_SESSION['panier']['id_produit'], $position_produit, 1);
		array_splice($_SESSION['panier']['quantite'], $position_produit, 1);
		array_splice($_SESSION['panier']['prix'], $position_produit, 1);
	}
}

