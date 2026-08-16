
<?php

namespace Models\Service;

use PDO;
use PDOException;
use Exception;
use InvalidArgumentException;

class VenteService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function traiterVente(int $clientId, array $items): int
    {
        if (empty($items)) {
            throw new InvalidArgumentException("Le panier ne peut pas être vide.");
        }

        $montantTotal = 0.0;
        foreach ($items as $item) {
            $this->validerStructureItem($item);
            $montantTotal += $item['quantite'] * $item['prix_unitaire'];
        }

        $this->pdo->beginTransaction();

        try {
            $stmtClient = $this->pdo->prepare("
                SELECT id, solde_compte, limite_credit 
                FROM clients 
                WHERE id = :clientId 
                FOR UPDATE
            ");
            $stmtClient->execute(['clientId' => $clientId]);
            $client = $stmtClient->fetch(PDO::FETCH_ASSOC);

            if (!$client) {
                throw new Exception("Client introuvable (ID: {$clientId}).");
            }

            $nouveauSolde = $client['solde_compte'] + $montantTotal;
            if ($client['limite_credit'] !== null && $nouveauSolde > $client['limite_credit']) {
                throw new Exception(
                    sprintf(
                        "Limite de crédit dépassée ! Crédit max: %.2f, Encours actuel: %.2f, Montant vente: %.2f",
                        $client['limite_credit'],
                        $client['solde_compte'],
                        $montantTotal
                    )
                );
            }

            $stmtVente = $this->pdo->prepare("
                INSERT INTO ventes (client_id, montant_total, date_vente) 
                VALUES (:clientId, :montantTotal, NOW())
            ");
            $stmtVente->execute([
                'clientId' => $clientId,
                'montantTotal' => $montantTotal
            ]);
            $venteId = (int) $this->pdo->lastInsertId();

            $stmtCheckStock = $this->pdo->prepare("
                SELECT id, quantite_stock, libelle 
                FROM articles 
                WHERE id = :articleId 
                FOR UPDATE
            ");

            $stmtDecrementStock = $this->pdo->prepare("
                UPDATE articles 
                SET quantite_stock = quantite_stock - :quantite 
                WHERE id = :articleId
            ");

            $stmtLigneVente = $this->pdo->prepare("
                INSERT INTO vente_lignes (vente_id, article_id, quantite, prix_unitaire, sous_total) 
                VALUES (:venteId, :articleId, :quantite, :prixUnitaire, :sousTotal)
            ");

            foreach ($items as $item) {
                $articleId = $item['article_id'];
                $quantite = $item['quantite'];
                $prixUnitaire = $item['prix_unitaire'];

                $stmtCheckStock->execute(['articleId' => $articleId]);
                $article = $stmtCheckStock->fetch(PDO::FETCH_ASSOC);

                if (!$article) {
                    throw new Exception("Article introuvable (ID: {$articleId}).");
                }

                if ($article['quantite_stock'] < $quantite) {
                    throw new Exception(
                        "Stock insuffisant pour l'article '{$article['libelle']}'. En stock: {$article['quantite_stock']}, Demandé: {$quantite}."
                    );
                }

                $stmtDecrementStock->execute([
                    'quantite' => $quantite,
                    'articleId' => $articleId
                ]);

                $stmtLigneVente->execute([
                    'venteId' => $venteId,
                    'articleId' => $articleId,
                    'quantite' => $quantite,
                    'prixUnitaire' => $prixUnitaire,
                    'sousTotal' => $quantite * $prixUnitaire
                ]);
            }

            $stmtUpdateClient = $this->pdo->prepare("
                UPDATE clients 
                SET solde_compte = solde_compte + :montant 
                WHERE id = :clientId
            ");
            $stmtUpdateClient->execute([
                'montant' => $montantTotal,
                'clientId' => $clientId
            ]);

            $this->pdo->commit();

            return $venteId;

        } catch (PDOException | Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    private function validerStructureItem(array $item): void
    {
        if (!isset($item['article_id'], $item['quantite'], $item['prix_unitaire'])) {
            throw new InvalidArgumentException("Chaque article du panier doit contenir article_id, quantite et prix_unitaire.");
        }

        if ($item['quantite'] <= 0) {
            throw new InvalidArgumentException("La quantité doit être supérieure à zéro.");
        }

        if ($item['prix_unitaire'] < 0) {
            throw new InvalidArgumentException("Le prix unitaire ne peut pas être négatif.");
        }
    }
}