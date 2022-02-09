<?php
require_once("inc/init.inc.php");
if(!internauteEstConnecteEtEstAdmin())
{
	header("location:connexion.php");
	exit();
}
//validation d'un produit
if(isset($_GET['action']) && $_GET['action'] == "validation")
{	// $contenu .= $_GET['id_produit']
	$resultat = executeRequete("SELECT * 
								FROM COMMANDE 
								WHERE id_commande=$_GET[id_commande]");
	$commande_a_valider = $resultat->fetch_assoc();
	$contenu .= '<div class="validation">Validation de la commande : ' . $_GET['id_commande'] . '</div>';
	executeRequete("UPDATE COMMANDE 
					SET COMMANDE.etat='traité' 
					WHERE id_commande=$_GET[id_commande]");
	$_GET['action'] = 'commandesEnCours';
}
//clôturer la journée
if(isset($_GET['action']) && $_GET['action'] == "suppression")
{	// $contenu .= $_GET['id_produit']
	$resultat = executeRequete("SELECT * 
								FROM COMMANDE ");
	$produit_a_supprimer = $resultat->fetch_assoc();
	
	$contenu .= '<div class="validation">Suppression du produit : ' . $_GET['id_produit'] . '</div>';
	executeRequete("INSERT INTO");
	$_GET['action'] = 'affichage';
}
//-------------------------------------------------- Affichage ---------------------------------------------------------//
require_once("inc/haut.inc.php");

echo $contenu;
echo '<a href="?action=toutesLesCommandes">Affichage de toutes les commandes</a><br />';
echo '<a href="?action=commandesEnCours">Affichage des commandes en cours</a><br /><br />';
echo '<a href="?action=gérerlesCommentaires">Modérer les commentaires</a><br /><br /><hr /><br />';




if(isset($_GET['action']) && $_GET['action'] == "toutesLesCommandes")
{
	echo '<h1> Voici les commandes passées sur le site </h1>';
	echo '<table border="1"><tr>';
	
	$information_sur_les_commandes = executeRequete("SELECT C.*, M.pseudo, M.adresse, M.ville, M.code_postal
													FROM COMMANDE AS C
													INNER JOIN MEMBRE AS M 
													ON  M.id_membre = C.id_membre");
	echo "Nombre de commande(s) : " . $information_sur_les_commandes->num_rows;
	echo "<table style='border-color:black' border=10> <tr>";
	while($colonne = $information_sur_les_commandes->fetch_field())
	{    
		echo '<th>' . $colonne->name . '</th>';
	}
	echo "</tr>";
	$chiffre_affaire = 0;
	while ($commande = $information_sur_les_commandes->fetch_assoc())
	{
		$chiffre_affaire += $commande['montant'];
		echo '<div>';
		echo '<tr>';
		echo '<td><a href="gestion_commande.php?suivi=' . $commande['id_commande'] . '">Voir la commande ' . $commande['id_commande'] . '</a></td>';
		// echo '<td>' . $commande['id_membre'] . '</td>';
		echo '<td>' . $commande['montant'] . '</td>';
		echo '<td>' . $commande['date_enregistrement'] . '</td>';
		echo '<td>' . $commande['etat'] . '</td>';
		echo '<td>' . $commande['pseudo'] . '</td>';
		// echo '<td>' . $commande['adresse'] . '</td>';
		// echo '<td>' . $commande['ville'] . '</td>';
		// echo '<td>' . $commande['code_postal'] . '</td>';
		echo '<td><a href="gestion_commande.php?suivi=' . $commande['id_commande'] . '">Voir la commande ' . $commande['etat'] . '</a></td>';
		echo '</tr>	';
		echo '</div>';
	}
	echo '</table><br />';
	//echo 'Calcul du montant total des revenus:  <br />';
		//print "le chiffre d'affaires de la societe est de : $chiffre_affaire €"; 
	
	echo '<br />';
	if(isset($_GET['suivi']))
	{	
		echo '<h1> Voici le détail pour une commande</h1>';
		echo '<table border="1">';
		echo '<tr>';
		$information_sur_une_commande = executeRequete("select * from details_commande where id_commande=$_GET[suivi]");
		
		$nbcol = $information_sur_une_commande->field_count;
		echo "<table style='border-color:black' border=10> <tr>";
		for ($i=0; $i < $nbcol; $i++)
		{    
			$colonne = $information_sur_une_commande->fetch_field(); 
			echo '<th>' . $colonne->name . '</th>';
		}
		echo "</tr>";

		while ($details_commande = $information_sur_une_commande->fetch_assoc())
		{
			echo '<tr>';
				echo '<td>' . $details_commande['id_details_commande'] . '</td>';
				echo '<td>' . $details_commande['id_commande'] . '</td>';
				echo '<td>' . $details_commande['id_produit'] . '</td>';
				echo '<td>' . $details_commande['quantite'] . '</td>';
				echo '<td>' . $details_commande['prix'] . '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}
	}

	// afficher les commandes en cours sur le site
	if(isset($_GET['action']) && $_GET['action'] == "commandesEnCours")
{
	echo '<h1> Voici les commandes en cours sur le site </h1>';
	echo '<table border="1"><tr>';
	
	$information_sur_les_commandes = executeRequete("SELECT C.*, M.pseudo, M.adresse, M.ville, M.code_postal
													FROM COMMANDE AS C
													INNER JOIN MEMBRE AS M 
													ON  M.id_membre = C.id_membre
													WHERE C.etat ='en cours de traitement'");
	echo "Nombre de commande(s) : " . $information_sur_les_commandes->num_rows;
	echo "<table style='border-color:black' border=10> <tr>";
	while($colonne = $information_sur_les_commandes->fetch_field())
	{    
		echo '<th>' . $colonne->name . '</th>';
	}
	echo "</tr>";
	$chiffre_affaire = 0;
	while ($commande = $information_sur_les_commandes->fetch_assoc())
	{
		$chiffre_affaire += $commande['montant'];
		echo '<div>';
		echo '<tr>';
		echo '<td><a href="gestion_commande.php?suivi=' . $commande['id_commande'] . '">Voir la commande ' . $commande['id_commande'] . '</a></td>';
		// echo '<td>' . $commande['id_membre'] . '</td>';
		echo '<td>' . $commande['montant'] . '</td>';
		echo '<td>' . $commande['date_enregistrement'] . '</td>';
		echo '<td>' . $commande['etat'] . '</td>';
		echo '<td>' . $commande['pseudo'] . '</td>';
		// echo '<td>' . $commande['adresse'] . '</td>';
		// echo '<td>' . $commande['ville'] . '</td>';
		// echo '<td>' . $commande['code_postal'] . '</td>';
		echo '<td><a href="gestion_commande.php?suivi=' . $commande['id_commande'] . '">Voir la commande ' . $commande['etat'] . '</a></td>';
		echo '<td><a href="?action=validation&id_commande=' . $commande['id_commande'] .'" OnClick="return(confirm(\'Valider la commande ?\'));"><img src="inc/img/valider.jpg" /></a></td>';
		echo '</tr>	';
		echo '</div>';
	}
	echo '</table><br />';


	echo '<br />';
	if(isset($_GET['suivi']))
	{	
		echo '<h1> Voici le détail pour une commande</h1>';
		echo '<table border="1">';
		echo '<tr>';
		$information_sur_une_commande = executeRequete("select * from details_commande where id_commande=$_GET[suivi]");
		
		$nbcol = $information_sur_une_commande->field_count;
		echo "<table style='border-color:black' border=10> <tr>";
		for ($i=0; $i < $nbcol; $i++)
		{    
			$colonne = $information_sur_une_commande->fetch_field(); 
			echo '<th>' . $colonne->name . '</th>';
		}
		echo "</tr>";

		while ($details_commande = $information_sur_une_commande->fetch_assoc())
		{
			echo '<tr>';
				echo '<td>' . $details_commande['id_details_commande'] . '</td>';
				echo '<td>' . $details_commande['id_commande'] . '</td>';
				echo '<td>' . $details_commande['id_produit'] . '</td>';
				echo '<td>' . $details_commande['quantite'] . '</td>';
				echo '<td>' . $details_commande['prix'] . '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}

}
