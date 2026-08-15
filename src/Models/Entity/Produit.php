
<?php

namespace Model\Entity;

class Produit {
    private ?int $id;
    private string $nom;
    private float $prixUnitaire;
    private ?int $quantiteStock;

    public function __construct(
        string $nom,
        float $prixUnitaire,
        int $quantiteStock,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prixUnitaire = $prixUnitaire;
        $this->quantiteStock = $quantiteStock;
    }

    public function getId() : ?int { return $this->id; }
    public function setId(?int $id) : self { $this->id = $id; return $this; }

    public function getNom() : string { return $this->nom; }
    public function setNom(string $nom) : self { $this->nom = $nom; return $this; }

    public function getPrixUnitaire() : float { return $this->prixUnitaire; }
    public function setPrixUnitaire(float $prixUnitaire) : self { $this->prixUnitaire = $prixUnitaire; return $this; }

    public function getQuantiteStock() : int { return $this->quantiteStock; }
    public function setQuantiteStock(int $quantiteStock) : self { $this->quantiteStock = $quantiteStock; return $this; }
}