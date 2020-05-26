<?php

class PersonnagesManager {

    private $_db;


    public function __construct($db)
    {
        $this->setDb($db);  // Définition de la base de données
    } 


    public function add(Personnage $perso) 
    {
        // Ajout d'un personnage dans la base de données
        $request = $this->_db->prepare('INSERT INTO personnages SET nom = :nom, `force` = :force, vie = :vie, niveau = :niveau, experience = :experience;'); // Préparation de la requête

        $request->bindValue(':nom', $perso->getNom(), PDO::PARAM_STR); // liaison entre valeur entrée nom et la colonne nom de la table  
        $request->bindValue(':force', $perso->getForce(), PDO::PARAM_INT); // liaison entre valeur entrée force et la colonne force de la table  
        $request->bindValue(':vie', $perso->getVie(), PDO::PARAM_INT); // liaison entre valeur entrée vie et la colonne vie de la table
        $request->bindValue(':niveau', $perso->getNiveau(), PDO::PARAM_INT); // liaison entre valeur entrée niveau et la colonne niveau de la table
        $request->bindValue(':experience', $perso->getExperience(), PDO::PARAM_INT); // liaison entre valeur entrée experience et la colonne experience de la table

        $request->execute(); // Execution de la requête INSERT INTO préparée auparavant 
    }


    public function update(Personnage $perso) 
    {
        // Modification (mise à jour) d'un personnage dans la base de données
        $request = $this->_db->prepare('UPDATE personnages SET `force` = :force, vie = :vie, niveau = :niveau, experience = :experience WHERE id = :id;'); // Préparation de la requête

        $request->bindValue(':force', $perso->getForce(), PDO::PARAM_INT); // liaison entre valeur entrée force et la colonne force de la table  
        $request->bindValue(':vie', $perso->getVie(), PDO::PARAM_INT); // liaison entre valeur entrée vie et la colonne vie de la table
        $request->bindValue(':niveau', $perso->getNiveau(), PDO::PARAM_INT); // liaison entre valeur entrée niveau et la colonne niveau de la table
        $request->bindValue(':experience', $perso->getExperience(), PDO::PARAM_INT); // liaison entre valeur entrée experience et la colonne experience de la table
        $request->bindValue(':id', $perso->getId(), PDO::PARAM_INT); // liaison entre valeur entrée id et la colonne id de la table

        $request->execute(); // Execution de la requête UPDATE préparée auparavant
    }


    public function delete(Personnage $perso)
    {
        // Suppression d'un personnage dans la base de données
        $request = $this->_db->exec('DELETE FROM personnages WHERE id = '.$perso->getId() . ";"); // Execution de la requête DELETE
    }


    public function getOne($nom)
    {
        $nom = (string) $nom;

        // $request = $this->_db->query('SELECT id, nom, `force`, vie, niveau, experience FROM personnages WHERE id=' . $id . ';');
        $request = $this->_db->prepare('SELECT id, nom, `force`, vie, niveau, experience FROM personnages WHERE nom= :nom ;');
        $request->bindValue(':nom', $nom, PDO::PARAM_STR);
        $request->execute();

        if ($request->errorCode() > 0) {
            print("Une erreur SQL est intervenue :<br/>");
            print_r($request->errorInfo()[2] . "<br/>");
        }

        $ligne = $request->fetch(PDO::FETCH_ASSOC);

        return new Personnage($ligne);
    }


    public function getList($nom='')
    {
        // Renvoie la liste de tout les personnages présents dans la base de données
        $persos = array(); // Préparation d'un tableau pour stocker les objets Personnages

        if($nom=='') {
            $request = $this->_db->query('SELECT id, nom, `force`, vie, niveau, experience FROM personnages ORDER BY nom;'); // Tout les personnages présents dans la BDD
        }
        else {
            $request = $this->_db->query("SELECT id, nom, `force`, vie, niveau, experience FROM personnages WHERE nom <> '$nom'  ORDER BY nom;"); // Exclusion personnage utilisé
        }
        
        if ($request) {
            while ($ligne = $request->fetch(PDO::FETCH_ASSOC))  // On stocke une ligne de notre table personnages (un personnage) dans la variable $ligne
            {
                $persos[] = new Personnage($ligne); // On créé un objet Personnage ayant pour informations celles de la ligne à laquelle il se trouve que l'on va venir ajouter au tableau $persos
            }
        }
        else {
            print("ERREUR : Requête SQL invalide sur getList<br />");
        }

        return $persos; // On renvoie le tableau des personnages
    }


    public function count() // Compteur du nombre de personnages
    {
        return $this->_db->query('SELECT COUNT(*) FROM personnages')->fetchColumn();
    }


    public function exists($info)
    {
        if (is_int($info)) // On veut voir si tel personnage ayant pour id $info existe
        {
            return (bool) $this->_db->query('SELECT COUNT(*) FROM personnages WHERE id = '.$info)->fetchColumn(); // On renvoie un booléen si le personnage existe ou non
        }
        
        // Sinon, c'est qu'on veut vérifier que le nom existe ou pas.
        
        $q = $this->_db->prepare('SELECT COUNT(*) FROM personnages WHERE nom = :nom');
        $q->execute([':nom' => $info]);
        
        return (bool) $q->fetchColumn(); // Renvoie un booléen 
    }


    public function setDb(PDO $db) {
        $this->_db = $db; // Définition de la base de données dans laquelle on travaille
    }
}