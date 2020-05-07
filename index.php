<?php
date_default_timezone_set('Europe/Paris');

chargerClasse("Personnage");

function chargerClasse($classe)
{
    require $classe . ".php";
}

spl_autoload_register('chargerClasse');

//-------------------------------------------------------------------------------------------------------------------------
print("<h1>PHP BattleGame</h1><hr />");

$dsn = 'mysql:dbname=battlegame;host=localhost';
$user = 'root';
$password = '';

try {
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    if ($db) {
        print('Lecture dans la base de données :<br/>');

        $personnagesManager = new PersonnagesManager($db);

        // $perso = new Personnage(array(
        //     'nom' => "Tatsu",
        //     'force' => 50,
        //     'vie' => 1000,
        //     'niveau' => 1,
        //     'experience' => 0
        // ));

        // $personnagesManager->add($perso);

        $personnages = $personnagesManager->getList();

        foreach ($personnages as $perso) {
            print($perso->getNom() . ' a ' . $perso->getForce() . ' de force, ' . $perso->getVie() . ' de vie, ' . $perso->getExperience() . ' d\'expérience et est au niveau ' . $perso->getNiveau() . "<br />");
        }
    }
} catch (PDOException $e) {
    print('<br/>Erreur de connexion : ' . $e->getMessage());
}
