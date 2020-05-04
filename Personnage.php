<?php

class Personnage
{
    private $_id;
    private $_force = 20; //--- Attributs avec $_
    private $_experience = 0; //--- Attributs avec $_
    private $_vie;  //--- Attributs concernant un objet ayant pour classe Personnage
    private $_nom = "Inconnu";
    private $_niveau;
    private static $_compteur = 0;  //--- Attribut statique concernant la classe Personnage

    //--- Méthode magique servant à mettre en place un constructeur pour créer un objet personnage faisant parti de la classe Personnage
    public function __construct(array $ligne)
    {
        $this->hydrate($ligne);
        self::$_compteur++;  //--- Incrémentation de l'attribut statique $_compteur concernant la classe Personnage
    }


    public function hydrate (array $ligne) 
    {
        foreach ($ligne as $key => $value) {
            $method = 'set'.ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

    }

    public static function getNombrePerso() {
        return ("Il y a " . self::$_compteur . " personnage(s)\n");
    }

    //--- Méthode dite magique servant à convertir en chaine de caractères les attributs d'un objet personnage
    public function __toString() {
        return $this->getNom() . " a " . $this->getVie() . " point(s) de vie, possède une force de " . $this->getForce() . " point(s) et " . $this->getExperience() . " point(s) d'expérience. <br />\n";
    }

    //--- SETTER & GETTER pour l'attribut _id
    public function setId($id)
    {
        $id = (int) $id;

        if ($id > 0) {
            $this->_id = $id;
        }
    }

    public function getId()
    {
        return $this->_id;
    }

    //--- SETTER & GETTER pour l'attribut _nom
    public function setNom($nom)
    {
        if (is_string($nom)) {
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
            //trigger_error("Veuillez saisir une force avec un format entier.", E_USER_WARNING);
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
        $experience = (int) $experience;

        if ($experience >= 1 && $experience <= 100) {
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
        $vie = (int) $vie;

        if ($vie >= 1 && $vie <= 1000) {
            $this->_vie = $vie;
        }
    }

    public function getVie()
    {
        return $this->_vie;
    }

    //--- SETTER & GETTER pour l'attribut _niveau
    public function setNiveau($niveau)
    {
        $niveau = (int) $niveau;

        if ($niveau >= 1 && $niveau <= 100) {
            $this->_niveau = $niveau;
        }
    }

    public function getNiveau()
    {
        return $this->_niveau;
    }

    //--- Combat entre deux personnages par l'action frapper
    public function frapper(Personnage $ennemi)
    {
        print($this->getNom() . " à frappé " . $ennemi->getNom() . "<br />\n");
        $vieEnnemiAvant = $ennemi->getVie();
        $ennemi->_vie -= $this->_force;
        $this->_experience += 1;
        $this->getExperience();
        print($ennemi->getNom() . " a perdu " . ($vieEnnemiAvant - $ennemi->getVie()) . " point(s) de vie. <br />\n");
        $this->resultFight($ennemi);
    }
    //--- Affichage de la vie d'un personnage
    public function afficherVie()
    {
        print("Vie du personnage " . $this->getNom() . " : " . $this->getVie());
    }
    //--- Affichage des points d'expérience d'un personnage
    public function afficherExperience() {
        print("Le personnage " . $this->_nom . " a " . $this->getExperience() . " point(s) d'expérience. <br/>\n");
    }
    //--- Résultats suite à un combat
    public function resultFight($ennemi)
    {
        print("<b>Résultats du combat :</b><br />");
        print("<i>Date du combat : " . date("l j F Y à H : i") . "</i><br />");
        print($this->getNom() . ": Vie = " . $this->getVie() . " / Force : " . $this->getForce() . " / Expérience : " . $this->getExperience() . " / Niveau :" . $this->getNiveau() . "<br />\n"); //--- Affichage des stats de l'attaquant
        print($ennemi->getNom() . ": Vie = " . $ennemi->getVie() . " / Force : " . $ennemi->getForce() . " / Expérience : " . $ennemi->getExperience() . " / Niveau :" . $ennemi->getNiveau() . "<br />\n"); //--- Affichage des stats de l'ennemi
    }

}