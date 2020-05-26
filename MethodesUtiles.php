<?php

class MethodesUtiles
{

    public function afficherForm()
    {
        print('<form action="" method="post">' . "\n");
        print('<p class="textForm">' . "\n");
        print('Nom : <input class="inputTextForm" type="text" name="nom" maxlength="50" />' . "\n");
        print('<input class="buttonForm" type="submit" value="Créer ce personnage" name="creer" />' . "\n");
        print('<input class="buttonForm" type="submit" value="Utiliser ce personnage" name="utiliser" />' . "\n");
        print('</p>' . "\n");
        print('</form>' . "\n");
    }

    public function afficherListePersonnages(PersonnagesManager $manager, string $nom = '', bool $frapper = false)
    {
        print('<fieldset class="characterList">' . "\n");

        if ($frapper == false) { // Légende en fonction de la valeur de $frapper
            print('<legend>Liste des joueurs présents :</legend>' . "\n");
        } else {
            print('<legend>Qui voulez-vous frapper ?</legend>' . "\n");
        }

        print('<ul>' . "\n");

        if ($nom == '') {
            $persoListe = $manager->getList(); // On cherche tout les personnages
        } else {
            $persoListe = $manager->getList($nom); // On cherche tout les personnages sauf celui qui porte le nom passé en paramètre
        }

        if ($frapper == false) {
            foreach ($persoListe as $persos) {
                print('<li>' . $persos->getNom() . '</li>' . "\n"); // Liste des personnages
            }
            print('</ul>' . "\n");
            print('</fieldset>' . "\n");
        } 
        else {
            foreach ($persoListe as $persos) {
                print('<li><a href="?frapper=' . $persos->getNom() . '">' . $persos->getNom() . '</a> (' . $persos->getVie() . ' pv)' . "\n"); // Liste des personnages
            }
            print('</ul>' . "\n");
        }

        
    }

    public function afficherInfoPerso(Personnage $perso)
    {
        // Affichage des informations du personnage utilisé
        print('<fieldset class="characterList">' . "\n");
        print('<legend>Vos informations :</legend>' . "\n");
        print('<ul>' . "\n");
        print('<li>Nom : ' . $perso->getNom() . '</li>' . "\n");
        print('<li>Vie : ' . $perso->getVie() . '</li>' . "\n");
        print('</ul>' . "\n");
        print('<p class="center"><a href="?deconnexion=1">Déconnexion </a></p>' . "\n");
        print('</fieldset>' . "\n");
    }

    public function retourFrapper($retour, Personnage $perso, Personnage $persoAFrapper, PersonnagesManager $manager) // affichage et gestion de la frappe en fonction du retour
    {
        switch ($retour) {
            case Personnage::CEST_MOI :
                print('<p class="center greenGlow">Mais... pourquoi voulez-vous vous frapper ???</p>');
                print('</fieldset>' . "\n");
                break;   

            case Personnage::PERSONNAGE_FRAPPE :
                print('<p class="center greenGlow">Le personnage ' . $persoAFrapper->getNom() . ' a bien été frappé !</p>' . "\n");
                print('</fieldset>' . "\n");
                
                $manager->update($perso);
                $manager->update($persoAFrapper);
            
                break;

            case Personnage::PERSONNAGE_TUE :
                print('<p class="center greenGlow">Vous avez tué ' . $persoAFrapper->getNom() . ' !</p>' . "\n");
                print('</fieldset>' . "\n");
                
                $manager->update($perso);
                $manager->delete($persoAFrapper);
            
                break;
        }
    }

    public function debugMode($etat = false)
    {
        if ($etat == false) {
            exit;
        }
        else {
            //------------- ZONE DEBUG ---------------
            if (isset($_POST['nom']) || isset($_POST['utiliser']) || isset($_POST['creer'])) {
                print('Débug POST :<br/>' . "\n");
            }

            // Débug des paramètres POST
            if (isset($_POST['nom'])) {
                print('Nom : ' . $_POST['nom'] . '<br/>' . "\n");
            }
            if (isset($_POST['utiliser'])) {
                print('Param : ' . $_POST['utiliser'] . '<br/>' . "\n");
            }
            if (isset($_POST['creer'])) {
                print('Param : ' . $_POST['creer'] . '<br/>' . "\n");
            }
            // Fin débug des paramètres POST

            // Débug des paramètres POST
            if (isset($_GET['frapper'])) {
                print('Paramètre frapper : ' . $_GET['frapper'] . '<br/>' . "\n");
            }
            // Fin débug des paramètres POST

            // Débug de la SESSION
            if (isset($_SESSION['perso'])) {
                print('Débug SESSION :<br/>' . "\n");
            }

            if (isset($_SESSION['perso'])) {
                print('Nom du joueur : ' . $_SESSION['perso']->getNom() . '<br/>' . "\n");
                print('Vie du joueur : ' . $_SESSION['perso']->getVie() . '<br/>' . "\n");
            }
            // Fin débug de la SESSION
            //---------- FIN ZONE DEBUG --------------
        }
    }

}
