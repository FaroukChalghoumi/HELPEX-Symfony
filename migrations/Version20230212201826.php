<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212201826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription_formation DROP FOREIGN KEY FK_E655E3A75200282E');
        $this->addSql('DROP TABLE inscription_formation');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inscription_formation (id INT AUTO_INCREMENT NOT NULL, formation_id INT NOT NULL, date_inscription_formation DATETIME DEFAULT NULL, INDEX IDX_E655E3A75200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE inscription_formation ADD CONSTRAINT FK_E655E3A75200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
    }
}
