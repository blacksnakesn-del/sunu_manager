
<?php

namespace Controller;

use Service\VenteService;
use PDO;
use Exception;
use InvalidArgumentException;

class POSController
{
    private PDO $pdo;
    private VenteService $venteService;

    public function __construct(PDO $pdo, VenteService $venteService)
    {
        $this->pdo = $pdo;
        $this->venteService = $venteService;
    }

    public function index(): void
    {
        $stmtClients = $this->pdo->query("SELECT id, nom, prenom, solde_compte, limite_credit FROM clients ORDER BY nom ASC");
        $clients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);

        $stmtArticles = $this->pdo->query("SELECT id, libelle, prix_unitaire, quantite_stock, code_barre FROM articles WHERE quantite_stock > 0 ORDER BY libelle ASC");
        $articles = $stmtArticles->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../../views/pos/index.php';
    }

    public function validerVente(): void
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        $clientId = filter_var($input['client_id'] ?? null, FILTER_VALIDATE_INT);
        $items = $input['items'] ?? [];

        if (!$clientId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Veuillez sélectionner un client valide.']);
            return;
        }

        if (empty($items) || !is_array($items)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Le panier est vide.']);
            return;
        }

        try {
            $venteId = $this->venteService->traiterVente($clientId, $items);

            echo json_encode([
                'success' => true,
                'message' => 'Vente enregistrée avec succès !',
                'vente_id' => $venteId
            ]);
        } catch (InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}