<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212220215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, id_categorie_formation_id INT DEFAULT NULL, id_centre_id INT DEFAULT NULL, nom_formation VARCHAR(255) NOT NULL, description_formation VARCHAR(500) NOT NULL, cout_formation DOUBLE PRECISION NOT NULL, nombre_de_place INT DEFAULT NULL, duree VARCHAR(255) DEFAULT NULL, INDEX IDX_404021BF2BD2D93B (id_categorie_formation_id), INDEX IDX_404021BFC6096BE4 (id_centre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF2BD2D93B FOREIGN KEY (id_categorie_formation_id) REFERENCES categorie_formation (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFC6096BE4 FOREIGN KEY (id_centre_id) REFERENCES centre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF2BD2D93B');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFC6096BE4');
        $this->addSql('DROP TABLE formation');
    }
}
