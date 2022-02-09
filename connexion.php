<?php
require_once("inc/init.inc.php");
//--------------------------------- TRAITEMENTS PHP ---------------------------------//
if(isset($_GET['action']) && $_GET['action'] == "deconnexion") 
{
	session_destroy(); 
}
if(internauteEstConnecte()) 
{
	header("location:profil.php");
}


if($_POST)
{
    $resultat = executeRequete("SELECT * FROM membre WHERE pseudo='$_POST[pseudo]'");
    $verification = executeRequete("SELECT verification FROM membre WHERE pseudo='$_POST[pseudo]'");

    if($resultat->num_rows != 0)
    {
        $membre = $resultat->fetch_assoc();
        if($membre['mdp'] == $_POST['mdp'])
        {
            /*if($verification = 1)//pour verifier si l'utilisateur est vérifié
            {*/
            
                foreach($membre as $indice => $element)
                {
                    if($indice != 'mdp')
                    {
                    $_SESSION['membre'][$indice] = $element; 
                    }
                }
                header("location:profil.php"); 
            /*}
            else
            {
                echo'<div class="erreur">Votre compte n\'a pas encore été vérifié, veuillez réessayer prochainement</div>'; 
            }*/
        }
        else
        {
            $contenu .= '<div class="erreur">Erreur de MDP</div>';
        }       
    }
    else
    {
        $contenu .= '<div class="erreur">Erreur de pseudo</div>';
    }
}
//--------------------------------- AFFICHAGE HTML ---------------------------------//
?>
<?php require_once("inc/haut.inc.php"); ?>
<?php echo $contenu; ?>
 
<form method="post" action="">
    <label for="pseudo">Pseudo</label><br />
    <input type="text" id="pseudo" name="pseudo" /><br /> <br />
         
    <label for="mdp">Mot de passe</label><br />
    <input type="password" id="mdp" name="mdp" maxlength="32" /><br /><br />
 
     <input type="submit" value="Se connecter"/>
</form>
 
<?php require_once("inc/bas.inc.php"); ?>