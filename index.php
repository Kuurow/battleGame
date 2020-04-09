<?php

class Personnage {
    private $_force;  //--- Attributs avec $_ 
    private $_localisation;  //--- Attributs avec $_ 
    private $_experience = 50;  //--- Attributs avec $_ 
    private $_degats;  //--- Attributs avec $_ 

    public function parler() {
        print("Je suis un personnage !");
    }

    public function afficherExperience() {
        print($this->_experience);
    }

    public function gagnerExperience($gainExperience) {
        print("<br/>" . ($this->_experience + $gainExperience));
    }

}
$perso = new Personnage();
$perso->afficherExperience();
$perso->gagnerExperience(10);
?>