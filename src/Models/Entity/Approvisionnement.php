

<?php

namespace Model\Entity;

use DateTime;

class Approvisionnement
{
    private ?int $id;
    private string $refBl;
    private DateTime $date;
    private float $valeurLot;
    private string $statut;
    private ?Fournisseur $fournisseur;
    /** @var LigneApprovisionnement[] */
    private array $lignesAppro = [];

    public function __construct(
        string $refBl = '',
        ?Fournisseur $fournisseur = null,
        string $statut = 'EN_ATTENTE',
        ?int $id = null,
        ?DateTime $date = null
    ) {
        $this->id = $id;
        $this->refBl = $refBl;
        $this->date = $date ?? new DateTime();
        $this->fournisseur = $fournisseur;
        $this->statut = $statut;
        $this->valeurLot = 0.0;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }

    public function getRefBl(): string { return $this->refBl; }
    public function setRefBl(string $refBl): self { $this->refBl = $refBl; return $this; }

    public function getDate(): DateTime { return $this->date; }
    public function setDate(DateTime $date): self { $this->date = $date; return $this; }

    public function getValeurLot(): float { return $this->valeurLot; }

    public function getStatut(): string { return $this->statut; }
    public function setStatut(string $statut): self { $this->statut = $statut; return $this; }

    public function getFournisseur(): ?Fournisseur { return $this->fournisseur; }
    public function setFournisseur(?Fournisseur $fournisseur): self { $this->fournisseur = $fournisseur; return $this; }

    public function getLignesAppro(): array { return $this->lignesAppro; }

    public function addLigneAppro(LigneApprovisionnement $ligne): self
    {
        $this->lignesAppro[] = $ligne;
        $this->calculerValeurLot();
        return $this;
    }

    public function calculerValeurLot(): float
    {
        $this->valeurLot = 0.0;
        foreach ($this->lignesAppro as $ligne) {
            $this->valeurLot += $ligne->getTotal();
        }
        return $this->valeurLot;
    }
}