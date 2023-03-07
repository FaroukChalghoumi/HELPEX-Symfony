<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230304235305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire ADD user VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE postelikes DROP FOREIGN KEY FK_7B30E659A76ED395');
        $this->addSql('ALTER TABLE postelikes DROP FOREIGN KEY FK_7B30E659A0905086');
        $this->addSql('ALTER TABLE postelikes ADD CONSTRAINT FK_7B30E659A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE postelikes ADD CONSTRAINT FK_7B30E659A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP user');
        $this->addSql('ALTER TABLE postelikes DROP FOREIGN KEY FK_7B30E659A0905086');
        $this->addSql('ALTER TABLE postelikes DROP FOREIGN KEY FK_7B30E659A76ED395');
        $this->addSql('ALTER TABLE postelikes ADD CONSTRAINT FK_7B30E659A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE postelikes ADD CONSTRAINT FK_7B30E659A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }
}
