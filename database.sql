-- Créer la base de données
CREATE DATABASE IF NOT EXISTS pharmacie_db;
USE pharmacie_db;

-- Table des utilisateurs (authentification)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('Admin', 'Pharmacien', 'Caissier', 'Livreur') NOT NULL,
    photo VARCHAR(255),
    statut ENUM('actif', 'inactif') DEFAULT 'actif',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des médicaments
CREATE TABLE IF NOT EXISTS medicaments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    code_barre VARCHAR(100) UNIQUE,
    stock INT DEFAULT 0,
    stock_min INT DEFAULT 10,
    emplacement VARCHAR(100), -- frigo, rayon, etc.
    date_expiration DATE,
    prix_vente DECIMAL(10,2),
    prix_achat DECIMAL(10,2),
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Table des produits
CREATE TABLE IF NOT EXISTS produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    quantite INT DEFAULT 0,
    prix DECIMAL(10,2) NOT NULL,
    date_expiration DATE,
    categorie VARCHAR(100),
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Table des ventes
CREATE TABLE IF NOT EXISTS ventes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_caissier INT,
    date_vente DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    FOREIGN KEY (id_caissier) REFERENCES users(id)
);

-- Table des détails des ventes
CREATE TABLE IF NOT EXISTS vente_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_vente INT,
    id_medicament INT,
    quantite INT,
    prix_unitaire DECIMAL(10,2),
    FOREIGN KEY (id_vente) REFERENCES ventes(id),
    FOREIGN KEY (id_medicament) REFERENCES medicaments(id)
);

-- Table des clients
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    telephone VARCHAR(20) UNIQUE,
    email VARCHAR(100),
    programme_fidelite BOOLEAN DEFAULT FALSE
);

-- Table des ordonnances
CREATE TABLE IF NOT EXISTS ordonnances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT,
    fichier VARCHAR(255), -- lien du fichier
    date_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES clients(id)
);

-- Table des fournisseurs
CREATE TABLE IF NOT EXISTS fournisseurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    telephone VARCHAR(20),
    email VARCHAR(100),
    adresse TEXT
);

-- Table des commandes
CREATE TABLE IF NOT EXISTS commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_fournisseur INT,
    date_commande DATE,
    etat ENUM('en attente', 'livrée', 'annulée') DEFAULT 'en attente',
    FOREIGN KEY (id_fournisseur) REFERENCES fournisseurs(id)
);

-- Table des détails des commandes
CREATE TABLE IF NOT EXISTS commande_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_commande INT,
    id_medicament INT,
    quantite INT,
    prix_achat DECIMAL(10,2),
    FOREIGN KEY (id_commande) REFERENCES commandes(id),
    FOREIGN KEY (id_medicament) REFERENCES medicaments(id)
);

-- Table des retours et pertes
CREATE TABLE IF NOT EXISTS retours_pertes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_medicament INT,
    type ENUM('retour_client', 'perte', 'expiration'),
    quantite INT,
    date_enregistrement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    commentaire TEXT,
    FOREIGN KEY (id_medicament) REFERENCES medicaments(id)
);

-- Table des logs d'activité
CREATE TABLE IF NOT EXISTS logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    action TEXT,
    date_action TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id)
);

-- Table des notifications internes
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('alerte_stock', 'expiration', 'autre'),
    message TEXT,
    id_user INT,
    vue BOOLEAN DEFAULT FALSE,
    date_notif TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id)
);

-- Table des gardes
CREATE TABLE IF NOT EXISTS gardes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_garde DATE,
    id_pharmacien INT,
    is_nuit BOOLEAN,
    commentaire TEXT,
    FOREIGN KEY (id_pharmacien) REFERENCES users(id)
);
