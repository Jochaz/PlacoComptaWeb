<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240806131728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_dupliquee (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, devis_id INT DEFAULT NULL, facture_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_DFE39B4BCF5E72D (categorie_id), INDEX IDX_DFE39B441DEFADA (devis_id), INDEX IDX_DFE39B47F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie_dupliquee ADD CONSTRAINT FK_DFE39B4BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_materiaux (id)');
        $this->addSql('ALTER TABLE categorie_dupliquee ADD CONSTRAINT FK_DFE39B441DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE categorie_dupliquee ADD CONSTRAINT FK_DFE39B47F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie_dupliquee DROP FOREIGN KEY FK_DFE39B4BCF5E72D');
        $this->addSql('ALTER TABLE categorie_dupliquee DROP FOREIGN KEY FK_DFE39B441DEFADA');
        $this->addSql('ALTER TABLE categorie_dupliquee DROP FOREIGN KEY FK_DFE39B47F2DEE08');
        $this->addSql('DROP TABLE categorie_dupliquee');
    }
}
