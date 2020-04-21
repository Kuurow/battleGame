<?php

class Personnage
{
    private $_force = 20; //--- Attributs avec $_
    private $_localisation; //--- Attributs avec $_
    private $_experience = 0; //--- Attributs avec $_
    private $_vie;
    private $_nom;

    public function __construct($nom, $force = 10, $experience = 0, $vie = 1000)
    {
        $this->setNom($nom);
        $this->setForce($force);
        $this->setExperience($experience);
        $this->setVie($vie);
    }
    //--- SETTER & GETTER pour l'attribut _nom
    public function setNom($nom)
    {
        if (!is_string($nom)) {
            trigger_error("Veuillez saisir le nom du personnage avec un format texte.", E_USER_WARNING);
        } else {
            $this->_nom = $nom;
        }
    }

    public function getNom()
    {
        return $this->_nom;
    }
    //--- SETTER & GETTER pour l'attribut _force
    public function setForce($force)
    {
        if (!is_int($force)) {
            trigger_error("Veuillez saisir une force avec un format entier.", E_USER_WARNING);
        } else {
            $this->_force = $force;
        }
    }

    public function getForce()
    {
        return $this->_force;
    }
    //--- SETTER & GETTER pour l'attribut _experience
    public function setExperience($experience)
    {
        if (!is_int($experience)) {
            trigger_error("Veuillez saisir une experience avec un format entier.", E_USER_WARNING);
        } else {
            $this->_experience = $experience;
        }
    }

    public function getExperience()
    {
        return $this->_experience;
    }
    //--- SETTER & GETTER pour l'attribut _vie
    public function setVie($vie)
    {
        if (!is_int($vie)) {
            trigger_error("Veuillez saisir nombre de points de vie avec un format entier.", E_USER_WARNING);
        } else {
            $this->_vie = $vie;
        }
    }

    public function getVie()
    {
        return $this->_vie;
    }

    //--- Combat entre deux personnages par l'action frapper
    public function frapper(Personnage $ennemi)
    {
        print($this->getNom() . " à frappé " . $ennemi->getNom() . "<br />");
        $vieEnnemiAvant = $ennemi->getVie();
        $ennemi->_vie -= $this->_force;
        $this->_experience += 1;
        $this->getExperience();
        print($ennemi->getNom() . " a perdu " . ($vieEnnemiAvant - $ennemi->getVie()) . " point(s) de vie. <br />");
        $this->resultFight($ennemi);
    }
    //--- Affichage de la vie d'un personnage
    public function afficherVie()
    {
        print("Vie du personnage " . $this->getNom() . " : " . $this->getVie());
    }
    //--- Affichage des points d'expérience d'un personnage
    public function afficherExperience() {
        print("Le personnage " . $this->_nom . " a " . $this->getExperience() . " point(s) d'expérience. <br/>");
    }
    //--- Résultats suite à un combat
    public function resultFight($ennemi)
    {
        print("<b>Résultats du combat :</b><br />");
        print($this->getNom() . ": Vie = " . $this->getVie() . " / Force : " . $this->getForce() . " / Expérience : " . $this->getExperience() . "<br />"); //--- Affichage des stats de l'attaquant
        print($ennemi->getNom() . ": Vie = " . $ennemi->getVie() . " / Force : " . $ennemi->getForce() . " / Expérience : " . $ennemi->getExperience() . "<br />"); //--- Affichage des stats de l'ennemi
    }

}

//-------------------------------------------------------------------------------------------------------------------------
print("<h1>PHP BattleGame</h1><hr />");
$perso1 = new Personnage("Paul");
$perso2 = new Personnage("Pierre");
$perso1->afficherExperience();
$perso2->afficherExperience();
$perso1->frapper($perso2);
