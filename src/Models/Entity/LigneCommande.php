<?php

namespace Model\Entity;

class LigneCommande
{
    private ?Produit $produit;
    private int $quantite;
    private float $prixUnitaire;

    public function __construct(?Produit $produit = null, int $quantite = 1, float $prixUnitaire = 0.0)
    {
        $this->produit = $produit;
        $this->quantite = $quantite;
        $this->prixUnitaire = $prixUnitaire > 0 ? $prixUnitaire : ($produit ? $produit->getPrixUnitaire() : 0.0);
    }

    public function getProduit(): ?Produit { return $this->produit; }
    public function setProduit(?Produit $produit): self { $this->produit = $produit; return $this; }

    public function getQuantite(): int { return $this->quantite; }
    public function setQuantite(int $quantite): self { $this->quantite = $quantite; return $this; }

    public function getPrixUnitaire(): float { return $this->prixUnitaire; }
    public function setPrixUnitaire(float $prixUnitaire): self { $this->prixUnitaire = $prixUnitaire; return $this; }

    public function getSousTotal(): float { return $this->quantite * $this->prixUnitaire; }
}
