<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308201749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE postelikes (id INT AUTO_INCREMENT NOT NULL, poste_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_7B30E659A0905086 (poste_id), INDEX IDX_7B30E659A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE postelikes ADD CONSTRAINT FK_7B30E659A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE postelikes ADD CONSTRAINT FK_7B30E659A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE centre DROP imagecentre');
        $this->addSql('ALTER TABLE commentaire ADD user VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE date_naissance date_naissance DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE postelikes DROP FOREIGN KEY FK_7B30E659A0905086');
        $this->addSql('ALTER TABLE postelikes DROP FOREIGN KEY FK_7B30E659A76ED395');
        $this->addSql('DROP TABLE postelikes');
        $this->addSql('ALTER TABLE centre ADD imagecentre VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire DROP user');
        $this->addSql('ALTER TABLE user CHANGE date_naissance date_naissance DATE DEFAULT NULL');
    }
}
