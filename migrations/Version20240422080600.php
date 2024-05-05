<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422080600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etat_document (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, abrege VARCHAR(255) NOT NULL, num_ordre INT NOT NULL, type_document VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devis ADD etat_document_id INT NOT NULL');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BAE4BD0E0 FOREIGN KEY (etat_document_id) REFERENCES etat_document (id)');
        $this->addSql('CREATE INDEX IDX_8B27C52BAE4BD0E0 ON devis (etat_document_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BAE4BD0E0');
        $this->addSql('DROP TABLE etat_document');
        $this->addSql('DROP INDEX IDX_8B27C52BAE4BD0E0 ON devis');
        $this->addSql('ALTER TABLE devis DROP etat_document_id');
    }
}
