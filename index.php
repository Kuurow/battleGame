<html lang="fr" xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css"/>
    <title>RUEL Marc - BattleGame</title>
    <meta charset="UTF-8"/>
</head>
<body>
    <h1 class="glow">PHP BattleGame</h1>
    <hr/>
    <div class="jeu"> <!-- début div du jeu -->

<?php //-----------------------------------------
function chargerClasse($classe)
{
    require $classe . ".php";
}

spl_autoload_register('chargerClasse');

session_start();

if (isset($_GET['deconnexion'])) {
    session_destroy();
    header('Location: .');
}


//---------------------------------

$dsn = 'mysql:dbname=battleGame;host=localhost';
$user = 'root';
$password = '';

try {
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    if ($db) {

        $personnagesManager = new PersonnagesManager($db); // Création de la liaison Client/Server avec PersonnagesManager

        if (isset($_SESSION['perso'])) // Si l'objet SESSION contient un personnage on le restaure
        {
            $perso = $_SESSION['perso'];

            $infoPerso = new MethodesUtiles();
            $infoPerso->afficherInfoPerso($perso);

            print('<hr/>' . "\n"); // Limitation entre infos perso et liste ennemis

            // Affichage des ennemis
            $ennemyList = new MethodesUtiles();
            $ennemyList->afficherListePersonnages($personnagesManager,$perso->getNom(),true);

            // Si on a cliqué sur un personnage pour le frapper.
            if (isset($_GET['frapper'])) 
            {
                $persoAFrapper = $personnagesManager->getOne($_GET['frapper']);

                $retour = $perso->frapper($persoAFrapper); // On stocke dans $retour les éventuelles erreurs ou messages que renvoie la méthode frapper.

                $etatFrappe = new MethodesUtiles();
                $etatFrappe->retourFrapper($retour, $perso, $persoAFrapper, $personnagesManager);
            }

            print('</ul>' . "\n");
            print('</fieldset>' . "\n");
            // Fin affichage des ennemis
        }
        else
        {
            if (!isset($_POST['utiliser']) && !isset($_POST['nom']) && !isset($_POST['utiliser'])) // Si aucun paramètre POST & SESSION
            {
                $persoList = new MethodesUtiles(); // Création d'un objet MethodesUtiles nommé persoList
                $persoList->afficherListePersonnages($personnagesManager); // On envoie $personnagesManager en tant que paramètre pour dire avec quel manager on travaille

                $formConnexion = new MethodesUtiles(); // Création d'un objet MethodesUtiles nommé formConnexion
                $formConnexion->afficherForm(); // Affichage du formulaire de connexion & création
            }

            if (isset($_POST['utiliser']) && isset($_POST['nom'])) // Si on à voulu utiliser un personnage
            {
                if ($personnagesManager->exists($_POST['nom'])) // Si celui-ci existe.
                {
                    $perso = $personnagesManager->getOne($_POST['nom']);
                    
                    $infoPerso = new MethodesUtiles();
                    $infoPerso->afficherInfoPerso($perso);

                    print('<hr/>' . "\n"); // Limitation entre infos perso et liste ennemis

                    // Affichage des ennemis
                    $ennemyList = new MethodesUtiles();
                    $ennemyList->afficherListePersonnages($personnagesManager,$perso->getNom(),true);

                    // Si on a cliqué sur un personnage pour le frapper.
                    if (isset($_GET['frapper'])) 
                    {
                        $persoAFrapper = $personnagesManager->getOne($_GET['frapper']);

                        $retour = $perso->frapper($persoAFrapper); // On stocke dans $retour les éventuelles erreurs ou messages que renvoie la méthode frapper.

                        $etatFrappe = new MethodesUtiles();
                        $etatFrappe->retourFrapper($retour, $perso, $persoAFrapper, $personnagesManager);
                    }

                    print('</ul>' . "\n");
                    print('</fieldset>' . "\n");
                    // Fin affichage des ennemis

                }
                else // S'il n'existe pas on réaffiche la liste des personnages & le formulaire ainsi qu'un message d'erreur
                { 
                    $persoList = new MethodesUtiles(); // Création d'un objet MethodesUtiles nommé persoList
                    $persoList->afficherListePersonnages($personnagesManager); // On envoie $personnagesManager en tant que paramètre pour dire avec quel manager on travaille

                    $formConnexion = new MethodesUtiles(); // Création d'un objet MethodesUtiles nommé formConnexion
                    $formConnexion->afficherForm(); // Affichage du formulaire de connexion & création

                    print('<p class="message">Le personnage ' . $_POST['nom'] . ' n\'existe pas ! <br/>Veuillez saisir un nom valide ou créer ce dernier.</p>' . "\n");
                }
            }

            if (isset($_POST['creer']) && isset($_POST['nom'])) // Si on à voulu créer un personnage
            {
                $perso = new Personnage(['nom' => $_POST['nom']]); // On crée un nouveau personnage.

                if ($perso->nomValide())
                { 
                    if ($personnagesManager->exists($_POST['nom'])) // Si celui-ci existe on réaffiche la liste des personnages & le formulaire ainsi qu'un message d'erreur.
                    {
                        $persoList = new MethodesUtiles(); // Création d'un objet MethodesUtiles nommé persoList
                        $persoList->afficherListePersonnages($personnagesManager); // On envoie $personnagesManager en tant que paramètre pour dire avec quel manager on travaille

                        $formConnexion = new MethodesUtiles(); // Création d'un objet MethodesUtiles nommé formConnexion
                        $formConnexion->afficherForm(); // Affichage du formulaire de connexion & création

                        print('<p class="message">Le personnage ' . $_POST['nom'] . ' existe déjà ! <br/>Veuillez saisir un autre nom pour créer votre personnage.</p>' . "\n");
                        unset($perso);
                    }
                    else // S'il n'existe pas on va créer le perso et on affiche la liste des adversaires
                    {
                        $personnagesManager->add($perso); // On l'ajoute à la base de données

                        $infoPerso = new MethodesUtiles();
                        $infoPerso->afficherInfoPerso($perso);

                        print('<hr/>' . "\n"); // Limitation entre infos perso et liste ennemis

                        // Affichage des ennemis
                        $ennemyList = new MethodesUtiles();
                        $ennemyList->afficherListePersonnages($personnagesManager,$perso->getNom(),true);

                        // Si on a cliqué sur un personnage pour le frapper.
                        if (isset($_GET['frapper'])) 
                        {
                            $persoAFrapper = $personnagesManager->getOne($_GET['frapper']); // On récupère les informations du personnage frappé

                            $retour = $perso->frapper($persoAFrapper); // On stocke dans $retour les éventuelles erreurs ou messages que renvoie la méthode frapper.

                            $etatFrappe = new MethodesUtiles();
                            $etatFrappe->retourFrapper($retour, $perso, $persoAFrapper, $personnagesManager);
                        }

                        print('</ul>' . "\n");
                        print('</fieldset>' . "\n");
                        // Fin affichage des ennemis
                    }
                }
                else {
                    $persoList = new MethodesUtiles(); // Création d'un objet MethodesUtiles nommé persoList
                    $persoList->afficherListePersonnages($personnagesManager); // On envoie $personnagesManager en tant que paramètre pour dire avec quel manager on travaille

                    $formConnexion = new MethodesUtiles(); // Création d'un objet MethodesUtiles nommé formConnexion
                    $formConnexion->afficherForm(); // Affichage du formulaire de connexion & création

                    print('<p class="message">Veuillez saisir un nom valide pour créer votre personnage.</p>' . "\n");
                    unset($perso);
                }
            }

            if (isset($_GET['debug'])) // Si on veut accéder au débug mode
            {
                $debug = new MethodesUtiles();
                $debug->debugMode(true);
            }
        }


    }
} catch (PDOException $e) {
    print('<br/>Erreur de connexion : ' . $e->getMessage() . "\n");
}

if (isset($perso)) // Si on a créé un personnage, on le stocke dans une variable session afin d'économiser une requête SQL.
{
    $_SESSION['perso'] = $perso;
}
?>
<hr/>
<a class='linkHome' href='index.php'>Accueil</a>

</body>