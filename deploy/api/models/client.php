<?php
class Client{
    public $nom;
    public $prenom;
    public $adresse;
    public $codepostal;
    public $ville;
    public $tel;
    public $mail;
    public $civilite;
    public $login;
    public $password;

    public function setNom($nom){
        $this->nom = $nom;
    }
    public function setPrenom($prenom){
        $this->prenom = $prenom;
    }
    public function setAdresse($adresse){
        $this->adresse = $adresse;
    }
    public function setCodepostal($codepostal){
        $this->codepostal = $codepostal;
    }
    public function setVille($ville){
        $this->ville = $ville;
    }
    public function setTel($tel){
        $this->tel = $tel;
    }
    public function setMail($mail){
        $this->mail = $mail;
    }
    public function setCivilite($civilite){
        $this->civilite = $civilite;
    }
    public function setLogin($login){
        $this->login = $login;
    }
    public function setPassword($password){
        $this->password = $password;
    }
    
}