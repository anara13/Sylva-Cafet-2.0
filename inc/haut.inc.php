<!Doctype html>
<html>
    <head>
        <title>Sylva'Cafet</title>
        <link rel="stylesheet" href="inc/css/style.css" />
    </head>
    <body>    
        <header>
			<div class="conteneur">                      
				
				<img src='photo/logo.png' width='100' height='50' /><br/>
                
				<nav>
					<?php

					if(internauteEstConnecteEtEstAdministratif()) // admin
					{ // BackOffice
						echo '<a href="gestion_membre.php">Gestion des membres</a>';
						echo '<a href="gestion_commande.php">Gestion des commandes</a>';
						echo '<a href="gestion_boutique.php">Gestion de la boutique</a><br/>';
					}
					if(internauteEstConnecteEtEstRestaurateur()) // admin
					{ // BackOffice
						echo '<a href="gestion_commande.php">Gestion des commandes</a>';
						echo '<a href="gestion_boutique.php">Gestion de la boutique</a><br/>';
						echo '<a href="boutique.php">Accès à la boutique</a>';
						echo '<a href="panier.php">Voir votre panier</a>';
						echo '<a>Solde : '.$_SESSION['membre']['solde']. '€</a>';//pour afficher le solde restant
					}
					if(internauteEstConnecteEtEstPasAdmin()) // membre 
					{
						echo '<a href="profil.php">Voir votre profil</a>';
						echo '<a href="boutique.php">Accès à la boutique</a>';
						echo '<a href="historique.php">Historique</a>';//pour afficher un historique de commandes 
						echo '<a href="panier.php">Voir votre panier</a>';
						echo '<a>Solde : '.$_SESSION['membre']['solde']. '€</a>';//pour afficher le solde restant
					}
					if(!internauteEstConnecte())
					{
						echo '<a href="inscription.php">Inscription</a>';
						echo '<a href="connexion.php">Connexion</a>';
						echo '<a href="boutique.php">Accès à la boutique</a>';
						echo '<a href="panier.php">Voir votre panier</a><br/>';
					}
					else
					{
						echo '<a href="connexion.php?action=deconnexion">Se déconnecter</a>';
					}

					?>
				</nav>
			</div>
        </header>
        <section>
			<div class="conteneur">     
			<?php 
			if(internauteEstConnecte())
			{
			echo '<p align="center">Vous êtes connecté en tant que : <strong>'.$_SESSION['membre']['pseudo']. '</strong></p><br/>'; 
			} 