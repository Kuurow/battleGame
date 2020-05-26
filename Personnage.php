<?php

class Personnage
{
    private $_id; // Id du personnage
    private $_nom; // Nom du personnage
    private $_force = 50; // Force du personnage
    private $_experience = 0; // Points d'expérience du personnage
    private $_vie = 1000; // Vie du personnage
    private $_niveau = 1; // Niveau du personnage


    const CEST_MOI = 1; // Constante renvoyée par la méthode `frapper` si on se frappe soi-même
    const PERSONNAGE_TUE = 2; // Constante renvoyée par la méthode `frapper` si on a tué le personnage en le frappant
    const PERSONNAGE_FRAPPE = 3; // Constante renvoyée par la méthode `frapper` si on a bien frappé le personnage


    public function __construct(array $ligne)
    {
        $this->hydrate($ligne);
    }


    public function hydrate(array $ligne)
    {
        foreach ($ligne as $key => $value) // Pour chaque ligne on récupère le nom des attributs ($key) qui ont une valeur ($value)
        {
            // On récupère le nom du setter correspondant à l'attribut.
            $method = 'set' . ucfirst($key);

            // Si le setter correspondant existe.
            if (method_exists($this, $method)) {
                // On appelle le setter.
                $this->$method($value);
            } else {
                print('<br/>La méthode <b>' . $method . '</b> n\'existe pas !' . "\n");
            }
        }
    }

    // public static function getNombrePersos()
    // {
    //     switch(self::$_compteur)
    //     {
    //         case 0:  // Dans le cas pour self::$_compteur = 0, soit aucun personnage
    //             print('Il n\'y a aucun personnage !');
    //             break;

    //         case 1:  // Dans le cas pour self::$_compteur = 1
    //             print('Il y a ' . self::$_compteur . ' personnage.' . "<br>\n");
    //             break;

    //         default: // Dans tout les autres cas
    //             print('Il y a ' . self::$_compteur . ' personnages.' . "<br>\n");
    //             break;
    //     }
    // }

    /** =======================================
     *  ------------ Début SETTERS ------------
     */
    public function setId($id) // attribut _id
    {
        $id = (int) $id;

        if ($id > 0) {
            $this->_id = $id;
        }
    }

    public function setNom($nom) // attribut _nom
    {
        // On vérifie qu'il s'agit bien d'une chaîne de caractères.
        if (is_string($nom)) {
            $this->_nom = $nom;
        }
    }

    public function setForce($force) // attribut _force
    {
        if (!is_int($force)) {
            trigger_error("Veuillez saisir une force avec un format entier.", E_USER_WARNING);
        } else {
            $this->_force = $force;
        }
    }

    public function setExperience($experience) // attribut _experience
    {
        $experience = (int) $experience;

        if ($experience >= 1 && $experience <= 100) {
            $this->_experience = $experience;
        }
    }

    public function setVie($vie) // attribut _vie
    {
        $vie = (int) $vie;

        if ($vie >= 1 && $vie <= 1000) {
            $this->_vie = $vie;
        }
    }

    public function setNiveau($niveau) // attribut _niveau
    {
        $niveau = (int) $niveau;

        if ($niveau >= 1 && $niveau <= 100) {
            $this->_niveau = $niveau;
        }
    }
    /** =======================================
     *  ------------- Fin SETTERS -------------
     *      ===============================
     *  ------------ Début GETTERS ------------
     *  =======================================
     */
    public function getId() // attribut _id
    {
        return $this->_id;
    }

    public function getNom() // attribut _nom
    {
        return $this->_nom;
    }

    public function getForce() // attribut _force
    {
        return $this->_force;
    }

    public function getExperience() // attribut _experience
    {
        return $this->_experience;
    }

    public function getVie() // attribut _vie
    {
        return $this->_vie;
    }

    public function getNiveau() // attribut _niveau
    {
        return $this->_niveau;
    }
    /**
     *  ------------- Fin GETTERS -------------
     *  =======================================
     */
    public function parler()
    {
        print('Je suis un personnage !' . "<br/>\n");
    }

    //--- Combat entre deux personnages par l'action frapper
    public function frapper(Personnage $ennemi)
    {
        if ($ennemi->getId() == $this->_id) {
            return self::CEST_MOI;
        }

        // On indique au personnage qu'il doit recevoir des dégâts.
        // Puis on retourne la valeur renvoyée par la méthode : self::PERSONNAGE_TUE ou self::PERSONNAGE_FRAPPE
        return $ennemi->recevoirDegats();
    }


    public function recevoirDegats()
    {
        $this->_vie -= 50;

        // Si vie personnage <= 0 il est tué.
        if ($this->_vie <= 0) {
            return self::PERSONNAGE_TUE;
        }

        // Sinon, on se contente de dire que le personnage a bien été frappé.
        return self::PERSONNAGE_FRAPPE;
    }

    public function nomValide() // Vérification si le nom est valide (si vide ou non)
    {
        return !empty($this->_nom);
    }

}
