<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230225174424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_formation (id INT AUTO_INCREMENT NOT NULL, nom_categorie_formation VARCHAR(255) NOT NULL, description_categorie_formation VARCHAR(500) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE centre (id INT AUTO_INCREMENT NOT NULL, nom_centre VARCHAR(255) NOT NULL, adresse_centre VARCHAR(255) NOT NULL, email_centre VARCHAR(255) NOT NULL, telephone_centre INT NOT NULL, site_web_centre VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filiere (id INT AUTO_INCREMENT NOT NULL, nom_filiere VARCHAR(255) DEFAULT NULL, description_filiere LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, id_categorie_formation_id INT DEFAULT NULL, id_centre_id INT DEFAULT NULL, nom_formation VARCHAR(255) NOT NULL, description_formation VARCHAR(500) NOT NULL, cout_formation DOUBLE PRECISION NOT NULL, nombre_de_place INT DEFAULT NULL, duree VARCHAR(255) DEFAULT NULL, iamgeformation VARCHAR(255) DEFAULT NULL, INDEX IDX_404021BF2BD2D93B (id_categorie_formation_id), INDEX IDX_404021BFC6096BE4 (id_centre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscription_formation (id INT AUTO_INCREMENT NOT NULL, formations_id INT DEFAULT NULL, user_id INT DEFAULT NULL, date_inscription_formation DATE DEFAULT NULL, status_formation VARCHAR(255) DEFAULT NULL, note INT DEFAULT NULL, INDEX IDX_E655E3A73BF5B0C2 (formations_id), INDEX IDX_E655E3A7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, filiere_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, adresse LONGTEXT NOT NULL, num_tel VARCHAR(255) NOT NULL, pdp VARCHAR(255) DEFAULT NULL, bio LONGTEXT NOT NULL, date_naissance DATE NOT NULL, diplome LONGTEXT DEFAULT NULL, tarif DOUBLE PRECISION DEFAULT NULL, is_enabled TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649180AA129 (filiere_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF2BD2D93B FOREIGN KEY (id_categorie_formation_id) REFERENCES categorie_formation (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFC6096BE4 FOREIGN KEY (id_centre_id) REFERENCES centre (id)');
        $this->addSql('ALTER TABLE inscription_formation ADD CONSTRAINT FK_E655E3A73BF5B0C2 FOREIGN KEY (formations_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE inscription_formation ADD CONSTRAINT FK_E655E3A7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649180AA129 FOREIGN KEY (filiere_id) REFERENCES filiere (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF2BD2D93B');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFC6096BE4');
        $this->addSql('ALTER TABLE inscription_formation DROP FOREIGN KEY FK_E655E3A73BF5B0C2');
        $this->addSql('ALTER TABLE inscription_formation DROP FOREIGN KEY FK_E655E3A7A76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649180AA129');
        $this->addSql('DROP TABLE categorie_formation');
        $this->addSql('DROP TABLE centre');
        $this->addSql('DROP TABLE filiere');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE inscription_formation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
