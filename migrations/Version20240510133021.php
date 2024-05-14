<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240510133021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture ADD etat_document_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410AE4BD0E0 FOREIGN KEY (etat_document_id) REFERENCES etat_document (id)');
        $this->addSql('CREATE INDEX IDX_FE866410AE4BD0E0 ON facture (etat_document_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410AE4BD0E0');
        $this->addSql('DROP INDEX IDX_FE866410AE4BD0E0 ON facture');
        $this->addSql('ALTER TABLE facture DROP etat_document_id');
    }
}
