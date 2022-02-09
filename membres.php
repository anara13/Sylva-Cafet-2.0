<?php
require_once("inc/init.inc.php");
if(!internauteEstConnecte())
{
	header("location:connexion.php");
	exit();
}
$msg="";
if($_POST)
{
	if(!empty($_POST['mdp']))
	{
		executeRequete("update membre SET mdp='$_POST[mdp]', nom='$_POST[nom]', prenom='$_POST[prenom]', email='$_POST[email]', civilite='$_POST[civilite]', ville='$_POST[ville]', code_postal='$_POST[code_postal]', adresse='$_POST[adresse]' where id_membre='".$_SESSION['membre']['id_membre']."'");
		unset($_SESSION['utilisateur']);		
		foreach($membre as $indice => $element)
		{
			if($indice != 'mdp')
			{
				$_SESSION['utilisateur'][$indice] = $element;
			}
			else
			{
				$_SESSION['utilisateur'][$indice] = $_POST['mdp'];
			}
		}
		header("Location:membres.php?action=modif");
	}
	else
	{
		$msg = "<div class='erreur'>Le nouveau mot de passe doit être renseigné !</div>";
	}
}
if(isset($_GET['action']) && $_GET['action'] == 'modif')
{
	$msg = "<div class='validation'>La modification a bien été prise en compte</div>";
}

require_once("inc/haut.inc.php");
//require_once("inc/menu.inc.php");

echo $msg;
?>
		<h2> Modification de vos informations </h2>
		<?php

		?><br /><br />
		<form method="post" enctype="multipart/form-data" action="membres.php">
		<input type="hidden" id="id_membre" name="id_membre" value="<?php if(isset($_SESSION['membre'])) print $_SESSION['membre']['id_membre']; ?>" />
			<label for="pseudo">Pseudo</label>
				<input disabled type="text" id="pseudo" name="pseudo" value="<?php if(isset($_SESSION['membre'])) print $_SESSION['membre']['pseudo']; ?>"/><br />
				<input type="hidden" id="pseudo" name="pseudo" value="<?php if(isset($_SESSION['membre'])) print $_SESSION['membre']['pseudo']; ?>"/>
			
			<label for="mdp">Nouveau Mot de passe</label>
				<input type="text" id="mdp" name="mdp" value="<?php if(isset($mdp)) print $mdp; ?>"/><br /><br />
			
			<label for="nom">Nom</label>
				<input type="text" id="nom" name="nom" value="<?php if(isset($_SESSION['membre'])) print $_SESSION['membre']['nom']; ?>"/><br />
			
			<label for="prenom">Prénom</label>
				<input type="text" id="prenom" name="prenom" value="<?php if(isset($_SESSION['membre'])) print $_SESSION['membre']['prenom']; ?>"/><br />

			<label for="email">Email</label>
				<input type="text" id="email" name="email" value="<?php if(isset($_SESSION['membre'])) print $_SESSION['membre']['email']; ?>"/><br />
			
			<label for="Civilité">Sexe</label>
					<select id="civilite" name="civilite">
						<option value="m" <?php if(isset($_SESSION['membre']['civilite']) && $_SESSION['membre']['civilite'] == "m") print "selected"; ?>>Homme</option>
						<option value="f" <?php if(isset($_SESSION['membre']['civilite']) && $_SESSION['membre']['civilite'] == "f") print "selected"; ?>>Femme</option>
					</select><br />
					
			<label for="ville">Ville</label>
				<input type="text" id="ville" name="ville" value="<?php if(isset($_SESSION['membre'])) print $_SESSION['membre']['ville']; ?>"/><br />
			
		<label for="cp">Code Postal</label>
			<input type="text" id="code_postal" name="code_postal" value="<?php if(isset($_SESSION['membre'])) print $_SESSION['membre']['code_postal']; ?>"/><br />
			
		<label for="adresse">Adresse</label>
					<textarea id="adresse" name="adresse"><?php if(isset($_SESSION['membre'])) print $_SESSION['membre']['adresse']; ?></textarea>
					<input type="hidden" name="statut" value="<?php if(isset($_SESSION['membre'])) print $_SESSION['membre']['statut']; ?>"/><br />
			<br /><br />
			<input type="submit" class="submit" name="modification" value="Modification"/>
	</form><br />
	<a href="profil.php">Retour à la page de profil</a>
</div>
