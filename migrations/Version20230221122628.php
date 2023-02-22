<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221122628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF2BD2D93B');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF2BD2D93B FOREIGN KEY (id_categorie_formation_id) REFERENCES categorie_formation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF2BD2D93B');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF2BD2D93B FOREIGN KEY (id_categorie_formation_id) REFERENCES categorie_formation (id) ON DELETE CASCADE');
    }
}
