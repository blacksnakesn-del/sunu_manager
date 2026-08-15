
<?php

namespace Model\Entity;

class Client {

    private ?int $id;
    private string $prenom;
    private string $nom;
    private ?string $telephone;
    private ?string $email;
    private float $limitCredit;

    public function __construct(
        ?int $id = null,
        string $prenom = '',
        string $nom = '',
        ?string $telephone = null,
        ?string $email = null,
        float $limitCredit = 0.00
    ) {
        $this->id = $id;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->telephone = $telephone;
        $this->email = $email;
        $this->limitCredit = $limitCredit;
    }

    public function getId() : ?int  { return $this->id; }
    public function setId(?int $id) : self { $this->id = $id; return $this; }

    public function getPrenom() : string { return $this->prenom; }
    public function setPrenom( string $prenom) : self { $this->prenom = $prenom; return $this; }

    public function getNom() : string { return $this->nom; }
    public function setNom(string $nom) : self { $this->nom = $nom; return $this; }

    public function getTelephone() : ?string { return $this->telephone; }
    public function setTelephone(?string $telephone) : self { $this->telephone = $telephone; return $this; }

    public function getEmail() : ?string { return $this->email; }
    public function setEmail(?string $email) : self { $this->email = $email; return $this; }

    public function getLimitCredit() : float { return $this->limitCredit; } 
    public function setLimitCredit(float $limitCredit) : self { $this->limitCredit = $limitCredit; return $this; }
}