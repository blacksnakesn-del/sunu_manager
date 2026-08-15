
<?php

namespace Model\Entity;

use DateTime;

class Commande
{
    private ?int $id;
    private DateTime $date;
    private float $montantTotal;
    private string $modeReglement;
    private float $montantVerse;
    private string $statut;
    private ?Client $client;
    private array $lignesCommande = [];

    public function __construct(
        ?Client $client = null,
        string $modeReglement = 'COMPTANT',
        float $montantVerse = 0.0,
        string $statut = 'PAYE',
        ?int $id = null,
        ?DateTime $date = null
    ) {
        $this->id = $id;
        $this->date = $date ?? new DateTime();
        $this->client = $client;
        $this->modeReglement = $modeReglement;
        $this->montantVerse = $montantVerse;
        $this->statut = $statut;
        $this->montantTotal = 0.0;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }

    public function getDate(): DateTime { return $this->date; }
    public function setDate(DateTime $date): self { $this->date = $date; return $this; }

    public function getMontantTotal(): float { return $this->montantTotal; }
    public function setMontantTotal(float $montantTotal): self { $this->montantTotal = $montantTotal; return $this; }

    public function getModeReglement(): string { return $this->modeReglement; }
    public function setModeReglement(string $modeReglement): self { $this->modeReglement = $modeReglement; return $this; }

    public function getMontantVerse(): float { return $this->montantVerse; }
    public function setMontantVerse(float $montantVerse): self { $this->montantVerse = $montantVerse; return $this; }

    public function getStatut(): string { return $this->statut; }
    public function setStatut(string $statut): self { $this->statut = $statut; return $this; }

    public function getClient(): ?Client { return $this->client; }
    public function setClient(?Client $client): self { $this->client = $client; return $this; }

    public function getLignesCommande(): array { return $this->lignesCommande; }
    
    public function addLigneCommande(LigneCommande $ligne): self
    {
        $this->lignesCommande[] = $ligne;
        $this->calculerMontantTotal();
        return $this;
    }

    public function calculerMontantTotal(): float
    {
        $this->montantTotal = 0.0;
        foreach ($this->lignesCommande as $ligne) {
            $this->montantTotal += $ligne->getSousTotal();
        }
        return $this->montantTotal;
    }
}
}