<?php

namespace Model\Repository;

use Core\Database;
use Model\Entity\Client;
use PDO;

class ClientRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM client ORDER BY nom ASC, prenom ASC");
        $results = $stmt->fetchAll();

        $clients = [];
        foreach ($results as $row) {
            $clients[] = $this->hydrate($row);
        }

        return $clients;
    }

    public function findById(int $id): ?Client
    {
        $stmt = $this->db->prepare("SELECT * FROM client WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    public function findByTelephone(string $telephone): ?Client
    {
        $stmt = $this->db->prepare("SELECT * FROM client WHERE telephone = :telephone");
        $stmt->execute([':telephone' => $telephone]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    public function save(Client $client): bool
    {
        if ($client->getId() === null) {
            $sql = "INSERT INTO client (prenom, nom, telephone, email, limite_credit) 
                    VALUES (:prenom, :nom, :telephone, :email, :limite_credit)";
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                ':prenom' => $client->getPrenom(),
                ':nom' => $client->getNom(),
                ':telephone' => $client->getTelephone(),
                ':email' => $client->getEmail(),
                ':limite_credit' => $client->getLimitCredit()
            ]);

            if ($success) {
                $client->setId((int) $this->db->lastInsertId());
            }

            return $success;
        }

        $sql = "UPDATE client 
                SET prenom = :prenom, nom = :nom, telephone = :telephone, email = :email, limite_credit = :limite_credit 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $client->getId(),
            ':prenom' => $client->getPrenom(),
            ':nom' => $client->getNom(),
            ':telephone' => $client->getTelephone(),
            ':email' => $client->getEmail(),
            ':limite_credit' => $client->getLimitCredit()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM client WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    private function hydrate(array $data): Client
    {
        return new Client(
            $data['prenom'],
            $data['nom'],
            $data['telephone'] ?? null,
            $data['email'] ?? null,
            (float) ($data['limite_credit'] ?? 0.0),
            (int) ($data['id'] ?? 0)
        );
    }
}