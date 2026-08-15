
<?php

namespace Model\Entity;

use DateTime;

class Paiement
{
    private ?int $id;
    private DateTime $date;
    private float $montantVerse;
    private string $modePaiement;
    private ?Dette $dette;

    public function __construct(
        ?Dette $dette = null,
        float $montantVerse = 0.0,
        string $modePaiement = 'ESPECES',
        ?int $id = null,
        ?DateTime $date = null
    ) {
        $this->id = $id;
        $this->date = $date ?? new DateTime();
        $this->dette = $dette;
        $this->montantVerse = $montantVerse;
        $this->modePaiement = $modePaiement;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }

    public function getDate(): DateTime { return $this->date; }
    public function setDate(DateTime $date): self { $this->date = $date; return $this; }

    public function getMontantVerse(): float { return $this->montantVerse; }
    public function setMontantVerse(float $montantVerse): self { $this->montantVerse = $montantVerse; return $this; }

    public function getModePaiement(): string { return $this->modePaiement; }
    public function setModePaiement(string $modePaiement): self { $this->modePaiement = $modePaiement; return $this; }

    public function getDette(): ?Dette { return $this->dette; }
    public function setDette(?Dette $dette): self { $this->dette = $dette; return $this; }
}