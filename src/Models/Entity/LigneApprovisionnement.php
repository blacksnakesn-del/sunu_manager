

<?php

namespace Model\Entity;

class LigneApprovisionnement
{
    private ?Produit $produit;
    private int $quantiteCommandee;
    private int $quantiteLivree;
    private float $coutUnitaire;

    public function __construct(
        ?Produit $produit = null,
        int $quantiteCommandee = 1,
        int $quantiteLivree = 0,
        float $coutUnitaire = 0.0
    ) {
        $this->produit = $produit;
        $this->quantiteCommandee = $quantiteCommandee;
        $this->quantiteLivree = $quantiteLivree;
        $this->coutUnitaire = $coutUnitaire;
    }

    public function getProduit(): ?Produit { return $this->produit; }
    public function setProduit(?Produit $produit): self { $this->produit = $produit; return $this; }

    public function getQuantiteCommandee(): int { return $this->quantiteCommandee; }
    public function setQuantiteCommandee(int $quantiteCommandee): self { $this->quantiteCommandee = $quantiteCommandee; return $this; }

    public function getQuantiteLivree(): int { return $this->quantiteLivree; }
    public function setQuantiteLivree(int $quantiteLivree): self { $this->quantiteLivree = $quantiteLivree; return $this; }

    public function getCoutUnitaire(): float { return $this->coutUnitaire; }
    public function setCoutUnitaire(float $coutUnitaire): self { $this->coutUnitaire = $coutUnitaire; return $this; }

    public function getTotal(): float { return $this->quantiteLivree * $this->coutUnitaire; }
}