<?php
require_once("inc/init.inc.php");

//--------------------------------- TRAITEMENTS PHP ---------------------------------//
//--- VERIFICATION ADMIN ---//
if(!internauteEstConnecteEtEstAdmin())
{
	header("location:connexion.php");
	exit();
}
// validation d'un commentaire
if(isset($_GET['action']) && $_GET['action'] == "validation")
{	// $contenu .= $_GET['id_produit']
	$resultat = executeRequete("SELECT * 
								FROM AVIS 
								WHERE id_avis=$_GET[id_avis]");
	$avis_a_valider = $resultat->fetch_assoc();
	$contenu .= '<div class="validation">Validation de l\'avis numéro : ' . $_GET['id_avis'] . '</div>';
	executeRequete("UPDATE AVIS 
					SET AVIS.statut_avis=1 
					WHERE id_avis=$_GET[id_avis]");
	$_GET['action'] = 'gérerlesCommentaires';
}

//--- SUPPRESSION PRODUIT ---//
if(isset($_GET['action']) && $_GET['action'] == "suppression")
{	// $contenu .= $_GET['id_produit']
	$resultat = executeRequete("SELECT * 
								FROM avis 
								WHERE id_avis=$_GET[id_avis]");
	$produit_a_supprimer = $resultat->fetch_assoc();
	$contenu .= '<div class="validation">Suppression de l\'avis : ' . $_GET['id_avis'] . '</div>';
	executeRequete("DELETE FROM avis WHERE id_avis=$_GET[id_avis]");
	$_GET['action'] = 'gérerlesCommentaires';
}

//--- ENREGISTREMENT PRODUIT ---//
if(!empty($_POST))
{	// debug($_POST);

	foreach($_POST as $indice => $valeur)
	{
		$_POST[$indice] = htmlEntities(addSlashes($valeur));
	}
	executeRequete("REPLACE INTO membre ( id_membre, pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse, statut, solde, verification) 
					values ('$_POST[id_membre]', '$_POST[pseudo]', '$_POST[mdp]', '$_POST[nom]', '$_POST[prenom]', '$_POST[email]', '$_POST[civilite]', '$_POST[ville]',  '$_POST[code_postal]',  '$_POST[adresse]', '$_POST[statut]', 
					'$_POST[solde]', '$_POST[verification]')");
	$contenu .= '<div class="validation">Le membre a bien été mis-à-jour</div>';
	$_GET['action'] = 'affichage';
}
//--- LIENS PRODUITS ---//
$contenu .= '<a href="?action=affichage">Affichage des membres inscrits</a><br />';
$contenu .= '<a href="?action=gérerlesCommentaires">Modérer les commentaires</a><br /><br /><hr /><br />';

//--- AFFICHAGE PRODUITS ---//
if(isset($_GET['action']) && $_GET['action'] == "affichage")
{
	$resultat = executeRequete("SELECT * FROM membre");
	
	$contenu .= '<h2> Affichage des membres </h2>';
	$contenu .= 'Nombre de membre(s) inscrits : ' . $resultat->num_rows;
	$contenu .= '<table border="1" cellpadding="5"><tr>';
	
	while($colonne = $resultat->fetch_field())
	{    
		$contenu .= '<th>' . $colonne->name . '</th>';
	}
	$contenu .= '<th>Modification</th>';
	$contenu .= '<th>Supression</th>';
	$contenu .= '</tr>';

	while ($ligne = $resultat->fetch_assoc())
	{
		$contenu .= '<tr>';
		foreach ($ligne as $indice => $information)
		{
			if($indice == "photo")
			{
				$contenu .= '<td><img src="' . $information . '" width="70" height="70" /></td>';
			}
			else
			{
				$contenu .= '<td>' . $information . '</td>';
			}
		}
		$contenu .= '<td><a href="?action=modification&id_membre=' . $ligne['id_membre'] .'"><img src="inc/img/edit.png" /></a></td>';
		$contenu .= '<td><a href="?action=suppression&id_membre=' . $ligne['id_membre'] .'" OnClick="return(confirm(\'En êtes vous certain ?\'));"><img src="inc/img/delete.png" /></a></td>';
		$contenu .= '</tr>';
	}
	$contenu .= '</table><br /><hr /><br />';
}
// affichage des comentaires à modérer sur le site
if(isset($_GET['action']) && $_GET['action'] == "gérerlesCommentaires")
{
	$contenu .= '<h1> Voici les avis non-modérés présents sur le site </h1>';
	$contenu .= '<table border="1"><tr>';
	
	 $contenu_avis = executeRequete("SELECT id_avis, pseudo_membre, date_achat, contenu_avis
						 FROM avis AS A
						 WHERE statut_avis = 0");
if ($contenu_avis->num_rows <= 0){

	$contenu .= 'Il n\'y a aucun avis à modérer en cours.';
}
else {
	$contenu .= "Nombre d'avis : " . $contenu_avis->num_rows;
	$contenu .= "<table style='border-color:black' border=10> <tr>";
	while($colonne = $contenu_avis->fetch_field())
	{    
		$contenu .= '<th>' . $colonne->name . '</th>';
	}
	$contenu .= "</tr>";
	$chiffre_affaire = 0;
	while ($avis = $contenu_avis->fetch_assoc())
	{
		$contenu .= '<div>';
		$contenu .= '<tr>';
		$contenu .= '<td>' . $avis['id_avis'] . '</td>';
		$contenu .= '<td>' . $avis['pseudo_membre'] . '</td>';
		$contenu .= '<td>' . $avis['date_achat'] . '</td>';
		$contenu .= '<td>' . $avis['contenu_avis'] . '</td>';
		$contenu .= '<td><a href="?action=validation&id_avis=' . $avis['id_avis'] .'" OnClick="return(confirm(\'Valider ce commentaire ?\'));"><img src="inc/img/valider.jpg" /></a></td>';
		$contenu .= '<td><a href="?action=suppression&id_avis=' . $avis['id_avis'] .'" OnClick="return(confirm(\'En êtes vous certain ?\'));"><img src="inc/img/delete.png" /></a></td>';
		$contenu .= '</tr>	';
		$contenu .= '</div>';
	}
	$contenu .= '</table><br />';
}
}
//--------------------------------- AFFICHAGE HTML ---------------------------------//
require_once("inc/haut.inc.php");
echo $contenu;
if(isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification'))
{
	if(isset($_GET['id_membre']))
	{
		$resultat = executeRequete("SELECT * FROM membre WHERE id_membre=$_GET[id_membre]");
		$produit_actuel = $resultat->fetch_assoc();
	}
	echo '
	<h1> Modification d\'un membre </h1>
	<form method="post" enctype="multipart/form-data" action=""><br/>

	<input type="hidden" id="id_membre" name="id_membre" value="'; 
	if(isset($produit_actuel['id_membre'])) echo $produit_actuel['id_membre']; echo '" />

	<label for="pseudo">Pseudo</label><br/>
	<input type="hidden" id="id_membre" name="id_produit" value="'; 
		if(isset($produit_actuel['id_membre'])) echo $produit_actuel['id_membre']; echo '" />
    <input type="text" id="pseudo" name="pseudo" maxlength="20" placeholder="Pseudo" pattern="[a-zA-Z0-9-_.]{1,20}" title="caractères acceptés : a-zA-Z0-9-_." required="required"value="';
		if(isset($produit_actuel['pseudo'])) echo $produit_actuel['pseudo']; echo '" /><br/>

    <label for="mdp">Mot de passe</label><br/>
	<input type="password" id="mdp" name="mdp" required="required" value="'; 
		if(isset($produit_actuel['mdp'])) echo $produit_actuel['mdp']; echo '" /><br/>
         
    <label for="nom">Nom</label><br/>
    <input type="text" id="nom" name="nom" placeholder="nom"value="'; 
	if(isset($produit_actuel['nom'])) echo $produit_actuel['nom']; echo '" /><br/>

    <label for="prenom">Prénom</label><br/>
    <input type="text" id="prenom" name="prenom" placeholder="prénom"value="'; 
		if(isset($produit_actuel['prenom'])) echo $produit_actuel['prenom']; echo '" /><br/>

    <label for="email">Email</label><br/>
    <input type="email" id="email" name="email" placeholder="exemple@gmail.com"value="'; 
		if(isset($produit_actuel['email'])) echo $produit_actuel['email']; echo '" /><br/><br/>

	<input type="radio" name="civilite" value="m"'; 
		if(isset($produit_actuel) && $produit_actuel['civilite'] == 'm') echo ' checked '; 
		elseif(!isset($produit_actuel) && !isset($_POST['civilite'])) echo 'checked'; echo '>Homme
	<input type="radio" name="civilite" value="f"'; 
		if(isset($produit_actuel) && $produit_actuel['civilite'] == 'f') echo ' checked '; echo '>Femme<br><br>
                 
    <label for="ville">Ville</label><br/>
    <input type="text" id="ville" name="ville" placeholder="votre ville" value="'; 
	if(isset($produit_actuel['ville'])) echo $produit_actuel['ville']; echo '" /><br/>
         
    <label for="cp">Code Postal</label><br/>
    <input type="text" id="code_postal" name="code_postal" placeholder="code postal" pattern="[0-9]{5}" title="5 chiffres requis : 0-9"value="'; 
	if(isset($produit_actuel['code_postal'])) echo $produit_actuel['code_postal']; echo '" /><br/>
         
    <label for="adresse">Adresse</label><br/>
    <textarea id="adresse" name="adresse" placeholder="votre adresse">'; 
	if(isset($produit_actuel['adresse'])) echo $produit_actuel['adresse']; echo '</textarea><br /><br />

	<input type="hidden" id="statut" name="statut" value="'; 
	if(isset($produit_actuel['statut'])) echo $produit_actuel['statut']; echo '" />
		
	<label for="cp">Solde disponible</label><br/>
    <input type="number" id="solde" name="solde" placeholder="solde disponible" value="'; 
	if(isset($produit_actuel['solde'])) echo $produit_actuel['solde']; echo '" /><br/>

	<input type="hidden" id="verification" name="verification" value="'; 
	if(isset($produit_actuel['verification'])) echo $produit_actuel['verification']; echo '" />
 
    <input type="submit" value="'; echo ucfirst($_GET['action']) . ' du membre"/>
</form>';

}

require_once("inc/bas.inc.php"); ?>