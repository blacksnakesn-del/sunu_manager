

CREATE TABLE produit (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    prix_unitaire NUMERIC(12, 2) NOT NULL CHECK (prix_unitaire >= 0),
    quantite_stock INT NOT NULL DEFAULT 0 CHECK (quantite_stock >= 0)
);

CREATE TABLE client (
    id SERIAL PRIMARY KEY,
    prenom VARCHAR(100) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    email VARCHAR(150) UNIQUE,
    limite_credit NUMERIC(12, 2) DEFAULT 0.00 CHECK (limite_credit >= 0)
);

CREATE TABLE fournisseur (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    telephone VARCHAR(20),
    adresse TEXT,
    email VARCHAR(150)
);

CREATE TABLE commande (
    id SERIAL PRIMARY KEY,
    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    montant_total NUMERIC(12, 2) NOT NULL DEFAULT 0.00,
    mode_reglement VARCHAR(50) NOT NULL,
    montant_verse NUMERIC(12, 2) NOT NULL DEFAULT 0.00,
    statut VARCHAR(50) NOT NULL DEFAULT 'PAYE', 
    client_id INT NOT NULL,
    CONSTRAINT fk_commande_client FOREIGN KEY (client_id) REFERENCES client(id) ON DELETE RESTRICT
);

CREATE TABLE ligne_commande (
    commande_id INT NOT NULL,
    produit_id INT NOT NULL,
    quantite INT NOT NULL CHECK (quantite > 0),
    prix_unitaire NUMERIC(12, 2) NOT NULL CHECK (prix_unitaire >= 0),
    sous_total NUMERIC(12, 2) NOT NULL CHECK (sous_total >= 0),
    PRIMARY KEY (commande_id, produit_id),
    CONSTRAINT fk_ligne_commande FOREIGN KEY (commande_id) REFERENCES commande(id) ON DELETE CASCADE,
    CONSTRAINT fk_ligne_produit FOREIGN KEY (produit_id) REFERENCES produit(id) ON DELETE RESTRICT
);

CREATE TABLE dette (
    id SERIAL PRIMARY KEY,
    date_creation TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    montant_initial NUMERIC(12, 2) NOT NULL CHECK (montant_initial >= 0),
    montant_paye NUMERIC(12, 2) NOT NULL DEFAULT 0.00 CHECK (montant_paye >= 0),
    reste_du NUMERIC(12, 2) NOT NULL CHECK (reste_du >= 0),
    statut VARCHAR(50) NOT NULL DEFAULT 'EN_COURS',
    commande_id INT UNIQUE,
    client_id INT NOT NULL,
    CONSTRAINT fk_dette_commande FOREIGN KEY (commande_id) REFERENCES commande(id) ON DELETE SET NULL,
    CONSTRAINT fk_dette_client FOREIGN KEY (client_id) REFERENCES client(id) ON DELETE RESTRICT
);

CREATE TABLE paiement (
    id SERIAL PRIMARY KEY,
    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    montant_verse NUMERIC(12, 2) NOT NULL CHECK (montant_verse > 0),
    mode_paiement VARCHAR(50) NOT NULL,
    dette_id INT NOT NULL,
    CONSTRAINT fk_paiement_dette FOREIGN KEY (dette_id) REFERENCES dette(id) ON DELETE CASCADE
);

CREATE TABLE approvisionnement (
    id SERIAL PRIMARY KEY,
    ref_bl VARCHAR(100) NOT NULL,
    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    valeur_lot NUMERIC(12, 2) NOT NULL DEFAULT 0.00 CHECK (valeur_lot >= 0),
    statut VARCHAR(50) NOT NULL DEFAULT 'RECU', 
    fournisseur_id INT NOT NULL,
    CONSTRAINT fk_appro_fournisseur FOREIGN KEY (fournisseur_id) REFERENCES fournisseur(id) ON DELETE RESTRICT
);

CREATE TABLE ligne_approvisionnement (
    approvisionnement_id INT NOT NULL,
    produit_id INT NOT NULL,
    quantite_commandee INT NOT NULL CHECK (quantite_commandee > 0),
    quantite_livree INT NOT NULL DEFAULT 0 CHECK (quantite_livree >= 0),
    cout_unitaire NUMERIC(12, 2) NOT NULL CHECK (cout_unitaire >= 0),
    total NUMERIC(12, 2) NOT NULL CHECK (total >= 0),
    PRIMARY KEY (approvisionnement_id, produit_id),
    CONSTRAINT fk_ligne_appro FOREIGN KEY (approvisionnement_id) REFERENCES approvisionnement(id) ON DELETE CASCADE,
    CONSTRAINT fk_ligne_appro_produit FOREIGN KEY (produit_id) REFERENCES produit(id) ON DELETE RESTRICT
);