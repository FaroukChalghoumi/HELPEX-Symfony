<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308152409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accompagnement (id INT AUTO_INCREMENT NOT NULL, task_id INT NOT NULL, user_id INT NOT NULL, user_pro_id INT NOT NULL, is_accepted TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_2130A05B8DB60186 (task_id), INDEX IDX_2130A05BA76ED395 (user_id), INDEX IDX_2130A05BA6E5FF87 (user_pro_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE caisse_organisation (id INT AUTO_INCREMENT NOT NULL, organisation_id INT NOT NULL, montant_caisse_org DOUBLE PRECISION DEFAULT NULL, goal DOUBLE PRECISION NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_5227387D9E6B1585 (organisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_formation (id INT AUTO_INCREMENT NOT NULL, nom_categorie_formation VARCHAR(255) NOT NULL, description_categorie_formation VARCHAR(500) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_produit (id INT AUTO_INCREMENT NOT NULL, nom_cat_produit VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorieposte (id INT AUTO_INCREMENT NOT NULL, topic VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE centre (id INT AUTO_INCREMENT NOT NULL, nom_centre VARCHAR(255) NOT NULL, adresse_centre VARCHAR(255) NOT NULL, email_centre VARCHAR(255) NOT NULL, telephone_centre INT NOT NULL, site_web_centre VARCHAR(255) DEFAULT NULL, imagecentre VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, poste_id INT DEFAULT NULL, date DATE NOT NULL, description VARCHAR(255) NOT NULL, compteurvote INT NOT NULL, INDEX IDX_67F068BCA0905086 (poste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filiere (id INT AUTO_INCREMENT NOT NULL, nom_filiere VARCHAR(255) DEFAULT NULL, description_filiere LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, id_categorie_formation_id INT DEFAULT NULL, id_centre_id INT DEFAULT NULL, nom_formation VARCHAR(255) NOT NULL, description_formation VARCHAR(500) NOT NULL, cout_formation DOUBLE PRECISION NOT NULL, nombre_de_place INT DEFAULT NULL, duree VARCHAR(255) DEFAULT NULL, iamgeformation VARCHAR(255) DEFAULT NULL, INDEX IDX_404021BF2BD2D93B (id_categorie_formation_id), INDEX IDX_404021BFC6096BE4 (id_centre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscription_formation (id INT AUTO_INCREMENT NOT NULL, formations_id INT DEFAULT NULL, user_id INT DEFAULT NULL, date_inscription_formation DATE DEFAULT NULL, status_formation VARCHAR(255) DEFAULT NULL, note INT DEFAULT NULL, autorisation TINYINT(1) DEFAULT NULL, INDEX IDX_E655E3A73BF5B0C2 (formations_id), INDEX IDX_E655E3A7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, tasks_id INT DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, time TIME NOT NULL, is_complete TINYINT(1) NOT NULL, photo VARCHAR(255) DEFAULT NULL, INDEX IDX_1F1B251EE3272D31 (tasks_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation (id INT AUTO_INCREMENT NOT NULL, description_organisation VARCHAR(255) NOT NULL, email_organisation VARCHAR(255) NOT NULL, num_tel_organisation VARCHAR(255) NOT NULL, document_organisation VARCHAR(255) NOT NULL, payment_info VARCHAR(255) NOT NULL, nom_org VARCHAR(255) NOT NULL, logo_org VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poste (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, user_id INT DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, date DATE NOT NULL, description VARCHAR(255) DEFAULT NULL, multimedia VARCHAR(255) DEFAULT NULL, compteurvote INT NOT NULL, INDEX IDX_7C890FABBCF5E72D (categorie_id), INDEX IDX_7C890FABA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits (id INT AUTO_INCREMENT NOT NULL, categorie_produit_id INT DEFAULT NULL, user_id INT DEFAULT NULL, nom_produit VARCHAR(255) NOT NULL, etat_produit VARCHAR(255) NOT NULL, prix_produit VARCHAR(255) NOT NULL, description_produit VARCHAR(255) DEFAULT NULL, image_path VARCHAR(255) DEFAULT NULL, status_produit VARCHAR(255) DEFAULT NULL, localisation_produit VARCHAR(255) DEFAULT NULL, brand VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, details VARCHAR(255) DEFAULT NULL, authorisation TINYINT(1) DEFAULT NULL, INDEX IDX_BE2DDF8C91FDB457 (categorie_produit_id), INDEX IDX_BE2DDF8CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tasks (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) DEFAULT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, is_valid TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, filiere_id INT DEFAULT NULL, accompagnement_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, adresse LONGTEXT NOT NULL, num_tel VARCHAR(255) NOT NULL, pdp VARCHAR(255) DEFAULT NULL, bio LONGTEXT NOT NULL, date_naissance DATE NOT NULL, diplome LONGTEXT DEFAULT NULL, tarif DOUBLE PRECISION DEFAULT NULL, is_enabled TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649180AA129 (filiere_id), INDEX IDX_8D93D6498E768805 (accompagnement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accompagnement ADD CONSTRAINT FK_2130A05B8DB60186 FOREIGN KEY (task_id) REFERENCES tasks (id)');
        $this->addSql('ALTER TABLE accompagnement ADD CONSTRAINT FK_2130A05BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE accompagnement ADD CONSTRAINT FK_2130A05BA6E5FF87 FOREIGN KEY (user_pro_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE caisse_organisation ADD CONSTRAINT FK_5227387D9E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF2BD2D93B FOREIGN KEY (id_categorie_formation_id) REFERENCES categorie_formation (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFC6096BE4 FOREIGN KEY (id_centre_id) REFERENCES centre (id)');
        $this->addSql('ALTER TABLE inscription_formation ADD CONSTRAINT FK_E655E3A73BF5B0C2 FOREIGN KEY (formations_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE inscription_formation ADD CONSTRAINT FK_E655E3A7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EE3272D31 FOREIGN KEY (tasks_id) REFERENCES tasks (id)');
        $this->addSql('ALTER TABLE poste ADD CONSTRAINT FK_7C890FABBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorieposte (id)');
        $this->addSql('ALTER TABLE poste ADD CONSTRAINT FK_7C890FABA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8C91FDB457 FOREIGN KEY (categorie_produit_id) REFERENCES categorie_produit (id)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649180AA129 FOREIGN KEY (filiere_id) REFERENCES filiere (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498E768805 FOREIGN KEY (accompagnement_id) REFERENCES accompagnement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accompagnement DROP FOREIGN KEY FK_2130A05B8DB60186');
        $this->addSql('ALTER TABLE accompagnement DROP FOREIGN KEY FK_2130A05BA76ED395');
        $this->addSql('ALTER TABLE accompagnement DROP FOREIGN KEY FK_2130A05BA6E5FF87');
        $this->addSql('ALTER TABLE caisse_organisation DROP FOREIGN KEY FK_5227387D9E6B1585');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA0905086');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF2BD2D93B');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFC6096BE4');
        $this->addSql('ALTER TABLE inscription_formation DROP FOREIGN KEY FK_E655E3A73BF5B0C2');
        $this->addSql('ALTER TABLE inscription_formation DROP FOREIGN KEY FK_E655E3A7A76ED395');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EE3272D31');
        $this->addSql('ALTER TABLE poste DROP FOREIGN KEY FK_7C890FABBCF5E72D');
        $this->addSql('ALTER TABLE poste DROP FOREIGN KEY FK_7C890FABA76ED395');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8C91FDB457');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CA76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649180AA129');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498E768805');
        $this->addSql('DROP TABLE accompagnement');
        $this->addSql('DROP TABLE caisse_organisation');
        $this->addSql('DROP TABLE categorie_formation');
        $this->addSql('DROP TABLE categorie_produit');
        $this->addSql('DROP TABLE categorieposte');
        $this->addSql('DROP TABLE centre');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE filiere');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE inscription_formation');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE organisation');
        $this->addSql('DROP TABLE poste');
        $this->addSql('DROP TABLE produits');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
