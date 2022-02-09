
<?php
require_once("inc/init.inc.php");
if(!internauteEstConnecte())
{
	header("location:connexion.php");
	exit();
}
//-------------------------------------------------- Affichage ---------------------------------------------------------//
require_once("inc/haut.inc.php");


	echo '<h1> Voici vos commandes passées sur le site </h1>';
	echo '<table border="1"><tr>';
	
	$information_sur_les_commandes = executeRequete("SELECT *
													FROM COMMANDE 
													WHERE id_membre=".$_SESSION['membre']['id_membre']."");
	echo "Nombre de commande(s) : " . $information_sur_les_commandes->num_rows;
	echo "<table style='border-color:black' border=10> <tr>";
	while($colonne = $information_sur_les_commandes->fetch_field())
	{    
		echo '<th>' . $colonne->name . '</th>';
	}
	

	while ($commande = $information_sur_les_commandes->fetch_assoc())
	{
		echo '<div>';
		echo '<tr>';
		echo '<td><a href="historique.php?suivi=' . $commande['id_commande'] . '">Voir la commande ' . $commande['id_commande'] . '</a></td>';
		echo '<td>' . $commande['id_membre'] . '</td>';
		echo '<td>' . $commande['montant'] . '</td>';
		echo '<td>' . $commande['date_enregistrement'] . '</td>';
		echo '<td>' . $commande['etat'] . '</td>';
		echo '</tr>	';
		echo '</div>';
	}

	echo '<br />';
	echo '<p>';
	if(isset($_GET['suivi']))
	{	
		echo '<strong> Voici le détail pour une commande</strong>';
		echo '<table border="1">';
		echo '<tr>';
		$information_sur_une_commande = executeRequete("SELECT D.*, P.titre, P.photo 
														FROM details_commande AS D
														INNER JOIN PRODUIT AS P
														ON D.id_produit = P.id_produit
														WHERE id_commande=$_GET[suivi]");
		
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
				echo '<td>' . $details_commande['titre'] . '</td>';
				echo '<td><img src="' . $details_commande['photo'] . '" width="70" height="70"/></td>';
			echo '</tr>';
		}
		echo '</p></table>';
	} ?>