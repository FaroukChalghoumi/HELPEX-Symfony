<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214104055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE caisse_organisation (id INT AUTO_INCREMENT NOT NULL, organisation_id INT NOT NULL, montant_caisse_org DOUBLE PRECISION DEFAULT NULL, goal DOUBLE PRECISION NOT NULL, INDEX IDX_5227387D9E6B1585 (organisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation (id INT AUTO_INCREMENT NOT NULL, description_organisation VARCHAR(255) NOT NULL, email_organisation VARCHAR(255) NOT NULL, num_tel_organisation VARCHAR(255) NOT NULL, document_organisation VARCHAR(255) NOT NULL, payment_info VARCHAR(255) NOT NULL, nom_org VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE caisse_organisation ADD CONSTRAINT FK_5227387D9E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE caisse_organisation DROP FOREIGN KEY FK_5227387D9E6B1585');
        $this->addSql('DROP TABLE caisse_organisation');
        $this->addSql('DROP TABLE organisation');
    }
}
