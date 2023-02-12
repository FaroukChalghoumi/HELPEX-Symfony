<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212190311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_formation (id INT AUTO_INCREMENT NOT NULL, nom_categorie_formation VARCHAR(255) NOT NULL, description_categorie_formation VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE centre (id INT AUTO_INCREMENT NOT NULL, nom_centre VARCHAR(255) NOT NULL, adresse_centre VARCHAR(255) NOT NULL, email_centre VARCHAR(255) NOT NULL, telephone_centre INT NOT NULL, site_web_centre VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, categorie_formation_id INT DEFAULT NULL, centre_id INT NOT NULL, nom_formation VARCHAR(255) NOT NULL, description_formation VARCHAR(255) NOT NULL, cout_formation DOUBLE PRECISION NOT NULL, nombre_de_place INT DEFAULT NULL, INDEX IDX_404021BF2B036EAC (categorie_formation_id), INDEX IDX_404021BF463CD7C3 (centre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscription_formation (id INT AUTO_INCREMENT NOT NULL, formation_id INT NOT NULL, date_inscription_formation DATETIME DEFAULT NULL, INDEX IDX_E655E3A75200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF2B036EAC FOREIGN KEY (categorie_formation_id) REFERENCES categorie_formation (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF463CD7C3 FOREIGN KEY (centre_id) REFERENCES centre (id)');
        $this->addSql('ALTER TABLE inscription_formation ADD CONSTRAINT FK_E655E3A75200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF2B036EAC');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF463CD7C3');
        $this->addSql('ALTER TABLE inscription_formation DROP FOREIGN KEY FK_E655E3A75200282E');
        $this->addSql('DROP TABLE categorie_formation');
        $this->addSql('DROP TABLE centre');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE inscription_formation');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
