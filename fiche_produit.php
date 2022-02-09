<?php
require_once("inc/init.inc.php");
$datedujour=date("Y-m-d");

//--------------------------------- TRAITEMENTS PHP ---------------------------------//
if(isset($_GET['id_produit'])) 	{ $resultat = executeRequete("SELECT * FROM produit WHERE id_produit = '$_GET[id_produit]'"); }
if($resultat->num_rows <= 0) { header("location:boutique.php"); exit(); }
$produit = $resultat->fetch_assoc();

// enregistrement d'un commentaire
if(!empty($_POST))
{	// debug($_POST);

	foreach($_POST as $indice => $valeur)
	{
		$_POST[$indice] = htmlEntities(addSlashes($valeur));
	}
	executeRequete("INSERT INTO avis ( pseudo_membre, contenu_avis, id_produit, date_achat) 
					values ('".$_SESSION['membre']['pseudo']."', '$_POST[contenu_avis]', '$produit[id_produit]', $datedujour)");
	$contenu .= '<div class="validation">Votre avis à bien été envoyé et devrait apparaître d\'ici quelques jours.</div>';
	$_GET['action'] = 'affichage';
}

$contenu .= "<h3>Titre : $produit[titre]</h3><hr /><br />";
$contenu .= "<p>Categorie: $produit[categorie]</p>";
$contenu .= "<img src='$produit[photo]' width='150' height='150' />";
$contenu .= "<p><i>Description: $produit[description]</i></p><br />";
$contenu .= "<p>Prix : $produit[prix] €</p><br />";

if($produit['stock'] > 0)
{
	$contenu .= "<i>Nombre de produit(s) disponible : $produit[stock] </i><br /><br />";
	$contenu .= '<form method="post" action="panier.php">';
		$contenu .= "<input type='hidden' name='id_produit' value='$produit[id_produit]' />";
		$contenu .= '<label for="quantite">Quantité : </label>';
		$contenu .= '<select id="quantite" name="quantite">';
			for($i = 1; $i <= $produit['stock'] && $i <= 5; $i++)
			{
				$contenu .= "<option>$i</option>";
			}
		$contenu .= '</select>';
		$contenu .= '<input type="submit" name="ajout_panier" value="ajout au panier" />';
	$contenu .= '</form>';
}
else
{
	$contenu .= 'Rupture de stock !';
}

// affichage des avis sur le produit

 $contenu_avis = executeRequete("SELECT pseudo_membre, date_achat, contenu_avis
						 FROM avis AS A
						 INNER JOIN membre AS M
						 ON A.pseudo_membre=M.pseudo
						 INNER JOIN produit AS P
						 ON A.id_produit=P.id_produit
						 WHERE A.id_produit='$produit[id_produit]'
						 AND A.statut_avis=1");

if($contenu_avis->num_rows > 0)
{
$nombre_avis=$contenu_avis->num_rows;
$contenu .="<br/><strong>Nonbre d'avis sur : $produit[titre], $nombre_avis avis.  </strong><hr/><br/>"; 
$avis = $contenu_avis->fetch_assoc();
$contenu .= "<strong><br/>De : $avis[pseudo_membre] </strong><br />";
$contenu .= "<p>Le : $avis[date_achat]</p><br/>";
$contenu .= "<p><i> $avis[contenu_avis]</i></p><br/><hr/><br />";
}


//--------------------------------- AFFICHAGE HTML ---------------------------------//
require_once("inc/haut.inc.php");
echo $contenu;
if(internauteEstConnecte())
{
	echo '
	<Strong>Laisser un commentaire</strong> <br/>
	<form method="post" enctype="multipart/form-data" action="">
	
		<input type="hidden" id="id_avis" name="id_avis" value="'; 
		echo '" />
			
		<label for="pseudo">Pseudo</label>
				<input disabled type="text" id="pseudo_membre" name="pseudo_membre" value="'; print $_SESSION['membre']['pseudo']; 
		echo '"/><br />

		<label for="avis">Avis</label><br />
		<textarea name="contenu_avis" id="contenu_avis" placeholder="Votre avis sur le produit">'; 
		echo '</textarea><br /><br />

		<input name="Poster" value="Mettre en ligne votre avis" type="submit">
		</form>';
}

echo "<br /><a href='boutique.php?categorie=" . $produit['categorie'] . "'>Retour vers la sélection de " . $produit['categorie'] . "</a>";

require_once("inc/bas.inc.php");