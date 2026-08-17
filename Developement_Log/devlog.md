# 📝 Journal de Développement (DEVLOG)

* **Développeur** : Abdou Kebe
* **Projet** : `sunu_manager` (ERP / PHP POO)
* **Format** : Markdown (`DEVLOG.md`)

---

## 📅 Chronologie des Realisations

### 🔹 [Vendredi - Phase 1] : Conception UML & Architecture
* **Heure de réalisation** : 17h00 - 20h00
* **Réalisations** :
  * Création de la structure de base du projet avec les deux dossiers principaux :
    * `Development_Log/` : Suivi quotidien de l'avancement.
    * `Documents/` : Contient les modélisations (Diagrammes de Use Cases pour les 4 profils utilisateurs et Diagramme de Classes des entités).

---

### 🔹 [Vendredi - Phase 1] : Modélisation BDD & Fallback SQLite
* **Heure de réalisation** : Vendredi 21h00 - Samedi 12h00
* **Réalisations** :
  * Création des scripts SQL de base de données : `schemas.sql` (PostgreSQL) et `schemas_sqlite.sql` (SQLite).
* **Difficultés & Obstacles** :
  * **PostgreSQL** : Difficultés lors de la création des clés étrangères et de l'ajout des contraintes `CHECK`. Résolu avec l'aide de l'IA.
  * **SQLite** : Compréhension initiale du fonctionnement de SQLite complexe. Travail d'explication et d'exemples pratiques avec l'IA pour assimiler les bases.

---

### 🔹 [Samedi - Phase 1] : Singleton Database & Fallback Automatique
* **Heure de réalisation** : Samedi 12h00 - 15h00
* **Réalisations** :
  * Création du dossier `src/Core/` et initialisation du composant de connexion BDD (`Database.php`).
  * Implémentation du mécanisme de **fallback** : si PostgreSQL n'est pas disponible, le bloc `catch` prend le relais et bascule automatiquement sur SQLite.
* **Difficultés & Obstacles** :
  * Gestion propre de la redirection / basculement vers la base SQLite en cas d'erreur de connexion.

---

### 🔹 [Samedi - Phase 2] : Entités POO Pure
* **Heure de réalisation** : Samedi 16h00 - 19h00
* **Réalisations** :
  * Implémentation complète des classes Entités dans `src/Model/Entity/` correspondant aux tables SQL.
* **Difficultés & Obstacles** :
  * Manipulation des entités et résolution d'incohérences de logique métier entre la BDD et le code POO.

---

### 🔹 [Samedi - Phase 2] : Repositories & SQL Sécurisé
* **Heure de réalisation** : Samedi 20h00 - 00h00
* **Réalisations** :
  * Création du dossier `src/Repository/` contenant les classes d'accès aux données :
    * `ClientRepository.php`
    * `FournisseurRepository.php`
    * `ProduitRepository.php`
  * Sécurisation des requêtes SQL à l'aide des requêtes préparées PDO.
* **Difficultés & Obstacles** :
  * Conception et logique métier des fonctions de requêtage dans les Repositories.

---

## 🔍 Partie Autopsie Code & Méthodes

### 1. Pattern Singleton : `Database::getInstance()`
* **Fichier** : `src/Core/Database.php`
* **Rôle** : Garantir une instance unique de la connexion PDO à travers toute l'application.

```php
public static function getInstance()
---

## 🔍 Partie Autopsie Code & Méthodes (Suite)

### 2. Transaction Vente : `POSController::validerVente()`
* **Fichier** : `src/Controllers/POSController.php`
* **Rôle** : Traiter, valider et finaliser une transaction de vente au niveau du point de vente (POS).
* **Explication ligne par ligne & Flux d'exécution** :

```php
// 1. Début de la transaction SQL
$db->beginTransaction();

// 2. Enregistrement de l'en-tête de la vente
$venteId = $this->venteRepository->create($vente);

// 3. Parcours des articles du panier
foreach ($panier as$item) {
    // 4. Contrôle ultime du stock réel disponible
    $produit = $this->produitRepository->find($item['produit_id']);
    if ($produit->getQuantiteStock() <$item['quantite']) {
        throw new Exception("Stock insuffisant pour le produit : " . $produit->getNom());
    }

    // 5. Enregistrement du détail de la ligne de vente
    $this->detailVenteRepository->create($venteId,$item);

    // 6. Mise à jour / Décrémentation du stock
    $this->produitRepository->updateStock($produit->getId(), -$item['quantite']);
}

// 7. Validation définitive de la transaction
$db->commit();