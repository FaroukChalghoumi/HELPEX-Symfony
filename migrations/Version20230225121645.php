<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230225121645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription_formation ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE inscription_formation ADD CONSTRAINT FK_E655E3A7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E655E3A7A76ED395 ON inscription_formation (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription_formation DROP FOREIGN KEY FK_E655E3A7A76ED395');
        $this->addSql('DROP INDEX IDX_E655E3A7A76ED395 ON inscription_formation');
        $this->addSql('ALTER TABLE inscription_formation DROP user_id');
    }
}
