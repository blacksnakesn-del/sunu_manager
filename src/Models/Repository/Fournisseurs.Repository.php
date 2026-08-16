<?php

namespace Model\Repository;

use Core\Database;
use Model\Entity\Fournisseur;
use PDO;

class FournisseurRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM fournisseur ORDER BY nom ASC");
        $results = $stmt->fetchAll();

        $fournisseurs = [];
        foreach ($results as $row) {
            $fournisseurs[] = $this->hydrate($row);
        }

        return $fournisseurs;
    }

    public function findById(int $id): ?Fournisseur
    {
        $stmt = $this->db->prepare("SELECT * FROM fournisseur WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    public function save(Fournisseur $fournisseur): bool
    {
        if ($fournisseur->getId() === null) {
            $sql = "INSERT INTO fournisseur (nom, telephone, adresse, email) 
                    VALUES (:nom, :telephone, :adresse, :email)";
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                ':nom' => $fournisseur->getNom(),
                ':telephone' => $fournisseur->getTelephone(),
                ':adresse' => $fournisseur->getAdresse(),
                ':email' => $fournisseur->getEmail()
            ]);

            if ($success) {
                $fournisseur->setId((int) $this->db->lastInsertId());
            }

            return $success;
        }

        $sql = "UPDATE fournisseur 
                SET nom = :nom, telephone = :telephone, adresse = :adresse, email = :email 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $fournisseur->getId(),
            ':nom' => $fournisseur->getNom(),
            ':telephone' => $fournisseur->getTelephone(),
            ':adresse' => $fournisseur->getAdresse(),
            ':email' => $fournisseur->getEmail()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM fournisseur WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    private function hydrate(array $data): Fournisseur
    {
        return new Fournisseur(
            $data['nom'],
            $data['telephone'] ?? null,
            $data['adresse'] ?? null,
            $data['email'] ?? null,
            (int) ($data['id'] ?? 0)
        );
    }
}