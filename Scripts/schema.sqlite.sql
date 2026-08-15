

PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS produit (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    prix_unitaire REAL NOT NULL CHECK (prix_unitaire >= 0),
    quantite_stock INTEGER NOT NULL DEFAULT 0 CHECK (quantite_stock >= 0)
);

CREATE TABLE IF NOT EXISTS client (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prenom TEXT NOT NULL,
    nom TEXT NOT NULL,
    telephone TEXT,
    email TEXT UNIQUE,
    limite_credit REAL DEFAULT 0.00 CHECK (limite_credit >= 0)
);

CREATE TABLE IF NOT EXISTS fournisseur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    telephone TEXT,
    adresse TEXT,
    email TEXT
);

CREATE TABLE IF NOT EXISTS commande (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    date TEXT NOT NULL DEFAULT (CURRENT_TIMESTAMP),
    montant_total REAL NOT NULL DEFAULT 0.00,
    mode_reglement TEXT NOT NULL,
    montant_verse REAL NOT NULL DEFAULT 0.00,
    statut TEXT NOT NULL DEFAULT 'PAYE',
    client_id INTEGER NOT NULL,
    FOREIGN KEY (client_id) REFERENCES client(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS ligne_commande (
    commande_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,
    quantite INTEGER NOT NULL CHECK (quantite > 0),
    prix_unitaire REAL NOT NULL CHECK (prix_unitaire >= 0),
    sous_total REAL NOT NULL CHECK (sous_total >= 0),
    PRIMARY KEY (commande_id, produit_id),
    FOREIGN KEY (commande_id) REFERENCES commande(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produit(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS dette (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    date_creation TEXT NOT NULL DEFAULT (CURRENT_TIMESTAMP),
    montant_initial REAL NOT NULL CHECK (montant_initial >= 0),
    montant_paye REAL NOT NULL DEFAULT 0.00 CHECK (montant_paye >= 0),
    reste_du REAL NOT NULL CHECK (reste_du >= 0),
    statut TEXT NOT NULL DEFAULT 'EN_COURS',
    commande_id INTEGER UNIQUE,
    client_id INTEGER NOT NULL,
    FOREIGN KEY (commande_id) REFERENCES commande(id) ON DELETE SET NULL,
    FOREIGN KEY (client_id) REFERENCES client(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS paiement (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    date TEXT NOT NULL DEFAULT (CURRENT_TIMESTAMP),
    montant_verse REAL NOT NULL CHECK (montant_verse > 0),
    mode_paiement TEXT NOT NULL,
    dette_id INTEGER NOT NULL,
    FOREIGN KEY (dette_id) REFERENCES dette(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS approvisionnement (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    ref_bl TEXT NOT NULL,
    date TEXT NOT NULL DEFAULT (CURRENT_TIMESTAMP),
    valeur_lot REAL NOT NULL DEFAULT 0.00 CHECK (valeur_lot >= 0),
    statut TEXT NOT NULL DEFAULT 'RECU',
    fournisseur_id INTEGER NOT NULL,
    FOREIGN KEY (fournisseur_id) REFERENCES fournisseur(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS ligne_approvisionnement (
    approvisionnement_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,
    quantite_commandee INTEGER NOT NULL CHECK (quantite_commandee > 0),
    quantite_livree INTEGER NOT NULL DEFAULT 0 CHECK (quantite_livree >= 0),
    cout_unitaire REAL NOT NULL CHECK (cout_unitaire >= 0),
    total REAL NOT NULL CHECK (total >= 0),
    PRIMARY KEY (approvisionnement_id, produit_id),
    FOREIGN KEY (approvisionnement_id) REFERENCES approvisionnement(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produit(id) ON DELETE RESTRICT
);