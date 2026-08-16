<?php

namespace Model\Repository;

use Core\Database;
use Model\Entity\Produit;
use PDO;

class ProduitRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM produit ORDER BY nom ASC");
        $results = $stmt->fetchAll();

        $produits = [];
        foreach ($results as $row) {
            $produits[] = $this->hydrate($row);
        }

        return $produits;
    }

    public function findById(int $id): ?Produit
    {
        $stmt = $this->db->prepare("SELECT * FROM produit WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    public function searchByName(string $keyword): array
    {
        $stmt = $this->db->prepare("SELECT * FROM produit WHERE nom LIKE :keyword ORDER BY nom ASC");
        $stmt->execute([':keyword' => '%' . $keyword . '%']);
        $results = $stmt->fetchAll();

        $produits = [];
        foreach ($results as $row) {
            $produits[] = $this->hydrate($row);
        }

        return $produits;
    }

    public function save(Produit $produit): bool
    {
        if ($produit->getId() === null) {
            $sql = "INSERT INTO produit (nom, prix_unitaire, quantite_stock) 
                    VALUES (:nom, :prix_unitaire, :quantite_stock)";
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                ':nom' => $produit->getNom(),
                ':prix_unitaire' => $produit->getPrixUnitaire(),
                ':quantite_stock' => $produit->getQuantiteStock()
            ]);

            if ($success) {
                $produit->setId((int) $this->db->lastInsertId());
            }

            return $success;
        }

        $sql = "UPDATE produit 
                SET nom = :nom, prix_unitaire = :prix_unitaire, quantite_stock = :quantite_stock 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $produit->getId(),
            ':nom' => $produit->getNom(),
            ':prix_unitaire' => $produit->getPrixUnitaire(),
            ':quantite_stock' => $produit->getQuantiteStock()
        ]);
    }

    public function updateStock(int $produitId, int $quantiteVariation): bool
    {
        $sql = "UPDATE produit SET quantite_stock = quantite_stock + :variation WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':variation' => $quantiteVariation,
            ':id' => $produitId
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM produit WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    private function hydrate(array $data): Produit
    {
        return new Produit(
            $data['nom'],
            (float) $data['prix_unitaire'],
            (int) $data['quantite_stock'],
            (int) ($data['id'] ?? 0)
        );
    }
}