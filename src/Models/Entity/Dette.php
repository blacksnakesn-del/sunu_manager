<?php

namespace Model\Entity;

use DateTime;

class Dette
{
    private ?int $id;
    private DateTime $dateCreation;
    private float $montantInitial;
    private float $montantPaye;
    private float $resteDu;
    private string $statut;
    private ?Client $client;
    private ?Commande $commande;

    public function __construct(
        ?Client $client = null,
        ?Commande $commande = null,
        float $montantInitial = 0.0,
        float $montantPaye = 0.0,
        string $statut = 'EN_COURS',
        ?int $id = null,
        ?DateTime $dateCreation = null
    ) {
        $this->id = $id;
        $this->dateCreation = $dateCreation ?? new DateTime();
        $this->client = $client;
        $this->commande = $commande;
        $this->montantInitial = $montantInitial;
        $this->montantPaye = $montantPaye;
        $this->statut = $statut;
        $this->updateResteDu();
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }

    public function getDateCreation(): DateTime { return $this->dateCreation; }
    public function setDateCreation(DateTime $dateCreation): self { $this->dateCreation = $dateCreation; return $this; }

    public function getMontantInitial(): float { return $this->montantInitial; }
    public function setMontantInitial(float $montantInitial): self { $this->montantInitial = $montantInitial; $this->updateResteDu(); return $this; }

    public function getMontantPaye(): float { return $this->montantPaye; }
    public function setMontantPaye(float $montantPaye): self { $this->montantPaye = $montantPaye; $this->updateResteDu(); return $this; }

    public function getResteDu(): float { return $this->resteDu; }

    public function getStatut(): string { return $this->statut; }
    public function setStatut(string $statut): self { $this->statut = $statut; return $this; }

    public function getClient(): ?Client { return $this->client; }
    public function setClient(?Client $client): self { $this->client = $client; return $this; }

    public function getCommande(): ?Commande { return $this->commande; }
    public function setCommande(?Commande $commande): self { $this->commande = $commande; return $this; }

    private function updateResteDu(): void
    {
        $this->resteDu = max(0.0, $this->montantInitial - $this->montantPaye);
        if ($this->resteDu === 0.0 && $this->montantInitial > 0) {
            $this->statut = 'SOLDEE';
        }
    }
}