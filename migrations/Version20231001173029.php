<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231001173029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, particulier_id INT DEFAULT NULL, professionnel_id INT DEFAULT NULL, adresse_chantier_id INT NOT NULL, adresse_facturation_id INT NOT NULL, devis_id INT DEFAULT NULL, num_facture VARCHAR(255) NOT NULL, date_facture DATE DEFAULT NULL, date_paiement_facture DATE DEFAULT NULL, num_dossier VARCHAR(255) DEFAULT NULL, objet VARCHAR(255) DEFAULT NULL, remise DOUBLE PRECISION DEFAULT NULL, prix_ht DOUBLE PRECISION DEFAULT NULL, prix_ttc DOUBLE PRECISION NOT NULL, tvaautoliquidation TINYINT(1) DEFAULT NULL, plusutilise TINYINT(1) NOT NULL, INDEX IDX_FE866410A89E0E67 (particulier_id), INDEX IDX_FE8664108A49CC82 (professionnel_id), INDEX IDX_FE8664101ECEFD72 (adresse_chantier_id), INDEX IDX_FE8664105BBD1224 (adresse_facturation_id), UNIQUE INDEX UNIQ_FE86641041DEFADA (devis_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410A89E0E67 FOREIGN KEY (particulier_id) REFERENCES particulier (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664108A49CC82 FOREIGN KEY (professionnel_id) REFERENCES professionnel (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664101ECEFD72 FOREIGN KEY (adresse_chantier_id) REFERENCES adresse_document (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664105BBD1224 FOREIGN KEY (adresse_facturation_id) REFERENCES adresse_facturation (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641041DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE ligne_facture ADD facture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne_facture ADD CONSTRAINT FK_611F5A297F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('CREATE INDEX IDX_611F5A297F2DEE08 ON ligne_facture (facture_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ligne_facture DROP FOREIGN KEY FK_611F5A297F2DEE08');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410A89E0E67');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664108A49CC82');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664101ECEFD72');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664105BBD1224');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641041DEFADA');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP INDEX IDX_611F5A297F2DEE08 ON ligne_facture');
        $this->addSql('ALTER TABLE ligne_facture DROP facture_id');
    }
}
