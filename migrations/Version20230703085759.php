<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703085759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE modele_piece (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, plus_utilise TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devis RENAME INDEX fk_8b27c52b1ecefd72 TO IDX_8B27C52B1ECEFD72');
        $this->addSql('ALTER TABLE devis RENAME INDEX fk_8b27c52b5bbd1224 TO IDX_8B27C52B5BBD1224');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE modele_piece');
        $this->addSql('ALTER TABLE devis RENAME INDEX idx_8b27c52b1ecefd72 TO FK_8B27C52B1ECEFD72');
        $this->addSql('ALTER TABLE devis RENAME INDEX idx_8b27c52b5bbd1224 TO FK_8B27C52B5BBD1224');
    }
}
