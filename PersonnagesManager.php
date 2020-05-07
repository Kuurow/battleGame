<?php

class PersonnagesManager
{
    private $_db;
    /*
     * Mise en place de la connexion avec la base de données
     */
    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }

    public function __construct($db)
    {
        $this->setDb($db);
    }

    public function add(Personnage $perso)
    {
        $request = $this->_db->prepare('INSERT INTO personnages SET nom = :nom, `force` = :force, vie = :vie, niveau = :niveau, experience = :experience;');

        $request->bindValue(':nom', $perso->getNom(), PDO::PARAM_STR);
        $request->bindValue(':force', $perso->getForce(), PDO::PARAM_INT);
        $request->bindValue(':vie', $perso->getVie(), PDO::PARAM_INT);
        $request->bindValue(':niveau', $perso->getNiveau(), PDO::PARAM_INT);
        $request->bindValue(':experience', $perso->getExperience(), PDO::PARAM_INT);

        $request->execute();
        if ($request->errorCode() > 0) {
            print("Une erreur SQL est intervenue :<br/>");
            print_r($request->errorInfo()[2] . "<br/>");
        }
    }
    /*
     * Supprimer un personnage en se servant de l'id
     */
    public function delete(Personnage $perso)
    {
        $request = $this->_db->prepare('SELECT id, nom, `force`, vie, niveau, experience FROM personnages WHERE id= :id ;');
        $request->bindValue(':id', $this->getId(), PDO::PARAM_STR);
        $request->execute();
    }
    /*
     * Retourne la ligne d'un personnage en se servant de l'id
     */
    public function getOne($id)
    {
        $id = (int) $id;

        // $request = $this->_db->query('SELECT id, nom, `force`, vie, niveau, experience FROM personnages WHERE id=' . $id . ';');
        $request = $this->_db->prepare('SELECT id, nom, `force`, vie, niveau, experience FROM personnages WHERE id= :id ;');
        $request->bindValue(':id', $this->getId(), PDO::PARAM_STR);
        $request->execute();

        if ($request->errorCode() > 0) {
            print("Une erreur SQL est intervenue :<br/>");
            print_r($request->errorInfo()[2] . "<br/>");
        }

        $ligne = $request->fetch(PDO::FETCH_ASSOC);

        return new Personnage($ligne);
    }
    /*
     * Retourne la liste des personnages (objets) par leur id
     */
    public function getList()
    {
        $persos = array();

        $request = $this->_db->query('SELECT id, nom, `force`, vie, niveau, experience FROM personnages ORDER BY nom;');
        if ($request) {
            while ($ligne = $request->FETCH(PDO::FETCH_ASSOC)) {
                $persos[] = new Personnage($ligne);
            }
        } else {
            print("ERREUR : Requête SQL invalide.<br />");
        }

        return $persos;
    }
    /*
     * Mise à jour d'un personnage (objet)
     */
    public function update(Personnage $perso)
    {
        $request = $this->_db->prepare('UPDATE personnages SET  `force` = :force, vie = :vie, niveau = :niveau, experience = :experience WHERE id = :id ;');

        $request->bindValue(':force', $perso->getForce(), PDO::PARAM_INT);
        $request->bindValue(':vie', $perso->getVie(), PDO::PARAM_INT);
        $request->bindValue(':niveau', $perso->getNiveau(), PDO::PARAM_INT);
        $request->bindValue(':experience', $perso->getExperience(), PDO::PARAM_INT);
        $request->bindvalue(':id', $perso->getId(), PDO::PARAM_INT);

        $request->execute();
    }
}
