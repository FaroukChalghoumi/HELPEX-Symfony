<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230225183441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE poste ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE poste ADD CONSTRAINT FK_7C890FABA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_7C890FABA76ED395 ON poste (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE poste DROP FOREIGN KEY FK_7C890FABA76ED395');
        $this->addSql('DROP INDEX IDX_7C890FABA76ED395 ON poste');
        $this->addSql('ALTER TABLE poste DROP user_id');
    }
}
