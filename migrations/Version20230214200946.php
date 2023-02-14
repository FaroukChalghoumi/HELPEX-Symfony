<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214200946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorieposte DROP FOREIGN KEY FK_5685F8E7A0905086');
        $this->addSql('DROP INDEX IDX_5685F8E7A0905086 ON categorieposte');
        $this->addSql('ALTER TABLE categorieposte DROP poste_id');
        $this->addSql('ALTER TABLE poste ADD categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE poste ADD CONSTRAINT FK_7C890FABBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorieposte (id)');
        $this->addSql('CREATE INDEX IDX_7C890FABBCF5E72D ON poste (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorieposte ADD poste_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categorieposte ADD CONSTRAINT FK_5685F8E7A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('CREATE INDEX IDX_5685F8E7A0905086 ON categorieposte (poste_id)');
        $this->addSql('ALTER TABLE poste DROP FOREIGN KEY FK_7C890FABBCF5E72D');
        $this->addSql('DROP INDEX IDX_7C890FABBCF5E72D ON poste');
        $this->addSql('ALTER TABLE poste DROP categorie_id');
    }
}
